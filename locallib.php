<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * A tool to validate passwords against particular password policies.
 *
 * @package   tool_leakcheck
 * @copyright 2025 Jordan Tomkinson <jordan.tomkinson@openlms.net>
 * @copyright 2019 Peter Burnett <peterburnett@catalyst-au.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Validates the password provided against the password policy configured in the plugin admin
 * settings menu. Calls all of the individual checks
 *
 * @param string $password The password to be validated.
 * @param object $user An optional user object
 * @return string Returns a string of any errors presented by the checks, or an empty string for success.
 *
 */
function tool_leakcheck_password_validate($password, $user) {
    $errs = '';
    // Check against HaveIBeenPwned.com password breach API.
    if (get_config('tool_leakcheck', 'password_blacklist')) {
        $leaked = tool_leakcheck_password_blacklist($password);
        $errs .= $leaked;

        if (!empty($leaked) && get_config('tool_leakcheck', 'lockout_on_leak')) {
            $stack = debug_backtrace();
            $run = false;
            foreach ($stack as $level => $data) {
                if ($data['function'] === 'authenticate_user_login' &&
                        stripos($data['file'], '/login/index.php') !== false) {
                    if ($stack[$level - 1]['function'] === 'check_password_policy') {
                        // We got here from checking policy after auth success.
                        $run = true;
                        break;
                    }
                }
            }

            if (!empty($user->id) && !isguestuser($user) && $run) {
                global $DB;
                // Set the password to empty, so it cannot be used again, locking the user out.
                $user->password = '';
                // Update the user record.
                $DB->update_record('user', $user);
                // Destroy all sessions for this user.
                \core\session\manager::destroy_user_sessions($user->id);
                // Redirect to the forgot password page with an error message.
                $forgoturl = new \moodle_url('/login/forgot_password.php');
                redirect($forgoturl, get_string('responsebreachedpasswordlogout', 'tool_leakcheck'), 1, \core\output\notification::NOTIFY_ERROR);
            }
        }
    }

    return $errs;
}

/**
 * Checks password against the HaveIBeenPwned password breach API. No passwords are transferred.
 * Password is hashed, and only the first 5 characters are sent over the network.
 *
 * @param string $password The password to be validated.
 * @return string Returns a string of any errors presented by the check, or an empty string for success.
 *
 */
function tool_leakcheck_password_blacklist($password) {
    global $CFG;
    require_once($CFG->libdir.'/filelib.php');
    $api = 'https://api.pwnedpasswords.com/range/';
    $pwhash = sha1($password);
    $searchstring = substr($pwhash, 0, 5); // Get first 5 chars of hash to search API for.

    // Get API response.
    $url = $api . $searchstring;
    $response = download_file_content($url, null, null, false, 5, 5); // 5 second timeout.

    if ($response == false) {
        // API not available, create error event, and log it.
        $failmessage = get_string('responseapierror', 'tool_leakcheck');
        $event = \core\event\webservice_login_failed::create(array('other' => array('reason' => $failmessage, 'method' => '')));
        $event->trigger();
        return '';
    }

    // Check for presence of hash in response.
    $shorthash = substr($pwhash, 5);
    if (stripos($response, $shorthash) !== false) {
        return get_string('responsebreachedpassword', 'tool_leakcheck').'<br>';
    }

    return '';
}

/**
 * Checks the global moodle configuration for any settings that conflict or are relied upon by the plugin
 *
 * @return string Returns a string of any errors presented by the check, or an empty string for success.
 *
 */
function tool_leakcheck_config_checker() {
    global $CFG;
    $response = '';
    $type = 'notifysuccess';

    // Check if a password policy is in place, inform users of visibility of password policy.
    if ($CFG->passwordpolicy != 1) {
        $response .= get_string('configpasswordpolicy', 'tool_leakcheck').'<br>';
        // If notify is currently success.
        if ($type == 'notifysuccess') {
            $type = 'notifymessage';
        }
    }

    // Check if password check on login is enabled.
    if ($CFG->passwordpolicycheckonlogin != 1) {
        $response .= get_string('configpasswordcheckonlogin', 'tool_leakcheck').'<br>';
        $type = 'notifyerror';
    }

    // If no errors at end, return a good message.
    if ($type == 'notifysuccess') {
        $response .= get_string('configpasswordgood', 'tool_leakcheck').'<br>';
    }

    return array($response, $type);
}

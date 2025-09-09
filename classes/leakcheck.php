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

namespace tool_leakcheck;

/**
 * Class callback
 *
 * @package    tool_leakcheck
 * @copyright  2025 Jordan Tomkinson <jordan.tomkinson@openlms.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class leakcheck {

    /**
     * Callback from core login process, checks password against breach API if enabled.
     * If password is found to be breached, the user is logged out, their password cleared
     * and all sessions killed, forcing them to reset their password.
     * 
     * @param \stdClass $user
     * @param string $password
     * @param \auth_plugin_base $authplugin
     * @return void
     */
    public static function auth_callback($user, $password, $authplugin) {
        if ($authplugin->is_internal() && !empty($user->id) && !isguestuser($user)) {
            $leaked = self::tool_leakcheck_password_validate($password);

            if (!empty($leaked)) {
                global $CFG, $DB;
                // Set the password to empty string, so it cannot be used again, locking the user out.
                $DB->update_record('user', ['id' => $user->id, 'password' => '']);
                // Destroy all sessions for this user.
                $killmethod = $CFG->branch >= 405 ? 'destroy_user_sessions' : 'kill_user_sessions';
                \core\session\manager::$killmethod($user->id);
                // Trigger a login failed event.
                $failurereason = get_string('responsebreachedpasswordlogout', 'tool_leakcheck');
                $event = \core\event\user_login_failed::create(array('other' => array('username' => $user->username,
                        'reason' => $failurereason)));
                $event->trigger();
                // Redirect to the forgot password page with an error message.
                if ($authplugin->can_reset_password()) {
                    $forgoturl = new \moodle_url('/login/forgot_password.php');
                }else {
                    $forgoturl = new \moodle_url('/login/index.php');
                }
                redirect($forgoturl, $failurereason, 1, \core\output\notification::NOTIFY_ERROR);
            }
        }
    }

    /**
     * Validates the password provided against the password policy configured in the plugin admin
     * settings menu. Calls all of the individual checks
     *
     * @param string $password The password to be validated.
     * @return string Returns a string of any errors presented by the checks, or an empty string for success.
     *
     */
    public static function tool_leakcheck_password_validate($password) {
        $errs = '';
        // sometimes we get here from other auth types (oidc) and
        // we cant check empty passwords anyway, so just return OK.
        if (empty($password)) {
            return '';
        }
        // Check against HaveIBeenPwned.com password breach API.
        if (get_config('tool_leakcheck', 'enabled')) {
            $leaked = self::tool_leakcheck_password_blacklist($password);
            $errs .= $leaked;
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
    public static function tool_leakcheck_password_blacklist($password) {
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
     * @return array Returns a string of any errors presented by the check, or an empty string for success.
     *
     */
    public static function tool_leakcheck_config_checker() {
        global $CFG;
        $response = '';
        $type = 'notifysuccess';

        // Check if the plugin is enabled.
        if (!get_config('tool_leakcheck', 'enabled')) {
            $response .= get_string('plugindisabled', 'tool_leakcheck').'<br>';
            // If notify is currently success.
            if ($type == 'notifysuccess') {
                $type = 'notifymessage';
            }
        }

        // If no errors at end, return a good message.
        if ($type == 'notifysuccess') {
            $response .= get_string('pluginenabled', 'tool_leakcheck').'<br>';
        }

        return array($response, $type);
    }

}

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
 * Install script for Password Leak Checker
 *
 * Documentation: {@link https://moodledev.io/docs/guides/upgrade}
 *
 * @package    tool_leakcheck
 * @copyright  2025 Jordan Tomkinson <jordan.tomkinson@openlms.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Executed on installation of Password Leak Checker
 *
 * @return bool
 */
function xmldb_tool_leakcheck_install() {
    global $DB;

    // get the current value of passwordpolicy from the database.
    $policyenabled = $DB->get_field('config', 'value', ['name' => 'passwordpolicy']);

    // if the password policy is not enabled.
    if ($policyenabled == 0) {
        // if the password policy was off, we will set the policy settings to 0.
        // this is to avoid a situation where the password policy is enabled but
        // the settings are not what the admin expects.
        // setting them to 0 means the policy functions the same as before we turned it on.
        // the admin is then free to change the settings as they see fit.
        set_config('minpasswordlength', 0);
        set_config('minpassworddigits', 0);
        set_config('minpasswordlower', 0);
        set_config('minpasswordupper', 0);
        set_config('minpasswordnonalphanum', 0);
        set_config('maxconsecutiveidentchars', 0);
        // now enable the password policy.
        set_config('passwordpolicy', 1);
    }

    // ensure that password policy check on login is enabled.
    set_config('passwordpolicycheckonlogin', 1);

    return true;
}

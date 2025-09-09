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
 * Callback implementations for Password Leak Checker
 *
 * @package    tool_leakcheck
 * @copyright  2025 Jordan Tomkinson <jordan.tomkinson@openlms.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use tool_leakcheck\leakcheck;

/**
 * Validate password change form
 * checks password against breach API if enabled.
 * If password is found to be breached, an error is returned to the form.
 * 
 * @param array $data The data from the form.
 * @param stdClass $user not used here.
 * @return array of errors
 */
function tool_leakcheck_validate_extend_change_password_form($data, $user) {
    if (get_config('tool_leakcheck', 'enabled')) {
        $error = leakcheck::tool_leakcheck_password_validate($data['newpassword1']);
        if (!empty($error)) {
            $errors = array();
            $errors['newpassword1'] = '<div>' . $error . '</div>';
            $errors['newpassword2'] = '<div>' . $error . '</div>';
            return $errors;
        }
    }
    return array();
}

/**
 * Validate password set form
 * checks password against breach API if enabled.
 * If password is found to be breached, an error is returned to the form.
 * 
 * @param array $data The data from the form.
 * @param stdClass $user not used here.
 * @return array of errors
 */
function tool_leakcheck_validate_extend_set_password_form($data, $user) {
    if (get_config('tool_leakcheck', 'enabled')) {
        $error = leakcheck::tool_leakcheck_password_validate($data['password']);
        if (!empty($error)) {
            $errors = array();
            $errors['password'] = '<div>' . $error . '</div>';
            $errors['password2'] = '<div>' . $error . '</div>';
            return $errors;
        }
    }
    return array();
}

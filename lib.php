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
 * Wrapper function for the password validation. Simply calls password validate
 * with test mode disabled.
 *
 * @param string $password The password to be validated.
 * @param stdClass $user A user object to perform validation against if preset. Defaults to null
 * @return string Returns a string of any errors presented by the checks, or an empty string for success.
 *
 */

function tool_leakcheck_check_password_policy($password, $user = null) {
    if (get_config('tool_leakcheck', 'enabled')) {
        // If plugin is enabled, execute validation.
        require_once(__DIR__.'/locallib.php');
        return tool_leakcheck_password_validate($password, $user);
    } else {
        // Empty, passed validation.
        return '';
    }
}


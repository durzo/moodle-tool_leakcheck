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
 * Upgrade steps for Password Leak Checker
 *
 * Documentation: {@link https://moodledev.io/docs/guides/upgrade}
 *
 * @package    tool_leakcheck
 * @category   upgrade
 * @copyright  2025 Jordan Tomkinson <jordan.tomkinson@openlms.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Execute the plugin upgrade steps from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_tool_leakcheck_upgrade($oldversion) {
    global $DB;

    if ($oldversion == 2025082700) {
        // in version 2025082700 we had in db/install.php that if disabled, enable password policy
        // and set the policy settings to 0. lets undo that here as we no longer depend on the policy.
        // get these directly from the db, as we may be overriding $CFG in config.php.
        $passwordpolicy = $DB->get_field('config', 'value', ['name' => 'passwordpolicy']);
        $minpasswordlength = $DB->get_field('config', 'value', ['name' => 'minpasswordlength']);
        $minpassworddigits = $DB->get_field('config','value', ['name'=> 'minpassworddigits']);
        $minpasswordlower = $DB->get_field('config','value', ['name'=> 'minpasswordlower']);
        $minpasswordupper = $DB->get_field('config','value', ['name'=> 'minpasswordupper']);
        $minpasswordnonalphanum = $DB->get_field('config','value', ['name'=> 'minpasswordnonalphanum']);
        $maxconsecutiveidentchars = $DB->get_field('config','value', ['name'=> 'maxconsecutiveidentchars']);
        $passwordpolicycheckonlogin = $DB->get_field('config','value', ['name'=> 'passwordpolicycheckonlogin']);

        if ($passwordpolicy === '1' && $passwordpolicycheckonlogin === '1') {
            if ($minpasswordlength === '0' && $minpassworddigits === '0'
                && $minpasswordlower === '0' && $minpasswordupper === '0'
                && $minpasswordnonalphanum === '0' && $maxconsecutiveidentchars === '0') {
                $DB->set_field('config', 'value', 0, ['name' => 'passwordpolicy']);
                $DB->set_field('config', 'value', 0, ['name' => 'passwordpolicycheckonlogin']);
            }
        }

        // removed setting lockout_on_leak
        if (!empty($DB->get_field('config_plugins', 'name', ['plugin' => 'tool_leakcheck', 'name' => 'lockout_on_leak']))) {
            $DB->delete_records('config_plugins', ['plugin' => 'tool_leakcheck', 'name' => 'lockout_on_leak']);
        }

        upgrade_plugin_savepoint(true, 2025090900, 'tool', 'leakcheck');
    }

    return true;
}

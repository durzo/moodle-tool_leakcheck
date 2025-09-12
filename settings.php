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
 *  Password Policy Checker Settings Page
 *
 * @package    tool_leakcheck
 * @copyright  2025 Jordan Tomkinson <jordan.tomkinson@openlms.net>
 * @copyright  2019 Peter Burnett <peterburnett@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {

    // Create leakcheck category for page and external page.
    $ADMIN->add('tools', new admin_category('leakcheck', get_string('pluginname', 'tool_leakcheck')));

    // Add External admin page for validation.
    $ADMIN->add('leakcheck', new admin_externalpage('tool_leakcheck_form',
    get_string('testpasswordpagestring', 'tool_leakcheck'),
    new moodle_url('/admin/tool/leakcheck/test_password.php')));

    // Add main plugin configuration page.
    $settings = new admin_settingpage('leakchecksettings', get_string('testpasswordpage', 'tool_leakcheck'));
    $ADMIN->add('leakcheck', $settings);

    // if (!during_initial_install()) {

    $settings->add(new admin_setting_configcheckbox('tool_leakcheck/enabled',
            get_string('passwordenablename', 'tool_leakcheck'),
            get_string('passwordenabledesc', 'tool_leakcheck'), 1));

    $settings->add(new admin_setting_configtext('tool_leakcheck/include_auths',
            get_string('includeauthsname', 'tool_leakcheck'),
            get_string('includeauthsdesc', 'tool_leakcheck'), '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('tool_leakcheck/exclude_auths',
            get_string('excludeauthsname', 'tool_leakcheck'),
            get_string('excludeauthsdesc', 'tool_leakcheck'), '', PARAM_TEXT));
}

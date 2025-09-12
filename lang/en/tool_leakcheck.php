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
 * Language pack
 *
 * @package    tool_leakcheck
 * @copyright  2025 Jordan Tomkinson <jordan.tomkinson@openlms.net>
 * @copyright  2019 Peter Burnett <peterburnett@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['eventfailed'] = 'Leakcheck failed';
$string['pluginname'] = 'Password Leak Checker';
$string['pluginenabled'] = 'The plugin is enabled.';
$string['plugindisabled'] = "The plugin is not enabled.";
$string['passwordenablename'] = 'Enable plugin';
$string['passwordenabledesc'] = 'Securely check passwords against the haveibeenpwned.com Breached passwords API.';
$string['includeauthsname'] = 'Included authentication types';
$string['includeauthsdesc'] = 'Additional authentication types to include in leak checking, comma separated. By default only auth types that define themselves as internal are checked.';
$string['excludeauthsname'] = 'Excluded authentication types';
$string['excludeauthsdesc'] = 'Authentication types to exclude from leak checking, comma separated. overrides additional or default internal auth types.';
$string['responsebreachedpassword'] = 'Password found in online breached passwords collection.';
$string['responsebreachedpasswordlogout'] = 'Password found in online breached passwords collection. Please request a new password immediately.';
$string['responseapierror'] = 'Service HaveIBeenPwned.com password API was not responsive.';
$string['testpasswordpagestring'] = 'Leaked password tester';
$string['testpasswordpage'] = 'Password leak check configuration';
$string['testpasswordpagepasswordbox'] = 'Enter a password to test:';
$string['testpasswordempty'] = 'Cannot test an empty password';
$string['testpasswordpagetestbutton'] = 'Test password';
$string['testpasswordconfigchecker'] = 'Moodle configuration checker';
$string['testpasswordvalidationtester'] = 'Password leak check tester';
$string['testpasswordvalidationpassed'] = 'Password successfully passed validation testing.';
$string["privacy:metadata"] = "The Password leak checker plugin does not store any personal data.";

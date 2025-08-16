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

$string['pluginname'] = 'Password Leak Checker';

$string['configpasswordpolicy'] = 'It appears that the "Password Policy" (passwordpolicy) control is disabled. If this control is disabled, new users will not be able to view information about the password policy when setting their password.';
$string['configpasswordcheckonlogin'] = 'It appears that the "Check password on login" (passwordpolicycheckonlogin) control is disabled. This plugin cannot function correctly without it.';
$string['configpasswordgood'] = 'Moodle configuration appears to be correct.';
$string['passwordenablename'] = 'Enable plugin';
$string['passwordenabledesc'] = 'Securely check passwords against the haveibeenpwned.com Breached passwords API.';
// $string['passwordforcedconfigmanual'] = 'Settings are read only, configuration is set in a forced configuration file.';
$string['responsebreachedpassword'] = 'Password found in online breached passwords collection.';
$string['responseapierror'] = 'Service HaveIBeenPwned.com password API was not responsive.';
$string['testpasswordpagestring'] = 'Leaked password tester';
$string['testpasswordpage'] = 'Password leak check configuration';
$string['testpasswordpagepasswordbox'] = 'Enter a password to test:';
$string['testpasswordpageusernamebox'] = 'Enter user account email or username to test configured password against:';
$string['testpasswordpagetestbutton'] = 'Test password';
$string['testpasswordconfigchecker'] = 'Moodle configuration checker';
$string['testpasswordvalidationtester'] = 'Password leak check tester';
$string['testpasswordvalidationpassed'] = 'Password successfully passed validation testing.';
$string['responsebreachedpasswordlogout'] = 'Password found in online breached passwords collection. Please request a new password immediately.';
$string['passwordleaklockoutname'] = 'Lockout on password leak';
$string['passwordleaklockoutdesc'] = 'If a password is found in the breached passwords collection, the user will be logged out of all sessions, locked out of Moodle, and redirected to the forgot password page.';
$string["privacy:metadata"] = "The Password leak checker plugin does not store any personal data.";

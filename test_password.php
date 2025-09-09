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
 * A form for password validation against custom settings
 *
 * @package   tool_leakcheck
 * @copyright 2025 Jordan Tomkinson <jordan.tomkinson@openlms.net>
 * @copyright 2019 Peter Burnett <peterburnett@catalyst-au.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
use tool_leakcheck\leakcheck;
require_once(dirname(__FILE__) . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('tool_leakcheck_form');

$prevurl = ($CFG->wwwroot.'/admin/category.php?category=leakcheck');
$success = false;
$configcheckdesc = leakcheck::tool_leakcheck_config_checker();

$form = new \tool_leakcheck\form\test_password_form();

if ($form->is_cancelled()) {
    redirect($prevurl);
} else if ($fromform = $form->get_data()) {
    $success = true;
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('testpasswordpagestring', 'tool_leakcheck'));

echo '<br>';
echo $OUTPUT->heading(get_string('testpasswordconfigchecker', 'tool_leakcheck'), 4);
echo $OUTPUT->notification($configcheckdesc[0], $configcheckdesc[1]);
echo '<br>';

echo $OUTPUT->heading(get_string('testpasswordvalidationtester', 'tool_leakcheck'), 4);
if ($success) {
    echo $OUTPUT->notification(get_string('testpasswordvalidationpassed', 'tool_leakcheck'), 'notifysuccess');
}

$form->display();

echo $OUTPUT->footer();

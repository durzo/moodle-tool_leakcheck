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
 * Password Validation Settings form
 *
 * @package     tool_leakcheck
 * @copyright   2025 Jordan Tomkinson <jordan.tomkinson@openlms.net>
 * @copyright   2019 Peter Burnett <peterburnett@catalyst-au.net>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


namespace tool_leakcheck\form;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class test_password_form extends \moodleform {

    public function definition() {

        $mform = $this->_form;

        $mform->addElement('text', 'testerpassword', get_string('testpasswordpagepasswordbox', 'tool_leakcheck'));
        $mform->setType('testerpassword', PARAM_RAW);

        $mform->addElement('text', 'testerinput', get_string('testpasswordpageusernamebox', 'tool_leakcheck'));
        $mform->setType('testerinput', PARAM_RAW);

        $this->add_action_buttons(true, get_string('testpasswordpagetestbutton', 'tool_leakcheck'));
    }

    public function validation($data, $files) {
        global $DB, $USER;
        require_once(__DIR__.'/../../lib.php');
        $errors = parent::validation($data, $files);

        $testpassword = $data['testerpassword'];
        $testerinput = $data['testerinput'];

        $otheruser = '';

        // Try input as username first, then email.
        $foundusers = $DB->get_records('user', array('username' => ($testerinput)));
        if (!empty($foundusers)) {
            // Get first matching username record.
            $otheruser = reset($foundusers);
        } else {
            $foundusers = $DB->get_records('user', array('email' => ($testerinput)));
            if (!empty($foundusers)) {
                // Get first matching email record (should be unique).
                $otheruser = reset($foundusers);
            } else {
                $otheruser = $USER;
            }
        }

        // Don't check if testpassword is empty. If record exists for optional user,
        // check pw against that account. Else, against currenlty logged in account.
        $testervalidation = '';
        if ($testpassword != '') {
            // $testervalidation = tool_leakcheck_check_password_policy($testpassword, $otheruser);
            $testervalidation = tool_leakcheck_password_validate($testpassword, $otheruser);
        }

        if (!empty($testervalidation)) {
            $errors['testerpassword'] = $testervalidation;
        }

        return $errors;
    }
}

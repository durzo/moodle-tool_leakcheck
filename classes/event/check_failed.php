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

namespace tool_leakcheck\event;

/**
 * Event failed
 *
 * @package    tool_leakcheck
 * @copyright  2025 Jordan Tomkinson <jordan.tomkinson@openlms.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class check_failed extends \core\event\base {

    /**
     * Set basic properties for the event.
     */
    protected function init() {
        $this->context = \context_system::instance();
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_OTHER;
    }

    /**
     * Return localised event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('eventfailed', 'tool_leakcheck');
    }

    /**
     * Returns non-localised event description with id's for admin use only.
     *
     * @return string
     */
    public function get_description() {
        $username = s($this->other['username']);
        return "Password for user '$username' found in online breach passwords collection";
    }

    /**
     * Get URL related to the action.
     *
     * @return \moodle_url|null
     */
    public function get_url() {
        if (isset($this->data['userid'])) {
            return new \moodle_url('/user/profile.php', array('id' => $this->data['userid']));
        } else {
            return null;
        }
    }

    public static function get_other_mapping() {
        return false;
    }
}

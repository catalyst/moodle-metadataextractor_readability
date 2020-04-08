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
 * metadataextractor_readable calculator tests.
 *
 * @package    metadataextractor_readable
 * @copyright  2020 Tom Dickman <tomdickman@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/admin/tool/metadata/extractor/readable/constants.php');

/**
 * metadataextractor_readable calculator tests.
 *
 * @package    metadataextractor_readable
 * @copyright  2020 Tom Dickman <tomdickman@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class calculator_test extends advanced_testcase {

    public function setUp() {
        $this->resetAfterTest();
    }

    /**
     * Test getting the average reading speed for use in calculations.
     */
    public function test_get_average_reading_speed() {
        $calculator = new \metadataextractor_readable\calculator();

        set_config('average_reading_speed', 300, 'metadataextractor_readable');

        $this->assertEquals(300, $calculator->get_average_reading_speed());

        unset_config('average_reading_speed', 'metadataextractor_readable');

        $this->assertEquals(METADATAEXTRACTOR_READABLE_DEFAULT_READING_SPEED,
            $calculator->get_average_reading_speed());
    }

    /**
     * Data provided for testing calculation of reading times.
     *
     * @return array array[]
     */
    public function calculate_reading_time_provider() {
        return [
            '500 words - No reading time set' => [500, 0, 0, 2, 6],
            '500 words - Default reading time set' => [500, METADATAEXTRACTOR_READABLE_DEFAULT_READING_SPEED, 0, 2, 6],
            '500 words - Custom reading time set' => [500, 400, 0, 1, 15],
            '5000 words - No reading time set' => [5000, 0, 0, 21, 0],
            '5000 words - Default reading time set' => [5000, METADATAEXTRACTOR_READABLE_DEFAULT_READING_SPEED, 0, 21, 0],
            '5000 words - Custom reading time set' => [5000, 400, 0, 12, 30],
            '50000 words - No reading time set' => [50000, 0, 3, 30, 5],
            '50000 words - Default reading time set' => [50000, METADATAEXTRACTOR_READABLE_DEFAULT_READING_SPEED, 3, 30, 5],
            '50000 words - Custom reading time set' => [50000, 400, 2, 5, 0],
        ];
    }

    /**
     * Test the calculation of reading times
     *
     * @dataProvider calculate_reading_time_provider
     *
     * @param int $wordcount word count to test
     * @param int $averagereadingspeed average reading time set
     * @param int $hours expected hours to read
     * @param int $minutes expected minutes to read
     * @param int $seconds expected seconds to read
     */
    public function test_calculate_reading_time($wordcount, $averagereadingspeed, $hours, $minutes, $seconds) {
        set_config('average_reading_speed', $averagereadingspeed, 'metadataextractor_readable');

        // Generate test text of required wordcount.
        $text = '';
        for($i = 0; $i < $wordcount; $i++) {
            $text .= ' test';
        }

        $calculator = new \metadataextractor_readable\calculator();
        $readingtime = $calculator->calculate_reading_time($text);

        $actual = explode(':', $readingtime);

        $this->assertEquals($hours, $actual[0]);
        $this->assertEquals($minutes, $actual[1]);
        $this->assertEquals($seconds, $actual[2]);
    }

}
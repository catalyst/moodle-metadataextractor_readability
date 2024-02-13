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
 * metadataextractor_readable readable_helper tests.
 *
 * @package    metadataextractor_readable
 * @copyright  2020 Tom Dickman <tomdickman@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/admin/tool/metadata/extractor/readable/constants.php');

/**
 * metadataextractor_readable readable_helper tests.
 *
 * @package    metadataextractor_readable
 * @copyright  2020 Tom Dickman <tomdickman@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @group      metadataextractor_readable
 */
class readable_helper_test extends advanced_testcase {

    public function setUp(): void {
        $this->resetAfterTest();
    }

    /**
     * Data provider for testing MIME type related methods.
     *
     * @return array string[][] each element in shape of:
     * [
     *     string complete mimetype with encoding,
     *     string MIME type within header,
     *     bool is the mimetype supported?
     * ]
     */
    public function contenttype_provider() {
        return [
            ['multipart/form-data; boundary=---------------------------8721656041911415653955004498', 'multipart/form-data', false],
            ['application/json; charset=utf-8', 'application/json', false],
            ['application/octet-stream', 'application/octet-stream', false],
            ['text/html; charset=utf-8', 'text/html', true],
            ['text/plain; charset=ISO-8859-1', 'text/plain', true],
            ['text/plain', 'text/plain', true],
            ['text/html', 'text/html', true],
            ['application/pdf', 'application/pdf', true],
            ['application/msword', 'application/msword', true],
            ['application/vnd.oasis.opendocument.text', 'application/vnd.oasis.opendocument.text', true],
            ['application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', true],
            ['application/x-abiword', 'application/x-abiword', true],
            ['application/vnd.ms-powerpoint', 'application/vnd.ms-powerpoint', true],
            ['application/vnd.oasis.opendocument.presentation', 'application/vnd.oasis.opendocument.presentation', true],
            ['application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation', true],
        ];
    }

    /**
     * Test extracting just the MIME type from a 'Content-Type' header.
     *
     * @dataProvider contenttype_provider
     *
     * @param string $contenttype the raw 'Content-Type' header value.
     * @param string $expected the expected MIME type.
     */
    public function test_get_mimetype_without_parameters(string $contenttype, string $expected) {
        $actual = \metadataextractor_readable\readable_helper::get_mimetype_without_parameters($contenttype);

        $this->assertIsString($actual);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test checking if a mimetype is supported.
     *
     * @dataProvider contenttype_provider
     *
     * @param string $unused raw MIME types are not used.
     * @param string $mimetype the MIME type/subtype to check.
     * @param bool $expected should the MIME type be supported?
     */
    public function test_is_mimetype_supported(string $unused, string $mimetype, bool $expected) {
        $actual = \metadataextractor_readable\readable_helper::is_mimetype_supported($mimetype);

        $this->assertIsBool($actual);
        $this->assertEquals($expected, $actual);
    }
}

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
 * metadataextractor_readable extractor tests.
 *
 * @package    metadataextractor_readable
 * @copyright  2020 Tom Dickman <tomdickman@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use tool_metadata\mock_file_builder;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/admin/tool/metadata/constants.php');
require_once($CFG->dirroot . '/admin/tool/metadata/extractor/readable/constants.php');

/**
 * metadataextractor_readable extractor tests.
 *
 * @package    metadataextractor_readable
 * @copyright  2020 Tom Dickman <tomdickman@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @group      metadataextractor_readable
 */
class extractor_test extends advanced_testcase {

    public function setUp(): void {
        $this->resetAfterTest();
    }

    /**
     * Test validating a file resource.
     */
    public function test_validate_resource_file() {
        global $DB;

        // Create a test file.
        [$unused, $file] = mock_file_builder::mock_document();

        // Create a test directory.
        $fs = get_file_storage();
        $syscontext = context_system::instance();
        $directory = $fs->create_directory($syscontext->id, 'metadataextractor_readable', 'unittest', 0, '/');

        $extractor = new \metadataextractor_readable\extractor();

        $this->assertTrue($extractor->validate_resource($file, TOOL_METADATA_RESOURCE_TYPE_FILE));
        $this->assertFalse($extractor->validate_resource($directory, TOOL_METADATA_RESOURCE_TYPE_FILE));

        // Set a null value in the file record for mimetype.
        $fileid = $file->get_id();
        $DB->update_record('files', ['id' => $fileid, 'mimetype' => null]);
        $file = $fs->get_file_by_id($fileid);

        // If stored_file has no mimetype should fall back to Tika coerced mimetype.
        $tikaextractor = new \metadataextractor_tika\extractor();
        if (!$tikaextractor->is_ready()) {
            try {
                $extractor->validate_resource($file, TOOL_METADATA_RESOURCE_TYPE_FILE);
                $this->fail('Should require configured metadataextractor_tika for coercing the mimetype of a file in fallback.');
            } catch (\Exception $exception) {
                $this->assertInstanceOf(\tool_metadata\extraction_exception::class, $exception);
            }
        } else {
            try {
                $actual = $extractor->validate_resource($file, TOOL_METADATA_RESOURCE_TYPE_FILE);
                $this->assertTrue($actual);
            } catch (\Exception $exception) {
                // Network errors should throw an exception, as we can't coerce mimetype without network.
                $this->assertInstanceOf(\tool_metadata\network_exception::class, $exception);
            }
        }
    }

    /**
     * Test validating a URL resource.
     */
    public function test_validate_resource_url() {
        // Generate a test URL, this should be a valid moodle.org link.
        $course = $this->getDataGenerator()->create_course();
        $url = $this->getDataGenerator()->create_module('url', ['course' => $course]);
        $url->externalurl = 'https://moodle.org';
        $url->parameters = 'a:0:{}';

        $extractor = new \metadataextractor_readable\extractor();

        $tikaextractor = new \metadataextractor_tika\extractor();
        if (!$tikaextractor->is_ready()) {
            try {
                $extractor->validate_resource($url, TOOL_METADATA_RESOURCE_TYPE_URL);
                $this->fail('Should require configured metadataextractor_tika for coercing the mimetype of a URL.');
            } catch (\Exception $exception) {
                $this->assertInstanceOf(\tool_metadata\extraction_exception::class, $exception);
            }
        } else {
            try {
                $actual = $extractor->validate_resource($url, TOOL_METADATA_RESOURCE_TYPE_URL);
                $this->assertTrue($actual);
            } catch (\Exception $exception) {
                // Network errors should throw an exception, as we can't validate URL without network.
                $this->assertInstanceOf(\tool_metadata\network_exception::class, $exception);
            }
        }

        // Set URL to an invalid value.
        $url->externalurl = 'moodle.org';

        if (!$tikaextractor->is_ready()) {
            try {
                $extractor->validate_resource($url, TOOL_METADATA_RESOURCE_TYPE_URL);
                $this->fail('Should require configured metadataextractor_tika for coercing the mimetype of a URL.');
            } catch (\Exception $exception) {
                $this->assertInstanceOf(\tool_metadata\extraction_exception::class, $exception);
            }
        } else {
            try {
                $actual = $extractor->validate_resource($url, TOOL_METADATA_RESOURCE_TYPE_URL);
                $this->assertFalse($actual);
            } catch (\Exception $exception) {
                // Network errors should throw an exception, as we can't validate URL without network.
                $this->assertInstanceOf(\tool_metadata\network_exception::class, $exception);
            }
        }
    }

    /**
     * Test extracting readability metadata from a file resource.
     */
    public function test_extract_file_metadata() {
        [$metadata, $file] = mock_file_builder::mock_document();

        $extractor = new \metadataextractor_readable\extractor();

        $tikaextractor = new \metadataextractor_tika\extractor();

        if (!$tikaextractor->is_ready()) {
            try {
                $extractor->extract_file_metadata($file);
                $this->fail('Should require configured metadataextractor_tika for coercing the content of a file.');
            } catch (\Exception $exception) {
                $this->assertInstanceOf(\tool_metadata\extraction_exception::class, $exception);
            }
        } else {
            $actual = $extractor->extract_file_metadata($file);
            $this->assertInstanceOf(\metadataextractor_readable\metadata::class, $actual);
            $this->assertEquals(0, $actual->id);
            $this->assertEquals($file->get_contenthash(), $actual->get_resourcehash());
        }
    }

    /**
     * Test extracting readability metadata from a url resource.
     */
    public function test_extract_url_metadata() {
        // Generate a test URL, this should be a valid moodle.org link.
        $course = $this->getDataGenerator()->create_course();
        $url = $this->getDataGenerator()->create_module('url', ['course' => $course]);
        $url->externalurl = 'https://moodle.org';
        $url->parameters = 'a:0:{}';

        $extractor = new \metadataextractor_readable\extractor();

        $tikaextractor = new \metadataextractor_tika\extractor();

        if (!$tikaextractor->is_ready()) {
            try {
                $extractor->extract_url_metadata($url);
                $this->fail('Should require configured metadataextractor_tika for coercing the content of a url.');
            } catch (\Exception $exception) {
                $this->assertInstanceOf(\tool_metadata\extraction_exception::class, $exception);
            }
        } else {
            $actual = $extractor->extract_url_metadata($url);
            $this->assertInstanceOf(\metadataextractor_readable\metadata::class, $actual);
            $this->assertEquals(0, $actual->id);
            $this->assertEquals(\tool_metadata\helper::get_resourcehash($url, TOOL_METADATA_RESOURCE_TYPE_URL),
                $actual->get_resourcehash());
        }
    }
}

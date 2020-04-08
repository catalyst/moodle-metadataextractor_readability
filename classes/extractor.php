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
 * Class for extraction of readability metadata.
 *
 * @package    metadataextractor_readable
 * @copyright  2020 Tom Dickman <tomdickman@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace metadataextractor_readable;

use stored_file;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/admin/tool/metadata/extractor/readable/vendor/autoload.php');
require_once($CFG->dirroot . '/admin/tool/metadata/extractor/readable/constants.php');

/**
 * Class for extraction of readability metadata.
 *
 * @package    metadataextractor_readable
 * @copyright  2020 Tom Dickman <tomdickman@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class extractor extends \tool_metadata\extractor {

    /**
     * The plugin name.
     */
    const METADATAEXTRACTOR_NAME = 'readable';

    /**
     * Default table name for storing extracted metadata for this extractor.
     */
    const METADATA_BASE_TABLE = 'metadataextractor_readable';

    /**
     * Attempt to extract url readability metadata.
     *
     * @param object $url the url to create metadata for.
     *
     * @return \tool_metadata\metadata|null a metadata object instance or false if no metadata.
     * @throws \tool_metadata\extraction_exception
     */
    public function extract_url_metadata($url) {

        $result = null;

        // TODO: Implement method.

        return $result;
    }

    /**
     * Attempt to extract file readability metadata.
     *
     * @param \stored_file $file the file to create readability data for.
     *
     * @return \tool_metadata\metadata|null a metadata object instance or null if no metadata.
     * @throws \tool_metadata\extraction_exception
     */
    public function extract_file_metadata(stored_file $file) {

        $result = null;

        // TODO: Implement method.

        return $result;
    }

    /**
     * Validate that metadata can be extracted from a resource.
     *
     * @param object $resource the resource instance to check
     * @param string $type the type of resource.
     *
     * @return bool
     */
    public function validate_resource($resource, string $type) : bool {
        switch($type) {
            // File resource cannot be directories.
            case TOOL_METADATA_RESOURCE_TYPE_FILE :
                if ($resource->is_directory()) {
                    $result = false;
                } else {
                    $result = true;
                }
                break;
            // Only support valid HTTP(S) URLs.
            case TOOL_METADATA_RESOURCE_TYPE_URL :
                if (!preg_match('/^https?:\/\//i', $resource->externalurl) ||
                    !url_appears_valid_url($resource->externalurl)) {
                    $result = false;
                } else {
                    $result = true;
                }
                break;
            default:
                $result = false;
                break;
        }

        return $result;
    }
}

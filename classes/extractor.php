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
use tool_metadata\extraction_exception;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/admin/tool/metadata/extractor/readable/vendor/autoload.php');
require_once($CFG->dirroot . '/admin/tool/metadata/constants.php');
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
     * Attempt to extract file readability metadata.
     *
     * @param \stored_file $file the file to create readability data for.
     *
     * @return string|null a metadata object instance or null if no metadata.
     * @throws \tool_metadata\extraction_exception
     */
    public function extract_file_metadata(stored_file $file) {
        $metadata = null;
        $tikaextractor = new \metadataextractor_tika\extractor();

        if ($tikaextractor->is_ready()) {
            $contents = $tikaextractor->extract_file_content($file);
        } else {
            throw new extraction_exception('error:dependency:tika:content', 'metadataextractor_readable');
        }

        if (!empty($contents)) {
            $calculator = new calculator();
            $scores = $calculator->calculate_scores($contents);
            $metadata = new metadata(0, \tool_metadata\helper::get_resourcehash($file, TOOL_METADATA_RESOURCE_TYPE_FILE), $scores);
        }

        return $metadata;
    }

    /**
     * Attempt to extract url readability metadata.
     *
     * @param object $url the url to create metadata for.
     *
     * @return \tool_metadata\metadata|null a metadata object instance or false if no metadata.
     * @throws \tool_metadata\extraction_exception
     */
    public function extract_url_metadata($url) {
        $metadata = null;
        $tikaextractor = new \metadataextractor_tika\extractor();

        if ($tikaextractor->is_ready()) {
            $contents = $tikaextractor->extract_url_content($url);
        } else {
            throw new extraction_exception('error:dependency:tika:content', 'metadataextractor_readable');
        }

        if (!empty($contents)) {
            $calculator = new calculator();
            $scores = $calculator->calculate_scores($contents);
            $metadata = new metadata(0, \tool_metadata\helper::get_resourcehash($url, TOOL_METADATA_RESOURCE_TYPE_URL), $scores);
        }

        return $metadata;
    }

    /**
     * Validate that metadata can be extracted from a resource.
     *
     * @param object $resource the resource instance to check
     * @param string $type the type of resource.
     *
     * @return bool
     * @throws \tool_metadata\extraction_exception when there is a networking issue and URL cannot be resolved.
     */
    public function validate_resource($resource, string $type) : bool {
        switch($type) {
            // File resource cannot be directories.
            case TOOL_METADATA_RESOURCE_TYPE_FILE :
                $mimetype = $resource->get_mimetype();
                if ($resource->is_directory() || !readable_helper::is_mimetype_supported($mimetype)) {
                    $result = false;
                } else {
                    $result = true;
                }
                break;
            // Only support valid HTTP(S) URLs and URLs for which the content is of a supported mimetype.
            case TOOL_METADATA_RESOURCE_TYPE_URL :
                $ishttp = (bool) preg_match('/^https?:\/\//i', $resource->externalurl);
                $validurl = url_appears_valid_url($resource->externalurl);

                try {
                    $tikaextractor = new \metadataextractor_tika\extractor();
                    $rawmimetype = $tikaextractor->extract_url_mimetype($resource);
                    if (!empty($rawmimetype)) {
                        $mimetype = readable_helper::get_mimetype_without_parameters($rawmimetype);
                        $supportedurl = readable_helper::is_mimetype_supported($mimetype);
                    } else {
                        $supportedurl = false;
                    }
                } catch (extraction_exception $exception) {
                    $supportedurl = false;
                }

                if (!$ishttp || !$validurl || !$supportedurl) {
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

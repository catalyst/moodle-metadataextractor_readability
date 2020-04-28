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
 * Helper class for metadataextractor_readable.
 *
 * Contains common functions used for extraction of readability data.
 *
 * @package    metadataextractor_readable
 * @copyright  2020 Tom Dickman <tomdickman@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace metadataextractor_readable;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/admin/tool/metadata/extractor/readable/vendor/autoload.php');
require_once($CFG->dirroot . '/admin/tool/metadata/constants.php');

/**
 * Helper class for metadataextractor_readable.
 *
 * Contains common functions used for extraction of readability data.
 *
 * @package    metadataextractor_readable
 * @copyright  2020 Tom Dickman <tomdickman@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class readable_helper {

    /**
     * Mimetypes which readability data can be extracted for.
     */
    const METADATAEXTRACTOR_READABLE_SUPPORTED_MIMETYPES = [
        'text/plain',
        'text/html',
        'application/pdf',
        'application/msword',
        'application/vnd.oasis.opendocument.text',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/x-abiword',
        'application/vnd.ms-powerpoint',
        'application/vnd.oasis.opendocument.presentation',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    ];

    /**
     * Get only the 'type/subtype' components of a MIME type, discarding the
     * encoding or any other parameters (if present).
     *
     * @param string $mimetype the MIME type to discard parameters from.
     *
     * @return string $mimetype the 'type/subtype' of MIME type.
     */
    public static function get_mimetype_without_parameters(string $mimetype) {
        $parts = explode(';', $mimetype);
        $mimetype = trim($parts[0]);

        return $mimetype;
    }

    /**
     * Does metadataextractor_readable support readability metadata extraction for
     * a particular mimetype?
     *
     * @param string $mimetype
     *
     * @return bool
     */
    public static function is_mimetype_supported(string $mimetype) : bool {
        if (empty($mimetype)) {
            $result = false;
        } else if (in_array($mimetype, static::METADATAEXTRACTOR_READABLE_SUPPORTED_MIMETYPES)) {
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }
}

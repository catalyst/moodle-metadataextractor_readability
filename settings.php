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
 * Admin settings for metadataextractor_readability.
 *
 * @package    metadataextractor_readability
 * @copyright  2020 Tom Dickman <tomdickman@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/admin/tool/metadata/extractor/readability/constants.php');

if ($hassiteconfig) {

    $settings->add(new admin_setting_heading('metadataextractor_readability/readabilitysettings',
        get_string('settings:heading', 'metadataextractor_readability'), ''));

    $settings->add(new admin_setting_configtext('metadataextractor_readability/average_reading_speed',
        get_string('settings:averagereadingspeed', 'metadataextractor_readability'),
        get_string('settings:averagereadingspeed_help', 'metadataextractor_readability'),
        METADATAEXTRACTOR_READABILITY_DEFAULT_READING_SPEED, PARAM_INT, 4));
}

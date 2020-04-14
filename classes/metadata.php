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
 * The metadata model for all metadataextractor_readable extracted data.
 *
 * @package    metadataextractor_readable
 * @copyright  2020 Tom Dickman <tomdickman@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace metadataextractor_readable;

defined('MOODLE_INTERNAL') || die();

/**
 * The metadata model for all metadataextractor_readable extracted data.
 *
 * @package    metadataextractor_readable
 * @copyright  2020 Tom Dickman <tomdickman@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class metadata extends \tool_metadata\metadata {

    /**
     * The table name where metadata records are stored.
     */
    const TABLE = 'metadataextractor_readable';

    /**
     * @var float Measure of the Flesh Kincaid reading ease of a piece of text.
     * This is the raw score, higher numbers indicate easier to read.
     *
     * Min: 0, Max: 100.
     */
    protected $fleschkincaidreadingease;

    /**
     * @var float The grade level to which the text score can be interpreted as applying to,
     * aligned to US grade level, maximum value is 12 indicating year 12 student or above.
     */
    protected $fleschkincaidgradelevel;

    /**
     * @var float Measure of the text against the Gunning Fog index
     * for readability, score based on US grade levels, maximum 19.
     */
    protected $gunningfogscore;

    /**
     * @var float Measure of the text against the Coleman Liau index
     * for readability, score based on US grade levels, maximum 12.
     */
    protected $colemanliauindex;

    /**
     * @var float Measure of the text against the Simple Measure of Gobbledygook
     * (SMOG) index, a more simplified version of Gunning Fog index, used for health messages.
     */
    protected $smogindex;

    /**
     * @var float Measure of the text against the Automated Readability index
     * for readability, score based on US grade levels, maximum 12.
     */
    protected $automatedreadabilityindex;

    /**
     * @var float Measure of the text against the Dale Chall Readability formula
     * for readability, uses a list of 3000 words that groups of fourth-grade US
     * students should reliably understand, considering any word not on that list to
     * be difficult, maximum 10.
     */
    protected $dalechallreadabilityscore;

    /**
     * @var int A count of the Dale Chall difficult words found in the text.
     * Dale Chall difficult words are any words not on a list of 3000 words that groups
     * of fourth-grade US students should reliably understand.
     */
    protected $dalechalldifficultwordcount;

    /**
     * @var float Measure of the text against the Spache Readability formula
     * for readability, uses a set list of everyday words which are considered readable
     * for young children, mostly designed for primary school education only, maximum 5.
     */
    protected $spachereadabilityscore;

    /**
     * @var int A count of the Spache difficult words found in the text.
     * Spache difficult words are any words not on a set list of everyday words
     * that groups of primary students should reliably understand.
     */
    protected $spachedifficultwordcount;

    /**
     * @var int The wordcount of text.
     */
    protected $wordcount;

    /**
     * @var float Average words per sentence of a text.
     */
    protected $averagewordspersentence;

    /**
     * @var int Calculated reading time for a text in seconds.
     */
    protected $readingtime;

    /**
     * The metadata key map for readability metadata.
     *
     * @return array string[].
     */
    protected function metadata_key_map() {
        return [
            'fleschkincaidreadingease' => [METADATAEXTRACTOR_READABLE_FK_READING_EASE],
            'fleschkincaidgradelevel' => [METADATAEXTRACTOR_READABLE_FK_GRADE_LEVEL],
            'gunningfogscore' => [METADATAEXTRACTOR_READABLE_GUNNING_FOG],
            'colemanliauindex' => [METADATAEXTRACTOR_READABLE_COLEMAN_LIAU],
            'smogindex' => [METADATAEXTRACTOR_READABLE_SMOG_INDEX],
            'automatedreadabilityindex' => [METADATAEXTRACTOR_READABLE_AUTOMATED_READABILITY],
            'dalechallreadabilityscore' => [METADATAEXTRACTOR_READABLE_DC_READABILITY],
            'dalechalldifficultwordcount' => [METADATAEXTRACTOR_READABLE_DC_DIFFICULT_WORDCOUNT],
            'spachereadabilityscore' => [METADATAEXTRACTOR_READABLE_SPACHE_READABILITY],
            'spachedifficultwordcount' => [METADATAEXTRACTOR_READABLE_SPACHE_DIFFICULT_WORDCOUNT],
            'wordcount' => [METADATAEXTRACTOR_READABLE_WORDCOUNT],
            'averagewordspersentence' => [METADATAEXTRACTOR_READABLE_WORDS_PER_SENTENCE],
            'readingtime' => [METADATAEXTRACTOR_READABLE_READING_TIME],
        ];
    }
}
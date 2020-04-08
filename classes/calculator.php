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
 * Class for calculating various readability scores.
 *
 * @package    metadataextractor_readability
 * @copyright  2020 Tom Dickman <tomdickman@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace metadataextractor_readability;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/admin/tool/metadata/extractor/readability/vendor/autoload.php');
require_once($CFG->dirroot . '/admin/tool/metadata/extractor/readability/constants.php');


/**
 * Class for calculating various readability scores.
 *
 * @package    metadataextractor_readability
 * @copyright  2020 Tom Dickman <tomdickman@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class calculator {

    /**
     * @var \DaveChild\TextStatistics\TextStatistics instance to calculate scores from.
     */
    protected $textstatistics;

    /**
     * calculator constructor.
     */
    public function __construct() {
        $this->textstatistics = new \DaveChild\TextStatistics\TextStatistics();
    }

    /**
     * Get the average reading speed to use in calculations.
     *
     * @return int $averagespeed the average reading speed in words per minute.
     */
    public function get_average_reading_speed() {
        $config = get_config('metadataextractor_readability', 'average_reading_speed');
        if (empty($config) || $config < 0) {
            $averagespeed = METADATAEXTRACTOR_READABILITY_DEFAULT_READING_SPEED;
        } else {
            $averagespeed = $config;
        }

        return $averagespeed;
    }

    /**
     * Calculate reading time for a text string.
     *
     * @param string $text the text to be measured.
     *
     * @return string $formattedtime a formatted reading time HH:MM:SS
     */
    public function calculate_reading_time(string $text) : string {

        $decimaltime = $this->textstatistics->wordCount($text) / $this->get_average_reading_speed();
        $hours = floor($decimaltime / 60);
        $minutes = floor($decimaltime % 60);
        $seconds = ($decimaltime - floor($decimaltime)) * 60;

        $formattedtime = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);

        return $formattedtime;
    }

    /**
     * Calculate test scores.
     *
     * @param string $text the text to calculate scores for.
     *
     * @return array string[] of calculated readability scores.
     */
    public function calculate_scores(string $text) : array{
        $results = [];

        $results[METADATAEXTRACTOR_READABILITY_FK_READING_EASE] = $this->textstatistics->fleschKincaidReadingEase($text);
        $results[METADATAEXTRACTOR_READABILITY_FK_GRADE_LEVEL] = $this->textstatistics->fleschKincaidGradeLevel($text);
        $results[METADATAEXTRACTOR_READABILITY_GUNNING_FOG] = $this->textstatistics->gunningFogScore($text);
        $results[METADATAEXTRACTOR_READABILITY_COLEMAN_LIAU] = $this->textstatistics->colemanLiauIndex($text);
        $results[METADATAEXTRACTOR_READABILITY_SMOG_INDEX] = $this->textstatistics->smogIndex($text);
        $results[METADATAEXTRACTOR_READABILITY_AUTOMATED_READABILITY] = $this->textstatistics->automatedReadabilityIndex($text);
        $results[METADATAEXTRACTOR_READABILITY_DC_READABILITY] = $this->textstatistics->daleChallReadabilityScore($text);
        $results[METADATAEXTRACTOR_READABILITY_DC_DIFFICULT_WORDCOUNT] = $this->textstatistics->daleChallDifficultWordCount($text);
        $results[METADATAEXTRACTOR_READABILITY_SPACHE_READABILITY] = $this->textstatistics->spacheReadabilityScore($text);
        $results[METADATAEXTRACTOR_READABILITY_SPACHE_DIFFICULT_WORDCOUNT] = $this->textstatistics->spacheDifficultWordCount($text);
        $results[METADATAEXTRACTOR_READABILITY_WORDCOUNT] = $this->textstatistics->wordCount($text);
        $results[METADATAEXTRACTOR_READABILITY_WORDS_PER_SENTENCE] = round($this->textstatistics->averageWordsPerSentence($text), 2);
        $results[METADATAEXTRACTOR_READABILITY_READING_TIME] = $this->calculate_reading_time($text);

        return $results;
    }

}
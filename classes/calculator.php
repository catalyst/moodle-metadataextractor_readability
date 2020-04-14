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
 * @package    metadataextractor_readable
 * @copyright  2020 Tom Dickman <tomdickman@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace metadataextractor_readable;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/admin/tool/metadata/extractor/readable/vendor/autoload.php');
require_once($CFG->dirroot . '/admin/tool/metadata/extractor/readable/constants.php');


/**
 * Class for calculating various readability scores.
 *
 * @package    metadataextractor_readable
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
    public function __construct(string $encoding = 'UTF-8') {
        $this->textstatistics = new \DaveChild\TextStatistics\TextStatistics($encoding);
    }

    /**
     * Get the average reading speed to use in calculations.
     *
     * @return int $averagespeed the average reading speed in words per minute.
     */
    public function get_average_reading_speed() {
        $config = get_config('metadataextractor_readable', 'average_reading_speed');
        if (empty($config) || $config < 0) {
            $averagespeed = METADATAEXTRACTOR_READABLE_DEFAULT_READING_SPEED;
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
     * @return int $seconds reading time in seconds.
     */
    public function calculate_reading_time(string $text) : int {

        $minutes = $this->textstatistics->wordCount($text) / $this->get_average_reading_speed();
        $seconds = (int) floor($minutes * 60);

        return $seconds;
    }

    /**
     * Clean up text which may cause issues for calculations,
     * removing tabs and double spaces between words.
     *
     * @param string $text the text string to clean for calculation.
     *
     * @return string $text the cleaned text.
     */
    protected function clean_text_for_calculation(string $text) {
        // Patterns in order of replacement priority.
        $patterns = [
            '\t' => ' ', // tabs.
            '\s{2,}' => ' ', // multiple whitspaces.
        ];

        foreach ($patterns as $pattern => $replacement) {
            $text = preg_replace("/$pattern/", $replacement, $text);
        }

        return $text;
    }

    /**
     * Get time in seconds as formatted string of HH:MM:SS.
     *
     * @param int $time the time to format in seconds.
     *
     * @return string a formatted string of hours, minutes and seconds separated by colons - 'HH:MM:SS'.
     */
    public function format_time(int $time) {
        $hours = floor($time / 3600);
        $minutes = floor(($time % 3600) / 60);
        $seconds = ($time - (3600 * $hours) - (60 * $minutes));

        $formattedtime = sprintf("%d:%02d:%02d", $hours, $minutes, $seconds);

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

        $cleantext = $this->clean_text_for_calculation($text);
        $results[METADATAEXTRACTOR_READABLE_FK_READING_EASE] = $this->textstatistics->fleschKincaidReadingEase($cleantext);
        $results[METADATAEXTRACTOR_READABLE_FK_GRADE_LEVEL] = $this->textstatistics->fleschKincaidGradeLevel($cleantext);
        $results[METADATAEXTRACTOR_READABLE_GUNNING_FOG] = $this->textstatistics->gunningFogScore($cleantext);
        $results[METADATAEXTRACTOR_READABLE_COLEMAN_LIAU] = $this->textstatistics->colemanLiauIndex($cleantext);
        $results[METADATAEXTRACTOR_READABLE_SMOG_INDEX] = $this->textstatistics->smogIndex($cleantext);
        $results[METADATAEXTRACTOR_READABLE_AUTOMATED_READABILITY] = $this->textstatistics->automatedReadabilityIndex($cleantext);
        $results[METADATAEXTRACTOR_READABLE_DC_READABILITY] = $this->textstatistics->daleChallReadabilityScore($cleantext);
        $results[METADATAEXTRACTOR_READABLE_DC_DIFFICULT_WORDCOUNT] = $this->textstatistics->daleChallDifficultWordCount($cleantext);
        $results[METADATAEXTRACTOR_READABLE_SPACHE_READABILITY] = $this->textstatistics->spacheReadabilityScore($cleantext);
        $results[METADATAEXTRACTOR_READABLE_SPACHE_DIFFICULT_WORDCOUNT] = $this->textstatistics->spacheDifficultWordCount($cleantext);
        $results[METADATAEXTRACTOR_READABLE_WORDCOUNT] = $this->textstatistics->wordCount($cleantext);
        $results[METADATAEXTRACTOR_READABLE_WORDS_PER_SENTENCE] = round($this->textstatistics->averageWordsPerSentence($cleantext), 1);
        $results[METADATAEXTRACTOR_READABLE_READING_TIME] = $this->calculate_reading_time($cleantext);

        return $results;
    }

}
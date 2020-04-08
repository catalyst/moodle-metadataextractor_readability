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
 * Definition of constants for metadataextractor_readable.
 *
 * @package    metadataextractor_readable
 * @copyright  2020 Tom Dickman <tomdickman@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * METADATAEXTRACTOR_READABLE_DEFAULT_READING_SPEED - The default reading speed to use when making
 * readability calculations if a reading speed has not been set.
 *
 * Value derived from:
 * Brysbaert, Marc. 2019. “How Many Words Do We Read Per Minute? A Review and Meta-analysis of Reading Rate.”
 * PsyArXiv. April 12. doi:10.31234/osf.io/xynwg.
 */
define('METADATAEXTRACTOR_READABLE_DEFAULT_READING_SPEED', 238);

/**
 * METADATAEXTRACTOR_READABLE_FK_READING_EASE - Measure of the Flesh Kincaid reading ease of a
 * piece of text. This is the raw score, higher numbers indicate easier to read.
 *
 * https://en.wikipedia.org/wiki/Flesch%E2%80%93Kincaid_readability_tests
 */
define('METADATAEXTRACTOR_READABLE_FK_READING_EASE', 'fleschkincaidreadingease');

/**
 * METADATAEXTRACTOR_READABLE_FK_GRADE_LEVEL - The grade level to which the text score can be
 * interpreted as applying to, aligned to US grade level, maximum value is 12 indicating
 * year 12 student or above.
 */
define('METADATAEXTRACTOR_READABLE_FK_GRADE_LEVEL', 'fleschkincaidgradelevel');

/**
 * METADATAEXTRACTOR_READABLE_GUNNING_FOG - Measure of the text against the Gunning Fog index
 * for readability, score based on US grade levels, maximum 19.
 *
 * https://en.wikipedia.org/wiki/Gunning_fog_index
 */
define('METADATAEXTRACTOR_READABLE_GUNNING_FOG', 'gunningfogscore');

/**
 * METADATAEXTRACTOR_READABLE_COLEMAN_LIAU - Measure of the text against the Coleman Liau index
 * for readability, score based on US grade levels, maximum 12.
 *
 * https://en.wikipedia.org/wiki/Coleman%E2%80%93Liau_index
 */
define('METADATAEXTRACTOR_READABLE_COLEMAN_LIAU', 'colemanliauindex');

/**
 * METADATAEXTRACTOR_READABLE_SMOG_INDEX - Measure of the text against the Simple Measure of Gobbledygook
 * (SMOG) index, a more simplified version of Gunning Fog index, used for health messages.
 *
 * https://en.wikipedia.org/wiki/SMOG
 */
define('METADATAEXTRACTOR_READABLE_SMOG_INDEX', 'smogindex');

/**
 * METADATAEXTRACTOR_READABLE_AUTOMATED_READABILITY- Measure of the text against the Automated Readability index
 * for readability, score based on US grade levels, maximum 12.
 *
 * https://en.wikipedia.org/wiki/Automated_readability_index
 */
define('METADATAEXTRACTOR_READABLE_AUTOMATED_READABILITY', 'automatedreadabilityindex');

/**
 * METADATAEXTRACTOR_READABLE_DC_READABILITY - Measure of the text against the Dale Chall Readability formula
 * for readability, uses a list of 3000 words that groups of fourth-grade US students should reliably understand,
 * considering any word not on that list to be difficult, maximum 10.
 *
 * https://en.wikipedia.org/wiki/Dale%E2%80%93Chall_readability_formula
 */
define('METADATAEXTRACTOR_READABLE_DC_READABILITY', 'dalechallreadabilityscore');

/**
 * METADATAEXTRACTOR_READABLE_DC_DIFFICULT_WORDCOUNT - A count of the Dale Chall difficult words found in the
 * text. Dale Chall difficult words are any words not on a list of 3000 words that groups of fourth-grade US students
 * should reliably understand.
 */
define('METADATAEXTRACTOR_READABLE_DC_DIFFICULT_WORDCOUNT', 'dalechalldifficultwordcount');

/**
 * METADATAEXTRACTOR_READABLE_SPACHE_READABILITY - Measure of the text against the Spache Readability formula
 * for readability, uses a set list of everyday words which are considered readable for young children, mostly designed
 * for primary school education only, maximum 5.
 *
 * https://en.wikipedia.org/wiki/Spache_readability_formula
 */
define('METADATAEXTRACTOR_READABLE_SPACHE_READABILITY', 'spachereadabilityscore');

/**
 * METADATAEXTRACTOR_READABLE_SPACHE_DIFFICULT_WORDCOUNT - A count of the Spache difficult words found in the
 * text. Spache difficult words are any words not on a set list of everyday words that groups of primary students
 * should reliably understand.
 */
define('METADATAEXTRACTOR_READABLE_SPACHE_DIFFICULT_WORDCOUNT', 'spachedifficultwordcount');

/**
 * METADATAEXTRACTOR_READABLE_WORDCOUNT - Simple wordcount of text.
 */
define('METADATAEXTRACTOR_READABLE_WORDCOUNT', 'wordcount');

/**
 * METADATAEXTRACTOR_READABLE_WORDS_PER_SENTENCE - Average words per sentence of a text.
 */
define('METADATAEXTRACTOR_READABLE_WORDS_PER_SENTENCE', 'averagewordspersentence');

/**
 * METADATAEXTRACTOR_READABLE_READING_TIME - Calculated reading time for a text.
 */
define('METADATAEXTRACTOR_READABLE_READING_TIME', 'readingtime');


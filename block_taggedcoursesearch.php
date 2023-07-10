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
 * Block taggedcoursesearch is defined here.
 *
 * @package     block_taggedcoursesearch
 * @copyright   2018 Arnaud Trouvé <arnaud.trouve@andil.fr>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
/**
 * taggedcoursesearch block.
 *
 * @package    block_taggedcoursesearch
 * @copyright  2018 Arnaud Trouvé <arnaud.trouve@andil.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_taggedcoursesearch extends block_base {

    /**
     * Initializes class member variables.
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_taggedcoursesearch');
    }

    public function instance_allow_multiple() {
        return true;
    }

    public function applicable_formats() {
        return array('all' => true);
    }

    public function instance_allow_config() {
        return true;
    }

    /**
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content() {

        if (isset($this->content)) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        $action = $this->page->url;
        $action->set_anchor('block-taggedcoursesearch-searchform');
        $searchform = new \block_taggedcoursesearch\form\search_form($action->out(false));
        $renderable = new \block_taggedcoursesearch\output\main($searchform);
        $renderer = $this->page->get_renderer('block_taggedcoursesearch');
        $this->content->text = $renderer->render($renderable);
        return $this->content;
    }

    public function specialization() {

        // Load userdefined title and make sure it's never empty.
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_taggedcoursesearch');
        } else {
            $this->title = format_string($this->config->title, true, ['context' => $this->context]);
        }
    }


    public function can_block_be_added(moodle_page $page): bool {
        global $CFG;

        return $CFG->usetags;
    }

    public function get_config_for_external() {
        // Return all settings for all users since it is safe (no private keys, etc..).
        $configs = !empty($this->config) ? $this->config : new stdClass();

        return (object) [
            'instance' => $configs,
            'plugin' => new stdClass(),
        ];
    }
}

<?php
/**
 * Define all the restore steps that will be used by the restore_opendesktop_activity_task
 *
 * @package    mod_opendesktop
 * @copyright  2013 David Bogner {@link http://www.edulabs.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Structure step to restore one opendesktop activity
 */
class restore_opendesktop_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {

        $paths = array();

        $paths[] = new restore_path_element('opendesktop', '/activity/opendesktop');

        // Return the paths wrapped into standard activity structure
        return $this->prepare_activity_structure($paths);
    }

    /**
     * Process opendesktop tag information
     * @param array $data information
     */
    protected function process_opendesktop($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        $newitemid = $DB->insert_record('opendesktop', $data);
        $this->apply_activity_instance($newitemid);
    }

    /**
     * Process chapter tag information
     * @param array $data information
     */
    protected function process_opendesktop_chapter($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();
        $data->opendesktopid = $this->get_new_parentid('opendesktop');

    }

    protected function after_execute() {
        global $DB;

        // Add opendesktop related files
        $this->add_related_files('mod_opendesktop', 'intro', null);
    }
}

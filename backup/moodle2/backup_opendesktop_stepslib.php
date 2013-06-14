<?php
/**
 * Define all the backup steps that will be used by the backup_opendesktop_activity_task
 *
 * @package    mod_opendesktop
 * @copyright  2010 David Bogner {@link http://www.edulabs.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Structure step to backup one opendesktop activity
 */
class backup_opendesktop_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {

        // Define each element separated
        $opendesktop = new backup_nested_element('opendesktop', array('id'), array('name', 'intro', 'introformat','timecreated', 'timemodified', 'languagesetting', 'desktopsize', 'quality'));

        // Define sources
        $opendesktop->set_source_table('opendesktop', array('id' => backup::VAR_ACTIVITYID));

        // Define file annotations
        $opendesktop->annotate_files('mod_opendesktop', 'intro', null); // This file area hasn't itemid
 
        // Return the root element (opendesktop), wrapped into standard activity structure
        return $this->prepare_activity_structure($opendesktop);
    }
}

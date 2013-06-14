<?php
/**
 * Description of opendesktop restore task
 *
 * @package    mod_opendesktop
 * @copyright  2010 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/opendesktop/backup/moodle2/restore_opendesktop_stepslib.php'); // Because it exists (must)

class restore_opendesktop_activity_task extends restore_activity_task {

    /**
     * Define (add) particular settings this activity can have
     *
     * @return void
     */
    protected function define_my_settings() {
        // No particular settings for this activity
    }

    /**
     * Define (add) particular steps this activity can have
     *
     * @return void
     */
    protected function define_my_steps() {
        // Choice only has one structure step
        $this->add_step(new restore_opendesktop_activity_structure_step('opendesktop_structure', 'opendesktop.xml'));
    }

    /**
     * Define the contents in the activity that must be
     * processed by the link decoder
     *
     * @return array
     */
    static public function define_decode_contents() {
        $contents = array();

        $contents[] = new restore_decode_content('opendesktop', array('intro'), 'opendesktop');

        return $contents;
    }

    /**
     * Define the decoding rules for links belonging
     * to the activity to be executed by the link decoder
     *
     * @return array
     */
    static public function define_decode_rules() {
        $rules = array();

        // List of opendesktops in course
        $rules[] = new restore_decode_rule('OPENDESKTOPINDEX', '/mod/opendesktop/index.php?id=$1', 'course');

        // opendesktop by cm->id
        $rules[] = new restore_decode_rule('OPENDESKTOPVIEWBYID', '/mod/opendesktop/view.php?id=$1', 'course_module');

        // opendesktop by opendesktop->id
        $rules[] = new restore_decode_rule('OPENDESKTOPVIEWBYB', '/mod/opendesktop/view.php?b=$1', 'opendesktop');

        // Convert old opendesktop links MDL-33362 & MDL-35007
        $rules[] = new restore_decode_rule('OPENDESKTOPSTART', '/mod/opendesktop/view.php?id=$1', 'course_module');

        return $rules;
    }

    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * opendesktop logs. It must return one array
     * of {@link restore_log_rule} objects
     *
     * @return array
     */
    static public function define_restore_log_rules() {
        $rules = array();

        $rules[] = new restore_log_rule('opendesktop', 'add', 'view.php?id={course_module}', '{opendesktop}');
        $rules[] = new restore_log_rule('opendesktop', 'update', 'view.php?id={course_module}', '{opendesktop}');
        $rules[] = new restore_log_rule('opendesktop', 'view', 'view.php?id={course_module}', '{opendesktop}');
       // To convert old 'generateimscp' log entries
        return $rules;
    }

    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * course logs. It must return one array
     * of {@link restore_log_rule} objects
     *
     * Note this rules are applied when restoring course logs
     * by the restore final task, but are defined here at
     * activity level. All them are rules not linked to any module instance (cmid = 0)
     *
     * @return array
     */
    static public function define_restore_log_rules_for_course() {
        $rules = array();

        $rules[] = new restore_log_rule('opendesktop', 'view all', 'index.php?id={course}', null);

        return $rules;
    }
}

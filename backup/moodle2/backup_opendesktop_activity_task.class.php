<?php

/**
 * Description of opendesktop backup task
 *
 * @package    mod_opendesktop
 * @copyright  2010-2013 David Bogner {@link http://www.edulabs.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot.'/mod/opendesktop/backup/moodle2/backup_opendesktop_stepslib.php');    // Because it exists (must)
require_once($CFG->dirroot.'/mod/opendesktop/backup/moodle2/backup_opendesktop_settingslib.php'); // Because it exists (optional)

class backup_opendesktop_activity_task extends backup_activity_task {

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
        // opendesktop only has one structure step
        $this->add_step(new backup_opendesktop_activity_structure_step('opendesktop_structure', 'opendesktop.xml'));
    }

    /**
     * Code the transformations to perform in the activity in
     * order to get transportable (encoded) links
     *
     * @param string $content
     * @return string encoded content
     */
    static public function encode_content_links($content) {
        global $CFG;

        $base = preg_quote($CFG->wwwroot, "/");

        // Link to the list of opendesktops
        $search  = "/($base\/mod\/opendesktop\/index.php\?id=)([0-9]+)/";
        $content = preg_replace($search, '$@OPENDESKTOPINDEX*$2@$', $content);

        // Link to opendesktop view by moduleid
        $search  = "/($base\/mod\/opendesktop\/view.php\?id=)([0-9]+)(&|&amp;)chapterid=([0-9]+)/";
        $content = preg_replace($search, '$@OPENDESKTOPVIEWBYIDCH*$2*$4@$', $content);

        $search  = "/($base\/mod\/opendesktop\/view.php\?id=)([0-9]+)/";
        $content = preg_replace($search, '$@OPENDESKTOPVIEWBYID*$2@$', $content);

        // Link to opendesktop view by opendesktopid
        $search  = "/($base\/mod\/opendesktop\/view.php\?b=)([0-9]+)(&|&amp;)chapterid=([0-9]+)/";
        $content = preg_replace($search, '$@OPENDESKTOPVIEWBYBCH*$2*$4@$', $content);

        $search  = "/($base\/mod\/opendesktop\/view.php\?b=)([0-9]+)/";
        $content = preg_replace($search, '$@OPENDESKTOPVIEWBYB*$2@$', $content);

        return $content;
    }
}

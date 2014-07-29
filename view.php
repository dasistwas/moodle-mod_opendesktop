<?php  
/**
 * This page prints a particular instance of opendesktop
 *
 * @author  David Bogner davidbogner@gmail.com
 * @package mod/opendesktop
 */


require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once($CFG->dirroot . '/mod/opendesktop/lib.php');
require_once($CFG->dirroot . '/mod/opendesktop/locallib.php');
require_once($CFG->dirroot . '/mod/opendesktop/curl.class.extended.php');

$id = required_param('id', PARAM_INT); // course_module ID, or
$task  = optional_param('task', '', PARAM_ALPHA);  // 
$token = optional_param('token', '', PARAM_ALPHANUM);
$sessionmode = optional_param('sessionmode', '', PARAM_ALPHA);
$join = optional_param('join', '', PARAM_ALPHA);
$ovduser = optional_param('ovduser', '', PARAM_INT);
$sesskey = optional_param('sesskey', '', PARAM_ALPHANUMEXT);
//for testing, otherwise $task = 'startoptions'
if(!$task){
	$task = "startoptions";
}

$cm = get_coursemodule_from_id('opendesktop', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
$opendesktop = $DB->get_record('opendesktop', array('id' => $cm->instance),'*', MUST_EXIST);
$opendesktop->windowmode = "frame";

require_login($course, true, $cm);

add_to_log($course->id, "opendesktop", "view", "view.php?id=$cm->id", "$opendesktop->id");

/// Print the page header
$context = context_module::instance($cm->id);
$PAGE->set_context($context);

//TODO! capabilities tests
//require_capability('mod/opendesktop:view', $context);

$url = new moodle_url('/mod/opendesktop/view.php', array('id'=>$id));
$PAGE->set_url($url);
$PAGE->set_title(get_string('modulename', 'opendesktop'));
$PAGE->set_heading($course->fullname);

$openview = new opendesktop($context, $cm, $course, $opendesktop);

$params = array('task'=>$task,'token' => $token,'join' => $join,'ovduser' => $ovduser,'sessionmode' => $sessionmode);
$openview->view($params);

?>

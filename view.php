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

$context = get_context_instance(CONTEXT_MODULE, $cm->id);

/// Print the page header
$context = context_module::instance($cm->id);
$PAGE->set_context($context);

//require_capability('mod/opendesktop:view', $context);

$url = new moodle_url('/mod/opendesktop/view.php', array('id'=>$id));
$PAGE->set_url($url);
$PAGE->set_title(get_string('modulename', 'opendesktop'));

$openview = new opendesktop($context, $cm, $course, $opendesktop);

$params = array('task'=>$task,'token' => $token,'join' => $join,'ovduser' => $ovduser,'sessionmode' => $sessionmode);
$openview->view($params);


/// Print the main part of the page

/*
else if (has_capability('mod/opendesktop:start', $context) && $task != 'join'){
	echo $OUTPUT->header();
	//check if chat exists in course
	if ($chats = $DB->get_records('chat', array('course' => $course->id))) {
	$errormessage = "no";
		foreach ($chats as $chat) {
			if($chat->chattime > time() && $chat->schedule != 0){
				$errormessage = "You have to set up a chat activity in this course in order to use it. Pease set up a chat with the standard settings.";
			}
			else {
				$chatid = $chat->id;
				break;
			}
		}
	}
	else {
		$errormessage =  "Please set up a chat activity in this course. Otherwise you can not use chat.";
	}
	echo '<div id="status" style="text-align: right; position: absolute; top: 0; left: 0; margin: 0; padding: 0; width: 800px; font-size: 12px; color: #555555;">Desktop ist starting...</div>';
	echo '<div style="margin: 0;"><iframe width=800 height=600 src="'.$_SERVER['PHP_SELF'].'?id='.$cm->id.'&task=printlogin&randpass='.$randpass.'"></iframe></div>';
	if ($errormessage == "no") {
		echo'<div style="margin: 0;"><iframe width="800" height="150" src="'.$CFG->wwwroot.'/mod/chat/gui_header_js/index.php?id='.$chatid.'"></iframe></div>';
	}
	else {
		echo $errormessage;
	}
	//ToDo: pass variables to JavaScript via YUI (but only possible from Moodle 2.5 onwards?
	echo '<div style="display:none" class="openviewregister">'.$_SERVER['PHP_SELF'].'?id='.$cm->id.'&task=register</div>';
}
else if (has_capability('mod/opendesktop:joinactive', $context) OR has_capability('mod/opendesktop:joinpassive', $context) && $task == 'join'){
	echo $OUTPUT->header();
	$chats = $DB->get_records('chat', array('course' => $course->id));
	if (!empty($chats)){
		$errormessage = "no";
		foreach ($chats as $chat) {
			if($chat->chattime > time() && $chat->schedule != 0){
				$errormessage = "Chat is not activated.";
			}
			else {
				$chatid = $chat->id;
				break;
			}
		}
	}
	else {
		$errormessage =  "Chat is not activated.";
	}
	
	echo '</span><iframe width=800 height=595 src="'.urldecode($pathtoappserv).'?token='.$token.'"></iframe></div>';
	//echo'<span style="float:left"><iframe width=800 height=150 src="'.$CFG->wwwroot.'/mod/chat/gui_header_js/index.php?id='.$chatid.'"></span></iframe>';
}
/// Finish the page

echo $OUTPUT->footer();

*/
?>

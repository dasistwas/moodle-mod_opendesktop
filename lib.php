<?php  
/**
 * Library of functions and constants for module opendesktop
 * This file should have two well differenced parts:
 *   - All the core Moodle functions, neeeded to allow
 *     the module to work integrated in Moodle.
 *   - All the opendesktop specific functions, needed
 *     to implement all the module logic. Please, note
 *     that, if the module become complex and this lib
 *     grows a lot, it's HIGHLY recommended to move all
 *     these module specific functions to a new php file,
 *     called "locallib.php" (see forum, quiz...). This will
 *     help to save some memory when Moodle is performing
 *     actions across all modules.
 */

$CFG->opendesktoproot = "$CFG->dirroot/mod/opendesktop";

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object $opendesktop An object from the form in mod_form.php
 * @return int The id of the newly inserted opendesktop record
 */
function opendesktop_add_instance($opendesktop) {
	global $DB;
	$opendesktop->timecreated = time();

	# You may have to add extra stuff in here #

	return $DB->insert_record('opendesktop', $opendesktop);
}


/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param object $opendesktop An object from the form in mod_form.php
 * @return boolean Success/Fail
 */
function opendesktop_update_instance($opendesktop) {
	global $DB;
	$opendesktop->timemodified = time();
	$opendesktop->id = $opendesktop->instance;

	# You may have to add extra stuff in here #

	return $DB->update_record('opendesktop', $opendesktop);
}


/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function opendesktop_delete_instance($id) {
	global $DB;
	if (! $opendesktop = $DB->get_record('opendesktop', array('id' => $id))) {
		return false;
	}

	$result = true;

	# Delete any dependent records here #

	if (! $DB->delete_records('opendesktop', array('id' => $opendesktop->id))) {
		$result = false;
	}

	return $result;
}

function opendesktop_supports($feature) {
	switch($feature) {
		case FEATURE_GROUPS:                  return false;
		case FEATURE_GROUPINGS:               return false;
		case FEATURE_GROUPMEMBERSONLY:        return false;
		case FEATURE_MOD_INTRO:               return true;
		case FEATURE_COMPLETION_TRACKS_VIEWS: return false;
		case FEATURE_COMPLETION_HAS_RULES:    return false;
		case FEATURE_GRADE_HAS_GRADE:         return false;
		case FEATURE_GRADE_OUTCOMES:          return false;
		case FEATURE_BACKUP_MOODLE2:          return true;

		default: return null;
	}
}

/**
 * Return a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return null
 * @todo Finish documenting this function
 */
function opendesktop_user_outline($course, $user, $mod, $opendesktop) {
	return $return;
}


/**
 * Print a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @return boolean
 * @todo Finish documenting this function
 */
function opendesktop_user_complete($course, $user, $mod, $opendesktop) {
	return true;
}


/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in opendesktop activities and print it out.
 * Return true if there was output, or false is there was none.
 *
 * @return boolean
 * @todo Finish documenting this function
 */
function opendesktop_print_recent_activity($course, $isteacher, $timestart) {
	return false;  //  True if anything was printed, otherwise false
}


/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * @return boolean
 * @todo Finish documenting this function
 **/
function opendesktop_cron () {
	return true;
}


/**
 * Must return an array of user records (all data) who are participants
 * for a given instance of opendesktop. Must include every user involved
 * in the instance, independient of his role (student, teacher, admin...)
 * See other modules as example.
 *
 * @param int $opendesktopid ID of an instance of this module
 * @return mixed boolean/array of students
 */
function opendesktop_get_participants($opendesktopid) {
	return false;
}


/**
 * This function returns if a scale is being used by one opendesktop
 * if it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $opendesktopid ID of an instance of this module
 * @return mixed
 * @todo Finish documenting this function
 */
function opendesktop_scale_used($opendesktopid, $scaleid) {
	$return = false;

	//$rec = get_record("opendesktop","id","$opendesktopid","scale","-$scaleid");
	//
	//if (!empty($rec) && !empty($scaleid)) {
	//    $return = true;
	//}

	return $return;
}


/**
 * Checks if scale is being used by any instance of opendesktop.
 * This function was added in 1.9
 *
 * This is used to find out if scale used anywhere
 * @param $scaleid int
 * @return boolean True if the scale is used by any opendesktop
 */
function opendesktop_scale_used_anywhere($scaleid) {
	global $DB;
	if ($scaleid and $DB->record_exists('opendesktop', array('grade' => -$scaleid))) {
		return true;
	} else {
		return false;
	}
}


/**
 * Execute post-install custom actions for the module
 * This function was added in 1.9
 *
 * @return boolean true if success, false on error
 */
function opendesktop_install() {
	return true;
}


/**
 * Execute post-uninstall custom actions for the module
 * This function was added in 1.9
 *
 * @return boolean true if success, false on error
 */
function opendesktop_uninstall() {
	return true;
}


//////////////////////////////////////////////////////////////////////////////////////
/// Any other opendesktop functions go here.  Each of them must have a name that
/// starts with opendesktop_
/// Remember (see note in first lines) that, if this section grows, it's HIGHLY
/// recommended to move all funcions below to a new "localib.php" file.

function opendesktop_str_rand($length = 8, $seeds = 'alphanum') {
	$seedings = array('alpha'=>'abcdefghijklmnopqrstuvwqyz','numeric'=>'0123456789', 'alphanum'=>'abcdefghijklmnopqrstuvwqyz0123456789','hexidec'=>'0123456789abcdef');

	// Choose seed
	if (isset($seedings[$seeds])) {
		$seeds = $seedings[$seeds];
	}

	// Seed generator
	list($usec, $sec) = explode(' ', microtime());
	$seed = (float) $sec + ((float) $usec * 100000);
	mt_srand($seed);

	// Generate
	$str = '';
	$seeds_count = strlen($seeds);

	for ($i = 0; $length > $i; $i++) {
		$str .= $seeds{mt_rand(0, $seeds_count - 1)};
	}
	return $str;
}

function opendesktop_delete_oldsessions() {
	global $DB;
	$timeexpired = time() - 129600;
	$sql = 'starttime <= '.$timeexpired;
	$DB->delete_records_select('opendesktop_sessions', $sql);
}


function opendesktop_generatelogin($loginparams){
	global $USER, $DB;
	if ($loginparams->windowmode != "frame"){
		$loginhtml = '<form class="opendesktopconnect" onmousedown="this.target=\'_blank\'" action="'.$loginparams->sessurl.'/startsession.php" method="post"><div style="float: left;">';
		$loginhtml .= '<input type="hidden" id="opendesktopdestination" value="'.$loginparams->destination.'" />';
		$loginhtml .= '<input type="hidden" id="opendesktopnextdest" value="'.$loginparams->nextdest.'" />';
		$loginhtml .= '<input type="hidden" name="client" value="browser" />';
		$loginhtml .= '<input type="hidden" id="quality" name="quality" value="'.$loginparams->quality.'" />';
		$loginhtml .= '<input type="hidden" id="timezone" name="timezone" value="" />';
		$loginhtml .= '<input type="hidden" name="login" value="'.$loginparams->ovdusername.'" />';
		$loginhtml .= '<input type="hidden" id="passwd" name="password" value="'.$loginparams->randpass.'" />';
		if ($loginparams->sessmanconfig == false) {
			notice(get_string('connecterror', 'opendesktop'),"../../course/");
		}
		else if ($loginparams->sessmanconfig['session_mode'] == 1 && $loginparams->sessval['status'] != 'suspended') {
			$loginhtml .= get_string('modeexplain', 'opendesktop').'<br />';
			$loginhtml .= 	'<select id="session_mode" name="session_mode">
					<option value="desktop" selected="selected">'.get_string('desktopmode', 'opendesktop').'</option>
					<option value="portal">'.get_string('portalmode', 'opendesktop').'</option>
					</select>
					';
		}
		$loginhtml .= '<input type="hidden" name="language" value="'.$loginparams->lang.'" />';
		$loginhtml .= '<div id="loading_div" style="margin-left: auto; margin-right: 0px; display: inline;">
			<img src="loading.gif" alt="Loading..." title="Loading..." height="25" width="25" /></div>
			<div id="launch_buttons" style="margin-left: auto; margin-right: 0px; display:inline;">
			<input type="button" id="failed_button" style="display:none;" value="ERROR" />
			<span id="java_download" style="display:none;"><a href="http://www.java.com/download/">Click here to install the free Java-Plugin</a></span> 			
			<input id="launch_button" type="submit" value="'.get_string('logintoyourdesktop','opendesktop').'" style="display:none" />
			</div>';
		$loginhtml .= '</div></form>';
		if ($loginparams->windowmode == "presentation"){

			$loginhtml = '<!--[if !IE]> <--><p style="font-weight:bold;">'.get_string('startpresentation', 'opendesktop').':</p>
			<form action="openview.php?id='.$loginparams->id.'" method="post">
					<input type="hidden" id="randpass" name="randpass" value="'.$loginparams->randpass.'"> 
			<div id="launch_buttons" style="margin-left: auto; margin-right: 0px; display:inline;">
			<input type="button" id="failed_button" style="display:none;" value="ERROR" />
			<span id="java_download" style="display:none;"><a href="http://www.java.com/download/">'.get_string('getjava','opendesktop').'</a></span> 			
			<div id="loading_div" style="margin-left: auto; margin-right: 0px; display: inline; float: left;">
			<img src="loading.gif" alt="Loading..." title="Loading..." height="25" width="25" /></div>
			<input type="hidden" id="timezone" name="timezone" value="" />	
			<input id="launch_button" type="submit" value="'.get_string('startyourpresentation','opendesktop').'" style="display:none; float: left;" />
			</div></form>';
			$loginhtml .= '		
			<!--> <![endif]-->
			<!--[if IE]>
			<p style="font-weight:bold;">'.get_string('browserwarning', 'opendesktop').'</p>
			<![endif]-->
			';
			if ($chats = $DB->get_records('chat',array('course' => $loginparams->courseid))) {
			$errormessage = "";
				foreach ($chats as $chat) {
					if($chat->chattime > time()){
						$errormessage = "The published chat times are in the past. Please configure a chat with the standard options.";
					}
					if ($chat->schedule != 0) {
						$errormessage = "The chat in this course is not scheduled. Please configure a chat with the standard options";
					}
				}
			}
			else {
				$errormessage =  '<p style="color:red;">Please set up a chat activity in this course. Otherwise you can not use chat.</p>';
			}
			$loginhtml .= $errormessage;
		}
	}
	else { 
		$loginhtml = '<form name="startform" action="'.$loginparams->sessurl.'/startsession.php" method="post"><input type="hidden" name="client" value="browser" />
		<input type="hidden" id="quality" name="quality" value="'.$loginparams->quality.'" />
		<input type="hidden" id="timezone" name="timezone" value="" />
		<input type="hidden" name="login" value="'.$loginparams->ovdusername.'" />
		<input type="hidden" id="passwd" name="password" value="'.$loginparams->randpass.'" />
		<input type="hidden" name="language" value="'.$loginparams->lang.'" />
		<input type="hidden" id="session_mode" name="session_mode" value="desktop" />
		<input id="launch_button" type="submit" value="'.get_string('logintoyourdesktop','opendesktop').'" style="display:none" />
		</form>';
		$loginhtml .= '<script type="text/javascript"> document.startform.submit();</script>';
	}
	return $loginhtml;
}
?>

<?php  

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once($CFG->dirroot . '/mod/opendesktop/lib.php');
require_once($CFG->dirroot . '/mod/opendesktop/locallib.php');
require_once($CFG->dirroot . '/mod/opendesktop/curl.class.extended.php');

$id = required_param('id', PARAM_INT); // course_module ID, or
$key  = optional_param('key', 0, PARAM_INT);  // opendesktop key to create userpassword
$task  = optional_param('task', '', PARAM_ALPHA);  // opendesktop task
$sess = optional_param('sess', '', PARAM_ALPHANUM);  // this is not the moodle session but the sessioninfo to connect to ovd servers


$cm = get_coursemodule_from_id('opendesktop', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
$opendesktop = $DB->get_record('opendesktop', array('id' => $cm->instance),'*', MUST_EXIST);

require_login($course, true, $cm);
$context = context_module::instance($cm->id);
$PAGE->set_context($context);
//Todo
//require_capability('mod/opendesktop:view', $context);

$url = new moodle_url('/mod/opendesktop/view.php', array('id'=>$id));
$PAGE->set_url($url);

add_to_log($course->id, "opendesktop", "view", "view.php?id=.$cm->id", $opendesktop->id, $cm->id);

$PAGE->set_title(format_string($opendesktop->name));
$PAGE->set_heading($course->fullname);

$usergroup = $CFG->opendesktop_usergroup;
$prefix = $CFG->opendesktop_useprefix;
$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
$new_pattern = array("_", "_", "");
$headersent = false;

$session = new opendesktop_curl_session(array(),$opendesktop->id);
//Todo should be integrated in opendesktop_curl_session class
$cookiefile = $session->cookiefile;

$PAGE->requires->js('/mod/opendesktop/javascript/common.js');
$PAGE->requires->js('/mod/opendesktop/javascript/timezones.js');

if (has_capability('mod/opendesktop:start', $context)) {
	// if task is login then register the OVD session in the course
	if ($task == 'login' && confirm_sesskey()){
		print_box(get_string('desktopisstarting','opendesktop'), 'generalbox', 'info');
		//delete sessions older than 48h
		opendesktop_delete_oldsessions();
		usleep(12000000);
		$getresult = $session->get($session->sessurl."/admin/users.php?action=manage&id=".$CFG->opendesktop_useprefix.$USER->username);
		if (preg_match('/<input type="hidden" name="info" value="([a-zA-Z0-9]+)" \/>/', $getresult, $sessids)) {
			$sessid = $sessids[1];
			$data = new StdClass;
			$data->token = $sessid;
			$data->userid = $USER->id;
			$data->starttime = time();
			$data->opendesktop = $opendesktop->id;
			$DB->insert_record('opendesktop_sessions', $data);
		}
		else {
			notice(get_string('starterror', 'opendesktop'), "$CFG->wwwroot/course/view.php?id=$course->id");
		}
	}

	else if ($task == 'logout' && confirm_sesskey()) {
		$paramskill = array('action'=>'kill','session'=>$sess);
		// kill session of logged in teacher
		$session->post($session->sessurl."/admin/sessions.php", $paramskill, array('CURLOPT_COOKIEFILE'=>$cookiefile));
		//logout from sessman
		$session->logout();
		unset($session);
		redirect($_SERVER['PHP_SELF']."?id=".$cm->id);
	}
	else {
		echo $OUTPUT->header();
		$headersent = true;
		//if OVD is not started then display link to start VD
		if ($opendesktop->windowmode == "presentation"){
			$legend = get_string('mypresentation','opendesktop');
		} else{
			$legend = get_string('myopendesktop','opendesktop');
		}
		echo '<fieldset id="myopendesktop" class="generalbox boxaligncenter boxwidthwide"><legend class="ftoggler" style="font-weight: bold;">'.$legend.'</legend>';


		//prepare login-button
		if($opendesktop->desktopsize == 'auto'){
			$opendesktop->desktopsize = '';
		}
		// generate random password to login the user and not to have to save passwords in database
		$ovdusername = $CFG->opendesktop_useprefix.$USER->username;
		$sessval = $session->sessionstatus($ovdusername);
		$destination = '?sesskey='.sesskey().'&amp;task=login&amp;id='.$cm->id;
		$nextdest = $_SERVER['PHP_SELF']."?id=".$cm->id;
		$redirectionurl = $CFG->wwwroot.'/course/view.php?id='.$course->id;
		$loginparams = new StdClass;
		$loginparams->id = $cm->id;
		$loginparams->courseid = $course->id;
		$loginparams->windowmode = $opendesktop->windowmode;
		$loginparams->desktopsizeoutput = preg_replace('/(\d+)x(\d+)/','$1, $2',$opendesktop->desktopsize);
		$loginparams->destination = $destination;
		$loginparams->nextdest = $nextdest;
		$loginparams->sessurl = $session->sessurl;
		$loginparams->quality = $opendesktop->quality;
		$loginparams->ovdusername = $ovdusername;
		$loginparams->sessmanconfig = $session->get_config();
		$loginparams->sessval = $sessval;
		$loginparams->lang = $opendesktop->languagesetting;
		$loginparams->randpass = opendesktop_str_rand(15, 'alphanum');
		$loginhtml = opendesktop_generatelogin($loginparams);
		$displaylogin = true;
		if($opendesktop->windowmode == 'presentation') {
			if ($roles = get_roles_used_in_context($context, true)) {
				$canstartusers   = get_users_by_capability($context, 'mod/opendesktop:start','u.id,username,firstname,lastname');
			}
			foreach ($canstartusers as $canstartuser) {
				$sql = 'SELECT MAX(starttime) AS starttime FROM {opendesktop_sessions} WHERE userid='.$canstartuser->id;
				$maxstarttime = $DB->get_field_sql($sql);
				if ($USER->username!=$canstartuser->username && $opendesktop->id == $DB->get_field('opendesktop_sessions','opendesktop', array ('userid' => $canstartuser->id, 'starttime' => $maxstarttime))) {
					// check if any user with the capability to start a desktop in this course has started OVD
					$sessvaldetect = $session->sessionstatus($ovdusername);
					if ($sessvaldetect['status'] == 'active') {
						$modid = $DB->get_field('opendesktop_sessions','opendesktop', array('token' => $sessval['token']));
						// check if the opendesktop was started in the same course, if not do not display the possibility to join
						if ($opendesktop->id == $modid) {
							$displaylogin = false;
						}
					}
				}
			}
		}
		// check if the teacher on the moodle-platform has already started VD on sessman
		if ($sessval) {
			if ($sessval['status'] == 'active') {
				// check if session is registered at all
				if (!$opendesktopid = $DB->get_field('opendesktop_sessions','opendesktop', array('token' => $sessval['token']))) {
					print_string('sessionnotregistered', 'opendesktop');
				}
				// check if session is registered in this course and if not display error message
				else if ($COURSE->id != $DB->get_field('opendesktop', 'course', array('id' => $opendesktopid))) {
					print_string('sessionnotregistered', 'opendesktop');
				}
				// session is registered
				else {
					print_string('opendesktoprunning', 'opendesktop');
					echo "<br /><br />";
				}

			}
			else {
				print_string('opendesktopsuspended','opendesktop');
				echo "<br /><br />";
				$params		=	array('action'=>'modify',
						'id'=>$prefix.$USER->username,
						'password'=>$loginparams->randpass,
						'add'=>'Save the modifications');
				$session->post($session->sessurl."/admin/users.php", $params, array('CURLOPT_COOKIEFILE'=>$cookiefile));
				if($displaylogin) {
					echo $loginhtml;
				}
			}
			// if session value exist(means logged in into VD) then display link to end session
			echo '<span style="text-align: right; float: right;">
					<form action="" method="post"><div style="display: inline; float: right;">
					<input type="hidden" name="sesskey" value="'. sesskey() .'" />
							<input name="id" type="hidden" value="'.$cm->id.'" /> <input name="task" type="hidden" value="logout" />
									<input name="sess" type="hidden" value="'.$sessval['token'].'" />
											<input type="submit" value="'.get_string('endnow','opendesktop').'" />
													</div></form></span>';
		}
		else {

			//check if user exists on session manager
			$html_exist = $session->get($session->sessurl."/admin/users.php?action=manage&id=".$prefix.$USER->username);
			if (strstr($html_exist, '<input type="hidden" name="id" value="'.$prefix.$USER->username.'" />')) {
				// check if user is assigned to the usergroup, if not add the user
				if (!strstr($html_exist,'<a href="usersgroup.php?action=manage&id='.$usergroup.'">')) {
					$groupparams = array('action'=>'add','name'=>'User_UserGroup','element'=>$prefix.$USER->username,'group'=>$usergroup,);
					$session->get($session->sessurl."/admin/actions.php", $groupparams, array('CURLOPT_COOKIEFILE'=>$cookiefile,
							'CURLOPT_HEADER'=>1,
							'CURLOPT_REFERER'=>$session->sessurl."/admin/users.php?action=manage&id=".$prefix.$USER->username));
				}
				$params		=	array('action'=>'modify',
						'id'=>$prefix.$USER->username,
						'password'=>$loginparams->randpass,
						'add'=>'Save the modifications');

				$session->post($session->sessurl."/admin/users.php", $params, array('CURLOPT_COOKIEFILE'=>$cookiefile));
				// display link to get into VD
				if($displaylogin) {
					echo $loginhtml;
				}
			}
			else {
				//if user does not exist add new user with random password
				$add = array('login'=>$prefix.$USER->username,'password'=>$loginparams->randpass,'displayname'=>preg_replace($old_pattern, $new_pattern , $USER->firstname).' '.preg_replace($old_pattern, $new_pattern , $USER->lastname),'uid'=>'','action'=>'add','add'=>'Add');
				$session->post($session->sessurl."/admin/users.php", $add, array('CURLOPT_COOKIEFILE'=>$cookiefile,'CURLOPT_HEADER'=>1));

				//add user to usergroup
				$session->get($session->sessurl."/admin/users.php?action=manage&id=".$prefix.$USER->username,array('CURLOPT_COOKIEFILE'=>$cookiefile));
				$groupparams = array('action'=>'add','name'=>'User_UserGroup','group'=>$usergroup,'element'=>$prefix.$USER->username);
				$session->get($session->sessurl."/admin/actions.php", $groupparams, array('CURLOPT_COOKIEFILE'=>$cookiefile,
						'CURLOPT_HEADER'=>1,
						'CURLOPT_REFERER'=>$session->sessurl."/admin/users.php?action=manage&id=".$prefix.$USER->username));
				//check if presentation is already started in presentatin mode
				if($displaylogin) {
					echo $loginhtml;
				}
			}
		}
	}
	echo'</fieldset>';
}
if (has_capability('mod/opendesktop:joinactive', $context) OR has_capability('mod/opendesktop:joinpassive', $context)) {
	// get all the people with rights to start desktop
	if(!$headersent){
		echo $OUTPUT->header();
	}
	if ($roles = get_roles_used_in_context($context, true)) {
		$canstartusers = get_users_by_capability($context, 'mod/opendesktop:start','u.id,username,firstname,lastname');
	}
	if ($opendesktop->windowmode == "presentation"){
		$legendjoin = get_string('joinpresentation','opendesktop');
	} else{
		$legendjoin = get_string('desktopstojoin','opendesktop');
	}
	echo ('<fieldset class="generalbox boxaligncenter boxwidthwide"><legend class="ftoggler" style="font-weight: bold;">'.$legendjoin.'</legend>');

	foreach ($canstartusers as $canstartuser) {
		//prevent users from joining own Desktop
		$sql = 'SELECT MAX(starttime) AS starttime FROM {opendesktop_sessions} WHERE userid='.$canstartuser->id;
		$maxstarttime = $DB->get_field_sql($sql);
		if ($USER->username!=$canstartuser->username && $opendesktop->id == $DB->get_field('opendesktop_sessions','opendesktop', array ('userid' => $canstartuser->id, 'starttime' => $maxstarttime))) {
			// check if any user with the capability to start a desktop in this course has started OVD
			$sessval = $session->sessionstatus($prefix.$canstartuser->username);
			if ($sessval['status'] == 'active') {
				$modid = $DB->get_field('opendesktop_sessions','opendesktop', array('token' => $sessval['token']));
				$urlchunks = Array();
				$td = '';
				// check if the opendesktop was started in the same course, if not do not display the possibility to join
				if ($opendesktop->id == $modid) {
					echo '<div style="clear: left; margin: 0; padding: 5px; font-weight: bold;">'.get_string('joindesktop','opendesktop').' '.$canstartuser->firstname.' '.$canstartuser->lastname."</div>";
					if (has_capability('mod/opendesktop:joinactive', $context)) {
						$add = array('session_debug_true'=>0,'join'=>$sessval['token'],'active'=>'Join this session');
						$returna = $session->post($session->sessurl."/admin/sessions.php", $add, array('CURLOPT_COOKIEFILE'=>$cookiefile,
								'CURLOPT_HEADER'=>1));
						preg_match('/http.+/', $returna, $inviteurl['active']);
						$urlchunks = parse_url(trim($inviteurl['active'][0]));
						parse_str($urlchunks['query'], $query);
						if ($opendesktop->windowmode != 'presentation'){
							echo '<form action="'.$urlchunks['scheme'].'://'.$urlchunks['host'].$urlchunks['path'].'" method="get" onsubmit="this.target=\'_blank\'"><div style="float: left;">';
							echo '<input type="hidden" name="token" value="'.$query['token'].'" />';
							echo '<input style="margin: 10px;" type="submit" value="'.get_string('joinactive', 'opendesktop').'" />';
							echo '</div></form>';
								
						}
						else {
							$pathtoappserv = urlencode($urlchunks['scheme'].'://'.$urlchunks['host'].$urlchunks['path']);
							echo '<!--[if !IE]> <-->
									<form action="openview.php?id='.$cm->id.'" method="post"><div style="float: left;">
											<input type="hidden" name="token" value="'.$query['token'].'" />
													<input type="hidden" name="task" value="join" />
													<input type="hidden" name="pathtoappserv" value="'.$pathtoappserv.'" />
															<input type="submit" value="'.get_string('joinactive', 'opendesktop').'" />
																	</div></form>
																	<!--> <![endif]-->
																	<!--[if IE]>
																	<p style="font-weight:bold;">'.get_string('browserwarning', 'opendesktop').'</p>
																			<![endif]-->';
						}
					}
					if (has_capability('mod/opendesktop:joinpassive', $context)) {
						$add = array('session_debug_true'=>0,'join'=>$sessval['token'],'passive'=>'Join this session');
						$returnb = $session->post($session->sessurl."/admin/sessions.php", $add, array('CURLOPT_COOKIEFILE'=>$cookiefile,
								'CURLOPT_HEADER'=>1));
						preg_match('/http.+/', $returnb, $inviteurl['passive']);
						$urlchunks = parse_url(trim($inviteurl['passive'][0]));
						parse_str($urlchunks['query'], $queryp);
						if ($opendesktop->windowmode != 'presentation'){
							echo '<form action="'.$urlchunks['scheme'].'://'.$urlchunks['host'].$urlchunks['path'].'" method="get" onsubmit="this.target=\'_blank\'"><div>';
							echo '<input type="hidden" name="token" value="'.$queryp['token'].'" />';
							echo '<input type="hidden" name="task" value="join" />';
							echo '<input style="margin: 10px;" type="submit" value="'.get_string('joinviewonly', 'opendesktop').'" />';
							echo '</div></form><span>  </span>';
						}
						else {
							$pathtoappserv = urlencode($urlchunks['scheme'].'://'.$urlchunks['host'].$urlchunks['path']);
							echo '<!--[if !IE]> <-->
									<form action="openview.php?id='.$cm->id.'" method="post"><div style="float: left;">
											<input type="hidden" name="token" value="'.$queryp['token'].'" />
													<input type="hidden" name="task" value="join" />
													<input type="hidden" name="pathtoappserv" value="'.$pathtoappserv.'" />
															<input type="submit" value="'.get_string('joinviewonly', 'opendesktop').'" />
																	</div></form>
																	<!--> <![endif]-->
																	<!--[if IE]>
																	<p style="font-weight:bold;">'.get_string('browserwarning', 'opendesktop').'</p>
																			<![endif]-->';
						}
					}
				}
			}
		}
	}
	if (empty($inviteurl)) {
		p(get_string('nosession', 'opendesktop'));
	}
	echo ('</fieldset>');
}
echo '<applet id="CheckJava" code="org.ulteo.applet.CheckJava" codebase="applet/" archive="CheckJava.jar" mayscript="mayscript" width="1" height="1"> </applet>';
$session->get($session->sessurl."/admin/logout.php");
unset($session);
/// Finish the page
echo $OUTPUT->footer();
?>

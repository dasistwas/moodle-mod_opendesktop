<?php

defined('MOODLE_INTERNAL') || die();

require_once(dirname(__FILE__).'/renderable.php');

/**
 * Internal library of functions for module opendesktop
 *
 * @package   mod_opendesktop
 * @copyright 2013 David Bogner {@link http://www.edulabs.org}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/


class opendesktop implements renderable {

	/** @var stdClass the opendesktop record that contains the global settings for this opendesktop instance */
	private $opendesktop = null;

	/** @var context the context of the course module for this opendesktop instance (or just the course if we are
	 creating a new one) */
	private $context = null;

	/** @var stdClass the course this opendesktop instance belongs to */
	private $course = null;

	/** @var stdClass the course module for this assign instance */
	private $cm = null;

	/** @var stdClass the curl session for connecting to OVD */
	private $session = null;

	/** @var stdClass the admin config for all opendesktop instances  */
	private $config = null;

	/** @var mod_opendesktop_renderer the custom renderer for this module */
	private $renderer = null;

	/**
	 * Constructor for the opendesktop class
	 *
	 * @param mixed $context context|null the course module context (or the course context if the coursemodule has not been created yet)
	 * @param mixed $coursemodule the current course module if it was already loaded - otherwise this class will load one from the context as required
	 * @param mixed $course the current course  if it was already loaded - otherwise this class will load one from the context as required
	 */
	public function __construct(context $context, stdClass $cm, stdClass $course, stdClass $opendesktop,stdClass $config = null) {
		$this->context = $context;
		$this->cm = $cm;
		$this->course = $course;
		$this->opendesktop = $opendesktop;
		$this->session = new opendesktop_curl_session(array(), $opendesktop->id);
		$this->config = get_config('opendesktop');
		//calculate the width and height of the virtual desktop
		if($opendesktop->desktopsize == 'auto'){
			$this->opendesktop->width = $this->opendesktop->height = '100%';
		} else {
			preg_match('/([0-9]+).([0-9]+)/u',$opendesktop->desktopsize,$matches);
			$this->opendesktop->width = $matches[1];
			$this->opendesktop->height = $matches[2];
		}
	}


	/**
	 * Get the module renderer
	 *
	 * @return mixed stdClass|null The module renderer
	 */
	public function get_renderer() {
		if (!$this->renderer) {
			global $PAGE;
			$this->renderer = $PAGE->get_renderer('mod_opendesktop');
		}
		return $this->renderer;
	}

	public function view($params) {
		switch ($params['task']) {
			case 'start':
				$output = $this->view_displayopendesktop($params);
				break;
			case 'register':
				$output = $this->view_register_desktop($params);
				break;
			case 'startoptions':
				$output = $this->view_overview($params);
				break;
			case 'join':
				$output = $this->view_join($params);
				break;
			case 'printlogin':
				$output = $this->view_printlogin($params);
				break;
			case 'killsession':
				$output = $this->view_killsession($params);
				break;
			default:
				print_error('Wrong parameter value: task');
				break;
		}
		return $output;
	}

	/**
	 *
	 * @param post and get values submitted by the user $params
	 */
	private function view_printlogin($params){
		global $PAGE, $OUTPUT;
		require_capability('mod/opendesktop:start', $this->context);
		$PAGE->force_theme('standard');
		$PAGE->set_pagelayout('embedded');
		$this->session->check_ovd_userexists();
		$this->renderer = $this->get_renderer();
		$loginform = $this->renderer->render(new opendesktop_loginform($this->opendesktop, $this->session, $this->cm->id, $params));
		echo $OUTPUT->header();
		echo $loginform;
		echo $OUTPUT->footer();
	}

	private function view_register_desktop($params){
		global $USER, $DB, $CFG;
		require_sesskey();
		require_capability('mod/opendesktop:start', $this->context);
		$getresult = $this->session->get($this->session->sessurl."/admin/users.php?action=manage&id=".$CFG->opendesktop_useprefix.$USER->username);
		if (preg_match('/<input type="hidden" name="info" value="([a-zA-Z0-9]+)" \/>/', $getresult, $sessids)) {
			$sessid = $sessids[1];
			$data = new StdClass;
			$data->token = $sessid;
			$data->userid = $USER->id;
			$data->starttime = time();
			$data->opendesktop = $this->opendesktop->id;
			$data->sessionmode = $params['sessionmode'];
			//prevent multi registering the same desktop: if session exists, then return success (because it is already registered
			if(!$DB->record_exists('opendesktop_sessions', array('token' => $sessid, 'opendesktop' => $this->opendesktop->id))){
				if($DB->insert_record('opendesktop_sessions', $data)){
					$output = "success";
				} else {
					$output = "fail";
				}
			} else {		
				$params['bigbluebuttonlink'] = $this->bigbluebutton();
				$output = "success";
			}
		} else {
			$output = "fail";
		}
		$this->session->logout();
		echo $output;
	}

	private function view_displayopendesktop($params){
		global $OUTPUT, $PAGE;
		require_sesskey();
		require_capability('mod/opendesktop:start', $this->context);
		$PAGE->force_theme('standard');
		$PAGE->set_pagelayout('embedded');
		$PAGE->requires->js('/mod/opendesktop/javascript/timezones.js');
		$PAGE->requires->js('/mod/opendesktop/javascript/common.js');
		$params['bigbluebuttonlink'] = $this->bigbluebutton();
		if($params["sessionmode"] == 'desktopplus' || $params["sessionmode"] == 'desktop'){
			$ovdmode = "desktop";
		} else if ($params["sessionmode"] == 'portal') {
			$ovdmode = "portal";
		}
		$this->session->iframeurl = '?id='.$this->cm->id.'&task=printlogin&sessionmode='.$ovdmode;
		$this->renderer = $this->get_renderer();
		$output = $this->renderer->render(new opendesktop_startpage($this->opendesktop, $this->session, $this->cm->id, $params));
		echo $OUTPUT->header();
		echo $output;
		echo $OUTPUT->footer();
	}

	private function view_join($params){
		global $PAGE, $OUTPUT, $DB, $CFG;
		require_sesskey();
		$params['bigbluebuttonlink'] = $this->bigbluebutton();
		$ovdusername = $CFG->opendesktop_useprefix.$DB->get_field('user', 'username', array('id' => $params["ovduser"]));
		if($params['join'] == 'active') {
			require_capability('mod/opendesktop:joinactive', $this->context);
			$this->session->create_invitation($params['join'], $ovdusername);
		} else {
			require_capability('mod/opendesktop:joinpassive', $this->context);
			$this->session->create_invitation($params['join'], $ovdusername);
		}
		$session_status = $this->session->sessionstatus($ovdusername);
		if (!$opendesktopid = $DB->get_field('opendesktop_sessions','opendesktop', array('token' => $session_status['token']))) {
			die();
		}
		$PAGE->force_theme('standard');
		$PAGE->set_pagelayout('embedded');
		$PAGE->add_body_class('opendesktop_join');
		$PAGE->requires->js('/mod/opendesktop/javascript/timezones.js');
		$PAGE->requires->js('/mod/opendesktop/javascript/common.js');
		$this->renderer = $this->get_renderer();
		$output = $this->renderer->render(new opendesktop_startpage($this->opendesktop, $this->session, $this->cm->id, $params));
		echo $OUTPUT->header();
		echo $output;
		echo $OUTPUT->footer();
	}

	private function view_overview(){
		global $PAGE, $OUTPUT, $DB;
		$params = array();		
		$PAGE->requires->js('/mod/opendesktop/javascript/deployJava.js');
		$PAGE->requires->js('/mod/opendesktop/javascript/applet.js');
		$PAGE->requires->js('/mod/opendesktop/javascript/common.js');
		$PAGE->requires->js('/mod/opendesktop/javascript/timezones.js');
		$PAGE->add_body_class('opendesktop_overview');
		
		$this->renderer = $this->get_renderer();
		$params['ovdstartlink'] = $this->display_startbutton();
		$params['availabledesktops'] = $this->available_desktops();
		$output = $this->renderer->render(new opendesktop_overview($this->opendesktop, $this->session, $this->cm->id, $params));
		
		//display link to start the desktop, or if own deskop is running/suspended and registered in the course, or in another course
		//display links to desktops that can be joined
		//make java checks: start java applet in order to keep startup time low for startin ovd
		
		echo $OUTPUT->header();
		echo $output;
		echo $OUTPUT->footer();
	}

	/**
	 * check whether to display the start button if yes return link for startbutton, if not the reason why not starbutton
	 */
	private function display_startbutton(){
		global $PAGE, $USER, $CFG;
		$return = array();
		//check session status
		$sessionstatus = $this->session->sessionstatus($CFG->opendesktop_useprefix.$USER->username);
		//if suspended, kill the session
		//TODO: Add possibility to resume the session, but for now just a "nice to have" feature
		if($sessionstatus){
			if($sessionstatus['status'] == 'suspended' || $sessionstatus['status'] == 'portal'){
				$this->session->killsession();
				$return['message']  = get_string('startpresentation','opendesktop');
				$return['url'] = new moodle_url($PAGE->url, array('id' => $this->cm->id, 'task' => 'start', 'sesskey' => sesskey()));
				$return['buttonmessage'] = 'logintoyourdesktop';
			} //registered and active session
			else if ($sessionstatus['status'] == 'active' && $sessionstatus['isregistered']){
				$return['message'] = get_string('opendesktoprunning', 'opendesktop');
				$return['url'] = new moodle_url($PAGE->url, array('id' => $this->cm->id, 'task' => 'killsession', 'sesskey' => sesskey()));
				$return['buttonmessage'] = 'killsession';
			} else if ($sessionstatus['status'] == 'active' && !$sessionstatus['isregistered']){
				$return['message']  = get_string('sessionnotregistered','opendesktop');
				$return['url'] = new moodle_url($PAGE->url, array('id' => $this->cm->id, 'task' => 'killsession', 'sesskey' => sesskey()));
				$return['buttonmessage'] = 'killsession';
			}
		} else {
			$return['message']  = get_string('startpresentation','opendesktop');
			$return['url'] = new moodle_url($PAGE->url, array('id' => $this->cm->id, 'task' => 'start', 'sesskey' => sesskey(), 'sessionmode' => 'desktop'));
			$return['buttonmessage'] = 'logintoyourdesktop';
		}
		return $return;
	}

	/**
	 *this function determins the desktops started and available in this course
	 *TODO: this function needs performance improvements
	 * @return array of moodle_urls or empty array
	 */
	private function available_desktops(){
		global $CFG, $PAGE, $USER, $DB;
		$url = array();
		if (has_capability('mod/opendesktop:joinactive', $this->context) OR has_capability('mod/opendesktop:joinpassive', $this->context)) {
			if ($roles = get_roles_used_in_context($this->context, true)) {
				$canstartusers   = get_users_by_capability($this->context, 'mod/opendesktop:start','u.id,username,firstname,lastname');
			}
			foreach ($canstartusers as $canstartuser) {
				$sql = 'SELECT MAX(starttime) AS starttime FROM {opendesktop_sessions} WHERE userid='.$canstartuser->id;
				$maxstarttime = $DB->get_field_sql($sql);
				$sessionmode = $DB->get_field('opendesktop_sessions','sessionmode', array ('userid' => $canstartuser->id, 'starttime' => $maxstarttime));
				if ($USER->username!=$canstartuser->username && $this->opendesktop->id == $DB->get_field('opendesktop_sessions','opendesktop', array ('userid' => $canstartuser->id, 'starttime' => $maxstarttime))) {
					// check if any user with the capability to start a desktop in this course has started OVD
					$sessvaldetect = $this->session->sessionstatus($CFG->opendesktop_useprefix.$canstartuser->username);
					// check if the opendesktop was started in the same course, if not do not display the possibility to join
					if ($sessvaldetect['status'] == 'active' && $sessvaldetect['isregistered']) {
						if  (has_capability('mod/opendesktop:joinactive', $this->context)){						
							$url[$canstartuser->username]['active'] = new moodle_url($PAGE->url, array('id' => $this->cm->id, 'task' => 'join', 'join' => 'active', 'sesskey' => sesskey(), 'sessionmode' => $sessionmode, 'ovduser' => $canstartuser->id));
						}
						if  (has_capability('mod/opendesktop:joinpassive', $this->context)){
							$url[$canstartuser->username]['passive'] = new moodle_url($PAGE->url, array('id' => $this->cm->id, 'task' => 'join', 'join' => 'passive', 'sesskey' => sesskey(), 'sessionmode' => $sessionmode, 'ovduser' => $canstartuser->id));
						}
						$url[$canstartuser->username]['fullname'] = $canstartuser->firstname.' '.$canstartuser->lastname;
					}
				}
			}
		}
		return $url;
	}
	
	private function view_killsession(){
		global $PAGE;
		require_sesskey();
		$this->session->killsession();
		redirect($PAGE->url, get_string('sessionkilled','opendesktop'));
	}
	
	private function bigbluebutton(){
		global $DB,$CFG;
		$bbbs = $DB->get_records('bigbluebuttonbn',array('course'=>$this->course->id));
		if(!empty($bbbs)){
			//TODO! the first occurance of an bigbluebutton instance is used, should be made 
			//selectable which one to choose if there are
			//more than 1 bbbs in a course
			$first_bbb_id = key($bbbs);
			$cm = get_coursemodule_from_instance('bigbluebuttonbn', $first_bbb_id);
			$bbb = new moodle_url($CFG->wwwroot."/mod/bigbluebuttonbn/view.php", array('id'=>$cm->id));
		} else {
			$bbb = "error";
		}
		return $bbb;
	}
}

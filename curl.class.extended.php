<?php

//require_once("$CFG->libdir/filelib.php");

require_once 'curl.class.php';

class opendesktop_curl_session extends opendesktop_curl {
	/** @var stdClass session object **/
	public $session;
	/** @var string the url to the ovd session manager **/
	public $sessurl = "";
	/** @var string path to the cookie file for curl **/
	public $cookiefile = "";
	/** @var string random ovd user password: automatically login user no pwd saving needed	**/
	public $randpass = "";
	/** @var boolean is there an active curl connection to ovd sessman or not **/
	public $activesession = false;
	/** @var array shows if desktop is running or not and if yes, the token**/
	/**private $sessval = false;**/
	/** @var string url for joining a running opendesktop **/
	public $iframeurl = "";
	/** @var string url for joining a running opendesktop in view only mode**/
	public $iframeurlpassive = "";
	/** @var int opendesktopid **/
	public $opendesktopid = null;
	/** @var array get and post variables from form submission **/
	private $params = "";

	public function __construct($options = array(),$opendesktopid) {
		opendesktop_curl::__construct(array('cache'=>true,'cookie'=>true));
		$this->create_session();
		$this->opendesktopid = $opendesktopid;
	}
	/*
	 * Create a cURL session and login to the web service
	*/
	private function create_session(){
		global $CFG;

		//define session url
		if ($CFG->opendesktop_usehttps == false) {
			$sessprotocol = 'http://';
		}
		else {
			$sessprotocol = 'https://';
		}
		$this->sessurl = $sessprotocol.$CFG->opendesktop_sessmanurl;

		//create cookiefile if it does not exist
		$this->cookiefile = $CFG->tempdir."/opendesktop/curl_cookie.txt";
		if (!file_exists($this->cookiefile)) {
			if (!file_exists($CFG->tempdir."/opendesktop")) {
				mkdir($CFG->tempdir."/opendesktop" , 0777);
			}
			$filehandle = fopen($this->cookiefile, 'w');
			chmod($this->cookiefile, 0777);
			fwrite($filehandle,'');
			fclose($filehandle);
		}
		$this->params = array('admin_login'=>$CFG->opendesktop_adminuser,'admin_password'=>$CFG->opendesktop_adminpass,'login_submit'=>'Log in');
		$this->session = $this->post($this->sessurl."/admin/login.php", $this->params, array('CURLOPT_COOKIEJAR'=>$this->cookiefile));
		$this->randpass = $this->generate_password(15, 'alphanum');
		$this->activesession = true;
	}

	public function get_config(){
		$confarr = array();
		$configpage = $this->get($this->sessurl."/admin/configuration-partial.php?mode=session_settings_defaults");
		if ($configpage == "") {
			$confarr = false;
		}
		else {
			$pattern = '/def/';
			preg_match('/<select id=\"general___session_settings_defaults___persistent\".+<\/select>/',$configpage,$confpersist);
			if(preg_match('/<option value=\"1\" selected=\"selected\" >/',$confpersist[0],$match) == 1){
				$confarr['persistant'] = 1;
			}
			if(preg_match('/checked=\"checked\" value=\"session_mode\"/',$configpage,$match) == 1){
				$confarr['session_mode'] = 1;
			}
		}
		return $confarr;
	}
	/**
	 * 
	 * @param $ovdusername string ovdusername (with eventual prefix from $CFG)
	 * @return false if the no opendesktop is running, or status: active or passive
	 */
	public function sessionstatus($ovdusername){
		global $DB;
		$getresult = $this->get($this->sessurl."/admin/users.php?action=manage&id=".$ovdusername);
		if (preg_match('/<input type="hidden" name="info" value="([a-zA-Z0-9]+)" \/>/', $getresult, $sessids)) {
			$token = $sessids[1];
			$statuspage = $this->get($this->sessurl."/admin/sessions.php?info=$token");
			$sessval = array();
			$sessval['token'] = $token;
			$sessval['isregistered'] = $DB->record_exists('opendesktop_sessions', array('token' => $token, 'opendesktop' => $this->opendesktopid));
			if (strstr($statuspage, 'Suspended')){
				$sessval['status'] = 'suspended';
			}
			else if (strstr($statuspage, 'Observe this session')) {
				$sessval['status'] = 'active';
			}
			else {
				$sessval['status'] = 'portal';
			}
		}
		else {
			$sessval = false;
		}
		return $sessval;
	}
	
	public function killsession(){
		global $USER, $CFG;
		if(!$this->activesession){
			$this->create_session();
		}
		$sessval = $this->sessionstatus($CFG->opendesktop_useprefix.$USER->username);
		if($sessval){
			$paramskill = array('action'=>'kill','session'=>$sessval['token']);
			// kill session of logged in teacher
			$this->post($this->sessurl."/admin/sessions.php", $paramskill, array('CURLOPT_COOKIEFILE'=>$this->cookiefile));
		}
		$this->logout();
	}
	
	/**
	 * create a password for existing user
	 * check if the user has already an account on the Ulteo OVD, if not thene create one.
	 * check if the user is part of the given usergroup, if not, add the user to the group. 
	 */
	public function check_ovd_userexists(){
		global $CFG,$USER;
		$prefix = $CFG->opendesktop_useprefix;
		$usergroup = $CFG->opendesktop_usergroup;
		
		$html_exist = $this->get($this->sessurl."/admin/users.php?action=manage&id=".$prefix.$USER->username);
		if (strstr($html_exist, '<input type="hidden" name="id" value="'.$prefix.$USER->username.'" />')) {
			// check if user is assigned to the usergroup, if not add the user
			if (!strstr($html_exist,'<a href="usersgroup.php?action=manage&id='.$usergroup.'">')) {
				$groupparams = array('action'=>'add','name'=>'User_UserGroup','element'=>$prefix.$USER->username,'group'=>$usergroup,);
				$this->get($this->sessurl."/admin/actions.php", $groupparams, array('CURLOPT_COOKIEFILE'=>$this->cookiefile,
						'CURLOPT_HEADER'=>1,
						'CURLOPT_REFERER'=>$this->sessurl."/admin/users.php?action=manage&id=".$prefix.$USER->username));
			}
			$params		=	array('action'=>'modify',
					'id'=>$prefix.$USER->username,
					'password'=>$this->randpass,
					'add'=>'Save the modifications');

			$this->post($this->sessurl."/admin/users.php", $params, array('CURLOPT_COOKIEFILE'=>$this->cookiefile));
			$this->randpass;
		}
		else {
			//if user does not exist add new user with random password
			$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
			$new_pattern = array("_", "_", "");
			$add = array('login'=>$prefix.$USER->username,'password'=>$this->randpass,'displayname'=>preg_replace($old_pattern, $new_pattern , $USER->firstname).' '.preg_replace($old_pattern, $new_pattern , $USER->lastname),'uid'=>'','action'=>'add','add'=>'Add');
			$this->post($this->sessurl."/admin/users.php", $add, array('CURLOPT_COOKIEFILE'=>$this->cookiefile,'CURLOPT_HEADER'=>1));

			//add user to usergroup
			$this->get($this->sessurl."/admin/users.php?action=manage&id=".$prefix.$USER->username,array('CURLOPT_COOKIEFILE'=>$this->cookiefile));
			$groupparams = array('action'=>'add','name'=>'User_UserGroup','group'=>$usergroup,'element'=>$prefix.$USER->username);
			$this->get($this->sessurl."/admin/actions.php", $groupparams, array('CURLOPT_COOKIEFILE'=>$this->cookiefile,
					'CURLOPT_HEADER'=>1,
					'CURLOPT_REFERER'=>$this->sessurl."/admin/users.php?action=manage&id=".$prefix.$USER->username));
		}
	}
	
	/**
	 * fetch the login url and token for joining a running desktop
	 * @param string $activepassive 'active'|'passive' : control the desktop|view only
	 */
	public function create_invitation($activepassive, $ovdusername){
		if(!$this->activesession){
			$this->create_session();
		}
		$sessval = $this->sessionstatus($ovdusername);
		$curl_formoptions = array('session_debug_true'=>0,'join'=>$sessval['token'], $activepassive =>'Join this session');
		$returnb = $this->post($this->sessurl."/admin/sessions.php", $curl_formoptions, array('CURLOPT_COOKIEFILE'=>$this->cookiefile,
				'CURLOPT_HEADER'=>1));
		preg_match('/http.+/', $returnb, $inviteurl);
		$this->iframeurl = $inviteurl[0];		
	}
	
	public function logout(){
		$this->get($this->sessurl."/admin/logout.php");
		$this->activesession = false;
	}

	public function generate_password($length = 8, $seeds = 'alphanum') {
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
}
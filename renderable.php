<?php

/**
 * Internal library of functions for module newsletter
 *
 * @package mod_newsletter
 * @copyright 2013 David Bogner <davidbogner@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Renderable opendesktop_loginform
 * @package mod_opendesktop
 * @copyright 2013 David Bogner <davidbogner@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
class opendesktop_loginform implements renderable {
	/** @var stdClass the opendesktop record */
	var $opendesktop = null;
	/** @var curl session for connecting to UlteOpenVirtual Desktop */
	var $session = false;
	/** @var int coursemoduleid - The course module id */
	var $cmid = 0;
	/** @var array post and get values */
	var $params = null;
	/**
	 * Constructor
	 *
	 * @param stdClass $opendesktop - the opendesktop database record
	 * @param stdClass $session curl session object of curl.class.extended.php to connect to OVD 
	 * @param cmid course module id
	 */
	public function __construct(stdClass $opendesktop, $session, $cmid, $params) {
		$this->opendesktop = $opendesktop;
		$this->session = $session;
		$this->cmid = $cmid;
		$this->params = $params;
	}
}

/**
 * Renderable opendesktop_loginform
 * @package mod_opendesktop
 * @copyright 2013 David Bogner <davidbogner@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class opendesktop_overview implements renderable {
	/** @var stdClass the opendesktop record */
	var $opendesktop = null;
	/** @var curl session for connecting to UlteOpenVirtual Desktop */
	var $session = false;
	/** @var int coursemoduleid - The course module id */
	var $cmid = 0;
	/** @var array post and get values */
	var $params = null;
	/**
	 * Constructor
	 *
	 * @param stdClass $opendesktop - the opendesktop database record
	 * @param stdClass $session curl session object of curl.class.extended.php to connect to OVD
	 * @param cmid course module id
	 */
	public function __construct(stdClass $opendesktop, $session, $cmid, $params) {
		$this->opendesktop = $opendesktop;
		$this->session = $session;
		$this->cmid = $cmid;
		$this->params = $params;
	}
}

/**
 * Renderable opendesktop_ovdstartpage
 * @package mod_opendesktop
 * @copyright 2013 David Bogner <davidbogner@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class opendesktop_startpage implements renderable {
	/** @var stdClass the opendesktop record */
	var $opendesktop = null;
	/** @var curl session for connecting to UlteOpenVirtual Desktop */
	var $session = false;
	/** @var int coursemoduleid - The course module id */
	var $cmid = 0;
	/** @var array post and get values */
	var $params = null;
	/**
	 * Constructor
	 *
	 * @param stdClass $opendesktop - the opendesktop database record
	 * @param stdClass $session curl session object of curl.class.extended.php to connect to OVD
	 * @param cmid course module id
	 */
	public function __construct(stdClass $opendesktop, $session, $cmid, $params) {
		$this->opendesktop = $opendesktop;
		$this->session = $session;
		$this->cmid = $cmid;
		$this->params = $params;
	}
}
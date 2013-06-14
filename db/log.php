<?php
/**
 * Definition of log events
*
* @package    mod
* @subpackage opendesktop
* @copyright  2013 David Bogner (http://edulabs.org)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

defined('MOODLE_INTERNAL') || die();

$logs = array(
		array('module'=>'opendesktop', 'action'=>'view', 'mtable'=>'opendesktop', 'field'=>'name'),
		array('module'=>'opendesktop', 'action'=>'update', 'mtable'=>'opendesktop', 'field'=>'name'),
		array('module'=>'opendesktop', 'action'=>'add', 'mtable'=>'opendesktop', 'field'=>'name'),
);
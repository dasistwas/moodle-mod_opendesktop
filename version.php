<?php 
/**
 * Code fragment to define the version of opendesktop
 * This fragment is called by moodle_needs_upgrading() and /admin/index.php
 *
 * @author David Bogner
 * @copyright David Bogner, http://www.edulabs.org
 * @package opendesktop
 **/

$module->version  = 2013072900;  // The current module version (Date: YYYYMMDDXX)
$module->requires = 2012120300;  // Requires this Moodle 2.X version
$module->release = '2.4.2';
$module->maturity = MATURITY_BETA;
$module->cron     = 0;           // Period for cron to check this module (secs)

?>

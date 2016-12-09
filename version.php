<?php 
/**
 * Code fragment to define the version of opendesktop
 * This fragment is called by moodle_needs_upgrading() and /admin/index.php
 *
 * @author David Bogner
 * @copyright David Bogner, http://www.edulabs.org
 * @package opendesktop
 **/

$plugin->version  = 2013112502;  // The current module version (Date: YYYYMMDDXX)
$plugin->requires = 2012120300;  // Requires this Moodle 2.X version
$plugin->component = 'mod_opendesktop';
$plugin->release = '2.4.2';
$plugin->maturity = MATURITY_BETA;
$plugin->cron     = 0;           // Period for cron to check this module (secs)

?>

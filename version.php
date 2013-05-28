<?php 
/**
 * Code fragment to define the version of opendesktop
 * This fragment is called by moodle_needs_upgrading() and /admin/index.php
 *
 * @author David Bogner
 * @version $Id: version.php,v 1.0 2009/09/29 15:54:02 dasistwas Exp $
 * @package opendesktop
 **/

$module->version  = 2013040605;  // The current module version (Date: YYYYMMDDXX)
$module->requires = 2010112400;  // Requires this Moodle 2.X version
$module->release = '2.4';
$module->maturity = MATURITY_ALPHA;
$module->cron     = 0;           // Period for cron to check this module (secs)

?>

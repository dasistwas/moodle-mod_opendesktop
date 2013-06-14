<?php
//
// Capability definitions for the feedback module.
//
// The capabilities are loaded into the database table when the module is
// installed or updated. Whenever the capability definitions are updated,
// the module version number should be bumped up.
//
// The system has four possible values for a capability:
// CAP_ALLOW, CAP_PREVENT, CAP_PROHIBIT, and inherit (not set).
//
//
// CAPABILITY NAMING CONVENTION
//
// It is important that capability names are unique. The naming convention
// for capabilities that are specific to modules and blocks is as follows:
//   [mod/block]/<component_name>:<capabilityname>
//
// component_name should be the same as the directory name of the mod or block.
//
// Core moodle capabilities are defined thus:
//    moodle/<capabilityclass>:<capabilityname>
//
// Examples: mod/forum:viewpost
//           block/recent_activity:view
//           moodle/site:deleteuser
//
// The variable name for the capability definitions array follows the format
//   $<componenttype>_<component_name>_capabilities
//
// For the core capabilities, the variable is $moodle_capabilities.


$capabilities = array(
  
    'mod/opendesktop:start' => array(

        'riskbitmask' => RISK_PERSONAL,
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => array(
            'user' => CAP_PREVENT,
			'student' => CAP_PREVENT,	
			'teacher' => CAP_ALLOW,
			'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    ),
    
    'mod/opendesktop:joinactive' => array(

        'riskbitmask' => RISK_PERSONAL,
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => array(
        	'guest' => CAP_PREVENT,
           	'student' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    ), 
    
    'mod/opendesktop:joinpassive' => array(

        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => array(
        	'guest' => CAP_ALLOW,
        	'student' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    ),  
    
    'mod/opendesktop:addinstance' => array(
    		'riskbitmask' => RISK_PERSONAL,
    
    		'captype' => 'write',
    		'contextlevel' => CONTEXT_COURSE,
    		'archetypes' => array(
    				'editingteacher' => CAP_ALLOW,
    				'manager' => CAP_ALLOW
    		),
    		'clonepermissionsfrom' => 'moodle/course:manageactivities'
    ),   
    'mod/opendesktop:view' => array(
    
    		'captype' => 'read',
    		'contextlevel' => CONTEXT_MODULE,
    		'archetypes' => array(
    				'editingteacher' => CAP_ALLOW,
    				'manager' => CAP_ALLOW,
    				'student' => CAP_ALLOW,
    				'teacher' => CAP_ALLOW,
    		),
    		'clonepermissionsfrom' => 'moodle/course:manageactivities'
    ),   
);

?>

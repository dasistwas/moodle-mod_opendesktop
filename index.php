<?php

/**
 * This page lists all the instances of opendesktop in a particular course
 *
 * @author  David Bogner <davidbogner@gmail.com>
 * @package mod/opendesktop
 */


require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = required_param('id', PARAM_INT);   // course

if (! $course = $DB->get_record('course', array('id' => $id))) {
    error('Course ID is incorrect');
}

require_course_login($course);

add_to_log($course->id, 'opendesktop', 'view all', "index.php?id=$course->id", '');


/// Get all required stringsopendesktop

$stropendesktops = get_string('modulenameplural', 'opendesktop');
$stropendesktop  = get_string('modulename', 'opendesktop');


/// Print the header

$navlinks = array();
$navlinks[] = array('name' => $stropendesktops, 'link' => '', 'type' => 'activity');
$navigation = build_navigation($navlinks);

print_header_simple($stropendesktops, '', $navigation, '', '', true, '', navmenu($course));

/// Get all the appropriate data

if (! $opendesktops = get_all_instances_in_course('opendesktop', $course)) {
    notice('There are no opendesktops', "../../course/view.php?id=$course->id");
    die;
}

/// Print the list of instances (your module will probably extend this)

$timenow  = time();
$strname  = get_string('name');
$strweek  = get_string('week');
$strtopic = get_string('topic');

if ($course->format == 'weeks') {
    $table->head  = array ($strweek, $strname);
    $table->align = array ('center', 'left');
} else if ($course->format == 'topics') {
    $table->head  = array ($strtopic, $strname);
    $table->align = array ('center', 'left', 'left', 'left');
} else {
    $table->head  = array ($strname);
    $table->align = array ('left', 'left', 'left');
}

foreach ($opendesktops as $opendesktop) {
    if (!$opendesktop->visible) {
        //Show dimmed if the mod is hidden
        $link = "<a class=\"dimmed\" href=\"view.php?id=$opendesktop->coursemodule\">$opendesktop->name</a>";
    } else {
        //Show normal if the mod is visible
        $link = "<a href=\"view.php?id=$opendesktop->coursemodule\">$opendesktop->name</a>";
    }

    if ($course->format == 'weeks' or $course->format == 'topics') {
        $table->data[] = array ($opendesktop->section, $link);
    } else {
        $table->data[] = array ($link);
    }
}

print_heading($stropendesktops);
print_table($table);

/// Finish the page

print_footer($course);

?>

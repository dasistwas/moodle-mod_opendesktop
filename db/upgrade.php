<?php  
// This file keeps track of upgrades to
// the opendesktop module
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installtion to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the functions defined in lib/ddllib.php

function xmldb_opendesktop_upgrade($oldversion=0) {

    global $CFG, $THEME, $DB;

    $result = true;

/// And upgrade begins here. For each one, you'll need one
/// block of code similar to the next one. Please, delete
/// this comment lines once this file start handling proper
/// upgrade code.



/// Lines below (this included)  MUST BE DELETED once you get the first version
/// of your module ready to be installed. They are here only
/// for demonstrative purposes and to show how the opendesktop
/// iself has been upgraded.

/// For each upgrade block, the file opendesktop/version.php
/// needs to be updated . Such change allows Moodle to know
/// that this file has to be processed.

/// To know more about how to write correct DB upgrade scripts it's
/// highly recommended to read information available at:
///   http://docs.moodle.org/en/Development:XMLDB_Documentation
/// and to play with the XMLDB Editor (in the admin menu) and its
/// PHP generation posibilities.

 //New version in version.php
    /// Define field intro to be added to opendesktop
    ///         $table = new XMLDBTable('opendesktop');
    ///         $field = new XMLDBField('intro');
    ///         $field->setAttributes(XMLDB_TYPE_TEXT, 'medium', null, null, null, null, null, null, 'name');
    /// Launch add field intro
    ///         $result = $result && add_field($table, $field);

    /// Define field introformat to be added to opendesktop
    ///         $table = new XMLDBTable('opendesktop');
    ///         $field = new XMLDBField('introformat');
    ///         $field->setAttributes(XMLDB_TYPE_INTEGER, '4', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'intro');
    /// Launch add field introformat
     ///        $result = $result && add_field($table, $field);
    //}

/// And that's all. Please, examine and understand the 3 example blocks above. Also
/// it's interesting to look how other modules are using this script. Remember that
/// the basic idea is to have "blocks" of code (each one being executed only once,
/// when the module version (version.php) is updated.

/// Lines above (this included) MUST BE DELETED once you get the first version of
/// yout module working. Each time you need to modify something in the module (DB
/// related, you'll raise the version and add one upgrade block here.

/// Final return of upgrade result (true/false) to Moodle. Must be
/// always the last line in the script
    return $result;
}

?>

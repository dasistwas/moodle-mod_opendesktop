<?php 
/**
 * This file defines the main opendesktop configuration form
 * It uses the standard core Moodle (>2.2) formslib. For
 * more info about them, please visit:
 *
 * http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * The form must provide support for, at least these fields:
 *   - name: text element of 64cc max
 *
 * Also, it's usual to use these fields:
 *   - intro: one htmlarea element to describe the activity
 *            (will be showed in the list of activities of
 *             opendesktop type (index.php) and in the header
 *             of the opendesktop main page (view.php).
 *   - introformat: The format used to write the contents
 *             of the intro field. It automatically defaults
 *             to HTML when the htmleditor is used and can be
 *             manually selected if the htmleditor is not used
 *             (standard formats are: MOODLE, HTML, PLAIN, MARKDOWN)
 *             See lib/weblib.php Constants and the format_text()
 *             function for more info
 */

require_once($CFG->dirroot.'/course/moodleform_mod.php');

class mod_opendesktop_mod_form extends moodleform_mod {

	function definition() {

		global $COURSE, $CFG;
		$mform =& $this->_form;

		//-------------------------------------------------------------------------------
		/// Adding the "general" fieldset, where all the common settings are showed
		$mform->addElement('header', 'general', get_string('general', 'form'));

		/// Adding the standard "name" field
		$mform->addElement('text', 'name', get_string('opendesktopname', 'opendesktop'), array('size'=>'64'));
		$mform->setType('name', PARAM_TEXT);
		$mform->addRule('name', null, 'required', null, 'client');
		$mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

		/// Adding the required "intro" field to hold the description of the instance
        $this->add_intro_editor(true, get_string('opendesktopintro', 'opendesktop'));

		//-------------------------------------------------------------------------------
		/// Adding the rest of opendesktop settings, spreeading all them into this fieldset
		/// or adding more fieldsets ('header' elements) if needed for better logic
		$mform->addElement('header', 'opendesktophdr', get_string('opendesktop_options', 'opendesktop'));
		$displayoptions=array();
		$displayoptions['auto'] = get_string('autosize','opendesktop');
		$displayoptions['800x600'] = get_string('small43','opendesktop');
		$displayoptions['1024x768'] = get_string('normal43','opendesktop');
		$displayoptions['1280x1024'] = get_string('huge43','opendesktop');
		$displayoptions['854×480'] = get_string('small169','opendesktop');
		$displayoptions['1024×576'] = get_string('medium169','opendesktop');
		$displayoptions['1280x720'] = get_string('normal169','opendesktop');
		$displayoptions['1920x1080'] = get_string('fullhd169','opendesktop');
		$mform->addElement('select', 'desktopsize', get_string('desktopsize', 'opendesktop'), $displayoptions);
		//$mform->addHelpButton('desktopsize', 'desktopsize', get_string('helpdesktopsize','opendesktop'));
		$langoptions=array();
		$handle = fopen($CFG->dirroot.'/mod/opendesktop/languages.csv', "r");
		while (($data = fgetcsv($handle, 500, ",")) !== FALSE) {
				$langoptions[$data[1]] = $data[0];
		}
		fclose($handle);
		$mform->addElement('select', 'languagesetting', get_string('languagesettings', 'opendesktop'), $langoptions);
		$mform->setType('languagesetting', PARAM_TEXT);
		$mform->setDefault('languagesetting', 'en_GB');
		//**!ToDo: Help text
		//$mform->setHelpButton('languagesetting', array('languagesetting', get_string('helplanguagesetting','opendesktop'), 'opendesktop'));
		$qualityoptions=array();
		$qualityoptions['medium'] = get_string('medium','opendesktop');
		$qualityoptions['high'] = get_string('high','opendesktop');
		$qualityoptions['highest'] = get_string('highest','opendesktop');
		$mform->addElement('select', 'quality', get_string('quality', 'opendesktop'), $qualityoptions);
		$mform->setType('quality', PARAM_TEXT);
		$mform->setDefault('quality', 'highest');
		//$mform->setHelpButton('quality', array('quality', get_string('helpquality','opendesktop'), 'opendesktop'));
		//-------------------------------------------------------------------------------
		// add standard elements, common to all modules
		$this->standard_coursemodule_elements();
		//-------------------------------------------------------------------------------
		// add standard buttons, common to all modules
		$this->add_action_buttons();

	}
}

?>

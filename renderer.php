<?php
/**
 * This file contains a renderer for the opendesktop
 *
 * @package   mod_opendesktop
 * @copyright 2013 David Bogner {@link http://www.edulabs.org}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
/** Include locallib.php */
require_once($CFG->dirroot . '/mod/opendesktop/locallib.php');


/**
 * A custom renderer class that extends the plugin_renderer_base and is used by the opendesktop module.
 *
 * @package   mod_opendesktop
 * @copyright 2013 David Bogner {@link http://www.edulabs.org}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
class mod_opendesktop_renderer extends plugin_renderer_base implements renderable {


	/**
	 * Render login form to start OpenVirtualDekstop
	 * @param opendesktop_loginform The form to render
	 * @return string
	 */
	public function render_opendesktop_loginform(opendesktop_loginform $formdata) {
		global $CFG,$USER;
		$o = '';
		$sessioninfo = $formdata->session->get_config();
		$sessval = $formdata->session->sessionstatus($CFG->opendesktop_useprefix.$USER->username);
		if ($sessioninfo == false) {
			$o .= $this->output->error_text(get_string('connecterror', 'opendesktop'));
		} else {
			$o .= $this->output->box_start('boxaligncenter ');
			$o .= print_object($formdata->params['sessionmode']);
			$formattributes = array();
			$formattributes['id'] = 'opendesktop_sessionlogin';
			$formattributes['class'] = 'opendesktopconnect';
			$formattributes['action'] = $formdata->session->sessurl.'/startsession.php';
			$formattributes['method'] = 'post';
			$formattributes['name'] ='launchopendesktop';
			
			$o .= html_writer::start_tag('form', $formattributes);
			$o .= html_writer::empty_tag('input', array('type'=>'hidden', 'name'=>'sesskey', 'value'=>sesskey()));
			$o .= html_writer::empty_tag('input', array('type'=>'hidden', 'name'=>'client', 'value'=>'browser'));
			$o .= html_writer::empty_tag('input', array('type'=>'hidden', 'name'=>'quality', 'value'=>$formdata->opendesktop->quality));
			$o .= html_writer::empty_tag('input', array('type'=>'hidden', 'name'=>'timezone', 'id'=>'timezone', 'value'=>''));
			$o .= html_writer::empty_tag('input', array('type'=>'hidden', 'name'=>'login', 'value'=>$CFG->opendesktop_useprefix.$USER->username));
			$o .= html_writer::empty_tag('input', array('type'=>'hidden', 'name'=>'language', 'value'=>$formdata->opendesktop->languagesetting));
			$o .= html_writer::empty_tag('input', array('type'=>'hidden', 'name'=>'password', 'id'=>'passwd', 'value'=>$formdata->session->randpass));
			$o .= html_writer::empty_tag('input', array('type'=>'hidden', 'name'=>'session_mode', 'id'=>'session_mode', 'value' => $formdata->params['sessionmode']));
			$o .= html_writer::start_tag('span', array('id'=>'loading_div', 'class'=>'loading_div'));
			$o .= html_writer::empty_tag('img', array('src'=>'pix/loading.div', 'alt'=>'Loading...', 'title'=> 'Loading...', 'height'=>'25', 'width'=>'25'));
			$o .= html_writer::end_tag('span');
			$o .= html_writer::start_tag('span', array('id'=>'launch_buttons','class'=>'launch_buttons'));
			$o .= html_writer::empty_tag('input', array('type'=>'button', 'class'=>'failed_button', 'id'=>'failed_button', 'value'=>'ERROR'));
			$o .= html_writer::tag('span', '<a href="http://www.java.com/download/">Click here to install the free Java-Plugin</a></span>', array('class'=>'java_download'));
			$o .= html_writer::empty_tag('input', array('type'=>'submit','id'=>'launch_button','class'=>'launch_button','value'=>get_string('logintoyourdesktop','opendesktop')));
			$o .= html_writer::end_tag('span');
			$o .= html_writer::end_tag('form');
			if ($formdata->opendesktop->windowmode == 'frame'){
				$o .= html_writer::tag('div', '<script type="text/javascript">document.launchopendesktop.submit();</script>');
			}
			$o .= $this->output->box_end();
		}
		return $o;
	}

	public function render_opendesktop_startpage(opendesktop_startpage $data){
		global $USER, $OUTPUT;
		 $o = '';
		 $buttons = html_writer::empty_tag('input', array('class' => 'opendesktop_hidebutton', 'type' => 'submit', 'value' => '⇧'));
		 $buttons .= html_writer::empty_tag('input', array('class' => 'opendesktop_switchit', 'type' => 'submit', 'value' => ''));
		 $clicktoregister = html_writer::empty_tag('input', array('value'=>get_string('clicktoregister','opendesktop'),'type'=>'button', 'class'=>'opendesktop_register_fail opendesktop_register_button'));
		 $message = html_writer::tag('span', get_string('registeringfailed','opendesktop').$clicktoregister,array('class'=>'opendesktop_register_fail'));
		 $messages = html_writer::tag('span',$message, array('class' => 'opendesktop_notifications'));
		 $o .= html_writer::tag('div', $buttons.$messages, array('class' => 'opendesktop_layout'));	 	
		 $o .= html_writer::tag('div','?id='.$data->cmid.'&task=register&sessionmode='.$data->params['sessionmode'].'&sesskey='.sesskey(), array('class' => 'openviewregister'));
		 
		 if($data->opendesktop->desktopsize == 'auto'){
		 	$parameter = array('src' => $data->session->iframeurl, 'width' => $data->opendesktop->width, 'height' => $data->opendesktop->height, 'class' => 'opendesktop_iframe opendesktop_iframes');
		 } else {
		 	$parameter = array('src' => $data->session->iframeurl, 'width' => $data->opendesktop->width, 'height' => $data->opendesktop->height, 'class' => 'opendesktop_iframes');
		 }
		 $iframe = html_writer::tag('iframe','', $parameter);
		 $o .= html_writer::tag('div', $iframe, array('class' => 'opendesktop_iframecontainer'));
		 if($data->params['sessionmode'] == 'desktopplus'){
		 	$bbbiframe = html_writer::tag('iframe','', array('src'=>$data->params['bigbluebuttonlink'], 'width' => $data->opendesktop->width, 'height'=> '500px', 'class' => 'opendesktop_bbbiframe'));
		 	$o .= html_writer::tag('div', $bbbiframe, array('class' => 'opendesktop_bbbcontainer'));
		 }
		 return $o;
	}
	
	public function render_opendesktop_overview(opendesktop_overview $data){
		global $USER, $OUTPUT, $PAGE,$CFG;
		$o = '';
		//start own opendesktop
		$formattributes = array('id' =>'opendesktop_sessionlogin', 'class' =>'opendesktopconnect', 'action' =>$PAGE->url, 'method'=> 'post', 'name'=> 'launchopendesktop');
		$formcontent = html_writer::input_hidden_params($data->params['ovdstartlink']['url']);
		$formcontent .= html_writer::select(array('desktopplus' => 'OpenDesktop + Video/Audio', 'portal' => 'Portal (Nur zum Dateiupload)', 'desktop' => 'OpenDesktop ohne Audio/Video',),'sessionmode','desktopplus');
		$formcontent .= html_writer::start_tag('div', array('id' => 'loading_div'));
		$formcontent .= html_writer::empty_tag('img', array('src'=>$OUTPUT->pix_url('loading','opendesktop'), 'alt'=>'Loading...', 'title'=> 'Loading...', 'height'=>'25', 'width'=>'25'));
		$formcontent .= html_writer::end_tag('div');
		$formcontent .= html_writer::start_tag('div', array('class'=>'fail_buttons'));
		$formcontent .= html_writer::empty_tag('input', array('type' => 'button', 'id'=>'failed_button', 'value'=>'ERROR'));
		$formcontent .= html_writer::start_tag('span', array('id'=> 'java_download'));
		$formcontent .= html_writer::tag('a', 'Click here to install the free Java-Plugin', array('href'=>'http://www.java.com/download/', 'class'=>'failed', 'target'=>'_blank'));
		$formcontent .= html_writer::end_tag('span');
		$formcontent .= html_writer::end_tag('div');
		$formcontent .= html_writer::empty_tag('input', array('type'=>'hidden','id'=>'timezone','class'=>'timezone','value'=>""));
		$formcontent .= html_writer::empty_tag('input', array('type'=>'submit','id'=>'launch_button','class'=>'launch_button','value'=>get_string($data->params['ovdstartlink']['buttonmessage'],'opendesktop')));
		$form = html_writer::tag('form', $formcontent, $formattributes);
		$starthtml = html_writer::tag('h2', get_string('logintoyourdesktop','opendesktop'));
		$starthtml .= $data->params['ovdstartlink']['message'];
		$starthtml .= $form;
		$o .= $OUTPUT->box($starthtml);
		
		//login to opendesktops of other users
		$joinhtml = html_writer::tag('h2', get_string('desktopstojoin','opendesktop'));
		if(empty($data->params['availabledesktops'])){
			$joinhtml .= html_writer::tag('div', get_string('nosession','opendesktop'));
			$o .= $OUTPUT->box($joinhtml);
			return $o;
		} 
		foreach ($data->params['availabledesktops'] as $username => $desktop){
			$joinhtml .= html_writer::start_tag('div');
			$joinhtml .= html_writer::tag('h3', get_string('joindesktop','opendesktop').' '.$desktop['fullname']);
			if(array_key_exists('active',$desktop)){
				$joinhtml .= html_writer::tag('a', get_string('joinactive','opendesktop'), array('href'=>$desktop['active'], 'class' => 'opendesktop_join'));
			}
			if(array_key_exists('passive',$desktop)){
				$joinhtml .= html_writer::tag('a', get_string('joinviewonly','opendesktop'), array('href'=>$desktop['passive'], 'class' => 'opendesktop_join'));
			}
			$joinhtml .= html_writer::end_tag('div');
		}
		$data->params['availabledesktops'];
		
		$o .= $OUTPUT->box($joinhtml);
		
		return $o;
	}
}


<?php //$Id: settings.php,v 1.1.2.3 2009/09/22 07:34:03 dasistwas Exp $

/*
 * Created on 09.05.2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

$settings->add(new admin_setting_heading('opendesktop_header', get_string('settingheading', 'opendesktop'), get_string('settinginfo', 'opendesktop')));

$settings->add(new admin_setting_configtext('opendesktop_adminuser', get_string('adminuser', 'opendesktop'),
                   get_string('configadminuser', 'opendesktop'), 'admin') );
                
$settings->add(new admin_setting_configpasswordunmask('opendesktop_adminpass', get_string('adminpass', 'opendesktop'),
                   get_string('configadminpass', 'opendesktop'), 'password of ulteo session manager admin-user') );
                   
$settings->add(new admin_setting_configtext('opendesktop_sessmanurl', get_string('sessmanurl', 'opendesktop'),
                   get_string('configsessmanurl', 'opendesktop'), 'example.com/sessionmanager') );
                   
$settings->add(new admin_setting_configtext('opendesktop_usergroup', get_string('usergroup', 'opendesktop'),
                   get_string('configusergroup', 'opendesktop'), 'static_1') );           
                         
$settings->add(new admin_setting_configcheckbox('opendesktop_usehttps', get_string('usehttps', 'opendesktop'),
                   get_string('configusehttps', 'opendesktop'), 1));
                   
$settings->add(new admin_setting_configtext('opendesktop_useprefix', get_string('useprefix', 'opendesktop'),
                   get_string('configuseprefix', 'opendesktop'), ''));
                   
?>

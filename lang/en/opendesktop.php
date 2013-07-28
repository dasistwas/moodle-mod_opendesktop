<?php

$string['opendesktop'] = 'OpenDesktop';
$string['pluginname'] = 'OpenDesktop';

$string['modulename'] = 'OpenDesktop';
$string['modulenameplural'] = 'OpenDesktops';
$string['opendesktop:addinstance'] = 'Add new OpenDesktop';

$string['opendesktopintro'] = 'Open Desktop Description';
$string['opendesktopname'] = 'Open Desktop';
$string['pluginadministration'] = 'OpenDesktop administration';

//admin settings
$string['adminuser'] = 'Admin username';
$string['adminpass'] = 'Password';
$string['sessmanurl'] = 'Session manager URL';
$string['usergroup'] = 'Usergroup ID';
$string['usehttps'] = 'Use secure connection (https)';
$string['useprefix'] = 'Use a prefix for usernames';
$string['settingheading'] = 'Configure your Open Virtual Desktop';
$string['settinginfo'] = '<b>How to configure this module</b>
							<ul>
								<li>Install your own server: You will find information about setting up, FAQ and support at <a href="http://docs.moodle.org/en/OpenDesktop_module" target="_blank">OpenDesktop_module Documentation</a></li>
							</ul>';

$string['configadminuser'] = 'Username of the admin account on the Ulteo Session Manager';
$string['configadminpass'] = 'Password of the admin of the Ulteo Session Manager';
$string['configsessmanurl'] = 'Url to the Ulteo Session Manager without http://<br /> Example: example.com/sessionmanager';
$string['configusergroup'] = 'Usergroup on the session manager to which new OpenDesktop users are subscribed to. Go to the session manager and click on the user group to find out the id (id is in the url as .../usersgroup.php?action=manage&id=1)';
$string['configusehttps'] = 'Use a secure connection to connect to the session manager. This is highly recommended to avoid security problems.';
$string['configuseprefix'] = 'If you are using the module on more than one moodle platforms und you have only one Open Virtual Desktop Server, then use a prefix to avoid identical usernames on the Ulteo Open Virtual Desktop.';

//capabilities
$string['opendesktop:view'] = 'View OpenDesktop Login options';
$string['opendesktop:start'] = 'Start an Open Virtual Desktop session';
$string['opendesktop:joinactive'] = 'Join an Open Virtual Desktop session and be able to control the desktop';
$string['opendesktop:joinpassive'] = 'Join an Open Virtual Desktop session in view only mode';

//module settings and options in mod_form
$string['windowmode'] = 'Choose OpenDesktop mode';
$string['opendesktoponly'] = 'Start Open Virtual Desktop only';
$string['presentationmode'] = 'Presentation mode with video and chat';
$string['opendesktop_options'] = 'Options for the OpenDesktop sessions';
$string['languagesettings'] = 'Language';
$string['autosize'] = 'auto (size of the browser window)';
$string['small43'] = 'Small 4:3(800x600)';
$string['normal43'] = 'Normal 4:3(1024x768)';
$string['huge43'] = 'Large 4:3(1280x1024)';
$string['small169'] = 'Small 16:9(854x480)';
$string['medium169'] = 'Medium 16:9(1024x576)';
$string['normal169'] = 'Normal 16:9(1280x720)';
$string['fullhd169'] = 'FullHD 16:9(1920x1080)';
$string['desktopsize'] = 'Display size';
$string['lowest'] = 'Lowest';
$string['medium'] = 'Medium';
$string['high'] = 'High';
$string['highest'] = 'Highest';
$string['quality'] = 'Quality';
//video settings
$string['videooptions'] = 'Audio/Video Settings';
$string['ustreamuser'] = 'Ustream.tv username';
$string['ustreamid'] = 'Channel ID of your stream';
$string['ustreampass'] = 'Ustream.tv password';
$string['ustreamprivate'] = 'Only course participants are allowed to watch the videostream';

//general messages
$string['killsession'] = 'End/restart OpenDesktop';
$string['endnow'] = 'End the session now';
$string['nosession'] = 'There is no OpenDesktop running at the moment. Try again later, if you want to join an OpenDesktop session.';
$string['joindesktop'] = 'OpenDesktop of';
$string['myopendesktop'] = 'My OpenDesktop';
$string['desktopstojoin'] = 'OpenDesktops you can join';
$string['logintoyourdesktop'] = 'Start your desktop';
$string['joinactive'] = 'Join and control the desktop';
$string['joinviewonly'] = 'Join the desktop in view only mode';
$string['starterror'] = 'There was an error registering your OpenDesktop in this course. Participants cannot view your desktop. Please shutdown your OpenDesktop and start it again. Possible reason: Long startup time. Usually when starting again, it will startup faster.';
$string['connecterror'] = 'No connection to the Open Virtual Desktop server. Please contact the administrator or check your OpenDesktop module configuration and the Open Virtual Desktop server configuration.';
$string['desktopisstarting'] = 'Your OpenDesktop is starting right now';
$string['opendesktoprunning'] = 'Your OpenDesktop was successfully started and registered in this course. Participants can now watch your OpenDesktop.';
$string['sessionnotregistered'] = 'Your OpenDesktop is running, but is not registered in this course (The course participants will not be able to join or watch your OpenDesktop). In order to allow participants to watch or join your OpenDesktop, end your session and restart it in this course.';
$string['registeringfailed'] = 'Your OpenDesktop was not registered in the course. Participants are not able to access your OpenDesktop.';
$string['clicktoregister'] = 'Register OpenDesktop';
$string['opendesktopsuspended'] = 'Your previous OpenDesktop session is suspended. If you want to resume the previous session choose "Start your desktop", choose "End the session now", if you want to end the previous session and start a new one.';
$string['desktopmode'] = 'Desktop Mode';
$string['portalmode'] = 'Portal Mode';
$string['modeexplain'] = 'You can use the portal mode to upload files from your local computer, and start single applications. In desktop mode you are working directly in your online-desktop and can share the desktop with other participants of this course.';
$string['startpresentation'] = 'Start OpenDesktop with video and chat';
$string['streamnotcreated'] = 'The ustream channel name is already in use, or contains a bad word or character. Click on "Continue" to choose another name!';
$string['namechannel'] = 'Choose a ustream channel name';
$string['choosechannel'] = 'Choose your ustream channel';
$string['startyourpresentation'] = 'Start the presentation';
$string['mypresentation'] = 'My presentation';
$string['joinpresentation'] = 'Join a presentation';
$string['browserwarning'] = 'Your browser does not support this application. Please use a normal browser like FireFox, Safari, Opera or Chrome.';
$string['endpresentation'] = 'End the presentation now';
$string['getjava'] = 'Click here to download the free Java-Plugin';
$string['sessionkilled'] = 'Your OpenDesktop session has ended. You can now start a new one.';

//help files etc.
$string['helplanguagesetting'] = 'Choose your language setting  correctly';
$string['helpquality'] = 'Choose the display quality of your OpenDesktop';
$string['helpszize'] = 'Choose the size of the display (FireFox only)';
?>

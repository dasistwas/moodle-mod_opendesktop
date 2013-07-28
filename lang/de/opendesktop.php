<?php

$string['opendesktop'] = 'OpenDesktop';
$string['pluginname'] = 'OpenDesktop';

$string['modulename'] = 'OpenDesktop';
$string['modulenameplural'] = 'OpenDesktops';
$string['opendesktop:addinstance'] = 'Neuen OpenDesktop anlegen';
$string['pluginadministration'] = 'OpenDesktop Verwaltung';

$string['opendesktopintro'] = 'Open Virtual Desktop Beschreibung';
$string['opendesktopname'] = 'Open Virtual Desktop Name';

//admin settings
$string['adminuser'] = 'Admin-Benutzername';
$string['adminpass'] = 'Passwort';
$string['sessmanurl'] = 'Sessionmanager URL';
$string['usergroup'] = 'Usergroup ID';
$string['usehttps'] = 'Eine sichere Verbindung verwenden (https)';
$string['useprefix'] = 'Ein Präfix für User verwenden';
$string['settingheading'] = 'Open Virtual Desktop konfigurieren';
$string['settinginfo'] = '<b>Konfiguration des Moduls</b>
							<ul>
								<li>Installieren Sie Ihren eigenen Server: Informationen zur Installation finden Sie unter <a href="http://docs.moodle.org/en/OpenDesktop_module" target="_blank">OpenDesktop_module Documentation</a></li>
							</ul>';
$string['configadminuser'] = 'Nutzername des Admin-Zugangs für den Ulteo Sessionmanager';
$string['configadminpass'] = 'Passwort des Admins des Ulteo Sessionmanager';
$string['configsessmanurl'] = 'URL zum Ulteo Sessionmanager ohne http://<br /> Beispiel: example.com/sessionmanager';
$string['configusergroup'] = 'Usergroup auf dem Sessionmanager, zu der neue Nutzer zugeordnet werden sollen. Die Usergroup kann man herausfinden, indem man im Sessionmanager auf die entsprechende Gruppe klickt und in der URL die id abliest. (.../usersgroup.php?action=manage&id=1)';
$string['configusehttps'] = 'Verwenden Sie eine sichere Verbindung, um die Kommunikation zwischen Moodle und dem Sessionmanager zu verschlüsseln. Das wird dringend empfohlen, um das Sicherheitsrisiko zu minimieren.';
$string['configuseprefix'] = 'Wenn mehrere Moodle Installationen auf einen Open Virtual Desktop Server zugreifen, verwenden Sie ein Präfix, um identische Usernamen zu vermeiden';


//capabilities
$string['opendesktop:view'] = 'OpenDesktop Login-Einstellungen';
$string['opendesktop:start'] = 'Eine OpenDesktop Sitzung starten';
$string['opendesktop:joinactive'] = 'An einer OpenDesktop Sitzung als aktive/r Nutzer/in teilnehmen';
$string['opendesktop:joinpassive'] = 'Als Zuseher/in eine OpenDesktop Sitzung mitverfolgen';

//module settings and options in mod_form
$string['windowmode'] = 'OpenDesktop anzeigen';
$string['newwindow'] = 'In einem neuen Fenster (_blank)';
$string['samewindow'] = 'Im selben Fenster (_top)';
$string['opendesktop_options'] = 'OpenDesktop Einstellungen';
$string['languagesettings'] = 'Spracheinstellungen';
$string['autosize'] = 'auto (Größe des Browserfensters)';
$string['small43'] = 'Klein 4:3(800x600)';
$string['normal43'] = 'Normal 4:3(1024x768)';
$string['huge43'] = 'Groß 4:3(1280x1024)';
$string['small169'] = 'Klein 16:9(854x480)';
$string['medium169'] = 'Mittel 16:9(1024x576)';
$string['normal169'] = 'Normal 16:9(1280x720)';
$string['fullhd169'] = 'FullHD 16:9(1920x1080)';
$string['desktopsize'] = 'Anzeigegröße';
$string['lowest'] = 'Niedrig';
$string['medium'] = 'Mittel';
$string['high'] = 'Hoch';
$string['highest'] = 'Am höchsten';
$string['quality'] = 'Anzeigequalität';

//general messages
$string['killsession'] = 'OpenDesktop beenden / Neu starten';
$string['endnow'] = 'Jetzt beenden';
$string['nosession'] = 'Im Moment ist kein OpenDesktop gestartet. Versuchen Sie es später, um an einer OpenDesktop Sitzung teilzunehmen.';
$string['joindesktop'] = 'OpenDesktop von';
$string['myopendesktop'] = 'Mein OpenDesktop';
$string['desktopstojoin'] = 'Zur Teilnahme verfügbare OpenDesktops';
$string['logintoyourdesktop'] = 'Desktop starten';
$string['joinactive'] = 'Am Desktop aktiv teilnehmen';
$string['joinviewonly'] = 'Desktop beobachten';
$string['starterror'] = 'OpenDesktop konnte nicht im Kurs registriert werden. Daher können Teilnehmer/innen den Desktop nicht sehen. Stoppen Sie den Desktop und starten Sie ihn erneut. Mögliche Ursache: Eine zu lange Startzeit. Normalerweise funktioniert der erneute Start schneller.';
$string['connecterror'] = 'Es konnte keine Verbindung zum Open Virtual Desktop Server hergestellt werden. Kontaktieren Sie den/die Administrator/in oder überprüfen Sie die OpenDesktop Moduleinstellungen und die Open Virtual Desktop Serverkonfiguration.';
$string['desktopisstarting'] = 'Der OpenDesktop wird gerade gestartet';
$string['opendesktoprunning'] = 'Ihr OpenDesktop wurde erfolgreich gestartet und in diesem Kurs registriert. Teilnehmer/innen können nun Ihren OpenDesktop beobachten oder daran teilnehmen.';
$string['sessionnotregistered'] = 'Ihr OpenDesktop wurde erfolgreich gestartet, aber nicht in diesem Kurs registriert. (Die Kursteilnehmer/innen haben daher nicht die Möglichkeit den OpenDesktop zu beobachten oder daran teilzunehmen.) Um den Kursteilnehmer/innen zu ermöglichen den OpenDesktop zu beobachten oder daran teilzunehmen, beenden Sie den OpenDesktop und starten Sie ihn erneut in diesem Kurs.';
$string['opendesktopsuspended'] = 'Ihr OpenDesktop befindet sich im Stand-By Modus. Klicken Sie auf "Desktop starten", um die vorangegangene Sitzung wieder aufzunehmen, oder auf "Jetzt beenden", um die vorangegangene Sitzung zu beenden und eine neue zu starten.';
$string['registeringfailed'] = 'Der OpenDesktop wurde nicht im Kurs nicht angemeldet. TeilnehmerInnen können nicht darauf zugreifen.';
$string['clicktoregister'] = 'OpenDesktop anmelden';
$string['desktopmode'] = 'Desktop Modus';
$string['portalmode'] = 'Portal Modus';
$string['modeexplain'] = 'Im Portalmodus können Sie von Ihrem lokalen Computer Dateien hochladen und einzelne Anwendungen starten. Im Desktop Modus arbeiten Sie direkt in ihrem persönlichen Onlinedesktop und können KursteilnehmerInnen aktiv daran teilnehmen oder zusehen lassen.';
$string['startpresentation'] = 'Start your OpenDesktop';
$string['streamnotcreated'] = 'Dieser Ustream-Kanal wird schon verwendet oder enthält nicht erlaubte Wörter oder Zeichen. Klicken Sie auf "Weiter", um einen anderen Namen zu wählen!';
$string['namechannel'] = 'Wählen Sie einen Namen für den Ustream-Kanal';
$string['choosechannel'] = 'Wählen Sie einen Ustream-Kanal';
$string['startyourpresentation'] = 'Präsentation starten';
$string['mypresentation'] = 'Meine Präsentation';
$string['joinpresentation'] = 'An einer Präsentation teilnehmen';
$string['browserwarning'] = 'Ihre Browser kann diese Anwendung nicht ausführen. Bitte steigen Sie um auf einen normalen Browser wie FireFox, Safari, Opera oder Chrome.';
$string['endpresentation'] = 'Präsentation beenden';
$string['getjava'] = 'Hier klicken, um das kostenlose Java-Plugin zu installieren';
$string['sessionkilled'] = 'Ihr OpenDesktop wurde beendet. Sie können ihn jetzt neu starten.';


//help files etc.
$string['helplanguagesetting'] = 'Die richtigen Spracheinstellungen wählen';
$string['helpquality'] = 'Die Bildqualität des OpenDesktops festlegen';
$string['helpszize'] = 'Die Anzeigegröße des OpenDesktops wählen (nur in FireFox möglich)';
?>

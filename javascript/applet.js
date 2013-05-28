var attributes = {code:'org.ulteo.applet.CheckJava',
		codebase:'applet/',archive:'CheckJava.jar',id:'check_java',mayscript:'true',width:1, height:1};
var parameters = '';
var version = '1.6';
if (deployJava.versionCheck('1.6+')) {            
	ulteo_applet_inited = true;
	deployJava.runApplet(attributes, parameters, version);
} 
/**
 * Copyright (C) 2009 Ulteo SAS / 2013 edulabs.org
 * http://www.ulteo.com and http://www.edulabs.org
 * Authors Jeremy DESVAGES <jeremy@ulteo.com>, David Bogner david@edulabs.org
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; version 2
 * of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 **/
YUI().use("node", "event", "io-base","transition", function (Y) {
	var ulteo_applet_inited = null;
	var testDone = false;
	
    function changeHeight(){
    	var iframeheight = Y.one("body").get("winHeight");
    	newheight = iframeheight - 15;
    	Y.one('.opendesktop_iframe').setAttribute('height', newheight);
    }

	function appletInited(status_) {
		ulteo_applet_inited = status_;
	}

	function appletLoaded() {
		if (!testDone) {
			testDone = true;
			Y.one('#loading_div').setStyle('display', 'none');
			Y.one('#launch_button').setStyle('display', 'block');
		}
	}

	function testFailed(failCode) {
		if (!testDone) {
			testDone = true;
			Y.one('#loading_div').setStyle('display', 'none');
			var failed_button_text = Y.one('#failed_button').get('value');
			if (failCode == 1) {
				Y.one('#failed_button').set('value', failed_button_text + ' (Please install Java)-->');
				Y.one('#java_download').setStyle('display', 'inline');
			} else if (failCode == 2) {
				Y.one('#failed_button').set('value', failed_button_text + ' (old Browser)');
			} else if (failCode == 3) {
				Y.one('#failed_button').set('value', failed_button_text + ' (Firewall)');
			}  else if (failCode == 5) {
				Y.one('#failed_button').set('value', failed_button_text + ' (Java problem)');
			} else if (failCode == -1) {
				Y.one('#failed_button').set('value', failed_button_text + ' (Session problem)');
			} else if (failCode == 4) {
				Y.one('#launch_button').setStyle('display', 'inline');
				return;
			}
			Y.one('#failed_button').setStyle('display', 'inline');
		}
	}

	function badPing() { //errCode
		if (!testDone) {
			testDone = true;
			Y.one('#loading_div').setStyle('display', 'none');
		}
	}

	function sessionLock() {
		if (!testDone) {
			testDone = true;
			Y.one('loading_div').setStyle('display', 'none');
			Y.one('lock_button').setStyle('display', 'block');
		}
	}

	function sessionStart() {
		if (testDone) {
			Y.one('#launch_button').setStyle('display', 'none');
			Y.one('#failed_button').setStyle('display', 'none');
		}
	}

	function sessionStop() {
		testDone = false;
		Y.one('#launch_button').setStyle('display', 'block');
		Y.one('#failed_button').setStyle('display', 'none');
	}

	Y.on("domready", function (){
		var opentimezone;
		if(opentimezone = Y.one('#timezone')){
			opentimezone.set('value', getTimezoneName());  
			setTimeout(function() {
				try {
					Y.one('#check_java').isActive();
				} catch(e) {
					testFailed(1);
					return;
				}
			}, 2000);
		}
	});

	function complete(id, o, args) {
		var id = id; // Transaction ID.
		var data = o.responseText; // Response data.
		var args = args[1]; // 'ipsum'.
		if(Y.one('.opendesktop_register_fail')){
			if(data == 'fail'){
				Y.all('.opendesktop_register_fail').setStyle('display', 'inline');
			} else if (data == 'success'){

				var notifybox = Y.one('.opendesktop_notifications');
				setTimeout(function() {
					notifybox.setStyle('display', 'none');
				}, 15000);
				notifybox.setStyle('display', 'inline');
				notifybox.setStyle('color', 'white');
				notifybox.set('text','Desktop registered! Switch to your OpenDesktop >>> ');
				notifybox.transition({
					duration: 5, // in seconds, default is 0.5
					easing: 'ease', // default is 'ease'
					delay: '0', // delay start for 1 second, default is 0
			        opacity: { // per property
			            value: 0,
			            duration: 5,
			            delay: 5,
			            easing: 'ease-in'
			        }
				});
			}
		}
	};

	function switchiframes() {
		Y.one('.opendesktop_iframecontainer').swapXY(Y.one('.opendesktop_bbbcontainer'));
		Y.one('.opendesktop_layout').toggleClass('opendesktop_switched');
	}
	
	function resetstyles(){
		Y.one('.opendesktop_layout').removeAttribute('style');
		Y.one('.opendesktop_layout').toggleClass('opendesktop_hideshow');
		Y.one('body').toggleClass('opendesktop_moveiframe');
	}
	
	function hidebuttons(){
		if(Y.one('.opendesktop_hideshow')){
			Y.one('.opendesktop_hidebutton').set('value','⇧');
			Y.one('.opendesktop_layout').transition({
		        duration: 1, // in seconds, default is 0.5
		        easing: 'ease-in', // default is 'ease'
		        delay: '0', // delay start for 1 second, default is 0		        
		        width: '200px',
		        top: '0'
		    }, 
		    resetstyles);			
		} else {
			Y.one('.opendesktop_hidebutton').set('value','⇩');
			Y.one('.opendesktop_layout').transition({
		        duration: 1, // in seconds, default is 0.5
		        easing: 'ease-out', // default is 'ease'
		        delay: '0.5', // delay start for 1 second, default is 0
		        width: '20px',
		        top: '-15px'
		    }, 
		    resetstyles);			
		}

	}
	
	function registerdesktop() {
		var uri=false;
		var request=false;
		if(Y.one('.openviewregister')){
			uri = Y.one('.openviewregister').get('text');
			request = Y.io(uri);			
		}
	}

	Y.on('domready', function() {
		if(Y.one('.openviewregister') && !Y.one('.opendesktop_join')){
			setTimeout(function() {
				registerdesktop(); }, 16000);
		}
		
		if(Y.one('.opendesktop_switchit')){
			setTimeout(function() {
					switchiframes(); }, 2000);
		}
		if(Y.one('.opendesktop_join')){
			var myelement = Y.one('.opendesktop_notifications');
			myelement.setStyle('display', 'inline');
			myelement.set('text', 'Zum OpenDesktop wechseln >> ');
			setTimeout(function() {
				myelement.setStyle('display', 'none');
			}, 20000);
		}
	});

    Y.on("available", appletLoaded, "#check_java", Y, "");
    Y.on("available", changeHeight, ".opendesktop_iframecontainer", Y, "");
	Y.on('io:complete', complete, Y, ['lorem', 'ipsum']);
	if(Y.one('.opendesktop_switchit')){
		Y.one('.opendesktop_switchit').on('click', switchiframes);
		Y.one('.opendesktop_hidebutton').on('click', hidebuttons);		
	}
	if(Y.one('.opendesktop_register_fail.opendesktop_register_button')){
		Y.one('.opendesktop_register_fail.opendesktop_register_button').on('click', registerdesktop);    	
	}
});
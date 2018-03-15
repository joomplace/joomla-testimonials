function refresh_captcha( params ) {
	var http_request = false;
	if (window.XMLHttpRequest) { // Mozilla, Safari,...
		http_request = new XMLHttpRequest();
		if (http_request.overrideMimeType) {
			http_request.overrideMimeType('text/xml');
		}
	} else if (window.ActiveXObject) { // IE
		try { http_request = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try { http_request = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}
	if (!http_request) {				
		return;
	}
	
	http_request.onreadystatechange = function() { new_captcha(http_request); };
	
	http_request.open('GET', params['mosConfig_live_site']+'/index.php?option=com_testimonials&task=new_captcha', true);
	http_request.send(null);
}

function new_captcha(http_request) {
	if (http_request.readyState == 4) {
		if ((http_request.status == 200)) {
			
			if(http_request.responseXML.documentElement == null){
				try {
					http_request.responseXML.loadXML(http_request.responseText);
				} catch (e) {}
			}
			response  = http_request.responseXML.documentElement;
			
			var result = '';
			try {
			result = response.getElementsByTagName('response')[0].firstChild.data; 
			} catch(e){}
			if (result) {
				//document.getElementById('captcha_container').innerHTML = result;
				document.getElementById('captcha_code').src = result;
			}
		}
	}
}

function check_and_submit( params ) {	
	var http_request = false;
	if (window.XMLHttpRequest) { // Mozilla, Safari,...
		http_request = new XMLHttpRequest();
		if (http_request.overrideMimeType) {
			http_request.overrideMimeType('text/xml');
		}
	} else if (window.ActiveXObject) { // IE
		try { http_request = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try { http_request = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}
	if (!http_request) {
		submit_form('topic.save');		
		return;
	}	
	http_request.onreadystatechange = function() { submit_form_req(http_request, params ); };
	http_request.open('GET', params['mosConfig_live_site']+'/index.php?option=com_testimonials&task=check_captcha&captcha='+document.getElementById('captcha_value').value, true);
	http_request.send(null);
}

function submit_form_req(http_request, params ) {
	if (http_request.readyState == 4) {
		if ((http_request.status == 200)) {			
			if(http_request.responseXML.documentElement == null){
				try {
					http_request.responseXML.loadXML(http_request.responseText);
				} catch (e) {
				}
			}
			response  = http_request.responseXML.documentElement;
			var result = '';
			try {
				result = response.getElementsByTagName('response')[0].firstChild.data; 
			} catch(e){}	
			if (result == 'OK') {				
				jQuery('input[name=task]', jQuery('#adminForm')).val('topic.save');
                                jQuery('#adminForm').submit();
			} else {
				alert(params['msg_invalid_code']);
				document.getElementById('captcha_value').focus();
			}
		}else {						
			jQuery('input[name=task]', jQuery('#adminForm')).val('topic.save');
                        jQuery('#adminForm').submit();				
		}
	}
}
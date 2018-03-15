	function makeRequest(url) {

		var http_request = false;
	
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
			http_request = new XMLHttpRequest();
			if (http_request.overrideMimeType) {
				http_request.overrideMimeType('text/xml');
				// See note below about this line
			}
		} else if (window.ActiveXObject) { // IE
			try {
				http_request = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try {
					http_request = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e) {}
			}
		}
	
		if (!http_request) {
			// alert('Giving up: Cannot create an XMLHTTP instance');
			return false;
		}
		if (url.indexOf('latestNews') == -1){
			http_request.onreadystatechange = function() { alertContents(http_request); }
		} else{
			http_request.onreadystatechange = function() { alertContentsNews(http_request); }
		}
		http_request.open('GET', url, true);
		http_request.send(null);
	}

    function alertContents(http_request) {

        if (http_request.readyState == 4) {
            if ((http_request.status == 200) && (http_request.responseText.length < 1025)) {
				document.getElementById('tm_LatestVersion').innerHTML = '&nbsp;'+http_request.responseText;
            } else {
                document.getElementById('tm_LatestVersion').innerHTML = 'There was a problem with the request.';
            }
        }

    }
	function alertContentsNews(http_request) {

        if (http_request.readyState == 4) {
            if ((http_request.status == 200) && (http_request.responseText.length < 1025)) {
				document.getElementById('tm_LatestNews').innerHTML = '&nbsp;'+http_request.responseText;
            } else {
                document.getElementById('tm_LatestNews').innerHTML = 'There was a problem with the request.';
            }
        }

    }

	function tm_CheckNews(){
		document.getElementById('tm_LatestNews').innerHTML = 'Checking latest news now...';
    	makeRequest('index.php?option=com_testimonials&task=latestNews&no_html=1');
    	return false;
	}

    function tm_CheckVersion() {
    	document.getElementById('tm_LatestVersion').innerHTML = 'Checking latest version now...';
    	makeRequest('index.php?option=com_testimonials&task=latestVersion&no_html=1');
    	return false;
    }
    function tm_InitAjax() {
    	makeRequest('index.php?option=com_testimonials&task=latestVersion&no_html=1');
    }
    
    
    function tm_dateAjaxRef()
    {
        jQuery.ajax({
            type: "POST",
            url: "index.php?option=com_testimonials&task=datedb&no_html=1"
        });
        window.open("http://www.joomplace.com/support/product-improvement-survey?utm_source=Testimonials&utm_medium=Survey&utm_campaign=Product%2BImprovement", "_blank");
    }

    function tm_dateAjaxIcon()
    {
        jQuery.ajax({
            type: "POST",
            url: "index.php?option=com_testimonials&task=datedb&no_html=1"
        });
        jQuery('#notification').remove();
    }
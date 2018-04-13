// All functions correlate to a button press; the only difference is the request message sent
function getMP3() {
	sendPHPRequest("get_mp3");
}

function getMP4() {
	sendPHPRequest("get_mp4");
}

function getBoth() {
	sendPHPRequest("get_both");
}

function validateURL(url) {
	// Validate the URL using regular expressions
	if (url != undefined || url != '') {
		var regularExp = /^.*(youtu.be\/|v\/|u\/w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
		var match = url.match(regularExp);
		return match && match[2].length == 11;
	}
	return false;
}

function sendPHPRequest(action) {
	// This is an AJAX call done in vanilla JavaScript
	var processing = document.getElementById("processing");
	var youtubeURL = encodeURI(document.getElementById("youtubeURL").value);
	if (!validateURL(youtubeURL)) {
		alert("URL is not valid");
		return;
	}
	processing.innerText = "Processing, please wait";

	var parameters = "action=" + action + "&url=" + youtubeURL;
	var req = new XMLHttpRequest();
	
	req.onreadystatechange = function(){
		if (req.readyState == 4 && req.status == 200) 
		{
			var processing2 = document.getElementById("processing");
			processing2.innerHTML = "<a href=" + req.responseText + ">Click to Download!</a>";
			processing2.style.display = "block";
		}
	}

	req.open("GET", "/script/download.php?" + parameters, true);
	req.send();
}
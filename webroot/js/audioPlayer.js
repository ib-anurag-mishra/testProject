//--------------------------------------------------------------
//
//  DOCUMENT READY
//
//--------------------------------------------------------------
//--------------------------------------------------------------
//
//  PLAYER EVENTS
//
//--------------------------------------------------------------

var imageID;
var URLOne;
var URLTwo;
var URLThree;
var PID;
var webRootURL;

/*
 *	Called from the audio player swf when the player is ready to receive calls.
 */
function onPlayerReady() {
    document.getElementById('audioplayer').addListeners();
}

/*
 *	Called from the audio player swf when the state of the stream playback has changed.
 */
function onStateChange(state) {
    if (state == "buffering") {
        document.getElementById("playaudio"+imageID).style.display = "none";
	 document.getElementById("loadaudio"+imageID).style.display = "block";
	 document.getElementById("stopaudio"+imageID).style.display = "none";
    }
    else if (state == "playing") {
        document.getElementById("playaudio"+imageID).style.display = "none";
	 document.getElementById("loadaudio"+imageID).style.display = "none";
	 document.getElementById("stopaudio"+imageID).style.display = "block";
    }
    else {
        document.getElementById("playaudio"+imageID).style.display = "block";
	 document.getElementById("loadaudio"+imageID).style.display = "none";
	 document.getElementById("stopaudio"+imageID).style.display = "none";
    }
}

/*
 *	Called from the audio player swf when the stream has updated.
 *	time: The current playback time of the stream.
 *	duration: The duration of the current stream.
 */
function onPlaybackUpdate(time, duration) {
}

/*
 *	Called from the audio player swf when the stream has completed playback.
 */
function onPlaybackComplete() {
       document.getElementById("playaudio"+imageID).style.display = "block";
	document.getElementById("loadaudio"+imageID).style.display = "none";
	document.getElementById("stopaudio"+imageID).style.display = "none";
}

/*
 *	Called from the audio player swf during the loading of the stream.
 *	pct: Numeric value between 0 and 100 indicating the load percentage.
 */
function onLoadProgress(pct) {
}

/*
 *	Called from the audio player swf when stream completes loading.
 */
function onLoadComplete() {
}

/*
 *	Called from the audio player swf when the load of the stream throws an error.
 */
function onLoadError() {
       document.getElementById("playaudio"+imageID).style.display = "block";
	document.getElementById("loadaudio"+imageID).style.display = "none";
	document.getElementById("stopaudio"+imageID).style.display = "none";
}

//--------------------------------------------------------------
//
//  BUTTON EVENTS
//
//--------------------------------------------------------------

function buttonOver(event) {
    $(this).css({
            'cursor' : 'pointer',
            'background-color' : '#F99',
            'border' : '1px solid #933'
    });
}

function buttonOut(event) {
    $(this).css({
            'cursor' : 'default',
            'background-color' : '#CCC',
            'border' : '1px solid #999'
    });
}

function getCurrentTime(event) {
    document.getElementById('audioplayer').getCurrentTime('handleResponse');
}

function getDuration(event) {
    document.getElementById('audioplayer').getDuration('handleResponse');
}

function getID(event) {
    document.getElementById('audioplayer').getID('handleResponse');
}

function getLoadPercent(event) {
    document.getElementById('audioplayer').getLoadPercent('handleResponse');
}

function getState(event) {
    document.getElementById('audioplayer').getState('handleResponse');
}

function getURL(event) {
    document.getElementById('audioplayer').getURL('handleResponse');
}

function getVolume(event) {
    document.getElementById('audioplayer').getVolume('handleResponse');
}

function handleResponse(value) {
}

function load(event, audioURLOne, audioURLTwo, audioURLThree, playID) {
    var finalURL = audioURLOne;
    finalURL += audioURLTwo;
    finalURL += audioURLThree;
    document.getElementById('audioplayer').load(unescape(finalURL), true, playID);
}

function pause(event) {
    document.getElementById('audioplayer').pauseAudio();
}

function play(event) {
    document.getElementById('audioplayer').playAudio();
}

function seek(event) {
    document.getElementById('audioplayer').seekAudio(20);
}

function stopThis(event, objID) {
    document.getElementById('audioplayer').pause();
    document.getElementById("playaudio"+objID).style.display = "block";
    document.getElementById("loadaudio"+objID).style.display = "none";
    document.getElementById("stopaudio"+objID).style.display = "none";
}

function playSample(obj, objID, audioURLOne, audioURLTwo, audioURLThree, playID, webRoot) {
    $("img[id^='playaudio']").each(function() {
        document.getElementById($(this).attr("id")).style.display = "block";
    });
    $("img[id^='loadaudio']").each(function() {
        document.getElementById($(this).attr("id")).style.display = "none";
    });
    $("img[id^='stopaudio']").each(function() {
        document.getElementById($(this).attr("id")).style.display = "none";
    });
    var hasRequiredVersion = DetectFlashVer(9, 0, 0);
    if (!hasRequiredVersion) {
       $(".upgradeFlash").colorbox({width:"50%", inline:true, href:"#upgradeFlash_div"});
	$(".upgradeFlash").click().delay(800);
    }
    imageID = objID;
    URLOne = audioURLOne;
    URLTwo = audioURLTwo;
    URLThree = audioURLThree;
    PID = playID;
    webRootURL = webRoot;
    load(obj, audioURLOne, audioURLTwo, audioURLThree, playID);
}
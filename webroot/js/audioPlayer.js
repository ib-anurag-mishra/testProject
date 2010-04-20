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
    document.getElementById('audioPlayer').addListeners();
}

/*
 *	Called from the audio player swf when the state of the stream playback has changed.
 */
function onStateChange(state) {
    if (state == "buffering") {
        document.getElementById("play_audio"+imageID).style.display = "none";
	 document.getElementById("load_audio"+imageID).style.display = "block";
	 document.getElementById("stop_audio"+imageID).style.display = "none";
    }
    else if (state == "playing") {
        document.getElementById("play_audio"+imageID).style.display = "none";
	 document.getElementById("load_audio"+imageID).style.display = "none";
	 document.getElementById("stop_audio"+imageID).style.display = "block";
    }
    else {
        document.getElementById("play_audio"+imageID).style.display = "block";
	 document.getElementById("load_audio"+imageID).style.display = "none";
	 document.getElementById("stop_audio"+imageID).style.display = "none";
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
       document.getElementById("play_audio"+imageID).style.display = "block";
	document.getElementById("load_audio"+imageID).style.display = "none";
	document.getElementById("stop_audio"+imageID).style.display = "none";
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
       document.getElementById("play_audio"+imageID).style.display = "block";
	document.getElementById("load_audio"+imageID).style.display = "none";
	document.getElementById("stop_audio"+imageID).style.display = "none";
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
    document.getElementById('audioPlayer').getCurrentTime('handleResponse');
}

function getDuration(event) {
    document.getElementById('audioPlayer').getDuration('handleResponse');
}

function getID(event) {
    document.getElementById('audioPlayer').getID('handleResponse');
}

function getLoadPercent(event) {
    document.getElementById('audioPlayer').getLoadPercent('handleResponse');
}

function getState(event) {
    document.getElementById('audioPlayer').getState('handleResponse');
}

function getURL(event) {
    document.getElementById('audioPlayer').getURL('handleResponse');
}

function getVolume(event) {
    document.getElementById('audioPlayer').getVolume('handleResponse');
}

function handleResponse(value) {
}

function load(event, audioURLOne, audioURLTwo, audioURLThree, playID) {
    var finalURL = audioURLOne;
    finalURL += audioURLTwo;
    finalURL += audioURLThree;
    document.getElementById('audioPlayer').load(unescape(finalURL), true, playID);
}

function pause(event) {
    document.getElementById('audioPlayer').pause();
}

function play(event) {
    document.getElementById('audioPlayer').play();
}

function seek(event) {
    document.getElementById('audioPlayer').seek(20);
}

function stopThis(event, objID) {
    document.getElementById('audioPlayer').pause();
    document.getElementById("play_audio"+objID).style.display = "block";
    document.getElementById("load_audio"+objID).style.display = "none";
    document.getElementById("stop_audio"+objID).style.display = "none";
}

function playSample(obj, objID, audioURLOne, audioURLTwo, audioURLThree, playID, webRoot) {
    $("img[id^='play_audio']").each(function() {
        document.getElementById($(this).attr("id")).style.display = "block";
    });
    $("img[id^='load_audio']").each(function() {
        document.getElementById($(this).attr("id")).style.display = "none";
    });
    $("img[id^='stop_audio']").each(function() {
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
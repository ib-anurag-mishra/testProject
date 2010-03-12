//--------------------------------------------------------------
//
//  DOCUMENT READY
//
//--------------------------------------------------------------
/*
$(document).ready(function() {
	$("#property #getCurrentTime").click(getCurrentTime);
	$("#property #getDuration").click(getDuration);
	$("#property #getID").click(getID);
	$("#property #getLoadPercent").click(getLoadPercent);
	$("#property #getState").click(getState);
	$("#property #getURL").click(getURL);
	$("#property #getVolume").click(getVolume);
	$("#property .button").hover(buttonOver, buttonOut);
	
	$("#methods #load").click(load);
	$("#methods #pause").click(pause);
	$("#methods #play").click(play);
	$("#methods #seek").click(seek);
	$("#methods #stop").click(stop);
	$("#methods .button").hover(buttonOver, buttonOut);
});
*/
//--------------------------------------------------------------
//
//  PLAYER EVENTS
//
//--------------------------------------------------------------

var imageID;
var URLOne;
var URLTwo;
var URLThree;
var TtlCount;
var PID;

/*
 *	Called from the audio player swf when the player is ready to receive calls.
 */
function onPlayerReady() {
	//$('#ready .status_value').text('TRUE');
	document.getElementById('audioPlayer').addListeners();
}

/*
 *	Called from the audio player swf when the state of the stream playback has changed.
 */
function onStateChange(state) {
	if (state == "buffering") {
		document.getElementById(imageID).src = 'img/ajax-loader.gif';
		document.getElementById(imageID).onclick = function(){stop(this);};
	}
	else if (state == "playing") {
		document.getElementById(imageID).src = 'img/stop.png';
		document.getElementById(imageID).onclick=function(){stop(this);};
	}
	else {
		document.getElementById(imageID).src = 'img/play.png';
	}
	//$('#stateChange .status_value').text(state);
}

/*
 *	Called from the audio player swf when the stream has updated.
 *	time: The current playback time of the stream.
 *	duration: The duration of the current stream.
 */
function onPlaybackUpdate(time, duration) {
	//$('#time .status_value').text(time);
	//$('#duration .status_value').text(duration);
}

/*
 *	Called from the audio player swf when the stream has completed playback.
 */
function onPlaybackComplete() {
	document.getElementById(imageID).src = 'img/play.png';
	//$('#events .status_value').text('Audio Complete');
	//$('#loadProgress .status_value').text('100 %');
}

/*
 *	Called from the audio player swf during the loading of the stream.
 *	pct: Numeric value between 0 and 100 indicating the load percentage.
 */
function onLoadProgress(pct) {
	//$('#loadProgress .status_value').text(pct+' %');
}

/*
 *	Called from the audio player swf when stream completes loading.
 */
function onLoadComplete() {
	//$('#events .status_value').text('Load Complete');
}

/*
 *	Called from the audio player swf when the load of the stream throws an error.
 */
function onLoadError() {
	document.getElementById(imageID).src = 'img/play.png';
	//$('#events .status_value').text('Load Error');
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
	$('#trace .status_value').text(value);
}

function load(event, audioURLOne, audioURLTwo, audioURLThree, playID) {
	var finalURL = audioURLOne;
	finalURL += audioURLTwo;
	finalURL += audioURLThree;
	document.getElementById('audioPlayer').load(unescape(finalURL), true, playID);
	//$('#url .status_value').text('http://www.extralush.com/cellosong.mp3');
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

function stop(event) {
	document.getElementById('audioPlayer').stop();
	document.getElementById(imageID).onclick = function(){playSample(this, imageID, URLOne, URLTwo, URLThree, TtlCount, PID);};
}

function playSample(obj, objID, audioURLOne, audioURLTwo, audioURLThree, totalCount, playID) {
	for(i = 0; i < totalCount; i++) {
		document.getElementById("play_audio"+i).src = 'img/play.png';
		var onClickAttrs = document.getElementById("play_audio"+i).getAttribute("onClick");
		document.getElementById("play_audio"+i).onclick = new Function(onClickAttrs);
	}
	
	imageID = objID;
	URLOne = audioURLOne;
	URLTwo = audioURLTwo;
	URLThree = audioURLThree;
	TtlCount = totalCount;
	PID = playID;
	
	load(obj, audioURLOne, audioURLTwo, audioURLThree, playID);
}
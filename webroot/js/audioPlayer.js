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
var TtlCount;
var TtlUpperLoopCount;
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
            document.getElementById(imageID).src = webRootURL+'img/ajax-loader.gif';
            document.getElementById(imageID).onclick = function(){stop(this);};
    }
    else if (state == "playing") {
            document.getElementById(imageID).src = webRootURL+'img/stop.png';
            document.getElementById(imageID).onclick=function(){stop(this);};
    }
    else {
            document.getElementById(imageID).src = webRootURL+'img/play.png';
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
    document.getElementById(imageID).src = webRootURL+'img/play.png';
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
    document.getElementById(imageID).src = webRootURL+'img/play.png';
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

function stop(event) {
    document.getElementById('audioPlayer').stop();
    document.getElementById(imageID).onclick = function(){playSample(this, imageID, URLOne, URLTwo, URLThree, TtlCount, TtlUpperLoopCount, PID, webRootURL);};
}

function playSample(obj, objID, audioURLOne, audioURLTwo, audioURLThree, totalCount, totalUpperLoopCount, playID, webRoot) {
    if(totalUpperLoopCount == 0) {
        for(i = 0; i < totalCount; i++) {
            document.getElementById("play_audio"+i).src = webRoot+'img/play.png';
            var onClickAttrs = document.getElementById("play_audio"+i).getAttribute("onClick");
            document.getElementById("play_audio"+i).onclick = new Function(onClickAttrs);
        }
    }
    else {
        for(i = 0; i < totalUpperLoopCount; i++) {
            for(j = 0; j < totalCount; j++) {
                document.getElementById("play_audio"+i+j).src = webRoot+'img/play.png';
                var onClickAttrs = document.getElementById("play_audio"+i+j).getAttribute("onClick");
                document.getElementById("play_audio"+i+j).onclick = new Function(onClickAttrs);
            }
        }
    }
    
    imageID = objID;
    URLOne = audioURLOne;
    URLTwo = audioURLTwo;
    URLThree = audioURLThree;
    TtlCount = totalCount;
    TtlUpperLoopCount = totalUpperLoopCount;
    PID = playID;
    webRootURL = webRoot;
    load(obj, audioURLOne, audioURLTwo, audioURLThree, playID);
}
var flashvars = {};
var params = {};
var attributes = {};
attributes.id = "fmp_player";
swfobject.switchOffAutoHideShow();
swfobject.embedSWF("/swf/fmp.swf?"+(Math.random()*1000000), "alt", "960", "100", "9.0", false, flashvars, params, attributes);
// swfobject.embedSWF("/swf/fmp-test.swf?"+(Math.random()*1000000), "alt", "960", "100", "9.0.0", false, flashvars, params, attributes);

console.log('in streaming.js');

var playerVersion = swfobject.getFlashPlayerVersion();
console.log(playerVersion);
function sendMessageToComponent(message) {

	var flash =	document.getElementById("fmp_player");

	flash.updatePlayerMessageJS(message);
}




function getTotalPlayerLoadedTime() {
	var flash =	document.getElementById("fmp_player");
	
	flash.returnTotalPlayerLoadedTimeAS3();
	
}

function returnTotalPlayerLoadedTimeJS(duration) {
	
	$('.playerLoaded').html("Player has been loaded for: " + duration + " seconds.");
	
}




function pushSongs (newSongArray) {

	
	var flash =	document.getElementById("fmp_player");

	flash.pushNewSongsFromJS(newSongArray);
	
}









function reportPrevSong(prevSongObj, playerEventCode) {
	plaulistId = prevSongObj.playlistId 
	songId = prevSongObj.songId
	songLength = prevSongObj.songLength
	songProviderType = prevSongObj.providerType
	songDuration = prevSongObj.psld   
        songToken = prevSongObj.token
	var playerEventCodeString;
	switch(playerEventCode) {
		
		case 1:
			playerEventCodeString = "Play";
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,12,songLength,songDuration,songToken);
			break;
			
		case 2:
			playerEventCodeString = "Pause";
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,13,songLength,songDuration,songToken);
			break;
			
		case 3:
			playerEventCodeString = "Prev";
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,14,songLength,songDuration,songToken);
			break;
			
			
		case 4:
			playerEventCodeString = "Next";
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,15,songLength,songDuration,songToken);
			break;
			
		case 5:
			playerEventCodeString = "Song Ended";
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,16,songLength,songDuration,songToken);
                        clearNowstreamingSession();
			break;
			
		case 6:
			playerEventCodeString = "User choose another song in the queue";
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,17,songLength,songDuration,songToken);
			break;
			
                 case 7:
			playerEventCodeString = "Queue loaded";
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,18,songLength,songDuration,songToken);
			break;
                 case 8:
			playerEventCodeString = "Queue cleared"
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,19,songLength,songDuration,songToken);
                        clearNowstreamingSession();
			break;                        
                        
		case 9:
			playerEventCodeString = "User ran out of time";
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,20,songLength,songDuration,songToken);
                        clearNowstreamingSession();
			break;
		case 10:
			playerEventCodeString = "Queue playback completed";
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,21,songLength,songDuration,songToken);
                        clearNowstreamingSession();
			break;
			
		case 11:
			playerEventCodeString = "New queue loaded";
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,21,songLength,songDuration,songToken);
                        clearNowstreamingSession();
			break;
		default:
			playerEventCodeString = "";
			break;				    	
			
		
	}
        



}

/* this is called before the song is played */ 
function validateSong(songObj, playerEventCode) {

	
	/* properties sent from flash */
	
	plaulistId = songObj.playlistId 
	songId = songObj.songId
	songLength = songObj.songLength
	songProviderType = songObj.providerType
	songDuration = songObj.tbpp
        songToken = songObj.token
	
	
       
     
	
	/* playerEventCode: 1 = Play, 2 = Pause, 3 = Prev, 4 = Next, 5 = Song Ended, 6 = Stream Switched */
	
	var playerEventCodeString;
	
	switch(playerEventCode) {
		
		case 1:
			playerEventCodeString = "Play";
                        songDuration = 0;
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,2,songLength,songDuration,songToken);
			break;
			
		case 2:
			playerEventCodeString = "Pause"
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,3,songLength,songDuration,songToken);
			break;
			
		case 3:
			playerEventCodeString = "Prev"
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,9,songLength,songDuration,songToken);
			break;
			
			
		case 4:
			playerEventCodeString = "Next"
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,8,songLength,songDuration,songToken);
			break;
			
		case 5:
			playerEventCodeString = "Song Ended"
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,5,songLength,songDuration,songToken);
                        clearNowstreamingSession();
			break;
			
		case 6:
			playerEventCodeString = "User choose another song in the queue";
                        songDuration = 0;
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,10,songLength,songDuration,songToken);
			break;
			
	    case 7:
			playerEventCodeString = "Queue loaded/play";
                        songDuration = 0;
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,1,songLength,songDuration,songToken);
			break;	
	    case 8:
			playerEventCodeString = "Queue cleared"
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,11,songLength,songDuration,songToken);
                        clearNowstreamingSession();
			break;			    	
	}
	


}		



function reportLowStreamTime(lsto) {
	plaulistId = lsto.playlistId 
	songId = lsto.songId
	songLength = lsto.songLength
	songProviderType = lsto.providerType
	songDuration = lsto.lsld
	
	streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,22,songLength,songDuration);	
	lowStreamTime = lowStreamTime - lsto.lsld;
	
	var flash =	document.getElementById("fmp_player");
	flash.reportNewStreamTime(lowStreamTime);
	
	
}

function clearNowstreamingSession(){
    var postURL = webroot+'queuelistdetails/clearNowStreamingSession';
    $.ajax({
        type: "POST",
        cache:false,
        url: postURL
    }).done(function(data){

    })
    .fail(function(){
        alert('An error has occurred. Please reload the page.');
    });                                

}


function callStreamingComponent(prodId,providerType,queueId,eventFired,songLength,userStreamedTime,songToken){
    
    var postURL = webroot+'queuelistdetails/getPlaylistData';
    $.ajax({
        type: "POST",
        cache:false,
        url: postURL,
        data: {prodId : prodId,providerType : providerType,queueId : queueId,eventFired:eventFired,songLength:songLength,userStreamedTime:userStreamedTime,songToken:songToken},
        async: false
    }).done(function(data){
        var result = JSON.parse(data);

        if(result.error){
            var result = [0,"An error has occurred. Please reload the page.",0,0,0,0];            
        }else if(result.error1){
            var result = [0,"An error has occurred. Please reload the page.",0,0,0,0];
        }
        streamingValidationJS(result);
    })
    .fail(function(){
        var errorFlag = 1;
        var errorData = [0,"An error has occurred. Please reload the page.",0,0,0,0];
        streamingValidationJS(errorData);
    });
}

function pingTimeJS() {
	
	var flash = document.getElementById("fmp_player");
	flash.pingTime();
	
}

function streamingValidationJS(responseDataJS) { 
	
        
	responseDataJS[5] = 	responseDataJS[5]*1000;
	

        if($("#hid_library_unlimited").text()==1)    /*  For Patron with unlimited Streaming Limit */
        {
             document.getElementById('remaining_stream_time').innerHTML = 'UNLIMITED';
        }
        else                            /*  For Patron with  Streaming Limit of 10800 sec */
        {
             document.getElementById('remaining_stream_time').innerHTML = secondstotime(responseDataJS[2]);
        }
        
       
	
	var flash =	document.getElementById("fmp_player");
	
	flash.streamingValidationAS(responseDataJS);
       
	
}


function secondstotime(secs)
{
    var t = new Date(1970,0,1);
    t.setSeconds(secs);
    var s = t.toTimeString().substr(0,8);
    if(secs > 86399)
    	s = Math.floor((t - Date.parse("1/1/70")) / 3600000) + s.substr(2);
    return s;
}





$(window).bind('beforeunload', function(){
	/*alert("About to leave tab...");*/
	
	var flash =	document.getElementById("fmp_player");
	
	flash.windowClosed();	
	
});

function reportWindowClosedSongInfo (wcsobj) {

	plaulistId = wcsobj.playlistId 
	songId = wcsobj.songId
	songLength = wcsobj.songLength
	songProviderType = wcsobj.providerType
	songDuration = wcsobj.tbpp
        songToken = wcsobj.token
         
         
        if(!(songId==='0' || songProviderType=='' || plaulistId==='0' || songDuration==='0' || songToken=='' || songLength==''))
        {                      
           streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,23,songLength,songDuration,songToken);
        }
       
       
        
	
}
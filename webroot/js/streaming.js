var WeCantStop = [
	{
		label:'We Can\'t Stop',
		songTitle:'We Can\'t Stop',
		artistName:'Miley Cyrus',
		songLength:400,
		data:'000/000/000/000/279/534/69/MileyCyrus_WeCantStop_G010002990907b_1_1-256K_44S_2C_cbr1x.mp3?nvb=20130930151614&nva=20130930161614&token=508bd813fc901421d6032'
		/* data:'000/000/000/000/279/534/69/MileyCyrus_WeCantStop_G010002990907b_1_1-256K_44S_2C_cbr1x.mp3' */
		
	}
];

var MechanicalBull = [
	{
		playlistId: 1,
		songId: 7,
		label:'Supersoaker',
		songTitle:'Supersoaker',
		artistName:'Kings Of Leon',
		songLength:400,
		data:'000/000/000/000/282/868/54/KingsOfLeon_Supersoaker_G010003006169j_1_1-256K_44S_2C_cbr1x.mp3?token=5a809d308b03ea40363a2'
		
	},
	{
		playlistId: 1,
		songId: 8,
		label:'Rock City',
		songTitle:'Rock City',
		artistName:'Kings Of Leon',
		songLength:400,
		data:'000/000/000/000/282/868/55/KingsOfLeon_RockCity_G010003006169j_1_2-256K_44S_2C_cbr1x.mp3?token=5ecd3d549fcf7a5144635'
		
	},
	{
		playlistId: 1,
		songId: 9,
		label:'Don\'t Matter',
		songTitle:'Don\'t Matter',
		artistName:'Kings Of Leon',
		songLength:400,
		data:'000/000/000/000/282/868/56/KingsOfLeon_DontMatter_G010003006169j_1_3-256K_44S_2C_cbr1x.mp3?token=590ae4c1be0f83b5e8e9b'
		
	},
	{
		playlistId: 1,
		songId: 10,
		label:'Beautiful War',
		songTitle:'Beautiful War',
		artistName:'Kings Of Leon',
		songLength:400,
		data:'000/000/000/000/282/868/57/KingsOfLeon_BeautifulWar_G010003006169j_1_4-256K_44S_2C_cbr1x.mp3?token=5f3e40ee41975ded7a0a0'
		
	},
	{
		playlistId: 1,
		songId: 11,
		label:'Temple',
		songTitle:'Temple',
		artistName:'Kings Of Leon',
		songLength:400,
		data:'000/000/000/000/282/868/58/KingsOfLeon_Temple_G010003006169j_1_5-256K_44S_2C_cbr1x.mp3?token=5036570790117ebeb55d6'
		
	}
	
	

];


var popMostPopular = [
	{
		playlistId: 0,
		songId: 1,
		label:'Hot N Cold',
		songTitle:'Hot N Cold',
		artistName:'Katy Perry',
		songLength:220,
		data:'000/000/000/000/209/450/50/KatyPerry_HotNCold_G0100017710556_1_2-256K_44S_2C_cbr1x.mp3?token=5c1a131cab1d806dfc156'
		
	},
	{
		playlistId: 0,
		songId: 2,
		label:'Get Lucky',
		songTitle:'Get Lucky',
		artistName:'Daft Punk',
		songLength:248,
		data:'000/000/000/000/278/177/55/DaftPunkFeatPharrell_GetLucky_G0100029758145_1_1-256K_44S_2C_cbr1x.mp3?token=59ecf71c8d267a5cd49c9'
		
	},
	{
		playlistId: 0,
		songId: 3,
		label:'3 x 5',
		songTitle:'3 x 5',
		artistName:'John Mayer',
		songLength:290,
		data:'000/000/000/000/000/294/80/JohnMayer_3X5_G0100014157652_1_8-256K_44S_2C_cbr1x.mp3?token=5e9b2913bd18c3c8399dd'
		
	},
	{
		playlistId: 0,
		songId: 4,
		label:'Give Me A Reason',
		songTitle:'Give Me A Reason',
		artistName:'P!nk',
		songLength:243,
		data:'000/000/000/000/262/308/32/PnkFeatNateRuess_JustGiveMeAReason_G010002829359t_1_4-256K_44S_2C_cbr1x.mp3?token=58ebde353113cb72c3778'
		
	},
	{
		playlistId: 0,
		songId: 5,
		label:'Paper Doll',
		songTitle:'Paper Doll',
		artistName:'John Mayer',
		songLength:257,
		data:'000/000/000/000/281/947/74/JohnMayer_PaperDoll_G010003006184n_1_4-256K_44S_2C_cbr1x.mp3?token=5ab28d05110b35ef02777'
		
	},
	{
		playlistId: 0,
		songId: 6,
		label:'Sex On Fire',
		songTitle:'Sex On Fire',
		artistName:'Kings Of Leon',
		songLength:224,
		data:'000/000/000/000/276/624/63/KingsOfLeon_SexOnFire_G010002958812p_4_3-256K_44S_2C_cbr1x.mp3?token=5515d45b3bcd52470a8cd'
		
	}



];




var flashvars = {};
var params = {};
var attributes = {};
attributes.id = "fmp_player";
swfobject.embedSWF("/swf/fmp.swf", "alt", "960", "100", "9.0.0", false, flashvars, params, attributes/* , swfCallback */);

/*
function swfCallback (e) {
	
	if (e.success) {

		
	    var initialTimeout = setTimeout(function (){
	        //Ensure Flash Player's PercentLoaded method is available and returns a value
	        if(typeof e.ref.PercentLoaded !== "undefined" && e.ref.PercentLoaded()){
	            //Set up a timer to periodically check value of PercentLoaded
	            var loadCheckInterval = setInterval(function (){
	                //Once value == 100 (fully loaded) we can do whatever we want
	                if(e.ref.PercentLoaded() === 100){
	                    //Execute function
	                    //console.log('loaded');
	                    //var flash =	document.getElementById("fmp_player");
	                    //flash.pushNewSongsFromJS(popMostPopular);
	                    playerLoaded();
	                    //Clear timer
	                    clearInterval(loadCheckInterval);
	                }
	            }, 500);
	        }
	    }, 200);
		
		
	}
	



	
	
}
*/

function getTotalPlayerLoadedTime() {
	var flash =	document.getElementById("fmp_player");
	//console.log(flash);
	flash.returnTotalPlayerLoadedTimeAS3();
	
}

function returnTotalPlayerLoadedTimeJS(duration) {
	
	$('.playerLoaded').html("Player has been loaded for: " + duration + " seconds.");
	
}




function pushSongs (newSongArray) {

	
	var flash =	document.getElementById("fmp_player");
	//console.log(flash);
	flash.pushNewSongsFromJS(newSongArray);
	
}





function reportTotalDuration(totalDuration) {
	$('.total_time').html("<br />Total time played in seconds: " + totalDuration + "<br /><br />");
	
}

/*
function reportSongInfo(songObj) {

	var remainingUserStreamTime = 240;
	
	$('.song_played').html("Current song being played: <br />" + "Playlist ID: " + songObj.playlistId + "<br />" + "Song ID: " + songObj.songId + "<br />" + "Label: " + songObj.label + "<br />" + "Artist Name: " + songObj.artistName + "<br />" + "Song Title: " + songObj.songTitle + "<br />" + "Song Length: " + songObj.songLength + "<br />" + "Song Data: " + songObj.data + "<br />" + "Provider Type: " + songObj.providerType + "<br />" + "Total Duration: " + songObj.tld + "<br />" + "Time Elapsed Previous Event: " + songObj.songTimeElapsed); 
	
	return remainingUserStreamTime;
}
*/



function reportPrevSong(prevSongObj, playerEventCode) {
	plaulistId = prevSongObj.playlistId 
	songId = prevSongObj.songId
	songLength = prevSongObj.songLength
	songProviderType = prevSongObj.providerType
	songDuration = prevSongObj.tbpp   
        
	var playerEventCodeString;
	switch(playerEventCode) {
		
		case 1:
			playerEventCodeString = "Play";
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,5,songLength,songDuration);
			break;
			
		case 2:
			playerEventCodeString = "Pause";
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,5,songLength,songDuration);
			break;
			
		case 3:
			playerEventCodeString = "Prev";
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,5,songLength,songDuration);
			break;
			
			
		case 4:
			playerEventCodeString = "Next";
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,5,songLength,songDuration);
			break;
			
		case 5:
			playerEventCodeString = "Song Ended";
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,5,songLength,songDuration);
			break;
			
		case 6:
			playerEventCodeString = "User choose another song in the queue";
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,5,songLength,songDuration);
			break;
			
                 case 7:
			playerEventCodeString = "Queue loaded";
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,5,songLength,songDuration);
			break;
                 case 8:
			playerEventCodeString = "Queue cleared"
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,5,songLength,songDuration);
			break;                        
                        
		case 9:
			playerEventCodeString = "User ran out of time";
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,5,songLength,songDuration);
			break;
			
		default:
			playerEventCodeString = "";
			break;				    	
			
		
	}
        
        console.log("inside reportPrevSong");
//	var prevSongInfoStr = "<p><span style='text-decoration:underline; font-weight:bold'>Prev Song Info:</span></p>" +
//					  "<p>Playlist ID: " + prevSongObj.playlistId + "</p>" +
//					  "<p>Song ID: " + prevSongObj.songId + "</p>" +
//					  "<p>Artist Name: " + prevSongObj.artistName + "</p>" +
//					  "<p>Song Title: " + prevSongObj.songTitle + "</p>" +
//					  "<p>Song Length: " + prevSongObj.songLength + "</p>" +
//					  "<p>Data: " + prevSongObj.data + "</p>" +
//					  "<p>Provider Type: " + prevSongObj.providerType + "</p>" +
//					  "<p>Prev Song Listening Duration: " + prevSongObj.psld + "</p>";
//	
//	$('.prevSongInfo').html(prevSongInfoStr);


}

/* this is called before the song is played */ 
function validateSong(songObj, playerEventCode) {

	
	// properties sent from flash
	
	plaulistId = songObj.playlistId 
	songId = songObj.songId
	songLength = songObj.songLength
	songProviderType = songObj.providerType
	songDuration = songObj.tbpp

	
	
       
//var songInfoStr = "<p>Playlist ID: " + songObj.playlistId + "</p>" +
//					  "<p>Song ID: " + songObj.songId + "</p>" +
//					  "<p>Artist Name: " + songObj.artistName + "</p>" +
//					  "<p>Song Title: " + songObj.songTitle + "</p>" +
//					  "<p>Song Length: " + songObj.songLength + "</p>" +
//					  "<p>Data: " + songObj.data + "</p>" +
//					  "<p>Provider Type: " + songObj.providerType + "</p>" +
//					  "<p>Prev Song Listening Duration / Time Before Pause: " + songObj.tbpp + "</p>";       
	
	// playerEventCode: 1 = Play, 2 = Pause, 3 = Prev, 4 = Next, 5 = Song Ended, 6 = Stream Switched
	
	var playerEventCodeString;
	
	switch(playerEventCode) {
		
		case 1:
			playerEventCodeString = "Play";
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,1,songLength,songDuration);
			break;
			
		case 2:
			playerEventCodeString = "Pause"
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,3,songLength,songDuration);
			break;
			
		case 3:
			playerEventCodeString = "Prev"
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,9,songLength,songDuration);
			break;
			
			
		case 4:
			playerEventCodeString = "Next"
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,8,songLength,songDuration);
			break;
			
		case 5:
			playerEventCodeString = "Song Ended"
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,5,songLength,songDuration);
			break;
			
		case 6:
			playerEventCodeString = "User choose another song in the queue"
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,10,songLength,songDuration);
			break;
			
	    case 7:
			playerEventCodeString = "Queue loaded/play"
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,1,songLength,songDuration);
			break;	
	    case 8:
			playerEventCodeString = "Queue cleared"
                        streamingResponse = callStreamingComponent(songId,songProviderType,plaulistId,11,songLength,songDuration);
			break;			    	
	}
	
//	var songInfoStr = "<p>Playlist ID: " + songObj.playlistId + "</p>" +
//					  "<p>Song ID: " + songObj.songId + "</p>" +
//					  "<p>Artist Name: " + songObj.artistName + "</p>" +
//					  "<p>Song Title: " + songObj.songTitle + "</p>" +
//					  "<p>Song Length: " + songObj.songLength + "</p>" +
//					  "<p>Data: " + songObj.data + "</p>" +
//					  "<p>Provider Type: " + songObj.providerType + "</p>" +
//					  "<p>Prev Song Listening Duration / Time Before Pause: " + songObj.psld + "</p>";
//	
//	$('.songInfo').html(songInfoStr);


	console.log("inside validateSong");
	
	
	//return isValid;
	//var responseDataArray = [0,"unable to stream this song",9660,6,358,60];
//	var responseDataArray = [1,"",300,6,358,5000];
//	streamingValidationJS(responseDataArray);

}		


function callStreamingComponent(prodId,providerType,queueId,eventFired,songLength,userStreamedTime){
    
    var postURL = webroot+'queuelistdetails/getPlaylistData';
    $.ajax({
        type: "POST",
        cache:false,
        url: postURL,
        data: {prodId : prodId,providerType : providerType,queueId : queueId,eventFired:eventFired,songLength:songLength,userStreamedTime:userStreamedTime}
    }).done(function(data){
        streamingValidationJS(JSON.parse(data));
    })
    .fail(function(){
        var errorFlag = 1;
        var errorData = [0,"Not able to stream this song due to some ineternal server problem",0,0,0,0];
        streamingValidationJS(errorData);
    });

    if(errorFlag == 1){
        return errorData;
    }else{
        return responseData;
    }
}

function pingTimeJS() {
	
	var flash = document.getElementById("fmp_player");
	flash.pingTime();
	
}

function streamingValidationJS(responseDataJS) {
	
	responseDataJS[5] = 	responseDataJS[5]*1000;
	console.log('inside streamingValidationJS'+responseDataJS);
	
	var flash =	document.getElementById("fmp_player");
	
	flash.streamingValidationAS(responseDataJS);
	
}
function reportTime(amt) {
	
	$('.report_time').html("Current time is " + amt + " seconds.");
}



function flashConsole(msg) {
	
	//console.log(msg);
}


$(document).ready(function(){

	
	
	var newSong = [
		{
		label:'Crawl',
		songTitle:'Crawl',
		artistName:'Kings Of Leon',
		songLength:247,
		data:'000/000/000/000/276/624/62/KingsOfLeon_Crawl_G010002958812p_4_2-256K_44S_2C_cbr1x.mp3?token=584ad5348d65c5cd89744'
		
		}
	];
	
	
	
	
	$(document).on('click','#pushNewSong', function(){
		
		pushSongs(newSong);
	});
	
	

});
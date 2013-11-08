var WeCantStop = [
	{
		label:'We Can\'t Stop',
		songTitle:'We Can\'t Stop',
		artistName:'Miley Cyrus',
		songLength:400,
		/* data:'000/000/000/000/279/534/69/MileyCyrus_WeCantStop_G010002990907b_1_1-256K_44S_2C_cbr1x.mp3?nvb=20130930151614&nva=20130930161614&token=508bd813fc901421d6032' */
		data:'000/000/000/000/279/534/69/MileyCyrus_WeCantStop_G010002990907b_1_1-256K_44S_2C_cbr1x.mp3'
		
	}
];

var SuperSoaker = [
	{
		label:'Supersoaker',
		songTitle:'Supersoaker',
		artistName:'Kings Of Leon',
		songLength:400,
		data:'000/000/000/000/282/868/54/KingsOfLeon_Supersoaker_G010003006169j_1_1-256K_44S_2C_cbr1x.mp3?token=5a809d308b03ea40363a2',
		providerType:'Sony'
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
		data:'000/000/000/000/282/868/54/KingsOfLeon_Supersoaker_G010003006169j_1_1-256K_44S_2C_cbr1x.mp3?token=5a809d308b03ea40363a2',
		providerType:'Sony'
		
	},
	{
		playlistId: 1,
		songId: 8,
		label:'Rock City',
		songTitle:'Rock City',
		artistName:'Kings Of Leon',
		songLength:400,
		data:'000/000/000/000/282/868/55/KingsOfLeon_RockCity_G010003006169j_1_2-256K_44S_2C_cbr1x.mp3?token=5ecd3d549fcf7a5144635',
		providerType:'Sony'
		
	},
	{
		playlistId: 1,
		songId: 9,
		label:'Don\'t Matter',
		songTitle:'Don\'t Matter',
		artistName:'Kings Of Leon',
		songLength:400,
		data:'000/000/000/000/282/868/56/KingsOfLeon_DontMatter_G010003006169j_1_3-256K_44S_2C_cbr1x.mp3?token=590ae4c1be0f83b5e8e9b',
		providerType:'Sony'
		
	},
	{
		playlistId: 1,
		songId: 10,
		label:'Beautiful War',
		songTitle:'Beautiful War',
		artistName:'Kings Of Leon',
		songLength:400,
		data:'000/000/000/000/282/868/57/KingsOfLeon_BeautifulWar_G010003006169j_1_4-256K_44S_2C_cbr1x.mp3?token=5f3e40ee41975ded7a0a0',
		providerType:'Sony'
		
		
	},
	{
		playlistId: 1,
		songId: 11,
		label:'Temple',
		songTitle:'Temple',
		artistName:'Kings Of Leon',
		songLength:400,
		data:'000/000/000/000/282/868/58/KingsOfLeon_Temple_G010003006169j_1_5-256K_44S_2C_cbr1x.mp3?token=5036570790117ebeb55d6',
		providerType:'Sony'
		
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
		data:'000/000/000/000/209/450/50/KatyPerry_HotNCold_G0100017710556_1_2-256K_44S_2C_cbr1x.mp3?token=5c1a131cab1d806dfc156',
		providerType:'Ioda'
		
	},
	{
		playlistId: 0,
		songId: 2,
		label:'Get Lucky',
		songTitle:'Get Lucky',
		artistName:'Daft Punk',
		songLength:248,
		data:'000/000/000/000/278/177/55/DaftPunkFeatPharrell_GetLucky_G0100029758145_1_1-256K_44S_2C_cbr1x.mp3?token=59ecf71c8d267a5cd49c9',
		providerType:'Ioda'
		
	},
	{
		playlistId: 0,
		songId: 3,
		label:'3 x 5',
		songTitle:'3 x 5',
		artistName:'John Mayer',
		songLength:290,
		data:'000/000/000/000/000/294/80/JohnMayer_3X5_G0100014157652_1_8-256K_44S_2C_cbr1x.mp3?token=5e9b2913bd18c3c8399dd',
		providerType:'Ioda'
		
	},
	{
		playlistId: 0,
		songId: 4,
		label:'Give Me A Reason',
		songTitle:'Give Me A Reason',
		artistName:'P!nk',
		songLength:243,
		data:'000/000/000/000/262/308/32/PnkFeatNateRuess_JustGiveMeAReason_G010002829359t_1_4-256K_44S_2C_cbr1x.mp3?token=58ebde353113cb72c3778',
		providerType:'Ioda'
		
	},
	{
		playlistId: 0,
		songId: 5,
		label:'Paper Doll',
		songTitle:'Paper Doll',
		artistName:'John Mayer',
		songLength:257,
		data:'000/000/000/000/281/947/74/JohnMayer_PaperDoll_G010003006184n_1_4-256K_44S_2C_cbr1x.mp3?token=5ab28d05110b35ef02777',
		providerType:'Ioda'
		
	},
	{
		playlistId: 0,
		songId: 6,
		label:'Sex On Fire',
		songTitle:'Sex On Fire',
		artistName:'Kings Of Leon',
		songLength:224,
		data:'000/000/000/000/276/624/63/KingsOfLeon_SexOnFire_G010002958812p_4_3-256K_44S_2C_cbr1x.mp3?token=5515d45b3bcd52470a8cd',
		providerType:'Ioda'
		
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
	
	
	
	var playerEventCodeString;
	
	switch(playerEventCode) {
		/*
		case 1:
			playerEventCodeString = "Play";
			break;
			
		case 2:
			playerEventCodeString = "Pause";
			break;
			
		case 3:
			playerEventCodeString = "Prev";
			break;
			
			
		case 4:
			playerEventCodeString = "Next";
			break;
			
		case 5:
			playerEventCodeString = "Song Ended";
			break;
			
		case 6:
			playerEventCodeString = "User choose another song in the queue";
			break;
			
	    case 7:
			playerEventCodeString = "Queue loaded";
			break;
		*/
		case 9:
			playerEventCodeString = "User ran out of time";
			break;
			
		case 10:
			playerEventCodeString = "Queue playback completed";
			break;
			
		default:
			playerEventCodeString = "";
			break;
		
		
	}
	
	var prevSongInfoStr = "<p><span style='text-decoration:underline; font-weight:bold'>Prev Song Info:</span></p>" +
					  "<p>Playlist ID: " + prevSongObj.playlistId + "</p>" +
					  "<p>Song ID: " + prevSongObj.songId + "</p>" +
					  "<p>Artist Name: " + prevSongObj.artistName + "</p>" +
					  "<p>Song Title: " + prevSongObj.songTitle + "</p>" +
					  "<p>Song Length: " + prevSongObj.songLength + "</p>" +
					  "<p>Data: " + prevSongObj.data + "</p>" +
					  "<p>Provider Type: " + prevSongObj.providerType + "</p>" +
					  "<p>Prev Song Listening Duration: " + prevSongObj.psld + "</p>" +
					  "<p>Player Event: " + playerEventCodeString + "</p>";
	
	$('.prevSongInfo').html(prevSongInfoStr);


	//console.log("inside validateSong");
	
		
	
}

var userRemainingTime = 40;

/* this is called before the song is played */ 
function validateSong(songObj, playerEventCode) {
	
	console.log("Validate Song:");
	console.log(songObj);
	/* properties sent from flash
	
	songObj.playlistId
	songObj.songId
	songObj.label
	songObj.artistName
	songObj.songTitle
	songObj.songLength
	songObj.data
	songObj.providerType
	songObj.totalListeningDuration
	
	
	*/
	
	// playerEventCode: 1 = Play, 2 = Pause, 3 = Prev, 4 = Next, 5 = Song Ended, 6 = Stream Switched
	
	var playerEventCodeString;
	
	switch(playerEventCode) {
		
		case 1:
			playerEventCodeString = "Play";
			break;
			
		case 2:
			playerEventCodeString = "Pause";
			break;
			
		case 3:
			playerEventCodeString = "Prev";
			break;
			
			
		case 4:
			playerEventCodeString = "Next";
			//userRemainingTime = userRemainingTime - 5;
			break;
			
		case 5:
			playerEventCodeString = "Song Ended";
			break;
			
		case 6:
			playerEventCodeString = "User choose another song in the queue";
			break;
			
	    case 7:
			playerEventCodeString = "Queue loaded";
			break;	
	    case 8:
			playerEventCodeString = "Queue cleared";
			break;
 	    	
			
		
	}
	
		

	
	var songInfoStr = "<p><span style='text-decoration:underline; font-weight:bold'>New Song Info:</span></p>" +
					  "<p>Playlist ID: " + songObj.playlistId + "</p>" +
					  "<p>Song ID: " + songObj.songId + "</p>" +
					  "<p>Artist Name: " + songObj.artistName + "</p>" +
					  "<p>Song Title: " + songObj.songTitle + "</p>" +
					  "<p>Song Length: " + songObj.songLength + "</p>" +
					  "<p>Data: " + songObj.data + "</p>" +
					  "<p>Provider Type: " + songObj.providerType + "</p>" +
					  "<p>Time Before Pause: " + songObj.tbpp + "</p>" +
					  "<p>Player Event: " + playerEventCodeString + "</p>";
					  
					  /*"<p>Prev Song Listening Duration / Time Before Pause: " + songObj.psld + "</p>"*/;
	
	$('.songInfo').html(songInfoStr);


	//console.log("inside validateSong");
	

	
	//return isValid;
	//var responseDataArray = [0,"unable to stream this song",9660,6,358,60];
	var responseDataArray = [1,"",60,6,358,5000];
	streamingValidationJS(responseDataArray);

}


function streamingValidationJS(responseDataJS) {
	
		
	//console.log('inside streamingValidationJS');
	
	var flash =	document.getElementById("fmp_player");
	
	flash.streamingValidationAS(responseDataJS);
	
}

//var lowStreamTime = 60;
function reportLowStreamTime(lsto) {
	console.log('lsld is ' + lsto.lsld);
	
	lowStreamTime = lowStreamTime - lsto.lsld;
	
	var flash =	document.getElementById("fmp_player");
	flash.reportNewStreamTime(lowStreamTime);
	
	//console.log(lsto);
}



function reportTime(amt) {
	
	$('.report_time').html("Current time is " + amt + " seconds.");
}



function flashConsole(msg) {
	
	console.log(msg);
}




/*
function reportTimeBetweenEvents(amt) {
	
	
	$('.timeBetweenEvents').html("Time between last event: " + amt + " seconds.");
}
*/

/*
function checkTime(obj) {
	
	var timeIsUp =  false;
	
	
	return timeIsUp;
	
	
}
*/



$(document).ready(function(){

	
	
/*
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
*/

	
	

});

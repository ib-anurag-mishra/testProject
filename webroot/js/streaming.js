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
	                    var flash =	document.getElementById("fmp_player");
	                    flash.pushNewSongsFromJS(popMostPopular);
	                    //Clear timer
	                    clearInterval(loadCheckInterval);
	                }
	            }, 500);
	        }
	    }, 200);
		
		
	}
	



	
	
}
*/

function pushSongs (newSongArray) {

	
	var flash =	document.getElementById("fmp_player");
	console.log(flash);
	flash.pushNewSongsFromJS(newSongArray);
	
}

function clearQueue () {
	
	var flash = document.getElementById("fmp_player");
	flash.clearQueueFromJS();
}



function reportTotalDuration(totalDuration) {
	$('.total_time').html("<br />Total time played in seconds: " + totalDuration + "<br /><br />");
	
}


function reportSongInfo(songObj) {
	
	$('.song_played').html("Current song being played: <br />" + "Playlist ID: " + songObj.playlistId + "<br />" + "Song ID: " + songObj.songId + "<br />" + "Label: " + songObj.label + "<br />" + "Artist Name: " + songObj.artistName + "<br />" + "Song Title: " + songObj.songTitle + "<br />" + "Song Length: " + songObj.songLength + "<br />" + "Song Data: " + songObj.data); 
}

function playPressed() {
	
	$('.current_action').html('<br />Current action:<br />play pressed');
}

function pausePressed() {
	
	$('.current_action').html('<br />Current action:<br />pause pressed');
}

function prevPressed() {
	$('.current_action').html('<br />Current action:<br />prev pressed');
	
}

function nextPressed() {
	
	$('.current_action').html('<br />Current action:<br />next pressed');
}


/* this is called before the song is played */ 
function validateSong(songObj, playerEventCode) {

	
	// properties sent from flash
	songObj.songProviderType = 'sony';
        songObj.songDuration = 60;
	plaulistId = songObj.playlistId;
	songId = songObj.songId;
        songProviderType = songObj.songProviderType;
	label = songObj.label; 
	artistName =  songObj.artistName;
	songTitle  = songObj.songTitle;
	songLength = songObj.songLength;
	data = songObj.data;
	songDuration = songObj.songDuration;
	
	
	
	// playerEventCode: 1 = Play, 2 = Pause, 3 = Prev, 4 = Next, 5 = Song Ended, 6 = Switch Stream
	
	var playerEventCodeString;
	
	switch(playerEventCode) {
		
		case 1:
			playerEventCodeString = "Play";
                        callStreamingComponent(songId,songProviderType,plaulistId,1,songLength,songDuration);
			break;
			
		case 2:
			playerEventCodeString = "Pause"
                        callStreamingComponent(songId,songProviderType,plaulistId,3,songLength,songDuration);
			break;
			
		case 3:
			playerEventCodeString = "Prev"
                        callStreamingComponent(songId,songProviderType,plaulistId,8,songLength,songDuration);
			break;
			
			
		case 4:
			playerEventCodeString = "Next"
                        callStreamingComponent(songId,songProviderType,plaulistId,9,songLength,songDuration);
			break;
			
		case 5:
			playerEventCodeString = "Song Ended"
                        callStreamingComponent(songId,songProviderType,plaulistId,5,songLength,songDuration);
			break;
			
		case 6:
			playerEventCodeString = "User choose another song in the queue"
                        callStreamingComponent(songId,songProviderType,plaulistId,'',songLength,songDuration);
			break;
			
	    case 7:
			playerEventCodeString = "Queue loaded"
                        callStreamingComponent(songId,songProviderType,plaulistId,'',songLength,songDuration);
			break;	    	
			
		
	}
	
	
	
	$('.playerEventCode').html("Player event code is: " + playerEventCodeString); 
	var isValid = true;
	return isValid;
}


function callStreamingComponent(prodId,providerType,queueId,eventFired,songLength,userStreamedTime){
    
        var postURL = webroot+'queuelistdetails/getPlaylistData';
        $.ajax({
            type: "POST",
            cache:false,
            url: postURL,
            data: {prodId : prodId,providerType : providerType,queueId : queueId,eventFired:eventFired,songLength:songLength,userStreamedTime:userStreamedTime}
        }).done(function(data){
//                var json = JSON.parse(data);
//                alert(json);exit;
//                if(json.error){
//                    alert(json.error[1]);
//					if(json.error[3] != 6){
//                                            var flash = document.getElementById("fmp_player");
//                                            flash.clearQueueFromJS();
//					}
//                }else if(json.success){
//                    
//                }
            
        })
        .fail(function(){
            alert('Ajax Call to Validate Playlist has been failed');
        });    
}

function pingTimeJS() {
	
	var flash = document.getElementById("fmp_player");
	flash.pingTime();	
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
var MechanicalBull = [
	{
		label:'Supersoaker',
		songTitle:'Supersoaker',
		artistName:'Kings Of Leon',
		songLength:400,
		data:'000/000/000/000/282/868/54/KingsOfLeon_Supersoaker_G010003006169j_1_1-256K_44S_2C_cbr1x.mp3?token=5a809d308b03ea40363a2'
		//data:'Boston_MoreThanAFeeling_G0100006706006_1_1-256K_44S_2C_cbr1x.mp3'
		
	},
  {
		label:'Rock City',
		songTitle:'Rock City',
		artistName:'Kings Of Leon',
		songLength:400,
		data:'000/000/000/000/282/868/55/KingsOfLeon_RockCity_G010003006169j_1_2-256K_44S_2C_cbr1x.mp3?token=5ecd3d549fcf7a5144635'
		
	},
 {
		label:'Don\'t Matter',
		songTitle:'Don\'t Matter',
		artistName:'Kings Of Leon',
		songLength:400,
		data:'000/000/000/000/282/868/56/KingsOfLeon_DontMatter_G010003006169j_1_3-256K_44S_2C_cbr1x.mp3?token=590ae4c1be0f83b5e8e9b'
		
	},
  {
		label:'Beautiful War',
		songTitle:'Beautiful War',
		artistName:'Kings Of Leon',
		songLength:400,
		data:'000/000/000/000/282/868/57/KingsOfLeon_BeautifulWar_G010003006169j_1_4-256K_44S_2C_cbr1x.mp3?token=5f3e40ee41975ded7a0a0'
		
	},
  {
		label:'Temple',
		songTitle:'Temple',
		artistName:'Kings Of Leon',
		songLength:400,
		data:'000/000/000/000/282/868/58/KingsOfLeon_Temple_G010003006169j_1_5-256K_44S_2C_cbr1x.mp3?token=5036570790117ebeb55d6'
		
	}
	
	

];


var popMostPopular = [
	{
		label:'Hot N Cold',
		songTitle:'Hot N Cold',
		artistName:'Katy Perry',
		songLength:220,
		data:'000/000/000/000/209/450/50/KatyPerry_HotNCold_G0100017710556_1_2-256K_44S_2C_cbr1x.mp3?token=5c1a131cab1d806dfc156'
		
	},
	{
		label:'Get Lucky',
		songTitle:'Get Lucky',
		artistName:'Daft Punk',
		songLength:248,
		data:'000/000/000/000/278/177/55/DaftPunkFeatPharrell_GetLucky_G0100029758145_1_1-256K_44S_2C_cbr1x.mp3?token=59ecf71c8d267a5cd49c9'
		
	},
	{
		label:'3 x 5',
		songTitle:'3 x 5',
		artistName:'John Mayer',
		songLength:290,
		data:'000/000/000/000/000/294/80/JohnMayer_3X5_G0100014157652_1_8-256K_44S_2C_cbr1x.mp3?token=5e9b2913bd18c3c8399dd'
		
	},
	{
		label:'Give Me A Reason',
		songTitle:'Give Me A Reason',
		artistName:'P!nk',
		songLength:243,
		data:'000/000/000/000/262/308/32/PnkFeatNateRuess_JustGiveMeAReason_G010002829359t_1_4-256K_44S_2C_cbr1x.mp3?token=58ebde353113cb72c3778'
		
	},
	{
		label:'Paper Doll',
		songTitle:'Paper Doll',
		artistName:'John Mayer',
		songLength:257,
		data:'000/000/000/000/281/947/74/JohnMayer_PaperDoll_G010003006184n_1_4-256K_44S_2C_cbr1x.mp3?token=5ab28d05110b35ef02777'
		
	},
	{
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
//swfobject.embedSWF("/app/webroot/swf/fmp.swf", "alt", "960", "100", "9.0.0", false, flashvars, params, attributes, swfCallback);
swfobject.embedSWF("fmp.swf", "alt", "960", "100", "9.0.0", false, flashvars, params, attributes, swfCallback);


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
	            }, 200);
	        }
	    }, 200);
		
		
	}
	



	
	
}


function pushSongs (newSongArray) {

	
	var flash =	document.getElementById("fmp_player");
	console.log(flash);
	flash.pushNewSongsFromJS(newSongArray);
	
}

function clearQueue () {
	
	var flash = document.getElementById("fmp_player");
	flash.clearQueueFromJS();
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
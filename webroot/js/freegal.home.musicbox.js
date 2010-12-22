/**
 * @file - freegal.home.musicbox.js
 * @author - deepakb (Mindfire Solution)
 * @version - $Id$
 * @date -16th Dec, 2010
 * JS functionality for home page Top Downloads|FreegalMusic box
 **/ 

function getMusicBox(type) {
	$('#loaderDivMusicBox').show();
	$.post(webroot+'homes/music_box', { type : type },function(data) {
		$('#musicbox').html();
		$('#musicbox').html(data);
		$('#loaderDivMusicBox').hide();
	});
}

function filterTD(div) {
	if (div == 'tab1') {
		
		$('#tab2').hide();
		$('#tab1').fadeIn();
		$('#t2').attr('class', '');
		$('#t1').attr('class', 'active');
	} else {
		
		$('#tab1').hide();
		$('#tab2').fadeIn();
		$('#t1').attr('class', '');
		$('#t2').attr('class', 'active');
	}
}
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
	$('#tb1').css('font-weight', 'normal');
	$('#tb2').css('font-weight', 'normal');
	if (div == 'tab1') {
		$('#tab2').hide();
		$('#tab1').fadeIn();
		$('#tb1').css('font-weight', 'bold');
	} else {
		$('#tab1').hide();
		$('#tab2').fadeIn();
		$('#tb2').css('font-weight', 'bold');
	}
}
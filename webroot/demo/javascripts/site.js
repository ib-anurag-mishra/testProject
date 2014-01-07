var flashvars = {};
var params = {};
var attributes = {};
attributes.id = "fmp_player";
swfobject.switchOffAutoHideShow();
swfobject.embedSWF("swf/fmp.swf?"+(Math.random()*1000000), "alt", "960", "100", "9.0.0", false, flashvars, params, attributes);

$(document).ready(function(){
	
	

	
	$('.my-account-menu').on('click',function(){
		
		$('.account-menu-dropdown').toggleClass('active');
		
	});
	
	$('.account-menu-dropdown').on('mouseleave',function(){
		
		$(this).removeClass('active');
	});
	
	var ulPosition;

	
	$('.left-scroll-button').on('click',function(){
		
		ulPosition = parseInt($(this).siblings('.top-albums-carousel').find('ul').css("margin-left"));
		if(ulPosition !== 0) {
		
			ulPosition = ulPosition + 860;
	
			
			
			$(this).siblings('.top-albums-carousel').find('ul').animate({
				
				marginLeft:ulPosition
			});
		}
		
	});
	
	
	$('.right-scroll-button').on('click',function(){
		
		ulPosition = parseInt($(this).siblings('.top-albums-carousel').find('ul').css("margin-left"));
		if(ulPosition !== -3440) {
		
			ulPosition = ulPosition - 860;
	
			
			
			$(this).siblings('.top-albums-carousel').find('ul').animate({
				
				marginLeft:ulPosition
			});
		}
		
	});
	
	$('.freegal-playlist-subcat-toggle').on('click',function(){
		
		$('.freegal-playlist-subcat').toggleClass('active');
	});
	
	$('.my-playlist-subcat-toggle').on('click',function(){
		
		$('.my-playlist-subcat').toggleClass('active');
	});
});
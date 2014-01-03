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
	
	$('.album-scroll-left').on('click',function(){
		
		ulPosition = parseInt($(this).parent('.album-navigation-container').siblings('.album-list-container').find('ul').css("margin-left"));
		
		if(ulPosition !== 0) {
		
			ulPosition = ulPosition + 860;
	
			
			
			$(this).parent('.album-navigation-container').siblings('.album-list-container').find('ul').animate({
				
				marginLeft:ulPosition
			});
		}
	});
	
	
	$('.album-scroll-right').on('click',function(){
	
		ulPosition = parseInt($(this).parent('.album-navigation-container').siblings('.album-list-container').find('ul').css("margin-left"));
		
		
		
		

		if(ulPosition !== -3440) {
		
			ulPosition = ulPosition - 860;
	


			$(this).parent('.album-navigation-container').siblings('.album-list-container').find('ul').animate({
				
				marginLeft:ulPosition
			});

		}
		
		
	});
});
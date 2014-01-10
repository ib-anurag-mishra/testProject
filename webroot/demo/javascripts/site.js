var flashvars = {};
var params = {};
var attributes = {};
attributes.id = "fmp_player";
swfobject.switchOffAutoHideShow();
swfobject.embedSWF("swf/fmp.swf?"+(Math.random()*1000000), "alt", "960", "100", "9.0.0", false, flashvars, params, attributes);

(function($) {

    $.fn.bindMouseWheel = function() {

        return this.each(function() {

            $(this).bind('mousewheel', function(e) {

                $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);
                //prevent page fom scrolling
                return false;

            });


        });

    };


}(jQuery));

$(document).ready(function(){
	
	$('.album-cover-container ul').bindMouseWheel();
	
	
	$('.album-cover-container').on('mouseenter',function(){
		$(this).find('.playlist-menu-icon').addClass('active');
		
	});
	
	$('.album-cover-container').on('mouseleave',function(){
		$(this).find('.playlist-menu-icon').removeClass('active');
		
	});
	
	$('.playlist-menu-icon').on('click',function(){
		
		$(this).siblings('ul').toggleClass('active');
	});
	
	$('.album-cover-container ul').on('mouseleave',function(){
		$(this).removeClass('active');
		
	});
	
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
	
	$('.freegal-playlist-subcat-toggle').on('click',function(e){
		e.preventDefault();
		
		$('.freegal-playlist-subcat').toggleClass('active');
	});
	
	$('.my-playlist-subcat-toggle').on('click',function(){
		
		$('.my-playlist-subcat').toggleClass('active');
	});
	
	$('.featured-grid-item').on('mouseenter',function(){
		$(this).find('.featured-grid-menu').addClass('active');
		
	});
	
	$('.featured-grid-item').on('mouseleave',function(){
		$(this).find('.featured-grid-menu').removeClass('active');
		
	});
	
});
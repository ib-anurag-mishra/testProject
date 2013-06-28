$(document).ready(function(){
	
	$('.my-playlists-page .playlist-filter-container .playlist-filter-button').addClass('active');
	
	$('.my-playlists-page .playlist-filter-container .create-playlist-button').on('mousedown',function(e){
		$(this).addClass('pressed');
	});
	
	
	$('.my-playlists-page .playlist-filter-container .create-playlist-button').on('mouseup',function(e){
		$(this).removeClass('pressed');
	});
	
	
	$('.my-playlists-page .filter-button').on('click',function(e){
		if($(this).hasClass('active')) {
			
			if($(this).hasClass('toggled')) {
				
				$(this).removeClass('toggled');
			} else {
				
				$(this).addClass('toggled');
			}
			
			
		} else {
			$('.my-playlists-page .filter-button').removeClass('active');
			$(this).addClass('active');
			
		}
		
		
	});
	
	
	$('.my-playlists-page .playlists-scrollable').bind('mousewheel',function(e){
		

		$(this).scrollTop($(this).scrollTop()-e.originalEvent.wheelDeltaY);
		
		
		
		

	    //prevent page fom scrolling
	    return false;

		
	});
	
	$('.my-playlists-page .add-to-playlist-button').on('click',function(e){
	
		$(this).siblings('.wishlist-popover').addClass('active');
	});
	
	$('.my-playlists-page .wishlist-popover').on('mouseleave',function(e){
	
		$(this).removeClass('active');
	});
	
});
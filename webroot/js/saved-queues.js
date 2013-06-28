$(document).ready(function(){
	
	$('.saved-queues-page .playlist-filter-container .playlist-filter-button').addClass('active');
	
	$('.saved-queues-page .playlist-filter-container .create-playlist-button').on('mousedown',function(e){
		$(this).addClass('pressed');
	});
	
	
	$('.saved-queues-page .playlist-filter-container .create-playlist-button').on('mouseup',function(e){
		$(this).removeClass('pressed');
	});
	
	
	$('.saved-queues-page .filter-button').on('click',function(e){
		if($(this).hasClass('active')) {
			
			if($(this).hasClass('toggled')) {
				
				$(this).removeClass('toggled');
			} else {
				
				$(this).addClass('toggled');
			}
			
			
		} else {
			$('.saved-queues-page .filter-button').removeClass('active');
			$(this).addClass('active');
			
		}
		
		
	});
	
	
	$('.saved-queues-page .playlists-scrollable').bind('mousewheel',function(e){
		

		$(this).scrollTop($(this).scrollTop()-e.originalEvent.wheelDeltaY);
		
		
		
		

	    //prevent page fom scrolling
	    return false;

		
	});
	
	$('.saved-queues-page .add-to-playlist-button').on('click',function(e){
	
		$(this).siblings('.wishlist-popover').addClass('active');
	});
	
	$('.saved-queues-page .wishlist-popover').on('mouseleave',function(e){
	
		$(this).removeClass('active');
	});
	
});
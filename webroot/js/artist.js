$(document).ready(function(){
	
	$('.artist-page .artist-filter-button').addClass('active');
	
	$('.artist-page .artist-downloads-filter-container div').on('click',function(e){
		if($(this).hasClass('active')) {
			
			if($(this).hasClass('toggled')) {
				
				$(this).removeClass('toggled');
				
			} else {
				
				$(this).addClass('toggled');
			}
			
			
		} else {
			$('.artist-page .artist-downloads-filter-container div').removeClass('active');
			$(this).addClass('active');
			
			
		}
		
		
	});	
	
	
	$('.artist-page .tracklist-scrollable').bind('mousewheel',function(e){
		

		$(this).scrollTop($(this).scrollTop()-e.originalEvent.wheelDeltaY);
		
		
		
		

	    //prevent page fom scrolling
	    return false;

		
	});
	
	
	$('.artist-page .tracklist-scrollable .wishlist-popover').slice(0,3).addClass('top');
	

	$('.artist-page .tracklist-scrollable').on('scroll',function(e){

		$('.artist-page .tracklist-scrollable .wishlist-popover').removeClass('top');
		

		$('.artist-page .tracklist-scrollable .tracklist').each(function(e){
			
			if($(this).position().top >= -22 && $(this).position().top <= 110) {
				
				
				

				$(this).find('.wishlist-popover').addClass('top');
				
				
				
			}
		
		});
		
	});
	
	
	$('.artist-page .tracklist-scrollable .tracklist .preview').on('click',function(e){
		
		if($(this).hasClass('playing')) {
			
			$(this).removeClass('playing');
			
			$(this).parents('.row').removeClass('playing');
			$(this).parent().removeClass('playing');
			$(this).siblings('.date').removeClass('playing');
			$(this).siblings('.album').removeClass('playing');
			$(this).siblings('.artist').removeClass('playing');
			$(this).siblings('.time').removeClass('playing');
			$(this).siblings('.song').removeClass('playing');
			$(this).siblings('.add-to-wishlist-button').removeClass('playing');
			$(this).siblings('.download').removeClass('playing');
			
			
		} else {
		
			$('.artist-page .tracklist-scrollable .tracklist').removeClass('playing');
			$('.artist-page .tracklist-scrollable .tracklist .date').removeClass('playing');
			$('.artist-page .tracklist-scrollable .tracklist .preview').removeClass('playing');
			$('.artist-page .tracklist-scrollable .tracklist .album').removeClass('playing');
			$('.artist-page .tracklist-scrollable .tracklist .artist').removeClass('playing');
			$('.artist-page .tracklist-scrollable .tracklist .time').removeClass('playing');
			$('.artist-page .tracklist-scrollable .tracklist .song').removeClass('playing');
			$('.artist-page .tracklist-scrollable .tracklist .add-to-wishlist-button').removeClass('playing');
			$('.artist-page .tracklist-scrollable .tracklist .download').removeClass('playing');
		
			$(this).addClass('playing');
			$(this).parents('.tracklist').addClass('playing');
			$(this).parent().addClass('playing');
			$(this).siblings('.date').addClass('playing');
			$(this).siblings('.album').addClass('playing');
			$(this).siblings('.artist').addClass('playing');
			$(this).siblings('.time').addClass('playing');
			$(this).siblings('.song').addClass('playing');
			$(this).siblings('.add-to-wishlist-button').addClass('playing');
			$(this).siblings('.download').addClass('playing');
			
			
		}
		
	});
	
	
	
	
	
});
$(document).ready(function(){
	
	$('.playlist-controls-container .prev-btn').on('mousedown',function(e){
		$(this).addClass('clicked');
		
	});
	
	$('.playlist-controls-container .prev-btn').on('mouseup',function(e){
		$(this).removeClass('clicked');
		
	});
	
	$('.playlist-controls-container .pause-btn').on('click',function(e){
	
		if($(this).hasClass('active')) {
			
			$(this).removeClass('active');
		} else {
			
			$(this).addClass('active');
			$(this).siblings('.play-btn').removeClass('active');
			//$('.album-info-playlist-container .album-info-container .album-cover-container:after').show();
		}
	});
	
	
	$('.playlist-controls-container .play-btn').on('click',function(e){
	
		if($(this).hasClass('active')) {
			
			$(this).removeClass('active');
		} else {
			
			$(this).addClass('active');
			$(this).siblings('.pause-btn').removeClass('active');
		}
	});
	
	$('.playlist-controls-container .next-btn').on('mousedown',function(e){
		$(this).addClass('clicked');
		
	});
	
	$('.playlist-controls-container .next-btn').on('mouseup',function(e){
		$(this).removeClass('clicked');
		
	});
	
	$('.playlist-controls-container .shuffle-btn').on('click',function(e){
	
		if($(this).hasClass('active')) {
			
			$(this).removeClass('active');
		} else {
			
			$(this).addClass('active');
			
		}
	});
	
	$('.playlist-controls-container .repeat-btn').on('click',function(e){
	
		if($(this).hasClass('active')) {
			
			$(this).removeClass('active');
		} else {
			
			$(this).addClass('active');
			
		}
	});
	
	
	$('.playlist-page .playlist-filter-container .artist-filter-button').addClass('active')
	
	$('.playlist-page .playlist-filter-container div').on('click',function(e){
		if($(this).hasClass('active')) {
			
			if($(this).hasClass('toggled')) {
				
				$(this).removeClass('toggled');
				
			} else {
				
				$(this).addClass('toggled');
			}
			
			
		} else {
			$('.playlist-page .playlist-filter-container div').removeClass('active');
			$(this).addClass('active');
			
			
		}
		
		
	});
	
	$('.playlist-page .playlist-scrollable').bind('mousewheel',function(e){
		

		$(this).scrollTop($(this).scrollTop()-e.originalEvent.wheelDeltaY);
		
		
		
		

	    //prevent page fom scrolling
	    return false;

		
	});
	
	$('.playlist-page .right-col .add-to-wishlist-button').on('click',function(e){
		e.preventDefault();
	
		$(this).siblings('.wishlist-popover').addClass('active');
	});
	
	$('.playlist-page .right-col .wishlist-popover').on('mouseleave',function(e){
	
		$(this).removeClass('active');
	});
	
	
	
	
	
	$('.playlist-page .playlist-scrollable .wishlist-popover').slice(0,3).addClass('top');
	

	$('.playlist-page .playlist-scrollable').on('scroll',function(e){

		$('.playlist-page .playlist-scrollable .wishlist-popover').removeClass('top');
		

		$('.playlist-page .playlist-scrollable .row').each(function(e){
			
			if($(this).position().top >= -22 && $(this).position().top <= 110) {
				
				
				

				$(this).find('.wishlist-popover').addClass('top');
				
				
				
			}
		
		});
		
	});
	
	
	$('.playlist-page .playlist-scrollable .row').on('mouseenter',function(){
		
		$(this).find('.album-title').addClass('hovered');
		$(this).find('.artist-name').addClass('hovered');
		$(this).find('.time').addClass('hovered');
		$(this).find('.song-title').addClass('hovered');
		$(this).find('.preview').addClass('hovered');
		$(this).find('.add-to-wishlist-button').addClass('hovered');
		
	});
	
	$('.playlist-page .playlist-scrollable .row').on('mouseleave',function(){
		
		$(this).find('.album-title').removeClass('hovered');
		$(this).find('.artist-name').removeClass('hovered');
		$(this).find('.time').removeClass('hovered');
		$(this).find('.song-title').removeClass('hovered');
		$(this).find('.preview').removeClass('hovered');
		$(this).find('.add-to-wishlist-button').removeClass('hovered');
		
	});
	
	
	

	$('.playlist-page .playlist-scrollable .row .preview').on('mouseenter',function(e){
		$(this).removeClass('hovered').addClass('blue-bkg');
	
	});
	
	$('.playlist-page .playlist-scrollable .row .preview').on('mouseleave',function(e){
		$(this).removeClass('blue-bkg').addClass('hovered');
	
	});
	
	$('.playlist-page .playlist-scrollable .row .preview').on('click',function(e){
		
		if($(this).hasClass('playing')) {
			
			$(this).removeClass('playing');
			
			$(this).parents('.row').removeClass('playing');
			$(this).parent().removeClass('playing');
			$(this).parent().siblings('.album-title').removeClass('playing');
			$(this).parent().siblings('.artist-name').removeClass('playing');
			$(this).parent().siblings('.time').removeClass('playing');
			$(this).parent().siblings('.song-title').removeClass('playing');
			$(this).parent().siblings('.add-to-wishlist-button').removeClass('playing');
			
			
		} else {
		
			$('.playlist-page .playlist-scrollable .row').removeClass('playing');
			$('.playlist-page .playlist-scrollable .row .preview').removeClass('playing');
			$('.playlist-page .playlist-scrollable .row .album-title').removeClass('playing');
			$('.playlist-page .playlist-scrollable .row .artist-name').removeClass('playing');
			$('.playlist-page .playlist-scrollable .row .time').removeClass('playing');
			$('.playlist-page .playlist-scrollable .row .song-title').removeClass('playing');
			$('.playlist-page .playlist-scrollable .row .add-to-wishlist-button').removeClass('playing');
		
			$(this).addClass('playing');
			$(this).parents('.row').addClass('playing');
			$(this).parent().addClass('playing');
			$(this).parent().siblings('.album-title').addClass('playing');
			$(this).parent().siblings('.artist-name').addClass('playing');
			$(this).parent().siblings('.time').addClass('playing');
			$(this).parent().siblings('.song-title').addClass('playing');
			$(this).parent().siblings('.add-to-wishlist-button').addClass('playing');
			
			
		}
		
	});

	
	
	
	
	
});
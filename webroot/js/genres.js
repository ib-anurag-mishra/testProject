$(document).ready(function(){
	
	
	
	
	$('.genre-list').bind('mousewheel',function(e){
	
	
		
		$(this).scrollTop($(this).scrollTop()-e.originalEvent.wheelDeltaY);
		
		
		
		

	    //prevent page fom scrolling
	    return false;
		
	});
	
	$('.alphabetical-filter').bind('mousewheel',function(e){
	
	
		
		$(this).scrollTop($(this).scrollTop()-e.originalEvent.wheelDeltaY);
		
		
		
		

	    //prevent page fom scrolling
	    return false;
		
	});
	
	
	$('.artist-list').bind('mousewheel',function(e){
	
	
		
		$(this).scrollTop($(this).scrollTop()-e.originalEvent.wheelDeltaY);
		
		
		
		

	    //prevent page fom scrolling
	    return false;
		
	});
	
	$('.album-list').bind('mousewheel',function(e){
	
	
		
		$(this).scrollTop($(this).scrollTop()-e.originalEvent.wheelDeltaY);
		
		
		
		

	    //prevent page fom scrolling
	    return false;
		
	});
	
	$('.tracklist .preview').on('click',function(e){
		e.preventDefault();
		
	});
	
	$('.tracklist .add-to-playlist-button').on('click',function(e){
		e.preventDefault();
		$(this).siblings('.wishlist-popover').addClass('active');
	});
	
	
	
	$('.genre-list a').on('click',function(e){
		var genre_type = $(this).data('genre');
		console.log('genre is ' + genre_type);
	});
	
	$('.alphabetical-filter a').on('click',function(e){
		var letter = $(this).data('letter');
		console.log('letter is ' + letter);
	});
	
	$('.artist-list a').on('click',function(e){
		var artist = $(this).data('artist');
		console.log('artist is ' + artist);
	});
	
	$('.more-by').on('mousedown',function(e){
		
		$(this).css('background','url(images/genres/more-by-click.jpg)')
	
	});
	
	
	$('.album-image a').on('click',function(e){
		
		$('.album-image').removeClass('selected');
		$(this).parent('.album-image').addClass('selected');
	});
	
	
	
	$('.genres-page .tracklist .preview').on('click',function(e){
	

		
		$('.tracklist').removeClass('playing');
		$('.preview').removeClass('playing');
		$('.song').removeClass('playing');
		$('.artist').removeClass('playing');
		$('.time').removeClass('playing');
		
		$(this).parent('.tracklist').addClass('playing');
		$(this).addClass('playing');
		$(this).siblings('.song').addClass('playing');
		$(this).siblings('.artist').addClass('playing');
		$(this).siblings('.time').addClass('playing');
	});
	
	
	
	
	
	
});
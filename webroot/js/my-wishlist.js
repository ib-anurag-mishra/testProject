$(document).ready(function(){
	
	$('.my-wishlist-page .date-filter-button').addClass('active');
	$('.my-wishlist-page .music-filter-button').addClass('active');
	
	
	$('.my-wishlist-page .my-wishlist-filter-container div.filter').on('click',function(e){
            
            	if($(this).hasClass('date-filter-button')){
            $('#sortForm #sort').val('date');
        } else if($(this).hasClass('song-filter-button')){
            $('#sortForm #sort').val('song');
        } else if($(this).hasClass('artist-filter-button')){
            $('#sortForm #sort').val('artist');
        } else if($(this).hasClass('album-filter-button')){
            $('#sortForm #sort').val('album');
        }
		if($(this).hasClass('active')) {
			
			if($(this).hasClass('toggled')) {
				
				$(this).removeClass('toggled');
                                $('#sortForm #sortOrder').val('asc');
				
			} else {
				
				$(this).addClass('toggled');
                                $('#sortForm #sortOrder').val('desc');
			}
			
			
		} else {
			$('.my-wishlist-page .my-wishlist-filter-container div.filter').removeClass('active');
			$(this).addClass('active');
                        $('#sortForm #sortOrder').val('asc');
			
			
		}
		
		$('#sortForm').submit();
	});
	
	
	$('.my-wishlist-page .my-wishlist-filter-container div.tab').on('click',function(e){
		if($(this).hasClass('active')) {
			
			if($(this).hasClass('toggled')) {
				
				$(this).removeClass('toggled');
				
			} else {
				
				$(this).addClass('toggled');
			}
			
			
		} else {
			$('.my-wishlist-page .my-wishlist-filter-container div.tab').removeClass('active');
			$(this).addClass('active');
			
			
		}
		
		
	});
	
	$('.my-wishlist-page .my-wishlist-scrollable').bind('mousewheel',function(e){
		

		$(this).scrollTop($(this).scrollTop()-e.originalEvent.wheelDeltaY);
		
		
		
		

	    //prevent page fom scrolling
	    return false;

		
	});
	
	$('.my-wishlist-page .my-video-wishlist-scrollable').bind('mousewheel',function(e){
		

		$(this).scrollTop($(this).scrollTop()-e.originalEvent.wheelDeltaY);
		
		
		
		

	    //prevent page fom scrolling
	    return false;

		
	});
	
	
	
	
	
	$('.my-wishlist-page .add-to-wishlist-button').on('click',function(e){
		e.preventDefault();
	
		$(this).siblings('.wishlist-popover').addClass('active');
	});
	
	$('.my-wishlist-page .wishlist-popover').on('mouseleave',function(e){
	
		$(this).removeClass('active');
	});
	
	
	
	
	
	$('.my-wishlist-page .my-wishlist-scrollable .wishlist-popover').slice(0,3).addClass('top');
	

	$('.my-wishlist-page .my-wishlist-scrollable').on('scroll',function(e){

		$('.my-wishlist-page .my-wishlist-scrollable .wishlist-popover').removeClass('top');
		

		$('.my-wishlist-page .my-wishlist-scrollable .row').each(function(e){
			
			if($(this).position().top >= -22 && $(this).position().top <= 110) {
				
				
				

				$(this).find('.wishlist-popover').addClass('top');
				
				
				
			}
		
		});
		
	});
	
	
	$('.my-wishlist-page .my-wishlist-scrollable .row').on('mouseenter',function(){
		$(this).find('.date').addClass('hovered');
		$(this).find('.album-title').addClass('hovered');
		$(this).find('.artist-name').addClass('hovered');
		$(this).find('.time').addClass('hovered');
		$(this).find('.song-title').addClass('hovered');
		$(this).find('.preview').addClass('hovered');
		$(this).find('.add-to-wishlist-button').addClass('hovered');
		
	});
	
	$('.my-wishlist-page .my-video-wishlist-scrollable .row').on('mouseleave',function(){
		$(this).find('.date').removeClass('hovered');
		$(this).find('.album-title').removeClass('hovered');
		$(this).find('.artist-name').removeClass('hovered');
		$(this).find('.time').removeClass('hovered');
		$(this).find('.song-title').removeClass('hovered');
		$(this).find('.preview').removeClass('hovered');
		$(this).find('.add-to-wishlist-button').removeClass('hovered');
		
	});

	$('.my-wishlist-page .my-video-wishlist-scrollable .row').on('mouseenter',function(){
		$(this).find('.date').addClass('hovered');
		$(this).find('.album-title').addClass('hovered');
		$(this).find('.artist-name').addClass('hovered');
		$(this).find('.time').addClass('hovered');
		$(this).find('.song-title').addClass('hovered');
		$(this).find('.preview').addClass('hovered');
		$(this).find('.add-to-wishlist-button').addClass('hovered');
		
	});
	
	$('.my-wishlist-page .my-video-wishlist-scrollable .row').on('mouseleave',function(){
		$(this).find('.date').removeClass('hovered');
		$(this).find('.album-title').removeClass('hovered');
		$(this).find('.artist-name').removeClass('hovered');
		$(this).find('.time').removeClass('hovered');
		$(this).find('.song-title').removeClass('hovered');
		$(this).find('.preview').removeClass('hovered');
		$(this).find('.add-to-wishlist-button').removeClass('hovered');
		
	});





	$('.my-wishlist-page .my-wishlist-scrollable .row .preview').on('mouseenter',function(e){
		$(this).removeClass('hovered').addClass('blue-bkg');
	
	});
	
	$('.my-wishlist-page .my-wishlist-scrollable .row .preview').on('mouseleave',function(e){
		$(this).removeClass('blue-bkg').addClass('hovered');
	
	});
	
	$('.my-wishlist-page .my-wishlist-scrollable .row .preview').on('click',function(e){
		
		if($(this).hasClass('playing')) {
			
			$(this).removeClass('playing');
			
			$(this).parents('.row').removeClass('playing');
			$(this).parent().removeClass('playing');
			$(this).siblings('.date').removeClass('playing');
			$(this).siblings('.album-title').removeClass('playing');
			$(this).siblings('.artist-name').removeClass('playing');
			$(this).siblings('.time').removeClass('playing');
			$(this).siblings('.song-title').removeClass('playing');
			$(this).siblings('.add-to-wishlist-button').removeClass('playing');
			$(this).siblings('.download').removeClass('playing');
			
			
		} else {
		
			$('.my-wishlist-page .my-wishlist-scrollable .row').removeClass('playing');
			$('.my-wishlist-page .my-wishlist-scrollable .row .date').removeClass('playing');
			$('.my-wishlist-page .my-wishlist-scrollable .row .preview').removeClass('playing');
			$('.my-wishlist-page .my-wishlist-scrollable .row .album-title').removeClass('playing');
			$('.my-wishlist-page .my-wishlist-scrollable .row .artist-name').removeClass('playing');
			$('.my-wishlist-page .my-wishlist-scrollable .row .time').removeClass('playing');
			$('.my-wishlist-page .my-wishlist-scrollable .row .song-title').removeClass('playing');
			$('.my-wishlist-page .my-wishlist-scrollable .row .add-to-wishlist-button').removeClass('playing');
			$('.my-wishlist-page .my-wishlist-scrollable .row .download').removeClass('playing');
		
			$(this).addClass('playing');
			$(this).parents('.row').addClass('playing');
			$(this).parent().addClass('playing');			$(this).siblings('.date').addClass('playing');
			$(this).siblings('.album-title').addClass('playing');
			$(this).siblings('.artist-name').addClass('playing');
			$(this).siblings('.time').addClass('playing');
			$(this).siblings('.song-title').addClass('playing');
			$(this).siblings('.add-to-wishlist-button').addClass('playing');
			$(this).siblings('.download').addClass('playing');			
			
			
		}
		
	});
        
    $('.video-filter-button').click(function(){
       $(this).addClass('active');
       $('.music-filter-button').removeClass('active');
       $('.my-wishlist-shadow-container').hide();
       $('.my-video-wishlist-shadow-container').show();
       
    });
    
    $('.music-filter-button').click(function(){
       $(this).addClass('active');
       $('.video-filter-button').removeClass('active');
       $('.my-video-wishlist-shadow-container').hide();
       $('.my-wishlist-shadow-container').show();
    });
	
});
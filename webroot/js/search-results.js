$(document).ready(function(){
	
	$('#query').keypress(function(event) {
		//auto_check();
		if (event.which == '13') {
	  //alert($('#search_query').val());
	  $('#searchQueryForm').submit();
		}
	});
	$("#query").autocomplete(webroot+"search/autocomplete",
	{
		minChars: 1,
		cacheLength: 10,
		autoFill: false,
		extraParams: {
		type:$('#search_type').val(),
        ufl:'1'
    },
	formatItem:function(data){
		return data[0];
	},
	formatResult:function(data){
		return data[1];
	}
	}).result(function(e, item) {
		$('#auto').attr('value', 1);
		if(item[2]==1){
			$('#search_type').val('artist');
		} else if(item[2]==2){
			$('#search_type').val('album');
		} else if(item[2]==3){
			$('#search_type').val('song');
		}
	});

	$('.search-page .tracklist .preview').on('click',function(e){
	

		
		$('.tracklist').removeClass('playing');
		$('.preview').removeClass('playing');
		$('.song').removeClass('playing');
		$('.artist').removeClass('playing');
		$('.time').removeClass('playing');
		$('.album').removeClass('playing');
		$('.download').removeClass('playing');
		$('.composer').removeClass('playing');
		
		$(this).parent('.tracklist').addClass('playing');
		$(this).addClass('playing');
		$(this).siblings('.song').addClass('playing');
		$(this).siblings('.artist').addClass('playing');
		$(this).siblings('.time').addClass('playing');
		$(this).siblings('.album').addClass('playing');
		$(this).siblings('.download').addClass('playing');
		$(this).siblings('.composer').addClass('playing');
	});
	
	
	
	$('.search-page .tracklist-scrollable').bind('mousewheel',function(e){
		

		$(this).scrollTop($(this).scrollTop()-e.originalEvent.wheelDeltaY);
		
		
		
		

	    //prevent page fom scrolling
	    return false;

		
	});
	
	$('.search-page .advanced-artists-scrollable').bind('mousewheel',function(e){
		

		$(this).scrollTop($(this).scrollTop()-e.originalEvent.wheelDeltaY);
		
		
		
		

	    //prevent page fom scrolling
	    return false;

		
	});
	
	
	$('.search-page .advanced-composers-scrollable').bind('mousewheel',function(e){
		

		$(this).scrollTop($(this).scrollTop()-e.originalEvent.wheelDeltaY);
		
		
		
		

	    //prevent page fom scrolling
	    return false;

		
	});
	
	
	$('.search-page .advanced-genres-scrollable').bind('mousewheel',function(e){
		

		$(this).scrollTop($(this).scrollTop()-e.originalEvent.wheelDeltaY);
		
		
		
		

	    //prevent page fom scrolling
	    return false;

		
	});
	
	$('.search-page .advanced-labels-scrollable').bind('mousewheel',function(e){
		

		$(this).scrollTop($(this).scrollTop()-e.originalEvent.wheelDeltaY);
		
		
		
		

	    //prevent page fom scrolling
	    return false;

		
	});
	
	
	
	
	// $('.advanced-search li:first-child a').addClass('active');

	
	$('.tracklist-header .album').addClass('active');
	
	$('.tracklist-header span').on('click',function(e){
		if($(this).hasClass('active')) {
			
			if($(this).hasClass('toggled')) {
				
				$(this).removeClass('toggled');
			} else {
				
				$(this).addClass('toggled');
			}
			
			
		} else {
			$('.tracklist-header span').removeClass('active');
			$(this).addClass('active');
			
		}
		
	});
	
	
	$('.search-page .wishlist-popover').slice(0,3).addClass('top');
	$('.search-page .tracklist').slice(0,3).addClass('current');

	$('.search-page .tracklist-scrollable').on('scroll',function(e){

		$('.search-page .wishlist-popover').removeClass('top');
		$('.search-page .tracklist').removeClass('current');

		$('.search-page .tracklist').each(function(e){
			
			if($(this).position().top >= -22 && $(this).position().top <= 110) {
				
				
				$(this).addClass('current');

				$(this).find('.wishlist-popover').addClass('top');
				
				
				
			}
		
		});
		
	});
	
	
	$('.search-page .advanced-search #submit').on('mousedown',function(e){
	
		
		$(this).addClass('clicked');
		
	});
	
	$('.search-page .advanced-search #submit').on('mouseup',function(e){
	
		
		$(this).removeClass('clicked');
		
	});
	
	$('.pagination a').on('click',function(e){
		//e.preventDefault();
		
		var target = $(this).attr('href');
		
		//console.log($(target).position().top);
		
		/*
		$('.tracklist-scrollable').animate({
		
			scrollTop: $(target).position().top
		},500);
		*/

		
		
		
	});
	
});
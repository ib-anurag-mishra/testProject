$(document).ready(function(){
	
	
	
	$('.now-streaming-page .album-cover-container').on('mouseenter',function(e){
		
	
		$(this).find('.download-now-button').css('opacity',1);
		// $(this).find('.add-to-playlist-button').css('opacity',1);
			
		
	});
	
	
	$('.now-streaming-page .album-cover-container').on('mouseleave',function(e){
		
	
		$(this).find('.download-now-button').css('opacity',.6);
		// $(this).find('.add-to-playlist-button').css('opacity',.6);
			
		
	})
	
	/*
	
	$('.now-streaming-page .song-detail .controls .play-pause').on('mousedown',function(e){
	
		
		
		

			
		if($(this).hasClass('paused')) {
			
			$(this).removeClass('paused');
			
		} else {
			
			$(this).addClass('paused');
		}


		
		
	});
	
	
	$('.now-streaming-page .song-detail .controls .play-pause').on('mouseup',function(e){
	
		
		
		

		
		
	});
	
	*/
	
	$('.now-streaming-page .song-detail .controls .next').on('mousedown',function(e){
	
		$(this).css('background','img/now-streaming/next-pressed.png');
	});
	
	$('.now-streaming-page .song-detail .controls .next').on('mouseup',function(e){
	
		$(this).css('background','img/now-streaming/next-btn.png');
	});
	
	
});
$(document).ready(function(){
	
	$(document).on('mouseenter','.songs-scrollable .song-container',function(){
		$(this).find('.add-to-playlist-button').css({opacity:1});
		$(this).find('.top-10-download-now-button').css({opacity:1});
		$(this).find('.preview').css({opacity:1});
		
	});
	
	$(document).on('mouseleave','.songs-scrollable .song-container',function(){
		$(this).find('.add-to-playlist-button').css({opacity:0});
		$(this).find('.top-10-download-now-button').css({opacity:0});
		$(this).find('.preview').css({opacity:0});
		
	});
	
	$(document).on('mouseenter','.videos-scrollable .video-container',function(){
		$(this).find('.add-to-playlist-button').css({opacity:1});
		$(this).find('.top-10-download-now-button').css({opacity:1});
		
		
	});
	
	$(document).on('mouseleave','.videos-scrollable .video-container',function(){
		$(this).find('.add-to-playlist-button').css({opacity:0});
		$(this).find('.top-10-download-now-button').css({opacity:0});
		
		
	});
	
});
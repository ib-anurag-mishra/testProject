$(document).ready(function(){
	
	$('.songs-scrollable .song-container').on('mouseenter',function(){
		$(this).find('.add-to-playlist-button').css({opacity:1});
		$(this).find('.top-10-download-now-button').css({opacity:1});
		$(this).find('.preview').css({opacity:1});
		
	});
	
	$('.songs-scrollable .song-container').on('mouseleave',function(){
		$(this).find('.add-to-playlist-button').css({opacity:0});
		$(this).find('.top-10-download-now-button').css({opacity:0});
		$(this).find('.preview').css({opacity:0});
		
	});
	
	$('.videos-scrollable .video-container').on('mouseenter',function(){
		$(this).find('.add-to-playlist-button').css({opacity:1});
		$(this).find('.top-10-download-now-button').css({opacity:1});
		
		
	});
	
	$('.videos-scrollable .video-container').on('mouseleave',function(){
		$(this).find('.add-to-playlist-button').css({opacity:0});
		$(this).find('.top-10-download-now-button').css({opacity:0});
		
		
	});
	
});
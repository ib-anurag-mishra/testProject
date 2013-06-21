$(document).ready(function(){
	
	$('.albums-page .tracklist .preview').on('click',function(e){
	

		
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
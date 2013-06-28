$(document).ready(function(){
	
	$('.site-login input[type="submit"]').on('mousedown',function(e){
		$(this).addClass('selected');
	});
	
	$('.site-login input[type="submit"]').on('mouseup',function(e){
		$(this).removeClass('selected');
	});

});
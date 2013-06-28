$(document).ready(function(){
	
	$('.my-account-page input[type="submit"]').on('mousedown',function(e){
		$(this).addClass('clicked');
	});
	
	$('.my-account-page input[type="submit"]').on('mouseup',function(e){
		$(this).removeClass('clicked');
	});
});
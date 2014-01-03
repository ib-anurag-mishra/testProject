$(document).ready(function(){
	
	
	$('.my-account-menu').on('click',function(){
		
		$('.account-menu-dropdown').toggleClass('active');
		
	});
	
	$('.account-menu-dropdown').on('mouseleave',function(){
		
		$(this).removeClass('active');
	});
	
	var ulPosition = 0;
	
	$('.album-scroll-left').on('click',function(){
		
		
		if(ulPosition !== 0) {
		
			ulPosition = ulPosition + 860;
	
			
			
			$('.album-list-container ul').animate({
				
				marginLeft:ulPosition
			});
		}
	});
	
	
	$('.album-scroll-right').on('click',function(){

		if(ulPosition !== -3440) {
		
			ulPosition = ulPosition - 860;
	


			$('.album-list-container ul').animate({
				
				marginLeft:ulPosition
			});

		}
		
		
	});
});
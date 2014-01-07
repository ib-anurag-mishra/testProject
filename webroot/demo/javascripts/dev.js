	
	$('.album-scroll-left').on('click',function(){
		
		ulPosition = parseInt($(this).parent('.album-navigation-container').siblings('.album-list-container').find('ul').css("margin-left"));
		
		if(ulPosition !== 0) {
		
			ulPosition = ulPosition + 860;
	
			
			
			$(this).parent('.album-navigation-container').siblings('.album-list-container').find('ul').animate({
				
				marginLeft:ulPosition
			});
		}
	});
	
	
	$('.album-scroll-right').on('click',function(){
	
		ulPosition = parseInt($(this).parent('.album-navigation-container').siblings('.album-list-container').find('ul').css("margin-left"));
		
		
		
		

		if(ulPosition !== -3440) {
		
			ulPosition = ulPosition - 860;
	


			$(this).parent('.album-navigation-container').siblings('.album-list-container').find('ul').animate({
				
				marginLeft:ulPosition
			});

		}
		
		
	});
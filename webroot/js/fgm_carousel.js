(function($){


/*
	var d = new Date();
	var monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];
	console.log("The current month is " + monthNames[d.getMonth()]);
*/
	
	
		
	var fgm_carousel = $('.fgm_carousel');
	var fgm_carousel_ul = fgm_carousel.find('ul');
	
	var prev = fgm_carousel.find('.prev');
	var next = fgm_carousel.find('.next');
	var active_li;
	var next_width;
	var prev_width;
	var current_width;
	
	fgm_carousel_ul.find('li:first-child').addClass('active');
	
	$.fn.fgm_carousel = function(options) {
		next.on('click',function(e){
			e.preventDefault();
			
			active_li = fgm_carousel_ul.find('li.active');
			current_width = active_li.width();

			fgm_carousel_ul.animate({left:'-='+current_width},500,function(){
				
				active_li.next().addClass('active');
				active_li.removeClass('active');
			});

			
			
		});
		
		
		prev.on('click',function(e){
			e.preventDefault();
			
			active_li = fgm_carousel_ul.find('li.active');
			prev_width = active_li.prev().width();
			

			fgm_carousel_ul.animate({left:'+='+prev_width},500,function(){
				active_li.prev().addClass('active');
				active_li.removeClass('active');				
				
			});
			
			
		});
		
		
		var settings = $.extend({

			
		},options);
		return this;
		
	};
}(jQuery));


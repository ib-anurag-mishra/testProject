window.onload = function(){

	var d = new Date();
	var monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];
	//console.log("The current month is " + monthNames[d.getMonth()]);
/*
		for(i=5;i<12;i++) {
			if(i===5) {
				var month_html = '<li><a href="#" class="month active">' + monthNames[i] + '</a></li>\n';
			} else {
				
				var month_html = '<li><a href="#" class="month">' + monthNames[i] + '</a></li>\n';
			}
			$('.slider ul').append(month_html);
		
		}
*/
	
/*
	for(i=d.getMonth();i<(d.getMonth() + 5);i++) {
		var month_html = '<li><a href="#" class="month">' + monthNames[i] + '</a></li>\n';
		$('.slider ul').append(month_html);
		
	}
*/
/*

	$( '#slider1' ).lemmonSlider({
		
		loop:false
	});
*/


}

//$('.fgm_carousel').fgm_carousel();

$(function() {


	
	
	var sidebar_a = $('.left-sidebar li a');
	var announcements = $('.announcements h4 a');
	var poll = $('.poll');
	var sidebar_sub_nav = $('.sidebar-sub-nav');
	var sidebar_anchor = $('.sidebar-anchor');
	var tooltip_a = $('.tooltip a');
	var plays_tooltip = $('.plays-tooltip');
	var arrow_up = $('.arrow-up');
	var filter_text = $('.filter-text');
	var filter_results = $('.filter-results');
	var fmp = $('.fmp');
	var music_player_container = $('.music-player-container');
	var mpc_height = music_player_container.height();
	var footer = $('.site-footer');
	var footer_height = footer.height();
	var doc_height = $(document).height();
	var lastScrollTop = 0;
	var scrollingDown;
	var footer_pos;
	var category_filter_a = $('.category-filter a');
	var search_text = $('#search-text');	
	var album_grid = $('#album-grid');
	var whats_happening_grid = $('#whats-happening-grid');
	var music_search_results = $('.master-music-search-results');
	var whats_happening_filter_text = $('#whats-happening-filter-text');
	var whats_happening_filter_results = $('.whats-happening-filter-results');
	var min_max = $('.min-max');
	var singles_grid = $('#singles-grid');
	var videos_grid = $('#videos-grid');
	
	var site_nav_a = $('.site-nav a');
	
	
	var right_tapered_shadow = $('.right-tapered-shadow');
	
	var top_100_nav = $('.top-100-nav li a');
	
	
	
	
	

	
	
	//var month_html;
/*	
	var carousel_array = new Array();
	var current_index = 0;
	var i = 0;

	carousel_array[0] = "January";
	carousel_array[1] = "February";
	carousel_array[2] = "March";
	carousel_array[3] = "April";
	carousel_array[4] = "May";
	carousel_array[5] = "June";
	carousel_array[6] = "July";
	carousel_array[7] = "August";
	carousel_array[8] = "September";
	carousel_array[9] = "October";
	carousel_array[10] = "November";
	carousel_array[11] = "December";

	
	months_carousel_li.each(function(index){
		//console.log(index+ ": " + $(this).text());
		months_carousel_position[index] = $(this).css("margin-left");
		console.log(months_carousel_position[index]);
		
	});
*/	
	//print_month_list(current_index);
	
	
	
	$('.site-nav li:first-child a').addClass('active');
	$('.category-filter li:first-child a').addClass('active');
	$('.top-100-nav li:first-child a').addClass('active');
		
	
	
	search_text.on('keyup',function(){
		
		music_search_results.show();
	});
	
	
	
		
	sidebar_a.on('click',function(e){
		e.preventDefault();
		$(sidebar_a).removeClass('active');
		$(this).addClass('active');
		
	});
	
	announcements.on('click',function(e){
		e.preventDefault();
		if($(poll).hasClass('active')) {
			$(poll).removeClass('active');
		} else {
			$(poll).addClass('active');
		}
	});
	
	sidebar_anchor.on('click',function(e){
		e.preventDefault();
		if($(this).next('ul').hasClass('active')) {
			$(this).next('ul').removeClass('active');
		} else {
			
			$(this).next('ul').addClass('active');
		}
	});
	
	tooltip_a.hover(
		function() {
			
			plays_tooltip.show();

			
		},
		function() {
			plays_tooltip.hide();

	});
	
	filter_text.on('keyup',function(){
		filter_results.show();
	});
	
	filter_text.on('blur',function(){
		filter_results.hide();
	});
	
	top_100_nav.on('click',function(){
		top_100_nav.removeClass('active');
		$(this).addClass('active');
		
	});


	fmp.mediaelementplayer({
		
		audioWidth:780,
		audioHeight:60,
		loop: true,
        shuffle: true,
        playlist: false,
        playlistposition: 'top',
        features: ['playlistfeature', 'prevtrack', 'playpause', 'nexttrack', 'current', 'progress', 'duration', 'volume','shuffle','loop','playlist'],


	});
	
	
	category_filter_a.on('click',function(e){
		console.log('cat filter a clicked');
		e.preventDefault();
		category_filter_a.removeClass('active');
		album_grid.hide();
		singles_grid.hide();
		videos_grid.hide();
		var target = $(this).attr('href');
		$(this).addClass('active');
		$(target).show();
		
		
	});

	


	
	
	min_max.on('click',function(e){
		e.preventDefault();
		
		if(music_player_container.hasClass('minimized')) {
			/*
			$('.music-player-container .music-player .album-title').animate({
				left:156,
				fontSize:13
				
			},500);
			*/
			
			$('.music-player-container .music-player .album-title').removeClass('minimized');
			
			/*
			$('.mejs-container').animate({
				marginTop:40
				
			},500);
			*/
			$('.music-player-container .music-player .album-cover-art').fadeIn(500);
			/*
music_player_container.animate({
				height:101
			
			},500).removeClass('minimized');
*/
			music_player_container.removeClass('minimized');
			
			/*
			$('.music-player-container .music-player .player-mgmt-container').animate({
				top:53
				
			},500);
			*/
			$('.music-player-container .music-player .player-mgmt-container').removeClass('minimized');
			$('.mejs-controls .mejs-button.mejs-play').removeClass('minimized');
			/*
			$('.mejs-controls div.mejs-time-rail').fadeIn(500);
			$('.mejs-controls .mejs-button.mejs-volume-button').fadeIn(500);
			$('.mejs-container .mejs-controls .mejs-time span').fadeIn(500);
			$('.mejs-controls div.mejs-horizontal-volume-slider').fadeIn(500);
			$('.mejs-controls .mejs-button.mejs-shuffle-on').fadeIn(500);
			$('.mejs-controls .mejs-button.mejs-shuffle-off').fadeIn(500);
			$('.mejs-controls .mejs-button.mejs-loop-on').fadeIn(500);
			$('.mejs-controls .mejs-button.mejs-loop-off').fadeIn(500);
			*/
			
			$('.mejs-controls .mejs-button.mejs-playlist-button').removeClass('minimized');
			$('.mejs-controls .mejs-button.mejs-prevtrack').removeClass('minimized');
			$('.mejs-controls .mejs-button.mejs-nexttrack').removeClass('minimized');
			$('.music-player-container .music-player .album-title').removeClass('minimized');
			$('.mejs-controls .mejs-time-rail .mejs-time-total').removeClass('minimized');
			$('.mejs-controls div.mejs-time-rail').removeClass('minimized');
			$('.mejs-container .mejs-controls .mejs-time').removeClass('minimized');
			$('.mejs-controls .mejs-button.mejs-volume-button').removeClass('minimized');
			$('.mejs-controls div.mejs-horizontal-volume-slider').removeClass('minimized');
			$('.mejs-controls .mejs-button.mejs-shuffle-on').removeClass('minimized');
			$('.mejs-controls .mejs-button.mejs-shuffle-off').removeClass('minimized');
			$('.mejs-controls .mejs-button.mejs-loop-off').removeClass('minimized');
			$('.mejs-controls .mejs-button.mejs-loop-on').removeClass('minimized');
			min_max.css({backgroundImage:'url(images/music_player/minimize.png)'});
			
		} else {
			
			/*
			$('.music-player-container .music-player .album-title').animate({
				left:339,
				top:30,
				fontSize:12
				
				
				
				
			},500);
			*/
			
			$('.music-player-container .music-player .album-title').addClass('minimized');
			
			$('.mejs-container').animate({
				marginTop:0
				
			},500);
			
			$('.music-player-container .music-player .album-cover-art').fadeOut(500);
			/*
			music_player_container.animate({
				height:57
			
			},500).addClass('minimized');
			*/
			music_player_container.addClass('minimized');
			
			/*
			$('.music-player-container .music-player .player-mgmt-container').animate({
				top:13
				
			},500);
			
			*/
			$('.music-player-container .music-player .player-mgmt-container').addClass('minimized');
			$('.mejs-controls .mejs-button.mejs-play').addClass('minimized');
			$('.mejs-controls .mejs-button.mejs-nexttrack').addClass('minimized');
			/*
			$('.mejs-controls div.mejs-time-rail').fadeOut(500);
			$('.mejs-controls .mejs-button.mejs-volume-button').fadeOut(500);
			$('.mejs-container .mejs-controls .mejs-time span').fadeOut(500);
			$('.mejs-controls div.mejs-horizontal-volume-slider').fadeOut(500);
			$('.mejs-controls .mejs-button.mejs-shuffle-on').fadeOut(500);
			$('.mejs-controls .mejs-button.mejs-shuffle-off').fadeOut(500);
			$('.mejs-controls .mejs-button.mejs-loop-on').fadeOut(500);
			$('.mejs-controls .mejs-button.mejs-loop-off').fadeOut(500);
			*/
			
			$('.mejs-controls .mejs-button.mejs-playlist-button').addClass('minimized');
			$('.mejs-controls .mejs-button.mejs-prevtrack').addClass('minimized');
			$('.music-player-container .music-player .album-title').addClass('minimized');
			$('.mejs-controls .mejs-time-rail .mejs-time-total').addClass('minimized');
			$('.mejs-controls div.mejs-time-rail').addClass('minimized');
			$('.mejs-container .mejs-controls .mejs-time').addClass('minimized');
			$('.mejs-controls .mejs-button.mejs-volume-button').addClass('minimized');
			$('.mejs-controls div.mejs-horizontal-volume-slider').addClass('minimized');
			$('.mejs-controls .mejs-button.mejs-shuffle-on').addClass('minimized');
			$('.mejs-controls .mejs-button.mejs-shuffle-off').addClass('minimized');
			$('.mejs-controls .mejs-button.mejs-loop-off').addClass('minimized');
			$('.mejs-controls .mejs-button.mejs-loop-on').addClass('minimized');
						
			min_max.css({backgroundImage:'url(images/music_player/maximize.png)'});
		}

		
		
		
	});
	
	
	whats_happening_filter_text.on('keyup',function(){
		whats_happening_filter_results.show();
		
	});


	$('.mejs-controls .mejs-button.mejs-prevtrack > button').on('mousedown',function(){
		
		$('.mejs-controls .mejs-button.mejs-prevtrack').css({background:'url(images/music_player/prev_btn_down.png)'});
		
	});
	
	$('.mejs-controls .mejs-button.mejs-prevtrack > button').on('mouseup',function(){
		
		$('.mejs-controls .mejs-button.mejs-prevtrack').css({background:'url(images/music_player/prev_btn.png)'});
		
	});
	
	$('.mejs-controls .mejs-button.mejs-nexttrack > button').on('mousedown',function(){
		
		$('.mejs-controls .mejs-button.mejs-nexttrack').css({background:'url(images/music_player/next_btn_down.png)'});
		
	});
	
	$('.mejs-controls .mejs-button.mejs-nexttrack > button').on('mouseup',function(){
		
		$('.mejs-controls .mejs-button.mejs-nexttrack').css({background:'url(images/music_player/next_btn.png)'});
		
	});
	
	
	$('.mejs-controls .mejs-button.mejs-volume-button > button').on('mousedown',function(){
		
		$('.mejs-controls .mejs-button.mejs-volume-button').css({background:'url(images/music_player/volume_btn_down.png)'});
		
	});
	
	$('.mejs-controls .mejs-button.mejs-volume-button > button').on('mouseup',function(){
		
		$('.mejs-controls .mejs-button.mejs-volume-button').css({background:'url(images/music_player/volume_btn.png)'});
		
	});
	
	
	$('.mejs-controls .mejs-button.mejs-playlist-button > button').on('mousedown',function(){
		
		$('.mejs-controls .mejs-button.mejs-playlist-button').css({background:'url(images/music_player/playlist_btn_down.png)'});
		
	});
	
	$('.mejs-controls .mejs-button.mejs-playlist-button > button').on('mouseup',function(){
		
		$('.mejs-controls .mejs-button.mejs-playlist-button').css({background:'url(images/music_player/playlist_btn.png)'});
		
	});
	

	/*
	album_grid.bind('mousewheel',function(e){
	
	    $(this).scrollTop($(this).scrollTop()-e.originalEvent.wheelDeltaY);

	    //prevent page fom scrolling
	    return false;
	
	});
	*/
	/*
	whats_happening_grid.bind('mousewheel',function(e){
	
	    $(this).scrollTop($(this).scrollTop()-e.originalEvent.wheelDeltaY);

	    //prevent page fom scrolling
	    return false;
	
	});
	*/
	
	

	$(document).on('scroll',function(){
	
		var st = $(this).scrollTop();
		if (st > lastScrollTop){
		// downscroll code
			scrollingDown = true;
		} else {
		// upscroll code
			scrollingDown = false;
		}
		lastScrollTop = st;


		
		
		
		if((st + $(window).height()) >= (doc_height - footer_height)) {

			
			if(scrollingDown) {
				footer_pos = computeVisibleHeight(footer);
				music_player_container.css({bottom:footer_pos});			
			} else {
			
				footer_pos = computeVisibleHeight(footer);
				music_player_container.css({bottom:footer_pos-1});
			}
			
		} else {
			
			music_player_container.css({bottom:0});	
			
		}




		
	});
	
	$('.album-cover-container').on('mouseenter',function(){
		

		$(this).find('.add-to-playlist-button').css({opacity:1});

		
	
	});
	
	$('.album-cover-container').on('mouseleave',function(){
		
	

		$(this).find('.add-to-playlist-button').css({opacity:.5});

	
	});
	
	
	$('.video-cover-container').on('mouseenter',function(){
		

		$(this).find('.add-to-playlist-button').css({opacity:1});

		
	
	});
	
	$('.video-cover-container').on('mouseleave',function(){
		
	

		$(this).find('.add-to-playlist-button').css({opacity:.5});

	
	});
	
	
	
	$('.add-to-playlist-button').on('click',function(e){
		e.preventDefault();
		
		$('.wishlist-popover').removeClass('active').hide();
		
		if($(this).next('.wishlist-popover').hasClass('active')) {
			$(this).next('.wishlist-popover').removeClass('active').hide();
			$(this).find('.add-to-playlist-button').css({opacity:.5});
		} else {
			
			$(this).next('.wishlist-popover').addClass('active').show();
		}
	});
	
	site_nav_a.on('click',function(e){
		e.preventDefault();
		
		
	});
	
	
	$(document).mouseup(function (e) {
	    var container = $('.wishlist-popover');
	    var container2 = $('.mejs-playlist.mejs-layer');
	    var container3 = music_search_results;
	    var container4 = whats_happening_filter_results;
	
	    if (container.has(e.target).length === 0)
	    {
	        container.hide();
	    }
	    
		if (container2.has(e.target).length === 0)
	    {
	        container2.hide();
	    }
	    
		if (container3.has(e.target).length === 0)
	    {
	        container3.hide();
	    }
	    
	    if (container4.has(e.target).length === 0)
	    {
	        container4.hide();
	    }
	    
	    
	});

	
	
	function computeVisibleHeight ($t) {
        var top = $t.position().top;
        var windowHeight = $(window).height();
        var scrollTop = $(window).scrollTop();
        var height = $t.height();

        if (top < scrollTop && height - scrollTop >= windowHeight) {
            // first case: the top and the bottom of the element is outside of the window
            return windowHeight;
        } else if (top < scrollTop) {
            // second: the top is outside of the viewport but the bottom is visible
            return height - (scrollTop - top);
        } else if (top > scrollTop && top + height < windowHeight) {
            // the whole element is visible
            return height;
        } else {
            // the top is visible but the bottom is outside of the viewport
            return windowHeight - (top - scrollTop);
        }
    }
    /*
    function print_month_list(ci) {
    	months_carousel.html('');
		for(i=ci;i<=ci+4;i++) {
			
			month_html = '<li><a href="#" class="month" id="' + carousel_array[i] +'">' + carousel_array[i] + '</a></li>\n';
			months_carousel.append(month_html);		
		}	    
	    
	    
    }
    */


	
	 
});
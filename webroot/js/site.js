$('#top-100-albums-grid .lazy').lazyload({
	
	effect:'fadeIn',
	container:$('#top-100-albums-grid')
});

$('#top-100-songs-grid .lazy').lazyload({
	
	effect:'fadeIn',
	container:$('#top-100-songs-grid'),
	skip_invisible: false
});

$('#top-100-videos-grid .lazy').lazyload({
	
	effect:'fadeIn',
	container:$('#top-100-videos-grid')
});


$('#featured-video-grid .lazy').lazyload({
	
	effect:'fadeIn',
	container: $('#featured-video-grid')
});


$('.video-top-genres-grid .lazy').lazyload({
	
	effect:'fadeIn',
	container:$('.video-top-genres-grid')
});

$('.most-downloaded-videos-grid .lazy').lazyload({
	
	effect:'fadeIn',
	container:$('.most-downloaded-videos-grid')
});

$('.most-viewed-videos-grid .lazy').lazyload({
	
	effect:'fadeIn',
	container:$('.most-viewed-videos-grid')
});

$('.videos-recommended-for-you-grid .lazy').lazyload({
	effect:'fadeIn',
	container:$('.videos-recommended-for-you-grid')
	
});

$('#top-100-songs-list-view .lazy').lazyload({
	effect:'fadeIn',
	container:$('#top-100-songs-list-view')
	
});

$('#top-100-videos-list-view .lazy').lazyload({
	effect:'fadeIn',
	container:$('#top-100-videos-list-view')
	
});

$('.featured-grid .lazy').lazyload({
	
	effect:'fadeIn',
	container:$('.featured-grid')
})


$('#coming-soon-singles-grid .lazy').lazyload({
	
	effect:'fadeIn',
	container:$('#coming-soon-singles-grid')
});

$('#coming-soon-videos-grid .lazy').lazyload({
	
	effect:'fadeIn',
	container:$('#coming-soon-videos-grid')
});

$('#whats-happening-grid .lazy').lazyload({
	
	effect:'fadeIn',
	container:$('#whats-happening-grid')
});


$('.more-videos-scrollable .video-thumb-container .lazy').lazyload({
	effect:'fadeIn',
	container:$('.more-videos-scrollable')
	
});







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
	var coming_soon_album_grid = $('#coming-soon-album-grid');
	var whats_happening_grid = $('#whats-happening-grid');
	var music_search_results = $('.master-music-search-results');
	var whats_happening_filter_text = $('#whats-happening-filter-text');
	var whats_happening_filter_results = $('.whats-happening-filter-results');
	var min_max = $('.min-max');
	var coming_soon_singles_grid = $('#coming-soon-singles-grid');
	var coming_soon_videos_grid = $('#coming-soon-videos-grid');
	
	var site_nav_a = $('.site-nav a');
	
	
	var right_tapered_shadow = $('.right-tapered-shadow');
	
	var top_100_nav = $('.top-100-nav li a');
	var add_to_playlist = $('.add-to-playlist');
	var add_to_queue = $('.add-to-queue');
	var add_to_wishlist = $('.add-to-wishlist');
	var playlist_list = $('.playlist-options');
	
	var create_new_playlist = $('.create-new-playlist');
	var preview = $('.preview');
	var top_100_albums_grid = $('#top-100-albums-grid');
	var top_100_songs_grid = $('#top-100-songs-grid');
	var top_100_videos_grid = $('#top-100-videos-grid');
	var top_100_grids = $('.top-100-grids');
	var grid_view_button = $('.grid-view-button');
	var list_view_button = $('.list-view-button');
	var grids = $('.grids');
	var lists = $('.lists');
	var top_100_albums_list_view = $('#top-100-albums-list-view');
	var top_100_songs_list_view = $('#top-100-songs-list-view');
	var top_100_videos_list_view = $('#top-100-videos-list-view');
		
	var category_type = 'songs';
	var view_type = 'grid';
	
	var artwork_container = $('.artwork-container');
	
	var video_thumbnail_container = $('.video-thumbnail-container');
	
	var wishlist_popover = $('.wishlist-popover');
	
	var library_list_scrollable = $('.library-list-scrollable');
	
	var faq_container = $('.faq-container li a');
	
	var most_popular_nav = $('.site-nav li:nth-child(3) a');
	var most_popular_sub_nav = $('.most-popular-sub-nav');
	var nav_regular = $('.site-nav .regular');
	
	
	
	//$('.site-nav li:first-child a').addClass('active');
	//$('.category-filter li:first-child a').addClass('active');
	//$('.top-100-nav li:first-child a').addClass('active');
	//coming_soon_album_grid.addClass('active');
	coming_soon_singles_grid.addClass('active');
	
	
	//top_100_albums_grid.addClass('active');
	top_100_songs_grid.addClass('active');
	
	grid_view_button.addClass('active');
	
	grids.addClass('active');
	
	
	/*search_text.on('keyup',function(){
		
		music_search_results.show();
	});*/
	
	$("#search-text").autocomplete("<?php echo $this->webroot; ?>search/autocomplete",
    {
        minChars: 1,
        cacheLength: 10,
        autoFill: false,
        extraParams: {
            type:'all'
        }
	}).result(function(e, item) {
        $('#auto').attr('value', 1);
    });
	
    $('.select-arrow').on('click',function(e){
		if($('.account-options-menu').hasClass('active')) {
			
			$('.account-options-menu').removeClass('active');
		} else {
			$('.account-options-menu').addClass('active');
			
		}

	
	});
	
	$('.account-options-menu').on('mouseleave',function(e){
	
		$('.account-options-menu').removeClass('active');
	});
	
	
	
	/*
	grid_view_button.on('click',function(e){
		e.preventDefault();
		grid_view_button.addClass('active');
		list_view_button.removeClass('active');
		view_type = 'grid';
		grids.addClass('active');
		lists.removeClass('active');
		
		if(category_type === 'albums') {
			top_100_albums_grid.addClass('active');
			
		}
		
		if(category_type === 'songs') {
			top_100_songs_grid.addClass('active');
			
		}
		
		if(category_type === 'videos') {
			top_100_videos_grid.addClass('active');
			
		}
		
		
	});
	
	list_view_button.on('click',function(e){
		e.preventDefault();
		list_view_button.addClass('active');
		grid_view_button.removeClass('active');
		view_type = 'list';
		lists.addClass('active');
		grids.removeClass('active');
		if(category_type === 'albums') {
			top_100_albums_list_view.addClass('active');
			
		}
		
		if(category_type === 'songs') {
			top_100_songs_list_view.addClass('active');
			
		}
		
		if(category_type === 'videos') {
			top_100_videos_list_view.addClass('active');
			
		}
		
		
		
	});
	
	*/
	
	top_100_nav.on('click',function(e){
		e.preventDefault();
		top_100_nav.removeClass('active');
		$(this).addClass('active');
		
		top_100_grids.removeClass('active');
		var target = $(this).attr('href');
		
		category_type = $(this).attr('data-category-type');
		
		if(view_type === 'grid') {
			
			var target_str = target + '-grid';
			lists.removeClass('active')
			grids.addClass('active');
			top_100_albums_grid.removeClass('active');
			top_100_songs_grid.removeClass('active');
			top_100_videos_grid.removeClass('active');
			
		}
		
		if(view_type === 'list') {
			
			var target_str = target + '-list-view';
			grids.removeClass('active');
			lists.addClass('active');
			top_100_albums_list_view.removeClass('active');
			top_100_songs_list_view.removeClass('active');
			top_100_videos_list_view.removeClass('active');
		}
		
		$(target_str).addClass('active');
		
		
		
		
	});
	
	wishlist_popover.children('a').on('hover',function(e){
		console.log('hovered');
		e.preventDefault();
		
		if(playlist_list.hasClass('active')) {
			
			playlist_list.removeClass('active');
		}
	});
	
	
	preview.on('mousedown',function(e){
		e.preventDefault();
		
		$(this).addClass('active');
	});
	
	preview.on('mouseup',function(e){
		e.preventDefault();
		
		$(this).removeClass('active');
	});
		
	sidebar_a.on('click',function(e){
		//e.preventDefault();
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
		//e.preventDefault();
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
		coming_soon_album_grid.removeClass('active');
		coming_soon_singles_grid.removeClass('active');
		coming_soon_videos_grid.removeClass('active');
		var target = $(this).attr('href');
		$(this).addClass('active');
		$(target).addClass('active');
		
		
	});

	


	
	
	min_max.on('click',function(e){
		e.preventDefault();
		
		if(music_player_container.hasClass('minimized')) {

			
			$('.music-player-container .music-player .album-title').removeClass('minimized');

			$('.music-player-container .music-player .album-cover-art').fadeIn(500);

			music_player_container.removeClass('minimized');
			

			$('.music-player-container .music-player .player-mgmt-container').removeClass('minimized');
			$('.mejs-controls .mejs-button.mejs-play').removeClass('minimized');
			$('.mejs-controls .mejs-button.mejs-pause').removeClass('minimized');

			
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
			min_max.css({backgroundImage:'url(img/music_player/minimize.png)'});
			
		} else {
			

			
			$('.music-player-container .music-player .album-title').addClass('minimized');
			
			$('.mejs-container').animate({
				marginTop:0
				
			},500);
			
			$('.music-player-container .music-player .album-cover-art').fadeOut(500);

			music_player_container.addClass('minimized');
			

			$('.music-player-container .music-player .player-mgmt-container').addClass('minimized');
			$('.mejs-controls .mejs-button.mejs-play').addClass('minimized');
			$('.mejs-controls .mejs-button.mejs-pause').addClass('minimized');
			$('.mejs-controls .mejs-button.mejs-nexttrack').addClass('minimized');

			
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
						
			min_max.css({backgroundImage:'url(img/music_player/maximize.png)'});
		}

		
		
		
	});
	
	
	add_to_playlist.on('mouseenter',function(){
		
		playlist_list.addClass('active');
	});
	
	
	
	

	
	
	playlist_list.on('mouseleave',function(){
		
		playlist_list.removeClass('active');
	});
	
	add_to_queue.on('mouseenter',function(){
		
		
		if(playlist_list.hasClass('active')) {
			
			playlist_list.removeClass('active');
		}
		
		
		
	});
	
	add_to_wishlist.on('mouseenter',function(){
		
		
		if(playlist_list.hasClass('active')) {
			
			playlist_list.removeClass('active');
		}
		
		
		
	});
	
	wishlist_popover.on('mouseleave',function(){
		
		$(this).removeClass('active');
	});
	
	
	

	playlist_list.bind('mousewheel',function(e){
		

		$(this).scrollTop($(this).scrollTop()-e.originalEvent.wheelDeltaY);
		
		
		
		

	    //prevent page fom scrolling
	    return false;

		
	});
	

	
	library_list_scrollable.bind('mousewheel',function(e){
	
	
		
		$(this).scrollTop($(this).scrollTop()-e.originalEvent.wheelDeltaY);
		
		
		
		

	    //prevent page fom scrolling
	    return false;
		
	});
	

	

	
	whats_happening_filter_text.on('keyup',function(){
		whats_happening_filter_results.show();
		
	});


	$('.mejs-controls .mejs-button.mejs-prevtrack > button').on('mousedown',function(){
		
		$('.mejs-controls .mejs-button.mejs-prevtrack').css({background:'url(img/music_player/prev_btn_down.png)'});
		
	});
	
	$('.mejs-controls .mejs-button.mejs-prevtrack > button').on('mouseup',function(){
		
		$('.mejs-controls .mejs-button.mejs-prevtrack').css({background:'url(img/music_player/prev_btn.png)'});
		
	});
	
	$('.mejs-controls .mejs-button.mejs-nexttrack > button').on('mousedown',function(){
		
		$('.mejs-controls .mejs-button.mejs-nexttrack').css({background:'url(img/music_player/next_btn_down.png)'});
		
	});
	
	$('.mejs-controls .mejs-button.mejs-nexttrack > button').on('mouseup',function(){
		
		$('.mejs-controls .mejs-button.mejs-nexttrack').css({background:'url(img/music_player/next_btn.png)'});
		
	});
	
	
	$('.mejs-controls .mejs-button.mejs-volume-button > button').on('mousedown',function(){
		
		$('.mejs-controls .mejs-button.mejs-volume-button').css({background:'url(img/music_player/volume_btn_down.png)'});
		
	});
	
	$('.mejs-controls .mejs-button.mejs-volume-button > button').on('mouseup',function(){
		
		$('.mejs-controls .mejs-button.mejs-volume-button').css({background:'url(img/music_player/volume_btn.png)'});
		
	});
	
	
	$('.mejs-controls .mejs-button.mejs-playlist-button > button').on('mousedown',function(){
		
		$('.mejs-controls .mejs-button.mejs-playlist-button').css({background:'url(img/music_player/playlist_btn_down.png)'});
		
	});
	
	$('.mejs-controls .mejs-button.mejs-playlist-button > button').on('mouseup',function(){
		
		$('.mejs-controls .mejs-button.mejs-playlist-button').css({background:'url(img/music_player/playlist_btn.png)'});
		
	});
	


	
	

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
	
	video_thumbnail_container.on('mouseenter',function(){
		
		$(this).find('.add-to-playlist-button').css({opacity:1});
		$(this).find('.featured-video-download-now-button').css({opacity:1});
		$(this).find('.preview').css({opacity:1});
	});
	
	video_thumbnail_container.on('mouseleave',function(){
		
		$(this).find('.add-to-playlist-button').css({opacity:0});
		$(this).find('.featured-video-download-now-button').css({opacity:0});
		$(this).find('.preview').css({opacity:0});
	});
	
	$('.album-cover-container').on('mouseenter',function(){
		

		$(this).find('.add-to-playlist-button').css({opacity:1});
		$(this).find('.top-100-download-now-button').css({opacity:1});
		$(this).find('.preview').css({opacity:1});
		
	
	});
	
	$('.album-cover-container').on('mouseleave',function(){
		
	

		$(this).find('.add-to-playlist-button').css({opacity:0});
		$(this).find('.top-100-download-now-button').css({opacity:0});
		$(this).find('.preview').css({opacity:0});
	
	});
	
	
	$('.song-cover-container').on('mouseenter',function(){
		

		$(this).find('.add-to-playlist-button').css({opacity:1});
		$(this).find('.top-100-download-now-button').css({opacity:1});
		$(this).find('.preview').css({opacity:1});
		
	
	});
	
	$('.song-cover-container').on('mouseleave',function(){
		
	

		$(this).find('.add-to-playlist-button').css({opacity:0});
		$(this).find('.top-100-download-now-button').css({opacity:0});
		$(this).find('.preview').css({opacity:0});
	
	});
	
	
	$('.single-cover-container').on('mouseenter',function(){
		

		$(this).find('.add-to-playlist-button').css({opacity:1});

		
	
	});
	
	$('.single-cover-container').on('mouseleave',function(){
		
	

		$(this).find('.add-to-playlist-button').css({opacity:0});
		
	
	});
	
	
	$('.video-cover-container').on('mouseenter',function(){
		

		$(this).find('.add-to-playlist-button').css({opacity:1});
		$(this).find('.top-100-download-now-button').css({opacity:1});
		
	});
	
	$('.video-cover-container').on('mouseleave',function(){
		
	

		$(this).find('.add-to-playlist-button').css({opacity:0});
		$(this).find('.top-100-download-now-button').css({opacity:0});
		
	
	});
	
	$('.hero-image-container').on('mouseenter',function(){
		$(this).find('.add-to-playlist-button').css({opacity:1});
		$(this).find('.download-now-button').css({opacity:1});
		
	});
	
	$('.hero-image-container').on('mouseleave',function(){
		$(this).find('.add-to-playlist-button').css({opacity:0});
		$(this).find('.download-now-button').css({opacity:0});
		
	});
	
	
	$('.more-videos-scrollable .video-thumb-container').on('mouseenter',function(){
		$(this).find('.add-to-playlist-button').css({opacity:1});
		$(this).find('.download-now-button').css({opacity:1});
		
	});
	
	$('.more-videos-scrollable .video-thumb-container').on('mouseleave',function(){
		$(this).find('.add-to-playlist-button').css({opacity:0});
		$(this).find('.download-now-button').css({opacity:0});
		
	});
	
	
	
	$('.add-to-playlist-button').on('click',function(e){
		e.preventDefault();
		
		$('.wishlist-popover').removeClass('active');
		
		if($(this).next('.wishlist-popover').hasClass('active')) {
			$(this).next('.wishlist-popover').removeClass('active');
			$(this).find('.add-to-playlist-button').css({opacity:.5});
		} else {
			
			$(this).next('.wishlist-popover').addClass('active');
		}
	});
	
	site_nav_a.on('click',function(e){
		
		
		
	});
	
	artwork_container.on('mouseenter',function(){
		
		$(this).find('.preview').css({opacity:1})
	});
	
	artwork_container.on('mouseleave',function(){
		
		$(this).find('.preview').css({opacity:.5})
		
	});
	
	/* now streaming/queue detail page */
	
	$('.gear-icon').on('click',function(e){
		$('.queue-options').addClass('active');
	
	});
	
	$('.queue-options').on('mouseleave',function(e){
	
		$('.queue-options').removeClass('active');
	});
	

	
	$('.now-streaming-page .playlist-scrollable,.queue-detail-page .playlist-scrollable').bind('mousewheel',function(e){
		

		$(this).scrollTop($(this).scrollTop()-e.originalEvent.wheelDeltaY);
		
		
		
		

	    //prevent page fom scrolling
	    return false;

		
	});
	
	$('.now-streaming-page .now-playing-container .add-to-wishlist-button,.queue-detail-page .now-playing-container .add-to-wishlist-button').on('click',function(e){
		e.preventDefault();
	
		$(this).siblings('.wishlist-popover').addClass('active');
	});
	
	$('.now-streaming-page .now-playing-container .wishlist-popover,.queue-detail-page .now-playing-container .wishlist-popover').on('mouseleave',function(e){
	
		$(this).removeClass('active');
	});
	
	
	
	
	
	$('.now-streaming-page .playlist-scrollable .wishlist-popover,.queue-detail-page .playlist-scrollable .wishlist-popover').slice(0,4).addClass('top');
	

	$('.now-streaming-page .playlist-scrollable,.queue-detail-page .playlist-scrollable').on('scroll',function(e){

		$('.now-streaming-page .playlist-scrollable .wishlist-popover,.queue-detail-page .playlist-scrollable .wishlist-popover').removeClass('top');
		

		$('.now-streaming-page .playlist-scrollable .row,.queue-detail-page .playlist-scrollable .row').each(function(e){
			
			if($(this).position().top >= -22 && $(this).position().top <= 130) {
				
				
				

				$(this).find('.wishlist-popover').addClass('top');
				
				
				
			}
		
		});
		
	});
	
	
	$('.now-streaming-page .playlist-scrollable .row,.queue-detail-page .playlist-scrollable .row').on('mouseenter',function(){
		
		$(this).find('.album-title').addClass('hovered');
		$(this).find('.artist-name').addClass('hovered');
		$(this).find('.time').addClass('hovered');
		$(this).find('.song-title').addClass('hovered');
		$(this).find('.preview').addClass('hovered');
		$(this).find('.add-to-wishlist-button').addClass('hovered');
		
	});
	
	$('.now-streaming-page .playlist-scrollable .row,.queue-detail-page .playlist-scrollable .row').on('mouseleave',function(){
		
		$(this).find('.album-title').removeClass('hovered');
		$(this).find('.artist-name').removeClass('hovered');
		$(this).find('.time').removeClass('hovered');
		$(this).find('.song-title').removeClass('hovered');
		$(this).find('.preview').removeClass('hovered');
		$(this).find('.add-to-wishlist-button').removeClass('hovered');
		
	});
	
	
	

	$('.now-streaming-page .playlist-scrollable .row .preview,.queue-detail-page .playlist-scrollable .row .preview').on('mouseenter',function(e){
		$(this).removeClass('hovered').addClass('blue-bkg');
	
	});
	
	$('.now-streaming-page .playlist-scrollable .row .preview,.queue-detail-page .playlist-scrollable .row .preview').on('mouseleave',function(e){
		$(this).removeClass('blue-bkg').addClass('hovered');
	
	});
	
	$('.now-streaming-page .playlist-scrollable .row .preview,.queue-detail-page .playlist-scrollable .row .preview').on('click',function(e){
		
		if($(this).hasClass('playing')) {
			
			$(this).removeClass('playing');
			
			$(this).parents('.row').removeClass('playing');
			$(this).parent().removeClass('playing');
			$(this).siblings('.album-title').removeClass('playing');
			$(this).siblings('.artist-name').removeClass('playing');
			$(this).siblings('.time').removeClass('playing');
			$(this).siblings('.song-title').removeClass('playing');
			$(this).siblings('.add-to-wishlist-button').removeClass('playing');
			
			
		} else {
		
			$('.now-streaming-page .playlist-scrollable .row,.queue-detail-page .playlist-scrollable .row').removeClass('playing');
			$('.now-streaming-page .playlist-scrollable .row .preview,.queue-detail-page .playlist-scrollable .row .preview').removeClass('playing');
			$('.now-streaming-page .playlist-scrollable .row .album-title,.queue-detail-page .playlist-scrollable .row .album-title').removeClass('playing');
			$('.now-streaming-page .playlist-scrollable .row .artist-name,.queue-detail-page .playlist-scrollable .row .artist-name').removeClass('playing');
			$('.now-streaming-page .playlist-scrollable .row .time,.queue-detail-page .playlist-scrollable .row .time').removeClass('playing');
			$('.now-streaming-page .playlist-scrollable .row .song-title,.queue-detail-page .playlist-scrollable .row .song-title').removeClass('playing');
			$('.now-streaming-page .playlist-scrollable .row .add-to-wishlist-button,.queue-detail-page .playlist-scrollable .row .add-to-wishlist-button').removeClass('playing');
		
			$(this).addClass('playing');
			$(this).parents('.row').addClass('playing');
			$(this).parent().addClass('playing');
			$(this).siblings('.album-title').addClass('playing');
			$(this).siblings('.artist-name').addClass('playing');
			$(this).siblings('.time').addClass('playing');
			$(this).siblings('.song-title').addClass('playing');
			$(this).siblings('.add-to-wishlist-button').addClass('playing');
			
			
		}
		
	});
	
	
	


	/* end now streaming page */
        
        /* overlays */
	
	$('.rename-queue').on('click',function(e){
		e.preventDefault();
		$('.queue-overlay').addClass('active');
		$('.rename-queue-dialog-box').addClass('active');
		$('.rename-queue-dialog-box').css('margin-top',100 + $(document).scrollTop());
		
	});
	
	$('.delete-queue').on('click',function(e){
		e.preventDefault();
		$('.queue-overlay').addClass('active');
		$('.delete-queue-dialog-box').addClass('active');
		$('.delete-queue-dialog-box').css('margin-top',100 + $(document).scrollTop());
	});
	
	$('.create-new-queue,.create-new-queue-btn').on('click',function(e){
		e.preventDefault();
		$('.queue-overlay').addClass('active');		
		$('.create-queue-dialog-box').addClass('active');
		$('.create-queue-dialog-box').css('margin-top',100 + $(document).scrollTop());
		$('.wishlist-popover').removeClass('active');
			
	});
	

	
	$('.close,.text-close').on('click',function(e){
		$('.queue-overlay').removeClass('active');
		$('.rename-queue-dialog-box').removeClass('active');
		$('.delete-queue-dialog-box').removeClass('active');
		$('.create-queue-dialog-box').removeClass('active');
	});
	
	/* end overlays */
	
        
        
	$(document).mouseup(function (e) {
	
	    var container = $('.wishlist-popover');
	    var container2 = $('.mejs-playlist.mejs-layer');
	    var container3 = music_search_results;
	    var container4 = whats_happening_filter_results;
	    var container5 = playlist_list;
	    var container6 = most_popular_sub_nav;
	
	    if (container.has(e.target).length === 0)
	    {
	        container.removeClass('active');
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
	    
	    if (container5.has(e.target).length === 0)
	    {
	        container5.removeClass('active');
	    }
	    
	    if (container6.has(e.target).length === 0)
	    {
	        container6.removeClass('active');
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
    

	/* FAQ page */
	
	faq_container.on('click',function(e){
		e.preventDefault();
		if($(this).siblings('p').hasClass('active')) {
		
			$(this).siblings('p').slideUp(500).removeClass('active');
		} else {
			
			$(this).siblings('p').slideDown(500).addClass('active');
			
		}
		
	});
	
	/* for most popular sub nav */
	most_popular_nav.on('mouseenter',function(e){
		e.preventDefault();
		
		most_popular_sub_nav.addClass('active');
		
	});
	
	most_popular_sub_nav.on('mouseleave',function(e){
		
		most_popular_sub_nav.removeClass('active');
	});
	
	
	nav_regular.on('mouseenter',function(e){
		//most_popular_sub_nav.removeClass('active');
	});
	
	
	

	
	 
});
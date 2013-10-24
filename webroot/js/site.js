$('#top-100-albums-grid .lazy').lazyload({
    effect: 'fadeIn',
    container: $('#top-100-albums-grid')
});

$('#top-100-songs-grid .lazy').lazyload({
    effect: 'fadeIn',
    container: $('#top-100-songs-grid'),
    skip_invisible: false
});

$('#top-100-videos-grid .lazy').lazyload({
    effect: 'fadeIn',
    container: $('#top-100-videos-grid')
});


$('#featured-video-grid .lazy').lazyload({
    effect: 'fadeIn',
    container: $('#featured-video-grid')
});


$('.video-top-genres-grid .lazy').lazyload({
    effect: 'fadeIn',
    container: $('.video-top-genres-grid')
});

$('.most-downloaded-videos-grid .lazy').lazyload({
    effect: 'fadeIn',
    container: $('.most-downloaded-videos-grid')
});

$('.most-viewed-videos-grid .lazy').lazyload({
    effect: 'fadeIn',
    container: $('.most-viewed-videos-grid')
});

$('.videos-recommended-for-you-grid .lazy').lazyload({
    effect: 'fadeIn',
    container: $('.videos-recommended-for-you-grid')

});

$('#top-100-songs-list-view .lazy').lazyload({
    effect: 'fadeIn',
    container: $('#top-100-songs-list-view')

});

$('#top-100-videos-list-view .lazy').lazyload({
    effect: 'fadeIn',
    container: $('#top-100-videos-list-view')

});

$('.featured-grid .lazy').lazyload({
    effect: 'fadeIn',
    container: $('.featured-grid')
})


$('#coming-soon-singles-grid .lazy').lazyload({
    effect: 'fadeIn',
    container: $('#coming-soon-singles-grid')
});

$('#coming-soon-videos-grid .lazy').lazyload({
    effect: 'fadeIn',
    container: $('#coming-soon-videos-grid')
});

$('#whats-happening-grid .lazy').lazyload({
    effect: 'fadeIn',
    container: $('#whats-happening-grid')
});

$('#detailsNews .lazy').lazyload({
    effect: 'fadeIn',
    container: $('#detailsNews')
});


$('.more-videos-scrollable .video-thumb-container .lazy').lazyload({
    effect: 'fadeIn',
    container: $('.more-videos-scrollable')

});

$('.top-videos-scrollable .video-thumb-container .lazy').lazyload({
    effect: 'fadeIn',
    container: $('.top-videos-scrollable')

});


/* end lazyload initalizations */




$('document').ready(function()
{
    $('.select-arrow').on('click', function(e) {

        e.preventDefault();

        if ($('.account-options-menu').hasClass('active')) {

            $('.account-options-menu').removeClass('active');
        } else {
            $('.account-options-menu').addClass('active');

        }
        return false;

    });

    $('.account-options-menu').on('mouseleave', function(e) {

        $('.account-options-menu').removeClass('active');
    });


    var sidebar_a = $('.left-sidebar li a');
    sidebar_a.on('click', function(e) {
        //e.preventDefault();
        $(sidebar_a).removeClass('active');
        $(this).addClass('active');

    });

    var poll = $('.poll');
    var announcements = $('.announcements h4 a');
    announcements.on('click', function(e) {
        e.preventDefault();
        if ($(poll).hasClass('active')) {
            $(poll).removeClass('active');
        } else {
            $(poll).addClass('active');

        }
    });



    var sidebar_anchor = $('.sidebar-anchor');
    sidebar_anchor.on('click', function(e) {
        //e.preventDefault();
        if ($(this).next('ul').hasClass('active')) {
            $(this).next('ul').removeClass('active');
        } else {

            $(this).next('ul').addClass('active');
        }
    });


    var tooltip_a = $('.tooltip a');
    var plays_tooltip = $('.plays-tooltip');
    tooltip_a.hover(
            function() {

                plays_tooltip.show();


            },
            function() {
                plays_tooltip.hide();

            });


    var filter_text = $('.filter-text');
    var filter_results = $('.filter-results');
    filter_text.on('keyup', function() {
        filter_results.show();
    });
    filter_text.on('blur', function() {
        filter_results.hide();
    });


    var music_player_container = $('.music-player-container');

    var doc_height = $(document).height();
    var lastScrollTop = 0;
    var scrollingDown;
    var footer_pos;
    var music_search_results = $('.master-music-search-results');


    var whats_happening_filter_text = $('#whats-happening-filter-text');
    var whats_happening_filter_results = $('.whats-happening-filter-results');
    whats_happening_filter_text.on('keyup', function() {
        whats_happening_filter_results.show();

    });



    var coming_soon_singles_grid = $('#coming-soon-singles-grid');
    coming_soon_singles_grid.addClass('active');

    var site_nav_a = $('.site-nav a');
site_nav_a.on('click', function(e) {



    });
    
    
    var add_to_playlist = $('.add-to-playlist');
    add_to_playlist.on('mouseenter', function() {

        playlist_list.addClass('active');
    });

    var add_to_queue = $('.add-to-queue');
    add_to_queue.on('mouseenter', function() {


        if (playlist_list.hasClass('active')) {

            playlist_list.removeClass('active');
        }



    });



    var add_to_wishlist = $('.add-to-wishlist');
    add_to_wishlist.on('mouseenter', function() {


        if (playlist_list.hasClass('active')) {

            playlist_list.removeClass('active');
        }



    });


    var playlist_list = $('.playlist-options');
    playlist_list.on('mouseleave', function() {

        playlist_list.removeClass('active');
    });
    playlist_list.bind('mousewheel', function(e) {


        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);





        //prevent page fom scrolling
        return false;


    });


    var preview = $('.preview');
    preview.on('mousedown', function(e) {
        e.preventDefault();

        $(this).addClass('active');
    });
    preview.on('mouseup', function(e) {
        e.preventDefault();

        $(this).removeClass('active');
    });

    //top_100_albums_grid.addClass('active');
    var top_100_songs_grid = $('#top-100-songs-grid');
    top_100_songs_grid.addClass('active');

    var grid_view_button = $('.grid-view-button');
    grid_view_button.addClass('active');

    var grids = $('.grids');
    grids.addClass('active');


    var artwork_container = $('.artwork-container');
    $(document).on('mouseenter', artwork_container, function() {
        $(this).find('.preview').css({opacity: 1});
    });
    $(document).on('mouseleave', artwork_container, function() {
        $(this).find('.preview').css({opacity: .5});
    });
    
    
    

    var video_thumbnail_container = $('.video-thumbnail-container');
    $(document).on('mouseenter', video_thumbnail_container, function() {
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.featured-video-download-now-button').css({opacity: 1});
        $(this).find('.preview').css({opacity: 1});

    });
    $(document).on('mouseleave', video_thumbnail_container, function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.featured-video-download-now-button').css({opacity: 0});
        $(this).find('.preview').css({opacity: 0});

    });




    var library_list_scrollable = $('.library-list-scrollable');
    library_list_scrollable.bind('mousewheel', function(e) {



        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);





        //prevent page fom scrolling
        return false;

    });

    var most_popular_sub_nav = $('.most-popular-sub-nav');


    var footer = $('.site-footer');
    var footer_height = footer.height();






    var wishlist_popover = $('.wishlist-popover');
    wishlist_popover.on('mouseleave', function() {

        $(this).removeClass('active');
    });
    wishlist_popover.children('a').on('hover', function(e) {

        e.preventDefault();

        if (playlist_list.hasClass('active')) {

            playlist_list.removeClass('active');
        }
    });








    $('.news .whats-happening #whats-happening-grid .post-excerpt').bind('mousewheel', function(e) { /* changed 080313 .. .post is now .post-excerpt */



        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);





        //prevent page fom scrolling
        return false;

    });







    $('.mejs-controls .mejs-button.mejs-prevtrack > button').on('mousedown', function() {

        $('.mejs-controls .mejs-button.mejs-prevtrack').css({background: 'url(img/music_player/prev_btn_down.png)'});

    });

    $('.mejs-controls .mejs-button.mejs-prevtrack > button').on('mouseup', function() {

        $('.mejs-controls .mejs-button.mejs-prevtrack').css({background: 'url(img/music_player/prev_btn.png)'});

    });

    $('.mejs-controls .mejs-button.mejs-nexttrack > button').on('mousedown', function() {

        $('.mejs-controls .mejs-button.mejs-nexttrack').css({background: 'url(img/music_player/next_btn_down.png)'});

    });

    $('.mejs-controls .mejs-button.mejs-nexttrack > button').on('mouseup', function() {

        $('.mejs-controls .mejs-button.mejs-nexttrack').css({background: 'url(img/music_player/next_btn.png)'});

    });


    $('.mejs-controls .mejs-button.mejs-volume-button > button').on('mousedown', function() {

        $('.mejs-controls .mejs-button.mejs-volume-button').css({background: 'url(img/music_player/volume_btn_down.png)'});

    });

    $('.mejs-controls .mejs-button.mejs-volume-button > button').on('mouseup', function() {

        $('.mejs-controls .mejs-button.mejs-volume-button').css({background: 'url(img/music_player/volume_btn.png)'});

    });


    $('.mejs-controls .mejs-button.mejs-playlist-button > button').on('mousedown', function() {

        $('.mejs-controls .mejs-button.mejs-playlist-button').css({background: 'url(img/music_player/playlist_btn_down.png)'});

    });

    $('.mejs-controls .mejs-button.mejs-playlist-button > button').on('mouseup', function() {

        $('.mejs-controls .mejs-button.mejs-playlist-button').css({background: 'url(img/music_player/playlist_btn.png)'});

    });






    $(document).on('scroll', function() {

        var st = $(this).scrollTop();
        if (st > lastScrollTop) {
            // downscroll code
            scrollingDown = true;
        } else {
            // upscroll code
            scrollingDown = false;
        }
        lastScrollTop = st;





        if ((st + $(window).height()) >= (doc_height - footer_height)) {


            if (scrollingDown) {
                footer_pos = computeVisibleHeight(footer);
                music_player_container.css({bottom: footer_pos});
            } else {

                footer_pos = computeVisibleHeight(footer);
                music_player_container.css({bottom: footer_pos - 1});
            }

        } else {

            music_player_container.css({bottom: 0});

        }





    });



    $(document).on('mouseenter', '.tracklist-shadow-container .tracklist-scrollable', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 1});
    });

    $(document).on('mouseleave', '.tracklist-shadow-container .tracklist-scrollable', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
    });


    $(document).on('mouseenter', '.top-music-video-cover-container', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.top-video-login-button').css({opacity: 1});
        $(this).find('.preview').css({opacity: 1});
    });

    $(document).on('mouseleave', '.top-music-video-cover-container', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.top-video-login-button').css({opacity: 0});
        $(this).find('.preview').css({opacity: 0});
    });


    $(document).on('mouseenter', '.album-cover-container', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.top-100-download-now-button').css({opacity: 1});
        $(this).find('.preview').css({opacity: 1});
    });

    $(document).on('mouseleave', '.album-cover-container', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.top-100-download-now-button').css({opacity: 0});
        $(this).find('.preview').css({opacity: 0});
    });


    $(document).on('mouseenter', '.song-cover-container', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.top-100-download-now-button').css({opacity: 1});
        $(this).find('.preview').css({opacity: 1});

    });

    $(document).on('mouseleave', '.song-cover-container', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.top-100-download-now-button').css({opacity: 0});
        $(this).find('.preview').css({opacity: 0});

    });


$(document).on('mouseenter', '.single-cover-container', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 1});
    });

    $(document).on('mouseleave', '.single-cover-container', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
    });
    
    

    $(document).on('mouseenter', '.video-cover-container', function() {
        $(this).find('.top-video-login-button').css({opacity: 1});
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.top-100-download-now-button').css({opacity: 1});
        $(this).find('.preview').css({opacity: 1});
    });

    $(document).on('mouseleave', '.video-cover-container', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.top-100-download-now-button').css({opacity: 0});
        $(this).find('.preview').css({opacity: 0});
        $(this).find('.top-video-login-button').css({opacity: 0});

    });



$(document).on('mouseenter', '.hero-image-container', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.download-now-button').css({opacity: 1});
    });

    $(document).on('mouseleave', '.hero-image-container', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.download-now-button').css({opacity: 0});
    });
    


$(document).on('mouseenter', '.more-videos-scrollable .video-thumb-container', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.download-now-button').css({opacity: 1});
    });

    $(document).on('mouseleave', '.more-videos-scrollable .video-thumb-container', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.download-now-button').css({opacity: 0});
    });
    
  

$(document).on('mouseenter', '.top-videos-scrollable .video-thumb-container', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.download-now-button').css({opacity: 1});
    });

    $(document).on('mouseleave', '.top-videos-scrollable .video-thumb-container', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.download-now-button').css({opacity: 0});
    });
    
    


    $(document).on('click', '.add-to-playlist-button', function(e) {
        e.preventDefault();


        $('.wishlist-popover').removeClass('active');

        if ($(this).next('.wishlist-popover').hasClass('active')) {
            $(this).next('.wishlist-popover').removeClass('active');
            $(this).find('.add-to-playlist-button').css({opacity: .5});
        } else {

            $(this).next('.wishlist-popover').addClass('active');
        }


    });



    

   

    /* clickoffs */
    $(document).mouseup(function(e) {

        var container = $('.wishlist-popover');
        var container2 = $('.mejs-playlist.mejs-layer');
        var container3 = music_search_results;
        var container4 = whats_happening_filter_results;
        var container5 = playlist_list;
        var container6 = most_popular_sub_nav;
        var container7 = $('.queue-overlay');

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

        if (container7.has(e.target).length === 0)
        {
            container7.removeClass('active');
            $('.rename-queue-dialog-box').removeClass('active');
            $('.delete-queue-dialog-box').removeClass('active');
            $('.create-queue-dialog-box').removeClass('active');
        }


    });
    /* end clickoffs */


   


    /* FAQ page */

    $('.faq-container').on('click', 'li a', function(e) {
        e.preventDefault();
        /* modified 080913
         if($(this).siblings('p').hasClass('active')) {
         
         $(this).siblings('p').slideUp(500).removeClass('active');
         } else {
         
         $(this).siblings('p').slideDown(500).addClass('active');
         
         if($(this).siblings('p').offset().top + 20 > $(window).height()) {
         
         
         $('html, body').animate({
         scrollTop: $(this).offset().top-10
         }, 1000);
         }
         
         }
         */

        /* commented out 081413 */
        /*
         $('p').slideUp(500).removeClass('active');
         $(this).siblings('p').slideDown(500).addClass('active');
         */

        /* added 081413 */

        if ($(this).siblings('p').hasClass('active')) {
            $(this).siblings('p').slideUp(500).removeClass('active');
        } else {
            $('.faq-container p').slideUp(500).removeClass('active');
            $(this).siblings('p').slideDown(500).addClass('active');
        }

    });

    $(documnet).on('mouseenter','.site-nav .most-popular a', function(e) {
        e.preventDefault();

        $('.most-popular-sub-nav').addClass('active');

    });

    $(document).on('mouseleave', '.most-popular-sub-nav' ,function(e) {
        $('.most-popular-sub-nav').removeClass('active');
    });

    $(document).on('mouseenter', '.site-nav .regular' ,function(e) {
        $('.most-popular-sub-nav').removeClass('active');
    });


    /* albums page */

    $(document).on('click', '.albums-page .tracklist .preview' ,function(e) {



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

    /* end albums page */



    /* artist page */
    $('.artist-page .tracklist-scrollable').bind('mousewheel', function(e) {


        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);





        //prevent page fom scrolling
        return false;


    });


    $('.artist-page .tracklist-scrollable .wishlist-popover').slice(0, 3).addClass('top');


    $(document).on('scroll','.artist-page .tracklist-scrollable', function(e) {

        $('.artist-page .tracklist-scrollable .wishlist-popover').removeClass('top');


        $('.artist-page .tracklist-scrollable .tracklist').each(function(e) {

            if ($(this).position().top >= -22 && $(this).position().top <= 110) {




                $(this).find('.wishlist-popover').addClass('top');



            }

        });

    });


    $(document).on('click', '.artist-page .tracklist-scrollable .tracklist .preview' ,function(e) {

        if ($(this).hasClass('playing')) {

            $(this).removeClass('playing');

            $(this).parents('.row').removeClass('playing');
            $(this).parent().removeClass('playing');
            $(this).siblings('.date').removeClass('playing');
            $(this).siblings('.album').removeClass('playing');
            $(this).siblings('.artist').removeClass('playing');
            $(this).siblings('.time').removeClass('playing');
            $(this).siblings('.song').removeClass('playing');
            $(this).siblings('.add-to-wishlist-button').removeClass('playing');
            $(this).siblings('.download').removeClass('playing');


        } else {

            $('.artist-page .tracklist-scrollable .tracklist').removeClass('playing');
            $('.artist-page .tracklist-scrollable .tracklist .date').removeClass('playing');
            $('.artist-page .tracklist-scrollable .tracklist .preview').removeClass('playing');
            $('.artist-page .tracklist-scrollable .tracklist .album').removeClass('playing');
            $('.artist-page .tracklist-scrollable .tracklist .artist').removeClass('playing');
            $('.artist-page .tracklist-scrollable .tracklist .time').removeClass('playing');
            $('.artist-page .tracklist-scrollable .tracklist .song').removeClass('playing');
            $('.artist-page .tracklist-scrollable .tracklist .add-to-wishlist-button').removeClass('playing');
            $('.artist-page .tracklist-scrollable .tracklist .download').removeClass('playing');

            $(this).addClass('playing');
            $(this).parents('.tracklist').addClass('playing');
            $(this).parent().addClass('playing');
            $(this).siblings('.date').addClass('playing');
            $(this).siblings('.album').addClass('playing');
            $(this).siblings('.artist').addClass('playing');
            $(this).siblings('.time').addClass('playing');
            $(this).siblings('.song').addClass('playing');
            $(this).siblings('.add-to-wishlist-button').addClass('playing');
            $(this).siblings('.download').addClass('playing');


        }

    });
    /* end artist page */


    /* genres page */

    $('.genre-list').bind('mousewheel', function(e) {



        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);





        //prevent page fom scrolling
        return false;

    });

    $('.alphabetical-filter').bind('mousewheel', function(e) {



        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);





        //prevent page fom scrolling
        return false;

    });


    $('.artist-list').bind('mousewheel', function(e) {



        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);





        //prevent page fom scrolling
        return false;

    });

    $('.album-list').bind('mousewheel', function(e) {



        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);





        //prevent page fom scrolling
        return false;

    });

    $(document).on('click', '.tracklist .preview' ,function(e) {
        e.preventDefault();

    });

    $(document).on('click', '.tracklist .add-to-playlist-button' ,function(e) {
        e.preventDefault();
        $(this).siblings('.wishlist-popover').addClass('active');
    });


    $(document).on('click', '.genre-list a' , function(e) {
        var genre_type = $(this).data('genre');
        $('.genre-list a').removeClass('selected');
        $('.alphabetical-filter a').removeClass('selected');
        $('.artist-list a').removeClass('selected');
        $(this).addClass('selected');


    });

    $(document).on('click', '.alphabetical-filter a' ,function(e) {

        var letter = $(this).data('letter');
        $('.alphabetical-filter a').removeClass('selected');
        $('.artist-list a').removeClass('selected');
        $(this).addClass('selected');
    });


    $(document).on('click', '.artist-list a' ,function(e) {
        var artist = $(this).data('artist');
        $('.artist-list a').removeClass('selected');
        $(this).addClass('selected');
    });

    $(document).on('mousedown', '.more-by' ,function(e) {

        $(this).css('background', 'url(images/genres/more-by-click.jpg)')

    });


    $(document).on('click', '.album-image a' , function(e) {

        $('.album-image').removeClass('selected');
        $(this).parent('.album-image').addClass('selected');
    });



    $(document).on('click', '.genres-page .tracklist .preview', function(e) {



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

    /* end genres page */


    /* history page */

    $('.history-page .history-scrollable').bind('mousewheel', function(e) {


        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);





        //prevent page fom scrolling
        return false;


    });

    $('.history-page .history-scrollable').bind('mousewheel', function(e) {


        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);





        //prevent page fom scrolling
        return false;


    });

    $(document).on('click', '.history-page .add-to-wishlist-button' , function(e) {
        e.preventDefault();

        $(this).siblings('.wishlist-popover').addClass('active');
    });

    $(document).on('mouseleave', '.history-page .wishlist-popover' , function(e) {

        $(this).removeClass('active');
    });


    $('.history-page .history-scrollable .wishlist-popover').slice(0, 3).addClass('top');

    $(document).on('scroll', '.history-page .history-scrollable' , function(e) {

        $('.history-page .history-scrollable .wishlist-popover').removeClass('top');


        $('.history-page .history-scrollable .row').each(function(e) {

            if ($(this).position().top >= -22 && $(this).position().top <= 110) {




                $(this).find('.wishlist-popover').addClass('top');



            }

        });

    });

    $(document).on('mouseenter', '.history-page .history-scrollable .row' , function() {
        $(this).find('.date').addClass('hovered');
        $(this).find('.album-title').addClass('hovered');
        $(this).find('.artist-name').addClass('hovered');
        $(this).find('.time').addClass('hovered');
        $(this).find('.song-title').addClass('hovered');
        $(this).find('.preview').addClass('hovered');
        $(this).find('.add-to-wishlist-button').addClass('hovered');

    });

    $(document).on('mouseleave', '.history-page .history-scrollable .row', function() {
        $(this).find('.date').removeClass('hovered');
        $(this).find('.album-title').removeClass('hovered');
        $(this).find('.artist-name').removeClass('hovered');
        $(this).find('.time').removeClass('hovered');
        $(this).find('.song-title').removeClass('hovered');
        $(this).find('.preview').removeClass('hovered');
        $(this).find('.add-to-wishlist-button').removeClass('hovered');

    });


    $(document).on('mouseenter', '.history-page .history-scrollable .row .preview' ,function(e) {
        $(this).removeClass('hovered').addClass('blue-bkg');

    });

    $(document).on('mouseleave', '.history-page .history-scrollable .row .preview' , function(e) {
        $(this).removeClass('blue-bkg').addClass('hovered');

    });

    $(document).on('click', '.history-page .history-scrollable .row .preview' , function(e) {

        if ($(this).hasClass('playing')) {

            $(this).removeClass('playing');

            $(this).parents('.row').removeClass('playing');
            $(this).parent().removeClass('playing');
            $(this).siblings('.date').removeClass('playing');
            $(this).siblings('.album-title').removeClass('playing');
            $(this).siblings('.artist-name').removeClass('playing');
            $(this).siblings('.time').removeClass('playing');
            $(this).siblings('.song-title').removeClass('playing');
            $(this).siblings('.add-to-wishlist-button').removeClass('playing');
            $(this).siblings('.download').removeClass('playing');


        } else {

            $('.history-page .history-scrollable .row').removeClass('playing');
            $('.history-page .history-scrollable .row .date').removeClass('playing');
            $('.history-page .history-scrollable .row .preview').removeClass('playing');
            $('.history-page .history-scrollable .row .album-title').removeClass('playing');
            $('.history-page .history-scrollable .row .artist-name').removeClass('playing');
            $('.history-page .history-scrollable .row .time').removeClass('playing');
            $('.history-page .history-scrollable .row .song-title').removeClass('playing');
            $('.history-page .history-scrollable .row .add-to-wishlist-button').removeClass('playing');
            $('.history-page .history-scrollable .row .download').removeClass('playing');

            $(this).addClass('playing');
            $(this).parents('.row').addClass('playing');
            $(this).parent().addClass('playing');
            $(this).siblings('.date').addClass('playing');
            $(this).siblings('.album-title').addClass('playing');
            $(this).siblings('.artist-name').addClass('playing');
            $(this).siblings('.time').addClass('playing');
            $(this).siblings('.song-title').addClass('playing');
            $(this).siblings('.add-to-wishlist-button').addClass('playing');
            $(this).siblings('.download').addClass('playing');

        }

    });
    /* end history page */


    /* my account page */
    $('.my-account-page input[type="submit"]').on('mousedown', function(e) {
        $(this).addClass('clicked');
    });

    $('.my-account-page input[type="submit"]').on('mouseup', function(e) {
        $(this).removeClass('clicked');
    });
    /* end my account page */



    /* my top 10 page */
     $(document).on('mouseenter', '.songs-scrollable .song-container', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.top-10-download-now-button').css({opacity: 1});
        $(this).find('.preview').css({opacity: 1});

    });

    $(document).on('mouseleave', '.songs-scrollable .song-container', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.top-10-download-now-button').css({opacity: 0});
        $(this).find('.preview').css({opacity: 0});

    });

    $(document).on('mouseenter', '.videos-scrollable .video-container', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.top-10-download-now-button').css({opacity: 1});

    });

    $(document).on('mouseleave', '.videos-scrollable .video-container', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.top-10-download-now-button').css({opacity: 0});

    });
    /* end my top 10 page */



    /****** Search page ******/
    $(document).on('mouseenter', '.videos-scrollable .video-container' ,function() {
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.top-10-download-now-button').css({opacity: 1});


    });

    $(document).on('mouseleave', '.videos-scrollable .video-container' ,function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.top-10-download-now-button').css({opacity: 0});


    });

    /* my wishlist page */

    $('.my-wishlist-page .my-wishlist-scrollable').bind('mousewheel', function(e) {


        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);





        //prevent page fom scrolling
        return false;


    });

    $('.my-wishlist-page .my-video-wishlist-scrollable').bind('mousewheel', function(e) {


        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);





        //prevent page fom scrolling
        return false;


    });





    $(document).on('click', '.my-wishlist-page .add-to-wishlist-button', function(e) {
        e.preventDefault();

        $(this).siblings('.wishlist-popover').addClass('active');
    });

    $(document).on('mouseleave', '.my-wishlist-page .wishlist-popover' ,function(e) {

        $(this).removeClass('active');
    });


    $('.my-wishlist-page .my-wishlist-scrollable .wishlist-popover').slice(0, 3).addClass('top');


    $('.my-wishlist-page .my-wishlist-scrollable').on('scroll', function(e) {

        $('.my-wishlist-page .my-wishlist-scrollable .wishlist-popover').removeClass('top');


        $('.my-wishlist-page .my-wishlist-scrollable .row').each(function(e) {

            if ($(this).position().top >= -22 && $(this).position().top <= 110) {




                $(this).find('.wishlist-popover').addClass('top');



            }

        });

    });


    $(document).on('mouseenter', '.my-wishlist-page .my-wishlist-scrollable .row' ,function() {
        $(this).find('.date').addClass('hovered');
        $(this).find('.album-title').addClass('hovered');
        $(this).find('.artist-name').addClass('hovered');
        $(this).find('.time').addClass('hovered');
        $(this).find('.song-title').addClass('hovered');
        $(this).find('.preview').addClass('hovered');
        $(this).find('.delete-btn').addClass('hovered');
        $(this).find('.add-to-wishlist-button').addClass('hovered');

    });

    $(document).on('mouseleave', '.my-wishlist-page .my-wishlist-scrollable .row' ,function() {
        $(this).find('.date').removeClass('hovered');
        $(this).find('.album-title').removeClass('hovered');
        $(this).find('.artist-name').removeClass('hovered');
        $(this).find('.time').removeClass('hovered');
        $(this).find('.song-title').removeClass('hovered');
        $(this).find('.preview').removeClass('hovered');
        $(this).find('.delete-btn').removeClass('hovered');
        $(this).find('.add-to-wishlist-button').removeClass('hovered');

    });

    $(document).on('mouseenter', '.my-wishlist-page .my-video-wishlist-scrollable .row' ,function() {
        $(this).find('.date').addClass('hovered');
        $(this).find('.album-title').addClass('hovered');
        $(this).find('.artist-name').addClass('hovered');
        $(this).find('.time').addClass('hovered');
        $(this).find('.song-title').addClass('hovered');
        $(this).find('.preview').addClass('hovered');
        $(this).find('.delete-btn').addClass('hovered');
        $(this).find('.add-to-wishlist-button').addClass('hovered');

    });

    $(document).on('mouseleave', '.my-wishlist-page .my-video-wishlist-scrollable .row' ,function() {
        $(this).find('.date').removeClass('hovered');
        $(this).find('.album-title').removeClass('hovered');
        $(this).find('.artist-name').removeClass('hovered');
        $(this).find('.time').removeClass('hovered');
        $(this).find('.song-title').removeClass('hovered');
        $(this).find('.preview').removeClass('hovered');
        $(this).find('.delete-btn').removeClass('hovered');
        $(this).find('.add-to-wishlist-button').removeClass('hovered');

    });





    $(document).on('mouseenter', '.my-wishlist-page .my-wishlist-scrollable .row .preview' ,function(e) {
        $(this).removeClass('hovered').addClass('blue-bkg');

    });

    $(document).on('mouseleave', '.my-wishlist-page .my-wishlist-scrollable .row .preview', function(e) {
        $(this).removeClass('blue-bkg').addClass('hovered');

    });

    $(document).on('click', '.my-wishlist-page .my-wishlist-scrollable .row .preview',function(e) {

        if ($(this).hasClass('playing')) {

            $(this).removeClass('playing');

            $(this).parents('.row').removeClass('playing');
            $(this).parent().removeClass('playing');
            $(this).siblings('.date').removeClass('playing');
            $(this).siblings('.album-title').removeClass('playing');
            $(this).siblings('.artist-name').removeClass('playing');
            $(this).siblings('.time').removeClass('playing');
            $(this).siblings('.song-title').removeClass('playing');
            $(this).siblings('.add-to-wishlist-button').removeClass('playing');
            $(this).siblings('.download').removeClass('playing');


        } else {

            $('.my-wishlist-page .my-wishlist-scrollable .row').removeClass('playing');
            $('.my-wishlist-page .my-wishlist-scrollable .row .date').removeClass('playing');
            $('.my-wishlist-page .my-wishlist-scrollable .row .preview').removeClass('playing');
            $('.my-wishlist-page .my-wishlist-scrollable .row .album-title').removeClass('playing');
            $('.my-wishlist-page .my-wishlist-scrollable .row .artist-name').removeClass('playing');
            $('.my-wishlist-page .my-wishlist-scrollable .row .time').removeClass('playing');
            $('.my-wishlist-page .my-wishlist-scrollable .row .song-title').removeClass('playing');
            $('.my-wishlist-page .my-wishlist-scrollable .row .add-to-wishlist-button').removeClass('playing');
            $('.my-wishlist-page .my-wishlist-scrollable .row .download').removeClass('playing');

            $(this).addClass('playing');
            $(this).parents('.row').addClass('playing');
            $(this).parent().addClass('playing');
            $(this).siblings('.date').addClass('playing');
            $(this).siblings('.album-title').addClass('playing');
            $(this).siblings('.artist-name').addClass('playing');
            $(this).siblings('.time').addClass('playing');
            $(this).siblings('.song-title').addClass('playing');
            $(this).siblings('.add-to-wishlist-button').addClass('playing');
            $(this).siblings('.download').addClass('playing');

        }

    });
    /* end my wishlist page */

    /* new release page */

    $(document).on('mouseenter', '.songs-scrollable .song-container' ,function() {
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.top-10-download-now-button').css({opacity: 1});
        $(this).find('.preview').css({opacity: 1});

    });

    $(document).on('mouseleave', '.songs-scrollable .song-container', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.top-10-download-now-button').css({opacity: 0});
        $(this).find('.preview').css({opacity: 0});

    });

    $(document).on('mouseenter', '.videos-scrollable .video-container', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.top-10-download-now-button').css({opacity: 1});


    });

    $(document).on('mouseleave', '.videos-scrollable .video-container', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.top-10-download-now-button').css({opacity: 0});


    });

    /* end new releases page */


    /* notifications page */

    $('.notifications-page input[type="submit"]').on('mousedown', function(e) {
        $(this).addClass('clicked');
    });

    $('.notifications-page input[type="submit"]').on('mouseup', function(e) {
        $(this).removeClass('clicked');
    });
    /* end notifications page */




    /* downloads page */

    $('.recent-downloads-page .recent-downloads-scrollable').bind('mousewheel', function(e) {


        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);





        //prevent page fom scrolling
        return false;


    });

    $('.recent-downloads-page .recent-video-downloads-scrollable').bind('mousewheel', function(e) {


        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);





        //prevent page fom scrolling
        return false;


    });





    $(document).on('click', '.recent-downloads-page .add-to-wishlist-button',function(e) {
        e.preventDefault();

        $(this).siblings('.wishlist-popover').addClass('active');
    });

    $(document).on('mouseleave', '.recent-downloads-page .wishlist-popover',function(e) {

        $(this).removeClass('active');
    });



    $('.recent-downloads-page .recent-downloads-scrollable .wishlist-popover').slice(0, 3).addClass('top');


    $('.recent-downloads-page .recent-downloads-scrollable').on('scroll', function(e) {

        $('.recent-downloads-page .recent-downloads-scrollable .wishlist-popover').removeClass('top');


        $('.recent-downloads-page .recent-downloads-scrollable .row').each(function(e) {

            if ($(this).position().top >= -22 && $(this).position().top <= 110) {




                $(this).find('.wishlist-popover').addClass('top');



            }

        });

    });


    $(document).on('mouseenter', '.recent-downloads-page .recent-downloads-scrollable .row' ,function() {
        $(this).find('.date').addClass('hovered');
        $(this).find('.album-title').addClass('hovered');
        $(this).find('.artist-name').addClass('hovered');
        $(this).find('.time').addClass('hovered');
        $(this).find('.song-title').addClass('hovered');
        $(this).find('.preview').addClass('hovered');
        $(this).find('.add-to-wishlist-button').addClass('hovered');

    });

    $(document).on('mouseleave', '.recent-downloads-page .recent-downloads-scrollable .row' ,function() {
        $(this).find('.date').removeClass('hovered');
        $(this).find('.album-title').removeClass('hovered');
        $(this).find('.artist-name').removeClass('hovered');
        $(this).find('.time').removeClass('hovered');
        $(this).find('.song-title').removeClass('hovered');
        $(this).find('.preview').removeClass('hovered');
        $(this).find('.add-to-wishlist-button').removeClass('hovered');

    });

    $(document).on('mouseenter', '.recent-downloads-page .recent-video-downloads-scrollable .row',function() {
        $(this).find('.date').addClass('hovered');
        $(this).find('.album-title').addClass('hovered');
        $(this).find('.artist-name').addClass('hovered');
        $(this).find('.time').addClass('hovered');
        $(this).find('.song-title').addClass('hovered');
        $(this).find('.preview').addClass('hovered');
        $(this).find('.add-to-wishlist-button').addClass('hovered');

    });

    $(document).on('mouseleave', '.recent-downloads-page .recent-video-downloads-scrollable .row',function() {
        $(this).find('.date').removeClass('hovered');
        $(this).find('.album-title').removeClass('hovered');
        $(this).find('.artist-name').removeClass('hovered');
        $(this).find('.time').removeClass('hovered');
        $(this).find('.song-title').removeClass('hovered');
        $(this).find('.preview').removeClass('hovered');
        $(this).find('.add-to-wishlist-button').removeClass('hovered');

    });


    $(document).on('mouseenter', '.recent-downloads-page .recent-downloads-scrollable .row .preview',function(e) {
        $(this).removeClass('hovered').addClass('blue-bkg');

    });

    $(document).on('mouseleave', '.recent-downloads-page .recent-downloads-scrollable .row .preview',function(e) {
        $(this).removeClass('blue-bkg').addClass('hovered');

    });

    $(document).on('click', '.recent-downloads-page .recent-downloads-scrollable .row .preview',function(e) {

        if ($(this).hasClass('playing')) {

            $(this).removeClass('playing');

            $(this).parents('.row').removeClass('playing');
            $(this).parent().removeClass('playing');
            $(this).siblings('.date').removeClass('playing');
            $(this).siblings('.album-title').removeClass('playing');
            $(this).siblings('.artist-name').removeClass('playing');
            $(this).siblings('.time').removeClass('playing');
            $(this).siblings('.song-title').removeClass('playing');
            $(this).siblings('.add-to-wishlist-button').removeClass('playing');
            $(this).siblings('.download').removeClass('playing');


        } else {

            $('.recent-downloads-page .recent-downloads-scrollable .row').removeClass('playing');
            $('.recent-downloads-page .recent-downloads-scrollable .row .date').removeClass('playing');
            $('.recent-downloads-page .recent-downloads-scrollable .row .preview').removeClass('playing');
            $('.recent-downloads-page .recent-downloads-scrollable .row .album-title').removeClass('playing');
            $('.recent-downloads-page .recent-downloads-scrollable .row .artist-name').removeClass('playing');
            $('.recent-downloads-page .recent-downloads-scrollable .row .time').removeClass('playing');
            $('.recent-downloads-page .recent-downloads-scrollable .row .song-title').removeClass('playing');
            $('.recent-downloads-page .recent-downloads-scrollable .row .add-to-wishlist-button').removeClass('playing');
            $('.recent-downloads-page .recent-downloads-scrollable .row .download').removeClass('playing');

            $(this).addClass('playing');
            $(this).parents('.row').addClass('playing');
            $(this).parent().addClass('playing');
            $(this).siblings('.date').addClass('playing');
            $(this).siblings('.album-title').addClass('playing');
            $(this).siblings('.artist-name').addClass('playing');
            $(this).siblings('.time').addClass('playing');
            $(this).siblings('.song-title').addClass('playing');
            $(this).siblings('.add-to-wishlist-button').addClass('playing');
            $(this).siblings('.download').addClass('playing');


        }

    });

    /* end downloads page */


    /* saved queues page */
    $('.saved-queues-page .playlist-filter-container .playlist-filter-button').addClass('active');

    $(document).on('mousedown', '.saved-queues-page .playlist-filter-container .create-playlist-button',function(e) {
        $(this).addClass('pressed');
    });


    $(document).on('mouseup', '.saved-queues-page .playlist-filter-container .create-playlist-button',function(e) {
        $(this).removeClass('pressed');
    });


    $(document).on('click', '.saved-queues-page .filter-button',function(e) {
        if ($(this).hasClass('active')) {

            if ($(this).hasClass('toggled')) {

                $(this).removeClass('toggled');
            } else {

                $(this).addClass('toggled');
            }


        } else {
            $('.saved-queues-page .filter-button').removeClass('active');
            $(this).addClass('active');

        }


    });


    $('.saved-queues-page .playlists-scrollable').bind('mousewheel', function(e) {


        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);





        //prevent page fom scrolling
        return false;


    });

    $(document).on('click', '.saved-queues-page .add-to-playlist-button',function(e) {

        $(this).siblings('.wishlist-popover').addClass('active');
    });

    $(document).on('mouseleave', '.saved-queues-page .wishlist-popover',function(e) {

        $(this).removeClass('active');
    });
    /* end saved queues page */


    /* search results page */

    $('.search-page .tracklist .preview').on('click', function(e) {



        $('.tracklist').removeClass('playing');
        $('.preview').removeClass('playing');
        $('.song').removeClass('playing');
        $('.artist').removeClass('playing');
        $('.time').removeClass('playing');
        $('.album').removeClass('playing');
        $('.download').removeClass('playing');
        $('.composer').removeClass('playing');

        $(this).parent('.tracklist').addClass('playing');
        $(this).addClass('playing');
        $(this).siblings('.song').addClass('playing');
        $(this).siblings('.artist').addClass('playing');
        $(this).siblings('.time').addClass('playing');
        $(this).siblings('.album').addClass('playing');
        $(this).siblings('.download').addClass('playing');
        $(this).siblings('.composer').addClass('playing');
    });



    $('.search-page .tracklist-scrollable').bind('mousewheel', function(e) {


        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);





        //prevent page fom scrolling
        return false;


    });

    $('.search-page .advanced-artists-scrollable').bind('mousewheel', function(e) {


        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);





        //prevent page fom scrolling
        return false;


    });


    $('.search-page .advanced-composers-scrollable').bind('mousewheel', function(e) {


        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);





        //prevent page fom scrolling
        return false;


    });


    $('.search-page .advanced-genres-scrollable').bind('mousewheel', function(e) {


        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);





        //prevent page fom scrolling
        return false;


    });

    $('.search-page .advanced-labels-scrollable').bind('mousewheel', function(e) {


        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);





        //prevent page fom scrolling
        return false;


    });




    // $('.advanced-search li:first-child a').addClass('active');


    //$('.tracklist-header .album').addClass('active');

    $(document).on('click', '.tracklist-header span' ,function(e) {
        if ($(this).hasClass('active')) {

            if ($(this).hasClass('toggled')) {

                $(this).removeClass('toggled');
            } else {

                $(this).addClass('toggled');
            }


        } else {
            $('.tracklist-header span').removeClass('active');
            $(this).addClass('active');

        }

    });


    $('.search-page .wishlist-popover').slice(0, 3).addClass('top');
    $('.search-page .tracklist').slice(0, 3).addClass('current');

    $('.search-page .tracklist-scrollable').on('scroll', function(e) {

        $('.search-page .wishlist-popover').removeClass('top');
        $('.search-page .tracklist').removeClass('current');

        $('.search-page .tracklist').each(function(e) {

            if ($(this).position().top >= -22 && $(this).position().top <= 110) {


                $(this).addClass('current');

                $(this).find('.wishlist-popover').addClass('top');



            }

        });

    });


    $('.search-page .advanced-search #submit').on('mousedown', function(e) {


        $(this).addClass('clicked');

    });

    $('.search-page .advanced-search #submit').on('mouseup', function(e) {


        $(this).removeClass('clicked');

    });

    $('.pagination a').on('click', function(e) {


        var target = $(this).attr('href');






    });
    /* end search results page */


    /* site login page */

    $('.site-login input[type="submit"]').on('mousedown', function(e) {
        $(this).addClass('selected');
    });

    $('.site-login input[type="submit"]').on('mouseup', function(e) {
        $(this).removeClass('selected');
    });

    /* end site login page */




    /* now streaming/queue detail page */

    $('.gear-icon').on('click', function(e) {
        $('.queue-options').addClass('active');

    });

    $('.queue-options').on('mouseleave', function(e) {

        $('.queue-options').removeClass('active');
    });



    $('.now-streaming-page .playlist-scrollable,.queue-detail-page .playlist-scrollable').bind('mousewheel', function(e) {


        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);





        //prevent page fom scrolling
        return false;


    });

    $('.now-streaming-page .now-playing-container .add-to-wishlist-button,.queue-detail-page .now-playing-container .add-to-wishlist-button').on('click', function(e) {
        e.preventDefault();

        $(this).siblings('.wishlist-popover').addClass('active');
    });

    $('.now-streaming-page .now-playing-container .wishlist-popover,.queue-detail-page .now-playing-container .wishlist-popover').on('mouseleave', function(e) {

        $(this).removeClass('active');
    });





    $('.now-streaming-page .playlist-scrollable .wishlist-popover,.queue-detail-page .playlist-scrollable .wishlist-popover').slice(0, 4).addClass('top');


    $('.now-streaming-page .playlist-scrollable,.queue-detail-page .playlist-scrollable').on('scroll', function(e) {

        $('.now-streaming-page .playlist-scrollable .wishlist-popover,.queue-detail-page .playlist-scrollable .wishlist-popover').removeClass('top');


        $('.now-streaming-page .playlist-scrollable .row,.queue-detail-page .playlist-scrollable .row').each(function(e) {

            if ($(this).position().top >= -22 && $(this).position().top <= 130) {




                $(this).find('.wishlist-popover').addClass('top');



            }

        });

    });


    $('.now-streaming-page .playlist-scrollable .row,.queue-detail-page .playlist-scrollable .row').on('mouseenter', function() {

        $(this).find('.album-title').addClass('hovered');
        $(this).find('.artist-name').addClass('hovered');
        $(this).find('.time').addClass('hovered');
        $(this).find('.song-title').addClass('hovered');
        $(this).find('.preview').addClass('hovered');
        $(this).find('.delete-btn').addClass('hovered');
        $(this).find('.add-to-wishlist-button').addClass('hovered');

    });

    $('.now-streaming-page .playlist-scrollable .row,.queue-detail-page .playlist-scrollable .row').on('mouseleave', function() {

        $(this).find('.album-title').removeClass('hovered');
        $(this).find('.artist-name').removeClass('hovered');
        $(this).find('.time').removeClass('hovered');
        $(this).find('.song-title').removeClass('hovered');
        $(this).find('.preview').removeClass('hovered');
        $(this).find('.delete-btn').removeClass('hovered');
        $(this).find('.add-to-wishlist-button').removeClass('hovered');

    });




    $('.now-streaming-page .playlist-scrollable .row .preview,.queue-detail-page .playlist-scrollable .row .preview').on('mouseenter', function(e) {
        $(this).removeClass('hovered').addClass('blue-bkg');

    });

    $('.now-streaming-page .playlist-scrollable .row .preview,.queue-detail-page .playlist-scrollable .row .preview').on('mouseleave', function(e) {
        $(this).removeClass('blue-bkg').addClass('hovered');

    });

    $('.now-streaming-page .playlist-scrollable .row .preview,.queue-detail-page .playlist-scrollable .row .preview').on('click', function(e) {

        if ($(this).hasClass('playing')) {

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

    $('.rename-queue').on('click', function(e) {
        e.preventDefault();
        $('.queue-overlay').addClass('active');
        $('.rename-queue-dialog-box').addClass('active');
        $('.rename-queue-dialog-box').css('margin-top', 100 + $(document).scrollTop());

    });

    $('.delete-queue').on('click', function(e) {
        e.preventDefault();
        $('.queue-overlay').addClass('active');
        $('.delete-queue-dialog-box').addClass('active');
        $('.delete-queue-dialog-box').css('margin-top', 100 + $(document).scrollTop());
    });

    $('.create-new-queue,.create-new-queue-btn').on('click', function(e) {
        e.preventDefault();
        $('.queue-overlay').addClass('active');
        $('.create-queue-dialog-box').addClass('active');
        $('.create-queue-dialog-box').css('margin-top', 100 + $(document).scrollTop());
        $('.wishlist-popover').removeClass('active');

    });



    $('.close,.text-close').on('click', function(e) {
        $('.queue-overlay').removeClass('active');
        $('.rename-queue-dialog-box').removeClass('active');
        $('.delete-queue-dialog-box').removeClass('active');
        $('.create-queue-dialog-box').removeClass('active');
    });

    /* end overlays */


    var most_popular_position = $('li.most-popular').position();
    var most_popular_width = $('li.most-popular').outerWidth();

    $('.most-popular-sub-nav').css('left', most_popular_position.left);
    $('.most-popular-sub-nav').css('width', most_popular_width);


 function computeVisibleHeight($t) {
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
    
});

//$('document').ready(function() {
//    $('#search-text').autocomplete("/search/autocomplete",
//            {
//                minChars: 1,
//                cacheLength: 10,
//                autoFill: false,
//                extraParams: {
//                    type: 'all'
//                },
//                formatItem: function(data) {
//                    return data[0];
//                },
//                formatResult: function(data) {
//                    return data[1];
//                }
//            }).result(function(e, item) {
//        $('#auto').attr('value', 1);
//        /*if(item[2]==1){
//         $('#header-search-type').val('artist');
//         } else if(item[2]==2){
//         $('#header-search-type').val('album');
//         } else if(item[2]==3){
//         $('#header-search-type').val('song');
//         }*/
//    });
//});
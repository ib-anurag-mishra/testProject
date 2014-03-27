(function($) {

    $.fn.bindMouseWheel = function() {

        return this.each(function() {

            $(this).bind('mousewheel', function(e) {

                $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);
                //prevent page fom scrolling
                return false;

            });




        });



    };


}(jQuery));

/* end lazyload initalizations */

$('document').ready(function()
{
    var doc_height = $(document).height();
    var lastScrollTop = 0;
    var scrollingDown;
    var footer_pos;
    var music_search_results = $('.master-music-search-results');

    var sidebar_anchor = $('.sidebar-anchor');
    var sidebar_a = $('.left-sidebar li a');

    var poll = $('.poll');
    var announcements = $('.announcements h4 a');

    var tooltip_a = $('.tooltip a');
    var plays_tooltip = $('.plays-tooltip');
    var filter_text = $('.filter-text');
    var filter_results = $('.filter-results');
    var music_player_container = $('.music-player-container');
    var whats_happening_filter_text = $('#whats-happening-filter-text');
    var whats_happening_filter_results = $('.whats-happening-filter-results');
    var coming_soon_singles_grid = $('#coming-soon-singles-grid');
    var site_nav_a = $('.site-nav a');
    var add_to_playlist = $('.add-to-playlist');
    var add_to_queue = $('.add-to-queue');
    var add_to_wishlist = $('.add-to-wishlist');
    var playlist_list = $('.playlist-options');
    var preview = $('.preview');
    //top_100_albums_grid.addClass('active');
    var top_100_songs_grid = $('#top-100-songs-grid');
    var grid_view_button = $('.grid-view-button');
    var grids = $('.grids');
    var artwork_container = $('.artwork-container');
    var video_thumbnail_container = $('.video-thumbnail-container');
    var library_list_scrollable = $('.library-list-scrollable');
    var most_popular_sub_nav = $('.most-popular-sub-nav');
    var footer = $('.site-footer');
    var wishlist_popover = $('.wishlist-popover');
    var footer_height = footer.height();





    /*
     $(document).on('click', '.left-sidebar li a', function(e) {
     //e.preventDefault();
     $(sidebar_a).removeClass('active');
     $(this).addClass('active');
     
     
     var home07 = $('#home07');
     home07.removeClass('active');
     var musicVideo07 = $('#musicVideo07');
     musicVideo07.removeClass('active');
     var newsRelease07 = $('#newsRelease07');
     newsRelease07.removeClass('active');
     var genre07 = $('#genre07');
     genre07.removeClass('active');
     var faq07 = $('#faq07');
     faq07.removeClass('active');
     
     });
     */

    $('#top-100-albums-grid .lazy').lazyload({
        effect: 'fadeIn',
        container: $('#top-100-albums-grid')
    });

    $('#top-100-songs-grid .lazy').lazyload({
        effect: 'fadeIn',
        container: $('#top-100-songs-grid')
    });

    $('#top-100-videos-grid .lazy').lazyload({
        effect: 'fadeIn',
        container: $('#top-100-videos-grid')
    });

    $('#coming-soon-singles-grid .lazy').lazyload({
        effect: 'fadeIn',
        container: $('#coming-soon-singles-grid')
    });

    $('#coming-soon-videos-grid .lazy').lazyload({
        effect: 'fadeIn',
        container: $('#coming-soon-videos-grid')
    });

    $('#featured-video-grid .lazy').lazyload({
        effect: 'fadeIn',
        container: $('#featured-video-grid .lazy')
    });

    $('.video-top-genres-grid .lazy').lazyload({
        effect: 'fadeIn',
        container: $('.video-top-genres-grid')
    });


    $(document).on('click', '.announcements h4 a', function(e) {
        e.preventDefault();
        if ($(poll).hasClass('active')) {
            $(poll).removeClass('active');
        } else {
            $(poll).addClass('active');

        }
    });




    $('.tooltip a').hover(
            function() {
                plays_tooltip.show();
            },
            function() {
                plays_tooltip.hide();

            });

    coming_soon_singles_grid.addClass('active');
    filter_text.on('keyup', function() {
        filter_results.show();
    });
    filter_text.on('blur', function() {
        filter_results.hide();
    });
    site_nav_a.on('click', function(e) {



    });

    whats_happening_filter_text.on('keyup', function() {
        whats_happening_filter_results.show();

    });


    $('.wishlist-popover').on('mouseleave', '.playlist-options', function() {
        $('.playlist-options').removeClass('active');
    });



    $('.add-to-playlist').on('mouseenter', function() {
        //console.log('add to playlist entered');		
        $('.playlist-options').addClass('active');

    });

    $('.genres-page .album-detail-container').on('mouseenter', '.add-to-playlist', function() {

        //console.log('add to playlist entered');
        $('.playlist-options').addClass('active');

    });

    //album-page js
    $('.albums-page .album-detail-container').on('mouseenter', '.album-cover-image', function() {
        $(this).find('.album-preview').css({opacity: 1});
        $(this).find('.add-to-playlist-button').css({opacity: 1});
    });

    $('.albums-page .album-detail-container').on('mouseleave', '.album-cover-image', function() {
        $(this).find('.album-preview').css({opacity: 0});
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.wishlist-popover').removeClass('active');
    });

    $('.albums-page .album-detail-container .tracklist-container').on('mouseleave', '.tracklist', function() {
        $(this).find('.wishlist-popover').removeClass('active');
    });


    //genres-page 
    $('.genres-page .album-detail-container').on('mouseenter', '.album-detail', function() {
        $('.album-preview').css({opacity: 1});
    });

    $('.genres-page .album-detail-container').on('mouseleave', '.album-detail', function() {
        $('.album-preview').css({opacity: 0});
        $(this).find('.wishlist-popover').removeClass('active');
    });

    $(document).on('mouseleave', '.search-page .tracklist-container .tracklist', function() {
        $(this).find('.wishlist-popover').removeClass('active');
    });

    $(document).on('mouseleave', '.genres-page .album-detail-container .album-detail .album-cover-image', function() {
        $(this).find('.wishlist-popover').removeClass('active');
    });

    $(document).on('mouseleave', '.genres-page .album-detail-container .tracklist-container .tracklist', function() {
        $(this).find('.wishlist-popover').removeClass('active');
    });

    $('.news .featured .featured-grid .featured-album-detail').on('mouseenter', '.album-cover-container', function() {
        $('.album-preview').css({opacity: 0});
        $(this).find('.album-preview').css({opacity: 1});
    });

    $('.news .featured .featured-grid .featured-album-detail').on('mouseleave', '.album-cover-container', function() {
        $(this).find('.album-preview').css({opacity: 0});
        $('.album-preview').css({opacity: 0});
        $(this).find('.wishlist-popover').removeClass('active');
    });

    /******* search page ***********/
    $('.search-page .advanced-search-results.row-1 .advanced-albums .advanced-albums-shadow-container .advanced-albums-scrollable').on('mouseenter', '.album-cover-container', function() {
        $('.album-preview').css({opacity: 0});
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.album-preview').css({opacity: 1});
    });

    $('.search-page .advanced-search-results.row-1 .advanced-albums .advanced-albums-shadow-container .advanced-albums-scrollable').on('mouseleave', '.album-cover-container', function() {
        $(this).find('.album-preview').css({opacity: 0});
        $('.album-preview').css({opacity: 0});
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.wishlist-popover').removeClass('active');
    });

    $('.search-page .advanced-search-results-albums .advanced-albums-shadow-container .advanced-albums-scrollable').on('mouseenter', '.album-cover-container', function() {
        $('.album-preview').css({opacity: 0});
        $(this).find('.album-preview').css({opacity: 1});
        $(this).find('.add-to-playlist-button').css({opacity: 1});
    });

    $('.search-page .advanced-search-results-albums .advanced-albums-shadow-container .advanced-albums-scrollable').on('mouseleave', '.album-cover-container', function() {
        $(this).find('.album-preview').css({opacity: 0});
        $('.album-preview').css({opacity: 0});
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.wishlist-popover').removeClass('active');
    });

    $('.search-page .advanced-search-results-albums .advanced-albums .advanced-albums-shadow-container .advanced-albums-scrollable .album-cover-container').on('mouseenter', '.add-to-playlist', function() {
        $(this).find('.playlist-options').addClass('active');
    });

    $('.add-to-queue').on('mouseenter', function() {

        if ($('.playlist-options').hasClass('active')) {
            $('.playlist-options').removeClass('active');
        }
    });

    $('.add-to-wishlist').on('mouseenter', function() {

        if ($('.playlist-options').hasClass('active')) {

            $('.playlist-options').removeClass('active');
        }
    });

    /*$('.playlist-options').on('mouseleave', function() {
     
     $('.playlist-options').removeClass('active');
     });
     
     $('.genres-page .album-detail-container').on('mouseleave', '.playlist-options', function() {
     
     $('.playlist-options').removeClass('active');
     }); */




    $('.playlist-options').bindMouseWheel();

    $(document).on('mousewheel', '.playlist-options', function(e) {

        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);

        return false;
    });



    preview.on('mousedown', function(e) {
        e.preventDefault();

        $(this).addClass('active');
    });
    preview.on('mouseup', function(e) {
        e.preventDefault();

        $(this).removeClass('active');
    });

    top_100_songs_grid.addClass('active');
    grid_view_button.addClass('active');
    grids.addClass('active');

    $('.video-thumbnail-container').on('mouseenter', function() {

        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.featured-video-download-now-button').css({opacity: 1});
        $(this).find('.preview').css({opacity: 1});

    });

    $('.video-thumbnail-container').on('mouseleave', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.featured-video-download-now-button').css({opacity: 0});
        $(this).find('.preview').css({opacity: 0});
        $(this).find('.wishlist-popover').removeClass('active');
    });
    /* commented this as it is creating problem for preview button  display
     $(document).on('mouseenter', artwork_container, function() {
     $(this).find('.preview').css({opacity: 1});
     });
     
     $(document).on('mouseleave', artwork_container, function() {
     $(this).find('.preview').css({opacity: .5});
     });
     
     */



    $('.library-list-scrollable').bindMouseWheel();

    // $(document).ready($('.preview').css({opacity: 0}));

    /*    $(document).on('mouseleave', '.wishlist-popover', function() {
     
     $(this).removeClass('active');
     
     
     });*/

    $(document).on('hover', '.wishlist-popover > a', function(e) {

        e.preventDefault();

        if ($('.playlist-options').hasClass('active')) {

            $('.playlist-options').removeClass('active');
        }
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

    $(document).on('mouseleave', '.account-options-menu', function(e) {

        $('.account-options-menu').removeClass('active');
    });



    $('.news .whats-happening #whats-happening-grid .post-excerpt').bindMouseWheel();

    $('.tracklist-shadow-container .tracklist-scrollable').on('mouseenter', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 1});

    });

    $('.tracklist-shadow-container .tracklist-scrollable').on('mouseleave', function() {
        $(this).find('.add-to-playlist-button').css({opacity: .5});

    });

    $('.top-music-video-cover-container').on('mouseenter', function() {

        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.top-video-login-button').css({opacity: 1});
        $(this).find('.preview').css({opacity: 1});

    });

    $('.top-music-video-cover-container').on('mouseleave', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.top-video-login-button').css({opacity: 0});
        $(this).find('.preview').css({opacity: 0});

    });

    $('.album-cover-container').on('mouseenter', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.top-100-download-now-button').css({opacity: 1});
        $(this).find('.preview').css({opacity: 1});


    });

    $('.album-cover-container').on('mouseleave', function() {

        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.top-100-download-now-button').css({opacity: 0});
        $(this).find('.preview').css({opacity: 0});
        $(this).find('.wishlist-popover').removeClass('active');

    });

    $('.song-cover-container').on('mouseenter', function() {
        $('.preview').css({opacity: 0});
        $('.album-preview').css({opacity: 0});
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.top-100-download-now-button').css({opacity: 1});
        $(this).find('.preview').css({opacity: 1});
        $(this).find('.album-preview').css({opacity: 1});
    });

    $('.song-cover-container').on('mouseleave', function() {
        $('.preview').css({opacity: 0});
        $('.album-preview').css({opacity: 0});
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.top-100-download-now-button').css({opacity: 0});
        $(this).find('.preview').css({opacity: 0});
        $(this).find('.album-preview').css({opacity: 0});
        $(this).find('.wishlist-popover').removeClass('active');
    });

    $('.single-cover-container').on('mouseenter', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 1});

    });

    $('.single-cover-container').on('mouseleave', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.wishlist-popover').removeClass('active');
    });

    $('.video-cover-container').on('mouseenter', function() {
        $(this).find('.top-video-login-button').css({opacity: 1});
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.top-100-download-now-button').css({opacity: 1});
        $(this).find('.preview').css({opacity: 1});
        $(this).find('.album-preview').css({opacity: 1});
    });

    $('.video-cover-container').on('mouseleave', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.top-100-download-now-button').css({opacity: 0});
        $(this).find('.preview').css({opacity: 0});
        $(this).find('.top-video-login-button').css({opacity: 0});
        $(this).find('.album-preview').css({opacity: 0});
        $(this).find('.wishlist-popover').removeClass('active');
    });

    $('.album-container').on('mouseleave', function() {
        $('.preview').css({opacity: 0});
        $('.album-preview').css({opacity: 0});
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.top-100-download-now-button').css({opacity: 0});
        $(this).find('.top-10-download-now-button').css({opacity: 0});
        $(this).find('.wishlist-popover').removeClass('active');
    });

    $('.album-container').on('mouseenter', function() {
        $('.preview').css({opacity: 0});
        $('.album-preview').css({opacity: 0});
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.preview').css({opacity: 1});
        $(this).find('.album-preview').css({opacity: 1});
        $(this).find('.top-100-download-now-button').css({opacity: 1});
        $(this).find('.top-10-download-now-button').css({opacity: 1});
    });

    $('.hero-image-container').on('mouseenter', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.download-now-button').css({opacity: 1});

    });

    $('.hero-image-container').on('mouseleave', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.download-now-button').css({opacity: 0});

    });

    $('.more-videos-scrollable .video-thumb-container').on('mouseenter', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.download-now-button').css({opacity: 1});

    });

    $('.more-videos-scrollable .video-thumb-container').on('mouseleave', function() {

        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.download-now-button').css({opacity: 0});
        $(this).find('.wishlist-popover').removeClass('active');
    });

    $('.top-videos-scrollable .video-thumb-container').on('mouseenter', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.download-now-button').css({opacity: 1});


    });

    $('.top-videos-scrollable .video-thumb-container').on('mouseleave', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.download-now-button').css({opacity: 0});
        $(this).find('.wishlist-popover').removeClass('active');

    });

//    $(document).on('click', '.add-to-playlist-button', function(e) {
//        e.preventDefault();
//        $('.wishlist-popover').removeClass('active');
//
//        if ($(this).next('.wishlist-popover').hasClass('active')) {
//            $(this).next('.wishlist-popover').removeClass('active');
//            $(this).find('.add-to-playlist-button').css({opacity: .5});
//        } else {
//
//            $(this).next('.wishlist-popover').addClass('active');
//        }
//    });

    $(document).on('click', '.add-to-playlist-button', function(e) {
        e.preventDefault();

        var queuelist = $(document).find('.playlist-options-test').html();
        var oldList = $(this).next('.wishlist-popover').find('.playlist-options');
        oldList.remove();

        $(this).next('.wishlist-popover').append(queuelist);

        $('.wishlist-popover').removeClass('active');

        if ($(this).next('.wishlist-popover').hasClass('active')) {
            $(this).next('.wishlist-popover').removeClass('active');
            $(this).find('.add-to-playlist-button').css({opacity: .5});
        } else {

            $(this).next('.wishlist-popover').addClass('active');
        }

        return false;
    });

    /* clickoffs */
    $(document).mouseup(function(e) {

        var container = $('.wishlist-popover');
        var container2 = $('.mejs-playlist.mejs-layer');
        var container3 = music_search_results;
        var container4 = whats_happening_filter_results;
        var container5 = $('.playlist-options');
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

    $('.site-nav .most-popular a').on('mouseenter', function(e) {
        e.preventDefault();

        $('.most-popular-sub-nav').addClass('active');
    });

    $('.most-popular-sub-nav').on('mouseleave', function() {
        $('.most-popular-sub-nav').removeClass('active');

    });

    $('.site-nav .regular').on('mouseenter', function() {
        $('.most-popular-sub-nav').removeClass('active');

    });
    /* albums page */

    $(document).on('click', '.albums-page .tracklist .preview', function(e) {



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

    $('.artist-page .tracklist-scrollable').bindMouseWheel();

    $('.artist-page .tracklist-scrollable .wishlist-popover').slice(0, 3).addClass('top');

    $(document).on('scroll', '.artist-page .tracklist-scrollable', function(e) {

        $('.artist-page .tracklist-scrollable .wishlist-popover').removeClass('top');
        $('.artist-page .tracklist-scrollable .tracklist').each(function(e) {
            if ($(this).position().top >= -22 && $(this).position().top <= 110) {
                $(this).find('.wishlist-popover').addClass('top');
            }
        });

    });

    var totalASLiWidth = 0;
    $('.artist-page .album-scrollable ul li').each(function() {
        totalASLiWidth = totalASLiWidth + $(this).outerWidth(true);

    });

    $('.artist-page .album-scrollable ul').css({width: totalASLiWidth + 5});

    var totalVSLiWidth = 0;

    $('.artist-page .videos-scrollable ul li').each(function() {
        totalVSLiWidth = totalVSLiWidth + $(this).outerWidth(true);

    });

    $('.artist-page .videos-scrollable ul').css({width: totalVSLiWidth + 5});

    $(document).on('click', '.artist-page .tracklist-scrollable .tracklist .preview', function(e) {

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
    
    $(document).on('click', '.artist-page .album-shadow-container div.paging span a', function(event) {

        // Add a class loading to the container box
        var loading_div = "<div class='loader'>";
        loading_div += "</div>";
        $('.content').append(loading_div);

        // Get the data from the link into the container box
        $('.artist-page .album-shadow-container').load($(this).attr('href'),
                function() {
                    $(document).find('.loader').fadeOut(50);
                    $(document).find('.content').find('.loader').remove();
                });

        event.preventDefault();
    });
    /* end artist page */


    /* genres page */






    $('.genre-list').bindMouseWheel();
    $('.alphabetical-filter').bindMouseWheel();
    $('.artist-list').bindMouseWheel();
    $('.album-list').bindMouseWheel();

    /* end genres page */

    $(document).on('click', '.tracklist .preview', function(e) {
        e.preventDefault();
    });

    $(document).on('click', '.tracklist .add-to-playlist-button', function(e) {
        e.preventDefault();
        $(this).siblings('.wishlist-popover').addClass('active');
        //getQueueList();
    });

    $(document).on('click', '.genre-list a', function(e) {
        var genre_type = $(this).data('genre');
        $('.genre-list a').removeClass('selected');
        $('.alphabetical-filter a').removeClass('selected');
        $('.artist-list a').removeClass('selected');
        $(this).addClass('selected');


    });

    $(document).on('click', '.alphabetical-filter a', function(e) {

        var letter = $(this).data('letter');
        $('.alphabetical-filter a').removeClass('selected');
        $('.artist-list a').removeClass('selected');
        $(this).addClass('selected');
    });

    $(document).on('click', '.artist-list a', function(e) {
        var artist = $(this).data('artist');
        $('.artist-list a').removeClass('selected');
        $(this).addClass('selected');
    });

    $(document).on('mousedown', '.more-by', function(e) {

        $(this).css('background', 'url(images/genres/more-by-click.jpg)')

    });

    $(document).on('click', '.album-image a', function(e) {

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

    $(document).on('mouseenter', '.album-cover-image', function() {
        $(this).find('.preview').css('opacity', 100);
        $(this).find('.add-to-playlist-button').css('opacity', 100);

    });

    $(document).on('mouseleave', '.album-cover-image', function() {
        $(this).find('.preview').css('opacity', 0);
        $(this).find('.add-to-playlist-button').css('opacity', 0);

    });

    /* end genres page */

    /* history page */



    $('.history-page .history-scrollable').bindMouseWheel();


    $(document).on('click', '.history-page .add-to-wishlist-button', function(e) {
        e.preventDefault();

        $(this).siblings('.wishlist-popover').addClass('active');
    });

    $(document).on('mouseleave', '.history-page .wishlist-popover', function(e) {

        $(this).removeClass('active');
    });

    $('.history-page .history-scrollable .wishlist-popover').slice(0, 3).addClass('top');

    $(document).on('scroll', '.history-page .history-scrollable', function(e) {

        $('.history-page .history-scrollable .wishlist-popover').removeClass('top');


        $('.history-page .history-scrollable .row').each(function(e) {

            if ($(this).position().top >= -22 && $(this).position().top <= 110) {
                $(this).find('.wishlist-popover').addClass('top');
            }

        });

    });




    $('.history-page .history-scrollable .row').on('mouseenter', function() {

        $(this).find('.date').addClass('hovered');
        $(this).find('.album-title').addClass('hovered');
        $(this).find('.artist-name').addClass('hovered');
        $(this).find('.time').addClass('hovered');
        $(this).find('.song-title').addClass('hovered');
        $(this).find('.preview').addClass('hovered');
        $(this).find('.add-to-wishlist-button').addClass('hovered');
    });


    $('.history-page .history-scrollable .row').on('mouseleave', function() {
        $(this).find('.date').removeClass('hovered');
        $(this).find('.album-title').removeClass('hovered');
        $(this).find('.artist-name').removeClass('hovered');
        $(this).find('.time').removeClass('hovered');
        $(this).find('.song-title').removeClass('hovered');
        $(this).find('.preview').removeClass('hovered');
        $(this).find('.add-to-wishlist-button').removeClass('hovered');

    });





    $('.history-page .history-scrollable .row .preview').on('mouseenter', function() {

        $(this).removeClass('hovered').addClass('blue-bkg');

    });

    $('.history-page .history-scrollable .row .preview').on('mouseleave', function() {
        $(this).removeClass('blue-bkg').addClass('hovered');

    });

    $(document).on('click', '.history-page .history-scrollable .row .preview', function(e) {

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


    $('.songs-scrollable .song-container').on('mouseleave', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.top-10-download-now-button').css({opacity: 0});
        $(this).find('.top-100-download-now-button').css({opacity: 0});
        $(this).find('.album-preview').css({opacity: 0});
        $(this).find('.preview').css({opacity: 0});
        $(this).find('.wishlist-popover').removeClass('active');
    });

    $('.songs-scrollable .song-container').on('mouseenter', function() {
        $('.album-preview').css({opacity: 0});
        $('.preview').css({opacity: 0});
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.top-10-download-now-button').css({opacity: 1});
        $(this).find('.top-100-download-now-button').css({opacity: 1});
        $(this).find('.album-preview').css({opacity: 1});
        $(this).find('.preview').css({opacity: 1});
    });

//$('.songs-scrollable .song-container').on('mouseenter', function() {
//        $('.preview').css({opacity: 0});
//        $(this).find('.add-to-playlist-button').css({opacity: 1});
//        $(this).find('.top-10-download-now-button').css({opacity: 1});
//        $(this).find('.preview').css({opacity: 1});
//
//    });
//
//    $('.songs-scrollable .song-container').on('mouseleave', function() {
//        $('.preview').css({opacity: 0});
//        $(this).find('.add-to-playlist-button').css({opacity: 0});
//        $(this).find('.top-10-download-now-button').css({opacity: 0});
//        $(this).find('.preview').css({opacity: 0});
//        $(this).find('.wishlist-popover').removeClass('active');
//    });

//    $('.videos-scrollable .video-container').on('mouseenter', function() {
//        $(this).find('.add-to-playlist-button').css({opacity: 1});
//        $(this).find('.top-10-download-now-button').css({opacity: 1});
//
//    });
//
//    $('.videos-scrollable .video-container').on('mouseleave', function() {
//        $(this).find('.add-to-playlist-button').css({opacity: 0});
//        $(this).find('.top-10-download-now-button').css({opacity: 0});
//    });


    /* end my top 10 page */


    /****** Search page ******/


    $('.videos-scrollable .video-container').on('mouseenter', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.top-10-download-now-button').css({opacity: 1});

    });

    $('.videos-scrollable .video-container').on('mouseleave', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.top-10-download-now-button').css({opacity: 0});
        $(this).find('.wishlist-popover').removeClass('active');
    });




//    $('.videos-scrollable .video-container').on('mousenter', function() {
//        $(this).find('.add-to-playlist-button').css({opacity: 1});
//        $(this).find('.top-10-download-now-button').css({opacity: 1});
//
//    });
//
//    $('.videos-scrollable .video-container').on('mouseleave', function() {
//        $(this).find('.add-to-playlist-button').css({opacity: 0});
//        $(this).find('.top-10-download-now-button').css({opacity: 0});
//
//    });

    /* my wishlist page */



    $('.my-wishlist-page .my-wishlist-scrollable').bindMouseWheel();
    $('.my-wishlist-page .my-video-wishlist-scrollable').bindMouseWheel();



    $(document).on('click', '.my-wishlist-page .add-to-wishlist-button', function(e) {
        e.preventDefault();

        $(this).siblings('.wishlist-popover').addClass('active');
    });

    $(document).on('mouseleave', '.my-wishlist-page .wishlist-popover', function(e) {

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

    $('.my-wishlist-page .my-wishlist-scrollable .row').on('mouseenter', function() {
        $(this).find('.date').addClass('hovered');
        $(this).find('.album-title').addClass('hovered');
        $(this).find('.artist-name').addClass('hovered');
        $(this).find('.time').addClass('hovered');
        $(this).find('.song-title').addClass('hovered');
        $(this).find('.preview').addClass('hovered');
        $(this).find('.delete-btn').addClass('hovered');
        $(this).find('.add-to-wishlist-button').addClass('hovered');

    });

    $('.my-wishlist-page .my-wishlist-scrollable .row').on('mouseleave', function() {

        $(this).find('.date').removeClass('hovered');
        $(this).find('.album-title').removeClass('hovered');
        $(this).find('.artist-name').removeClass('hovered');
        $(this).find('.time').removeClass('hovered');
        $(this).find('.song-title').removeClass('hovered');
        $(this).find('.preview').removeClass('hovered');
        $(this).find('.delete-btn').removeClass('hovered');
        $(this).find('.add-to-wishlist-button').removeClass('hovered');
    });

    $('.my-wishlist-page .my-video-wishlist-scrollable .row').on('mouseenter', function() {
        $(this).find('.date').addClass('hovered');
        $(this).find('.album-title').addClass('hovered');
        $(this).find('.artist-name').addClass('hovered');
        $(this).find('.time').addClass('hovered');
        $(this).find('.song-title').addClass('hovered');
        $(this).find('.preview').addClass('hovered');
        $(this).find('.delete-btn').addClass('hovered');
        $(this).find('.add-to-wishlist-button').addClass('hovered');

    });

    $('.my-wishlist-page .my-video-wishlist-scrollable .row').on('mouseleave', function() {

        $(this).find('.date').removeClass('hovered');
        $(this).find('.album-title').removeClass('hovered');
        $(this).find('.artist-name').removeClass('hovered');
        $(this).find('.time').removeClass('hovered');
        $(this).find('.song-title').removeClass('hovered');
        $(this).find('.preview').removeClass('hovered');
        $(this).find('.delete-btn').removeClass('hovered');
        $(this).find('.add-to-wishlist-button').removeClass('hovered');
    });





    $('.my-wishlist-page .my-wishlist-scrollable .row .preview').on('mouseenter', function() {
        $(this).removeClass('hovered').addClass('blue-bkg');

    });

    $('.my-wishlist-page .my-wishlist-scrollable .row .preview').on('mouseleave', function() {
        $(this).removeClass('blue-bkg').addClass('hovered');

    });

    $(document).on('click', '.my-wishlist-page .my-wishlist-scrollable .row .preview', function(e) {

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



    $('.recent-downloads-page .recent-downloads-scrollable').bindMouseWheel();
    $('.recent-downloads-page .recent-video-downloads-scrollable').bindMouseWheel();







    $(document).on('click', '.recent-downloads-page .add-to-wishlist-button', function(e) {
        e.preventDefault();

        $(this).siblings('.wishlist-popover').addClass('active');
    });




    $('.recent-downloads-page .wishlist-popover').on('mouseleave', function() {

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





    $('.recent-downloads-page .recent-downloads-scrollable .row').on('mouseenter', function() {
        $(this).find('.date').addClass('hovered');
        $(this).find('.album-title').addClass('hovered');
        $(this).find('.artist-name').addClass('hovered');
        $(this).find('.time').addClass('hovered');
        $(this).find('.song-title').addClass('hovered');
        $(this).find('.preview').addClass('hovered');
        $(this).find('.add-to-wishlist-button').addClass('hovered');

    });

    $('.recent-downloads-page .recent-downloads-scrollable .row').on('mouseleave', function() {
        $(this).find('.date').removeClass('hovered');
        $(this).find('.album-title').removeClass('hovered');
        $(this).find('.artist-name').removeClass('hovered');
        $(this).find('.time').removeClass('hovered');
        $(this).find('.song-title').removeClass('hovered');
        $(this).find('.preview').removeClass('hovered');
        $(this).find('.add-to-wishlist-button').removeClass('hovered');

    });



    $('.recent-downloads-page .recent-video-downloads-scrollable .row').on('mouseenter', function() {

        $(this).find('.date').addClass('hovered');
        $(this).find('.album-title').addClass('hovered');
        $(this).find('.artist-name').addClass('hovered');
        $(this).find('.time').addClass('hovered');
        $(this).find('.song-title').addClass('hovered');
        $(this).find('.preview').addClass('hovered');
        $(this).find('.add-to-wishlist-button').addClass('hovered');

    });

    $('.recent-downloads-page .recent-video-downloads-scrollable .row').on('mouseleave', function() {

        $(this).find('.date').removeClass('hovered');
        $(this).find('.album-title').removeClass('hovered');
        $(this).find('.artist-name').removeClass('hovered');
        $(this).find('.time').removeClass('hovered');
        $(this).find('.song-title').removeClass('hovered');
        $(this).find('.preview').removeClass('hovered');
        $(this).find('.add-to-wishlist-button').removeClass('hovered');
    });





    $('.recent-downloads-page .recent-downloads-scrollable .row .preview').on('mouseenter', function() {

        $(this).removeClass('hovered').addClass('blue-bkg');

    });

    $('.recent-downloads-page .recent-downloads-scrollable .row .preview').on('mouseleave', function() {

        $(this).removeClass('blue-bkg').addClass('hovered');

    });

    $(document).on('click', '.recent-downloads-page .recent-downloads-scrollable .row .preview', function(e) {

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

    $(document).on('mousedown', '.saved-queues-page .playlist-filter-container .create-playlist-button', function(e) {
        $(this).addClass('pressed');
    });


    $(document).on('mouseup', '.saved-queues-page .playlist-filter-container .create-playlist-button', function(e) {
        $(this).removeClass('pressed');
    });


    $(document).on('click', '.saved-queues-page .filter-button', function(e) {
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




    $('.saved-queues-page .playlists-scrollable').bindMouseWheel();

    $(document).on('click', '.saved-queues-page .add-to-playlist-button', function(e) {

        $(this).siblings('.wishlist-popover').addClass('active');
    });



    $('.saved-queues-page .wishlist-popover').on('mouseleave', function() {

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



    $('.search-page .tracklist-scrollable').bindMouseWheel();

    $('.search-page .advanced-artists-scrollable').bindMouseWheel();

    $('.search-page .advanced-composers-scrollable').bindMouseWheel();

    $('.search-page .advanced-genres-scrollable').bindMouseWheel();

    $('.search-page .advanced-labels-scrollable').bindMouseWheel();



    $(document).on('click', '.tracklist-header span', function(e) {
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

    $(document).on('click', '.pagination a', function(e) {
        var target = $(this).attr('href');
    });
    /* end search results page */


    /* site login page */
    $(document).on('mousedown', '.site-login input[type="submit"]', function(e) {
        $(this).addClass('selected');
    });

    $(document).on('mouseup', '.site-login input[type="submit"]', function(e) {
        $(this).removeClass('selected');
    });
    /* end site login page */


    /* now streaming/queue detail page */
    $(document).on('click', '.gear-icon', function(e) {
        $('.queue-options').addClass('active');

    });



    $('.queue-options').on('mouseleave', function() {

        $('.queue-options').removeClass('active');
    });



    $('.now-streaming-page .playlist-scrollable,.queue-detail-page .playlist-scrollable').bindMouseWheel();

    $(document).on('mouseenter', '.playlist-options', function() {

        $('.queue-detail-page .playlist-scrollable').unbind('mousewheel');

    });


    $(document).on('click', '.now-streaming-page .now-playing-container .add-to-wishlist-button,.queue-detail-page .now-playing-container .add-to-wishlist-button', function(e) {
        e.preventDefault();

        var queuelist = $(document).find('.playlist-options-test').html();
        var oldList = $(this).siblings('.wishlist-popover').find('.playlist-options');
        oldList.remove();

        $(this).siblings('.wishlist-popover').append(queuelist);

        $('.wishlist-popover').removeClass('active');

        if ($(this).siblings('.wishlist-popover').hasClass('active')) {
            $(this).siblings('.wishlist-popover').removeClass('active');
            $(this).find('.add-to-playlist-button').css({opacity: .5});
        } else {

            $(this).siblings('.wishlist-popover').addClass('active');
        }
    });


    $('.now-streaming-page .now-playing-container .wishlist-popover,.queue-detail-page .now-playing-container .wishlist-popover').on('mouseleave', function() {
        $(this).removeClass('active');

    });


    $('.now-streaming-page .playlist-scrollable .wishlist-popover,.queue-detail-page .playlist-scrollable .wishlist-popover').slice(0, 4).addClass('top');

    $(document).on('scroll', '.now-streaming-page .playlist-scrollable,.queue-detail-page .playlist-scrollable', function(e) {

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




    $('.now-streaming-page .playlist-scrollable .row .preview,.queue-detail-page .playlist-scrollable .row .preview').on('mouseenter', function() {

        $(this).removeClass('hovered').addClass('blue-bkg');
    });

    $('.now-streaming-page .playlist-scrollable .row .preview,.queue-detail-page .playlist-scrollable .row .preview').on('mouseleave', function() {

        $(this).removeClass('blue-bkg').addClass('hovered');
    });

    $(document).on('click', '.now-streaming-page .playlist-scrollable .row .preview,.queue-detail-page .playlist-scrollable .row .preview', function(e) {

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
    $(document).on('click', '.rename-queue', function(e) {
        e.preventDefault();
        $('.queue-overlay').addClass('active');
        $('.rename-queue-dialog-box').addClass('active');
        $('.rename-queue-dialog-box').css('margin-top', 100 + $(document).scrollTop());

    });

    $(document).on('click', '.delete-queue', function(e) {
        e.preventDefault();
        $('.queue-overlay').addClass('active');
        $('.delete-queue-dialog-box').addClass('active');
        $('.delete-queue-dialog-box').css('margin-top', 100 + $(document).scrollTop());
    });

    $(document).on('click', ".create-new-queue , .create-new-queue-btn", function(e) {
        e.preventDefault();
        $('.queue-overlay').addClass('active');
        $('.create-queue-dialog-box').addClass('active');
        $('.create-queue-dialog-box').css('margin-top', 100 + $(document).scrollTop());
        $('.wishlist-popover').removeClass('active');
        if (!$(this).parent().hasClass('clearfix'))
        {
            createLinkThis = $(this);
        }
        else
        {
            createLinkThis = null;
        }
        
         if ($(this).parent().parent().parent().parent().hasClass('header-container'))
                    {
                       multi_create = true;
                    }
                    else
                    {
                       multi_create = false;
                    }
    });

    $(document).on('click', '.close,.text-close', function(e) {
        $('.queue-overlay').removeClass('active');
        $('.rename-queue-dialog-box').removeClass('active');
        $('.delete-queue-dialog-box').removeClass('active');
        $('.create-queue-dialog-box').removeClass('active');
    });


    /* end overlays */


    /** Genres Page */

    $(document).on('click', '.artist-list a', function() {
        var artist = $(this).data('artist');
        $('.artist-list a').removeClass('selected');
        $(this).addClass('selected');
        $(this).css("cursor", "pointer");
    });

    $(document).on('click', '.alphabetical-filter a', function() {

        var letter = $(this).data('letter');
        $('.alphabetical-filter a').removeClass('selected');
        $('.artist-list a').removeClass('selected');
        $(this).addClass('selected');
    });


    if ($('li.most-popular').position() != 'undefined') {
        var most_popular_position = $('li.most-popular').position();
        var most_popular_width = $('li.most-popular').outerWidth();

        $('.most-popular-sub-nav').css('left', most_popular_position.left);
        $('.most-popular-sub-nav').css('width', most_popular_width);

    }

    /* calculate width for Videos > Top Videos */
    var totalTVLiWidth = 0;
    $('.video-top-genres-grid li').each(function(){
        totalTVLiWidth = totalTVLiWidth = $(this).outerWidth(true);

    });

    $('.video-top-genres-grid ul').width(totalTVLiWidth);
    



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

    $('.content').on('click', '.hp-tabs', function(e) {

        //console.log($(this).attr('href') + ' clicked');
        e.preventDefault();

    });



    var CurrentPageState = History.getState();
    var ReadycurrentPageState = CurrentPageState.url;
    if (ReadycurrentPageState.toLowerCase().indexOf("artists/view/") >= 0) {
        resetNavigation();
    }
    if (ReadycurrentPageState.toLowerCase().indexOf("artists/album/") >= 0) {
        resetNavigation();
    }
    if (ReadycurrentPageState.toLowerCase().indexOf("videos/details") >= 0) {
        resetNavigation();
    }


});

function documentHtml(html) {
    // Prepare
    var result = String(html)
            .replace(/<\!DOCTYPE[^>]*>/i, '')
            .replace(/<(html|head|body|title|meta|script)([\s\>])/gi, '<div class="document-$1"$2')
            .replace(/<\/(html|head|body|title|meta|script)\>/gi, '</div>')
            ;

    // Return
    return $.trim(result);
}

function callSearchAjax() {
    $("#headerSearchSubmit").click(function(event) {
        console.log('called ajaxsearch');
        ajaxSearch();
        return false;
    });
}

$(document).ready(function(){
    
    $('#headerSearchSubmit').on('click', function() {
        ajaxSearch();
        return false;
    });
    
    $('#btnMyAccount').on('click', function() {
        ajaxMyAccount();
        return false;
    });

    $('#btnNotification').on('click', function() {
        ajaxNotification();
        return false;
    });
    
});

function resetNavigation() {

    var sidebar_anchor = $('.sidebar-anchor');
    sidebar_anchor.removeClass('active');
    var sidebar_sub_nav_07 = $('.sidebar-sub-nav');
    sidebar_sub_nav_07.removeClass('active');
    var stream_sidebar_sub_nav_07 = $('.stream-sidebar-sub-nav');
    stream_sidebar_sub_nav_07.removeClass('active'); 
    var queue_sidebar_sub_nav_07 = $('.queue-sidebar-sub-nav');
    queue_sidebar_sub_nav_07.removeClass('active');     
    var sidebar_freegalqueues = $('.leftfqueuesclass');
    sidebar_freegalqueues.removeClass('active');

    var home07 = $('#home07');
    var musicVideo07 = $('#musicVideo07');
    var newsRelease07 = $('#newsRelease07');
    var genre07 = $('#genre07');
    var faq07 = $('#faq07');

    musicVideo07.removeClass('active');
    newsRelease07.removeClass('active');
    genre07.removeClass('active');
    faq07.removeClass('active');
    home07.removeClass('active');



}

function ajaxSearch() {
    resetNavigation();

    var loading_div = "<div class='loader'>";
    loading_div += "</div>";
    $('.content').append(loading_div);

    var q = $('#search-text').val();
    var type = $('#master-filter').val();

    History.pushState(null, 'Search', '/search/index' + '?' + 'q=' + q + '&type=' + type);
    $(document).find('.ac_results').remove();
    
    $.ajax({
        url: '/search/index',
        method: 'get',
        data: {'q': q, 'type': type , 'layout' : 'ajax'},
        success: function(response) {
            var className = $('body').attr('class');
            $('body').removeClass(className);
            $('body').addClass('page-search-index');

            $(document).find('.content').find('section').remove();
            $(document).find('.content').append(response);            

            //$.getScript(webroot + 'css/styles.css');
            //$.getScript(webroot + 'css/freegal_styles.css');

            //$.getScript(webroot + 'js/site.js');
            //$.getScript(webroot + 'js/freegal.js');

            $('.loader').fadeOut(500);
            $('.content').remove('.loader');

            $(document).find('.content').ajaxify().css('opacity', 100).show();
            $('div.ac_results').hide();
            $('#search-text').val('');
            //callSearchAjax();
        },
        error: function(response) {
           
        }
    });
    
    return false;
}
// code to ajaxify MyAccount form start
function callMyAccountAjax() {
    $("#btnMyAccount").click(function(event) {
        ajaxMyAccount();
    });
}

function ajaxMyAccount() {
    //console.log('inside ajaxMyaccount');
//       $('#btnMyAccount').click(function(){
    var UFirstName = '';
    var ULastName = '';
    var UEmail = '';
    var UPassword = '';
    var UserID = '';
    var contentSelector = '.content,article:first,.article:first,.post:first';
    var $content = $(contentSelector).filter(':first');
    var $body = $(document.body);
    //  Ensure Content
    if ($content.length === 0) {
        $content = $body;
    }
    /*
     var q = $('#search-text').val();
     var type = $('#master-filter').val();
     */
    if ($('#UserFirstName').val()) {
        UFirstName = $('#UserFirstName').val();
    }
    if ($('#UserLastName').val()) {
        ULastName = $('#UserLastName').val();
    }
    if ($('#UserEmail').val()) {
        UEmail = $('#UserEmail').val();
    }
    if ($('#UserPassword').val()) {
        UPassword = $('#UserPassword').val();
    }
    if ($('#UserId').val()) {
        UserID = $('#UserId').val();
    }
    var loading_div = "<div class='loader'>";
    loading_div += "</div>";
    $('.content').append(loading_div);

    // Start Fade Out
    // Animating to opacity to 0 still keeps the element's height intact
    // Which prevents that annoying pop bang issue when loading in new content
    $content.animate({opacity: 0}, 800);


    $.ajax({
        url: '/users/my_account',
        method: 'post',
        data: {'data[User][id]': UserID, 'data[User][first_name]': UFirstName, 'data[User][last_name]': ULastName, 'data[User][email]': UEmail, 'data[User][password]': UPassword},
        success: function(response) {
            $('.content').html($(response).filter('.content'));
            // Prepare
            var $data = $(documentHtml(response)),
                    $dataBody = $data.find('.document-body:first'),
                    $dataContent = $dataBody.find(contentSelector).filter(':first'),
                    $menuChildren, contentHtml, $scripts;

            // Fetch the scripts
            $scripts = $dataContent.find('.document-script');
            if ($scripts.length) {
                $scripts.detach();
            }

            // Fetch the content
            contentHtml = $dataContent.html() || $data.html();
            if (!contentHtml) {
                alert('Problem fetching data');
                return false;
            }

            // Update the menu
            /*
             $menuChildren = $menu.find(menuChildrenSelector);
             $menuChildren.filter(activeSelector).removeClass(activeClass);
             $menuChildren = $menuChildren.has('a[href^="' + relativeUrl + '"],a[href^="/' + relativeUrl + '"],a[href^="' + url + '"]');
             if ($menuChildren.length === 1) {
             $menuChildren.addClass(activeClass);
             }
             */

            // Update the content
            $content.stop(true, true);
            $content.html(contentHtml).ajaxify().css('opacity', 100).show(); /* you could fade in here if you'd like */

            // Update the title
            document.title = $data.find('.document-title:first').text();
            try {
                document.getElementsByTagName('title')[0].innerHTML = document.title.replace('<', '&lt;').replace('>', '&gt;').replace(' & ', ' &amp; ');
            }
            catch (Exception) {
            }

            // Add the scripts
            if ($scripts.length > 1) {
                $scripts.each(function() {
                    var $script = $(this), scriptText = $script.text(), scriptNode = document.createElement('script');
                    if ($script.attr('src')) {
                        if (!$script[0].async) {
                            scriptNode.async = false;
                        }
                        scriptNode.src = $script.attr('src');
                    }
                    scriptNode.appendChild(document.createTextNode(scriptText));
                    contentNode.appendChild(scriptNode);
                });
            }

            // Complete the change
            if ($body.ScrollTo || false) {
                $body.ScrollTo(scrollOptions);
            } /* http://balupton.com/projects/jquery-scrollto */


            //$body.removeClass('loader');
            $.getScript(webroot + 'css/styles.css');
            $.getScript(webroot + 'css/freegal_styles.css');

            //$.getScript(webroot + 'js/freegal.js');
            //$.getScript(webroot + 'js/site.js');

            $.getScript(webroot + 'js/audioPlayer.js');
            $.getScript(webroot + 'js/recent-downloads.js');
            $.getScript(webroot + 'js/search-results.js');


            $('.loader').fadeOut(500);

            $('.content').remove('.loader');
            //callMyAccountAjax();
        },
        failure: function() {
            alert('Problem fetching data');
        }
    });
    return false;
//    });
}
// code to ajaxify MyAccount form end

// code to ajaxify Notification form start
function callNotificationAjax() {
    $("#btnNotification").click(function(event) {
        ajaxNotification();
    });
}

// code to ajaxify Notification form end
function ajaxNotification() {
    //console.log('inside ajaxnotification');
//       $('#btnMyAccount').click(function(){
    var USendNewsLetterCheck = '';
    var UNewsletterEmail = '';
//            var UserID='';
    var contentSelector = '.content,article:first,.article:first,.post:first';
    var $content = $(contentSelector).filter(':first');
    var $body = $(document.body);
    //  Ensure Content
    if ($content.length === 0) {
        $content = $body;
    }
    /*
     var q = $('#search-text').val();
     var type = $('#master-filter').val();
     */
    if ($('#UserSendNewsLetterCheck').val()) {
        USendNewsLetterCheck = $('#UserSendNewsLetterCheck').val();
    }
    if ($('#UserNewsletterEmail').val()) {
        UNewsletterEmail = $('#UserNewsletterEmail').val();
    }

    /*if($('#UserId').val()){
     UserID=$('#UserId').val();
     }*/
    var loading_div = "<div class='loader'>";
    loading_div += "</div>";
    $('.content').append(loading_div);

    // Start Fade Out
    // Animating to opacity to 0 still keeps the element's height intact
    // Which prevents that annoying pop bang issue when loading in new content
    $content.animate({opacity: 0}, 800);


    $.ajax({
        url: '/users/manage_notification',
        method: 'post',
        data: {'data[User][sendNewsLetterCheck]': USendNewsLetterCheck, 'data[User][NewsletterEmail]': UNewsletterEmail},
        success: function(response) {
            $('.content').html($(response).filter('.content'));
            // Prepare
            var $data = $(documentHtml(response)),
                    $dataBody = $data.find('.document-body:first'),
                    $dataContent = $dataBody.find(contentSelector).filter(':first'),
                    $menuChildren, contentHtml, $scripts;

            // Fetch the scripts
            $scripts = $dataContent.find('.document-script');
            if ($scripts.length) {
                $scripts.detach();
            }

            // Fetch the content
            contentHtml = $dataContent.html() || $data.html();
            if (!contentHtml) {
                alert('Problem fetching data');
                return false;
            }

            // Update the menu
            /*
             $menuChildren = $menu.find(menuChildrenSelector);
             $menuChildren.filter(activeSelector).removeClass(activeClass);
             $menuChildren = $menuChildren.has('a[href^="' + relativeUrl + '"],a[href^="/' + relativeUrl + '"],a[href^="' + url + '"]');
             if ($menuChildren.length === 1) {
             $menuChildren.addClass(activeClass);
             }
             */

            // Update the content
            $content.stop(true, true);
            $content.html(contentHtml).ajaxify().css('opacity', 100).show(); /* you could fade in here if you'd like */

            // Update the title
            document.title = $data.find('.document-title:first').text();
            try {
                document.getElementsByTagName('title')[0].innerHTML = document.title.replace('<', '&lt;').replace('>', '&gt;').replace(' & ', ' &amp; ');
            }
            catch (Exception) {
            }

            // Add the scripts
            if ($scripts.length > 1) {
                $scripts.each(function() {
                    var $script = $(this), scriptText = $script.text(), scriptNode = document.createElement('script');
                    if ($script.attr('src')) {
                        if (!$script[0].async) {
                            scriptNode.async = false;
                        }
                        scriptNode.src = $script.attr('src');
                    }
                    scriptNode.appendChild(document.createTextNode(scriptText));
                    contentNode.appendChild(scriptNode);
                });
            }

            // Complete the change
            if ($body.ScrollTo || false) {
                $body.ScrollTo(scrollOptions);
            } /* http://balupton.com/projects/jquery-scrollto */


            //$body.removeClass('loader');
            $.getScript(webroot + 'css/styles.css');
            $.getScript(webroot + 'css/freegal_styles.css');

            //$.getScript(webroot + 'js/freegal.js');
            //$.getScript(webroot + 'js/site.js');

            $.getScript(webroot + 'js/audioPlayer.js');
            $.getScript(webroot + 'js/recent-downloads.js');
            $.getScript(webroot + 'js/search-results.js');


            $('.loader').fadeOut(500);

            $('.content').remove('.loader');
            //callNotificationAjax();
        },
        failure: function() {
            alert('Problem fetching data');
        }
    });
    return false;
//    });
}


//funciton to get Queue List on fly
function getQueueList(ProdID, type, addTo) {

    $.ajax({
        type: "post",
        data: {'prodID': ProdID, 'type': type},
        url: webroot + 'queues/queueListAlbums',
        success: function(response)
        {
            $(addTo).next('.wishlist-popover').append(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // log the error to the console
            console.log(
                    "The following error occured: " +
                    textStatus, errorThrown);
        }
    });
    return false;
}

$('document').ready(function() {
    $('#search-text').autocomplete("/search/autocomplete",
            {
                minChars: 3,
                cacheLength: 10,
                autoFill: false,
                width:177,
                extraParams: {
                    type: 'all'
                },
                formatItem: function(data) {
                    return data[0];
                },
                formatResult: function(data) {
                    return data[1];
                }
            }).result(function(e, item) {
        $('#auto').attr('value', 1);
        /*if(item[2]==1){
         $('#header-search-type').val('artist');
         } else if(item[2]==2){
         $('#header-search-type').val('album');
         } else if(item[2]==3){
         $('#header-search-type').val('song');
         }*/
    });

    //callSearchAjax();
    //callMyAccountAjax();
    //callNotificationAjax();

});

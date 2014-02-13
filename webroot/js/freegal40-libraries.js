var message = "Function Disabled!";
var id;
var isIE = (navigator.appVersion.indexOf("MSIE") != -1) ? true : false;
var isWin = (navigator.appVersion.toLowerCase().indexOf("win") != -1) ? true : false;
var isOpera = (navigator.userAgent.indexOf("Opera") != -1) ? true : false;

///////////////////////////////////
function clickIE4() {
    if (event.button === 2) {
        return false;
    }
}

function clickNS4(e) {
    if (document.layers || document.getElementById && !document.all) {
        if (e.which === 2 || e.which === 3) {
            return false;
        }
    }
}

if (document.layers) {
    document.captureEvents(Event.MOUSEDOWN);
    document.onmousedown = clickNS4;
}
document.oncontextmenu = new Function("return false");

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

$(document).ready(function()
{
    var CurrentPageState = History.getState();
    var ReadycurrentPageState = CurrentPageState.url;

    /******************* variable used in webpages *************/
    var doc_height = $(document).height();
    var lastScrollTop = 0;
    var scrollingDown;
    var footer_pos;
    var music_search_results = $('.master-music-search-results');
    var plays_tooltip = $('.plays-tooltip');
    var filter_text = $('.filter-text');
    var filter_results = $('.filter-results');
    var music_player_container = $('.music-player-container');
    var whats_happening_filter_text = $('#whats-happening-filter-text');
    var whats_happening_filter_results = $('.whats-happening-filter-results');
    var coming_soon_singles_grid = $('#coming-soon-singles-grid');
    var site_nav_a = $('.site-nav a');
    var preview = $('.preview');
    var top_100_songs_grid = $('#top-100-songs-grid');
    var grid_view_button = $('.grid-view-button');
    var grids = $('.grids');
    var most_popular_sub_nav = $('.most-popular-sub-nav');
    var footer = $('.site-footer');
    var footer_height = footer.height();

    top_100_songs_grid.addClass('active');
    grid_view_button.addClass('active');
    grids.addClass('active');
    preview.on('mousedown', function(e) {
        e.preventDefault();

        $(this).addClass('active');
    });
    preview.on('mouseup', function(e) {
        e.preventDefault();

        $(this).removeClass('active');
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


    if (ReadycurrentPageState.toLowerCase().indexOf("artists/view/") >= 0) {
        resetNavigation();
    }
    if (ReadycurrentPageState.toLowerCase().indexOf("artists/album/") >= 0) {
        resetNavigation();
    }
    if (ReadycurrentPageState.toLowerCase().indexOf("videos/details") >= 0) {
        resetNavigation();
    }

    $('.content').on('click', '.hp-tabs', function(e) {
        //console.log($(this).attr('href') + ' clicked');
        e.preventDefault();
    });

    /************* clickoffs ***************/
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
    /************* end clickoffs ************/

    if ($('li.most-popular').position() != 'undefined') {
        var most_popular_position = $('li.most-popular').position();
        var most_popular_width = $('li.most-popular').outerWidth();

        $('.most-popular-sub-nav').css('left', most_popular_position.left);
        $('.most-popular-sub-nav').css('width', most_popular_width);

    }
    
    /**************** end lazyload initalizations *****************/
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
    /**************** end lazyload initalizations *****************/
    $('.tooltip a').hover(
            function() {
                plays_tooltip.show();
            },
            function() {
                plays_tooltip.hide();
            }
    );
    /*********************Genreal Javascript ************************/
    $('.library-list-scrollable').bindMouseWheel();
    $('.playlist-options').bindMouseWheel();
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
    $('.videos-scrollable .video-container').on('mouseenter', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.top-10-download-now-button').css({opacity: 1});

    });
    $('.videos-scrollable .video-container').on('mouseleave', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.top-10-download-now-button').css({opacity: 0});
        $(this).find('.wishlist-popover').removeClass('active');
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
    $('.tracklist-shadow-container .tracklist-scrollable').on('mouseenter', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 1});
    });
    $('.tracklist-shadow-container .tracklist-scrollable').on('mouseleave', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
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
    $('.wishlist-popover').on('mouseleave', '.playlist-options', function() {
        $('.playlist-options').removeClass('active');
    });
    $('.add-to-playlist').on('mouseenter', function() {
        $('.playlist-options').addClass('active');
    });
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
    $(document).on('mousewheel', '.playlist-options', function(e) {
        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);
        return false;
    });
    /*********************Genreal Javascript ************************/

    /******************** Play button click event *******************/
    $(document).on('click', '.play-queue-btn', function() {
        playlist = $('#playlist_data').text();
        playlist = JSON.parse(playlist);
        if (playlist.length) {
            pushSongs(playlist);
        }
    });
    $(document).on('click', '.play-album-btn', function() {
        playlist = $('#playlist_data').text();
        playlist = JSON.parse(playlist);
        if (playlist.length) {
            pushSongs(playlist);
        }
    });
    /******************** Play button click event *******************/

    /******************** Add to Playlist event *******************/
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
    /******************** Add to Playlist event *******************/

    /********************* site login page **********************/
    $(document).on('mousedown', '.site-login input[type="submit"]', function(e) {
        $(this).addClass('selected');
    });
    $(document).on('mouseup', '.site-login input[type="submit"]', function(e) {
        $(this).removeClass('selected');
    });
    /********************* site login page **********************/

    /************** overlays **********************/
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
    });
    $(document).on('click', '.close,.text-close', function(e) {
        $('.queue-overlay').removeClass('active');
        $('.rename-queue-dialog-box').removeClass('active');
        $('.delete-queue-dialog-box').removeClass('active');
        $('.create-queue-dialog-box').removeClass('active');
    });
    /*********************** end overlays **************************/

    /*************** notifications page ************************/
    $('.notifications-page input[type="submit"]').on('mousedown', function(e) {
        $(this).addClass('clicked');
    });
    $('.notifications-page input[type="submit"]').on('mouseup', function(e) {
        $(this).removeClass('clicked');
    });
    /*************** notifications page ************************/

    /************* my top 10 page **************/
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
    /************* my top 10 page **************/

    /***************** my account page *********************/
    $('.my-account-page input[type="submit"]').on('mousedown', function(e) {
        $(this).addClass('clicked');
    });
    $('.my-account-page input[type="submit"]').on('mouseup', function(e) {
        $(this).removeClass('clicked');
    });
    /***************** my account page *********************/

    /****************Home Page************************/
    $('.news .whats-happening #whats-happening-grid .post-excerpt').bindMouseWheel();
    $('.news .featured .featured-grid .featured-album-detail').on('mouseenter', '.album-cover-container', function() {
        $('.album-preview').css({opacity: 0});
        $(this).find('.album-preview').css({opacity: 1});
    });
    $('.news .featured .featured-grid .featured-album-detail').on('mouseleave', '.album-cover-container', function() {
        $(this).find('.album-preview').css({opacity: 0});
        $('.album-preview').css({opacity: 0});
        $(this).find('.wishlist-popover').removeClass('active');
    });
    $(document).on('click', '.announcements h4 a', function(e) {
        e.preventDefault();
        if ($(poll).hasClass('active')) {
            $(poll).removeClass('active');
        } else {
            $(poll).addClass('active');

        }
    });
    /****************Home Page************************/


    /************* Start Genres javascripts ***********************/
    $('.genre-list').bindMouseWheel();
    $('.alphabetical-filter').bindMouseWheel();
    $('.artist-list').bindMouseWheel();
    $('.album-list').bindMouseWheel();
    $('.genres-page .album-detail-container').on('mouseenter', '.add-to-playlist', function() {
        $('.playlist-options').addClass('active');
    });
    $('.genres-page .album-detail-container').on('mouseenter', '.album-detail', function() {
        $('.album-preview').css({opacity: 1});
    });
    $('.genres-page .album-detail-container').on('mouseleave', '.album-detail', function() {
        $('.album-preview').css({opacity: 0});
        $(this).find('.wishlist-popover').removeClass('active');
    });
    $(document).on('mouseenter', '.album-cover-image', function() {
        $(this).find('.preview').css('opacity', 100);
        $(this).find('.add-to-playlist-button').css('opacity', 100);
    });
    $(document).on('mouseleave', '.album-cover-image', function() {
        $(this).find('.preview').css('opacity', 0);
        $(this).find('.add-to-playlist-button').css('opacity', 0);
    });
    $(document).on('click', '.genre-list a', function(e) {
        var genre_type = $(this).data('genre');
        $('.genre-list a').removeClass('selected');
        $('.alphabetical-filter a').removeClass('selected');
        $('.artist-list a').removeClass('selected');
        $(this).addClass('selected');
    });
    $(document).on('click', '.alphabetical-filter a', function() {
        var letter = $(this).data('letter');
        $('.alphabetical-filter a').removeClass('selected');
        $('.artist-list a').removeClass('selected');
        $(this).addClass('selected');
    });
    $(document).on('click', '.artist-list a', function() {
        var artist = $(this).data('artist');
        $('.artist-list a').removeClass('selected');
        $(this).addClass('selected');
        $(this).css("cursor", "pointer");
    });
    $(document).on('click', '.album-image a', function() {
        $('.album-image').removeClass('selected');
        $(this).parent('.album-image').addClass('selected');
    });
    $(document).on('mousedown', '.more-by', function() {
        $(this).css('background', 'url(images/genres/more-by-click.jpg)')
    });
    $(document).on('click', '.genres-page .tracklist .preview', function() {
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
    $(document).on('mouseleave', '.genres-page .album-detail-container .album-detail .album-cover-image', function() {
        $(this).find('.wishlist-popover').removeClass('active');
    });
    $(document).on('mouseleave', '.genres-page .album-detail-container .tracklist-container .tracklist', function() {
        $(this).find('.wishlist-popover').removeClass('active');
    });
    $(document).on('click', '.tracklist .preview', function(e) {
        e.preventDefault();
    });
    $(document).on('click', '.tracklist .add-to-playlist-button', function(e) {
        e.preventDefault();
        $(this).siblings('.wishlist-popover').addClass('active');
    });
    /************* End Genres javascripts ***********************/

    /********************** Start now streaming/queue detail page ******************/
    $('.now-streaming-page .playlist-scrollable,.queue-detail-page .playlist-scrollable').bindMouseWheel();
    $('.saved-queues-page .playlists-scrollable').bindMouseWheel();
    $('.saved-queues-page .playlist-filter-container .playlist-filter-button').addClass('active');
    $('.now-streaming-page .playlist-scrollable .wishlist-popover,.queue-detail-page .playlist-scrollable .wishlist-popover').slice(0, 4).addClass('top');
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
    $('.now-streaming-page .now-playing-container .wishlist-popover,.queue-detail-page .now-playing-container .wishlist-popover').on('mouseleave', function() {
        $(this).removeClass('active');
    });
    $('.queue-options').on('mouseleave', function() {
        $('.queue-options').removeClass('active');
    });
    $('.saved-queues-page .wishlist-popover').on('mouseleave', function() {
        $(this).removeClass('active');
    });
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
    $(document).on('click', '.saved-queues-page .add-to-playlist-button', function(e) {
        $(this).siblings('.wishlist-popover').addClass('active');
    });
    $(document).on('click', '.gear-icon', function(e) {
        $('.queue-options').addClass('active');
    });
    $(document).on('mouseenter', '.playlist-options', function() {
        $('.queue-detail-page .playlist-scrollable').unbind('mousewheel');
    });
    $(document).on('scroll', '.now-streaming-page .playlist-scrollable,.queue-detail-page .playlist-scrollable', function(e) {
        $('.now-streaming-page .playlist-scrollable .wishlist-popover,.queue-detail-page .playlist-scrollable .wishlist-popover').removeClass('top');
        $('.now-streaming-page .playlist-scrollable .row,.queue-detail-page .playlist-scrollable .row').each(function(e) {
            if ($(this).position().top >= -22 && $(this).position().top <= 130) {
                $(this).find('.wishlist-popover').addClass('top');
            }
        });
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
    /********************** End now streaming/queue detail page ******************/

    /************* Wishlist javascripts ***********************/
    $('.my-wishlist-page .my-wishlist-scrollable').bindMouseWheel();
    $('.my-wishlist-page .my-video-wishlist-scrollable').bindMouseWheel();
    $('.my-wishlist-page .date-filter-button').addClass('active');
    $('.my-wishlist-page .music-filter-button').addClass('active');
    $('.my-wishlist-page .my-wishlist-scrollable .wishlist-popover').slice(0, 3).addClass('top');
    $('.songdelete').click(function(e) {
        e.preventDefault();
        var parent = $(this).parent();
        $.ajax({
            type: 'post',
            url: webroot + 'homes/removeWishlistSong/',
            data: 'ajax=1&delete=' + parent.attr('id').replace('wishlistsong-', ''),
            beforeSend: function() {
                parent.animate({'backgroundColor': '#fb6c6c'}, 600);
            },
            success: function(data) {
                parent.slideUp(600, function() {
                    parent.remove();
                });
            }
        });
    });
    $('.videodelete').click(function(e) {
        e.preventDefault();
        var parent = $(this).parent();
        $.ajax({
            type: 'post',
            url: webroot + 'homes/removeWishlistVideo/',
            data: 'ajax=1&delete=' + parent.attr('id').replace('wishlistvideo-', ''),
            beforeSend: function() {
                parent.animate({'backgroundColor': '#fb6c6c'}, 600);
            },
            success: function() {
                //alert(1);
                parent.slideUp(600, function() {
                    parent.remove();
                });
            }
        });
    });
    $('.video-filter-button').click(function() {
        $(this).addClass('active');
        $('.music-filter-button').removeClass('active');
        $('.my-wishlist-shadow-container').hide();
        $('.my-video-wishlist-shadow-container').show();
    });
    $('.music-filter-button').click(function() {
        $(this).addClass('active');
        $('.video-filter-button').removeClass('active');
        $('.my-video-wishlist-shadow-container').hide();
        $('.my-wishlist-shadow-container').show();
    });
    $('.my-wishlist-page .my-wishlist-filter-container div.filter').on('click', function(e) {
        e.preventDefault();
        if ($(this).hasClass('date-filter-button')) {
            $('#sortForm #sort').val('date');
        } else if ($(this).hasClass('song-filter-button')) {
            $('#sortForm #sort').val('song');
        } else if ($(this).hasClass('artist-filter-button')) {
            $('#sortForm #sort').val('artist');
        } else if ($(this).hasClass('album-filter-button')) {
            $('#sortForm #sort').val('album');
        }
        if ($(this).hasClass('active')) {
            if ($(this).hasClass('toggled')) {
                $(this).removeClass('toggled');
                $('#sortForm #sortOrder').val('asc');
            } else {
                $(this).addClass('toggled');
                $('#sortForm #sortOrder').val('desc');
            }
        } else {
            $('.my-wishlist-page .my-wishlist-filter-container div.filter').removeClass('active');
            $(this).addClass('active');
            $('#sortForm #sortOrder').val('asc');
        }
    });
    $('.my-wishlist-page .my-wishlist-filter-container div.tab').on('click', function(e) {
        e.preventDefault();
        if ($(this).hasClass('active')) {
            if ($(this).hasClass('toggled')) {
                $(this).removeClass('toggled');
            } else {
                $(this).addClass('toggled');
            }
        } else {
            $('.my-wishlist-page .my-wishlist-filter-container div.tab').removeClass('active');
            $(this).addClass('active');
        }
    });
    $('.my-wishlist-page .my-wishlist-scrollable').bind('mousewheel', function(e) {
        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);
        return false;
    });
    $('.my-wishlist-page .my-wishlist-scrollable').on('scroll', function(e) {
        $('.my-wishlist-page .my-wishlist-scrollable .wishlist-popover').removeClass('top');
        $('.my-wishlist-page .my-wishlist-scrollable .row').each(function(e) {

            if ($(this).position().top >= -22 && $(this).position().top <= 110) {




                $(this).find('.wishlist-popover').addClass('top');



            }

        });
    });
    $('.my-wishlist-page .my-video-wishlist-scrollable').bind('mousewheel', function(e) {
        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);
        return false;
    });
    $('.my-wishlist-page .add-to-wishlist-button').on('click', function(e) {
        e.preventDefault();
        $(this).siblings('.wishlist-popover').addClass('active');
    });
    $('.my-wishlist-page .my-video-wishlist-scrollable .row').on('mouseleave', function() {
        $(this).find('.date').removeClass('hovered');
        $(this).find('.album-title').removeClass('hovered');
        $(this).find('.artist-name').removeClass('hovered');
        $(this).find('.time').removeClass('hovered');
        $(this).find('.song-title').removeClass('hovered');
        $(this).find('.preview').removeClass('hovered');
        $(this).find('.add-to-wishlist-button').removeClass('hovered');
    });
    $('.my-wishlist-page .my-video-wishlist-scrollable .row').on('mouseenter', function() {
        $(this).find('.date').addClass('hovered');
        $(this).find('.album-title').addClass('hovered');
        $(this).find('.artist-name').addClass('hovered');
        $(this).find('.time').addClass('hovered');
        $(this).find('.song-title').addClass('hovered');
        $(this).find('.preview').addClass('hovered');
        $(this).find('.add-to-wishlist-button').addClass('hovered');
    });
    $('.my-wishlist-page .my-wishlist-scrollable .row .preview').on('mouseenter', function(e) {
        $(this).removeClass('hovered').addClass('blue-bkg');
    });
    $('.my-wishlist-page .my-wishlist-scrollable .row .preview').on('mouseleave', function() {
        $(this).removeClass('blue-bkg').addClass('hovered');
    });
    $('.recent-downloads-filter-container .date-filter-button , .recent-downloads-filter-container .song-filter-button , .recent-downloads-filter-container .artist-filter-button ,.recent-downloads-filter-container .album-filter-button , .recent-downloads-filter-container .download-button').off('click');
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
    $(document).on('click', '.my-wishlist-page .add-to-wishlist-button', function(e) {
        e.preventDefault();
        $(this).siblings('.wishlist-popover').addClass('active');
    });
    $(document).on('mouseleave', '.my-wishlist-page .wishlist-popover', function(e) {
        $(this).removeClass('active');
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
    /************* Wishlist javascripts ***********************/

    /*************** search results page *******************/
    $('.search-page .tracklist-scrollable').bindMouseWheel();
    $('.search-page .advanced-artists-scrollable').bindMouseWheel();
    $('.search-page .advanced-composers-scrollable').bindMouseWheel();
    $('.search-page .advanced-genres-scrollable').bindMouseWheel();
    $('.search-page .advanced-labels-scrollable').bindMouseWheel();
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
    $('.search-page .advanced-search #submit').on('mousedown', function(e) {
        $(this).addClass('clicked');
    });
    $('.search-page .advanced-search #submit').on('mouseup', function(e) {
        $(this).removeClass('clicked');
    });
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
    $(document).on('mouseleave', '.search-page .tracklist-container .tracklist', function() {
        $(this).find('.wishlist-popover').removeClass('active');
    });
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
    $(document).on('click', '.pagination a', function(e) {
        var target = $(this).attr('href');
    });
    /*************** search results page *******************/

    /*************** downloads page ****************/
    $('.recent-downloads-page .recent-downloads-scrollable').bindMouseWheel();
    $('.recent-downloads-page .recent-video-downloads-scrollable').bindMouseWheel();
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
    $(document).on('click', '.recent-downloads-page .add-to-wishlist-button', function(e) {
        e.preventDefault();
        $(this).siblings('.wishlist-popover').addClass('active');
    });
    /*************** downloads page ****************/

    /********************** history page ********************/
    $('.history-page .history-scrollable').bindMouseWheel();
    $('.history-page .history-scrollable .wishlist-popover').slice(0, 3).addClass('top');
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
    $(document).on('click', '.history-page .add-to-wishlist-button', function(e) {
        e.preventDefault();
        $(this).siblings('.wishlist-popover').addClass('active');
    });
    $(document).on('mouseleave', '.history-page .wishlist-popover', function(e) {
        $(this).removeClass('active');
    });
    $(document).on('scroll', '.history-page .history-scrollable', function(e) {
        $('.history-page .history-scrollable .wishlist-popover').removeClass('top');
        $('.history-page .history-scrollable .row').each(function(e) {
            if ($(this).position().top >= -22 && $(this).position().top <= 110) {
                $(this).find('.wishlist-popover').addClass('top');
            }
        });
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
    /********************** history page ********************/

    /***************** artist page **********************/
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
    /***************** artist page **********************/

    /************* albums page ***************/
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
    /************* albums page ***************/

    /********************* FAQ page **********************/
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
    /********************* FAQ page **********************/

    /********* manage notification code js **********/
    $('#UserSendNewsLetterCheck').click(function() {
        //alert($('#UserSendNewsLetterCheck:checked').val());

        var isChecked = $('#UserSendNewsLetterCheck:checked').is(':checked');

        if (isChecked) {
            $('#show_newsletterboxField').hide();
            $('#UserSendNewsLetterCheck').attr('value', '1');
        } else {
            $('#show_newsletterboxField').show();
            $('#UserSendNewsLetterCheck').attr('value', '0');
        }

    });
});

//currently used in website
function changeLang(type)
{ //alert("http://jeffersonlibrary.freegaldev.com/"+webroot+"homes/language");
    var language = type;
    var data = "lang=" + language;
    $.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/language", // URL to request
        data: data, // post data
        success: function(response) { //alert("in js"+webroot);
            var msg = response.substring(0, 5);
            if (msg === 'error')
            {
                alert("There was an error while saving your request.");
                location.reload();
                return false;
            }
            else
            {
                location.reload();
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
}
function userDownloadAll(prodId)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('downloading_' + prodId).style.display = 'block';
    document.getElementById('song_' + prodId).style.display = 'none';
    document.getElementById('download_loader_' + prodId).style.display = 'block';
    $('#form' + prodId).submit();
    setTimeout("location.reload(true)", 7000);
}
function addQtip(prodId)
{
    $('#song_' + prodId).qtip({
        content: "You have already downloaded this song. Get it from your recent downloads.",
        position: {
            corner: {
                target: 'topLeft',
                tooltip: 'bottomRight'
            }
        },
        style: {
            name: 'cream',
            padding: '2px 5px',
            width: {
                max: 350,
                min: 0
            },
            border: {
                width: 1,
                radius: 8,
                color: '#FAF7AA'
            },
            tip: true
        }
    });
}
function addQtip_top(prodId)
{
    $('#song_' + prodId).qtip({
        content: "You have already downloaded this song. Get it from your recent downloads.",
        position: {
            corner: {
                target: 'topRight',
                tooltip: 'bottomLeft'
            }
        },
        style: {
            name: 'cream',
            padding: '2px 5px',
            width: {
                max: 350,
                min: 0
            },
            border: {
                width: 1,
                radius: 8,
                color: '#FAF7AA'
            },
            tip: true
        }
    });
}
function addQtip_toptab(prodId)
{
    $('#songtab_' + prodId).qtip({
        content: "You have already downloaded this song. Get it from your recent downloads.",
        position: {
            corner: {
                target: 'topRight',
                tooltip: 'bottomLeft'
            }
        },
        style: {
            name: 'cream',
            padding: '2px 5px',
            width: {
                max: 350,
                min: 0
            },
            border: {
                width: 1,
                radius: 8,
                color: '#FAF7AA'
            },
            tip: true
        }
    });
}
function addToWishlist(prodId, providerType)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    //document.getElementById('wishlist_loader_'+prodId).style.display = 'block';
    var data = "prodId=" + prodId + "&provider=" + providerType;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/addToWishlist", // URL to request
        data: data, // post data
        success: function(response) {
            //alert(response);
            var msg = response.substring(0, 5);
            if (msg === 'error')
            {

                document.getElementById('ajaxflashMessage44').innerHTML = 'You can not add more songs to your wishlist.';

                //alert("You can not add more songs to your wishlist.");
                //location.reload();
                return false;
            } else if (msg === 'error1') {

                document.getElementById('wishlist' + prodId).innerHTML = '<a class="add-to-wishlist">Already Added</a>';
            }
            else
            {
                var msg = response.substring(0, 7);
                if (msg === 'Success')
                {
                    $('.beforeClick').show();
                    $('.afterClick').hide();
                    if (languageSet === 'en') {
                        document.getElementById('wishlist' + prodId).innerHTML = '<a class="add-to-wishlist">Added to Wishlist</a>';
                    } else {
                        document.getElementById('wishlist' + prodId).innerHTML = '<a class="add-to-wishlist">Añadido a su Lista Deseos</a>';
                    }
                    //document.getElementById('wishlist_loader_'+prodId).style.display = 'none';
                }
                else
                {
                    document.getElementById('ajaxflashMessage44').innerHTML = 'You have been logged out from the system. Please login again.';
                    //alert("You have been logged out from the system. Please login again.");
                    location.reload();
                    return false;
                }
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function addToWishlistVideo(prodId, providerType)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    //document.getElementById('wishlist_loader_'+prodId).style.display = 'block';

    var data = "prodId=" + prodId + "&provider=" + providerType;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/addToWishlistVideo", // URL to request
        data: data, // post data
        success: function(response) {
            //alert(response);
            var msg = response.substring(0, 5);
            if (msg === 'error')
            {
                document.getElementById('ajaxflashMessage44').innerHTML = 'You can not add more songs to your wishlist.';
                //alert("You can not add more songs to your wishlist.");
                location.reload();
                return false;
            }
            else if (msg === 'error1')
            {
                document.getElementById('video_wishlist' + prodId).innerHTML = '<a class="add-to-wishlist">Already Added</a>';
                return false;
            }


            var msg = response.substring(0, 7);
            if (msg === 'Success')
            {
                $('.beforeClick').show();
                $('.afterClick').hide();

                // alert(languageSet);
                if (languageSet === 'en')
                {
                    document.getElementById('video_wishlist' + prodId).innerHTML = '<a class="add-to-wishlist">Added to Wishlist</a>';
                    return false;
                }
                else
                {
                    document.getElementById('video_wishlist' + prodId).innerHTML = '<a class="add-to-wishlist">Añadido a su Lista Deseos</a>';
                    return false;
                }
                //document.getElementById('wishlist_loader_'+prodId).style.display = 'none';
            }
            else
            {
                document.getElementById('ajaxflashMessage44').innerHTML = 'You have been logged out from the system. Please login again.';
                //alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }

        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            console.log(textStatus);
            return false;
        }
    });
    return false;
}
function wishlistDownloadIE(prodId, id, provider)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('wishlist_loader_' + prodId).style.display = 'block';
    document.getElementById('downloading_' + prodId).style.display = 'block';
    document.getElementById('wishlist_song_' + prodId).style.display = 'none';
    var data = "prodId=" + prodId + "&id=" + id + "&provider=" + provider;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/wishlistDownload", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg === 'error')
            {
                alert("Your download limit has exceeded.");
                //location.reload();
                return false;
            }
            else if (msg === 'suces')
            {
                $('.afterClick').hide();
                $('.beforeClick').show();
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                document.getElementById('wishlist_song_' + prodId).innerHTML = '<a title="You have already downloaded this Song. Get it from your recent downloads" href="/homes/my_history">Downloaded</a>';
                document.getElementById('wishlist_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('wishlist_song_' + prodId).style.display = 'block';
            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function wishlistDownloadIEHome(prodId, id, provider, CdnPath, SaveAsName)
{
    //console.log('wishlistDownloadIE called');
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('wishlist_loader_' + prodId).style.display = 'block';
    document.getElementById('downloading_' + prodId).style.display = 'block';
    document.getElementById('wishlist_song_' + prodId).style.display = 'none';
    var data = "prodId=" + prodId + "&id=" + id + "&provider=" + provider + "&CdnPath=" + CdnPath + "&SaveAsName=" + SaveAsName;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/wishlistDownloadHome", // URL to request
        data: data, // post data
        async: false,
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg === 'error')
            {
                alert("Your download limit has exceeded.");
                location.reload();
                return false;
            }
            else if (msg === 'suces')
            {
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                //document.getElementById('song_download_' + prodId).href = downloadUsedArr[2];
                //window.location = unescape(downloadUsedArr[2]);
                location.href = unescape(downloadUsedArr[2]);

                $('.afterClick').hide();
                $('.beforeClick').show();

                document.getElementById('wishlist_song_' + prodId).innerHTML = '<a title="You have already downloaded this Song. Get it from your recent downloads" href="/homes/my_history">Downloaded</a>';
                document.getElementById('wishlist_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('wishlist_song_' + prodId).style.display = 'block';
                return false;
            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function toDownload(urlToDownload)
{
    window.location.href = urlToDownload;
}
function wishlistVideoDownloadIE(prodId, id, provider)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('vdownloading_' + prodId).style.display = 'block';
    document.getElementById('download_video_' + prodId).style.display = 'none';
    document.getElementById('vdownload_loader_' + prodId).style.display = 'block';
    var data = "prodId=" + prodId + "&id=" + id + "&provider=" + provider;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/wishlistVideoDownload", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg === 'error')
            {
                alert("Your download limit has exceeded.");
                //location.reload();
                return false;
            }
            else if (msg === 'suces')
            {
                $('.afterClick').hide();
                $('.beforeClick').show();
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                document.getElementById('download_video_' + prodId).innerHTML = '<a title="You have already downloaded this Song. Get it from your recent downloads" href="/homes/my_history"><label class="top-10-download-now-button">Downloaded</label></a>';
                document.getElementById('vdownload_loader_' + prodId).style.display = 'none';
                document.getElementById('vdownloading_' + prodId).style.display = 'none';
                document.getElementById('download_video_' + prodId).style.display = 'block';
                return false;
            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function wishlistVideoDownloadIEToken(prodId, id, provider, CdnPath, SaveAsName)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('vdownloading_' + prodId).style.display = 'block';
    document.getElementById('download_video_' + prodId).style.display = 'none';
    document.getElementById('vdownload_loader_' + prodId).style.display = 'block';
    var data = "prodId=" + prodId + "&id=" + id + "&provider=" + provider + "&CdnPath=" + CdnPath + "&SaveAsName=" + SaveAsName;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/wishlistVideoDownloadToken", // URL to request
        data: data, // post data
        async: false,
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg === 'error')
            {
                alert("Your download limit has exceeded.");
                //location.reload();
                return false;
            }
            else if (msg === 'suces')
            {
                var downloadUsedArr = response.split('|');

                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];

                location.href = unescape(downloadUsedArr[2]);
                $('.afterClick').hide();
                $('.beforeClick').show();

                document.getElementById('download_video_' + prodId).innerHTML = '<a title="You have already downloaded this Song. Get it from your recent downloads" href="/homes/my_history"><label class="top-10-download-now-button">Downloaded</label></a>';
                document.getElementById('vdownload_loader_' + prodId).style.display = 'none';
                document.getElementById('vdownloading_' + prodId).style.display = 'none';
                document.getElementById('download_video_' + prodId).style.display = 'block';

                return false;

            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function historyDownload(id, libID, patronID, CdnPath, SaveAsName)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    //document.getElementById('download_loader_'+id).style.display = 'block';
    var data = "libid=" + libID + "&patronid=" + patronID + "&id=" + id + "&CdnPath=" + CdnPath + "&SaveAsName=" + SaveAsName;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/historyDownload", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg === 'error')
            {
                alert("Your have already downloaded this song twice.");
                //location.reload();
                return false;
            }
            else if (msg === 'suces')
            {
                var count = response.substring(0, 1);
                var downloadUsedArr = response.split('|');

                if (count === 2) {
                    if (languageSet === 'en') {
                        document.getElementById('download_song_' + id).innerHTML = 'Limit Met';
                    } else {
                        document.getElementById('download_song_' + id).innerHTML = 'Límite Excedido';
                    }
                }
                location.href = unescape(downloadUsedArr[2]);
                //document.getElementById('download_loader_'+id).style.display = 'none';
                $('.afterClick').hide();
                $('.beforeClick').show();
                return false;
            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function historyDownloadOthers(id, libID, patronID, CdnPath, SaveAsName)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('download_loader_' + id).style.display = 'block';
//    var finalURL = downloadUrl1;
//    finalURL += downloadUrl2;
//    finalURL += downloadUrl3;
    var data = "libid=" + libID + "&patronid=" + patronID + "&id=" + id + "&CdnPath=" + CdnPath + "&SaveAsName=" + SaveAsName;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/historyDownload", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg === 'error')
            {
                alert("Your download limit has exceeded.");
                document.getElementById('download_loader_' + id).style.display = 'none';
                //location.reload();
                return false;
            }
            else if (msg === 'suces')
            {
                var count = response.substring(0, 1);
                var downloadUsedArr = response.split('|');
                if (count === 2) {
                    if (languageSet === 'en') {
                        document.getElementById('download_song_' + id).innerHTML = 'Limit Met';
                    } else {
                        document.getElementById('download_song_' + id).innerHTML = 'Límite Excedido';
                    }
                }
                $('.afterClick').hide();
                $('.beforeClick').show();
                document.getElementById('download_loader_' + id).style.display = 'none';
                location.href = unescape(downloadUsedArr[2]);
                return false;
            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function historyDownloadVideo(id, libID, patronID)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    //document.getElementById('download_loader_'+id).style.display = 'block';
    var data = "libid=" + libID + "&patronid=" + patronID + "&id=" + id;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/historyDownloadVideo", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg === 'error')
            {
                alert("Your have already downloaded this song twice.");
                //location.reload();
                return false;
            }
            else if (msg === 'suces')
            {
                var count = response.substring(0, 1);
                if (count === 2) {
                    if (languageSet === 'en') {
                        document.getElementById('download_song_' + id).innerHTML = 'Limit Met';
                    } else {
                        document.getElementById('download_song_' + id).innerHTML = 'Límite Excedido';
                    }
                }
                //document.getElementById('download_loader_'+id).style.display = 'none';
                $('.afterClick').hide();
                $('.beforeClick').show();
                return false;
            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function historyDownloadVideoOthers(id, libID, patronID, downloadUrl1, downloadUrl2, downloadUrl3)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('download_loader_' + id).style.display = 'block';
    var finalURL = downloadUrl1;
    finalURL += downloadUrl2;
    finalURL += downloadUrl3;
    var data = "libid=" + libID + "&patronid=" + patronID + "&id=" + id;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/historyDownloadVideo", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg === 'error')
            {
                alert("Your download limit has exceeded.");
                document.getElementById('download_loader_' + id).style.display = 'none';
                //location.reload();
                return false;
            }
            else if (msg === 'suces')
            {
                var count = response.substring(0, 1);
                if (count === 2) {
                    if (languageSet === 'en') {
                        document.getElementById('download_song_' + id).innerHTML = 'Limit Met';
                    } else {
                        document.getElementById('download_song_' + id).innerHTML = 'Límite Excedido';
                    }
                }
                $('.afterClick').hide();
                $('.beforeClick').show();
                document.getElementById('download_loader_' + id).style.display = 'none';
                location.href = unescape(finalURL);
                return flase;
            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function wishlistDownloadOthers(prodId, id, downloadUrl1, downloadUrl2, downloadUrl3, provider)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('downloading_' + prodId).style.display = 'block';
    document.getElementById('wishlist_song_' + prodId).style.display = 'none';
    document.getElementById('wishlist_loader_' + prodId).style.display = 'block';
    var finalURL = downloadUrl1;
    finalURL += downloadUrl2;
    finalURL += downloadUrl3;
    var data = "prodId=" + prodId + "&id=" + id + "&provider=" + provider;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/wishlistDownload", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg === 'error')
            {
                alert("Your download limit has exceeded.");
                //location.reload();
                return false;
            }
            else if (msg === 'suces')
            {
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                if (languageSet === 'en') {
                    document.getElementById('wishlist_song_' + prodId).innerHTML = '<a title="You have already downloaded this Song. Get it from your recent downloads" href="/homes/my_history">Downloaded</a>';
                } else {
                    document.getElementById('wishlist_song_' + prodId).innerHTML = '<a href="/homes/my_history">bajaedas</a>';
                }
                document.getElementById('wishlist_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('wishlist_song_' + prodId).style.display = 'block';
                location.href = unescape(finalURL);
                $('.afterClick').hide();
                $('.beforeClick').show();
            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function wishlistDownloadOthersHome(prodId, id, CdnPath, SaveAsName, provider)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('downloading_' + prodId).style.display = 'block';
    document.getElementById('wishlist_song_' + prodId).style.display = 'none';
    document.getElementById('wishlist_loader_' + prodId).style.display = 'block';
//    var finalURL = downloadUrl1;
//    finalURL += downloadUrl2;
//    finalURL += downloadUrl3;
    var data = "prodId=" + prodId + "&id=" + id + "&provider=" + provider + "&CdnPath=" + CdnPath + "&SaveAsName=" + SaveAsName;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/wishlistDownloadHome", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg === 'error')
            {
                alert("Your download limit has exceeded.");
                //location.reload();
                return false;
            }
            else if (msg === 'suces')
            {
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                if (languageSet === 'en') {
                    document.getElementById('wishlist_song_' + prodId).innerHTML = '<a title="You have already downloaded this Song. Get it from your recent downloads" href="/homes/my_history">Downloaded</a>';
                } else {
                    document.getElementById('wishlist_song_' + prodId).innerHTML = '<a href="/homes/my_history">bajaedas</a>';
                }
                document.getElementById('wishlist_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('wishlist_song_' + prodId).style.display = 'block';
                location.href = unescape(downloadUsedArr[2]);
                $('.afterClick').hide();
                $('.beforeClick').show();
            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function wishlistVideoDownloadOthers(prodId, id, downloadUrl1, downloadUrl2, downloadUrl3, provider)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('vdownloading_' + prodId).style.display = 'block';
    document.getElementById('download_video_' + prodId).style.display = 'none';
    document.getElementById('vdownload_loader_' + prodId).style.display = 'block';
    var finalURL = downloadUrl1;
    finalURL += downloadUrl2;
    finalURL += downloadUrl3;
    var data = "prodId=" + prodId + "&id=" + id + "&provider=" + provider;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/wishlistVideoDownload", // URL to request
        data: data, // post data
        async: false,
        success: function(response) {
            //  alert(response);
            var msg = response.substring(0, 5);
            if (msg === 'error')
            {
                alert("Your download limit has exceeded.");
                //location.reload();
                return false;
            }
            else if (msg === 'suces')
            {
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                if (languageSet === 'en') {
                    document.getElementById('download_video_' + prodId).innerHTML = '<a title="You have already downloaded this Video. Get it from your recent downloads" href="/homes/my_history"><label class="top-10-download-now-button">Downloaded</label></a>';
                } else {
                    document.getElementById('download_video_' + prodId).innerHTML = '<a href="/homes/my_history"><label class="top-10-download-now-button">bajaedas</label></a>';
                }
                document.getElementById('vdownload_loader_' + prodId).style.display = 'none';
                document.getElementById('vdownloading_' + prodId).style.display = 'none';
                document.getElementById('download_video_' + prodId).style.display = 'block';
                location.href = unescape(finalURL);
                $('.afterClick').hide();
                $('.beforeClick').show();
                return false;
            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function wishlistVideoDownloadOthersToken(prodId, id, CdnPath, SaveAsName, provider)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('vdownloading_' + prodId).style.display = 'block';
    document.getElementById('download_video_' + prodId).style.display = 'none';
    document.getElementById('vdownload_loader_' + prodId).style.display = 'block';
//    var finalURL = downloadUrl1;
//    finalURL += downloadUrl2;
//    finalURL += downloadUrl3;
    var data = "prodId=" + prodId + "&id=" + id + "&provider=" + provider + "&CdnPath=" + CdnPath + "&SaveAsName=" + SaveAsName;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/wishlistVideoDownloadToken", // URL to request
        data: data, // post data
        success: function(response) {
            //  alert(response);
            var msg = response.substring(0, 5);
            if (msg === 'error')
            {
                alert("Your download limit has exceeded.");
                //location.reload();
                return false;
            }
            else if (msg === 'suces')
            {
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                if (languageSet === 'en') {
                    document.getElementById('download_video_' + prodId).innerHTML = '<a title="You have already downloaded this Video. Get it from your recent downloads" href="/homes/my_history"><label class="top-10-download-now-button">Downloaded</label></a>';
                } else {
                    document.getElementById('download_video_' + prodId).innerHTML = '<a href="/homes/my_history"><label class="top-10-download-now-button">bajaedas</label></a>';
                }
                document.getElementById('vdownload_loader_' + prodId).style.display = 'none';
                document.getElementById('vdownloading_' + prodId).style.display = 'none';
                document.getElementById('download_video_' + prodId).style.display = 'block';
                location.href = unescape(downloadUsedArr[2]);
                $('.afterClick').hide();
                $('.beforeClick').show();
            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function checkPatron(libid, patronid)
{
    var data = "libid=" + libid + "&patronid=" + patronid.replace('+', '_');
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/checkPatron", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 7);
            if (msg === 'success')
            {
                setTimeout(function() {
                    checkPatron(libid, patronid)
                }, 30000);
            }
            else if (response != '')
            {
                //	alert("You have been logged out from the system. Please login again.");
                //	location.reload();
                //	return false;
                setTimeout(function() {
                    checkPatron(libid, patronid)
                }, 30000);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function approvePatron(libid, patronid)
{
    var _loaderDiv = $("#loaderDiv");
    _loaderDiv.show();
    var data = "libid=" + libid + "&patronid=" + patronid;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/approvePatron", // URL to request
        data: data, // post data
        success: function(response) {
            location.reload();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            location.reload();
        }
    });
    return false;
}
function ControlVersion()
{
    var version;
    var axo;
    var e;

    // NOTE : new ActiveXObject(strFoo) throws an exception if strFoo isn't in the registry

    try {
        // version will be set for 7.X or greater players
        axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.7");
        version = axo.GetVariable("$version");
    } catch (e) {
    }

    if (!version)
    {
        try {
            // version will be set for 6.X players only
            axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.6");

            // installed player is some revision of 6.0
            // GetVariable("$version") crashes for versions 6.0.22 through 6.0.29,
            // so we have to be careful. 

            // default to the first public version
            version = "WIN 6,0,21,0";

            // throws if AllowScripAccess does not exist (introduced in 6.0r47)		
            axo.AllowScriptAccess = "always";

            // safe to call for 6.0r47 or greater
            version = axo.GetVariable("$version");

        } catch (e) {
        }
    }

    if (!version)
    {
        try {
            // version will be set for 4.X or 5.X player
            axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.3");
            version = axo.GetVariable("$version");
        } catch (e) {
        }
    }

    if (!version)
    {
        try {
            // version will be set for 3.X player
            axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.3");
            version = "WIN 3,0,18,0";
        } catch (e) {
        }
    }

    if (!version)
    {
        try {
            // version will be set for 2.X player
            axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash");
            version = "WIN 2,0,0,11";
        } catch (e) {
            version = -1;
        }
    }

    return version;
}
// JavaScript helper required to detect Flash Player PlugIn version information
function GetSwfVer()
{
    // NS/Opera version >= 3 check for Flash plugin in plugin array
    var flashVer = -1;

    if (navigator.plugins != null && navigator.plugins.length > 0) {
        if (navigator.plugins["Shockwave Flash 2.0"] || navigator.plugins["Shockwave Flash"]) {
            var swVer2 = navigator.plugins["Shockwave Flash 2.0"] ? " 2.0" : "";
            var flashDescription = navigator.plugins["Shockwave Flash" + swVer2].description;
            var descArray = flashDescription.split(" ");
            var tempArrayMajor = descArray[2].split(".");
            var versionMajor = tempArrayMajor[0];
            var versionMinor = tempArrayMajor[1];
            var versionRevision = descArray[3];
            if (versionRevision === "") {
                versionRevision = descArray[4];
            }
            if (versionRevision[0] === "d") {
                versionRevision = versionRevision.substring(1);
            } else if (versionRevision[0] === "r") {
                versionRevision = versionRevision.substring(1);
                if (versionRevision.indexOf("d") > 0) {
                    versionRevision = versionRevision.substring(0, versionRevision.indexOf("d"));
                }
            }
            var flashVer = versionMajor + "." + versionMinor + "." + versionRevision;
        }
    }
    // MSN/WebTV 2.6 supports Flash 4
    else if (navigator.userAgent.toLowerCase().indexOf("webtv/2.6") != -1)
        flashVer = 4;
    // WebTV 2.5 supports Flash 3
    else if (navigator.userAgent.toLowerCase().indexOf("webtv/2.5") != -1)
        flashVer = 3;
    // older WebTV supports Flash 2
    else if (navigator.userAgent.toLowerCase().indexOf("webtv") != -1)
        flashVer = 2;
    else if (isIE && isWin && !isOpera) {
        flashVer = ControlVersion();
    }
    return flashVer;
}
// When called with reqMajorVer, reqMinorVer, reqRevision returns true if that version or greater is available
function DetectFlashVer(reqMajorVer, reqMinorVer, reqRevision)
{
    versionStr = GetSwfVer();
    if (versionStr === -1) {
        return false;
    } else if (versionStr != 0) {
        if (isIE && isWin && !isOpera) {
            // Given "WIN 2,0,0,11"
            tempArray = versionStr.split(" "); 	// ["WIN", "2,0,0,11"]
            tempString = tempArray[1];			// "2,0,0,11"
            versionArray = tempString.split(",");	// ['2', '0', '0', '11']
        } else {
            versionArray = versionStr.split(".");
        }
        var versionMajor = versionArray[0];
        var versionMinor = versionArray[1];
        var versionRevision = versionArray[2];

        // is the major.revision >= requested major.revision AND the minor version >= requested minor
        if (versionMajor > parseFloat(reqMajorVer)) {
            return true;
        } else if (versionMajor === parseFloat(reqMajorVer)) {
            if (versionMinor > parseFloat(reqMinorVer))
                return true;
            else if (versionMinor === parseFloat(reqMinorVer)) {
                if (versionRevision >= parseFloat(reqRevision))
                    return true;
            }
        }
        return false;
    }
}

function addToAlbumTest(queueID, addTo)
{
    var type = $(addTo).parent().parent().parent().parent().find('input[type="hidden"]').attr('value');
    var ProdID = $(addTo).parent().parent().parent().parent().find('input[type="hidden"]').attr('id');

    if ((typeof type === 'undefined') && (typeof ProdID === 'undefined'))
    {
        type = $(createLinkThis).parent().parent().parent().parent().find('input[type="hidden"]').attr('value');
        ProdID = $(createLinkThis).parent().parent().parent().parent().find('input[type="hidden"]').attr('id');
    }

    $.ajax({
        type: "post",
        data: {'prodID': ProdID, 'type': type, 'QueueID': queueID},
        url: webroot + 'queues/queueListAlbums',
        success: function(response)
        {
            //alert(response);
            addToQueueResponse(response, type);
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
function addToQueueResponse(response, type)
{
    if (response.length === 6) {
        var msg = response.substring(0, 6);
    } else {
        var msg = response.substring(0, 5);
    }

    if (msg === 'error')
    {
        if (document.getElementById('flash-message'))
        {
            document.getElementById('flash-message').innerHTML = '';
            document.getElementById("flash-message").setAttribute("class", "");
        }

        document.getElementById("ajaxflashMessage44").style.display = "block";
        document.getElementById("ajaxflashMessage44").style.background = "red";
        document.getElementById('ajaxflashMessage44').innerHTML = 'There is some problem in adding ' + type + ' to Playlist.';

        return false;
    } else if (msg === 'error1') {

        if (document.getElementById('flash-message'))
        {
            document.getElementById('flash-message').innerHTML = '';
            document.getElementById("flash-message").setAttribute("class", "");
        }

        document.getElementById("ajaxflashMessage44").style.display = "block";
        document.getElementById('ajaxflashMessage44').innerHTML = 'This ' + type + ' is already added to Playlist';
    }
    else if (msg === 'invalid_for_stream')
    {
        if (document.getElementById('flash-message'))
        {
            document.getElementById('flash-message').innerHTML = '';
            document.getElementById("flash-message").setAttribute("class", "");
        }

        document.getElementById("ajaxflashMessage44").style.display = "block";
        document.getElementById('ajaxflashMessage44').innerHTML = 'This ' + type + ' is not allowed for Streaming';
    }
    else
    {
        var msg = response.substring(0, 7);
        if (msg === 'Success')
        {
            if (document.getElementById('flash-message'))
            {
                document.getElementById('flash-message').innerHTML = '';
                document.getElementById("flash-message").setAttribute("class", "");
            }
            document.getElementById("ajaxflashMessage44").style.display = "block";
            document.getElementById('ajaxflashMessage44').innerHTML = 'Successfully added ' + type + ' to Playlist';

        }
        else
        {
            if (document.getElementById('flash-message'))
            {
                document.getElementById('flash-message').innerHTML = '';
                document.getElementById("flash-message").setAttribute("class", "");
            }

            document.getElementById("ajaxflashMessage44").style.display = "block";
            document.getElementById("ajaxflashMessage44").style.background = "red";
            document.getElementById('ajaxflashMessage44').innerHTML = 'There is some problem arised when adding ' + type + ' to Playlist.';
            return false;
        }
    }
}
function addToQueue(songProdId, songProviderType, albumProdId, albumProviderType, queueId)
{
    var data = "songProdId=" + songProdId + "&songProviderType=" + songProviderType + "&albumProdId=" + albumProdId + "&albumProviderType=" + albumProviderType + "&queueId=" + queueId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "queues/addToQueue", // URL to request
        data: data, // post data
        success: function(response) {


            var playlist_list_popup = $('.playlist-options');
            playlist_list_popup.removeClass('active');
            var wishlist_list_popup = $('.wishlist-popover');
            wishlist_list_popup.removeClass('active');



            if (response.length === 6) {
                var msg = response.substring(0, 6);
            } else {
                var msg = response.substring(0, 5);
            }
            if (msg === 'error')
            {
                if (document.getElementById('flash-message'))
                {
                    document.getElementById('flash-message').innerHTML = '';
                    document.getElementById("flash-message").setAttribute("class", "");
                }

                document.getElementById("ajaxflashMessage44").style.display = "block";
                document.getElementById("ajaxflashMessage44").style.background = "red";
                document.getElementById('ajaxflashMessage44').innerHTML = 'There is some problem in adding song to Playlist.';

                return false;
            } else if (msg === 'error1') {

                if (document.getElementById('flash-message'))
                {
                    document.getElementById('flash-message').innerHTML = '';
                    document.getElementById("flash-message").setAttribute("class", "");
                }

                document.getElementById("ajaxflashMessage44").style.display = "block";
                document.getElementById('ajaxflashMessage44').innerHTML = 'This song is already added to Playlist';
            }
            else if (msg === 'invalid_for_stream')
            {
                if (document.getElementById('flash-message'))
                {
                    document.getElementById('flash-message').innerHTML = '';
                    document.getElementById("flash-message").setAttribute("class", "");
                }

                document.getElementById("ajaxflashMessage44").style.display = "block";
                document.getElementById('ajaxflashMessage44').innerHTML = 'This song is not allowed for Streaming';
            }
            else
            {
                var msg = response.substring(0, 7);
                if (msg === 'Success')
                {
                    if (document.getElementById('flash-message'))
                    {
                        document.getElementById('flash-message').innerHTML = '';
                        document.getElementById("flash-message").setAttribute("class", "");
                    }
                    document.getElementById("ajaxflashMessage44").style.display = "block";
                    document.getElementById('ajaxflashMessage44').innerHTML = 'Successfully added song to playlist';

                }
                else
                {
                    if (document.getElementById('flash-message'))
                    {
                        document.getElementById('flash-message').innerHTML = '';
                        document.getElementById("flash-message").setAttribute("class", "");
                    }

                    document.getElementById("ajaxflashMessage44").style.display = "block";
                    document.getElementById("ajaxflashMessage44").style.background = "red";
                    document.getElementById('ajaxflashMessage44').innerHTML = 'There is some problem arised when adding song to playlist.';
                    return false;
                }
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            if (document.getElementById('flash-message'))
            {
                document.getElementById('flash-message').innerHTML = '';
                document.getElementById("flash-message").setAttribute("class", "");
            }
            document.getElementById("ajaxflashMessage44").style.display = "block";
            document.getElementById("ajaxflashMessage44").style.background = "red";
            document.getElementById('ajaxflashMessage44').innerHTML = 'Ajax call for adding song to playlist is unsuccessfull';
        }
    });
    return false;
}
function addAlbumSongsToQueue(albumSongsToBeAdded)
{
    var playlist_list_popup = $('.playlist-options');
    playlist_list_popup.removeClass('active');
    var wishlist_list_popup = $('.wishlist-popover');
    wishlist_list_popup.removeClass('active');

    var data = "albumSongs=" + albumSongsToBeAdded;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "queues/addAlbumSongsToQueue", // URL to request
        data: data, // post data
        success: function(response) {
            if (response.length === 6) {
                var msg = response.substring(0, 6);
            } else {
                var msg = response.substring(0, 5);
            }
            if (msg === 'error')
            {
                if (document.getElementById('flash-message'))
                {
                    document.getElementById('flash-message').innerHTML = '';
                    document.getElementById("flash-message").setAttribute("class", "");
                }

                document.getElementById("ajaxflashMessage44").style.display = "block";
                document.getElementById("ajaxflashMessage44").style.background = "red";
                document.getElementById('ajaxflashMessage44').innerHTML = 'There is some problem in adding Album to Playlist.';

                return false;
            } else if (msg === 'error1') {

                if (document.getElementById('flash-message'))
                {
                    document.getElementById('flash-message').innerHTML = '';
                    document.getElementById("flash-message").setAttribute("class", "");
                }

                document.getElementById("ajaxflashMessage44").style.display = "block";
                document.getElementById('ajaxflashMessage44').innerHTML = 'This Album is already added to playlist';
            }
            else if (msg === 'invalid_for_stream')
            {
                if (document.getElementById('flash-message'))
                {
                    document.getElementById('flash-message').innerHTML = '';
                    document.getElementById("flash-message").setAttribute("class", "");
                }

                document.getElementById("ajaxflashMessage44").style.display = "block";
                document.getElementById('ajaxflashMessage44').innerHTML = 'This Album is not allowed for Streaming';
            }
            else
            {
                var msg = response.substring(0, 7);
                if (msg === 'Success')
                {
                    if (document.getElementById('flash-message'))
                    {
                        document.getElementById('flash-message').innerHTML = '';
                        document.getElementById("flash-message").setAttribute("class", "");
                    }
                    document.getElementById("ajaxflashMessage44").style.display = "block";
                    document.getElementById('ajaxflashMessage44').innerHTML = 'Successfully added Album to Playlist';

                }
                else
                {
                    if (document.getElementById('flash-message'))
                    {
                        document.getElementById('flash-message').innerHTML = '';
                        document.getElementById("flash-message").setAttribute("class", "");
                    }

                    document.getElementById("ajaxflashMessage44").style.display = "block";
                    document.getElementById("ajaxflashMessage44").style.background = "red";
                    document.getElementById('ajaxflashMessage44').innerHTML = 'There is some problem arised when adding Album to Playlist.';
                    return false;
                }
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            if (document.getElementById('flash-message'))
            {
                document.getElementById('flash-message').innerHTML = '';
                document.getElementById("flash-message").setAttribute("class", "");
            }
            document.getElementById("ajaxflashMessage44").style.display = "block";
            document.getElementById("ajaxflashMessage44").style.background = "red";
            document.getElementById('ajaxflashMessage44').innerHTML = 'Ajax call for adding Album to playlist is unsuccessfull';
        }
    });
    return false;
}
function removeSong(pdId, divId)
{
    var data = "songId=" + pdId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "queuelistdetails/removeSongFromQueue", // URL to request
        data: data, // post data
        success: function(response) {


            var playlist_list_popup = $('.playlist-options');
            playlist_list_popup.removeClass('active');
            var wishlist_list_popup = $('.wishlist-popover');
            wishlist_list_popup.removeClass('active');



            if (response.length === 6) {
                var msg = response.substring(0, 6);
            } else {
                var msg = response.substring(0, 5);
            }
            if (msg === 'error')
            {
                if (document.getElementById('flash-message'))
                {
                    document.getElementById('flash-message').innerHTML = '';
                    document.getElementById("flash-message").setAttribute("class", "");
                }

                document.getElementById("ajaxflashMessage44").style.display = "block";
                document.getElementById("ajaxflashMessage44").style.background = "red";
                document.getElementById('ajaxflashMessage44').innerHTML = 'There is some problem in deleting song from Playlist';

                return false;
            } else if (msg === 'error1') {

                if (document.getElementById('flash-message'))
                {
                    document.getElementById('flash-message').innerHTML = '';
                    document.getElementById("flash-message").setAttribute("class", "");
                }

                document.getElementById("ajaxflashMessage44").style.display = "block";
                document.getElementById('ajaxflashMessage44').innerHTML = 'This song cannot be deleted';
            }
            else if (msg === 'error2')
            {
                if (document.getElementById('flash-message'))
                {
                    document.getElementById('flash-message').innerHTML = '';
                    document.getElementById("flash-message").setAttribute("class", "");
                }

                document.getElementById("ajaxflashMessage44").style.display = "block";
                document.getElementById('ajaxflashMessage44').innerHTML = 'You need to login in for Removing a Song from your Playlist';
            }
            else
            {
                var msg = response.substring(0, 7);
                if (msg === 'Success')
                {
                    if (document.getElementById('flash-message'))
                    {
                        document.getElementById('flash-message').innerHTML = '';
                        document.getElementById("flash-message").setAttribute("class", "");
                    }
                    document.getElementById("ajaxflashMessage44").style.display = "block";
                    $('.clearfix' + divId).remove();
                    document.getElementById('ajaxflashMessage44').innerHTML = 'Successfully removed song from Playlist';

                }
                else
                {
                    if (document.getElementById('flash-message'))
                    {
                        document.getElementById('flash-message').innerHTML = '';
                        document.getElementById("flash-message").setAttribute("class", "");
                    }

                    document.getElementById("ajaxflashMessage44").style.display = "block";
                    document.getElementById("ajaxflashMessage44").style.background = "red";
                    document.getElementById('ajaxflashMessage44').innerHTML = 'There is some problem arised when removing from Playlist.';
                    return false;
                }
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            if (document.getElementById('flash-message'))
            {
                document.getElementById('flash-message').innerHTML = '';
                document.getElementById("flash-message").setAttribute("class", "");
            }
            document.getElementById("ajaxflashMessage44").style.display = "block";
            document.getElementById("ajaxflashMessage44").style.background = "red";
            document.getElementById('ajaxflashMessage44').innerHTML = 'Ajax call for removing song from playlist is unsuccessfull';
        }
    });
    return false;
}
function loadSong(songFile, songTitle, artistName, songLength, prodId, providerType, playlistId)
{
    playlistId = (playlistId === undefined) ? 0 : playlistId;
    var newSong = [
        {
            playlistId: playlistId,
            songId: prodId,
            providerType: providerType,
            label: base64_decode(songTitle),
            songTitle: base64_decode(songTitle),
            artistName: base64_decode(artistName),
            songLength: songLength,
            data: songFile
        }
    ];
    pushSongs(newSong);

}
function loadNationalTopSong(cdnPath, sourceUrl, songTitle, artistName, songLength, prodId, providerType, playlistId)
{
    playlistId = (playlistId === undefined) ? 0 : playlistId;
    cdnPath = base64_decode(cdnPath);
    sourceUrl = base64_decode(sourceUrl);
    songLength = base64_decode(songLength);
    songTitle = base64_decode(songTitle);
    artistName = base64_decode(artistName);

    var data = "cdnPath=" + cdnPath + "&sourceUrl=" + sourceUrl + "&songLength=" + songLength + "&songTitle=" + songTitle + "&artistName=" + artistName + "&providerType=" + providerType + "&playlistId=" + playlistId + "&prodId=" + prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "artists/getSongStreamUrl", // URL to request
        data: data, // post data
        dataType: "json",
        success: function(response) {
            if (response.success) {
                playlist = base64_decode(response.success);
                playlist = JSON.parse(playlist);
                if (playlist.length) {
                    pushSongs(playlist);
                }
            } else if (response.error) {
                console.log(response.error);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            console.log('Ajax call to get album songs has been failed');
        }
    });
    return false;
}
function loadAlbumSong(albumSongs)
{
    playlist = base64_decode(albumSongs);
    playlist = JSON.parse(playlist);
    if (playlist.length) {
        pushSongs(playlist);
    }
}
function loadAlbumData(albumtData)
{
    var data = "albumtData=" + albumtData;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "artists/getAlbumData", // URL to request
        data: data, // post data
        dataType: "json",
        success: function(response) {
            if (response.success) {
                playlist = base64_decode(response.success);
                playlist = JSON.parse(playlist);
                if (playlist.length) {
                    pushSongs(playlist);
                }
            } else if (response.error) {
                console.log(response.error);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            console.log('Ajax call to get album songs has been failed');
        }
    });
    return false;
}
function loadNationalAlbumData(artistText, prodId, providerType)
{
    artistText = base64_decode(artistText);
    providerType = base64_decode(providerType);
    var data = "artistText=" + artistText + "&prodId=" + prodId + "&providerType=" + providerType;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "artists/getNationalAlbumData", // URL to request
        data: data, // post data
        dataType: "json",
        success: function(response) {
            if (response.success) {
                playlist = base64_decode(response.success);
                playlist = JSON.parse(playlist);
                if (playlist.length) {
                    pushSongs(playlist);
                }
            } else if (response.error) {
                console.log(response.error);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            console.log('Ajax call to get album songs has been failed');
        }
    });
    return false;
}
function base64_decode(data)
{
    // http://kevin.vanzonneveld.net
    // +   original by: Tyler Akins (http://rumkin.com)
    // +   improved by: Thunder.m
    // +      input by: Aman Gupta
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Onno Marsman
    // +   bugfixed by: Pellentesque Malesuada
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: base64_decode('S2V2aW4gdmFuIFpvbm5ldmVsZA==');
    // *     returns 1: 'Kevin van Zonneveld'
    // mozilla has this native
    // - but breaks in 2.0.0.12!
    //if (typeof this.window['atob'] ===  'function') {
    //    return atob(data);
    //}
    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
            ac = 0,
            dec = "",
            tmp_arr = [];

    if (!data) {
        return data;
    }

    data += '';

    do { // unpack four hexets into three octets using index points in b64
        h1 = b64.indexOf(data.charAt(i++));
        h2 = b64.indexOf(data.charAt(i++));
        h3 = b64.indexOf(data.charAt(i++));
        h4 = b64.indexOf(data.charAt(i++));

        bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;

        o1 = bits >> 16 & 0xff;
        o2 = bits >> 8 & 0xff;
        o3 = bits & 0xff;

        if (h3 === 64) {
            tmp_arr[ac++] = String.fromCharCode(o1);
        } else if (h4 === 64) {
            tmp_arr[ac++] = String.fromCharCode(o1, o2);
        } else {
            tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
        }
    } while (i < data.length);

    dec = tmp_arr.join('');

    return dec;
}
//load the artist list via ajax    
function load_artist(link, id_serial, genre_name)
{
    $('.album-list-span').html('');
    $('#album_details_container').html('');
    $('#ajax_artistlist_content').html('<span id="mydiv" style="height: 250px;width: 250px;position: relative;background-color: gray;"><img src="' + webroot + 'app/webroot/img/AjaxLoader.gif" style="display: block; left: 50%; margin-left: 147px; margin-top: 85px; position: absolute; top: 50%;"/></span>');
    // var data = "ajax_genre_name="+genre_name;
    var data = "ajax_genre_name=" + genre_name;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: link, // URL to request
        data: data, // post data
        success: function(response) {
            $('#ajax_artistlist_content').html(response);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            // alert('No artist available for this Genre.');
        }
    });
}
//load the albums list via ajax 
function showAllAlbumsList(albumListURL)
{
    $('#album_details_container').html('');
    $('.album-list-span').html('<span id="mydiv" style="height: 250px; width: 250px; position: relative; background-color: gray;">\n\
            <img src="' + webroot + 'app/webroot/img/AjaxLoader.gif" style="display: block; left: 50%; margin-left: 115px; margin-top: 85px; position: absolute; top: 50%;"/></span>');

    var data = "";
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + albumListURL, // URL to request
        data: data, // post data
        success: function(response) {
            $('.album-list-span').html(response);
            $('a[title]').qtip({
                position: {
                    corner: {
                        target: 'topLeft',
                        tooltip: 'bottomRight'
                    }
                },
                style: {
                    color: '#444',
                    fontSize: 12,
                    border: {
                        color: '#444'
                    },
                    width: {
                        max: 350,
                        min: 0
                    },
                    tip: {
                        corner: 'bottomRight',
                        size: {
                            x: 5,
                            y: 5
                        }
                    }
                }
            });
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            // alert('No album available for this artist.');
        }
    });
}
//load the albums details via ajax
function showAlbumDetails(albumDetailURL)
{
    $('#album_details_container').html('<span id="mydiv" style="height: 250px;width: 250px;position: relative;background-color: gray;"><img src="' + webroot + 'app/webroot/img/AjaxLoader.gif" style="display: block;left: 50%;margin-left: 398px;margin-top: 3px;position: absolute;top: 50%;"/></span>');
    var data = "";
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + albumDetailURL, // URL to request
        data: data, // post data
        success: function(response) {


            $('#album_details_container').html(response);
            $('#album_details_container').ajaxify();
            $('a[title]').qtip({
                position: {
                    corner: {
                        target: 'topLeft',
                        tooltip: 'bottomRight'
                    }
                },
                style: {
                    color: '#444',
                    fontSize: 12,
                    border: {
                        color: '#444'
                    },
                    width: {
                        max: 350,
                        min: 0
                    },
                    tip: {
                        corner: 'bottomRight',
                        size: {
                            x: 5,
                            y: 5
                        }
                    }
                }
            });
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            // alert('Album detail not available.');
        }
    });
}
function showHideGrid(varType)
{
    // var top_100_grids = $('.top-100-grids');
    var top_100_songs_grid = $('#top-100-songs-grid');
    var top_100_videos_grid = $('#top-100-videos-grid');
    var songsIDVal = $('#songsIDVal');
    var videosIDVal = $('#videosIDVal');

    if (varType === 'songs') {
        videosIDVal.removeClass('active');
        songsIDVal.addClass('active');
        top_100_videos_grid.removeClass('active');
        top_100_songs_grid.addClass('active');
    } else {
        songsIDVal.removeClass('active');
        videosIDVal.addClass('active');
        top_100_songs_grid.removeClass('active');
        top_100_videos_grid.addClass('active');
    }
}
function showHideGridCommingSoon(varType)
{
    //   var top_100_grids = $('.top-100-grids');
    var coming_soon_singles_grid = $('#coming-soon-singles-grid');
    var coming_soon_videos_grid = $('#coming-soon-videos-grid');
    var songsIDValComming = $('#songsIDValComming');
    var videosIDValComming = $('#videosIDValComming');

    if (varType === 'songs') {
        videosIDValComming.removeClass('active');
        songsIDValComming.addClass('active');

        coming_soon_videos_grid.removeClass('active');
        coming_soon_singles_grid.addClass('active');
    } else {
        songsIDValComming.removeClass('active');
        videosIDValComming.addClass('active');

        coming_soon_singles_grid.removeClass('active');
        coming_soon_videos_grid.addClass('active');
    }
}
function documentHtml(html)
{
    // Prepare
    var result = String(html)
            .replace(/<\!DOCTYPE[^>]*>/i, '')
            .replace(/<(html|head|body|title|meta|script)([\s\>])/gi, '<div class="document-$1"$2')
            .replace(/<\/(html|head|body|title|meta|script)\>/gi, '</div>')
            ;

    // Return
    return $.trim(result);
}
function callSearchAjax()
{
    $("#headerSearchSubmit").click(function(event) {
        ajaxSearch();
    });
}
function resetNavigation()
{
    var sidebar_anchor = $('.sidebar-anchor');
    sidebar_anchor.removeClass('active');
    var sidebar_sub_nav_07 = $('.sidebar-sub-nav');
    sidebar_sub_nav_07.removeClass('active');
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
function ajaxSearch()
{
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
        data: {'q': q, 'type': type, 'layout': 'ajax'},
        success: function(response) {
            var className = $('body').attr('class');
            $('body').removeClass(className);
            $('body').addClass('page-search-index');

            $(document).find('.content').find('section').remove();
            $(document).find('.content').append(response);

            $.getScript(webroot + 'css/styles.css');
            $.getScript(webroot + 'css/freegal_styles.css');

            $.getScript(webroot + 'js/site.js');
            $.getScript(webroot + 'js/freegal.js');

            $('.loader').fadeOut(500);
            $('.content').remove('.loader');

            $(document).find('.content').ajaxify().css('opacity', 100).show();
            $('div.ac_results').hide();
            $('#search-text').val('');
            callSearchAjax();
        },
        error: function(response) {

        }
    });

    return false;
}
// code to ajaxify MyAccount form start
function callMyAccountAjax()
{
    $("#btnMyAccount").click(function(event) {
        ajaxMyAccount();
    });
}
function ajaxMyAccount()
{
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

            $.getScript(webroot + 'js/freegal.js');
            $.getScript(webroot + 'js/site.js');

            $.getScript(webroot + 'js/audioPlayer.js');
            $.getScript(webroot + 'js/recent-downloads.js');
            $.getScript(webroot + 'js/search-results.js');


            $('.loader').fadeOut(500);

            $('.content').remove('.loader');
            callMyAccountAjax();
        },
        failure: function() {
            alert('Problem fetching data');
        }
    });
    return false;

}
// code to ajaxify Notification form start
function callNotificationAjax()
{
    $("#btnNotification").click(function(event) {
        ajaxNotification();
    });
}
// code to ajaxify Notification form end
function ajaxNotification()
{
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

    if ($('#UserSendNewsLetterCheck').val()) {
        USendNewsLetterCheck = $('#UserSendNewsLetterCheck').val();
    }
    if ($('#UserNewsletterEmail').val()) {
        UNewsletterEmail = $('#UserNewsletterEmail').val();
    }

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

            $.getScript(webroot + 'js/freegal.js');
            $.getScript(webroot + 'js/site.js');

            $.getScript(webroot + 'js/audioPlayer.js');
            $.getScript(webroot + 'js/recent-downloads.js');
            $.getScript(webroot + 'js/search-results.js');


            $('.loader').fadeOut(500);

            $('.content').remove('.loader');
            callNotificationAjax();
        },
        failure: function() {
            alert('Problem fetching data');
        }
    });
    return false;
//    });
}
//funciton to get Queue List on fly
function getQueueList(ProdID, type, addTo)
{
    $.ajax({
        type: "post",
        data: {'prodID': ProdID, 'type': type},
        url: webroot + 'queues/queueListAlbums',
        success: function(response) {
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
function computeVisibleHeight($t)
{
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
function userDownloadIE(prodId)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('download_loader_' + prodId).style.display = 'block';
    document.getElementById('downloading_' + prodId).style.display = 'block';
    document.getElementById('song_' + prodId).style.display = 'none';
    var data = "prodId=" + prodId;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/userDownload", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg === 'error')
            {
                alert("Your download limit has exceeded.");
                //location.reload();
                return false;
            }
            else if (msg === 'incld')
            {
                alert("You have already downloaded this song.Get it from your recent downloads");
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('song_' + prodId).innerHTML = '';
                if (languageSet === 'en') {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('song_' + prodId).style.display = 'block';
                addQtip_top(prodId);
                $('.afterClick').hide();
                $('.beforeClick').show();
                return false;
            }
            else if (msg === 'suces')
            {
                $('.afterClick').hide();
                $('.beforeClick').show();
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('song_' + prodId).innerHTML = '';
                if (languageSet === 'en') {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('song_' + prodId).style.display = 'block';
                addQtip(prodId);
            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function userDownloadOthers(prodId, downloadUrl1, downloadUrl2, downloadUrl3)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('downloading_' + prodId).style.display = 'block';
    document.getElementById('song_' + prodId).style.display = 'none';
    document.getElementById('download_loader_' + prodId).style.display = 'block';
    var finalURL = downloadUrl1;
    finalURL += downloadUrl2;
    finalURL += downloadUrl3;
    var data = "prodId=" + prodId;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/userDownload", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg === 'error')
            {
                alert("Your download limit has exceeded.");
                //location.reload();
                return false;
            }
            else if (msg === 'incld')
            {
                alert("You have already downloaded this song.Get it from your recent downloads");
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('song_' + prodId).innerHTML = '';
                if (languageSet === 'en') {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('song_' + prodId).style.display = 'block';
                addQtip_top(prodId);
                $('.afterClick').hide();
                $('.beforeClick').show();
                return false;
            }
            else if (msg === 'suces')
            {
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('song_' + prodId).innerHTML = '';
                if (languageSet === 'en') {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('song_' + prodId).style.display = 'block';
                addQtip(prodId);
                location.href = unescape(finalURL);
                $('.afterClick').hide();
                $('.beforeClick').show();
            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function userDownloadIE_top(prodId)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('download_loader_' + prodId).style.display = 'block';
    document.getElementById('downloading_' + prodId).style.display = 'block';
    document.getElementById('song_' + prodId).style.display = 'none';
    var data = "prodId=" + prodId;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/userDownload", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg === 'error')
            {
                alert("Your download limit has exceeded.");
                //location.reload();
                return false;
            }
            else if (msg === 'incld')
            {
                alert("You have already downloaded this song.Get it from your recent downloads");
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('song_' + prodId).innerHTML = '';
                if (languageSet === 'en') {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('song_' + prodId).style.display = 'block';
                addQtip_top(prodId);
                $('.afterClick').hide();
                $('.beforeClick').show();
                return false;
            }
            else if (msg === 'suces')
            {
                $('.afterClick').hide();
                $('.beforeClick').show();
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('song_' + prodId).innerHTML = '';
                if (languageSet === 'en') {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('song_' + prodId).style.display = 'block';
                addQtip_top(prodId);

            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function userDownloadOthers_top(prodId, downloadUrl1, downloadUrl2, downloadUrl3)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('downloading_' + prodId).style.display = 'block';
    document.getElementById('song_' + prodId).style.display = 'none';
    document.getElementById('download_loader_' + prodId).style.display = 'block';
    var finalURL = downloadUrl1;
    finalURL += downloadUrl2;
    finalURL += downloadUrl3;
    var data = "prodId=" + prodId;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/userDownload", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg === 'error')
            {
                alert("Your download limit has exceeded.");
                //location.reload();
                return false;
            }
            else if (msg === 'incld')
            {
                alert("You have already downloaded this song.Get it from your recent downloads");
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('song_' + prodId).innerHTML = '';
                if (languageSet === 'en') {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('song_' + prodId).style.display = 'block';
                addQtip_top(prodId);
                $('.afterClick').hide();
                $('.beforeClick').show();
                return false;
            }
            else if (msg === 'suces')
            {
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('song_' + prodId).innerHTML = '';
                if (languageSet === 'en') {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('song_' + prodId).style.display = 'block';
                addQtip_top(prodId);
                location.href = unescape(finalURL);
                $('.afterClick').hide();
                $('.beforeClick').show();
            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function userDownloadIE_toptab(prodId)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('download_loader_' + prodId).style.display = 'block';
    document.getElementById('downloading_' + prodId).style.display = 'block';
    document.getElementById('songtab_' + prodId).style.display = 'none';
    var data = "prodId=" + prodId;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/userDownload", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg === 'error')
            {
                alert("Your download limit has exceeded.");
                //location.reload();
                return false;
            }
            else if (msg === 'incld')
            {
                alert("You have already downloaded this song.Get it from your recent downloads");
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('songtab_' + prodId).innerHTML = '';
                if (languageSet === 'en') {
                    document.getElementById('songtab_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('songtab_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('songtab_' + prodId).style.display = 'block';
                addQtip_toptab(prodId);
                $('.afterClick').hide();
                $('.beforeClick').show();
                return false;
            }
            else if (msg === 'suces')
            {
                $('.afterClick').hide();
                $('.beforeClick').show();
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('songtab_' + prodId).innerHTML = '';
                if (languageSet === 'en') {
                    document.getElementById('songtab_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('songtab_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('songtab_' + prodId).style.display = 'block';
                addQtip_toptab(prodId);

            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function userDownloadOthers_toptab(prodId, downloadUrl1, downloadUrl2, downloadUrl3)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('downloading_' + prodId).style.display = 'block';
    document.getElementById('songtab_' + prodId).style.display = 'none';
    document.getElementById('download_loader_' + prodId).style.display = 'block';
    var finalURL = downloadUrl1;
    finalURL += downloadUrl2;
    finalURL += downloadUrl3;
    var data = "prodId=" + prodId;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/userDownload", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg === 'error')
            {
                alert("Your download limit has exceeded.");
                //location.reload();
                return false;
            }
            else if (msg === 'incld')
            {
                alert("You have already downloaded this song.Get it from your recent downloads");
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('songtab_' + prodId).innerHTML = '';
                if (languageSet === 'en') {
                    document.getElementById('songtab_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('songtab_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('songtab_' + prodId).style.display = 'block';
                addQtip_toptab(prodId);
                $('.afterClick').hide();
                $('.beforeClick').show();
                return false;
            }
            else if (msg === 'suces')
            {
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                document.getElementById('songtab_' + prodId).innerHTML = '';
                if (languageSet === 'en') {
                    document.getElementById('songtab_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('songtab_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('songtab_' + prodId).style.display = 'block';
                addQtip_toptab(prodId);
                location.href = unescape(finalURL);
                $('.afterClick').hide();
                $('.beforeClick').show();
            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function userDownloadOthers_safari(prodId, downloadUrl1, downloadUrl2, downloadUrl3)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('downloading_' + prodId).style.display = 'block';
    document.getElementById('song_' + prodId).style.display = 'none';
    document.getElementById('download_loader_' + prodId).style.display = 'block';
    var finalURL = downloadUrl1;
    finalURL += downloadUrl2;
    finalURL += downloadUrl3;
    var data = "prodId=" + prodId;
    id = prodId;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/userDownload", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg === 'error')
            {
                alert("Your download limit has exceeded.");
                //location.reload();
                return false;
            }
            else if (msg === 'incld')
            {
                alert("You have already downloaded this song.Get it from your recent downloads");
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                $('.download_links_' + prodId).html('');
                if (languageSet === 'en') {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('song_' + prodId).style.display = 'block';
                addQtip_top(prodId);
                $('.afterClick').hide();
                $('.beforeClick').show();
                return false;
            }
            else if (msg === 'suces')
            {
                $('.afterClick').hide();
                $('.beforeClick').show();
                var downloadUsedArr = response.split('|');
                document.getElementById('downloads_used').innerHTML = downloadUsedArr[1];
                document.getElementById('download_loader_' + prodId).style.display = 'none';
                document.getElementById('downloading_' + prodId).style.display = 'none';
                $('.download_links_' + prodId).html('');
                if (languageSet === 'en') {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>Downloaded</a>";
                } else {
                    document.getElementById('song_' + prodId).innerHTML = "<a href='/homes/my_history'>bajaedas</a>";
                }
                document.getElementById('song_' + prodId).style.display = 'block';
                addQtip(prodId);
                location.href = unescape(finalURL);
            }
            else
            {
                alert("You have been logged out from the system. Please login again.");
                location.reload();
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function addToWishlist_top(prodId, providerType)
{
    $('.beforeClick').hide();
    $('.afterClick').show();
    document.getElementById('wishlist_loader_' + prodId).style.display = 'block';
    var data = "prodId=" + prodId + "&provider=" + providerType;
    jQuery.ajax({
        type: "post", // Request method: post, get
        url: webroot + "homes/addToWishlist", // URL to request
        data: data, // post data
        success: function(response) {
            var msg = response.substring(0, 5);
            if (msg === 'error')
            {
                alert("You can not add more songs to your wishlist.");
                location.reload();
                return false;
            }
            else
            {
                var msg = response.substring(0, 7);
                if (msg === 'Success')
                {
                    $('.beforeClick').show();
                    $('.afterClick').hide();
                    if (languageSet === 'en') {
                        document.getElementById('wishlist_top' + prodId).innerHTML = 'Added to Wishlist';
                    } else {
                        document.getElementById('wishlist_top' + prodId).innerHTML = 'Añadido a su Lista Deseost';
                    }
                    document.getElementById('wishlist_loader_' + prodId).style.display = 'none';
                }
                else
                {
                    alert("You have been logged out from the system. Please login again.");
                    location.reload();
                    return false;
                }
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });
    return false;
}
function videoDownloadAll(prodId)
{
    hidVideoValue = $("#hid_VideoDownloadStatus").val();
    if (hidVideoValue === 1) {
        var r = confirm('A video download will use up 2 of your available downloads. Are you sure you want to continue?');
        if (r === true)
        {
            $('.beforeClick').hide();
            $('.afterClick').show();
            document.getElementById('downloading_' + prodId).style.display = 'block';
            document.getElementById('song_' + prodId).style.display = 'none';
            document.getElementById('download_loader_' + prodId).style.display = 'block';
            $('#form' + prodId).submit();
            setTimeout("location.reload(true)", 7000);
        }
        else
        {
            return;
        }
    }
    else
    {
        alert('Sorry, you do not have enough credits to download a video.');
    }
}
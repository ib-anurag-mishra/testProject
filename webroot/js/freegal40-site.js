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




$(document).ready(function() {


    var $music_search_results = $('.master-music-search-results');
    var $preview = $('.preview');
    var $most_popular_sub_nav = $('.most-popular-sub-nav');
    




   $('.wishlist-popover').on('mouseleave', '.playlist-options', function() {
        $('.playlist-options').removeClass('active');
    });





    $('.add-to-playlist').on('mouseenter', function() {
    
        $('.playlist-options').addClass('active');

    });

   



    //album-page js
    $album_page_album_detail_container = $('.albums-page').find('.album-detail-container');
    $album_page_album_detail_container_tracklist_container = $album_page_album_detail_container.find('.tracklist-container');

    $album_page_album_detail_container.on('mouseenter', '.album-cover-image', function() {
        $(this).find('.album-preview').css({opacity: 1});
        $(this).find('.add-to-playlist-button').css({opacity: 1});
    });

    $album_page_album_detail_container.on('mouseleave', '.album-cover-image', function() {
        $(this).find('.album-preview').css({opacity: 0});
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.wishlist-popover').removeClass('active');
    });

    $album_page_album_detail_container_tracklist_container.on('mouseleave', '.tracklist', function() {
        $(this).find('.wishlist-popover').removeClass('active');
    });   

    $('.albums-page').find('.album-detail-container').find('ul').on('mouseleave',function(){
        $(this).removeClass('active');

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






    $('.playlist-options').bindMouseWheel();

    $(document).on('mousewheel', '.playlist-options', function(e) {

        $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);

        return false;
    });



    $preview.on('mousedown', function(e) {
        e.preventDefault();

        $(this).addClass('active');
    });
    $preview.on('mouseup', function(e) {
        e.preventDefault();

        $(this).removeClass('active');
    });

    $video_thumbnail_container = $('.video-thumbnail-container');

    $video_thumbnail_container.on('mouseenter', function() {

        $(this).find('.add-to-playlist-button').css({opacity: 1});
        $(this).find('.featured-video-download-now-button').css({opacity: 1});
        $(this).find('.preview').css({opacity: 1});

    });

    $video_thumbnail_container.on('mouseleave', function() {
        $(this).find('.add-to-playlist-button').css({opacity: 0});
        $(this).find('.featured-video-download-now-button').css({opacity: 0});
        $(this).find('.preview').css({opacity: 0});
        $(this).find('.wishlist-popover').removeClass('active');
    });




    $('.library-list-scrollable').bindMouseWheel();



    $(document).on('hover', '.wishlist-popover > a', function(e) {

        e.preventDefault();

        if ($('.playlist-options').hasClass('active')) {

            $('.playlist-options').removeClass('active');
        }
    });


    $(document).on('mouseleave', '.account-options-menu', function() {

        $('.account-options-menu').removeClass('active');
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



    $(document).on('click', '.add-to-playlist-button', function(e) {
        e.preventDefault();

        var queuelist = $(document).find('.playlist-options-test').html();
        var oldList = $(this).next('.wishlist-popover').find('.playlist-options');
        oldList.remove();

        $(this).next('.wishlist-popover').append(queuelist);

        $('.wishlist-popover').removeClass('active');

        if ($(this).next('.wishlist-popover').hasClass('active')) {
            $(this).next('.wishlist-popover').removeClass('active');
            $(this).find('.add-to-playlist-button').css({opacity: 0.5});
        } else {

            $(this).next('.wishlist-popover').addClass('active');
        }

        return false;
    });


    $(document).on('click', '.albums-page .album-cover-image .add-to-playlist-button', function(e) {
        e.preventDefault();

        // var queuelist = $(document).find('.playlist-options-test').html();
        // console.log(queuelist);
        // var oldList = $(this).next('.wishlist-popover').find('.playlist-options');

        // oldList.remove();

        // $(this).next('.wishlist-popover').append(queuelist);


        // $('.wishlist-popover').removeClass('active');


        // if ($(this).next('.wishlist-popover').hasClass('active')) {
        //     $(this).next('.wishlist-popover').removeClass('active');
        //     $(this).find('.add-to-playlist-button').css({opacity: 0.5});
        // } else {

        //     $(this).next('.wishlist-popover').addClass('active');
        //     $(this).next('.wishlist-popover').children('.playlist-options').addClass('active');

        // }
 

        var oldList = $(this).siblings('ul');

        oldList.empty();
        var queuelist = $(document).find('.playlist-options-test').find('ul').html();
        console.log(queuelist);
        // $(this).next('ul').append(queuelist).addClass('active');

        $(this).next('ul').append(queuelist).addClass('active');        

       

        return false;
    });    



    /* clickoffs */
    $(document).mouseup(function(e) {

        var container = $('.wishlist-popover');
        var container2 = $('.mejs-playlist.mejs-layer');
        var container3 = $music_search_results;
        var container5 = $('.playlist-options');
        var container6 = $most_popular_sub_nav;
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

    var $site_nav_most_popular_anchor = $('.site-nav').find('.most-popular').find('a');
    var $site_nav_regular = $('.site-nav').find('.regular');
    
    $site_nav_most_popular_anchor.on('mouseenter', function(e) {
        e.preventDefault();

        $most_popular_sub_nav.addClass('active');
    });

    $most_popular_sub_nav.on('mouseleave', function() {
        $most_popular_sub_nav.removeClass('active');

    });

    $site_nav_regular.on('mouseenter', function() {
        $most_popular_sub_nav.removeClass('active');

    });



    /* albums page */

    $(document).on('click', '.albums-page .tracklist .preview', function() {



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



    var totalASLiWidth = 0;
    $('.artist-page .album-scrollable').children('ul').children('li').each(function() {
        totalASLiWidth = totalASLiWidth + $(this).outerWidth(true);

    });

    $('.artist-page .album-scrollable').children('ul').css({width: totalASLiWidth + 5});

    var totalVSLiWidth = 0;

    $('.artist-page .videos-scrollable').children('ul').children('li').each(function() {
        totalVSLiWidth = totalVSLiWidth + $(this).outerWidth(true);

    });

    $('.artist-page .videos-scrollable').children('ul').css({width: totalVSLiWidth + 5});


    var totalMVLiWidth = 0;
    $('.individual-videos-page .more-videos-scrollable').children('ul').children('li').each(function() {
        totalMVLiWidth = totalMVLiWidth + $(this).outerWidth(true);

    });

    $('.individual-videos-page .more-videos-scrollable').children('ul').css({width: totalMVLiWidth + 5});

    var totalTVLiWidth = 0;
    $('.individual-videos-page .top-videos-scrollable').children('ul').children('li').each(function() {
        totalTVLiWidth = totalTVLiWidth + $(this).outerWidth(true);

    });

    $('.individual-videos-page .top-videos-scrollable').children('ul').css({width: totalTVLiWidth + 5});          


    
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
    $('.albums-page').find('.album-cover-image').find('ul').bindMouseWheel();

    /* end genres page */

    $(document).on('click', '.tracklist .preview', function(e) {
        e.preventDefault();
    });

    $(document).on('click', '.tracklist .add-to-playlist-button', function(e) {
        e.preventDefault();
        $(this).siblings('.wishlist-popover').addClass('active');
        //getQueueList();
    });

    $(document).on('click', '.genre-list a', function() {
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
    });

    $(document).on('mousedown', '.more-by', function() {

        $(this).css('background', 'url(images/genres/more-by-click.jpg)');

    });

    $(document).on('click', '.album-image a', function() {

        $('.album-image').removeClass('selected');
        $(this).parent('.album-image').addClass('selected');
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

    $(document).on('mouseenter', '.album-cover-image', function() {
        $(this).find('.preview').css('opacity', 100);
        $(this).find('.add-to-playlist-button').css('opacity', 100);

    });

    $(document).on('mouseleave', '.album-cover-image', function() {
        $(this).find('.preview').css('opacity', 0);
        $(this).find('.add-to-playlist-button').css('opacity', 0);

    });

    /* end genres page */



    /* my account page */
    $('.my-account-page input[type="submit"]').on('mousedown', function() {
        $(this).addClass('clicked');
    });

    $('.my-account-page input[type="submit"]').on('mouseup', function() {
        $(this).removeClass('clicked');
    });
    /* end my account page */

    /* my top 10 page */

    $song_scrollable_song_container = $('.songs-scrollable').find('.song-container');

    $song_scrollable_song_container.on('mouseleave', function() {
        $this = $(this);
        $this.find('.add-to-playlist-button').css({opacity: 0});
        $this.find('.top-10-download-now-button').css({opacity: 0});
        $this.find('.top-100-download-now-button').css({opacity: 0});
        $this.find('.album-preview').css({opacity: 0});
        $this.find('.preview').css({opacity: 0});
        $this.find('.wishlist-popover').removeClass('active');
    });

    $song_scrollable_song_container.on('mouseenter', function() {
        $this = $(this);
        $('.album-preview').css({opacity: 0});
        $('.preview').css({opacity: 0});
        $this.find('.add-to-playlist-button').css({opacity: 1});
        $this.find('.top-10-download-now-button').css({opacity: 1});
        $this.find('.top-100-download-now-button').css({opacity: 1});
        $this.find('.album-preview').css({opacity: 1});
        $this.find('.preview').css({opacity: 1});
    });









    $videos_scrollable_video_container = $('.videos-scrollable').find('.video-container');
    $videos_scrollable_video_container.on('mouseenter', function() {
        $this = $(this);
        $this.find('.add-to-playlist-button').css({opacity: 1});
        $this.find('.mylib-top-10-video-download-now-button').css({opacity: 1});
        $this.find('.top-100-download-now-button').css({opacity:1});

    });

    $videos_scrollable_video_container.on('mouseleave', function() {
        $this = $(this);
        $this.find('.add-to-playlist-button').css({opacity: 0});
        $this.find('.mylib-top-10-video-download-now-button').css({opacity: 0});
        $this.find('.top-100-download-now-button').css({opacity:0});
        $this.find('.wishlist-popover').removeClass('active');
    });

    /* end my top 10 page */




    /* my wishlist page */



    $('.my-wishlist-page .my-wishlist-scrollable').bindMouseWheel();
    $('.my-wishlist-page .my-video-wishlist-scrollable').bindMouseWheel();





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

    $(document).on('click', '.my-wishlist-page .my-wishlist-scrollable .row .preview', function() {

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







    /* downloads page */



    $('.recent-downloads-page .recent-downloads-scrollable').bindMouseWheel();
    $('.recent-downloads-page .recent-video-downloads-scrollable').bindMouseWheel();




    $('.recent-downloads-page .recent-downloads-scrollable .row .preview').on('mouseenter', function() {

        $(this).removeClass('hovered').addClass('blue-bkg');

    });

    $('.recent-downloads-page .recent-downloads-scrollable .row .preview').on('mouseleave', function() {

        $(this).removeClass('blue-bkg').addClass('hovered');

    });



    /* end downloads page */


    /* saved queues page */
    $('.saved-queues-page .playlist-filter-container .playlist-filter-button').addClass('active');

    $(document).on('mousedown', '.saved-queues-page .playlist-filter-container .create-playlist-button', function() {
        $(this).addClass('pressed');
    });


    $(document).on('mouseup', '.saved-queues-page .playlist-filter-container .create-playlist-button', function() {
        $(this).removeClass('pressed');
    });


    $(document).on('click', '.saved-queues-page .filter-button', function() {
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

    $(document).on('click', '.saved-queues-page .add-to-playlist-button', function() {

        $(this).siblings('.wishlist-popover').addClass('active');
    });



    $('.saved-queues-page .wishlist-popover').on('mouseleave', function() {

        $(this).removeClass('active');
    });

    /* end saved queues page */












    /* now streaming/queue detail page */
    $(document).on('click', '.gear-icon', function() {
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
            $(this).find('.add-to-playlist-button').css({opacity: 0.5});
        } else {

            $(this).siblings('.wishlist-popover').addClass('active');
        }
    });


    $('.now-streaming-page .now-playing-container .wishlist-popover,.queue-detail-page .now-playing-container .wishlist-popover').on('mouseleave', function() {
        $(this).removeClass('active');

    });


    $('.now-streaming-page .playlist-scrollable .wishlist-popover,.queue-detail-page .playlist-scrollable .wishlist-popover').slice(0, 4).addClass('top');

    $(document).on('scroll', '.now-streaming-page .playlist-scrollable,.queue-detail-page .playlist-scrollable', function() {

        $('.now-streaming-page .playlist-scrollable .wishlist-popover,.queue-detail-page .playlist-scrollable .wishlist-popover').removeClass('top');


        $('.now-streaming-page .playlist-scrollable .row,.queue-detail-page .playlist-scrollable .row').each(function() {
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

    $(document).on('click', '.now-streaming-page .playlist-scrollable .row .preview,.queue-detail-page .playlist-scrollable .row .preview', function() {

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


    /* calculate width for Videos > Featured Videos */
    var totalFVLiWidth = 0;
    $('#featured-video-grid li').each(function(){
        totalFVLiWidth = totalFVLiWidth + $(this).outerWidth(true);

    });
    $('#featured-video-grid ul').width(totalFVLiWidth);

    $('.video-top-genres-grid ul').width(totalTVLiWidth);
    /* calculate width for Videos > Top Videos */
    var totalTVLiWidth = 0;
    $('.video-top-genres-grid li').each(function(){
        totalTVLiWidth = totalTVLiWidth + $(this).outerWidth(true);

    });

    $('.video-top-genres-grid ul').width(totalTVLiWidth);
    
    $hero_image_container = $('.hero-image-container');
    $video_detail_hero_wishlist_btn = $hero_image_container.find('.wishlist-popover');
    $hero_image_container.on('mouseleave',function(){
        $video_detail_hero_wishlist_btn.removeClass('active');

    });



    $('.content').on('click', '.hp-tabs', function(e) {

        //console.log($(this).attr('href') + ' clicked');
        e.preventDefault();

    });

    $('#search-text').keypress(function(e) {
        if(e.which == 13) {
            $('#search-text').blur();   
        }
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









    $(document).on('mouseenter', '.ac_results ul', function() {

        var $this = $(this);

        $this.bind('mousewheel', function(e) {

            $this.scrollTop($this.scrollTop() - e.originalEvent.wheelDeltaY);
            //prevent page fom scrolling
            return false;

        });
    });

    var $playlist_menu = $('.playlist-menu,.top-songs .options-menu .playlist-menu');
    $playlist_menu.bindMouseWheel();
    // $('.top-songs .options-menu .playlist-menu').bindMouseWheel();
    $(document).on('mouseenter', '.top-songs .options-menu .playlist-menu', function() {
        var $this = $(this);
        $this.bind('mousewheel', function(e) {

            $this.scrollTop($this.scrollTop() - e.originalEvent.wheelDeltaY);
            //prevent page fom scrolling
            return false;

        });
    });


     
    $('#LibraryZipcode').focus();

    var $music_note_icon = $('.music-note-icon');
    var $plays_tooltip = $('.plays-tooltip');

    $music_note_icon.on('mouseenter', function() {

        $plays_tooltip.addClass('active');

    });

    $music_note_icon.on('mouseleave', function() {

        $plays_tooltip.removeClass('active');

    });

    var $my_account_menu = $('.my-account-menu');
    var $account_menu_dropdown = $('.account-menu-dropdown');

    $my_account_menu.on('click', function() {
        $account_menu_dropdown.addClass('active');
        return false;
    });

    $my_account_menu.on('mouseleave', function(e) {
        if (e.offsetX < 0 || e.offsetX > $(this).width() || e.offsetY < 0) {
            $account_menu_dropdown.removeClass('active');
        }
    });

    $('.top-single-container').on('mouseleave',function(){
        $(this).find('ul').removeClass('active');        

    });

    $top_single_container_list = $('.top-single-container').find('ul');

    $top_single_container_list.bindMouseWheel();
    $top_single_container_list.on('mouseleave', function() {
        $(this).removeClass('active');

    });    

    

    var $album_cover_container_ul = $('.album-cover-container').find('ul');


    $album_cover_container_ul.bindMouseWheel();
    $album_cover_container_ul.on('mouseleave', function() {
        $(this).removeClass('active');

    });




    var $album_cover_container = $('.album-cover-container');

    $album_cover_container.on('mouseenter',function(){
        $(this).find('.toggleable').addClass('active');

    });

    $album_cover_container.on('mouseleave',function(){
        $(this).find('.toggleable').removeClass('active');

    });    

    var $playlist_menu_icon = $('.playlist-menu-icon');
    $playlist_menu_icon.on('click', function() {
        $(this).siblings('ul').addClass('active');
    });

    var $left_scroll_button_and_wishlist_icon = $('.left-scroll-button,.wishlist-icon');
    $left_scroll_button_and_wishlist_icon.on('mouseenter', function() {


        $album_cover_container_ul.removeClass('active');


    });
    var $top_albums_carousel_ul_li = $('.top-albums-carousel>ul>li');
    $top_albums_carousel_ul_li.on('mouseleave', function() {
        $album_cover_container_ul.removeClass('active');
    });

    /*
    $('.top-songs-filter-icon').on('mouseleave', function(e) {
        if (e.offsetX < 0 || e.offsetX > $(this).width() || e.offsetY < 0) {
            $('.top-songs-filter-menu').removeClass('active');
        }
    });
    */


    var ulPosition;
    var $left_scroll_button = $('.left-scroll-button');

    $left_scroll_button.on('click', function() {
        
        var $siblings_carousel = $(this).siblings('.carousel');
        

        var currentScrollLeft = $siblings_carousel.scrollLeft();
        currentScrollLeft = currentScrollLeft - 654;
        $siblings_carousel.animate({scrollLeft: currentScrollLeft});        

    });

    var $right_scroll_button = $('.right-scroll-button');
    $right_scroll_button.on('click', function() {
        var $siblings_carousel = $(this).siblings('.carousel');

        var currentScrollLeft = $siblings_carousel.scrollLeft();
        currentScrollLeft = currentScrollLeft + 654;
        $siblings_carousel.animate({scrollLeft: currentScrollLeft});






    });


    var $left_scroll_button_ajax = $('.left-scroll-button-ajax');

    $left_scroll_button_ajax.on('click', function() {
        
        var $siblings_carousel = $(this).siblings('.carousel-ajax');
        

        var currentScrollLeft = $siblings_carousel.scrollLeft();
        currentScrollLeft = currentScrollLeft - 654;
        $siblings_carousel.animate({scrollLeft: currentScrollLeft});        

    });

    var $right_scroll_button_ajax = $('.right-scroll-button-ajax');
    $right_scroll_button_ajax.on('click', function() {




        var $siblings_carousel = $(this).siblings('.carousel-ajax');

        var currentScrollLeft = $siblings_carousel.scrollLeft();



        currentScrollLeft = currentScrollLeft + 654;
        $siblings_carousel.animate({scrollLeft: currentScrollLeft});

        /* if there are more than 50 albums (not added in code yet - needs to be added by IB), and the scrollLeft is at the threshold, get more albums */

        if($siblings_carousel.children('ul').width() - currentScrollLeft < 654) {
            
            /* IB - get 50 albums at a time */

            setTimeout(function(){




                var newCarouselWidth = 0;
                nextPage = $('.next_page').val();
                artistText = $('.artist_text').val();
                $('.next_page').remove();
                $('.artist_text').remove();
                if(nextPage) {
                    $('#artist_loader').show();
                    $.ajax({
                        type: "post",
                        url: webroot + 'artists/load_albums/'+artistText+'/'+nextPage,
                        success: function(response) {
                            /* IB - append new album html */
                            if(response){
                                $('#artist_loader').hide();
                                $('.artist-albums').append(response);  
                            } else {
                                $('#artist_loader').hide();
                            }                        
                            /* recalculate ul width */

                            $siblings_carousel.children('ul').children('li').each(function(){

                                newCarouselWidth = newCarouselWidth + $(this).outerWidth(true);

                            });
                            $siblings_carousel.children('ul').css({width:newCarouselWidth});

                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                        }
                    });
                }

            },500);


        }




    });    





    $('.search-results-albums-page .album-detail-container .add-to-playlist').on('click',function(e){

        e.preventDefault();
    });
 

    $('.search-results-songs-page .row .add-to-playlist').on('click',function(e){

        e.preventDefault();
    });



    $account_menu_dropdown.on('mouseleave', function() {

        $(this).removeClass('active');
    });
    var $genre_page_columns = $('.genre-column,.alpha-artist-list-column,.artist-column');
    $genre_page_columns.bindMouseWheel();

    $(document).on('mouseenter', '.artist-column', function() {
        var $this = $(this);

        $this.bind('mousewheel', function(e) {

            $this.scrollTop($this.scrollTop() - e.originalEvent.wheelDeltaY);
            //prevent page fom scrolling
            return false;

        });
    });

    $(document).on('mouseenter', '.genre-column', function() {
        var $this = $(this);
        $this.bind('mousewheel', function(e) {

            $this.scrollTop($this.scrollTop() - e.originalEvent.wheelDeltaY);
            //prevent page fom scrolling
            return false;

        });
    });



    $('.genre-column a').on('click', function(e) {
        // e.preventDefault();
        // $('.genre-column a').removeClass('active');
        // $(this).addClass('active');

    });

    $alpha_artist_list_column_anchor = $('.alpha-artist-list-column').find('a');

    $alpha_artist_list_column_anchor.on('click', function(e) {
        e.preventDefault();
        $alpha_artist_list_column_anchor.removeClass('active');
        $(this).addClass('active');

    });





    var genreScrollAmount;
    var $genre_column = $('.genre-column');
    var $genre_column_ul = $genre_column.find('ul');


    $(document).on('click', '.genre-scroll-up', function() {
        var currentScrollTop = $genre_column.scrollTop();
        var genreListHeight = $genre_column_ul.height();
        var genreColumnHeight = $genre_column.height();

        genreScrollAmount = currentScrollTop + genreColumnHeight;



        $genre_column.animate({
            scrollTop: genreScrollAmount
        });

    });

    

    $(document).on('click', '.genre-scroll-down', function() {

        var currentScrollTop = $genre_column.scrollTop();
        var genreListHeight = $genre_column_ul.height();
        var genreColumnHeight = $genre_column.height();

        genreScrollAmount = currentScrollTop - genreColumnHeight;



        $genre_column.animate({
            scrollTop: genreScrollAmount
        });
    });




    var artistScollAmount;


    $(document).on('click', '.artist-scroll-up', function() {
        var $artist_column = $('.artist-column');
        var $artist_column_ul = $artist_column.find('ul');
        var currentScrollTop = $artist_column.scrollTop();
        var artistListHeight = $artist_column_ul.height();
        var artistColumnHeight = $artist_column.height();

        artistScrollAmount = currentScrollTop + artistColumnHeight;



        $artist_column.animate({
            scrollTop: artistScrollAmount
        });
    });

    $(document).on('click', '.artist-scroll-down', function() {

        var $artist_column = $('.artist-column');
        var $artist_column_ul = $artist_column.find('ul');        

        var currentScrollTop = $artist_column.scrollTop();
        var artistListHeight = $artist_column_ul.height();
        var artistColumnHeight = $artist_column.height();

        artistScrollAmount = currentScrollTop - artistColumnHeight;



        $artist_column.animate({
            scrollTop: artistScrollAmount
        });

    });


    var $sr_albums_prev = $('.sr-albums-prev');
    var $search_results_albums = $('.search-results-albums');

    $sr_albums_prev.on('click', function() {
        var currentScrollLeft = $search_results_albums.scrollLeft();
        currentScrollLeft = currentScrollLeft - 660;
        $search_results_albums.animate({
            scrollLeft: currentScrollLeft
        });


    });

    var $sr_albums_next = $('.sr-albums-next');
    $sr_albums_next.on('click', function() {



        var currentScrollLeft = $search_results_albums.scrollLeft();
        currentScrollLeft = currentScrollLeft + 660;
        $search_results_albums.animate({
            scrollLeft: currentScrollLeft
        });

    });

    var multipleRowsChecked = false;
    var $row_checkbox = $('.row-checkbox');
    var $multi_select_icon = $('.multi-select-icon');

    $('.row-checkbox').on('click', function() {
        var $this = $(this);
        $this.parent('.row').toggleClass('highlighted');
        var c = 0;
        $('.row-checkbox').each(function() {
            if ($this.is(':checked')) {
                c++;
            }
            if (c >= 2) {
                $('.multi-select-icon').addClass('highlighted');
                multipleRowsChecked = true;
            } else {
                $('.multi-select-icon').removeClass('highlighted');
                multipleRowsChecked = false;
            }
        });
    });

    var $add_to_playlist = $('.add-to-playlist');

    $('.add-to-playlist').on('mouseenter', function() {
        $(this).parents('ul').next('.playlist-menu').addClass('active');

    });

    var $options_menu = $('.options-menu');

    $('.options-menu').on('mouseleave', function() {
        var $this = $(this);
        $this.children('.playlist-menu').removeClass('active');
        $this.removeClass('active');
        if (!multipleRowsChecked) {
            $('.multi-select-icon').removeClass('highlighted');
        }
    });

    $('.multi-select-icon').on('click', function() {

        $(this).siblings('.options-menu').addClass('active');
        $('.multi-select-icon').addClass('highlighted');

    });

    $('.multi-select-icon').on('mouseleave', function(e) {
        var $this = $(this);

        if (e.offsetX > $this.width() || e.offsetY < 0) {

            $('.options-menu').removeClass('active');
            $this.removeClass('highlighted');
        }
    });

    var $select_all = $('.select-all');
    $('.select-all').on('click', function(e) {
        e.preventDefault();
        $('.row-checkbox').each(function() {
            $(this).prop('checked', true);
        });
    });

    var $clear_all = $('.clear-all'); 
    $('.clear-all').on('click', function(e) {
        e.preventDefault();
        $('.row-checkbox').each(function() {
            $(this).prop('checked', false);
        });
    });

    
    // var $menu_btn = $('.menu-btn');
    $('.menu-btn').on('click',function() {
        $(this).siblings('.options-menu').addClass('active');
    });

    $('.menu-btn').on('mouseleave', function(e) {

        if (e.offsetX > $(this).width() || e.offsetY < 0) {

            $('.options-menu').removeClass('active');
        }
    });




    $('#bu-close').on('click',function(e){
        e.preventDefault();
        $('.browser-update').addClass('closed');

    });


    $(document).on('mouseenter','.featured-grid-item',function(){

        $(this).find('.featured-grid-menu').addClass('active');
    });

    $(document).on('mouseleave','.featured-grid-item',function(){

        $(this).find('.featured-grid-menu').removeClass('active');
    });


    /* FAQ page */
    var $faq_container = $('.faq-container');
    var $faq_container_question = $faq_container.find('.fq');
    var $faq_container_answer = $faq_container.find('.fa');


    $faq_container_question.on('click',function(){
        $this = $(this);
        if ($this.next('.fa').hasClass('active')) {

            $this.next('.fa').removeClass('active').slideUp(500);

        } else {

            $('.fa').removeClass('active').slideUp(500);

            $this.next('.fa').addClass('active').slideDown(500);
        }


    });





    var paginationcount = 10;

    $(document).find('.top-songs-container .rows-container .row')
    {
        var
                count = $(document).find('.top-songs-container .rows-container > div.row').length,
                num_cols = Math.floor(count / paginationcount),
                container = $(document).find('.top-songs-container .rows-container');

        //dividing the songs list in pagination
        for (var i = 0; i < num_cols; i++)
        {
            var listItems = $(document).find('.top-songs-container .rows-container > div.row').slice(0, paginationcount);
            var newList = $("<div />").append(listItems);
            newList.addClass('page' + (i + 1));
            if (i === 0)
            {
                newList.css('display', 'block');
            }

            container.append(newList);
        }

        //adding the pagination 
        $(document).find('.top-songs-container .pagination-container')
        {
            var pagination_string = '<button class="beginning"></button><button class="prev"></button>';
            for (var i = 1; i <= num_cols; i++)
            {
                pagination_string += "<button class='page-" + i + "' >" + i + "</button>";
            }
            pagination_string += '<button class="next"></button><button class="last"></button>';

            $(document).find('.top-songs-container .pagination-container').append(pagination_string);
        }

    }

    $(document).find('.top-songs-container .pagination-container').on('click', 'button', function()
    {
        var page_class = $(this).attr('class');

        if (!page_class.indexOf('page-'))
        {
            var to_show_page = page_class.replace('-', '');
            $(document).find('.top-songs-container .rows-container div[class*="page"]').css('display', 'none');
            $(document).find('.top-songs-container .rows-container div.' + to_show_page).css('display', 'block');
        }
        else
        {
            if (page_class === 'next')
            {
                var total_length = $(document).find('.top-songs-container .rows-container div[class*="page"]').length;
                $(document).find('.top-songs-container .rows-container div[class*="page"]').each(function() {
                    if ($(this).css('display') === 'block')
                    {
                        to_show_page = 'page' + (parseInt($(this).attr('class').replace('page', '')) + 1);
                        if ((parseInt($(this).attr('class').replace('page', '')) + 1) < total_length + 1)
                        {
                            $(this).css('display', 'none');
                            $(document).find('.top-songs-container .rows-container div.' + to_show_page).css('display', 'block');
                        }
                        return false;
                    }
                });
            }
            else if (page_class === 'last')
            {
                $(document).find('.top-songs-container .rows-container div[class*="page"]').css('display', 'none');
                var last_class = $(document).find('.top-songs-container .rows-container div[class*="page"]').length;
                to_show_page = 'page' + last_class;
                $(document).find('.top-songs-container .rows-container div.' + to_show_page).css('display', 'block');
                return false;
            }
            else if (page_class === 'prev')
            {
                $(document).find('.top-songs-container .rows-container div[class*="page"]').each(function() {
                    if ($(this).css('display') === 'block')
                    {
                        to_show_page = 'page' + (parseInt($(this).attr('class').replace('page', '')) - 1);
                        if ((parseInt($(this).attr('class').replace('page', '')) - 1) > 0)
                        {
                            $(this).css('display', 'none');
                            $(document).find('.top-songs-container .rows-container div.' + to_show_page).css('display', 'block');
                        }
                        return false;
                    }
                });
            }
            else if (page_class === 'beginning')
            {
                $(document).find('.top-songs-container .rows-container div[class*="page"]').css('display', 'none');
                to_show_page = 'page1';
                $(document).find('.top-songs-container .rows-container div.' + to_show_page).css('display', 'block');
                return false;
            }
        }
        return false;
    });


    /* added for my lib top 10 */
    $('.my-top-100-page .album-container .add-to-playlist-button,.my-top-100-page .song-container .add-to-playlist-button').on('click',function(e){
        e.preventDefault();
        
        var oldList = $(this).next('ul');
        oldList.empty();
        var queuelist = $(document).find('.playlist-options-new').find('ul').html();
        $(this).next('ul').append(queuelist).addClass('active');

    });

    $('.my-top-100-page .album-container .playlist-menu-icon,.my-top-100-page .song-container .playlist-menu-icon').next('ul').on('mouseleave',function(){
        $(this).removeClass('active');

    });

    $('.my-top-100-page .album-container .playlist-menu-icon,.my-top-100-page .song-container .playlist-menu-icon').next('ul').bindMouseWheel();

    $('.my-top-100-page .album-shadow-container .album-scrollable ul li .album-container').on('mouseenter',function(){

        $(this).find('.playlist-menu-icon').css({opacity:.5});
        $(this).find('.album-preview').css({opacity:.5});
    });

    $('.my-top-100-page .album-shadow-container .album-scrollable ul li .album-container').on('mouseleave',function(){

        $(this).find('.playlist-menu-icon').css({opacity:.0});
        $(this).find('.album-preview').css({opacity:.0});
    });

    $('.my-top-100-page .album-shadow-container .album-scrollable ul li .album-container .playlist-menu-icon').on('mouseenter',function(){
        $(this).css({opacity:1});

    });

    $('.my-top-100-page .album-shadow-container .album-scrollable ul li .album-container .playlist-menu-icon').on('mouseleave',function(){
        $(this).css({opacity:.5});

    });

    $('.my-top-100-page .album-shadow-container .album-scrollable ul li .album-container .album-preview').on('mouseenter',function(){
        $(this).css({opacity:1});

    });

    $('.my-top-100-page .album-shadow-container .album-scrollable ul li .album-container .album-preview').on('mouseleave',function(){
        $(this).css({opacity:.5});

    });


 
    $('.my-top-100-page .songs-shadow-container .songs-scrollable ul li .song-container').on('mouseenter',function(){
        $this = $(this);
        $this.find('.playlist-menu-icon').css({opacity:.5});
        $this.find('.top-10-download-now-button').css({opacity:.5});
        $this.find('span.top-10-download-now-button').find('.add-to-wishlist').css({opacity:.5});
        $this.find('.album-preview').css({opacity:.5});
        $this.find('.wishlist-icon').css({opacity:.5});
    });

    $('.my-top-100-page .songs-shadow-container .songs-scrollable ul li .song-container').on('mouseleave',function(){
        $this = $(this);
        $this.find('.playlist-menu-icon').css({opacity:.0});
        $this.find('.top-10-download-now-button').css({opacity:0});
        $this.find('span.top-10-download-now-button').find('.add-to-wishlist').css({opacity:.0});
        $this.find('.album-preview').css({opacity:.0});
        $this.find('.wishlist-icon').css({opacity:.0});
    });

    $('.my-top-100-page .songs-shadow-container .songs-scrollable ul li .song-container .playlist-menu-icon').on('mouseenter',function(){
        $(this).css({opacity:1});

    });
    $('.my-top-100-page .songs-shadow-container .songs-scrollable ul li .song-container .playlist-menu-icon').on('mouseleave',function(){
        $(this).css({opacity:.5});

    });

    $('.my-top-100-page .songs-shadow-container .songs-scrollable ul li .song-container .top-10-download-now-button').on('mouseenter',function(){
        $(this).css({opacity:1});

    });
    $('.my-top-100-page .songs-shadow-container .songs-scrollable ul li .song-container .top-10-download-now-button').on('mouseleave',function(){
        $(this).css({opacity:.5});

    });

    $('.my-top-100-page .songs-shadow-container .songs-scrollable ul li .song-container span.top-10-download-now-button .add-to-wishlist').on('mouseenter',function(){
        $(this).css({opacity:1});

    });
    $('.my-top-100-page .songs-shadow-container .songs-scrollable ul li .song-container span.top-10-download-now-button .add-to-wishlist').on('mouseleave',function(){
        $(this).css({opacity:.5});

    });    

    $('.my-top-100-page .songs-shadow-container .songs-scrollable ul li .song-container .wishlist-icon').on('mouseenter',function(){
        $(this).css({opacity:1});

    });
    $('.my-top-100-page .songs-shadow-container .songs-scrollable ul li .song-container .wishlist-icon').on('mouseleave',function(){
        $(this).css({opacity:.5});

    }); 

    $('.my-top-100-page .songs-shadow-container .songs-scrollable ul li .song-container .album-preview').on('mouseenter',function(){
        $(this).css({opacity:1});

    });
    $('.my-top-100-page .songs-shadow-container .songs-scrollable ul li .song-container .album-preview').on('mouseleave',function(){
        $(this).css({opacity:.5});

    });                         

    /* */


    $('.artist-page').find('.playlist-menu-icon').on('click',function(e){
        e.preventDefault();
        
        var oldList = $(this).next('ul');
        oldList.empty();
        var queuelist = $(document).find('.playlist-options-new').find('ul').html();
        $(this).next('ul').append(queuelist).addClass('active');

    });

    $('.artist-page').find('.playlist-menu-icon').next('ul').on('mouseleave',function(){
        $(this).removeClass('active');

    });

    $('.artist-page').find('.album-container').on('mouseenter',function(){
        $this = $(this);
        $this.find('.playlist-menu-icon').css({opacity:.5});
        $this.find('.album-preview').css({opacity:.5});

    });

    $('.artist-page').find('.album-container').on('mouseleave',function(){
        $this = $(this);
        $this.find('.playlist-menu-icon').css({opacity:0});
        $this.find('.album-preview').css({opacity:0});

    });    

    $('.artist-page').find('.album-container').children('.playlist-menu-icon').on('mouseenter',function(){
        $(this).css({opacity:1});

    });

    $('.artist-page').find('.album-container').children('.playlist-menu-icon').on('mouseleave',function(){
        $(this).css({opacity:.5});

    });

    $('.artist-page').find('.album-container').children('.album-preview').on('mouseenter',function(){
        $(this).css({opacity:1});

    });

    $('.artist-page').find('.album-container').children('.album-preview').on('mouseleave',function(){
        $(this).css({opacity:.5});

    });    

    $(document).find('.top-songs .menu-btn ,  .top-single-container .playlist-menu-icon,  .playlist-menu-icon,  .top-songs .multi-select-icon , .album-info .menu-btn , .songs .menu-btn ,  .songs .multi-select-icon , .songs-results-list .menu-btn ,  .songs-results-list .multi-select-icon').on('click', function(e)
    {
        e.preventDefault();


        if ($(this).hasClass('playlist-menu-icon'))
        {           
            var oldList = $(this).next('ul');
            oldList.empty();
            var queuelist = $(document).find('.playlist-options-new').find('ul').html();
            $(this).next('ul').append(queuelist);
        }
        else
        {            
            var queuelist = $(document).find('.playlist-options-new').html();
            var oldList = $(this).next('.options-menu').find('.playlist-menu');
            oldList.remove();

            $(this).next('.options-menu').append(queuelist);
        }
        return false;
    });

    $(document).find('.song-container .wishlist-icon').on('click',function(e){
        e.preventDefault();

        var ProdID = $(this).prev('input[type="hidden"]').attr('id');
        var Provider = $(this).prev('input[type="hidden"]').attr('data-provider');
        var type = $(this).prev('input[type="hidden"]').attr('value');


        $.ajax({
            type: "post",
            data: {'prodID': ProdID, 'provider_type': Provider, 'type': type},
            url: webroot + 'homes/addToWishlistNewHome',
            success: function(response)
            {
               
                
                displayMessage(response);
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                // log the error to the console
                console.log(
                        "The following error occured: " +
                        textStatus, errorThrown);
            }
        });

    });

    $(document).find('.add-all-to-wishlist , .wishlist-icon, .top-songs .add-to-wishlist, .album-info .add-to-wishlist , .songs .add-to-wishlist , .songs-results-list .add-to-wishlist').on('click', function(e)
    {
        if ($(this).hasClass('wishlist-icon'))
        {
            var ProdID = $(this).parent().find('input[type="hidden"]').attr('id');
            var Provider = $(this).parent().find('input[type="hidden"]').attr('data-provider');
            var type = $(this).parent().find('input[type="hidden"]').attr('value');

            $.ajax({
                type: "post",
                data: {'prodID': ProdID, 'provider_type': Provider, 'type': type},
                url: webroot + 'homes/addToWishlistNewHome',
                success: function(response)
                {
                   
                    
                    displayMessage(response);
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    // log the error to the console
                    console.log(
                            "The following error occured: " +
                            textStatus, errorThrown);
                }
            });
        }
        else if ($(this).hasClass('add-to-wishlist'))
        {
            $('.beforeClick').hide();
            $('.afterClick').show();
            
            
            var selected_songs = [];
            var prod_id = $(this).parent().parent().parent().parent().find('input[type="hidden"]').attr('id');
            var provider = $(this).parent().parent().parent().parent().find('input[type="hidden"]').attr('data-provider');
            var type = $(this).parent().parent().parent().parent().find('input[type="hidden"]').attr('value');
            if(!type){
                var type = 'song';
            }
            var song = prod_id + '&' + provider;
            selected_songs.push(song);
            $.ajax({
                type: "post",
                data: {'songs': selected_songs, 'type': type},
                url: webroot + 'homes/addToWishlistNewHome',
                success: function(response)
                {

                    $('.beforeClick').show();
                    $('.afterClick').hide();
                                
                    if (languageSet === 'en') {
                        document.getElementById('wishlist' + prod_id).innerHTML = '<a class="add-to-wishlist added-to-wishlist">Added to Wishlist</a>';
                    } else {
                        document.getElementById('wishlist' + prod_id).innerHTML = '<a class="add-to-wishlist">Añadido a su Lista Deseos</a>';
                    }
                    
                    displayMessage(response);
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    // log the error to the console
                    console.log(
                            "The following error occured: " +
                            textStatus, errorThrown);
                }
            });
        }
        else
        {
            $('.beforeClick').hide();
            $('.afterClick').show();
    
            var type = 'song';
            var selected_songs = [];
            $(document).find('.top-songs-container .rows-container .row , .songs .rows-container .row , .songs-results-list .rows-container .row').each(function()
            {
                if ($(this).find('.row-checkbox').prop('checked'))
                {
                    selected_songs.push($(this).find('.options-menu input[type="hidden"]').attr('id') + '&' + $(this).find('.options-menu input[type="hidden"]').attr('data-provider'));
                }
            });

            $.ajax({
                type: "post",
                data: {'songs': selected_songs, 'type': type},
                url: webroot + 'homes/addToWishlistNewHome',
                success: function(response)
                {
                     $('.beforeClick').show();
                    $('.afterClick').hide();
                    
                    for (i = 0; i < selected_songs.length; i++)
                    {
                        var temp = selected_songs[i].split('&');
                        if ($('#wishlist'+temp[0]).length > 0){
                            if (languageSet === 'en') {
                                document.getElementById('wishlist' + temp[0]).innerHTML = '<a class="add-to-wishlist">Added to Wishlist</a>';
                            } else {                            
                                document.getElementById('wishlist' + temp[0]).innerHTML = '<a class="add-to-wishlist">Añadido a su Lista Deseos</a>';
                            }
                        }
                    }                  
                    
                    displayMessage(response);
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    // log the error to the console
                    console.log(
                            "The following error occured: " +
                            textStatus, errorThrown);
                }
            });
        }

        return false;
    });

    $(window).scroll(function()
    {
        var path = window.location.pathname;
        if (path === '/homes/index' || path === '/index' || 
                path === '/homes/index/' || path === '/index/' || path === '/')
        {
            if (!complete && !results_completed)
            {
                if ($(window).scrollTop() + $(window).height() > $(document).height() - 100)
                {
                    complete = true;
                    getFeaturedArtist();
                }
            }
        }
    });

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

            // $(document).find('.content').ajaxify().css('opacity', 100).show();
            $(document).find('.content').ajaxify().show();
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

    var Cookies = {
        // Initialize by splitting the array of Cookies
        init: function () {
                var allCookies = document.cookie.split('; ');
                for (var i=0;i<allCookies.length;i++) {
                        var cookiePair = allCookies[i].split('=');
                        this[cookiePair[0]] = cookiePair[1];
                }
        },
        // Create Function: Pass name of cookie, value, and days to expire
        create: function (name,value,days) {
                if (days) {
                        var date = new Date();
                        date.setTime(date.getTime()+(days*24*60*60*1000));
                        var expires = "; expires="+date.toGMTString();
                }
                else var expires = "";
                document.cookie = name+"="+value+expires+"; path=/";
                this[name] = value;
        },
        // Erase cookie by name
        erase: function (name) {
                this.create(name,'',-1);
                this[name] = undefined;
        }
    };
    Cookies.init();
    var cur = document.URL;
    var fullpath = cur.split('/');
    var fullpath = fullpath.slice(3);
    var fullpath = fullpath.join('/');
    var fullpath = '/'+fullpath;
    var path = window.location.pathname;

    if(path != '/users/redirection_manager' && path!= '/index' && path!= '/homes/chooser' && path!='/' && 'homes/index') {
  
        document.cookie = "lastUrl=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
        Cookies.erase('lastUrl');
        document.cookie = "lastUrl =" + fullpath + ";domain=.freegalmusic.com;path=/";

    }
    
    callSearchAjax();
    callMyAccountAjax();
    callNotificationAjax();

});














function displayMessage(response)
{
    var responseArray = response.split('|');

    if (responseArray[0] === 'error')
    {
        if (document.getElementById('flash-message'))
        {
            document.getElementById('flash-message').innerHTML = '';
            document.getElementById("flash-message").setAttribute("class", "");
        }

        document.getElementById("ajaxflashMessage44").style.display = "block";
        document.getElementById("ajaxflashMessage44").style.background = "red";
        document.getElementById('ajaxflashMessage44').innerHTML = responseArray[1];
        $('#ajaxflashMessage44').fadeOut(5000);
        return false;
    }
    else if (responseArray[0] === 'success')
    {
        if (document.getElementById('flash-message'))
        {
            document.getElementById('flash-message').innerHTML = '';
            document.getElementById("flash-message").setAttribute("class", "");
        }

        document.getElementById("ajaxflashMessage44").style.display = "block";
        document.getElementById("ajaxflashMessage44").style.background = "#52c6ec";
        document.getElementById('ajaxflashMessage44').innerHTML = responseArray[1];
        $('#ajaxflashMessage44').fadeOut(5000);
        return false;
    }
}

function multiSongCreateNewPlaylist(queueID)
{
    var type_of = 'multi';
        var selected_songs = [];
        $(document).find('.top-songs-container .rows-container .row , .songs .rows-container .row , .songs-results-list .rows-container .row').each(function()
        {
            if ($(this).find('.row-checkbox').prop('checked'))
            {
                selected_songs.push($(this).find('.options-menu input[type="hidden"]').attr('id') + '&' + $(this).find('.options-menu input[type="hidden"]').attr('data-provider'));
            }
        });

        $.ajax({
            type: "post",
            data: {'prodID': selected_songs, 'type': type_of, 'QueueID': queueID},
            url: webroot + 'queues/queueListAlbums',
            success: function(response)
            {
                addToQueueResponse(response, 'songs ');
            },
            error: function(jqXHR, textStatus, errorThrown) {              
                console.log(
                        "The following error occured: " +
                        textStatus, errorThrown);
            }
        });
}
$(document).ready(function() {
    scrollToSelectedGenre();
});

function scrollToSelectedGenre()
{
    sleep(100);
    var to_scroll = $(document).find(".genre-column");
    $(document).find(".genre-column li").each(function() {
        if ($(this).find('a').hasClass('active'))
        {
            var scroll_distance = $(this).offset().top - $(this).parent().offset().top;
            to_scroll.animate({
                scrollTop: scroll_distance
            }, 2000);

            $(this).find('a').focus();

        }
    });
}


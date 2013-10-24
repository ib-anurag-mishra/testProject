$(document).ready(function() {
    $('.recent-downloads-shadow-container').show();
    $('.recent-downloads-page .music-filter-button').addClass('active');

    $('.recent-downloads-page .recent-downloads-filter-container div.filter').on('click', function(e) {
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
            $('.recent-downloads-page .recent-downloads-filter-container div.filter').removeClass('active');
            $(this).addClass('active');
            $('#sortForm #sortOrder').val('asc');
        }

        $('#sortForm').submit();
    });

    $('.recent-downloads-page .recent-downloads-filter-container div.tab').on('click', function(e) {
        if ($(this).hasClass('active')) {

            if ($(this).hasClass('toggled')) {

                $(this).removeClass('toggled');

            } else {

                $(this).addClass('toggled');
            }


        } else {
            $('.recent-downloads-page .recent-downloads-filter-container div.tab').removeClass('active');
            $(this).addClass('active');


        }


    });

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

    $('.recent-downloads-page .add-to-wishlist-button').on('click', function(e) {
        e.preventDefault();

        $(this).siblings('.wishlist-popover').addClass('active');
    });

    $('.recent-downloads-page .wishlist-popover').on('mouseleave', function(e) {

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

    $('.recent-downloads-page .recent-video-downloads-scrollable .row').on('mouseleave', function() {
        $(this).find('.date').removeClass('hovered');
        $(this).find('.album-title').removeClass('hovered');
        $(this).find('.artist-name').removeClass('hovered');
        $(this).find('.time').removeClass('hovered');
        $(this).find('.song-title').removeClass('hovered');
        $(this).find('.preview').removeClass('hovered');
        $(this).find('.add-to-wishlist-button').removeClass('hovered');

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

    $('.recent-downloads-page .recent-video-downloads-scrollable .row').on('mouseleave', function() {
        $(this).find('.date').removeClass('hovered');
        $(this).find('.album-title').removeClass('hovered');
        $(this).find('.artist-name').removeClass('hovered');
        $(this).find('.time').removeClass('hovered');
        $(this).find('.song-title').removeClass('hovered');
        $(this).find('.preview').removeClass('hovered');
        $(this).find('.add-to-wishlist-button').removeClass('hovered');

    });


    $('.recent-downloads-page .recent-downloads-scrollable .row .preview').on('mouseenter', function(e) {
        $(this).removeClass('hovered').addClass('blue-bkg');

    });

    $('.recent-downloads-page .recent-downloads-scrollable .row .preview').on('mouseleave', function(e) {
        $(this).removeClass('blue-bkg').addClass('hovered');

    });

    $('.recent-downloads-page .recent-downloads-scrollable .row .preview').on('click', function(e) {

        if ($(this).hasClass('playing')) {

            $(this).removeClass('playing');

            $(this).parents('.row').removeClass('playing');
            $(this).parent().removeClass('playing');
            $(this).parent().siblings('.date').removeClass('playing');
            $(this).parent().siblings('.album-title').removeClass('playing');
            $(this).parent().siblings('.artist-name').removeClass('playing');
            $(this).parent().siblings('.time').removeClass('playing');
            $(this).parent().siblings('.song-title').removeClass('playing');
            $(this).parent().siblings('.add-to-wishlist-button').removeClass('playing');
            $(this).parent().siblings('.download').removeClass('playing');


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
            $(this).parent().siblings('.date').addClass('playing');
            $(this).parent().siblings('.album-title').addClass('playing');
            $(this).parent().siblings('.artist-name').addClass('playing');
            $(this).parent().siblings('.time').addClass('playing');
            $(this).parent().siblings('.song-title').addClass('playing');
            $(this).parent().siblings('.add-to-wishlist-button').addClass('playing');
            $(this).parent().siblings('.download').addClass('playing');


        }

    });

    $('.video-filter-button').click(function() {
        $(this).addClass('active');
        $('.music-filter-button').removeClass('active');
        $('.recent-downloads-shadow-container').hide();
        $('.recent-video-downloads-shadow-container').show();

    });

    $('.music-filter-button').click(function() {
        $(this).addClass('active');
        $('.video-filter-button').removeClass('active');
        $('.recent-video-downloads-shadow-container').hide();
        $('.recent-downloads-shadow-container').show();
    });

});
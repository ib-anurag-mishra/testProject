
$(document).ready(function() {

    $(document).on('mouseenter', '.ac_results ul', function() {

        $(this).bind('mousewheel', function(e) {

            $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);
            //prevent page fom scrolling
            return false;

        });
    });

    $('.music-note-icon').on('mouseenter', function() {

        $('.plays-tooltip').addClass('active');

    });

    $('.music-note-icon').on('mouseleave', function() {

        $('.plays-tooltip').removeClass('active');

    });

    $('.my-account-menu').on('click', function() {

        $('.account-menu-dropdown').toggleClass('active');

    });

    $('.my-account-menu').on('mouseleave', function(e) {

        if (e.offsetX < 0 || e.offsetX > $(this).width() || e.offsetY < 0) {

            $('.account-menu-dropdown').removeClass('active');
        }
    });

    $('.album-cover-container ul').bindMouseWheel();
    $('.album-cover-container ul').on('mouseleave', function() {
        $(this).removeClass('active');

    });


    $('.album-cover-container').on('mouseenter', function() {

        $(this).find('.toggleable').addClass('active');

    });

    $('.album-cover-container').on('mouseleave', function() {

        $(this).find('.toggleable').removeClass('active');

    });


    $('.playlist-menu-icon').on('click', function() {

        $(this).siblings('ul').toggleClass('active');
    });

    $('.left-scroll-button,.wishlist-icon').on('mouseenter', function() {


        $('.album-cover-container ul').removeClass('active');


    });

    $('.top-albums-carousel>ul>li').on('mouseleave', function() {

        $('.album-cover-container ul').removeClass('active');
    });

    $('.top-songs-filter-icon').on('mouseleave', function(e) {

        if (e.offsetX < 0 || e.offsetX > $(this).width() || e.offsetY < 0) {

            $('.top-songs-filter-menu').removeClass('active');
        }

    });


    var ulPosition;


    $('.left-scroll-button').on('click', function() {



        var currentScrollLeft = $('.top-albums-carousel').scrollLeft();

        currentScrollLeft = currentScrollLeft - 660;


        $('.top-albums-carousel').animate({scrollLeft: currentScrollLeft});

    });


    $('.right-scroll-button').on('click', function() {




        var currentScrollLeft = $('.top-albums-carousel').scrollLeft();

        currentScrollLeft = currentScrollLeft + 660;


        $('.top-albums-carousel').animate({scrollLeft: currentScrollLeft});






    });

    $('.account-menu-dropdown').on('mouseleave', function() {

        $(this).removeClass('active');
    });
    $('.genre-column,.alpha-artist-list-column,.artist-column').bindMouseWheel();

    $(document).on('mouseenter', '.artist-column', function() {

        $(this).bind('mousewheel', function(e) {

            $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);
            //prevent page fom scrolling
            return false;

        });
    });

    $(document).on('mouseenter', '.genre-column', function() {

        $(this).bind('mousewheel', function(e) {

            $(this).scrollTop($(this).scrollTop() - e.originalEvent.wheelDeltaY);
            //prevent page fom scrolling
            return false;

        });
    });        



    $('.genre-column a').on('click', function(e) {
        // e.preventDefault();
        // $('.genre-column a').removeClass('active');
        // $(this).addClass('active');

    });


    $('.alpha-artist-list-column a').on('click', function(e) {
        e.preventDefault();
        $('.alpha-artist-list-column a').removeClass('active');
        $(this).addClass('active');

    });





    var genreScrollAmount;

    $(document).on('click', '.genre-scroll-up', function() {
        var currentScrollTop = $('.genre-column').scrollTop();
        var genreListHeight = $('.genre-column ul').height();
        var genreColumnHeight = $('.genre-column').height();

        genreScrollAmount = currentScrollTop + genreColumnHeight;



        $('.genre-column').animate({
            scrollTop: genreScrollAmount
        });

    });

    $(document).on('click', '.genre-scroll-down', function() {

        var currentScrollTop = $('.genre-column').scrollTop();
        var genreListHeight = $('.genre-column ul').height();
        var genreColumnHeight = $('.genre-column').height();

        genreScrollAmount = currentScrollTop - genreColumnHeight;



        $('.genre-column').animate({
            scrollTop: genreScrollAmount
        });
    });


    $('.genre-scroll-up').on('click', function() {

        var currentScrollTop = $('.genre-column').scrollTop();
        var genreListHeight = $('.genre-column ul').height();
        var genreColumnHeight = $('.genre-column').height();

        genreScrollAmount = currentScrollTop + genreColumnHeight;



        $('.genre-column').animate({
            scrollTop: genreScrollAmount
        });


    });

    $('.genre-scroll-down').on('click', function() {
        var currentScrollTop = $('.genre-column').scrollTop();
        var genreListHeight = $('.genre-column ul').height();
        var genreColumnHeight = $('.genre-column').height();

        genreScrollAmount = currentScrollTop - genreColumnHeight;



        $('.genre-column').animate({
            scrollTop: genreScrollAmount
        });




    });


    var artistScollAmount;

    $(document).on('click', '.artist-scroll-up', function() {

        var currentScrollTop = $('.artist-column').scrollTop();
        var artistListHeight = $('.artist-column ul').height();
        var artistColumnHeight = $('.artist-column').height();

        artistScrollAmount = currentScrollTop + artistColumnHeight;



        $('.artist-column').animate({
            scrollTop: artistScrollAmount
        });
    });

    $(document).on('click', '.artist-scroll-down', function() {
        var currentScrollTop = $('.artist-column').scrollTop();
        var artistListHeight = $('.artist-column ul').height();
        var artistColumnHeight = $('.artist-column').height();

        artistScrollAmount = currentScrollTop - artistColumnHeight;



        $('.artist-column').animate({
            scrollTop: artistScrollAmount
        });

    });


    $('.artist-scroll-up').on('click', function() {



        var currentScrollTop = $('.artist-column').scrollTop();
        var artistListHeight = $('.artist-column ul').height();
        var artistColumnHeight = $('.artist-column').height();

        artistScrollAmount = currentScrollTop + artistColumnHeight;



        $('.artist-column').animate({
            scrollTop: artistScrollAmount
        });



    });

    $('.artist-scroll-down').on('click', function() {

        var currentScrollTop = $('.artist-column').scrollTop();
        var artistListHeight = $('.artist-column ul').height();
        var artistColumnHeight = $('.artist-column').height();

        artistScrollAmount = currentScrollTop - artistColumnHeight;



        $('.artist-column').animate({
            scrollTop: artistScrollAmount
        });

    });


    $('.sr-albums-prev').on('click', function() {
        var currentScrollLeft = $('.search-results-albums').scrollLeft();
        currentScrollLeft = currentScrollLeft - 660;
        $('.search-results-albums').animate({
            scrollLeft: currentScrollLeft
        });


    });

    $('.sr-albums-next').on('click', function() {



        var currentScrollLeft = $('.search-results-albums').scrollLeft();
        currentScrollLeft = currentScrollLeft + 660;
        $('.search-results-albums').animate({
            scrollLeft: currentScrollLeft
        });

    });

    var multipleRowsChecked = false;
    $('.row-checkbox').on('click', function() {
        $(this).parent('.row').toggleClass('highlighted');
        var c = 0;
        $('.row-checkbox').each(function() {
            if ($(this).is(':checked')) {
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

    $('.add-to-playlist').on('mouseenter', function() {
        $(this).parents('ul').next('.playlist-menu').addClass('active');

    });

    $('.options-menu').on('mouseleave', function() {
        $(this).children('.playlist-menu').removeClass('active');
        $(this).removeClass('active');
        if (!multipleRowsChecked) {
            $('.multi-select-icon').removeClass('highlighted');
        }
    });

    $('.multi-select-icon').on('click', function() {

        $(this).siblings('.options-menu').addClass('active');
        $('.multi-select-icon').addClass('highlighted');

    });

    $('.multi-select-icon').on('mouseleave', function(e) {

        if (e.offsetX > $(this).width() || e.offsetY < 0) {

            $('.options-menu').removeClass('active');
            $(this).removeClass('highlighted');
        }
    });

    $('.select-all').on('click', function(e) {
        e.preventDefault();
        $('.row-checkbox').each(function() {
            $(this).prop('checked', true);
        });
    });

    $('.clear-all').on('click', function(e) {
        e.preventDefault();
        $('.row-checkbox').each(function() {
            $(this).prop('checked', false);
        });
    });

    $('.playlist-menu').bindMouseWheel();

    $('.menu-btn').on('click', function() {

        $(this).siblings('.options-menu').addClass('active');
    });

    $('.menu-btn').on('mouseleave', function(e) {

        if (e.offsetX > $(this).width() || e.offsetY < 0) {

            $('.options-menu').removeClass('active');
        }
    });



    $('.top-songs-filter-icon').on('click', function() {
        $(this).siblings('.top-songs-filter-menu').toggleClass('active');

    });

    $('.top-songs-filter-menu').on('mouseleave', function() {
        $(this).removeClass('active');

    });

    $('.featured-grid-item').on('mouseenter', function() {
        $(this).find('.featured-grid-menu').addClass('active');

    });

    $('.featured-grid-item').on('mouseleave', function() {
        $(this).find('.featured-grid-menu').removeClass('active');

    });





    $(document).find('.top-songs-container .rows-container .row')
    {
        var
                count = $(document).find('.top-songs-container .rows-container > div.row').length,
                num_cols = Math.floor(count / 20),
                container = $(document).find('.top-songs-container .rows-container');

        //dividing the songs list in pagination
        for (var i = 0; i < num_cols; i++)
        {
            var listItems = $(document).find('.top-songs-container .rows-container > div.row').slice(0, 20);
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

    $(document).find('.top-songs .menu-btn , .playlist-menu-icon , .top-songs .multi-select-icon').on('click', function(e)
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

    $(document).find('.add-all-to-wishlist , .wishlist-icon, .top-songs .add-to-wishlist').on('click', function(e)
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
            var type = 'song';
            var selected_songs = [];
            var prod_id = $(this).parent().parent().parent().find('input[type="hidden"]').attr('id');
            var provider = $(this).parent().parent().parent().find('input[type="hidden"]').attr('data-provider');
            var song = prod_id + '&' + provider;
            selected_songs.push(song);
            $.ajax({
                type: "post",
                data: {'songs': selected_songs, 'type': type},
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
        else
        {           
            var type = 'song';
            var selected_songs = [];
            $(document).find('.top-songs-container .rows-container .row').each(function()
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
        if( path === '/homes/index' || path === '/index' )
        {
            if($(window).scrollTop() + $(window).height() > $(document).height() - 300) 
            {
                getFeaturedArtist();
             }
        }
    });
    
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
        document.getElementById('ajaxflashMessage44').innerHTML = responseArray[1];
        return false;
    }
}

function getFeaturedArtist()
{
    $(document).find('#artist_loader').css('display','block');
}



$(document).ready(function() {

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

    /*

    $('.top-single-container ul').bindMouseWheel();
    $('.top-single-container ul').on('mouseleave', function() {
        $(this).removeClass('active');

    });    

    */

    var $album_cover_container_ul = $('.album-cover-container').find('ul');


    $album_cover_container_ul.bindMouseWheel();
    $album_cover_container_ul.on('mouseleave', function() {
        $(this).removeClass('active');

    });


    /*
    $('.album-cover-container , .album-container').on('mouseenter', function() {

        $(this).find('.toggleable').addClass('active');
        $(this).find('.toggeable').addClass('active');

    });

    $('.album-cover-container , .album-container').on('mouseleave', function() {

        $(this).find('.toggleable').removeClass('active');
        $(this).find('.toggeable').removeClass('active');
    });
    */

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
        c
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
    var $artist_column = $('.artist-column');
    var $artist_column_ul = $artist_column.find('ul');

    $(document).on('click', '.artist-scroll-up', function() {

        var currentScrollTop = $artist_column.scrollTop();
        var artistListHeight = $artist_column_ul.height();
        var artistColumnHeight = $artist_column.height();

        artistScrollAmount = currentScrollTop + artistColumnHeight;



        $artist_column.animate({
            scrollTop: artistScrollAmount
        });
    });

    $(document).on('click', '.artist-scroll-down', function() {
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

    $row_checkbox.on('click', function() {
        var $this = $(this);
        $this.parent('.row').toggleClass('highlighted');
        var c = 0;
        $row_checkbox.each(function() {
            if ($this.is(':checked')) {
                c++;
            }
            if (c >= 2) {
                $multi_select_icon.addClass('highlighted');
                multipleRowsChecked = true;
            } else {
                $multi_select_icon.removeClass('highlighted');
                multipleRowsChecked = false;
            }
        });
    });

    var $add_to_playlist = $('.add-to-playlist')

    $add_to_playlist.on('mouseenter', function() {
        $(this).parents('ul').next('.playlist-menu').addClass('active');

    });

    var $options_menu = $('.options-menu');

    $options_menu.on('mouseleave', function() {
        var $this = $(this);
        $this.children('.playlist-menu').removeClass('active');
        $this.removeClass('active');
        if (!multipleRowsChecked) {
            $multi_select_icon.removeClass('highlighted');
        }
    });

    $multi_select_icon.on('click', function() {

        $(this).siblings('.options-menu').addClass('active');
        $multi_select_icon.addClass('highlighted');

    });

    $multi_select_icon.on('mouseleave', function(e) {
        var $this = $(this);

        if (e.offsetX > $this.width() || e.offsetY < 0) {

            $options_menu.removeClass('active');
            $this.removeClass('highlighted');
        }
    });

    var $select_all = $('.select-all');
    $select_all.on('click', function(e) {
        e.preventDefault();
        $row_checkbox.each(function() {
            $(this).prop('checked', true);
        });
    });

    var $clear_all = $('.clear-all'); 
    $clear_all.on('click', function(e) {
        e.preventDefault();
        $row_checkbox.each(function() {
            $(this).prop('checked', false);
        });
    });

    
    var $menu_btn = $('.menu-btn');
    $menu_btn.on('click', function() {

        $(this).siblings('.options-menu').addClass('active');
    });

    $menu_btn.on('mouseleave', function(e) {

        if (e.offsetX > $(this).width() || e.offsetY < 0) {

            $options_menu.removeClass('active');
        }
    });


    /*
    $('.top-songs-filter-icon').on('click', function() {
        $(this).siblings('.top-songs-filter-menu').toggleClass('active');

    });

    $('.top-songs-filter-menu').on('mouseleave', function() {
        $(this).removeClass('active');

    });
    */
    /*
    $('.featured-grid-item').on('mouseenter', function() {
        $(this).find('.featured-grid-menu').addClass('active');

    });

    $('.featured-grid-item').on('mouseleave', function() {
        $(this).find('.featured-grid-menu').removeClass('active');

    });
    */
    $(document).on('mouseenter','.featured-grid-item',function(){

        $(this).find('.featured-grid-menu').addClass('active');
    });

    $(document).on('mouseleave','.featured-grid-item',function(){

        $(this).find('.featured-grid-menu').removeClass('active');
    });


    /* FAQ page */
    var $faq_container_anchor = $('.faq-container').find('li').find('a');
    var $faq_container_paragraph = $('.faq-container').find('p');


    $faq_container_anchor.on('click', function(e) {
        e.preventDefault();
        var $paragraph_siblings = $(this).siblings('p');



        if ($paragraph_siblings.hasClass('active')) {
            $paragraph_siblings.slideUp(500).removeClass('active');
        } else {
            $faq_container_paragraph.slideUp(500).removeClass('active');
            $paragraph_siblings.slideDown(500).addClass('active');
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

    $(document).find('.top-songs .menu-btn ,  .top-single-container .playlist-menu-icon,  .playlist-menu-icon , .top-songs .multi-select-icon , .album-info .menu-btn , .songs .menu-btn ,  .songs .multi-select-icon , .songs-results-list .menu-btn ,  .songs-results-list .multi-select-icon').on('click', function(e)
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
                        document.getElementById('wishlist' + prod_id).innerHTML = '<a class="add-to-wishlist">Added to Wishlist</a>';
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
                path === '/homes/index/' || path === '/index/')
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

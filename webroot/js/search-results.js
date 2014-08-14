function callSearchPageAjax(){
    $("#submit").click(function(event){
       ajaxSearchPage();
    });
} 

function ajaxSearchPage(){
       var contentSelector = '.content,article:first,.article:first,.post:first';
       var $content = $(contentSelector).filter(':first');
       var $body = $(document.body);
       // Ensure Content
        if ($content.length === 0) {
            $content = $body;
        }
       
       var q = $('#query').val();
       var type = $('#search_type').val();
       
       var loading_div = "<div class='loader'>";
            loading_div += "</div>";
            $('.content').append(loading_div);
         
       // Start Fade Out
       // Animating to opacity to 0 still keeps the element's height intact
       // Which prevents that annoying pop bang issue when loading in new content
       $content.animate({opacity: 0}, 800);
            
       
       $.ajax({
           url:'/search/index',
           method:'get',
           data:{'q':q,'type':type},
           success:function(response){
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
//                    if ($body.ScrollTo || false) {
//                        $body.ScrollTo(scrollOptions);
//                    } /* http://balupton.com/projects/jquery-scrollto */

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
                    $(document).ready(function(){

                        reloadJqueryFunctions();
                    });

               callSearchPageAjax();
           },
           failure:function(){
               alert('Problem fetching data');
           }
       });
       $('#search-text').val(q);
       $('#master-filter').val(type);
       History.pushState(null, 'Search', '/search/index'+'?'+'q='+q+'&type='+type);
       return false;
}


$(document).ready(function() {

    $('#query').keypress(function(event) {
        //auto_check();
        if (event.which == '13') {
            //alert($('#search_query').val());
            $('#searchQueryForm').submit();
        }
    });
    
    $("#query").autocomplete(webroot + "search/autocomplete", {
                minChars: 3,
                cacheLength: 10,
                autoFill: false,
                extraParams: {
                    type: $('#search_type').val(),
                    ufl: '1'
                },
                formatItem: function(data) {
                    return data[0];
                },
                formatResult: function(data) {
                    return data[1];
                }
            }).result(function(e, item) {
        $('#auto').attr('value', 1);
        if (item[2] == 1) {
            $('#search_type').val('artist');
        } else if (item[2] == 2) {
            $('#search_type').val('album');
        } else if (item[2] == 3) {
            $('#search_type').val('song');
        }
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

    // $('.tracklist-header .album').addClass('active');

    $('.tracklist-header span').on('click', function(e) {
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
        //e.preventDefault();

        var target = $(this).attr('href');

        //console.log($(target).position().top);

        /*
         $('.tracklist-scrollable').animate({
         
         scrollTop: $(target).position().top
         },500);
         */




    });
    
    callSearchPageAjax();

});
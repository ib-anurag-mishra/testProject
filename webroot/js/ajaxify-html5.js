// Ajaxify
// v1.0.1 - 30 September, 2012
// https://github.com/browserstate/ajaxify 
(function(window, undefined) {

    // Prepare our Variables
    var            
            History = window.History,
            $ = window.jQuery,
            document = window.document;

    // Check to see if History.js is enabled for our Browser
    if (!History.enabled) {
        console.log('history not enabled');
        return false;
    }

    // Wait for Document
    $(function() {

        // Prepare Variables
        var
                /* Application Specific Variables */
                contentSelector = '.content,article:first,.article:first,.post:first',
                $content = $(contentSelector).filter(':first'),
                contentNode = $content.get(0),
                $menu = $('.left-sidebar,#menu,#nav,nav:first,.nav:first').filter(':first'),
                activeClass = 'active selected current youarehere',
                activeSelector = '.active,.selected,.current,.youarehere',
                menuChildrenSelector = '> li,> ul > li',
                completedEventName = 'statechangecomplete',
                /* Application Generic Variables */
                $window = $(window),
                search = false,
                $body = $(document.body),
                rootUrl = History.getRootUrl(),
                scrollOptions = {
            duration: 800,
            easing: 'swing'
        };

        // Ensure Content
        if ($content.length === 0) {
            $content = $body;
        }

        // Internal Helper
        $.expr[':'].internal = function(obj, index, meta, stack) {
            // Prepare
            var
                    $this = $(obj),
                    url = $this.attr('href') || '',
                    isInternalLink;

            // Check link
            isInternalLink = url.substring(0, rootUrl.length) === rootUrl || url.indexOf(':') === -1;


            // Ignore or Keep
            return isInternalLink;
        };

        // HTML Helper
        var documentHtml = function(html) {
            // Prepare
            var result = String(html)
                    .replace(/<\!DOCTYPE[^>]*>/i, '')
                    .replace(/<(html|head|body|title|meta|script)([\s\>])/gi, '<div class="document-$1"$2')
                    .replace(/<\/(html|head|body|title|meta|script)\>/gi, '</div>')
                    ;

            // Return
            return $.trim(result);
        };

        // Ajaxify Helper
        $.fn.ajaxify = function() {
            // Prepare
            var $this = $(this);

            // Ajaxify
            $this.find('a:internal:not(.no-ajaxy)').click(function(event) {


                // Prepare
                var
                        $this = $(this),
                        url = $this.attr('href'),
                        title = $this.attr('title') || null;

                // Continue as normal for cmd clicks etc
                if (event.which == 2 || event.metaKey) {
                    return true;
                }

                // Ajaxify this link
                if (typeof console === "undefined") {
                    console = {
                        log: function() {
                        }
                    };
                }
                //console.log(url);

                var tempURL = url.split('/');
                if (tempURL[0] === 'search')
                {
                    search = true;
                }

                History.pushState(null, title, url);
                event.preventDefault();
                return false;


            });

            // Chain
            return $this;
        };

        // Ajaxify our Internal Links
        $body.ajaxify();

        // Hook into State Changes
        $window.bind('statechange', function(event) {
            // Prepare Variables
            var
                    scriptPath = document.getElementById('Scripts_Path').value;
                    State = History.getState(),
                    url = State.url,
                    relativeUrl = url.replace(rootUrl, '');

            // for search page 
            var tempURL = relativeUrl.split('/');
            if (tempURL[0] === 'search' && search)
            {
                return false;
            }

            // Set Loading
            var loading_div = "<div class='loader'>";
            loading_div += "</div>";
            $('#content').append(loading_div);

            $.ajax({
                url: webroot + 'users/isPatronLogin',
                type: "get",
                success:
                        function(data) {
                            if (!data) {
                                window.location.href = url;
                            }
                            event.preventDefault();
                        }
            });

            //$body.addClass('loader');
            // Start Fade Out
            // Animating to opacity to 0 still keeps the element's height intact
            // Which prevents that annoying pop bang issue when loading in new content

            // Disabled for making spinnnign spoke visible till content is laoded
            // $content.animate({opacity: 0}, 800);

            // Ajax Request the Traditional Page
            $.ajax({
                url: url,
                success: function(data, textStatus, jqXHR) {
                    // Prepare
                    var
                            $data = $(documentHtml(data)),
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
                        document.location.href = url;
                        return false;
                    }

                    // Update the top bar menu                    
                    $menuChildren = $('.site-nav li');
                    $menuChildren.each(function() {
                        if ($(this).find('a').hasClass('active'))
                        {
                            $(this).find('a').removeClass('active');
                        }
                        if ($(this).find('a').attr('href') === '/' + relativeUrl)
                        {
                            $(this).find('a').addClass('active');
                        }
                    });

                    // update side bar menu
                    $menuChildren = $('.left-sidebar li');
                    $menuChildren.each(function() {
                        if ($(this).find('a').hasClass('active'))
                        {
                            $(this).find('a').removeClass('active');
                        }
                        if ($(this).find('a').attr('href') === '/' + relativeUrl)
                        {
                            $(this).find('a').addClass('active');
                        }


                    });

                    if (relativeUrl === 'homes/us_top_10')
                    {
                        $('.topmylib07').addClass('active');
                        $('#topmostpopuler07').addClass('active');
                        $('#ustoplib07').addClass('active');
                    }
//                    $menuChildren.filter(activeSelector).removeClass(activeClass);
//                    $menuChildren = $menuChildren.has('a[href^="' + relativeUrl + '"],a[href^="/' + relativeUrl + '"],a[href^="' + url + '"]');
//                    if ($menuChildren.length === 1) {
//                        $menuChildren.addClass(activeClass);
//                    }


                    // Update the content
                    $content.stop(true, true);
                    $content.html(contentHtml).ajaxify().css('opacity', 100).show(); /* you could fade in here if you'd like */

                    // Update the title
                    document.title = $data.find('.document-title:first').text();
                    try {
                        // document.getElementsByTagName('title')[0].innerHTML = document.title.replace('<', '&lt;').replace('>', '&gt;').replace(' & ', ' &amp; ');
                        document.title = document.title.replace('<', '&lt;').replace('>', '&gt;').replace(' & ', ' &amp; ');
                    }
                    catch (Exception) {
                    }

                    // Add the scripts
/*
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
*/
                    // Complete the change
                    if ($body.ScrollTo || false) {
                        $body.ScrollTo(scrollOptions);
                    } /* http://balupton.com/projects/jquery-scrollto */

                    $window.trigger(completedEventName);

                    // Inform Google Analytics of the change
                    if (typeof window._gaq !== 'undefined') {
                        window._gaq.push(['_trackPageview', relativeUrl]);
                    }

                    // Inform ReInvigorate of a state change
                    if (typeof window.reinvigorate !== 'undefined' && typeof window.reinvigorate.ajax_track !== 'undefined') {
                        reinvigorate.ajax_track(url);
                        // ^ we use the full url here as that is what reinvigorate supports
                    }

                    //$body.removeClass('loader');
                    //$.getScript(webroot + 'css/styles.css');
                    //$.getScript(webroot + 'css/freegal_styles.css');

                    //$.getScript(scriptPath + '/js/freegal.js');
                    //$.getScript(webroot + 'js/site.js');
                    $.getScript(webroot + 'js/freegal.js');
                    $.getScript(webroot + 'js/freegal40-site.js');
                    //$.getScript(webroot + 'js/audioPlayer.js');
                    $.getScript(scriptPath + '/js/recent-downloads.js');
                    //$.getScript(webroot + 'js/search-results.js');

                    $('.loader').fadeOut(50);
                    $('#content').find('.loader').remove();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    document.location.href = url;
                    return false;
                }
            }); // end ajax

        }); // end onStateChange


    }); // end onDomLoad

})(window); // end closure

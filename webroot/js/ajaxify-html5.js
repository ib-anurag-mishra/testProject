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
                $menu = $('#menu,#nav,nav:first,.nav:first').filter(':first'),
                activeClass = 'active selected current youarehere',
                activeSelector = '.active,.selected,.current,.youarehere',
                menuChildrenSelector = '> li,> ul > li',
                completedEventName = 'statechangecomplete',
                /* Application Generic Variables */
                $window = $(window),
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
                    State = History.getState(),
                    url = State.url,
                    relativeUrl = url.replace(rootUrl, '');


            // Set Loading
            $('.content-wrapper').append('<div class="loader"></div>');
            
            //$body.addClass('loader');

            // Start Fade Out
            // Animating to opacity to 0 still keeps the element's height intact
            // Which prevents that annoying pop bang issue when loading in new content
            $content.animate({opacity: 0}, 800);

            // Ajax Request the Traditional Page
            $.ajax({
                url: url,
                success: function(data, textStatus, jqXHR) {
                    // Below method for removal of '#' & '#.' in between URL
                    // this is used in IE8 

                    // checking for #. in url 
                    var indexOfHash = window.location.href.indexOf('#.');
                    if (indexOfHash > 0)
                    {
                        var current_nav = '';

                        var base_url = window.location.href.slice(0, window.location.href.indexOf('.com/') + 4);
                        var url_slice = window.location.href.slice(indexOfHash + 2, window.location.href.length);

                        if (url_slice.indexOf('_top_10') > -1 || url_slice.indexOf('my_history') > -1
                                || url_slice.indexOf('_wishlist') > -1 || url_slice.indexOf('_releases') > -1)
                        {
                            if (window.location.href.indexOf('/homes') > -1)
                            {
                                current_nav = base_url + '/homes' + url_slice;
                            }
                            else
                            {
                                if (url_slice.indexOf('homes') > -1)
                                {
                                    current_nav = base_url + url_slice;
                                }
                                else
                                {
                                    current_nav = base_url + '/homes' + url_slice;
                                }
                            }
                        }
                        else
                        {
                            current_nav = base_url + url_slice;
                        }

                        if (url_slice.indexOf('_notification') > -1 || url_slice.indexOf('_account') > -1 || url_slice.indexOf('logout') > -1)
                        {
                            if (window.location.href.indexOf('/users') > -1)
                            {
                                current_nav = base_url + '/users' + url_slice;
                            }
                            else
                            {
                                if (url_slice.indexOf('users') > -1)
                                {
                                    current_nav = base_url + url_slice;
                                }
                                else
                                {
                                    current_nav = base_url + '/users' + url_slice;
                                }
                            }
                        }
                       

                        current_nav.replace('/homes/homes', '/homes');
                        window.location.href = current_nav;
                        return true;
                    }

                    // chekcing for # in url
                    var indexOfHash = window.location.href.indexOf('#');
                    if (indexOfHash > 0)
                    {
                        var current_nav = '';

                        var base_url = window.location.href.slice(0, window.location.href.indexOf('.com/') + 5);
                        var url_slice = window.location.href.slice(indexOfHash + 1, window.location.href.length);

                        if (url_slice.indexOf('_top_10') > -1 || url_slice.indexOf('my_history') > -1
                                || url_slice.indexOf('_wishlist') > -1 || url_slice.indexOf('_releases') > -1)
                        {
                            if (window.location.href.indexOf('/homes') > -1)
                            {
                                current_nav = base_url + '/homes' + url_slice;
                            }
                            else
                            {
                                if (url_slice.indexOf('homes') > -1)
                                {
                                    current_nav = base_url + url_slice;
                                }
                                else
                                {
                                    current_nav = base_url + '/homes' + url_slice;
                                }
                            }
                        }
                        else
                        {
                            current_nav = base_url + url_slice;
                        }

                        if (url_slice.indexOf('_notification') > -1 || url_slice.indexOf('_account') > -1 || url_slice.indexOf('logout') > -1)
                        {
                            if (window.location.href.indexOf('/users') > -1)
                            {
                                current_nav = base_url + '/users' + url_slice;
                            }
                            else
                            {
                                if (url_slice.indexOf('users') > -1)
                                {
                                    current_nav = base_url + url_slice;
                                }
                                else
                                {
                                    current_nav = base_url + '/users' + url_slice;
                                }
                            }
                        }
                      
                        current_nav.replace('/homes/homes', '/homes');
                        window.location.href = current_nav;
                        return true;
                    }

                    // After removal of '#' & '#.' the below statements are exceuted

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

                    // Update the menu
                    $menuChildren = $menu.find(menuChildrenSelector);
                    $menuChildren.filter(activeSelector).removeClass(activeClass);
                    $menuChildren = $menuChildren.has('a[href^="' + relativeUrl + '"],a[href^="/' + relativeUrl + '"],a[href^="' + url + '"]');
                    if ($menuChildren.length === 1) {
                        $menuChildren.addClass(activeClass);
                    }

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

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    document.location.href = url;
                    return false;
                },
            })
                    .done(function() {
                //$body.removeClass('loader');
                $('.loader').fadeOut(3000);
                $('.content-wrapper').remove(".loader");
            }); // end ajax

        }); // end onStateChange

    }); // end onDomLoad

})(window); // end closure

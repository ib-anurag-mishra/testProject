<!DOCTYPE html>
<html>

    <head>

        <?php
        echo $this->Html->charset();
        //echo $this->Html->meta(array('http-equiv' => "X-UA-Compatible", 'content' => "IE=edge,chrome=1"));

        echo $this->Html->meta('icon');

        if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
        {
            header('X-UA-Compatible: IE=edge,chrome=1');
        }
        ?>

        <title>
            <?php __('Freegal Music : Your New Music Library :'); ?>
            <?php
            if ($title_for_layout == "Homes")
            {
                echo substr($title_for_layout, 0, -1);
            }
            else
            {
                echo $title_for_layout;
            }
            ?>
        </title>
        
        <script type="text/javascript" src="<?php echo Configure::read('App.Script') ?>/js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="<?php echo Configure::read('App.Script') ?>/js/modernizr.custom.js"></script>
        <script type="text/javascript" src="<?php echo Configure::read('App.Script') ?>/js/lazyload.1.9.1.js"></script>
        <script type="text/javascript" src="<?php echo Configure::read('App.Script') ?>/js/jquery.colorbox.js"></script>
        <script type="text/javascript" src="<?php echo Configure::read('App.Script') ?>/js/jquery.cycle.all.js"></script>
        <script type="text/javascript" src="<?php echo Configure::read('App.Script') ?>/js/jquery.autocomplete.js"></script>
        <script type="text/javascript" src="<?php echo Configure::read('App.Script') ?>/js/jquery.history.js"></script>
<!--        <script type="text/javascript" src="<?php echo Configure::read('App.Script') ?>/js/ajaxify-html5.js"></script>-->
<!--		<script type="text/javascript" src="<?php echo Configure::read('App.Script') ?>/js/site.js"></script>-->
        <script type="text/javascript" src="<?php echo Configure::read('App.Script') ?>/js/audioPlayer.js"></script>
        <!--<script type="text/javascript" src="<?php echo Configure::read('App.Script') ?>/js/freegal.js"></script>-->
        <script type="text/javascript" src="<?php echo Configure::read('App.Script') ?>/js/recent-downloads.js"></script>
        <script type="text/javascript" src="<?php echo Configure::read('App.Script') ?>/js/search-results.js"></script>
        <script type="text/javascript" src="<?php echo Configure::read('App.Script') ?>/js/qtip.2.1.1.min.js"></script>
        <script type="text/javascript" src="<?php echo Configure::read('App.Script') ?>/js/qtip_add.js"></script>        

        <?php
        echo $this->Html->css(
                array(
                    'freegal40',
                    'freegal_styles',
                    'jquery.autocomplete',
                    'colorbox'
                        
                )
        );



        echo $javascript->link(
                array(                    
                    'freegal','ajaxify-html5.js', 'site.js','freegal40-libraries','html5shiv','freegal40-site'
                )
        );
        
        ?>

        <?php
        if ($this->Session->read("patron"))
        {
            if ($this->Session->read('library_type') == '2')
            {
                ?>      

            <?php } ?>

            <script type="text/javascript" src="<?php echo Configure::read('App.Script') ?>/js/swfobject.js"></script>
            <?php
        }
        if ($this->Session->read('library') && $this->Session->read('library') != '')
        {
            $libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
            ?>

            <script type="text/javascript">

    <?php $setLang = ($this->Session->read('Config.language') == 'en') ? 'en' : 'es'; ?>
                var languageSet = '<?php echo $setLang; ?>';
                var webroot = '<?php echo $this->webroot; ?>';
                function sleep(milliseconds) {
                    var start = new Date().getTime();
                    for (var i = 0; i < 1e7; i++) {
                        if ((new Date().getTime() - start) > milliseconds) {
                            break;
                        }
                    }
                }
                function validateEmail(email) {
                    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                    return re.test(email);
                }

                function setUpperNavigation(pageName) {

                    var sidebar_anchor = $('.sidebar-anchor');
                    sidebar_anchor.removeClass('active');
                    var sidebar_sub_nav_07 = $('.sidebar-sub-nav');


                    var sidebar_freegalqueues = $('.leftfqueuesclass');
                    sidebar_freegalqueues.removeClass('active');


                    var home07 = $('#home07');
                    var musicVideo07 = $('#musicVideo07');
                    var newsRelease07 = $('#newsRelease07');
                    var genre07 = $('#genre07');
                    var faq07 = $('#faq07');
                    var topmylib07 = $('.topmylib07');
                    var topustop07 = $('#topustop07');
                    var topmostpopuler07 = $('#topmostpopuler07');

                    var leftmusicVideo07 = $('#leftmusicVideo07');
                    var leftmylib07 = $('#leftmylib07');
                    var ustoplib07 = $('#ustoplib07');
                    var leftnewrelease07 = $('#leftnewrelease07');
                    var leftmyhistory07 = $('#leftmyhistory07');
                    var leftmywishlist07 = $('#leftmywishlist07');
                    var leftsavedqueues07 = $('#leftsavedqueues07');
                    var leftnowstreaming07 = $('#leftnowstreaming07');


                    if (pageName.indexOf("leftfqueues_") !== -1) {
                        var leftfqueuesclass = $('.leftfqueuesclass');
                        leftfqueuesclass.removeClass('active');

                        var leftfqueues = $('#' + pageName);
                        musicVideo07.removeClass('active');
                        newsRelease07.removeClass('active');
                        genre07.removeClass('active');
                        faq07.removeClass('active');
                        leftmusicVideo07.removeClass('active');
                        ustoplib07.removeClass('active');
                        topmylib07.removeClass('active');
                        topustop07.removeClass('active');
                        leftnewrelease07.removeClass('active');
                        leftmywishlist07.removeClass('active');
                        leftnowstreaming07.removeClass('active');
                        leftmyhistory07.removeClass('active');
                        leftsavedqueues07.removeClass('active');
                        leftmylib07.removeClass('active');
                        home07.removeClass('active');
                        leftfqueues.addClass('active');
                    }


                    if (pageName === 'home07') {
                        musicVideo07.removeClass('active');
                        newsRelease07.removeClass('active');
                        genre07.removeClass('active');
                        faq07.removeClass('active');
                        leftmusicVideo07.removeClass('active');
                        ustoplib07.removeClass('active');
                        topmylib07.removeClass('active');
                        topustop07.removeClass('active');
                        leftnewrelease07.removeClass('active');
                        leftmywishlist07.removeClass('active');
                        leftnowstreaming07.removeClass('active');
                        leftmyhistory07.removeClass('active');
                        leftsavedqueues07.removeClass('active');
                        leftmylib07.removeClass('active');
                        home07.addClass('active');
                    } else if (pageName === 'musicVideo07') {
                        home07.removeClass('active');
                        newsRelease07.removeClass('active');
                        genre07.removeClass('active');
                        faq07.removeClass('active');
                        ustoplib07.removeClass('active');
                        topmylib07.removeClass('active');
                        topustop07.removeClass('active');
                        leftnewrelease07.removeClass('active');
                        leftnowstreaming07.removeClass('active');
                        leftmywishlist07.removeClass('active');
                        leftmyhistory07.removeClass('active');
                        leftmylib07.removeClass('active');
                        leftsavedqueues07.removeClass('active');
                        musicVideo07.addClass('active');
                        leftmusicVideo07.addClass('active');
                    } else if (pageName === 'newsRelease07') {
                        musicVideo07.removeClass('active');
                        home07.removeClass('active');
                        genre07.removeClass('active');
                        faq07.removeClass('active');
                        leftmusicVideo07.removeClass('active');
                        ustoplib07.removeClass('active');
                        topmylib07.removeClass('active');
                        topustop07.removeClass('active');
                        leftnowstreaming07.removeClass('active');
                        leftsavedqueues07.removeClass('active');
                        leftmywishlist07.removeClass('active');
                        leftmyhistory07.removeClass('active');
                        leftmylib07.removeClass('active');

                        leftnewrelease07.addClass('active');
                        newsRelease07.addClass('active');
                    } else if (pageName === 'genre07') {
                        musicVideo07.removeClass('active');
                        newsRelease07.removeClass('active');
                        home07.removeClass('active');
                        faq07.removeClass('active');
                        leftmusicVideo07.removeClass('active');
                        leftmylib07.removeClass('active');
                        ustoplib07.removeClass('active');
                        topmylib07.removeClass('active');
                        topustop07.removeClass('active');
                        leftsavedqueues07.removeClass('active');
                        leftnowstreaming07.removeClass('active');
                        leftnewrelease07.removeClass('active');
                        leftmywishlist07.removeClass('active');
                        leftmyhistory07.removeClass('active');
                        genre07.addClass('active');
                    } else if (pageName === 'faq07') {
                        musicVideo07.removeClass('active');
                        newsRelease07.removeClass('active');
                        home07.removeClass('active');
                        genre07.removeClass('active');
                        leftmusicVideo07.removeClass('active');
                        ustoplib07.removeClass('active');
                        topmylib07.removeClass('active');
                        topustop07.removeClass('active');
                        leftnewrelease07.removeClass('active');
                        leftmywishlist07.removeClass('active');
                        leftsavedqueues07.removeClass('active');
                        leftnowstreaming07.removeClass('active');
                        leftmyhistory07.removeClass('active');
                        leftmylib07.removeClass('active');
                        faq07.addClass('active');
                    } else if (pageName === 'leftmusicVideo07') {
                        newsRelease07.removeClass('active');
                        genre07.removeClass('active');
                        faq07.removeClass('active');
                        home07.removeClass('active');
                        leftmylib07.removeClass('active');
                        topustop07.removeClass('active');
                        leftnewrelease07.removeClass('active');
                        topmylib07.removeClass('active');
                        leftmywishlist07.removeClass('active');
                        leftmyhistory07.removeClass('active');
                        leftsavedqueues07.removeClass('active');
                        leftnowstreaming07.removeClass('active');
                        ustoplib07.removeClass('active');
                        musicVideo07.addClass('active');
                        leftmusicVideo07.addClass('active');
                    } else if (pageName === 'leftmylib07') {
                        musicVideo07.removeClass('active');
                        newsRelease07.removeClass('active');
                        genre07.removeClass('active');
                        faq07.removeClass('active');
                        home07.removeClass('active');
                        leftmusicVideo07.removeClass('active');
                        ustoplib07.removeClass('active');
                        topustop07.removeClass('active');
                        leftmyhistory07.removeClass('active');
                        leftnowstreaming07.removeClass('active');
                        leftsavedqueues07.removeClass('active');
                        leftnewrelease07.removeClass('active');
                        leftmywishlist07.removeClass('active');
                        topmylib07.addClass('active');
                        leftmylib07.addClass('active');

                    } else if (pageName === 'ustoplib07') {
                        musicVideo07.removeClass('active');
                        newsRelease07.removeClass('active');
                        genre07.removeClass('active');
                        leftmyhistory07.removeClass('active');
                        faq07.removeClass('active');
                        home07.removeClass('active');
                        topustop07.removeClass('active');
                        leftmusicVideo07.removeClass('active');
                        leftnewrelease07.removeClass('active');
                        leftmywishlist07.removeClass('active');
                        leftsavedqueues07.removeClass('active');
                        leftnowstreaming07.removeClass('active');
                        leftmylib07.removeClass('active');
                        leftmyhistory07.removeClass('active');
                        topmylib07.addClass('active');
                        ustoplib07.addClass('active');

                    } else if (pageName === 'leftnewrelease07') {
                        musicVideo07.removeClass('active');
                        newsRelease07.removeClass('active');
                        genre07.removeClass('active');
                        faq07.removeClass('active');
                        home07.removeClass('active');
                        leftmusicVideo07.removeClass('active');
                        leftmylib07.removeClass('active');
                        ustoplib07.removeClass('active');
                        topmylib07.removeClass('active');
                        topustop07.removeClass('active');
                        leftnowstreaming07.removeClass('active');
                        leftsavedqueues07.removeClass('active');
                        leftmywishlist07.removeClass('active');
                        leftmyhistory07.removeClass('active');
                        newsRelease07.addClass('active');
                        leftnewrelease07.addClass('active');
                    } else if (pageName === 'leftmyhistory07') {
                        musicVideo07.removeClass('active');
                        newsRelease07.removeClass('active');
                        genre07.removeClass('active');
                        faq07.removeClass('active');
                        home07.removeClass('active');
                        leftmusicVideo07.removeClass('active');
                        leftmylib07.removeClass('active');
                        ustoplib07.removeClass('active');
                        topmylib07.removeClass('active');
                        leftnewrelease07.removeClass('active');
                        leftsavedqueues07.removeClass('active');
                        leftnowstreaming07.removeClass('active');
                        topustop07.removeClass('active');
                        leftmywishlist07.removeClass('active');
                        leftmyhistory07.addClass('active');
                    } else if (pageName === 'leftmywishlist07') {
                        musicVideo07.removeClass('active');
                        newsRelease07.removeClass('active');
                        genre07.removeClass('active');
                        faq07.removeClass('active');
                        home07.removeClass('active');
                        leftmusicVideo07.removeClass('active');
                        leftmylib07.removeClass('active');
                        ustoplib07.removeClass('active');
                        leftnewrelease07.removeClass('active');
                        leftmyhistory07.removeClass('active');
                        leftsavedqueues07.removeClass('active');
                        topustop07.removeClass('active');
                        leftnowstreaming07.removeClass('active');
                        topmylib07.removeClass('active');
                        leftmywishlist07.addClass('active');
                    } else if (pageName === 'topmylib07') {
                        musicVideo07.removeClass('active');
                        newsRelease07.removeClass('active');
                        genre07.removeClass('active');
                        faq07.removeClass('active');
                        home07.removeClass('active');
                        leftmusicVideo07.removeClass('active');
                        ustoplib07.removeClass('active');
                        leftnewrelease07.removeClass('active');
                        leftmyhistory07.removeClass('active');
                        leftmywishlist07.removeClass('active');
                        leftsavedqueues07.removeClass('active');
                        leftnowstreaming07.removeClass('active');
                        topustop07.removeClass('active');
                        topmostpopuler07.addClass('active');

                        sidebar_sub_nav_07.addClass('active');
                        topmylib07.addClass('active');
                        leftmylib07.addClass('active');
                    } else if (pageName === 'topustop07') {
                        musicVideo07.removeClass('active');
                        newsRelease07.removeClass('active');
                        genre07.removeClass('active');
                        faq07.removeClass('active');
                        home07.removeClass('active');
                        leftmusicVideo07.removeClass('active');
                        leftmylib07.removeClass('active');

                        leftnewrelease07.removeClass('active');
                        leftmyhistory07.removeClass('active');
                        leftnowstreaming07.removeClass('active');
                        leftmywishlist07.removeClass('active');
                        leftsavedqueues07.removeClass('active');
                        topmylib07.addClass('active');
                        topmostpopuler07.addClass('active');
                        ustoplib07.addClass('active');
                    } else if (pageName === 'leftsavedqueues07') {
                        musicVideo07.removeClass('active');
                        newsRelease07.removeClass('active');
                        genre07.removeClass('active');
                        faq07.removeClass('active');
                        home07.removeClass('active');
                        leftmusicVideo07.removeClass('active');
                        leftmylib07.removeClass('active');
                        ustoplib07.removeClass('active');
                        leftnewrelease07.removeClass('active');
                        leftmyhistory07.removeClass('active');
                        leftmywishlist07.removeClass('active');
                        topmylib07.removeClass('active');
                        leftnowstreaming07.removeClass('active');
                        leftsavedqueues07.addClass('active');

                    } else if (pageName === 'leftnowstreaming07') {
                        musicVideo07.removeClass('active');
                        newsRelease07.removeClass('active');
                        genre07.removeClass('active');
                        faq07.removeClass('active');
                        home07.removeClass('active');
                        leftmusicVideo07.removeClass('active');
                        leftmylib07.removeClass('active');
                        ustoplib07.removeClass('active');
                        leftnewrelease07.removeClass('active');
                        leftmyhistory07.removeClass('active');
                        leftmywishlist07.removeClass('active');
                        topmylib07.removeClass('active');
                        leftsavedqueues07.removeClass('active');
                        leftnowstreaming07.addClass('active');

                    }
                }

                $(document).ready(function() {
    <?php
    if ($this->Session->read('approved') && $this->Session->read('approved') === 'no')
    {
        ?>
                        $(".termsApproval")
                                .colorbox(
                                {
                                    width: "50%", inline: true, open: true,
                                    overlayClose: false, opacity: .5,
                                    noEscape: true, href: "#termsApproval_div",
                                    onOpen: function() {
                                        $(document).unbind("keydown.cbox_close");
                                    }
                                }
                        );
        <?php
    }

    if (($this->Session->read('showNotificationPopup') && $this->Session->read('showNotificationPopup') == 'no') && ($this->Session->read('approved') && $this->Session->read('approved') == 'yes') && ($this->Session->read('isLibaryExistInTimzone') && $this->Session->read('isLibaryExistInTimzone') == 1))
    {
        ?>


                        $(".notificationApproval")
                                .colorbox(
                                {
                                    width: "50%", inline: true, open: true,
                                    overlayClose: false, opacity: .5,
                                    escKey: false, noEscape: true, href: "#notificationApproval_div",
                                    onOpen: function() {
                                        $(document).unbind("keydown.cbox_close");
                                    }});
                        //close the popup 
                        $("#colorboxCloseBtn").click(function() {

                            var data = {notificationClose: 1};
                            jQuery.ajax({
                                type: "post", // Request  method: post, get
                                url: webroot + "users/saveNotification", // URL to request
                                data: data, // post data
                                success: function(response) {
                                    $.fn.colorbox.close();
                                    $('#noti_content').hide();
                                    //location.reload();
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                }
                            });
                        });
                        //save email notificaion data and close t he popup
                        $("#colorboxSubmitBtn").click(function() {

                            if (!$('#userNewsletterEmailField').val()) {
                                alert('Please enter the valid email address.');
                                return false;
                            }

                            if (!validateEmail($('#userNewsletterEmailField').val())) {
                                alert('Please enter the valid email address.');
                                return false;
                            }

                            //post the notification information





                            var pid = <?= $this->Session->read('patron') ?>;
                            var lid = <?= $this->Session->read('library') ?>;
                            var data = {notificatinEmail: $("#userNewsletterEmailField").val(), pid: pid, lid: lid};
                            $('#noti_content').html('<span style="padding-top:15px;"><b>Your subscription has been done successfully.</b></span>');
                            jQuery.ajax({
                                type: "post", // Request method: post, get
                                url: webroot + "users/saveNotification", // URL to request
                                data: data, // postdata
                                async:
                                        false,
                                success: function(response) {
                                    sleep(2000);
                                    $.fn.colorbox.close();
                                    $('#noti_content').hide();
                                    //location.reload();
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                }
                            });
                        });

        <?php
    }
    ?>
    <?php
    $userLogin = $this->Session->read("userlogin");
    if($userLogin == 'yes') {
    if (($this->Session->read('streamPopupShow') && $this->Session->read('streamPopupShow') == 'no') && ($this->Session->read('showNotificationPopup') && $this->Session->read('showNotificationPopup') == 'yes') && ($this->Session->read('approved') && $this->Session->read('approved') == 'yes'))
    {
        ?>
                        $(".streamApproval")
                                .colorbox(
                                {
                                    width: "50%", inline: true, open: true,
                                    overlayClose: false, opacity: .5,
                                    escKey: false, noEscape: true, href: "#streamApproval_div",
                                    onOpen: function() {
                                        $(document).unbind("keydown.cbox_close");
                                    }});

                        $("#colorboxOKBtn").click(function() {
                            var pid = '<?= $this->Session->read('patron') ?>';
                            var lid = <?= $this->Session->read('library') ?>;
                            var data = {pid: pid, lid: lid};
                            jQuery.ajax({
                                type: "post", // Request method: post, get
                                url: webroot + "users/savestreampopup", // URL to request
                                data: data, // postdata
                                async: false,
                                success: function(response) {
                                    sleep(2000);
                                    $.fn.colorbox.close();
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                }
                            });
                        });
        <?php
    }
    } else {
        if (($this->Session->read('streamPopupShow') && $this->Session->read('streamPopupShow') == 'no') && ($this->Session->read('approved') && $this->Session->read('approved') == 'yes'))
        {
        ?>
                        $(".streamApproval")
                                .colorbox(
                                {
                                    width: "50%", inline: true, open: true,
                                    overlayClose: false, opacity: .5,
                                    escKey: false, noEscape: true, href: "#streamApproval_div",
                                    onOpen: function() {
                                        $(document).unbind("keydown.cbox_close");
                                    }});

                        $("#colorboxOKBtn").click(function() {
                            var pid = '<?= $this->Session->read('patron') ?>';
                            var lid = <?= $this->Session->read('library') ?>;
                            var data = {pid: pid, lid: lid};
                            jQuery.ajax({
                                type: "post", // Request method: post, get
                                url: webroot + "users/savestreampopup", // URL to request
                                data: data, // postdata
                                async: false,
                                success: function(response) {
                                    sleep(2000);
                                    $.fn.colorbox.close();
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                }
                            });
                        });
        <?php
        }
    }
    ?>

                });

            </script>

            <?php
        }
        else
        {
            ?>
            <link href="<?php echo $this->webroot; ?>css/freegal_styles.php" type="text/css" rel="stylesheet" />
            <link type="text/css" rel="stylesheet" href="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/css&amp;f=jquery.autocomplete.css,colorbox.css" />
            <?php
        }



        if ($this->Session->read('lId') && $this->Session->read('lId') != '')
        {
            echo $this->Html->css('styles');
            ?>
            <link rel="shortcut icon" href="<? echo $this->webroot; ?>app/webroot/favicon.ico" />
            <link rel="icon" href="<? echo $this->webroot; ?>app/webroot/favicon.ico" />

            <script type="text/javascript">
                var webroot = '<?php echo $this->webroot; ?>';

                function showhide(flag, id)
                {

                    if (flag == "short")
                    {
                        document.getElementById("shortNews" + id).style.display = "block";
                        document.getElementById("detailsNews" + id).style.display = "none";
                    }

                    if (flag == "detail")
                    {
                        document.getElementById("shortNews" + id).style.display = "none";
                        document.getElementById("detailsNews" + id).style.display = "block";
                    }
                }

                function queueModifications()
                {
                    document.getElementById('name').value = document.getElementById('hid_playlist_name').value;
                    document.getElementById('description').value = document.getElementById('hid_description').value;
                    document.getElementById('rqPlid').value = document.getElementById('hid_Plid').value;
                    document.getElementById('dqPlid').value = document.getElementById('hid_Plid').value;
                    queueName = $('#hid_playlist_name').val();
                    $('.confirm-text span').text(queueName);
                    $('.rename-queue-dialog-box span').text(queueName);
                }
            </script>

            <?php
            $libraryInfo = $library->getLibraryDetails($this->Session->read('lId'));
        }
        ?>

        <style>
            #slideshow a { display: none }
            #slideshow a.first { display: block }
            #featured_artist a { display: none }
            #featured_artist a.first { display: block }
            #newly_added a { display: none }
            #newly_added a.first { display: block }


            .player {

                position: fixed;
                bottom: 0;
                width: 100%;
                height: 100px;
                overflow: hidden;


            }
        </style>



        <noscript>
        <?php
        if ($this->params['action'] != 'aboutus')
        {
            echo $html->meta(null, null, array('http-equiv' => 'refresh', 'content' => "0; url=" . $this->webroot . "homes/aboutus/js_err"), false);
        }
        ?>
        </noscript>

        <?php
        //echo "URI: ". strstr($_SERVER['REQUEST_URI'], '/videos/details/'); die;

        if ($_SERVER['REQUEST_URI'] == '/index' || $_SERVER['REQUEST_URI'] == '')
        {
            $body_class = 'page-news';
        }
        else if ($_SERVER['REQUEST_URI'] == '/videos')
        {
            $body_class = 'page-videos';
        }
        else if ((strstr($_SERVER['REQUEST_URI'], '/videos/details/')) != '')
        {
            $body_class = 'page-videos-details';
        }
        else if ($_SERVER['REQUEST_URI'] == '/homes/my_lib_top_10')
        {
            $body_class = 'page-my-lib-top-10';
        }
        else if ($_SERVER['REQUEST_URI'] == '/homes/us_top_10')
        {
            $body_class = 'page-us-top-10';
        }
        else if ($_SERVER['REQUEST_URI'] == '/homes/new_releases')
        {
            $body_class = 'page-new-releases';
        }
        else if ($_SERVER['REQUEST_URI'] == '/questions')
        {
            $body_class = 'page-questions';
        }
        else if ($_SERVER['REQUEST_URI'] == '/genres/view')
        {
            $body_class = 'page-genres';
        }
        else if ($_SERVER['REQUEST_URI'] == '/homes/my_history')
        {
            $body_class = 'page-my-history';
        }
        else if ((strstr($_SERVER['REQUEST_URI'], '/queuelistdetails/queue_details')) != '')
        {
            $body_class = 'page-queue-details';
        }
        else if ((strstr($_SERVER['REQUEST_URI'], '/queuelistdetails/now_streaming')) != '')
        {
            $body_class = 'page-now-streaming';
        }
        else if ($_SERVER['REQUEST_URI'] == '/homes/my_wishlist')
        {
            $body_class = 'page-my-wishlist';
        }
        else if ((strstr($_SERVER['REQUEST_URI'], '/search/index')) != '')
        {
            $body_class = 'page-search-index';
        }
        else if ((strstr($_SERVER['REQUEST_URI'], '/artists/view')) != '')
        {
            $body_class = 'page-artists-view';
        }
        else if ((strstr($_SERVER['REQUEST_URI'], '/users/')) != '')
        {
            $body_class = 'page-users-login';
        }
        else
        {
            $body_class = 'page-news';
        }
        ?> 
    </head>

    <body class="<?php echo $body_class; ?>">


        <div id="audioPixel"><div id="audioflash"></div></div>
        <?php $session->flash(); ?>
        <a class='upgradeFlash' href="#"></a>
        <div style="display:none;">
            <div id="upgradeFlash_div">   
                This site requires Flash player version 9 or more to play the sample audio files.
                Please <a class="orange_link"  href="http://www.adobe.com/support/flashplayer/downloads.html" target="_blank">click here</a> 
                to upgrade your Flash Player.<br /><br />
            </div>
        </div>

        <?php
        if ($this->Session->read('approved') && $this->Session->read('approved') == 'no')
        {
            ?>
            <style>#cboxClose{display:none !important;}</style>

            <a class='termsApproval' href="#"></a>
            <div style="display:none;">
                <div id="termsApproval_div">
                    <div id="loaderDiv" style="display:none;position:absolute;width:100%;text-align:center;top:125px;bottom:0;left:305px;right:0;z-index:10000;">
                        <?php echo $html->image('ajax-loader-big.gif', array('alt' => 'Loading...')); ?>
                    </div>

                    <b>You need to accept the terms and conditions to browse the site.</b><br />
                    <div style="overflow:auto;height:200px;border: 1px solid #ccc; margin: 10px; padding: 5px; text-align: justify;">
                        <?php echo $page->getPageContent('terms'); ?>
                    </div>

                    <br />
                    <input type="button" value="Accept" 
                           onclick="Javascript: approvePatron('<?php echo $this->Session->read('library'); ?>', '<?php echo base64_encode($this->Session->read('patron')); ?>');"> 
                    <input type="button" value="Deny" onclick="Javascript: history.back();">
                </div>
            </div>
            <?php
        }

        if (($this->Session->read('showNotificationPopup') && $this->Session->read('showNotificationPopup') == 'no') && ($this->Session->read('approved') && $this->Session->read('approved') == 'yes') && ($this->Session->read('isLibaryExistInTimzone') && $this->Session->read('isLibaryExistInTimzone') == 1))
        {
            ?>
            <style>#cboxClose{display:none !important;}</style>

            <a class='notificationApproval' href="#"></a>
            <div style="display:none;">
                <div id="notificationApproval_div">
                    <span id="noti_content">

                        <div id="loaderDiv" style="display:none;position:absolute;width:100%;text-align:center;top:125px;bottom:0;left:305px;right:0;z-index:10000;">
                            <?php echo $html->image('ajax-loader-big.gif', array('alt' => 'Loading...')); ?>
                        </div>
                        <b>Email Notification</b><br />

                        <div style="height:100px;border: 1px solid #ccc; margin: 10px; padding: 5px; text-align: justify;">
                            Please add your email address here to receive twice-weekly email reminders of your available downloads.
                            <br /><br /><br />
                            <div >
                                <b>*Email :</b>
                                <input type='text' style="width:210px;" name='emailNotification' id='userNewsletterEmailField'>
                            </div>
                        </div>
                        <br />
                        <input type="button" value="Submit" id="colorboxSubmitBtn"> <input type="button" value="Cancel" id="colorboxCloseBtn" >

                    </span>
                </div>


            </div>
            <?php
        }
        ?>      

        <?php
        $userLogin = $this->Session->read("userlogin");
        if($userLogin == 'yes') {
        if (($this->Session->read('streamPopupShow') && $this->Session->read('streamPopupShow') == 'no') && ($this->Session->read('showNotificationPopup') && $this->Session->read('showNotificationPopup') == 'yes') && ($this->Session->read('approved') && $this->Session->read('approved') == 'yes'))
        {
            ?>
            <style>#cboxClose{display:none !important;}</style>
            <a class='streamApproval' href="#"></a>
            <div style="display:none;">
                <div id="streamApproval_div">
                    <span id="stream_content">
                        <div id="loaderDiv" style="display:none;position:absolute;width:100%;text-align:center;top:0;bottom:0;left:0;right:0;z-index:10000;">
                            <?php echo $html->image('ajax-loader-big.gif', array('alt' => 'Loading...')); ?>
                        </div>
                        <?php echo $page->getPageContent('stream_123'); ?>
                        <br />
                        <center><input type="button" value="OK" id="colorboxOKBtn"></center>
                    </span>

                </div>
            </div>

        <?php }
        } else {
        if (($this->Session->read('streamPopupShow') && $this->Session->read('streamPopupShow') == 'no') && ($this->Session->read('approved') && $this->Session->read('approved') == 'yes'))
        {
            ?>
            <style>#cboxClose{display:none !important;}</style>
            <a class='streamApproval' href="#"></a>
            <div style="display:none;">
                <div id="streamApproval_div">
                    <span id="stream_content">
                        <div id="loaderDiv" style="display:none;position:absolute;width:100%;text-align:center;top:0;bottom:0;left:0;right:0;z-index:10000;">
                            <?php echo $html->image('ajax-loader-big.gif', array('alt' => 'Loading...')); ?>
                        </div>
                        <?php echo $page->getPageContent('stream_123'); ?>
                        <br />
                        <center><input type="button" value="OK" id="colorboxOKBtn"></center>
                    </span>

                </div>
            </div>

        <?php }
        }
        ?>

        <div id="border-background" >
            <div id="container">
                <?php echo $this->element('header'); ?>
                <div id="content">
                    <?php
                    if ($this->Session->read('library') && $this->Session->read('library') != '')
                    {
                        echo $this->element('navigation');
                    }
                    echo $content_for_layout;
                    ?>
                </div>
                <br class="clr">
                <input type="hidden" value="<?php echo Configure::read('App.Script');   ?>" id="Scripts_Path" />
            </div>
            <?php echo $this->element('footer'); ?>

        </div>
    </div> <!-- body - background -->
</body>
</html>
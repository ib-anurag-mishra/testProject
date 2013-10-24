<?php /* <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>

        <?php
        echo $this->Html->charset();
        echo $this->Html->meta(array('http-equiv' => "X-UA-Compatible", 'content' => "IE=edge,chrome=1"));
        echo $this->Html->meta('icon');
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


        <?php
        echo $this->Html->css(
                array(
                    'freegal_styles',
                    'jquery.autocomplete',
                    'colorbox',
                )
        );



        echo $javascript->link(
                array(
                    'jquery-1.10.2.min',
                    'modernizr.custom',
                    'lazyload',
                    'jquery.colorbox',
                    'jquery.cycle.all',
                    'jquery.autocomplete',
                    
                    'jquery.history',
                    'ajaxify-html5',
                    'site',             
                    
                    'audioPlayer',                    
                    'freegal',
                    'recent-downloads',
                    'search-results',
                    
                    'swfobject.js'
                )
        );

        


        if ($this->Session->read('library') && $this->Session->read('library') != '')
        {
            $libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
            ?>
        
            <script type="text/javascript">
                
                <?php $setLang = ($this->Session->read('Config.language') == 'en') ? 'en' : 'es'; ?>
                    
                var languageSet = '<?php echo $setLang; ?>';
                var webroot = '<?php echo $this->webroot; ?>';
                
                function validateEmail(email) {
                    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                    return re.test(email);
                }

                function setUpperNavigation(pageName) {


                    var home07 = $('#home07');
                    var musicVideo07 = $('#musicVideo07');
                    var newsRelease07 = $('#newsRelease07');
                    var genre07 = $('#genre07');
                    var faq07 = $('#faq07');

                    if (pageName == 'home07') {
                        musicVideo07.removeClass('active');
                        newsRelease07.removeClass('active');
                        genre07.removeClass('active');
                        faq07.removeClass('active');
                        home07.addClass('active');
                    } else if (pageName == 'musicVideo07') {
                        home07.removeClass('active');
                        newsRelease07.removeClass('active');
                        genre07.removeClass('active');
                        faq07.removeClass('active');
                        musicVideo07.addClass('active');
                    } else if (pageName == 'newsRelease07') {
                        musicVideo07.removeClass('active');
                        home07.removeClass('active');
                        genre07.removeClass('active');
                        faq07.removeClass('active');
                        newsRelease07.addClass('active');
                    } else if (pageName == 'genre07') {
                        musicVideo07.removeClass('active');
                        newsRelease07.removeClass('active');
                        home07.removeClass('active');
                        faq07.removeClass('active');
                        genre07.addClass('active');
                    } else if (pageName == 'faq07') {
                        musicVideo07.removeClass('active');
                        newsRelease07.removeClass('active');
                        home07.removeClass('active');
                        genre07.removeClass('active');
                        faq07.addClass('active');
                    }
                }


                $(document).ready(function() {
                        <?php
                        if ($this->Session->read('approved') && $this->Session->read('approved') == 'no')
                        {
                            ?>
                                    $(".termsApproval")
                                            .colorbox(
                                            {
                                                width: "50%", inline: true, open: true, overlayClose: false, opacity: .5, noEscape: true, href: "#termsApproval_div",
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
                                function sleep(milliseconds) {
                                    var start = new Date().getTime();
                                    for (var i = 0; i < 1e7; i++) {
                                        if ((new Date().getTime() - start) > milliseconds) {
                                            break;
                                        }
                                    }
                                }



                                $(".notificationApproval").colorbox({width: "50%", inline: true, open: true, overlayClose: false, opacity: .5, noEscape: true, href: "#notificationApproval_div", onOpen: function() {
                                        $(document).unbind("k e ydown.cbox_close");
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
                                            sleep(1000);
                                            $.fn.colorbox.close();
                                        },
                                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                                        }
                                    });
                                });

                            <?php
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



        </style>



        <noscript>
            <?php
            if ($this->params['action'] != 'aboutus')
            {
                echo $html->meta(null, null, array('http-equiv' => 'refresh', 'content' => "0.1;url=" . $this->webroot . "homes/aboutus/js_err"), false);
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
                                            <div style="display:none;">

                                            </div>
                                            <?php
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
                                            </div>
                                            <?php echo $this->element('footer');
                                            ?>

                                        </div>
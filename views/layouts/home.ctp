<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php __('Freegal Music : Your New Music Library :'); ?>
            <?php
            if ($title_for_layout == "Homes") {
                echo substr($title_for_layout, 0, -1);
            } else {
                echo $title_for_layout;
            }
            ?>
        </title>
        
    
<!--            <script lenguage="javascript" src='http://code.jquery.com/jquery-1.10.2.min.js'></script>   -->
  
            
        <?php
            echo $javascript->link('jquery-1.10.2.min');
            echo $this->Html->meta('icon');
            //echo $javascript->link('ImageDisableRightClick');
            echo $this->Html->css('freegal_styles');
            echo $this->Html->css('jquery.autocomplete');
            echo $this->Html->css('colorbox');
            // echo $javascript->link('jquery.min');         
          echo $javascript->link('jquery.cycle.all');
          echo $javascript->link('curvycorners');
          echo $javascript->link('swfobject');
          echo $javascript->link('audioPlayer');
          echo $javascript->link('freegal');
          // echo $javascript->link('jquery.bgiframe');
          echo $javascript->link('jquery.autocomplete');          
          echo $javascript->link('recent-downloads');
          echo $javascript->link('search-results');
           echo $javascript->link('jquery.colorbox');
        
        
            if($this->Session->read('library') && $this->Session->read('library') != '')
		{
			$libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
	?>
			<script type="text/javascript">
                          
				$(document).ready(function() {
                                                
					<?php
					if($this->Session->read('approved') && $this->Session->read('approved') == 'no')
					{
					?>// alert("HI");
						$(".termsApproval").colorbox({width:"50%", inline:true, open:true, overlayClose:false, opacity:.5, noEscape: true, href:"#termsApproval_div", onOpen:function(){$(document).unbind("keydown.cbox_close");}});
                                                
                                                
					<?php }	?>
				});
                                
                                <?php 

if ($this->Session->read('Config.language') == 'en') {
    $setLang = 'en';
} else {
    $setLang = 'es';
}

?>
				var languageSet = '<?php echo $setLang; ?>';
                               	var webroot = '<?php echo $this->webroot; ?>';
				var params = {allowscriptaccess:"always", menu:"false", bgcolor:"000000"};
				var attributes = { id: "audioplayer" }; 
				swfobject.embedSWF("<?php echo $this->webroot; ?>swf/audioplayer.swf", "audioflash", "1", "0", "9.0.0", "<?php echo $this->webroot; ?>swf/xi.swf", {}, params, attributes);
                                      
                                            
          <?php 
               if(($this->Session->read('showNotificationPopup') && $this->Session->read('showNotificationPopup') == 'no') 
                       && ($this->Session->read('approved') && $this->Session->read('approved') == 'yes') 
                       && ($this->Session->read('isLibaryExistInTimzone') && $this->Session->read('isLibaryExistInTimzone') == 1)){ 
           ?>  
           
               function sleep(milliseconds) {
                    var start = new Date().getTime();
                    for (var i = 0; i < 1e7; i++) {
                        if ((new Date().getTime() - start) > milliseconds){
                        break;
                        }
                    }
               }
               $(document).ready(function() {
               
               $(".notificationApproval").colorbox({width:"50%", inline:true, open:true, overlayClose:false, opacity:.5, noEscape: true, href:"#notificationApproval_div", onOpen:function(){$(document).unbind("keydown.cbox_close");}});
                                           
                //close the popup 
               $("#colorboxCloseBtn").click(function() { 
                   
                   var data = {notificationClose: 1};
                    jQuery.ajax({
                            type: "post",  // Request method: post, get
                            url: webroot+"users/saveNotification", // URL to request
                            data: data,  // post data
                            success: function(response) {
                                                $.fn.colorbox.close();                                  
                            },
                            error:function (XMLHttpRequest, textStatus, errorThrown) {}
                    }); 
                   
               });
                
               //save email notificaion data and close the popup
               $("#colorboxSubmitBtn").click(function() { 

                            if(!$('#userNewsletterEmailField').val()){
                                 alert('Please enter the valid email address.');
                                 return false;
                            }

                            if(!validateEmail($('#userNewsletterEmailField').val())){
                                alert('Please enter the valid email address.');
                                return false;
                            }

                            //post the notification information





                            var pid = <?=$this->Session->read('patron')?>;
                            var lid = <?=$this->Session->read('library')?>;
                            var data = {notificatinEmail: $("#userNewsletterEmailField").val(), pid: pid,lid:lid};
                            $('#noti_content').html('<span style="padding-top:15px;"><b>Your subscription has been done successfully.</b></span>');
                            jQuery.ajax({
                                    type: "post",  // Request method: post, get
                                    url: webroot+"users/saveNotification", // URL to request
                                    data: data,  // post data
                                    async: false,
                                    success: function(response) {
                                        sleep(1000);                          
                                        $.fn.colorbox.close();                                  
                                    },
                                    error:function (XMLHttpRequest, textStatus, errorThrown) {}
                            });  

                   });
                   });
	<?php
         } 
        ?>  
			</script>
			<style>
				<?php 
				if($this->Session->read('approved') && $this->Session->read('approved') == 'no')
				{
				?>
					#cboxClose{display:none !important;}
				<?php
				}
				?>
                                                                          
          <?php
               if(($this->Session->read('showNotificationPopup') && $this->Session->read('showNotificationPopup') == 'no') && ($this->Session->read('approved') && $this->Session->read('approved') == 'yes') && ($this->Session->read('isLibaryExistInTimzone') && $this->Session->read('isLibaryExistInTimzone') == 1)){ 
           ?>
					#cboxClose{display:none !important;}
				<?php
				} 
				?>
                                        
                               
					
				
				#slideshow a { display: none }
				#slideshow a.first { display: block }
				#featured_artist a { display: none }
				#featured_artist a.first { display: block }
				#newly_added a { display: none }
				#newly_added a.first { display: block }
			</style>
	<?php
		}
		else {
	?>
			<link href="<?php echo $this->webroot; ?>css/freegal_styles.php" type="text/css" rel="stylesheet" />
			<link type="text/css" rel="stylesheet" href="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/css&amp;f=jquery.autocomplete.css,colorbox.css" />
	<?php
		} 
	?>
        
        
        		

        

        <?php
     
//		echo $javascript->link('qtip');
//		echo $javascript->link('qtip_add');
//		echo $scripts_for_layout;
        if ($this->Session->read('Config.language') == 'en') {
            $setLang = 'en';
        } else {
            $setLang = 'es';
        }      
        if ($this->Session->read('lId') && $this->Session->read('lId') != '') {
            $libraryInfo = $library->getLibraryDetails($this->Session->read('lId'));
            ?>

       <!--<script src="<? echo $this->webroot; ?>app/webroot/js/jquery.js"></script> -->
              
                            

<!--            <link rel="stylesheet" type="text/css" href="<? echo $this->webroot; ?>app/webroot/css/forms.css" />                   -->
            <link rel="shortcut icon" href="<? echo $this->webroot; ?>app/webroot/favicon.ico">
            <link rel="icon" href="<? echo $this->webroot; ?>app/webroot/favicon.ico">
            <link rel="stylesheet/less" type="text/css" href="<? echo $this->webroot; ?>app/webroot/css/styles.less" />
            <!--link rel="stylesheet/less" type="text/css" href="<? echo $this->webroot; ?>app/webroot/css/template.less" /> -->
            
            
            <script src="<? echo $this->webroot; ?>app/webroot/js/less.js"></script>          
            <script src="<? echo $this->webroot; ?>app/webroot/js/modernizr.custom.js"></script>  
            
            <link rel="stylesheet" type="text/css" href="<? echo $this->webroot; ?>app/webroot/js/mediaelement/mep-feature-playlist-custom.css" />
            <link rel="stylesheet" type="text/css" href="<? echo $this->webroot; ?>app/webroot/js/mediaelement/mediaelementplayer-custom.css" />
            <script src="<? echo $this->webroot; ?>app/webroot/js/mediaelement/mediaelement-and-player.min.js"></script>
            <script src="<? echo $this->webroot; ?>app/webroot/js/mediaelement/mep-feature-playlist-custom.js"></script>
           
           

               <script type="text/javascript">
                var webroot = '<?php echo $this->webroot; ?>';
                function showhide(flag, id)
                {	   		

                  if(flag=="short")
                  {
                     document.getElementById("shortNews"+id).style.display="block";
                     document.getElementById("detailsNews"+id).style.display="none";
                  }

                  if(flag=="detail")
                  {
                     document.getElementById("shortNews"+id).style.display="none";
                     document.getElementById("detailsNews"+id).style.display="block";
                  }
                }
       
       
                function queueModifications()
                {                    
                    document.getElementById('name').value           =   document.getElementById('hid_playlist_name').value;
                    document.getElementById('description').value    =   document.getElementById('hid_description').value;
                    document.getElementById('rqPlid').value         =   document.getElementById('hid_Plid').value;
                    document.getElementById('dqPlid').value         =   document.getElementById('hid_Plid').value;
                   
                }
       
       
       
        </script>     
            

                    
                    <style>
    <?php 
    if ($this->Session->read('approved') && $this->Session->read('approved') == 'no') {
        ?>
                            #cboxClose{display:none !important;}
        <?php
    }
    ?>
                                                                                  
    <?php
    if (($this->Session->read('showNotificationPopup') && $this->Session->read('showNotificationPopup') == 'no') && ($this->Session->read('approved') && $this->Session->read('approved') == 'yes') && ($this->Session->read('isLibaryExistInTimzone') && $this->Session->read('isLibaryExistInTimzone') == 1)) {
        ?>
                            #cboxClose{display:none !important;}
        <?php
    }
    ?>
                                                
                                       
        					
        				
                        #slideshow a { display: none }
                        #slideshow a.first { display: block }
                        #featured_artist a { display: none }
                        #featured_artist a.first { display: block }
                        #newly_added a { display: none }
                        #newly_added a.first { display: block }
                    </style>
                    <?php
                }
                ?>
                <noscript>
                    <?php
                    if ($this->params['action'] != 'aboutus') {
                        echo $html->meta(null, null, array('http-equiv' => 'refresh', 'content' => "0.1;url=" . $this->webroot . "homes/aboutus/js_err"), false);
                    }
                    ?>
                </noscript>
                <script type="text/javascript">
                    $().ready(function() {
                        var tmpcookie = new Date();
                        chkcookie = (tmpcookie.getTime() + '');
                        document.cookie = "chkcookie=" + chkcookie;
                        if (document.cookie.indexOf(chkcookie,0) < 0) {
<?php if (!isset($this->params['pass']['0'])) { ?>				
                location.href = "<?php echo $this->webroot; ?>homes/aboutus/cookie_err";
<?php } ?>
        }
    });
                
                              
    function validateEmail(email) { 
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }    
                </script>
            <?php   
        //echo "URI: ". strstr($_SERVER['REQUEST_URI'], '/videos/details/'); die;
          
        if($_SERVER['REQUEST_URI']=='/index' || $_SERVER['REQUEST_URI']=='')
        {
            $body_class =   'page-news';
        }
        else if($_SERVER['REQUEST_URI']=='/videos')
        {
            $body_class =   'page-videos'; 
        }
        else if((strstr($_SERVER['REQUEST_URI'], '/videos/details/')) != '')
        {
            $body_class =   'page-videos-details'; 
        }
        else if($_SERVER['REQUEST_URI']=='/homes/my_lib_top_10')
        {
            $body_class =   'page-my-lib-top-10'; 
        }
        else if($_SERVER['REQUEST_URI']=='/homes/us_top_10')
        {
            $body_class =   'page-us-top-10'; 
        }
        else if($_SERVER['REQUEST_URI']=='/homes/new_releases')
        {
            $body_class =   'page-new-releases'; 
        }
        else if($_SERVER['REQUEST_URI']=='/questions')
        {
            $body_class =   'page-questions'; 
        }
        else if($_SERVER['REQUEST_URI']=='/genres/view')
        {
            $body_class =   'page-genres'; 
        }
         else if($_SERVER['REQUEST_URI']=='/homes/my_history')
        {
            $body_class =   'page-my-history'; 
        }         
         else if((strstr($_SERVER['REQUEST_URI'], '/queuelistdetails/queue_details')) != '')
        {
            $body_class =   'page-queue-details'; 
        }        
        else if((strstr($_SERVER['REQUEST_URI'], '/queuelistdetails/now_streaming')) != '')
        {
            $body_class =   'page-now-streaming'; 
        }
         else if($_SERVER['REQUEST_URI']=='/homes/my_wishlist')
        {
            $body_class =   'page-my-wishlist'; 
        }          
        else if((strstr($_SERVER['REQUEST_URI'], '/search/index')) != '')
        {
            $body_class =   'page-search-index'; 
        }
        else if((strstr($_SERVER['REQUEST_URI'], '/artists/view')) != '')        
        {
            $body_class =   'page-artists-view'; 
        }
        else if((strstr($_SERVER['REQUEST_URI'], '/users/')) != '')             
        {
            $body_class =   'page-users-login'; 
        }
        else
        {
            $body_class =   'page-news';
        }
        
            
?> 
                </head>
                <body class="<?php echo $body_class; ?>">
                    <!--[if lt IE 7]>
                    <div style='border: 1px solid #F7941D; background: #FEEFDA; text-align: center; clear: both; height: 75px; position: relative;'>
                    <div style='position: absolute; right: 3px; top: 3px; font-family: courier new; font-weight: bold;'>
                                    <a href='#' onclick='javascript:this.parentNode.parentNode.style.display="none"; return false;'>
                                            <img src='http://www.ie6nomore.com/files/theme/ie6nomore-cornerx.jpg' style='border: none;' alt='Close this notice'/>
                                    </a>
                            </div>
                    <div style='width: 640px; margin: 0 auto; text-align: left; padding: 0; overflow: hidden; color: black;'>
                            <div style='width: 75px; float: left;'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-warning.jpg' alt='Warning!'/></div>
                            <div style='width: 275px; float: left; font-family: Arial, sans-serif;'>
                                    <div style='font-size: 14px; font-weight: bold; margin-top: 12px;'>You are using an outdated browser</div>
                                    <div style='font-size: 12px; margin-top: 6px; line-height: 12px;'>For a better experience using this site, please upgrade to a modern web browser.</div>
                            </div>
                            <div style='width: 75px; float: left;'>
                                            <a href='http://www.firefox.com' target='_blank'>
                                                    <img src='http://www.ie6nomore.com/files/theme/ie6nomore-firefox.jpg' style='border: none;' alt='Get Firefox 3.5'/>
                                            </a>
                                    </div>
                            <div style='width: 75px; float: left;'>
                                            <a href='http://www.browserforthebetter.com/download.html' target='_blank'>
                                                    <img src='http://www.ie6nomore.com/files/theme/ie6nomore-ie8.jpg' style='border: none;' alt='Get Internet Explorer 8'/>
                                            </a>
                                    </div>
                            <div style='width: 73px; float: left;'>
                                            <a href='http://www.apple.com/safari/download/' target='_blank'>
                                                    <img src='http://www.ie6nomore.com/files/theme/ie6nomore-safari.jpg' style='border: none;' alt='Get Safari 4'/>
                                            </a>
                                    </div>
                            <div style='float: left;'>
                                            <a href='http://www.google.com/chrome' target='_blank'>
                                                    <img src='http://www.ie6nomore.com/files/theme/ie6nomore-chrome.jpg' style='border: none;' alt='Get Google Chrome'/>
                                            </a>
                                    </div>
                    </div>
                    </div>
                    <![endif]-->
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
                    if ($this->Session->read('approved') && $this->Session->read('approved') == 'no') {
                        ?>
                        <a class='termsApproval' href="#"></a>
                        <div style="display:none;">
                            <div id="termsApproval_div">
                                <div id="loaderDiv" style="display:none;position:absolute;width:100%;text-align:center;top:125px;bottom:0;left:305px;right:0;z-index:10000;">
                                    <?php echo $html->image('ajax-loader-big.gif', array('alt' => 'Loading...')); ?>
                                </div>
                                <b>You need to accept the terms and conditions to browse the site.</b><br />
                                <div style="overflow:auto;height:200px;border: 1px solid #ccc; margin: 10px; padding: 5px; text-align: justify;"><?php echo $page->getPageContent('terms'); ?></div><br />
                                <input type="button" value="Accept" onclick="Javascript: approvePatron('<?php echo $this->Session->read('library'); ?>','<?php echo $this->Session->read('patron'); ?>');"> <input type="button" value="Deny" onclick="Javascript: history.back();">
                                        </div>
                                        </div>
                                    <?php } ?>

                                    <?php
                                    if (($this->Session->read('showNotificationPopup') && $this->Session->read('showNotificationPopup') == 'no') && ($this->Session->read('approved') && $this->Session->read('approved') == 'yes') && ($this->Session->read('isLibaryExistInTimzone') && $this->Session->read('isLibaryExistInTimzone') == 1)) {
                                        ?>
                                        <a class='notificationApproval' href="#"></a>
                                        <div style="display:none;">
                                            <div id="notificationApproval_div">
                                                <span id="noti_content">
                                                    <div id="loaderDiv" style="display:none;position:absolute;width:100%;text-align:center;top:125px;bottom:0;left:305px;right:0;z-index:10000;">
                                                        <?php echo $html->image('ajax-loader-big.gif', array('alt' => 'Loading...')); ?>
                                                    </div>
                                                    <b>Email Notification</b><br />
                                                    <div style="height:100px;border: 1px solid #ccc; margin: 10px; padding: 5px; text-align: justify;">Please add your email address here to receive twice-weekly email reminders of your available downloads.<br /><br /><br /><div ><b>*Email :</b>&nbsp;&nbsp;<input type='text' style="width:210px;" name='emailNotification' id='userNewsletterEmailField'></div></div><br />
                                                    <input type="button" value="Submit" id="colorboxSubmitBtn"> <input type="button" value="Cancel" id="colorboxCloseBtn" >
                                                            </span>

                                                            </div>
                                                            </div>

                                                        <?php 
                                                        
                                                        }
                                                        ?>

                                                        <div id="border-background" >
                                                            <div id="container">
                                                                <?php echo $this->element('header'); ?>
                                                                <div id="content">
                                                                    <?php
                                                                    if ($this->Session->read('library') && $this->Session->read('library') != '') {
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

                                                    </body>
                                                        </html>
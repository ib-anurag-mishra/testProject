<?php
/*
 File Name : my_account.ctp
 File Description : view page for my_account
 Author : m68interactive
 */
?>
<style>
.txt-my-history {
	background: url("../img/<?php echo $this->Session->read('Config.language'); ?>/my_account.png") no-repeat scroll 0 0 transparent;
    height: 62px;
    left: 35px;
    overflow: hidden;
    position: relative;
    text-indent: -9999px;
    width: 228px;
}

</style>


<?php echo $session->flash();?>
<?php
function ieversion()
{
	  ereg('MSIE ([0-9]\.[0-9])',$_SERVER['HTTP_USER_AGENT'],$reg);
	  if(!isset($reg[1])) {
		return -1;
	  } else {
		return floatval($reg[1]);
	  }
}
$ieVersion =  ieversion();
?>
<section class="my-account-page">
<div class="breadCrumb">
<?php
	$html->addCrumb(__('My Account', true), '/users/my_account');
	echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
?>
</div>
<br class="clr">
<header>
        <h2>My Account</h2>

</header>
<?php
    $this->pageTitle = 'My Account';
    echo $session->flash();
	echo '<br class="clr">';
    
    
    
?>
		<div class="forms-wrapper">
			<div class="account-info-wrapper">
				<h3>Account Information</h3>
                                    <?php echo $this->Form->create('User', array( 'controller' => 'User','action' => 'my_account')); ?>
                                    <?php if( isset($getData) && (count($getData) > 0) ) { ?>
                        		<?php echo $this->Form->hidden( 'id', array( 'label' => false ,'value' => $getData['User']['id'])); ?>
                                        <div>
                                        <?php echo $this->Form->label(__('First Name', true));?>
					<?php echo $this->Form->input('first_name', array('label' => false,'type' => 'text', 'value' => $getData['User']['first_name'], 'div' => false, 'class' => 'form_fields') ); ?>
                                        </div>    
                                        <div>
                                        <?php echo $this->Form->label(__('Last Name', true));?>
					<?php echo $this->Form->input( 'last_name', array('label' => false ,'type' => 'text','value' => $getData['User']['last_name'], 'div' => false, 'class' => 'form_fields')); ?>
                                        </div>    
                                        <div>
                                        <?php echo $this->Form->label(__('Email', true));?>
					<?php echo $this->Form->input( 'email', array( 'label' => false ,'type' => 'text','value' => $getData['User']['email'], 'div' => false, 'class' => 'form_fields', 'readonly' => true)); ?>
                                        </div>
                                        <div>    
                                        <?php echo $this->Form->label(__('Password', true));?>
					<?php echo $this->Form->input('password', array('label' => false,'value' => '','type' => 'password', 'div' => false, 'class' => 'form_fields') ); ?>
                                        </div>
                                        <div>
                                        <input type="button" value="<?php __('Save')?>" id="btnMyAccount" />
                                        </div>
                                        <?php echo $this->Form->end(); ?>
                       </div>

<?php }  ?>
   <?php
        if(isset($notificationShow) && $notificationShow == 1){
?>
                    
			<div class="email-notification-wrapper">
				<h3>Email Notification</h3>
                                <?php echo $this->Form->create('User', array( 'controller' => 'User','action' => 'manage_notification')); ?>
                                <div>
                                <?php echo $this->Form->checkbox('sendNewsLetterCheck', array('label' => false, 'div' => false, 'class' => 'form_fields', 'checked' => $notificationAlreadySave)); ?>
                                Add your email address here to receive twice-weekly email reminders of your available downloads.
                                </div>
                                <div id="show_newsletterboxField" style="display:none;">
                                <?php echo $this->Form->label('Notification Email');?>
                                <?php echo $this->Form->input('NewsletterEmail',array('label' => false ,'value' => $notificationEmail, 'div' => false, 'class' => 'form_fields'));?>
                                </div>    
                                <div>
                                <input type="submit" name="notification_submit" onclick="return checkEmailValue()" value="<?php __('Save')?>" />
                                </div>
                                <?php echo $this->Form->end(); ?>
                        </div>
                </div>
</section>


<?php
        
        }
        
         if(isset($notificationShow) && $notificationShow == 1){
?>
<script type="text/javascript">
    $(function() {  
        
        <?php 
        
        if($notificationAlreadySave === 'true'){
            ?>
                $("#show_newsletterboxField").show();  
               
                
                <?php
        }
        
        ?>
        
        
        $('#UserSendNewsLetterCheck').click(function(){	           
            var isChecked = $('#UserSendNewsLetterCheck:checked').val()?true:false;           
            if(isChecked){               
                $("#show_newsletterboxField").show();  
                
            }else{
                $("#show_newsletterboxField").hide();
                
            }            
        });
        
        $('#btnMyAccount').click(function(){
            alert("Here");
            var UFirstName='';
            var ULastName='';
            var UEmail='';
            var UPassword='';
           /*var contentSelector = '.content,article:first,.article:first,.post:first';
           var $content = $(contentSelector).filter(':first');
           var $body = $(document.body);
           // Ensure Content
            if ($content.length === 0) {
                $content = $body;
            }

           var q = $('#search-text').val();
           var type = $('#master-filter').val();
           */
           if($('#UserFirstName').val()){
               UFirstName=$('#UserFirstName').val();
           }
           if($('#UserLastName').val()){
               ULastName=$('#UserLastName').val();
           }
           if($('#UserEmail').val()){
               UEmail=$('#UserEmail').val();
           }
           if($('#UserPassword').val()){
               UPassword=$('#UserPassword').val();
           }
           var loading_div = "<div class='loader'>";
                loading_div += "</div>";
                $('.content').append(loading_div);

           // Start Fade Out
           // Animating to opacity to 0 still keeps the element's height intact
           // Which prevents that annoying pop bang issue when loading in new content
           $content.animate({opacity: 0}, 800);

/*
           $.ajax({
               url:'/users/my_account',
               method:'post',
               data:{'data[User][first_name]':UFirstName,'data[User][last_name]':ULastName,'data[User][email]':UEmail,'data[User][password]':UPassword},
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

                        var delay = 2; // 5 second delay
                        var now = new Date();
                        var desiredTime = new Date().setSeconds(now.getSeconds() + delay);

                        while (now < desiredTime) {
                            now = new Date(); // update the current time
                        }


                        //$body.removeClass('loader');
                        $.getScript(webroot + 'css/styles.css');
                        $.getScript(webroot + 'css/freegal_styles.css');

                        $.getScript(webroot + 'js/freegal.js');
                        $.getScript(webroot + 'js/site.js');

                        $.getScript(webroot + 'js/audioPlayer.js');
                        $.getScript(webroot + 'js/recent-downloads.js');
                        $.getScript(webroot + 'js/search-results.js');


                        $('.loader').fadeOut(500);

                        $('.content').remove('.loader');

               },
               failure:function(){
                   alert('Problem fetching data');
               }
           });*/
           return false;
        });

        
    });
    function checkEmailValue(){
        
         
        if(!$('#UserNewsletterEmail').val()){
            alert('Please enter the valid notification email address.');
            return false;
        }
        if(!validateEmail($('#UserNewsletterEmail').val())){
            alert('Please enter the valid notification email address.');
            return false;
        }
        return true;
    }
    
    
    
    function validateEmail(email) { 
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
    
</script>
<?php
       
        }
?>
<?php
/*
 File Name : manage_notification.ctp
 File Description : view page for manage_notification
 Author : m68interactive
 */
?>
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
	$html->addCrumb(__('Notifications', true), '/users/manage_notification');
	echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
?>
</div>
<br class="clr">
<header>
        <h2>Manage Notifications</h2>

</header>
<?php
    $this->pageTitle = 'Manage Notifications';
    echo $session->flash();
	echo '<br class="clr">';
    
    
    
?>
		
   <?php
        if(isset($notificationShow) && $notificationShow == 1){
?>
                <div class="forms-wrapper">    
			<div class="email-notification-wrapper">
				<h3>Email Notification</h3>
                                <?php echo $this->Form->create('User', array( 'controller' => 'User','action' => 'manage_notification')); ?>
                                <div>
                                    <?php
                                    if($notificationAlreadySave==true)
                                    {
                                        $notificationAlreadySaveFlag=1; 
                                    }
                                    else
                                    {
                                        $notificationAlreadySaveFlag=0;
                                    }
                                    ?>
                                <?php echo $this->Form->checkbox('sendNewsLetterCheck', array('label' => false, 'div' => false, 'class' => 'form_fields', 'checked' => $notificationAlreadySave)); ?>
                                Add your email address here to receive twice-weekly email reminders of your available downloads.
                                </div>
                                <div id="show_newsletterboxField" style="display:none;">
                                <?php echo $this->Form->label('Notification Email');?>
                                <?php echo $this->Form->input('NewsletterEmail',array('label' => false ,'value' => $notificationEmail, 'div' => false, 'class' => 'form_fields'));?>
                                </div>    
                                <div>
                                <!--<input type="submit" name="notification_submit" onclick="return checkEmailValue()" value="<?php __('Save')?>" />-->
                                <button id="btnNotification" type="button" onclick="return checkEmailValue()" ><?php echo __('Save')?></button>
                                </div>
                                <?php echo $this->Form->end(); ?>
                        </div>
                </div>
</section>


<?php
       /* }
        
         if(isset($notificationShow) && $notificationShow == 1){*/
?>
<script type="text/javascript">
    $(function() {  
        alert('<?php echo $notificationAlreadySave?>');
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
                $('#UserSendNewsLetterCheck').attr('value','1');
            }else{
                $("#show_newsletterboxField").hide();
                $('#UserSendNewsLetterCheck').attr('value',0);
            }            
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
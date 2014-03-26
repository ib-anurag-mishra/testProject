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
	echo $html->getCrumbs(' > ', __('Home', true), '/homes');
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
                                        <button id="btnMyAccount" type="button" ><?php echo __('Save')?></button>
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
                                <div id="show_newsletterboxField" <?php if($notificationAlreadySave != 'true'){ ?> style="display:none;" <?php } ?>>
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
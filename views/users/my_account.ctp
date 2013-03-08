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
<div class="breadCrumb">
<?php
	$html->addCrumb(__('My Account', true), '/users/my_account');
	echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
?>
</div>
<br class="clr">
<div class="txt-my-history">
	<?php __("Download History");?>
</div>
<?php
    $this->pageTitle = 'My Account';
    echo $session->flash();
	echo '<br class="clr">';
    
    
    
?>
<?php echo $this->Form->create('User', array( 'controller' => 'User','action' => 'my_account')); ?>
<?php if( isset($getData) && (count($getData) > 0) ) { ?>
	<div>
		<?php echo $this->Form->hidden( 'id', array( 'label' => false ,'value' => $getData['User']['id'])); ?>
		<div>
			<table cellspacing="10" cellpadding="0" border="0" width="100%">
                             <tr>
                        <td align="right" width="350"><h><b>Account Information</b></h></td>
                        <td>&nbsp;</td>
                </tr>
				<tr>
					<td align="right" valign="top" width="390"><?php echo $this->Form->label(__('First Name', true));?></td>
					<td align="left"><?php echo $this->Form->input('first_name', array('label' => false, 'value' => $getData['User']['first_name'], 'div' => false, 'class' => 'form_fields') ); ?></td>
				</tr>
				<tr>
					<td align="right" valign="top" width="390"><?php echo $this->Form->label(__('Last Name', true));?></td>
					<td align="left"><?php echo $this->Form->input( 'last_name', array('label' => false ,'value' => $getData['User']['last_name'], 'div' => false, 'class' => 'form_fields')); ?></td>
				</tr>
				<tr>
					<td align="right" valign="top" width="390"><?php echo $this->Form->label(__('Email', true));?></td>
					<td align="left"><?php echo $this->Form->input( 'email', array( 'label' => false ,'value' => $getData['User']['email'], 'div' => false, 'class' => 'form_fields', 'readonly' => true)); ?></td>
				</tr>
				<tr>
					<td align="right" valign="top" width="390"><?php echo $this->Form->label(__('Password', true));?></td>
					<td align="left"><?php echo $this->Form->input('password', array('label' => false,'value' => '', 'div' => false, 'class' => 'form_fields') ); ?></td>
				</tr>
                                
				<tr>
					<td align="center" colspan="2"><p class="submit"><input type="submit" value="<?php __('Save')?>" /></p></td>
				</tr>
			</table>
		</div>
	</div>

<?php }  ?>
   <?php
        echo $this->Form->end();   
        if(isset($notificationShow) && $notificationShow == 1){
?>

<?php echo $this->Form->create('User', array( 'controller' => 'User','action' => 'manage_notification')); ?>
<div> 
  
    <div>
        <table cellspacing="10" cellpadding="0" border="0" width="100%">
            
            <tr>
                        <td align="right" width="350"><h><b>Email Notification</b></h></td>
                        <td>&nbsp;</td>
                </tr>
               <tr>
                        <td align="right" width="250"><?php echo $this->Form->checkbox('sendNewsLetterCheck', array('label' => false, 'div' => false, 'class' => 'form_fields', 'checked' => $notificationAlreadySave)); ?></td>
                        <td>Add your email address here to receive twice-weekly email reminders of your available downloads.</td>
                </tr>

                <tr id="show_newsletterboxField" style="display:none;">
                                <td align="right" valign="top" width="390"><?php echo $this->Form->label('Notification Email #');?></td>
                                <td align="left"><?php echo $this->Form->input('NewsletterEmail',array('label' => false ,'value' => $notificationEmail, 'div' => false, 'class' => 'form_fields'));?></td>
                </tr>

                <tr id="show_newsletterboxButton" >
                        <td align="center" colspan="2"><p class="submit"><input type="submit" name="notification_submit" onclick="return checkEmailValue()" value="<?php __('Save')?>" /></p></td>
                </tr>
        </table>
    </div>
</div>


<?php
        echo $this->Form->end(); 
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
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
    echo $this->Form->create('User', array( 'controller' => 'User','action' => 'my_account'));            
?>
	<div>
		<?php echo $this->Form->hidden( 'id', array( 'label' => false ,'value' => $getData['User']['id'])); ?>
		<div>
			<table cellspacing="10" cellpadding="0" border="0" width="100%">
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
	<?php
		echo $this->Form->end();               
	?>
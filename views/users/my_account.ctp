<?php
/*
 File Name : my_account.ctp
 File Description : view page for my_account
 Author : m68interactive
 */
?>
<?php
    $this->pageTitle = 'My Account';
    echo $session->flash();
	echo '<div id=aboutBox>'.__("Manage Account", true).'</div>';
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
<?php echo $javascript->link('freegal_about_curvy'); ?>
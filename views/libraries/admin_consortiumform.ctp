<?php
/*
 File Name : admin_consortiumform.ctp
 File Description : View page for consortium form
 Author : m68interactive
 */
?>
<?php
	$this->pageTitle = 'Libraries'; 
	echo $this->Form->create('Library', array( 'action' => $formAction, 'type' => 'file', 'id' => 'addconsortium'));
	print ""
?>
<fieldset>
	<table cellspacing="10" cellpadding="0" border="0">
		<tr>
			<td colspan="2" style="padding-left:275px;">Update Consortium</td>
		</tr>
		<tr>
			<td style="text-align:right;">
				<?php
					echo $this->Form->label('Consortium Name');
				?>
			</td>
			<td>
				<?php
					echo $this->Form->input('consortium_name',array('label' => false , 'div' => false,'value' => $consortium['Consortium']['consortium_name']));
				?>				
			</td>
		</tr>
		<tr>
				<td style="text-align:right;"><label>API Key</label></td>
			<td>
				<?php
					echo $this->Form->input('consortium_key',array('type'=>'textarea','label' => false , 'div' => false,'style' => 'width:40%', 'value' => $consortium['Consortium']['consortium_key']));
				?>
				<?php echo $this->Form->hidden( 'id', array('value' => $id)); ?>
			</td>
		</tr>		
		<tr>
			<td colspan="2" style="padding-left:275px;"><?php echo $this->Form->submit('Update Consortium', array('div' => false, 'class' => 'form_fields')); ?></td>
		</tr>		
	</table>
</fieldset>
<?php echo $this->Form->end(); ?>
<?php echo $session->flash(); ?>
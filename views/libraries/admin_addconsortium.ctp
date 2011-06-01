<?php
	$this->pageTitle = 'Libraries'; 
	echo $this->Form->create('Library', array( 'action' => $formAction, 'type' => 'file', 'id' => 'addconsortium'));
?>
<fieldset>
	<table cellspacing="10" cellpadding="0" border="0">
		<tr>
			<td colspan="2" style="padding-left:275px;">Add Consortium</td>
		</tr>
		<tr>
			<td style="text-align:right;">
				<?php
					echo $this->Form->label('Consortium Name');
				?>
			</td>
			<td>
				<?php
					echo $this->Form->input('consortium_name',array('label' => false , 'div' => false));
				?>				
			</td>
		</tr>
		<tr>
			<td style="text-align:right;"><label>API Key</lable></td>
			<td>
				<?php
					echo $this->Form->input('consortium_key',array('type'=>'textarea','label' => false , 'div' => false,'style' => 'width:40%'));
				?>			
			</td>
		</tr>		
		<tr>
			<td colspan="2" style="padding-left:275px;"><?php echo $this->Form->submit('Add Consortium', array('div' => false, 'class' => 'form_fields')); ?></td>
		</tr>		
	</table>
</fieldset>
<?php echo $this->Form->end(); ?>
<?php echo $session->flash(); ?>
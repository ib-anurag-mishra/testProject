<?php
/*
 File Name : admin_addconsortium.ctp
 File Description : View page for adding consortium
 Author : m68interactive
 */
?>
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
					echo $this->Form->input('consortium_key',array('type'=>'textarea','label' => false , 'div' => false,'style' => 'width:40%','onfocus' => 'convertString();'));
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
<script type="text/javascript" src="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/js&amp;f=page_specific/libraries_create.js,page_specific/ajaxfileupload.js,datepicker/jquery.ui.core.js,datepicker/jquery.ui.widget.js,datepicker/jquery.ui.datepicker.js"></script>

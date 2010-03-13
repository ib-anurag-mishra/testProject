<?php
	$this->pageTitle = 'Libraries'; 
	echo $this->Form->create('Library', array( 'controller' => 'Library','action' => $formAction));
	if(empty($getData))
    {
        $getData['Library']['id'] = "";
        $getData['Library']['admin_id'] = "";        
        $getData['Library']['library_name'] = "";   
        $getData['Library']['referrer_url'] = "";        
        $getData['Library']['download_limit'] = "";
        $getData['Library']['library_download_daily_limit'] = "";
        $getData['Library']['library_download_weekly_limit'] = "";
        $getData['Library']['library_download_monthly_limit'] = "";
        $getData['Library']['library_download_annual_limit'] = "";
    }
?>
<fieldset>
	<legend><?php echo $formHeader;?></legend>
	<div class="formFieldsContainer">
		<div class="formStepsbox">
			<div id="spet1" class="active_step">Site Setup</div>
			<div id="spet2" class="inactive_step">User Accounts</div>
			<div id="spet3" class="inactive_step">Library Download Control</div>
			<div id="spet4" class="inactive_step">User Download Control</div>
			<div id="spet5" class="inactive_step">Purchase More Downloads</div>
		</div>
		<div class="formFieldsbox">
			<div id="form_step1" class="form_steps">
				<h1>Site Setup</h1>
				<?php echo $this->Form->hidden( 'id', array( 'label' => false ,'value' => $getData['Library']['id'])); ?>
				<table cellspacing="10" cellpadding="0" border="0">
					<tr>
						<td align="right"><?php echo $this->Form->label('Library Name');?></td>
						<td align="left"><?php echo $this->Form->input('library_name',array('label' => false ,'value' => $getData['Library']['library_name'], 'div' => false, 'class' => 'form_fields'));?></td>
					</tr>
					<tr>
						<td align="right"><?php echo $this->Form->label('Library Domain');?></td>
						<td align="left"><?php echo $this->Form->input('referrer_url',array( 'label' => false ,'value' => $getData['Library']['referrer_url'], 'div' => false, 'class' => 'form_fields'));?></td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2"><?php echo $this->Form->label('Template ( Choose One )');?></td></tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2"><?php echo $this->Form->label('Contact');?></td></tr>
					<tr>
						<td align="right"><?php echo $this->Form->label('First Name');?></td>
						<td align="left"><?php echo $this->Form->input('library_name',array('label' => false ,'value' => $getData['Library']['library_name'], 'div' => false, 'class' => 'form_fields'));?></td>
					</tr>
					<tr>
						<td align="right"><?php echo $this->Form->label('Last Name');?></td>
						<td align="left"><?php echo $this->Form->input('library_name',array('label' => false ,'value' => $getData['Library']['library_name'], 'div' => false, 'class' => 'form_fields'));?></td>
					</tr>
					<tr>
						<td align="right"><?php echo $this->Form->label('Email Address');?></td>
						<td align="left"><?php echo $this->Form->input('library_name',array('label' => false ,'value' => $getData['Library']['library_name'], 'div' => false, 'class' => 'form_fields'));?></td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2"><?php echo $this->Form->label('Logo Upload ( Image not to exceed 250 x 250 pixels )');?></td></tr>
					<tr>
						<td align="right"><?php echo $this->Form->label('Select File');?></td>
						<td align="left">
							<?php echo $this->Form->input('library_name',array('label' => false , 'type' => 'file', 'div' => false, 'class' => 'form_fields'));?>
							<?php echo $this->Form->button('Upload', array('type'=>'button'));?>
						</td>
					</tr>
					<tr style="display: none;">
						<td align="right"><?php echo $this->Form->label('Preview');?></td>
						<td align="left"></td>
					</tr>
					<tr>
						<td colspan="2" align="right"><?php echo $this->Form->button('Next >', array('type'=>'button'));?></td>
					</tr>
				</table>
			</div>
			<div id="form_step2" class="form_steps" style="display: none;">
				<h1>User Accounts</h1>
			</div>
			<div id="form_step3" class="form_steps" style="display: none;">
				<h1>Library Download Control</h1>
			</div>
			<div id="form_step4" class="form_steps" style="display: none;">
				<h1>User Download Control</h1>
			</div>
			<div id="form_step5" class="form_steps" style="display: none;">
				<h1>Purchase More Downloads</h1>
				<p class="submit"><input type="submit" value="Save" /></p>
				<?php echo $this->Form->end(); ?>
				<?php echo $session->flash(); ?>
			</div>
		</div>
	</div>
</form>
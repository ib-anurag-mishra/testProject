<?php
	$this->pageTitle = 'Libraries'; 
	echo $this->Form->create('Library', array( 'controller' => 'Library','action' => $formAction));
	if(empty($getData))
	{
	        $getData['Library']['id'] = "";
	        $getData['Library']['library_admin_id'] = "";
	        $getData['Library']['library_name'] = "";
	        $getData['Library']['library_domain_name'] = "";
		$getData['Library']['library_contact_fname'] = "";
		$getData['Library']['library_contact_lname'] = "";
		$getData['Library']['library_contact_email'] = "";
	        $getData['Library']['library_user_download_limit'] = "";
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
			<div id="step1" class="active_step">Site Setup</div>
			<div id="step2" class="inactive_step">User Accounts</div>
			<div id="step3" class="inactive_step">Library Download Control</div>
			<div id="step4" class="inactive_step">User Download Control</div>
			<div id="step5" class="inactive_step">Purchase More Downloads</div>
		</div>
		<div id="loadingDiv" style="display:none;position:absolute;width:863px;text-align:center;top:300px;">
			<?php echo $html->image('ajax-loader-big.gif', array('alt' => 'Loading...')); ?>
		</div>
		<div class="formFieldsbox">
			<?php echo $this->Form->hidden( 'libraryStepNum', array( 'label' => false ,'value' => '')); ?>
			<div id="form_step1" class="form_steps">
				<h1>Site Setup</h1>
				<?php echo $this->Form->hidden( 'id', array( 'label' => false ,'value' => $getData['Library']['id'])); ?>
				<table cellspacing="10" cellpadding="0" border="0">
					<tr><td class="formError" colspan="2"></td></tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Library Name');?></td>
						<td align="left"><?php echo $this->Form->input('library_name',array('label' => false ,'value' => $getData['Library']['library_name'], 'div' => false, 'class' => 'form_fields'));?></td>
					</tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Library Domain');?></td>
						<td align="left"><?php echo $this->Form->input('library_domain_name',array( 'label' => false ,'value' => $getData['Library']['library_domain_name'], 'div' => false, 'class' => 'form_fields'));?></td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2"><?php echo $this->Form->label('Template ( Choose One )');?></td></tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2"><?php echo $this->Form->label('Contact');?></td></tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('First Name');?></td>
						<td align="left"><?php echo $this->Form->input('library_contact_fname',array('label' => false ,'value' => $getData['Library']['library_contact_fname'], 'div' => false, 'class' => 'form_fields'));?></td>
					</tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Last Name');?></td>
						<td align="left"><?php echo $this->Form->input('library_contact_lname',array('label' => false ,'value' => $getData['Library']['library_contact_lname'], 'div' => false, 'class' => 'form_fields'));?></td>
					</tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Email Address');?></td>
						<td align="left"><?php echo $this->Form->input('library_contact_email',array('label' => false ,'value' => $getData['Library']['library_contact_email'], 'div' => false, 'class' => 'form_fields'));?></td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2"><?php echo $this->Form->label('Logo Upload ( Image not to exceed 250 x 250 pixels )');?></td></tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Select File');?></td>
						<td align="left">
							<?php echo $this->Form->input('library_logo',array('label' => false , 'type' => 'file', 'div' => false, 'class' => 'form_fields'));?>
							<?php echo $this->Form->button('Upload', array('type'=>'button'));?>
						</td>
					</tr>
					<tr style="display: none;">
						<td align="right" width="250"><?php echo $this->Form->label('Preview');?></td>
						<td align="left"></td>
					</tr>
					<tr>
						<td colspan="2" align="right"><?php echo $this->Form->button('Next >', array('type' => 'button', 'id' => 'next_btn1'));?></td>
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
<?php
	if (isset ($javascript)) {
		$javascript->link(array('page_specific/libraries_create'), false);
	}
?>
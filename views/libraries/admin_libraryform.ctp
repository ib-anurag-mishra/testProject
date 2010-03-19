<?php
	$this->pageTitle = 'Libraries'; 
	echo $this->Form->create('Library', array( 'action' => $formAction, 'type' => 'file'));
	if(empty($getData))
	{
	        $getData['Library']['id'] = "";
	        $getData['Library']['library_admin_id'] = "";
	        $getData['Library']['library_name'] = "";
	        $getData['Library']['library_domain_name'] = "";
		$getData['Library']['library_template_id'] = "";
		$getData['Library']['library_contact_fname'] = "";
		$getData['Library']['library_contact_lname'] = "";
		$getData['Library']['library_contact_email'] = "";
	        $getData['Library']['library_user_download_limit'] = "";
	        $getData['Library']['library_download_daily_limit'] = "";
	        $getData['Library']['library_download_weekly_limit'] = "";
	        $getData['Library']['library_download_monthly_limit'] = "";
	        $getData['Library']['library_download_annual_limit'] = "";
		$getData['User']['email'] = "";
		$getData['LibraryPurchase']['purchased_order_num'] = "";
		$getData['LibraryPurchase']['purchased_tracks'] = "";
		$getData['LibraryPurchase']['purchased_amount'] = "";
		$getData['Library']['library_download_limit_manual'] = "";
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
			<div id="step5" class="inactive_step">Purchase Downloads</div>
		</div>
		<div id="loadingDiv" style="display:none;position:absolute;width:863px;text-align:center;top:300px;">
			<?php echo $html->image('ajax-loader-big.gif', array('alt' => 'Loading...')); ?>
		</div>
		<div class="formFieldsbox">
			<?php echo $this->Form->hidden( 'libraryStepNum', array('value' => '1')); ?>
			<div id="form_step1" class="form_steps">
				<h1>Site Setup</h1>
				<?php echo $this->Form->hidden( 'id', array('value' => $getData['Library']['id'])); ?>
				<table cellspacing="10" cellpadding="0" border="0">
					<tr><td id="formError1" class="formError" colspan="2"></td></tr>
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
					<tr>
						<td>&nbsp;</td>
						<td align="left">
							<?
								if(count($allTemplates) > 0) {
									$libraryTemplates = array();
									foreach($allTemplates as $templates) {
										$libraryTemplates[$templates['LibraryTemplate']['id']] = "<div style='background:".$templates['LibraryTemplate']['template_color']."' class='radio_div'></div>";
									}
									echo $this->Form->radio('library_template_id', $libraryTemplates, array('legend' => false, 'label' => false, 'class' => 'radio_fields', 'value' => $getData['Library']['library_template_id']));
								}
								else {
									echo '<label>There are no templates available at this moment.</label>';
								}
							?>
						</td>
					</tr>
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
					<tr><td colspan="2"><?php echo $this->Form->label('Logo Upload ( Image height should not exceed 60 pixels )');?></td></tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Select File');?></td>
						<td align="left">
							<input type="file" name="fileToUpload" id="fileToUpload" class="form_fields" />
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
				<table cellspacing="10" cellpadding="0" border="0">
					<tr><td id="formError2" class="formError" colspan="2"></td></tr>
					<tr><td colspan="2"><?php echo $this->Form->label('Admin');?></td></tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('UserName');?></td>
						<td align="left"><?php echo $this->Form->input('User.email',array('label' => false ,'value' => $getData['User']['email'], 'div' => false, 'class' => 'form_fields'));?></td>
					</tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Password');?></td>
						<td align="left"><?php echo $this->Form->input('User.password',array( 'type' => 'password', 'label' => false ,'value' => '', 'div' => false, 'class' => 'form_fields'));?></td>
					</tr>
					<tr>
						<td colspan="2" align="right"><?php echo $this->Form->button('Next >', array('type' => 'button', 'id' => 'next_btn2'));?></td>
					</tr>
				</table>
			</div>
			<div id="form_step3" class="form_steps" style="display: none;">
				<h1>Library Download Control</h1>
				<table cellspacing="10" cellpadding="0" border="0">
					<tr><td id="formError3" class="formError" colspan="2"></td></tr>
					<tr>
						<td colspan="2">
							<?php
								echo $this->Form->input('library_download_limit', array('options' => array(
									'' => 'Number Of Songs',
									'5000' => '5000',
									'10000' => '10000',
									'15000' => '15000',
									'20000' => '20000',
									'manual' => 'Manually',
									), 'label' => false, 'div' => false, 'class' => 'select_fields')
								);
							?>
							<span id="manual_download" style="display:none">
							<?
								echo $this->Form->input('library_download_limit_manual',array('label' => false ,'value' => $getData['Library']['library_download_limit'], 'div' => false, 'class' => 'form_fields'));
							?>
							</span>
						</td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td colspan="2">
							<?php
								echo $this->Form->input('library_download_type', array('options' => array(
									'daily' => 'Daily',
									'weekly' => 'Weekly',
									'monthly' => 'Monthly',
									'anually' => 'Anually'
									), 'label' => false, 'div' => false, 'class' => 'select_fields')
								);
							?>
						</td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td align="left" colspan="2">
							<?php echo $this->Form->checkbox('library_block_explicit_content', array('label' => false, 'div' => false, 'class' => 'form_fields')); ?>
							<?php echo $this->Form->label('Block Explicit Content');?>
						</td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td colspan="2" align="right"><?php echo $this->Form->button('Next >', array('type' => 'button', 'id' => 'next_btn3'));?></td>
					</tr>
				</table>
			</div>
			<div id="form_step4" class="form_steps" style="display: none;">
				<h1>User Download Control</h1>
				<table cellspacing="10" cellpadding="0" border="0">
					<tr><td id="formError4" class="formError" colspan="2"></td></tr>
					<tr>
						<td align="left" width="100">
							<?php echo $this->Form->label('Per Week');?>
						</td>
						<td align="left">
							<?php
								echo $form->input('library_user_download_limit', array('options' => array(
									'' => 'Number Of Songs',
									'5' => '5',
									'10' => '10',
									'15' => '15',
									'20' => '20'
									), 'label' => false, 'div' => false, 'class' => 'select_fields')
								);
							?>
						</td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td colspan="2" align="right"><?php echo $this->Form->button('Next >', array('type' => 'button', 'id' => 'next_btn4'));?></td>
					</tr>
				</table>
			</div>
			<div id="form_step5" class="form_steps" style="display: none;">
				<h1>Purchase Downloads</h1>
				<table cellspacing="10" cellpadding="0" border="0">
					<tr><td id="formError5" class="formError" colspan="2"></td></tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Purchase Order #');?></td>
						<td align="left"><?php echo $this->Form->input('LibraryPurchase.purchased_order_num',array('label' => false ,'value' => $getData['LibraryPurchase']['purchased_order_num'], 'div' => false, 'class' => 'form_fields'));?></td>
					</tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('# of Purchased Tracks');?></td>
						<td align="left"><?php echo $this->Form->input('LibraryPurchase.purchased_tracks',array('label' => false ,'value' => $getData['LibraryPurchase']['purchased_tracks'], 'div' => false, 'class' => 'form_fields'));?></td>
					</tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Purchased Amount in $');?></td>
						<td align="left"><?php echo $this->Form->input('LibraryPurchase.purchased_amount',array('label' => false ,'value' => $getData['LibraryPurchase']['purchased_amount'], 'div' => false, 'class' => 'form_fields'));?></td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td colspan="2" align="right"><?php echo $this->Form->button('Save', array('type' => 'button', 'id' => 'next_btn5'));?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</fieldset>
<?php echo $this->Form->end(); ?>
<?php echo $session->flash(); ?>
<?php
	if (isset ($javascript)) {
		$javascript->link(array('page_specific/libraries_create'), false);
		$javascript->link(array('page_specific/ajaxfileupload'), false);
	}
?>
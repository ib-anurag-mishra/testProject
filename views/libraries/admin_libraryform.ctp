<?php
	$this->pageTitle = 'Libraries'; 
	echo $this->Form->create('Library', array( 'action' => $formAction, 'type' => 'file', 'id' => 'LibraryAdminForm'));
	if(empty($getData))
	{
	    $getData['Library']['id'] = "";
	    $getData['Library']['library_admin_id'] = "";
	    $getData['Library']['library_name'] = "";
		$getData['Library']['library_authentication_method'] = "";
	    $getData['Library']['library_domain_name'] = "";
		$getData['Library']['library_home_url'] = "";
		$getData['Library']['library_authentication_num'] = "";
		$getData['Library']['library_authentication_url'] = "";
		$getData['Library']['library_authentication_variable'] = "";
		$getData['Library']['library_authentication_response'] = "";
		$getData['Library']['error_msg'] = "";		
		$getData['Library']['library_host_name'] = "";
		$getData['Library']['library_port_no'] = "";
		$getData['Library']['library_sip_login'] = "";
		$getData['Library']['library_sip_password'] = "";
		$getData['Library']['library_sip_location'] = "";
		$getData['Library']['library_ezproxy_secret'] = "";
		$getData['Library']['library_ezproxy_referral'] = "";		
		$getData['Library']['library_bgcolor'] = "606060";
		$getData['Library']['library_nav_bgcolor'] = "3F3F3F";
		$getData['Library']['library_boxheader_bgcolor'] = "CCCCCC";
		$getData['Library']['library_boxheader_text_color'] = "666666";
		$getData['Library']['library_text_color'] = "666666";
		$getData['Library']['library_links_color'] = "666666";
		$getData['Library']['library_links_hover_color'] = "000000";
		$getData['Library']['library_navlinks_color'] = "FFFFFF";
		$getData['Library']['library_navlinks_hover_color'] = "FFFFFF";
		$getData['Library']['library_box_header_color'] = "FFFFFF";
		$getData['Library']['library_box_hover_color'] = "FFFFFF";
		$getData['Library']['library_contact_fname'] = "";
		$getData['Library']['library_contact_lname'] = "";
		$getData['Library']['library_contact_email'] = "";
		$getData['Library']['library_download_limit'] = "";
	    $getData['Library']['library_user_download_limit'] = "";
	    $getData['Library']['library_download_type'] = "daily";
		$getData['Library']['library_image_name'] = "";
		$getData['Library']['library_block_explicit_content'] = 0;
		$getData['Library']['show_library_name'] = 0;
		$getData['User']['first_name'] = "";
		$getData['User']['last_name'] = "";
		$getData['User']['email'] = "";
		$getData['Library']['library_contract_start_date'] = "";
		$getData['LibraryPurchase']['purchased_order_num'] = "";
		$getData['LibraryPurchase']['purchased_tracks'] = "";
		$getData['LibraryPurchase']['purchased_amount'] = "";
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
		<div id="loadingDiv" style="display:none;position:absolute;left:40%; right:40%;text-align:center;top:300px;">
			<?php echo $html->image('ajax-loader-big.gif', array('alt' => 'Loading...')); ?>
		</div>
		<div class="formFieldsbox">
			<?php echo $this->Form->hidden( 'libraryStepNum', array('value' => '1')); ?>
			<div id="form_step1" class="form_steps">
				<h1>Site Setup</h1>
				<?php echo $this->Form->hidden( 'id', array('value' => $getData['Library']['id'])); ?>
				<?php echo $this->Form->hidden( 'LibraryPurchase.library_id', array('value' => $getData['Library']['id'])); ?>
				<table cellspacing="10" cellpadding="0" border="0">
					<tr><td id="formError1" class="formError" colspan="2"></td></tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Library Name');?></td>
						<td align="left"><?php echo $this->Form->input('library_name',array('label' => false ,'value' => $getData['Library']['library_name'], 'div' => false, 'class' => 'form_fields', 'size' => 50));?></td>
					</tr>
					<tr>
						<td align="right">
							<?php
								if($getData['Library']['show_library_name'] == 0) {
									$checked = false;
								}
								elseif($getData['Library']['show_library_name'] == 1) {
									$checked = true;
								}
								echo $this->Form->checkbox('show_library_name', array('label' => false, 'div' => false, 'class' => 'form_fields', 'checked' => $checked)); ?>
						</td>
						<td align="left">
							<?php echo $this->Form->label('Do not show library name on site');?>
						</td>
					</tr>					
					<?php
					if($getData['Library']['library_authentication_method'] != "") {
					?>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label(null, 'Library Authentication Method');?></td>
						<td align="left">
							<?php
								if($getData['Library']['library_authentication_method'] == "referral_url") {
									echo "<label>Referral URL</label>";
								}
								elseif($getData['Library']['library_authentication_method'] == "user_account") {
									echo "<label>User Account</label>";
								}
								elseif($getData['Library']['library_authentication_method'] == "innovative") {
									echo "<label>Innovative</label>";
								}
								elseif($getData['Library']['library_authentication_method'] == "innovative_wo_pin") {
									echo "<label>Innovative w/o Pin</label>";
								}								
								elseif($getData['Library']['library_authentication_method'] == "sip2") {
									echo "<label>SIP2</label>";
								}
								elseif($getData['Library']['library_authentication_method'] == "sip2_wo_pin") {
									echo "<label>SIP2 w/o Pin</label>";
								}
								elseif($getData['Library']['library_authentication_method'] == "innovative_var_wo_pin") {
									echo "<label>Innovative Var w/o Pin</label>";
								}		
								elseif($getData['Library']['library_authentication_method'] == "sip2_var") {
									echo "<label>SIP2 Var</label>";
								}
								elseif($getData['Library']['library_authentication_method'] == "sip2_var_wo_pin") {
									echo "<label>SIP2 Var w/o Pin</label>";
								}								
								elseif($getData['Library']['library_authentication_method'] == "ezproxy") {
									echo "<label>EZProxy</label>";
								}									
								echo $this->Form->hidden( 'library_authentication_method', array('value' => $getData['Library']['library_authentication_method']));
							?>
						</td>
					</tr>
					<?php
					}
					else {
					?>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label(null, 'Library Authentication Method');?></td>
						<td align="left">
							<?php
								echo $this->Form->input('library_authentication_method', array('options' => array(
									'' => 'Select a Method',
									'referral_url' => 'Referral URL',
									'sip2' => 'SIP2',
									'sip2_wo_pin' => 'SIP2 w/o Pin',
									'sip2_var' => 'SIP2 Var',
									'sip2_var_wo_pin' => 'SIP2 Var w/o PIN',
									'ezproxy' => 'EZProxy',
									'user_account' => 'User Account',
									'innovative' => 'Innovative',
									'innovative_wo_pin' => 'Innovative w/o PIN',
									'innovative_var_wo_pin' => 'Innovative Var w/o PIN'), 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['Library']['library_authentication_method'])
								);
							?>
						</td>
					</tr>					
					<?php
					}
					?>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label(null, 'Library HomePage URL');?></td>
						<td align="left"><?php echo $this->Form->input('library_home_url',array( 'label' => false ,'value' => $getData['Library']['library_home_url'], 'div' => false, 'class' => 'form_fields', 'size' => 50));?></td>
					</tr>					
					<tr>
						<td colspan="2"  id="allurl" <?php if($getData['Library']['library_authentication_method'] != "innovative" && $getData['Library']['library_authentication_method'] != "innovative_wo_pin" && $getData['Library']['library_authentication_method'] != "sip2" && $getData['Library']['library_authentication_method'] != "sip2_wo_pin" && $getData['Library']['library_authentication_method'] != "innovative_var" && $getData['Library']['library_authentication_method'] != "innovative_var_wo_pin" && $getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "ezproxy"){?>style="display:none;"<?php } ?>>					
						<?php
						if(empty($allUrls))
						{
						?>	
							<table id="tab0" cellspacing="6" cellpadding="0" border="0">
								<td align="right" width="250"><?php echo $this->Form->label(null, 'Library Referral URL');?></td>
								<td align="left"><input type="text" name="data[Libraryurl][0][domain_name]" class="form_fields" size="50"><input type="button" value="+" class="form_fields" onClick="addUrl(1);"></td>
							</table>
						<?php
						}else{
							$count = count($allUrls)+1;
							foreach($allUrls as $k=>$v){
								$j = $k+1;
						?>	
								<table id="tab<?php echo $k; ?>" cellspacing="6" cellpadding="0" border="0">
								<tr>
									<td align="right" width="250"><?php echo $this->Form->label(null, 'Library Referral URL');?></td>
									<td aligh="left"><input type="text" name="data[Libraryurl][<?php echo $k; ?>][domain_name]" class="form_fields" size="50" value="<?php echo $v['Url']['domain_name']; ?>"><?php if($k==0){ ?><input type="button" value="+" class="form_fields" onClick="addUrl(<?php echo $count; ?>);"><?php }else{ ?><input type="button" value="Remove" class="form_fields" onClick="removeUrl(<?php echo $k; ?>);"><?php } ?></td>
								</tr>
								</table>
						<?php
								$j++;
							}
						}
						?>
						</td>
					</tr>
					
					<tr id="referral_url" <?php if($getData['Library']['library_authentication_method'] != "referral_url"){?>style="display:none;"<?php } ?>>
						<td align="right" width="250"><?php echo $this->Form->label(null, 'Referral URL');?></td>
						<td align="left"><?php echo $this->Form->input('library_domain_name',array( 'label' => false ,'value' => $getData['Library']['library_domain_name'], 'div' => false, 'class' => 'form_fields', 'size' => 50));?></td>
					</tr>
					<tr id="innovative1" <?php if($getData['Library']['library_authentication_method'] != "innovative" && $getData['Library']['library_authentication_method'] != "innovative_wo_pin" && $getData['Library']['library_authentication_method'] != "sip2" && $getData['Library']['library_authentication_method'] != "sip2_wo_pin" && $getData['Library']['library_authentication_method'] != "innovative_var" && $getData['Library']['library_authentication_method'] != "innovative_var_wo_pin" && $getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin" && $getData['Library']['library_authentication_method'] != "ezproxy"){?>style="display:none;"<?php } ?>>
						<td align="right" width="250"><?php echo $this->Form->label(null, 'Library Authentication Number');?></td>
						<td align="left"><?php echo $this->Form->input('library_authentication_num',array( 'label' => false ,'value' => $getData['Library']['library_authentication_num'], 'div' => false, 'class' => 'form_fields', 'size' => 50));?></td>
					</tr>
					<tr id="innovative2" <?php if($getData['Library']['library_authentication_method'] != "innovative" && $getData['Library']['library_authentication_method'] != "innovative_wo_pin" && $getData['Library']['library_authentication_method'] != "innovative_var" && $getData['Library']['library_authentication_method'] != "innovative_var_wo_pin"){?>style="display:none;"<?php } ?>>
						<td align="right" width="250"><?php echo $this->Form->label(null, 'Library Authentication URL');?></td>
						<td align="left"><?php echo $this->Form->input('library_authentication_url',array( 'label' => false ,'value' => $getData['Library']['library_authentication_url'], 'div' => false, 'class' => 'form_fields', 'size' => 50));?></td>
					</tr>
					<tr id="sip_host" <?php if($getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin" && $getData['Library']['library_authentication_method'] != "sip2" && $getData['Library']['library_authentication_method'] != "sip2_wo_pin"){?>style="display:none;"<?php } ?>>
						<td align="right" width="250"><?php echo $this->Form->label(null, 'Library Host Name');?></td>
						<td align="left"><?php echo $this->Form->input('library_host_name',array( 'label' => false ,'value' => $getData['Library']['library_host_name'], 'div' => false, 'class' => 'form_fields', 'size' => 50));?></td>
					</tr>
					<tr id="sip_port" <?php if($getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin" && $getData['Library']['library_authentication_method'] != "sip2" && $getData['Library']['library_authentication_method'] != "sip2_wo_pin"){?>style="display:none;"<?php } ?>>
						<td align="right" width="250"><?php echo $this->Form->label(null, 'Library Port Number');?></td>
						<td align="left"><?php echo $this->Form->input('library_port_no',array( 'label' => false ,'value' => $getData['Library']['library_port_no'], 'div' => false, 'class' => 'form_fields', 'size' => 50));?></td>
					</tr>
					<tr id="sip_login" <?php if($getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin" && $getData['Library']['library_authentication_method'] != "sip2" && $getData['Library']['library_authentication_method'] != "sip2_wo_pin"){?>style="display:none;"<?php } ?>>
						<td align="right" width="250"><?php echo $this->Form->label(null, 'Library SIP2 Server Login');?></td>
						<td aligh="left"><?php echo $this->Form->input('library_sip_login',array('label' => false, 'value' => $getData['Library']['library_sip_login'], 'div' => false, 'class' => 'form_fields', 'size' => 50));?></td>
					</tr>
					<tr id="sip_password" <?php if($getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin" && $getData['Library']['library_authentication_method'] != "sip2" && $getData['Library']['library_authentication_method'] != "sip2_wo_pin"){?>style="display:none;"<?php } ?>>
						<td align="right" width="250"><?php echo $this->Form->label(null, 'Library SIP2 Server Password');?></td>
						<td aligh="left"><?php echo $this->Form->input('library_sip_password',array('label' => false, 'value' => $getData['Library']['library_sip_password'], 'div' => false, 'class' => 'form_fields', 'size' => 50));?></td>
					</tr>
					<tr id="sip_location" <?php if($getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin" && $getData['Library']['library_authentication_method'] != "sip2" && $getData['Library']['library_authentication_method'] != "sip2_wo_pin"){?>style="display:none;"<?php } ?>>
						<td align="right" width="250"><?php echo $this->Form->label(null, 'Library SIP2 Server Location');?></td>
						<td aligh="left"><?php echo $this->Form->input('library_sip_location',array('label' => false, 'value' => $getData['Library']['library_sip_location'], 'div' => false, 'class' => 'form_fields', 'size' => 50));?></td>
					</tr>
					<tr id="ezproxy_secret" <?php if($getData['Library']['library_authentication_method'] != "ezproxy"){?>style="display:none;"<?php } ?>>
						<td align="right" width="250"><?php echo $this->Form->label(null, 'EZProxy Secret');?></td>
						<td aligh="left"><?php echo $this->Form->input('library_ezproxy_secret',array('label' => false, 'value' => $getData['Library']['library_ezproxy_secret'], 'div' => false, 'class' => 'form_fields', 'size' => 50));?></td>
					</tr>
					<tr id="ezproxy_referral" <?php if($getData['Library']['library_authentication_method'] != "ezproxy"){?>style="display:none;"<?php } ?>>
						<td align="right" width="250"><?php echo $this->Form->label(null, 'EZProxy Referral URL');?></td>
						<td aligh="left"><?php echo $this->Form->input('library_ezproxy_referral',array('label' => false, 'value' => $getData['Library']['library_ezproxy_referral'], 'div' => false, 'class' => 'form_fields', 'size' => 50));?></td>
					</tr>					
					<tr><td colspan="2" id="innv_var" <?php if($getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin" && $getData['Library']['library_authentication_method'] != "innovative_var_wo_pin"){?>style="display:none;"<?php } ?>>
					<?php
					if(empty($allVariables))
					{
					?>
					<table id="table0"  cellspacing="6" cellpadding="0" border="0">
						<tr>
							<td align="right" width="250"><?php echo $this->Form->label(null, 'Library Authentication Variable');?></td>
							<td aligh="left" class="libalign"><input type="text" name="data[Variable][0][authentication_variable]" class="form_fields" size="50"></td>
						</tr>
						<tr>
							<td align="right" width="250"><?php echo $this->Form->label(null, 'Library Authentication Response');?></td>
							<td aligh="left" class="libalign"><input type="text" name="data[Variable][0][authentication_response]" class="form_fields" size="50"></td>
						</tr>
						<tr>
							<td align="right" width="250"><?php echo $this->Form->label(null, 'Library Comparison Operator');?></td>
							<td align="left" style="padding-left:20px" class="libselect">
								<select name="data[Variable][0][comparison_operator]">
									<option value="">Select a Operator</option>
									<option value="=">=</option>
									<option value=">">></option>
									<option value="<"><</option>
								</select>							
							</td>
						</tr>						
						<tr>
							<td align="right" width="250"><?php echo $this->Form->label(null, 'Library Error Message');?></td>
							<td aligh="left" class="libalign"><input type="text" name="data[Variable][0][error_msg]" class="form_fields" size="50"><input type="button" value="+" class="form_fields" onClick="addVariable(1);"></td>
						</tr>
					</table>
					<?php
					}else{
						$count = count($allVariables)+1;
						foreach($allVariables as $k=>$v){
							$j = $k+1;
					?>	
							<table id="table<?php echo $k; ?>"  cellspacing="6" cellpadding="0" border="0">
							<tr>
								<td align="right" width="250"><?php echo $this->Form->label(null, 'Library Authentication Variable');?></td>
								<td aligh="left" class="libalign"><input type="text" name="data[Variable][<?php echo $k; ?>][authentication_variable]" class="form_fields" size="50" value="<?php echo $v['Variable']['authentication_variable']; ?>"></td>
							</tr>
							<tr>
								<td align="right" width="250"><?php echo $this->Form->label(null, 'Library Authentication Response');?></td>
								<td aligh="left" class="libalign"><input type="text" name="data[Variable][<?php echo $k; ?>][authentication_response]" class="form_fields" size="50" value="<?php echo $v['Variable']['authentication_response']; ?>"></td>
							</tr>
							<tr>
								<td align="right" width="250"><?php echo $this->Form->label(null, 'Library Comparison Operator');?></td>
								<td align="left" style="padding-left:20px" class="libselect">
									<?php $var = $v['Variable']['comparison_operator']; ?>
									<select name="data[Variable][<?php echo $k; ?>][comparison_operator]">
										<option value="">Select a Operator</option>
										<option <?php if($var == '='){ ?> selected = "selected" <?php } ?> value = "=" >=</option>
										<option <?php if($var == '>'){ ?> selected = "selected" <?php } ?> value=">"> > </option>
										<option <?php if($var == '<'){ ?> selected = "selected" <?php } ?> value="<"> < </option>
									</select>							
								</td>
							</tr>							
							<tr>
								<td align="right" width="250"><?php echo $this->Form->label(null, 'Library Error Message');?></td>
								<td aligh="left"  class="libalign"><input type="text" name="data[Variable][<?php echo $k; ?>][error_msg]" class="form_fields" size="50" value="<?php echo $v['Variable']['error_msg']; ?>"><?php if($k==0){ ?><input type="button" value="+" class="form_fields" onClick="addVariable(<?php echo $count; ?>);"><?php }else{ ?><input type="button" value="Remove" class="form_fields" onClick="removeVariable(<?php echo $k; ?>);"><?php } ?></td>
							</tr>
							</table>
					<?php
							$j++;
						}
					}
					?>
					</td></tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2"><?php echo $this->Form->label('Template Settings');?></td></tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Background Color');?></td>
						<td align="left"><?php echo $this->Form->input('library_bgcolor',array('label' => false ,'value' => $getData['Library']['library_bgcolor'], 'div' => false, 'class' => 'form_fields', 'size' => 6, 'readonly' => 'readonly'));?></td>
					</tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Navigation Background Color');?></td>
						<td align="left"><?php echo $this->Form->input('library_nav_bgcolor',array('label' => false ,'value' => $getData['Library']['library_nav_bgcolor'], 'div' => false, 'class' => 'form_fields', 'size' => 6, 'readonly' => 'readonly'));?></td>
					</tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Box Header Background Color');?></td>
						<td align="left"><?php echo $this->Form->input('library_boxheader_bgcolor',array('label' => false ,'value' => $getData['Library']['library_boxheader_bgcolor'], 'div' => false, 'class' => 'form_fields', 'size' => 6, 'readonly' => 'readonly'));?></td>
					</tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Box Header Text Color');?></td>
						<td align="left"><?php echo $this->Form->input('library_boxheader_text_color',array('label' => false ,'value' => $getData['Library']['library_boxheader_text_color'], 'div' => false, 'class' => 'form_fields', 'size' => 6, 'readonly' => 'readonly'));?></td>
					</tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Box Header Links Color');?></td>
						<td align="left"><?php echo $this->Form->input('library_box_header_color',array('label' => false ,'value' => $getData['Library']['library_box_header_color'], 'div' => false, 'class' => 'form_fields', 'size' => 6, 'readonly' => 'readonly'));?></td>
					</tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Box Header Links Hover Color');?></td>
						<td align="left"><?php echo $this->Form->input('library_box_hover_color',array('label' => false ,'value' => $getData['Library']['library_box_hover_color'], 'div' => false, 'class' => 'form_fields', 'size' => 6, 'readonly' => 'readonly'));?></td>
					</tr>						
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Text Color');?></td>
						<td align="left"><?php echo $this->Form->input('library_text_color',array('label' => false ,'value' => $getData['Library']['library_text_color'], 'div' => false, 'class' => 'form_fields', 'size' => 6, 'readonly' => 'readonly'));?></td>
					</tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Page Links Color');?></td>
						<td align="left"><?php echo $this->Form->input('library_links_color',array('label' => false ,'value' => $getData['Library']['library_links_color'], 'div' => false, 'class' => 'form_fields', 'size' => 6, 'readonly' => 'readonly'));?></td>
					</tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Page Links Hover Color');?></td>
						<td align="left"><?php echo $this->Form->input('library_links_hover_color',array('label' => false ,'value' => $getData['Library']['library_links_hover_color'], 'div' => false, 'class' => 'form_fields', 'size' => 6, 'readonly' => 'readonly'));?></td>
					</tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Navigation Links Color');?></td>
						<td align="left"><?php echo $this->Form->input('library_navlinks_color',array('label' => false ,'value' => $getData['Library']['library_navlinks_color'], 'div' => false, 'class' => 'form_fields', 'size' => 6, 'readonly' => 'readonly'));?></td>
					</tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Navigation Links Hover Color');?></td>
						<td align="left"><?php echo $this->Form->input('library_navlinks_hover_color',array('label' => false ,'value' => $getData['Library']['library_navlinks_hover_color'], 'div' => false, 'class' => 'form_fields', 'size' => 6, 'readonly' => 'readonly'));?></td>
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
					<?php if($getData['Library']['library_image_name'] != "") { ?>
						<tr>
							<td align="right" width="250" valign="top"><?php echo $this->Form->label('Preview');?></td>
							<td align="left">
								<?php echo $html->image('libraryimg/'.$getData['Library']['library_image_name'], array('alt' => 'Library Image', 'class' => 'form_fields'))?>
							</td>
						</tr>
					<?php } ?>
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
						<td align="right" width="250"><?php echo $this->Form->label('First Name');?></td>
						<td align="left"><?php echo $this->Form->input('User.first_name',array('label' => false ,'value' => $getData['User']['first_name'], 'div' => false, 'class' => 'form_fields'));?></td>
					</tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Last Name');?></td>
						<td align="left"><?php echo $this->Form->input('User.last_name',array('label' => false ,'value' => $getData['User']['last_name'], 'div' => false, 'class' => 'form_fields'));?></td>
					</tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Email');?></td>
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
								if($getData['Library']['library_download_limit'] != '5000' && $getData['Library']['library_download_limit'] != '10000' && $getData['Library']['library_download_limit'] != '15000' && $getData['Library']['library_download_limit'] != '20000' && $getData['Library']['library_download_limit'] != '') {
									$default_download_limit = 'manual';
								}
								else {
									$default_download_limit = $getData['Library']['library_download_limit'];
								}
								echo $this->Form->input('library_download_limit', array('options' => array(
									'' => 'Number Of Songs',
									'5000' => '5000',
									'10000' => '10000',
									'15000' => '15000',
									'20000' => '20000',
									'manual' => 'Manually',
									), 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $default_download_limit)
								);
							?>
							<span id="manual_download" <?php if($default_download_limit != "manual") { ?> style="display:none" <?php } ?>>
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
									), 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['Library']['library_download_type'])
								);
							?>
						</td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td align="left" colspan="2">
							<?php
								if($getData['Library']['library_block_explicit_content'] == 0) {
									$checked = false;
								}
								elseif($getData['Library']['library_block_explicit_content'] == 1) {
									$checked = true;
								}
								echo $this->Form->checkbox('library_block_explicit_content', array('label' => false, 'div' => false, 'class' => 'form_fields', 'checked' => $checked)); ?>
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
									'1' => '1',
									'2' => '2',
									'3' => '3',
									'5' => '5',
									'10' => '10',
									'15' => '15',
									'20' => '20'
									), 'label' =>false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['Library']['library_user_download_limit'])
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
						<td align="right" width="250"><?php echo $this->Form->label('Library Contract Start Date');?></td>
						<td align="left"><?php echo $this->Form->input('Library.library_contract_start_date',array('label' => false ,'value' => $getData['Library']['library_contract_start_date'], 'div' => false, 'class' => 'form_fields', 'readonly' => 'readonly', 'type' => 'text')); ?></td>
					</tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Purchase Order #');?></td>
						<td align="left"><?php echo $this->Form->input('LibraryPurchase.purchased_order_num',array('label' => false ,'value' => '', 'div' => false, 'class' => 'form_fields'));?></td>
					</tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('# of Purchased Tracks');?></td>
						<td align="left"><?php echo $this->Form->input('LibraryPurchase.purchased_tracks',array('label' => false ,'value' => '', 'div' => false, 'class' => 'form_fields'));?></td>
					</tr>
					<tr>
						<td align="right" width="250"><?php echo $this->Form->label('Purchased Amount in $');?></td>
						<td align="left"><?php echo $this->Form->input('LibraryPurchase.purchased_amount',array('label' => false ,'value' => '', 'div' => false, 'class' => 'form_fields'));?></td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td colspan="2" align="right"><?php echo $this->Form->button('Save', array('type' => 'button', 'id' => 'next_btn5'));?></td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
				</table>
				<?php
				if($getData['Library']['id'] != "") {
				?>
					<h1>Previously Purchased Downloads</h1>
					<table cellspacing="10" cellpadding="0" border="0">
						<?php
						if(count($allPurchases) == 0) {
						?>
							<tr>
								<td colspan="2"><label>There are no current purchases data available for this library at this moment.</label></td>
							</tr>
						<?php
						}
						else {
						?>
							<tr>
								<th><label><b>Sl.No.</b></label></th>
								<th><label><b>Purchase Order #</b></label></th>
								<th><label><b># Of Purchased Tracks</b></label></th>
								<th><label><b>Purchased Amount In $</b></label></th>
								<th><label><b>Purchase Entry Date</b></lable></th>
							</tr>
						<?php
							foreach($allPurchases as $key=>$purchases) {
						?>
								<tr>
									<td><label><?php echo $key+1; ?></label></td>
									<td><label><?php echo $purchases['LibraryPurchase']['purchased_order_num']; ?></label></td>
									<td><label><?php echo $purchases['LibraryPurchase']['purchased_tracks']; ?></label></td>
									<td><label>$<?php echo $purchases['LibraryPurchase']['purchased_amount']; ?></label></td>
									<td><label><?php echo $purchases['LibraryPurchase']['created']; ?></label></td>
								</tr>
						<?php
							}
						}
						?>
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td colspan="2">&nbsp;</td></tr>
					</table>
				<?php
				}
				?>
			</div>
		</div>
	</div>
</fieldset>
<?php echo $this->Form->end(); ?>
<?php echo $session->flash(); ?>
<?php
	if (isset ($javascript)) {
	?>
		<script type="text/javascript" src="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/js&amp;f=page_specific/libraries_create.js,page_specific/ajaxfileupload.js,datepicker/jquery.ui.core.js,datepicker/jquery.ui.widget.js,datepicker/jquery.ui.datepicker.js"></script>
		<link type="text/css" rel="stylesheet" href="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/css&amp;f=flick/jquery-ui-1.8.custom.css" />
		<script type="text/javascript">
			$(function() {
				$("#LibraryLibraryContractStartDate").datepicker({showWeek: true, firstDay: 1, numberOfMonths: 3, dateFormat: 'yy-mm-dd'});
				$("#LibraryLibraryAuthenticationMethod").change(function() {
					if($(this).val() == 'referral_url') {
						$("#referral_url").show();
						$("#allurl").hide();
						$("#innovative1").hide();
						$("#innovative2").hide();
						$("#innv_var").hide();
						//$("#innovative_var_pin").hide();
						//$("#variable").hide();						
						$("#sip_host").hide();
						$("#sip_port").hide();
						$("#sip_pin").hide();
						$("#sip_login").hide();
						$("#sip_password").hide();
						$("#ezproxy_secret").hide();
						$("#ezproxy_referral").hide();							
					}
					else if ($(this).val() == 'innovative') {
						$("#allurl").show();
						$("#referral_url").hide();
						$("#innovative1").show();
						$("#innovative2").show();
						$("#innv_var").hide();
						//$("#innovative_var_pin").hide();
						//$("#variable").hide();						
						$("#sip_host").hide();
						$("#sip_port").hide();
						$("#sip_pin").hide();
						$("#sip_login").hide();
						$("#sip_password").hide();
						$("#ezproxy_secret").hide();
						$("#ezproxy_referral").hide();							
					}
					else if ($(this).val() == 'innovative_wo_pin') {
						$("#allurl").show();
						$("#referral_url").hide();
						$("#innovative1").show();
						$("#innovative2").show();
						$("#innv_var").hide();
						//$("#innovative_var_pin").hide();
						//$("#variable").hide();						
						$("#sip_host").hide();
						$("#sip_port").hide();
						$("#sip_pin").hide();
						$("#sip_login").hide();
						$("#sip_password").hide();
						$("#sip_location").hide();
						$("#ezproxy_secret").hide();
						$("#ezproxy_referral").hide();							
					}
					else if ($(this).val() == 'sip2') {
						$("#allurl").show();
						$("#referral_url").hide();
						$("#innovative1").show();
						$("#innovative2").hide();
						$("#innv_var").hide();
						//$("#innovative_var_pin").hide();
						//$("#variable").hide();						
						$("#sip_host").show();
						$("#sip_port").show();
						$("#sip_pin").show();
						$("#sip_login").show();
						$("#sip_password").show();
						$("#sip_location").show();
						$("#ezproxy_secret").hide();
						$("#ezproxy_referral").hide();							
					}
					else if ($(this).val() == 'sip2_wo_pin') {
						$("#allurl").show();
						$("#referral_url").hide();
						$("#innovative1").show();
						$("#innovative2").hide();
						$("#innv_var").hide();
						//$("#innovative_var_pin").hide();
						//$("#variable").hide();						
						$("#sip_host").show();
						$("#sip_port").show();
						$("#sip_pin").show();
						$("#sip_login").show();
						$("#sip_password").show();
						$("#sip_location").show();
						$("#ezproxy_secret").hide();
						$("#ezproxy_referral").hide();							
					}
					else if ($(this).val() == 'sip2_var') {
						$("#allurl").show();
						$("#referral_url").hide();
						$("#innovative1").show();
						$("#innovative2").hide();
						$("#innv_var").show();
						//$("#innovative_var_pin").show();
						//$("#variable").show();						
						$("#sip_host").show();
						$("#sip_port").show();
						$("#sip_pin").show();
						$("#sip_login").show();
						$("#sip_password").show();
						$("#sip_location").show();
						$("#ezproxy_secret").hide();
						$("#ezproxy_referral").hide();							
					}
					else if ($(this).val() == 'sip2_var_wo_pin') {
						$("#allurl").show();
						$("#referral_url").hide();
						$("#innovative1").show();
						$("#innovative2").hide();
						$("#innv_var").show();
						//$("#innovative_var_pin").show();
						//$("#variable").show();						
						$("#sip_host").show();
						$("#sip_port").show();
						$("#sip_pin").show();
						$("#sip_login").show();
						$("#sip_password").show();
						$("#sip_location").show();
						$("#ezproxy_secret").hide();
						$("#ezproxy_referral").hide();							
					}
					else if ($(this).val() == 'ezproxy') {
						$("#allurl").show();
						$("#referral_url").hide();
						$("#innovative1").show();
						$("#innovative2").hide();
						$("#innv_var").hide();
						//$("#innovative_var_pin").hide();
						//$("#variable").hide();						
						$("#sip_host").hide();
						$("#sip_port").hide();
						$("#sip_pin").hide();
						$("#sip_login").hide();
						$("#sip_password").hide();
						$("#sip_location").hide();
						$("#ezproxy_secret").show();
						$("#ezproxy_referral").show();						
					}					
					else if ($(this).val() == 'innovative_var_wo_pin') {
						$("#allurl").show();
						$("#referral_url").hide();
						$("#innovative1").show();
						$("#innovative2").show();
						$("#innv_var").show();
						//$("#innovative_var_pin").show();
						//$("#variable").show();						
						$("#sip_host").hide();
						$("#sip_port").hide();
						$("#sip_pin").hide();
						$("#sip_login").hide();
						$("#sip_password").hide();
						$("#sip_location").hide();
						$("#ezproxy_secret").hide();
						$("#ezproxy_referral").hide();							
					}										
					else {
						$("#allurl").hide();
						$("#referral_url").hide();
						$("#innovative1").hide();
						$("#innovative2").hide();
						$("#innv_var").hide();
						//$("#innovative_var_pin").hide();
						//$("#variable").hide();						
						$("#sip_host").hide();
						$("#sip_port").hide();
						$("#sip_pin").hide();
						$("#sip_login").hide();
						$("#sip_password").hide();
						$("#sip_location").hide();
						$("#ezproxy_secret").hide();
						$("#ezproxy_referral").hide();							
					}
				});
			});


		</script>
<?php
	}
?>
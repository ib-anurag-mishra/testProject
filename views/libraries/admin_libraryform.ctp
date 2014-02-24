<?php
/*
  File Name : admin_libraryform.ctp
  File Description : View page for library form
  Author : m68interactive
 */
?>
<?php
$this->pageTitle = 'Libraries';
echo $this->Form->create('Library', array('action' => $formAction, 'type' => 'file', 'id' => 'LibraryAdminForm'));


if (empty($getData))
{
    
    
    $getData['Library']['id'] = "";
    $getData['Library']['library_admin_id'] = "";
    $getData['Library']['library_name'] = "";
    $getData['Library']['library_authentication_method'] = "";
    $getData['Library']['library_domain_name'] = "";
    $getData['Library']['library_home_url'] = "";
    $getData['Library']['library_authentication_num'] = "";
    $getData['Library']['library_authentication_url'] = "";
    $getData['Library']['library_space_check'] = "yes";
    $getData['Library']['library_logout_url'] = "";
    $getData['Library']['library_subdomain'] = "";
    $getData['Library']['library_apikey'] = "none";
    $getData['Library']['library_soap_url'] = "";
    $getData['Library']['library_curl_url'] = "";
    $getData['Library']['library_curl_db'] = "";
    $getData['Library']['library_authentication_variable'] = "";
    $getData['Library']['library_authentication_response'] = "";
    $getData['Library']['error_msg'] = "";
    $getData['Library']['library_host_name'] = "";
    $getData['Library']['library_port_no'] = "";
    $getData['Library']['library_sip_login'] = "";
    $getData['Library']['library_sip_password'] = "";
    $getData['Library']['library_sip_location'] = "";
    $getData['Library']['library_sip_terminal_password'] = "";
    $getData['Library']['library_sip_version'] = "";
    $getData['Library']['library_sip_error'] = "on";
    $getData['Library']['minimum_card_length'] = 5;
    $getData['Library']['library_sip_institution'] = "";
    $getData['Library']['library_sip_command'] = "";
    $getData['Library']['library_sip_24_check'] = "yes";
    $getData['Library']['library_sip_64_check_off'] = 0;
    $getData['Library']['library_ezproxy_secret'] = "";
    $getData['Library']['library_ezproxy_referral'] = "";
    $getData['Library']['library_ezproxy_name'] = "";
    $getData['Library']['library_ezproxy_logout'] = "";
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
    $getData['Library']['library_phone'] = "";
    $getData['Library']['library_address'] = "";
    $getData['Library']['library_address2'] = "";
    $getData['Library']['library_city'] = "";
    $getData['Library']['library_state'] = "";
    $getData['Library']['library_country'] = "";
    $getData['Library']['library_zipcode'] = "";
    $getData['Library']['library_download_limit'] = "";
    $getData['Library']['library_user_download_limit'] = "";
    $getData['Library']['library_download_type'] = "daily";
    $getData['Library']['library_territory'] = "";
    $getData['Library']['library_image_name'] = "";
    $getData['Library']['library_block_explicit_content'] = 0;
    $getData['Library']['show_library_name'] = 0;
    $getData['User']['first_name'] = "";
    $getData['User']['last_name'] = "";
    $getData['User']['email'] = "";
    $getData['Library']['library_contract_start_date'] = "";
    $getData['Library']['library_contract_end_date'] = "";
    $getData['LibraryPurchase']['purchased_order_num'] = "";
    $getData['LibraryPurchase']['purchased_tracks'] = "";
    $getData['LibraryPurchase']['purchased_amount'] = "";
    $getData['Library']['library_unlimited'] = 0;
    $getData['Library']['facebook_icon'] = '';
    $getData['Library']['twiter_icon'] = '';
    $getData['Library']['youtube_icon'] = '';

    $getData['Library']['library_language'] = 'en';
    $getData['Library']['library_exp_date_format'] = '';
    $getData['Library']['library_type'] = '1';
}
?>
<fieldset>
    <legend><?php echo $formHeader; ?></legend>
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
            <?php echo $this->Form->hidden('libraryStepNum', array('value' => '1')); ?>
            <div id="form_step1" class="form_steps">
                <h1>Site Setup</h1>
                <?php echo $this->Form->hidden('id', array('value' => $getData['Library']['id'])); ?>
                <?php echo $this->Form->hidden('LibraryPurchase.library_id', array('value' => $getData['Library']['id'])); ?>
                <table cellspacing="10" cellpadding="0" border="0">
                    <tr><td id="formError1" class="formError" colspan="2"></td></tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Library Name'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_name', array('label' => false, 'value' => $getData['Library']['library_name'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr>
                    <tr>
                        <td align="right">
                            <?php
                            if ($getData['Library']['show_library_name'] == 0)
                            {
                                $checked = false;
                            }
                            elseif ($getData['Library']['show_library_name'] == 1)
                            {
                                $checked = true;
                            }
                            echo $this->Form->checkbox('show_library_name', array('label' => false, 'div' => false, 'class' => 'form_fields', 'checked' => $checked));
                            ?>
                        </td>
                        <td align="left">
                            <?php echo $this->Form->label('Do not show library name on site'); ?>
                        </td>
                    </tr>					
                    <?php
                    if ($getData['Library']['library_authentication_method'] != "")
                    {
                        ?>
                        <tr>
                            <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Authentication Method'); ?></td>
                            <td align="left">
                                <?php
                                if ($getData['Library']['library_authentication_method'] == "referral_url")
                                {
                                    echo "<label>Referral URL</label>";
                                }
                                elseif ($getData['Library']['library_authentication_method'] == "user_account")
                                {
                                    echo "<label>User Account</label>";
                                }
                                elseif ($getData['Library']['library_authentication_method'] == "innovative")
                                {
                                    echo "<label>Innovative</label>";
                                }
                                elseif ($getData['Library']['library_authentication_method'] == "innovative_var")
                                {
                                    echo "<label>Innovative Var</label>";
                                }
                                elseif ($getData['Library']['library_authentication_method'] == "capita")
                                {
                                    echo "<label>Capita</label>";
                                }
                                elseif ($getData['Library']['library_authentication_method'] == "symws")
                                {
                                    echo "<label>Symphony WS</label>";
                                }
                                elseif ($getData['Library']['library_authentication_method'] == "innovative_var_name")
                                {
                                    echo "<label>Innovative Var Name</label>";
                                }
                                elseif ($getData['Library']['library_authentication_method'] == "innovative_var_https_name")
                                {
                                    echo "<label>Innovative Var HTTPS Name</label>";
                                }
                                elseif ($getData['Library']['library_authentication_method'] == "innovative_https")
                                {
                                    echo "<label>Innovative HTTPS</label>";
                                }
                                elseif ($getData['Library']['library_authentication_method'] == "innovative_var_https")
                                {
                                    echo "<label>Innovative Var HTTPS</label>";
                                }
                                elseif ($getData['Library']['library_authentication_method'] == "innovative_var_https_wo_pin")
                                {
                                    echo "<label>Innovative Var HTTPS w/o Pin</label>";
                                }
                                elseif ($getData['Library']['library_authentication_method'] == "innovative_wo_pin")
                                {
                                    echo "<label>Innovative w/o Pin</label>";
                                }
                                elseif ($getData['Library']['library_authentication_method'] == "sip2")
                                {
                                    echo "<label>SIP2</label>";
                                }
                                elseif ($getData['Library']['library_authentication_method'] == "sip2_wo_pin")
                                {
                                    echo "<label>SIP2 w/o Pin</label>";
                                }
                                elseif ($getData['Library']['library_authentication_method'] == "innovative_var_wo_pin")
                                {
                                    echo "<label>Innovative Var w/o Pin</label>";
                                }
                                elseif ($getData['Library']['library_authentication_method'] == "sip2_var")
                                {
                                    echo "<label>SIP2 Var</label>";
                                }
                                elseif ($getData['Library']['library_authentication_method'] == "sip2_var_wo_pin")
                                {
                                    echo "<label>SIP2 Var w/o Pin</label>";
                                }
                                elseif ($getData['Library']['library_authentication_method'] == "ezproxy")
                                {
                                    echo "<label>EZProxy</label>";
                                }
                                elseif ($getData['Library']['library_authentication_method'] == "soap")
                                {
                                    echo "<label>SOAP Web Services</label>";
                                }
                                elseif ($getData['Library']['library_authentication_method'] == "curl_method")
                                {
                                    echo "<label>Curl Method</label>";
                                }
                                elseif ($getData['Library']['library_authentication_method'] == "mdlogin_reference")
                                {
                                    echo "<label>MDLogin</label>";
                                }
                                elseif ($getData['Library']['library_authentication_method'] == "mndlogin_reference")
                                {
                                    echo "<label>MNDLogin</label>";
                                }
                                echo $this->Form->hidden('library_authentication_method', array('value' => $getData['Library']['library_authentication_method']));
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    else
                    {
                        ?>
                        <tr>
                            <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Authentication Method'); ?></td>
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
                                        'soap' => 'SOAP Web Services',
                                        'curl_method' => 'Curl Method',
                                        'user_account' => 'User Account',
                                        'innovative' => 'Innovative',
                                        'innovative_var' => 'Innovative Var',
                                        'innovative_var_name' => 'Innovative Var Name',
                                        'innovative_var_https_name' => 'Innovative Var HTTPS Name',
                                        'innovative_wo_pin' => 'Innovative w/o PIN',
                                        'innovative_https' => 'Innovative HTTPS',
                                        'innovative_var_https' => 'Innovative Var HTTPS',
                                        'innovative_var_https_wo_pin' => 'Innovative Var HTTPS w/o PIN',
                                        'innovative_var_wo_pin' => 'Innovative Var w/o PIN', 'mdlogin_reference' => 'MDLogin', 'mndlogin_reference' => 'MNDLogin',  'capita' => 'Capita', 'symws' => 'Symphony WS'), 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['Library']['library_authentication_method'],
                                        )
                                );
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library HomePage URL'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_home_url', array('label' => false, 'value' => $getData['Library']['library_home_url'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Logout URL'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_logout_url', array('label' => false, 'value' => $getData['Library']['library_logout_url'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Consortium'); ?></td>
                        <td align="left">
                            <?php
                            $consortium['none'] = 'None';
                            if ($getData['Library']['library_apikey'] == '')
                            {
                                $getData['Library']['library_apikey'] = "none";
                            }
                            echo $this->Form->input('library_apikey', array('options' => $consortium, 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['Library']['library_apikey']));
                            ?>						
                        </td>
                    </tr>					
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Sub Domain'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_subdomain', array('label' => false, 'value' => $getData['Library']['library_subdomain'], 'div' => false, 'class' => 'form_fields', 'size' => 45)); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2"  id="allurl" <?php
                        if ($getData['Library']['library_authentication_method'] == "user_account" || $getData['Library']['library_authentication_method'] == "referral_url" || $getData['Library']['library_authentication_method'] == "ezproxy")
                        {
                            ?>style="display:none;"<?php } ?>>					
                            <?php
                            if (empty($allUrls))
                            {
                                ?>	
                                <table id="tab0" cellspacing="6" cellpadding="0" border="0">
                                    <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Referral URL'); ?></td>
                                    <td align="left"><input type="text" name="data[Libraryurl][0][domain_name]" class="form_fields" size="50"><input type="button" value="+" class="form_fields" onClick="addUrl(1);"></td>
                                </table>
                                <?php
                            }
                            else
                            {
                                $count = count($allUrls) + 1;
                                foreach ($allUrls as $k => $v)
                                {
                                    $j = $k + 1;
                                    ?>	
                                    <table id="tab<?php echo $k; ?>" cellspacing="6" cellpadding="0" border="0">
                                        <tr>
                                            <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Referral URL'); ?></td>
                                            <td aligh="left"><input type="text" name="data[Libraryurl][<?php echo $k; ?>][domain_name]" class="form_fields" size="50" value="<?php echo $v['Url']['domain_name']; ?>"><?php
                                                if ($k == 0)
                                                {
                                                    ?><input type="button" value="+" class="form_fields" onClick="addUrl(<?php echo $count; ?>);"><?php
                                                }
                                                else
                                                {
                                                    ?><input type="button" value="Remove" class="form_fields" onClick="removeUrl(<?php echo $k; ?>);"><?php } ?></td>
                                        </tr>
                                    </table>
                                    <?php
                                    $j++;
                                }
                            }
                            ?>
                        </td>
                    </tr>

                    <tr id="referral_url" <?php
                    if ($getData['Library']['library_authentication_method'] != "referral_url")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Referral URL'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_domain_name', array('label' => false, 'value' => $getData['Library']['library_domain_name'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr>
                    <tr id="space" <?php
                    if ($getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Space Check'); ?></td>
                        <td aligh="left">
                            <?php
                            echo $this->Form->input('library_space_check', array('options' => array(
                                    'yes' => 'yes',
                                    'no' => 'no'
                                ), 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['Library']['library_space_check'])
                            );
                            ?>
                        </td>
                    </tr>					
                    <tr id="innovative1" <?php
                    if ($getData['Library']['library_authentication_method'] != "innovative" && $getData['Library']['library_authentication_method'] != "innovative_https" && $getData['Library']['library_authentication_method'] != "innovative_wo_pin" && $getData['Library']['library_authentication_method'] != "sip2" && $getData['Library']['library_authentication_method'] != "sip2_wo_pin" && $getData['Library']['library_authentication_method'] != "capita" && $getData['Library']['library_authentication_method'] != "symws" && $getData['Library']['library_authentication_method'] != "innovative_var" && $getData['Library']['library_authentication_method'] != "innovative_var_https" &&
                            $getData['Library']['library_authentication_method'] != "innovative_var_https_wo_pin" &&
                            $getData['Library']['library_authentication_method'] != "innovative_var_wo_pin" && $getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin" && $getData['Library']['library_authentication_method'] != "innovative_var_name" &&
                            $getData['Library']['library_authentication_method'] != "innovative_var_https_name" &&
                            $getData['Library']['library_authentication_method'] != "soap" &&
                            $getData['Library']['library_authentication_method'] != "mdlogin_reference" &&
                            $getData['Library']['library_authentication_method'] != "mndlogin_reference")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Authentication Number'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_authentication_num', array('label' => false, 'value' => $getData['Library']['library_authentication_num'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr>
                    <tr id="innovative2" <?php
                    if ($getData['Library']['library_authentication_method'] != "innovative" && $getData['Library']['library_authentication_method'] != "innovative_https" && $getData['Library']['library_authentication_method'] != "innovative_wo_pin" && $getData['Library']['library_authentication_method'] != "innovative_var" && $getData['Library']['library_authentication_method'] != "capita" && $getData['Library']['library_authentication_method'] != "symws"   && $getData['Library']['library_authentication_method'] != "innovative_var_https" && $getData['Library']['library_authentication_method'] != "innovative_var_https_wo_pin" && $getData['Library']['library_authentication_method'] != "innovative_var_wo_pin" && $getData['Library']['library_authentication_method'] != "innovative_var_name" && $getData['Library']['library_authentication_method'] != "innovative_var_https_name")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Authentication URL'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_authentication_url', array('label' => false, 'value' => $getData['Library']['library_authentication_url'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr>
                    <tr id="soap" <?php
                    if ($getData['Library']['library_authentication_method'] != "soap")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library SOAP URL'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_soap_url', array('label' => false, 'value' => $getData['Library']['library_soap_url'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr>					
                    <tr id="sip_host" <?php
                    if ($getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "symws" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin" && $getData['Library']['library_authentication_method'] != "sip2" && $getData['Library']['library_authentication_method'] != "sip2_wo_pin")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Host Name'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_host_name', array('label' => false, 'value' => $getData['Library']['library_host_name'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr>
                    <tr id="sip_port" <?php
                    if ($getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin" && $getData['Library']['library_authentication_method'] != "sip2" && $getData['Library']['library_authentication_method'] != "sip2_wo_pin")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Port Number'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_port_no', array('label' => false, 'value' => $getData['Library']['library_port_no'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr>
                    <tr id="sip_login" <?php
                    if ($getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin" && $getData['Library']['library_authentication_method'] != "sip2" && $getData['Library']['library_authentication_method'] != "sip2_wo_pin")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library SIP2 Server Login'); ?></td>
                        <td aligh="left"><?php echo $this->Form->input('library_sip_login', array('label' => false, 'value' => $getData['Library']['library_sip_login'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr>
                    <tr id="sip_password" <?php
                    if ($getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin" && $getData['Library']['library_authentication_method'] != "sip2" && $getData['Library']['library_authentication_method'] != "sip2_wo_pin")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library SIP2 Server Password'); ?></td>
                        <td aligh="left"><?php echo $this->Form->input('library_sip_password', array('label' => false, 'value' => $getData['Library']['library_sip_password'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr>
                    <tr id="sip_location" <?php
                    if ($getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin" && $getData['Library']['library_authentication_method'] != "sip2" && $getData['Library']['library_authentication_method'] != "sip2_wo_pin")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library SIP2 Server Location'); ?></td>
                        <td aligh="left"><?php echo $this->Form->input('library_sip_location', array('label' => false, 'value' => $getData['Library']['library_sip_location'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr>
                    <tr id="sip_terminal" <?php
                    if ($getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin" && $getData['Library']['library_authentication_method'] != "sip2" && $getData['Library']['library_authentication_method'] != "sip2_wo_pin")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library SIP2 Terminal Password'); ?></td>
                        <td aligh="left"><?php echo $this->Form->input('library_sip_terminal_password', array('label' => false, 'value' => $getData['Library']['library_sip_terminal_password'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr>
                    <tr id="sip_institution" <?php
                    if ($getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library SIP2 Institution ID'); ?></td>
                        <td aligh="left"><?php echo $this->Form->input('library_sip_institution', array('label' => false, 'value' => $getData['Library']['library_sip_institution'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr>
                    <tr id="sip_over_ssh" <?php
                    if ($getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'SIP2 Over SSH'); ?></td>
                        <td aligh="left">
                            <?php
                            echo $this->Form->input('is_sip_over_ssh', array('options' => array(
                                    '0' => 'No',
                                    '1' => 'Yes'
                                ), 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['Library']['is_sip_over_ssh'])
                            );
                            ?>
                        </td>
                    </tr>
                    <tr id="ssh_command" <?php
                    if ($getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library SIP2 SSH Command'); ?></td>
                        <td aligh="left"><?php echo $this->Form->input('library_sip_command', array('label' => false, 'value' => $getData['Library']['library_sip_command'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr>
                    <tr id="curl_url" <?php
                    if ($getData['Library']['library_authentication_method'] != "curl_method" && $getData['Library']['library_authentication_method'] != "curl_method")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Curl URL'); ?></td>
                        <td aligh="left"><?php echo $this->Form->input('library_curl_url', array('label' => false, 'value' => $getData['Library']['library_curl_url'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr>
                    <tr id="curl_db" <?php
                    if ($getData['Library']['library_authentication_method'] != "curl_method" && $getData['Library']['library_authentication_method'] != "curl_method")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Curl DB Name'); ?></td>
                        <td aligh="left"><?php echo $this->Form->input('library_curl_db', array('label' => false, 'value' => $getData['Library']['library_curl_db'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr>					

                    <tr id="sip_version" <?php
                    if ($getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250">
                            <?php echo $this->Form->label(null, 'Library SIP2 Server Version'); ?></td>
                        <td aligh="left">
                            <?php
                            if ($getData['Library']['library_sip_version'] == '2.0E' || $getData['Library']['library_sip_version'] == '2.0S')
                            {
                                $version = $getData['Library']['library_sip_version'];
                            }
                            else
                            {
                                $version = '2.00';
                            }
                            echo $this->Form->input('library_sip_version', array('options' => array(
                                    '2.00' => '2.00',
                                    '2.0E' => '2.0E',
                                    '2.0S' => '2.0S'
                                ), 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $version)
                            );
                            ?>						
                        </td>
                    </tr>
                    <tr id="24_message" <?php
                    if ($getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Message(24) Check'); ?></td>
                        <td aligh="left">
                            <?php
                            echo $this->Form->input('library_sip_24_check', array('options' => array(
                                    'yes' => 'yes',
                                    'no' => 'no'
                                ), 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['Library']['library_sip_24_check'])
                            );
                            ?>
                        </td>
                    </tr>	
                    <tr id="64_message" <?php
                    if ($getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250">
                            <?php
                            if ($getData['Library']['library_sip_64_check_off'] == 0)
                            {
                                $checked = false;
                            }
                            elseif ($getData['Library']['library_sip_64_check_off'] == 1)
                            {
                                $checked = true;
                            }
                            echo $this->Form->checkbox('library_sip_64_check_off', array('label' => false, 'div' => false, 'class' => 'form_fields', 'checked' => $checked));
                            ?>            
                        </td>
                        <td aligh="left">
                            <?php echo $this->Form->label('Library Message(64) Check off'); ?>
                        </td>
                    </tr> 
                    <tr id="sip_error" <?php
                    if ($getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250">
                            <?php echo $this->Form->label(null, 'Library SIP2 Error Correction'); ?></td>
                        <td aligh="left">
                            <?php
                            if ($getData['Library']['library_sip_error'] == 'on')
                            {
                                $error = 'on';
                            }
                            else
                            {
                                $error = 'off';
                            }
                            echo $this->Form->input('library_sip_error', array('options' => array(
                                    'on' => 'on',
                                    'off' => 'off'
                                ), 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $error)
                            );
                            ?>						
                        </td>
                    </tr>					
                    <tr id="ezproxy_secret" <?php
                    if ($getData['Library']['library_authentication_method'] != "ezproxy")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'EZProxy Secret'); ?></td>
                        <td aligh="left"><?php echo $this->Form->input('library_ezproxy_secret', array('label' => false, 'value' => $getData['Library']['library_ezproxy_secret'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr>
                    <tr id="ezproxy_referral" <?php
                    if ($getData['Library']['library_authentication_method'] != "ezproxy")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'EZProxy Referral URL'); ?></td>
                        <td aligh="left"><?php echo $this->Form->input('library_ezproxy_referral', array('label' => false, 'value' => $getData['Library']['library_ezproxy_referral'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr>
                    <tr id="ezproxy_name" <?php
                    if ($getData['Library']['library_authentication_method'] != "ezproxy")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'EZProxy Library Name'); ?></td>
                        <td aligh="left"><?php echo $this->Form->input('library_ezproxy_name', array('label' => false, 'value' => $getData['Library']['library_ezproxy_name'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr>
                    <tr id="ezproxy_logout" <?php
                    if ($getData['Library']['library_authentication_method'] != "ezproxy")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'EZProxy Library Logout URL'); ?></td>
                        <td aligh="left"><?php echo $this->Form->input('library_ezproxy_logout', array('label' => false, 'value' => $getData['Library']['library_ezproxy_logout'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr>					
                    <tr><td colspan="2" id="innv_var" <?php
                        if ($getData['Library']['library_authentication_method'] != "innovative_https" && $getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin" && $getData['Library']['library_authentication_method'] != "innovative_var_wo_pin" && $getData['Library']['library_authentication_method'] != "innovative_var" && $getData['Library']['library_authentication_method'] != "capita" && $getData['Library']['library_authentication_method'] != "symws" && $getData['Library']['library_authentication_method'] != "innovative_var_https" && $getData['Library']['library_authentication_method'] != "innovative_var_https_wo_pin" && $getData['Library']['library_authentication_method'] != "innovative_var_name" && $getData['Library']['library_authentication_method'] != "innovative_var_https_name")
                        {
                            ?>style="display:none;"<?php } ?>>
                            <input type="hidden" id="dropDown">
                            <?php
                            if (empty($allVariables))
                            {
                                ?>
                                <table id="table0"  cellspacing="6" cellpadding="0" border="0">
                                    <tr>
                                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Authentication Variable'); ?></td>
                                        <td aligh="left" class="libalign"><input type="text" name="data[Variable][0][authentication_variable]" class="form_fields" size="50"></td>
                                    </tr>
                                    <tr>
                                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Comparison Operator'); ?></td>
                                        <td align="left" style="padding-left:20px" class="libselect">
                                            <select name="data[Variable][0][comparison_operator]" id="oprDrop0"  onChange="getArray('0');
                                                getResponse('0');">
                                                <option value="">Select a Operator</option>
                                                <option value="=">=</option>
                                                <option value=">">></option>
                                                <option value="<"><</option>
                                                <option value="<>"><></option>
                                                <option value="begins">begins</option>
                                                <option value="notbegins">does not begin with</option>
                                                <option value="cmp_pos">cmp_pos</option>
                                                <option value="contains">Contains</option>
                                                <option value="notcontain">does not contain</option>
                                                <option value="date">Expired</option>
                                                <option value="=(Fixed)">=(Fixed)</option>
                                                <option value=">(Fixed)">>(Fixed)</option>
                                                <option value="<(Fixed)"><(Fixed)</option>
                                                <option value="<>(Fixed)"><>(Fixed)</option>
                                                <option value="begins(Fixed)">begins(Fixed)</option>
                                                <option value="notbegins(Fixed)">does not begin with(Fixed)</option>
                                                <option value="cmp_pos(Fixed)">cmp_pos(Fixed)</option>
                                                <option value="contains(Fixed)">Contains(Fixed)</option>
                                                <option value="notcontain(Fixed)">does not contain(Fixed)</option>
                                            </select>							
                                        </td>
                                    </tr>
                                    <tr id="authentication_response_pos0" style="display:none">
                                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Response Position'); ?></td>
                                        <td align="left"  class="libselect">
                                            <input type="text" name="data[Variable][0][authentication_response_pos]" class="form_fields" size="20" value="<?php echo $v['Variable']['authentication_response_pos']; ?>">							
                                        </td>
                                    </tr>
                                    <tr id="resArr0" style="display:none">
                                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Result Array'); ?></td>
                                        <td align="left" style="padding-left:20px" class="libselect">
                                            <select name="data[Variable][0][result_arr]">
                                                <option value="fixed">Fixed</option>
                                                <option value="variable">Variable</option>
                                            </select>							
                                        </td>
                                    </tr>						
                                    <tr>
                                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Authentication Response'); ?></td>
                                        <td aligh="left" class="libalign"><input type="text" name="data[Variable][0][authentication_response]" class="form_fields" size="50" id="responseField0"></td>
                                    </tr>	

                                    <tr>
                                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Authentication Variable Index'); ?></td>
                                        <td  style="font-size:12px;">
                                            <input id="varialbe_index_flag00" type="radio" name="data[Variable][0][variable_index]" value="1" class="form_fields"  >Index Value<input type="text" name="data[Variable][0][authentication_variable_index]" class="form_fields" size="15" id="authentication_variable_index0">
                                            <input id="varialbe_index_flag10" type="radio" name="data[Variable][0][variable_index]" value="2" class="form_fields"  >All Index
                                        </td>
                                    </tr>
    <!--                                                <tr>
                                                    <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Authentication Variable Index'); ?></td>
                                                    <td aligh="left" class="libalign"><input type="text" name="data[Variable][0][authentication_variable_index]" class="form_fields" size="50" id="authentication_variable_index0"></td>                                     
                                            </tr>-->
                                    <tr id="msgNo">
                                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Message No'); ?></td>
                                        <td align="left" style="padding-left:20px" class="libselect">
                                            <select name="data[Variable][0][message_no]" id="msg0" onchange="getResponse('0');">
                                                <option value="">Select a Message No</option>
                                                <option value="24">24</option>
                                                <option value="64">64</option>
                                                <option value="98">98</option>
                                            </select>							
                                        </td>
                                    </tr>						
                                    <tr>
                                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Error Message'); ?></td>
                                        <td aligh="left" class="libalign"><input type="text" name="data[Variable][0][error_msg]" class="form_fields" size="50"><input type="button" value="+" class="form_fields" onClick="addVariable(1);"></td>
                                    </tr>
                                </table>
                                <?php
                            }
                            else
                            {
                                $count = count($allVariables) + 1;
                                foreach ($allVariables as $k => $v)
                                {
                                    $j = $k + 1;
                                    ?>	
                                    <table id="table<?php echo $k; ?>"  cellspacing="6" cellpadding="0" border="0">
                                        <tr>
                                            <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Authentication Variable'); ?></td>
                                            <td aligh="left" class="libalign"><input type="text" name="data[Variable][<?php echo $k; ?>][authentication_variable]" class="form_fields" size="50" value="<?php echo $v['Variable']['authentication_variable']; ?>"></td>
                                        </tr>
                                        <tr>
                                            <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Comparison Operator'); ?></td>
                                            <td align="left" style="padding-left:20px" class="libselect">
                                                <?php $var = $v['Variable']['comparison_operator']; ?>
                                                <select name="data[Variable][<?php echo $k; ?>][comparison_operator]" id="oprDrop<?php echo $k; ?>" onchange="getResponse(<?php echo $k; ?>);
                                                    getArray(<?php echo $k; ?>);">
                                                    <option value="">Select a Operator</option>
                                                    <option <?php
                                                    if ($var == '=')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value = "=" >=</option>
                                                    <option <?php
                                                    if ($var == '>')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value=">"> > </option>
                                                    <option <?php
                                                    if ($var == '<')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value="<"> < </option>
                                                    <option <?php
                                                    if ($var == '<>')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value="<>"> <> </option>
                                                    <option <?php
                                                    if ($var == 'begins')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value="begins"> begins </option>
                                                    <option <?php
                                                    if ($var == 'notbegins')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value="notbegins">does not begin with</option>
                                                    <option <?php
                                                    if ($var == 'cmp_pos')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value="cmp_pos"> cmp_pos</option>
                                                    <option <?php
                                                    if ($var == 'contains')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value="contains"> Contains </option>
                                                    <option <?php
                                                    if ($var == 'notcontain')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value="notcontain">does not contain</option>
                                                    <option <?php
                                                    if ($var == 'date')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value="date"> Expired </option>
                                                    <option <?php
                                                    if ($var == '=(Fixed)')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value = "=(Fixed)" >=(Fixed)</option>
                                                    <option <?php
                                                    if ($var == '>(Fixed)')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value=">(Fixed)"> >(Fixed) </option>
                                                    <option <?php
                                                    if ($var == '<(Fixed)')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value="<(Fixed)"> <(Fixed) </option>
                                                    <option <?php
                                                    if ($var == '<>(Fixed)')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value="<>(Fixed)"> <>(Fixed) </option>
                                                    <option <?php
                                                    if ($var == 'begins(Fixed)')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value="begins(Fixed)"> begins(Fixed) </option>
                                                    <option <?php
                                                    if ($var == 'notbegins(Fixed)')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value="notbegins(Fixed)">does not begin with(Fixed)</option>
                                                    <option <?php
                                                    if ($var == 'cmp_pos(Fixed)')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value="cmp_pos(Fixed)"> cmp_pos(Fixed) </option>
                                                    <option <?php
                                                    if ($var == 'contains(Fixed)')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value="contains(Fixed)"> Contains(Fixed) </option>
                                                    <option <?php
                                                    if ($var == 'notcontain(Fixed)')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value="notcontain(Fixed)">does not contain(Fixed)</option>
                                                </select>							
                                            </td>
                                        </tr>
                                        <tr id="authentication_response_pos<?php echo $k; ?>" <?php
                                        if (!($var == 'cmp_pos' || $var == 'cmp_pos(Fixed)'))
                                        {
                                            ?> style="display:none"<?php } ?>>
                                            <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Response Position'); ?></td>
                                            <td align="left"  class="libselect">
                                                <input type="text" name="data[Variable][<?php echo $k; ?>][authentication_response_pos]" class="form_fields" size="50" value="<?php echo $v['Variable']['authentication_response_pos']; ?>">							
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Authentication Response'); ?></td>
                                            <td aligh="left" class="libalign"><input type="text" id="responseField<?php echo $k; ?>" name="data[Variable][<?php echo $k; ?>][authentication_response]" class="form_fields" size="50" value="<?php echo $v['Variable']['authentication_response']; ?>"></td>
                                        </tr>





                                        <tr>
                                            <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Authentication Variable Index'); ?></td>
                                            <td  style="font-size:12px;">

                                                <input id="varialbe_index_flag0<?php echo $k; ?>" type="radio" name="data[Variable][<?php echo $k; ?>][variable_index]" value="1" class="form_fields"  <?php
                                                if ($v['Variable']['variable_index'] == 1)
                                                {
                                                    ?> checked="checked" <?php } ?>>Index Value<input type="text" name="data[Variable][<?php echo $k; ?>][authentication_variable_index]" value="<?php
                                                       if ($v['Variable']['variable_index'] == 1)
                                                       {
                                                           echo $v['Variable']['authentication_variable_index'];
                                                       }
                                                       ?>" class="form_fields" size="15" id="authentication_variable_index<?php echo $k; ?>">
                                                <input id="varialbe_index_flag1<?php echo $k; ?>" type="radio" name="data[Variable][<?php echo $k; ?>][variable_index]" value="2" class="form_fields"  <?php
                                                if ($v['Variable']['variable_index'] == 2)
                                                {
                                                    ?> checked="checked" <?php } ?>>All Index
                                            </td>
                                        </tr>



                                        <tr id="resArr<?php echo $k; ?>" <?php
                                        if ($var != 'contains')
                                        {
                                            ?> style="display:none"<?php } ?>>
                                            <td align="right" width="250"><?php echo $this->Form->label(null, 'Result Array'); ?></td>
                                            <td align="left" style="padding-left:20px" class="libselect">
                                                <select name="data[Variable][<?php echo $k; ?>][result_arr]">
                                                    <option <?php
                                                    if ($v['Variable']['result_arr'] == 'fixed')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value = "fixed" >Fixed</option>
                                                    <option <?php
                                                    if ($v['Variable']['result_arr'] == 'variable')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value = "variable" >Variable</option>
                                                </select>							
                                            </td>
                                        </tr>							
                                        <tr <?php
                                        if ($getData['Library']['library_authentication_method'] != "sip2_var" && $getData['Library']['library_authentication_method'] != "sip2_var_wo_pin")
                                        {
                                            ?>style="display:none;"<?php } ?>>
                                            <td align="right" width="250"><?php echo $this->Form->label(null, 'Message No'); ?></td>
                                            <td align="left" style="padding-left:20px" class="libselect">
                                                <?php $messageVar = $v['Variable']['message_no']; ?>
                                                <select name="data[Variable][<?php echo $k; ?>][message_no]" id="msg<?php echo $k; ?>">
                                                    <option value="">Select a Message No</option>
                                                    <option <?php
                                                    if ($messageVar == '24')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value = "24" >24</option>
                                                    <option <?php
                                                    if ($messageVar == '64')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value="64">64</option>
                                                    <option <?php
                                                    if ($messageVar == '98')
                                                    {
                                                        ?> selected = "selected" <?php } ?> value="98">98</option>
                                                </select>							
                                            </td>
                                        </tr>							
                                        <tr>
                                            <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Error Message'); ?></td>
                                            <td aligh="left"  class="libalign"><input type="text" name="data[Variable][<?php echo $k; ?>][error_msg]" class="form_fields" size="50" value="<?php echo $v['Variable']['error_msg']; ?>"><?php
                                                if ($k == 0)
                                                {
                                                    ?><input type="button" value="+" class="form_fields" onClick="addVariable(<?php echo $count; ?>);"><?php
                                                }
                                                else
                                                {
                                                    ?><input type="button" value="Remove" class="form_fields" onClick="removeVariable(<?php echo $k; ?>);"><?php } ?></td>
                                        </tr>
                                    </table>
                                    <?php
                                    $j++;
                                }
                            }
                            ?>
                        </td></tr>
                    <tr><td colspan="2"></td></tr>
                    <tr><td colspan="2"><?php echo $this->Form->label('Template Settings'); ?></td></tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Background Color'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_bgcolor', array('label' => false, 'value' => $getData['Library']['library_bgcolor'], 'div' => false, 'class' => 'form_fields', 'size' => 6, 'readonly' => 'readonly')); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Navigation Background Color'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_nav_bgcolor', array('label' => false, 'value' => $getData['Library']['library_nav_bgcolor'], 'div' => false, 'class' => 'form_fields', 'size' => 6, 'readonly' => 'readonly')); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Box Header Background Color'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_boxheader_bgcolor', array('label' => false, 'value' => $getData['Library']['library_boxheader_bgcolor'], 'div' => false, 'class' => 'form_fields', 'size' => 6, 'readonly' => 'readonly')); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Box Header Text Color'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_boxheader_text_color', array('label' => false, 'value' => $getData['Library']['library_boxheader_text_color'], 'div' => false, 'class' => 'form_fields', 'size' => 6, 'readonly' => 'readonly')); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Box Header Links Color'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_box_header_color', array('label' => false, 'value' => $getData['Library']['library_box_header_color'], 'div' => false, 'class' => 'form_fields', 'size' => 6, 'readonly' => 'readonly')); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Box Header Links Hover Color'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_box_hover_color', array('label' => false, 'value' => $getData['Library']['library_box_hover_color'], 'div' => false, 'class' => 'form_fields', 'size' => 6, 'readonly' => 'readonly')); ?></td>
                    </tr>						
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Text Color'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_text_color', array('label' => false, 'value' => $getData['Library']['library_text_color'], 'div' => false, 'class' => 'form_fields', 'size' => 6, 'readonly' => 'readonly')); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Page Links Color'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_links_color', array('label' => false, 'value' => $getData['Library']['library_links_color'], 'div' => false, 'class' => 'form_fields', 'size' => 6, 'readonly' => 'readonly')); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Page Links Hover Color'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_links_hover_color', array('label' => false, 'value' => $getData['Library']['library_links_hover_color'], 'div' => false, 'class' => 'form_fields', 'size' => 6, 'readonly' => 'readonly')); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Navigation Links Color'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_navlinks_color', array('label' => false, 'value' => $getData['Library']['library_navlinks_color'], 'div' => false, 'class' => 'form_fields', 'size' => 6, 'readonly' => 'readonly')); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Navigation Links Hover Color'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_navlinks_hover_color', array('label' => false, 'value' => $getData['Library']['library_navlinks_hover_color'], 'div' => false, 'class' => 'form_fields', 'size' => 6, 'readonly' => 'readonly')); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->button('Preview', array('type' => 'button', 'id' => 'preview')); ?></td>
                    </tr>
                    <tr><td colspan="2"></td></tr>
                    <tr><td colspan="2"><?php echo $this->Form->label('Contact'); ?></td></tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('First Name'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_contact_fname', array('label' => false, 'value' => $getData['Library']['library_contact_fname'], 'div' => false, 'class' => 'form_fields')); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Last Name'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_contact_lname', array('label' => false, 'value' => $getData['Library']['library_contact_lname'], 'div' => false, 'class' => 'form_fields')); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Email Address'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_contact_email', array('label' => false, 'value' => $getData['Library']['library_contact_email'], 'div' => false, 'class' => 'form_fields')); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Phone Number'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_phone', array('label' => false, 'value' => $getData['Library']['library_phone'], 'div' => false, 'class' => 'form_fields')); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Address'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_address', array('label' => false, 'type' => 'textarea', 'rows' => 4, 'value' => $getData['Library']['library_address'], 'div' => false, 'style' => 'width:40%', 'class' => 'form_fields')); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Address2'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_address2', array('label' => false, 'type' => 'textarea', 'rows' => 4, 'value' => $getData['Library']['library_address2'], 'div' => false, 'style' => 'width:40%', 'class' => 'form_fields')); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('City'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_city', array('label' => false, 'value' => $getData['Library']['library_city'], 'div' => false, 'class' => 'form_fields')); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('State'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_state', array('label' => false, 'value' => $getData['Library']['library_state'], 'div' => false, 'class' => 'form_fields')); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Zip'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_zipcode', array('label' => false, 'value' => $getData['Library']['library_zipcode'], 'div' => false, 'class' => 'form_fields')); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Country'); ?></td>
                        <td align="left"><?php echo $this->Form->input('library_country', array('label' => false, 'value' => $getData['Library']['library_country'], 'div' => false, 'class' => 'form_fields')); ?></td>
                    </tr>
                    <tr><td colspan="2"></td></tr>
                    <tr>
                        <td align="right" width="255"><?php echo $this->Form->label(null, 'Choose Territory'); ?></td>
                        <td align="left">
                            <?php
                            echo $this->Form->input('library_territory', array('options' => $territory, 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['Library']['library_territory'])
                            );
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" width="255"><?php echo $this->Form->label(null, 'Choose Language'); ?></td>
                        <td align="left">
                            <?php
                            echo $this->Form->input('library_language', array('options' => array(
                                    'en' => 'English',
                                    'es' => 'Spanish', 'it' => 'Italian'), 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['Library']['library_language'])
                            );
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" width="255"><?php echo $this->Form->label(null, 'Expiration Date Format'); ?></td>
                        <td align="left">
                            <?php
                            $option_array = array(
                                '0' => 'Select a Expiration Date Format',
                                'MM-DD-YYYY' => 'MM-DD-YYYY',
                                'DD-MM-YYYY' => 'DD-MM-YYYY',
                                'MM-DD-YY' => 'MM-DD-YY',
                                'DD-MM-YY' => 'DD-MM-YY',
                                'YYYYMMDD' => 'YYYYMMDD',
                                'YYYY-MM-DD' => 'YYYY-MM-DD',
                            );
                            echo $this->Form->input('library_exp_date_format', array('options' => $option_array, 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['Library']['library_exp_date_format'])
                            );
                            ?>
                        </td>
                    </tr> 
                    <tr>
                        <td align="right" width="255"><?php echo $this->Form->label(null, 'Select minimum card length ( If Applicable )'); ?></td>
                        <td align="left">
                            <?php
                            echo $this->Form->input('minimum_card_length', array('options' => array(
                                    '1' => '1',
                                    '2' => '2',
                                    '3' => '3',
                                    '4' => '4',
                                    '5' => '5'), 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['Library']['minimum_card_length'])
                            );
                            ?>
                        </td>
                    </tr>
                    <tr><td colspan="2"></td></tr>
                    <tr><td colspan="2"><?php echo $this->Form->label('Logo Upload ( Image height should not exceed 60 pixels )'); ?></td></tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Select File'); ?></td>
                        <td align="left">
                            <input type="file" name="fileToUpload" id="fileToUpload" class="form_fields" />
                        </td>
                    </tr>
                    <?php
                    if ($getData['Library']['library_image_name'] != "")
                    {
                        ?>
                        <tr>
                            <td align="right" width="250" valign="top"><?php echo $this->Form->label('Preview'); ?></td>
                            <td align="left">
                                <?php echo $html->image($cdnPath . 'libraryimg/' . $getData['Library']['library_image_name'], array('alt' => 'Library Image', 'class' => 'form_fields', 'id' => 'imagePreview')) ?>
                            </td>
                        </tr>
                    <?php } ?>

                    <tr><td colspan="2"><?php echo $this->Form->label('Social Networking Settings'); ?></td></tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Twiter link'); ?></td>
                        <td aligh="left"><?php echo $this->Form->input('twiter_icon', array('label' => false, 'value' => $getData['Library']['twiter_icon'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr>       
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Fecbook Link'); ?></td>
                        <td aligh="left"><?php echo $this->Form->input('facebook_icon', array('label' => false, 'value' => $getData['Library']['facebook_icon'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr> 
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label(null, 'Youtube Link'); ?></td>
                        <td aligh="left"><?php echo $this->Form->input('youtube_icon', array('label' => false, 'value' => $getData['Library']['youtube_icon'], 'div' => false, 'class' => 'form_fields', 'size' => 50)); ?></td>
                    </tr>		
                    <tr>
                        <td colspan="2" align="right"><?php echo $this->Form->button('Next >', array('type' => 'button', 'id' => 'next_btn1')); ?></td>
                    </tr>
                </table>
            </div>
            <div id="form_step2" class="form_steps" style="display: none;">
                <h1>User Accounts</h1>
                <table cellspacing="10" cellpadding="0" border="0">
                    <tr><td id="formError2" class="formError" colspan="2"></td></tr>
                    <tr><td colspan="2"><?php echo $this->Form->label('Admin'); ?></td></tr>
                    <tr><td colspan="2"></td></tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('First Name'); ?></td>
                        <td align="left"><?php echo $this->Form->input('User.first_name', array('label' => false, 'value' => $getData['User']['first_name'], 'div' => false, 'class' => 'form_fields')); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Last Name'); ?></td>
                        <td align="left"><?php echo $this->Form->input('User.last_name', array('label' => false, 'value' => $getData['User']['last_name'], 'div' => false, 'class' => 'form_fields')); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Email'); ?></td>
                        <td align="left"><?php echo $this->Form->input('User.email', array('label' => false, 'value' => $getData['User']['email'], 'div' => false, 'class' => 'form_fields')); ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Password'); ?></td>
                        <td align="left"><?php echo $this->Form->input('User.password', array('type' => 'password', 'label' => false, 'value' => '', 'div' => false, 'class' => 'form_fields')); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right"><?php echo $this->Form->button('Next >', array('type' => 'button', 'id' => 'next_btn2')); ?></td>
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
                            if ($getData['Library']['library_download_limit'] != '5000' && $getData['Library']['library_download_limit'] != '10000' && $getData['Library']['library_download_limit'] != '15000' && $getData['Library']['library_download_limit'] != '20000' && $getData['Library']['library_download_limit'] != '')
                            {
                                $default_download_limit = 'manual';
                            }
                            else
                            {
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
                            <span id="manual_download" <?php
                            if ($default_download_limit != "manual")
                            {
                                ?> style="display:none" <?php } ?>>
                                  <?
                                  echo $this->Form->input('library_download_limit_manual', array('label' => false, 'value' => $getData['Library']['library_download_limit'], 'div' => false, 'class' => 'form_fields'));
                                  ?>
                            </span>
                        </td>
                    </tr>
                    <tr><td colspan="2"></td></tr>
                    <tr><td colspan="2"></td></tr>
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
                    <tr><td colspan="2"></td></tr>
                    <tr><td colspan="2"></td></tr>
                    <tr id="block_explicit" <?php
                    if ($getData['Library']['library_authentication_method'] == "soap")
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="left" colspan="2">
                            <?php
                            if ($getData['Library']['library_block_explicit_content'] == 0)
                            {
                                $checked = false;
                            }
                            elseif ($getData['Library']['library_block_explicit_content'] == 1)
                            {
                                $checked = true;
                            }
                            echo $this->Form->checkbox('library_block_explicit_content', array('label' => false, 'div' => false, 'class' => 'form_fields', 'checked' => $checked));
                            ?>
                            <?php echo $this->Form->label('Block Explicit Content'); ?>
                        </td>
                    </tr>
                    <tr><td colspan="2"></td></tr>
                    <tr>
                        <td colspan="2" align="right"><?php echo $this->Form->button('Next >', array('type' => 'button', 'id' => 'next_btn3')); ?></td>
                    </tr>
                </table>
            </div>
            <div id="form_step4" class="form_steps" style="display: none;">
                <h1>User Download Control</h1>
                <table cellspacing="10" cellpadding="0" border="0">
                    <tr><td id="formError4" class="formError" colspan="2"></td></tr>
                    
                    
                      <tr>
                        <td width="200" align="left"><?php echo $this->Form->label('Library Streaming is Allowed'); ?></td>
                        <td  style="font-size:12px;">
                            <input id="redio1" type="radio" name="data[Library][library_type]" value="2" class="form_fields" <?php
                            if ($getData['Library']['library_type'] == 2)
                            {
                                ?> checked="checked" <?php } ?>> Allowed
                            <input id="redio2" type="radio" name="data[Library][library_type]" value="1" class="form_fields"  <?php
                            if ($getData['Library']['library_type'] == 1)
                            {
                                ?> checked="checked" <?php } ?>> Not Allowed
                        </td>
                    </tr>
                    
                    
                    
                    
                    
                    <tr><td colspan="2"></td></tr>
                    <tr>
                        <td align="left" width="100">
                            <?php echo $this->Form->label('Per Week'); ?>
                        </td>
                        <td align="left">
                            <?php
                            echo $form->input('library_user_download_limit', array('options' => array(
                                    '' => 'Number Of Songs',
                                    '1' => '1',
                                    '2' => '2',
                                    '3' => '3',
                                    '4' => '4',
                                    '5' => '5',
                                    '6' => '6',
                                    '7' => '7',
                                    '8' => '8',
                                    '9' => '9',
                                    '10' => '10',
                                    '15' => '15',
                                    '20' => '20'
                                ), 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['Library']['library_user_download_limit'])
                            );
                            ?>
                        </td>
                    </tr>
                    <tr><td colspan="2"></td></tr>
                    <tr>
                        <td align="left" width="150">
                            <?php echo $this->Form->label('Streaming Hours'); ?>
                        </td>
                        <td align="left">
                            <?php
                            $hoursArray = array();
                            for($ik=0;$ik<=24;$ik++){
                                $hoursArray[$ik] = $ik;
                            }
                            
                            echo $form->input('library_streaming_hours', array('options' => $hoursArray, 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['Library']['library_streaming_hours'])
                            );
                            ?>&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-size:8%;'><br/>(0 hour means no streaming,24 hours means unlimited streaming)</span>
                        </td>
                    </tr>
                    <tr><td colspan="2"></td></tr>
                    <tr><td colspan="2"></td></tr>
                    <tr><td colspan="2"></td></tr>
                    <tr>
                        <td colspan="2" align="right"><?php echo $this->Form->button('Next >', array('type' => 'button', 'id' => 'next_btn4')); ?></td>
                    </tr>
                </table>
            </div>
            <div id="form_step5" class="form_steps" style="display: none;">
                <h1>Purchase Downloads</h1>
                <table cellspacing="10" cellpadding="0" border="0">
                    <tr><td id="formError5" class="formError" colspan="2"></td></tr
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Library Download Type'); ?></td>
                        <td  style="font-size:12px;">
                            <input id="redio1" type="radio" name="data[Library][library_unlimited]" value="0" class="form_fields" onClick="get_purFields('0');" <?php
                            if ($getData['Library']['library_unlimited'] == 0)
                            {
                                ?> checked="checked" <?php } ?>>A la Carte
                            <input id="redio2" type="radio" name="data[Library][library_unlimited]" value="1" class="form_fields" onClick="get_purFields('1');" <?php
                            if ($getData['Library']['library_unlimited'] == 1)
                            {
                                ?> checked="checked" <?php } ?>>Unlimited
                        </td>
                    </tr>
                    <tr><td colspan="2"></td></tr>                   
                   
                    <tr>
                        <td align="right" width="250"><?php echo $this->Form->label('Create a New Contract'); ?></td>
                        <td>
                            <input type="checkbox" id="LibraryShowContract" onclick="showContract()" class="form_fields">
                        </td>
                    </tr>					
                    <tr id="contract_start">
                        <td align="right" width="250"><?php echo $this->Form->label('Library Contract Start Date'); ?></td>
                        <td align="left"><?php echo $this->Form->input('Library.library_contract_start_date', array('label' => false, 'div' => false, 'class' => 'form_fields', 'value' => $getData['Library']['library_contract_start_date'], 'readonly' => 'readonly', 'type' => 'text')); ?><input type="hidden" id="contractStart" value="<?php echo $getData['Library']['library_contract_start_date']; ?>"></td>
                    </tr>
                    <tr id="contract_end">
                        <td align="right" width="250"><?php echo $this->Form->label('Library Contract End Date'); ?></td>
                        <td align="left"><?php echo $this->Form->input('Library.library_contract_end_date', array('label' => false, 'div' => false, 'class' => 'form_fields', 'value' => $getData['Library']['library_contract_end_date'], 'readonly' => 'readonly', 'type' => 'text')); ?><input type="hidden" id="contractEnd" value="<?php echo $getData['Library']['library_contract_end_date']; ?>"></td>
                    </tr>
                    <tr <?php
                    if ($getData['Library']['library_unlimited'] == 1 || $getData['Library']['library_contract_start_date'] == '')
                    {
                        ?> style="display:none;" <?php } ?> id="upgrd">
                        <td align="right" width="250"><?php echo $this->Form->label(' Upgrade Current Library Contract'); ?></td>
                        <td  align="left" style="padding-left:20px;"><?php echo $this->Form->button('Upgrade', array('type' => 'button', 'id' => 'upgrade')); ?></td
                    </tr>
                    <tr id="pur_order" style="display:none;">
                        <td align="right" width="250"><?php echo $this->Form->label('Purchase Order #'); ?></td>
                        <td align="left"><?php echo $this->Form->input('LibraryPurchase.purchased_order_num', array('label' => false, 'value' => '', 'div' => false, 'class' => 'form_fields')); ?></td>
                    </tr>
                    <tr id="pur_track" style="display:none;">
                        <td align="right" width="250"><?php echo $this->Form->label('# of Purchased Tracks'); ?></td>
                        <td align="left"><?php echo $this->Form->input('LibraryPurchase.purchased_tracks', array('label' => false, 'value' => '', 'div' => false, 'class' => 'form_fields')); ?></td>
                    </tr>
                    <tr id="pur_amount" style="display:none;">
                        <td align="right" width="250"><?php echo $this->Form->label('Purchased Amount in $'); ?></td>
                        <td align="left"><?php echo $this->Form->input('LibraryPurchase.purchased_amount', array('label' => false, 'value' => '', 'div' => false, 'class' => 'form_fields')); ?></td>
                    </tr>
                    <tr><td colspan="2"></td></tr>
                    <tr>
                        <td colspan="2" align="right"><?php echo $this->Form->button('Save', array('type' => 'button', 'id' => 'next_btn5')); ?></td>
                    </tr>
                    <tr><td colspan="2"></td></tr>
                </table>
                <?php
                if ($getData['Library']['id'] != "")
                {
                    ?>
                    <h1>Previously Purchased Downloads</h1>
                    <table cellspacing="10" cellpadding="0" border="0">
                        <?php
                        if (count($allPurchases) == 0)
                        {
                            ?>
                            <tr>
                                <td colspan="2"><label>There are no current purchases data available for this library at this moment.</label></td>
                            </tr>
                            <?php
                        }
                        else
                        {
                            ?>
                            <tr>
                                <th><label><b>No.</b></label></th>
                                <th><label><b>Purchase Order #</b></label></th>
                                <th><label><b># Of Purchased Tracks</b></label></th>
                                <th><label><b>Purchased Amount In $</b></label></th>
                                <th><label><b>Purchase Entry Date</b></lable></th>
                            </tr>
                            <?php
                            foreach ($allPurchases as $key => $purchases)
                            {
                                ?>
                                <tr>
                                    <td><label><?php echo $key + 1; ?></label></td>
                                    <td><label><?php echo $purchases['LibraryPurchase']['purchased_order_num']; ?></label></td>
                                    <td><label><?php echo $purchases['LibraryPurchase']['purchased_tracks']; ?></label></td>
                                    <td><label>$<?php echo $purchases['LibraryPurchase']['purchased_amount']; ?></label></td>
                                    <td><label><?php echo $purchases['LibraryPurchase']['created']; ?></label></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        <tr><td colspan="2"></td></tr>
                        <tr><td colspan="2"></td></tr>
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
if (isset($javascript))
{
    ?>
    <script type="text/javascript" src="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/js&amp;f=page_specific/libraries_create.js,page_specific/ajaxfileupload.js,datepicker/jquery.ui.core.js,datepicker/jquery.ui.widget.js,datepicker/jquery.ui.datepicker.js"></script>
    <link type="text/css" rel="stylesheet" href="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/css&amp;f=flick/jquery-ui-1.8.custom.css" />
    <script type="text/javascript">
                                        $(function() {
                                            $("#LibraryLibraryContractStartDate").datepicker({showWeek: true, firstDay: 1, numberOfMonths: 3, dateFormat: 'yy-mm-dd', onSelect: function(date) {
                                                    oDate = $("#LibraryLibraryContractStartDate").datepicker("getDate");
                                                    oDate.setDate(oDate.getDate() + 365);
                                                    var MM = oDate.getMonth() + 1;
                                                    var DD = oDate.getDate();
                                                    var YY = oDate.getFullYear();
                                                    if (MM < 10)
                                                        MM = "0" + MM;
                                                    if (DD < 10)
                                                        DD = "0" + DD;
                                                    $("#LibraryLibraryContractEndDate").val(YY + "-" + MM + "-" + DD);
                                                }
                                            });
                                            $("#LibraryLibraryContractEndDate").datepicker({showWeek: true, firstDay: 1, numberOfMonths: 3, dateFormat: 'yy-mm-dd'});
                                            $("#LibraryLibraryAuthenticationMethod").change(function() {
                                                $("#dropDown").val($(this).val());
                                                if ($(this).val() == 'referral_url') {
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
                                                    $("#sip_location").hide();
                                                    $("#sip_terminal").hide();
                                                    $("#sip_version").hide();
                                                    $("#sip_password").hide();
                                                    $("#sip_error").hide();
                                                    $("#sip_institution").hide();
                                                    $("#ezproxy_secret").hide();
                                                    $("#ezproxy_referral").hide();
                                                    $("#ezproxy_name").hide();
                                                    $("#ezproxy_logout").hide();
                                                    $("#soap").hide();
                                                    $("#block_explicit").show();
                                                    $("#space").hide();
                                                    $("#24_message").hide();
                                                    $("#sip_over_ssh").hide();
                                                    $("#ssh_command").hide();
                                                    $("#64_message").hide();
                                                    $("#curl_url").hide();
                                                    $("#curl_db").hide();
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
                                                    $("#sip_location").hide();
                                                    $("#sip_terminal").hide();
                                                    $("#sip_version").hide();
                                                    $("#sip_password").hide();
                                                    $("#sip_error").hide();
                                                    $("#sip_institution").hide();
                                                    $("#ezproxy_secret").hide();
                                                    $("#ezproxy_referral").hide();
                                                    $("#ezproxy_name").hide();
                                                    $("#ezproxy_logout").hide();
                                                    $("#soap").hide();
                                                    $("#block_explicit").show();
                                                    $("#space").hide();
                                                    $("#24_message").hide();
                                                    $("#sip_over_ssh").hide();
                                                    $("#ssh_command").hide();
                                                    $("#64_message").hide();
                                                    $("#curl_url").hide();
                                                    $("#curl_db").hide();
                                                }
                                                else if ($(this).val() == 'mdlogin_reference') {
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
                                                    $("#sip_location").hide();
                                                    $("#sip_terminal").hide();
                                                    $("#sip_version").hide();
                                                    $("#sip_password").hide();
                                                    $("#sip_error").hide();
                                                    $("#sip_institution").hide();
                                                    $("#ezproxy_secret").hide();
                                                    $("#ezproxy_referral").hide();
                                                    $("#ezproxy_name").hide();
                                                    $("#ezproxy_logout").hide();
                                                    $("#soap").hide();
                                                    $("#block_explicit").show();
                                                    $("#space").hide();
                                                    $("#24_message").hide();
                                                    $("#sip_over_ssh").hide();
                                                    $("#ssh_command").hide();
                                                    $("#64_message").hide();
                                                    $("#curl_url").hide();
                                                    $("#curl_db").hide();
                                                }
                                                else if ($(this).val() == 'mndlogin_reference') {
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
                                                    $("#sip_location").hide();
                                                    $("#sip_terminal").hide();
                                                    $("#sip_version").hide();
                                                    $("#sip_password").hide();
                                                    $("#sip_error").hide();
                                                    $("#sip_institution").hide();
                                                    $("#ezproxy_secret").hide();
                                                    $("#ezproxy_referral").hide();
                                                    $("#ezproxy_name").hide();
                                                    $("#ezproxy_logout").hide();
                                                    $("#soap").hide();
                                                    $("#block_explicit").show();
                                                    $("#space").hide();
                                                    $("#24_message").hide();
                                                    $("#sip_over_ssh").hide();
                                                    $("#ssh_command").hide();
                                                    $("#64_message").hide();
                                                    $("#curl_url").hide();
                                                    $("#curl_db").hide();
                                                }
                                                else if ($(this).val() == 'innovative_var') {
                                                    $("#allurl").show();
                                                    $("#referral_url").hide();
                                                    $("#innovative1").show();
                                                    $("#innovative2").show();
                                                    $("#innv_var").show();
                                                    //$("#innovative_var_pin").hide();
                                                    //$("#variable").hide();						
                                                    $("#sip_host").hide();
                                                    $("#sip_port").hide();
                                                    $("#sip_pin").hide();
                                                    $("#sip_login").hide();
                                                    $("#sip_location").hide();
                                                    $("#sip_terminal").hide();
                                                    $("#sip_version").hide();
                                                    $("#sip_password").hide();
                                                    $("#sip_error").hide();
                                                    $("#sip_institution").hide();
                                                    $("#ezproxy_secret").hide();
                                                    $("#ezproxy_referral").hide();
                                                    $("#ezproxy_name").hide();
                                                    $("#ezproxy_logout").hide();
                                                    $("#soap").hide();
                                                    $("#block_explicit").show();
                                                    $("#msgNo").hide();
                                                    $("#space").hide();
                                                    $("#24_message").hide();
                                                    $("#sip_over_ssh").hide();
                                                    $("#ssh_command").hide();
                                                    $("#64_message").hide();
                                                    $("#curl_url").hide();
                                                    $("#curl_db").hide();
                                                }
                                                else if ($(this).val() == 'capita') {
                                                    $("#allurl").show();
                                                    $("#referral_url").hide();
                                                    $("#innovative1").show();
                                                    $("#innovative2").show();
                                                    $("#innv_var").show();
                                                    //$("#innovative_var_pin").hide();
                                                    //$("#variable").hide();						
                                                    $("#sip_host").hide();
                                                    $("#sip_port").hide();
                                                    $("#sip_pin").hide();
                                                    $("#sip_login").hide();
                                                    $("#sip_location").hide();
                                                    $("#sip_terminal").hide();
                                                    $("#sip_version").hide();
                                                    $("#sip_password").hide();
                                                    $("#sip_error").hide();
                                                    $("#sip_institution").hide();
                                                    $("#ezproxy_secret").hide();
                                                    $("#ezproxy_referral").hide();
                                                    $("#ezproxy_name").hide();
                                                    $("#ezproxy_logout").hide();
                                                    $("#soap").hide();
                                                    $("#block_explicit").show();
                                                    $("#msgNo").hide();
                                                    $("#space").hide();
                                                    $("#24_message").hide();
                                                    $("#sip_over_ssh").hide();
                                                    $("#ssh_command").hide();
                                                    $("#64_message").hide();
                                                    $("#curl_url").hide();
                                                    $("#curl_db").hide();
                                                }
                                                else if ($(this).val() == 'symws') {
                                                    $("#allurl").show();
                                                    $("#referral_url").hide();
                                                    $("#innovative1").show();
                                                    $("#innovative2").show();
                                                    $("#innv_var").show();
                                                    //$("#innovative_var_pin").hide();
                                                    //$("#variable").hide();						
                                                    $("#sip_host").show();
                                                    $("#sip_port").hide();
                                                    $("#sip_pin").hide();
                                                    $("#sip_login").hide();
                                                    $("#sip_location").hide();
                                                    $("#sip_terminal").hide();
                                                    $("#sip_version").hide();
                                                    $("#sip_password").hide();
                                                    $("#sip_error").hide();
                                                    $("#sip_institution").hide();
                                                    $("#ezproxy_secret").hide();
                                                    $("#ezproxy_referral").hide();
                                                    $("#ezproxy_name").hide();
                                                    $("#ezproxy_logout").hide();
                                                    $("#soap").hide();
                                                    $("#block_explicit").show();
                                                    $("#msgNo").hide();
                                                    $("#space").hide();
                                                    $("#24_message").hide();
                                                    $("#sip_over_ssh").hide();
                                                    $("#ssh_command").hide();
                                                    $("#64_message").hide();
                                                    $("#curl_url").hide();
                                                    $("#curl_db").hide();
                                                }
                                                else if ($(this).val() == 'innovative_var_https') {
                                                    $("#allurl").show();
                                                    $("#referral_url").hide();
                                                    $("#innovative1").show();
                                                    $("#innovative2").show();
                                                    $("#innv_var").show();
                                                    //$("#innovative_var_pin").hide();
                                                    //$("#variable").hide();						
                                                    $("#sip_host").hide();
                                                    $("#sip_port").hide();
                                                    $("#sip_pin").hide();
                                                    $("#sip_login").hide();
                                                    $("#sip_location").hide();
                                                    $("#sip_terminal").hide();
                                                    $("#sip_version").hide();
                                                    $("#sip_error").hide();
                                                    $("#sip_institution").hide();
                                                    $("#sip_password").hide();
                                                    $("#ezproxy_secret").hide();
                                                    $("#ezproxy_referral").hide();
                                                    $("#ezproxy_name").hide();
                                                    $("#ezproxy_logout").hide();
                                                    $("#soap").hide();
                                                    $("#block_explicit").show();
                                                    $("#msgNo").hide();
                                                    $("#space").hide();
                                                    $("#24_message").hide();
                                                    $("#sip_over_ssh").hide();
                                                    $("#ssh_command").hide();
                                                    $("#64_message").hide();
                                                    $("#curl_url").hide();
                                                    $("#curl_db").hide();
                                                }
                                                else if ($(this).val() == 'innovative_var_https_wo_pin') {
                                                    $("#allurl").show();
                                                    $("#referral_url").hide();
                                                    $("#innovative1").show();
                                                    $("#innovative2").show();
                                                    $("#innv_var").show();
                                                    //$("#innovative_var_pin").hide();
                                                    //$("#variable").hide();						
                                                    $("#sip_host").hide();
                                                    $("#sip_port").hide();
                                                    $("#sip_pin").hide();
                                                    $("#sip_login").hide();
                                                    $("#sip_location").hide();
                                                    $("#sip_terminal").hide();
                                                    $("#sip_version").hide();
                                                    $("#sip_error").hide();
                                                    $("#sip_institution").hide();
                                                    $("#sip_password").hide();
                                                    $("#ezproxy_secret").hide();
                                                    $("#ezproxy_referral").hide();
                                                    $("#ezproxy_name").hide();
                                                    $("#ezproxy_logout").hide();
                                                    $("#soap").hide();
                                                    $("#block_explicit").show();
                                                    $("#msgNo").hide();
                                                    $("#space").hide();
                                                    $("#24_message").hide();
                                                    $("#sip_over_ssh").hide();
                                                    $("#ssh_command").hide();
                                                    $("#64_message").hide();
                                                    $("#curl_url").hide();
                                                    $("#curl_db").hide();
                                                }
                                                else if ($(this).val() == 'innovative_https') {
                                                    $("#allurl").show();
                                                    $("#referral_url").hide();
                                                    $("#innovative1").show();
                                                    $("#innovative2").show();
                                                    $("#innv_var").show();
                                                    //$("#innovative_var_pin").hide();
                                                    //$("#variable").hide();						
                                                    $("#sip_host").hide();
                                                    $("#sip_port").hide();
                                                    $("#sip_pin").hide();
                                                    $("#sip_login").hide();
                                                    $("#sip_location").hide();
                                                    $("#sip_terminal").hide();
                                                    $("#sip_version").hide();
                                                    $("#sip_password").hide();
                                                    $("#sip_error").hide();
                                                    $("#sip_institution").hide();
                                                    $("#ezproxy_secret").hide();
                                                    $("#ezproxy_referral").hide();
                                                    $("#ezproxy_name").hide();
                                                    $("#ezproxy_logout").hide();
                                                    $("#soap").hide();
                                                    $("#block_explicit").show();
                                                    $("#space").hide();
                                                    $("#24_message").hide();
                                                    $("#sip_over_ssh").hide();
                                                    $("#ssh_command").hide();
                                                    $("#64_message").hide();
                                                    $("#curl_url").hide();
                                                    $("#curl_db").hide();
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
                                                    $("#sip_location").hide();
                                                    $("#sip_terminal").hide();
                                                    $("#sip_version").hide();
                                                    $("#sip_error").hide();
                                                    $("#sip_password").hide();
                                                    $("#sip_location").hide();
                                                    $("#sip_institution").hide();
                                                    $("#ezproxy_secret").hide();
                                                    $("#ezproxy_referral").hide();
                                                    $("#ezproxy_name").hide();
                                                    $("#ezproxy_logout").hide();
                                                    $("#soap").hide();
                                                    $("#block_explicit").show();
                                                    $("#space").hide();
                                                    $("#24_message").hide();
                                                    $("#sip_over_ssh").hide();
                                                    $("#ssh_command").hide();
                                                    $("#64_message").hide();
                                                    $("#curl_url").hide();
                                                    $("#curl_db").hide();
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
                                                    $("#sip_terminal").show();
                                                    $("#sip_version").hide();
                                                    $("#sip_institution").hide();
                                                    $("#sip_error").hide();
                                                    $("#ezproxy_secret").hide();
                                                    $("#ezproxy_referral").hide();
                                                    $("#ezproxy_name").hide();
                                                    $("#ezproxy_logout").hide();
                                                    $("#soap").hide();
                                                    $("#block_explicit").show();
                                                    $("#space").hide();
                                                    $("#24_message").hide();
                                                    $("#sip_over_ssh").hide();
                                                    $("#ssh_command").hide();
                                                    $("#64_message").hide();
                                                    $("#curl_url").hide();
                                                    $("#curl_db").hide();
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
                                                    $("#sip_terminal").show();
                                                    $("#sip_version").hide();
                                                    $("#sip_institution").hide();
                                                    $("#sip_error").hide();
                                                    $("#ezproxy_secret").hide();
                                                    $("#ezproxy_referral").hide();
                                                    $("#ezproxy_name").hide();
                                                    $("#ezproxy_logout").hide();
                                                    $("#soap").hide();
                                                    $("#block_explicit").show();
                                                    $("#space").hide();
                                                    $("#24_message").hide();
                                                    $("#sip_over_ssh").hide();
                                                    $("#ssh_command").hide();
                                                    $("#64_message").hide();
                                                    $("#curl_url").hide();
                                                    $("#curl_db").hide();
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
                                                    $("#sip_terminal").show();
                                                    $("#sip_version").show();
                                                    $("#sip_institution").show();
                                                    $("#sip_error").show();
                                                    $("#ezproxy_secret").hide();
                                                    $("#ezproxy_referral").hide();
                                                    $("#ezproxy_name").hide();
                                                    $("#ezproxy_logout").hide();
                                                    $("#soap").hide();
                                                    $("#block_explicit").show();
                                                    $("#msgNo").show();
                                                    $("#space").show();
                                                    $("#24_message").show();
                                                    $("#64_message").show();
                                                    $("#sip_over_ssh").show();
                                                    $("#ssh_command").show();
                                                    $("#curl_url").hide();
                                                    $("#curl_db").hide();
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
                                                    $("#sip_terminal").show();
                                                    $("#sip_version").show();
                                                    $("#sip_institution").show();
                                                    $("#sip_error").show();
                                                    $("#ezproxy_secret").hide();
                                                    $("#ezproxy_referral").hide();
                                                    $("#ezproxy_name").hide();
                                                    $("#ezproxy_logout").hide();
                                                    $("#soap").hide();
                                                    $("#block_explicit").show();
                                                    $("#msgNo").show();
                                                    $("#space").show();
                                                    $("#24_message").show();
                                                    $("#sip_over_ssh").show();
                                                    $("#ssh_command").show();
                                                    $("#64_message").show();
                                                    $("#curl_url").hide();
                                                    $("#curl_db").hide();
                                                }
                                                else if ($(this).val() == 'ezproxy') {
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
                                                    $("#sip_terminal").hide();
                                                    $("#sip_version").hide();
                                                    $("#sip_error").hide();
                                                    $("#sip_institution").hide();
                                                    $("#ezproxy_secret").show();
                                                    $("#ezproxy_referral").show();
                                                    $("#ezproxy_name").show();
                                                    $("#ezproxy_logout").show();
                                                    $("#soap").hide();
                                                    $("#block_explicit").show();
                                                    $("#space").hide();
                                                    $("#24_message").hide();
                                                    $("#sip_over_ssh").hide();
                                                    $("#ssh_command").hide();
                                                    $("#64_message").hide();
                                                    $("#curl_url").hide();
                                                    $("#curl_db").hide();
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
                                                    $("#sip_terminal").hide();
                                                    $("#sip_version").hide();
                                                    $("#sip_error").hide();
                                                    $("#sip_institution").hide();
                                                    $("#ezproxy_secret").hide();
                                                    $("#ezproxy_referral").hide();
                                                    $("#ezproxy_name").hide();
                                                    $("#ezproxy_logout").hide();
                                                    $("#soap").hide();
                                                    $("#block_explicit").show();
                                                    $("#msgNo").hide();
                                                    $("#space").hide();
                                                    $("#24_message").hide();
                                                    $("#sip_over_ssh").hide();
                                                    $("#ssh_command").hide();
                                                    $("#64_message").hide();
                                                    $("#curl_url").hide();
                                                    $("#curl_db").hide();
                                                }
                                                else if ($(this).val() == 'innovative_var_name') {
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
                                                    $("#sip_terminal").hide();
                                                    $("#sip_version").hide();
                                                    $("#sip_error").hide();
                                                    $("#sip_institution").hide();
                                                    $("#ezproxy_secret").hide();
                                                    $("#ezproxy_referral").hide();
                                                    $("#ezproxy_name").hide();
                                                    $("#ezproxy_logout").hide();
                                                    $("#soap").hide();
                                                    $("#block_explicit").show();
                                                    $("#msgNo").hide();
                                                    $("#space").hide();
                                                    $("#24_message").hide();
                                                    $("#sip_over_ssh").hide();
                                                    $("#ssh_command").hide();
                                                    $("#64_message").hide();
                                                    $("#curl_url").hide();
                                                    $("#curl_db").hide();
                                                }
                                                else if ($(this).val() == 'innovative_var_https_name') {
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
                                                    $("#sip_terminal").hide();
                                                    $("#sip_version").hide();
                                                    $("#sip_error").hide();
                                                    $("#sip_institution").hide();
                                                    $("#ezproxy_secret").hide();
                                                    $("#ezproxy_referral").hide();
                                                    $("#ezproxy_name").hide();
                                                    $("#ezproxy_logout").hide();
                                                    $("#soap").hide();
                                                    $("#block_explicit").show();
                                                    $("#msgNo").hide();
                                                    $("#space").hide();
                                                    $("#24_message").hide();
                                                    $("#sip_over_ssh").hide();
                                                    $("#ssh_command").hide();
                                                    $("#64_message").hide();
                                                    $("#curl_url").hide();
                                                    $("#curl_db").hide();
                                                }
                                                else if ($(this).val() == 'soap') {
                                                    $("#allurl").show();
                                                    $("#referral_url").hide();
                                                    $("#innovative1").show();
                                                    $("#innovative2").hide();
                                                    $("#soap").show();
                                                    $("#innv_var").hide();
                                                    //$("#innovative_var_pin").hide();
                                                    //$("#variable").hide();						
                                                    $("#sip_host").hide();
                                                    $("#sip_port").hide();
                                                    $("#sip_pin").hide();
                                                    $("#sip_login").hide();
                                                    $("#sip_location").hide();
                                                    $("#sip_terminal").hide();
                                                    $("#sip_version").hide();
                                                    $("#sip_error").hide();
                                                    $("#sip_password").hide();
                                                    $("#sip_institution").hide();
                                                    $("#ezproxy_secret").hide();
                                                    $("#ezproxy_referral").hide();
                                                    $("#ezproxy_name").hide();
                                                    $("#ezproxy_logout").hide();
                                                    $("#block_explicit").hide();
                                                    $("#space").hide();
                                                    $("#24_message").hide();
                                                    $("#sip_over_ssh").hide();
                                                    $("#ssh_command").hide();
                                                    $("#64_message").hide();
                                                    $("#curl_url").hide();
                                                    $("#curl_db").hide();
                                                }
                                                else if ($(this).val() == 'curl_method') {
                                                    $("#allurl").show();
                                                    $("#referral_url").hide();
                                                    $("#innovative1").hide();
                                                    $("#innovative2").hide();
                                                    $("#soap").hide();
                                                    $("#innv_var").hide();
                                                    //$("#innovative_var_pin").hide();
                                                    //$("#variable").hide();						
                                                    $("#sip_host").hide();
                                                    $("#sip_port").hide();
                                                    $("#sip_pin").hide();
                                                    $("#sip_login").hide();
                                                    $("#sip_location").hide();
                                                    $("#sip_terminal").hide();
                                                    $("#sip_version").hide();
                                                    $("#sip_error").hide();
                                                    $("#sip_password").hide();
                                                    $("#sip_institution").hide();
                                                    $("#ezproxy_secret").hide();
                                                    $("#ezproxy_referral").hide();
                                                    $("#ezproxy_name").hide();
                                                    $("#ezproxy_logout").hide();
                                                    $("#block_explicit").hide();
                                                    $("#space").hide();
                                                    $("#24_message").hide();
                                                    $("#sip_over_ssh").hide();
                                                    $("#ssh_command").hide();
                                                    $("#64_message").hide();
                                                    $("#curl_url").show();
                                                    $("#curl_db").show();
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
                                                    $("#sip_terminal").hide();
                                                    $("#sip_version").hide();
                                                    $("#sip_error").hide();
                                                    $("#sip_institution").hide();
                                                    $("#ezproxy_secret").hide();
                                                    $("#ezproxy_referral").hide();
                                                    $("#ezproxy_name").hide();
                                                    $("#ezproxy_logout").hide();
                                                    $("#soap").hide();
                                                    $("#block_explicit").show();
                                                    $("#space").hide();
                                                    $("#24_message").hide();
                                                    $("#sip_over_ssh").hide();
                                                    $("#ssh_command").hide();
                                                    $("#64_message").hide();
                                                    $("#curl_url").hide();
                                                    $("#curl_db").hide();
                                                }
                                            });
                                            $("#LibraryLibraryAuthenticationMethod").change(function() {
                                                $("#dropDown").val($(this).val());
                                                if ($(this).val() == 'referral_url') {
                                                }
                                            });
                                        });


    </script>
    <?php
}
?>
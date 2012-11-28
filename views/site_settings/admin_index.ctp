<?php
/*
 File Name : admin_index.ctp
 File Description : view page for admin index for site setting
 Author : m68interactive
 */
?>
<?php
    $this->pageTitle = 'Content'; 
    echo $this->Form->create('SiteSetting', array( 'action' => $formAction ));
?>
<fieldset>
    <legend>Site Setting</legend>
    <div class="formFieldsContainer">
        <div class="formFieldsbox">
            <div id="form_step" class="form_steps">
                <table cellspacing="10" cellpadding="0" border="0" width="100%">
                    <?php
                    if(!empty($siteConfig)) {
                        foreach($siteConfig as $k => $v) {
                            if($k == "suggestion_counter") {
                    ?>
                                <tr><td colspan="2" align="center"><h1>Suggestion Settings</h1></td></tr>
                                <tr>
                                    <td align="right" width="435"><?php echo $this->Form->label('Enter # of Songs to generate with Suggestion XML'); ?></td>
                                    <td align="left">
                                        <?php
                                            echo $this->Form->input($k,array('label' => false ,'value' => $v, 'div' => false, 'class' => 'form_fields'))."&nbsp;&nbsp;&nbsp;".$this->Form->button('Generate Suggestion XML', array('type' => 'button', 'id' => 'generate_suggestionXML'));
                                        ?>
                                    </td>
                                </tr>
                    <?php
                            }
                             if($k == "single_channel") {
                    ?>
                                <tr><td colspan="2" align="center"><h1>Single Channel Downloads</h1></td></tr>
                                <tr>
                                    <td align="right" width="435"><?php echo $this->Form->label('Enable Single Channel Downloads'); ?></td>
                                    <td align="left">
                                        <?php
                                            echo $this->Form->checkbox($k,array('label' => false ,'value' => $v, 'checked' => $v, 'div' => false, 'class' => 'form_fields'));
                                        ?>
                                    </td>
                                </tr>
                    <?php
                            }
                            if($k == "maintain_ldt") {
                    ?>
                                <tr><td colspan="2" align="center"><h1>Latest Downloads Table</h1></td></tr>
                                <tr>
                                    <td align="right" width="435"><?php echo $this->Form->label('Enable latest downloads table'); ?></td>
                                    <td align="left">
                                        <?php
                                            echo $this->Form->checkbox($k,array('label' => false ,'value' => $v, 'checked' => $v, 'div' => false, 'class' => 'form_fields'));
                                        ?>
                                    </td>
                                </tr>
                    <?php
                            }
                            if($k == "multiple_countries") {
                    ?>
                                <tr><td colspan="2" align="center"><h1>Multiple Countries Table</h1></td></tr>
                                <tr>
                                    <td align="right" width="435"><?php echo $this->Form->label('Enable Multiple Countries Table Option'); ?></td>
                                    <td align="left">
                                        <?php
                                            echo $this->Form->checkbox($k,array('label' => false ,'value' => $v, 'checked' => $v, 'div' => false, 'class' => 'form_fields'));
                                        ?>
                                    </td>
                                </tr>
                                    
                    <?php
                            }
                        }
                    ?>
                    <tr>
                        <td colspan="2" align="center"><?php echo $this->Form->submit('Save', array('id' => 'saveSettings'));?></td>
                    </tr>
                    <?php
                    }
                    else {
                    ?>
                    <tr>
                        <td colspan="2" align="center"><label>Currently there are no settings to make</label></td>
                    </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</fieldset>
<?php
    echo $this->Form->end();
    echo $session->flash();
    if (isset ($javascript)) {
?>
        <script type="text/javascript">
            $(function() {
                $("#generate_suggestionXML").click(function() {
                    $("#SiteSettingAdminIndexForm").attr('action','/admin/site_settings/generateXML');
                    $("#SiteSettingAdminIndexForm").submit();
                });
            });
        </script>
<?php
    }
?>
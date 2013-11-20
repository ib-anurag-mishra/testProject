<?php
/*
 File Name : admin_librarytimezoneform.ctp
 File Description : Add/Edit library timezone data
 Author : m68interactive
 */
?>
<?php
    $this->pageTitle = 'Admin';
    echo $this->Form->create();

    if( isset($getData) && empty($getData) )
    {
            $getData[0]['lbs']['library_name'] = "";
            $getData[0]['lt']['libraries_timezone'] = "";
            $getData[0]['lbs']['id'] = "";                     
    }
echo $this->Html->css('jquery.autocomplete');
?>

<script type="text/javascript" src="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/js&amp;f=jquery.autocomplete.js"></script>
<script lenguage="javscript">
    function pageRedirect(){
        window.location='http://<?= $_SERVER['HTTP_HOST']?>/admin/libraries/librarytimezone';
    }
</script>

  <script type="text/javascript">
      
        $(document).ready(function() {
            jQuery('#LibraryLibraryName').keypress(function(event) {
                                                    //auto_check();
                    if (event.which != '13') {
                        $('#auto').attr('value', 0);
                            }
            });					
                    jQuery("#LibraryLibraryName").autocomplete("<?php echo $this->webroot; ?>admin/libraries/libajax",
                    { minChars: 1,cacheLength: 10,autoFill: false}).result(function(e, item) {
                            $('#auto').attr('value', 1);
                    });
        
        });
  
</script>
<fieldset>
        <legend> <?php if($paction) { echo ucfirst($paction); } ?> Library Timezone</legend>
        <div class="formFieldsContainer">               
                <div class="form_steps">
                    <table cellspacing="10" cellpadding="0" border="0" width="100%">
                           
                            <tr>
                                <td align="right" width="250"></td>
                                <td align="left"></td>
                            </tr>	
                            
                            <tr>
                                    <td align="right" width="390"><?php echo $this->Form->label('Library Name');?></td>
                                    <td align="left"><?php echo $this->Form->input('library_name', array('label' => false, 'value' => $getData[0]['lbs']['library_name'], 'div' => false, 'class' => 'form_fields ac_input','width'=>'50','style'=>'width:304px;') ); ?></td>
                            </tr>
                            <tr>
                                    <td align="right" width="390"><?php echo $this->Form->label('Library Timezone');?></td>
                                    <td align="left">
                                        
<?php

$checkedValue = stripslashes($getData[0]['lt']['libraries_timezone']);
echo '<select name="library_timezone" class="form_fields" style="width:305px;">';
foreach($timezoneResults as $timezoneResult) {
  
    $optionValue = $timezoneResult['Timezone']['zone_name'];    
    $addString ='';
    if($optionValue === $checkedValue){
        $addString = 'selected="selected"';        
    }  
echo '<option value="'.$optionValue.'" '.$addString.'>' .$optionValue. '</option>';
    
}
echo '</select>';

?>
                                        
                                        
                                        
                                        </td>
                            </tr>
                          
                      
                            <tr >
                                <td align="right" width="250"></td>
                                <td align="left">
                                    <?php echo $this->Form->hidden( 'edit_id', array( 'label' => false ,'value' => $getData[0]['lbs']['id'])); ?>						
                                </td>
                            </tr>								   
                            <tr>
                                <td align="center" colspan="2"><p class="submit"><input type="submit" value="Save" style="cursor: pointer;"/><input type="button" value="Back" onClick="pageRedirect();" style="cursor: pointer;" /></p></td>
                            </tr>
                            
                    </table>
                </div>
        </div>
    </fieldset>
<?php
        echo $this->Form->end(); 
        echo $session->flash();
?>
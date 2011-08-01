<?php
/*
 File Name : admin_poatronform.ctp
 File Description : view page for admin patron form
 Author : m68interactive
 */
?>
       <?php
              $this->pageTitle = 'Admin';
              echo $this->Form->create('User', array( 'controller' => 'User','action' => $formAction));
              if(empty($getData))
              {
                     $getData['User']['library_id'] = "";
                     $getData['User']['first_name'] = "";
                     $getData['User']['last_name'] = "";
                     $getData['User']['email'] = "";
                     $getData['User']['username'] = "";
                     $getData['User']['id'] = "";
                     $getData['Group']['id'] = "5";
					 $getData['User']['sales'] = "no";
              }
       ?>
       <fieldset>
              <legend><?php echo $formHeader;?> <?php if($libraryID != "") { echo "for \"".$libraryname."\""; }?></legend>
              <div class="formFieldsContainer">
                     <?php echo $this->Form->hidden( 'id', array( 'label' => false ,'value' => $getData['User']['id'])); ?>
                     <div class="form_steps">
                            <table cellspacing="10" cellpadding="0" border="0" width="100%">
                                   <?php
                                          if($libraryID == "") {
                                   ?>
                                                 <tr>
                                                        <td align="right" width="390"><?php echo $this->Form->label('Select Library');?></td>
                                                        <td align="left">
                                                               <?php echo $this->Form->input('library_id', array('options' => $libraries, 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['User']['library_id'])); ?>
                                                        </td>
                                                 </tr>
                                   <?php
                                          }
                                          else {
                                                 echo $this->Form->hidden('library_id', array('value' => $libraryID));
                                          }
                                   ?>
                                   <tr>
                                          <td align="right" width="390"><?php echo $this->Form->label('First Name');?></td>
                                          <td align="left"><?php echo $this->Form->input('first_name', array('label' => false, 'value' => $getData['User']['first_name'], 'div' => false, 'class' => 'form_fields') ); ?></td>
                                   </tr>
                                   <tr>
                                          <td align="right" width="390"><?php echo $this->Form->label('Last Name');?></td>
                                          <td align="left"><?php echo $this->Form->input( 'last_name', array('label' => false ,'value' => $getData['User']['last_name'], 'div' => false, 'class' => 'form_fields')); ?></td>
                                   </tr>
                                   <tr>
                                          <td align="right" width="390"><?php echo $this->Form->label('email');?></td>
                                          <td align="left"><?php echo $this->Form->input( 'email', array( 'label' => false ,'value' => $getData['User']['email'], 'div' => false, 'class' => 'form_fields')); ?></td>
                                   </tr>								   
                                   <?php
                                          if(!empty($this->params['named']['id']))//gets the values from the url in form  of array
                                          {
                                   ?>
                                   <tr>
                                          <td align="right" width="390"><?php echo $this->Form->label('Password');?></td>
                                          <td align="left">
                                                 <?php
                                                        echo $this->Form->hidden( 'original_password', array( 'label' => false ,'value' => ''));
                                                        echo $this->Form->input( 'password', array( 'label' => false ,'value' => '', 'div' => false, 'class' => 'form_fields'));
                                                 ?>
                                          </td>
                                   </tr>
                                   <?php
                                          }
                                          echo $this->Form->hidden( 'type_id', array( 'label' => false ,'value' => $getData['Group']['id']));
                                   ?>
                                   <tr <?php if($libraryID != "" || $getData['User']['library_id'] != Configure::read('LibraryIdeas')) { ?> style="display:none" <?php }?> id="showCheckBox">
										<td align="right" width="250"><?php echo $this->Form->label('Sales User');?></td>
										<td style="padding-left:20px;">
											<input type="checkbox" id="LibraryShowContract" name="data[Check][sales]" <?php if($getData['User']['sales'] == 'yes'){?> checked="checked" <?php } ?>>
										</td>
                                   </tr>								   
                                   <tr>
                                          <td align="center" colspan="2"><p class="submit"><input type="submit" value="Save" id="SavePatron" /></p></td>
                                   </tr>
                                   
                            </table>
                     </div>
              </div>
         </fieldset>
       <?php
              echo $this->Form->end(); 
              echo $session->flash();
              if(!empty($this->params['named']['id']))//gets the values from the url in form  of array
              {
       ?>
       <script type="text/javascript">

              $(function() {
                      $("#SavePatron").click(function(){
                            $("#UserOriginalPassword").val($("#UserPassword").val());
                      });
              });
      </script>
       <?php } ?>
	   <script type="text/javascript">
				$("#UserLibraryId").change(function() {
					if($(this).val() == '2') {
						$("#showCheckBox").show();
					}
					else {
						$("#showCheckBox").hide();
					}
				});
	   </script>
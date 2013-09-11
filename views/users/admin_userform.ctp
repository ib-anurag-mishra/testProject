<?php
/*
 File Name : admin_userform.ctp
 File Description : view page for admin user form
 Author : m68interactive
 */
?>
       <?php
                $this->pageTitle = 'Admin';
              echo $this->Form->create('User', array( 'controller' => 'User','action' => $formAction));
			  $options[6] = "Consortium";
              if(empty($getData))
              {
                     $getData['User']['first_name'] = "";
                     $getData['User']['last_name'] = "";
                     $getData['User']['email'] = "";
                     $getData['User']['username'] = "";
                     $getData['User']['id'] = "";
                     $getData['Group']['id'] = "";
					 $getData['User']['consortium'] = "";
              }
			  if($getData['User']['consortium'] != ''){
					$getData['Group']['id'] = 6;
			  }
       ?>
       <fieldset>
              <legend><?php echo $formHeader;?></legend>
              <div class="formFieldsContainer">
                     <?php echo $this->Form->hidden( 'id', array( 'label' => false ,'value' => $getData['User']['id'])); ?>
                     <div class="form_steps">
                            <table cellspacing="10" cellpadding="0" border="0" width="100%">
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
                                   <tr>
                                          <td align="right" width="390"><?php echo $this->Form->label('Password');?></td>
                                          <td align="left"><?php echo $this->Form->input('password', array('label' => false,'value' => '', 'div' => false, 'class' => 'form_fields') ); ?></td>
                                   </tr>
                                   <?php
                                   if($getData['Group']['id'] != 4) {
                                          if($getData['User']['id'] == $session->read('Auth.User.id')) {
                                   ?>
                                                 <tr>
                                                        <td align="right" width="390"><?php echo $this->Form->label('Admin Type');?></td>
                                                        <td align="left">
                                                               <?php echo $this->Form->hidden( 'type_id', array( 'label' => false ,'value' => $getData['Group']['id'])); ?>
                                                               <label class="form_fields"><?php echo $user->getAdminType($getData['User']['type_id']); ?></label>
                                                        </td>
                                                 </tr>
                                   <?php
                                          }
                                          else {
                                   ?>
                                                 <tr>
                                                        <td align="right" width="390"><?php echo $this->Form->label('Admin Type');?></td>
                                                        <td align="left"><?php echo $this->Form->input('type_id', array('type' => 'select', 'label' => false, 'options' => $options, 'selected' => $getData['Group']['id'], 'div' => false, 'class' => 'select_fields', 'onchange' => 'showRow();')) ;?></td>
                                                 </tr>
                                   <?php
                                          }
                                   }
                                   else {
                                   ?>
                                          <?php echo $this->Form->hidden( 'type_id', array( 'label' => false ,'value' => $getData['Group']['id'])); ?>
                                   <?php       
                                   }
                                   ?>
                                    <tr id="showConsortium" <?php if($getData['User']['consortium'] == ''){ ?>style="display:none" <?php }?>>
                                            <td align="right" width="250"><?php echo $this->Form->label(null, 'Library Consortium');?></td>
                                            <td align="left">
                                                    <?php
                                                            echo $this->Form->input('consortium', array('options' => $consortium, 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['User']['consortium']));
                                                    ?>						
                                            </td>
                                    </tr>								   
                                    <tr>
                                        <?php if(1 == $su_admin['User']['type_id']){?>
                                        <td align="right" width="390"><?php echo $this->Form->label('Library Name');?></td>
                                        <td align="left">
                                            <?php
                                                echo $this->Form->input('library_list', array('options' => $library_del, 'label' => False, 'div' => false, 'class' => 'select_fields', 'empty' => 'Select Library'));
                                            ?>						
                                        </td>
                                        <?php
                                        }
                                        ?>
                                    </tr>
                                   <tr>
                                          <td align="center" colspan="2"><p class="submit"><input type="submit" value="Save" /></p></td>
                                   </tr>
                                   
                            </table>
                     </div>
              </div>
         </fieldset>
       <?php
              echo $this->Form->end(); 
              echo $session->flash();
       ?>
	<script type="text/javascript" src="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/js&amp;f=page_specific/libraries_create.js,page_specific/ajaxfileupload.js,datepicker/jquery.ui.core.js,datepicker/jquery.ui.widget.js,datepicker/jquery.ui.datepicker.js"></script>
	   
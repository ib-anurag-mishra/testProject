<?php
    $this->pageTitle = 'My Account';
    echo $session->flash();
    echo $this->Form->create('User', array( 'controller' => 'User','action' => 'my_account'));            
?>

              <legend><?php echo 'Manage Account';?></legend>
              <div>
                     <?php echo $this->Form->hidden( 'id', array( 'label' => false ,'value' => $getData['User']['id'])); ?>
                     <div>
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
                                          <td align="left"><?php echo $this->Form->input( 'email', array( 'label' => false ,'value' => $getData['User']['email'], 'div' => false, 'class' => 'form_fields', 'readonly' => true)); ?></td>
                                   </tr>
                                   <tr>
                                          <td align="right" width="390"><?php echo $this->Form->label('Password');?></td>
                                          <td align="left"><?php echo $this->Form->input('password', array('label' => false,'value' => '', 'div' => false, 'class' => 'form_fields') ); ?></td>
                                   </tr>                                   
                                   <tr>
                                          <td align="center" colspan="2"><p class="submit"><input type="submit" value="Save" /></p></td>
                                   </tr>
                                   
                            </table>
                     </div>
              </div>
         
       <?php
              echo $this->Form->end();               
       ?>
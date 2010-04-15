<?php
    $this->pageTitle = 'Forgot Password';
    echo $session->flash();
    echo $this->Form->create('Home', array( 'controller' => 'Home','action' => 'forgot_password'));            
?>

              <legend><?php echo 'Forgot Password';?></legend>
              <div>                     
                     <div>
                            <table cellspacing="10" cellpadding="0" border="0" width="100%">
                                   <tr>
                                          <td align="right" width="390"><?php echo $this->Form->label('Email');?></td>
                                          <td align="left"><?php echo $this->Form->input('email', array('label' => false, 'div' => false, 'class' => 'form_fields') ); ?></td>
                                   </tr>                                                                      
                                   <tr>
                                          <td align="center" colspan="2"><p class="submit"><input type="submit" value="Submit" /></p></td>
                                   </tr>
                                   
                            </table>
                     </div>
              </div>
         
       <?php
              echo $this->Form->end();               
       ?>
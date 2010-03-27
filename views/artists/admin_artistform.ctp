<?php
 $this->pageTitle = 'Content'; 
 echo $form->create('Artist', array( 'controller' => 'Artist','action' => $formAction,'enctype' => 'multipart/form-data'));       	 	
 if(empty($getData))
 {
  $getData['Featuredartist']['artist_name'] = ""; 
  $getData['Featuredartist']['id'] = "";	              
 }        
?>
<fieldset>
 <legend><?php echo $formHeader;?></legend>
 <div class="formFieldsContainer">
  <?php echo $form->hidden( 'id', array( 'label' => false ,'value' => $getData['Featuredartist']['id'])); ?>
  <div class="form_steps">
   <table cellspacing="10" cellpadding="0" border="0" width="100%">
          <tr>
                 <td align="right" width="390"><?php echo $form->label('Artist Name');?></td>
                 <td align="left"><?php echo $form->select('artist_name', $getArtistData, $getData['Featuredartist']['artist_name'], array('label' => false, 'div' => false, 'class' => 'select_fields')); ?></td>
          </tr>
          <tr>
                 <td align="right" width="390"><?php echo $form->label('Artist Photo');?></td>
                 <td align="left"><?php echo $form->file('artist_image', array('label' => false, 'div' => false, 'class' => 'form_fields')); ?></td>
          </tr>
          <tr>
                 <td align="center" colspan="2"><p class="submit"><input type="submit" value="Save" /></p></td>
          </tr>
   </table>
   </div>
  </div>
</fieldset> 
<?php
 echo $form->end();
 echo $session->flash();
?>
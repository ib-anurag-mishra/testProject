<?php
$this->pageTitle = 'Admin';
echo $form->create('Artist', array( 'controller' => 'Artist','action' => $formAction,'enctype' => 'multipart/form-data'));       	 	
if(empty($getData))
{
$getData['Newartist']['artist_name'] = ""; 
  $getData['Newartist']['id'] = "";	              
}        
?>
<fieldset>
<legend><?php echo $formHeader;?></legend>        
<?php echo $form->hidden( 'id', array( 'label' => false ,'value' => $getData['Newartist']['id'])); ?>
<?php echo $form->label('Artist Name');?><br/>
<?php echo $form->select('artist_name',$getArtistData,$getData['Newartist']['artist_name']) ;?><br/>
<?php echo $form->label('Artist Photo');?><br/>
<?php echo $form->file('artist_image') ;?> 
<p class="submit"><input type="submit" value="Save" /></p>
<?php echo $form->end(); ?>
<?php 
 echo $session->flash();
?>   
</form>
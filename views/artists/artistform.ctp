<?php
echo $form->create('Artist', array( 'controller' => 'Artist','action' => $formAction,'enctype' => 'multipart/form-data'));       	 	
if(empty($getData))
{
$getData['Featuredartist']['artist_name'] = ""; 
  $getData['Featuredartist']['id'] = "";	              
}        
?>
<fieldset>
<legend><?php echo $formHeader;?></legend>        
<?php echo $form->hidden( 'id', array( 'label' => false ,'value' => $getData['Featuredartist']['id'])); ?>
<?php echo $form->label('Artist Name');?><br/>
<?php echo $form->select('artist_name',$getArtistData,$getData['Featuredartist']['artist_name']) ;?><br/>
<?php echo $form->label('Artist Photo');?><br/>
<?php echo $form->file('artist_image') ;?> 
<p class="submit"><input type="submit" value="Save" /></p>
<?php echo $form->end(); ?>
<fieldset> 
<?php 
 echo $session->flash();
?>
   </fieldset> 
</form>
<?php
/*
	 File Name : admin_language.ctp
	 File Description : View page for admin language setting
	 Author : m68interactive
 */
$this->pageTitle = 'Content';
if(empty($getData))
{
	$getData['Language']['short_name'] = "";
	$getData['Language']['full_name'] = "";
} 
?>
<?php echo $form->create('Homes', array( 'controller' => 'homes','action' => $formAction));?>       	 	
<fieldset>
<legend><?php echo $formHeader;?></legend>
<div class="formFieldsContainer">
<div class="form_steps">
<table cellspacing="10" cellpadding="0" border="0" width="100%">
	<tr>
		<td align="right" width="390"><?php echo $form->label('Language Short Name');?></td>
		<td align="left"><div id="getArtist"><?php echo $form->input('short_name',array('label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['Language']['short_name'])); ?></div>
		</td>
	</tr>
	<tr>
		<td align="right" width="390"><?php echo $form->label('Language Full Name');?></td>
		<td align="left"><div id="getArtist"><?php echo $form->input('full_name',array('label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['Language']['full_name'])); ?></div>
		</td>
	</tr>
	<tr>
	<tr>
		<td align="center" colspan="2"><p class="submit"><input type="submit" value="Save" /></p></td>
	</tr>
</table>
</div>
</div>
</fieldset>

<fieldset>
	<div class="formFieldsContainer">
		<table>
			<tr>
				<th>No</th>
				<th>Short Name</th>
				<th>Full Name</th>
				<th>Status</th>
			</tr>
			<?php $i =1;foreach ($languages as $lang): ?>
			<tr>
				<td><?php echo $i; ?></td>
				<td>
					<?php echo $lang['Language']['short_name']; ?></td>
				</td>
				<td>
					<?php echo $lang['Language']['full_name']; ?></td>
				</td>
				<td>
				<?php
					if($lang['Language']['status']=='inactive'){
						echo $html->link('Activate', array('controller'=>'homes','action'=>'language_activate','id'=>$lang['Language']['id']));
					}else{
						echo $html->link('Deactivate', array('controller'=>'homes','action'=>'language_deactivate','id'=>$lang['Language']['id']));
					}
				?>
				</td>				
			</tr>
			<?php $i++;endforeach; ?>
		</table>
	</div>
</fieldset>
<?php echo $this->Form->end(); ?>
<?php echo $session->flash();?>
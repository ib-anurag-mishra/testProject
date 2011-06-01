<?php
	$this->pageTitle = 'Libraries'; 
	echo $this->Form->create('Library', array( 'action' => $formAction, 'type' => 'file', 'id' => 'consortiumform'));
?>
<fieldset>
	<table>
		<tr>
			<td style="border-right:1px solid #E0E0E0;text-align:center;">Manage Consortium</td>
			<td>
				<select name="data[Library][libraryIds][]" class="select_fields" multiple="multiple" size="8">
					<?php
						foreach($allLibraries as $k => $v){ echo "<option value=".$k;
							if(array_key_exists($k, $selectLibraries)){
								echo " selected=selected ";
							}
							echo ">".$v."</option>"; 
						}
						
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><?php echo $this->Form->hidden('Library.library_apikey', array('value' => $consortium_name)); ?>&nbsp;&nbsp;</td>
		</tr>		
		<tr>
			<td colspan="2" align="center"><?php echo $this->Form->submit('Update Consortium', array('div' => false, 'class' => 'form_fields')); ?></td>
		</tr>		
	</table>
</fieldset>
<?php echo $this->Form->end(); ?>
<?php echo $session->flash(); ?>
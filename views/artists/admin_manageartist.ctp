<?php
/*
 File Name : admin_manageartist.php
File Description : View page for manage artist
Author : m68interactive
*/
?>
<?php $this->pageTitle = 'Content'; ?>
<script>
function validateForm() {
	document.artistAdminManageartistForm.submit();
}	

function m_delete(flagVar) { // Delete the contact
	
	var k2=0;
        if(flagVar == 1){
            
            for (var i=0;i<document.artistAdminManageartistForm.elements.length;i++)
            {
                    var e1 = document.artistAdminManageartistForm.elements[i];

                    if((e1.type=="checkbox")&&(e1.name=='data[Info][ ]'))
                    {
                            if(e1.checked==true)
                                    {
                                            k2++;
                                    }
                    }
            }
            
        }else{
            
            k2 = 1;
        }	
       
	if(k2==0)
	{
		alert('Please select at least one recode for remove.');
		return false;
	}
	else
	{
		if(flagVar == 1){
                    var x=confirm('Are you sure you want to remove all selected records ?');
                }else if(flagVar == 2){
                    var x=confirm('Are you sure you want to remove all records ?');
                }
		
		if(x==false)
		{
			return false;
		}
		else(x==true)
		{
			
                        document.getElementById('artistSelectedOpt').value = flagVar;                        
                        return true;
		
		}
	}
}


function CheckAllChk(theForm,maincheckname)
{
	for(var z=0; z<theForm.length;z++)
	{
		if(theForm[z].type =='checkbox')
		{
			if(maincheckname.checked == true)
			{
				theForm[z].checked=true;
			}		   
			else
			{
				theForm[z].checked=false;
			}
		}
	}	
}
</script>
<?php echo $this->Form->create('artist', array('type' => 'post','name' => 'artistAdminManageartistForm','url' => array('controller' => 'artists', 'action' => 'admin_deleteartists'))); ?>
<fieldset style="border: 0 solid #E0E0E0;">
	<legend>Artist Listing</legend>

	<table cellpadding="3" cellspacing="3">
		<tr>
			<td>

				<table id="list">
					<tr>
						<th class="left">Artist Name</th>
						<th class="left">Territory</th>
						<th>Artist image</th>
						<?php if($userTypeId != 7) { ?>
						<th>Edit</th>
						<th><input type="checkbox" name="maincheckbox" id="maincheckbox"
							value="1" onClick="CheckAllChk(form,this);">Delete</th>
					<?php } ?>
					</tr>

					<?php if(count($artists)) { ?>
					<?php                
					foreach($artists as $artist)
					{
						$artistImage = $artist['Artist']['artist_image'];
						?>
					<tr>
						<td class="left"><?php echo $artist['Artist']['artist_name'];?></td>
						<td class="left"><?php echo $artist['Artist']['territory'];?></td>
						<td><a
							href="<?php echo $cdnPath.'artistimg/'.$artist['Artist']['artist_image'];?>"
							rel="image"
							onclick="javascript: show_uploaded_images('<?php echo $cdnPath.'artistimg/'.$artist['Artist']['artist_image'];?>')"><?php echo $artistImage;?>
						</a></td>
						<?php if($userTypeId != 7) { ?>
						<td><?php echo $html->link('Edit', array('controller'=>'artists','action'=>'createartist','id'=>$artist['Artist']['id']));?>
						</td>
						<td><?php echo $this->Form->input("Info. ", array('type'=>'checkbox','id'=>$artist['Artist']['id'], 'value' => $artist['Artist']['id'], 'hiddenField' => false)); ?>
						</td>
					<?php } ?>
					</tr>
					<?php
					}
					?>

					<tr>
						<td colspan="5"></td>
					</tr>
					<tr>
						<td class="left" colspan="5"><div class="paging">
								<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
								|
								<?php echo $paginator->numbers();?>
								<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
								<?php echo $this->Form->hidden('selectedOpt'); ?>
							</div> <?php if(count($artists)) { ?> <span style="float: right;">
								<table>
									<tr>
<?php if($userTypeId != 7) { ?>
<td><?php echo $this->Form->button('Remove Selected', array('name' => 'remove_selected','label'=>'Remove Selected','onclick' => 'return m_delete(1)')); ?>
										</td>
										<td><?php echo $this->Form->button('Remove All', array('name' => 'remove_all','label'=>'Remove All','onclick' => 'return m_delete(2)')); ?>
										</td>
<?php } ?>
									</tr>

								</table>
						</span> <?php } ?>
						</td>

					</tr>
					<?php }else{ ?>
					<tr>
						<td colspan="5" align="center">No Records available.</td>
					</tr>
					<?php } ?>

				</table>
				</fieldset> <?php echo $this->Form->end(); ?> <?php echo $session->flash();?>

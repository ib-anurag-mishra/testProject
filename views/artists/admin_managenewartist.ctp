<?php
/*
 File Name : admin_managenewartist.php
File Description : View page for manage new artist
Author : m68interactive
*/
?>
<?php $this->pageTitle = 'Content'; ?>
<form>
	<fieldset>
		<legend>Newly Added Artists Listing</legend>
		<table id="list">
			<tr>
				<th class="left">Artist Name</th>
				<th class="left">Territory</th>
				<th>Artist image</th>
				<?php if($userTypeId !=7) { ?>
				<th>Edit</th>
				<th>Delete</th>
				<?php } ?>
			</tr>
			<?php
			foreach($artists as $artist)
			{
				$artistImage = $artist['Newartist']['artist_image'];
				?>
			<tr>
				<td class="left"><?php echo $artist['Newartist']['artist_name'];?></td>
				<td class="left"><?php echo $artist['Newartist']['territory'];?></td>
				<td><a
					href="<?php echo $cdnPath.'newartistimg/'.$artist['Newartist']['artist_image'];?>"
					rel="image"
					onclick="javascript: show_uploaded_images('<?php echo $cdnPath.'newartistimg/'.$artist['Newartist']['artist_image'];?>')"><?php echo $artistImage;?>
				</a></td>
				<?php if($userTypeId !=7) { ?>
				<td><?php echo $html->link('Edit', array('controller'=>'artists','action'=>'addnewartist','id'=>$artist['Newartist']['id']));?>
				</td>
				<td><?php echo $html->link('Delete', array('controller'=>'artists','action'=>'deletenewartists','id'=>$artist['Newartist']['id']));?>
				</td>
				<?php } ?>
			</tr>
			<?php
			}
			?>
		</table>
		<br class="clr" />
		<div class="paging">
			<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
			|
			<?php echo $paginator->numbers();?>
			<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
		</div>
		<?php echo $session->flash();?>
	</fieldset>
</form>

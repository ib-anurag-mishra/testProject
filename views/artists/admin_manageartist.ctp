<?php $this->pageTitle = 'Content'; ?>
<form>
<fieldset>
<legend>Artist Listing</legend>
	<table id="list">
		<tr>            
			<th class="left">Artist Name</th>
			<th class="left">Territory</th>
			<th>Artist image</th>
			<th>Edit</th>
			<th>Delete</th>
		</tr>
		<?php
		foreach($artists as $artist)
		{
			$artistImage = $artist['Artist']['artist_image'];	   
		?>
		<tr>
			<td class="left"><?php echo $artist['Artist']['artist_name'];?></td>
			<td class="left"><?php echo $artist['Artist']['territory'];?></td>
			<td><a href="<?php echo $cdnPath.'artistimg/'.$artist['Artist']['artist_image'];?>" rel="image" onclick="javascript: show_uploaded_images('<?php echo $cdnPath.'artistimg/'.$artist['Artist']['artist_image'];?>')"><?php echo $artistImage;?></a></td>                
			<td><?php echo $html->link('Edit', array('controller'=>'artists','action'=>'createartist','id'=>$artist['Artist']['id']));?></td>
			<td><?php echo $html->link('Delete', array('controller'=>'artists','action'=>'deleteartists','id'=>$artist['Artist']['id']));?></td>
		</tr>
		<?php
		}
		?>
	</table>
	<br class="clr" />
	<div class="paging">
	      <?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
	| 	<?php echo $paginator->numbers();?>
	      <?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
	</div>
</fieldset>
<?php echo $session->flash();?>
</form>


<?php $this->pageTitle = 'Content'; ?>

<form>
	<fieldset>
		<legend>Top Albums Listing</legend>
		<table id="list">
			<tr>
				<th class="left">Artist Name</th>
				<th class="left">Territory</th>
				<th class="left">Album</th>
				<?php if($userTypeId !=7) { ?>
				<th>Edit</th>
				<th>Delete</th>
				<?php } ?>
			</tr>
			<?php
			foreach($topAlbums as $topAlbum)
			{
				?>
			<tr>
				<td class="left"><?php echo $topAlbum['TopAlbum']['artist_name'];?>
				</td>
				<td class="left"><?php echo $topAlbum['TopAlbum']['territory'];?>
				</td>
				<td class="left"><?php $data = $album->getAlbum($topAlbum['TopAlbum']['album']);echo $data[0]['Album']['AlbumTitle'];?>
				</td>
				<?php if($userTypeId !=7) { ?>
				<td><?php echo $html->link('Edit', array('controller'=>'artists','action'=>'topalbumform','id'=>$topAlbum['TopAlbum']['id']));?>
				</td>
				<td><?php echo $html->link('Delete', array('controller'=>'artists','action'=>'topalbumdelete','id'=>$topAlbum['TopAlbum']['id']));?>
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

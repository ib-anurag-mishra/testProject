<?php $this->pageTitle = 'Content'; ?>
<form>
	<fieldset>
		<legend>Featured Artists Listing</legend>
		<table id="list">
			<tr>
				<th class="left">Artist Name</th>
				<th class="left">Territory</th>
				<th class="left">Album</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
			<?php
			foreach($artists as $artist)
			{
				?>
			<tr>
				<td class="left"><?php echo $artist['Featuredartist']['artist_name'];?>
				</td>
				<td class="left"><?php echo $artist['Featuredartist']['territory'];?>
				</td>
				<td class="left"><?php $data = $album->getAlbum($artist['Featuredartist']['album']);echo $data[0]['Album']['AlbumTitle'];?>
				</td>
				<td><?php echo $html->link('Edit', array('controller'=>'artists','action'=>'artistform','id'=>$artist['Featuredartist']['id']));?>
				</td>
				<td><?php echo $html->link('Delete', array('controller'=>'artists','action'=>'delete','id'=>$artist['Featuredartist']['id']));?>
				</td>
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
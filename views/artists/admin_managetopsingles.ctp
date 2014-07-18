<?php $this->pageTitle = 'Content';  echo "<pre>"; print_r($topSingles); exit;?>

<form>
	<fieldset>
		<legend>Top Singles Listing</legend>
		<table id="list">
			<tr>
				<th class="left">Artist Name</th>
				<th class="left">Territory</th>
				<th class="left">Album</th>
				<th class="left">Song</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
			<?php
			foreach($topSingles as $topSingle)
			{
				?>
			<tr>
				<td class="left"><?php echo $topSingle['TopSingles']['artist_name'];?>
				</td>
				<td class="left"><?php echo $topSingle['TopSingles']['territory'];?>
				</td>
				<td class="left"><?php $data = $album->getSongDetails($topSingle['TopSingles']['album']);echo $data[0]['Album']['AlbumTitle'];?>
				</td>
				<td><?php echo $html->link('Edit', array('controller'=>'artists','action'=>'topalbumform','id'=>$topAlbum['TopAlbum']['id']));?>
				</td>
				<td><?php echo $html->link('Delete', array('controller'=>'artists','action'=>'topalbumdelete','id'=>$topAlbum['TopAlbum']['id']));?>
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

<?php $this->pageTitle = 'Content'; ?>

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
			foreach($topSingles as $topsingle)
			{
				?>
			<tr>
				<td class="left"><?php echo $topsingle['Topsingles']['artist_name'];?>
				</td>
				<td class="left"><?php echo $topsingle['Topsingles']['territory'];?>
				</td>
				<td class="left"><?php $data = $album->getAlbum($topsingle['Topsingles']['album']);echo $data[0]['Album']['AlbumTitle'];?>
				</td>
				<td class="left"><?php echo $topSingle['Topsingles']['prod_id'];?>
				</td>
				<td><?php echo $html->link('Edit', array('controller'=>'artists','action'=>'topsinglesform','id'=>$topsingle['Topsingles']['id']));?>
				</td>
				<td><?php echo $html->link('Delete', array('controller'=>'artists','action'=>'topalbumdelete','id'=>$topsingle['Topsingles']['id']));?>
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

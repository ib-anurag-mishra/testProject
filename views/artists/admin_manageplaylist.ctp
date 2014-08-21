<?php $this->pageTitle = 'Content'; ?>
<form>
	<fieldset>
		<legend>Freegal Playlists Listing</legend>
		<table id="list">
			<?php
			foreach($queueLists as $queueLists)
			{
				?>
			<tr>
				<td class="left"><?php echo $queueLists['QueueList']['queue_name'];?>
				</td>
				<td><?php echo $html->link('Edit', array('controller'=>'artists','action'=>'addplaylist','id'=>$queueLists['QueueList']['queue_id']));?>
				</td>
				<td><?php echo $html->link('Delete', array('controller'=>'artists','action'=>'deletePlaylist','id'=>$queueLists['QueueList']['queue_id']));?>
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

<?php
/*
 File Name : admin_index.ctp
 File Description : View page for admin index
 Author : m68interactive
 */
?>
<?php echo $javascript->link('dragdrop'); ?>
<?php echo $javascript->link('dragdrop_add'); ?>
<?php $this->pageTitle = 'Content'; ?>
<div class="questions index" id="content">
	<h2><?php __('News List');?></h2>
	<table cellpadding="0" cellspacing="0" id="table_1">
	<tr>
			<th><?php echo $this->Paginator->sort('News');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<TBODY>
	<?php
	$i = 0;
	foreach ($news as $question):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>

	<tr<?php echo $class;?> id="<?php echo $question['News']['id']; ?>">

		<td><?php echo $question['News']['subject']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $question['News']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $question['News']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $question['News']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $question['News']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</TBODY>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('News', true)), array('action' => 'add')); ?></li>
	</ul>
</div>
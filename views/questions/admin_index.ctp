<?php
/*
 File Name : admin_index.ctp
 File Description : View page for admin index
 Author : m68interactive
 */
echo $javascript->link('dragdrop');
echo $javascript->link('dragdrop_add');
$this->pageTitle = 'Content'; ?>

<div class="questions index" id="content">
	<h2><?php __('FAQs');?></h2>
	<table cellpadding="0" cellspacing="0" id="table_1">
		<tr>
			<th><?php echo $this->Paginator->sort('section_id');?></th>
			<th><?php echo $this->Paginator->sort('question');?></th>
			<th class="actions"><?php __('Actions');?></th>
		</tr>
		<TBODY>
		<?php
		$i = 0;
		foreach ($questions as $question):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>

		<tr<?php echo $class;?> id="<?php echo $question['Question']['id']; ?>">
			<td>
				<?php echo $this->Html->link($question['Section']['title'], array('controller' => 'sections', 'action' => 'view', $question['Section']['id'])); ?>
			</td>
			<td><?php echo $question['Question']['question']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('action' => 'view', $question['Question']['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $question['Question']['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $question['Question']['id']), null, sprintf(__('Are you sure you want to delete', true) . ' # %s?', $question['Question']['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
		</TBODY>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page', true) . ' %page% ' . __('of', true) . ' %pages%, ' . __('showing', true) . ' %current% ' . __('records out of', true) . ' %count% ' . __('total, starting on record', true) . ' %start%, ' . __('ending on', true) . ' %end%'
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
		<li><?php echo $this->Html->link(sprintf(__('New', true) . ' %s', __('Question', true)), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List', true) . ' %s', __('Sections', true)), array('controller' => 'sections', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New', true) . ' %s', __('Section', true)), array('controller' => 'sections', 'action' => 'add')); ?> </li>
	</ul>
</div>
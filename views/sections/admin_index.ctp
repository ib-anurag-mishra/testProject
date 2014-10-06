<?php
/*
 File Name : admin_index.ctp
 File Description : view page for admin index page
 Author : m68interactive
 */
$this->pageTitle = 'Content'; ?>
<div class="sections index">
	<h2><?php __('FAQ Sections');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('title');?></th>
			<th class="actions"><?php __('Actions');?></th>
			<th><?php __('Language');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($sections as $section):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $section['Section']['title']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $section['Section']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $section['Section']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $section['Section']['id']), null, sprintf(__('Are you sure you want to delete', true) . ' # %s?', $section['Section']['id'])); ?>
		</td>
		<td><?php echo $section['Section']['language']; ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
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
		<li><?php echo $this->Html->link(sprintf(__('New', true) . ' %s', __('Section', true)), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List', true) . ' %s', __('Questions', true)), array('controller' => 'questions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New', true) . ' %s', __('Question', true)), array('controller' => 'questions', 'action' => 'add')); ?> </li>
	</ul>
</div>
<?php $this->pageTitle = 'Content'; ?>
<div class="questions form">
<?php echo $this->Form->create('Question');?>
	<fieldset>
 		<legend><?php printf(__('Admin Edit %s', true), __('Question', true)); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('section_id');
		echo $this->Form->input('question');
		echo $this->Form->input('answer');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Question.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Question.id'))); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Questions', true)), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Sections', true)), array('controller' => 'sections', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Section', true)), array('controller' => 'sections', 'action' => 'add')); ?> </li>
	</ul>
</div>
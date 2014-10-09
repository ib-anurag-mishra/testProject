<?php
/*
 File Name : admin_view.ctp
 File Description : View page foradmin_view
 Author : m68interactive
 */

$this->pageTitle = 'Content'; ?>

<div class="questions view">
<h2><?php  __('FAQs');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Section'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($question['Section']['title'], array('controller' => 'sections', 'action' => 'view', $question['Section']['id'])); ?>
			
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Question'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $question['Question']['question']; ?>
			
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Answer'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $question['Question']['answer']; ?>
			
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $question['Question']['created']; ?>
			
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Last Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $question['Question']['modified']; ?>
			
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(sprintf(__('Edit', true) . ' %s', __('Question', true)), array('action' => 'edit', $question['Question']['id'])); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('Delete', true) . ' %s', __('Question', true)), array('action' => 'delete', $question['Question']['id']), null, sprintf(__('Are you sure you want to delete', true) . ' # %s?', $question['Question']['id'])); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List', true) . ' %s', __('Questions', true)), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New', true) . ' %s', __('Question', true)), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List', true) . ' %s', __('Sections', true)), array('controller' => 'sections', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New', true) . ' %s', __('Section', true)), array('controller' => 'sections', 'action' => 'add')); ?> </li>
	</ul>
</div>

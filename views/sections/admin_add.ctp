<?php
/*
 File Name : admin_add.ctp
 File Description : view page for adding new sections
 Author : m68interactive
 */
$this->pageTitle = 'Content'; ?>
<div class="sections form">
	<?php echo $this->Form->create('Section');?>
	<fieldset>
		<legend><?php printf(__('Admin Add', true) . ' %s', __('Section', true)); ?></legend>
		<?php
		echo $this->Form->input('title');
		?>
		<select id="SectionLanguage" name="data[Section][language]">
		<?php
		foreach($languages as $k => $v){
			echo '<option value="'.$k.'" ';
			echo '>'.$v.'</option>';
		}
		?>
		</select>
		<label class="selectlanguage">You must select a language</label>
	</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>

<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Section.id')), null, sprintf(__('Are you sure you want to delete', true) . ' # %s?', $this->Form->value('Section.id'))); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List', true) . ' %s', __('Sections', true)), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(sprintf(__('List', true) . ' %s', __('Questions', true)), array('controller' => 'questions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New', true) . ' %s', __('Question', true)), array('controller' => 'questions', 'action' => 'add')); ?> </li>
	</ul>
</div>
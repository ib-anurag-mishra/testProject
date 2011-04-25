<?php $this->pageTitle = 'Content'; ?>
<div class="sections form">
<?php echo $this->Form->create('Section');?>
 <fieldset>
   <legend><?php printf(__('Admin Add %s', true), __('Section', true)); ?></legend>
 <?php
  echo $this->Form->input('title');
 ?>
  <select id="SectionLanguage" name="data[Section][language]">
   <option value="en">English</option>
   <option value="es">Spanish</option>
  </select>
  <label class="selectlanguage">You must select a language</label>
 </fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
 <h3><?php __('Actions'); ?></h3>
 <ul>
  <li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Section.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Section.id'))); ?></li>
  <li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Sections', true)), array('action' => 'index'));?></li>
  <li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Questions', true)), array('controller' => 'questions', 'action' => 'index')); ?> </li>
  <li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Question', true)), array('controller' => 'questions', 'action' => 'add')); ?> </li>
 </ul>
</div>
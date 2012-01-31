<?php
/*
 File Name : admin_view.ctp
 File Description : View page for admin_view
 Author : m68interactive
 */
?>

<?php $this->pageTitle = 'Content'; 
?>
<div class="questions view">
<h2><?php  __('News');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Subject'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $news['News']['subject']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Place'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $news['News']['place']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Body'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $news['News']['body']; ?>
			&nbsp;
		</dd>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('News Image'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php if($news['News']['image_name'] != ''){ ?>
				<img style = "height: 150px;" src = "<?php echo $cdnPath. 'news_image/' . $news['News']['image_name']; ?>" alt = '<?php echo $news['News']['subject']; ?>' />
			<?php }
				else 
					echo __('No Image');
					
			?>
		</dd>
		
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $news['News']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Last Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $news['News']['modified']; ?>
			&nbsp;
		</dd>
		
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(sprintf(__('Edit %s', true), __('News', true)), array('action' => 'edit', $news['News']['id'])); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('Delete %s', true), __('News', true)), array('action' => 'delete', $news['News']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $news['News']['id'])); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('News', true)), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('News', true)), array('action' => 'add')); ?> </li>
	</ul>
</div>

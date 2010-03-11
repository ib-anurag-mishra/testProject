<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php __('Freegal Music : The New Music Library :'); ?>
		<?php echo $title_for_layout; ?>
	</title>
   	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('freegal_styles');
		echo $this->Html->css('jquery.autocomplete');
		echo $javascript->link('jquery-1');
		echo $javascript->link('jquery');
	?>        
		
	<?php		
		echo $javascript->link('curvycorners');
		echo $javascript->link('jquery.autocomplete');
		echo $javascript->link('autocomplete');
		echo $javascript->link('freegal');
		echo $scripts_for_layout;
	?>
</head>
<body>
	<?php $session->flash(); ?>
	<div id="container">
		<?php echo $this->element('header'); ?>
		<div id="content">
			<?php echo $this->element('navigation'); ?>
            <?php echo $content_for_layout; ?>
		</div>
		<br class="clr">
	</div>
	<?php echo $this->element('footer'); ?>
</body>
</html>

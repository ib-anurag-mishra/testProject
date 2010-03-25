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
                echo $this->Html->css('freegal_admin_styles');
                echo $this->Html->css('superfish');
                echo $this->Html->css('colorbox');
                echo $javascript->link('jquery-1.3.2.min');
		echo $javascript->link('jquery.tools.min');
		echo $javascript->link('admin_functions');
                echo $javascript->link('jquery.colorbox');
                echo $javascript->link('jquery.hoverIntent.min');
                echo $javascript->link('superfish');
                echo $javascript->link('supersubs');
                
                echo $scripts_for_layout;
	?>
	<script type="text/javascript">
		var webroot = '<? echo $this->webroot; ?>';
	</script>
</head>
<body>
	<?php $session->flash(); ?>
	<div id="container">
		<div id="header">
			<div id="header_title">
				<a href="/admin">Library Ideas / Freegal Music Admin</a>
			</div>
			<div id="header_image">
				<?php echo $html->image('freegal_logo.png', array("alt" => "Freegal Music")); ?>
			</div>
		</div>
		<div id="navigation">
			<?php if ($this->pageTitle != 'Login') {
				echo $this->element('admin_navigation');
				echo $html->link('Logout', array('controller'=>'users','action'=>'logout'), array('class' => 'logout'));
			} ?>
		</div>
		<div id="content"><br>
			<?php echo $content_for_layout; ?>
		</div>
		<div id="footer">
			&copy; 2010 Library Ideas, LLC&nbsp;&nbsp;All Rights Reserved
		</div>
	</div>
</body>
</html>

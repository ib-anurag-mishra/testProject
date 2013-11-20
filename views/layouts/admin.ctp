<?php
/*
 File Name : admin.ctp
 File Description : View page for admin layout
 Author : m68interactive
 */
?>
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
		/*echo $this->Html->css('freegal_admin_styles');
		echo $this->Html->css('superfish');
		echo $this->Html->css('colorbox');
		echo $this->Html->css('colorpicker');*/
		/*echo $javascript->link('jquery-1.3.2.min');
		echo $javascript->link('jquery.tools.min');
		echo $javascript->link('admin_functions');
		echo $javascript->link('jquery.colorbox');
		echo $javascript->link('jquery.hoverIntent.min');
		echo $javascript->link('superfish');
		echo $javascript->link('supersubs');
		echo $javascript->link('colorpicker');*/
		?>
		<link type="text/css" rel="stylesheet" href="<?php echo $this->webroot; ?>app/webroot/min/b=app/webroot/css&amp;f=superfish.css,freegal_admin_styles.css,colorbox.css,colorpicker.css" />
                <!--<script type="text/javascript" src="<?php echo $this->webroot; ?>app/webroot/min/b=app/webroot/js&amp;f=jquery-1.3.2.min.js,jquery.tools.min.js,admin_functions.js,jquery.colorbox.js,jquery.hoverIntent.min.js,superfish.js,supersubs.js,colorpicker.js"></script>-->
		<script type="text/javascript" src="<?php echo $this->webroot; ?>app/webroot/min/b=app/webroot/js&amp;f=jquery.min.js,jquery.tools.min.js,jquery.hoverIntent.min.js,superfish.js,supersubs.js,colorpicker.js,admin_functions.js,jquery.colorbox.js"></script>
        <?php
		echo $scripts_for_layout;
	?>
	<script type="text/javascript">
		var webroot = '<?php echo $this->webroot; ?>';
	</script>
	<noscript>
		<?php
			if($this->params['action'] != 'admin_aboutus') {
				echo $html->meta(null, null, array( 'http-equiv' => 'refresh', 'content' => "0.1;url=".$this->webroot."admin/homes/aboutus/js_err"), false);
			}
		 ?>
	</noscript>
	<script type="text/javascript">
		$().ready(function() {	
			var tmpcookie = new Date();
			chkcookie = (tmpcookie.getTime() + '');
			document.cookie = "chkcookie=" + chkcookie;
			if (document.cookie.indexOf(chkcookie,0) < 0) {
				<?php if($this->params['action'] != 'admin_aboutus') { ?>
					location.href = "<?php echo $this->webroot; ?>admin/homes/aboutus/cookie_err";
				<?php } ?>
			}
		});
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
			&copy; 2011 Library Ideas, LLC  All Rights Reserved
		</div>
	</div>
</body>
</html>

<br class="clr" />
<?php
	if ($session->flash() != '') {
?>
		<div class="error_div">
		    <?php echo $session->flash(); ?>
		</div>
<?php
	}
?>
<div id="aboutUs">
    <?php echo $page->getPageContent('aboutus'); ?>
</div>
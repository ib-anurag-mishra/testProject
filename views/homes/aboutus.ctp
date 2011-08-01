<?php
/*
	 File Name : aboutus.ctp
	 File Description : View page for about us
	 Author : m68interactive
 */
?>
<?php echo $session->flash(); ?>
<div id="aboutBox">
	About Freegal Music&trade;
</div>
<div id="aboutUs"><?php echo $page->getPageContent('aboutus'); ?></div>
<?php echo $javascript->link('freegal_about_curvy'); ?>
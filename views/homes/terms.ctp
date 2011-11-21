<?php
/*
	 File Name : terms.ctp
	 File Description : View page for terms and coniditions
	 Author : m68interactive
 */
?>
<div id="aboutBox">
	<?php __('Terms &amp; Conditions');?>
</div>
<div id="terms">
    <?php echo $page->getPageContent('terms'); ?>
</div>
<?php echo $javascript->link('freegal_about_curvy'); ?>
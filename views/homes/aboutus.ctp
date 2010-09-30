<?php echo $session->flash(); ?>
<?php
if($this->Session->read('referral') && $this->Session->read('referral') != '')
{
	print $this->Session->read('referral');
}
?>
<div id="aboutBox">
	About Freegal Music&trade;
</div>
<div id="aboutUs"><?php echo $page->getPageContent('aboutus'); ?></div>
<?php echo $javascript->link('freegal_about_curvy'); ?>
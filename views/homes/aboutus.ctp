<?php
/*
	 File Name : aboutus.ctp
	 File Description : View page for about us
	 Author : m68interactive
 */
?>
<?php
if($this->Session->read('Config.language') != '') {
?>
<div class="breadCrumb">
<?php
	$html->addCrumb('About Freegal Music', '/homes/aboutus');
	echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
?>
</div>
<?php
}
?>
<br class="clr">
<style>
.txt-my-about {
	background: url("../img/<?php if($this->Session->read('Config.language') != '') { echo $this->Session->read('Config.language'); }else{ echo "en"; }?>/about.png") no-repeat scroll 0 0 transparent;
    height: 62px;
    left: 35px;
    overflow: hidden;
    position: relative;
    text-indent: -9999px;
    width: 285px;
}
</style>
<?php echo $session->flash(); ?>
<div class="txt-my-about">
	About Freegal Music&trade;
</div>
<br class="clr">
<div id="aboutUs"><?php echo $page->getPageContent('aboutus'); ?></div>
<?php
/*
	 File Name : terms.ctp
	 File Description : View page for terms and coniditions
	 Author : m68interactive
 */
?>
<div class="breadCrumb">
<?php
	$html->addCrumb(__('Terms & Conditions', true), '/homes/terms');
	echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
?>
</div>
<style>
.txt-my-terms {
	background: url("../img/<?php echo $this->Session->read('Config.language'); ?>/terms.png") no-repeat scroll 0 0 transparent;
    height: 62px;
    left: 35px;
    overflow: hidden;
    position: relative;
    text-indent: -9999px;
    width: 285px;
}
</style>
<div class="txt-my-terms">
	<?php __('Terms &amp; Conditions');?>
</div>
<div id="terms">
    <?php echo $page->getPageContent('terms'); ?>
</div>
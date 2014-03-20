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
	echo $html->getCrumbs(' > ', __('Home', true), '/homes');
?>
</div>
<br class="clr">
<style>
.txt-my-terms {
    /*background: url("../img/<?php echo $this->Session->read('Config.language'); ?>/terms.png") no-repeat scroll 0 0 transparent;*/
    height: 35px;
    margin-left:-35px;
    font-size:30px;
    left: 35px;
    overflow: hidden;
    position: relative;
    /*text-indent: -9999px;*/
    width: 285px;
}
.content{

    line-height: 25px;
    font-size: 14px;
    padding-left: 20px;
    padding-top: 20px;
}
</style>
<div class="txt-my-terms">
	<?php echo __('Terms &amp; Conditions');?>
</div>

<div id="terms">
    <?php echo $page->getPageContent('terms'); ?>
</div>

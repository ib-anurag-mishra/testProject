<?php
/*
 File Name : index.ctp
 File Description : View page for index
 Author : m68interactive
 */
?>
<style>
.txt-my-faq {
	 background: url("../img/<?php echo $this->Session->read('Config.language'); ?>/faq.png") no-repeat scroll 0 0 transparent;
    height: 62px;
    left: 40px;
    overflow: hidden;
    position: relative;
    text-indent: -9999px;
    width: 228px;
}
</style>
<div class="breadCrumb">
<?php
	$html->addCrumb('FAQ', '/questions');
	echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
?>
</div>
<br class="clr">
<div class="questions index">
	<div class="txt-my-faq">
		<?php __('FAQs');?>
	</div>
	<div class="question_list">
		<?php $Title = "";
		 foreach ($questions as $question): ?>
			<?php if($Title != $question['Section']['title']) 
			{?>
				<p><?php echo $question['Section']['title']; ?></p>
			<?}?>			
			<div class="question"><?php echo $question['Question']['question']; ?></div>
			<div class="answer"><?php echo $question['Question']['answer']; ?></div>
			<?php $Title = $question['Section']['title']; ?>
		<?php endforeach; ?>
	</div>
</div>
<?php echo $javascript->link('freegal_questions'); ?>
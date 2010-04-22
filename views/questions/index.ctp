<div class="questions index">
	<div id="aboutBox">
		<?php __('FAQs');?>
	</div>
	<br class="clr" />
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
<?php echo $javascript->link('freegal_about_curvy'); ?>
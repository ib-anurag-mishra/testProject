<?php echo $javascript->link('freegal_questions'); ?>
<div class="questions index">
	<div id="aboutBox">
		<?php __('FAQs');?>
	</div>
	<div class="question_list">
		<?php $Title = "";
		 foreach ($questions as $question): ?>
			<?php if($Title != $question['Section']['title']) 
			{?>
				<p><?php echo $question['Section']['title']; ?></p>
			<?}?>			
			<p class="question"><?php echo $question['Question']['question']; ?></p>
			<div class="answer"><?php echo $question['Question']['answer']; ?></div>
			<?php $Title = $question['Section']['title']; ?>
		<?php endforeach; ?>
	</div>
</div>
<?php echo $javascript->link('freegal_about_curvy'); ?>
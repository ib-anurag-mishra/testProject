<?php echo $javascript->link('freegal_questions'); ?>
<div class="questions index">
	<h2><?php __('FAQs');?></h2>
	<div class="question_list">
		<?php foreach ($questions as $question): ?>
			<p class="question"><?php echo $question['Question']['question']; ?></p>
			<div class="answer"><?php echo $question['Question']['answer']; ?></div>
		<?php endforeach; ?>
	</div>
</div>
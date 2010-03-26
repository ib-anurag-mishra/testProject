<?php echo $javascript->link('freegal_advsearch_curvy'); ?>
<div id="advance_search_box">
	Advance Search
</div>
<br class="clr">
<div id="advance_search">
	<?php
		echo $form->create('Home', array('action' => 'advance_search'));
		echo $form->input('Match', array('options' => array('All', 'Any')));
		echo $form->input('artist');
		echo $form->input('song');
		echo $form->input('album');
		echo $form->input('Genre');
		echo $form->end('Advance Search');
	?>
</div>

<div id="advance_search_box">
	Advanced Search
</div>
<br class="clr">
<div id="advance_search">
	<?php
		echo $form->create('Home', array('action' => 'search'));
			echo $form->input('Match', array('options' => array('All' => 'All', 'Any' => 'Any')));
			echo $form->input('artist');
			echo $form->input('composer');
			echo $form->input('song');
			echo $form->input('album');
			echo $form->input('genre_id', array('type' => 'select', 'empty' => ''));
		echo $form->end('Advanced Search');
	?>
</div>
<?php echo $javascript->link('freegal_advsearch_curvy'); ?>
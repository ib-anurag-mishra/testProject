<?php
/*
	 File Name : advance_search.ctp
	 File Description : View page for advance search
	 Author : m68interactive
 */
?>
<div class="breadCrumb">
<?php
	$html->addCrumb(__('Advance Search', true), '/homes/advance_search');
	echo $html->getCrumbs(' > ', __('Home', true), '/homes');
?>
</div>
<div id="advance_search_box">
	<?php __('Advanced Search'); ?>
</div>

<div id="advance_search">
	<?php
		echo $form->create('Home', array('action' => 'search'));
			echo $form->input('Match', array('options' => array('All' => 'All', 'Any' => 'Any')));
			echo $form->input('artist');
      echo $form->input('label');
			echo $form->input('composer');
			echo $form->input('song');
			echo $form->input('album');
			echo $form->input('genre_id', array('type' => 'select', 'empty' => ''));
		echo $form->end(__('Advanced Search', true));
	?>
</div>
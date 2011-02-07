<?php
	$this->pageTitle = 'sndLogin';
	if(!isset($card)){
		$card='';
	}	
	echo $session->flash();	
	echo $form->create('User', array( 'action' => 'sndlogin', 'id' => 'login'));
		echo '<p class="loginbox"></p>';
		echo $form->input('card', array('label' => 'Card Number','value' => $card));
		echo '<br class="clr">';
	echo $form->end('Login');
    
?>
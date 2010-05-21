<?php
	$this->pageTitle = 'snLogin';
	if(!isset($card)){
		$card='';
	}
	echo $session->flash();	
	echo $form->create('User', array( 'action' => 'snlogin', 'id' => 'login'));
		echo '<p class="loginbox"></p>';
		echo $form->input('card', array('label' => 'Card No','value' => $card));
	echo $form->end('Login');  
?>
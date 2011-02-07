<?php
	$this->pageTitle = 'sdLogin';
	if(!isset($pin)){
		$pin='';
	}
	if(!isset($card)){
		$card='';
	}	
	echo $session->flash();	
	echo $form->create('User', array( 'action' => 'sdlogin', 'id' => 'login'));
		echo '<p class="loginbox"></p>';
		echo $form->input('card', array('label' => 'Card Number','value' => $card));
		echo '<br class="clr">';
		echo $form->input('pin', array('label' => 'Pin','value' => $pin, 'type'=>'password'));
	echo $form->end('Login');
    
?>
<?php
	$this->pageTitle = 'idLogin';
	echo $session->flash();	
	echo $form->create('User', array( 'action' => 'idlogin', 'id' => 'login'));
		echo '<p class="loginbox"></p>';
		echo $form->input('card', array('label' => 'Card No','value' => $card));
		echo '<br class="clr">';
		echo $form->input('pin', array('label' => 'Pin','value' => $pin, 'type'=>'password'));
	echo $form->end('Login');
    
?>
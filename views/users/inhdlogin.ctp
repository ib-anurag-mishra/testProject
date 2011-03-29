<?php
	$this->pageTitle = 'inhdlogin';
	echo $session->flash();	
	echo $form->create('User', array( 'action' => 'inhdlogin', 'id' => 'login'));
		echo '<p class="loginbox"></p>';
		echo $form->input('card', array('label' => 'Card Number','value' => $card));
		echo '<br class="clr">';
	echo $form->end('Login');
    
?>
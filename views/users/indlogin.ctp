<?php
	$this->pageTitle = 'indLogin';
	echo $session->flash();	
	echo $form->create('User', array( 'action' => 'indlogin', 'id' => 'login'));
		echo '<p class="loginbox"></p>';
		echo $form->input('card', array('label' => 'Card Number','value' => $card));
		echo '<br class="clr">';
	echo $form->end('Login');
    
?>
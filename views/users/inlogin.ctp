<?php
	$this->pageTitle = 'inLogin';
	echo $session->flash();	
	echo $form->create('User', array( 'action' => 'inlogin', 'id' => 'login'));
		echo '<p class="loginbox"></p>';
		echo $form->input('card', array('label' => 'Card No','value' => $card));		
	echo $form->end('Login');
    
?>
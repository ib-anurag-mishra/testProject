<?php
/*
 File Name : sso.ctp
 File Description : view page for sso login
 Author : m68interactive
 */
?>
<?php
	$this->pageTitle = 'sso';
	if(!isset($card)){
		$card='';
	}
	echo $session->flash();	
	echo $form->create('User', array( 'action' => 'sso', 'id' => 'login'));
		echo '<p class="loginbox"></p>';
		echo $form->input('card', array('label' => 'Card No','value' => $card));
	echo $form->end('Login');
    
?>
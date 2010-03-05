<?php
	$this->pageTitle = 'Login';
	echo $session->flash();
	echo $session->flash('auth');
	echo $form->create('User', array( 'action' => 'admin_login', 'id' => 'login'));
		echo '<p class="loginbox"></p>';
		echo $form->input('email', array('label' => 'Email'));
		echo '<br class="clr">';
		echo $form->input('password', array('label' => 'Password'));
	echo $form->end('Login');
?>
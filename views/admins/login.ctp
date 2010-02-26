<?php
	$this->pageTitle = 'Login';
	echo $session->flash();
	echo $session->flash('auth');
	echo $form->create('Admin', array( 'action' => 'login', 'id' => 'login'));
		echo '<p class="loginbox"></p>';
		echo $form->input('username', array('label' => 'Username'));
		echo '<br class="clr">';
		echo $form->input('password', array('label' => 'Password'));
	echo $form->end('Login');
?>
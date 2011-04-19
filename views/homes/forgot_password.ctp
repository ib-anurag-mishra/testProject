<?php
    $this->pageTitle = __('Forgot Password');
    echo $session->flash();
    echo $this->Form->create('Home', array( 'controller' => 'Home','action' => 'forgot_password', 'id' => 'login'));
		echo '<p class ="loginbox"></p>';
		echo '<span class="forgot_text">Forgot Password</span>';
		echo $form->input('email', array('label' => 'Email'));
	echo $this->Form->end('Submit');
?>
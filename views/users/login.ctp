<?php
/*
 File Name : login.ctp
 File Description : view page for login
 Author : m68interactive
 */
?>
<?php
	$this->pageTitle = 'Login';
	echo $session->flash();
	echo $session->flash('auth');
		echo '<div class="login-form-wrapper clearfix">';
		echo $form->create('User', array( 'action' => 'login', 'id' => 'login-form'));
		echo '<div class="card-number-container" id = "card_number">';
		echo $form->input('email', array('label' => $this->getTextEncode( __('Email', true)),'div' => false));
		echo '</div>';
                echo '<div class="divider"></div>';
		echo '<div class="pin-number-container" id ="pass">';
		echo $form->input('password', array('label' => $this->getTextEncode( __('Password', true)),'div' => false,'type'=>'password'));
		echo '</div>';
		echo $form->end(array('label' => $this->getTextEncode( __('Login', true)),'div' => false, 'class' => 'button' ));
		echo '</div>';
		echo '<span class="forgot">';
		echo $html->link( $this->getTextEncode( __('Forgot Password?', true)), array('controller' => 'homes', 'action' => 'forgot_password'));
		echo '</span>';		
?>
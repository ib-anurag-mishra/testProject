<?php
/*
 File Name : ildlogin.ctp
 File Description : view page for ildlogin
 Author : m68interactive
 */
?>
<?php
	$this->pageTitle = 'Login';
	echo $session->flash();
	echo $session->flash('auth');
		echo '<div class="login-form-wrapper clearfix">';
		echo $form->create('User', array( 'action' => 'ilhdlogin', 'id' => 'login-form'));
		echo '<div class="card-number-container">';
		echo $form->input('card', array('label' => $this->getTextEncode( __('Card Number', true)),'value' => $card, 'alt'=> $this->getTextEncode( __('Card Number', true))));
		echo '</div>';
                echo '<div class="divider"></div>';
		echo '<div class="pin-number-container" id ="last_name">';
		echo $form->input('name', array('label' => 'Last Name','value' => $name, 'type'=>'password', 'alt'=> $this->getTextEncode( __('Last Name', true))));
		echo '</div>';
		echo $form->end(array('label' => $this->getTextEncode( __('Login', true)),'div' => false, 'class' => 'button' ));
		echo '</div>';
?>

<?php
/*
 File Name : snlogin.ctp
 File Description : view page for snlogin
 Author : m68interactive
 */
?>
<?php
	$this->pageTitle = 'snlogin';
	echo $session->flash();
	echo $session->flash('auth');
		echo '<div class="login-form-wrapper clearfix" >';
		echo $form->create('User', array( 'action' => 'snlogin', 'id' => 'login-form'));
		echo '<div class="card-number-container" id="ind_field">';
		echo $form->input('card', array('label' => $this->getTextEncode( __('Card Number', true)),'value' => $card, 'alt'=> $this->getTextEncode( __('Card Number', true))));
		echo '</div>';
                echo '<div class="divider"></div>';
		echo $form->end(array('label' => $this->getTextEncode( __('Login', true)),'div' => false, 'class' => 'button' ));
		echo '</div>';
?>

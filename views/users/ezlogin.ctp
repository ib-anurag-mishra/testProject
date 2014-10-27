<?php
/*
 File Name : ezlogin.ctp
 File Description : view page for ezlogin
 Author : LibraryIdeas
 */
?>
<?php
	$this->pageTitle = 'EzLogin';
	echo $session->flash();
	echo $session->flash('auth');
		echo '<div class="login-form-wrapper clearfix">';
		echo $form->create('User', array( 'action' => 'ezlogin', 'id' => 'login-form'));
		echo '<div class="card-number-container">';
		echo $form->input('card', array('label' => $this->getTextEncode( __('Card Number', true)),'value' => $card, 'alt'=> $this->getTextEncode( __('Card Number', true))));
		echo '</div>';
                echo '<div class="divider"></div>';
		echo '<div class="pin-number-container">';
		echo $form->input('pin', array('label' => 'Pin','value' => $pin, 'type'=>'password', 'alt'=>'PIN'));
		echo '</div>';
		echo $form->end(array('label' => $this->getTextEncode( __('Login', true)),'div' => false, 'class' => 'button' ));
		echo '</div>';
?>


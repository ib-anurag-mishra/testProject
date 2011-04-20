<?php
	$this->pageTitle = 'sndLogin';
	if(!isset($card)){
		$card='';
	}	
	echo $session->flash();	
	echo $form->create('User', array( 'action' => 'sndlogin', 'id' => 'login'));
		echo '<p class="loginbox"></p>';
		echo $form->input('card', array('label' => 'Card Number','value' => $card));
		echo '<br class="clr">';
	echo $form->end('Login');
?>
<div id="language">
<div id="english" onClick="english();">English</div>
<div id="spanish" onClick="spanish();">Spanish</div>
</div>
<div class="clr"></div>
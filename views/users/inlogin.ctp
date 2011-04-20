<?php
	$this->pageTitle = 'inLogin';
	echo $session->flash();	
	echo $form->create('User', array( 'action' => 'inlogin', 'id' => 'login'));
		echo '<p class="loginbox"></p>';
		echo $form->input('card', array('label' => 'Card Number','value' => $card));		
	echo $form->end('Login');
?>
<div id="language">
<div id="english" onClick="english();">English</div>
<div id="spanish" onClick="spanish();">Spanish</div>
</div>
<div class="clr"></div>
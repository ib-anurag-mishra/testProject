<?php
	$this->pageTitle = 'ildLogin';
	echo $session->flash();
	if(!isset($name)){
		$name='';
	}
	if(!isset($card)){
		$card='';
	}	
	echo $form->create('User', array( 'action' => 'ildlogin', 'id' => 'login'));
		echo '<p class="loginbox"></p>';
		echo $form->input('card', array('label' => 'Card Number','value' => $card));
		echo '<br class="clr">';
		echo $form->input('name', array('label' => 'Last Name','value' => $name, 'type'=>'password'));
	echo $form->end('Login');
?>
<div id="language">
<div id="english" onClick="english();">English</div>
<div id="spanish" onClick="spanish();">Spanish</div>
</div>
<div class="clr"></div>
<?php
	$this->pageTitle = 'sLogin';
	if(!isset($pin)){
		$pin='';
	}
	if(!isset($card)){
		$card='';
	}
	echo $session->flash();	
	echo $form->create('User', array( 'action' => 'slogin', 'id' => 'login'));
		echo '<p class="loginbox"></p>';
		echo $form->input('card', array('label' => 'Card Number','value' => $card));
		echo '<br class="clr">';
		echo $form->input('pin', array('label' => 'Pin','value' => $pin, 'type'=>'password'));
	echo $form->end('Login');
?>
<div id="language">
<?php __('Also available in');?>
&nbsp;&nbsp;
<a href="javascript:void(0)" id="english" onClick="english();"><?php __('English');?></a>
|
<a href="javascript:void(0)" id="spanish" onClick="spanish();"><?php __('Spanish');?></a>
</div>
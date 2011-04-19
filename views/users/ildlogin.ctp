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
<?php __('Also available in');?>
&nbsp;&nbsp;
<a href="javascript:void(0)" id="english" onClick="english();"><?php __('English');?></a>
|
<a href="javascript:void(0)" id="spanish" onClick="spanish();"><?php __('Spanish');?></a>
</div>
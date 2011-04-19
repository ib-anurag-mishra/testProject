<?php
	$this->pageTitle = 'indLogin';
	echo $session->flash();	
	echo $form->create('User', array( 'action' => 'indlogin', 'id' => 'login'));
		echo '<p class="loginbox"></p>';
		echo $form->input('card', array('label' => 'Card Number','value' => $card));
		echo '<br class="clr">';
	echo $form->end('Login');
?>
<div id="language">
<?php __('Also available in');?>
&nbsp;&nbsp;
<a href="javascript:void(0)" id="english" onClick="english();"><?php __('English');?></a>
|
<a href="javascript:void(0)" id="spanish" onClick="spanish();"><?php __('Spanish');?></a>
</div>
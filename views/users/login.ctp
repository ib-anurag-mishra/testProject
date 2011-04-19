<?php
	$this->pageTitle = 'Login';
	echo $session->flash();
	echo $session->flash('auth');
	echo $form->create('User', array( 'action' => 'login', 'id' => 'login'));
		echo '<p class="loginbox"></p>';
		echo $form->input('email', array('label' => 'Email'));
		echo '<br class="clr">';
		echo $form->input('password', array('label' => 'Password'));
		echo '<span class="forgot_password">';
		echo $html->link('Forgot Password?', array('controller' => 'homes', 'action' => 'forgot_password'));
		echo '</span>';
	echo $form->end('Login');   
?>
<div id="language">
<?php __('Also available in');?>
&nbsp;&nbsp;
<a href="javascript:void(0)" id="english" onClick="english();"><?php __('English');?></a>
|
<a href="javascript:void(0)" id="spanish" onClick="spanish();"><?php __('Spanish');?></a>
</div>
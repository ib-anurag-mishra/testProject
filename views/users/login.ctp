<?php
	$this->pageTitle = 'Login';
	echo $session->flash();
	echo $session->flash('auth');
	echo $form->create('User', array( 'action' => 'login', 'id' => 'login'));
		echo '<p class="loginbox"></p>';
		echo $form->input(__('email', true), array('label' => 'Email'));
		echo '<br class="clr">';
		echo $form->input(__('password', true), array('label' => 'Password'));
		echo '<span class="forgot_password">';
		echo $html->link(__('Forgot Password?', true), array('controller' => 'homes', 'action' => 'forgot_password'));
		echo '</span>';
	echo $form->end('Login');   
?>
<div id="language">
<?php
$language = $language->getLanguage();
foreach($language as $k => $v){
	echo "<div id=".$k." onClick='changeLang(".$k.")'";
	if($k == '1'){
		echo 'class = "active"';
	}
	else {
		echo 'class = "non-active"';
	}
	echo '>'.$v.'</div>';
}
?>
</div>
<div class="clr"></div>

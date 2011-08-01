<?php
/*
 File Name : login.ctp
 File Description : view page for login
 Author : m68interactive
 */
?>
<?php
	$this->pageTitle = 'Login';
	echo $session->flash();
	echo $session->flash('auth');
	echo $form->create('User', array( 'action' => 'login', 'id' => 'login'));
		echo '<p class="loginbox"></p>';
		echo $form->input('email', array('label' => __('Email', true)));
		echo '<br class="clr">';
		echo $form->input('password', array('label' => __('Password', true)));
		echo '<br class="clr">';
		echo '<span class="forgot_password">';
			echo $html->link(__('Forgot Password?', true), array('controller' => 'homes', 'action' => 'forgot_password'));
		echo '</span>';
	echo $form->end(__('Login', true));
?>
<div id="language">
<?php
$language = $language->getLanguage();
if($this->Session->read('Config.language') == 'en' || $this->Session->read('Config.language') == ''){
	$lang = "English";
} 
elseif($this->Session->read('Config.language') == 'es'){
	$lang = "Español";
}
elseif($this->Session->read('Config.language') == 'fr'){
	$lang = "FranÃ§ais";
}
elseif($this->Session->read('Config.language') == 'it'){
	$lang = "Italiano";
}
foreach($language as $k => $v){
	?>
	<div id="<?php echo $k; ?>" onClick="changeLang(<?php echo $k;?>,'login')"
	<?php
	if($v == $lang){
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
<script type="text/javascript">
	$("#loadingDiv").hide();
</script>

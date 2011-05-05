<?php
	$this->pageTitle = 'Login';
	echo $session->flash();
	echo $session->flash('auth');
	echo $form->create('User', array( 'action' => 'login', 'id' => 'login'));
		echo '<p class="loginbox"></p>';
		echo $form->input('email', array('label' => __('Email', true)));
		echo '<br class="clr">';
		echo $form->input('password', array('label' => __('Password', true)));
		echo '<span class="forgot_password">';
		if($this->Session->read('Config.language') == '' || $this->Session->read('Config.language') == 'en'){
			echo $html->link(__('Forgot Password?', true), array('controller' => 'homes', 'action' => 'forgot_password'));
		}
		else{
			echo $html->link(__('Olvide mi clave', true), array('controller' => 'homes', 'action' => 'forgot_password'));
		}
		echo '</span>';
		if($this->Session->read('Config.language') == '' || $this->Session->read('Config.language') == 'en'){
			echo $form->end(__('Login', true));
		}
		else{
			echo $form->end(__('Comenzar', true));
		}
?>
<div id="language">
<?php
$language = $language->getLanguage();
if($this->Session->read('Config.language') == 'en' || $this->Session->read('Config.language') == ''){
	$lang = "English";
} 
else{
	$lang = "Spanish";
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

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
		echo $html->link(__('Forgot', true).' '.__('Password', true).'?', array('controller' => 'homes', 'action' => 'forgot_password'));
		echo '</span>';
	echo $form->end('Login');   
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
	echo "<div id=".$k." onClick='changeLang(".$k.")'";
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

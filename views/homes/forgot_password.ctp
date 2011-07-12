<?php
    $this->pageTitle = __('Forgot Password', true);
    echo $session->flash();
    echo $this->Form->create('Home', array( 'controller' => 'Home','action' => 'forgot_password', 'id' => 'login'));
		echo '<p class ="loginbox"></p>';
		echo '<span class="forgot_text">'.__('Forgot Password', true).'</span>';
		echo $form->input('email', array('label' => 'Email'));
	echo $this->Form->end('Submit');
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
	<div id="<?php echo $k; ?>" onClick="changeLang_password(<?php echo $k;?>,'forgot_password')"
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
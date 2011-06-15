<?php
	$this->pageTitle = 'idLogin';
	echo $session->flash();	
	echo $form->create('User', array( 'action' => 'idlogin', 'id' => 'login'));
		echo '<p class="loginbox"></p>';
		echo $form->input('card', array('label' => __('Card Number', true),'value' => $card));
		echo '<br class="clr">';
		echo $form->input('pin', array('label' => 'Pin','value' => $pin, 'type'=>'password'));
	echo $form->end(__('Login', true));
?>
<div id="language">
<?php
$language = $language->getLanguage();
if($this->Session->read('Config.language') == 'en' || $this->Session->read('Config.language') == ''){
	$lang = "English";
} 
elseif($this->Session->read('Config.language') == 'es'){
	$lang = "EspaÃ±ol";
}
elseif($this->Session->read('Config.language') == 'fr'){
	$lang = "FranÃ§ais";
}
elseif($this->Session->read('Config.language') == 'it'){
	$lang = "Italiano";
}
foreach($language as $k => $v){
	?>
	<div id="<?php echo $k; ?>" onClick="changeLang(<?php echo $k;?>,'inlogin')"
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
<?php
/*
 File Name : ildlogin.ctp
 File Description : view page for ildlogin
 Author : m68interactive
 */
?>
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
		echo $form->input('card', array('label' => __('Card Number', true),'value' => $card));
		echo '<br class="clr">';
		echo $form->input('name', array('label' => 'Last Name','value' => $name, 'type'=>'password'));
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
	<div id="<?php echo $k; ?>" onClick="changeLang(<?php echo $k;?>,'ildlogin')"
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
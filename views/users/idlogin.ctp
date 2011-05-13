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
else{
	$lang = "Spanish";
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
	echo '>';?><?php echo __($v); ?><?php echo '</div>';
}
?>
</div>
<div class="clr"></div>
<script type="text/javascript">
	$("#loadingDiv").hide();
</script>
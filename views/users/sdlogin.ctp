<?php
/*
 File Name : sdlogin.ctp
 File Description : view page for sdlogin
 Author : m68interactive
 */
?>
<?php
	$this->pageTitle = 'sdLogin';
	if(!isset($pin)){
		$pin='';
	}
	if(!isset($card)){
		$card='';
	}
//	echo $session->flash();
	echo '<div class="login-box">';
	echo '<div class="holder">';
	echo '<fieldset>';
	echo $form->create('User', array( 'action' => 'sdlogin', 'id' => 'login', 'class'=>'login-form'));
		echo '<div class="row">';
		echo $form->input('card', array('label' => __('Card Number', true),'value' => $card, 'alt'=>__('Card Number', true)));
		echo '</div>';
		echo '<div class="row">';
		echo $form->input('pin', array('label' => 'Pin','value' => $pin, 'type'=>'password', 'alt'=>'PIN'));
		echo '</div>';
	echo $form->end(array('label' => __('Login', true),'div' => false, 'class' => 'button'));
	echo '</fieldset>';
	echo '</div>';
	echo '</div>';
?>
<ul class="lang">
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
		<li id="<?php echo $k; ?>" onClick="changeLang(<?php echo $k;?>,'sdlogin')"
		<?php
		if($v == $lang){
			echo 'class = "active"';
		}
		else {
			echo 'class = "non-active"';
		}
		echo '>'.$this->getTextEncode($v).'</li>';
	}
	?>
</ul>
<?php
if($this->Session->read('Config.language') == 'es' || $this->Session->read('Config.language') == 'it'){
?>
	<style>
	.popup .login-box .holder {padding: 46px 0px 45px}
	.popup .row label {margin: margin: 0 1px 0 0;}
	.popup .row label {width:133px}
	.popup .row input {width:116px}
	.popup .button {width:138px}
	</style>
<?php
}
?>
<div class="clr"></div>
<script type="text/javascript">
	$("#loadingDiv").hide();
</script>
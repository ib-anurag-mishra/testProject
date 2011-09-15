<?php
/*
 File Name : ihdlogin.ctp
 File Description : view page for ihdlogin
 Author : m68interactive
 */
?>
<?php
	$this->pageTitle = 'ihdlogin';
//	echo $session->flash();
	echo '<div class="login-box">';
	echo '<div class="holder">';
	echo '<fieldset>';	
	echo $form->create('User', array( 'action' => 'ihdlogin', 'id' => 'login'));
		echo '<div class="row">';
		echo $form->input('card', array('label' => __('Card Number', true),'value' => $card));
		echo '</div>';
		echo '<div class="row">';
		echo $form->input('pin', array('label' => 'Pin','value' => $pin, 'type'=>'password'));
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
		<li id="<?php echo $k; ?>" onClick="changeLang(<?php echo $k;?>,'ihdlogin')"
		<?php
		if($v == $lang){
			echo 'class = "active"';
		}
		else {
			echo 'class = "non-active"';
		}
		echo '>'.$v.'</li>';
	}
	?>
</ul>
<?php
if($this->Session->read('Config.language') == 'es'){
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
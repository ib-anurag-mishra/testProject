<?php
/*
 File Name : login.ctp
 File Description : view page for login
 Author : m68interactive
 */
?>
<?php
	$this->pageTitle = 'Login';
//	echo $session->flash();
//	echo $session->flash('auth');
		echo '<div class="login-box">';
		echo '<div class="holder">';
		echo '<fieldset>';
		echo $form->create('User', array( 'action' => 'login', 'id' => 'login', 'class'=>'login-form'));
		echo '<div class="row">';
		echo $form->input('email', array('label' => $this->getTextEncode( __('Email', true)),'div' => false,'alt' => $this->getTextEncode( __('Email', true))));
		echo '</div>';
		echo '<div class="row">';
		echo $form->input('password', array('label' => $this->getTextEncode( __('Password', true)),'div' => false, 'alt' => $this->getTextEncode( __('Password', true))));
		echo '</div>';
		echo '<span class="forgot">';
    echo $html->link( $this->getTextEncode( __('Forgot Password?', true)), array('controller' => 'homes', 'action' => 'forgot_password'));
		echo '</span>';
		echo $form->end(array('label' => $this->getTextEncode( __('Login', true)),'div' => false, 'class' => 'button' ));
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
		<li id="<?php echo $k; ?>" onClick="changeLang(<?php echo $k;?>,'login')"
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
	<script type="text/javascript">
	$(".popup .login-box .holder").css('padding', '46px 9px 45px');
	</script>
<?php
}
?>
<script type="text/javascript">
	$("#loadingDiv").hide();
</script>

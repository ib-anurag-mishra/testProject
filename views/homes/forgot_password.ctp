<?php
    $this->pageTitle = __('Forgot Password', true);
//    echo $session->flash();
	echo '<div class="login-box">';
	echo '<div class="holder">';
	echo '<fieldset>';	
    echo $this->Form->create('Home', array( 'controller' => 'Home','action' => 'forgot_password', 'id' => 'login'));
		echo '<div class="row_f">';
		echo $this->Form->label(null, __('Forgot Password', true));
		echo '</div>';
		echo '<div class="row">';
		echo $form->input('email', array('label' => __('Email', true),'div' => false, 'alt'=>__('Email', true)));
		echo '</div>';
	echo $form->end(array('label' => __('Submit', true),'div' => false, 'class' => 'submit'));		
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
		<li id="<?php echo $k; ?>" onClick="changeLang_password(<?php echo $k;?>,'forgot_password')"
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
<div class="clr"></div>
<script type="text/javascript">
	$("#loadingDiv").hide();
</script>
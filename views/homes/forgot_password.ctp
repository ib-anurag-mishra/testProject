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
		echo $form->input('email', array('label' => 'Email'));
		echo '</div>';
	echo $form->end(array('label' => __('Submit', true),'div' => false, 'class' => 'button'));		
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
		<li id="<?php echo $k; ?>" onClick="changeLang(<?php echo $k;?>,'snlogin')"
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
<style>
.popup .row_f label {
    color: #FFFFFF;
    font-weight: bold;
    line-height: 17px;
    margin: 0 3px 0 65px;
    text-align: left;
    width: 112px;
}
</style>
<?php
if($this->Session->read('Config.language') == 'es'){
?>
	<style>
	.popup .login-box .holder {padding: 46px 0px 45px}
	.popup .row label {margin: margin: 0 1px 0 0;}
	.popup .row label {width:133px;text-align: center;}
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
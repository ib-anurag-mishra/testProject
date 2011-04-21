<?php
	$this->pageTitle = 'inLogin';
	echo $session->flash();	
	echo $form->create('User', array( 'action' => 'inlogin', 'id' => 'login'));
		echo '<p class="loginbox"></p>';
		echo $form->input('card', array('label' => 'Card Number','value' => $card));		
	echo $form->end('Login');
?>
<div id="language">
<?php
$language = $language->getLanguage();
foreach($language as $k => $v){
	echo "<div id=".$k." onClick='changeLang(".$k.")'";
	if($k == '1'){
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
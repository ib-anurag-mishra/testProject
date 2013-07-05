<?php
	$this->pageTitle = 'Login';
	echo $session->flash();
	echo $session->flash('auth');
?>

<div class="login-container clearfix">
<header class="clearfix">
        <h3>Forgot Password</h3>
           
</header>
 <div class="login-library-container clearfix">
        <div class="form-wrapper">
<?php
    //$this->pageTitle = __('Forgot Password', true);
//    echo $session->flash();
//	echo '<div class="login-box">';
//	echo '<div class="holder">';
//	echo '<fieldset>';
    //echo $this->Form->create('Home', array( 'controller' => 'Home','action' => 'forgot_password', 'id' => 'login', 'class'=>'login-form'));
           echo $this->Form->create('Home', array('url' => array('controller' => 'homes', 'action' =>'forgot_password')));
		echo '<div class="row_f">';
		//echo $this->Form->label(null, __('Forgot Password', true));
		echo '</div>';
                
                ?>
    
    <div class="input text">
                    <label for="LibraryZipcode">Email </label>
                    <input type="text" id="LibraryZipcode"  name="email" >
                    <input type="hidden" i  name="hid_action" value="1">
    </div>
    
    <?php
//		echo '<div class="row">';
//		echo $form->input('email', array('label' => __('Email', true),'div' => false, 'alt'=>__('Email', true)));
//		echo '</div>';
	//echo $form->end(array('label' => __('Submit', true),'div' => false, 'class' => 'submit'));
        echo $this->Html->div('loginbtn', $form->end('Submit'));
//	echo '</fieldset>';
//	echo '</div>';
//	echo '</div>';
?>
            
        </div>
 </div>
    
<ul class="lang">
	<?php
//	$language = $language->getLanguage();
//	if($this->Session->read('Config.language') == 'en' || $this->Session->read('Config.language') == ''){
//		$lang = "English";
//	}
//	elseif($this->Session->read('Config.language') == 'es'){
//		$lang = "Espa�ol";
//	}
//	elseif($this->Session->read('Config.language') == 'fr'){
//		$lang = "Français";
//	}
//	elseif($this->Session->read('Config.language') == 'it'){
//		$lang = "Italiano";
//	}
//	foreach($language as $k => $v){
//		?>
		<li id="<?php //echo $k; ?>" onClick="changeLang_password(<?php echo $k;?>,'forgot_password')"
		<?php
//		if($v == $lang){
//			echo 'class = "active"';
//		}
//		else {
//			echo 'class = "non-active"';
//		}
//		echo '>'.$v.'</li>';
//	}
	?>
</ul>
<div class="clr"></div>
<script type="text/javascript">
	$("#loadingDiv").hide();
</script>
</div>
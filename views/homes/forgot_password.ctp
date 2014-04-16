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
        echo $this->Form->create('Home', array('url' => array('controller' => 'homes', 'action' =>'forgot_password')));
		echo '<div class="row_f">';
		echo '</div>';
?>
    
    <div class="input text">
	    <label for="LibraryZipcode">Email </label>
        <input type="text" id="LibraryZipcode"  name="email" >
        <input type="hidden" name="hid_action" value="1">
    </div>
    
    <?php

      echo $this->Html->div('loginbtn', $form->end('Submit'));
?>
            
        </div>
 </div>

<div class="clr"></div>
<script type="text/javascript">
	$("#loadingDiv").hide();
</script>
</div>
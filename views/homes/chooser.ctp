<script language="text/javascript">
	$(function() {
		$('.blkButton').corner('5px');
	});
</script>
<?php
	$this->pageTitle = 'Login';
	echo $session->flash();
	echo $session->flash('auth');
?>
<div class="login-container clearfix">
    <header class="clearfix">
        <h3>Select Your Library</h3>
            <div class="more-info">Need help? Visit our <a href="/questions">FAQ section</a></div>
    </header>
    <div class="login-library-container clearfix">
        <div class="form-wrapper">
            <?php /*<legend align="center" ><?php __('<p>Please enter either your Library Name, Zip Code, City, State or Country.</p>'); ?></legend> */ ?>
                <p>Please enter either your Library Name, Zip Code, City, State or Country.</p>
                <?php
                    echo $this->Form->create('Library_details1', array('url' => array('controller' => 'homes', 'action' =>'chooser')));
                ?>
                <div class="input text">
                    <label for="LibraryZipcode">Library Name </label>
                    <input type="text" id="LibraryZipcode"  name="data[Library_details1][library_name]" >
                </div>
		<div class="input text">
                    <label for="LibraryZipcode">Zip Code</label>
                    <input type="text" id="LibraryZipcode"  name="data[Library_details1][zipcode]" >
                </div>
		<div class="input text">
                    <label for="LibraryZipcode">City </label>
                    <input type="text" id="LibraryZipcode"  name="data[Library_details1][city]" >
                </div>
		<div class="input text">
                    <label for="LibraryZipcode">State </label>
                    <input type="text" id="LibraryZipcode"  name="data[Library_details1][state]" >
                    <!--<select name="state" id="state">
                        <option>Tennessee</option>
			<option>California</option>
			<option>New York</option>
                    </select>-->
		</div>
		<?php
                    //Added code for contries
                    if(count($territorylist) > 1) { 
                        echo '<div class="select_country"><div style="float:right">';
                        echo $this->Form->input('Library_details1.country', array('type'=>'select','label' => 'Country ','options'=>$territorylist));
                        echo '</div></div>';																
                    } 

                   echo $this->Html->div('loginbtn', $form->end('Find Libraries'));
               ?>
	</div>
        <?php if(isset($libraries)) {
		if(!empty($libraries)){
        ?>
	<div class="library-list-container">
        <?php /*<legend align="center"><?php __('<b>Please select your Library</b>'); ?></legend> */ ?>
        <p>Please select your Library</p>
        <?php /*<br>*/ ?>
            <div class="sidebox">
                    <div class="holder">
                            <div class="frame library-list-scrollable" align="left">
                            <table align="left">
                            <?php foreach($libraries as $library_var) { $library_name_var   =   $library_var['Library']['library_name'];  $library_subdomain   =   empty($library_var['Library']['library_subdomain'])?'www':$library_var['Library']['library_subdomain'];  ?>
                                <tr>
                                    <?php
                                        if(empty($library_var['Library']['library_subdomain'])){ ?>
                                            <td onclick="window.location='<?php echo 'http://'.$library_subdomain.'.'.Configure::read('App.name').'/users/redirection/'.$library_var['Library']['id']; ?>';" ><?php echo (strlen($library_name_var)>40)?substr(strtoupper($this->getTextEncode($library_name_var)),0,40)."...":$this->getTextEncode($library_name_var); ?></td>    
                                  <?php } else if($library_subdomain == 'www' && $library_var['Library']['library_authentication_method'] == 'user_account'){ ?>
                                            <td onclick="window.location='<?php echo 'http://'.$library_subdomain.'.'.Configure::read('App.name').'/users/login'; ?>';" ><?php echo (strlen($library_name_var)>40)?substr(strtoupper($this->getTextEncode($library_name_var)),0,40)."...":$this->getTextEncode($library_name_var); ?></td>
                                  <?php }else{ ?>
                                            <td onclick="window.location='<?php echo 'http://'.$library_subdomain.'.'.Configure::read('App.name').'/users/redirection_manager'; ?>';" ><?php echo (strlen($library_name_var)>40)?substr(strtoupper($this->getTextEncode($library_name_var)),0,40)."...":$this->getTextEncode($library_name_var); ?></td>
                                  <?php } ?>
                                </tr>
                            <?php } ?>
                            </table>
                            </div>
                    </div>
            </div>
    </div>
        <?php } } ?>
    </div>
</div>
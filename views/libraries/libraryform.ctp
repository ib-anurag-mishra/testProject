<?php
echo $this->Form->create('Library', array( 'controller' => 'Library','action' => $formAction));
if(empty($getData))
    {
        $getData['Library']['id'] = "";
        $getData['Library']['first_name'] = "";
        $getData['Library']['last_name'] = "";
        $getData['Library']['username'] = "";
        $getData['Library']['library_name'] = "";   
        $getData['Library']['referrer_url'] = "";        
        $getData['Library']['download_limit'] = "";
    }
?>
<fieldset>
<legend><?php echo $formHeader;?></legend>        
<?php echo $this->Form->hidden( 'id', array( 'label' => false ,'value' => $getData['Library']['id'])); ?>
<?php echo $this->Form->label('First Name');?><br/>
<?php echo $this->Form->input('first_name',array( 'label' => false ,'value' => $getData['Library']['first_name']));?>
<?php echo $this->Form->label('Last Name');?><br/>
<?php echo $this->Form->input('last_name',array( 'label' => false ,'value' => $getData['Library']['last_name']));?>
<?php echo $this->Form->label('Username');?><br/>
<?php echo $this->Form->input('username',array( 'label' => false ,'value' => $getData['Library']['username']));?>
<?php echo $this->Form->label('Password');?><br/>
<?php echo $this->Form->password('password', array( 'label' => false,'value' => '')); ?><br/>
<?php echo $this->Form->label('Library Name');?><br/>
<?php echo $this->Form->input('library_name',array( 'label' => false ,'value' => $getData['Library']['library_name']));?>
<?php echo $this->Form->label('Referral Url');?><br/>
<?php echo $this->Form->input('referrer_url',array( 'label' => false ,'value' => $getData['Library']['referrer_url']));?>
<?php echo $this->Form->label('Download Limit Per Week');?><br/>
<?php echo $this->Form->input('download_limit',array('type' => 'select','options' => array('5' => '5','10' => '10','15' =>'15','20' => '20'),'label' => false,'selected' => $getData['Library']['download_limit']));?><br/>
<p class="submit"><input type="submit" value="Save" /></p>
<?php echo $this->Form->end(); ?>
<fieldset> 
<?php 
 echo $session->flash();
?>
   </fieldset> 
</form>
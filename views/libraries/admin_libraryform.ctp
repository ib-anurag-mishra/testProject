<?php
 $this->pageTitle = 'Admin'; 
echo $this->Form->create('Library', array( 'controller' => 'Library','action' => $formAction));
if(empty($getData))
    {
        $getData['Library']['id'] = "";
        $getData['Library']['admin_id'] = "";        
        $getData['Library']['library_name'] = "";   
        $getData['Library']['referrer_url'] = "";        
        $getData['Library']['download_limit'] = "";
        $getData['Library']['library_download_daily_limit'] = "";
        $getData['Library']['library_download_weekly_limit'] = "";
        $getData['Library']['library_download_monthly_limit'] = "";
        $getData['Library']['library_download_annual_limit'] = "";
    }
?>
<fieldset>
<legend><?php echo $formHeader;?></legend>        
<?php echo $this->Form->hidden( 'id', array( 'label' => false ,'value' => $getData['Library']['id'])); ?>
<?php echo $this->Form->label('Library Admin');?><br/>
<?php echo $this->Form->input('admin_id',array('type' => 'select','label' => false,'options' => $allAdmins),$getData['Library']['admin_id']) ;?><br/>
<?php echo $this->Form->label('Library Name');?><br/>
<?php echo $this->Form->input('library_name',array('label' => false ,'value' => $getData['Library']['library_name']));?>
<?php echo $this->Form->label('Referral Url');?><br/>
<?php echo $this->Form->input('referrer_url',array( 'label' => false ,'value' => $getData['Library']['referrer_url']));?>
<?php echo $this->Form->label('User Download Limit Per Week');?><br/>
<?php echo $this->Form->input('download_limit',array('type' => 'select','options' => array('5' => '5','10' => '10','15' =>'15','20' => '20'),'label' => false,'selected' => $getData['Library']['download_limit']));?><br/>
<?php echo $this->Form->label('Libraryr Download Limit Per Day');?><br/>
<?php echo $this->Form->input('library_download_daily_limit',array( 'label' => false ,'value' => $getData['Library']['library_download_daily_limit']));?>
<?php echo $this->Form->label('Libraryr Download Limit Per Week');?><br/>
<?php echo $this->Form->input('library_download_weekly_limit',array( 'label' => false ,'value' => $getData['Library']['library_download_weekly_limit']));?>
<?php echo $this->Form->label('Libraryr Download Limit Per Month');?><br/>
<?php echo $this->Form->input('library_download_monthly_limit',array( 'label' => false ,'value' => $getData['Library']['library_download_monthly_limit']));?>
<?php echo $this->Form->label('Libraryr Download Limit Per Year');?><br/>
<?php echo $this->Form->input('library_download_annual_limit',array( 'label' => false ,'value' => $getData['Library']['library_download_annual_limit']));?>
<p class="submit"><input type="submit" value="Save" /></p>
<?php echo $this->Form->end(); ?>
<?php 
 echo $session->flash();
?>
</form>
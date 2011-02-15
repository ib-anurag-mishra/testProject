<?php $this->pageTitle = 'Admin'; ?>
<form>
<fieldset>
<legend>Welcome <?php echo $session->read('Auth.User.first_name'); ?></legend>
Welcome to the Administrative Section of <b><i>Freegal Music</i></b>
</fieldset>
</form>
<?php if($this->Session->read('Auth.User.type_id') == 1){ ?>
<?php $this->pageTitle = 'Libraries'; ?>
<form>
<fieldset>
<legend>Library Listing</legend>
<p>
<?php
$curStartDate = date("Y-m-d")." 00:00:00";
$curEndDate = date("Y-m-d")." 23:59:59";
$curWeekStartDate = Configure::read('App.curWeekStartDate');
$curWeekEndDate = Configure::read('App.curWeekEndDate');
$monthStartDate = date("Y-m-d", strtotime('this month',strtotime(date('m').'/01/'.date('Y').' 00:00:00')))." 00:00:00";
$monthEndDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime('this month',strtotime(date('m').'/01/'.date('Y').' 00:00:00')))))." 23:59:59";
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
  <table id="list">
          <tr>            
            <th class="left" style="border-right:1px solid #E0E0E0" rowspan="2">Name</th>
			<th class="left" style="border-right:1px solid #E0E0E0;text-align:center" colspan="2">Contract</th>
			<th class="left" style="border-right:1px solid #E0E0E0;text-align:center" colspan="5">Downloads</th>
		</tr>
		<tr>
            <th style="border-right:1px solid #E0E0E0">Start Date</th>
			<th style="border-right:1px solid #E0E0E0">End Date</th>
            <th style="border-right:1px solid #E0E0E0">Today </th>
			<th style="border-right:1px solid #E0E0E0">Week</th>
            <th style="border-right:1px solid #E0E0E0">Month</th>
			<th style="border-right:1px solid #E0E0E0">YTD</th>
			<th style="border-right:1px solid #E0E0E0">Remaining</th>	
          </tr>
          <?php
          foreach($libraries as $library)
          {
            ?>
            <tr>
                <td class="left"><?php echo $library['Library']['library_name'];?></td>
				<td class="left"><?php echo $library['Library']['library_contract_start_date'];?></td>
				<td class="left"><?php echo date("Y-m-d",strtotime($library['Library']['library_contract_start_date'])+365*24*60*60);?></td>
				<td class="left"><?php echo $download->getDownloadData($library['Library']['id'], $curStartDate, $curEndDate);?></td>
				<td class="left"><?php echo $download->getDownloadData($library['Library']['id'], $curWeekStartDate, $curWeekEndDate);?></td>
				<td class="left"><?php echo $download->getDownloadData($library['Library']['id'], $monthStartDate, $monthEndDate);?></td>
				<td class="left"><?php echo $download->getDownloadData($library['Library']['id'], $library['Library']['library_contract_start_date']." 00:00:00", date("Y-m-d",strtotime($library['Library']['library_contract_start_date'])+365*24*60*60)." 23:59:59");?></td>
				<td class="left"><?php echo $library['Library']['library_available_downloads'];?></td>
				
            </tr>            
            <?php
          }
          ?>
        </table>
	<br class="clr" />
	<div class="paging">
	      <?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
	| 	<?php echo $paginator->numbers();?>
	      <?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
	</div>
</fieldset>
<?php 
 echo $session->flash();
?>
</form>
<?php } ?>
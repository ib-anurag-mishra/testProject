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
<br class="clr">
<div id="library_search">
 <a name="bottom">Library Search&nbsp;</a>&nbsp;
 <?php echo $html->link('ALL',array('controller' => 'users', 'action' => 'admin_index'));?>&nbsp;
 <?php echo $html->link('#',array('controller' => 'users', 'action' => 'admin_index', 'special'));?>&nbsp;
 <?php echo $html->link('A',array('controller' => 'users', 'action' => 'admin_index', 'A'));?>&nbsp;
 <?php echo $html->link('B',array('controller' => 'users', 'action' => 'admin_index', 'B'));?>&nbsp;
 <?php echo $html->link('C',array('controller' => 'users', 'action' => 'admin_index', 'C'));?>&nbsp;
 <?php echo $html->link('D',array('controller' => 'users', 'action' => 'admin_index', 'D'));?>&nbsp;
 <?php echo $html->link('E',array('controller' => 'users', 'action' => 'admin_index', 'E'));?>&nbsp;
 <?php echo $html->link('F',array('controller' => 'users', 'action' => 'admin_index', 'F'));?>&nbsp;
 <?php echo $html->link('G',array('controller' => 'users', 'action' => 'admin_index', 'G'));?>&nbsp;
 <?php echo $html->link('H',array('controller' => 'users', 'action' => 'admin_index', 'H'));?>&nbsp;
 <?php echo $html->link('I',array('controller' => 'users', 'action' => 'admin_index', 'I'));?>&nbsp;
 <?php echo $html->link('J',array('controller' => 'users', 'action' => 'admin_index', 'J'));?>&nbsp;
 <?php echo $html->link('K',array('controller' => 'users', 'action' => 'admin_index', 'K'));?>&nbsp;
 <?php echo $html->link('L',array('controller' => 'users', 'action' => 'admin_index', 'L'));?>&nbsp;
 <?php echo $html->link('M',array('controller' => 'users', 'action' => 'admin_index', 'M'));?>&nbsp;
 <?php echo $html->link('N',array('controller' => 'users', 'action' => 'admin_index', 'N'));?>&nbsp;
 <?php echo $html->link('O',array('controller' => 'users', 'action' => 'admin_index', 'O'));?>&nbsp;
 <?php echo $html->link('P',array('controller' => 'users', 'action' => 'admin_index', 'P'));?>&nbsp;
 <?php echo $html->link('Q',array('controller' => 'users', 'action' => 'admin_index', 'Q'));?>&nbsp;
 <?php echo $html->link('R',array('controller' => 'users', 'action' => 'admin_index', 'R'));?>&nbsp;
 <?php echo $html->link('S',array('controller' => 'users', 'action' => 'admin_index', 'S'));?>&nbsp;
 <?php echo $html->link('T',array('controller' => 'users', 'action' => 'admin_index', 'T'));?>&nbsp;
 <?php echo $html->link('U',array('controller' => 'users', 'action' => 'admin_index', 'U'));?>&nbsp;
 <?php echo $html->link('V',array('controller' => 'users', 'action' => 'admin_index', 'V'));?>&nbsp;
 <?php echo $html->link('W',array('controller' => 'users', 'action' => 'admin_index', 'W'));?>&nbsp;
 <?php echo $html->link('X',array('controller' => 'users', 'action' => 'admin_index', 'X'));?>&nbsp;
 <?php echo $html->link('Y',array('controller' => 'users', 'action' => 'admin_index', 'Y'));?>&nbsp;
 <?php echo $html->link('Z',array('controller' => 'users', 'action' => 'admin_index', 'Z'));?>&nbsp;
</div>
<br class="clr">
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
			<th class="left" style="border-right:1px solid #E0E0E0;text-align:center" colspan="6">Downloads</th>
		</tr>
		<tr>
            <th style="border-right:1px solid #E0E0E0">Start Date</th>
            <th class="left"><?php echo $paginator->sort('End Date', 'library_contract_start_date')."&nbsp;".$paginator->sort('`', 'library_contract_start_date', array('id' => 'sort_arrow'));?></th>
            <th style="border-right:1px solid #E0E0E0">Today </th>
			<th style="border-right:1px solid #E0E0E0">Week</th>
            <th style="border-right:1px solid #E0E0E0">Month</th>
			<th style="border-right:1px solid #E0E0E0">YTD</th>
            <th class="left"><?php echo $paginator->sort('Remaining', 'library_available_downloads')."&nbsp;".$paginator->sort('`', 'library_available_downloads', array('id' => 'sort_arrow'));?></th>
          </tr>
          <?php
          foreach($libraries as $library)
          {
            ?>
            <tr>
				<td><?php echo $html->link($library['Library']['library_name'], array('controller'=>'libraries','action'=>'libraryform','id'=>$library['Library']['id']));?></td>
				<td class="left"><?php echo $library['Library']['library_contract_start_date'];?></td>
				<td class="left"><?php echo $library['Library']['library_contract_end_date'];?></td>
				<td class="left"><?php echo $download->getDownloadData($library['Library']['id'], $curStartDate, $curEndDate);?></td>
				<td class="left"><?php echo $download->getDownloadData($library['Library']['id'], $curWeekStartDate, $curWeekEndDate);?></td>
				<td class="left"><?php echo $download->getDownloadData($library['Library']['id'], $monthStartDate, $monthEndDate);?></td>
				<td class="left"><?php echo $download->getDownloadData($library['Library']['id'], $library['Library']['library_contract_start_date']." 00:00:00", $library['Library']['library_contract_end_date']." 23:59:59");?></td>
				<td class="left"><?php if($library['Library']['library_unlimited'] == 1){
				 echo "Unlimited"; } else { echo $library['Library']['library_available_downloads']; }?></td>
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
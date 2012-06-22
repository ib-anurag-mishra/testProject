
<?php
/*
 File Name : admin_index.ctp
 File Description : view page for admin index
 Author : m68interactive
 */
?>
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
<?php
echo "<table  id='list'>\n";
echo "\t<tr>\n";
echo "\t\t<th>&nbsp;</th>\n";
echo "\t\t<th>Library Name</th>\n";
echo "\t\t<th>Start Date</th>\n";
echo "\t\t<th>End Date</th>\n";
echo "\t\t<th>Today</th>\n";
echo "\t\t<th>Week</th>\n";				
echo "\t\t<th>Month</th>\n";
echo "\t\t<th>YTD</th>\n";
echo "\t\t<th>Remaining</th>\n";
echo "\t</tr>\n";
foreach($x as $library)
{
	if($library['library_name'] != ''){
	?>
		<tr>
			<td><?php echo $library['id'];?></td>
			<td><?php echo $html->link($library['library_name'], array('controller'=>'libraries','action'=>'libraryform','id'=>$library['id']));?></td>
			<td class="left"><?php echo $library['library_contract_start_date'];?></td>
			<td class="left"><?php echo $library['library_contract_end_date'];?></td>
			<td class="left"><?php echo $library['day'];?></td>
			<td class="left"><?php echo $library['week'];?></td>
			<td class="left"><?php echo $library['month'];?></td>
			<td class="left"><?php echo $library['ytd'];?></td>
			<td class="left"><?php if($library['library_unlimited'] == 1){
			 echo "Unlimited"; } else { echo $library['library_available_downloads']; }?></td>
		</tr>            
	<?php
	}
  }
  ?>
</table>
</fieldset>
<?php 
 echo $session->flash();
?>
</form>
<?php } ?>
<script type="text/javascript" src="//asset0.zendesk.com/external/zenbox/zenbox-2.0.js"></script>
<style type="text/css" media="screen, projection">

    @import url(//asset0.zendesk.com/external/zenbox/zenbox-2.0.css);

</style>
<script type="text/javascript">

    if (typeof(Zenbox) !== "undefined") {

        Zenbox.init({
        dropboxID: "20038017",
        url: "libraryideas.zendesk.com",
        tabID: "support",
        tabColor: "#0099FF",
        tabPosition: "Right"

    });

    }

</script>
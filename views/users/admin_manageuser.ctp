<?php
/*
 File Name : admin_manageuser.ctp
 File Description : view page for manage user
 Author : m68interactive
 */
?>
<?php $this->pageTitle = 'Admin'; ?>
<form>
<fieldset>
<legend>User Listing</legend>
<br class="clr">
<div id="library_search">
<a name="bottom">User Search&nbsp;</a>&nbsp;
<?php echo $html->link('ALL',array('controller' => 'users', 'action' => 'admin_manageuser'));?>&nbsp;
<?php echo $html->link('#',array('controller' => 'users', 'action' => 'admin_manageuser', 'special'));?>&nbsp;
<?php echo $html->link('A',array('controller' => 'users', 'action' => 'admin_manageuser', 'A'));?>&nbsp;
<?php echo $html->link('B',array('controller' => 'users', 'action' => 'admin_manageuser', 'B'));?>&nbsp;
<?php echo $html->link('C',array('controller' => 'users', 'action' => 'admin_manageuser', 'C'));?>&nbsp;
<?php echo $html->link('D',array('controller' => 'users', 'action' => 'admin_manageuser', 'D'));?>&nbsp;
<?php echo $html->link('E',array('controller' => 'users', 'action' => 'admin_manageuser', 'E'));?>&nbsp;
<?php echo $html->link('F',array('controller' => 'users', 'action' => 'admin_manageuser', 'F'));?>&nbsp;
<?php echo $html->link('G',array('controller' => 'users', 'action' => 'admin_manageuser', 'G'));?>&nbsp;
<?php echo $html->link('H',array('controller' => 'users', 'action' => 'admin_manageuser', 'H'));?>&nbsp;
<?php echo $html->link('I',array('controller' => 'users', 'action' => 'admin_manageuser', 'I'));?>&nbsp;
<?php echo $html->link('J',array('controller' => 'users', 'action' => 'admin_manageuser', 'J'));?>&nbsp;
<?php echo $html->link('K',array('controller' => 'users', 'action' => 'admin_manageuser', 'K'));?>&nbsp;
<?php echo $html->link('L',array('controller' => 'users', 'action' => 'admin_manageuser', 'L'));?>&nbsp;
<?php echo $html->link('M',array('controller' => 'users', 'action' => 'admin_manageuser', 'M'));?>&nbsp;
<?php echo $html->link('N',array('controller' => 'users', 'action' => 'admin_manageuser', 'N'));?>&nbsp;
<?php echo $html->link('O',array('controller' => 'users', 'action' => 'admin_manageuser', 'O'));?>&nbsp;
<?php echo $html->link('P',array('controller' => 'users', 'action' => 'admin_manageuser', 'P'));?>&nbsp;
<?php echo $html->link('Q',array('controller' => 'users', 'action' => 'admin_manageuser', 'Q'));?>&nbsp;
<?php echo $html->link('R',array('controller' => 'users', 'action' => 'admin_manageuser', 'R'));?>&nbsp;
<?php echo $html->link('S',array('controller' => 'users', 'action' => 'admin_manageuser', 'S'));?>&nbsp;
<?php echo $html->link('T',array('controller' => 'users', 'action' => 'admin_manageuser', 'T'));?>&nbsp;
<?php echo $html->link('U',array('controller' => 'users', 'action' => 'admin_manageuser', 'U'));?>&nbsp;
<?php echo $html->link('V',array('controller' => 'users', 'action' => 'admin_manageuser', 'V'));?>&nbsp;
<?php echo $html->link('W',array('controller' => 'users', 'action' => 'admin_manageuser', 'W'));?>&nbsp;
<?php echo $html->link('X',array('controller' => 'users', 'action' => 'admin_manageuser', 'X'));?>&nbsp;
<?php echo $html->link('Y',array('controller' => 'users', 'action' => 'admin_manageuser', 'Y'));?>&nbsp;
<?php echo $html->link('Z',array('controller' => 'users', 'action' => 'admin_manageuser', 'Z'));?>&nbsp;
</div>
<br class="clr">
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
  <table id="list">
          <tr>
            <th class="left">First Name</th>
            <th class="left">Last Name</th>
            <th class="left">Email</th>
			<th class="left">Admin Type</th>
            <th>Edit</th>
			<th>Action</th>
          </tr>
          <?php
          foreach($admins as $admin)
          {
            ?>
            <tr>
                <td class="left"><?php echo $admin['User']['first_name'];?></td>
                <td class="left"><?php echo $admin['User']['last_name'];?></td>
                <td class="left"><?php echo $admin['User']['email'];?></td>
		<td class="left"><?php if($admin['User']['consortium'] != ''){ echo 'consortium'; }else{ echo $user->getAdminType($admin['User']['type_id']);} ?></td>
        <td><?php echo $html->link('Edit', array('controller'=>'users','action'=>'userform','id'=>$admin['User']['id']));?></td>
		<td>
		<?php
		if($admin['User']['type_id']!=1){
			if($admin['User']['user_status']=='inactive'){
				echo $html->link('Activate', array('controller'=>'users','action'=>'user_activate','id'=>$admin['User']['id']));
			}else{
				echo $html->link('Deactivate', array('controller'=>'users','action'=>'user_deactivate','id'=>$admin['User']['id']));
			}
		}
		?>
		</td>
		<?php
		if($admin['User']['type_id'] != 4 && $this->Session->read("Auth.User.id") != $admin['User']['id']) {
		?>

		<? } ?>
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
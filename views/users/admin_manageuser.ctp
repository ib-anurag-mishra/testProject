<?php $this->pageTitle = 'Admin'; ?>
<form>
<fieldset>
<legend>User Listing</legend>
  <table id="list">
          <tr>
            <th class="left">First Name</th>
            <th class="left">Last Name</th>
            <th class="left">Email</th>
	    <th class="left">Admin Type</th>
            <th>Edit</th>
	    <th>Delete</th>
          </tr>
          <?php
          foreach($admins as $admin)
          {
            ?>
            <tr>
                <td class="left"><?php echo $admin['User']['first_name'];?></td>
                <td class="left"><?php echo $admin['User']['last_name'];?></td>
                <td class="left"><?php echo $admin['User']['email'];?></td>
		<td class="left"><?php echo $user->getAdminType($admin['User']['type_id']); ?></td>
                <td><?php echo $html->link('Edit', array('controller'=>'users','action'=>'userform','id'=>$admin['User']['id']));?></td>
		<?php
		if($admin['User']['type_id'] != 4) {
		?>
			<td><?php echo $html->link('Delete', array('controller'=>'users','action'=>'admin_delete','id'=>$admin['User']['id']));?></td>
		<? } ?>
            </tr>
            
            <?php
          }
          ?>
        </table>
</fieldset>
<?php 
 echo $session->flash();
?>
</form>
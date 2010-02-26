<?php $this->pageTitle = 'Users'; ?>
<form>
<fieldset>
<legend>User Listing</legend>
	<table id="list">
		<tr>
			<th class="left">First Name</th>
			<th>Last Name</th>
			<th>Email</th>
			<th>Username</th>
			<th>Edit</th>
			<th>Delete</th>
		</tr>
		<?php
			foreach($admins as $admin) {
		?>
		<tr>
			<td class="left"><?php echo $admin['Admin']['first_name'];?></td>
			<td><?php echo $admin['Admin']['last_name'];?></td>
			<td><?php echo $admin['Admin']['email'];?></td>
			<td><?php echo $admin['Admin']['username'];?></td>
			<td><?php echo $html->link('Edit', array('controller'=>'admin_homes','action'=>'userform','id'=>$admin['Admin']['id']));?></td>
			<td><?php echo $html->link('Delete', array('controller'=>'admin_homes','action'=>'delete','id'=>$admin['Admin']['id']));?></td>
		</tr>
		<?php
			}
		?>
	</table>
</fieldset>
<?php if ($session->check('Message.flash')) { ?>
	<fieldset>
	   <?php echo $session->flash();?> 
	</fieldset>
<?php } ?>
</form>
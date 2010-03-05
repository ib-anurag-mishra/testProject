<?php $this->pageTitle = 'Admin'; ?>
<form>
<fieldset>
<legend>User Listing</legend>
  <table id="list">
          <tr>
            <th class="left">First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Edit</th>
	    <th>Delete</th>
          </tr>
          <?php
          foreach($admins as $admin)
          {
            ?>
            <tr>
                <td class="left"><?php echo $admin['User']['first_name'];?></td>
                <td><?php echo $admin['User']['last_name'];?></td>
                <td><?php echo $admin['User']['email'];?></td>
                <td><?php echo $html->link('Edit', array('controller'=>'users','action'=>'userform','id'=>$admin['User']['id']));?></td>
                <td><?php echo $html->link('Delete', array('controller'=>'users','action'=>'admin_delete','id'=>$admin['User']['id']));?></td>
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
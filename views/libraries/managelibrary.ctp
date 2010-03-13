<?php $this->pageTitle = 'Admin'; ?>
<form>
<fieldset>
<legend>User Listing</legend>
  <table id="list">
          <tr>
            <th class="left">First Name</th>
            <th>Last Name</th>
            <th>Username</th>
            <th>Library Name</th>
            <th>Edit</th>
	    <th>Delete</th>
          </tr>
          <?php
          foreach($libraries as $library)
          {
            ?>
            <tr>
                <td class="left"><?php echo $library['Library']['first_name'];?></td>
                <td><?php echo $library['Library']['last_name'];?></td>
                <td><?php echo $library['Library']['username'];?></td>
                <td><?php echo $library['Library']['library_name'];?></td>
                <td><?php echo $html->link('Edit', array('controller'=>'libraries','action'=>'libraryform','id'=>$library['Library']['id']));?></td>
                <td><?php echo $html->link('Delete', array('controller'=>'libraries','action'=>'delete','id'=>$library['Library']['id']));?></td>
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
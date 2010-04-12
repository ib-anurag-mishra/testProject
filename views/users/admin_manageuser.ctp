<?php $this->pageTitle = 'Admin'; ?>
<form>
<fieldset>
<legend>User Listing</legend>
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
	    <!-- <th>Delete</th> -->
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
		if($admin['User']['type_id'] != 4 && $this->Session->read("Auth.User.id") != $admin['User']['id']) {
		?>
			<!-- <td><?php //echo $html->link('Delete', array('controller'=>'users','action'=>'admin_delete','id'=>$admin['User']['id']));?></td> -->
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
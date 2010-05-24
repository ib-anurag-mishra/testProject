<?php $this->pageTitle = 'Admin'; ?>
<form>
<fieldset>
<legend>Patrons Listing <?php if($libraryID != "") { echo "for \"".$libraryname."\""; }?></legend>
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
	    <th class="left">Libary Name</th>
            <th>Edit</th>
			<th>Action</th>
	    <!-- <th>Delete</th> -->
          </tr>
          <?php
          foreach($patrons as $patron)
          {
            ?>
            <tr>
                <td class="left"><?php echo $patron['User']['first_name'];?></td>
                <td class="left"><?php echo $patron['User']['last_name'];?></td>
                <td class="left"><?php echo $patron['User']['email'];?></td>
		<td class="left"><?php echo $library->getLibraryName($patron['User']['library_id']); ?></td>
        <td><?php echo $html->link('Edit', array('controller'=>'users','action'=>'patronform','id'=>$patron['User']['id']));?></td>
		<td>
		<?php if($patron['User']['user_status']=='inactive'){
			echo $html->link('Activate', array('controller'=>'users','action'=>'patron_activate','id'=>$patron['User']['id']));
		}else{
			echo $html->link('Deactivate', array('controller'=>'users','action'=>'patron_deactivate','id'=>$patron['User']['id']));
		}
		?>
		</td>
		<?php
		if($patron['User']['type_id'] != 4 && $this->Session->read("Auth.User.id") != $patron['User']['id']) {
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
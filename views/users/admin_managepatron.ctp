<?php
/*
 File Name : admin_managepatron.ctp
 File Description : view page for manage patron
 Author : m68interactive
 */
?>
<?php $this->pageTitle = 'Admin'; ?>
<form>
<fieldset>
<legend>Patrons Listing <?php if($libraryID != "") { echo "for \"".$libraryname."\""; }?></legend>
<br class="clr">
<div id="library_search">
<a name="bottom">Patron Search&nbsp;</a>&nbsp;
<?php echo $html->link('ALL',array('controller' => 'users', 'action' => 'admin_managepatron'));?>&nbsp;
<?php echo $html->link('#',array('controller' => 'users', 'action' => 'admin_managepatron', 'special'));?>&nbsp;
<?php echo $html->link('A',array('controller' => 'users', 'action' => 'admin_managepatron', 'A'));?>&nbsp;
<?php echo $html->link('B',array('controller' => 'users', 'action' => 'admin_managepatron', 'B'));?>&nbsp;
<?php echo $html->link('C',array('controller' => 'users', 'action' => 'admin_managepatron', 'C'));?>&nbsp;
<?php echo $html->link('D',array('controller' => 'users', 'action' => 'admin_managepatron', 'D'));?>&nbsp;
<?php echo $html->link('E',array('controller' => 'users', 'action' => 'admin_managepatron', 'E'));?>&nbsp;
<?php echo $html->link('F',array('controller' => 'users', 'action' => 'admin_managepatron', 'F'));?>&nbsp;
<?php echo $html->link('G',array('controller' => 'users', 'action' => 'admin_managepatron', 'G'));?>&nbsp;
<?php echo $html->link('H',array('controller' => 'users', 'action' => 'admin_managepatron', 'H'));?>&nbsp;
<?php echo $html->link('I',array('controller' => 'users', 'action' => 'admin_managepatron', 'I'));?>&nbsp;
<?php echo $html->link('J',array('controller' => 'users', 'action' => 'admin_managepatron', 'J'));?>&nbsp;
<?php echo $html->link('K',array('controller' => 'users', 'action' => 'admin_managepatron', 'K'));?>&nbsp;
<?php echo $html->link('L',array('controller' => 'users', 'action' => 'admin_managepatron', 'L'));?>&nbsp;
<?php echo $html->link('M',array('controller' => 'users', 'action' => 'admin_managepatron', 'M'));?>&nbsp;
<?php echo $html->link('N',array('controller' => 'users', 'action' => 'admin_managepatron', 'N'));?>&nbsp;
<?php echo $html->link('O',array('controller' => 'users', 'action' => 'admin_managepatron', 'O'));?>&nbsp;
<?php echo $html->link('P',array('controller' => 'users', 'action' => 'admin_managepatron', 'P'));?>&nbsp;
<?php echo $html->link('Q',array('controller' => 'users', 'action' => 'admin_managepatron', 'Q'));?>&nbsp;
<?php echo $html->link('R',array('controller' => 'users', 'action' => 'admin_managepatron', 'R'));?>&nbsp;
<?php echo $html->link('S',array('controller' => 'users', 'action' => 'admin_managepatron', 'S'));?>&nbsp;
<?php echo $html->link('T',array('controller' => 'users', 'action' => 'admin_managepatron', 'T'));?>&nbsp;
<?php echo $html->link('U',array('controller' => 'users', 'action' => 'admin_managepatron', 'U'));?>&nbsp;
<?php echo $html->link('V',array('controller' => 'users', 'action' => 'admin_managepatron', 'V'));?>&nbsp;
<?php echo $html->link('W',array('controller' => 'users', 'action' => 'admin_managepatron', 'W'));?>&nbsp;
<?php echo $html->link('X',array('controller' => 'users', 'action' => 'admin_managepatron', 'X'));?>&nbsp;
<?php echo $html->link('Y',array('controller' => 'users', 'action' => 'admin_managepatron', 'Y'));?>&nbsp;
<?php echo $html->link('Z',array('controller' => 'users', 'action' => 'admin_managepatron', 'Z'));?>&nbsp;
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
            <th class="left"><?php echo $paginator->sort('Last Name', 'last_name')."&nbsp;".$paginator->sort('`', 'last_name', array('id' => 'sort_arrow'));?></th>
            <th class="left"><?php echo $paginator->sort('Email', 'email')."&nbsp;".$paginator->sort('`', 'email', array('id' => 'sort_arrow')); ?></th>
			<th class="left">Libary Name</th>
			<th class="left"><?php echo $paginator->sort('Created', 'created')."&nbsp;".$paginator->sort('`', 'created', array('id' => 'sort_arrow')); ?></th>
            <th>Edit</th>
			<th>Action</th>
          </tr>
          <?php
          foreach($patrons as $patron)
          {
            ?>
            <tr>
                <td class="left"><?php echo $patron['User']['first_name'];?></td>
                <td class="left"><?php echo $patron['User']['last_name'];?></td>
                <td class="left"><?php echo $patron['User']['email'];?></td>				
				<td class="left"><?php  echo $library->getLibraryName($patron['User']['library_id']); ?></td>
				<td class="left"><?php echo date("Y-m-d",strtotime($patron['User']['created']));?></td>		
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
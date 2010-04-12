<?php $this->pageTitle = 'Libraries'; ?>
<form>
<fieldset>
<legend>Library Listing</legend>
  <table id="list">
          <tr>            
            <th class="left" style="border-right:1px solid #E0E0E0">Library Name</th>
            <th style="border-right:1px solid #E0E0E0">Edit</th>
	    <th>Action</th>
          </tr>
          <?php
          foreach($libraries as $library)
          {
            ?>
            <tr>
                <td class="left"><?php echo $library['Library']['library_name'];?></td>
                <td><?php echo $html->link('Edit', array('controller'=>'libraries','action'=>'libraryform','id'=>$library['Library']['id']));?></td>
		
		<?php
		if($library['Library']['library_status'] == 'active') {
		?>
			<td><?php echo $html->link('Deactivate', array('controller'=>'libraries','action'=>'deactivate','id'=>$library['Library']['id']));?></td>
		<?php
		}
		elseif($library['Library']['library_status'] == 'inactive') {
		?>
			<td><?php echo $html->link('Activate', array('controller'=>'libraries','action'=>'activate','id'=>$library['Library']['id']));?></td>
		<?php
		}
		?>
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
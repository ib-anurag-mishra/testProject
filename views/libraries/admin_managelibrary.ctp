<?php
/*
 File Name : admin_managelibrary.ctp
 File Description : View page for edit library.
 Author : m68interactive
 */
?>
<?php $this->pageTitle = 'Libraries'; ?>
<form>
<fieldset>
<legend>Library Listing</legend>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<p><br>
<?php echo $this->Form->input('library_name', array('label' => 'Search Library', 'value' => $searchKeyword, 'div' => false, 'class' => 'form_fields ac_input','width'=>'50','style'=>'width:304px;') ); ?>
</p>
<p>
    <br>
    <?php
    for($j = 65;$j < 91;$j++){
       if (in_array(chr($j), $libraryFilter)) {
           echo $html->link(chr($j), array('controller'=>'libraries', 'action'=>'managelibrary', 'alpha'=>chr($j)));
           echo " | ";           
       }        
    }
    ?>
</p>
  <table id="list">
          <tr>            
            <th class="left" style="border-right:1px solid #E0E0E0">Library Name</th>
	<?php if($userTypeId != 7){ ?>
            <th style="border-right:1px solid #E0E0E0">Edit</th>
	    <th>Action</th>
            <th>Cache</th>
	<?php } ?>
          </tr>
          <?php
          foreach($libraries as $library)
          {
            ?>
            <tr>
                <td class="left"><?php echo $library['Library']['library_name'];?></td>
     		<?php if($userTypeId != 7){ ?>
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
                <td><?php echo $html->link('Clear Cache', array('controller'=>'clear','action'=>'library',$library['Library']['id']));?></td>	
		<?php } ?>
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

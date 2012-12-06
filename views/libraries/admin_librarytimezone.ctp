<?php
/*
 File Name : admin_managelibrarytimezone.ctp
 File Description : View page for edit library.
 Author : m68interactive
 */
?>
<?php $this->pageTitle = 'Libraries'; ?>
<form>
<fieldset>
<legend>Library Timezone Listing</legend>
<br/>
<p style="font-size:10px; ">
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>

<p><?php 
echo '<br/>';
 echo $session->flash();
?></p>
<div style="float:right;padding-right:25px;"><?php echo $html->link('Add New Timezone', array('controller'=>'libraries','action'=>'librarytimezoneform','add'));?></div>
  <table id="list">
          <tr>            
            <th class="left" style="border-right:1px solid #E0E0E0">Library Name</th>
            <th class="left" style="border-right:1px solid #E0E0E0">Library Timezone</th>
            <th style="border-right:1px solid #E0E0E0">Edit</th>
	    <th>Delete</th>            
          </tr>
          <?php
          if(count($librariesTimezones)) {
            foreach($librariesTimezones as $librariesTimezone)
            {
                ?>
                <tr>
                    <td class="left"><?php echo $librariesTimezone['Library']['library_name'];?></td>
                    <td class="left"><?php echo $librariesTimezone['LibrariesTimezone']['libraries_timezone'];?></td>
                    <td><?php echo $html->link('Edit', array('controller'=>'libraries','action'=>'librarytimezoneform','edit',$librariesTimezone['LibrariesTimezone']['id']));?></td>		
                    <td><?php echo $html->link('Delete', array('controller'=>'libraries','action'=>'removelibrarytimezone',$librariesTimezone['Library']['id']),array('confirm' => 'Are you sure you want to remove this record ?'));?></td>
                </tr>            
                <?php
            }
           }else{
            ?>
            <tr> <td class="center">No records found.</td></tr>    
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

</form>
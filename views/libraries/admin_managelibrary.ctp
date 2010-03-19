<?php $this->pageTitle = 'Libraries'; ?>
<form>
<fieldset>
<legend>Library Listing</legend>
  <table id="list">
          <tr>            
            <th class="left" style="border-right:1px solid #E0E0E0">Library Name</th>
            <th style="border-right:1px solid #E0E0E0">Edit</th>
	    <th>Delete</th>
          </tr>
          <?php
          foreach($libraries as $library)
          {
            ?>
            <tr>
                <td class="left"><?php echo $library['Library']['library_name'];?></td>
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
<?php
/*
 File Name : admin_consortium.ctp
 File Description : View page for consortium
 Author : m68interactive
 */
?>
<?php $this->pageTitle = 'Libraries'; ?>
<form>
<fieldset>
<legend>Consortium Listing</legend>
<p>
</p>
  <table id="list">
          <tr>            
            <th class="left" style="border-right:1px solid #E0E0E0">Consortium Name</th>
            <th style="border-right:1px solid #E0E0E0">Edit</th>
          </tr>
          <?php
          foreach($consortium as $consortium)
          {
            ?>
            <tr>
                <td class="left"><?php echo $consortium['Consortium']['consortium_name'];?></td>
                <td><?php echo $html->link('Edit', array('controller'=>'libraries','action'=>'consortiumform','id'=>$consortium['Consortium']['id']));?></td>	
            </tr>            
            <?php
          }
          ?>
        </table>
	<br class="clr" />
</fieldset>
<?php 
 echo $session->flash();
?>
</form>
<?php
/*
 File Name : admin_sonyreport.ctp
 File Description : view page for generating sony report
 Author : m68interactive
 */
?>
<?php
    $this->pageTitle = 'Reports';
?>
<form>
    <fieldset>
        <legend>Sony Sales Reports</legend>
        <table id="list">
        <?php
        if(empty($sonyReports)) {
        ?>
            <tr>            
                <td align="center">There are no reports to show at this moment</td>
            </tr>
        <?php
        }
        else {
        ?>
          <tr>            
            <th class="left" style="border-right:1px solid #E0E0E0">Report Name</th>
            <th style="border-right:1px solid #E0E0E0">Is Sent to Sony?</th>
	    <th>Created Date</th>
            <th>Latest Activity Date</th>
          </tr>
          <?php
            foreach($sonyReports as $sonyReport)
            {
              ?>
              <tr>
                  <td class="left">
                      <span style="margin:5px;float:left;">
                      <?php
                          echo $html->link(
                                      $html->image('download_icon.jpg', array("alt" => "Download Report", "title" => "Download Report", 'style' => 'cursor:pointer;margin:')),
                                      array('controller'=>'reports', 'action'=>'sonyreports', 'id'=>base64_encode($sonyReport['SonyReport']['id'])),
                                      array('escape'=>false)
                          );
                      ?>
                      </span>
                      <span style="float:left;margin-top:10px;">
                      <?php
                          echo $sonyReport['SonyReport']['report_name'];
                      ?>
                      </span>
                  </td>
                  <?php
                  if($sonyReport['SonyReport']['is_uploaded'] == 'yes') {
                  ?>
                          <td>Yes</td>
                  <?php
                  }
                  else {
                  ?>
                          <td>No</td>
                  <?php
                  }
                  ?>
                  <td><?php echo $sonyReport['SonyReport']['created']; ?></td>
                  <td><?php echo $sonyReport['SonyReport']['modified']; ?></td>
              </tr>            
              <?php
            }
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
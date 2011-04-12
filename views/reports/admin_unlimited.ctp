<?php
    $this->pageTitle = 'Reports'; 
    echo $this->Form->create('Report', array( 'action' => $formAction ));
    if(empty($date))
    {
        $date = "";
    }
?>
<fieldset>
<legend>Generate Unlimited Library Downloads Report<?php if($libraryID != "") { echo "for \"".$libraryname."\""; }?></legend>
    <div class="formFieldsContainer">
        <div class="formFieldsbox">
            <div id="form_step" class="form_steps">
                <h1>Report Settings</h1>
                <table cellspacing="10" cellpadding="0" border="0" width="100%">
                    <tr><td id="formError" class="formError" colspan="4"></td></tr>
                    <tr><td colspan="6">&nbsp;</td></tr>
                    <tr id="initial_date_range">
                        <td align="center" colspan="6">
                            <?php
                                echo $this->Form->label('Select Date of a Month');
                                echo $this->Form->input('date',array('label' => false ,'value' => $date, 'div' => false, 'class' => 'form_fields', 'readonly' => 'readonly'));
                            ?>
                        </td>
                    </tr>
                    <tr><td colspan="6">&nbsp;</td></tr>
                    <tr>
                        <td colspan="6" align="center"><?php echo $this->Form->submit('Generate Report', array('id' => 'generateReportSubmit'));?></td>
                    </tr>
                    <tr><td colspan="6">&nbsp;</td></tr>
                    <?php
                    if(!empty($downloadResult)) {
                    ?>
                    <tr>
                        <td colspan="3" align="center">
                            <?php
                                echo $html->image('excel_icon.gif', array("alt" => "Download As CSV", "title" => "Download As CSV", 'style' => 'cursor:pointer;', 'id' => 'downloadCSVOne'));
                            ?>
                        </td>
                        <td colspan="3" align="center">
                            <?php
                                echo $html->image('pdf_icon.gif', array("alt" => "Download As PDF", "title" => "Download As PDF", 'style' => 'cursor:pointer;', 'id' => 'downloadPDFOne'));
                            ?>
                        </td>
                    </tr>
                    <tr><td colspan="6">&nbsp;</td></tr>
                    <tr><th colspan="6" align="center">Monthly Price list for Unlimited Libraries</th></tr>
                    <tr>
                        <td colspan="6" align="center">
                            <table cellspacing="0" cellpadding="0" border="1" class="reportsTable" align="center">
                                <tr>
									<th>&nbsp;</th>
                                    <th>Library Name</th>
                                    <th><?php echo $month;?> Download</th>
									<th>Annual Price</th>
									<th>Monthly Price</th>
									<th>Price per Download</th>
									<th>Mechanical Royalty</th>
                                </tr>
                                <?php
								$i = 1;
								$downloads = 0;
								$libPrice = 0;
								$monPrice = 0;
								$dwldPrice = 0;
								$royalty = 0;
                                foreach($downloadResult as $k => $v) {
                                ?>
                                    <tr>
										<td><?php echo $i; ?></td>
                                        <td><?php echo $v['Download']['library_name']; ?></td>
										<td>
											<?php 
												echo $v['0']['totalDownloads']; 
												$downloads = $downloads + $v['0']['totalDownloads'];
											?>
										</td>
                                        <td>
											<?php 
												echo "$".number_format($v['Download']['library_price'], 2);
												$libPrice = $libPrice + $v['Download']['library_price'];
											?>
										</td>
										<td>
											<?php
												$monPrice = $monPrice + $v['Download']['monthly_price'];
												echo "$".number_format($v['Download']['monthly_price'], 2); 
											?>
										</td>
										<td>
											<?php 
												$dwldPrice = $dwldPrice + $v['Download']['download_price'];
												echo "$".number_format($v['Download']['download_price'], 2); 
											?>
										</td>
										<td>
											<?php
												$royalty = $royalty + $v['Download']['mechanical_royalty'];
												echo "$".number_format($v['Download']['mechanical_royalty'], 2); 
											?>
										</td>
                                    </tr>
                                <?php
									$i++;
                                }
                                ?>
								<tr>
									<td>&nbsp;</td>
									<td>Total</td>
									<td><?php echo $downloads; ?></td>
									<td><?php echo "$".number_format($libPrice, 2); ?></td>
									<td><?php echo "$".number_format($monPrice, 2); ?></td>
									<td><?php echo "$".number_format(($monPrice/$downloads), 2); ?></td>
									<td><?php echo "$".number_format($royalty, 2); ?></td>
								</tr>
                            </table>
                        </td>
                    </tr>
                    <?php
                    }
                    elseif(empty($downloads) && empty($errors) && isset($this->data)) {
                    ?>
                    <tr>
                        <td colspan="6" align="center"><label>There are not downloads found for the selected criteria.</label></td>
                    </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</fieldset>
<?php
 echo $this->Form->end();
 echo $session->flash();
?>
<link type="text/css" rel="stylesheet" href="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/css&amp;f=flick/jquery-ui-1.8.custom.css" />
<script type="text/javascript" src="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/js&amp;f=datepicker/jquery.ui.core.js,datepicker/jquery.ui.widget.js,datepicker/jquery.ui.datepicker.js"></script>
<script type="text/javascript">
    $(function() {
        $("#ReportDate").datepicker({showWeek: true, firstDay: 1, maxDate: '+0D', numberOfMonths: 3});		
    });
    <?php
        if(!empty($downloadResult)) {
    ?>
            $("#generateReportSubmit").click(function() {
                $("#ReportAdminUnlimitedForm").attr('action','/admin/reports/unlimited');
            });
            
            $("#downloadCSVOne").click(function() {
                $("#ReportAdminUnlimitedForm").attr('action','/admin/reports/unlimitedcsv');
                $("#ReportAdminUnlimitedForm").submit();
            });
            
            $("#downloadPDFOne").click(function() {
                $("#ReportAdminUnlimitedForm").attr('action','/admin/reports/unlimitedpdf');
                $("#ReportAdminUnlimitedForm").submit();
            });
    <?php
        }
    ?>
</script>
<?php
/*
 File Name : admin_consortiumform.ctp
 File Description : View page for consortium form
 Author : m68interactive
 */
?>
<?php
    $this->pageTitle = 'Reports'; 
    echo $this->Form->create('Report', array( 'action' => $formAction ));
    if(empty($getData))
    {
        $getData['Report']['library_id'] = "all";
        $getData['Report']['downloadType'] = "";
        $getData['Report']['reports_daterange'] = "day";
        $getData['Report']['date'] = "";
        $getData['Report']['date_from'] = "";
        $getData['Report']['date_to'] = "";
		$getData['Report']['library_apikey'] = "";
    }
?>
<fieldset>
<legend>Generate Consortium Report <?php if($libraryID != "") { echo "for \"".$this->getAdminTextEncode($libraryname)."\""; }?></legend>
    <div class="formFieldsContainer">
        <div class="formFieldsbox">
            <div id="form_step" class="form_steps">
                <h1>Consortium Report Settings</h1>
                <table cellspacing="10" cellpadding="0" border="0" width="100%">
                    <tr><td id="formError" class="formError" colspan="4"></td></tr>
                    <tr>
                        <?php
                            echo $this->Form->hidden('downloadType', array('value' => ""));
                            if($libraryID == "") {
                        ?>
                                <td align="right"><?php echo $this->Form->label('Select Consortium');?></td>
                                <td align="left">
                        <?php    
                            echo $this->Form->input('library_apikey', array('options' => $consortium, 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['Report']['library_apikey']));
                        ?>
                                </td>
                                <td align="right"><?php echo $this->Form->label('Range');?></td>
                                <td align="left">
                                    <?php
                                        echo $this->Form->input('reports_daterange', array('options' => array(
                                                                        'day' => 'Day',
                                                                        'week' => 'Week',
                                                                        'month' => 'Month',
                                                                        'year' => 'Year',
                                                                        'manual' => 'Manual'
                                                                        ), 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['Report']['reports_daterange'])
                                                                );
                                    ?>
                                </td>
                        <?php
                            }
                            else {
                        ?>
                                <td align="center" colspan="4">
                                    <?php
                                        echo $this->Form->label('Range');
                                        echo $this->Form->hidden('library_id', array('value' => $libraryID));
                                        echo $this->Form->input('reports_daterange', array('options' => array(
                                                                        'day' => 'Day',
                                                                        'week' => 'Week',
                                                                        'month' => 'Month',
                                                                        'year' => 'Year',
                                                                        'manual' => 'Manual'
                                                                        ), 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['Report']['reports_daterange'])
                                                                );
                                    ?>
                                </td>
                        <?php
                            }
                        ?>
                    </tr>
                    <tr><td colspan="4">&nbsp;</td></tr>
                    <tr id="initial_date_range" <?php if($getData['Report']['reports_daterange'] == "manual") {?>style="display:none;"<?php } ?>>
                        <td align="center" colspan="4">
                            <?php
                                echo $this->Form->label('Select Date');
                                echo $this->Form->input('date',array('label' => false ,'value' => $getData['Report']['date'], 'div' => false, 'class' => 'form_fields', 'readonly' => 'readonly'));
                            ?>
                        </td>
                    </tr>
                    <tr id="date_range" <?php if($getData['Report']['reports_daterange'] != "manual") {?>style="display:none;"<?php } ?>>
                        <td align="right" colspan="2">
                            <?php
                                echo $this->Form->label('From');
                                echo $this->Form->input('date_from',array('label' => false ,'value' => $getData['Report']['date_from'], 'div' => false, 'class' => 'form_fields', 'readonly' => 'readonly'));
                            ?>
                        </td>
                        <td align="left" colspan="2">
                            <?php
                                echo $this->Form->label('To');
                                echo $this->Form->input('date_to',array('label' => false ,'value' => $getData['Report']['date_to'], 'div' => false, 'class' => 'form_fields', 'readonly' => 'readonly'));
                            ?>
                        </td>
                    </tr>
                    <tr><td colspan="4">&nbsp;</td></tr>
                    <tr>
                        <td colspan="4" align="center"><?php echo $this->Form->submit('Generate Report', array('id' => 'generateWishlistSubmit', 'div' => false, 'class' => 'form_fields')); ?></td>
                    </tr>
                    <tr><td colspan="4">&nbsp;</td></tr>
                    <?php
                    if(!empty($downloads)) {
                    ?>
                    <tr>
                        <td colspan="3" align="center">
                            <?php
                                echo $html->image('excel_icon.gif', array("alt" => "Download As CSV", "title" => "Download As CSV", 'style' => 'cursor:pointer;', 'id' => 'downloadCVS'));
                            ?>
                        </td>
                        <td colspan="3" align="center">
                            <?php
                                echo $html->image('pdf_icon.gif', array("alt" => "Download As PDF", "title" => "Download As PDF", 'style' => 'cursor:pointer;', 'id' => 'downloadPDF'));
                            ?>
                        </td>
                    </tr>
                    <tr><td colspan="6">&nbsp;</td></tr>
                    <tr><th colspan="6" align="center">Library Remaining Downloads</th></tr>
	                <tr>
                        <td colspan="6" align="center">
                            <table cellspacing="0" cellpadding="0" border="1" class="reportsTable" align="center">
                                <tr>
									<th>&nbsp;</th>
                                    <th>Library Name</th>
                                    <th>Number of Remaining Downloads</th>
                                </tr>
                                <?php
								$i = 1;
                                foreach($libraries_download as $LibraryName => $libraryid) {
                                ?>
                                    <tr>
										<td><?php echo $i; ?></td>
                                        <td><?php echo $this->getAdminTextEncode($libraryid['Library']['library_name']); ?></td>
											<?php
											if($libraryid['Library']['library_unlimited'] == 1){
												$text = "Unlimited";
											} else {
												$text = $libraryid['Library']['library_available_downloads'];
											}
											?>
                                        <td align="center"><?php echo $text; ?></td>
                                    </tr>
                                <?php
								$i++;
                                }
                                ?>
                            </table>
                        </td>
                    </tr>				
					<tr><td colspan="6">&nbsp;</td></tr>
                    <tr><th colspan="6" align="center">Library Downloads Report</th></tr>
                    <tr>
                        <td colspan="6" align="center">
                            <table cellspacing="0" cellpadding="0" border="1" class="reportsTable" align="center">
                                <tr>
				    <th>&nbsp;</th>
                                    <th>Library Name</th>
                                    <th>ID</th>
                                    <th>Artists Name</th>
                                    <th>Track Title</th>
                                    <th>Download</th>
                                </tr>
                                <?php
								$i = 1;
                                foreach($downloads as $key => $download) {	
                                ?>
                                    <tr>
					<td><?php echo $i; ?></td>
                                        <td><?php echo $this->getAdminTextEncode($library->getLibraryName($download['Download']['library_id'])); ?></td>
                                        <td><?php echo $download['Currentpatrons']['id']; ?></td>
                                        <td><?php echo $this->getAdminTextEncode($download['Download']['artist']); ?></td>
                                        <td><?php echo $this->getAdminTextEncode($download['Download']['track_title']); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($download['Download']['created'])); ?></td>
                                    </tr>
                                <?php
				    $i++;
                                }
                                ?>
                            </table>
                        </td>
                    </tr>
                    <tr><td colspan="6">&nbsp;</td></tr>
                    <tr><th colspan="6" align="center">Patron Downloads Report</th></tr>
                    <tr>
                        <td colspan="6" align="center">
                            <table cellspacing="0" cellpadding="0" border="1" class="reportsTable" align="center">
                                <tr>
				    <th>&nbsp;</th>
                                    <th>ID</th>
                                    <th>Library Name</th>
                                    <th>Total Number of Tracks Downloaded</th>
                                </tr>
                                <?php
				$i = 1;
                                foreach($patronDownloads as $key => $patronDownload) {
                                ?>
                                    <tr>
					<td><?php echo $i; ?></td>
                                        <td><?php 
                                        echo $patronDownload['Currentpatrons']['id']; ?>
                                        </td>
                                        <td><?php echo $this->getAdminTextEncode($library->getLibraryName($patronDownload['Download']['library_id'])); ?></td>
                                        <td align="center"><?php echo $patronDownload[0]['totalDownloads']; ?></td>
                                    </tr>
                                <?php
				    $i++;
                                }
                                ?>
                            </table>
                        </td>
                    </tr>
                    <tr><td colspan="6">&nbsp;</td></tr>
                    <tr><th colspan="6" align="center">Genres Downloads Report</th></tr>
                    <tr>
                        <td colspan="6" align="center">
                            <table cellspacing="0" cellpadding="0" border="1" class="reportsTable" align="center">
                                <tr>
				    <th>&nbsp;</th>
                                    <th>Genre Name</th>
                                    <th>Total Number of Tracks Downloaded</th>
                                </tr>
                                <?php
				$i = 1;
                                foreach($genreDownloads as $key => $genreDownload) {
                                ?>
                                    <tr>
					<td><?php echo $i; ?></td>
                                        <td><?php echo $genreDownload['Genre']['Genre']; ?></td>
                                        <td align="center"><?php echo $genreDownload[0]['totalProds']; ?></td>
                                    </tr>
                                <?php
				    $i++;
                                }
                                ?>
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
        //$("#ReportDate").datepicker({showWeek: true, firstDay: 1, numberOfMonths: 3});
        $("#ReportDate").datepicker({showWeek: true, firstDay: 1, maxDate: '+0D', numberOfMonths: 3});
        var dates = $('#ReportDateFrom, #ReportDateTo').datepicker({
                defaultDate: "-1w",
                changeMonth: true,
                numberOfMonths: 3,
                onSelect: function(selectedDate) {
                        var option = this.id == "ReportDateFrom" ? "minDate" : "maxDate";
                        var instance = $(this).data("datepicker");
                        var date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
                        dates.not(this).datepicker("option", option, date);
                }
        });
        $("#ReportReportsDaterange").change(function() {
            if($(this).val() == "manual") {
                $("#date_range").show();
                $("#initial_date_range").hide();
            }
            else {
                $("#initial_date_range").show();
                $("#date_range").hide();
            }
        });
    });
    <?php
        if(!empty($downloads)) {
    ?>
            $("#generateWishlistSubmit").click(function() {
                $("#ReportDownloadType").val("");
            });
            
            $("#downloadCVS").click(function() {
                $("#ReportDownloadType").val("csv");
                $("#ReportAdminConsortiumForm").submit();
            });
            
            $("#downloadPDF").click(function() {
                $("#ReportDownloadType").val("pdf");
                $("#ReportAdminConsortiumForm").submit();
            });
    <?php
        }
    ?>
</script>
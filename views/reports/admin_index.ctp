<?php
    $this->pageTitle = 'Reports';
    echo $this->Form->create('Report', array( 'action' => $formAction ));
    if(empty($getData))
    {
        $getData['Report']['library_id'] = "all";
        $getData['Report']['reports_daterange'] = "day";
        $getData['Report']['date'] = "";
        $getData['Report']['date_from'] = "";
        $getData['Report']['date_to'] = "";
		$getData['Report']['Territory'] = "";
    }
?>
<fieldset>
<legend>Generate Library Downloads Report <?php if($libraryID != "") { echo "for \"".$this->getTextEncode($libraryname)."\""; }?></legend>
    <div class="formFieldsContainer">
        <div class="formFieldsbox">
            <div id="form_step" class="form_steps">
                <h1>Report Settings</h1>
                <table cellspacing="10" cellpadding="0" border="0" width="100%">
                    <tr><td id="formError" class="formError" colspan="4"></td></tr>
                    <tr>
                        <?php
                            if($libraryID == "") {
                        ?>
                            <td align="right"><?php echo $this->Form->label('Select Library');?></td>
                            <td align="left">
                                    <div id="allLibrary">
                                        <?php
                                        if ($this->Session->read("Auth.User.consortium") == '')
                                        {
                                            $libraries['all'] = "All Libraries";
                                        }
                                        echo $this->Form->input('library_id', array('options' => $libraries, 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['Report']['library_id']));
                                        ?>
                                    </div>
                                </td>
                            <td align="right"><?php echo $this->Form->label('Range');?></td>
                            <td align="left">
                                <?php
                                    echo $this->Form->input('reports_daterange', array('options' => array(
                                                                    'day' => 'Day',
                                                                    'week' => 'Week',
                                                                    'month' => 'Month',
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
                                                                    'manual' => 'Manual'
                                                                    ), 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['Report']['reports_daterange'])
                                                            );
                                ?>
                            </td>
                        <?php
                            }
                        ?>

                    </tr>
                    <tr><td colspan="6">&nbsp;</td></tr>
                    <tr id="initial_date_range" <?php if($getData['Report']['reports_daterange'] == "manual") {?>style="display:none;"<?php } ?>>
                        <td align="center" colspan="6">
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
                    <tr><td colspan="6">&nbsp;</td></tr>
                    <tr>
                        <td colspan="6" align="center"><?php echo $this->Form->submit('Generate Report', array('id' => 'generateReportSubmit'));?></td>
                    </tr>
                    <tr><td colspan="6">&nbsp;</td></tr>
                    <?php
                    if(!empty($downloads) || !empty($videoDownloads)) {
                    ?>
                    <tr>
                        <td colspan="3" align="center">
                            <?php
                                echo $html->image('excel_icon.gif', array("alt" => "Download As CSV", "title" => "Download As CSV", 'style' => 'cursor:pointer;', 'id' => 'downloadCVSOne'));
                            ?>
                        </td>
                        <td colspan="3" align="center">
                            <?php
                                echo $html->image('pdf_icon.gif', array("alt" => "Download As PDF", "title" => "Download As PDF", 'style' => 'cursor:pointer;', 'id' => 'downloadPDFOne'));
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
                                        <td><?php echo $this->getTextEncode($libraryid['Library']['library_name']); ?></td>
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
              
              <?php 
                
                if(empty($arr_all_library_downloads)) { ?>
                 
                  <tr>
                    <th colspan="6" align="center">Total Downloads during Reporting Period</th>
                  </tr>
                  <tr>
                    <td colspan="6" align="center">
                      <table cellspacing="0" cellpadding="0" border="1" class="reportsTable" align="center">
                        <tr>
                          <th>Total Downloads</th>
                        </tr>
                        <tr>
                          <td align="center"><?php echo count($downloads)+(count($videoDownloads)*2); ?></td>
                        </tr>
                      </table>
                    </td>
                  </tr>
              
              <?php  } else {  ?> 
                <tr>
        
                <td colspan="6" align="center">
                  <div style="padding-bottom: 5px;"> <b> Total Downloads during Reporting Period </b> </div>
               
                <table cellpadding="0" cellspacing="0" border="1" class="reportsTable"> 
                  <tr>
                    <th align="center"> &nbsp; </th>
                    <th align="center"> Library name </th>
                    <th align="center"> Total Downloads </th>
                  </tr>
                  
                  <?php 
                    $index = 1;
                    foreach($arr_all_library_downloads AS $key => $val) {
                  ?>
                  
                  <tr>
                   <td> <?php echo $index; ?> </td>
                   <td> <?php echo $key; ?> </td>
                   
                   <td align="center"> <?php echo $val+($arr_all_video_library_downloads[$key]*2); ?> </td>
                  </tr>
                  
                  <?php $index++; } ?>
                </table> </td></tr>    
               <?php  } ?>         
                    
					<tr><td colspan="6">&nbsp;</td></tr>
                    
            <?php
              if(empty($arr_all_patron_downloads)) {
            ?>                              
              <tr><th colspan="6" align="center">Total Patrons</th></tr>
                <tr>
                  <td colspan="6" align="center">
                    <table cellspacing="0" cellpadding="0" border="1" class="reportsTable" align="center">
                      <tr><th>Total Number of Patrons who have downloaded during Reporting Period</th></tr>
                      <tr><td align="center"><?php echo count($patronBothDownloads); ?></td></tr>
                    </table>
                  </td>
                </tr>
            <?php  } else {  ?>         
              <tr>
                <td colspan="6" align="center">
                  <div style="padding-bottom: 5px;"> <b> Total Number of Patrons who have downloaded during Reporting Period </b> </div>
               
                <table cellpadding="0" cellspacing="0" border="1" class="reportsTable"> 
                  <tr>
                    <th align="center"> &nbsp; </th>
                    <th align="center"> Library name </th>
                    <th align="center"> Total Patrons </th>
                  </tr>
                  
                  <?php 
                    $index = 1;
                    foreach($arr_all_patron_downloads AS $key => $val) {
                  ?>
                  
                  <tr>
                   <td> <?php echo $index; ?> </td>
                   <td> <?php echo $key; ?> </td>
                   <td align="center"> <?php echo $val; ?> </td>
                  </tr>
                  
                  <?php $index++; } ?>
                </table> </td></tr>
                
            <?php }?>            
                    
                    
					<tr><td colspan="6">&nbsp;</td></tr>
                    <tr><th colspan="6" align="center">Library Downloads Report</th></tr>
                    <tr>
                        <td colspan="6" align="center">
                            <table cellspacing="0" cellpadding="0" border="1" class="reportsTable" align="center">
                                <tr>
				    <th>&nbsp;</th>
                                    <th>Library Name</th>
                                    <th>Patron ID</th>
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
                                        <td><?php echo $this->getTextEncode($library->getLibraryName($download['Download']['library_id'])); ?></td>
                                        <td><?php
											if($download['Download']['email']!=''){
												echo $download['Download']['email'];
											}else{
												echo $download['Download']['patron_id'];
											}?>
										</td>
                                        <td><?php echo $this->getTextEncode($download['Download']['artist']); ?></td>
                                        <td><?php echo $this->getTextEncode($download['Download']['track_title']); ?></td>
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
                    <tr><th colspan="6" align="center">Library Video Downloads Report</th></tr>
                    <tr>
                        <td colspan="6" align="center">
                            <table cellspacing="0" cellpadding="0" border="1" class="reportsTable" align="center">
                                <tr>
				    <th>&nbsp;</th>
                                    <th>Library Name</th>
                                    <th>Patron ID</th>
                                    <th>Artists Name</th>
                                    <th>Video Title</th>
                                    <th>Download</th>
                                </tr>
                                <?php
								$i = 1;
                                foreach($videoDownloads as $key => $download) {
                                ?>
                                    <tr>
										<td><?php echo $i; ?></td>
                                        <td><?php echo $this->getTextEncode($library->getLibraryName($download['Videodownload']['library_id'])); ?></td>
                                        <td><?php
											if($download['Videodownload']['email']!=''){
												echo $download['Videodownload']['email'];
											}else{
												echo $download['Videodownload']['patron_id'];
											}?>
										</td>
                                        <td><?php echo $this->getTextEncode($download['Videodownload']['artist']); ?></td>
                                        <td><?php echo $this->getTextEncode($download['Videodownload']['track_title']); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($download['Videodownload']['created'])); ?></td>
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
                                    <th>Patron ID</th>
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
										if(isset($patronDownload['Downloadpatron']['email']) && $patronDownload['Downloadpatron']['email']!=''){
											echo $patronDownload['Downloadpatron']['email'];
										}else{
											echo $patronDownload['Downloadpatron']['patron_id'];
										}?>
										</td>
                                        <td><?php echo $this->getTextEncode($library->getLibraryName($patronDownload['Downloadpatron']['library_id'])); ?></td>
                                        <td align="center"><?php echo (($getData['Report']['reports_daterange'] == 'day')?$patronDownload['Downloadpatron']['total']:$patronDownload[0]['total']); ?></td>
                                    </tr>
                                <?php
				    $i++;
                                }
                                ?>
                            </table>
                        </td>
                    </tr>
                    <tr><td colspan="6">&nbsp;</td></tr>
                    <tr><th colspan="6" align="center">Patron Videos Downloads Report</th></tr>
                    <tr>
                        <td colspan="6" align="center">
                            <table cellspacing="0" cellpadding="0" border="1" class="reportsTable" align="center">
                                <tr>
				    <th>&nbsp;</th>
                                    <th>Patron ID</th>
                                    <th>Library Name</th>
                                    <th>Total Number of Videos Downloaded</th>
                                </tr>
                                <?php
				$i = 1;
                                foreach($patronVideoDownloads as $key => $patronDownload) {
                                ?>
                                    <tr>
					<td><?php echo $i; ?></td>
										<td><?php
										if(isset($patronDownload['DownloadVideoPatron']['email']) && $patronDownload['DownloadVideoPatron']['email']!=''){
											echo $patronDownload['DownloadVideoPatron']['email'];
										}else{
											echo $patronDownload['DownloadVideoPatron']['patron_id'];
										}?>
										</td>
                                        <td><?php echo $this->getTextEncode($library->getLibraryName($patronDownload['DownloadVideoPatron']['library_id'])); ?></td>
                                        <td align="center"><?php echo (($getData['Report']['reports_daterange'] == 'day')?$patronDownload['DownloadVideoPatron']['total']:$patronDownload[0]['total']); ?></td>
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
                                        <td><?php echo $this->getTextEncode($genreDownload['Downloadgenre']['genre_name']); ?></td>
                                        <td align="center"><?php echo (($getData['Report']['reports_daterange'] == 'day')?$genreDownload['Downloadgenre']['total']:$genreDownload[0]['total']); ?></td>
                                    </tr>
                                <?php
				    $i++;
                                }
                                ?>
                            </table>
                        </td>
                    </tr>
                    <tr><td colspan="6">&nbsp;</td></tr>
                    <tr><th colspan="6" align="center">Genres Video Downloads Report</th></tr>
                    <tr>
                        <td colspan="6" align="center">
                            <table cellspacing="0" cellpadding="0" border="1" class="reportsTable" align="center">
                                <tr>
				    <th>&nbsp;</th>
                                    <th>Genre Name</th>
                                    <th>Total Number of Videos Downloaded</th>
                                </tr>
                                <?php
                                $i = 1;
                                foreach($genreVideoDownloads as $key => $genreDownload) {
                                ?>
                                    <tr>
					<td><?php echo $i; ?></td>
                                        <td><?php echo $this->getTextEncode($genreDownload['DownloadVideoGenre']['genre_name']); ?></td>
                                        <td align="center"><?php echo (($getData['Report']['reports_daterange'] == 'day')?$genreDownload['DownloadVideoGenre']['total']:$genreDownload[0]['total']); ?></td>
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
                    elseif(empty($downloads) && empty($videoDownloads) && empty($errors) && isset($this->data)) {
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
		<?php 
		if(empty($library_id) || ($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") != ''))
		{
		?>
			//report_load_page();
		<?php
		}
		?>
		$("#ReportDate").datepicker({showWeek: true, firstDay: 1, maxDate: '+0D', numberOfMonths: 3});
        var dates = $('#ReportDateFrom, #ReportDateTo').datepicker({
                defaultDate: "-1w",
                maxDate: '+0D',
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
        $("#ReportTerritory").change(function() {
			var data = "Territory="+$("#ReportTerritory").val();
			jQuery.ajax({
				type: "post",  // Request method: post, get
				url: webroot+"admin/reports/getLibraryIds", // URL to request
				data: data,  // post data
				success: function(response) {
						$('#allLibrary').text('');
						$('#allLibrary').html(response);
				},
				error:function (XMLHttpRequest, textStatus, errorThrown) {}
			});
			return false;
		});
    });
    <?php
        if(!empty($downloads) || !empty($videoDownloads)) {
    ?>
            $("#generateReportSubmit").click(function() {
                $("#ReportAdminIndexForm").attr('action','/admin/reports/index');
            });

            $("#downloadCVSOne").click(function() {
                $("#ReportAdminIndexForm").attr('action','/admin/reports/downloadAsCsv');
                $("#ReportAdminIndexForm").submit();
            });

            $("#downloadPDFOne").click(function() {
                $("#ReportAdminIndexForm").attr('action','/admin/reports/downloadAsPdf');
                $("#ReportAdminIndexForm").submit();
            });
    <?php
        }
    ?>
</script>
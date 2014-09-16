<?php
$this->pageTitle = 'Reports';
echo $this->Form->create('Report', array('action' => $formAction));
if (empty($getData)) {
    $getData['Report']['library_id'] = "all";
    $getData['Report']['reports_daterange'] = "day";
    $getData['Report']['date'] = "";
    $getData['Report']['date_from'] = "";
    $getData['Report']['date_to'] = "";
    $getData['Report']['Territory'] = "";
}
?>
<fieldset>
    <legend>Generate Streaming Report 
        <?php
        if ($libraryID != "")
        {
            echo "for \"" . $this->getTextEncode($libraryname) . "\"";
        }
        ?>
    </legend>
    <div class="formFieldsContainer">
        <div class="formFieldsbox">
            <div id="form_step" class="form_steps">
                <h1>Report Settings</h1>
                <table cellspacing="10" cellpadding="0" border="0" width="100%">
                    <tr><td id="formError" class="formError" colspan="4"></td></tr>
                    <tr>
                        <?php
                        if ($libraryID == "") {
                            ?>
                        <td align="right"><?php echo $this->Form->label('Select Library'); ?></td>
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
                            <td align="right"><?php echo $this->Form->label('Range'); ?></td>
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
                        } else {
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
                    <tr id="initial_date_range" <?php if ($getData['Report']['reports_daterange'] == "manual") { ?>style="display:none;"<?php } ?>>
                        <td align="center" colspan="6">
                            <?php
                            echo $this->Form->label('Select Date');
                            echo $this->Form->input('date', array('label' => false, 'value' => $getData['Report']['date'], 'div' => false, 'class' => 'form_fields', 'readonly' => 'readonly'));
                            ?>
                        </td>
                    </tr>
                    <tr id="date_range" <?php if ($getData['Report']['reports_daterange'] != "manual") { ?>style="display:none;"<?php } ?>>
                        <td align="right" colspan="2">
                            <?php
                            echo $this->Form->label('From');
                            echo $this->Form->input('date_from', array('label' => false, 'value' => $getData['Report']['date_from'], 'div' => false, 'class' => 'form_fields', 'readonly' => 'readonly'));
                            ?>
                        </td>
                        <td align="left" colspan="2">
                            <?php
                            echo $this->Form->label('To');
                            echo $this->Form->input('date_to', array('label' => false, 'value' => $getData['Report']['date_to'], 'div' => false, 'class' => 'form_fields', 'readonly' => 'readonly'));
                            ?>
                        </td>
                    </tr>
                    <tr><td colspan="6">&nbsp;</td></tr>
                    <tr>
                        <td colspan="6" align="center"><?php echo $this->Form->submit('Generate Report', array('id' => 'generateReportSubmit')); ?></td>
                    </tr>
                    <tr><td colspan="6">&nbsp;</td></tr>
                    <?php
                    if (!empty($streamingHours)) {
                        ?>
                        <tr>
                            <td colspan="3" align="center">
                                <?php
                                echo $html->image('excel_icon.gif', array("alt" => "Download As CSV", "title" => "Download As CSV", 'style' => 'cursor:pointer;', 'id' => 'downloadStreamingCVSOne'));
                                ?>
                            </td>
                            <td colspan="3" align="center">
                                <?php
                                echo $html->image('pdf_icon.gif', array("alt" => "Download As PDF", "title" => "Download As PDF", 'style' => 'cursor:pointer;', 'id' => 'downloadStreamingPDFOne'));
                                ?>
                            </td>
                        </tr>
                        <tr><td colspan="6">&nbsp;</td></tr>

                        <tr><td colspan="6">&nbsp;</td></tr>

                        <?php if (!is_array($streamingHours)) { ?>

                            <tr>
                                <th colspan="6" align="center">Total Songs Streamed</th>
                            </tr>
                            <tr>
                                <td colspan="6" align="center">
                                    <table cellspacing="0" cellpadding="0" border="1" class="reportsTable" align="center">
                                        <tr>
                                            <th>Total Streamed (Number of Songs)</th>
                                        </tr>
                                        <tr>
                                            <td align="center"><?php echo $streamingHours; ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                        <?php } else { ?> 
                            <tr>

                                <td colspan="6" align="center">
                                    <div style="padding-bottom: 5px;"> <b> Total Songs Streamed </b> </div>

                                    <table cellpadding="0" cellspacing="0" border="1" class="reportsTable"> 
                                        <tr>
                                            <th align="center"> &nbsp; </th>
                                            <th align="center"> Library name </th>
                                            <th align="center"> Total Streamed </th>
                                        </tr>

                                        <?php
                                        $index = 1;
                                        foreach ($streamingHours AS $key => $val) {
                                            ?>

                                            <tr>
                                                <td> <?php echo $index; ?> </td>
                                                <td> <?php echo $val['lib']['library_name']; ?> </td>
                                                <td align="center"> <?php echo $val['0']['total_count']; ?> </td>
                                            </tr>

                                            <?php $index++;
                                        } ?>
                                    </table> </td></tr>    
    <?php } ?>         

                        <tr><td colspan="6">&nbsp;</td></tr>

                        <?php
                        if (!is_array($patronStreamedInfo)) {
                            ?>                              
                            <tr><th colspan="6" align="center">Total Patrons</th></tr>
                            <tr>
                                <td colspan="6" align="center">
                                    <table cellspacing="0" cellpadding="0" border="1" class="reportsTable" align="center">
                                        <tr><th>Total Number of Patrons who have streamed during Reporting Period</th></tr>
                                        <tr><td align="center"><?php echo $patronStreamedInfo; ?></td></tr>
                                    </table>
                                </td>
                            </tr>
    <?php } else { ?>         
                            <tr>
                                <td colspan="6" align="center">
                                    <div style="padding-bottom: 5px;"> <b> Total Number of Patrons who have streamed during Reporting Period </b> </div>

                                    <table cellpadding="0" cellspacing="0" border="1" class="reportsTable"> 
                                        <tr>
                                            <th align="center"> &nbsp; </th>
                                            <th align="center"> Library name </th>
                                            <th align="center"> Total Patrons </th>
                                        </tr>

                                        <?php
                                        $index = 1;
                                        foreach ($patronStreamedInfo AS $key => $val) {
                                            ?>

                                            <tr>
                                                <td> <?php echo $index; ?> </td>
                                                <td> <?php echo $val['lib']['library_name']; ?> </td>
                                                <td align="center"> <?php echo $val['0']['total_patrons']; ?> </td>
                                            </tr>

            <?php $index++;
        } ?>
                                    </table> </td></tr>

    <?php } ?>            

<?php if(!empty($dayStreamingInfo)){ ?>
                        <tr><td colspan="6">&nbsp;</td></tr>
                        <tr><th colspan="6" align="center">Library Streaming Report</th></tr>
                        <tr>
                            <td colspan="6" align="center">
                                <table cellspacing="0" cellpadding="0" border="1" class="reportsTable" align="center">
                                    <tr>
                                        <th>&nbsp;</th>
                                        <?php if(!is_numeric($library_id)):?>
                                        <th>Library Name</th>
                                        <?php endif; ?>
                                        <th>Patron ID</th>
                                        <th>Artists Name</th>
                                        <th>Track Title</th>
                                        <th>Streamed</th>
                                    </tr>
                                    <?php
                                    $i = 1;
                                    //print "<pre>";print_r($downloads);exit;
                                    foreach ($dayStreamingInfo as $key => $streamInformation) {
                                        ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <?php if(!is_numeric($library_id)):?>
                                            <td><?php echo $this->getTextEncode($library->getLibraryName($streamInformation['StreamingHistory']['library_id'])); ?></td>
                                            <?php endif; ?>
                                            <td><?php
                                               if( isset( $streamInformation['lib']['show_barcode'] ) && ( $streamInformation['lib']['show_barcode'] == 1) ){                                                
                                                    echo $streamInformation['StreamingHistory']['patron_id'];
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $this->getTextEncode($streamInformation['songs']['artist']); ?></td>
                                            <td><?php echo $this->getTextEncode($streamInformation['songs']['track_title']); ?></td>
                                            <td><?php echo date('Y-m-d', strtotime($streamInformation['StreamingHistory']['createdOn'])); ?></td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                </table>
                            </td>
                        </tr>
<?php } ?>
                        <tr><td colspan="6">&nbsp;</td></tr>
                        <tr><th colspan="6" align="center">Patron Streaming Report</th></tr>
                        <tr>
                            <td colspan="6" align="center">
                                <table cellspacing="0" cellpadding="0" border="1" class="reportsTable" align="center">
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>ID</th>
                                        <?php if(!is_numeric($library_id)):?>
                                        <th>Library Name</th>
                                        <?php endif; ?>
                                        <th>Total Number of Tracks Streamed</th>
                                    </tr>
                                        <?php
                                        $i = 1;
                                        //print_r($patronStreamedDetailedInfo);
                                        foreach ($patronStreamedDetailedInfo as $key => $patronStramed)
                                        {
                                            ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td>
                                                    <?php
                                                    if( isset( $patronStramed['lib']['show_barcode'] ) && ( $patronStramed['lib']['show_barcode'] == 1) ){
                                                         echo $patronStramed['StreamingHistory']['patron_id'];
                                                    }else{
                                                         echo $patronStramed['Currentpatrons']['id'];
                                                    }                                                          
                                                    ?>
                                                </td>
                                                <?php if (!is_numeric($library_id)): ?>
                                                    <td><?php echo $this->getTextEncode($library->getLibraryName($patronStramed['StreamingHistory']['library_id'])); ?></td>
                                                <?php endif; ?>
                                                <td align="center"><?php echo $patronStramed[0]['total_streamed_songs']; ?></td>
                                            </tr>
                                            <?php
                                            $i++;
                                        }
                                        ?>
                                </table>
                            </td>
                        </tr>
                        <tr><td colspan="6">&nbsp;</td></tr>
                        
                        <tr><th colspan="6" align="center">Genres Streaming Report</th></tr>
                        <tr>
                            <td colspan="6" align="center">
                                <table cellspacing="0" cellpadding="0" border="1" class="reportsTable" align="center">
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>Genre Name</th>
                                        <th>Total Number of Tracks Streamed</th>
                                    </tr>
    <?php
    $i = 1;
    foreach ($genreDayStremedInfo as $key => $genreStreamed) {
        ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $this->getTextEncode($genreStreamed['Genres']['expected_genre']); ?></td>
                                            <td align="center"><?php echo $genreStreamed[0]['total_streamed_songs']; ?></td>
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
                    } elseif (empty($streamingHours) && empty($errors) && isset($this->data)) {
                        ?>
                        <tr>
                            <td colspan="6" align="center"><label>There are no streaming found for the selected criteria.</label></td>
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

if (empty($library_id) || ($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") != '')) {
    
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
            if ($(this).val() == "manual") {
                $("#date_range").show();
                $("#initial_date_range").hide();
            }
            else {
                $("#initial_date_range").show();
                $("#date_range").hide();
            }
        });
        $("#ReportTerritory").change(function() {
            var data = "Territory=" + $("#ReportTerritory").val();
            jQuery.ajax({
                type: "post", // Request method: post, get
                url: webroot + "admin/reports/getLibraryIds", // URL to request
                data: data, // post data
                success: function(response) {
                    $('#allLibrary').text('');
                    $('#allLibrary').html(response);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                }
            });
            return false;
        });
    });
<?php
if (!empty($streamingHours)) {
    ?>
        $("#generateReportSubmit").click(function() {
            $("#ReportAdminStreamingreportForm").attr('action', '/admin/reports/streamingreport');
        });

        $("#downloadStreamingCVSOne").click(function() {
            $("#ReportAdminStreamingreportForm").attr('action', '/admin/reports/streamingreport/csv');
            $("#ReportAdminStreamingreportForm").submit();
        });

        $("#downloadStreamingPDFOne").click(function() {
            $("#ReportAdminStreamingreportForm").attr('action', '/admin/reports/streamingreport/pdf');
            $("#ReportAdminStreamingreportForm").submit();
        });
    <?php
}
?>
</script>
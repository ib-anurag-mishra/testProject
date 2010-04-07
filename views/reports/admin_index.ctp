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
    }
?>
<fieldset>
<legend>Generate Libraries Report</legend>
    <div class="formFieldsContainer">
        <div class="formFieldsbox">
            <div id="form_step" class="form_steps">
                <h1>Report Settings</h1>
                <table cellspacing="10" cellpadding="0" border="0">
                    <tr><td id="formError" class="formError" colspan="4"></td></tr>
                    <tr>
                        <td align="right"><?php echo $this->Form->label('Select Library');?></td>
                        <td align="left">
                            <?php
                                $libraries['all'] = "All Libraries";
                                echo $this->Form->input('library_id', array('options' => $libraries, 'label' => false, 'div' => false, 'class' => 'select_fields', 'default' => $getData['Report']['library_id']));
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
                        <td align="center" colspan="4">
                            <?php
                                echo $this->Form->label('From');
                                echo $this->Form->input('date_from',array('label' => false ,'value' => $getData['Report']['date_from'], 'div' => false, 'class' => 'form_fields', 'readonly' => 'readonly'));
                                echo $this->Form->label('To');
                                echo $this->Form->input('date_to',array('label' => false ,'value' => $getData['Report']['date_to'], 'div' => false, 'class' => 'form_fields', 'readonly' => 'readonly'));
                            ?>
                        </td>
                    </tr>
                    <tr><td colspan="4">&nbsp;</td></tr>
                    <tr>
                        <td colspan="4" align="center"><?php echo $this->Form->submit('Generate Report');?></td>
                    </tr>
                    <tr><td colspan="4">&nbsp;</td></tr>
                    <?php
                    if(!empty($downloads)) {
                    ?>
                    <tr>
                        <td colspan="2" align="center">
                            <?php
                                echo $html->image('excel_icon.gif', array("alt" => "Download As CSV", "title" => "Download As CSV", 'style' => 'cursor:pointer;', 'id' => 'downloadCVSOne'));
                            ?>
                        </td>
                        <td colspan="2" align="center">
                            <?php
                                echo $html->image('pdf_icon.gif', array("alt" => "Download As PDF", "title" => "Download As PDF", 'style' => 'cursor:pointer;', 'id' => 'downloadPDFOne'));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" align="center">
                            <table cellspacing="0" cellpadding="0" border="1" class="reportsTable" align="center">
                                <tr>
                                    <th>Sl No.</th>
                                    <th>Library ID</th>
                                    <th>Library Name</th>
                                    <th>Patron ID</th>
                                    <th>Artists Name</th>
                                    <th>Track Title</th>
                                    <th>Downloaded Date</th>
                                </tr>
                                <?php
                                foreach($downloads as $key => $download) {
                                ?>
                                    <tr>
                                        <td><?php echo $key+1; ?></td>
                                        <td><?php echo $download['Download']['library_id']; ?></td>
                                        <td><?php echo $library->getLibraryName($download['Download']['library_id']); ?></td>
                                        <td><?php echo $download['Download']['patron_id']; ?></td>
                                        <td><?php echo $download['Download']['artist']; ?></td>
                                        <td><?php echo $download['Download']['track_title']; ?></td>
                                        <td><?php echo $download['Download']['created']; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <?php
                                echo $html->image('excel_icon.gif', array("alt" => "Download As CSV", "title" => "Download As CSV", 'style' => 'cursor:pointer;', 'id' => 'downloadCVSTwo'));
                            ?>
                        </td>
                        <td colspan="2" align="center">
                            <?php
                                echo $html->image('pdf_icon.gif', array("alt" => "Download As PDF", "title" => "Download As PDF", 'style' => 'cursor:pointer;', 'id' => 'downloadPDFTwo'));
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</fieldset>
<?php
 echo $this->Form->end();
 echo $session->flash();
?>
<link type="text/css" rel="stylesheet" href="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/css&amp;f=dark-hive/jquery-ui-1.8.custom.css" />
<script type="text/javascript" src="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/js&amp;f=datepicker/jquery.ui.core.js,datepicker/jquery.ui.widget.js,datepicker/jquery.ui.datepicker.js"></script>
<script type="text/javascript">
    $(function() {
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
    });
    <?php
        if(!empty($downloads)) {
    ?>
            $("#downloadCVSOne").click(function() {
                $("#ReportAdminIndexForm").attr('action','/admin/reports/downloadAsCsv');
                $("#ReportAdminIndexForm").submit();
            });
            
            $("#downloadPDFOne").click(function() {
                $("#ReportAdminIndexForm").attr('action','/admin/reports/downloadAsPdf');
                $("#ReportAdminIndexForm").submit();
            });
            
            $("#downloadCVSTwo").click(function() {
                $("#ReportAdminIndexForm").attr('action','/admin/reports/downloadAsCsv');
                $("#ReportAdminIndexForm").submit();
            });
            
            $("#downloadPDFTwo").click(function() {
                $("#ReportAdminIndexForm").attr('action','/admin/reports/downloadAsPdf');
                $("#ReportAdminIndexForm").submit();
            });
    <?php
        }
    ?>
</script>
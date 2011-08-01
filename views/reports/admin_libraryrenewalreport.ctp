<?php
/*
 File Name : admin_libraryrenewalreport.ctp
 File Description : view page for admin library renewal report
 Author : m68interactive
 */
?>
<?php
    $this->pageTitle = 'Reports';
?>
<form name="libraryRenewalReport" id="libraryRenewalReport" action="libraryrenewalreport" method="post">
    <fieldset>
        <legend>Libraries Renewal Report</legend>
        <div class="formFieldsContainer">
            <div class="formFieldsbox">
                <div class="form_steps">
                    <table cellspacing="10" cellpadding="0" class="reportsTable" border="0" width="100%">
                    <?php
                        if(empty($sitelibraries)) {
                    ?>
                            <tr><td align="center"> There are no Libraries to show at this moment</td></tr>
                    <?php
                        }
                        else {
                    ?>
                            <tr>
                                <td colspan="4" align="center">
                                    <?php echo $this->Form->hidden('downloadType', array('value' => ""));?>
                                    <?php echo $html->image('excel_icon.gif', array("alt" => "Download As CSV", "title" => "Download As CSV", 'style' => 'cursor:pointer;', 'id' => 'downloadCVSOne')); ?>
                                    <?php echo $html->image('pdf_icon.gif', array("alt" => "Download As PDF", "title" => "Download As PDF", 'style' => 'cursor:pointer;', 'id' => 'downloadPDFOne')); ?>
                                </td>
                            </tr>
                            <tr><td colspan="4">&nbsp;</td></tr>
                            <tr>
                                <th>Library Name</th>
                                <th>Contract Start Date</th>
                                <th>Contract Renewal Date</th>
                                <th>Current Library Status</th>
                            </tr>
                    <?php
                            foreach($sitelibraries as $library) {
                    ?>
                                <tr>
                                    <td align="left" ><?php echo $library['Library']['library_name']; ?></td>
                                    <td align="left" ><?php echo $library['Library']['library_contract_start_date']; ?></td>
                                    <td align="left" >
                                    <?php
                                        $contractDateArr = explode("-", $library['Library']['library_contract_start_date']);
                                        echo date("Y-m-d", mktime(0, 0, 0, $contractDateArr[1], $contractDateArr[2], $contractDateArr[0]+1));
                                    ?>
                                    </td>
                                    <td align="left" >
                                    <?php
                                        if($library['Library']['library_status'] == 'active') {
                                            echo "Active";
                                        }
                                        else {
                                            echo "Inactive";
                                        }
                                    ?>
                                    </td>
                                </tr>
                    <?php
                            }
                        }
                    ?>
                    </table>
                </div>
            </div>
        </div>
    </fieldset>
</form>
<script type="text/javascript">
    $("#downloadCVSOne").click(function() {
        $("#downloadType").val("csv");
        $("#libraryRenewalReport").submit();
    });
    
    $("#downloadPDFOne").click(function() {
        $("#downloadType").val("pdf");
        $("#libraryRenewalReport").submit();
    });
</script>
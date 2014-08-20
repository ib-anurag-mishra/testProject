<?php
/*
 File Name : admin_libraryrenewalreport.ctp
 File Description : view page for library wishlist report
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
    }
?>
<fieldset>
<legend>Generate Library WishLists Report <?php if($libraryID != "") { echo "for \"".$libraryname."\""; }?></legend>
    <div class="formFieldsContainer">
        <div class="formFieldsbox">
            <div id="form_step" class="form_steps">
                <h1>Report Settings</h1>
                <table cellspacing="10" cellpadding="0" border="0" width="100%">
                    <tr><td id="formError" class="formError" colspan="4"></td></tr>
                    <tr>
                        <?php
                            echo $this->Form->hidden('downloadType', array('value' => ""));
                            if($libraryID == "") {
                        ?>
                                <td align="right"><?php echo $this->Form->label('Select Library');?></td>
                                <td align="left">
                        <?php    
                                    if($this->Session->read("Auth.User.consortium") == ''){ $libraries['all'] = "All Libraries"; }
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
                    if(!empty($wishlists)) {
                        if($this->data['Report']['library_id'] == "all") {
                    ?>
                            <tr>
                                <td colspan="2" align="center">
                                    <?php
                                        echo $html->image('excel_icon.gif', array("alt" => "Download As CSV", "title" => "Download As CSV", 'style' => 'cursor:pointer;', 'id' => 'downloadCVS'));
                                    ?>
                                </td>
                                <td colspan="2" align="center">
                                    <?php
                                        echo $html->image('pdf_icon.gif', array("alt" => "Download As PDF", "title" => "Download As PDF", 'style' => 'cursor:pointer;', 'id' => 'downloadPDF'));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" align="center">
                                    <table cellspacing="0" cellpadding="0" border="1" class="reportsTable" align="center">
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>Library Name</th>
                                            <th>Available Downloads</th>
                                            <th>Download Limit Type</th>
                                            <th>Download Limit</th>
                                            <th># of Songs WishListed</th>
                                        </tr>
                                        <?php
                                        $i = 1;
                                        $totalSongs = 0;
                                        foreach($wishlists as $key => $wishlist) {
                                            $libraryDetails = $library->getLibraryDetails($wishlist['Wishlist']['library_id']);
                                            
                                        ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $libraryDetails['Library']['library_name']; ?></td>
                                                <td><?php 
														if($libraryDetails['Library']['library_unlimited'] == 1){
															echo "Unlimited";
														} else {
															echo $libraryDetails['Library']['library_available_downloads'];
														}
													?>
												</td>
                                                <td><?php echo ucwords($libraryDetails['Library']['library_download_type']); ?></td>
                                                <td><?php echo $libraryDetails['Library']['library_download_limit']; ?></td>
                                                <td><?php echo $wishlist[0]['totalWishlistedSongs']; ?></td>
                                            </tr>
                                        <?php
                                            $i++;
                                            $totalSongs = $totalSongs+$wishlist[0]['totalWishlistedSongs'];
                                        }
                                        ?>
                                        <tr><th colspan="6">&nbsp;</th></tr>
                                        <tr><th colspan="6" align="right">Total # of Songs WishListed: <?php echo $totalSongs; ?></th></tr>
                                    </table>
                                </td>
                            </tr>
                    <?php
                        }
                        else {
                    ?>
                            <tr>
                                <td colspan="2" align="center">
                                    <?php
                                        echo $html->image('excel_icon.gif', array("alt" => "Download As CSV", "title" => "Download As CSV", 'style' => 'cursor:pointer;', 'id' => 'downloadCVS'));
                                    ?>
                                </td>
                                <td colspan="2" align="center">
                                    <?php
                                        echo $html->image('pdf_icon.gif', array("alt" => "Download As PDF", "title" => "Download As PDF", 'style' => 'cursor:pointer;', 'id' => 'downloadPDF'));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" align="center">
                                    <table cellspacing="0" cellpadding="0" border="1" class="reportsTable" align="center">
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>Library Name</th>
                                            <th>ID</th>
                                            <th>Artists Name</th>
                                            <th>Track Title</th>
                                            <th>WishListed On</th>
                                        </tr>
                                        <?php
                                        $i = 1;
                                        $libraryDetails = $library->getLibraryDetails($this->data['Report']['library_id']);
                                        foreach($wishlists as $key => $wishlist) {
                                        ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $libraryDetails['Library']['library_name']; ?></td>
                                                <td><?php echo $wishlist['Currentpatrons']['id']; ?></td>
                                                <td><?php echo $wishlist['Wishlist']['artist']; ?></td>
                                                <td><?php echo $wishlist['Wishlist']['track_title']; ?></td>
                                                <td><?php echo date("Y-m-d", strtotime($wishlist['Wishlist']['created'])); ?></td>
                                            </tr>
                                        <?php
                                            $i++;
                                        }
                                        ?>
                                        <tr>
                                            <th colspan="6">&nbsp;</th>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Available Downloads: 
											<?php 
											if($libraryDetails['Library']['library_unlimited'] == 1){
												echo "Unlimited";
											} else {
												echo $libraryDetails['Library']['library_available_downloads'];
											}											
											?>
											</th>
                                            <th colspan="2">Download Limit Type: <?php echo ucwords($libraryDetails['Library']['library_download_type']); ?></th>
                                            <th>Download Limit: <?php echo $libraryDetails['Library']['library_download_limit']; ?></th>
                                            <th>Total # of Songs WishListed: <?php echo count($wishlists); ?></th>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    elseif(empty($wishlists) && empty($errors) && isset($this->data)) {
                    ?>
                    <tr>
                        <td colspan="4" align="center"><label>There are not songs found in the wishlist for the selected library.</label></td>
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
        if(!empty($wishlists)) {
    ?>
            $("#generateWishlistSubmit").click(function() {
                $("#ReportDownloadType").val("");
            });
            
            $("#downloadCVS").click(function() {
                $("#ReportDownloadType").val("csv");
                $("#ReportAdminLibrarywishlistreportForm").submit();
            });
            
            $("#downloadPDF").click(function() {
                $("#ReportDownloadType").val("pdf");
                $("#ReportAdminLibrarywishlistreportForm").submit();
            });
    <?php
        }
    ?>
</script>
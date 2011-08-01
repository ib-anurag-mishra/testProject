<?php
/*
 File Name : admin_downloadLibraryRenewalReportAsCsv.ctp
 File Description : 
 Author : m68interactive
 */
?>
<?php
$line = array('Library Name', 'Contract Start Date', 'Contract Renewal Date', 'Current Library Status');
$csv->addRow($line);

foreach($sitelibraries as $library) {
    $contractDateArr = explode("-", $library['Library']['library_contract_start_date']);
    $contractEndDate = date("Y-m-d", mktime(0, 0, 0, $contractDateArr[1], $contractDateArr[2], $contractDateArr[0]+1));
    if($library['Library']['library_status'] == 'active') {
        $currentsatus = "Active";
    }
    else {
        $currentsatus = "Inactive";
    }
    $line = array($library['Library']['library_name'], $library['Library']['library_contract_start_date'], $contractEndDate, $currentsatus);
    $csv->addRow($line);
}
echo $csv->render('Libraries_Renewal_Dates_Report_By_'.date("Y-m-d").'.csv');
?>
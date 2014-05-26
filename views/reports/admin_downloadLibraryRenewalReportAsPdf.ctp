<?php
/*
 File Name : admin_downloadLibraryRenewalReportAsPdf.ctp
 File Description : 
 Author : m68interactive
 */
?>
<?php
    App::import('Vendor','xtcpdf'); 
    $tcpdf = new XTCPDF('L', 'mm', 'LETTER', true, 'UTF-8', false);
    $textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans'

    // set document information
    $tcpdf->SetCreator("FreegalMusic.com");
    $tcpdf->SetAuthor('MayCreate');
    $tcpdf->SetTitle('Libraries Renewal Dates Report');
    $tcpdf->SetSubject('Libraries Renewal Dates Report');
    $tcpdf->SetKeywords('Library, Renewal, Dates, Report');
    
    //set auto page breaks
    $tcpdf->SetAutoPageBreak(TRUE, "20");
    
    // set default header data
    // set header and footer fonts
    $tcpdf->setHeaderFont(array($textfont,'',12));
    $tcpdf->xheadertext = 'Libraries Renewal Dates Report generate on '.date("Y-m-d H:i:s");
    $tcpdf->xfootertext = 'Copyright © %d FreegalMusic.com. All rights reserved.';
    
    //set margins
    $tcpdf->SetMargins("10", "15", "0");
    $tcpdf->SetHeaderMargin("10");
    $tcpdf->SetFooterMargin("10");
    
    // ---------------------------------------------------------
    
    // set font
    $tcpdf->SetFont('helvetica', '', 8);
    
    // add a page
    $tcpdf->AddPage();
    
    //Column titles
    $header = array('Library Name', 'Contract Start Date', 'Contract Renewal Date', 'Current Library Status');
    
    //Data loading
    foreach($sitelibraries as $library) {
        $contractDateArr = explode("-", $library['Library']['library_contract_start_date']);
        $contractEndDate = $library['Library']['library_contract_end_date'];
        if($library['Library']['library_status'] == 'active') {
            $currentsatus = "Active";
        }
        else {
            $currentsatus = "Inactive";
        }
        $data[] = array($library['Library']['library_name'], $library['Library']['library_contract_start_date'], $contractEndDate, $currentsatus);
    }
    
    // print colored table
    // Colors, line width and bold font
    $tcpdf->SetFillColor(0, 153, 255);
    $tcpdf->SetTextColor(255);
    $tcpdf->SetDrawColor(224, 224, 224);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    // Header
    $w = array(130, 40, 40, 50);
    for($i = 0; $i < count($header); $i++)
        $tcpdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        $tcpdf->Ln();
    // Color and font restoration
    $tcpdf->SetFillColor(224, 235, 255);
    $tcpdf->SetTextColor(0);
    $tcpdf->SetFont('');
    // Data
    $fill = 0;
    foreach($data as $k=>$row) {
        if($k%28 == 0 && $k != 0) {
            // Colors, line width and bold font
            $tcpdf->SetFillColor(0, 153, 255);
            $tcpdf->SetTextColor(255);
            $tcpdf->SetDrawColor(224, 224, 224);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            // Header
            for($i = 0; $i < count($header); $i++)
                $tcpdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
                $tcpdf->Ln();
        }
        $tcpdf->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[2], 6, $row[2], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[3], 6, $row[3], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Ln();
        $fill=!$fill;
    }
    $tcpdf->Cell(array_sum($w), 0, '', 'T');
    
    echo $tcpdf->Output('Libraries_Renewal_Dates_Report_By_'.date("Y-m-d").'.pdf', 'D');
?>
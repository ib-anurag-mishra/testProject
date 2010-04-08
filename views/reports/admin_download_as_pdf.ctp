<?php
    App::import('Vendor','xtcpdf'); 
    $tcpdf = new XTCPDF('L', 'mm', 'LETTER', true, 'UTF-8', false);
    $textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans'
    
    if($this->data['Report']['library_id'] == "all") {
        $savelibraryName = "All_Libraries";
        $displaylibraryName = "All Libraries";
    }
    else {
        $savelibraryName = "LibraryID_".$download['Download']['library_id'];
        $displaylibraryName = "LibraryID ".$download['Download']['library_id'];
    }
    $date_arr = explode("/", $this->data['Report']['date']);
    $date_arr_from = explode("/", $this->data['Report']['date_from']);
    $date_arr_to = explode("/", $this->data['Report']['date_to']);
    if($this->data['Report']['reports_daterange'] == 'day') {
        $savedateRange = "_for_".$date_arr[2]."-".$date_arr[0]."-".$date_arr[1];
        $displaydateRange = " for ".$date_arr[2]."-".$date_arr[0]."-".$date_arr[1];
    }
    elseif($this->data['Report']['reports_daterange'] == 'week') {
        $savedateRange = "_for_week_of_".$date_arr[2]."-".$date_arr[0]."-".$date_arr[1];
        $displaydateRange = " for week of ".$date_arr[2]."-".$date_arr[0]."-".$date_arr[1];
    }
    elseif($this->data['Report']['reports_daterange'] == 'month') {
        $savedateRange = "_for_month_of_".date("F", $date_arr[0])."_".date("Y", $date_arr[2]);
        $displaydateRange = " for month of ".date("F", $date_arr[0])."_".date("Y", $date_arr[2]);
    }
    elseif($this->data['Report']['reports_daterange'] == 'Year') {
        $savedateRange = "_for_".date("Y", $date_arr[2]);
        $displaydateRange = " for ".date("Y", $date_arr[2]);
    }
    elseif($this->data['Report']['reports_daterange'] == 'manual') {
        $savedateRange = "_for_".$date_arr_from[2]."-".$date_arr_from[0]."-".$date_arr_from[1]."_to_".$date_arr_to[2]."-".$date_arr_to[0]."-".$date_arr_to[1];
        $displaydateRange = " from ".$date_arr_from[2]."-".$date_arr_from[0]."-".$date_arr_from[1]." to ".$date_arr_to[2]."-".$date_arr_to[0]."-".$date_arr_to[1];
    }
    
    // set document information
    $tcpdf->SetCreator("FreegalMusic.com");
    $tcpdf->SetAuthor('MayCreate');
    $tcpdf->SetTitle('Downloads Report');
    $tcpdf->SetSubject('Library Downloads Report');
    $tcpdf->SetKeywords('Library, Downloads, Report');
    
    //set auto page breaks
    $tcpdf->SetAutoPageBreak(TRUE, "20");
    
    // set default header data
    // set header and footer fonts
    $tcpdf->setHeaderFont(array($textfont,'',12));
    $tcpdf->xheadertext = 'Libraries Download Report for '.$displaylibraryName.$displaydateRange;
    $tcpdf->xfootertext = 'Copyright © %d FreegalMusic.com. All rights reserved.';
    
    //set margins
    $tcpdf->SetMargins("0", "15", "0");
    $tcpdf->SetHeaderMargin("10");
    $tcpdf->SetFooterMargin("10");
    
    //set image scale factor
    //$tcpdf->setImageScale("1"); 
    
    // ---------------------------------------------------------
    
    // set font
    $tcpdf->SetFont('helvetica', '', 8);
    
    // add a page
    $tcpdf->AddPage();
    
    //Column titles
    $header = array('Library Name', 'Patron ID', 'Artists Name', 'Track title', 'Download');
    
    //Data loading
    foreach($downloads as $key => $download) {
        $libraryName = $library->getLibraryName($download['Download']['library_id']);
        $data[] = array($libraryName, $download['Download']['patron_id'], $download['Download']['artist'], $download['Download']['track_title'], date('Y-m-d', strtotime($download['Download']['created'])));
    }
    
    // print colored table
    // Colors, line width and bold font
    $tcpdf->SetFillColor(0, 153, 255);
    $tcpdf->SetTextColor(255);
    $tcpdf->SetDrawColor(224, 224, 224);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    // Header
    $w = array(70, 25, 70, 85, 30);
    for($i = 0; $i < count($header); $i++)
        $tcpdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        $tcpdf->Ln();
    // Color and font restoration
    $tcpdf->SetFillColor(224, 235, 255);
    $tcpdf->SetTextColor(0);
    $tcpdf->SetFont('');
    // Data
    $fill = 0;
    foreach($data as $row) {
        $tcpdf->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[2], 6, $row[2], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[3], 6, $row[3], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[4], 6, $row[4], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Ln();
        $fill=!$fill;
    }
    $tcpdf->Cell(array_sum($w), 0, '', 'T');
    
    echo $tcpdf->Output('DownloadsReport_'.$savelibraryName.$savedateRange.'.pdf', 'D');
?>
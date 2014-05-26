<?php
/*
 File Name : admin_downloadConsortiumRenewalReportAsPdf.ctp
 File Description : 
 Author : m68interactive
 */
?>
<?php
    App::import('Vendor','xtcpdf'); 
    $tcpdf = new XTCPDF('L', 'mm', 'LETTER', true, 'UTF-8', false);
    $textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans'
    
    if($this->data['Report']['library_id'] == "all") {
        $savelibraryName = "All_Libraries";
        $displaylibraryName = "All Libraries";
    }
    else {
        $savelibraryName = "LibraryID_".$downloads[0]['Download']['library_id'];
        $displaylibraryName = "LibraryID ".$downloads[0]['Download']['library_id'];
    }
    $date_arr = explode("/", $this->data['Report']['date']);
    $date_arr_from = explode("/", $this->data['Report']['date_from']);
    $date_arr_to = explode("/", $this->data['Report']['date_to']);
    if($this->data['Report']['reports_daterange'] == 'day') {
        $savedateRange = "_for_".$date_arr[2]."-".$date_arr[0]."-".$date_arr[1];
        $displaydateRange = " for ".$date_arr[2]."-".$date_arr[0]."-".$date_arr[1];
    }
    elseif($this->data['Report']['reports_daterange'] == 'week') {
		if(date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) == 0){
			$startDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))-6, $date_arr[2]));	
			$endDate = date('Y-m-d H:i:s', mktime(23, 59, 59, $date_arr[0], ($date_arr[1]-date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2]));
		}else{	  
			$startDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))+1, $date_arr[2]));	
			$endDate = date('Y-m-d H:i:s', mktime(23, 59, 59, $date_arr[0], ($date_arr[1]-date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2])))+7, $date_arr[2]));
		}	  
        $savedateRange = "_for_week_of_".$startDate."_to_".$endDate;
        $displaydateRange = " for week of ".$startDate." to ".$endDate;
    }
    elseif($this->data['Report']['reports_daterange'] == 'month') {
        $savedateRange = "_for_month_of_".date("F", mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]))."_".date("Y", mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]));
        $displaydateRange = " for month of ".date("F", mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]))." ".date("Y", mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]));
    }
    elseif($this->data['Report']['reports_daterange'] == 'year') {
        $savedateRange = "_for_".date("Y", mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]));
        $displaydateRange = " for ".date("Y", mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]));
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
    $tcpdf->xheadertext = 'Libraries/Patrons Download Report for '.$displaylibraryName.$displaydateRange;
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
    
    $tcpdf->SetTextColor(0);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    $tcpdf->Cell(250, 7, 'Library Remaining Downloads', 0, 0, 'C', 0);
    $tcpdf->Ln();
    
    $tcpdf->SetFillColor(0, 153, 255);
    $tcpdf->SetTextColor(255);
    $tcpdf->SetDrawColor(224, 224, 224);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    // Header
    $w = array(10, 50, 190);
    $DownloadCount_header = array('', 'Library Name', 'Number of Remaining Downloads');	
    for($i = 0; $i < count($DownloadCount_header); $i++)
        $tcpdf->Cell($w[$i], 7, $DownloadCount_header[$i], 1, 0, 'C', 1);
        $tcpdf->Ln();
    // Color and font restoration
    $tcpdf->SetFillColor(224, 235, 255);
    $tcpdf->SetTextColor(0);
    $tcpdf->SetFont('');
    // Data
    $fill = 0;
	$key = 1;
    foreach($libraries_download as $LibraryName => $DownloadCount) {
		if($DownloadCount['Library']['library_unlimited'] == 1){
			$text = "Unlimited";
		} else {
			$text = $DownloadCount['Library']['library_available_downloads'];
		}	
        $libraries_downloads[] = array($key, $DownloadCount['Library']['library_name'], $text);
		$key++;
    }	
    foreach($libraries_downloads as $k=>$row) {
        if($k%27 == 0 && $k != 0) {
            $tcpdf->SetTextColor(0);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            $tcpdf->Cell(250, 7, 'Libraries Remaining Downloads', 0, 0, 'C', 0);
            $tcpdf->Ln();
            
            // Colors, line width and bold font
            $tcpdf->SetFillColor(0, 153, 255);
            $tcpdf->SetTextColor(255);
            $tcpdf->SetDrawColor(224, 224, 224);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            // Header
            for($i = 0; $i < count($DownloadCount_header); $i++)
                $tcpdf->Cell($w[$i], 7, $DownloadCount_header[$i], 1, 0, 'C', 1);
                $tcpdf->Ln();
        }
        // Color and font restoration
        $tcpdf->SetFillColor(224, 235, 255);
        $tcpdf->SetTextColor(0);
        $tcpdf->SetFont('');
        
        $tcpdf->Cell($w[0], 6, number_format($row[0]), 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[2], 6, $row[2], 'LR', 0, 'C', $fill, '', 3);
        $tcpdf->Ln();
        $fill=!$fill;
    }
    
    $tcpdf->Cell(array_sum($w), 0, '', 'T');	
    
    // add a page
    $tcpdf->AddPage();
    
    //Column titles
    $header = array('','Library Name', 'Patron ID', 'Artists Name', 'Track title', 'Download');
    $patron_header = array('', 'Patron ID', 'Library Name', 'Total Number of Tracks Downloaded');
    $genre_header = array('', 'Genre Name', 'Total Number of Tracks Downloaded');
    
    //Data loading
    foreach($downloads as $key => $download) {
		if($download['Download']['email']!=''){
			$patron = $download['Download']['email'];
		}
		else{
			$patron = $download['Download']['patron_id'];
		}
        $libraryName = $library->getLibraryName($download['Download']['library_id']);
        $data[] = array($key+1, $libraryName, $patron, $download['Download']['artist'], $download['Download']['track_title'], date('Y-m-d', strtotime($download['Download']['created'])));
    }
    
    foreach($patronDownloads as $key => $patronDownload) {
		if($patronDownload['Download']['email']!=''){
			$patron_id = $patronDownload['Download']['email'];
		}
		else{
			$patron_id = $patronDownload['Download']['patron_id'];
		}
        $patron_data[] = array($key+1, $patron_id, $library->getLibraryName($patronDownload['Download']['library_id']), $patronDownload[0]['totalDownloads']);
    }
    
    foreach($genreDownloads as $key => $genreDownload) {
        $genre_data[] = array($key+1, $genreDownload['Genre']['Genre'], $genreDownload[0]['totalProds']);
    }
    
    // print colored table
    // Colors, line width and bold font
    $tcpdf->SetTextColor(0);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    $tcpdf->Cell(250, 7, 'Library Downloads Report', 0, 0, 'C', 0);
    $tcpdf->Ln();
    
    $tcpdf->SetFillColor(0, 153, 255);
    $tcpdf->SetTextColor(255);
    $tcpdf->SetDrawColor(224, 224, 224);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    // Header
    $w = array(10, 50, 40, 60, 80, 20);
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
        if($k%27 == 0 && $k != 0) {
            $tcpdf->SetTextColor(0);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            $tcpdf->Cell(250, 7, 'Library Downloads Report', 0, 0, 'C', 0);
            $tcpdf->Ln();
            
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
        // Color and font restoration
        $tcpdf->SetFillColor(224, 235, 255);
        $tcpdf->SetTextColor(0);
        $tcpdf->SetFont('');
        
        $tcpdf->Cell($w[0], 6, number_format($row[0]), 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[2], 6, $row[2], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[3], 6, $row[3], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[4], 6, $row[4], 'LR', 0, 'L', $fill, '', 3);
	$tcpdf->Cell($w[5], 6, $row[5], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Ln();
        $fill=!$fill;
    }
    
    // add a page
    $tcpdf->AddPage();
    
    $tcpdf->SetTextColor(0);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    $tcpdf->Cell(250, 7, 'Patron Downloads Report', 0, 0, 'C', 0);
    $tcpdf->Ln();
    
    $tcpdf->SetFillColor(0, 153, 255);
    $tcpdf->SetTextColor(255);
    $tcpdf->SetDrawColor(224, 224, 224);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    // Header
    $w = array(10, 50, 100, 90);
    for($i = 0; $i < count($patron_header); $i++)
        $tcpdf->Cell($w[$i], 7, $patron_header[$i], 1, 0, 'C', 1);
        $tcpdf->Ln();
    // Color and font restoration
    $tcpdf->SetFillColor(224, 235, 255);
    $tcpdf->SetTextColor(0);
    $tcpdf->SetFont('');
    // Data
    $fill = 0;
    foreach($patron_data as $k=>$row) {
        if($k%27 == 0 && $k != 0) {
            $tcpdf->SetTextColor(0);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            $tcpdf->Cell(250, 7, 'Patron Downloads Report', 0, 0, 'C', 0);
            $tcpdf->Ln();
            
            // Colors, line width and bold font
            $tcpdf->SetFillColor(0, 153, 255);
            $tcpdf->SetTextColor(255);
            $tcpdf->SetDrawColor(224, 224, 224);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            // Header
            for($i = 0; $i < count($patron_header); $i++)
                $tcpdf->Cell($w[$i], 7, $patron_header[$i], 1, 0, 'C', 1);
                $tcpdf->Ln();
        }
        // Color and font restoration
        $tcpdf->SetFillColor(224, 235, 255);
        $tcpdf->SetTextColor(0);
        $tcpdf->SetFont('');
        
        $tcpdf->Cell($w[0], 6, number_format($row[0]), 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[2], 6, $row[2], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[3], 6, $row[3], 'LR', 0, 'C', $fill, '', 3);
        $tcpdf->Ln();
        $fill=!$fill;
    }
    
    // add a page
    $tcpdf->AddPage();
    
    $tcpdf->SetTextColor(0);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    $tcpdf->Cell(250, 7, 'Genres Downloads Report', 0, 0, 'C', 0);
    $tcpdf->Ln();
    
    $tcpdf->SetFillColor(0, 153, 255);
    $tcpdf->SetTextColor(255);
    $tcpdf->SetDrawColor(224, 224, 224);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    // Header
    $w = array(10, 50, 190);
    for($i = 0; $i < count($genre_header); $i++)
        $tcpdf->Cell($w[$i], 7, $genre_header[$i], 1, 0, 'C', 1);
        $tcpdf->Ln();
    // Color and font restoration
    $tcpdf->SetFillColor(224, 235, 255);
    $tcpdf->SetTextColor(0);
    $tcpdf->SetFont('');
    // Data
    $fill = 0;
    foreach($genre_data as $k=>$row) {
        if($k%27 == 0 && $k != 0) {
            $tcpdf->SetTextColor(0);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            $tcpdf->Cell(250, 7, 'Genres Downloads Report', 0, 0, 'C', 0);
            $tcpdf->Ln();
            
            // Colors, line width and bold font
            $tcpdf->SetFillColor(0, 153, 255);
            $tcpdf->SetTextColor(255);
            $tcpdf->SetDrawColor(224, 224, 224);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            // Header
            for($i = 0; $i < count($genre_header); $i++)
                $tcpdf->Cell($w[$i], 7, $genre_header[$i], 1, 0, 'C', 1);
                $tcpdf->Ln();
        }
        // Color and font restoration
        $tcpdf->SetFillColor(224, 235, 255);
        $tcpdf->SetTextColor(0);
        $tcpdf->SetFont('');
        
        $tcpdf->Cell($w[0], 6, number_format($row[0]), 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[2], 6, $row[2], 'LR', 0, 'C', $fill, '', 3);
        $tcpdf->Ln();
        $fill=!$fill;
    }
    
    $tcpdf->Cell(array_sum($w), 0, '', 'T');
    
    echo $tcpdf->Output('DownloadsReport_'.$savelibraryName.$savedateRange.'.pdf', 'D');
?>
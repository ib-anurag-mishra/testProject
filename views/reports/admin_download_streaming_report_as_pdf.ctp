<?php
    App::import('Vendor','xtcpdf');
    $tcpdf = new XTCPDF('L', 'mm', 'LETTER', true, 'UTF-8', false);
    $textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans'

    if($this->data['Report']['library_id'] == "all") {
        $savelibraryName = "All_Libraries";
        $displaylibraryName = "All Libraries";
    }
    else {       
        $savelibraryName =  $library->getLibraryName($library_id);
        $displaylibraryName = "LibraryID ".$library_id;
    }
    $date_arr = explode("/", $this->data['Report']['date']);
    $date_arr_from = explode("/", $this->data['Report']['date_from']);
    $date_arr_to = explode("/", $this->data['Report']['date_to']);
    if($this->data['Report']['reports_daterange'] == 'day') {
        $savedateRange = "_for_".$date_arr[2]."-".$date_arr[0]."-".$date_arr[1];
        $displaydateRange = " for ".$date_arr[2]."-".$date_arr[0]."-".$date_arr[1];
    }elseif($this->data['Report']['reports_daterange'] == 'week') {
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
    $tcpdf->SetTitle('Streaming Report');
    $tcpdf->SetSubject('Library Streaming Report');
    $tcpdf->SetKeywords('Library, Streaming, Report');

    //set auto page breaks
    $tcpdf->SetAutoPageBreak(TRUE, "20");

    // set default header data
    // set header and footer fonts
    $tcpdf->setHeaderFont(array($textfont,'',12));
    $tcpdf->xheadertext = 'Libraries/Patrons Streaming Report for '.$savelibraryName.$displaydateRange;
    $tcpdf->xfootertext = 'Copyright � %d FreegalMusic.com. All rights reserved.';

    //set margins
    $tcpdf->SetMargins("10", "15", "0");
    $tcpdf->SetHeaderMargin("10");
    $tcpdf->SetFooterMargin("10");

    // ---------------------------------------------------------
      
    // set font
    $tcpdf->SetFont('helvetica', '', 8);

        
     
 
    // start - Library Remaining Streaming
    
    // add a page
    $tcpdf->AddPage();

    // Data
    $fill = 0;
	
    $key = 1;

    // end - Library Remaining Downloads  
      
    //----------------------------------------------------------------------------------------------  
    
    // start - Total Downloads during Reporting Period
    
 
    if(!is_array($streamingHours)) {
       
    // add a page
    $tcpdf->AddPage();
        
    $tcpdf->SetTextColor(0);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    $tcpdf->Cell(250, 7, 'Total Songs Streamed', 0, 0, 'C', 0);
    $tcpdf->Ln();

    $tcpdf->SetFillColor(0, 153, 255);
    $tcpdf->SetTextColor(255);
    $tcpdf->SetDrawColor(224, 224, 224);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    
    // Header
    $w = array(250);
    $StreamedCount_header = array('Total Streamed (Number of Songs)');
    for($i = 0; $i < count($StreamedCount_header); $i++)
        $tcpdf->Cell($w[$i], 7, $StreamedCount_header[$i], 1, 0, 'C', 1);
        $tcpdf->Ln();
    // Color and font restoration
    $tcpdf->SetFillColor(224, 235, 255);
    $tcpdf->SetTextColor(0);
    $tcpdf->SetFont('');
    // Data
    $fill = 0;
	
 
    $arr_all_library_streaming_data[] = array($streamingHours);

    
    foreach($arr_all_library_streaming_data as $k=>$row) {
        if($k%27 == 0 && $k != 0) {
            $tcpdf->SetTextColor(0);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            $tcpdf->Cell(250, 7, 'Total Songs Streamed', 0, 0, 'C', 0);
            $tcpdf->Ln();

            // Colors, line width and bold font
            $tcpdf->SetFillColor(0, 153, 255);
            $tcpdf->SetTextColor(255);
            $tcpdf->SetDrawColor(224, 224, 224);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            // Header
            for($i = 0; $i < count($StreamedCount_header); $i++)
                $tcpdf->Cell($w[$i], 7, $StreamedCount_header[$i], 1, 0, 'C', 1);
                $tcpdf->Ln();
        }
        // Color and font restoration
        $tcpdf->SetFillColor(224, 235, 255);
        $tcpdf->SetTextColor(0);
        $tcpdf->SetFont('');

        $tcpdf->Cell($w[0], 6, number_format($row[0]), 'LR', 0, 'C', $fill, '', 3);
        $tcpdf->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[2], 6, $row[2], 'LR', 0, 'C', $fill, '', 3);
        $tcpdf->Ln();
        $fill=!$fill;
    }

    $tcpdf->Cell(array_sum($w), 0, '', 'T');
    
    } else {
    
    
    // add a page
    $tcpdf->AddPage();
        
    $tcpdf->SetTextColor(0);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    $tcpdf->Cell(250, 7, 'Total Songs Streamed', 0, 0, 'C', 0);
    $tcpdf->Ln();

    $tcpdf->SetFillColor(0, 153, 255);
    $tcpdf->SetTextColor(255);
    $tcpdf->SetDrawColor(224, 224, 224);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    
    // Header
    $w = array(10, 50, 190);
    $StreamingCount_header = array('', 'Library Name', 'Total Streamed (Number of Songs)');
    for($i = 0; $i < count($StreamingCount_header); $i++)
        $tcpdf->Cell($w[$i], 7, $StreamingCount_header[$i], 1, 0, 'C', 1);
        $tcpdf->Ln();
    // Color and font restoration
    $tcpdf->SetFillColor(224, 235, 255);
    $tcpdf->SetTextColor(0);
    $tcpdf->SetFont('');
    // Data
    $fill = 0;
	
 
    $key = 1;
    foreach($streamingHours as $LibraryName => $DownloadCount) {
      
      $arr_all_library_streming_data[] = array($key, $DownloadCount['lib']['library_name'], $DownloadCount['0']['total_count']);
      $key++;
    }
    
    foreach($arr_all_library_streming_data as $k=>$row) {
        if($k%27 == 0 && $k != 0) {
            $tcpdf->SetTextColor(0);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            $tcpdf->Cell(250, 7, 'Total Streamed during Reporting Period', 0, 0, 'C', 0);
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
    }
    
    // end - Total Downloads during Reporting Period 
    
    //------------------------------------------------------------------------------------------------
    
    // start - Total Number of Patrons who have downloaded during Reporting Period
    
    if(!is_array($patronStreamedInfo)) {

    // add a page
    $tcpdf->AddPage();
        
    $tcpdf->SetTextColor(0);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    $tcpdf->Cell(250, 7, 'Total Patrons', 0, 0, 'C', 0);
    $tcpdf->Ln();

    $tcpdf->SetFillColor(0, 153, 255);
    $tcpdf->SetTextColor(255);
    $tcpdf->SetDrawColor(224, 224, 224);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    
    // Header
    $w = array(250);
    $StreamingCount_header = array('Total Number of Patrons who have streamed during Reporting Period');
    for($i = 0; $i < count($StreamingCount_header); $i++)
        $tcpdf->Cell($w[$i], 7, $StreamingCount_header[$i], 1, 0, 'C', 1);
        $tcpdf->Ln();
    // Color and font restoration
    $tcpdf->SetFillColor(224, 235, 255);
    $tcpdf->SetTextColor(0);
    $tcpdf->SetFont('');
    // Data
    $fill = 0;
  
    $arr_all_patron_streaming_data[] = array($patronStreamedInfo);

    
    foreach($arr_all_patron_streaming_data as $k=>$row) {
        if($k%27 == 0 && $k != 0) {
            $tcpdf->SetTextColor(0);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            $tcpdf->Cell(250, 7, 'Total Number of Patrons who have streamed during Reporting Period', 0, 0, 'C', 0);
            $tcpdf->Ln();

            // Colors, line width and bold font
            $tcpdf->SetFillColor(0, 153, 255);
            $tcpdf->SetTextColor(255);
            $tcpdf->SetDrawColor(224, 224, 224);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            // Header
            for($i = 0; $i < count($StreamingCount_header); $i++)
                $tcpdf->Cell($w[$i], 7, $StreamingCount_header[$i], 1, 0, 'C', 1);
                $tcpdf->Ln();
        }
        // Color and font restoration
        $tcpdf->SetFillColor(224, 235, 255);
        $tcpdf->SetTextColor(0);
        $tcpdf->SetFont('');

        $tcpdf->Cell($w[0], 6, number_format($row[0]), 'LR', 0, 'C', $fill, '', 3);
        $tcpdf->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[2], 6, $row[2], 'LR', 0, 'C', $fill, '', 3);
        $tcpdf->Ln();
        $fill=!$fill;
    }

    $tcpdf->Cell(array_sum($w), 0, '', 'T');
    
    } else {
    
    // add a page
    $tcpdf->AddPage();
        
    $tcpdf->SetTextColor(0);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    $tcpdf->Cell(250, 7, 'Total Number of Patrons who have streamed during Reporting Period', 0, 0, 'C', 0);
    $tcpdf->Ln();

    $tcpdf->SetFillColor(0, 153, 255);
    $tcpdf->SetTextColor(255);
    $tcpdf->SetDrawColor(224, 224, 224);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    
    // Header
    $w = array(10, 50, 190);
    $StreamingCount_header = array('', 'Library Name', 'Total Patrons');
    for($i = 0; $i < count($StreamingCount_header); $i++)
        $tcpdf->Cell($w[$i], 7, $StreamingCount_header[$i], 1, 0, 'C', 1);
        $tcpdf->Ln();
    // Color and font restoration
    $tcpdf->SetFillColor(224, 235, 255);
    $tcpdf->SetTextColor(0);
    $tcpdf->SetFont('');
    // Data
    $fill = 0;
    
    
    $key = 1;
    foreach($patronStreamedInfo as $LibraryName => $StreamedCount) {
      
      $arr_all_patron_streamed_data[] = array($key, $this->getAdminTextEncode($StreamedCount['lib']['library_name']), $StreamedCount['0']['total_patrons']);
      $key++;
    }
    
    foreach($arr_all_patron_streamed_data as $k=>$row) {
        if($k%27 == 0 && $k != 0) {
            $tcpdf->SetTextColor(0);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            $tcpdf->Cell(250, 7, 'Total Number of Patrons who have streamed during Reporting Period', 0, 0, 'C', 0);
            $tcpdf->Ln();

            // Colors, line width and bold font
            $tcpdf->SetFillColor(0, 153, 255);
            $tcpdf->SetTextColor(255);
            $tcpdf->SetDrawColor(224, 224, 224);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            // Header
            for($i = 0; $i < count($StreamedCount_header); $i++)
                $tcpdf->Cell($w[$i], 7, $StreamedCount_header[$i], 1, 0, 'C', 1);
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
    
    }
    // end - Total Number of Patrons who have streamed during Reporting Period
     
    //------------------------------------------------------------------------------------------------------ 
     
    // add a page
    $tcpdf->AddPage();

    //Column titles
    if($this->data['Report']['library_id'] == "all") {
        $header = array('','Library Name', 'Patron ID', 'Artists Name', 'Track title', 'Streamed date');
        $patron_header = array('', 'Patron ID', 'Library Name', 'Total Number of Tracks Streamed');
    }else{
        $header = array('', 'Patron ID', 'Artists Name', 'Track title', 'Streamed date');
        $patron_header = array('', 'Patron ID', 'Total Number of Tracks Streamed');
    }
    $genre_header = array('', 'Genre Name', 'Total Number of Tracks Streamed');


    //Data loading
    if($this->data['Report']['library_id'] == "all") {
        foreach($dayStreamingInfo as $key => $stream) {
                    if($stream['StreamingHistory']['patron_id']!=''){
                            $patron = $stream['StreamingHistory']['patron_id'];
                    }
            $libraryName = $this->getAdminTextEncode($library->getLibraryName($stream['StreamingHistory']['library_id']));
            $data[] = array($key+1, $libraryName, $patron, $this->getAdminTextEncode($stream['songs']['artist']), $this->getAdminTextEncode($stream['songs']['track_title']), date('Y-m-d', strtotime($stream['songs']['createdOn'])));
        }
    }else{
        foreach($dayStreamingInfo as $key => $stream) {
                    if($stream['StreamingHistory']['patron_id']!=''){
                            $patron = $stream['StreamingHistory']['patron_id'];
                    }

            $data[] = array($key+1, $patron, $this->getAdminTextEncode($stream['songs']['artist']), $this->getAdminTextEncode($stream['songs']['track_title']), date('Y-m-d', strtotime($stream['songs']['createdOn'])));
        }
    }

    if($this->data['Report']['library_id'] == "all") {
        foreach($patronStreamedDetailedInfo as $key => $patronStreamed) {
                    if($patronStreamed['StreamingHistory']['patron_id']!=''){
                            $patron_id = $patronStreamed['StreamingHistory']['patron_id'];
                    }
            $patron_data[] = array($key+1, $patron_id, $this->getAdminTextEncode($library->getLibraryName($patronStreamed['StreamingHistory']['library_id'])), ($patronStreamed[0]['total_streamed_songs']));
        }
    }else{
        foreach($patronStreamedDetailedInfo as $key => $patronStreamed) {
                    if($patronStreamed['StreamingHistory']['patron_id']!=''){
                            $patron_id = $patronStreamed['StreamingHistory']['patron_id'];
                    }
            $patron_data[] = array($key+1, $patron_id, ($patronStreamed[0]['total_streamed_songs']));
        }
    }
    foreach($genreDayStremedInfo as $key => $genreStreamed) {
        $genre_data[] = array($key+1, $this->getAdminTextEncode($genreStreamed['songs']['Genre']), ($genreStreamed[0]['total_streamed_songs']));
    }
    
    // print colored table
    // Colors, line width and bold font
    $tcpdf->SetTextColor(0);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    $tcpdf->Cell(250, 7, 'Library Streaming Report', 0, 0, 'C', 0);
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
        if($k%13 == 0 && $k != 0) {
            $tcpdf->SetTextColor(0);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            $tcpdf->Cell(250, 7, 'Library Streaming Report', 0, 0, 'C', 0);
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
        
        $tcpdf->MultiCell($w[0], 12.5, number_format($row[0]), 'LR', 'L',  $fill, 0);
        $tcpdf->MultiCell($w[1], 12.5, $row[1], 'LR', 'L',  $fill, 0);
        $tcpdf->MultiCell($w[2], 12.5, $row[2], 'LR', 'L',  $fill, 0);
        $tcpdf->MultiCell($w[3], 12.5, $row[3], 'LR', 'L',  $fill, 0);
        $tcpdf->MultiCell($w[4], 12.5, $row[4], 'LR', 'L',  $fill, 0);
        if($this->data['Report']['library_id'] == "all") {
            $tcpdf->MultiCell($w[5], 12.5, $row[5], 'LR', 'L',  $fill, 0);
        }        
        $tcpdf->Ln();
        $fill=!$fill;
    }
     // add a page
    
    $tcpdf->AddPage();

    $tcpdf->SetTextColor(0);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    $tcpdf->Cell(250, 7, 'Patron Streaming Report', 0, 0, 'C', 0);
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
            $tcpdf->Cell(250, 7, 'Patron Streaming Report', 0, 0, 'C', 0);
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
        if($this->data['Report']['library_id'] == "all") {
            $tcpdf->Cell($w[3], 6, $row[3], 'LR', 0, 'C', $fill, '', 3);
        }
        $tcpdf->Ln();
        $fill=!$fill;
    }

    // add a page
    $tcpdf->AddPage();

    $tcpdf->SetTextColor(0);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    $tcpdf->Cell(250, 7, 'Genres Streaming Report', 0, 0, 'C', 0);
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
            $tcpdf->Cell(250, 7, 'Genres Streaming Report', 0, 0, 'C', 0);
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

    echo $tcpdf->Output('StreamingReport_'.str_replace(" ", "_", $savelibraryName).$savedateRange.'.pdf', 'D');
?>
<?php
    App::import('Vendor','xtcpdf');
    $tcpdf = new XTCPDF('L', 'mm', 'LETTER', true, 'UTF-8', false);
    $textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans'

    if($this->data['Report']['library_id'] == "all") {
        $savelibraryName = "All_Libraries";
        $displaylibraryName = "All Libraries";
    }
    else {   
        $savelibraryName =  $libraries_download[0]['Library']['library_name'];
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
    $tcpdf->xheadertext = 'Libraries/Patrons Download Report for '.$savelibraryName.$displaydateRange;
    $tcpdf->xfootertext = 'Copyright � %d FreegalMusic.com. All rights reserved.';

    //set margins
    $tcpdf->SetMargins("10", "15", "0");
    $tcpdf->SetHeaderMargin("10");
    $tcpdf->SetFooterMargin("10");

    // ---------------------------------------------------------
      
    // set font
    $tcpdf->SetFont('helvetica', '', 8);

        
     
 
    // start - Library Remaining Downloads
    
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
        $libraries_downloads[] = array($key, $this->getAdminTextEncode($DownloadCount['Library']['library_name']), $text);
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
    
    // end - Library Remaining Downloads  
      
    //----------------------------------------------------------------------------------------------  
    
    // start - Total Downloads during Reporting Period
    
 
    if(empty($arr_all_library_downloads)) {
       
    // add a page
    $tcpdf->AddPage();
        
    $tcpdf->SetTextColor(0);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    $tcpdf->Cell(250, 7, 'Total Downloads during Reporting Period', 0, 0, 'C', 0);
    $tcpdf->Ln();

    $tcpdf->SetFillColor(0, 153, 255);
    $tcpdf->SetTextColor(255);
    $tcpdf->SetDrawColor(224, 224, 224);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    
    // Header
    $w = array(250);
    $DownloadCount_header = array('Total Downloads');
    for($i = 0; $i < count($DownloadCount_header); $i++)
        $tcpdf->Cell($w[$i], 7, $DownloadCount_header[$i], 1, 0, 'C', 1);
        $tcpdf->Ln();
    // Color and font restoration
    $tcpdf->SetFillColor(224, 235, 255);
    $tcpdf->SetTextColor(0);
    $tcpdf->SetFont('');
    // Data
    $fill = 0;
	
 
    $arr_all_library_downloads_data[] = array(count($downloads)+(count($videoDownloads)*2));

    
    foreach($arr_all_library_downloads_data as $k=>$row) {
        if($k%27 == 0 && $k != 0) {
            $tcpdf->SetTextColor(0);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            $tcpdf->Cell(250, 7, 'Total Downloads during Reporting Period', 0, 0, 'C', 0);
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
    $tcpdf->Cell(250, 7, 'Total Downloads during Reporting Period', 0, 0, 'C', 0);
    $tcpdf->Ln();

    $tcpdf->SetFillColor(0, 153, 255);
    $tcpdf->SetTextColor(255);
    $tcpdf->SetDrawColor(224, 224, 224);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    
    // Header
    $w = array(10, 50, 190);
    $DownloadCount_header = array('', 'Library Name', 'Total Downloads');
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
    foreach($arr_all_library_downloads as $LibraryName => $DownloadCount) {
      
      $arr_all_library_downloads_data[] = array($key, $LibraryName, $DownloadCount+($arr_all_video_library_downloads[$LibraryName]*2));
      $key++;
    }
    
    foreach($arr_all_library_downloads_data as $k=>$row) {
        if($k%27 == 0 && $k != 0) {
            $tcpdf->SetTextColor(0);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            $tcpdf->Cell(250, 7, 'Total Downloads during Reporting Period', 0, 0, 'C', 0);
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
    
    if(empty($arr_all_patron_downloads)) {

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
    $DownloadCount_header = array('Total Number of Patrons who have downloaded during Reporting Period');
    for($i = 0; $i < count($DownloadCount_header); $i++)
        $tcpdf->Cell($w[$i], 7, $DownloadCount_header[$i], 1, 0, 'C', 1);
        $tcpdf->Ln();
    // Color and font restoration
    $tcpdf->SetFillColor(224, 235, 255);
    $tcpdf->SetTextColor(0);
    $tcpdf->SetFont('');
    // Data
    $fill = 0;
  
    $arr_all_patron_downloads_data[] = array(count($patronBothDownloads));

    
    foreach($arr_all_patron_downloads_data as $k=>$row) {
        if($k%27 == 0 && $k != 0) {
            $tcpdf->SetTextColor(0);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            $tcpdf->Cell(250, 7, 'Total Number of Patrons who have downloaded during Reporting Period', 0, 0, 'C', 0);
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
    $tcpdf->Cell(250, 7, 'Total Number of Patrons who have downloaded during Reporting Period', 0, 0, 'C', 0);
    $tcpdf->Ln();

    $tcpdf->SetFillColor(0, 153, 255);
    $tcpdf->SetTextColor(255);
    $tcpdf->SetDrawColor(224, 224, 224);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    
    // Header
    $w = array(10, 50, 190);
    $DownloadCount_header = array('', 'Library Name', 'Total Patrons');
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
    foreach($arr_all_patron_downloads as $LibraryName => $DownloadCount) {
      
      $arr_all_patron_downloads_data[] = array($key, $this->getAdminTextEncode($LibraryName), $DownloadCount);
      $key++;
    }
    
    foreach($arr_all_patron_downloads_data as $k=>$row) {
        if($k%27 == 0 && $k != 0) {
            $tcpdf->SetTextColor(0);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            $tcpdf->Cell(250, 7, 'Total Number of Patrons who have downloaded during Reporting Period', 0, 0, 'C', 0);
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
    // end - Total Number of Patrons who have downloaded during Reporting Period
     
    //------------------------------------------------------------------------------------------------------ 
     
    // add a page
    $tcpdf->AddPage();

    //Column titles
    $header = array('','Library Name', 'Patron ID', 'Artists Name', 'Track title', 'Download');
    $video_header = array('','Library Name', 'Patron ID', 'Artists Name', 'Video title', 'Download');
    $patron_header = array('', 'Patron ID', 'Library Name', 'Total Number of Tracks Downloaded');
    $patron_video_header = array('', 'Patron ID', 'Library Name', 'Total Number of Videos Downloaded');
    $genre_header = array('', 'Genre Name', 'Total Number of Tracks Downloaded');
    $genre_video_header = array('', 'Genre Name', 'Total Number of Videos Downloaded');

    //Data loading
    foreach($downloads as $key => $download) {
	$patron = $download['Currentpatrons']['id'];
        $libraryName = $this->getAdminTextEncode($library->getLibraryName($download['Download']['library_id']));
        $data[] = array($key+1, $libraryName, $patron, $this->getAdminTextEncode($download['Download']['artist']), $this->getAdminTextEncode($download['Download']['track_title']), date('Y-m-d', strtotime($download['Download']['created'])));
    }
    foreach($videoDownloads as $key => $download) {
	$patron = $download['Currentpatrons']['id'];
        $libraryName = $library->getLibraryName($download['Videodownload']['library_id']);
        $video_data[] = array($key+1, $this->getAdminTextEncode($libraryName), $patron, $this->getAdminTextEncode($download['Videodownload']['artist']), $this->getAdminTextEncode($download['Videodownload']['track_title']), date('Y-m-d', strtotime($download['Videodownload']['created'])));
    }

    foreach($patronDownloads as $key => $patronDownload) {
	$patron_id = $patronDownload['Currentpatrons']['id'];
        $patron_data[] = array($key+1, $patron_id, $this->getAdminTextEncode($library->getLibraryName($patronDownload['Downloadpatron']['library_id'])), (($dataRange == 'day')?$patronDownload['Downloadpatron']['total']:$patronDownload[0]['total']));
    }
    
    foreach($patronVideoDownloads as $key => $patronDownload) {
	$patron_id = $patronDownload['Currentpatrons']['id'];
        $patron_video_data[] = array($key+1, $patron_id, $this->getAdminTextEncode($library->getLibraryName($patronDownload['DownloadVideoPatron']['library_id'])), (($dataRange == 'day')?$patronDownload['DownloadVideoPatron']['total']:$patronDownload[0]['total']));
    }    

    foreach($genreDownloads as $key => $genreDownload) {
        $genre_data[] = array($key+1, $this->getAdminTextEncode($genreDownload['Downloadgenre']['genre_name']), (($dataRange == 'day')?$genreDownload['Downloadgenre']['total']:$genreDownload[0]['total']));
    }
    
    foreach($genreVideoDownloads as $key => $genreDownload) {
        $genre_video_data[] = array($key+1, $this->getAdminTextEncode($genreDownload['DownloadVideoGenre']['genre_name']), (($dataRange == 'day')?$genreDownload['DownloadVideoGenre']['total']:$genreDownload[0]['total']));
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
        if($k%13 == 0 && $k != 0) {
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
        
        $tcpdf->MultiCell($w[0], 12.5, number_format($row[0]), 'LR', 'L',  $fill, 0);
        $tcpdf->MultiCell($w[1], 12.5, $row[1], 'LR', 'L',  $fill, 0);
        $tcpdf->MultiCell($w[2], 12.5, $row[2], 'LR', 'L',  $fill, 0);
        $tcpdf->MultiCell($w[3], 12.5, $row[3], 'LR', 'L',  $fill, 0);
        $tcpdf->MultiCell($w[4], 12.5, $row[4], 'LR', 'L',  $fill, 0);
        $tcpdf->MultiCell($w[5], 12.5, $row[5], 'LR', 'L',  $fill, 0);
        
        $tcpdf->Ln();
        $fill=!$fill;
    }
     // add a page
    $tcpdf->AddPage();
    // print colored table
    // Colors, line width and bold font
    $tcpdf->SetTextColor(0);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    $tcpdf->Cell(250, 7, 'Library Video Downloads Report', 0, 0, 'C', 0);
    $tcpdf->Ln();

    $tcpdf->SetFillColor(0, 153, 255);
    $tcpdf->SetTextColor(255);
    $tcpdf->SetDrawColor(224, 224, 224);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    // Header
    $w = array(10, 50, 40, 60, 80, 20);
    for($i = 0; $i < count($video_header); $i++)
        $tcpdf->Cell($w[$i], 7, $video_header[$i], 1, 0, 'C', 1);
        $tcpdf->Ln();
    // Color and font restoration
    $tcpdf->SetFillColor(224, 235, 255);
    $tcpdf->SetTextColor(0);
    $tcpdf->SetFont('');
    // Data
    $fill = 0;
    foreach($video_data as $k=>$row) {
        if($k%13 == 0 && $k != 0) {
            $tcpdf->SetTextColor(0);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            $tcpdf->Cell(250, 7, 'Library Video Downloads Report', 0, 0, 'C', 0);
            $tcpdf->Ln();

            // Colors, line width and bold font
            $tcpdf->SetFillColor(0, 153, 255);
            $tcpdf->SetTextColor(255);
            $tcpdf->SetDrawColor(224, 224, 224);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            // Header
            for($i = 0; $i < count($video_header); $i++)
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
        $tcpdf->MultiCell($w[5], 12.5, $row[5], 'LR', 'L',  $fill, 0);
        
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
    $tcpdf->Cell(250, 7, 'Patron Video Downloads Report', 0, 0, 'C', 0);
    $tcpdf->Ln();

    $tcpdf->SetFillColor(0, 153, 255);
    $tcpdf->SetTextColor(255);
    $tcpdf->SetDrawColor(224, 224, 224);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    // Header
    $w = array(10, 50, 100, 90);
    for($i = 0; $i < count($patron_video_header); $i++)
        $tcpdf->Cell($w[$i], 7, $patron_video_header[$i], 1, 0, 'C', 1);
        $tcpdf->Ln();
    // Color and font restoration
    $tcpdf->SetFillColor(224, 235, 255);
    $tcpdf->SetTextColor(0);
    $tcpdf->SetFont('');
    // Data
    $fill = 0;
    foreach($patron_video_data as $k=>$row) {
        if($k%27 == 0 && $k != 0) {
            $tcpdf->SetTextColor(0);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            $tcpdf->Cell(250, 7, 'Patron Video Downloads Report', 0, 0, 'C', 0);
            $tcpdf->Ln();

            // Colors, line width and bold font
            $tcpdf->SetFillColor(0, 153, 255);
            $tcpdf->SetTextColor(255);
            $tcpdf->SetDrawColor(224, 224, 224);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            // Header
            for($i = 0; $i < count($patron_video_header); $i++)
                $tcpdf->Cell($w[$i], 7, $patron_video_header[$i], 1, 0, 'C', 1);
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

    
    // add a page
    $tcpdf->AddPage();

    $tcpdf->SetTextColor(0);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    $tcpdf->Cell(250, 7, 'Genres Video Downloads Report', 0, 0, 'C', 0);
    $tcpdf->Ln();

    $tcpdf->SetFillColor(0, 153, 255);
    $tcpdf->SetTextColor(255);
    $tcpdf->SetDrawColor(224, 224, 224);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    // Header
    $w = array(10, 50, 190);
    for($i = 0; $i < count($genre_video_header); $i++)
        $tcpdf->Cell($w[$i], 7, $genre_video_header[$i], 1, 0, 'C', 1);
        $tcpdf->Ln();
    // Color and font restoration
    $tcpdf->SetFillColor(224, 235, 255);
    $tcpdf->SetTextColor(0);
    $tcpdf->SetFont('');
    // Data
    $fill = 0;
    foreach($genre_video_data as $k=>$row) {
        if($k%27 == 0 && $k != 0) {
            $tcpdf->SetTextColor(0);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            $tcpdf->Cell(250, 7, 'Genres Video Downloads Report', 0, 0, 'C', 0);
            $tcpdf->Ln();

            // Colors, line width and bold font
            $tcpdf->SetFillColor(0, 153, 255);
            $tcpdf->SetTextColor(255);
            $tcpdf->SetDrawColor(224, 224, 224);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            // Header
            for($i = 0; $i < count($genre_video_header); $i++)
                $tcpdf->Cell($w[$i], 7, $genre_video_header[$i], 1, 0, 'C', 1);
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

    echo $tcpdf->Output('DownloadsReport_'.str_replace(" ", "_", $savelibraryName).$savedateRange.'.pdf', 'D');
?>
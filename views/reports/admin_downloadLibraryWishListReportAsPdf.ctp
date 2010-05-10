<?php
    App::import('Vendor','xtcpdf'); 
    $tcpdf = new XTCPDF('L', 'mm', 'LETTER', true, 'UTF-8', false);
    $textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans'
    
    if($this->data['Report']['library_id'] == "all") {
        $savelibraryName = "All_Libraries";
        $displaylibraryName = "All Libraries";
    }
    else {
        $savelibraryName = "LibraryID_".$wishlist['Download']['library_id'];
        $displaylibraryName = "LibraryID ".$wishlist['Download']['library_id'];
    }
    $date_arr = explode("/", $this->data['Report']['date']);
    $date_arr_from = explode("/", $this->data['Report']['date_from']);
    $date_arr_to = explode("/", $this->data['Report']['date_to']);
    if($this->data['Report']['reports_daterange'] == 'day') {
        $savedateRange = "_for_".$date_arr[2]."-".$date_arr[0]."-".$date_arr[1];
        $displaydateRange = " for ".$date_arr[2]."-".$date_arr[0]."-".$date_arr[1];
    }
    elseif($this->data['Report']['reports_daterange'] == 'week') {
        $startDate = date('Y-m-d', strtotime($date_arr[2]."W".date('W', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]))."1"));
        $endDate = date('Y-m-d', strtotime($date_arr[2]."W".date('W', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]))."7"));
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
    $tcpdf->SetTitle('Libraries WishList Report');
    $tcpdf->SetSubject('Libraries WishList Report');
    $tcpdf->SetKeywords('Library, WishList, Report');
    
    //set auto page breaks
    $tcpdf->SetAutoPageBreak(TRUE, "20");
    
    // set default header data
    // set header and footer fonts
    $tcpdf->setHeaderFont(array($textfont,'',12));
    $tcpdf->xheadertext = $displaylibraryName.' WishList Report generated on '.$displaydateRange;
    $tcpdf->xfootertext = 'Copyright � %d FreegalMusic.com. All rights reserved.';
    
    //set margins
    $tcpdf->SetMargins("10", "15", "0");
    $tcpdf->SetHeaderMargin("10");
    $tcpdf->SetFooterMargin("10");
    
    //set image scale factor
    //$tcpdf->setImageScale("1"); 
    
    // ---------------------------------------------------------
    
    // set font
    $tcpdf->SetFont('helvetica', '', 8);
    
    // add a page
    $tcpdf->AddPage();
    
    if($this->data['Report']['library_id'] == "all") {
        //Column titles
        $header = array('Library Name', 'Available Downloads', 'Download Limit Type', 'Download Limit', '# of Songs WishListed');
        
        //Data loading
        $totalSongs = 0;
        foreach($wishlists as $key => $wishlist) {
            $libraryDetails = $library->getLibraryDetails($wishlist['Wishlist']['library_id']);
            $data[] = array($libraryDetails['Library']['library_name'], $libraryDetails['Library']['library_available_downloads'], ucwords($libraryDetails['Library']['library_download_type']), $libraryDetails['Library']['library_download_limit'], $wishlist[0]['totalWishlistedSongs']);
            $totalSongs = $totalSongs+$wishlist[0]['totalWishlistedSongs'];
        }
        
        // print colored table
        // Colors, line width and bold font
        $tcpdf->SetFillColor(0, 153, 255);
        $tcpdf->SetTextColor(255);
        $tcpdf->SetDrawColor(224, 224, 224);
        $tcpdf->SetLineWidth(0.3);
        $tcpdf->SetFont('', 'B');
        // Header
        $w = array(60, 50, 50, 50, 50);
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
            $tcpdf->Cell($w[4], 6, $row[4], 'LR', 0, 'L', $fill, '', 3);
            $tcpdf->Cell($w[5], 6, $row[5], 'LR', 0, 'L', $fill, '', 3);
            $tcpdf->Ln();
            $fill=!$fill;
        }
        $tcpdf->Cell(260, 6, '', 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Ln();
        
        $tcpdf->SetFont('', 'B');
        $tcpdf->Cell(260, 6, 'Total Songs WishListed: '.$totalSongs, 'LR', 0, 'R', !$fill, '', 3);
        $tcpdf->Ln();
        
        $tcpdf->Cell(array_sum($w), 0, '', 'T');
        
        echo $tcpdf->Output('WishListsReport_'.$savelibraryName.$savedateRange.'.pdf', 'D');
    }
    else {
        $libraryDetails = $library->getLibraryDetails($this->data['Report']['library_id']);
        //Column titles
        $header = array('Library Name', 'Patron ID', 'Artists Name', 'Track Title', 'WishListed On');
        
        //Data loading
        foreach($wishlists as $key => $wishlist) {
            $data[] = array($libraryDetails['Library']['library_name'], $wishlist['Wishlist']['patron_id'], $wishlist['Wishlist']['artist'], $wishlist['Wishlist']['track_title'], date("Y-m-d", strtotime($wishlist['Wishlist']['created'])));
        }
        
        // print colored table
        // Colors, line width and bold font
        $tcpdf->SetFillColor(0, 153, 255);
        $tcpdf->SetTextColor(255);
        $tcpdf->SetDrawColor(224, 224, 224);
        $tcpdf->SetLineWidth(0.3);
        $tcpdf->SetFont('', 'B');
        // Header
        $w = array(60, 30, 70, 70, 30);
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
            $tcpdf->Cell($w[4], 6, $row[4], 'LR', 0, 'L', $fill, '', 3);
            $tcpdf->Cell($w[5], 6, $row[5], 'LR', 0, 'L', $fill, '', 3);
            $tcpdf->Ln();
            $fill=!$fill;
        }
        
        $tcpdf->Cell(260, 6, '', 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Ln();
        
        $tcpdf->SetFont('', 'B');
        $tcpdf->Cell(65, 6, 'Available Downloads: '.$libraryDetails['Library']['library_available_downloads'], 'LR', 0, 'C', !$fill, '', 3);
        $tcpdf->Cell(65, 6, 'Download Limit Type: '.ucwords($libraryDetails['Library']['library_download_type']), 'LR', 0, 'C', !$fill, '', 3);
        $tcpdf->Cell(65, 6, 'Download Limit: '.$libraryDetails['Library']['library_download_limit'], 'LR', 0, 'C', !$fill, '', 3);
        $tcpdf->Cell(65, 6, 'Total Songs WishListed: '.count($wishlists), 'LR', 0, 'C', !$fill, '', 3);
        $tcpdf->Ln();
        
        $tcpdf->Cell(array_sum($w), 0, '', 'T');
        
        echo $tcpdf->Output('WishListsReport_'.$savelibraryName.$savedateRange.'.pdf', 'D');
    }
?>
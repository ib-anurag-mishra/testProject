<?php
/*
 File Name : admin_unlimitedpdf.ctp
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
    $tcpdf->SetTitle('Downloads Report');
    $tcpdf->SetSubject('Library Downloads Report');
    $tcpdf->SetKeywords('Library, Downloads, Report');
    
    //set auto page breaks
    $tcpdf->SetAutoPageBreak(TRUE, "20");
    
    // set default header data
    // set header and footer fonts
    $tcpdf->setHeaderFont(array($textfont,'',12));
    $tcpdf->xheadertext = 'Libraries Download Report for ';
    $tcpdf->xfootertext = 'Copyright � %d FreegalMusic.com. All rights reserved.';
    
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
    $tcpdf->Cell(250, 7, 'Library Unlimited Library Repoprt', 0, 0, 'C', 0);
    $tcpdf->Ln();
    
    $tcpdf->SetFillColor(0, 153, 255);
    $tcpdf->SetTextColor(255);
    $tcpdf->SetDrawColor(224, 224, 224);
    $tcpdf->SetLineWidth(0.3);
    $tcpdf->SetFont('', 'B');
    // Header
    $w = array(15, 42, 42, 42, 42, 42, 42);
    $DownloadCount_header = array('', 'Library Name', $month.' Download', 'Annual Price', 'Monthly Price', 'Price per Download', 'Mechanical Royalty');	
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
	$downloads = 0;
	$libPrice = 0;
	$monPrice = 0;
	$dwldPrice = 0;
	$royalty = 0;
    foreach($downloadResult as $k => $v) {
		$libraries_downloads[] = array($key, $v['Download']['library_name'], $v['0']['totalDownloads'], "$".number_format($v['Download']['library_price'], 2), "$".number_format($v['Download']['monthly_price'], 2), "$".number_format($v['Download']['download_price'], 2), "$".number_format($v['Download']['mechanical_royalty'], 2));
		$downloads = $downloads + $v['0']['totalDownloads'];
		$libPrice = $libPrice + $v['Download']['library_price'];
		$monPrice = $monPrice + $v['Download']['monthly_price'];
		$dwldPrice = $dwldPrice + $v['Download']['download_price'];
		$royalty = $royalty + $v['Download']['mechanical_royalty'];
		$key++;
    }
	$libraries_downloads[] =  array('', 'Total', $downloads, "$".number_format($libPrice, 2), "$".number_format($monPrice, 2), "$".number_format(($monPrice/$downloads), 2), "$".number_format($royalty, 2));
    foreach($libraries_downloads as $k=>$row) {
        if($k%27 == 0 && $k != 0) {
            $tcpdf->SetTextColor(0);
            $tcpdf->SetLineWidth(0.3);
            $tcpdf->SetFont('', 'B');
            $tcpdf->Cell(250, 7, 'Library Unlimited Library Repoprt', 0, 0, 'C', 0);
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
        
        $tcpdf->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[2], 6, $row[2], 'LR', 0, 'C', $fill, '', 3);
        $tcpdf->Cell($w[3], 6, $row[3], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[4], 6, $row[4], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[5], 6, $row[5], 'LR', 0, 'C', $fill, '', 3);
        $tcpdf->Cell($w[6], 6, $row[6], 'LR', 0, 'L', $fill, '', 3);
        $tcpdf->Cell($w[7], 6, $row[7], 'LR', 0, 'L', $fill, '', 3);		
        $tcpdf->Ln();
        $fill=!$fill;
    }
    $tcpdf->Cell(array_sum($w), 0, '', 'T');
    echo $tcpdf->Output('LibraryUnlimitedLibraryRepoprt', 'D');
?>
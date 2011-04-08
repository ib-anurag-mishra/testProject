<?php
$line = array('Unlimited Library Downloads');
$csv->addRow($line);

$line = array('', 'Library Name', $month.' Download', 'Annual Price', 'Monthly Price', 'Price per Download', 'Mechanical Royalty');
$csv->addRow($line);
$key=1;
$downloads = 0;
$libPrice = 0;
$monPrice = 0;
$dwldPrice = 0;
$royalty = 0;
foreach($downloadResult as $k => $v) {
    $line = array($key, $v['Download']['library_name'], $v['0']['totalDownloads'], "$".number_format($v['Download']['library_price'], 2), "$".number_format($v['Download']['monthly_price'], 2), "$".number_format($v['Download']['download_price'], 2), "$".number_format($v['Download']['mechanical_royalty'], 2));
	$downloads = $downloads + $v['0']['totalDownloads'];
	$libPrice = $libPrice + $v['Download']['library_price'];
	$monPrice = $monPrice + $v['Download']['monthly_price'];
	$dwldPrice = $dwldPrice + $v['Download']['download_price'];
	$royalty = $royalty + $v['Download']['mechanical_royalty'];
    $csv->addRow($line);
	$key++;
}
$line = array('', '', '', '', '', '');
$csv->addRow($line);
$line = array('', 'Total', $downloads, "$".number_format($libPrice, 2), "$".number_format($monPrice, 2), "$".number_format($dwldPrice, 2), "$".number_format($royalty, 2));
$csv->addRow($line);
echo $csv->render('MonthlyUnlimitedLibraryDownload');
?>
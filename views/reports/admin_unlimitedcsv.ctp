<?php
$line = array('Unlimited Library Downloads');
$csv->addRow($line);

$line = array('', 'Library Name', 'Monthly Download', 'Annual Price', 'Monthly Price', 'Price per Download', 'Mechanical Royalty');
$csv->addRow($line);
$key=1;
foreach($downloadResult as $k => $v) {
    $line = array($key, $v['Download']['library_name'], $v['0']['totalDownloads'], $v['Download']['library_price'], $v['Download']['monthly_price'], $v['Download']['download_price'], $v['Download']['mechanical_royalty']);
    $csv->addRow($line);
	$key++;
}
$line = array('', '', '', '', '', '');
$csv->addRow($line);
echo $csv->render('MonthlyUnlimitedLibraryDownload');
?>
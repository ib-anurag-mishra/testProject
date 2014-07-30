<?php

/*
 * Code to generate Royality report
 */

//set timezone
date_default_timezone_set('America/New_York');

ini_set('memory_limit', '-1');
error_reporting(1);
set_time_limit(0);

include_once "config_ioda.php";
include_once "functions_ioda.php";

$reportsFolder = 'ioda_reports2';
unlink($reportsFolder . '/tmp_debug_data.txt');

$arr_dates = array();

//$arr_dates['month']['from_date'] = date("Y-m-01 00:00:00", mktime(0, 0, 0, (date(m) - 1), 1, date(Y))); //'2012-10-01 00:00:00';
//$arr_dates['month']['to_date'] = date("Y-m-t 23:59:59", mktime(0, 0, 0, (date(m) - 1), 1, date(Y))); //'2012-10-31 23:59:59';
$arr_dates['month']['from_date'] = '2014-06-01 00:00:00';
$arr_dates['month']['to_date'] = '2014-06-31 23:59:59';

$fetchRecordsFromTable = 'latest_downloads';
//$fetchRecordsFromTable = 'downloads';
$libraryType = array('ALC' => '0');
//$libraryType = array('ALC' => '0', 'Unlimited' => '1');

$country_curency = array('CA' => 'CAD');
//$country_curency = array('CA' => 'CAD', 'US' => 'USD', 'AU' => 'AUD', 'IT' => 'EUR', 'NZ' => 'NZD');
//$country_curency = array('CA' => 'USD', 'US' => 'USD', 'AU' => 'USD', 'IT' => 'USD', 'NZ' => 'USD', 'BM' => 'USD', 'DE' => 'USD');

$unit_sales_rate = null;

foreach ($arr_dates AS $key => $value)
{
    foreach ($libraryType as $libTypeKey => $libTypeValue)
    {
        $unit_sales_rate = ($libTypeKey == 'ALC') ? 0.25 : 0;
    }

    $query_country = "Select distinct libraries.library_territory from libraries";
    $result_country = mysql_query($query_country, $freegal);
    
    echo "Error : ".mysql_error($freegal);
    
}
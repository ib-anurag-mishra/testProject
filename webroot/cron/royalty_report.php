<?php

/*
 * Code to generate Royality report
 */
error_reporting(1);
set_time_limit(0);
include_once "config_ioda.php";
include_once "functions_ioda.php";
$reportsFolder = 'ioda_reports2';
unlink($reportsFolder . '/tmp_debug_data.txt');
$arr_dates = array();
//set timezone
date_default_timezone_set('America/New_York');
ini_set('memory_limit', '-1');

//$arr_dates['month']['from_date'] = date('Y-m-01 00:00:00',strtotime('-31 days'));
//$arr_dates['month']['to_date'] = date('Y-m-t 23:59:59',strtotime('-31 days'));

$arr_dates['month']['from_date'] = date("Y-m-01 00:00:00", mktime(0, 0, 0, (date(m) - 1), 1, date(Y))); //'2012-10-01 00:00:00';
$arr_dates['month']['to_date'] = date("Y-m-t 23:59:59", mktime(0, 0, 0, (date(m) - 1), 1, date(Y))); //'2012-10-31 23:59:59';

//$arr_dates['month']['from_date'] =  '2012-10-01 00:00:00';
//$arr_dates['month']['to_date'] = '2012-10-31 23:59:59';

//$fetchRecordsFromTable = 'latest_downloads';
$fetchRecordsFromTable = 'downloads';

$libraryType = array('ALC' => '0', 'Unlimited' => '1');

// $libraryType = array('ALC'=>'0');

$unit_sales_rate = null;
foreach ($arr_dates AS $key => $value)
{
    foreach ($libraryType as $libTypeKey => $libTypeValue)
    {
        if ($libTypeKey == 'ALC')
        {
            $unit_sales_rate = 0.25;
        }
        else
        {
            $unit_sales_rate = 0;
        }
        //$country_curency = array('CA' => 'CAD', 'US' => 'USD', 'AU' => 'AUD', 'IT' => 'EUR', 'NZ' => 'NZD');
        $country_curency = array('CA' => 'USD', 'US' => 'USD', 'AU' => 'USD', 'IT' => 'USD', 'NZ' => 'USD');
        // $country_curency = array('US' => 'USD');

        $query_country = "Select distinct libraries.library_territory from libraries";
        $result_country = mysql_query($query_country, $freegal);

        while ($row_country = mysql_fetch_assoc($result_country))
        {
            $royalty_content = array(
                array(
                    array("RECORD_TYPE", "PERIOD_START_DATE", "PERIOD_END_DATE", "TOTAL_SALES", "ISO_CURRENCY_CODE", "INVOICE_REQUIRED", "TIMEZONE", "SPEC_NUMBER", "VERSION_NUMBER")
                ),
                array(
                    array('RECORD_TYPE', 'IODA_TRACK_ID', 'ISRC', 'IODA_RELEASE_ID', 'UPC', 'TRACK_NAME', 'RELEASE_NAME', 'ARTIST_NAME', 'TRANSACTION_TYPE', 'DELIVERY_ID', 'FORMAT', 'UNIT_COUNT', 'UNIT_PRICE', 'TOTAL_SALES', 'ISO_COUNTRY_CODE', 'VENDOR', 'IODA_VENDOR_ID', 'VENDOR_TRACK_ID', 'VENDOR_RELEASE_ID', 'RETAIL_UNIT_PRICE', 'RETAIL_CURRENCY_CODE', 'TAX_DEDUCTION', 'MECHANICAL_DEDUCTION', 'CHARITY_DEDUCTION', 'MISC_DEDUCTION', 'TRANSACTION_DATE', "VENDOR_TRANSACTION_ID", 'VENDOR_CUSTOMER_ID'),
                ),
                array(
                    array('RECORD_TYPE', 'DETAIL_ROWS', 'TOTAL_SOLD', 'TOTAL_FREE', 'TOTAL_PROMO', 'TOTAL_SUB', 'TOTAL_UPGRADE', 'TOTAL_REFUND')
                ),
            );
            echo $query = "SELECT clp.library_id, clp.library_contract_start_date, clp.library_contract_end_date, clp.library_unlimited, "
                    . "sum(lp.purchased_tracks) as total_tracks_purchase ,  sum(lp.purchased_amount) as total_amount, l.library_territory, "
                    . "lp.library_id, clp.id as contract_id, clp.id_library_purchases, l.library_name, l.library_user_download_limit, "
                    . "l.library_download_type, l.library_download_limit, l.library_current_downloads, l.library_total_downloads, "
                    . "l.library_available_downloads, l.library_territory, l.library_status "
                    . "FROM library_purchases lp "
                    . "INNER JOIN contract_library_purchases clp ON lp.id = clp.id_library_purchases "
                    . "INNER JOIN libraries l ON clp.library_id = l.id "
                    . "WHERE ( (clp.library_contract_start_date <= '" . $value['from_date'] . "' AND clp.library_contract_end_date >= '" . $value['to_date'] . "')  "
                    . "OR (clp.library_contract_start_date <= '" . $value['from_date'] . "' AND clp.library_contract_end_date BETWEEN '" . $value['from_date'] . "' "
                    . "AND '" . $value['to_date'] . "') OR (clp.library_contract_start_date BETWEEN '" . $value['from_date'] . "' AND '" . $value['to_date'] . "' "
                    . "AND clp.library_contract_end_date >= '" . $value['to_date'] . "') OR (clp.library_contract_start_date >= '" . $value['from_date'] . "' "
                    . "AND clp.library_contract_end_date <= '" . $value['to_date'] . "') ) AND clp.library_unlimited = '" . $libTypeValue . "' "
                    . "AND l.library_territory='" . $row_country['library_territory'] . "' "
                    . "GROUP BY concat(clp.library_contract_start_date,'-',clp.library_contract_end_date,'-',clp.library_id),clp.library_unlimited,clp.library_id "
                    . "ORDER BY clp.library_id;";

            //$query = "select Songs.*,count(Songs.ProdID) as unit_count,Albums.AlbumTitle,Albums.UPC,libraries.library_territory From downloads JOIN Songs on Songs.ProdID = downloads.ProdID AND Songs.provider_type = 'ioda'  JOIN Albums ON Albums.ProdID = Songs.ReferenceID AND Albums.provider_type = 'ioda' JOIN libraries ON downloads.library_id = libraries.id Where downloads.created >= '".$value['from_date']."' AND downloads.created <= '".$value['to_date']."' AND downloads.provider_type='ioda' AND libraries.library_territory = '$row_country['library_territory']' GROUP BY Songs.ProdID ";

            $result = mysql_query($query, $freegal);
            $file_name = $reportsFolder . '/tmp_debug_data.txt';
            $tmp_cont = $query . '//' . mysql_num_rows($result) . '////\r\n';
            $fh = fopen($file_name, 'a') or die("can't open file");
            fwrite($fh, $tmp_cont);
            $total_sales = 0.0;
            $total_records = 0;
            $total_sold = 0;
            while ($q = mysql_fetch_assoc($result))
            {
                //$dates_query = "SELECT contract_library_purchases.library_contract_start_date from contract_library_purchases where contract_library_purchases.id > '$q[contract_id]' and library_id = '$q[library_id]' order by id ;";
                //$least_date = $to_date < ($q['library_contract_end_date'] . " 23:59:59") ? $to_date : ($q['library_contract_end_date'] . " 23:59:59");
                //$dates_query_result = mysql_query($dates_query);
                if (strtotime($q['library_contract_start_date']) <= strtotime($value['from_date']))
                {
                    if (strtotime($q['library_contract_end_date']) >= strtotime($value['to_date']))
                    {
                        echo '1';
                        echo $song_download_query = "select Songs.*,count(Songs.ProdID) as unit_count,Albums.AlbumTitle,Albums.UPC,libraries.library_territory, downloads.created "
                                . "From $fetchRecordsFromTable as downloads "
                                . "JOIN Songs on Songs.ProdID = downloads.ProdID AND Songs.provider_type = 'ioda'  "
                                . "JOIN Albums ON Albums.ProdID = Songs.ReferenceID AND Albums.provider_type = 'ioda' "
                                . "JOIN libraries ON downloads.library_id = libraries.id "
                                . "Where downloads.created >= '" . $value['from_date'] . "' AND downloads.created <= '" . $value['to_date'] . "' "
                                . "AND downloads.provider_type='ioda' AND libraries.library_territory = '" . $row_country['library_territory'] . "' "
                                . "AND library_id = " . $q['library_id'] . " GROUP BY Songs.ProdID ";
                    }
                    else
                    {
                        echo "2";
                        echo $song_download_query = "select Songs.*,count(Songs.ProdID) as unit_count,"
                                . "Albums.AlbumTitle,Albums.UPC,libraries.library_territory, downloads.created "
                                . "From $fetchRecordsFromTable as downloads "
                                . "JOIN Songs on Songs.ProdID = downloads.ProdID AND Songs.provider_type = 'ioda'  "
                                . "JOIN Albums ON Albums.ProdID = Songs.ReferenceID AND Albums.provider_type = 'ioda' "
                                . "JOIN libraries ON downloads.library_id = libraries.id Where downloads.created >= '" . $value['from_date'] . "' "
                                . "AND downloads.created <= '" . $q['library_contract_end_date'] . " 23:59:59' AND downloads.provider_type='ioda' "
                                . "AND libraries.library_territory = '" . $row_country['library_territory'] . "' AND library_id = " . $q['library_id'] . " "
                                . "GROUP BY Songs.ProdID ";
                    }
                }
                else
                {
                    if (strtotime($q['library_contract_end_date']) >= strtotime($value['to_date']))
                    {
                        echo "3";
                        //$total_download_query = "SELECT library_id, count(*) as total_count From $fetchRecordsFromTable as downloads WHERE provider_type='ioda' and downloads.created between '" . $q['library_contract_start_date'] . " 00:00:00' and '" . $to_date . "' and library_id = " . $q['library_id'];
                        echo $song_download_query = "select Songs.*,count(Songs.ProdID) as unit_count,Albums.AlbumTitle,Albums.UPC,"
                                . "libraries.library_territory, downloads.created "
                                . "From $fetchRecordsFromTable as downloads "
                                . "JOIN Songs on Songs.ProdID = downloads.ProdID AND Songs.provider_type = 'ioda'  "
                                . "JOIN Albums ON Albums.ProdID = Songs.ReferenceID AND Albums.provider_type = 'ioda' "
                                . "JOIN libraries ON downloads.library_id = libraries.id "
                                . "Where downloads.created >= '" . $q['library_contract_start_date'] . " 00:00:00' "
                                . "AND downloads.created <= '" . $value['to_date'] . "' AND downloads.provider_type='ioda' "
                                . "AND libraries.library_territory = '" . $row_country['library_territory'] . "' "
                                . "AND library_id = " . $q['library_id'] . " GROUP BY Songs.ProdID ";
                    }
                    else
                    {
                        echo "4";
                        $song_download_query = "select Songs.*,count(Songs.ProdID) as unit_count,Albums.AlbumTitle,Albums.UPC,"
                                . "libraries.library_territory, downloads.created "
                                . "From $fetchRecordsFromTable as downloads "
                                . "JOIN Songs on Songs.ProdID = downloads.ProdID AND Songs.provider_type = 'ioda'  "
                                . "JOIN Albums ON Albums.ProdID = Songs.ReferenceID AND Albums.provider_type = 'ioda' "
                                . "JOIN libraries ON downloads.library_id = libraries.id "
                                . "Where downloads.created between '" . $q['library_contract_start_date'] . " 00:00:00' and '" . $q['library_contract_end_date'] . " 23:59:59' "
                                . "AND downloads.provider_type='ioda' AND libraries.library_territory = '" . $row_country['library_territory'] . "' "
                                . "AND library_id = " . $q['library_id'] . " GROUP BY Songs.ProdID ";
                    }
                }

                $tmp_cont2 = $song_download_query . '//' . mysql_num_rows($result) . "////\r\n";
                fwrite($fh, $tmp_cont2);

                $song_download_result = mysql_query($song_download_query, $freegal);

                if (mysql_num_rows($song_download_result) == 0)
                {
                    continue;
                }

                echo mysql_error();

                while ($row = mysql_fetch_assoc($song_download_result))
                {
                    $unit_count = $row['unit_count'];
                    //$sales = $unit_count * 0.65;
                    $sales = $unit_count * $unit_sales_rate;
                    $total_sales += $sales;
                    $artistText = trim($row['ArtistText']);
                    //$royalty_content[1][] = array("D" , $row['ProdID'] ,$row['ISRC'] , $row['ReferenceID'] , $row['UPC' ], $row['SongTitle'] , $row['AlbumTitle'] , $row['ArtistText'] , 'S' , 1 , 't' , $unit_count ,  0.65 ,  $sales , $row['library_territory'] , 'Library Ideas ' , '10753' , $row['ProdID'] ,$row['ProductID'] ,  1.30 , $country_curency[$row_country['library_territory']] , '0.00', '0.00', '0.00', '0.00' , '' ,'' , ''   );
                    $retail_price = ($libTypeKey == 'ALC') ? '0.5' : '   ';
                    $royalty_content[1][] = array("D", $row['ProdID'], $row['ISRC'], $row['ReferenceID'], $row['UPC'], $row['SongTitle'], $row['AlbumTitle'], $artistText, 'S', 1, 't', $unit_count, $unit_sales_rate, $sales, $row['library_territory'], 'Library Ideas ', '10753', $row['ProdID'], $row['ProductID'], $retail_price, $country_curency[$row_country['library_territory']], '0.00', '0.00', '0.00', '0.00', date('Y-m-d', strtotime($row['created'])), '', '');
                    $total_records++;
                    $total_sold += $unit_count;                                       
                }
            }
            
//            $version = 1;
//            $file_name = "Freegal_r_" . strtolower($row_country['library_territory']) . "_" . date('Ym', strtotime($value['from_date'])) . '_' . $libTypeKey . "_v$version" . ".txt";
//            while (1)
//            {
//                if (file_exists($reportsFolder . "/" . $file_name))
//                {
//                    $version++;
//                    $file_name = "Freegal_r_" . strtolower($row_country['library_territory']) . "_" . date('Ym', strtotime($value['from_date'])) . '_' . $libTypeKey . "_v$version" . ".txt";
//                }
//                else
//                {
//                    break;
//                }
//            }
//            $round_total_sales = round($total_sales, 2);
//            if (false === strpos($round_total_sales, '.'))
//            {
//                $round_total_sales .= '.00';
//            }
//            else
//            {
//                $strlen = strlen(end(explode('.', $round_total_sales)));
//                if (1 == $strlen)
//                {
//                    $round_total_sales .= '0';
//                }
//            }
//
//            $royalty_content[0][] = array("H", date('Ymd', strtotime($value['from_date'])), date('Ymd', strtotime($value['to_date'])), $round_total_sales, $country_curency[$row_country['library_territory']], "Y", "ET", "3.0", "$version");
//            $royalty_content[2][] = array("T", $total_records, $total_sold, 0, 0, 0, 0, 0);
//            write_file($royalty_content, $file_name, $reportsFolder . "/");
        }
    }
}

function write_file($content, $file_name, $folder)
{
    if (count($content[1]) > 1)
    {
        $outputFile = "iodareports_output_" . date('Y_m_d_h_i_s') . ".txt";
        $logFileWrite = fopen(IMPORTLOGS . $outputFile, 'w') or die("Can't Open the file!");
        echo $file = $folder . $file_name;
        $fh = fopen($file, 'w') or die("can't open file");
        foreach ($content as $data)
        {
            $delimiter = "\t";
            $titleString = implode($delimiter, $data[0]);
            unset($data[0]);
            fwrite($fh, $titleString . "\n");
            foreach ($data as $subArray)
            {
                $dataRowString = implode($delimiter, $subArray);
                fwrite($fh, $dataRowString . "\n");
            }
            //fwrite($fh ,"\r\n"); 
        }
        fclose($fh);

        //FOR SENDING REPORT TO IODA SERVER USING SFTP
//        if (sendReportFilesftp($folder . $file_name, $file_name, $logFileWrite, "monthly"))
//        {
//            echo "Report $file_name sent";
//        }
        //FOR SENDING REPORT TO IODA SERVER USING FTP
//        if (sendReportFileftp($folder . $file_name, $file_name, $logFileWrite, "monthly"))
//        {
//            echo "Report $file_name sent";
//        }
    }
}

?>

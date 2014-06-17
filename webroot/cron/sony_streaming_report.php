<?php

/**
 *
 *
 * @author Rob Richmond
 * @version $Id$
 * @package report
 * This cron script is intended to run on every week to generate the download report for Sony and SCP to sony server
 * */
include 'functions.php';
$count = '';

ini_set('error_reporting', E_ALL);
ini_set('memory_limit', '-1');

set_time_limit(0);

//set timezone
date_default_timezone_set('America/New_York');

$countrys = array('CA' => 'CAD', 'US' => 'USD', 'AU' => 'AUD', 'IT' => 'EUR', 'NZ' => 'NZD', 'GB' => 'GBP', 'IE' => 'EUR');
//$countrys = array('GB' => 'GBP');


$lib_types = array('Unlimited');

$currentDate = '2014-04-01';
//$currentDate = date("Y-m-d", time());

echo "\n----------- Start " . $currentDate . " -----------";

list($year, $month, $day) = explode('-', $currentDate);

echo $monthFirstDate = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));

if (($currentDate == $monthFirstDate))
{
    $reports_dir = SONY_REPORTFILES;

    if (!file_exists($reports_dir))
    {
        fwrite($error_log, date('Y-m-d h:i:s') . " $reports_dir not found. " . "\n");
        mkdir($reports_dir);
    }

    $logs_dir = IMPORTLOGS;
    if (!file_exists($logs_dir))
    {
        fwrite($error_log, date('Y-m-d h:i:s') . " $logs_dir not found. " . "\n");
        mkdir($logs_dir);

        echo $outputFile = "/reports_output_" . date('Y_m_d_h_i_s') . ".txt";
        $logFileWrite = fopen(IMPORTLOGS . $outputFile, 'w') or die("Can't Open the log file!");

        echo $error_log_file = "/error_reports_output_" . date('Y_m_d_h_i_s') . ".txt";
        $error_log = fopen(IMPORTLOGS . $error_log_file, 'w') or die("Can't Open the error log file!");
    }

    $lib_type_int = 1;
    $lib_type_cond = (!$lib_type_int) ? " l.library_streaming_hours < 24 AND l.library_streaming_hours > 0 " : " l.library_streaming_hours = 24 ";

    foreach ($countrys as $country => $currency)
    {

        echo "\n----------------\n";
        echo $showStartDate = date("Ymd", strtotime('-1 month', strtotime(date('m', strtotime($currentDate)) . '/01/' . date('Y', strtotime($currentDate)) . ' 00:00:00')));
        echo $showEndDate = date("Ymd", strtotime('-1 second', strtotime('+1 month', strtotime('-1 month', strtotime(date('m', strtotime($currentDate)) . '/01/' . date('Y', strtotime($currentDate)) . ' 00:00:00')))));
        echo "\n----------------\n";

        $condStartDate = date("Y-m-d", strtotime('-1 month', strtotime(date('m', strtotime($currentDate)) . '/01/' . date('Y', strtotime($currentDate)) . ' 00:00:00'))) . " 00:00:00";
        $condEndDate = date("Y-m-d", strtotime('-1 second', strtotime('+1 month', strtotime('-1 month', strtotime(date('m', strtotime($currentDate)) . '/01/' . date('Y', strtotime($currentDate)) . ' 00:00:00'))))) . " 23:59:59";

        $count = 1;
        echo $sql = "SELECT COUNT(*) as ReportCount, id FROM sony_reports WHERE report_name = 'PM43_M_" . $showStartDate . "_" . $showEndDate . "_STREAM_" . $country . "_" . $count . ".txt'";
        $result3 = mysql_query($sql);
        fwrite($error_log, date('Y-m-d h:i:s') . "Line 77.  $sql " . "\n");

        if ($result3)
        {
            $row2 = mysql_fetch_array($result3, MYSQL_ASSOC);

            if ($row2['ReportCount'] > 0)
            {
                $count = $row2['ReportCount'] + 1;
            }
            else
            {
                $count = 1;
            }

            $row2['ReportCount'] = 0;
            $report_name = $reports_dir . "/PM43_M_" . $showStartDate . "_" . $showEndDate . "_STREAM_" . $country . "_" . $count . ".txt";
            $all_Ids = '';


            $sql = "SELECT lp.library_id,clp.library_contract_start_date,clp.library_contract_end_date,clp.library_unlimited,l.library_territory "
                    . "FROM library_purchases lp INNER JOIN contract_library_purchases clp ON lp.library_id = clp.library_id "
                    . "INNER JOIN libraries l ON clp.library_id = l.id WHERE $lib_type_cond AND ( (clp.library_contract_start_date <= '" . $condStartDate
                    . "' AND clp.library_contract_end_date >= '" . $condEndDate . "')  OR (clp.library_contract_start_date <= '" . $condStartDate
                    . "' AND clp.library_contract_end_date BETWEEN '" . $condStartDate . "' AND '" . $condEndDate . "') OR (clp.library_contract_start_date BETWEEN '"
                    . $condStartDate . "' AND '" . $condEndDate . "' AND clp.library_contract_end_date >= '" . $condEndDate . "') "
                    . "OR (clp.library_contract_start_date >= '" . $condStartDate . "' AND clp.library_contract_end_date <= '" . $condEndDate . "') ) "
                    . "AND l.library_territory = '$country' and l.library_type = 2 "
                    . "GROUP BY concat(clp.library_contract_start_date,'-',clp.library_contract_end_date,'-',lp.library_id),lp.library_id ORDER BY lp.library_id;";
            $result = mysql_query($sql);
            fwrite($error_log, date('Y-m-d h:i:s') . "Line 77.  $sql " . "\n");

            if ($result)
            {
                $countno = mysql_num_rows($result);
                $data = array();
                $videodata = array();

                if ($countno > 0)
                {
                    while ($row = mysql_fetch_assoc($result))
                    {
                        $library_id = $row['library_id'];
                        if ($row['library_contract_start_date'] <= $condStartDate)
                        {
                            if ($row['library_contract_end_date'] >= $condEndDate)
                            {
                                $query = "SELECT 1 AS TrkCount, Songs.ISRC AS TrkID, Songs.Artist as artist, Albums.AlbumTitle, Songs.SongTitle as track_title,Songs.ProductID AS productcode,sh.library_id,sh.createdOn as created, sh.token_id,sh.patron_id as id,sh.consumed_time FROM streaming_histories as sh LEFT JOIN Songs on Songs.ProdID=sh.ProdID AND Songs.provider_type=sh.provider_type LEFT JOIN Albums on Albums.ProdID=Songs.ReferenceID AND Albums.provider_type=Songs.provider_type WHERE sh.provider_type='sony' and sh.createdOn between '" . $condStartDate . "' and '" . $condEndDate . "' and library_id = '" . $library_id . "'";
                            }
                            else
                            {
                                $query = "SELECT 1 AS TrkCount, Songs.ISRC AS TrkID, Songs.Artist as artist, Albums.AlbumTitle, Songs.SongTitle as track_title,Songs.ProductID AS productcode,sh.library_id,sh.createdOn as created, sh.token_id,sh.patron_id as id,sh.consumed_time FROM streaming_histories as sh LEFT JOIN Songs on Songs.ProdID=sh.ProdID AND Songs.provider_type=sh.provider_type LEFT JOIN Albums on Albums.ProdID=Songs.ReferenceID AND Albums.provider_type=Songs.provider_type WHERE sh.provider_type='sony' and sh.createdOn between '" . $condStartDate . "' and '" . $row['library_contract_end_date'] . " 23:59:59' and library_id = '" . $library_id . "'";
                            }
                        }
                        else
                        {
                            if ($row['library_contract_end_date'] >= $condEndDate)
                            {
                                $query = "SELECT 1 AS TrkCount, Songs.ISRC AS TrkID, Songs.Artist as artist, Albums.AlbumTitle, Songs.SongTitle as track_title,Songs.ProductID AS productcode,sh.library_id,sh.createdOn as created, sh.token_id,sh.patron_id as id,sh.consumed_time FROM streaming_histories as sh LEFT JOIN Songs on Songs.ProdID=sh.ProdID AND Songs.provider_type=sh.provider_type LEFT JOIN Albums on Albums.ProdID=Songs.ReferenceID AND Albums.provider_type=Songs.provider_type WHERE sh.provider_type='sony' and sh.createdOn between '" . $row['library_contract_start_date'] . " 00:00:00' and '" . $condEndDate . "' and library_id = '" . $library_id . "'";
                            }
                            else
                            {
                                $query = "SELECT 1 AS TrkCount, Songs.ISRC AS TrkID, Songs.Artist as artist, Albums.AlbumTitle, Songs.SongTitle as track_title,Songs.ProductID AS productcode,sh.library_id,sh.createdOn as created, sh.token_id,sh.patron_id as id,sh.consumed_time FROM streaming_histories as sh left LEFT JOIN Songs on Songs.ProdID=sh.ProdID AND Songs.provider_type=sh.provider_type LEFT JOIN Albums on Albums.ProdID=Songs.ReferenceID AND Albums.provider_type=Songs.provider_type WHERE sh.provider_type='sony' and sh.createdOn between '" . $row['library_contract_start_date'] . " 00:00:00' and '" . $row['library_contract_end_date'] . " 23:59:59' and library_id = '" . $library_id . "'";
                            }
                        }

                        $dataresult = mysql_query($query);
                        fwrite($error_log, date('Y-m-d h:i:s') . "Line 144.  $query " . "\n");

                        if ($dataresult)
                        {
                            while ($datarow = mysql_fetch_assoc($dataresult))
                            {
                                $data[$library_id][] = $datarow;
                            }
                        }
                        else
                        {
                            fwrite($error_log, date('Y-m-d h:i:s') . "Line 155.  $query failed " . "\n");
                            sendalert("Query failed: " . $query);
                            die("Query failed: " . $query . " Error: " . mysql_error());
                        }
                    }
                }
                else
                {
                    $data = array();
                    $videodata = array();
                }

                if (!empty($data) || !empty($videodata))
                {

                    $file = fopen($report_name, "w");
                    if ($file == false)
                    {
                        die("\nUnable to open/create file");
                    }

                    $header = "A#*#PM43#*#" . $showStartDate . "#*#" . $showEndDate . "#*#" . $count;
                    fwrite($file, $header . "\n");

                    $numSales = 0;
                    $numberOfSalesRecords = 0;
                    if (!empty($data))
                    {
                        foreach ($data as $libid => $lib)
                        {
                            $libSales = 0;

                            foreach ($lib as $line)
                            {
                                $sales = "N#*#PM43#*#2222#*#" . $showStartDate . "#*#" . $showEndDate . "#*#" . ($lib_type_int ? "Library Ideas - Stream" : "Library Ideas - Stream") . "#*#" . ($lib_type_int ? "PEP6" : "PEP6") . "#*#$country#*#SA#*##*##*#";
                                $sales .= $line['productcode'] . '#*#'; // UPC/Official Product Number (PhysicalProduct.ProductID)
                                $sales .= $line['TrkID'] . "#*#"; // ISRC/Official Track Number (METADATA.ISRC)
                                $sales .= "#*#"; // GRID/Official Digital Identifier
                                $sales .= "11#*#"; // Product Type Key
                                $sales .= $line['TrkCount'] . "#*#"; // Quantity
                                $sales .= "0#*#"; // Quantity Returned

                                if ($lib_type_int)
                                {
                                    $sales .= "0#*#"; // WPU
                                    $sales .= "0#*#"; // Wholesale Value (WPU * Quantity)
                                    $sales .= "0#*#"; // Net Invoice Price (same as WPU)
                                    $sales .= "0#*#"; // Net Invoice Value (same as Wholesale Value)
                                    $sales .= "0#*#"; // Retail Value
                                }
                                else
                                {
                                    $sales .= ".65#*#"; // WPU
                                    $sales .= ("0.65" * $line['TrkCount']) . "#*#"; // Wholesale Value (WPU * Quantity)
                                    $sales .= ".65#*#"; // Net Invoice Price (same as WPU)
                                    $sales .= ("0.65" * $line['TrkCount']) . "#*#"; // Net Invoice Value (same as Wholesale Value)
                                    $sales .= ("1.29" * $line['TrkCount']) . "#*#"; // Retail Value
                                }

                                $sales .= "0#*#"; // Charity Amount
                                $sales .= "$currency#*#"; // Currency Key
                                $sales .= "0#*#"; // VAT/TAX
                                $sales .= "0#*#"; // VAT/TAX Charity Amount
                                if ($country != 'US')
                                {
                                    $sales .= "Y#*#"; // Copyright Indicator (NEED TO FIND OUT FROM BRIAN DOWNING)
                                }
                                else
                                {
                                    $sales .= "N#*#"; // Copyright Indicator (NEED TO FIND OUT FROM BRIAN DOWNING)
                                }
                                $sales .= "05#*#"; // Distribution Type Key
                                $sales .= "10#*#"; // Transaction Type Key
                                $sales .= "20#*#"; // Service Type Key
                                $sales .= "MP3#*#"; // Media Key
                                $sales .= $line['artist'] . "#*#"; // Artist Name (METADATA.Artist)
                                $sales .= $line['AlbumTitle'] . "#*#"; // Album Title
                                $sales .= $line['track_title'] . "#*#"; // Track Title (METADATA.Title)
                                $sales .= $line['id'] . "#*#"; // patron_id
                                $sales .= $line['library_id'] . "#*#"; // library_id
                                fwrite($file, $sales . "\n");
                                $libSales = $libSales + $line['TrkCount'];
                                $numberOfSalesRecords++;
                            }

                            $numSales = $numSales + $libSales;
                        }
                    }

                    $market = "M#*#PM43#*#2222#*#" . $showStartDate . "#*#" . $showEndDate . "#*#";
                    $market .= "Library Ideas - Stream#*#"; // Vendor/Retailer Name was Library Ideas#*#
                    $market .= "PEP6#*#"; // Vendor Key was PM43#*#
                    $market .= "$country#*#11#*#100";
                    fwrite($file, $market . "\n");


                    $trailer = "Z#*#PM43#*#" . $showStartDate . "#*#" . $showEndDate . "#*#";
                    $trailer .= $numberOfSalesRecords . "#*#"; // Number of Standard Sales Records (total number of N records)
                    $trailer .= "1#*#"; // Number of Market Share Records (total number of M records)
                    //$trailer .= $row['Count'] . "#*#"; // Total Quantity
                    $trailer .= $numSales . "#*#"; // Total Quantity
                    $trailer .= "0#*#"; // Total Quantity Free
                    $trailer .= "0#*#"; // Total Quantity Promo
                    $trailer .= "0"; // Total Quantity Returned
                    fwrite($file, $trailer);
                    fclose($file);


                    echo $sql = "INSERT INTO sony_reports(report_name,new_report_name, report_location, created, modified)values('PM43_M_" . $showStartDate . "_" . $showEndDate . "_" . $lib_type . "_" . $country . "_STREAMING.txt','PM43_M_" . $showStartDate . "_" . $showEndDate . "_" . $lib_type . "_" . $count . "_" . $country . "_STREAMING.txt', '" . addslashes(SONY_REPORTFILES) . "', now(), now())";
//                    $result6 = mysql_query($sql);
//
//                    if ($result6)
//                    {
//                        if (sendReportFilesftp($report_name, "/PM43_M_" . $showStartDate . "_" . $showEndDate . "_STREAM_" . $country . "_" . $count . ".txt", $logFileWrite, "monthly"))
//                        {
//                            // FOR SENDING REPORT TO SONY SERVER USING FTP
//                            $sql = "UPDATE sony_reports SET is_uploaded = 'yes', modified = now() WHERE id = " . mysql_insert_id();
//                            $result7 = mysql_query($sql);
//
//                            if ($result7)
//                            {
//                                fwrite($error_log, date('Y-m-d h:i:s') . " File Send " . "\n");
//                                echo "============= File Send. DB updated =============";
//                            }
//                            else
//                            {
//                                sendalert("Query failed: " . $sql);
//                                die("Query failed: " . $sql . " Error: " . mysql_error());
//                            }
//                        }
//                        else
//                        {
//                            sendalert("Error while sending File to Sony.");
//                            fwrite($error_log, date('Y-m-d h:i:s') . " Error while sending the File. " . "\n");
//                        }
//                    }
//                    else
//                    {
//                        sendalert("Query failed: " . $sql);
//                        fwrite($error_log, date('Y-m-d h:i:s') . "Query failed: " . $sql . " Error: " . mysql_error() . "\n");
//                        die("Query failed: " . $sql . " Error: " . mysql_error());
//                    }
                }
            }
            else
            {
                fwrite($error_log, date('Y-m-d h:i:s') . " Line no : 283. Query Failes " . "\n");
                sendalert("Query failed: " . $sql);
                die("Query failed: " . $sql . " Error: " . mysql_error());
            }
        }
        else
        {
            fwrite($error_log, date('Y-m-d h:i:s') . " Query Fails : $sql " . "\n");
            sendalert("Query failed: " . $sql);
            die(" Query failed: " . $sql . " Error: " . mysql_error());
        }
    }
}
else
{
    echo "\nToday is not either the week first day or the month first day so the report didn't get generated.\n";
    fwrite($error_log, date('Y-m-d h:i:s') . " Line no : 300. Today is not month First Date " . "\n");
}

fwrite($error_log, date('Y-m-d h:i:s') . " ====  End  ====" . "\n");
fclose($error_log);
echo "\n----------- End -----------";

<?php
/**
 *
 *
 * @author Rob Richmond
 * @version $Id$
 * @package report
 * This cron script is intended to run on every week to generate the download report for Sony and SCP to sony server
 **/

include 'functions.php';
$count = '';
ini_set('error_reporting', E_ALL);
set_time_limit(0);

$countrys = array('CA' => 'CAD' , 'US' => 'USD' , 'AU' => 'AUD' , 'IT' => 'EUR' , 'NZ' => 'NZD', 'GB' => 'GBP', 'IE' => 'EUR');
//$countrys = array('CA' => 'CAD' , 'US' => 'USD');
$lib_types = array('Unlimited' , 'ALC');

$currentDate = '2013-09-01';
// $currentDate = date( "Y-m-d", time());

echo "\n----------- Start ".$currentDate." -----------";

list($year, $month, $day) = explode('-', $currentDate);

$weekFirstDay = date('Y-m-d', strtotime(date('Y' , strtotime($currentDate))."W".date('W' , strtotime($currentDate))."1"));
$monthFirstDate = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));

// Only creates the report if it is the first day of the month or the first day of the week.
if(($currentDate == $weekFirstDay) || ($currentDate == $monthFirstDate)){
    
    // Sets the name for the log file
    $outputFile = "/reports_output_".date('Y_m_d_h_i_s')."_test.txt";
    
    // Loops through the unlimited and the ala carte libraries
    foreach($lib_types as $lib_type){
        
        // If the lib_type is 'ALC' then this is '0', if then '1'
        $lib_type_int = $lib_type == "ALC" ? 0 : 1 ;
                
        // Loops through each country in the counties array
        foreach($countrys as $country => $currency){
            
            // $reports_dir = 'D:\projects\Freegalmusic\app\webroot\cron\sfv_reports';
            $reports_dir = SONY_REPORTFILES;

            if(!file_exists($reports_dir)){
                mkdir($reports_dir);
            }

            $logs_dir = IMPORTLOGS;

            if(!file_exists($logs_dir)){
                mkdir($logs_dir);
            }

            // Creates the log file
            $logFileWrite = fopen(IMPORTLOGS.$outputFile,'w') or die("Can't Open the file!");

            if($currentDate == $weekFirstDay){
                
                echo "\ncurrent date is week first day";
                $StartOfLastWeek = 6 + date("w",strtotime($currentDate));
                $showStartDate = date('Ymd' , strtotime($currentDate ." -$StartOfLastWeek day"));
                $showEndDate = date('Ymd', strtotime($currentDate." last sunday") );
                $condStartDate = date('Y-m-d', strtotime($currentDate . " -$StartOfLastWeek day"))." 00:00:00";
                $condEndDate = date('Y-m-d', strtotime($currentDate." last sunday"))." 23:59:59";
                $report_name = $reports_dir."/PM43_W_" . $showStartDate . "_" . $showEndDate . "_".$lib_type."_".$country."_test.txt";

                $sql = "SELECT COUNT(*) as ReportCount, id FROM sony_reports WHERE report_name = 'PM43_W_" . $showStartDate . "_" . $showEndDate . "_".$lib_type."_".$country."_test.txt'";
                $result3 = mysql_query($sql);
                
                if(!$result3){
                    die(" Query failed: ". $sql. " Error: " .mysql_error());
                }

                $row2 = mysql_fetch_array($result3, MYSQL_ASSOC);
                
                if($row2['ReportCount'] > 0){
                    $count = $row2['ReportCount']+1;
                } else {
                    $count = 1;
                }

                $row2['ReportCount'] = 0;
                $all_Ids = '';
                $sql = "SELECT lp.library_id,clp.library_contract_start_date,clp.library_contract_end_date,clp.library_unlimited,l.library_territory FROM library_purchases lp INNER JOIN contract_library_purchases clp ON lp.id = clp.id_library_purchases INNER JOIN libraries l ON clp.library_id = l.id WHERE clp.library_unlimited = '".$lib_type_int."' AND ( (clp.library_contract_start_date <= '".$condStartDate."' AND clp.library_contract_end_date >= '".$condEndDate."')  OR (clp.library_contract_start_date <= '".$condStartDate."' AND clp.library_contract_end_date BETWEEN '".$condStartDate."' AND '".$condEndDate."') OR (clp.library_contract_start_date BETWEEN '".$condStartDate."' AND '".$condEndDate."' AND clp.library_contract_end_date >= '".$condEndDate."') OR (clp.library_contract_start_date >= '".$condStartDate."' AND clp.library_contract_end_date <= '".$condEndDate."') ) AND l.library_territory = '$country' GROUP BY concat(clp.library_contract_start_date,'-',clp.library_contract_end_date,'-',lp.library_id),lp.library_id ORDER BY lp.library_id;";
                $result = mysql_query($sql);
                
                if(!$result){
                    die(" Query failed: ". $sql. " Error: " .mysql_error());
                }

                $countno = mysql_num_rows($result);
                $data = array();
                
                if($countno > 0) {
                    
                    while ($row = mysql_fetch_assoc($result)) {
                        $library_id = $row['library_id'];
                        if($row['library_contract_start_date'] <= $condStartDate){
                          if($row['library_contract_end_date'] >= $condEndDate){
                            $query = "SELECT 1 AS TrkCount, downloads.ISRC AS TrkID, downloads.artist, Albums.AlbumTitle, downloads.track_title, downloads.ProductID AS productcode,currentpatrons.id,downloads.library_id,downloads.created FROM downloads left join currentpatrons on currentpatrons.libid = downloads.library_id AND currentpatrons.patronid = downloads.patron_id LEFT JOIN Songs on Songs.ProdID=downloads.ProdID AND Songs.provider_type=downloads.provider_type LEFT JOIN Albums on Albums.ProdID=Songs.ReferenceID AND Albums.provider_type=Songs.provider_type WHERE downloads.provider_type='sony' and downloads.created between '".$condStartDate."' and '".$condEndDate."' and library_id = ".$library_id." group by downloads.id";
                          }
                          else{
                            $query = "SELECT 1 AS TrkCount, downloads.ISRC AS TrkID, downloads.artist, Albums.AlbumTitle, downloads.track_title, downloads.ProductID AS productcode,currentpatrons.id,downloads.library_id,downloads.created FROM downloads left join currentpatrons on currentpatrons.libid = downloads.library_id AND currentpatrons.patronid = downloads.patron_id LEFT JOIN Songs on Songs.ProdID=downloads.ProdID AND Songs.provider_type=downloads.provider_type LEFT JOIN Albums on Albums.ProdID=Songs.ReferenceID AND Albums.provider_type=Songs.provider_type WHERE downloads.provider_type='sony' and downloads.created between '".$condStartDate."' and '".$row['library_contract_end_date']." 23:59:59' and library_id = ".$library_id." group by downloads.id";
                          }
                        } else {
                          if($row['library_contract_end_date'] >= $condEndDate){
                            $query = "SELECT 1 AS TrkCount, downloads.ISRC AS TrkID, downloads.artist, Albums.AlbumTitle, downloads.track_title, downloads.ProductID AS productcode,currentpatrons.id,downloads.library_id,downloads.created FROM downloads left join currentpatrons on currentpatrons.libid = downloads.library_id AND currentpatrons.patronid = downloads.patron_id LEFT JOIN Songs on Songs.ProdID=downloads.ProdID AND Songs.provider_type=downloads.provider_type LEFT JOIN Albums on Albums.ProdID=Songs.ReferenceID AND Albums.provider_type=Songs.provider_type WHERE downloads.provider_type='sony' and downloads.created between '".$row['library_contract_start_date']." 00:00:00' and '".$condEndDate."' and library_id = ".$library_id." group by downloads.id";
                          }
                          else{
                            $query = "SELECT 1 AS TrkCount, downloads.ISRC AS TrkID, downloads.artist, Albums.AlbumTitle, downloads.track_title, downloads.ProductID AS productcode,currentpatrons.id,downloads.library_id,downloads.created FROM downloads left join currentpatrons on currentpatrons.libid = downloads.library_id AND currentpatrons.patronid = downloads.patron_id LEFT JOIN Songs on Songs.ProdID=downloads.ProdID AND Songs.provider_type=downloads.provider_type LEFT JOIN Albums on Albums.ProdID=Songs.ReferenceID AND Albums.provider_type=Songs.provider_type WHERE downloads.provider_type='sony' and downloads.created between '".$row['library_contract_start_date']." 00:00:00' and '".$row['library_contract_end_date']." 23:59:59' and library_id = ".$library_id." group by downloads.id";
                          }
                        }

                        $dataresult = mysql_query($query);
                        
                        if(!$dataresult) {
                            die(" Query failed: ". $query. " Error: " .mysql_error());
                        }
                        
                        while ($datarow = mysql_fetch_assoc($dataresult)) {
                          $data[$library_id][] = $datarow;
                        }

                    }

                } else {
                  $data = array();
                }
                
                if(!empty($data)){

                    $file = fopen($report_name, "w");
                    
                    if ($file == false){
                        die ("\nUnable to open/create file");
                    }

                    $header = "A#*#PM43#*#" . $showStartDate . "#*#" . $showEndDate . "#*#".$count;
                    fwrite($file, $header . "\n");
                    $numSales = 0;
                    $numberOfSalesRecords = 0;
    
                    foreach ($data as $libid=>$lib) {
                        
                        $libSales = 0;
                        
                        foreach($lib as $line){
                            $sales = "N#*#PM43#*#2222#*#" . $showStartDate . "#*#" . $showEndDate . "#*#".($lib_type_int ? "Library Ideas Unlimited Service" : "Library Ideas A La Carte")."#*#" . ($lib_type_int ? "PAR3" : "PAR2") . "#*#$country#*#SA#*##*##*#";
                            $sales .= $line['productcode'] . '#*#'; // UPC/Official Product Number (PhysicalProduct.ProductID)
                            $sales .= $line['TrkID'] . "#*#"; // ISRC/Official Track Number (METADATA.ISRC)
                            $sales .= "#*#"; // GRID/Official Digital Identifier
                            $sales .= "11#*#"; // Product Type Key
                            $sales .= $line['TrkCount'] . "#*#"; // Quantity
                            $sales .= "0#*#"; // Quantity Returned
                            
                            if($lib_type_int){
                                $sales .= "0#*#"; // WPU
                                $sales .= "0#*#"; // Wholesale Value (WPU * Quantity)
                                $sales .= "0#*#"; // Net Invoice Price (same as WPU)
                                $sales .= "0#*#"; // Net Invoice Value (same as Wholesale Value)
                                $sales .= "0#*#"; // Retail Value
                            } else {
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
                            
                            if($country != 'US') {
                                $sales .= "Y#*#"; // Copyright Indicator (NEED TO FIND OUT FROM BRIAN DOWNING)
                            } else {
                                $sales .= "N#*#"; // Copyright Indicator (NEED TO FIND OUT FROM BRIAN DOWNING)
                            }

                            $sales .= "05#*#"; // Distribution Type Key
                            $sales .= "20#*#"; // Transaction Type Key
                            $sales .= "10#*#"; // Service Type Key
                            $sales .= "MP3#*#"; // Media Key
                            $sales .= $line['artist'] . "#*#"; // Artist Name (METADATA.Artist)
                            $sales .= $line['AlbumTitle']."#*#"; // Album Title
                            $sales .= $line['track_title']. "#*#"; // Track Title (METADATA.Title)
                            $sales .= $line['id']. "#*#"; // patron_id
                            $sales .= $line['library_id']; // library_id
                            fwrite($file, $sales . "\n");
                            $libSales = $libSales + $line['TrkCount'];
                            $numberOfSalesRecords++;
                        }

                        $numSales = $numSales + $libSales;
                    }

                    $market = "M#*#PM43#*#2222#*#" . $showStartDate . "#*#" . $showEndDate . "#*#";
                    $market .= "#*#"; // Vendor/Retailer Name was Library Ideas#*#
                    $market .= "#*#"; // Vendor Key was PM43#*#
                    $market .= "$country#*#10#*#100";
                    fwrite($file, $market . "\n");

                    $trailer = "Z#*#PM43#*#" . $showStartDate . "#*#" . $showEndDate . "#*#";
                    $trailer .= $numberOfSalesRecords . "#*#"; // Number of Standard Sales Records (total number of N records)
                    $trailer .= "1#*#"; // Number of Market Share Records (total number of M records)
                    $trailer .= $numSales . "#*#";
                    $trailer .= "0#*#"; // Total Quantity Free
                    $trailer .= "0#*#"; // Total Quantity Promo
                    $trailer .= "0"; // Total Quantity Returned
                    fwrite($file, $trailer);
                    fclose($file);

                }
            } // End first day of week report

            // This report is created for the month prior to the current date.
            if($currentDate == $monthFirstDate){
                
                echo "\n----------------";
                echo $showStartDate = date("Ymd", strtotime('-1 month',strtotime(date('m' , strtotime($currentDate)).'/01/'.date('Y' , strtotime($currentDate)).' 00:00:00')));
                echo $showEndDate = date("Ymd", strtotime('-1 second',strtotime('+1 month',strtotime('-1 month',strtotime(date('m' , strtotime($currentDate)).'/01/'.date('Y' , strtotime($currentDate)).' 00:00:00')))));
                echo "\n----------------";

                $condStartDate = date("Y-m-d", strtotime('-1 month',strtotime(date('m' , strtotime($currentDate)).'/01/'.date('Y' , strtotime($currentDate)).' 00:00:00')))." 00:00:00";
                $condEndDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime('-1 month',strtotime(date('m' , strtotime($currentDate)).'/01/'.date('Y' , strtotime($currentDate)).' 00:00:00')))))." 23:59:59";

                $report_name = $reports_dir."/PM43_M_" . $showStartDate . "_" . $showEndDate . "_".$lib_type."_".$country."_test.txt";

                $sql = "SELECT COUNT(*) as ReportCount, id FROM sony_reports WHERE report_name = 'PM43_M_" . $showStartDate . "_" . $showEndDate . "_".$lib_type."_".$country."_test.txt'";
                $result3 = mysql_query($sql);
                
                if(!$result3){
                    die(" Query failed: ". $sql. " Error: " .mysql_error());
                }

                $row2 = mysql_fetch_array($result3, MYSQL_ASSOC);

                if($row2['ReportCount'] > 0){
                    $count = $row2['ReportCount'] + 1;
                } else {
                    $count = 1;
                }

                $row2['ReportCount'] = 0;
                $all_Ids = '';

                $sql = "SELECT lp.library_id,clp.library_contract_start_date,clp.library_contract_end_date,clp.library_unlimited,l.library_territory FROM library_purchases lp INNER JOIN contract_library_purchases clp ON lp.library_id = clp.library_id INNER JOIN libraries l ON clp.library_id = l.id WHERE clp.library_unlimited = '".$lib_type_int."' AND ( (clp.library_contract_start_date <= '".$condStartDate."' AND clp.library_contract_end_date >= '".$condEndDate."')  OR (clp.library_contract_start_date <= '".$condStartDate."' AND clp.library_contract_end_date BETWEEN '".$condStartDate."' AND '".$condEndDate."') OR (clp.library_contract_start_date BETWEEN '".$condStartDate."' AND '".$condEndDate."' AND clp.library_contract_end_date >= '".$condEndDate."') OR (clp.library_contract_start_date >= '".$condStartDate."' AND clp.library_contract_end_date <= '".$condEndDate."') ) AND l.library_territory = '$country' GROUP BY concat(clp.library_contract_start_date,'-',clp.library_contract_end_date,'-',lp.library_id),lp.library_id ORDER BY lp.library_id;";
                $result = mysql_query($sql);
                
                if(!$result){
                    die("Query failed: ". $sql. " Error: " .mysql_error());
                }
                
                $countno = mysql_num_rows($result);
                $data = array();

                if($countno>0){
                    
                    while ($row = mysql_fetch_assoc($result)){
                        
                        $library_id = $row['library_id'];
                        
                        if($row['library_contract_start_date'] <= $condStartDate){
                             
                             if($row['library_contract_end_date'] >= $condEndDate){
                                 $query = "SELECT 1 AS TrkCount, downloads.ISRC AS TrkID, downloads.artist, Albums.AlbumTitle, downloads.track_title, downloads.ProductID AS productcode,currentpatrons.id,downloads.library_id,downloads.created FROM downloads left join currentpatrons on currentpatrons.libid = downloads.library_id AND currentpatrons.patronid = downloads.patron_id LEFT JOIN Songs on Songs.ProdID=downloads.ProdID LEFT JOIN Albums on Albums.ProdID=Songs.ReferenceID WHERE downloads.provider_type='sony' and downloads.created between '".$condStartDate."' and '".$condEndDate."' and library_id = ".$library_id." group by downloads.id";
                             } else {
                                 $query = "SELECT 1 AS TrkCount, downloads.ISRC AS TrkID, downloads.artist, Albums.AlbumTitle, downloads.track_title, downloads.ProductID AS productcode,currentpatrons.id,downloads.library_id,downloads.created FROM downloads left join currentpatrons on currentpatrons.libid = downloads.library_id AND currentpatrons.patronid = downloads.patron_id LEFT JOIN Songs on Songs.ProdID=downloads.ProdID LEFT JOIN Albums on Albums.ProdID=Songs.ReferenceID WHERE downloads.provider_type='sony' and downloads.created between '".$condStartDate."' and '".$row['library_contract_end_date']." 23:59:59' and library_id = ".$library_id." group by downloads.id";
                             }

                         } else {

                            if($row['library_contract_end_date'] >= $condEndDate){
                                $query = "SELECT 1 AS TrkCount, downloads.ISRC AS TrkID, downloads.artist, Albums.AlbumTitle, downloads.track_title, downloads.ProductID AS productcode,currentpatrons.id,downloads.library_id,downloads.created FROM downloads left join currentpatrons on currentpatrons.libid = downloads.library_id AND currentpatrons.patronid = downloads.patron_id LEFT JOIN Songs on Songs.ProdID=downloads.ProdID LEFT JOIN Albums on Albums.ProdID=Songs.ReferenceID WHERE downloads.provider_type='sony' and downloads.created between '".$row['library_contract_start_date']." 00:00:00' and '".$condEndDate."' and library_id = ".$library_id." group by downloads.id";
                            } else {
                                $query = "SELECT 1 AS TrkCount, downloads.ISRC AS TrkID, downloads.artist, Albums.AlbumTitle, downloads.track_title, downloads.ProductID AS productcode,currentpatrons.id,downloads.library_id,downloads.created FROM downloads left join currentpatrons on currentpatrons.libid = downloads.library_id AND currentpatrons.patronid = downloads.patron_id LEFT JOIN Songs on Songs.ProdID=downloads.ProdID LEFT JOIN Albums on Albums.ProdID=Songs.ReferenceID WHERE downloads.provider_type='sony' and downloads.created between '".$row['library_contract_start_date']." 00:00:00' and '".$row['library_contract_end_date']." 23:59:59' and library_id = ".$library_id." group by downloads.id";
                            }

                        }

                        $dataresult = mysql_query($query);
                        
                        if(!$dataresult){
                            die("Query failed: ". $query. " Error: " .mysql_error());
                        }
                
                        while ($datarow = mysql_fetch_assoc($dataresult)) {
                          $data[$library_id][] = $datarow;
                        }

                    }

                } else {
                    
                    $data = array();
                }

                if(!empty($data)){
                   
                    $file = fopen($report_name, "w");
                    
                    if ($file == false){

                        die ("\nUnable to open/create file");
                    }

                    $header = "A#*#PM43#*#" . $showStartDate . "#*#" . $showEndDate . "#*#".$count;
                    fwrite($file, $header . "\n");

                    $numSales = 0;
                    $numberOfSalesRecords = 0;
               
                    foreach ($data as $libid=>$lib){
                        
                        $libSales = 0;

                        foreach($lib as $line){
                            
                            $sales = "N#*#PM43#*#2222#*#" . $showStartDate . "#*#" . $showEndDate . "#*#".($lib_type_int ? "Library Ideas Unlimited Service" : "Library Ideas A La Carte")."#*#" . ($lib_type_int ? "PAR3" : "PAR2") . "#*#$country#*#SA#*##*##*#";
                            $sales .= $line['productcode'] . '#*#'; // UPC/Official Product Number (PhysicalProduct.ProductID)
                            $sales .= $line['TrkID'] . "#*#"; // ISRC/Official Track Number (METADATA.ISRC)
                            $sales .= "#*#"; // GRID/Official Digital Identifier
                            $sales .= "11#*#"; // Product Type Key
                            $sales .= $line['TrkCount'] . "#*#"; // Quantity
                            $sales .= "0#*#"; // Quantity Returned

                            if($lib_type_int){
                                
                                $sales .= "0#*#"; // WPU
                                $sales .= "0#*#"; // Wholesale Value (WPU * Quantity)
                                $sales .= "0#*#"; // Net Invoice Price (same as WPU)
                                $sales .= "0#*#"; // Net Invoice Value (same as Wholesale Value)
                                $sales .= "0#*#"; // Retail Value
                            
                            } else {
                                
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
                            
                            if($country != 'US'){
                                $sales .= "Y#*#"; // Copyright Indicator (NEED TO FIND OUT FROM BRIAN DOWNING)
                            } else { 
                                $sales .= "N#*#"; // Copyright Indicator (NEED TO FIND OUT FROM BRIAN DOWNING)
                            }

                            $sales .= "05#*#"; // Distribution Type Key
                            $sales .= "20#*#"; // Transaction Type Key
                            $sales .= "10#*#"; // Service Type Key
                            $sales .= "MP3#*#"; // Media Key
                            $sales .= $line['artist'] . "#*#"; // Artist Name (METADATA.Artist)
                            $sales .= $line['AlbumTitle']."#*#"; // Album Title
                            $sales .= $line['track_title']. "#*#"; // Track Title (METADATA.Title)
                            $sales .= $line['id']. "#*#"; // patron_id
                            $sales .= $line['library_id'] . "#*#"; // library_id
                            fwrite($file, $sales . "\n");
                            $libSales = $libSales + $line['TrkCount'];
                            $numberOfSalesRecords++;
                        }

                        $numSales = $numSales + $libSales;
                    }

                    $market = "M#*#PM43#*#2222#*#" . $showStartDate . "#*#" . $showEndDate . "#*#";
                    $market .= "#*#"; // Vendor/Retailer Name was Library Ideas#*#
                    $market .= "#*#"; // Vendor Key was PM43#*#
                    $market .= "$country#*#11#*#100";
                    fwrite($file, $market . "\n");

                    $trailer = "Z#*#PM43#*#" . $showStartDate . "#*#" . $showEndDate . "#*#";
                    $trailer .= $numberOfSalesRecords . "#*#"; // Number of Standard Sales Records (total number of N records)
                    $trailer .= "1#*#"; // Number of Market Share Records (total number of M records)
                    $trailer .= $numSales . "#*#";
                    $trailer .= "0#*#"; // Total Quantity Free
                    $trailer .= "0#*#"; // Total Quantity Promo
                    $trailer .= "0"; // Total Quantity Returned
                    fwrite($file, $trailer);
                    fclose($file);
                }

            } // End first day of month report

        } // end of countries foreach loop

    } // end of libtype foreach loop

} else {
    echo "\nToday is not either the week first day or the month first day so the report didn't get generated.\n";
}

echo "\n----------- End -----------";

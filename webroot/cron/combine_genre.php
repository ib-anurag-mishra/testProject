<?php
/**
 * 
 *
 * @author Rob Richmond
 * @version $Id$
 * @copyright Maycreate Idea Group, 19 January, 2010
 * This cron script is intended to run every midnight to update the Album Image Path
 * Contract Start Date by adding One Year.
 * @package update_library_status_cron
 **/

error_reporting(E_ALL);
ini_set('display_errors', 2);
set_time_limit(0);

require "logfile.php";

$conn = mysql_connect("10.208.19.162","freegal_test","c45X^E1X7:TQ");
mysql_select_db("freegal", $conn);

$lf = new logfile();

// Read values from combine_genre table and store in associative array.
$syngenre_query    = "SELECT genre, expected_genre from combine_genre";
$rs_syngenre       = mysql_query($syngenre_query) or die('Query failed: ' . mysql_error());
$total_syngenres   = mysql_num_rows($rs_syngenre);

echo "<br>Total Syn Genres: ". $total_syngenres;
$lf->write("\nTotal Genres from combine_genre Table: ". $total_syngenres);

$combine_genre_arr = array();

for($count=0;$count<$total_syngenres; $count++)
{
    $row_data    =   mysql_fetch_array($rs_syngenre,MYSQL_ASSOC);
    $combine_genre_arr[$row_data['genre']] = $row_data['expected_genre'];
    //print_r($row_data);    
}


        //Find Total number of records in Genre Table
        $count_query        = "SELECT count(*) from Genre";
        $rs_count           = mysql_query($count_query) or die('Query failed: ' . mysql_error());
        $count_data         = mysql_fetch_array($rs_count, MYSQL_ASSOC);
        $total_genres       = $count_data['count(*)'];
        echo "<br>Total records in Genre Table: ". $total_genres;
        $lf->write("\nTotal records in Genre Table: ". $total_genres);

        
        //Find Total Iterations to update expected_genre column in Genre Table.
        $total_iterations   =   ceil($total_genres/10000);
               
        for($count=0; $count<$total_iterations; $count++)
        {
            $start_limit    =   10000*$count;
            $end_limit      =   ($start_limit+10000)-1;
            
            // echo "<br>Start Limit: ".$start_limit.", End Limit: ".$end_limit;
            // Fetch 10000 records from Genre table to update expected_genre Field.
            $genre_query    = "SELECT ProdID, Genre from Genre Limit ".$start_limit.", ".$end_limit;
            $rs_genre       = mysql_query($genre_query) or die('Query failed: ' . mysql_error());
            $tot_count      = mysql_num_rows($rs_genre);
            
            while ($rowData = mysql_fetch_array($rs_genre, MYSQL_ASSOC)) 
            {
                $value_to_be_updated    =  $combine_genre_arr[$rowData['Genre']]; // value from expected_genre field in combine_genre table
                //$genreUpdate_query      =  "Update Genre set expected_genre='".$value_to_be_updated."' where ProdID=".$rowData['ProdID']." and Genre='".$rowData['Genre']."'";
                //$rs_genre               =  mysql_query($genreUpdate_query) or die('Query failed: ' . mysql_error());
                echo "<br>Genre updated: From ". $rowData['Genre']." to ".$value_to_be_updated." having ProdID ".$rowData['ProdID'];
                $lf->write("\nGenre updated: From ". $rowData['Genre']." to ".$value_to_be_updated." having ProdID ".$rowData['ProdID']);
            }
            
            die;
        }
        
        echo "<br>Done With Updation";
        $lf->write("\nDone with Updation");

?>
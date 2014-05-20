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

$conn = mysql_connect("10.208.19.162","freegal_test","c45X^E1X7:TQ");       // Freegaldev
//$conn = mysql_connect("192.168.100.114","freegal_prod","}e47^B1EO9hD");       // Freegal Production
mysql_select_db("freegal", $conn);

$lf = new logfile();

// Read values from combine_genres table and store in associative array.
$syngenre_query    = "SELECT id, genre, expected_genre from combine_genres where update_genre=0";
$rs_syngenre       = mysql_query($syngenre_query) or die('Query failed: ' . mysql_error());
$total_syngenres   = mysql_num_rows($rs_syngenre);

echo "<br>Total Syn Genres: ". $total_syngenres;
$lf->write("\nTotal Genres from combine_genres Table: ". $total_syngenres);

$combine_genre_arr = array();


for($count=0;$count<$total_syngenres; $count++)
{
    $row_data    =   mysql_fetch_array($rs_syngenre,MYSQL_ASSOC);
    
    $id                     =  mysql_real_escape_string($row_data['id']);
    $current_genre_value    =  mysql_real_escape_string($row_data['genre']);
    $updated_genre_value    =  mysql_real_escape_string($row_data['expected_genre']);
   
    
    $genreUpdate_query       =  "Update Genre set expected_genre='".$updated_genre_value."' where Genre='".$current_genre_value."'";
    $rs_ugenre               =  mysql_query($genreUpdate_query) or die('Query failed: ' . mysql_error());
  
    
    $total_affected_rows    =   mysql_affected_rows();
    
    $lf->write("\nGenre updated: From ". $current_genre_value." to ".$updated_genre_value." Total Affected Rows: ".$total_affected_rows);
    
    if($total_affected_rows>0)
    {
        $cGenreUpdate_query       =  "Update combine_genres set update_genre='1' where id='".$id."'";
        $rs_ugenre                =  mysql_query($genreUpdate_query) or die('Query failed: ' . mysql_error());
        
        $lf->write("\nupdate_genre in combine_genres set to 1 for id: ". $id);
    }
}


$lf->write("\nDone with Updation");


?>
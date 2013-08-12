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
set_time_limit(0);
include 'functions.php';

$memcache = new Memcache;
//$memcache->addServer('10.178.4.51', 11211);
$memcache->connect('10.178.4.51', 11211) or die ("Could not connect to memcache server");


$query_count = "SELECT  count(*) 
from Albums as Album LEFT JOIN File AS FileInfo on Album.FileID=FileInfo.FileID where FileInfo.SourceURL!=''";

$result_count = mysql_query($query_count) or die('Query failed: ' . mysql_error());


if($AlbumDataCount = mysql_fetch_array($result_count, MYSQL_ASSOC))
{
    $Total_records      = $AlbumDataCount['count(*)'];
    $Total_iterations   = $Total_records / 10000;

        for($i=0;$i<$Total_iterations;$i++)
        {
                $temp  =   $i*10000;
                $query = "SELECT  Album.ProdID, Album.FileID,  FileInfo.SourceURL, FileInfo.CdnPath 
                from Albums as Album LEFT JOIN File AS FileInfo on Album.FileID=FileInfo.FileID where FileInfo.SourceURL!='' LIMIT ".$temp.", 10000";
                
                //echo "<br>Queyr: ".$query;

                $result = mysql_query($query) or die('Query failed: ' . mysql_error());

                while ($AlbumData = mysql_fetch_array($result, MYSQL_ASSOC)) 
                { 
                        //echo "<pre>"; print_r($line);                      
                        echo "<br>SR.NO.: ".$i.", ProdID: ".$AlbumData['ProdID'].", Path: ".$memcache->get("album_image_path" .$AlbumData['ProdID']);		
                }     
        }
        
        echo "<br><br>Done with Updation";
  }
else
{
                 echo "<br><br>No Records";
}

   



?>
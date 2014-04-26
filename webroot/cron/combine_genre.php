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

$conn = mysql_connect("10.208.19.162","freegal_test","c45X^E1X7:TQ");
mysql_select_db("freegal", $conn);

//$memcache = new Memcache;
////$memcache->addServer('10.178.4.51', 11211);
//$memcache->connect('10.178.4.51', 11211) or die ("Could not connect to memcache server");


$genre_query = "SELECT distinct Genre from Genre";
$rs_genre = mysql_query($genre_query) or die('Query failed: ' . mysql_error());
$distinct_genre = mysql_fetch_array($rs_genre, MYSQL_ASSOC);

print_r($distinct_genre); die;

if($total_genre['count(*)']>0)      // if count of Genres > 0
{    
    $total_iternations   = $total_genre['count(*)'] / 10000;

        for($i=0;$i<$total_iternations;$i++)
        {
                $temp  =   $i*10000;
                $query = "SELECT  Album.ProdID, Album.FileID,  FileInfo.SourceURL, FileInfo.CdnPath 
                from Albums as Album LEFT JOIN File AS FileInfo on Album.FileID=FileInfo.FileID where FileInfo.SourceURL!='' LIMIT ".$temp.", 10000";
                
                //echo "<br>Queyr: ".$query;

                $result = mysql_query($query) or die('Query failed: ' . mysql_error());

                while ($AlbumData = mysql_fetch_array($result, MYSQL_ASSOC)) 
                { 
                        //echo "<pre>"; print_r($line);    

                        $album_img =  shell_exec('perl ../files/tokengen_artwork ' . $AlbumData['CdnPath']."/".$AlbumData['SourceURL']);
                        $album_img =  "http://music.libraryideas.com/".$album_img;         

                        echo "<br>album_img: ".$album_img; 
                        echo "<BR>ProdID: album_image_path".$AlbumData['ProdID'].$memcache->delete("album_image_path" .$AlbumData['ProdID']);
                        echo "<br>SET: ".$memcache->set("album_image_path" .$AlbumData['ProdID'],$album_img,false,86400);		

                }     
        }
        
        echo "<br><br>Done with Updation";
        
  }
else
{
                 echo "<br><br>No Records";
}

   



?>
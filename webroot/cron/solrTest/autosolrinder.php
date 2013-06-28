<?php

echo '<br />Start : '.date('Y-m-d h:i:s').'<br />'; 

error_reporting(E_ALL);
ini_set('display_errors', '1');


// includes service file
require_once( __dir__.'/Apache/Solr/Service.php' );
//require_once( __dir__.'/SolrPhpClient/Apache/Solr/Service.php' );


// Try to connect to the named server, port, and url
$solr = new Apache_Solr_Service( '192.168.100.24', '8080', '/solr/freegalmusic/' );


if ( ! $solr->ping() ) {
  echo 'Solr service not responding.';
  exit;
}

// connect db3
$link = mysql_connect('10.181.56.177', 'freegal_test', 'c45X^E1X7:TQ');
if (!$link) {
    die('Could not connect to 10.181.56.177: ' . mysql_error());
}


//connect db
mysql_select_db('freegal', $link) or die('Could not select database.');


//collect Songs table data
$obj_resultset = mysql_query("SELECT ProdID, Title, Title as TTitle, Title as CTitle, Artist, Sample_Duration, FullLength_Duration, 
ISRC, ReferenceID, ArtistText, ArtistText as TArtistText, ArtistText as CArtistText, Sample_FileID, FullLength_FIleID, 
DownloadStatus, TrackBundleCount, SongTitle, SongTitle as TSongTitle, SongTitle as CSongTitle, Advisory, Genre, 
Genre as TGenre, Genre as CGenre, Composer, Composer as TComposer, Composer as CComposer, provider_type, 
CAST(CONCAT(ProdID,'-',provider_type) AS CHAR(50)) as ppjoin, CAST(CONCAT(ReferenceID,'-',provider_type) 
AS CHAR(50)) as rpjoin FROM Songs WHERE Sample_FileID IS NOT NULL AND DownloadStatus = '1' LIMIT 100");


$docs = array(); $cnt = 1;
while($arr_row = mysql_fetch_assoc( $obj_resultset )){

  $arr_docs['doc_'.$cnt] = $arr_row;
  $cnt++; 
}


//collect countries table data
foreach($arr_docs AS $key => $arr_row){

  $obj_resultset = mysql_query("SELECT Territory, SalesDate, CAST(concat(Territory,'_',SalesDate) AS CHAR(15)) as TerritorySalesDate FROM countries
WHERE ProdID = '".$arr_row['ProdID']."' AND provider_type = '".$arr_row['provider_type']."'");

  $arr_tmp = mysql_fetch_assoc( $obj_resultset );
  $arr_docs[$key] = array_merge($arr_docs[$key], $arr_tmp);
}


//collect Albums table data
foreach($arr_docs AS $key => $arr_row){

  $obj_resultset = mysql_query("SELECT Label, Label as CLabel, Advisory as AAdvisory FROM Albums WHERE ProdID = '".$arr_row['ReferenceID']."' 
AND provider_type = '".$arr_row['provider_type']."'");

  $arr_tmp = mysql_fetch_assoc( $obj_resultset );
  $arr_docs[$key] = array_merge($arr_docs[$key], $arr_tmp);
}


//collect File table data
foreach($arr_docs AS $key => $arr_row){

  $obj_resultset = mysql_query("SELECT CdnPath, SaveAsName FROM File WHERE FileID = '".$arr_row['Sample_FileID']."'");

  $arr_tmp = mysql_fetch_assoc( $obj_resultset );
  $arr_docs[$key] = array_merge($arr_docs[$key], $arr_tmp);
}

/*echo '<pre>';
print_r($arr_docs);
echo '</pre>;*/


//creates index document
$documents = array();
   
foreach ( $arr_docs as $item => $fields ) {
     
  $part = new Apache_Solr_Document();
     
  foreach ( $fields as $key => $value ) {
    if ( is_array( $value ) ) {
      foreach ( $value as $data ) {
        $part->setMultiValue( $key, $data );
      }
    }
    else {
      $part->$key = $value;
    }
  }
     
  $documents[] = $part;
}


/*echo '<pre>';
print_r($documents);
echo '</pre>';*/


//oad the documents into the index
foreach($documents AS $key => $song) {

  try {
    $solr->delete('<delete><query>ProdID:'.$song->ProdID.' AND provider_type:sony</query></delete>');
    $solr->commit();
//    $solr->optimize();
    $solr->addDocument($song);
    $solr->commit();
//    $solr->optimize();
  }
  catch ( Exception $e ) {
    echo "<br/>============================<br />";
    echo $song->ProdID."<br/>";
    echo $e->getMessage();
    echo "<br/>============================<br />";
  }


}


echo '<br />End : '.date('Y-m-d h:i:s').'<br />';


mysql_close($link);

?>


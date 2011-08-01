<?php
/**
 * 
 *
 * @author Rob Richmond
 * @version $Id$
 * This cron script is intended to run on a regular interval to create/update the home page suggestion songs random list of songs
 * @package update_suggestion_songs_cron
 **/
include 'functions.php';

if(!file_exists("../suggestion_xml")) {
        mkdir("../suggestion_xml");
}

$suggestionCounterQuery = "SELECT `Siteconfig`.`svalue` FROM `siteconfigs` AS `Siteconfig` WHERE `Siteconfig`.`soption` = 'suggestion_counter'";
$result = mysql_query($suggestionCounterQuery) or die('Query failed: ' . mysql_error());
$suggestionCounter = mysql_fetch_array($result, MYSQL_ASSOC);

$doc = new DomDocument('1.0', 'UTF-8');
$doc->formatOutput = true;

$root = $doc->createElement('suggestionsongs');
$root = $doc->appendChild($root);

$suggestionSongsQuery = "SELECT  * FROM Songs
                         WHERE DownloadStatus = '1' AND TrackBundleCount = '0' AND
                         Advisory = 'F' ORDER BY rand() ASC LIMIT ".$suggestionCounter['svalue'];				 
$songsresult = mysql_query($suggestionSongsQuery) or die('Query failed: ' . mysql_error());
while ($line = mysql_fetch_array($songsresult, MYSQL_ASSOC)) {
    $child = $doc->createElement("songdetails");
    $child = $root->appendChild($child);
    
    $sub_child = $doc->createElement("ProdID");
    $sub_child = $child->appendChild($sub_child);
    $value = $doc->createTextNode($line['ProdID']);
    $value = $sub_child->appendChild($value);
    
    $sub_child = $doc->createElement("Title");
    $sub_child = $child->appendChild($sub_child);
    $value = $doc->createTextNode($line['SongTitle']);
    $value = $sub_child->appendChild($value);
    
    $sub_child = $doc->createElement("ReferenceID");
    $sub_child = $child->appendChild($sub_child);
    $value = $doc->createTextNode($line['ReferenceID']);
    $value = $sub_child->appendChild($value);
    
    $sub_child = $doc->createElement("Artist");
    $sub_child = $child->appendChild($sub_child);
    $value = $doc->createTextNode($line['Artist']);
    $value = $sub_child->appendChild($value);
    
    $sub_child = $doc->createElement("ArtistText");
    $sub_child = $child->appendChild($sub_child);
    $value = $doc->createTextNode($line['ArtistText']);
    $value = $sub_child->appendChild($value);
    
    $fileQuery = "SELECT `Files`.`CdnPath`, `Files`.`SaveAsName` FROM `File` AS `Files` WHERE `Files`.`FileID` = ".$line['Sample_FileID'];
    $fileResult = mysql_query($fileQuery) or die('Query failed: ' . mysql_error());
    $fileResults = mysql_fetch_array($fileResult, MYSQL_ASSOC);
    
    $sub_child = $doc->createElement("CdnPath");
    $sub_child = $child->appendChild($sub_child);
    $value = $doc->createTextNode($fileResults['CdnPath']);
    $value = $sub_child->appendChild($value);
    
    $sub_child = $doc->createElement("SaveAsName");
    $sub_child = $child->appendChild($sub_child);
    $value = $doc->createTextNode($fileResults['SaveAsName']);
    $value = $sub_child->appendChild($value);

    $countryQuery = "SELECT * FROM `countries` WHERE `ProdID` =".$line['ProdID']." LIMIT 1";
    $countryResult = mysql_query($countryQuery) or die('Query failed: ' . mysql_error());
    $countryResults = mysql_fetch_array($countryResult, MYSQL_ASSOC);
    
    $sub_child = $doc->createElement("Territory");
    $sub_child = $child->appendChild($sub_child);
    $value = $doc->createTextNode($countryResults['Territory']);
    $value = $sub_child->appendChild($value);
	
}

if($doc->save("../suggestion_xml/suggestion_songs.xml")) {
    echo 'Suggestion songs XML generated/updated successfully!';
}
else {
    echo 'There is some occurred while creating/updating the suggestion songs XML !';
}
?>
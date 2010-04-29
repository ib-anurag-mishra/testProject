<?php
/**
 * 
 *
 * @author Rob Richmond
 * @version $Id$
 * @copyright Maycreate Idea Group, 19 January, 2010
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

$suggestionSongsQuery = "SELECT  `Physicalproduct`.`ProdID`, `Physicalproduct`.`Title`, `Physicalproduct`.`ReferenceID`,
                            `Physicalproduct`.`ArtistText`, `Physicalproduct`.`DownloadStatus`, `Physicalproduct`.`SalesDate`,
                            `Metadata`.`Title`, `Metadata`.`Artist`, `Metadata`.`Advisory` FROM `PhysicalProduct` AS `Physicalproduct`
                            LEFT JOIN `METADATA` AS `Metadata` ON (`Metadata`.`ProdID` = `Physicalproduct`.`ProdID`)
                            WHERE `Physicalproduct`.`ReferenceID` <> `Physicalproduct`.`ProdID` AND
                            `Physicalproduct`.`DownloadStatus` = 1 AND `Physicalproduct`.`TrackBundleCount` = 0 AND
                            `Metadata`.`Advisory` = 'F' ORDER BY rand() ASC LIMIT ".$suggestionCounter['svalue'];
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
    $value = $doc->createTextNode($line['Title']);
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
    
    $audioQuery = "SELECT `Audio`.`FileID`, `Audio`.`TrkID` FROM `Audio` AS `Audio` WHERE `Audio`.`TrkID` = ".$line['ProdID'];
    $audioResult = mysql_query($audioQuery) or die('Query failed: ' . mysql_error());
    $audioResults = mysql_fetch_array($audioResult, MYSQL_ASSOC);
    
    $fileQuery = "SELECT `Files`.`CdnPath`, `Files`.`SaveAsName` FROM `File` AS `Files` WHERE `Files`.`FileID` = ".$audioResults['FileID'];
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
}

if($doc->save("../suggestion_xml/suggestion_songs.xml")) {
    echo 'Suggestion songs XML generated/updated successfully!';
}
else {
    echo 'There is some occurred while creating/updating the suggestion songs XML !';
}
?>
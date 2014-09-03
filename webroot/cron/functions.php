<?php
/**
File Name : functions.php
File Description : Contains all the necessary function for the xml parser
@author : Maycreate
**/
include 'config.php';
include 'dbconnect.php';

/*
Function Name : sendReportFileftp_US
Description : Function for sending report through FTP for US Libraies
*/

function sendReportFileftp($src,$dst,$logFileWrite,$typeReport)
{

	if(!($con = ftp_connect(REPORTS_SFTP_HOST,REPORTS_SFTP_PORT)))
	{
		echo "Not Able to Establish Connection\n";
		return false;
	}
	else
	{
		if(!ftp_login($con,REPORTS_SFTP_USER,REPORTS_SFTP_PASS))
		{
			echo "fail: unable to authenticate\n";
			return false;
		}
		else
		{
			ftp_pasv($con, true);
			if(!is_dir("ftp.".REPORTS_SFTP_PATH.$typeReport."/"))
			{
				ftp_mkdir($con,REPORTS_SFTP_PATH.$typeReport."/");
			}
			if(!ftp_put($con,REPORTS_SFTP_PATH.$typeReport."/".$dst,$src, FTP_BINARY)){
				echo "error sending " . $typeReport . " report to Sony server\n";
				fwrite($logFileWrite, "error sending " . $typeReport . " report to Sony server\n");
				return false;
			}
			else
			{
				echo ucfirst($typeReport) . " Report Sucessfully sent\n";
				fwrite($logFileWrite, ucfirst($typeReport) . " Report Sucessfully sent\n");
				sendFile($src, $dst);
				sendReportEmail("US ".$typeReport);
				return true;
			}
		}
	}
}

/*
Function Name : sendReportFileftp_CA
Description : Function for sending report through FTP for Canadian Libraies
*/

function sendReportFileftp_CA($src,$dst,$logFileWrite,$typeReport)
{

	if(!($con = ftp_connect(REPORTS_SFTP_HOST_CA,REPORTS_SFTP_PORT_CA)))
	{
		echo "Not Able to Establish Connection\n";
		return false;
	}
	else
	{
		if(!ftp_login($con,REPORTS_SFTP_USER_CA,REPORTS_SFTP_PASS_CA))
		{
			echo "fail: unable to authenticate\n";
			return false;
		}
		else
		{
			ftp_pasv($con, true);
			if(!is_dir("ftp.".REPORTS_SFTP_PATH_CA.$typeReport."/"))
			{
				ftp_mkdir($con,REPORTS_SFTP_PATH_CA.$typeReport."/");
			}
			if(!ftp_put($con,REPORTS_SFTP_PATH_CA.$typeReport."/".$dst,$src, FTP_BINARY)){
				echo "error sending " . $typeReport . " report to Sony server\n";
				fwrite($logFileWrite, "error sending " . $typeReport . " report to Sony server\n");
				return false;
			}
			else
			{
				echo ucfirst($typeReport) . " Report Sucessfully sent\n";
				fwrite($logFileWrite, ucfirst($typeReport) . " Report Sucessfully sent\n");
				sendFile($src, $dst);
				sendReportEmail("Canadian ".$typeReport);
				return true;
			}
		}
	}
}

/*
Function Name : sendReportFile
Description : Function for sending report through SFTP
*/

function sendReportFilesftp($src,$dst,$logFileWrite,$typeReport)
{
    if(strpos($src,"PM43_M_"))
    {
        $name = explode("PM43_M_", $src);
        $reportName = "PM43_M_".$name[1];
    }
    else
    {
        $name = explode("PM43_W_", $src);  
        $reportName = "PM43_W_".$name[1];
    }
    //$reportName = "PM43_W_".$name[1];
    //$showEndDate = date('Ymd', strtotime($currentDate." last sunday") );
    //$reportName = explode($showEndDate, $reportName);
    $reportName = str_replace(".txt","",$reportName);
    $reportName = ltrim(str_replace("_"," ",$reportName));

    if(!($con = ssh2_connect(REPORTS_SFTP_HOST,REPORTS_SFTP_PORT)))
    {
        echo "Not Able to Establish Connection\n";
        return false;
    }
    else
    {
        if(!ssh2_auth_password($con,REPORTS_SFTP_USER,REPORTS_SFTP_PASS))
        {
            echo "fail: unable to authenticate\n";
            return false;
        }
        else
        {
            $sftp = ssh2_sftp($con);
            if(!is_dir("ssh2.sftp://$sftp".REPORTS_SFTP_PATH.$typeReport."/"))
            {
                ssh2_sftp_mkdir($sftp,REPORTS_SFTP_PATH.$typeReport."/");
            }

            if(!ssh2_scp_send($con, $src, REPORTS_SFTP_PATH.$typeReport."/".$dst, 0644)){
                echo "error sending " . $typeRepport . " report to Sony server\n";
                fwrite($logFileWrite, "error sending " . $typeRepport . " report to Sony server\n");
                return false;
            }
            else
            {
                echo ucfirst($typeReport) . " Report Sucessfully sent\n";
                fwrite($logFileWrite, ucfirst($typeReport) . " Report Sucessfully sent\n");
                sendFile($src, $dst);
                sendReportEmail($typeReport, $reportName);
                return true;
            }
        }
    }
}

function resetDownloads()
{
    date_default_timezone_set("America/New_York");
    $currentDate = date('Y-m-d');
    $nextDayTS = strtotime($currentDate); 
    $nextDay = date('Y-m-d', strtotime('+1 day', $nextDayTS));    
    $date = date('y-m-d');
    list($year, $month, $day) = explode('-', $date);
    $weekFirstDay = date('Y-m-d', strtotime(date('Y')."W".date('W')."1"));
    $monthFirstDate = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
    $yearFirstDate = date('Y-m-d', mktime(0, 0, 0, 1, 1, $year));      
    $qry = "Select * from libraries";
    $results = mysql_query($qry);
    while($resultsArr = mysql_fetch_assoc($results))
    {
        $downloadType = $resultsArr['library_download_type'];	
        if($downloadType == "daily")
        {
            $sql = "UPDATE `libraries` SET `library_current_downloads` = '0' WHERE `libraries`.`id` =".$resultsArr['id'];
            echo date("Y-m-d H:i:s")." - Daily basis library_current_downloads variable updated successfully for Library ID ".$resultsArr['id']." to 0 !!\n";
            mysql_query($sql);            
        }
        else if($downloadType == "weekly")
        {
            if($currentDate == $weekFirstDay)
            {
                $sql = "UPDATE `libraries` SET `library_current_downloads` = '0' WHERE `libraries`.`id` =".$resultsArr['id'];
                echo date("Y-m-d H:i:s")." - Weekly basis library_current_downloads variable updated successfully for Library ID ".$resultsArr['id']." to 0 !!\n";
                mysql_query($sql);
            }
        }
        else if($downloadType == "monthly")
        {
            if($currentDate == $monthFirstDate)
            {
                $sql = "UPDATE `libraries` SET `library_current_downloads` = '0' WHERE `libraries`.`id` =".$resultsArr['id'];
                echo date("Y-m-d H:i:s")." - Monthly basis library_current_downloads variable updated successfully for Library ID ".$resultsArr['id']." to 0 !!\n";

                mysql_query($sql);
            }
        }
/*
        $libraryId = $resultsArr['id'];	
        $sql = "SELECT count(*) as count from wishlists where `delete_on` <= '".$currentDate."' AND `delete_on` != '0000-00-00' AND `library_id` = ".$libraryId;	
        $result = mysql_query($sql);
        $row = mysql_fetch_assoc($result);
        $count = $row['count'];	
        $sql="UPDATE `libraries` SET library_available_downloads=library_available_downloads+".$count." Where id=".$libraryId;	
        mysql_query($sql);
        $qry = "Delete from wishlists where `delete_on` <= '".$currentDate."' AND `delete_on` != '0000-00-00' AND library_id=".$libraryId;
        mysql_query($qry);

        if(($resultsArr['library_available_downloads'] > 0) && ($resultsArr['library_download_limit'] > $resultsArr['library_current_downloads'])){		
            $qry = "UPDATE wishlists SET `delete_on` = '".$nextDay."' WHERE `library_id` = ".$libraryId;		
            mysql_query($qry);
        } 
 * 
 */
    }
}

/*
Function Name : sendReportEmail
Description : Function for sending Email for Reports
*/

function sendReportEmail($typereport, $reportName){
    $subject = $reportName." ".$typereport." ".REPORT_SUBJECT;
    $body = $reportName." ".$typereport." ".REPORT_BODY;
    $success = mail(REPORT_TO,$subject,$body,REPORT_HEADERS);
    return $success;
}

function sendalert($message, $subject="")
{
    if($subject=="")
    {
        $subject = "Monthly-Weekly Report failed";  
    }

    $success = mail(REPORT_TO,$subject,$message,REPORT_HEADERS);
    return $success;
}

/*
 Function Name : sendFile
 Desc : function used for uploading the file to CDN
*/
function sendFile($src,$dst)
{
	$SFTP_HOST = SFTP_HOST;
	$SFTP_PORT = SFTP_PORT;
	$SFTP_USER = SFTP_USER;
	$SFTP_PASS = SFTP_PASS;
//	$CdnPath = '/published/freegalmusic_reports/sony_reports/';
        $CdnPath = '/published/freegalmusic/prod/EN/sony_reports/';
	
	if(!($con = ssh2_connect($SFTP_HOST,$SFTP_PORT)))
	{
		echo "Not Able to Establish Connection\n";
	}
	else
	{
		if(!ssh2_auth_password($con,$SFTP_USER,$SFTP_PASS))
		{
			echo "fail: unable to authenticate\n";
		}
		else
		{
			$sftp = ssh2_sftp($con);
                        /*echo $src."\n";
                        echo $dst."\n";
                        echo $con;*/
			if(!ssh2_scp_send($con, $src, $CdnPath.$dst, 0644)){
				echo "error\n";
			}
			else
			{
				echo "FILE Sucessfully sent\n";
			}

		}
	}
}

function xml2array($contents, $get_attributes=1, $priority = 'tag') {
    if(!$contents) return array();

    if(!function_exists('xml_parser_create')) {
        //print "'xml_parser_create()' function not found!";
        return array();
    }

    //Get the XML parser of PHP - PHP must have this module for the parser to work
    $parser = xml_parser_create('');
    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($contents), $xml_values);
    xml_parser_free($parser);

    if(!$xml_values) return;//Hmm...

    //Initializations
    $xml_array = array();
    $parents = array();
    $opened_tags = array();
    $arr = array();

    $current = &$xml_array; //Refference

    //Go through the tags.
    $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
    foreach($xml_values as $data) {
        unset($attributes,$value);//Remove existing values, or there will be trouble

        //This command will extract these variables into the foreach scope
        // tag(string), type(string), level(int), attributes(array).
        extract($data);//We could use the array by itself, but this cooler.

        $result = array();
        $attributes_data = array();
        
        if(isset($value)) {
            if($priority == 'tag') $result = $value;
            else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
        }

        //Set the attributes too.
        if(isset($attributes) and $get_attributes) {
            foreach($attributes as $attr => $val) {
                if($priority == 'tag') $attributes_data[$attr] = $val;
                else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
            }
        }

        //See tag status and do the needed.
        if($type == "open") {//The starting of the tag '<tag>'
            $parent[$level-1] = &$current;
            if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                $current[$tag] = $result;
                if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
                $repeated_tag_index[$tag.'_'.$level] = 1;

                $current = &$current[$tag];

            } else { //There was another element with the same tag name

                if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                    $repeated_tag_index[$tag.'_'.$level]++;
                } else {//This section will make the value an array if multiple tags with the same name appear together
                    $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
                    $repeated_tag_index[$tag.'_'.$level] = 2;
                    
                    if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                        $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                        unset($current[$tag.'_attr']);
                    }

                }
                $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
                $current = &$current[$tag][$last_item_index];
            }

        } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
            //See if the key is already taken.
            if(!isset($current[$tag])) { //New Key
                $current[$tag] = $result;
                $repeated_tag_index[$tag.'_'.$level] = 1;
                if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

            } else { //If taken, put all things inside a list(array)
                if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

                    // ...push the new element into that array.
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                    
                    if($priority == 'tag' and $get_attributes and $attributes_data) {
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag.'_'.$level]++;

                } else { //If it is not an array...
                    $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                    $repeated_tag_index[$tag.'_'.$level] = 1;
                    if($priority == 'tag' and $get_attributes) {
                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            
                            $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                            unset($current[$tag.'_attr']);
                        }
                        
                        if($attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                        }
                    }
                    $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
                }
            }

        } elseif($type == 'close') { //End of tag '</tag>'
            $current = &$parent[$level-1];
        }
    }
    
    return($xml_array);
}

?>
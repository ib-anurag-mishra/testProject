<?php
/**
File Name : functions.php
File Description : Contains all the necessary function for the xml parser
@author : Maycreate
**/
include 'config.php';
include 'dbconnect.php';

function sendReportFile($src,$dst,$logFileWrite,$typeReport)
{
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
			if(!is_dir("ssh2.sftp://$sftp".REPORTS_SFTP_PATH.$typeReport))
			{
				ssh2_sftp_mkdir($sftp,REPORTS_SFTP_PATH.$typeReport);
			}

			if(!ssh2_scp_send($con, $src, REPORTS_SFTP_PATH.$typeReport.$dst, 0644)){
				echo "error sending " . $typeRepport . " report to Sony server\n";
				fwrite($logFileWrite, "error sending " . $typeRepport . " report to Sony server\n");
				return false;
			}
			else
			{
				echo ucfirst($typeReport) . " Report Sucessfully sent\n";
				fwrite($logFileWrite, ucfirst($typeReport) . " Report Sucessfully sent\n");
				return true;
			}
		}
	}
}

function resetDownloads()
{
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
	    mysql_query($sql);            
	}
	else if($downloadType == "weekly")
	{
	    if($currentDate == $weekFirstDay)
	    {
		$sql = "UPDATE `libraries` SET `library_current_downloads` = '0' WHERE `libraries`.`id` =".$resultsArr['id'];
		mysql_query($sql);
	    }
	}
	else if($downloadType == "monthly")
	{
	    if($currentDate == $monthFirstDate)
	    {
		$sql = "UPDATE `libraries` SET `library_current_downloads` = '0' WHERE `libraries`.`id` =".$resultsArr['id'];
		mysql_query($sql);
	    }
	}
	else if($downloadType == "anually")
	{
	    if($currentDate == $yearFirstDate)
	    {
		$sql = "UPDATE `libraries` SET `library_current_downloads` = '0' WHERE `libraries`.`id` =".$resultsArr['id'];
		mysql_query($sql);
	    }
	}
	$libraryId = $resultsArr['id'];	
	$sql = "SELECT count(*) as count from wishlists where `delete_on` ='".$currentDate."' and `library_id` = ".$libraryId;	
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	$count = $row['count'];	
	$sql="UPDATE `libraries` SET library_available_downloads=library_available_downloads+".$count." Where id=".$libraryId;	
        mysql_query($sql);
	$qry = "Delete from wishlists where delete_on ='".$currentDate."'";	
	mysql_query($qry);
	if(($resultsArr['library_available_downloads'] > 0) && ($resultsArr['library_download_limit'] > $resultsArr['library_current_downloads'])){		
		$qry = "UPDATE wishlists SET `delete_on` = '".$nextDay."' WHERE `library_id` = ".$libraryId;		
		mysql_query($qry);
	}	
    }     
}
?>
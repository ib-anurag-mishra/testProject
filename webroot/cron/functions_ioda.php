<?php

/**
  File Name : functions.php
  File Description : Contains all the necessary function for the xml parser
  @author : Maycreate
 * */
//include 'config.php';
//include 'dbconnect.php';

/*
  Function Name : sendReportFileftp_US
  Description : Function for sending report through FTP for US Libraies
 */

function sendReportFileftp($src, $dst, $logFileWrite, $typeReport)
{

    if (!($con = ftp_connect(REPORTS_SFTP_HOST, REPORTS_SFTP_PORT)))
    {
        echo "Not Able to Establish Connection with The Orchard using ftp. \n";
        return false;
    }
    else
    {
        if (!ftp_login($con, REPORTS_SFTP_USER, REPORTS_SFTP_PASS))
        {
            echo "fail: unable to authenticate with The Orchard using ftp.\n";
            return false;
        }
        else
        {
            ftp_pasv($con, true);
            if (!is_dir("ftp." . REPORTS_SFTP_PATH . "uploads/"))
            {
                ftp_mkdir($con, REPORTS_SFTP_PATH . "uploads/");
            }
            if (!ftp_put($con, REPORTS_SFTP_PATH . "uploads/" . $dst, $src, FTP_BINARY))
            {
                echo "error sending " . $src . " to " . REPORTS_SFTP_PATH . "uploads/" . $dst . " " . $typeReport . " report to IODA server\n";
                fwrite($logFileWrite, "error sending " . $typeReport . " report to IODA server\n");
                return false;
            }
            else
            {
                echo ucfirst($typeReport) . " Report Sucessfully sent\n";
                fwrite($logFileWrite, ucfirst($typeReport) . " Report Sucessfully sent\n");
                sendFile($src, $dst);
                sendReportEmail($typeReport, $dst);
                return true;
            }
        }
    }
}

/*
  Function Name : sendReportFileftp_CA
  Description : Function for sending report through FTP for Canadian Libraies
 */

/* function sendReportFileftp_CA($src,$dst,$logFileWrite,$typeReport)
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
  echo "error sending " . $typeReport . " report to IODA server\n";
  fwrite($logFileWrite, "error sending " . $typeReport . " report to IODA server\n");
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
  } */

/*
  Function Name : sendReportFile
  Description : Function for sending report through SFTP
 */

function sendReportFilesftp($src, $dst, $logFileWrite, $typeReport)
{
    if (!($con = ssh2_connect(REPORTS_SFTP_HOST, REPORTS_SFTP_PORT)))
    {
        echo "Not Able to Establish Connection with The Orchard SFTP\n";
        return false;
    }
    else
    {
        if (!ssh2_auth_password($con, REPORTS_SFTP_USER, REPORTS_SFTP_PASS))
        {
            echo "fail: unable to authenticate with The Orchard SFTP\n";
            return false;
        }
        else
        {

            // Create SFTP session
            $sftp = ssh2_sftp($con);
            $sftpStream = fopen('ssh2.sftp://' . $sftp . '/' . $dst, 'w');

            try
            {
                if (!$sftpStream)
                {
                    throw new Exception("Could not open remote file: $dst");
                }

                $data_to_send = file_get_contents($src);
                if ($data_to_send === false)
                {
                    throw new Exception("Could not open local file: $src.");
                }

                if (fwrite($sftpStream, $data_to_send) === false)
                {
                    throw new Exception("Could not send data from file: $src.");
                }

                fclose($sftpStream);

                echo ucfirst($typeReport) . " Report Sucessfully sent\n";
                fwrite($logFileWrite, ucfirst($typeReport) . " Report Sucessfully sent\n");


                return true;
            }
            catch (Exception $e)
            {
                echo "error sending $src report to /$dst report to IODA server\n";
                fwrite($logFileWrite, "error sending " . $typeReport . " report to IODA server\n");
                echo 'Exception: ' . $e->getMessage();
                fclose($sftpStream);
                return false;
            }


//			if(!ssh2_scp_send($con, $src, "/".$dst, 0644)){
//				echo "error sending $src report to /$dst report to IODA server\n";
//				fwrite($logFileWrite, "error sending " . $typeReport . " report to IODA server\n");
//				return false;
//			}
//			else
//			{
//				echo ucfirst($typeReport) . " Report Sucessfully sent\n";
//				fwrite($logFileWrite, ucfirst($typeReport) . " Report Sucessfully sent\n");
//                                sendFile($src, $dst);
//				sendReportEmail($typeReport, $dst);
//				return true;
//			}
        }
    }
}

/* function resetDownloads()
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
  }
  } */

/*
  Function Name : sendReportEmail
  Description : Function for sending Email for Reports
 */

function sendReportEmail($typereport, $dst, $message)
{
    $subject = $typereport . REPORT_SUBJECT;
    $success = mail(REPORT_TO, $subject, $dst . " " . $message . REPORT_BODY, REPORT_HEADERS);
    return $success;
}

/*
  Function Name : sendFile
  Desc : function used for uploading the file to CDN
 */

function sendFile($src, $dst)
{
    $SFTP_HOST = SFTP_HOST;
    $SFTP_PORT = SFTP_PORT;
    $SFTP_USER = SFTP_USER;
    $SFTP_PASS = SFTP_PASS;
    $CdnPath = '/published/freegalmusic/prod/EN/ioda_reports/';

    if (!($con = ssh2_connect($SFTP_HOST, $SFTP_PORT)))
    {
        echo "Not Able to Establish Connection with CDN.\n";
    }
    else
    {
        if (!ssh2_auth_password($con, $SFTP_USER, $SFTP_PASS))
        {
            echo "fail: unable to authenticate with CDN.\n";
        }
        else
        {
            $sftp = ssh2_sftp($con);
            if (!ssh2_scp_send($con, $src, $CdnPath . $dst, 0644))
            {
                echo "error\n";
                return false;
            }
            else
            {
                echo "FILE Sucessfully sent to CDN\n";
                return true;
            }
        }
    }
}

function getFileNameCDN($library_territory, $from_date, $libTypeKey, $version)
{
    $file_name = "Freegal_r_" . strtolower($library_territory) . "_" . date('Ym', strtotime($from_date)) . '_' . $libTypeKey . "_v$version" . ".txt";

    $SFTP_HOST = SFTP_HOST;
    $SFTP_PORT = SFTP_PORT;
    $SFTP_USER = SFTP_USER;
    $SFTP_PASS = SFTP_PASS;
    $CdnPath = '/published/freegalmusic/prod/EN/ioda_reports/';

    if (!($con = ssh2_connect($SFTP_HOST, $SFTP_PORT)))
    {
        echo "Not Able to Establish Connection with CDN.\n";
    }
    else
    {
        if (!ssh2_auth_password($con, $SFTP_USER, $SFTP_PASS))
        {
            echo "fail: unable to authenticate with CDN.\n";
        }
        else
        {
            $sftp = ssh2_sftp($con);

            while (1)
            {
                echo $CdnPath . $file_name;
                if (file_exists('ssh2.sftp://' . $sftp . $CdnPath . $file_name))
                {
                    $version++;
                    echo $file_name = "Freegal_r_" . strtolower($library_territory) . "_" . date('Ym', strtotime($from_date)) . '_' . $libTypeKey . "_v$version" . ".txt";
                }
                else
                {
                    break;
                }
            }
        }
    }

    return $file_name;
}

function getFileNameDB($library_territory, $from_date, $libTypeKey, $version, $db)
{
    $file_name = "Freegal_r_" . strtolower($library_territory) . "_" . date('Ym', strtotime($from_date)) . '_' . $libTypeKey . "_v$version" . ".txt";

    while (1)
    {
        $query = "SELECT * FROM freegal.ioda_reports where report_name='$file_name' ";
        $file_found = mysql_query($query, $db);

        if (mysql_num_rows($file_found) == 0)
        {
            break;
        }
        else
        {
            $version++;
            $file_name = "Freegal_r_" . strtolower($library_territory) . "_" . date('Ym', strtotime($from_date)) . '_' . $libTypeKey . "_v$version" . ".txt";
        }
    }
    return $file_name;
}

function write_file($content, $file_name, $folder, $db)
{
    $outputFile = "iodareports_output_" . date('Y_m_d_h_i_s') . ".txt";
    $logFileWrite = fopen(IMPORTLOGS . $outputFile, 'w') or die("Can't Open the file!");

    if (count($content[1]) > 1)
    {
        echo $file = $folder . $file_name;
        $fh = fopen($file, 'w') or die("can't open file");
        foreach ($content as $data)
        {
            $delimiter = "\t";
            $titleString = implode($delimiter, $data[0]);
            unset($data[0]);
            fwrite($fh, $titleString . "\n");
            foreach ($data as $subArray)
            {
                $dataRowString = implode($delimiter, $subArray);
                fwrite($fh, $dataRowString . "\n");
            }
        }
        fclose($fh);

        $status_message = '';

        $cdn_status = sendFile($file, $file_name);
        if ($cdn_status)
        {
            $update_query = "UPDATE `freegal`.`ioda_reports` SET `report_cdn_uploaded`='1' , modified=now() WHERE `report_name`='$file_name' ";
            mysql_query($update_query, $db);
            fwrite($logFileWrite, "$file_name uploaded on CDN \n");
            $status_message .="$file_name uploaded on CDN \n";
        }
        else
        {
            fwrite($logFileWrite, "$file_name not uploaded on CDN \n");
            $status_message .="$file_name not uploaded on CDN \n";
        }

        $ioda_status = sendReportFileIODA($file, $file_name, $logFileWrite, "monthly");
        if ($ioda_status)
        {
            $update_query = "UPDATE `freegal`.`ioda_reports` SET `report_send_ioda`='1' , modified=now() WHERE `report_name`='$file_name' ";
            mysql_query($update_query, $db);
            fwrite($logFileWrite, "$file_name uploaded on IODA SERVER \n");
            $status_message .="$file_name uploaded on IODA SERVER \n";
        }
        else
        {
            fwrite($logFileWrite, "$file_name not uploaded on IODA SERVER\n");
            $status_message .="$file_name not uploaded on IODA SERVER \n";
        }

        if ($cdn_status && $ioda_status)
        {
            echo exec(" rm  " . $file);
        }
        else
        {
            fwrite($logFileWrite, "$file_name not uploaded on SERVER. Not deleted. \n");
        }
        sendReportEmail("monthly", $file_name);
    }
    else
    {
        fwrite($logFileWrite, "Array is empty \n");
    }
    fclose($logFileWrite);
}

function sendReportFileIODA($src, $dst, $logFileWrite, $typeReport)
{
    if (!($con = ssh2_connect(REPORTS_SFTP_HOST, REPORTS_SFTP_PORT)))
    {
        fwrite($logFileWrite, "Not Able to Establish Connection with The Orchard SFTP \n");
        return false;
    }
    else
    {
        if (!ssh2_auth_password($con, REPORTS_SFTP_USER, REPORTS_SFTP_PASS))
        {
            fwrite($logFileWrite, "fail: unable to authenticate with The Orchard SFTP \n");
            return false;
        }
        else
        {
            // Create SFTP session
            $sftp = ssh2_sftp($con);
            $sftpStream = fopen('ssh2.sftp://' . $sftp . '/' . $dst, 'w');

            try
            {
                if (!$sftpStream)
                {
                    fwrite($logFileWrite, "Could not open remote file: $dst. \n");
                    throw new Exception("Could not open remote file: $dst.");
                }

                $data_to_send = file_get_contents($src);
                if ($data_to_send === false)
                {
                    fwrite($logFileWrite, "Could not open local file: $src \n");
                    throw new Exception("Could not open local file: $src.");
                }

                if (fwrite($sftpStream, $data_to_send) === false)
                {
                    fwrite($logFileWrite, "Could not send data from file: $src \n");
                    throw new Exception("Could not send data from file: $src.");
                }

                fwrite($logFileWrite, ucfirst($typeReport) . " Report Sucessfully sent\n");
                fclose($sftpStream);
                echo ucfirst($typeReport) . " Report Sucessfully sent\n";
                return true;
            }
            catch (Exception $e)
            {
                echo "error sending $src report to /$dst report to IODA server\n";
                echo 'Exception: ' . $e->getMessage();
                fwrite($logFileWrite, "error sending " . $typeReport . " report to IODA server\n" . 'Exception: ' . $e->getMessage() . "\n");
                fclose($sftpStream);
                return false;
            }
        }
    }
}

?>

<?php
/**
File Name : functions.php
File Description : Contains all the necessary function for the xml parser
@author : Maycreate
**/

function sendReportFile($src,$dst,$logFileWrite)
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
			if(!is_dir("ssh2.sftp://$sftp".REPORTS_SFTP_PATH."sony_reports/"))
			{
				ssh2_sftp_mkdir($sftp,REPORTS_SFTP_PATH."sony_reports/");
			}

			if(!ssh2_scp_send($con, $src, REPORTS_SFTP_PATH."sony_reports/".$dst, 0644)){
				echo "error sending report to Sony server\n";
				fwrite($logFileWrite, "error sending report to Sony server\n");
				return false;
			}
			else
			{
				echo "Report Sucessfully sent\n";
				fwrite($logFileWrite, "Report Sucessfully sent\n");
				return true;
			}
		}
	}
}

?>
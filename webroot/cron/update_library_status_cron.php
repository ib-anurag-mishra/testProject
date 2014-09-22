<?php
/**
 * 
 *
 * @author Rob Richmond
 * @version $Id$
 * @copyright Maycreate Idea Group, 19 January, 2010
 * This cron script is intended to run every midnight to update the libraries Activate or Deactivate status based on their
 * Contract Start Date by adding One Year.
 * @package update_library_status_cron
 **/
        include 'functions.php';

        $query = 'SELECT * FROM `libraries`';
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());

        $mailContent     = '';
        $send_mail       = 0;

        while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) 
        {
                if($line['library_status_updated_by'] == 'cron') 
                {
                        $currDate = strtotime(date("Y-m-d"));
                        $contractEndDate = strtotime($line['library_contract_end_date']);

                        if($contractEndDate >= $currDate) 
                        {
                                $status = "active";
                        }
                        else 
                        {
                                $status = "inactive";
                                $library_name   =   $line['library_name'];

                                if($line['library_status']=='active')       // Send mail containing details of those libraries whose status is changed from active to inactive
                                {
                                    $send_mail      = 1;
                                    $mailContent   .=   $library_name. " (ID: ".$line['id'].", library_contract_end_date: ".$contractEndDate.") is set inactive.\n";                        
                                }
                        }

                        $sql = "UPDATE libraries SET library_status='$status' WHERE id=".$line['id'];
                        $result2 = mysql_query($sql) or die('Query failed: ' . mysql_error());
                        echo date("Y-m-d H:i:s")." - Library satus updated successfully for Library ID ".$line['id']." to $status !!\n";
                }
                else 
                {
                        echo date("Y-m-d H:i:s")." - Library ID ".$line['id']." status not changed as it has been modified by the admin!!\n";
                }
        }

        if($send_mail)
        {    
            $mailText        .=   "Hi,\n\n";
            $mailText        .=   "Following is list of libraries whose contract is expired:\n\n";
            $mailText        .=   $mailContent;  
            $mailText        .=   "Thanks\n\n";

            $to         = "narendra.nagesh@infobeans.com";
            $subject    = "Libraries whose Contract is expired";
            $headers    = "From:no-reply@freegalmusic.com" . "\r\n" .
                          "BCC: kushal.pogul@infobeans.com";

            $mail_response = mail($to,$subject,$mailText,$headers);  
            
            if($mail_response)
            {
                echo "Mail Sent";
            }
            else 
            {
                echo "Problem in sending Mail.";
            } 
        }


//Reseting library download limit for all the libraries so the download can be available and checked for patrons
        resetDownloads();
?>
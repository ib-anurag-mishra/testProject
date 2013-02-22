<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once("config/dbconnect.php");
include_once("api/andriod.php");
include_once("api/ios.php");
include_once("includes/class.phpmailer.php");

class notification extends database
{
  public $dayOfWeek;
  public $device;
  public $timezoneString;
  public $subscriberData;
  
  function __construct() 
  {
    //echo "<pre>";
    // Get database connection
    
    //$arr = array("APA91bHD2r2ZEdDEpNF3AA3uOjKRcpdvAmefWBgACKz_p1Ta4gdPQhNW-L-_Vjm51AcK7w2BmsuRkFdLnPApv5K2uilwwDphQZntPGmOo9WWVz2RWmjr98KNwVdErimwhxWS6k4uXf0pW0RrrdW18jxAbAKLpzHOjQ");
    //echo sendGCM($arr, "if you get this please let me know");
    //echo sendIphoneNotification("299732b23d01ab6736084897b93671735264471bab7c28fada17c11b661e6cd2", $count=1, $isDev=0, "if you get this please let me know");
    //exit;
      
    $this->database();
    
    $timezoneArray = $this->getAllTimeZone();
    
    //print_r($timezoneArray);
   
    if(count($timezoneArray) > 0)
    {
      // Get the all the device IDS
      $this->device = $this->getDeviceIds();
      
      // get all the email addresses who have suscribed for email notification.
      $this->subscriberData = $this->getsubscribedEmailIds();
      
      // Send email notification
      $this->sendEmaiNotification();
    
    exit;

      // Send email notification.
      //$response = $this->sendNotification();
    }
    else
    {
      $response =  "There are no libraries to send notification";
      $this->createLog($response);
    }
  }
  
  /*
   * Send Email notification to all email addresses who have subscribed.
   */
  function sendEmaiNotification()
  {
      $subscriberData = $this->subscriberData; 
      
      for($i=0;$i<count($subscriberData);$i++)
      {
        $remainingDownloads = 0;
        $remainingDownloads = $subscriberData[$i]['library_user_download_limit'] - $subscriberData[$i]['cnt'];
        $newDownloads = $subscriberData[$i]['library_user_download_limit'];
        
        // Prepare the message
        $subscriberData[$i]['user_language'] = "en";
        $language = strtolower($subscriberData[$i]['user_language']);
        
        if($this->dayOfWeek == 1)
        {
          $emailContent = str_replace("#downloads#", $newDownloads, $this->mondayEmailContent($language));
        }
        else if($this->dayOfWeek == 4)
        {
          $emailContent = str_replace("#downloads#", $remainingDownloads, $this->thursdayEmailContent($language));
        }
        
        $unsubscribeLink = "http://www.localfm.com/notification/unsubscribe/".base64_encode($subscriberData[$i]['email_id']);
        
        $emailContent .= " <a href='".base64_decode($unsubscribeLink)."'>".$unsubscribeLink."<a>";
        
        if($subscriberData[$i]['email_id'] != "")
        {
            //Send email
            $response = $this->sendEmail($subscriberData[$i]['email_id'], $emailContent);

            // Log the message
            $subscriberData[$i]['system_type'] = "email";
            $this->createLog($response, $subscriberData[$i]);
        }
      }
      
      // return string response 
      return $response;
  }
  
    /*
     * Send Email to given email address with given content
     */
    function sendEmail($emailId, $emailContent)
    {
        $mail = new PHPMailer(); // defaults to using php "mail()"

        $mail->IsSendmail(); // telling the class to use SendMail transport

        $body = $emailContent;
        //echo $body = preg_replace('/[\]/','',$body);

        $mail->SetFrom('admin@freegalmusic.com', '');
        //$mail->AddReplyTo("admin@freegalmusic.com","");
        $address = $emailId;
        $mail->AddAddress($address, "");
        $mail->Subject = "FreegalMusic-remaining downloads";
        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
        $mail->MsgHTML($body);

        if(!$mail->Send()) {
            return "Mailer Error: " . $mail->ErrorInfo;
        } else {
            return "Message sent!";
        }
    }
  
  function mondayEmailContent($language)
  {
      if($language == 'es')
      {
        $message = "Vi havas #downloads# novaj downloads";
      }
      else if($language == 'it')
      {
        $message = "Hai #downloads# nuovi download";
      }
      else if($language == 'fr')
      {
        $message = "Vous avez #downloads# nouveaux téléchargements";
      }
      else
      {
        $message = "You have #downloads# new downloads";
      }
      
      return $message;
  }
  
  function thursdayEmailContent($language)
  {
      if($language == 'es')
      {
        $message = "/n/tSaluton,
        /n/tNur amika memorigas, ke vi havas #downloads# Freegal Muziko elŝutoj ceteraj inter nun kaj Dimanĉo nokto. Bonvolu viziti vian bibliotekon retejo kaj ensaluti al Freegal Muziko por elsxuti viajn elŝutoj.
        /n/tPor malaboni, klaku ĉi tie";
      }
      else if($language == 'it')
      {
        $message = "/n/tCiao,
        /n/tSolo un richiamo amichevole che avete Download musicali Freegal #downloads# rimanenti tra oggi e Domenica sera. Si prega di visitare il vostro sito web della biblioteca e accedere a musica Freegal per recuperare i download.
        /n/tPer annullare l'iscrizione, clicca qui";
      }
      else if($language == 'fr')
      {
        $message = "/n/tBonjour,
        /n/tJuste un rappel amical que vous avez téléchargements #downloads# Musique Freegal restants d'ici dimanche soir. S'il vous plaît visiter votre site de la bibliothèque et connectez-vous à la musique Freegal pour récupérer vos téléchargements.
        /n/tPour vous désinscrire, cliquez ici";
      }
      else
      {
        $message = "/n/tHello,
        /n/tJust a friendly reminder that you have #downloads# Freegal Music downloads remaining between now and Sunday night. Please visit your library website and log on to Freegal Music to retrieve your downloads.
        /n/tTo unsubscribe, press here";
      }
      
      return $message;
  }
  
  // Get the list of all the patron and their device token with number of remaining downloads.
  function getDeviceIds()
  {
    // Get the list of all libraries comes under given timezone. Then get the list of parton and from patron and their device ids.
    // For each parton get the number of remaining downloads
    if($this->dayOfWeek == 4)
    {
	$sql = "SELECT dm.patron_id, l.id, l.library_name, dm.registration_id, dm.device_id,
        (select COUNT(d.id) from latest_downloads AS d where dm.patron_id=d.patron_id and dm.library_id=d.library_id AND d.created BETWEEN '".CURRENT_WEEK_START_DATE."' AND '".CURRENT_WEEK_END_DATE."') AS cnt,
        dm.system_type, l.library_user_download_limit, l.library_user_download_limit, dm.user_language, lt.libraries_timezone FROM device_masters dm
        LEFT JOIN libraries AS l ON l.id=dm.library_id
        LEFT JOIN libraries_timezone AS lt ON l.id=lt.library_id
        WHERE lt.libraries_timezone ".$this->timezoneString."
        GROUP BY dm.patron_id";
      $res = $this->db->query($sql);
    }
    else if($this->dayOfWeek == 1)
    {
      $sql = "SELECT dm.patron_id, l.id, l.library_name, dm.registration_id, dm.device_id,COUNT(d.id) AS cnt, dm.system_type, l.library_user_download_limit, l.library_user_download_limit, dm.user_language, lt.libraries_timezone FROM device_masters dm
            LEFT JOIN latest_downloads AS d ON dm.patron_id=d.patron_id and dm.library_id=d.library_id
            LEFT JOIN libraries AS l ON l.id=dm.library_id
            LEFT JOIN libraries_timezone AS lt ON l.id=lt.library_id
            WHERE lt.libraries_timezone ".$this->timezoneString." 
            GROUP BY d.patron_id";
      $res = $this->db->query($sql);
    }
    
    //echo $sql;

    if(is_object($res))
    {
      $i=0;
      while($row = $res->fetch_assoc())
      {
        $result[$i] = $row;
        $i++;
      }
    }
    return $result;
  }

  function sendNotification()
  {
    //print_r($this->device);    
    $device = $this->device;
    
    for($i=0;$i<count($device);$i++)
    {    
      $ids = array($device[$i]['registration_id']);
      
      $remainingDownloads = 0;
      $remainingDownloads = $device[$i]['library_user_download_limit'] - $device[$i]['cnt'];
      $newDownloads = $device[$i]['library_user_download_limit'];
      
      // Prepare the message
      $language = strtolower($device[$i]['user_language']);

      if($this->dayOfWeek == 1)
      {
        if($language == 'es')
        {
          $message = "Vi havas $newDownloads novaj downloads";
        }
        else if($language == 'it')
        {
          $message = "Hai $newDownloads nuovi download";
        }
        else if($language == 'fr')
        {
          $message = "Vous avez $newDownloads nouveaux téléchargements";
        }
        else
        {
          $message = "You have $newDownloads new downloads";
        }
      }
      else if($this->dayOfWeek == 4)
      {
        if($language == 'es')
        {
          $message = "Ne forgesu vi havas $remainingDownloads elŝutoj forlasis";
        }
        else if($language == 'it')
        {
          $message = "Non dimenticare che hai $remainingDownloads downloads sinistra";
        }
        else if($language == 'fr')
        {
          $message = "N'oubliez pas que vous avez $remainingDownloads téléchargements gauche";
        }
        else
        {
          $message = "Don't forget you have $remainingDownloads downloads left";
        }
      }
      else 
      {
        $message = "";
      }
      
      //echo "<br>".$message;
      if($message != "")
      {
        if($device[$i]['system_type'] == 1)
        {
          // Send notification to Andriod device. 
          //$response = sendGCM($ids, $message);

          // Log the message
          $this->createLog($response, $device[$i]);
        }
        else
        {
          //Send notification to IOS device. to ".$device[$i]['registration_id'];
          //$response = sendIphoneNotification($device[$i]['registration_id'], $count=0, $isDev=0, $message);

          // Log the message
          $this->createLog($response, $device[$i]);
        }
      }
    }
    
    // return string response 
    return $response;
  }

  // Create log
  function createLog($response, $device_details="")
  {
    $file = 'log/log.txt';

    date_default_timezone_set('America/Chicago');
	
    if($device_details)
    {    
        if($device_details['system_type'] == 1)
        {
          $system_type = "Andriod";
          $request_details = $system_type."|".$device_details['patron_id']."|".$device_details['library_name']."|".$device_details['user_language']."|".$device_details['device_id'];
        }
        else if($device_details['system_type'] == 2)
        {
          $system_type = "IPhone";
          $request_details = $system_type."|".$device_details['patron_id']."|".$device_details['library_name']."|".$device_details['user_language']."|".$device_details['device_id'];
        }
        else if($device_details['system_type'] == "email")
        {
          $system_type = "Email";
          $request_details = $system_type."|".$device_details['patron_id']."|".$device_details['library_name']."|".$device_details['user_language']."|".$device_details['email_id'];
        }       

        echo $content = "\n".date("d-m-y H:i:s")." request - ".$request_details." response - ".$response;
    }
    else
    {
        echo $content = "\n".date("d-m-y H:i:s")." response - ".$response;
    }
    // Write the contents to the file, 
    // using the FILE_APPEND flag to append the content to the end of the file
    // and the LOCK_EX flag to prevent anyone else writing to the file at the same time
    file_put_contents($file, $content, FILE_APPEND | LOCK_EX);
  }
  
  function getAllTimeZone()
  {
    // get the list of distinct timezone from active libraries
    $sql = "select distinct(lt.libraries_timezone) as timezone from libraries_timezone as lt, libraries as l where l.library_status='active' and l.id=lt.library_id";
    $res = $this->db->query($sql);
    
    // create array of only those timezone whoes current time is between 9am to 10am
    $i=0;
    while($row = $res->fetch_assoc())
    {
      date_default_timezone_set($row['timezone']);
      $week = date('w');
      $time = date('h');
      $meridiem = date('a');
      //if($time >= 01 && $time <= 12 && ($meridiem == 'pm' || $meridiem == 'am'))
      if(($week == 1 || $week == 5))
      {
        $this->dayOfWeek = $week;
        $this->dayOfWeek = 4;
        
        $result[$i] = $row;
        $i++;
      }      
    }

    //echo "<br>-------------<br>";
    //print_r($result);
    
    // Prepare timezone string to use in sql query.
    $this->timezoneString .= "IN (";
    for($i=0;$i<count($result); $i++)
    {
      if($i==0)
      {
        $this->timezoneString .= "'".$result[$i]['timezone']."'";
      }
      else
      {
        $this->timezoneString .= ", '".$result[$i]['timezone']."'";
      }
    }
    $this->timezoneString .= ")";
    
    return $result;
  }
  
  function getsubscribedEmailIds()
  {
    $this->dayOfWeek = 4;
    if($this->dayOfWeek == 4)
    {
	$sql = "SELECT ns.patron_id, l.id, l.library_name, ns.email_id,
        (select COUNT(d.id) from latest_downloads AS d where ns.patron_id=d.patron_id and ns.library_id=d.library_id AND d.created BETWEEN '2012-02-18 00:00:00' AND '".CURRENT_WEEK_END_DATE."') AS cnt,
        l.library_user_download_limit, l.library_user_download_limit, lt.libraries_timezone 
        FROM notification_subscriptions ns
        LEFT JOIN libraries AS l ON l.id=ns.library_id
        LEFT JOIN libraries_timezone AS lt ON l.id=lt.library_id
        WHERE lt.libraries_timezone IN ('America/New_York') 
        GROUP BY ns.patron_id";
        $res = $this->db->query($sql);
    }
    else if($this->dayOfWeek == 1)
    {
        $sql = "SELECT ns.patron_id, ns.email_id, 
            COUNT(d.id) AS cnt, 
            l.library_user_download_limit, l.library_user_download_limit, l.id, l.library_name,
            lt.libraries_timezone 
            FROM notification_subscriptions ns
            LEFT JOIN latest_downloads AS d ON ns.patron_id=d.patron_id and ns.library_id=d.library_id
            LEFT JOIN libraries AS l ON l.id=ns.library_id
            LEFT JOIN libraries_timezone AS lt ON l.id=lt.library_id
            WHERE lt.libraries_timezone IN ('America/New_York') 
            GROUP BY d.patron_id";
        $res = $this->db->query($sql);
    }    
    
    //echo $sql;

    if(is_object($res))
    {
      $i=0;
      while($row = $res->fetch_assoc())
      {
        $result[$i] = $row;
        $i++;
      }
    }
    
    //print_r($result);
    return $result;
  }
}

$notification = new notification();
?>

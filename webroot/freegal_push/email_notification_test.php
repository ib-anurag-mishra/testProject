<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once("config/dbconnect.php");
include_once("includes/class.phpmailer.php");

class notification extends database
{
  public $device;
  public $timezoneString;
  public $subscriberData;
  
  function __construct() 
  {   
    $this->database();
    
    $timezoneArray = $this->getAllTimeZone();
    
    if(count($timezoneArray) > 0)
    {      
      // get all the email addresses who have suscribed for email notification.
      $this->subscriberData = $this->getsubscribedEmailIds();
      
      // Send email notification
      $this->sendEmailNotification();
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
  function sendEmailNotification()
  {
    $subscriberData = $this->subscriberData; 

    for($i=0;$i<count($subscriberData);$i++)
    {
      $remainingDownloads = 0;
      $remainingDownloads = $subscriberData[$i]['library_user_download_limit'] - $subscriberData[$i]['cnt'];

      if($remainingDownloads > 0)
      {
          // Prepare the message
          $subscriberData[$i]['user_language'] = "en";
          $language = strtolower($subscriberData[$i]['user_language']);

          $emailContent = str_replace("#downloads#", $remainingDownloads, $this->emailContent($language));

          $link = "http://www.freegaltest.com/users/unsubscribe/".base64_encode($subscriberData[$i]['email_id']);

          $unsubscribeLink = " <a href='".$link."'>".$link."</a>";

          $emailContent = str_replace("#link#", $unsubscribeLink, $emailContent);

          if($subscriberData[$i]['email_id'] != "")
          {
              //Send email
              $response = $this->sendEmail($subscriberData[$i]['email_id'], $emailContent);

              // Log the message
              $subscriberData[$i]['system_type'] = "email";
              $this->createLog($response, $subscriberData[$i]);
          }
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

        $mail->SetFrom('no-reply@freegalmusic.com', '');
        //$mail->AddReplyTo("admin@freegalmusic.com","");
        $address = $emailId;
        $mail->AddAddress($address, "");
        $mail->Subject = "Freegal Music downloads expiring this week";
        //$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
        $mail->MsgHTML($body);

        if(!$mail->Send()) {
            return "Mailer Error: " . $mail->ErrorInfo;
        } else {
            return "Message sent!";
        }
    }
   
  function emailContent($language)
  {
      if($language == 'es')
      {
        $message = "Saluton,
        Nur amika memorigas, ke vi havas #downloads# Freegal Muziko elŝutoj ceteraj inter nun kaj Dimanĉo nokto. Bonvolu viziti vian bibliotekon retejo kaj ensaluti al Freegal Muziko por elsxuti viajn elŝutoj.
        Por malaboni, klaku ĉi tie";
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
        $message = "Hello,<br />
<br />
        Just a friendly reminder that you have #downloads# Freegal Music downloads remaining between now and Sunday night. Please visit your library website and log on to Freegal Music to retrieve your downloads.<br />
<br />
        To unsubscribe, press here<br />
	#link#<br />
<br />	
	Regards<br />
	Freegalmusic Team";
      }
      
      return $message;
  }
  
  // Create log
  function createLog($response, $device_details="")
  {
    $file = 'log/email_log.txt';

    date_default_timezone_set('America/Chicago');
	
    $request_details = $device_details['patron_id']."|".$device_details['library_name']."|".$device_details['user_language']."|".$device_details['email_id'];

    echo $content = "\n".date("d-m-y H:i:s")." request - ".$request_details." response - ".$response;

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
      if(($week == 1 || $week == 2 || $week == 3 || $week == 4 || $week == 3))
      {        
        $result[$i] = $row;
        $i++;
      }      
    }

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
    $sql = "SELECT ns.patron_id, l.id, l.library_name, ns.email_id,
    (select COUNT(d.id) from latest_downloads AS d where ns.patron_id=d.patron_id and ns.library_id=d.library_id AND d.created BETWEEN '".CURRENT_WEEK_START_DATE."' AND '".CURRENT_WEEK_END_DATE."') AS cnt,
    l.library_user_download_limit, l.library_user_download_limit, lt.libraries_timezone 
    FROM notification_subscriptions ns
    LEFT JOIN libraries AS l ON l.id=ns.library_id
    LEFT JOIN libraries_timezone AS lt ON l.id=lt.library_id
    WHERE lt.libraries_timezone ".$this->timezoneString." 
    GROUP BY ns.patron_id";
    $res = $this->db->query($sql);    

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

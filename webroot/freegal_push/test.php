<?php
//echo date_default_timezone_get();
//exit;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once("config/dbconnect.php");
include_once("api/andriod.php");
include_once("api/ios.php");

class notification extends database
{
  public $dayOfWeek;
  public $device;
  public $timezoneString;
  
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

      // Send notification.
      $response = $this->sendNotification();
    }
    else
    {
      $response =  "There are no libraries to send notification";
      $this->createLog($response);
    }
  }
  
  // Get the list of all the patron and their device token with number of remaining downloads.
  function getDeviceIds()
  {
    // Get the list of all libraries comes under given timezone. Then get the list of parton and from patron and their device ids.
    // For each parton get the number of remaining downloads
    if($this->dayOfWeek == 4)
    {
/*      $sql = "SELECT dm.patron_id, l.id, l.library_name, dm.registration_id, dm.device_id,COUNT(d.id) AS cnt, dm.system_type, l.library_user_download_limit, l.library_user_download_limit, dm.user_language, lt.libraries_timezone FROM device_masters dm
            LEFT JOIN latest_downloads AS d ON dm.patron_id=d.patron_id and dm.library_id=d.library_id
            LEFT JOIN libraries AS l ON l.id=dm.library_id
            LEFT JOIN libraries_timezone AS lt ON l.id=lt.library_id
            WHERE lt.libraries_timezone ".$this->timezoneString." AND d.created BETWEEN '".CURRENT_WEEK_START_DATE."' AND '".CURRENT_WEEK_END_DATE."'
            GROUP BY d.patron_id";*/
	$sql = "SELECT dm.patron_id, l.id, l.library_name, dm.registration_id, dm.device_id,
        (select COUNT(d.id) from latest_downloads AS d where dm.patron_id=d.patron_id and dm.library_id=d.library_id AND d.created BETWEEN '".CURRENT_WEEK_START_DATE."' AND '".CURRENT_WEEK_END_DATE."') AS cnt,
        dm.system_type, l.library_user_download_limit, l.library_user_download_limit, dm.user_language, lt.libraries_timezone FROM device_masters dm
        LEFT JOIN libraries AS l ON l.id=dm.library_id
        LEFT JOIN libraries_timezone AS lt ON l.id=lt.library_id
        WHERE lt.libraries_timezone ".$this->timezoneString."
        GROUP BY dm.patron_id";
      $res = $this->db->query($sql);
    }
    else if($this->dayOfWeek == 3)
    {
      $sql = "SELECT dm.patron_id, l.id, l.library_name, dm.registration_id, dm.device_id,COUNT(d.id) AS cnt, dm.system_type, l.library_user_download_limit, l.library_user_download_limit, dm.user_language, lt.libraries_timezone FROM device_masters dm
            LEFT JOIN latest_downloads AS d ON dm.patron_id=d.patron_id and dm.library_id=d.library_id
            LEFT JOIN libraries AS l ON l.id=dm.library_id
            LEFT JOIN libraries_timezone AS lt ON l.id=lt.library_id
            WHERE lt.libraries_timezone ".$this->timezoneString." 
            GROUP BY d.patron_id";
      $res = $this->db->query($sql);
    }
    
    echo $sql;
    
    //exit;

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
    print_r($this->device);    
    $device = $this->device;
    //exit;
    for($i=0;$i<count($device);$i++)
    {    
      $ids = array($device[$i]['registration_id']);
      
      $remainingDownloads = 0;
      $remainingDownloads = $device[$i]['library_user_download_limit'] - $device[$i]['cnt'];
      $newDownloads = $device[$i]['library_user_download_limit'];
      
      // Prepare the message
      $language = strtolower($device[$i]['user_language']);
      /*date_default_timezone_set($device[$i]['libraries_timezone']);
      $week = date('w');
      $time = date('h');
      $meridiem = date('a');*/
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
        $message = "Hello there";
      }
      
      //echo "<br>".$message;
      if($message != "")
      {
        if($device[$i]['system_type'] == 1)
        {
          // Send notification to Andriod device. 
          $response = sendGCM($ids, $message);

          // Log the message
          $this->createLog($response, $device[$i]);
        }
        else
        {
          //Send notification to IOS device. to ".$device[$i]['registration_id'];
          $response = sendIphoneNotification($device[$i]['registration_id'], $count=0, $isDev=0, $message);

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
		}
		else if($device_details['system_type'] == 2)
		{
		  $system_type = "IPhone";
		}	
		$request_details = $system_type."|".$device_details['patron_id']."|".$device_details['library_name']."|".$device_details['user_language']."|".$device_details['device_id'];
    
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
    $sql = "select distinct(lt.libraries_timezone) as timezone from libraries_timezone as lt, libraries as l where l.library_status='active' and l.id=lt.library_id and l.id=1";
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
      if(($week == 3 || $week == 4) &&  $time == '6' && $meridiem == 'am')
      {
        $this->dayOfWeek = $week;
        //$this->dayOfWeek = 1;
        
        $result[$i] = $row;
        $i++;
      }
    }

    echo "<br>-------------<br>";
    print_r($result);
    
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
}

$notification = new notification();
?>

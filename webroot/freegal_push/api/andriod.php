<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// This function will send the notifications to all provided registration ids.
function sendGCM ($devices, $data)
{
  global $mysqli;
	$message = json_encode(array(
                'registration_ids'  => $devices,
                'data'              => array( "message" => $data ),
                ));
  $phcaApiKey = ANDRIOD_KEY; // SECRET!!!
 
	$url = ANDRIOD_API_URL;
 
	$headers = array('Authorization: key=' . $phcaApiKey, "Content-Type: application/json");
 
	$x = curl_init($url); 
	curl_setopt($x, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($x, CURLOPT_HEADER, 1); 
	curl_setopt($x, CURLOPT_POST, 1); 
	curl_setopt($x, CURLOPT_POSTFIELDS, $message); 
	curl_setopt($x, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec($x); 
	$http_code = curl_getinfo($x, CURLINFO_HTTP_CODE);
  
  /*if($http_code != 200)
  {    
    //request failed    
    return "Error: Request failed-".$devices[$device_number]." message-". $data;
  }*/
  
	$header_size = curl_getinfo($x,CURLINFO_HEADER_SIZE);
	$body = substr( $response, $header_size );
 
	$json_response = json_decode($body);
	$results = $json_response->results; 
  
  $device_number = 0;
	foreach ($results as $device_response)
  {
		if (is_array($device_response))
    {
			// only happens when a registration id needs to be updated.
			$old_id = $devices[$device_number];
			$new_id = $device_response->registration_id;			
			$sql = "update device_masters SET registration_id = ". "'$new_id' WHERE registration_id='$old_id'";
			$result2 = $mysqli->query($sql);
      return "Success: registration id updated new id-".$devices[$device_number]." old id ".$old_id." message-".$data;
		}
    else if ($device_response->error)
    {
      switch ($device_response->error)
      {
        case "NotRegistered":
          // delete the device from the userdevices table
          $sql = "DELETE FROM device_masters WHERE registration_id='".$devices[$device_number]."'";
          $result2 = $mysqli->query($sql);
          return "Error: NotRegistered- ".$devices[$device_number]." message-".$data;
          break;
        case "InvalidRegistration":
        case "Unavailable":
        case "MissingRegistration":
        case "MismatchSenderId":
        case "MessageTooBig":
        case "InvalidTtl":
        case "Unavailable":
        case "MessageTooBig":
          return "Error: ".$device_response->error."- ".$devices[$device_number]." message-".$data;
          break;
        default: 
          return "Success: registration id -".$devices[$device_number]." message-".$data;
          break;
      }			
		}
    else
    {
      return "Success: registration id -".$devices[$device_number]." message-".$data;
    }
    
    $device_number++;
	} 
  // close the curl object
	curl_close($x); 
} 
?>

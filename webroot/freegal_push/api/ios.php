<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// Send notification to Iphone
function sendIphoneNotification($token, $count, $isDev, $alert_message)
{  
	global $apnsData, $mysqli;
	
	//echo "Notify device " . $token . " with badge number " . $count . "<br/>";

	$development = $isDev ? 'sandbox' : 'production';

	$ctx = stream_context_create();
	stream_context_set_option($ctx, 'ssl', 'local_cert', $apnsData[$development]['certificate']);
	stream_context_set_option($ctx, 'ssl', 'passphrase', '');
	$fp = stream_socket_client($apnsData[$development]['ssl'], $error, $errorString, 100, (STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT), $ctx);
	
	$message = array();
	$message["aps"] = array();
	$message["aps"]["alert"] = $alert_message;
	//$message["aps"]["badge"] = intval($count);
	$message["aps"]["sound"] = "bingbong.aiff";
	$json = json_encode($message);

	if(!$fp)
	{
		return "Error: FAILED to connect to " . $apnsData[$development]['ssl'] . ": $error $errorString <br/>";
	}
	else 
	{
		$msg = chr(0) . chr(0) . chr(32) . pack('H*', $token) . chr(0) . chr(strlen($json)) . $json;
		$fwrite = fwrite($fp, $msg);
    //checkAppleErrorResponse($fp);
    checkResponse($fp);
		if(!$fwrite) 
		{
			return "Error: ".$token." - ".checkIphoneFeedback($development, $token);
		}
		else
		{
			return "Sucess: ".$token." - ".checkIphoneFeedback($development, $token);
		}
	}
	fclose($fp);
}

function checkResponse($fp)
{
  $read = array($fp);
  $null = null;
  $changedStreams = stream_select($read, $null, $null, 0, 1000000);

  if ($changedStreams === false) {
    return "Error: Unabled to wait for a stream availability";
  } elseif ($changedStreams > 0) {
    $responseBinary = fread($fp, 6);
    if ($responseBinary !== false || strlen($responseBinary) == 6) {
      $response = unpack('Ccommand/Cstatus_code/Nidentifier', $responseBinary);
      return $response;
    }
  }
}

// Check notification responce for Iphone
function checkIphoneFeedback($development, $token)
{
	global $apnsData, $mysqli;

	$ctx = stream_context_create();
	stream_context_set_option($ctx, 'ssl', 'local_cert', $apnsData[$development]['certificate']);
	stream_context_set_option($ctx, 'ssl', 'verify_peer', false);
	$fp = stream_socket_client($apnsData[$development]['feedback'], $error,$errorString, 100, (STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT), $ctx);

	if(!$fp) 
		return $return =  "&nbsp;&nbsp;&nbsp;&nbsp;Failed to connect to device: {$error} {$errorString}. <br/>";

	while ($devcon = fread($fp, 38)){
		$arr = unpack("H*", $devcon);
		$rawhex = trim(implode("", $arr));
		$token = substr($rawhex, 12, 64);
    $feedback = unpack("N1timestamp/n1length/H*devtoken", $devcon);
    $return = "Sent message at " . date("r", $feedback['timestamp']);
    $return .= " with length " . $feedback['length'];
    $return .= " to token " . $feedback['devtoken'];
		if(!empty($token)){		
			return "Message: Unregistering Device Token: {$token}.";
			$mysqli->query("DELETE FROM device_masters WHERE registration_id LIKE '$token'");
		}
	}
	
	fclose($fp);
	return $return;
  
  /*echo $apnsData[$development]['certificate'];
  
  $streamContext = stream_context_create();
  stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsData[$development]['certificate']);

  $apns = stream_socket_client(
      'ssl://feedback.sandbox.push.apple.com:2196',
      $error,
      $errorString,
      60,
      STREAM_CLIENT_CONNECT,
      $streamContext
  );
  
  echo "errorString-".$errorString;
  echo "error-".$error;
  
  print_r($apns);

  if($apns) {
    while ($data = fread($apns, 38)) {
      print_r($data);
      $feedback = unpack("N1timestamp/n1length/H*devtoken", $data);
      echo "Sent message at " . date("r", $feedback['timestamp']);
      echo " with length " . $feedback['length'];
      echo " to token " . $feedback['devtoken'];
    }
    fclose($apns);
  }*/
}

//FUNCTION to check if there is an error response from Apple
//         Returns TRUE if there was and FALSE if there was not
function checkAppleErrorResponse($fp) {

   $apple_error_response = fread($fp, 38); //byte1=always 8, byte2=StatusCode, bytes3,4,5,6=identifier(rowID). Should return nothing if OK.
   //NOTE: Make sure you set stream_set_blocking($fp, 0) or else fread will pause your script and wait forever when there is no response to be sent.
   if ($apple_error_response) {
        $error_response = unpack('Ccommand/Cstatus_code/Nidentifier', $apple_error_response); //unpack the error response (first byte 'command" should always be 8)

        if ($error_response['status_code'] == '0') {
            $error_response['status_code'] = '0-No errors encountered';

        } else if ($error_response['status_code'] == '1') {
            $error_response['status_code'] = '1-Processing error';

        } else if ($error_response['status_code'] == '2') {
            $error_response['status_code'] = '2-Missing device token';

        } else if ($error_response['status_code'] == '3') {
            $error_response['status_code'] = '3-Missing topic';

        } else if ($error_response['status_code'] == '4') {
            $error_response['status_code'] = '4-Missing payload';

        } else if ($error_response['status_code'] == '5') {
            $error_response['status_code'] = '5-Invalid token size';

        } else if ($error_response['status_code'] == '6') {
            $error_response['status_code'] = '6-Invalid topic size';

        } else if ($error_response['status_code'] == '7') {
            $error_response['status_code'] = '7-Invalid payload size';

        } else if ($error_response['status_code'] == '8') {
            $error_response['status_code'] = '8-Invalid token';

        } else if ($error_response['status_code'] == '255') {
            $error_response['status_code'] = '255-None (unknown)';

        } else {
            $error_response['status_code'] = $error_response['status_code'].'-Not listed';

        }

        echo '<br><b>+ + + + + + ERROR</b> Response Command:<b>' . $error_response['command'] . '</b>&nbsp;&nbsp;&nbsp;Identifier:<b>' . $error_response['identifier'] . '</b>&nbsp;&nbsp;&nbsp;Status:<b>' . $error_response['status_code'] . '</b><br>';
        echo 'Identifier is the rowID (index) in the database that caused the problem, and Apple will disconnect you from server. To continue sending Push Notifications, just start at the next rowID after this Identifier.<br>';

        return true;
   }
   return false;
}
?>

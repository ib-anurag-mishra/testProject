<?php
 /**
 * Validate patrons function
 * This function will validate the patron access
 */
 
Class AuthRequestComponent extends Object
{
     function getAuthResponse($data,$authUrl) {
		$url = $authUrl;
		$data = (array)$data;
        $ch=curl_init();
		// tell curl target url
		curl_setopt($ch, CURLOPT_URL, $url);
		// tell curl we will be sending via POST
		curl_setopt($ch, CURLOPT_POST, true);
		// tell it not to validate ssl cert
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); 
		// tell it where to get POST variables from
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		// make the connection
		$result = curl_exec($ch);
		// close connection
		curl_close($ch);
		return $result;
    }
}
?>
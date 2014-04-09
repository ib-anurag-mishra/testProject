<?php
 /**
 *File Name : auth_request.php
 * Validate patrons function
 * This function will validate the patron access
	Author : m68interactive
 */
 
Class AuthRequestComponent extends Object
{
     function getAuthResponse($data,$authUrl) {
		App::import(array('Xml'));
		if(!empty($data))
		{
			$str = '<data ';
			foreach($data as $key=>$value)
			{
				$str = $str.$key.'="'.htmlentities($value).'" ';
			}
			$str = $str."></data>";
		}
		$post_data = array('xml'=>$str);
		$url = $authUrl;
		$ch=curl_init();
		// tell curl target url
		curl_setopt($ch, CURLOPT_URL, $url);
		// tell curl we will be sending via POST
		curl_setopt($ch, CURLOPT_POST, true);
		// tell it not to validate ssl cert
		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		// tell it where to get POST variables from
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		// make the connection
		$result = curl_exec($ch);
                $this->log($str."---".$result,"auth");
                if($result === false)
                {
                    $this->log('Curl error: ' . curl_error($ch),"auth");
                }
		curl_close($ch);
   		$result =& new Xml($result);
		$result = Set::reverse($result);
		return $result;
    }

	function getRestResponse($data,$authUrl)
	{
		App::import(array('Xml'));
		if(!empty($data))
		{
			$str = '<data ';
			foreach($data as $key=>$value)
			{
				$str = $str.$key.'="'.htmlentities($value).'" ';
			}
			$str = $str."></data>";
		}
		$post_data = array('xml'=>$str);
		$opts = array
				('http' =>
					array
					(
						'method'  => 'POST',
						'header'  => 'Content-type: application/x-www-form-urlencoded' . "\r\n",
						'content' => http_build_query($post_data)
					)
				);

		$context  = stream_context_create($opts);
		$parsed_xml = file_get_contents($authUrl, false, $context);
		$parsed_xml = substr($parsed_xml,strpos($parsed_xml,'>')+1);
		$parsed_xml =& new Xml($parsed_xml);
		$parsed_xml = Set::reverse($parsed_xml);
		return $parsed_xml;
	}
}
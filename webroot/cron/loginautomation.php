<?php
error_reporting(E_ALL);
set_time_limit(0);
include 'functions.php';
$sql = 'SELECT * FROM `credentials` WHERE library_id NOT IN(20,22,37,49,44,68,69,79,147,170,419,497,477,81,82,85,97,116,155,163,244,267,327,328,168,169,108,109,210,216,222,224,228,232,234,235,242,253,262,282,283,284,351,378,385,408,425,426,427,428,392,429,456,473,465,481) Limit 25';
//$sql = 'SELECT * FROM `credentials` WHERE library_id NOT IN (109,425,426,79)';
$result = mysql_query($sql);
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$credential[] = $line; 
}
//print_r($credential);exit;
$working = array();
$notWorking = array();
$login = '';
foreach($credential as $k=>$v){

  if (!mysql_ping ($conn)) {
     //Reconnect to mysql if lost the connection.
     mysql_close($conn);
     $conn = mysql_connect(DBHOST,DBUSER,DBPASS);
     mysql_select_db(DBNAME, $conn);
  }
	$data = array();
	$card = $v['card'];
	$card = str_replace(" ","",$card);
	$card = strtolower($card);			
	$data['card'] = $card;
	$data['card_orig'] = $card;
	$data['pin'] = @$v['pin'];
	$data['name'] = @$v['pin'];	
/*	if($v['pin'] != ''){
		$data['pin'] = $pin;
	}*/
	$patronId = $card;
	$data['patronId'] = $patronId;
	$cardNo = substr($card,0,5);
	$data['cardNo'] = $cardNo;
	$data['library_cond'] = $v['library_id'];
	$sql = 'SELECT * FROM `libraries` WHERE id ='.$v['library_id'];
	$result = mysql_query($sql);
	$libraryArr = array();
	$urlArr = array();
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$libraryArr[] = $line; 
	}
	$sql = 'SELECT * FROM `urls` WHERE library_id ='.$v['library_id'];
	$result = mysql_query($sql);
	$urlArr = array();
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$urlArr[] = $line; 
	}	
	$data['referral']= @$urlArr[0]['domain_name'];
	$data['subdomain']= $libraryArr[0]['library_subdomain'];
	$data['database'] = 'freegal';
	if($libraryArr[0]['library_territory'] == 'AU'){
		$auth = "http://auth2.libraryideas.com/Authentications/";
	}else{
		$auth = "https://auth.libraryideas.com/Authentications/";
	}
	if($libraryArr[0]['library_authentication_method'] != 'ezproxy') {
		if($libraryArr[0]['library_authentication_method'] == 'referral_url') {
			//do nothing
		}
		elseif($libraryArr[0]['library_authentication_method'] == 'innovative') {
			$data['url'] = $libraryArr[0]['library_authentication_url']."/PATRONAPI/".$card."/".$v['pin']."/pintest";
			$authUrl = $auth."ilogin_validation";
		}
		elseif($libraryArr[0]['library_authentication_method'] == 'innovative_var') {
			$data['url'] = $libraryArr[0]['library_authentication_url']."/PATRONAPI/".$card."/".$v['pin']."/pintest";
			$authUrl = $auth."idlogin_validation";
		}
		elseif($libraryArr[0]['library_authentication_method'] == 'innovative_var_name') {
			$data['url'] = $libraryArr[0]['library_authentication_url']."/PATRONAPI/".$card."/dump";
			$authUrl = $auth."ildlogin_validation";
		}
		elseif($libraryArr[0]['library_authentication_method'] == 'innovative_var_https_name') {
			$data['url'] = $libraryArr[0]['library_authentication_url']."/PATRONAPI/".$card."/dump";
			$authUrl = $auth."ilhdlogin_validation";
		}			
		elseif($libraryArr[0]['library_authentication_method'] == 'innovative_var_https') {
			$data['url'] = $libraryArr[0]['library_authentication_url']."/PATRONAPI/".$card."/".$v['pin']."/pintest";
			$authUrl = $auth."ihdlogin_validation";
		}
		elseif($libraryArr[0]['library_authentication_method'] == 'innovative_var_https_wo_pin') {
			$data['url'] = $libraryArr[0]['library_authentication_url']."/PATRONAPI/".$card."/dump";
			$authUrl = $auth."inhdlogin_validation";
		}			
		elseif($libraryArr[0]['library_authentication_method'] == 'innovative_https'){
			$data['url'] = $libraryArr[0]['library_authentication_url']."/PATRONAPI/".$card."/".$v['pin']."/pintest";
			$authUrl = $auth."inhlogin_validation";
		}
		elseif($libraryArr[0]['library_authentication_method'] == 'innovative_wo_pin') {
			$data['url'] = $libraryArr[0]['library_authentication_url']."/PATRONAPI/".$card."/dump";
			$authUrl = $auth."inlogin_validation";
		}
		elseif($libraryArr[0]['library_authentication_method'] == 'innovative_var_wo_pin') {
			$data['url'] = $libraryArr[0]['library_authentication_url']."/PATRONAPI/".$card."/dump";
			$authUrl = $auth."indlogin_validation";
		}		
		elseif($libraryArr[0]['library_authentication_method'] == 'sip2'){            
			$authUrl = $auth."slogin_validation";
		}
		elseif($libraryArr[0]['library_authentication_method'] == 'sip2_wo_pin'){            
			$authUrl = $auth."snlogin_validation";
		}
		elseif($libraryArr[0]['library_authentication_method'] == 'sip2_var'){            
			$authUrl = $auth."sdlogin_validation";
		}
		elseif($libraryArr[0]['library_authentication_method'] == 'sip2_var_wo_pin'){            
			$authUrl = $auth."sndlogin_validation";
		}			
		elseif($libraryArr[0]['library_authentication_method'] == 'ezproxy'){            
			//do nothing
		}
		elseif($libraryArr[0]['library_authentication_method'] == 'soap'){            
			$authUrl = $auth."plogin_validation";
		}
		elseif($libraryArr[0]['library_authentication_method'] == 'mndlogin_reference'){            
			$query = "SELECT * FROM `cards` WHERE card_number ='".$card."' AND library_id='".$v['library_id']."'";
			$res = mysql_query($query);
			$num_rows = mysql_num_rows($res);
			if($num_rows > 0){
				$result = "++successful";
			}else{
				$result = "failure";
			}
		}
		elseif($libraryArr[0]['library_authentication_method'] == 'mdlogin_reference'){            
			$query = "SELECT * FROM `cards` WHERE card_number ='".$card."' AND pin = '".$data['pin']."' AND library_id='".$v['library_id']."'";
			$res = mysql_query($query);
			$num_rows = mysql_num_rows($res);
			if($num_rows > 0){
				$result = "++successful";
			}else{
				$result = "failure";
			}
		}		
		else {
		   //do nothing
		}
		if($libraryArr[0]['library_authentication_method'] != 'mndlogin_reference' && $libraryArr[0]['library_authentication_method'] != 'mdlogin_reference'){
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
			//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			//curl_setopt($ch, CURLOPT_CAINFO, getcwd() . "/CAcerts/BuiltinObjectToken-EquifaxSecureCA.crt"); 
			// tell it where to get POST variables from
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			// make the connection
			$result = curl_exec($ch);
		}
		echo $libraryArr[0]['id']."\n\n";
		
                if(strpos($result,"successful") != false){
			echo $libraryArr[0]['library_name']. " is working";
			//$working[$v['library_id']][$libraryArr[0]['library_name']] = 'working';
                        $login .= $libraryArr[0]['library_name']." is working\n\n\n\n";
		}
		else{
			/*if($libraryArr[0]['library_name']){
				$login .= $libraryArr[0]['library_name']." is not working\n\n\n\n";
			//	$notWorking[$v['library_id']][$libraryArr[0]['library_name']] = "not working";
				echo $libraryArr[0]['library_name']." is not working\n\n\n\n";
			}*/
                    
                        if($libraryArr[0]['library_name']){
					$arr = xml2array($result);
					//echo "<pre>"; print_r($arr['posts']['message']);
					if(($libraryArr[0]['library_authentication_method'] != 'mdlogin_reference') && ($libraryArr[0]['library_authentication_method'] != 'mndlogin_reference')){
					$message = $arr['posts']['message'];
}
					$login .= $libraryArr[0]['library_name']." is not working.".$message."\n\n\n\n";
				//	$notWorking[$v['library_id']][$libraryArr[0]['library_name']] = "not working";
					echo $libraryArr[0]['library_name']." is not working<BR>".$message;
				}
                    
                    
		}
	}
}
//print $login;exit tech@m68interactive.com;
//echo mail('tech@m68interactive.com',"Library Login Test",$login,'From:no-reply@freegalmusic.com');exit;
echo mail('kushal.pogul@infobeans.com',"Library Login Test",$login,'From:no-reply@freegalmusic.com');exit;
//print "<pre>";print_r($working);print_r($notWorking);exit;



function xml2array($contents, $get_attributes=1, $priority = 'tag') {
    if(!$contents) return array();

    if(!function_exists('xml_parser_create')) {
        //print "'xml_parser_create()' function not found!";
        return array();
    }

    //Get the XML parser of PHP - PHP must have this module for the parser to work
    $parser = xml_parser_create('');
    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($contents), $xml_values);
    xml_parser_free($parser);

    if(!$xml_values) return;//Hmm...

    //Initializations
    $xml_array = array();
    $parents = array();
    $opened_tags = array();
    $arr = array();

    $current = &$xml_array; //Refference

    //Go through the tags.
    $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
    foreach($xml_values as $data) {
        unset($attributes,$value);//Remove existing values, or there will be trouble

        //This command will extract these variables into the foreach scope
        // tag(string), type(string), level(int), attributes(array).
        extract($data);//We could use the array by itself, but this cooler.

        $result = array();
        $attributes_data = array();
        
        if(isset($value)) {
            if($priority == 'tag') $result = $value;
            else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
        }

        //Set the attributes too.
        if(isset($attributes) and $get_attributes) {
            foreach($attributes as $attr => $val) {
                if($priority == 'tag') $attributes_data[$attr] = $val;
                else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
            }
        }

        //See tag status and do the needed.
        if($type == "open") {//The starting of the tag '<tag>'
            $parent[$level-1] = &$current;
            if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                $current[$tag] = $result;
                if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
                $repeated_tag_index[$tag.'_'.$level] = 1;

                $current = &$current[$tag];

            } else { //There was another element with the same tag name

                if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                    $repeated_tag_index[$tag.'_'.$level]++;
                } else {//This section will make the value an array if multiple tags with the same name appear together
                    $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
                    $repeated_tag_index[$tag.'_'.$level] = 2;
                    
                    if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                        $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                        unset($current[$tag.'_attr']);
                    }

                }
                $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
                $current = &$current[$tag][$last_item_index];
            }

        } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
            //See if the key is already taken.
            if(!isset($current[$tag])) { //New Key
                $current[$tag] = $result;
                $repeated_tag_index[$tag.'_'.$level] = 1;
                if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

            } else { //If taken, put all things inside a list(array)
                if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

                    // ...push the new element into that array.
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                    
                    if($priority == 'tag' and $get_attributes and $attributes_data) {
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag.'_'.$level]++;

                } else { //If it is not an array...
                    $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                    $repeated_tag_index[$tag.'_'.$level] = 1;
                    if($priority == 'tag' and $get_attributes) {
                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            
                            $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                            unset($current[$tag.'_attr']);
                        }
                        
                        if($attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                        }
                    }
                    $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
                }
            }

        } elseif($type == 'close') { //End of tag '</tag>'
            $current = &$parent[$level-1];
        }
    }
    
    return($xml_array);
}

?>

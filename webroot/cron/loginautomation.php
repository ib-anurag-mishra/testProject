<?php
error_reporting(E_ALL);
set_time_limit(0);
include 'functions.php';
$sql = 'SELECT * FROM `credentials` WHERE library_id NOT IN(20,22,37,49,44,68,69,79,147,170,419,497,477,81,82,85,97,116,155,163,244,267,327,328,168,169,108,109,210,216,222,224,228,232,234,235,242,253,262,282,283,284,351,378,385,408,425,426,427,428,392,429,456,473,465,481)';
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
		//	echo $libraryArr[0]['library_name']. " is working";
			//$working[$v['library_id']][$libraryArr[0]['library_name']] = 'working';
                      //  $login .= $libraryArr[0]['library_name']." is working\n\n\n\n";
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

echo mail('tech@libraryideas.com',"Freegalmusic Library Login Test",$login,'From:no-reply@freegalmusic.com');exit;
//print "<pre>";print_r($working);print_r($notWorking);exit;

?>

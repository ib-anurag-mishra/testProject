<?php
/**
 * Author: Library Ideas    
 */

    error_reporting(E_ALL);
    header("Content-Type: text/plain"); # Not HTML
    $host = "192.168.100.114";
    $user = "freegal_prod";
    $pass = "}e47^B1EO9hD";
    $encodingKey = 'Xcpt3XuG5B';

    $patronids = "SELECT patron_id,password from freegal.authtokens_copy where password is not null";

 
    $link = mysql_connect($host, $user, $pass, null);

    if($link){
		$pids = mysql_query($patronids, $link);
     }
	else {
        printf("BAD: Connection Failed %s", mysql_error());
        mysql_close($link);
        return;
    }

	if($pids){
		
		while($r[]=mysql_fetch_array($pids));
	}
	else {
        printf("BAD: Query failed - %s\n", mysql_error($link));
        mysql_close($link);
        return;
    }
    
 	for($i=0;$i<count($r);$i++){
		$enc_pass = freegalEncrypt($r[$i]['password']);
		$sql = "UPDATE TABLE freegal.authtokens_copy SET password =".$enc_pass." where patron_id =".$r[$i]['patron_id'];
		if(mysql_query($sql,$link)) echo "updated".$r[$i]['patron_id'];
		else echo "failed";
	}
   
    mysql_close($link);

   /*
     * @func freegalEncrypt
     * @desc This is used to encrypt a value using the key
     */

	function freegalEncrypt($string, $key) {
  		$result = '';
  		for($i=0; $i<strlen($string); $i++) {
    	$char = substr($string, $i, 1);
    	$keychar = substr($key, ($i % strlen($key))-1, 1);
    	$char = chr(ord($char)+ord($keychar));
    	$result.=$char;
  		}
 
  		return base64_encode($result);
	}
 
	/*
     * @func freegalDecrypt
     * @desc This is used to decrypt an encrypted value using the key
     */

	function freegalDecrypt($string, $key) {
  		$result = '';
  		$string = base64_decode($string);
 
  		for($i=0; $i<strlen($string); $i++) {
    		$char = substr($string, $i, 1);
    		$keychar = substr($key, ($i % strlen($key))-1, 1);
    		$char = chr(ord($char)-ord($keychar));
    		$result.=$char;
  		}
 
  		return $result;
	}


?>
    

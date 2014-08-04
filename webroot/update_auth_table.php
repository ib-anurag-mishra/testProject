<?php
/**
 * Author: Library Ideas    
 */

    error_reporting(E_ALL);
    header("Content-Type: text/plain"); # Not HTML
    $host = "192.168.100.114";
    $user = "freegal_prod";
    $pass = "}e47^B1EO9hD";
   
    $sql = "SElECT * FROM freegal.authtokens_copy limit 100";
    $link = mysql_connect($host, $user, $pass, null);

    if($link)
        $result = mysql_query($sql, $link);
    else {
        printf("BAD: Connection Failed %s", mysql_error());
        mysql_close($link);
        return;
    }

    if($result){
        $status = mysql_fetch_assoc($result);
	print_r($status);
	}
    else {
        printf("BAD: Query failed - %s\n", mysql_error($link));
        mysql_close($link);
        return;
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
    

<?php

class EZproxySSOComponent {
	var $user = "";
	var $valid = false;
	var $expired = false;
	var $ip = "";
	var $ts1 = "";
	var $ts2 = "";
	var $debug = false;

	function EZproxySSOComponent(
			$secret = "",
			$ssourl = "",
			$tolerance = 360,
			$initDebug = false)
	{
		$debug = $initDebug;

		if (! (isset($_GET["ts"]) && isset($_GET["sso"]))) {
			if (strcmp($ssourl, "") != 0) {
				header("Location: $ssourl");
				exit();
			}
			return;
		}

		$this->ts1 = $_GET["ts"];
		$ssoCryptBase64 = $_GET["sso"];
		$ssoCrypt =  base64_decode($ssoCryptBase64);
		$iv = sprintf("%08x", $this->ts1);
		$td = mcrypt_module_open(MCRYPT_3DES, "", MCRYPT_MODE_CBC, "");
		if (mcrypt_generic_init($td, $secret, $iv) == -1) {
			echo("mcrypt_generic_init failed");
		} else {
			$sso = mdecrypt_generic($td, $ssoCrypt);
			mcrypt_generic_deinit($td);
			mcrypt_module_close($td);
			// Remove PKCS #5 padding
			$sso = trim($sso, "\x00..\x10");
			list($hash, $body) = explode("|", $sso, 2);
			$checkHash = sha1($body);
			if (strcasecmp($hash, $checkHash) != 0) {
				if ($debug) {
					echo("Received $sso<br>\nExpected hash $checkHash<br>\n");
				}
			} else {
				list ($salt, $this->ts2, $this->ip, $this->user) =
				explode("|", $body, 4);
				$this->expired = abs($this->ts1 - time()) > $tolerance;
				$this->valid = ! $this->expired;
			}
		}
	}

	function user()    {
		return $this->user;    }
		function valid()   {
			return $this->valid;
		}
		function expired() {
			return $this->expired;
		}
		function ip()      {
			return $this->ip;
		}
		function ts1()     {
			return $this->ts1;
		}
		function ts2()     {
			return $this->ts2;
		}
}
?>
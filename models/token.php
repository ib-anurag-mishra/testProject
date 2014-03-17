<?php
/**
 * File Name: token.php
 * Class Name: Token
*/

class Token extends AppModel {

	function regularToken($uri) {
		$gen = '5';
		$key = 'LibrariesrockwithFreegalmusic.com!';

		$file = $uri;

		$str = gmdate('s i H d m Y', time());
		$date_array = explode(' ', $str);
		list($sec, $min, $hour, $mday, $mon, $year) = $date_array;
		$sec = (int) $sec;
		$min = (int) $min;
		$hour = (int) $hour;
		$mday = (int) $mday;
		$mon = (int) $mon;
		$year = (int) $year;

		$nvb = sprintf("%4d%02d%02d%02d%02d%02d", $year,$mon,$mday,$hour,$min,$sec);

		if ($hour == 24) {
			$hour = 1;
		} else {
			$hour = $hour + 1;
		}

		$nva = sprintf("%4d%02d%02d%02d%02d%02d", $year,$mon,$mday,$hour,$min,$sec);

		$uri = '/' .$file . '?nvb=' . $nvb . '&nva=' . $nva;

		// generate the token
		$hmac = hash_hmac("sha1", $uri, $key, FALSE);

		// format the hash
		$hash = sprintf("%1.1s%20.20s", $gen, $hmac);

		// append the token and hash to the uri
		$uri .= "&token=$hash";

		// return the token
		return $uri;
	}
	
	function artworkToken($uri) {	
		$gen = '5';
		$key = 'LibrariesrockwithFreegalmusic.com!';
		
		// add beginning forward slash to the uri
		$uri = '/' . $uri;

		// generate the token
		$hmac = hash_hmac("sha1", $uri, $key, FALSE);

		// format the hash
		$hash = sprintf("%1.1s%20.20s", $gen, $hmac);
		
		// append the hash to the uri
		$uri .= "?token=$hash";

		// return the token
		return $uri;
	}

	function hlsToken($file, $path) {
		$gen = '5';
		$key = 'LibrariesrockwithFreegalmusic.com!';

		$start_path = '/' . $path . '/file.m3u8?tlm=hls&streams=';
		$music_file = $file . '.m3u8:256';
		$full_path = $start_path . $music_file;

		// generate the token
		$hmac = hash_hmac("sha1", $full_path, $key, FALSE);

		// format the hash
		$hash = sprintf("%1.1s%20.20s", $gen, $hmac);

		// format the final uri
		$final_uri = $start_path . $music_file . '&token=' . $hash;

		// return the token
		return $final_uri;
	}

	function streamingToken($uri) {
		$gen = '5';
		$key = 'LibrariesrockwithFreegalmusic.com!';
		
		$start_path = '/libraryideas';
		$full_path = $start_path . '/' . $uri;

		// generate the token
		$hmac = hash_hmac("sha1", $full_path, $key, FALSE);

		// format the hash
		$hash = sprintf("%1.1s%20.20s", $gen, $hmac);
		
		// format the final uri
		$final_uri = $start_path . '/mp3:' . $uri . '?token=' . $hash;

		// return the token
		return $final_uri;
	}
}

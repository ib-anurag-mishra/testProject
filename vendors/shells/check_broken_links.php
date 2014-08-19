<?php

App::import('Core', 'HttpSocket');

class CheckBrokenLinksShell extends Shell {
	
	public function main() {

		$pageToCheck = array( 'http://m68.freegalmusic.com/artists/album/TUFHSUMh/UG9w', 'http://m68.freegalmusic.com/videos/details/29197062' );

		$domDoc = new DOMDocument; // creating DOM Object
		
		$domDoc->preserveWhiteSpace = false;
		
		$pageCount = count( $pageToCheck );
		$msgBody   = '';

		for ( $i = 0; $i < $pageCount; $i++ ) {
		
			//IF THE PAGE BEING CHECKED LOADS
			if( @$domDoc->loadHTMLFile( $pageToCheck[$i] ) ) {
		
				$brokenAnchorTags = $this->checkBrokenLinks( $domDoc, 'a', 'href' );

				if ( count ($brokenAnchorTags ) > 0 ) {
					$msgBody .= '<br />Broken Link(s) on Page: <a href="' . $pageToCheck[$i] . '">' . $pageToCheck[$i] . '</a> <br /><br />';

					foreach ( $brokenAnchorTags as $brokenAnchorTag ) {
						$msgBody .= 'Name: ' . $brokenAnchorTag['name'] . '<br />';
						$msgBody .= 'Link: ' . $brokenAnchorTag['link'] . '<br />';
					}
				}

				$brokenImgTags = $this->checkBrokenLinks( $domDoc, 'img', 'src' );

				if ( count ($brokenImgTags ) > 0 ) {
					$msgBody .= '<br />Broken Imgag(s) on Page: <a href="' . $pageToCheck[$i] . '">' . $pageToCheck[$i] . '</a> <br /><br />';

					foreach ( $brokenImgTags as $brokenImgTag ) {
						$msgBody .= 'Image Link: ' . $brokenImgTag['link'] . '<br />';
					}
				}

			}
		}

		if ( $msgBody != '' ) {

			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

			mail( 'kiran.pyati@infobeans.com', 'Notification for Broken Link(s)', $msgBody, $headers );
			echo $msgBody;
		}
	}

	public function checkBrokenLinks( $domDoc, $tagName, $attrName ) {

		$badLinks       = array();
		$badStatusCodes = array('404');

		$pageLinks = $domDoc->getElementsByTagName( $tagName );

		foreach ( $pageLinks as $currLink ) {

			//LOOP THROUGH ATTRIBUTES FOR CURRENT LINK
			foreach ( $currLink->attributes as $attributeName => $attributeValue ) {

				//IF CURRENT ATTRIBUTE CONTAINS THE WEBSITE ADDRESS
				if ( $attributeName == $attrName ) {

					//INITIALIZE CURL AND TEST THE LINK
					$ch = curl_init( $attributeValue->value );

					curl_setopt( $ch, CURLOPT_NOBODY, true );
					curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
					curl_exec( $ch );

					$returnCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

					curl_close( $ch );

					//TRACK THE RESPONSE
					if( in_array( $returnCode, $badStatusCodes ) ) {
						$badLinks[] = array( 'name' => $currLink->nodeValue, 'link' => $attributeValue->value );
					}
				}
			}
		}

		return $badLinks;
	}
}
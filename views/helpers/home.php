<?php
class HomeHelper extends AppHelper {
	
	public function trimString( $str, $size, $offSet = 0 ) {
		
		if ( isset( $str ) && strlen( $str ) > $size ) {
			$str = substr( $str, $offSet, $size ) . '..';
		}

		return $str;
	}
	
	public function explicitContent( $advisory, $str, $isColor = false ) {
		
		if ( isset( $advisory ) && 'T' == $advisory ) {
			if ( $isColor === true ) {
				return $str . ' <span style="color: red;display: inline;">(Explicit)</span>';
			} else {
				return $str . ' (Explicit)';
			}
		} else {
			return $str;
		}
	}
}
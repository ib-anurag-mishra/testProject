<?php
/*
 File Name : zipusstate.php
File Description : Models page for the token functionality.
Author : m68interactive
*/

class Zipcode extends AppModel {

	var $name = 'Zipcode';
	var $useTable = 'zipcodes';
	
	public function getZipCode( $zip ) {

		$options = array( 'fields' => 'DISTINCT(ZipCode)', 'conditions' => array( 'ZipCode' => $zip ) );
		return $this->find( 'first', $options );
	}
}
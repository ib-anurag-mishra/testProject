<?php
/**
 * Validate patrons function
 * This function will validate the patron access
 Author : m68interactive
 */

Class ValidatePatronComponent extends Object
{
	var $components = array('Session');

	function validatepatron() {
		if(!$this->Session->read('library') && !$this->Session->read('patron')) {
			return '0';
		}
		else {
			if($this->Session->read('library') != '' && $this->Session->read('patron') != '') {
				return '1';
			}
			else {
				return '2';
			}
		}
	}
}

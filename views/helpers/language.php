<?php
/*
 File Name : language.php
File Description : helper file for getting language
Author : m68interactive
*/
class LanguageHelper extends AppHelper {
	var $uses = array('Language');
	var $helpers = array('Session');

	function getLanguage() {
		$languageInstance = ClassRegistry::init('Language');
		$languageDetails =  $languageInstance->find('list', array('conditions' => array('status' => 'active'), 'fields' => array('id', 'full_name'), 'cache' => 'yes'));
		return $languageDetails;
	}
}

?>
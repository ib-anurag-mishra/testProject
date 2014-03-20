<?php
/*
 File Name : library.php
File Description : helper file for getting library detail
Author : m68interactive
*/
class LibraryHelper extends AppHelper {
	var $uses = array('Library');

	function getLibraryDetails($id) {
		$libraryInstance = ClassRegistry::init('Library');
		$libraryInstance->recursive = -1;
		if (($library = Cache::read("library".$id)) === false) {
			$libraryDetails = $libraryInstance->find('first', array('conditions' => array('id' => $id)));
			Cache::write("library".$id, $libraryDetails);
		}
		$libraryDetails = Cache::read("library".$id);
		return $libraryDetails;
	}

	function getLibraryName($id) {
		$libraryInstance = ClassRegistry::init('Library');
		$libraryInstance->recursive = -1;
		$libraryDetails = $libraryInstance->find('first', array('conditions' => array('id' => $id), 'fields' => 'library_name'));
		return $libraryDetails['Library']['library_name'];
	}

	function getAuthenticationType($id) {
		$libraryInstance = ClassRegistry::init('Library');
		$libraryInstance->recursive = -1;
		$libraryDetails = $libraryInstance->find('first', array('conditions' => array('library_admin_id' => $id), 'fields' => 'library_authentication_method'));
		return $libraryDetails['Library']['library_authentication_method'];
	}
}

?>
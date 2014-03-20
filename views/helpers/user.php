<?php
/*
 File Name : user.php
File Description : helper file for getting user group detail
Author : m68interactive
*/
class UserHelper extends AppHelper {
	var $uses = array('Group');

	function getAdminType($id) {
		$groupInstance = ClassRegistry::init('Group');
		$getAdminTypes = $groupInstance->find('first', array('conditions' => array('id' => $id)));
		return $getAdminTypes['Group']['type'];
	}
}

?>
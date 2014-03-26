<?php
/*
 File Name : group.php
File Description : model for the admin types
Author : m68interactive
*/

class Group extends AppModel
{
	var $name = 'Group';
	var $actsAs = array('Acl' => array('type' => 'requester'));

	/*
	 Function Name : parentNode
	Desc :
	*/
	function parentNode(){
		return null;
	}

	var $hasMany = array(
			'User' => array(
					'className' => 'User',
					'foreignKey' => 'type_id',
					'dependent' => false,
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'limit' => '',
					'offset' => '',
					'exclusive' => '',
					'finderQuery' => '',
					'counterQuery' => ''
			)
	);

	/*
	 Function Name : getallusertype
	Desc : Gets all the User Types
	*/
	function getallusertype() {
		$getAdminTypes = $this->find('all');
		$resultArr = array();
		foreach($getAdminTypes as $getAdminType){
			if($getAdminType['Group']['id'] != 4 && $getAdminType['Group']['id'] != 5) {
				$resultArr[$getAdminType['Group']['id']] = $getAdminType['Group']['type'];
			}
		}
		return $resultArr;
	}
}
?>
<?php
 /*
 File Name : group.php
 File Description : model for the admin types
 Author : maycreate
 */
class Group extends AppModel
{
  var $name = 'Group';
  
  /*
  Function Name : getallusertype
  Desc : Gets all the User Types
  */
  var $actsAs = array('Acl' => array('type' => 'requester'));
  
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

  function getallusertype() {
   $getAdminTypes = $this->find('all');
   $resultArr = array();
   foreach($getAdminTypes as $getAdminType)
   {
    if($getAdminType['Group']['id'] != 4 && $getAdminType['Group']['id'] != 5) {
     $resultArr[$getAdminType['Group']['id']] = $getAdminType['Group']['type'];
    }
   }
   return $resultArr;
  }
}
?>
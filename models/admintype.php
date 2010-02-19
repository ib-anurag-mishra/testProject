<?php
 /*
 File Name : admin_type.php
 File Description : model for the admin types
 Author : maycreate
 */
class Admintype extends AppModel
{
  var $name = 'Admintype';
  /*
  Function Name : getallusertype
  Desc : Gets all the User Types
  */

  public function getallusertype()
  {
   $getAdminTypes = $this->find('all');
   $resultArr = array();
   foreach($getAdminTypes as $getAdminType)
   {
    $resultArr[$getAdminType['Admintype']['id']] = $getAdminType['Admintype']['type'];
   }
   return $resultArr;
  }
}
?>
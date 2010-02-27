<?php
/*
File Name : user.php
File Description : Models page for the  login functionality.
Author : maycreate
*/
class User extends AppModel
{
  var $name = 'User';
  var $actsAs = array('Acl' => 'requester');
  var $belongsTo = array(
  'Group' => array(
  'className' => 'Group',
  'foreignKey' => 'type_id',
  'conditions' => '',
  'fields' => '',
  'order' => ''
  )
  );
  
  var $validate = array(
  'first_name' => array('rule' => array( 'minLength' , 1 ),
  'message' => 'Please provide Firstname.',                        
  ) ,
  'last_name' => array('rule' => array( 'minLength' , 1 ),
  'message' => 'Please provide Lastname.',                        
  ) ,        
  'email' => array('email-1' => array
  ('rule' => array('email', true),
  'message' => 'Please provide a valid email address.',
  'last' => true
  ) ,
  'email-2' => array
  ('rule' => 'isUnique',
  'message' => 'This email already exists in our database.'                             
  )                    
  ),
  'encrypt_password' => array('rule' => array( 'minLength' , 1 ),
  'message' => 'Please provide password'                                                
  ),
  'usernameRule-2' => array
  ('rule' => 'isUnique',
  'message' => 'This username has already been taken.'                             
  ),                    
  'type_id' => array('rule' => 'notEmpty',
  'message' => 'Select a User Type.'                        
  ) 
  
  );
  function parentNode()
  {
    if (!$this->id && empty($this->data)) {
    return null;
    }
    $data = $this->data;
    if (empty($this->data)) {
    $data = $this->read();
    }
    if (!$data['User']['type_id']) {
    return null;
    } else {   
    return array('Group' => array('id' => $data['User']['type_id']));
    }
  }  
  /*
  Function Name : login
  Desc : gets all the admin users details from the db
  */
  
  public function getallusers()
  {
    $getAdmins = $this->find('all');
    return $getAdmins;
  }
  
  /*
  Function Name :  getuserdata
  Desc : gets the details for a user
  */
  
  public function getuserdata($id)
  {
    $getAdminData = $this->find('first', array('conditions' => array('User.id' => $id)));
    return $getAdminData;
  }
    
 
  /*
  Function Name : arrayremovekey
  Desc : removes the elements from an array based on keys
  */
  
  public function arrayremovekey()
  {
    $args = func_get_args();
    $arr = $args[0];
    $keys = array_slice($args,1);
    foreach($arr as $k=>$v)
    {
    if(in_array($k, $keys))
    unset($arr[$k]);
    }
    return $arr;
  }
  
  public function del($id)
  {
   if($this->delete($id))
   {
   return true;
   }else{
   return false;
   }
  }
}
?>
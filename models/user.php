<?php
/*
 File Name : user.php
 File Description : Models page for the  login functionality.
 Author : m68interactive
*/

class User extends AppModel
{
  var $name = 'User';
  var $actsAs = array('Acl' => 'requester', 'Multivalidatable', 'Containable');
  var $belongsTo = array(
                    'Group' => array(
                        'className' => 'Group',
                        'foreignKey' => 'type_id',
                        'conditions' => '',
                        'fields' => '',
                        'order' => ''
                    )
  );
  
  var $hasOne = array(
      'Library' => array(
          'className'    => 'Library',
          'dependent'    => false,
          'foreignKey' => 'library_admin_id'
      )
  );

  var $validate = array(
                  'first_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide First Name.'),
                  'last_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Last Name.'),
                  'email' => array(
                                    'email-1' => array('rule' => array('email', true), 'message' => 'Please provide a valid email address.', 'last' => true),
                                    'email-2' => array('rule' => 'isUnique', 'message' => 'This email already exists in our database.')
                              ),
                  'password' => array('rule' => array( 'minLength' , 1 ), 'message' => 'Please provide password'),
                  'usernameRule-2' => array('rule' => 'isUnique', 'message' => 'This username has already been taken.'),
                  'type_id' => array('rule' => 'notEmpty', 'message' => 'Select a User Type.')
  );
  
  var $validationSets = array(
     'library_step2' => array(
      'first_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Admin First Name.'),
      'last_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Admin Last Name.'),
      'email' => array(
                       'email-1' => array(
                                          'rule' => array('email', true),
                                          'message' => 'Please provide a valid user name/email id.',
                                          'last' => true
                       ),
                       'email-2' => array(
                                          'rule' => 'isUnique',
                                          'message' => 'This user name/email id already exists in our database.',
                                          'on' => 'create'
                       )
                 ),
      'password' => array('rule' => array( 'minLength' , 1 ), 'message' => 'Please provide password.', 'on' => 'create')
     ),
     'validate_patron_super_admin' => array(
      'first_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Admin First Name.'),
      'last_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Admin Last Name.'),
      'email' => array(
                       'email-1' => array(
                                          'rule' => array('email', true),
                                          'message' => 'Please provide a valid user name/email id.',
                                          'last' => true
                       ),
                       'email-2' => array(
                                          'rule' => 'isUnique',
                                          'message' => 'This user name/email id already exists in our database.',
                                          'on' => 'create'
                       )
                 ),
      'library_id' => array('rule' => array( 'minLength' , 1 ), 'message' => 'Please select a library.')
     )
    );
  
  /*
   Function Name : parentNode
   Desc : 
  */
  function parentNode() {
    if (!$this->id && empty($this->data)) {
      return null;
    }
    $data = $this->data;
    if (empty($this->data)) {
      $data = $this->read();
    }
    if(isset($data['User']['type_id'])){    
      if (!$data['User']['type_id']) {
        return null;
      }
      else {   
        return array('Group' => array('id' => $data['User']['type_id']));
      }
    }
  }
  
  /*
   Function Name : login
   Desc : gets all the admin users details from the db
  */
  function getallusers() {
    $getAdmins = $this->find('all');
    return $getAdmins;
  }
  
  /*
   Function Name :  getuserdata
   Desc : gets the details for a user
  */
  function getuserdata($id) {
    $getAdminData = $this->find('first', array('conditions' => array('User.id' => $id)));
    return $getAdminData;
  }
    
 
  /*
   Function Name : arrayremovekey
   Desc : removes the elements from an array based on keys
  */
  function arrayremovekey() {
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
  
  /*
   Function Name : del
   Desc : deletes the user
  */
  function del($id) {
    if($this->delete($id)){
    return true;
    }else{
    return false;
    }
  }
  
  /*
   Function Name : getalllibraryadmins
   Desc : gets all the library admins from the db
  */
  function getalllibraryadmins($condition,$id) {
    $this->recursive = -1;
    $librarytObj = new Library();
    $existingLibraries = $librarytObj->find('all', array(
                              'field' => 'library_admin_id'));
    $finalArr = array();
    foreach($existingLibraries as $existingLibrary){
      array_push($finalArr,$existingLibrary['Library']['library_admin_id']);
    }    
    $allAdmins = $this->find('all', array(
              'conditions' => array(
                      'type_id' => '4',
                      'NOT' => array('id' => $finalArr)
                      )              
    ));    
    $finalArray = Array();
    if($condition == 'edit'){
      $libraryDetails = $librarytObj->getlibrarydata($id);      
      $adminDetails = $this->getuserdata($libraryDetails['Library']['library_admin_id']);
      $finalArray[$adminDetails['User']['id']] = $adminDetails['User']['email'];      
    }
    if($allAdmins != ''){
      foreach($allAdmins as $allAdmin){
        $finalArray[$allAdmin['User']['id']] =  $allAdmin['User']['email'];
      }
    }
    return $finalArray;
  }
}
?>
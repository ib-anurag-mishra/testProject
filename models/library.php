<?php
 /*
 File Name : library.php
 File Description : Models page for the  libraries functionality.
 Author : maycreate
 */
class Library extends AppModel
{
    var $name = 'Library';
    
    var $actsAs = array('Multivalidatable');
    
    var $belongsTo = array(
      'User' => array(
      'className' => 'User',
      'foreignKey' => 'library_admin_id',
      'condition' => 'User.type_id = 4'
      )
    );
    
    var $hasOne = array(
      'LibraryTemplate' => array(
          'className'    => 'LibraryTemplate',
          'dependent'    => false,
          'foreignKey' => 'id'
      )
    );
    
    var $hasMany = array(
      'LibraryPurchase' => array(
          'className'    => 'LibraryPurchase',
          'dependent'    => false,
          'foreignKey' => 'library_id'
      )
    );
    
    var $validate = array(
      'library_name' => array('rule' => array( 'minLength' , 1 ), 'message' => 'Please provide Library Name.'),
      'library_domain_name' => array('rule' => 'url', 'message' => 'Please provide a valid Library Domain Name.'),
      'library_contact_fname' => array('rule' => array( 'minLength' , 1 ), 'message' => 'Please provide Library Contact First Name.'),
      'library_contact_lname' => array('rule' => array( 'minLength' , 1 ), 'message' => 'Please provide Library Contact Last Name.'),
      'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.'),
      'library_download_limit' => array('rule' => array( 'minLength' , 1 ), 'message' => 'Please select a Library Download Limit.'),
      'library_download_type' => array('rule' => array( 'minLength' , 1 ), 'message' => 'Please select a Library Download Type.'),
      'library_user_download_limit' => array('rule' => array( 'minLength' , 1 ), 'message' => 'Please select a Library User\' Download Limit.')
    );
    
    var $validationSets = array(
     'library_step1' => array(
      'library_name' => array('rule' => array( 'minLength' , 1 ), 'message' => 'Please provide Library Name.'),
      'library_domain_name' => array('rule' => 'url', 'message' => 'Please provide a valid Library Domain Name.'),
      'library_contact_fname' => array('rule' => array( 'minLength' , 1 ), 'message' => 'Please provide Library Contact First Name.'),
      'library_contact_lname' => array('rule' => array( 'minLength' , 1 ), 'message' => 'Please provide Library Contact Last Name.'),
      'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.')
     ),
     'library_step3' => array(
       'library_download_limit' => array('rule' => array( 'minLength' , 1 ), 'message' => 'Please select a Library Download Limit.'),
       'library_download_type' => array('rule' => array( 'minLength' , 1 ), 'message' => 'Please select a Library Download Type.')
      ),
     'library_step4' => array(
       'library_user_download_limit' => array('rule' => array( 'minLength' , 1 ), 'message' => 'Please select a Library User\' Download Limit.')
      )
    );
    
    /*
    Function Name : getalllibraries
    Desc : gets all the library details from the db
    */
    
    public function getalllibraries()
    {
        $getLibraries = $this->find('all');
        return $getLibraries;
    }
    
     /*
    Function Name :  getlibrarydata
    Desc : gets the details for a library
    */
    
    public function getlibrarydata($id)
    {
       $getLibraryData = $this->find('first', array('conditions' => array('Library.id' => $id)));
       return $getLibraryData;
    }
    
    /*
    Function Name : checkusername
    Desc : Checks the presence of username
    */
    
    public function checkusername($username,$id = ' ')
    {
     if($id == ' ')
     {
       $getUsernameCount = $this->find('count', array('conditions' => array('username' => $username)));
     }else{
       $getUsernameCount = $this->find('count', array('conditions' => array('username' => $username,'Library.id !=' => $id)));
     }
       if($getUsernameCount == 0)
       {
            return 1;
       }else{
            return 0;
       }
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
}
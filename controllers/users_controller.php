<?php
 /*
 File Name : admins_controller.php
 File Description : Controller page for the  login functionality.
 Author : maycreate
 */
Class UsersController extends AppController
{
   var $name = 'Users';
   var $helpers = array('Html','Ajax','Javascript','Form', 'User', 'Library');
   var $layout = 'admin';
   var $components = array('Session','Auth','Acl','PasswordHelper','Email');
   var $uses = array('User','Group', 'Library', 'Currentpatron', 'Download');
   
   function before_filter() {
     $this->Auth->userModel = 'User';
     $this->Auth->loginAction = array('controller' => 'users', 'action' => 'admin_login');
     $this->Auth->loginRedirect = array('controller' => 'users', 'action' => 'admin_index');
     $this->Auth->logoutRedirect = '/users/admin_login';
     $this->Auth->allow('admin_login','admin_logout','forgot_password');
     $this->set('username', $this->Session->read('Auth.User.username'));
   }
 
   function admin_index() {
         $userType = $this->Session->read('Auth.User.type_id');
         if($userType == '5'){
            $this->redirect('/homes/index');
            $this->Auth->autoRedirect = false;     
         }
        //takes to the default admin home page
         $this->set('username', $this->Session->read('Auth.User.username'));  //setting the username to display on the header 
   }
    
   /*
    Function Name : login
    Desc : Validates admin login credentials
   */
   function admin_login() {
      $this->layout = 'admin';
      if (empty($this->data)) {
         $this->Session->delete('Message.auth');
      }
      if ($this->Session->read('Auth.User')) {
         $userType = $this->Session->read('Auth.User.type_id');
         if($userType == '5'){
            $this->redirect('/homes/index');
            $this->Auth->autoRedirect = false;     
         }
         $this->redirect('/users/admin_index');
         $this->Auth->autoRedirect = false;
      }
   }
    
   /*
    Function Name : logout
    Desc : Logs admin out of the system
   */
   function admin_logout() {
     $this->redirect($this->Auth->logout()); 
   }
   
   function login(){     
      if ($this->Session->read('Auth.User')){
         $userType = $this->Session->read('Auth.User.type_id');
         if($userType == '5'){
            $this->redirect('/homes/index');
            $this->Auth->autoRedirect = false;     
         }
      }
   }
   
   function index(){      
      /*echo "<PRE>";
      print_r($_SESSION);exit;*/
      $patronId = $this->Session->read('Auth.User.id');      
      $typeId = $this->Session->read('Auth.User.type_id');
      if($typeId == '5'){
         $libraryId = $this->Session->read('Auth.User.library_id');
         $this->Library->recursive = -1;
         $libraryArr = $this->Library->find('first',array(                                                
                                                'conditions' => array('Library.id' => $libraryId)
                                                )
                                            );         
         $authMethod = $libraryArr['Library']['library_authentication_method'];        
         if($authMethod == 'user_account'){
            $currentPatron = $this->Currentpatron->find('all', array('conditions' => array('libid' => $libraryId, 'patronid' => $patronId)));           
            if(count($currentPatron) > 0)
            {
                $modifiedTime = strtotime($currentPatron[0]['Currentpatron']['modified']);                           
                $date = strtotime(date('Y-m-d H:i:s'));              
                if(!(isset($_SESSION['patron'])))
                {               
                    if(($date-$modifiedTime) > 60)
                    {
                        $updateArr = array();
                        $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                
                        $updateArr['created'] = date('Y-m-d H:i:s');
                        $updateArr['session_id'] = session_id();
                        $this->Currentpatron->save($updateArr);
                    }
                    else
                    {                
                        $this -> Session -> setFlash("This account is already active.");
                        $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
                    }
                }
                else
                {
                    $sessionId = session_id();                    
                    if($currentPatron[0]['Currentpatron']['session_id'] != $sessionId)
                    {                        
                        if(($date-$modifiedTime) > 60)
                        {                            
                            $updateArr = array();
                            $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                
                            $updateArr['created'] = date('Y-m-d H:i:s');
                            $updateArr['session_id'] = session_id();
                            $this->Currentpatron->save($updateArr);
                        }
                        else
                        {                            
                            $this -> Session -> setFlash("This account is already active.");
                            $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
                        }                  
                    }                    
                }
            }
            else
            {                
                $insertArr['libid'] = $libraryId;
                $insertArr['patronid'] = $patronId;
                $insertArr['session_id'] = session_id();                 
                $this->Currentpatron->save($insertArr);
            }
            $this->Session->write("library", $libraryId);
            $this->Session->write("patron", $patronId);
            $isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $libraryId,'patronid' => $patronId)));            
            $this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
            $startDate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-(date('w', mktime(0, 0, 0, date('m'), date('d'), date('Y')))-1), date('Y')))." 00:00:00";
            $endDate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')+(7-date('w', mktime(0, 0, 0, date('m'), date('d'), date('Y')))), date('Y')))." 23:59:59";            
            $this->Session->write("downloadsAllotted", $libraryArr['Library']['library_user_download_limit']);
            $results =  $this->Download->find('count',array('conditions' => array('library_id' => $libraryId,'patron_id' => $patronId,'created BETWEEN ? AND ?' => array($startDate, $endDate))));
            $this ->Session->write("downloadsUsed", $results);            
            if($libraryArr['Library']['library_block_explicit_content'] == '1')
            {
                $this ->Session->write("block", 'yes');
            }
            else{
                $this ->Session->write("block", 'no');
            }
            $this->redirect(array('controller' => 'homes', 'action' => 'index'));
         }
         else{
            $this->redirect($this->Auth->logout());
         }
      }
      else{
         $this->redirect($this->Auth->logout());
      }
   }
   
   function logout() {
      session_destroy(); 
      $this->redirect($this->Auth->logout()); 
   }
   
   
   /*
    Function Name : listuser
    Desc : action for listing all the admin users
   */
   function admin_manageuser()
   {
        $this->paginate = array('conditions' => array('type_id <> 5'));
        $this->set('admins', $this->paginate('User'));
   }
    
   /*
    Function Name : userform
    Desc : action for displaying the add/edit user form
   */
    
   function admin_userform() {
       if(!empty($this->params['named']['id']))//gets the values from the url in form  of array
       {
           $adminUserId = $this->params['named']['id'];
           if(trim($adminUserId) != "" && is_numeric($adminUserId))
           {
               $this->set('formAction','admin_userform/id:'.$adminUserId);
               $this->set('formHeader','Edit User');     
               $this->set('getData', $this->User->getuserdata($adminUserId));
               //editting a value
               if(isset($this->data))
               {
                   $updateObj = new User();
                   $getData['User'] = $this->data['User'];
                   $getData['Group']['id'] = $this->data['User']['type_id'];
                   $this->set('getData', $getData);
                   $this->User->id = $this->data['User']['id'];
                   if(trim($this->data['User']['password']) == "48d63321789626f8844afe7fdd21174eeacb5ee5")
                   {
                      // do not update the password
                      $this->data['User']= $updateObj->arrayremovekey($this->data['User'],'password');
                   }
                   $this->User->set($this->data['User']);
                   if($this->User->save())
                   {
                     $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                     $this->redirect('manageuser');
                   }                
               }
                //editting a value
           }
       }else{
               $arr = array();
               $this->set('getData',$arr);
               $this->set('formAction','admin_userform');
               $this->set('formHeader','Create User');
               
               //insertion Operation
               if(isset($this->data))
               {
                   $insertObj = new User();                    
                   $getData['User'] = $this->data['User'];
                   $getData['Group']['id'] = $this->data['User']['type_id'];
                   $this->set('getData', $getData);
                   if($this->data['User']['password'] == "48d63321789626f8844afe7fdd21174eeacb5ee5")
                   {                     
                    $this->data['User']['password'] = "";                      
                   }
                  
                   $this->User->set($this->data['User']);                  
                   if($this->User->save())
                   {                    
                     $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                     $this->redirect('manageuser');
                   }
                   else
                   {
                     $this->data['User']['password'] = '';
                     $this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
                   }             
               }
               //insertion operation
       }
       $this->set('options',$this->Group->getallusertype());
   }
   
   /*
    Function Name : managepatron
    Desc : action for listing all the patron users
   */
   function admin_managepatron()
   {
        if($this->Session->read("Auth.User.type_id") == 4) {
            $libraryAdminID = $this->Library->find("first", array("conditions" => array('library_admin_id' => $this->Session->read("Auth.User.id")), 'fields' => array('id', 'library_name'), 'recursive' => -1));
            $this->set('libraryID', $libraryAdminID["Library"]["id"]);
            $this->set('libraryname', $libraryAdminID["Library"]["library_name"]);
            $this->paginate = array('conditions' => array('type_id' => 5, 'library_id' => $libraryAdminID["Library"]["id"]));
        }
        else {
            $this->set('libraryID', "");
            $this->paginate = array('conditions' => array('type_id' => 5));
        }
        $this->User->recursive = -1;
        $this->set('patrons', $this->paginate('User'));
   }
   
   /*
    Function Name : patronform
    Desc : action for displaying the add/edit patron form
   */
   function admin_patronform() {
       if(!empty($this->params['named']['id']))//gets the values from the url in form  of array
       {
           $patronUserId = $this->params['named']['id'];
           if(trim($patronUserId) != "" && is_numeric($patronUserId))
           {
               $this->set('formAction','admin_patronform/id:'.$patronUserId);
               $this->set('formHeader','Edit Patron');     
               $this->set('getData', $this->User->getuserdata($patronUserId));
               //editting a value
               if(isset($this->data))
               {
                   $updateObj = new User();
                   $getData['User'] = $this->data['User'];
                   $getData['Group']['id'] = $this->data['User']['type_id'];
                   $this->set('getData', $getData);
                   $this->User->id = $this->data['User']['id'];
                   $password = trim($this->data['User']['password']);
                   if(trim($this->data['User']['password']) == "48d63321789626f8844afe7fdd21174eeacb5ee5")
                   {
                      // do not update the password
                      $this->data['User'] = $updateObj->arrayremovekey($this->data['User'],'password');
                   }
                   $this->User->set($this->data['User']);
                   $this->User->setValidation('validate_patron_super_admin');
                   if($this->User->validates()) {
                    if($this->User->save())
                    {
                     if($password != "48d63321789626f8844afe7fdd21174eeacb5ee5")
                     {
                      $temp_password = $this->data['User']['original_password'];
                      $this->_sendModifyPatronMail( $this->User->id, $temp_password );
                     }
                      $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                      $this->redirect('managepatron');
                    }
                    else {
                     $this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
                    }
                   }
                   else {
                    $this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
                   }
               }
                //editting a value
           }
       }else{
               $arr = array();
               $this->set('getData',$arr);
               $this->set('formAction','admin_patronform');
               $this->set('formHeader','Create Patron');
               
               //insertion Operation
               if(isset($this->data))
               {
                   $insertObj = new User();
                   $getData['User'] = $this->data['User'];
                   $getData['Group']['id'] = $this->data['User']['type_id'];
                   $this->set('getData', $getData);
                  
                   $this->User->set($this->data['User']);
                   $this->User->setValidation('validate_patron_super_admin');
                   if($this->User->validates()) {
                    $temp_password = $this->PasswordHelper->generatePassword(8);
                    $this->data['User']['password'] = Security::hash(Configure::read('Security.salt').$temp_password);
                    $this->User->set($this->data['User']);
                    if($this->User->save())
                    {
                      $this->_sendNewPatronMail( $this->User->id, $temp_password );
                      $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                      $this->redirect('managepatron');
                    }
                    else
                    {
                      $this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
                    }
                   }
                   else
                   {
                     $this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
                   }
               }
               //insertion operation
       }
       if($this->Session->read("Auth.User.type_id") == 4) {
           $libraryAdminID = $this->Library->find("first", array("conditions" => array('library_admin_id' => $this->Session->read("Auth.User.id")), 'fields' => array('id', 'library_name'), 'recursive' => -1));
           $this->set('libraryID', $libraryAdminID["Library"]["id"]);
           $this->set('libraryname', $libraryAdminID["Library"]["library_name"]);
       }
       else {
           $this->set('libraries', $this->Library->find('list', array('fields' => array('Library.library_name'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
           $this->set('libraryID', "");
       }
       $this->set('options',$this->Group->getallusertype());
   }
     
   /*
    Function Name : delete
    Desc : For deleting a user
   */
   function admin_delete() {
     $deleteAdminUserId = $this->params['named']['id'];      
     if($this->User->delete($deleteAdminUserId))
     {
       $this->Session->setFlash('Data deleted successfully!', 'modal', array('class' => 'modal success'));
       $this->redirect('manageuser');
     }else{
       $this->Session->setFlash('Error occured while deleteting the record', 'modal', array('class' => 'modal problem'));
       $this->redirect('manageuser');
     }
   }
   
   function _sendNewPatronMail($id, $password) {
    Configure::write('debug', 0);
    $this->Email->template = 'email/newPatronEmail';
    $this->User->recursive = -1;
    $Patron = $this->User->read(null,$id);
    $this->set('Patron', $Patron);
    $this->set('password', $password);
    $this->set('webroot', $this->webroot);
    $this->Email->to = $Patron['User']['email'];
    $this->Email->subject = 'FreegalMusic - New patron account information';
    $result = $this->Email->send(); 
   }
   
   function _sendModifyPatronMail($id, $password) {
    Configure::write('debug', 0);
    $this->Email->template = 'email/modifyPatronEmail';
    $this->User->recursive = -1;
    $Patron = $this->User->read(null,$id);
    $this->set('Patron', $Patron);
    $this->set('password', $password);
    $this->set('webroot', $this->webroot);
    $this->Email->to = $Patron['User']['email'];
    $this->Email->subject = 'FreegalMusic - Patron account password changed!!';
    $result = $this->Email->send(); 
   }
   
   function my_account(){
      $this->layout = 'home';
      $patronId = $this->Session->read('patron');
      $this->set('getData', $this->User->getuserdata($patronId));
      if(isset($this->data)){
         $this->data['User']['type_id'] = 5;
         $getData['User'] = $this->data['User'];
         $getData['type_id'] = 5;        
         $this->set('getData', $getData);        
         if(trim($this->data['User']['password']) == "48d63321789626f8844afe7fdd21174eeacb5ee5"){            
            // do not update the password
            $this->data['User']= $this->User->arrayremovekey($this->data['User'],'password');
         }         
         $this->User->set($this->data['User']);        
         if($this->User->save()){         
            $this->Session->setFlash('Data has been saved successfully!');
            $this->redirect($this->webroot.'users/my_account');
         }
      }
   }
}
?>
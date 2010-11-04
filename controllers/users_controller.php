<?php
 /*
 File Name : users_controller.php
 File Description : Controller page for the  login functionality.
 Author : maycreate
 */
 
Class UsersController extends AppController
{
   var $name = 'Users';
   var $helpers = array('Html','Ajax','Javascript','Form', 'User', 'Library', 'Page');
   var $layout = 'admin';
   var $components = array('Session','Auth','Acl','PasswordHelper','Email','sip2','ezproxysso');
   var $uses = array('User','Group', 'Library', 'Currentpatron', 'Download','Variable','Url');
   
   /*
    Function Name : beforeFilter
    Desc : actions that needed before other functions are getting called
   */
   function beforeFilter(){
	parent::beforeFilter();
        $this->Auth->allow('logout','ilogin','inlogin','indlogin','inhlogin','slogin','snlogin','sdlogin','sndlogin','admin_user_deactivate','admin_user_activate','admin_patron_deactivate','admin_patron_activate','sso');
   }
   
   /*
    Function Name : admin_index
    Desc : actions for welcome admin login
   */
   
   function admin_index() {
         $userType = $this->Session->read('Auth.User.type_id'); 
		 if($this->Session->read('Auth.User.user_status')=='inactive'){
			$this->Session->destroy('user'); 
			$this -> Session -> setFlash("This account has been deactivated.  Please contact your administrator for further questions.");
			$this->redirect(array('controller' => 'users', 'action' => 'login'));	
		 }
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
   
   /*
    Function Name : login
    Desc : Logs users/patrons in to the system
   */
   
   function login(){
      $this->layout = 'login';
      if ($this->Session->read('Auth.User')){
         $userType = $this->Session->read('Auth.User.type_id');
         if($userType == '5'){
            $this->redirect('/homes/index');
            $this->Auth->autoRedirect = false;     
         }
      }
   }
   
   /*
    Function Name : index
    Desc : Users index to allow/disallow valid users
   */
   
   function index(){ 
      $patronId = $this->Session->read('Auth.User.id');      
      $typeId = $this->Session->read('Auth.User.type_id');
	  if($this->Session->read('Auth.User.user_status')=='inactive'){
		  $this->Session->destroy('user'); 
		  $this -> Session -> setFlash("This account has been deactivated.  Please contact your administrator for further questions.");
		  $this->redirect(array('controller' => 'users', 'action' => 'login'));	
	  }	  
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
            if(count($currentPatron) > 0){
                $modifiedTime = strtotime($currentPatron[0]['Currentpatron']['modified']);                           
                $date = strtotime(date('Y-m-d H:i:s'));              
                if(!($this->Session->read('patron'))){               
                    if(($date-$modifiedTime) > 60){
                        $updateArr = array();
                        $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                                        
                        $updateArr['session_id'] = session_id();
                        $this->Currentpatron->save($updateArr);
                    }
                    else{
                        $this->Session->destroy('user'); 
                        $this -> Session -> setFlash("This account is already active.");
                        $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
                    }
                }
                else{
                    $sessionId = session_id();                    
                    if($currentPatron[0]['Currentpatron']['session_id'] != $sessionId){                        
                        if(($date-$modifiedTime) > 60){                            
                            $updateArr = array();
                            $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                                           
                            $updateArr['session_id'] = session_id();
                            $this->Currentpatron->save($updateArr);
                        }
                        else{
                            $this->Session->destroy('user');
                            $this -> Session -> setFlash("This account is already active.");                            
                            $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
                        }                  
                    }                    
                }
            }
            else{                
                $insertArr['libid'] = $libraryId;
                $insertArr['patronid'] = $patronId;
                $insertArr['session_id'] = session_id();                 
                $this->Currentpatron->save($insertArr);
            }
            $this->Session->write("library", $libraryId);
            $this->Session->write("patron", $patronId);
			$this->Session->write("territory", $libraryArr['Library']['library_territory']);
            $isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $libraryId,'patronid' => $patronId)));            
            $this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
            $startDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."1"))." 00:00:00";
            $endDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."7"))." 23:59:59";
            $this->Session->write("downloadsAllotted", $libraryArr['Library']['library_user_download_limit']);
			$this->Download->recursive = -1;
            $results =  $this->Download->find('count',array('conditions' => array('library_id' => $libraryId,'patron_id' => $patronId,'created BETWEEN ? AND ?' => array($startDate, $endDate))));
            $this ->Session->write("downloadsUsed", $results);            
            if($libraryArr['Library']['library_block_explicit_content'] == '1'){
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
   
   /*
    Function Name : logout
    Desc : Logs users/patron out of the system
   */
   
   function logout() {      
      $patronId = $this->Session->read('patron');
      $libraryId = $this->Session->read('library');
      $patronDetails = $this->Currentpatron->find('all',array('conditions' => array('patronid' => $patronId,'libid' => $libraryId)));
      if(count($patronDetails) > 0){         
         $updateTime = date( "Y-m-d H:i:s", time()-60 );
         $this->Currentpatron->id = $patronDetails[0]['Currentpatron']['id'];        
         $this->Currentpatron->saveField('modified',$updateTime, false);         
         if($this->Session->read('referral_url') && ($this->Session->read('referral_url') != '')){            
			$redirect_url = $this->Session->read('referral_url');
			$this->Session->destroy();
			$this->redirect($redirect_url, null, true);
         }
         elseif($this->Session->read('innovative') && ($this->Session->read('innovative') != '')){            
			if($this->Session->read('referral')){
				$redirect_url = $this->Session->read('referral');
				$this->Session->destroy();
				$this->redirect($redirect_url, null, true);
			} else {
				$this->Session->destroy();
				$this->redirect(array('controller' => 'users', 'action' => 'ilogin'));				
			}
         }
         elseif($this->Session->read('innovative_https') && ($this->Session->read('innovative_https') != '')){            
			if($this->Session->read('referral')){
				$redirect_url = $this->Session->read('referral');
				$this->Session->destroy();
				$this->redirect($redirect_url, null, true);
			} else {
				$this->Session->destroy();
				$this->redirect(array('controller' => 'users', 'action' => 'inhlogin'));				
			}
         }		 
         elseif($this->Session->read('innovative_wo_pin') && ($this->Session->read('innovative_wo_pin') != '')){            
			if($this->Session->read('referral')){
				$redirect_url = $this->Session->read('referral');
				$this->Session->destroy();
				$this->redirect($redirect_url, null, true);
			} else {
				$this->Session->destroy();
				$this->redirect(array('controller' => 'users', 'action' => 'inlogin'));				
			}
		 }
         elseif($this->Session->read('innovative_var_wo_pin') && ($this->Session->read('innovative_var_wo_pin') != '')){
			if($this->Session->read('referral')){
				$redirect_url = $this->Session->read('referral');
				$this->Session->destroy();
				$this->redirect($redirect_url, null, true);
			} else {
				$this->Session->destroy();
				$this->redirect(array('controller' => 'users', 'action' => 'indlogin'));				
			}
         }		 
         elseif($this->Session->read('sip2') && ($this->Session->read('sip2') != '')){            
			if($this->Session->read('referral')){
				$redirect_url = $this->Session->read('referral');
				$this->Session->destroy();
				$this->redirect($redirect_url, null, true);
			} else {
				$this->Session->destroy();
				$this->redirect(array('controller' => 'users', 'action' => 'slogin'));				
			}
         }
		 elseif($this->Session->read('sip') && ($this->Session->read('sip') != '')){            
			if($this->Session->read('referral')){
				$redirect_url = $this->Session->read('referral');
				$this->Session->destroy();
				$this->redirect($redirect_url, null, true);
			} else {
				$this->Session->destroy();
				$this->redirect(array('controller' => 'users', 'action' => 'snlogin'));				
			}
         }
		 elseif($this->Session->read('sip2_var') && ($this->Session->read('sip2_var') != '')){            
			if($this->Session->read('referral')){
				$redirect_url = $this->Session->read('referral');
				$this->Session->destroy();
				$this->redirect($redirect_url, null, true);
			} else {
				$this->Session->destroy();
				$this->redirect(array('controller' => 'users', 'action' => 'sdlogin'));				
			}
 
		}
		 elseif($this->Session->read('sip2_var_wo_pin') && ($this->Session->read('sip2_var_wo_pin') != '')){            
			if($this->Session->read('referral')){
				$redirect_url = $this->Session->read('referral');
				$this->Session->destroy();
				$this->redirect($redirect_url, null, true);
			} else {
				$this->Session->destroy();
				$this->redirect(array('controller' => 'users', 'action' => 'sndlogin'));				
			}
 
		}		
		 elseif($this->Session->read('ezproxy') && ($this->Session->read('ezproxy') != '')){		
			$redirect_url = $this->Session->read('referral');
			$redirect_url = str_replace('login', 'logout',$redirect_url);
			$this->Session->destroy();
			$this->redirect($redirect_url, null, true);				
		}		
         else{            
            $this->Session->destroy();
			$this->redirect($this->Auth->logout());    
         }         
      }     
   }
   
   /*
    Function Name : admin_manageuser
    Desc : action for listing all the admin users
   */
   
   function admin_manageuser(){
        $this->paginate = array('conditions' => array('type_id <> 5'));
        $this->set('admins', $this->paginate('User'));
   }
    
   /*
    Function Name : admin_userform
    Desc : action for displaying the add/edit user form
   */
   
   function admin_userform() {
       if(!empty($this->params['named']['id'])){ //gets the values from the url in form  of array
           $adminUserId = $this->params['named']['id'];
           if(trim($adminUserId) != "" && is_numeric($adminUserId)){
               $this->set('formAction','admin_userform/id:'.$adminUserId);
               $this->set('formHeader','Edit User');
               $this->set('getData', $this->User->getuserdata($adminUserId));
               //editting a value
               if(isset($this->data)){
                   $updateObj = new User();
                   $getData['User'] = $this->data['User'];
                   $getData['Group']['id'] = $this->data['User']['type_id'];
                   $this->set('getData', $getData);
                   $this->User->id = $this->data['User']['id'];
                   if(trim($this->data['User']['password']) == "48d63321789626f8844afe7fdd21174eeacb5ee5"){
                      // do not update the password
                      $this->data['User']= $updateObj->arrayremovekey($this->data['User'],'password');
                   }
                   $this->User->set($this->data['User']);
                   if($this->User->save()){
                     $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                     $this->redirect('manageuser');
                   }                
               }               
           }
       }else{
               $arr = array();
               $this->set('getData',$arr);
               $this->set('formAction','admin_userform');
               $this->set('formHeader','Create User');               
               //insertion Operation
               if(isset($this->data)){
                   $insertObj = new User();                    
                   $getData['User'] = $this->data['User'];
                   $getData['Group']['id'] = $this->data['User']['type_id'];
                   $this->set('getData', $getData);
                   if($this->data['User']['password'] == "48d63321789626f8844afe7fdd21174eeacb5ee5"){                     
                    $this->data['User']['password'] = "";                      
                   }                  
                   $this->User->set($this->data['User']);                  
                   if($this->User->save()){                    
                     $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                     $this->redirect('manageuser');
                   }
                   else{
                     $this->data['User']['password'] = '';
                     $this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
                   }             
               }               
       }
       $this->set('options',$this->Group->getallusertype());
   }
   
   /*
    Function Name : admin_managepatron
    Desc : action for listing all the patron users
   */
   
   function admin_managepatron(){
       if($this->Session->read("Auth.User.type_id") == 4 && $this->Library->getAuthenticationType($this->Session->read('Auth.User.id')) == "referral_url") {
        $this->redirect('/admin/reports/index');
       }
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
    Function Name : admin_patronform
    Desc : action for displaying the add/edit patron form
   */
   
   function admin_patronform() {
       if($this->Session->read("Auth.User.type_id") == 4 && $this->Library->getAuthenticationType($this->Session->read('Auth.User.id')) == "referral_url") {
        $this->redirect('/admin/reports/index');
       }
       if(!empty($this->params['named']['id'])){ //gets the values from the url in form  of array
           $patronUserId = $this->params['named']['id'];
           if(trim($patronUserId) != "" && is_numeric($patronUserId)){
               $this->set('formAction','admin_patronform/id:'.$patronUserId);
               $this->set('formHeader','Edit Patron');     
               $this->set('getData', $this->User->getuserdata($patronUserId));
               //editting a value
               if(isset($this->data)){
                   $updateObj = new User();
                   $getData['User'] = $this->data['User'];
                   $getData['Group']['id'] = $this->data['User']['type_id'];
                   $this->set('getData', $getData);
                   $this->User->id = $this->data['User']['id'];
                   $password = trim($this->data['User']['password']);
                   if(trim($this->data['User']['password']) == "48d63321789626f8844afe7fdd21174eeacb5ee5"){
                      // do not update the password
                      $this->data['User'] = $updateObj->arrayremovekey($this->data['User'],'password');
                   }
                   $this->User->set($this->data['User']);
                   $this->User->setValidation('validate_patron_super_admin');
                   if($this->User->validates()) {
                    if($this->User->save()){
                     if($password != "48d63321789626f8844afe7fdd21174eeacb5ee5"){
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
           }
       }else{
               $arr = array();
               $this->set('getData',$arr);
               $this->set('formAction','admin_patronform');
               $this->set('formHeader','Create Patron');               
               //insertion Operation
               if(isset($this->data)){
                   $insertObj = new User();
                   $getData['User'] = $this->data['User'];
                   $getData['Group']['id'] = $this->data['User']['type_id'];
                   $this->set('getData', $getData);                  
                   $this->User->set($this->data['User']);
                   $this->User->setValidation('validate_patron_super_admin');
                   if($this->User->validates()){
                    $temp_password = $this->PasswordHelper->generatePassword(8);
                    $this->data['User']['password'] = Security::hash(Configure::read('Security.salt').$temp_password);
                    $this->User->set($this->data['User']);
                    if($this->User->save()){
						$receipt = $this->_sendNewPatronMail( $this->User->id, $temp_password );
						if($receipt == '1'){
							$this->Session->setFlash('Data has been saved successfully and the Email has been sent.', 'modal', array('class' => 'modal success'));
						} else {
							$this->Session->setFlash($receipt, 'modal', array('class' => 'modal problem'));					  
						}
						$this->redirect('managepatron');
                    }
                    else{
						$this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
                    }
                   }
                   else{
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
           $this->set('libraries', $this->Library->find('list', array("conditions" => array('library_authentication_method' => 'user_account'), 'fields' => array('Library.library_name'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
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
     if($this->User->delete($deleteAdminUserId)){
       $this->Session->setFlash('Data deleted successfully!', 'modal', array('class' => 'modal success'));
       $this->redirect('manageuser');
     }else{
       $this->Session->setFlash('Error occured while deleteting the record', 'modal', array('class' => 'modal problem'));
       $this->redirect('manageuser');
     }
   }
 
    /*
     Function Name : admin_user_activate
     Desc : For activating a User
    */
	
    function admin_user_activate() {
        $userID = $this->params['named']['id'];
        if(trim($userID) != "" && is_numeric($userID)) {
			$existingUser = $this->User->getuserdata($userID);
            $this->User->id = $userID;
			$this->User->set(array('user_status' => 'active','type_id'=>$existingUser['User']['type_id']));
            $this->Session -> setFlash( 'User activated successfully!', 'modal', array( 'class' => 'modal success' ) );
            $this->User->save();
            $this->autoRender = false;
            $this->redirect('manageuser');
        }
        else {
            $this->Session -> setFlash( 'Error occured while activating the library', 'modal', array( 'class' => 'modal problem' ) );
            $this->autoRender = false;
            $this->redirect('manageuser');
        }
    }
	
    /*
     Function Name : admin_user_deactivate
     Desc : For deactivating a User
    */
	
    function admin_user_deactivate() {
        $userID = $this->params['named']['id'];
        if(trim($userID) != "" && is_numeric($userID)) {
			$existingUser = $this->User->getuserdata($userID);
            $this->User->id = $userID;
			$this->User->set(array('user_status' => 'inactive','type_id'=>$existingUser['User']['type_id']));
            $this->Session -> setFlash( 'User deactivated successfully!', 'modal', array( 'class' => 'modal success' ) );
            $this->User->save();
            $this->autoRender = false;
            $this->redirect('manageuser');
        }
        else {
            $this->Session->setFlash('Error occured while deactivating the User', 'modal', array('class' => 'modal problem'));
            $this->autoRender = false;
            $this->redirect('manageuser');
        }
    }
    
    /*
     Function Name : admin_patron_activate
     Desc : For activating a Patron
    */
	
    function admin_patron_activate() {
        $userID = $this->params['named']['id'];
        if(trim($userID) != "" && is_numeric($userID)) {
			$existingUser = $this->User->getuserdata($userID);
            $this->User->id = $userID;
			$this->User->set(array('user_status' => 'active','type_id'=>$existingUser['User']['type_id']));
            $this->Session -> setFlash( 'Patron activated successfully!', 'modal', array( 'class' => 'modal success' ) );
            $this->User->save();
            $this->autoRender = false;
            $this->redirect('managepatron');
        }
        else {
            $this->Session -> setFlash( 'Error occured while activating the library', 'modal', array( 'class' => 'modal problem' ) );
            $this->autoRender = false;
            $this->redirect('managepatron');
        }
    }
	
    /*
     Function Name : admin_patron_deactivate
     Desc : For deactivating a Patron
    */
	
    function admin_patron_deactivate() {
        $userID = $this->params['named']['id'];
        if(trim($userID) != "" && is_numeric($userID)) {
			$existingUser = $this->User->getuserdata($userID);
            $this->User->id = $userID;
			$this->User->set(array('user_status' => 'inactive','type_id'=>$existingUser['User']['type_id']));
            $this->Session -> setFlash( 'Patron deactivated successfully!', 'modal', array( 'class' => 'modal success' ) );
            $this->User->save();
            $this->autoRender = false;
            $this->redirect('managepatron');
        }
        else {
            $this->Session->setFlash('Error occured while deactivating the User', 'modal', array('class' => 'modal problem'));
            $this->autoRender = false;
            $this->redirect('managepatron');
        }
    }
    
 
   /*
    Function Name : _sendNewPatronMail
    Desc : For sending new patron email
   */
   
   function _sendNewPatronMail($id, $password) {
    Configure::write('debug', 0);
    $this->Email->template = 'email/newPatronEmail';
    $this->User->recursive = -1;
    $Patron = $this->User->read(null,$id);
    $this->set('Patron', $Patron);
    $this->set('password', $password);
    $this->Email->to = $Patron['User']['email'];
    $this->Email->from = Configure::read('App.adminEmail');
    $this->Email->fromName = Configure::read('App.fromName');
    $this->Email->subject = 'FreegalMusic - New patron account information';
    $this->Email->smtpHostNames = Configure::read('App.SMTP');
    $this->Email->smtpAuth = Configure::read('App.SMTP_AUTH');
    $this->Email->smtpUserName = Configure::read('App.SMTP_USERNAME');
    $this->Email->smtpPassword = Configure::read('App.SMTP_PASSWORD');
    $result = $this->Email->send();
	return $result;
   }
   
   /*
    Function Name : _sendModifyPatronMail
    Desc : For sending modified patron email
   */
   
   function _sendModifyPatronMail($id, $password) {
    Configure::write('debug', 0);
    $this->Email->template = 'email/modifyPatronEmail';
    $this->User->recursive = -1;
    $Patron = $this->User->read(null,$id);
    $this->set('Patron', $Patron);
    $this->set('password', $password);
    $this->Email->to = $Patron['User']['email'];
    $this->Email->from = Configure::read('App.adminEmail');
    $this->Email->fromName = Configure::read('App.fromName');
    $this->Email->subject = 'FreegalMusic - Patron account password changed!!';
    $this->Email->smtpHostNames = Configure::read('App.SMTP');
    $this->Email->smtpAuth = Configure::read('App.SMTP_AUTH');
    $this->Email->smtpUserName = Configure::read('App.SMTP_USERNAME');
    $this->Email->smtpPassword = Configure::read('App.SMTP_PASSWORD');
    $result = $this->Email->send(); 
   }
   
   /*
    Function Name : my_account
    Desc : For patron my acount page
   */
   
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
   
   /*
    Function Name : ilogin
    Desc : For patron ilogin(Innovative) login method
   */
   
   function ilogin(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => strtolower($_SERVER['HTTP_REFERER']))));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",strtolower($_SERVER['HTTP_REFERER']));
						$this->Session->write("lId",$url[0]['Url']['library_id']);
					}
				}
			}
		}
      $this->layout = 'login';
      if ($this->Session->read('Auth.User')){
         $userType = $this->Session->read('Auth.User.type_id');
         if($userType == '5'){
            $this->redirect('/homes/index');
            $this->Auth->autoRedirect = false;     
         }
      }
      $this->set('pin',"");
      $this->set('card',"");
      if($this->data){         
         $card = $this->data['User']['card'];
         $pin = $this->data['User']['pin'];
         $patronId = $card;        
         if($card == ''){            
            $this -> Session -> setFlash("Please provide card number.");
            if($pin != ''){
               $this->set('pin',$pin);
            }
            else{
               $this->set('pin',"");
            }            
         }
         elseif($pin == ''){            
            $this -> Session -> setFlash("Please provide pin.");            
            if($card != ''){
               $this->set('card',$card);
            }
            else{
               $this->set('card',"");
            }            
         }
         else{
            $cardNo = substr($card,0,5);
            $this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');
			if($this->Session->read('referral')){
				$library_cond = array('id' => $this->Session->read('lId'));
			} else {
				$library_cond = '';
			}		
            $existingLibraries = $this->Library->find('all',array(
                                                'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'innovative',$library_cond),
												'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content')
                                                )
                                             );
            if(count($existingLibraries) == 0){
                $this -> Session -> setFlash("This is not a valid credential.");
                $this->redirect(array('controller' => 'users', 'action' => 'ilogin'));
            }        
            else{
               $authUrl = $existingLibraries['0']['Library']['library_authentication_url'];               
               $url = $authUrl."/PATRONAPI/".$card."/".$pin."/pintest";               
               $dom= new DOMDocument();
               @$dom->loadHtmlFile($url);
               $xpath = new DOMXPath($dom);
               $body = $xpath->query('/html/body');
               $retStr = $dom->saveXml($body->item(0));               
               $retMsgArr = explode("RETCOD=",$retStr);               
               @$retStatus = $retMsgArr['1'];               
	       if($retStatus == ''){
                  $errMsgArr =  explode("ERRNUM=",$retMsgArr['0']);
                  @$errMsgCount = substr($errMsgArr['1'],0,1);
                  if($errMsgCount == '1'){
                     $this -> Session -> setFlash("Requested record not found.");
                     $this->redirect(array('controller' => 'users', 'action' => 'ilogin'));
                  }
                  else{
                     $this -> Session -> setFlash("Authentication server down.");
                     $this->redirect(array('controller' => 'users', 'action' => 'ilogin'));
                  }                  
               }
               elseif($retStatus == 0){
                  $currentPatron = $this->Currentpatron->find('all', array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'], 'patronid' => $patronId)));
                  if(count($currentPatron) > 0){
                      $modifiedTime = strtotime($currentPatron[0]['Currentpatron']['modified']);                           
                      $date = strtotime(date('Y-m-d H:i:s'));              
                      if(!($this->Session->read('patron'))){               
                          if(($date-$modifiedTime) > 60){
                              $updateArr = array();
                              $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                
                              $updateArr['session_id'] = session_id();
                              $this->Currentpatron->save($updateArr);
                          }
                          else{
                              $this->Session->destroy('user');
                              $this -> Session -> setFlash("This account is already active.");                              
                              $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
                          }
                     }
                      else{
                          $sessionId = session_id();                    
                          if($currentPatron[0]['Currentpatron']['session_id'] != $sessionId){                        
                              if(($date-$modifiedTime) > 60){                            
                                  $updateArr = array();
                                  $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                
                                  $updateArr['session_id'] = session_id();
                                  $this->Currentpatron->save($updateArr);
                              }
                              else{
                                  $this->Session->destroy('user'); 
                                  $this -> Session -> setFlash("This account is already active.");                                  
                                  $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
                              }                  
                           }                    
                     }
                  }
                  else{
                     $insertArr['libid'] = $existingLibraries['0']['Library']['id'];
                     $insertArr['patronid'] = $patronId;
                     $insertArr['session_id'] = session_id();
                     $this->Currentpatron->save($insertArr);
                  }
                  $this->Session->write("library", $existingLibraries['0']['Library']['id']);
                  $this->Session->write("patron", $patronId);
                  $this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
				  $this->Session->write("innovative","innovative");
                  $isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
                  $this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
                  $startDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."1"))." 00:00:00";
                  $endDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."7"))." 23:59:59";
				  $this->Download->recursive = -1;
                  $this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
                  $results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array($startDate, $endDate))));
                  $this ->Session->write("downloadsUsed", $results);
                  if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
                      $this ->Session->write("block", 'yes');
                  }
                  else{
                      $this ->Session->write("block", 'no');
                  }
                  $this->redirect(array('controller' => 'homes', 'action' => 'index'));
               }
               else{
                  $errStrArr = explode('ERRMSG=',$retStr);
                  $errMsg = $errStrArr['1'];
                  $this -> Session -> setFlash($errMsg);
                  $this->redirect(array('controller' => 'users', 'action' => 'ilogin'));
               }
            }
         }         
      }
   }
   
   /*
    Function Name : inlogin
    Desc : For patron inlogin(Innovative w/o PIN) login method
   */
   
	function inlogin(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => strtolower($_SERVER['HTTP_REFERER']))));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",strtolower($_SERVER['HTTP_REFERER']));
						$this->Session->write("lId",$url[0]['Url']['library_id']);
					}
				}
			}
		}
		$this->layout = 'login';     
		if ($this->Session->read('Auth.User')){
			$userType = $this->Session->read('Auth.User.type_id');
			if($userType == '5'){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;     
			}
		}      
		$this->set('card',"");      
		if($this->data){         
			$card = $this->data['User']['card'];         
			$patronId = $card;        
			if($card == ''){            
				$this -> Session -> setFlash("Please provide card number.");            
			}         
			else{
				$cardNo = substr($card,0,5);
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				if($this->Session->read('referral')){
					$library_cond = array('id' => $this->Session->read('lId'));
				} else {
					$library_cond = '';
				}				
				
				$existingLibraries = $this->Library->find('all',array(
												'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'innovative_wo_pin',$library_cond),
												'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content')
												)
											 );            
				if(count($existingLibraries) == 0){
					$this -> Session -> setFlash("This is not a valid credential.");
					$this->redirect(array('controller' => 'users', 'action' => 'inlogin'));
				}        
				else{
					$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];               
					$url = $authUrl."/PATRONAPI/".$card."/dump";               
					$dom= new DOMDocument();
					@$dom->loadHtmlFile($url);
					$xpath = new DOMXPath($dom);
					$body = $xpath->query('/html/body');
					$retStr = $dom->saveXml($body->item(0));
					$retCardArr = explode("P BARCODE[pb]",$retStr);
					$retPos = strpos($retCardArr['1'],"<br/>");
					$retCard = substr($retCardArr['1'],1,$retPos-1);
					$pos = strpos($retStr, "CREATED");
					if ($pos == false) {                 
						$retMsgArr = explode("ERRNUM=",$retStr);               
						if(count($retMsgArr) > 1){                    
							@$retStatus = $retMsgArr['1'];
							$retMsgArr = explode("<BR>",$retStatus);               
							if($retMsgArr[0] == 1){                         
								$this->set('card',$card); 
								$this -> Session -> setFlash("Requested record not found.");
								$this->redirect(array('controller' => 'users', 'action' => 'inlogin'));
							}   
						}
						else{                     
							$this -> Session -> setFlash("Authentication server down.");
							$this->redirect(array('controller' => 'users', 'action' => 'inlogin'));   
						}
					}
					else{                  
						$currentPatron = $this->Currentpatron->find('all', array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'], 'patronid' => $patronId)));
						if(count($currentPatron) > 0){
							$modifiedTime = strtotime($currentPatron[0]['Currentpatron']['modified']);                           
							$date = strtotime(date('Y-m-d H:i:s'));              
							if(!$this->Session->read('patron')){               
							  if(($date-$modifiedTime) > 60){
								  $updateArr = array();
								  $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                
								  $updateArr['session_id'] = session_id();
								  $this->Currentpatron->save($updateArr);
							  }
							  else{
								//  $this->Session->destroy('user');
								  $this -> Session -> setFlash("This account is already active.");                              
								  $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
							  }
							}
							else{
							  $sessionId = session_id();                    
							  if($currentPatron[0]['Currentpatron']['session_id'] != $sessionId){                        
								  if(($date-$modifiedTime) > 60){                            
									  $updateArr = array();
									  $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                
									  $updateArr['session_id'] = session_id();
									  $this->Currentpatron->save($updateArr);
								  }
								  else{
									 // $this->Session->destroy('user');   
									  $this -> Session -> setFlash("This account is already active.");                                  
									  $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
								  }                  
							   }                    
							}
						}
						else{                
						 $insertArr['libid'] = $existingLibraries['0']['Library']['id'];
						 $insertArr['patronid'] = $patronId;
						 $insertArr['session_id'] = session_id();
						 $this->Currentpatron->save($insertArr);
						}
						$this->Session->write("library", $existingLibraries['0']['Library']['id']);
						$this->Session->write("patron", $patronId);
						$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
						$this->Session->write("innovative_wo_pin","innovative_wo_pin");
						$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
						$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
						$startDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."1"))." 00:00:00";
						$endDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."7"))." 23:59:59";           
						$this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
						$this->Download->recursive = -1;
						$results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array($startDate, $endDate))));
						$this ->Session->write("downloadsUsed", $results);
						if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
						  $this ->Session->write("block", 'yes');
						}
						else{
						  $this ->Session->write("block", 'no');
						}
						$this->redirect(array('controller' => 'homes', 'action' => 'index'));
					}
				}
			}         
		}
	}

   /*
    Function Name : indlogin
    Desc : For patron idlogin(Innovative Var w/o Pin) login method
   */
   
   function indlogin(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => strtolower($_SERVER['HTTP_REFERER']))));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",strtolower($_SERVER['HTTP_REFERER']));
						$this->Session->write("lId",$url[0]['Url']['library_id']);
					}
				}
			}
		}
		$this->layout = 'login';
		if ($this->Session->read('Auth.User')){
			$userType = $this->Session->read('Auth.User.type_id');
			if($userType == '5'){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;     
			}
		}
		$this->set('card',"");
		if($this->data){         
			$card = $this->data['User']['card'];
			$patronId = $card;
			if($card == ''){            
				$this -> Session -> setFlash("Please provide card number.");
			}
			else{				
				$cardNo = substr($card,0,5);
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				if($this->Session->read('referral')){
					$library_cond = array('id' => $this->Session->read('lId'));
				} else {
					$library_cond = '';
				}
				$existingLibraries = $this->Library->find('all',array(
													'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'innovative_var_wo_pin',$library_cond),
													'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content')
													)
												 );
				if(count($existingLibraries) == 0){
					$this -> Session -> setFlash("This is not a valid credential.");
					$this->redirect(array('controller' => 'users', 'action' => 'indlogin'));
				}        
				else{
					$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];               
					$url = $authUrl."/PATRONAPI/".$card."/dump";               
					$dom= new DOMDocument();
					@$dom->loadHtmlFile($url);
					$xpath = new DOMXPath($dom);
					$body = $xpath->query('/html/body');
					$retStr = $dom->saveXml($body->item(0));
					$retCardArr = explode("P BARCODE[pb]",$retStr);
					$retPos = strpos($retCardArr['1'],"<br/>");
					$retCard = substr($retCardArr['1'],1,$retPos-1);
					if($card == $retCard){
						$this->Variable->recursive = -1;
						$allVariables = $this->Variable->find('all',array(
															'conditions' => array('library_id' => $existingLibraries['0']['Library']['id']),
															'fields' => array('authentication_variable','authentication_response','comparison_operator','error_msg',)
															)
														 );
						foreach($allVariables as $k=>$v){
							$retStatusArr = explode($v['Variable']['authentication_variable'],$retStr);
							$pos = strpos($retStatusArr['1'],"<br/>");
							$retStatus = substr($retStatusArr['1'],1,$pos-1);
							if($retStatus == ''){
								$status = '';
							}elseif($v['Variable']['comparison_operator'] == '='){
								if($retStatus == $v['Variable']['authentication_response']){
									$status = 1;
								}else{
									$status = 'error';
								}
							}elseif($v['Variable']['comparison_operator'] == '<'){
								$res = explode("$",$retStatus);
								if(isset($res[1])){
									$cmp = $res[1];
								} else {
									$cmp = $res[0];
								}							
								if($cmp < $v['Variable']['authentication_response']){
									$status = 1;
								}else{
									$status = 'error';
								}
							}elseif($v['Variable']['comparison_operator'] == '>'){
								$res = explode("$",$retStatus);
								if(isset($res[1])){
									$cmp = $res[1];
								} else {
									$cmp = $res[0];
								}							
								if($cmp > $v['Variable']['authentication_response']){
									$status = 1;
								}else{
									$status = 'error';
								}
							}elseif($v['Variable']['comparison_operator'] == '<>'){
								$res = explode("$",$retStatus);
								if(isset($res[1])){
									$cmp = $res[1];
								} else {
									$cmp = $res[0];
								}							
								if($cmp != $v['Variable']['authentication_response']){
									$status = 1;
								}else{
									$status = 'error';
								}	
							}else{
								$status = 'error';
							}
							if(!$status || $status == 'error'){
								$msg = $v['Variable']['error_msg'];
								break;
							}
						}						
						if($status == ''){
							$errMsgArr =  explode("ERRNUM=",$retStr);
							@$errMsgCount = substr($errMsgArr['1'],0,1);
							if($errMsgCount == '1'){
								$this -> Session -> setFlash("Requested record not found.");
								$this->redirect(array('controller' => 'users', 'action' => 'indlogin'));
							}
							else{
								$this -> Session -> setFlash("Authentication server down.");
								$this->redirect(array('controller' => 'users', 'action' => 'indlogin'));
							}                  
						}
						elseif($status == 1){
							$currentPatron = $this->Currentpatron->find('all', array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'], 'patronid' => $patronId)));
							if(count($currentPatron) > 0){
								$modifiedTime = strtotime($currentPatron[0]['Currentpatron']['modified']);                           
								$date = strtotime(date('Y-m-d H:i:s'));              
								if(!($this->Session->read('patron'))){               
									if(($date-$modifiedTime) > 60){
									  $updateArr = array();
									  $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                
									  $updateArr['session_id'] = session_id();
									  $this->Currentpatron->save($updateArr);
									}
									else{
									  $this->Session->destroy('user');
									  $this -> Session -> setFlash("This account is already active.");                              
									  $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
									}
								}
								else{
									$sessionId = session_id();                    
									if($currentPatron[0]['Currentpatron']['session_id'] != $sessionId){                        
										if(($date-$modifiedTime) > 60){                            
										  $updateArr = array();
										  $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                
										  $updateArr['session_id'] = session_id();
										  $this->Currentpatron->save($updateArr);
										}
										else{
										  $this->Session->destroy('user'); 
										  $this -> Session -> setFlash("This account is already active.");                                  
										  $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
										}                  
									}                    
								}
							}
							else{
								$insertArr['libid'] = $existingLibraries['0']['Library']['id'];
								$insertArr['patronid'] = $patronId;
								$insertArr['session_id'] = session_id();
								$this->Currentpatron->save($insertArr);
							}
							$this->Session->write("library", $existingLibraries['0']['Library']['id']);
							$this->Session->write("patron", $patronId);
							$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
							$this->Session->write("innovative_var_wo_pin","innovative_var_wo_pin");
							$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
							$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
							$startDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."1"))." 00:00:00";
							$endDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."7"))." 23:59:59";
							$this->Download->recursive = -1;
							$this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
							$results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array($startDate, $endDate))));
							$this ->Session->write("downloadsUsed", $results);
							if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
								$this ->Session->write("block", 'yes');
							}
							else{
								$this ->Session->write("block", 'no');
							}
							$this->redirect(array('controller' => 'homes', 'action' => 'index'));
						}
						else{
							//$errStrArr = explode('ERRMSG=',$retStr);
							//$errMsg = $errStrArr['1'];
							$this->Session->setFlash($msg);
							$this->redirect(array('controller' => 'users', 'action' => 'indlogin'));
						}
					}
					else{
						$this -> Session -> setFlash("Requested record not found.");
						$this->redirect(array('controller' => 'users', 'action' => 'indlogin'));			
					}
				}         
			}
		}
	}	
	
	/*
		Function Name : slogin
		Desc : For patron slogin(SIP2 Authentication) login method
	*/ 
	   
	   
	function slogin(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => strtolower($_SERVER['HTTP_REFERER']))));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",strtolower($_SERVER['HTTP_REFERER']));
						$this->Session->write("lId",$url[0]['Url']['library_id']);
					}
				}
			}
		}
		$this->layout = 'login';     
		if ($this->Session->read('Auth.User')){
			$userType = $this->Session->read('Auth.User.type_id');
			if($userType == '5'){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;     
			}
		}	            
		if($this->data){  
			$card = $this->data['User']['card'];
			$pin = $this->data['User']['pin'];
			$patronId = $card;        
			if($card == ''){            
				$this -> Session -> setFlash("Please provide card number.");            
		
				if($pin != ''){
				   $this->set('pin',$pin);
				}
				else{
				   $this->set('pin',"");
				}            
			}
			elseif($pin == ''){            
				$this -> Session -> setFlash("Please provide pin.");            
				if($card != ''){
				   $this->set('card',$card);
				}
				else{
				   $this->set('card',"");
				}            
			}
			else{
				$cardNo = substr($card,0,5);
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				if($this->Session->read('referral')){
					$library_cond = array('id' => $this->Session->read('lId'));
				} else {
					$library_cond = '';
				}				
				$existingLibraries = $this->Library->find('all',array(
													'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'sip2',$library_cond),
													'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_user_download_limit','Library.library_block_explicit_content')
													)
												 );
				if(count($existingLibraries) == 0){
					$this -> Session -> setFlash("This is not a valid credential.");
					$this->redirect(array('controller' => 'users', 'action' => 'slogin'));
				}        
				else{
						//Start
						$mysip = new $this->sip2;
						$mysip->hostname = $existingLibraries['0']['Library']['library_host_name'];
						$mysip->port = $existingLibraries['0']['Library']['library_port_no'];
						$mysip->sip_login = $existingLibraries['0']['Library']['library_sip_login'];
						$mysip->sip_password = $existingLibraries['0']['Library']['library_sip_password'];
						$mysip->sip_location = $existingLibraries['0']['Library']['library_sip_location'];
						if($mysip->connect()) {
							
							if(!empty($mysip->sip_login)){
								$sc_login=$mysip->msgLogin($mysip->sip_login,$mysip->sip_password,$mysip->sip_location);
								$mysip->parseLoginResponse($mysip->get_message($sc_login));
							}
							
							//send selfcheck status message
							$in = $mysip->msgSCStatus();
							$msg_result = $mysip->get_message($in);
							// Make sure the response is 98 as expected
							if (preg_match("/^98/", $msg_result)) {

									
								  $result = $mysip->parseACSStatusResponse($msg_result);

								  //  Use result to populate SIP2 setings
								  $mysip->AO = $result['variable']['AO'][0]; /* set AO to value returned */
								  $mysip->AN = $result['variable']['AN'][0]; /* set AN to value returned */

								  $mysip->patron = $card;
								  $mysip->patronpwd = $pin;
								  $in = $mysip->msgPatronStatusRequest();
								  $msg_result = $mysip->get_message($in); 
								  // Make sure the response is 24 as expected
								  if (preg_match("/^24/", $msg_result)) {
									  $result = $mysip->parsePatronStatusResponse( $msg_result );

									  if ($result['variable']['BL'][0] == 'Y') {
										  // Successful Card!!!
										
										 if ($result['variable']['CQ'][0] == 'Y') {
											// Successful PIN !!!
										  
												$currentPatron = $this->Currentpatron->find('all', array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'], 'patronid' => $patronId)));
												if(count($currentPatron) > 0){
												  $modifiedTime = strtotime($currentPatron[0]['Currentpatron']['modified']);                           
												  $date = strtotime(date('Y-m-d H:i:s'));              
												  if(!$this->Session->read('patron')){               
													  if(($date-$modifiedTime) > 60){
														  $updateArr = array();
														  $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                
														  $updateArr['session_id'] = session_id();
														  $this->Currentpatron->save($updateArr);
													  }
													  else{
														//  $this->Session->destroy('user');
														  $this -> Session -> setFlash("This account is already active.");                              
														  $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
													  }
												 }
												  else{
													  $sessionId = session_id();                    
													  if($currentPatron[0]['Currentpatron']['session_id'] != $sessionId){                        
														  if(($date-$modifiedTime) > 60){                            
															  $updateArr = array();
															  $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                
															  $updateArr['session_id'] = session_id();
															  $this->Currentpatron->save($updateArr);
														  }
														  else{
															//  $this->Session->destroy('user');   
															  $this -> Session -> setFlash("This account is already active.");                                  
															  $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
														  }                  
													  }                    
												 }
											  }
											  else{                
												 $insertArr['libid'] = $existingLibraries['0']['Library']['id'];
												 $insertArr['patronid'] = $patronId;
												 $insertArr['session_id'] = session_id();
												 $this->Currentpatron->save($insertArr);
											  }
											  $this->Session->write("library", $existingLibraries['0']['Library']['id']);
											  $this->Session->write("patron", $patronId);
											  $this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
											  $this->Session->write("sip2","sip2");
											  $isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
											  $this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
											  $startDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."1"))." 00:00:00";
											  $endDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."7"))." 23:59:59";           
											  $this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
											  $this->Download->recursive = -1;
											  $results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array($startDate, $endDate))));
											  $this ->Session->write("downloadsUsed", $results);
											  if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
												  $this ->Session->write("block", 'yes');
											  }
											  else{
												  $this ->Session->write("block", 'no');
											  }
											  $this->redirect(array('controller' => 'homes', 'action' => 'index'));
										} else {
										//	$this->Session->destroy('user');
											$this->Session->setFlash("The PIN is Invalid.");
											$this->redirect(array('controller' => 'users', 'action' => 'slogin'));
										}
									}else{
									//	  $this->Session->destroy('user');
										  $this -> Session -> setFlash("The Card No is Invalid.");                              
										  $this->redirect(array('controller' => 'users', 'action' => 'slogin'));

									}
									
									
								}else{
								//	  $this->Session->destroy('user');
									  $this -> Session -> setFlash("Authentication server down.");                              
									  $this->redirect(array('controller' => 'users', 'action' => 'slogin'));

							}
						}else{
						//	  $this->Session->destroy('user');
							  $this -> Session -> setFlash("Authentication server down.");                              
							  $this->redirect(array('controller' => 'users', 'action' => 'slogin'));

						}
					}else{
					//	$this->Session->destroy('user');
						$this -> Session -> setFlash("Authentication server down.");                              
						$this->redirect(array('controller' => 'users', 'action' => 'slogin'));

					}
				}
			}
		}
	}	

	/*
		Function Name : snlogin
		Desc : For patron slogin(SIP2 Authentication) login method without the pin no
	*/ 
	   
	   
	function snlogin(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => strtolower($_SERVER['HTTP_REFERER']))));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",strtolower($_SERVER['HTTP_REFERER']));
						$this->Session->write("lId",$url[0]['Url']['library_id']);
					}
				}
			}
		}
		$this->layout = 'login';     
		if ($this->Session->read('Auth.User')){
			$userType = $this->Session->read('Auth.User.type_id');
			if($userType == '5'){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;     
			}
		}	            
		if($this->data){  
			$card = $this->data['User']['card'];
			$patronId = $card;        
			if($card == ''){            
				$this -> Session -> setFlash("Please provide card number.");            
			}
			else{
				$cardNo = substr($card,0,5);
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				if($this->Session->read('referral')){
					$library_cond = array('id' => $this->Session->read('lId'));
				} else {
					$library_cond = '';
				}
				$existingLibraries = $this->Library->find('all',array(
													'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'sip2_wo_pin',$library_cond),
													'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_user_download_limit','Library.library_block_explicit_content')
													)
												 );
				if(count($existingLibraries) == 0){
					$this -> Session -> setFlash("This is not a valid credential.");
					$this->redirect(array('controller' => 'users', 'action' => 'snlogin'));
				}        
				else{
						//Start
						$mysip = new $this->sip2;
						$mysip->hostname = $existingLibraries['0']['Library']['library_host_name'];
						$mysip->port = $existingLibraries['0']['Library']['library_port_no'];
						$mysip->sip_login = $existingLibraries['0']['Library']['library_sip_login'];
						$mysip->sip_password = $existingLibraries['0']['Library']['library_sip_password'];
						$mysip->sip_location = $existingLibraries['0']['Library']['library_sip_location'];
						if($mysip->connect()) {
							
							if(!empty($mysip->sip_login)){
								$sc_login=$mysip->msgLogin($mysip->sip_login,$mysip->sip_password,$mysip->sip_location);
								$mysip->parseLoginResponse($mysip->get_message($sc_login));
							}
							
							//send selfcheck status message
							$in = $mysip->msgSCStatus();
							$msg_result = $mysip->get_message($in);
							// Make sure the response is 98 as expected
							if (preg_match("/^98/", $msg_result)) {
							
									
								  $result = $mysip->parseACSStatusResponse($msg_result);

								  //  Use result to populate SIP2 setings
								  $mysip->AO = $result['variable']['AO'][0]; /* set AO to value returned */
								  $mysip->AN = $result['variable']['AN'][0]; /* set AN to value returned */

								  $mysip->patron = $card;
								  $mysip->patronpwd = '';

								  $in = $mysip->msgPatronStatusRequest();

								  $msg_result = $mysip->get_message($in);
								  // Make sure the response is 24 as expected
								  if (preg_match("/^24/", $msg_result)) {
									  $result = $mysip->parsePatronStatusResponse( $msg_result );
									  if (($result['variable']['BL'][0] == 'Y')) {
										  // Success!!!
										  
										  
											$currentPatron = $this->Currentpatron->find('all', array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'], 'patronid' => $patronId)));
											if(count($currentPatron) > 0){
											  $modifiedTime = strtotime($currentPatron[0]['Currentpatron']['modified']);                           
											  $date = strtotime(date('Y-m-d H:i:s'));              
											  if($this->Session->read('patron')){               
												  if(($date-$modifiedTime) > 60){
													  $updateArr = array();
													  $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                
													  $updateArr['session_id'] = session_id();
													  $this->Currentpatron->save($updateArr);
												  }
												  else{
													//  $this->Session->destroy('user');
													  $this -> Session -> setFlash("This account is already active.");                              
													  $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
												  }
											 }
											  else{
												  $sessionId = session_id();                    
												  if($currentPatron[0]['Currentpatron']['session_id'] != $sessionId){                        
													  if(($date-$modifiedTime) > 60){                            
														  $updateArr = array();
														  $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                
														  $updateArr['session_id'] = session_id();
														  $this->Currentpatron->save($updateArr);
													  }
													  else{
														//  $this->Session->destroy('user');   
														  $this -> Session -> setFlash("This account is already active.");                                  
														  $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
													  }                  
												  }                    
											 }
										  }
										  else{                
											 $insertArr['libid'] = $existingLibraries['0']['Library']['id'];
											 $insertArr['patronid'] = $patronId;
											 $insertArr['session_id'] = session_id();
											 $this->Currentpatron->save($insertArr);
										  }
										  $this->Session->write("library", $existingLibraries['0']['Library']['id']);
										  $this->Session->write("patron", $patronId);
										  $this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
										  $this->Session->write("sip","sip");
										  $isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
										  $this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
										  $startDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."1"))." 00:00:00";
										  $endDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."7"))." 23:59:59";           
										  $this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
										  $this->Download->recursive = -1;
										  $results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array($startDate, $endDate))));
										  $this ->Session->write("downloadsUsed", $results);
										  if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
											  $this ->Session->write("block", 'yes');
										  }
										  else{
											  $this ->Session->write("block", 'no');
										  }
										  $this->redirect(array('controller' => 'homes', 'action' => 'index'));
									}else{
									//	  $this->Session->destroy('user');
										  $this -> Session -> setFlash("The Card No is Invalid.");                              
										  $this->redirect(array('controller' => 'users', 'action' => 'snlogin'));

									}
									
									
								}else{
								//	  $this->Session->destroy('user');
									  $this -> Session -> setFlash("Authentication server down.");                              
									  $this->redirect(array('controller' => 'users', 'action' => 'snlogin'));

							}
						}else{
						//	  $this->Session->destroy('user');
							  $this -> Session -> setFlash("Authentication server down.");                              
							  $this->redirect(array('controller' => 'users', 'action' => 'snlogin'));

						}
					}else{
					//	$this->Session->destroy('user');
						$this -> Session -> setFlash("Authentication server down.");                              
						$this->redirect(array('controller' => 'users', 'action' => 'snlogin'));

					}
				}
			}
		}
	}	

	/*
		Function Name : sdlogin
		Desc : For patron sdlogin(SIP2 Var) login method
	*/ 	   
	   
	function sdlogin(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => strtolower($_SERVER['HTTP_REFERER']))));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",strtolower($_SERVER['HTTP_REFERER']));
						$this->Session->write("lId",$url[0]['Url']['library_id']);
					}
				}
			}
		}
		$this->layout = 'login';     
		if ($this->Session->read('Auth.User')){
			$userType = $this->Session->read('Auth.User.type_id');
			if($userType == '5'){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;     
			}
		}	            
		if($this->data){  
			$card = $this->data['User']['card'];
			$pin = $this->data['User']['pin'];
			$patronId = $card;        
			if($card == ''){            
				$this -> Session -> setFlash("Please provide card number.");            
		
				if($pin != ''){
				   $this->set('pin',$pin);
				}
				else{
				   $this->set('pin',"");
				}            
			}
			elseif($pin == ''){            
				$this -> Session -> setFlash("Please provide pin.");            
				if($card != ''){
				   $this->set('card',$card);
				}
				else{
				   $this->set('card',"");
				}            
			}
			else{
				$cardNo = substr($card,0,5);
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				if($this->Session->read('referral')){
					$library_cond = array('id' => $this->Session->read('lId'));
				} else {
					$library_cond = '';
				}				
				$existingLibraries = $this->Library->find('all',array(
													'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'sip2_var',$library_cond),
													'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_user_download_limit','Library.library_block_explicit_content')
													)
												 );
				if(count($existingLibraries) == 0){
					$this -> Session -> setFlash("This is not a valid credential.");
					$this->redirect(array('controller' => 'users', 'action' => 'sdlogin'));
				}        
				else{
						//Start
						$mysip = new $this->sip2;
						$mysip->hostname = $existingLibraries['0']['Library']['library_host_name'];
						$mysip->port = $existingLibraries['0']['Library']['library_port_no'];
						$mysip->sip_login = $existingLibraries['0']['Library']['library_sip_login'];
						$mysip->sip_password = $existingLibraries['0']['Library']['library_sip_password'];
						$mysip->sip_location = $existingLibraries['0']['Library']['library_sip_location'];
						if($mysip->connect()) {
						
							if(!empty($mysip->sip_login)){
								$sc_login=$mysip->msgLogin($mysip->sip_login,$mysip->sip_password,$mysip->sip_location);
								$mysip->parseLoginResponse($mysip->get_message($sc_login));
							}
							
							//send selfcheck status message
							$in = $mysip->msgSCStatus();
							$msg_result = $mysip->get_message($in);

							// Make sure the response is 98 as expected
							if (preg_match("/98/", $msg_result)) {

									
								  $result = $mysip->parseACSStatusResponse($msg_result);

								  //  Use result to populate SIP2 setings
								  $mysip->AO = $result['variable']['AO'][0]; /* set AO to value returned */
								  $mysip->AN = $result['variable']['AN'][0]; /* set AN to value returned */

								  $mysip->patron = $card;
								  $mysip->patronpwd = $pin;
								  $in = $mysip->msgPatronStatusRequest();
								  $msg_result = $mysip->get_message($in);
								  // Make sure the response is 24 as expected
								  if (preg_match("/24/", $msg_result)) {
									  $result = $mysip->parsePatronStatusResponse( $msg_result );
									  
									  if ($result['variable']['BL'][0] == 'Y') {
										  // Successful Card!!!
										
										 if ($result['variable']['CQ'][0] == 'Y') {
											// Successful PIN !!!
										  
											$in = $mysip->msgPatronInformation('none');
											$info_status = $mysip->parsePatronInfoResponse( $mysip->get_message($in) );
											$this->Variable->recursive = -1;										
											$allVariables = $this->Variable->find('all',array(
																				'conditions' => array('library_id' => $existingLibraries['0']['Library']['id']),
																				'fields' => array('authentication_variable','authentication_response','comparison_operator','error_msg',)
																				)
																			 );
											foreach($allVariables as $k=>$v){
												$response = explode(",",$v['Variable']['authentication_response']);
												if($v['Variable']['comparison_operator'] == '='){
													$status = strpos($v['Variable']['authentication_response'],$info_status['variable'][$v['Variable']['authentication_variable']][0]);
												}elseif($v['Variable']['comparison_operator'] == '<'){
													foreach($response as $key => $val){
														$res = explode("$",$info_status['variable'][$v['Variable']['authentication_variable']][0]);
														if(isset($res[1])){
															$cmp = $res[1];
														} else {
															$cmp = $res[0];
														}
														if($cmp < $val){
															$status = 1;
															break;
														}else{
															$status = false;
														}
													}
												}elseif($v['Variable']['comparison_operator'] == '>'){
														foreach($response as $key => $val){
														$res = explode("$",$info_status['variable'][$v['Variable']['authentication_variable']][0]);
														if(isset($res[1])){
															$cmp = $res[1];
														} else {
															$cmp = $res[0];
														}
														if($cmp > $val){
															$status = 1;
															break;
														}else{
															$status = false;
														}
													}
												}elseif($v['Variable']['comparison_operator'] == '<>'){
														foreach($response as $key => $val){
														$res = explode("$",$info_status['variable'][$v['Variable']['authentication_variable']][0]);
														if(isset($res[1])){
															$cmp = $res[1];
														} else {
															$cmp = $res[0];
														}
														if($cmp != $val){
															$status = 1;
															break;
														}else{
															$status = false;
														}
													}
												}
												if($status === false){
													$msg = $v['Variable']['error_msg'];											
												}
												if(isset($msg)){
													break;
												}
											}
											if(!($status === false)){
												$currentPatron = $this->Currentpatron->find('all', array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'], 'patronid' => $patronId)));
												if(count($currentPatron) > 0){
													$modifiedTime = strtotime($currentPatron[0]['Currentpatron']['modified']);                           
													$date = strtotime(date('Y-m-d H:i:s'));              
													if(!$this->Session->read('patron')){               
														if(($date-$modifiedTime) > 60){
														  $updateArr = array();
														  $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                
														  $updateArr['session_id'] = session_id();
														  $this->Currentpatron->save($updateArr);
														}
														else{
														  $this->Session->destroy('user');
														  $this -> Session -> setFlash("This account is already active.");                              
														  $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
														}
													}
													else{
														$sessionId = session_id();                    
														if($currentPatron[0]['Currentpatron']['session_id'] != $sessionId){                        
															if(($date-$modifiedTime) > 60){                            
															  $updateArr = array();
															  $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                
															  $updateArr['session_id'] = session_id();
															  $this->Currentpatron->save($updateArr);
															}
															else{
														//	  $this->Session->destroy('user');   
															  $this -> Session -> setFlash("This account is already active.");                                  
															  $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
															}                  
														}                    
													}
												}
												else{                
													$insertArr['libid'] = $existingLibraries['0']['Library']['id'];
													$insertArr['patronid'] = $patronId;
													$insertArr['session_id'] = session_id();
													$this->Currentpatron->save($insertArr);
												}
												$this->Session->write("library", $existingLibraries['0']['Library']['id']);
												$this->Session->write("patron", $patronId);
												$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
												$this->Session->write("sip2_var","sip2_var");
												$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
												$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
												$startDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."1"))." 00:00:00";
												$endDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."7"))." 23:59:59";           
												$this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
												$this->Download->recursive = -1;
												$results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array($startDate, $endDate))));
												$this ->Session->write("downloadsUsed", $results);
												if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
													$this ->Session->write("block", 'yes');
												}
												else{
													$this ->Session->write("block", 'no');
												}
												$this->redirect(array('controller' => 'homes', 'action' => 'index'));
											} 
											else {
											//	$this->Session->destroy('user');
												$this->Session->setFlash($msg);
												$this->redirect(array('controller' => 'users', 'action' => 'sdlogin'));
											}											  
										}
										else{
										//	  $this->Session->destroy('user');
											  $this -> Session -> setFlash("The PIN is Invalid.");
											  $this->redirect(array('controller' => 'users', 'action' => 'sdlogin'));

										}
									}
									else{
										//  $this->Session->destroy('user');
										  $this -> Session -> setFlash("The Card No is Invalid.");                              
										  $this->redirect(array('controller' => 'users', 'action' => 'sdlogin'));

									}										
								}
								else{
									//  $this->Session->destroy('user');
									  $this -> Session -> setFlash("Authentication server down.");                              
									  $this->redirect(array('controller' => 'users', 'action' => 'sdlogin'));

								}
						}
						else{
							//  $this->Session->destroy('user');
							  $this -> Session -> setFlash("Authentication server down.");                              
							  $this->redirect(array('controller' => 'users', 'action' => 'sdlogin'));

						}
					}
					else{
					//	$this->Session->destroy('user');
						$this -> Session -> setFlash("Authentication server down.");                              
						$this->redirect(array('controller' => 'users', 'action' => 'sdlogin'));

					}
				}
			}
		}
	}

	/*
		Function Name : sndlogin
		Desc : For patron sndlogin(SIP2 Var w/o Pin) login method
	*/ 	   
	   
	function sndlogin(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => strtolower($_SERVER['HTTP_REFERER']))));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",strtolower($_SERVER['HTTP_REFERER']));
						$this->Session->write("lId",$url[0]['Url']['library_id']);
					}
				}
			}
		}
		$this->layout = 'login';     
		if ($this->Session->read('Auth.User')){
			$userType = $this->Session->read('Auth.User.type_id');
			if($userType == '5'){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;     
			}
		}	            
		if($this->data){  
			$card = $this->data['User']['card'];
			$patronId = $card;        
			if($card == ''){            
				$this -> Session -> setFlash("Please provide card number.");            
			}
			else{
				$cardNo = substr($card,0,5);
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				if($this->Session->read('referral')){
					$library_cond = array('id' => $this->Session->read('lId'));
				} else {
					$library_cond = '';
				}				
				$existingLibraries = $this->Library->find('all',array(
													'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'sip2_var_wo_pin',$library_cond),
													'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_user_download_limit','Library.library_block_explicit_content')
													)
												 );
				if(count($existingLibraries) == 0){
					$this -> Session -> setFlash("This is not a valid credential.");
					$this->redirect(array('controller' => 'users', 'action' => 'sndlogin'));
				}        
				else{
					//Start
					$mysip = new $this->sip2;
					$mysip->hostname = $existingLibraries['0']['Library']['library_host_name'];
					$mysip->port = $existingLibraries['0']['Library']['library_port_no'];
					$mysip->sip_login = $existingLibraries['0']['Library']['library_sip_login'];
					$mysip->sip_password = $existingLibraries['0']['Library']['library_sip_password'];
					$mysip->sip_location = $existingLibraries['0']['Library']['library_sip_location'];
					if($mysip->connect()) {
					
						if(!empty($mysip->sip_login)){
							$sc_login=$mysip->msgLogin($mysip->sip_login,$mysip->sip_password,$mysip->sip_location);
							$mysip->parseLoginResponse($mysip->get_message($sc_login));
						}
						
						//send selfcheck status message
						$in = $mysip->msgSCStatus();
						$msg_result = $mysip->get_message($in);

						// Make sure the response is 98 as expected
						if (preg_match("/^98/", $msg_result)) {
							$result = $mysip->parseACSStatusResponse($msg_result);

							//  Use result to populate SIP2 setings
							$mysip->AO = $result['variable']['AO'][0]; /* set AO to value returned */
							$mysip->AN = $result['variable']['AN'][0]; /* set AN to value returned */

							$mysip->patron = $card;
							//$mysip->patronpwd = $pin;
							$in = $mysip->msgPatronStatusRequest();
							$msg_result = $mysip->get_message($in);
							// Make sure the response is 24 as expected
							if (preg_match("/^24/", $msg_result)) {
								$result = $mysip->parsePatronStatusResponse( $msg_result );
								if ($result['variable']['BL'][0] == 'Y') {
									  // Successful Card!!!
									$in = $mysip->msgPatronInformation('none');
									$info_status = $mysip->parsePatronInfoResponse( $mysip->get_message($in) );
									$this->Variable->recursive = -1;										
									$allVariables = $this->Variable->find('all',array(
																		'conditions' => array('library_id' => $existingLibraries['0']['Library']['id']),
																		'fields' => array('authentication_variable','authentication_response','comparison_operator','error_msg',)
																		)
																	 );
									foreach($allVariables as $k=>$v){
										$response = explode(",",$v['Variable']['authentication_response']);
										if($v['Variable']['comparison_operator'] == '='){
											$status = strpos($v['Variable']['authentication_response'],$info_status['variable'][$v['Variable']['authentication_variable']][0]);
										}elseif($v['Variable']['comparison_operator'] == '<'){
											foreach($response as $key => $val){
												$res = explode("$",$info_status['variable'][$v['Variable']['authentication_variable']][0]);
												if(isset($res[1])){
													$cmp = $res[1];
												} else {
													$cmp = $res[0];
												}
												if($cmp < $val){
													$status = 1;
													break;
												}else{
													$status = false;
												}
											}
										}elseif($v['Variable']['comparison_operator'] == '>'){
												foreach($response as $key => $val){
												$res = explode("$",$info_status['variable'][$v['Variable']['authentication_variable']][0]);
												if(isset($res[1])){
													$cmp = $res[1];
												} else {
													$cmp = $res[0];
												}
												if($cmp > $val){
													$status = 1;
													break;
												}else{
													$status = false;
												}
											}
										}elseif($v['Variable']['comparison_operator'] == '<>'){
												foreach($response as $key => $val){
												$res = explode("$",$info_status['variable'][$v['Variable']['authentication_variable']][0]);
												if(isset($res[1])){
													$cmp = $res[1];
												} else {
													$cmp = $res[0];
												}
												if($cmp != $val){
													$status = 1;
													break;
												}else{
													$status = false;
												}
											}
										}
										if($status === false){
											$msg = $v['Variable']['error_msg'];											
										}
										if(isset($msg)){
											break;
										}
									}
									if(!($status === false)){
										$currentPatron = $this->Currentpatron->find('all', array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'], 'patronid' => $patronId)));
										if(count($currentPatron) > 0){
											$modifiedTime = strtotime($currentPatron[0]['Currentpatron']['modified']);                           
											$date = strtotime(date('Y-m-d H:i:s'));              
											if(!$this->Session->read('patron')){               
												if(($date-$modifiedTime) > 60){
												  $updateArr = array();
												  $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                
												  $updateArr['session_id'] = session_id();
												  $this->Currentpatron->save($updateArr);
												}
												else{
												  $this->Session->destroy('user');
												  $this -> Session -> setFlash("This account is already active.");                              
												  $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
												}
											}
											else{
												$sessionId = session_id();                    
												if($currentPatron[0]['Currentpatron']['session_id'] != $sessionId){                        
													if(($date-$modifiedTime) > 60){                            
													  $updateArr = array();
													  $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                
													  $updateArr['session_id'] = session_id();
													  $this->Currentpatron->save($updateArr);
													}
													else{
												//	  $this->Session->destroy('user');   
													  $this -> Session -> setFlash("This account is already active.");                                  
													  $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
													}                  
												}                    
											}
										}
										else{                
											$insertArr['libid'] = $existingLibraries['0']['Library']['id'];
											$insertArr['patronid'] = $patronId;
											$insertArr['session_id'] = session_id();
											$this->Currentpatron->save($insertArr);
										}
										$this->Session->write("library", $existingLibraries['0']['Library']['id']);
										$this->Session->write("patron", $patronId);
										$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
										$this->Session->write("sip2_var_wo_pin","sip2_var_wo_pin");
										$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
										$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
										$startDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."1"))." 00:00:00";
										$endDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."7"))." 23:59:59";           
										$this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
										$this->Download->recursive = -1;
										$results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array($startDate, $endDate))));
										$this ->Session->write("downloadsUsed", $results);
										if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
											$this ->Session->write("block", 'yes');
										}
										else{
											$this ->Session->write("block", 'no');
										}
										$this->redirect(array('controller' => 'homes', 'action' => 'index'));
									} 
									else {
									//	$this->Session->destroy('user');
										$this->Session->setFlash($msg);
										$this->redirect(array('controller' => 'users', 'action' => 'sndlogin'));
									}
								}
								else{
									//  $this->Session->destroy('user');
									  $this -> Session -> setFlash("The Card No is Invalid.");                              
									  $this->redirect(array('controller' => 'users', 'action' => 'sndlogin'));

								}										
							}
							else{
							//  $this->Session->destroy('user');
							  $this -> Session -> setFlash("Authentication server down.");                              
							  $this->redirect(array('controller' => 'users', 'action' => 'sndlogin'));

							}
						}
						else{
							//  $this->Session->destroy('user');
							  $this -> Session -> setFlash("Authentication server down.");                              
							  $this->redirect(array('controller' => 'users', 'action' => 'sndlogin'));

						}
					}
					else{
					//	$this->Session->destroy('user');
						$this -> Session -> setFlash("Authentication server down.");                              
						$this->redirect(array('controller' => 'users', 'action' => 'sndlogin'));

					}
				}
			}
		}
	}
	
	/*
		Function Name : sso
		Desc : For patron slogin(EZProxy) login method
	*/ 
	   
	   
	function sso(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				if($this->Session->read('referral') == ''){
					$this->Session->write("referral",strtolower($_SERVER['HTTP_REFERER']));
				}
			}
		}
		$this->layout = 'login';
		$referral = $this->Session->read('referral');
		$ref = explode("url=",$referral);
		$this->Library->recursive = -1;
		$this->Library->Behaviors->attach('Containable');	
		$existingLibraries = $this->Library->find('all',array(
											'conditions' => array('library_ezproxy_referral' => $referral,'library_status' => 'active','library_authentication_method' => 'ezproxy'),
											'fields' => array('Library.id','Library.library_territory','Library.library_ezproxy_secret','Library.library_ezproxy_referral','Library.library_user_download_limit','Library.library_block_explicit_content')
											)
										 );
		if(count($existingLibraries) == 0){
			$this -> Session -> setFlash("This is not a valid credential.");
			$this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
		}        
		else{	
			$EZproxySSO = new EZproxySSOComponent($existingLibraries['0']['Library']['library_ezproxy_secret'],$existingLibraries['0']['Library']['library_ezproxy_referral']);
			if (! $EZproxySSO->valid()) {
				if ($EZproxySSO->expired()) {
					echo("This URL has expired\n");
				} else {
					echo("Invalid access attempt\n");
				}
				exit();
			}
			$user = $EZproxySSO->user();
			$card = $user;	
			$currentPatron = $this->Currentpatron->find('all', array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'], 'patronid' => $user)));
			if(count($currentPatron) > 0){
				$modifiedTime = strtotime($currentPatron[0]['Currentpatron']['modified']);                           
				$date = strtotime(date('Y-m-d H:i:s'));              
				if(!$this->Session->read('patron')){               
					if(($date-$modifiedTime) > 60){
					  $updateArr = array();
					  $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                
					  $updateArr['session_id'] = session_id();
					  $this->Currentpatron->save($updateArr);
					}
					else{
					  $this->Session->destroy('user');
					  $this -> Session -> setFlash("This account is already active.");                              
					  $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
					}
				}
				else{
					$sessionId = session_id();                    
					if($currentPatron[0]['Currentpatron']['session_id'] != $sessionId){                        
						if(($date-$modifiedTime) > 60){                            
						  $updateArr = array();
						  $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                
						  $updateArr['session_id'] = session_id();
						  $this->Currentpatron->save($updateArr);
						}
						else{
						  $this->Session->destroy('user');   
						  $this -> Session -> setFlash("This account is already active.");                                  
						  $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
						}                  
					}                    
				}
			}
			else{                
				$insertArr['libid'] = $existingLibraries['0']['Library']['id'];
				$insertArr['patronid'] = $user;
				$insertArr['session_id'] = session_id();
				$this->Currentpatron->save($insertArr);
			}
			$this->Session->write("library", $existingLibraries['0']['Library']['id']);
			$this->Session->write("patron", $user);
			$this->Session->write("ezproxy","ezproxy");
			$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
			$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $user)));            
			$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
			$startDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."1"))." 00:00:00";
			$endDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."7"))." 23:59:59";           
			$this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
			$this->Download->recursive = -1;
			$results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $user,'created BETWEEN ? AND ?' => array($startDate, $endDate))));
			$this ->Session->write("downloadsUsed", $results);
			if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
				$this ->Session->write("block", 'yes');
			}
			else{
				$this ->Session->write("block", 'no');
			}
			$this->redirect(array('controller' => 'homes', 'action' => 'index'));
		}
	}
   /*
    Function Name : ilogin
    Desc : For patron ilogin(Innovative) login method
   */
   
   function inhlogin(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => strtolower($_SERVER['HTTP_REFERER']))));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",strtolower($_SERVER['HTTP_REFERER']));
						$this->Session->write("lId",$url[0]['Url']['library_id']);
					}
				}
			}
		}
      $this->layout = 'login';
      if ($this->Session->read('Auth.User')){
         $userType = $this->Session->read('Auth.User.type_id');
         if($userType == '5'){
            $this->redirect('/homes/index');
            $this->Auth->autoRedirect = false;     
         }
      }
      $this->set('pin',"");
      $this->set('card',"");
      if($this->data){         
         $card = $this->data['User']['card'];
         $pin = $this->data['User']['pin'];
         $patronId = $card;        
         if($card == ''){            
            $this -> Session -> setFlash("Please provide card number.");
            if($pin != ''){
               $this->set('pin',$pin);
            }
            else{
               $this->set('pin',"");
            }            
         }
         elseif($pin == ''){            
            $this -> Session -> setFlash("Please provide pin.");            
            if($card != ''){
               $this->set('card',$card);
            }
            else{
               $this->set('card',"");
            }            
         }
         else{
            $cardNo = substr($card,0,5);
            $this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');
			if($this->Session->read('referral')){
				$library_cond = array('id' => $this->Session->read('lId'));
			} else {
				$library_cond = '';
			}		
            $existingLibraries = $this->Library->find('all',array(
                                                'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'innovative_https',$library_cond),
												'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content')
                                                )
                                             );
            if(count($existingLibraries) == 0){
                $this -> Session -> setFlash("This is not a valid credential.");
                $this->redirect(array('controller' => 'users', 'action' => 'ilogin'));
            }        
            else{
				$matches = array();
				$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];               
				$url = $authUrl."/PATRONAPI/".$card."/".$pin."/pintest";
				$session = curl_init($url);
				curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
				curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt ($session, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($session, CURLOPT_HEADER, true);
				if(!$response = curl_exec ($session))  {
					throw new Exception(curl_error($session));
				}
				curl_close($session);
                $retMsgArr = explode("RETCOD=",$response);               
                @$retStatus = $retMsgArr['1']; 
				if($retStatus == ''){
					$errMsgArr =  explode("ERRNUM=",$response);
					@$errMsgCount = substr($errMsgArr['1'],0,1);
					if($errMsgCount == '1'){
					 $this -> Session -> setFlash("Requested record not found.");
					 $this->redirect(array('controller' => 'users', 'action' => 'inhlogin'));
					}
					else{
					 $this -> Session -> setFlash("Authentication server down.");
					 $this->redirect(array('controller' => 'users', 'action' => 'inhlogin'));
					}                  
               }
               elseif($retStatus == 0){
					$status =1;
					$this->Variable->recursive = -1;
					$allVariables = $this->Variable->find('all',array(
														'conditions' => array('library_id' => $existingLibraries['0']['Library']['id']),
														'fields' => array('authentication_variable','authentication_response','comparison_operator','error_msg',)
														)
													 );
					foreach($allVariables as $k=>$v){
						$retStatusArr = explode($v['Variable']['authentication_variable'],$response);
						$pos = strpos($retStatusArr['1'],"<br/>");
						$retStatus = substr($retStatusArr['1'],1,$pos-1);
						if($retStatus == ''){
							$status = '';
						}elseif($v['Variable']['comparison_operator'] == '='){
							if($retStatus == $v['Variable']['authentication_response']){
								$status = 1;
							}else{
								$status = 'error';
							}
						}elseif($v['Variable']['comparison_operator'] == '<'){
							$res = explode("$",$retStatus);
							if(isset($res[1])){
								$cmp = $res[1];
							} else {
								$cmp = $res[0];
							}							
							if($cmp < $v['Variable']['authentication_response']){
								$status = 1;
							}else{
								$status = 'error';
							}
						}elseif($v['Variable']['comparison_operator'] == '>'){
							$res = explode("$",$retStatus);
							if(isset($res[1])){
								$cmp = $res[1];
							} else {
								$cmp = $res[0];
							}							
							if($cmp > $v['Variable']['authentication_response']){
								$status = 1;
							}else{
								$status = 'error';
							}
						}elseif($v['Variable']['comparison_operator'] == '<>'){
							$res = explode("$",$retStatus);
							if(isset($res[1])){
								$cmp = $res[1];
							} else {
								$cmp = $res[0];
							}							
							if($cmp != $v['Variable']['authentication_response']){
								$status = 1;
							}else{
								$status = 'error';
							}	
						}else{
							$status = 'error';
						}
						if(!$status || $status == 'error'){
							$msg = $v['Variable']['error_msg'];
							break;
						}
					}						
					if($status == ''){
						$errMsgArr =  explode("ERRNUM=",$response);
						@$errMsgCount = substr($errMsgArr['1'],0,1);
						if($errMsgCount == '1'){
							$this -> Session -> setFlash("Requested record not found.");
							$this->redirect(array('controller' => 'users', 'action' => 'indlogin'));
						}
						else{
							$this -> Session -> setFlash("Authentication server down.");
							$this->redirect(array('controller' => 'users', 'action' => 'indlogin'));
						}                  
					}
					elseif($status == 1){
						$currentPatron = $this->Currentpatron->find('all', array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'], 'patronid' => $patronId)));
						if(count($currentPatron) > 0){
						  $modifiedTime = strtotime($currentPatron[0]['Currentpatron']['modified']);                           
						  $date = strtotime(date('Y-m-d H:i:s'));              
						  if(!($this->Session->read('patron'))){               
							  if(($date-$modifiedTime) > 60){
								  $updateArr = array();
								  $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                
								  $updateArr['session_id'] = session_id();
								  $this->Currentpatron->save($updateArr);
							  }
							  else{
								  $this->Session->destroy('user');
								  $this -> Session -> setFlash("This account is already active.");                              
								  $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
							  }
						 }
						  else{
							  $sessionId = session_id();                    
							  if($currentPatron[0]['Currentpatron']['session_id'] != $sessionId){                        
								  if(($date-$modifiedTime) > 60){                            
									  $updateArr = array();
									  $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                
									  $updateArr['session_id'] = session_id();
									  $this->Currentpatron->save($updateArr);
								  }
								  else{
									  $this->Session->destroy('user'); 
									  $this -> Session -> setFlash("This account is already active.");                                  
									  $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
								  }                  
							   }                    
						    }
						}
						else{
							$insertArr['libid'] = $existingLibraries['0']['Library']['id'];
							$insertArr['patronid'] = $patronId;
							$insertArr['session_id'] = session_id();
							$this->Currentpatron->save($insertArr);
						}
						$this->Session->write("library", $existingLibraries['0']['Library']['id']);
						$this->Session->write("patron", $patronId);
						$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
						$this->Session->write("innovative_https","innovative_https");
						$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
						$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
						$startDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."1"))." 00:00:00";
						$endDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."7"))." 23:59:59";
						$this->Download->recursive = -1;
						$this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
						$results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array($startDate, $endDate))));
						$this ->Session->write("downloadsUsed", $results);
						if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
						  $this ->Session->write("block", 'yes');
						}
						else{
						  $this ->Session->write("block", 'no');
						}
						$this->redirect(array('controller' => 'homes', 'action' => 'index'));
					   }
					   else{
						  $errStrArr = explode('ERRMSG=',$response);
						  $errMsg = $errStrArr['1'];
						  $this -> Session -> setFlash($errMsg);
						  $this->redirect(array('controller' => 'users', 'action' => 'inhlogin'));
						}
					}	
					else{
						$this -> Session -> setFlash("Requested record not found.");
						$this->redirect(array('controller' => 'users', 'action' => 'indlogin'));			
					}				
				}
			}         
		}
   }
	
}
?>
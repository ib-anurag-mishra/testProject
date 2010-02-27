<?php
 /*
 File Name : admins_controller.php
 File Description : Controller page for the  login functionality.
 Author : maycreate
 */
Class UsersController extends AppController
{
   var $name = 'Users';
   var $helpers = array('Html','Ajax','Javascript','Form');
   var $layout = 'admin';
   var $components = array('Session','Auth','Acl');
   var $uses = array('User','Group');
   
   function before_filter()
   {
     $this->Auth->userModel = 'User';
     $this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
     $this->Auth->loginRedirect = array('controller' => 'users', 'action' => 'index');
     $this->Auth->logoutRedirect = '/users/login';
     $this->Auth->allow('login','logout');
     $this->set('username', $this->Session->read('Auth.User.username'));
   }
 
 
   public function index()
    {
         //$this->layout = 'login';
        //takes to the default admin home page
         $this->set('username', $this->Session->read('Auth.User.username'));  //setting the username to display on the header 
    }
    
    /*
    Function Name : login
    Desc : Validates admin login credentials
   */
    
    public function login()
    {
       $this->layout = 'admin';
       if ($this->Session->read('Auth.User'))
       {
        $this->redirect('/users/index');
        $this->Auth->autoRedirect = false;
       }
    }
    
    /*
    Function Name : logout
    Desc : Logs admin out of the system
   */
    
    public function logout()
    {
     $this->redirect($this->Auth->logout()); 
    }
    
    /*
    Function Name : listuser
    Desc : action for listing all the admin users
   */
    
   public function manageuser()
    {  
        $this->set('admins', $this->User->getallusers());
    }
    
    /*
    Function Name : userform
    Desc : action for displaying the add/edit user form
   */
    
    public function userform()
    {
        if(!empty($this->params['named']['id']))//gets the values from the url in form  of array
        {
            $adminUserId = $this->params['named']['id'];
            if(trim($adminUserId) != "" && is_numeric($adminUserId))
            {
                $this->set('formAction','userform/id:'.$adminUserId);
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
                    if(trim($this->data['User']['password']) == "7f86df28b26af363bb0d519f137a4e22ec6e64a6")
                    {
                       // do not update the password
                       $this->data['User']= $updateObj->arrayremovekey($this->data['User'],'password');
                    }
                    $this->User->set($this->data['User']);  
                    if($this->User->save())
                    {
                      $this->Session->setFlash('Data has been saved Sucessfully!', 'modal', array('class' => 'modal success'));
                      $this->redirect('/users/manageuser');
                    }                
                }
                 //editting a value
            }
        }else{
                $arr = array();
                $this->set('getData',$arr);
                $this->set('formAction','userform');
                $this->set('formHeader','Create User');
                
                //insertion Operation
                if(isset($this->data))
                {
                    $insertObj = new User();
                    $getData['User'] = $this->data['User'];
                    $getData['Group']['id'] = $this->data['User']['type_id'];
                    $this->set('getData', $getData);
                  
                    if($this->data['User']['password'] == "7f86df28b26af363bb0d519f137a4e22ec6e64a6")
                    {
                     $this->data['User']['password'] = "";
                      
                    }              
                    $this->User->set($this->data['User']);                  
                    if($this->User->save())
                    {                    
                      $this->Session->setFlash('Data has been saved Sucessfully!', 'modal', array('class' => 'modal success'));
                      $this->redirect('/users/manageuser');
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
    Function Name : delete
    Desc : For deleting a user
   */
    public function delete()
    {
      $deleteAdminUserId = $this->params['named']['id'];
      $deleteObj  = new User();
      if($deleteObj->del($deleteAdminUserId))
      {
        $this->Session->setFlash('Data deleted Sucessfully!', 'modal', array('class' => 'modal success'));
        $this->redirect('/users/manageuser');
      }else{
        $this->Session->setFlash('Error occured while deleteting the record', 'modal', array('class' => 'modal problem'));
        $this->redirect('/users/manageuser');
      }

    }
    
}
?>
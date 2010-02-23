<?php
 /*
 File Name : library_admins_controller.php
 File Description : Controller page for the  library admin login functionality.
 Author : maycreate
 */
Class LibraryAdminsController extends AppController
{
   var $name = 'LibraryAdmins';
   var $helpers = array('Html','Ajax','Javascript','Form');
   var $uses = array('Library','LibraryAdmin','Admin');	
   var $layout = 'login';
   var $components = array('Session','Auth');
   
   
   function beforeFilter()
    {
       $this->Auth->allow('login','logout');
       $this->Auth->userModel = 'Library';
       $this->Auth->loginAction = array('controller' => 'library_admins', 'action' => 'login');
       $this->Auth->loginRedirect = array('controller' => 'library_admins', 'action' => 'index');
       $this->Auth->logoutRedirect = '/library_admins/login';
    }
   /*
    Function Name : index
    Desc : Sets the layout for the default admin login page
   */
    public function index()
    {
        //$this->redirect('/library_admins/login');
    }
   
    /*
    Function Name : login
    Desc : Validates admin login credentials
   */
    
    public function login()
    {
    }
    
    /*
    Function Name : logout
    Desc : Logs admin out of the system
   */
    
    public function logout()
    {
        $this->redirect($this->Auth->logout()); 
    }
}
?>
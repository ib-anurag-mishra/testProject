<?php
 /*
 File Name : admins_controller.php
 File Description : Controller page for the  login functionality.
 Author : maycreate
 */
Class AdminsController extends AppController
{
   var $name = 'Admins';
   var $helpers = array('Html','Ajax','Javascript','Form');
   var $layout = 'admin';
   var $components = array('Session','Auth');
   
   function before_filter()
   {
      $this->Auth->userModel = 'Admin';
      $this->Auth->fields = array(
          'username' => 'username', 
          'password' =>'password');
   }
 
   /*
    Function Name : index
    Desc : Sets the layout for the default admin login page
   */
    public function index()
    {
         $this->layout = 'login';
    }
   
    /*
    Function Name : login
    Desc : Validates admin login credentials
   */
    
    public function login()
    {
      //used by Auth Component
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
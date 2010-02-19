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
	
   var $layout = 'login';
  // var $components = array('Auth');
   
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
      $username = $this->data['Admin']['username'];
      $password = $this->data['Admin']['password'];
      $loginObj = new Admin();
      if($loginObj->login($username,$password))
      {
         $this->Session->write("username",$username);
         $this->redirect('/admin_homes/index');
      }else{
        $this->redirect('/admins/index');
      }
      die();
    }
    
    /*
    Function Name : logout
    Desc : Logs admin out of the system
   */
    
    public function logout()
    {
        $this ->Session->destroy();
        $this->redirect('/admins/index');
    }
}
?>
<?php
 /*
 File Name : admins_home_controller.php
 File Description : Admin home controller page
 Author : maycreate
 */
Class AdminHomesController extends AppController
{
    var $uses = array('Admin','Admintype','Physicalproduct','Featuredartist');
    var $helpers = array('Session','Html','Ajax','Javascript','Form');
    var $layout = 'admin';
    var $components = array('Session');
   /*
    Function Name : beforeFilter
    Desc : This function is called before any other method is called in theis controller
   */
    
    function beforeFilter()
    {
      if($this->Session->read('username') == "")
      {
         $this->redirect('/admins/index');
      }
       $this->set('username',  $this->Session->read('username'));//setting the username to display on the header 
    }
    
    /*
    Function Name : index
    Desc : Sets the layout for the default admin home page
   */
    
    public function index()
    {
        //takes to the default admin home page
         $this->set('username',  $this->Session->read('username'));  //setting the username to display on the header 
    }
    
    /*
    Function Name : listuser
    Desc : action for listing all the admin users
   */
    
    public function manageuser()
    {
         $adminObj = new Admin();
         $admins = $adminObj->getallusers();
         $this->set('admins',$admins);
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
                $getUserDataObj = new Admin();
                $getData = $getUserDataObj->getuserdata($adminUserId);
                $this->set('getData', $getData);
                //editting a value
                if(isset($this->data))
                {
                 $updateObj = new Admin();
                 $getData['Admin'] = $this->data['AdminHome'];
                 $getData['Admintype']['id'] = $this->data['AdminHome']['type_id'];
                 $this->set('getData', $getData);
                 $errorMsg = '';
                 if(trim($this->data['AdminHome']['first_name']) == "")
                 {
                   $errorMsg .= 'First name cannot be left blank<br/>';
                 }
                 if(trim($this->data['AdminHome']['last_name']) == "")
                 {
                   $errorMsg .= 'Last name cannot be left blank<br/>';
                 }
                 if(trim($this->data['AdminHome']['email']) == "")
                 {
                   $errorMsg .= 'Email cannot be left blank<br/>';
                 }else{
                  if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $this->data['AdminHome']['email']))
                  {
                    $errorMsg .= 'Invalid Email Address<br/>';
                  }
                 }
                 if(trim($this->data['AdminHome']['username']) == "")
                 {
                   $errorMsg .= 'Username cannot be left blank<br/>';
                 }else
                 {
                  $userExists = $updateObj->checkusername($this->data['AdminHome']['username'],$getData['Admin']['id']);
                  if($userExists == 0)
                  {
                     $errorMsg .= 'Username Already exists<br/>';
                  }
                 }
                 if(trim($this->data['AdminHome']['type_id']) == "")
                 {
                   $errorMsg .= 'Select a User Type<br/>';
                 }
                 if(empty($errorMsg))
                 {
                   $this->Admin->id = $this->data['AdminHome']['id'];
                   if(trim($this->data['AdminHome']['password']) != "")
                   {
                     $this->data['AdminHome']['password'] = md5($this->data['AdminHome']['password']);
                   }else{
                     // do not update the password
                    $this->data['AdminHome'] = $updateObj->arrayremovekey( $this->data['AdminHome'],'password');
                   }
                   if($updateObj->update($this->data['AdminHome']))
                   {
                     $this->Session->setFlash('Data has been save Sucessfully');
                     $this->redirect('/admin_homes/manageuser');
                   }
                 }else{
                   echo  $this->Session->setFlash($errorMsg);
                    //$this->redirect('/admin_homes/manageuser');
                   // echo $errorMsg;
                    
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
                 $insertObj = new Admin();
                 $getData['Admin'] = $this->data['AdminHome'];
                 $getData['Admintype']['id'] = $this->data['AdminHome']['type_id'];
                 $this->set('getData', $getData);
                 $errorMsg = '';   
                 if(trim($this->data['AdminHome']['first_name']) == "")
                 {
                   $errorMsg .= 'First name cannot be left blank<br/>';
                 }
                 if(trim($this->data['AdminHome']['last_name']) == "")
                 {
                   $errorMsg .= 'Last name cannot be left blank<br/>';
                 }
                 if(trim($this->data['AdminHome']['email']) == "")
                 {
                   $errorMsg .= 'Email cannot be left blank<br/>';
                 }else{
                  if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $this->data['AdminHome']['email']))
                  {
                    $errorMsg .= 'Invalid Email Address<br/>';
                  }
                 }
                 if(trim($this->data['AdminHome']['username']) == "")
                 {
                   $errorMsg .= 'Username cannot be left blank<br/>';
                 }else
                 {
                  $userExists = $insertObj->checkusername($this->data['AdminHome']['username']);
                  if($userExists == 0)
                  {
                     $errorMsg .= 'Username Already exists<br/>';
                  }
                 }
                 if(trim($this->data['AdminHome']['password']) == "")
                 {
                   $errorMsg .= 'Password cannot be left blank<br/>';
                 }
                 if(trim($this->data['AdminHome']['type_id']) == "")
                 {
                   $errorMsg .= 'Select a User Type<br/>';
                 }
                 //end of validation for posted data
                 if(empty($errorMsg))
                 {
                  $this->data['AdminHome']['password'] = md5($this->data['AdminHome']['password']);
                  if($insertObj->insert($this->data))
                  {
                    $this->Session->setFlash('Data has been saved Sucessfully');
                    $this->redirect('/admin_homes/manageuser');
                  }
                 }else{
                    $this->Session->setFlash($errorMsg);
                 }
                }
                //insertion operation
            }
        $adminTypeObj = new Admintype();
        $typeArr = $adminTypeObj->getallusertype();
        $this->set('options',$typeArr);
    }
    
     
    /*
    Function Name : delete
    Desc : For deleting a user
   */
    public function delete()
    {
      $deleteAdminUserId = $this->params['named']['id'];
      $deleteObj  = new Admin();
      if($deleteObj->del($deleteAdminUserId))
      {
        $this->Session->setFlash('Data deleted Sucessfully');
        $this->redirect('/admin_homes/manageuser');
      }else{
        $this->Session->setFlash('Error occured while deleteting the record');
        $this->redirect('/admin_homes/manageuser');
      }

    }
    
}
?>
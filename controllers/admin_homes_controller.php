<?php
 /*
 File Name : admins_home_controller.php
 File Description : Admin home controller page
 Author : maycreate
 */
Class AdminHomesController extends AppController
{
    var $name = 'AdminHomes';
    var $uses = array('Admin','AdminHome','Physicalproduct','Featuredartist');
    var $helpers = array('Session','Html','Ajax','Javascript','Form');
    var $layout = 'admin';
    var $components = array('Session');
   /*
    Function Name : beforeFilter
    Desc : This function is called before any other method is called in theis controller
   */
    
    function beforeFilter()
    {
       $this->Auth->userModel = 'Admin';
       if($this->Session->read('Auth.Admin.type_id') == 1)
       {
          $this->Auth->allow('*');
          $this->set('username',  $this->Session->read('Auth.Admin.username'));
       }else{
         $this->redirect('/admins/login');
       }
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
                    $updateObj = new AdminHome();
                    $getData['Admin'] = $this->data['AdminHome'];
                    $getData['Admintype']['id'] = $this->data['AdminHome']['type_id'];
                    $this->set('getData', $getData);
                
                    $this->AdminHome->id = $this->data['AdminHome']['id'];                   
                    if(trim($this->data['AdminHome']['password']) != "")
                    {
                    $this->data['AdminHome']['password'] = $this->Auth->password($this->data['AdminHome']['password']);
                    }
                    else
                    {
                       // do not update the password
                       $this->data['AdminHome']= $updateObj->arrayremovekey( $this->data['AdminHome'],'password');
                    }
                    $this->AdminHome->set($this->data['AdminHome']);  
                    if($this->AdminHome->save())
                    {
                      $this->Session->setFlash('Data has been save Sucessfully');
                      $this->redirect('/admin_homes/manageuser');
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
                    $insertObj = new AdminHome();
                    $getData['Admin'] = $this->data['AdminHome'];
                    $getData['Admintype']['id'] = $this->data['AdminHome']['type_id'];
                    $this->set('getData', $getData);
                
                 
                    if(!empty($this->data['AdminHome']['password']))
                    {
                      $this->data['AdminHome']['password'] = $this->Auth->password($this->data['AdminHome']['password']);
                    }                  
                    $this->AdminHome->set($this->data['AdminHome']);                  
                    if($this->AdminHome->save())
                    {                    
                      $this->Session->setFlash('Data has been saved Sucessfully');
                      $this->redirect('/admin_homes/manageuser');
                    }
                    else
                    {
                      $this->data['AdminHome']['password'] = '';
                      $this->Session->setFlash('There was a problem saving this information');
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
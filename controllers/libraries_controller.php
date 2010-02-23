<?php
  /*
 File Name : libraries_controller.php
 File Description : Library controller page
 Author : maycreate
 */
Class LibrariesController extends AppController
{
  var $name = 'Libraries';  
  var $layout = 'admin';  
  var $helpers = array('Html','Ajax','Javascript','Form' );
  
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
    Function Name : managelibraries
    Desc : action for listing all the libraries
   */
    
    public function managelibrary()
    {         
      $libraryObj = new Library();
      $libraries = $libraryObj->getalllibraries();
      $this->set('libraries',$libraries);   
    }
    
    public function libraryform()
    {
      if(!empty($this->params['named']['id']))//gets the values from the url in form  of array
        {
          $libraryId = $this->params['named']['id'];
          if(trim($libraryId) != "" && is_numeric($libraryId))
          {
            $this->set('formAction','libraryform/id:'.$libraryId);
            $this->set('formHeader','Edit Library');
            $getLibraryDataObj = new Library();
            $getData = $getLibraryDataObj->getlibrarydata($libraryId);
            $this->set('getData', $getData);
            //editting a value
            if(isset($this->data))
            {
              $updateObj = new Library();
              $getData['Library'] = $this->data['Library'];              
              $this->set('getData', $getData);
              /*$errorMsg = '';
              if(trim($this->data['Library']['first_name']) == "")
              {
                $errorMsg .= 'First name cannot be left blank<br/>';
              }
              if(trim($this->data['Library']['last_name']) == "")
              {
                $errorMsg .= 'Last name cannot be left blank<br/>';
              }                 
              if(trim($this->data['Library']['username']) == "")
              {
                $errorMsg .= 'Username cannot be left blank<br/>';
              }              
              if(trim($this->data['Library']['referrer_url']) == "")
              {
                $errorMsg .= 'Please Provide Referral URL.<br/>';
              }
              if(trim($this->data['Library']['download_limit']) == "")
              {
                $errorMsg .= 'Select a Download Limit<br/>';
              }
             
              if(empty($errorMsg))
              {*/
                $this->Library->id = $this->data['Library']['id'];                
                if(trim($this->data['Library']['password']) != "")
                {                  
                  $this->data['Library']['password'] = md5($this->data['Library']['password']);
                }else{                  
                  // do not update the password
                 $this->data['Library'] = $updateObj->arrayremovekey($this->data['Library'],'password');
                }
                $this->Library->set($this->data['Library']); 
                if($this->Library->save())
                {
                  $this->Session->setFlash('Data has been save Sucessfully');
                  $this->redirect('/libraries/managelibrary');
                }
              //}
              else
              {
                echo  $this->Session->setFlash('Data could not be updated.');               
              }
            }
             //editting a value
          }
        }
        else
        { 
          $arr = array();                
          $this->set('getData',$arr);
          $this->set('formAction','libraryform');
          $this->set('formHeader','Create Library');
          //insertion Operation
          if(isset($this->data))
          {
           $insertObj = new Library();
           //$getData['Admin'] = $this->data['AdminHome'];
           //$getData['Admintype']['id'] = $this->data['AdminHome']['type_id'];
           //$this->set('getData', $getData);
          /* $errorMsg = '';   
           if(trim($this->data['Library']['first_name']) == "")
           {
             $errorMsg .= 'First name cannot be left blank<br/>';
           }
           if(trim($this->data['Library']['last_name']) == "")
           {
             $errorMsg .= 'Last name cannot be left blank<br/>';
           }                 
           if(trim($this->data['Library']['username']) == "")
           {
             $errorMsg .= 'Username cannot be left blank<br/>';
           }else
           {
            $userExists = $insertObj->checkusername($this->data['Library']['username']);
            if($userExists == 0)
            {
               $errorMsg .= 'Username Already exists<br/>';
            }
           }
           if(trim($this->data['Library']['password']) == "")
           {
             $errorMsg .= 'Password cannot be left blank<br/>';
           }
           if(trim($this->data['Library']['library_name']) == "")
           {
             $errorMsg .= 'Please Provide Library Name.<br/>';
           }
           if(trim($this->data['Library']['referrer_url']) == "")
           {
             $errorMsg .= 'Please Provide Referral URL.<br/>';
           }
           if(trim($this->data['Library']['download_limit']) == "")
           {
             $errorMsg .= 'Select a Download Limit<br/>';
           }
           //end of validation for posted data
           if(empty($errorMsg))
           {*/
            if(!empty($this->data['Library']['password']))
            {
              $this->data['Library']['password'] = md5($this->data['Library']['password']);
            }
            $this->Library->set($this->data['Library']);
            if($this->Library->save($this->data))
            {
              $this->Session->setFlash('Data has been saved Sucessfully');
              $this->redirect('/libraries/managelibrary');
            }
           //}
           else{
              $this->Session->setFlash('Data could not be saved.');
           }
          }
          //insertion operation
        }
        
    }    
    
    /*
    Function Name : delete
    Desc : For deleting a library
   */
    public function delete()
    {
      $deleteLibraryId = $this->params['named']['id'];
      $deleteObj  = new Library();
      if($deleteObj->del($deleteLibraryId))
      {
        $this->Session->setFlash('Data deleted Sucessfully');
        $this->redirect('/libraries/managelibrary');
      }else{
        $this->Session->setFlash('Error occured while deleteting the record');
        $this->redirect('/libraries/managelibrary');
      }

    }
}
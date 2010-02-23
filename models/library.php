<?php
 /*
 File Name : admin.php
 File Description : Models page for the  login functionality.
 Author : maycreate
 */
class Library extends AppModel
{
    var $name = 'Library';    
    var $validate = array(
    'library_name' => array('rule' => array( 'minLength' , 1 ),
                        'message' => 'Please provide Library Name.',                        
                ) ,
    'first_name' => array('rule' => array( 'minLength' , 1 ),
                        'message' => 'please provide Firstname.',                        
                ) ,        
    'last_name' => array('rule' => array( 'minLength' , 1 ),
                        'message' => 'please provide Lastname.',                        
                ) ,
    'username' => array('rule' => array( 'minLength' , 1 ),
        'message' => 'Please provide username.'
                ),  
    'password' => array('rule' => array( 'minLength' , 1 ),
                        'message' => 'please provide password'                                                
                ) ,          
    'referrer_url' => array('rule' => 'url',
                        'message' => 'Please provide a valid url.'                        
                )
    );
    /*
    Function Name : login
    Input Params : username,password
    Returns : Boolean
    Desc : Validates library login in the DB
    */
    
    public function login($username,$password)
    {
        $password = md5($password);
        $getArr = $this->find('count', array('conditions' => array('username' => $username ,'password' => $password)));
        if($getArr == 1)
        {
            return true;
        }else{
            return false;
        }
    }
    
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
   
    /*
    Function Name : insert
    Desc : inserts library data
    */
    
    public function insert($data)
    {
      if($this->save($data['Library']))
      {
        return true;
      }else{
        return false;
      }
    }
    
    /*
    Function Name : update
    Desc : updates a library data
    */
    
    public function update($data)
    {
        if($this->save($data))
        {
            return true;
        }else{
            return false;
        }
    }
    
    /*
    Function Name : del
    Desc : deletes a library
    */
    public function del($id)
    {
     if($this->delete($id))
      {
            return true;
      }else{
          return false;
      }
    }
}
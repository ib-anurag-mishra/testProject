<?php
 /*
 File Name : admin.php
 File Description : Models page for the  login functionality.
 Author : maycreate
 */
class Admin extends AppModel
{
    var $name = 'Admin';
    var $belongsTo =  array('Admintype' => array('className' => 'Admintype','foreignKey' =>'type_id'));
  

    /*
    Function Name : login
    Input Params : username,password
    Returns : Boolean
    Desc : Validates admin login in the DB
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
    Function Name : checkusername
    Desc : Checks the presence of username
    */
    
    public function checkusername($username,$id = ' ')
    {
     if($id == ' ')
     {
       $getUsernameCount = $this->find('count', array('conditions' => array('username' => $username)));
     }else{
       $getUsernameCount = $this->find('count', array('conditions' => array('username' => $username,'Admin.id !=' => $id)));
     }
       if($getUsernameCount == 0)
       {
            return 1;
       }else{
            return 0;
       }
    }
    
    /*
    Function Name : login
    Desc : gets all the admin users details from the db
    */
    
    public function getallusers()
    {
        $getAdmins = $this->find('all');
        return $getAdmins;
    }
    
    /*
    Function Name :  getuserdata
    Desc : gets the details for a user
    */
    
    public function getuserdata($id)
    {
       $getAdminData = $this->find('first', array('conditions' => array('Admin.id' => $id)));
       return $getAdminData;
    }
    
    /*
    Function Name : insert
    Desc : inserts admin user data
    */
    
    public function insert($data)
    {
      if($this->save($data['AdminHome']))
      {
        return true;
      }else{
        return false;
      }
    }
    
    /*
    Function Name : update
    Desc : updatesa admin user data
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
?>
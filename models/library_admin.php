<?php
 /*
 File Name : libraryadmin.php
 File Description : Models page for the  login functionality.
 Author : maycreate
 */
class Libraryadmin extends AppModel
{
    var $name = 'Libraryadmin';
    var $useTable = 'libraries';
    //var $uses = array('Library','LibraryAdmin');
    //var $belongsTo =  array('Admintype' => array('className' => 'Admintype','foreignKey' =>'type_id'));
  

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
  
}
?>
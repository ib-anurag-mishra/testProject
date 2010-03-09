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
    'admin_id' => array('rule' => array( 'minLength' , 1 ),
                        'message' => 'please provide Library Admin.',                        
                ) ,           
    'referrer_url' => array('rule' => 'url',
                        'message' => 'Please provide a valid url.'                        
                )
    );    
    
   
    
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
}
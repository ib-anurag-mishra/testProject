<?php
    class AdminHome extends AppModel
    {
    var $name = 'AdminHome';
    var $useTable = 'admins';
    var $validate = array(
    'first_name' => array('rule' => array( 'minLength' , 1 ),
                        'message' => 'Please provide Firstname.',                        
                ) ,
    'last_name' => array('rule' => array( 'minLength' , 1 ),
                        'message' => 'please provide Lastname.',                        
                ) ,        
    'username' => array('usernameRule-1' => array
                            ('rule' => array( 'minLength' , 1 ),
                             'message' => 'Please provide username.',
                             'last' => true
                        ) ,
                        'usernameRule-2' => array
                            ('rule' => 'isUnique',
                             'message' => 'This username has already been taken.'                             
                        )                    
                ) ,
    'password' => array('rule' => array( 'minLength' , 1 ),
                        'message' => 'please provide password'                                                
                ) ,    
    'email' => array('rule' => array('email', true),
        'message' => 'Please supply a valid email address.'
                ),        
    'type_id' => array('rule' => 'notEmpty',
                        'message' => 'Select a User Type.'                        
                ) 
   
    );
    
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
?>
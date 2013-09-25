<?php

/*
 File Name : queues_controller.php
 File Description : Queues controller page
 Author : m68interactive
 */

class RegisterConcertsController extends AppController{
    
    var $name = 'RegisterConcerts';
    var $layout = 'home';
    var $helpers = array( 'Html', 'Form', 'Session');
    var $components = array('Session', 'Auth', 'Acl');
    var $uses = array( 'RegisterConcert', 'User');
    
    function beforeFilter(){
       
            parent::beforeFilter();
          //  $this->Auth->allow('index');
    }

    
    /*
     Function Name : userStreaming
     Desc : Ajax function used for submitting registeration concert details
     * 
     * 
     * @return Message
    */
    
    function ajax_submit_register_concert() 
    {        
       $this -> layout = 'ajax';
       //Configure::write('debug', 2);
             
            if(empty($this->data['first_name']) || empty($this->data['last_name']) || empty($this->data['phone_no']))
            {                
                    $this->set('Message', "Failure");	

            }   
            else
            {
                    $this->data['RegisterConcert']['first_name']    = $this->data['first_name'];
                    $this->data['RegisterConcert']['last_name']     = $this->data['last_name'];
                    $this->data['RegisterConcert']['library_card']  = $this->Session->read('patron');
                    $this->data['RegisterConcert']['phone_no']      = $this->data['phone_no'];
                    $this->data['RegisterConcert']['library_id']    = $_POST['library_id'];
                    $this->data['RegisterConcert']['created']       = date('Y-m-d H:i:s');                
                
                     // echo "<pre>"; print_r($this->data);
            
                    $this->RegisterConcert->setDataSource('master');
                    if($this->RegisterConcert->save($this->data['RegisterConcert']))
                    {
                      $this->set('Message', '<font style="color:green;">Thanks for entering the Concert Ticket Giveway.</font><br><br>Contest closes October 11, 2013.');      						
                    }
                    else
                    {
                            $this->set('Message', "There has been error while storing the details.");				
                    }
                    
                    $this->RegisterConcert->setDataSource('default');
                
            }
       
    }
    
    
    
     /*
     Function Name : aboutus
     Desc : actions used for User end checking for cookie and javascript enable
    */
    function great_fall_concert() 
    {		
		$this->layout = 'home';
    }
    
    
    
    
}


?>

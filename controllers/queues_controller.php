<?php

/*
 File Name : queues_controller.php
 File Description : Queues controller page
 Author : m68interactive
 */

class QueuesController extends AppController{
    
    var $name = 'Queues';
    var $layout = 'home';
    var $helpers = array( 'Html', 'Form', 'Session');
    var $components = array('Session', 'Auth', 'Acl' ,'Queue');
    var $uses = array( 'Queuelist','QueuelistDetails','User','Album','Song');
    
    function beforeFilter(){
            parent::beforeFilter();
            $this->Auth->allow('getDefaultQueues','createQueue','savedQueuesList');
    }
    
    /**
     * function name : queueList
     * Description   : This function is used to retrieve all the queues created by an individual
     */
    
    function savedQueuesList($patron_id){
        $this->layout = 'home';
        $queueData = $this->Queue->getQueueList($patron_id);
        $this->set('queueData',$queueData);
    }
    
    /**
     * function name : createQueueList
     * Description   : This function is used to create a new queue list
     */    
    
    function createQueue(){
        if(isset($this->data)) {
            if($this->Session->read("Auth.User.type_id") == 1){
                $this->data['Queuelist']['user_type']  = 'd';
            }else{
                $this->data['Queuelist']['user_type']  = 'c';
            }
            
            $this->data['Queuelist']['Created']  = date('Y-m-d H:i:s');
            $this->data['Queuelist']['patronID'] = $this->Session->read('patron');
            
            if($this->Queuelist->save($this->data['Queuelist'])){
                    $this->Session ->setFlash('Queue has been Added successfully', 'modal', array( 'class' => 'queue success' ));
                    $this->redirect('/queues/savedQueuesList/'.$this->Session->read('patron'));						
            }
            else{
                    $this->Session ->setFlash('Error occured while adding queue', 'modal', array( 'class' => 'queue problem' ));
                    $this->redirect('/queues/savedQueuesList/'.$this->Session->read('patron'));					
            }			
        }
                                
    }
    
    
    /**
     * Function Name  :  getDefaultQueues
     * Description    :  This function is used to get default queues created by Admin
     */

    function getDefaultQueues(){
        $cond = array('user_type' => 'd', 'status' => '1');

        // Unbinded User model
        $this->Queuelist->unbindModel(
            array('belongsTo' => array('User'),'hasMany' => array('QueuelistDetails'))
        );        

        if ( ((Cache::read('defaultqueuelist')) === false)  || (Cache::read('defaultqueuelist') === null) ) {
            $queueData = $this->Queuelist->find('all', array(
                    'conditions' => $cond,
                    'fields' => array('Plid','PlaylistName'),
                    'order' => 'Queuelist.Created DESC',
                    'limit' => 100
                  ));
            Cache::write("defaultqueuelist", $queueData);
        }else{
            $queueData = Cache::read("defaultqueuelist");
        }        
        return $queueData;             

    }  
    
    
    
    
}


?>

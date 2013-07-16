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
            $this->Auth->allow('savedQueuesList','createQueue');
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

        if(!empty($this->params['named']['id'])){ //gets the values from the url in form  of array
                $adminUserId = $this->params['named']['id'];
                if(trim($adminUserId) != "" && is_numeric($adminUserId)){
                        $this->set('formAction','/queues/createQueue/id:'.$adminUserId);
                        $this->set('formHeader','Edit Queuelist');
                        $this->set('getData', $this->User->getuserdata($adminUserId));
                        //editting a value
                        if(isset($this->data)){
                                $updateObj = new User();
                                $getData['User'] = $this->data['User'];
                        //	$this->data['User']['library_id'] = Configure::read('LibraryIdeas');
                                if($this->data['User']['type_id'] == 5){
                                        $this->data['User']['sales'] = 'yes';
                                }
                                if($this->data['User']['type_id'] == 6 && $this->data['User']['consortium'] != ''){
                                        $this->data['User']['type_id'] = 4;
                                }else{
                                        $this->data['User']['consortium'] = '';
                                }
                                $getData['Group']['id'] = $this->data['User']['type_id'];
                                $this->set('getData', $getData);
                                $this->User->id = $this->data['User']['id'];
                                if(trim($this->data['User']['password']) == "48d63321789626f8844afe7fdd21174eeacb5ee5"){
                                        // do not update the password
                                        $this->data['User']= $updateObj->arrayremovekey($this->data['User'],'password');
                                }
                                $this->User->set($this->data['User']);
                                if($this->User->save()){
                                        $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                                        $this->redirect('manageuser');
                                }
                        }
                }
        }else{
            $arr = array();
            $this->set('getData',$arr);
            $this->set('formAction','/queues/createQueue');
            $this->set('formHeader','Create Queue');
            //insertion Operation
            if(isset($this->data)){
                    $insertObj = new User();
                    $getData['User'] = $this->data['User'];
            //	$this->data['User']['library_id'] = Configure::read('LibraryIdeas');
                    if($this->data['User']['type_id'] == 5){
                            $this->data['User']['sales'] = 'yes';
                    }
                    if($this->data['User']['type_id'] == 6 && $this->data['User']['consortium'] != ''){
                            $this->data['User']['type_id'] = 4;
                    }else{
                            $this->data['User']['consortium'] = '';
                    }
                    $getData['Group']['id'] = $this->data['User']['type_id'];
                    $this->set('getData', $getData);
                    if($this->data['User']['password'] == "48d63321789626f8844afe7fdd21174eeacb5ee5"){
                            $this->data['User']['password'] = "";
                    }
                    $this->User->set($this->data['User']);
                    if($this->User->save()){
                            $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                            $this->redirect('manageuser');
                    }
                    else{
                            $this->data['User']['password'] = '';
                            $this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
                    }
            }
        }        
        
                                print_r($this->data);exit;
    }
    
    
    
    
}


?>

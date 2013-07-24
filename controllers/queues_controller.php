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
    var $uses = array( 'QueueList', 'QueueDetail','User','Album','Song');
    
    function beforeFilter(){
            parent::beforeFilter();
            $this->Auth->allow('getDefaultQueues','savedQueuesList','createQueue','addToQueue');
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
                $this->data['QueueList']['queue_type']  = 1;
            }else{
                $this->data['QueueList']['queue_type']  = 0;
            }
            $this->data['QueueList']['created']  = date('Y-m-d H:i:s');
            $this->data['QueueList']['patron_id'] = $this->Session->read('patron');
            $this->QueueList->setDataSource('master');
            if($this->QueueList->save($this->data['QueueList'])){
                    $this->Session ->setFlash('Queue has been Added successfully', 'modal', array( 'class' => 'queue success' ));
                    $this->redirect($this->referer());						
            }
            else{
                    $this->Session ->setFlash('Error occured while adding queue', 'modal', array( 'class' => 'queue problem' ));
                    $this->redirect($this->referer());					
            }
            $this->QueueList->setDataSource('default');
        }
                                
    }
    
    /**
     * Function Name : addToQueue
     * Description   : This function is used to add a song to a Queue
     */
    
    function addToQueue(){
        Configure::write('debug', 0);
        if( $this->Session->read('library') && $this->Session->read('patron') && !empty($_REQUEST['songProdId']) && !empty($_REQUEST['songProviderType'])&& !empty($_REQUEST['albumProdId'])&& !empty($_REQUEST['albumProviderType'])&& !empty($_REQUEST['queueId']) ){
            $queuesongsCount =  $this->QueueDetail->find('count',array('conditions' => array('queue_id' => $_REQUEST['queueId'],'song_prodid' => $_REQUEST['songProdId'],'song_providertype' => $_REQUEST['songProviderType'],'album_prodid' => $_REQUEST['albumProdId'],'album_providertype' => $_REQUEST['albumProviderType'])));
            if(!$queuesongsCount){
                $insertArr = Array();
                $insertArr['queue_id'] = $_REQUEST['queueId'];
                $insertArr['song_prodid'] = $_REQUEST['songProdId'];
                $insertArr['song_providertype'] = $_REQUEST['songProviderType'];
                $insertArr['album_prodid'] = $_REQUEST['albumProdId'];
                $insertArr['album_providertype'] = $_REQUEST['albumProviderType'];
                //insert into queuedetail table
                $this->QueueDetail->setDataSource('master');
                $this->QueueDetail->save($insertArr);
                $this->QueueDetail->setDataSource('default');
                echo "Success";
                exit;

                }else{
                    echo 'error1';
                    exit; 

                }
            
        }else{
            echo 'error';
            exit;
        }        
        
    }
    
    
    
    /**
     * Function Name  :  getDefaultQueues
     * Description    :  This function is used to get default queues created by Admin
     */

    function getDefaultQueues(){
        $cond = array('queue_type' => 1, 'status' => '1');

        // Unbinded User model
        $this->QueueList->unbindModel(
            array('belongsTo' => array('User'),'hasMany' => array('QueueDetail'))
        );        

        if ( ((Cache::read('defaultqueuelist')) === false)  || (Cache::read('defaultqueuelist') === null) ) {
            $queueData = $this->QueueList->find('all', array(
                    'conditions' => $cond,
                    'fields' => array('queue_id','queue_name','queue_type'),
                    'order' => 'QueueList.created DESC',
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

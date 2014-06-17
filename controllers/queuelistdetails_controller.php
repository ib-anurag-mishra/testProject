<?php

/*
  File Name : queues_controller.php
  File Description : Queues controller page
  Author : m68interactive
 */

class QueueListDetailsController extends AppController
{

    var $name = 'QueuesListDetails';
    var $layout = 'home';
    var $helpers = array( 'Html', 'Form', 'Session', 'Wishlist','Queue','Song');
    var $components = array('Session', 'Auth', 'Acl' ,'Queue', 'Downloads','Streaming');
    var $uses = array( 'QueueDetail','User','Album','Song', 'Wishlist','QueueList','Download', 'Token');
    
    function beforeFilter(){
           
            parent::beforeFilter();    
            if($this->Session->read('patron')!='')  //  After Login
            {
                    $this->Auth->allow('*');
            }
            else  //  Before Login
            {
                     $this->Auth->allow('');
            }
    }

    function index()
    {
        $dqPlid = $this->params['form']["dqPlid"];
        $patron_id = $this->Session->read('patron');
        if (!empty($patron_id))
        {
            $this->QueueDetail->setDataSource('master');
            $this->QueueList->setDataSource('master');
            if ($this->params['form']['hdn_remove_song'])
            {
                if (!empty($this->params['form']["Pdid"]))
                {
                    if ($this->QueueDetail->deleteAll(array('id' => $this->params['form']["Pdid"]), false))
                    {
                        $this->Session->setFlash('Song has been deleted successfully from playlist', 'modal', array('class' => 'queue success'));
                        $this->redirect($this->referer());
                    }
                    else
                    {
                        $this->Session->setFlash('Error occured while deleting song from playlist', 'modal', array('class' => 'queue problem'));
                        $this->redirect($this->referer());
                    }
                }
            }
            else if ($this->params['form']['hid_action'] == 'rename_queue')
            {
                if (!empty($this->params['form']["rqPlid"]))
                {
                    $this->data['QueueList']['queue_id'] = $this->params['form']["rqPlid"];
                    $this->QueueList->set($this->data['QueueList']);
                    if ($this->QueueList->save())
                    {
                        $this->layout = 'ajax';
                                echo 'Playlist has been renamed successfully';
                                exit;
                    }
                    else
                    {
                        $this->layout = 'ajax';
                                echo 'Error occured while renaming playlist';
                                exit;
                    }
                }
            }
            else if ($this->params['form']['hid_action'] == 'delete_queue')
            {
                if (!empty($dqPlid))
                {
                    $delqueueDetail = $this->QueueDetail->deleteAll(array('queue_id' => $dqPlid), false);
                    $delqueue = $this->QueueList->deleteAll(array('queue_id' => $dqPlid), false);

                    if ((true === $delqueueDetail) && (true === $delqueue))
                    {
                        $this->Session->setFlash('Playlist has been deleted successfully', 'modal', array('class' => 'queue success'));
                        $this->redirect('/queues/savedQueuesList/' . $this->Session->read('patron'));
                    }
                    else
                    {
                        $this->Session->setFlash('Error occured while deleteing playlist', 'modal', array('class' => 'queue problem'));
                        $this->redirect($this->referer());
                    }
                }
            }
            $this->QueueDetail->setDataSource('default');
            $this->QueueList->setDataSource('default');
        }
    }

    function removeSongFromQueue()
    {
        Configure::write('debug', 0);
        if ($this->Session->read('library') && $this->Session->read('patron'))
        {
            if (!empty($this->params['form']['songId']))
            {
                $this->QueueDetail->setDataSource('master');
                $this->QueueList->setDataSource('master');
                if ($this->QueueDetail->deleteAll(array('id' => $this->params['form']['songId']), false))
                {
                    echo "Success";
                    exit;
                }
                else
                {
                    echo "error";
                    exit;
                }
                
                $this->QueueDetail->setDataSource('default');
                $this->QueueList->setDataSource('default');
            }
            else    // Song cannot be deleted
            {
                echo 'error1';
                exit;
            }
        }
        else
        {
            echo 'error2';
            exit;
        }
    }

    /**
     * function name : queueList
     * Description   : This function is used to retrieve all the queues created by an individual
     * return        : List of queues 
     */
    function now_streaming()
    {
        $this->layout = 'home';
        $libId = $this->Session->read('library');
        $patId = $this->Session->read('patron');
        $territory = $this->Session->read('territory');
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
        $this->set('libraryDownload', $libraryDownload);
        $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);
        $this->set('patronDownload', $patronDownload);
        $queueId = $this->Session->read('queuePlaying');
        $songPlaying = $this->Session->read('songPlaying');
        if (!empty($queueId))
        {
            $queue_list_array = $this->Queue->getQueueDetails($queueId, $territory);
            $total_seconds = 0;
            foreach ($queue_list_array as $k => $v)
            {
                $full_length = $v['Songs']['FullLength_Duration'];
                $temp_arr = explode(":", $full_length);
                $minutes = $temp_arr[0];
                $seconds = $temp_arr[1];
                $total_seconds += $minutes * 60 + $seconds;                
                $filePath = $this->Token->streamingToken($v['SongFile']['SCdnPath'] . "/" . $v['SongFile']['SSaveAsName']);
                if (!empty($filePath))
                {
                    $songPath = explode(':', $filePath);
                    $streamUrl = trim($songPath[1]);
                    $queue_list_array[$k]['streamUrl'] = $streamUrl;
                }
            }

            $total_duration = $total_seconds / 60;
            $total_minutes = floor($total_duration);
            $total_seconds = $total_seconds % 60;

            $this->set('queue_list_array', $queue_list_array);
            $this->set('queue_id', $queueId);
            $this->set('queue_songs_count', count($queue_list_array));
            $this->set('total_time', $total_minutes . ":" . $total_seconds);
        }
        else if (!empty($songPlaying))
        {
            $trackDetails = $this->Queue->getNowstreamingSongDetails($songPlaying['prodId'], $songPlaying['providerType'], $territory);
            foreach ($trackDetails as $k => $v)
            {                
                $filePath = $this->Token->streamingToken($v['SongFile']['SCdnPath'] . "/" . $v['SongFile']['SSaveAsName']);
                if (!empty($filePath))
                {
                    $songPath = explode(':', $filePath);
                    $streamUrl = trim($songPath[1]);
                    $trackDetails[$k]['streamUrl'] = $streamUrl;
                }
            }
            $this->set('trackDetails', $trackDetails);
        }
    }

    function queue_details()
    {
        $this->layout = 'home';
        $libId = $this->Session->read('library');
        $patId = $this->Session->read('patron');
        $territory = $this->Session->read('territory');
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
        $this->set('libraryDownload', $libraryDownload);
        $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);
        $this->set('patronDownload', $patronDownload);

        if ($this->params['pass'][1] == '1')   //  Default Queue
        {
            $queue_list_array = Cache::read("defaultqueuelistdetails".$territory . $this->params['pass'][0]);
            if ($queue_list_array === false)
            {
                $queue_list_array = $this->Queue->getQueueDetails($this->params['pass'][0], $territory);
                if (!empty($queue_list_array))
                {
                    Cache::write("defaultqueuelistdetails".$territory. $this->params['pass'][0], $queue_list_array);
                }
            }
            $queue_name = base64_decode($this->params['pass'][2]);
            $this->set('queue_name', $queue_name);
            $this->set('queueType', 'Default');
        }
        else        // Custom Queue
        {
            $queue_list_array = $this->Queue->getQueueDetails($this->params['pass'][0], $territory);            
            $queue_name = base64_decode($this->params['pass'][2]);
            $this->set('queue_name', $queue_name);
            $this->set('queueType', 'Custom');
        }

        //Find Total Duration
        $total_seconds = 0;
        foreach ($queue_list_array as $k => $v)
        {
            $full_length = $v['Songs']['FullLength_Duration'];
            $temp_arr = explode(":", $full_length);
            $minutes = $temp_arr[0];
            $seconds = $temp_arr[1];
            $total_seconds += $minutes * 60 + $seconds;            
            $filePath = $this->Token->streamingToken($v['SongFile']['SCdnPath'] . "/" . $v['SongFile']['SSaveAsName']);
            if (!empty($filePath))
            {
                $songPath = explode(':', $filePath);
                $streamUrl = trim($songPath[1]);
                $queue_list_array[$k]['streamUrl'] = $streamUrl;
            }
            
        }    
        $total_duration     =    $total_seconds/60;
        $total_minutes      =    floor($total_duration);
        $total_seconds      =    $total_seconds%60;
        $total_seconds      =   ($total_seconds < 10 ) ? '0'.$total_seconds  : $total_seconds ;
        $total_minutes      =   ($total_minutes < 10 ) ? '0'.$total_minutes  : $total_minutes ;
        
        if($this->params['pass'][1]=='1')   //  Default Queue
        {
            $this->set('default_queue', $this->params['pass'][1]);
        }
        else
        {
            $this->set('default_queue', $this->params['pass'][1]);
        }
        
        if(count($queue_list_array)==0 || empty($queue_list_array))
        {
               $queue_list_array = $this->Queue->getOnlyQueueDetails($this->params['pass'][0]); 
               $queue_songs_count = 0;
        }
        else
        {
               $queue_songs_count =  count($queue_list_array);
        }
                              
        $this->set('queue_list_array', $queue_list_array);
        $this->set('queue_id', $this->params['pass'][0]);
        $this->set('queue_songs_count', $queue_songs_count);
        $this->set('total_time', $total_minutes . ":" . $total_seconds);
    }

    function getPlaylistData()
    {
        $prodId = $this->params['form']['prodId'];
        $provider = $this->params['form']['providerType'];
        $eventType = $this->params['form']['eventFired'];
        $userStreamedTime = $this->params['form']['userStreamedTime'];
        $songDuration = $this->params['form']['songLength'];
        $libId = $this->Session->read('library');
        $patId = $this->Session->read('patron');
        $this->Session->delete('queuePlaying');
        $this->Session->delete('songPlaying');
        $eventArray = array(5, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21);

        $validationResponse = $this->Streaming->validateSongStreaming($libId, $patId, $prodId, $provider, $userStreamedTime, $eventType, '', $songDuration, $this->params['form']['queueId'], $this->params['form']['songToken']);
        if (!empty($validationResponse) && is_array($validationResponse))
        {
            if ($validationResponse[0] == 0)
            {
                echo json_encode($validationResponse);
                exit;
            }
            else if ($validationResponse[0] == 1)
            {
                if (!in_array($eventType, $eventArray))
                {
                    if (!empty($this->params['form']['queueId']))
                    {
                        $this->Session->write("queuePlaying", $this->params['form']['queueId']);
                    }
                    else
                    {
                        if (!empty($prodId) && !empty($provider))
                        {
                            $songDetails = array('prodId' => $prodId, 'providerType' => $provider);
                            $this->Session->write("songPlaying", $songDetails);
                        }
                    }
                }
                echo json_encode($validationResponse);
                exit;
            }
            else
            {
                $this->log('Componenet gave some other value other than 0/1 in response', 'streaming');
                $error_message = array('error1' => $validationResponse);
                echo json_encode($validationResponse);
                exit;
            }
        }
        else
        {
            $this->log('Componenet gave empty response and the response is' . $validationResponse, 'streaming');
            $error_message = array('error' => $validationResponse);
            echo json_encode($validationResponse);
            exit;
        }
    }

    function clearNowStreamingSession()
    {
        $this->Session->delete('queuePlaying');
        $this->Session->delete('songPlaying');
        echo "success";
        exit;
    }

    function ajaxQueueValidation()
    {
        $this->layout = 'ajax';

        if ($this->Session->read('patron') == '')
        {
            echo 'Patron Not Login';
            exit;
        }
        elseif ($this->data['QueueList']['queue_name'] == '')
        {
            echo 'Playlist Name is empty';
            exit;
        }
        else
        {
            if ($this->Session->read("Auth.User.type_id") == 1)
            {
                $queue_type = 1;
            }
            else
            {
                $queue_type = 0;
            }

            $cond = array('queue_type' => $queue_type, 'status' => '1', 'patron_id' => array($this->Session->read('patron')), 'queue_name' => $this->data['QueueList']['queue_name']);

            $queueData = $this->QueueList->find('all', array(
                'conditions' => $cond,
                'fields' => array('queue_id'),
                'order' => 'QueueList.created DESC'
            ));

            if (count($queueData) == 0)
            {
                echo 'Insertion Allowed';
            }
            else
            {
                echo 'Playlist Name you entered is already present. Please try different name.';
            }

            exit;
        }

        exit;
    }

}
?>
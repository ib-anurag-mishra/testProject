<?php

class QueueListData{
  /**
	 * queuelistdata
	 * @var QueueListDataType
	 */
  public $queuelistdata;

  /**
	 * Constructor
	 * @param QueueListDataType $queuelistdata
	 * @return bool
	 */
  public function __construct($queuelistdata){
    $this->queuelistdata = $queuelistdata;
    return true;
  }
}

class QueueListDataType{
  /**
	 * PlaylistID
	 * @var int
	 */
  public $QueueID;
  /**
	 * PlaylistName
	 * @var string
	 */
  public $QueueName;
  /**
	 * PlayCreated
	 * @var string
	 */
  public $QueueCreated;  
  /**
	 * Playmodified
	 * @var string
	 */
  public $Queuemodified;
  
  /**
	 * Constructor
	 */
  public function __construct(){
  
  }
}
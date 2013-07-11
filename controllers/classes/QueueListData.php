<?php

class QueueListData{
  /**
	 * queuelistdata
	 * @var QueueListDatatType
	 */
  public $queuelistdata;

  /**
	 * Constructor
	 * @param QueueListDatatType $queuelistdata
	 * @return bool
	 */
  public function __construct($queuelistdata){
    $this->queuelistdata = $queuelistdata;
    return true;
  }
}

class QueueListDatatType{
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
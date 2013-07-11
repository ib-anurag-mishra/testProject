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
	 * QueueID
	 * @var int
	 */
  public $QueueID;
  /**
	 * QueueName
	 * @var string
	 */
  public $QueueName;
  /**
	 * QueueCreated
	 * @var string
	 */
  public $QueueCreated;  
  /**
	 * Queuemodified
	 * @var string
	 */
  public $QueueModified;
  /**
	 * Queueuser
	 * @var string
	 */
  public $QueueUser;
  /**
	 * Constructor
	 */
  public function __construct(){
  
  }
}
<?php

class QueueOperation{
	/**
	 * queueoperation
	 * @var QueueOperationType
	 */
	public $queueoperation;

	/**
	 * Constructor
	 * @param QueueOperationType $queueoperation
	 * @return bool
	 */
	public function __construct($queueoperation){
		$this->queueoperation = $queueoperation;
		return true;
	}
}

class QueueOperationType{
	/**
	 * success
	 * @var bool
	 */
	public $success;
	/**
	 * message
	 * @var string
	 */
	public $message;

	/**
	 * Constructor
	 */
	public function __construct(){

	}
}
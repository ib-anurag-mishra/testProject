<?php

class SuccessResponse{
	/**
	 * successresponse
	 * @var SuccessResponseType
	 */
	public $successresponse;

	/**
	 * Constructor
	 * @param SuccessResponseType $successresponse
	 * @return bool
	 */
	public function __construct($successresponse){
		$this->successresponse = $successresponse;
		return true;
	}
}

class SuccessResponseType{
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
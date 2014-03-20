<?php

class StreamingResponse{
	/**
	 * streamingresponse
	 * @var StreamingResponseType
	 */
	public $streamingresponse;

	/**
	 * Constructor
	 * @param StreamingResponseType $streamingresponse
	 * @return bool
	 */
	public function __construct($streamingresponse){
		$this->streamingresponse = $streamingresponse;
		return true;
	}
}

class StreamingResponseType{
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
	 * remaningtime
	 * @var string
	 */
	public $remaningtime;
	/**
	 * mp4url
	 * @var string
	 */
	public $mp4url;
	/**
	 * timerCallTime
	 * @var integer
	 */
	public $timerCallTime;
	/**
	 * timerCallDuration
	 * @var integer
	 */
	public $timerCallDuration;

	/**
	 * Constructor
	 */
	public function __construct(){

	}
}
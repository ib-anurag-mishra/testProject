<?php

class AuthenticationResponseData{
  /**
	 * authenticationresponsedata
	 * @var AuthenticationResponseDataType
	 */
  public $authenticationresponsedata;

  /**
	 * Constructor
	 * @param AuthenticationResponseDataType $authenticationresponsedata
	 * @return bool
	 */
  public function __construct($authenticationresponsedata){
    $this->authenticationresponsedata = $authenticationresponsedata;
    return true;
  }
}

class AuthenticationResponseDataType{
  /**
	 * response
	 * @var bool
	 */
  public $response;
    /**
	 * response_msg
	 * @var string
	 */
  public $response_msg;
  /**
	 * authentication_token
	 * @var string
	 */
  public $authentication_token; 
  /**
	 * patron_id
	 * @var int
	 */
  public $patron_id;
  /**
	 * user_type
	 * @var int
	 */
  public $user_type;

  /**
	 * Constructor
	 */
  public function __construct(){

  }
}
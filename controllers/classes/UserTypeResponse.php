<?php

class UserTypeResponse{
  /**
	 * usertyperesponse
	 * @var UserTypeResponseType
	 */
  public $usertyperesponse;

  /**
	 * Constructor
	 * @param UserTypeResponseType $usertyperesponse
	 * @return bool
	 */
  public function __construct($usertyperesponse){
    $this->usertyperesponse = $usertyperesponse;
    return true;
  }
}

class UserTypeResponseType{
  /**
	 * usertype
	 * @var integer
	 */
  public $usertype;

  /**
	 * Constructor
	 */
  public function __construct(){
  
  }
}
<?php

class UserData{
  /**
	 * userdata
	 * @var UserDataType
	 */
  public $userdata;

  /**
	 * Constructor
	 * @param UserDataType $userdata
	 * @return bool
	 */
  public function __construct($userdata){
    $this->userdata = $userdata;
    return true;
  }
}

class UserDataType{
  /**
	 * first_name
	 * @var string
	 */
  public $first_name;
  /**
	 * last_name
	 * @var string
	 */
  public $last_name;
  /**
	 * email
	 * @var string
	 */
  public $email;  

  /**
	 * Constructor
	 */
  public function __construct(){
  
  }
}
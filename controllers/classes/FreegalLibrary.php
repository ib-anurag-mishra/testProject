<?php

class FreegalLibrary{
  /**
	 * library
	 * @var FreegalLibraryType
	 */
  public $library;

  /**
	 * Constructor
	 * @param FreegalLibraryType $library
	 * @return bool
	 */
  public function __construct($library){
    $this->library = $library;
    return true;
  }
}

class FreegalLibraryType{
  /**
	 * LibraryId
	 * @var int
	 */
  public $LibraryId;
  /**
	 * LibraryName
	 * @var string
	 */
  public $LibraryName;
  /**
	 * LibraryApiKey
	 * @var string
	 */
  public $LibraryApiKey;
  /**
	 * LibraryAuthenticationMethod
	 * @var string
	 */
  public $LibraryAuthenticationMethod;
  /**
	 * LibraryAuthenticationNum
	 * @var string
	 */
  public $LibraryAuthenticationNum;
  /**
	 * LibraryAuthenticationUrl
	 * @var string
	 */
  public $LibraryAuthenticationUrl;

  /**
	 * Constructor
	 */
  public function __construct(){

  }
}
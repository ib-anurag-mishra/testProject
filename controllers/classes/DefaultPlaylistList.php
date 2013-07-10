<?php

class DefaultPlaylistList{
  /**
	 * defaultplaylistlist
	 * @var DefaultPlaylistListType
	 */
  public $defaultplaylistlist;

  /**
	 * Constructor
	 * @param DefaultPlaylistListType $defaultplaylistlist
	 * @return bool
	 */
  public function __construct($defaultplaylistlist){
    $this->defaultplaylistlist = $defaultplaylistlist;
    return true;
  }
}

class DefaultPlaylistListType{
  /**
	 * PlaylistID
	 * @var int
	 */
  public $PlaylistID;
  /**
	 * PlaylistName
	 * @var string
	 */
  public $PlaylistName;
  /**
	 * PlayCreated
	 * @var string
	 */
  public $PlayCreated;  
  /**
	 * Playmodified
	 * @var string
	 */
  public $Playmodified;
  
  /**
	 * Constructor
	 */
  public function __construct(){
  
  }
}
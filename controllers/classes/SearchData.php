<?php

class SearchData{
  /**
	 * searchdata
	 * @var SearchDataType
	 */
  public $searchdata;

  /**
	 * Constructor
	 * @param SearchDataType $searchdata
	 * @return bool
	 */
  public function __construct($searchdata){
    $this->searchdata = $searchdata;
    return true;
  }
}

class SearchDataType{
  /**
	 * SongProdID
	 * @var int
	 */
  public $SongProdID;
  /**
	 * SongTitle
	 * @var string
	 */
  public $SongTitle;
  /**
	 * SongArtist
	 * @var string
	 */
  public $SongArtist;
  /**
	 * AlbumArtist
	 * @var string
	 */
  public $AlbumArtist;
  /**
	 * fileURL
	 * @var string
	 */
  public $fileURL;

  /**
	 * AlbumProdID
	 * @var int
	 */
  public $AlbumProdID;
  
  /**
	 * AlbumTitle
	 * @var string
	 */
  public $AlbumTitle;
  
  /**
	 * Sample_Duration
	 * @var string
	 */
  public $Sample_Duration;
  
  /**
	 * FullLength_Duration
	 * @var string
	 */
  public $FullLength_Duration;
  
  /**
	 * ISRC
	 * @var string
	 */
  public $ISRC;
  
  /**
	 * Constructor
	 */
  public function __construct(){
  
  }
}
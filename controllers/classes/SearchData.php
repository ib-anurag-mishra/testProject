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
	 * Title
	 * @var string
	 */
  public $Title;
  /**
	 * SongArtist
	 * @var string
	 */
  public $SongArtist;
  
  /**
	 * ArtistText
	 * @var string
	 */
  public $ArtistText;
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
	 * FullLengthFileURL
	 * @var string
	 */
  public $FullLengthFileURL;
  
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
	 * DownloadStatus
	 * @var int
	 */
  public $DownloadStatus;
  
  /**
	 * playButtonStatus
	 * @var int
	 */
  public $playButtonStatus;
  
  
  /**
	 * Constructor
	 */
  public function __construct(){
  
  }
}
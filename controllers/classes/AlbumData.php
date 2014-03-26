<?php

class AlbumData{
  /**
	 * albumdata
	 * @var AlbumDataType
	 */
  public $albumdata;

  /**
	 * Constructor
	 * @param AlbumDataType $albumdata
	 * @return bool
	 */
  public function __construct($albumdata){
    $this->albumdata = $albumdata;
    return true;
  }
}

class AlbumDataType{
  /**
	 * ProdID
	 * @var int
	 */
  public $ProdID;
  /**
	 * ProductID
	 * @var string
	 */
  public $ProductID;
  /**
	 * AlbumTitle
	 * @var string
	 */
  public $AlbumTitle;
  /**
	 * Title
	 * @var string
	 */
  public $Title;

  /**
	 * ArtistText
	 * @var string
	 */
  public $ArtistText;

  /**
	 * Artist
	 * @var string
	 */
  public $Artist;

  /**
	 * ArtistURL
	 * @var string
	 */
  public $ArtistURL;

  /**
	 * Label
	 * @var string
	 */
  public $Label;

  /**
	 * Copyright
	 * @var string
	 */
  public $Copyright;

  /**
	 * Advisory
	 * @var string
	 */
  public $Advisory;

  /**
	 * FileURL
	 * @var string
	 */
  public $FileURL;

  /**
	 * DownloadStatus
	 * @var int
	 */
  public $DownloadStatus;

  /**
	 * TrackBundleCount
	 * @var int
	 */
  public $TrackBundleCount;

  /**
	 * TrackBundleCount
	 * @var SongDataType[]
	 */
  public $Songs;

  /**
	 * Constructor
	 * @param SongDataType[] $songs
	 */
  public function __construct($songs){

  }
}
<?php

class NationalTopTen{
  /**
	 * nationaltopten
	 * @var NationalTopTenType
	 */
  public $nationaltopten;

  /**
	 * Constructor
	 * @param NationalTopTenType $nationaltopten
	 * @return bool
	 */
  public function __construct($nationaltopten){
    $this->nationaltopten = $nationaltopten;
    return true;
  }
}

class NationalTopTenType{
  /**
	 * ProdId
	 * @var int
	 */
  public $ProdId;
  /**
	 * ProductId
	 * @var string
	 */
  public $ProductId;
  /**
	 * ReferenceId
	 * @var int
	 */
  public $ReferenceId;
  /**
	 * Title
	 * @var string
	 */
  public $Title;
  /**
	 * SongTitle
	 * @var string
	 */
  public $SongTitle;
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
	 * AlbumTitle
	 * @var string
	 */
  public $AlbumTitle;

  /**
	 * ISRC
	 * @var string
	 */
  public $ISRC;

  /**
	 * Composer
	 * @var string
	 */
  public $Composer;

  /**
	 * Genre
	 * @var string
	 */
  public $Genre;

  /**
	 * Territory
	 * @var string
	 */
  public $Territory;

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
	 * fileURL
	 * @var string
	 */
  public $fileURL;

  /**
	 * FullLength_FIleID
	 * @var int
	 */
  public $FullLength_FIleID;

  /**
	 * Constructor
	 */
  public function __construct(){

  }
}
<?php

class SongData{
	/**
	 * songdata
	 * @var SongDataType
	 */
	public $songdata;

	/**
	 * Constructor
	 * @param SongDataType $songdata
	 * @return bool
	 */
	public function __construct($songdata){
		$this->songdata = $songdata;
		return true;
	}
}

class SongDataType{
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
	 * ReferenceID
	 * @var int
	 */
	public $ReferenceID;
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
	 * Advisory
	 * @var string
	 */
	public $Advisory;

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
	 * Sample_FileID
	 * @var int
	 */
	public $Sample_FileID;

	/**
	 * Sample_FileURL
	 * @var string
	 */
	public $Sample_FileURL;

	/**
	 * FullLength_FIleID
	 * @var int
	 */
	public $FullLength_FIleID;

	/**
	 * CreatedOn
	 * @var string
	 */
	public $CreatedOn;

	/**
	 * UpdateOn
	 * @var string
	 */
	public $UpdateOn;

	/**
	 * FullLength_FIleURL
	 * @var string
	 */
	public $FullLength_FIleURL;

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
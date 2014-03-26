<?php

class VideoSongData{
	/**
	 * videosongdata
	 * @var VideoSongDataType
	 */
	public $videosongdata;

	/**
	 * Constructor
	 * @param VideoSongDataType $videosongdata
	 * @return bool
	 */
	public function __construct($videosongdata){
		$this->videosongdata = $videosongdata;
		return true;
	}
}

class VideoSongDataType{

	/**
	 * VideoProdID
	 * @var int
	 */
	public $VideoProdID;

	/**
	 * VideoReferenceID
	 * @var string
	 */
	public $VideoReferenceID;

	/**
	 * VideoTitle
	 * @var string
	 */
	public $VideoTitle;

	/**
	 * VideoSongTitle
	 * @var string
	 */
	public $VideoSongTitle;

	/**
	 * VideoArtistText
	 * @var string
	 */
	public $VideoArtistText;

	/**
	 * VideoArtist
	 * @var string
	 */
	public $VideoArtist;

	/**
	 * VideoAdvisory
	 * @var string
	 */
	public $VideoAdvisory;

	/**
	 * VideoISRC
	 * @var string
	 */
	public $VideoISRC;

	/**
	 * VideoComposer
	 * @var string
	 */
	public $VideoComposer;

	/**
	 * VideoGenre
	 * @var string
	 */
	public $VideoGenre;

	/**
	 * VideoDownloadStatus
	 * @var int
	 */
	public $VideoDownloadStatus;

	/**
	 * VideoSalesStatus
	 * @var int
	 */
	public $VideoSalesStatus;

	/**
	 * VideoFullLength_Duration
	 * @var string
	 */
	public $VideoFullLength_Duration;

	/**
	 * VideoFullLength_FileURL
	 * @var string
	 */
	public $VideoFullLength_FileURL;

	/**
	 * VideoImage_FileURL
	 * @var string
	 */
	public $VideoImage_FileURL;


	/**
	 * Constructor
	 */
	public function __construct(){

	}
}
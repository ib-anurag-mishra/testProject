<?php

class AlbumDataByArtist{
	/**
	 * albumdatabyartist
	 * @var AlbumDataByArtistType
	 */
	public $albumdatabyartist;

	/**
	 * Constructor
	 * @param AlbumDataByArtistType $albumdatabyartist
	 * @return bool
	 */
	public function __construct($albumdatabyartist){
		$this->albumdatabyartist = $albumdatabyartist;
		return true;
	}
}

class AlbumDataByArtistType{
	/**
	 * ProdID
	 * @var int
	 */
	public $ProdID;

	/**
	 * Genre
	 * @var string
	 */
	public $Genre;
	 
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
	 * Label
	 * @var string
	 */
	public $Label;

	/**
	 * FileURL
	 * @var string
	 */
	public $FileURL;

	/**
	 * Constructor
	 */
	public function __construct(){

	}
}
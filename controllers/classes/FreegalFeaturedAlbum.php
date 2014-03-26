<?php

class FreegalFeaturedAlbum{
	/**
	 * featured_album
	 * @var FreegalFeaturedAlbumType
	 */
	public $featured_album;

	/**
	 * Constructor
	 * @param FreegalFeaturedAlbumType $featured_album
	 * @return bool
	 */
	public function __construct($featured_album){
		$this->featured_album = $featured_album;
		return true;
	}
}

class FreegalFeaturedAlbumType{
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
	 * FileID
	 * @var string
	 */
	public $FileURL;
	/**
	 * FeaturedWebsiteTime
	 * @var string
	 */
	public $FeaturedWebsiteTime;

	/**
	 * Constructor
	 */
	public function __construct(){

	}
}
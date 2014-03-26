<?php

class WishlistData{
	/**
	 * wishlistdata
	 * @var WishlistDataType
	 */
	public $wishlistdata;

	/**
	 * Constructor
	 * @param WishlistDataType $wishlistdata
	 * @return bool
	 */
	public function __construct($wishlistdata){
		$this->wishlistdata = $wishlistdata;
		return true;
	}
}

class WishlistDataType{
	/**
	 * song_id
	 * @var int
	 */
	public $song_id;
	/**
	 * library_id
	 * @var int
	 */
	public $library_id;
	/**
	 * patron_id
	 * @var int
	 */
	public $patron_id;
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
	 * ISRC
	 * @var string
	 */
	public $ISRC;

	/**
	 * artist
	 * @var string
	 */
	public $artist;

	/**
	 * album
	 * @var string
	 */
	public $album;

	/**
	 * track_title
	 * @var string
	 */
	public $track_title;

	/**
	 * user_agent
	 * @var string
	 */
	public $user_agent;

	/**
	 * ip
	 * @var string
	 */
	public $ip;

	/**
	 * created
	 * @var string
	 */
	public $created;

	/**
	 * delete_on
	 * @var string
	 */
	public $delete_on;

	/**
	 * week_start_date
	 * @var string
	 */
	public $week_start_date;

	/**
	 * week_end_date
	 * @var string
	 */
	public $week_end_date;

	/**
	 * SongUrl
	 * @var string
	 */
	public $SongUrl;

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
	 * AlbumProdID
	 * @var int
	 */
	public $AlbumProdID;

	/**
	 * Constructor
	 */
	public function __construct(){

	}
}
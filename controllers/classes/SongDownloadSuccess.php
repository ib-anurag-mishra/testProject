<?php

class SongDownloadSuccess{
	/**
	 * songdownloadsuccess
	 * @var SongDownloadSuccessType
	 */
	public $songdownloadsuccess;

	/**
	 * Constructor
	 * @param SongDownloadSuccessType $songdownloadsuccess
	 * @return bool
	 */
	public function __construct($songdownloadsuccess){
		$this->songdownloadsuccess = $songdownloadsuccess;
		return true;
	}
}

class SongDownloadSuccessType{
	/**
	 * message
	 * @var string
	 */
	public $message;
	/**
	 * song_url
	 * @var string
	 */
	public $song_url;
	/**
	 * success
	 * @var bool
	 */
	public $success;
	/**
	 * currentDownloadCount
	 * @var int
	 */
	public $currentDownloadCount;
	/**
	 * totalDownloadLimit
	 * @var int
	 */
	public $totalDownloadLimit;

	/**
	 * showWishlist
	 * @var int
	 */
	public $showWishlist;

	/**
	 * Constructor
	 */
	public function __construct(){

	}
}
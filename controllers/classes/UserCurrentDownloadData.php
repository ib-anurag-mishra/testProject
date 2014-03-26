<?php

class UserCurrentDownloadData{
	/**
	 * usercurrentdownloaddata
	 * @var UserCurrentDownloadDataType
	 */
	public $usercurrentdownloaddata;

	/**
	 * Constructor
	 * @param UserCurrentDownloadDataType $usercurrentdownloaddata
	 * @return bool
	 */
	public function __construct($usercurrentdownloaddata){
		$this->usercurrentdownloaddata = $usercurrentdownloaddata;
		return true;
	}
}


class UserCurrentDownloadDataType{
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
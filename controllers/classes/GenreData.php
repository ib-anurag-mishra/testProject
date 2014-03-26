<?php

class GenreData {
	/**
	 * genredata
	 * @var GenreDataType
	 */
	public $genredata;

	/**
	 * Constructor
	 * @param GenreDataType $genredata
	 * @return bool
	 */
	public function __construct($genredata) {
		$this->genredata = $genredata;
		return true;
	}
}

class GenreDataType{
	/**
	 * GenreTitle
	 * @var string
	 */
	public $GenreTitle;

	/**
	 * Constructor
	 */
	public function __construct() {

	}
}
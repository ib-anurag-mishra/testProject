<?php

class PageContent{
	/**
	 * page_content_type
	 * @var PageContentType
	 */
	public $page_content_type;

	/**
	 * Constructor
	 * @param PageContentType $page_content_type
	 * @return bool
	 */
	public function __construct($page_content_type){
		$this->page_content_type = $page_content_type;
		return true;
	}
}

class PageContentType{
	/**
	 * id
	 * @var int
	 */
	public $id;

	/**
	 * page_name
	 * @var string
	 */
	public $page_name;

	/**
	 * page_content
	 * @var string
	 */
	public $page_content;

	/**
	 * language
	 * @var string
	 */
	public $language;
	/**
	 * created
	 * @var string
	 */
	public $created;
	/**
	 * modified
	 * @var string
	 */
	public $modified;

	/**
	 * Constructor
	 */
	public function __construct(){

	}
}
<?php

class VideoDownloadSuccess{
  /**
	 * videodownloadsuccess
	 * @var VideoDownloadSuccessType
	 */
  public $videodownloadsuccess;

  /**
	 * Constructor
	 * @param VideoDownloadSuccessType $videodownloadsuccess
	 * @return bool
	 */
  public function __construct($videodownloadsuccess){
    $this->videodownloadsuccess = $videodownloadsuccess;
    return true;
  }
}

class VideoDownloadSuccessType{
  /**
	 * message
	 * @var string
	 */
  public $message;
  /**
	 * video_url
	 * @var string
	 */
  public $video_url; 
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
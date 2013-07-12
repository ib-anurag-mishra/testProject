<?php

class QueueDetailData{
  /**
	 * queuedetaildata
	 * @var QueueDetailDataType
	 */
  public $queuedetaildata;

  /**
	 * Constructor
	 * @param QueueDetailDataType $queuedetaildata
	 * @return bool
	 */
  public function __construct($queuedetaildata){
    $this->queuedetaildata = $queuedetaildata;
    return true;
  }
}        
        
class QueueDetailDataType{
  /**
	 * QueueName
	 * @var string
	 */
  public $QueueName;
  /**
	 * QueueSongSongTitle
	 * @var string
	 */
  public $QueueSongSongTitle;  
  /**
	 * QueueSongTitle
	 * @var string
	 */
  public $QueueSongTitle;  
  /**
	 * QueueSongArtistText
	 * @var string
	 */
  public $QueueSongArtistText;  
  /**
	 * QueueSongArtist
	 * @var string
	 */
  public $QueueSongArtist; 
  /**
	 * QueueSongFullLengthURL
	 * @var string
	 */
  public $QueueSongFullLengthURL;
  /**
	 * QueueAlbumProductID
	 * @var string
	 */
  public $QueueAlbumProductID;
  /**
	 * QueueAlbumTitle
	 * @var string
	 */
  public $QueueAlbumTitle;
  /**
	 * QueueAlbumAlbumTitle
	 * @var string
	 */
  public $QueueAlbumAlbumTitle;
    /**
	 * QueueAlbumImage
	 * @var string
	 */
  public $QueueAlbumImage;
  /**
	 * Constructor
	 */
  public function __construct(){
  
  }
}
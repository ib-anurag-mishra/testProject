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
	 * QueueAlbumProdID
	 * @var string
	 */
  public $QueueAlbumProdID;
    /**
	 * QueueSongProdID
	 * @var string
	 */
  public $QueueSongProdID;
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
         * QueueFullLength_Duration
         * @var string
         */
  public $QueueFullLength_Duration;


  /**
	 * Constructor
	 */
  public function __construct(){
  
  }
}

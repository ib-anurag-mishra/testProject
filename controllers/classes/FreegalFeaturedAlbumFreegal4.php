<?php

class FreegalFeaturedAlbumFreegal4{
  /**
	 * featured_album_freegal4
	 * @var FreegalFeaturedAlbumFreegal4Type
	 */
  public $featured_album_freegal4;

  /**
	 * Constructor
	 * @param FreegalFeaturedAlbumFreegal4Type $featured_album_freegal4
	 * @return bool
	 */
  public function __construct($featured_album_freegal4){
    $this->featured_album_freegal4 = $featured_album_freegal4;
    return true;
  }
}

class FreegalFeaturedAlbumFreegal4Type{
  /**
	 * AlbumProdId
	 * @var int
	 */
  public $AlbumProdId;
  /**
	 * AlbumTitle
	 * @var string
	 */
  public $AlbumTitle;
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
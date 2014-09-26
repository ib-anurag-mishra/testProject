<?php

Configure::write('debug', 0); 

App::import('Model', 'AuthenticationToken');
App::import('Model', 'Zipusstate');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'FreegalLibrary.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'NationalTopTen.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'TopSingles.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'LibraryTopTen.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'AlbumData.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'SongData.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'VideoSongData.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'WishlistData.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'QueueDetailData.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'QueueListData.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'UserCurrentDownloadData.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'AuthenticationResponseData.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'UserData.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'SuccessResponse.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'StreamingResponse.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'QueueOperation.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'UserTypeResponse.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'SongDownloadSuccess.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'VideoDownloadSuccess.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'FreegalFeaturedAlbum.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'FreegalFeaturedAlbumFreegal4.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'SearchData.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'PageContent.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'AlbumDataByArtist.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'GenreData.php');
class SoapsController extends AppController {

  private $uri = 'http://www.freegalmusic.com/';
  private $artist_image_base_url = 'http://music.libraryideas.com/freegalmusic/prod/EN/artistimg/';
  private $library_search_radius = 60;
  private $CDN_HOST = 'libraryideas.ingest.cdn.level3.net';
  private $CDN_USER = 'libraryideas';
  private $CDN_PASS = 't837dgkZU6xCMnc';
  private $encodingKey = 'bGlicmFyeWlkZWFzMjAxNA==';

  private $authenticated = false;
  var $uses = array('User','Library','Download','Song','Wishlist','Album','Url','Language','Credentials','Files', 'Zipusstate', 'Artist', 'Genre','AuthenticationToken','Country','Card','Currentpatron','Product', 'DeviceMaster', 'LibrariesTimezone', 'LatestDownload', 'Video', 'LatestVideodownload', 'Videodownload', 'QueueList', 'QueueDetail', 'Featuredartist', 'File_mp4','Token','TopAlbum'); 
  var $components = array('Downloads', 'AuthRequest', 'Downloadsvideos', 'Streaming', 'Solr', 'Queue','Common'); 

  
  function index(){
    
    Configure::write('debug',0);
    $this->autoRender = false;
    ini_set("soap.wsdl_cache_enabled", "0");
    $server = new SoapServer(null,array('uri'=>$this->uri));
    $server->setObject($this);
    $server->handle();
  }


  function wsdl(){
  
    Configure::write('debug',0);
    $this->autoRender = false;
    $siteUrl = Configure::read('App.base_url');
    ini_set("soap.wsdl_cache_enabled", "0");
    App::import("Vendor","php2wsdl",array('file' => "php2wsdl".DS."WSDLCreator.php"));
    App::import("Vendor","xmlcreator",array('file' => "php2wsdl".DS."XMLCreator.php"));
    $test = new WSDLCreator("FreegalWebServices", $siteUrl);
    $test->includeMethodsDocumentation(false);
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."FreegalLibrary.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."NationalTopTen.php");
	$test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."TopSingles.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."LibraryTopTen.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."AlbumData.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."AlbumDataByArtist.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."GenreData.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."SongData.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."VideoSongData.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."WishlistData.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."QueueListData.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."QueueDetailData.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."UserCurrentDownloadData.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."AuthenticationResponseData.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."UserData.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."SuccessResponse.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."StreamingResponse.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."QueueOperation.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."UserTypeResponse.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."SongDownloadSuccess.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."VideoDownloadSuccess.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."FreegalFeaturedAlbum.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."FreegalFeaturedAlbumFreegal4.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."SearchData.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."classes".DS."PageContent.php");
    $test->addFile(ROOT.DS.APP_DIR.DS."controllers".DS."soaps_controller.php");
    $test->setTnsUrl($siteUrl);
    $test->setClassesGeneralURL($siteUrl);
    $test->addURLToClass("FreegalLibrary", $siteUrl."soaps/");
    $test->addURLToClass("NationalTopTen", $siteUrl."soaps/");
	$test->addURLToClass("TopSingles", $siteUrl."soaps/");
    $test->addURLToClass("LibraryTopTen", $siteUrl."soaps/");
    $test->addURLToClass("AlbumData", $siteUrl."soaps/");
    $test->addURLToClass("AlbumDataByArtist", $siteUrl."soaps/");
    $test->addURLToClass("GenreData", $siteUrl."soaps/");
    $test->addURLToClass("SongData", $siteUrl."soaps/");
    $test->addURLToClass("VideoSongData", $siteUrl."soaps/");
    $test->addURLToClass("WishlistData", $siteUrl."soaps/");
    $test->addURLToClass("QueueListData", $siteUrl."soaps/");
    $test->addURLToClass("QueueDetailData", $siteUrl."soaps/");
    $test->addURLToClass("UserCurrentDownloadData", $siteUrl."soaps/");
    $test->addURLToClass("AuthenticationResponseData", $siteUrl."soaps/");
    $test->addURLToClass("UserData", $siteUrl."soaps/");
    $test->addURLToClass("SuccessResponse", $siteUrl."soaps/");
    $test->addURLToClass("StreamingResponse", $siteUrl."soaps/");
    $test->addURLToClass("QueueOperation", $siteUrl."soaps/");
    $test->addURLToClass("UserTypeResponse", $siteUrl."soaps/");
    $test->addURLToClass("SongDownloadSuccess", $siteUrl."soaps/");
    $test->addURLToClass("VideoDownloadSuccess", $siteUrl."soaps/");
    $test->addURLToClass("FreegalFeaturedAlbum", $siteUrl."soaps/");
    $test->addURLToClass("FreegalFeaturedAlbumFreegal4", $siteUrl."soaps/");
    $test->addURLToClass("SearchData", $siteUrl."soaps/");
    $test->addURLToClass("PageContent", $siteUrl."soaps/");
    $test->addURLToClass("SoapsController", $siteUrl."soaps/");
    $test->ignoreMethod(array("SoapsController"=>"index"));
    $test->ignoreMethod(array("SoapsController"=>"wsdl"));
    $test->ignoreMethod(array("SoapsController"=>"authenticate"));
    $test->createWSDL();
    $test->printWSDL(true);
  }


  /**
   * Function Name : getLibrary
   * Desc : To get the libraries listing by zipcode
   * @param string $data
	 * @return FreegalLibraryType[]
   */
	function getLibrary($data) {

    $siteUrl = Configure::read('App.base_url');
    $list = array();
    $keys = array('LibraryID','LibraryName','LibraryApiKey','LibraryAuthenticationVariable','LibraryAuthenticationMethod','LibraryAuthenticationNum','LibraryAuthenticationUrl','LibraryAuthenticationResponse');
    if(is_numeric($data)){
        $zipcode = trim($data);
        $result = null;
        
            if(strlen($zipcode) == 5){
                   
                App::import('vendor', 'zipcode_class', array('file' => 'zipcode.php'));
                $obj_zipcode = new zipcode_class();  
                      
                $result = $obj_zipcode->get_zips_in_range($zipcode, $this->library_search_radius, _ZIPS_SORT_BY_DISTANCE_ASC, true);
                if( empty($result) ){
                  throw new SOAPFault('Soap:client', 'No library available for current location. Please try with other location.');
                }
                $this->Library->recursive = -1 ;
                $condition = implode("',library_zipcode) OR find_in_set('",explode(',',$result));
                $libraries = $this->Library->find('all',array(
                  'conditions' => array(
                    'library_status'=>'active',
                    'OR'=>array("substring(library_zipcode,1,5) in ($result)","find_in_set('".$condition."',library_zipcode)")
                  )
                ));                     

                if(!empty($libraries)){
                    
                    $list = array();
                    foreach($libraries as $library){

                        if( ('referral_url' == $library['Library']['library_authentication_method'] || 'ezproxy' == $library['Library']['library_authentication_method']) && ('' == trim($library['Library']['mobile_auth'])) ) {

                        } else { 

                          $obj = new FreegalLibraryType;
                          $obj->LibraryId = (int)$library['Library']['id'];
                          $obj->LibraryName = $library['Library']['library_name'];
                          $obj->LibraryApiKey = $library['Library']['library_apikey'];

                          $identifier = $this->getLibraryIdentefierByLibraryMethod($library['Library']['library_authentication_method']);
                          $obj->LibraryAuthenticationMethod = $identifier;

                          $auth_url = trim(strtolower($library['Library']['mobile_auth']));
                          if( ('referral_url' == $library['Library']['library_authentication_method'] || 'ezproxy' == $library['Library']['library_authentication_method']) && (false === stripos($auth_url, '=pin')) && ('' != $auth_url) ) {
                            $obj->LibraryAuthenticationNum = 1;
                          } else {
                            $obj->LibraryAuthenticationNum = 0;
                          }

                          $obj->LibraryAuthenticationUrl = $library['Library']['library_authentication_url'];

                          $list[] = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'FreegalLibraryType');
                        }                        
                   }

                   if(!empty($list)){
                      $data = new SoapVar($list,SOAP_ENC_OBJECT,null,null,'ArrayOfFreegalLibraryType');
                      return $data;
                   }
                   else{
                      throw new SOAPFault('Soap:client', 'No library available for current location. Please try with other location.');
                   }
                } 
                else {
                    throw new SOAPFault('Soap:client', 'No library available for current location. Please try with other location.');
                }

            } else {
                throw new SOAPFault('Soap:client', 'Invalid Zip Code. Please provide a valid code.');
            }
    } else {

      $data = strtolower(trim($data));

      if( 5 > strlen($data)) {
        throw new SOAPFault('Soap:client', 'No library available for current location. Please try with other location.');
      }

      $pos1 = stripos('United States', $data);
      if ($pos1 !== false) {
        throw new SOAPFault('Soap:client', 'No library available for current location. Please try with other location.');
      }


      $libraries = $this->Library->find('all',
        array('fields' =>
          array(
            'Library.id',
            'library_name',
            'library_apikey',
            'library_authentication_variable',
            'library_authentication_method',
            'library_authentication_num',
            'library_authentication_url',
            'library_authentication_response',
            'mobile_auth'
          ),
          'conditions' => array(
           'AND' => array(
              'OR'=>array(
                'Library.library_city LIKE' => '%' . $data . '%',
                'Library.library_state LIKE' => '%' . $data . '%',
                'Library.library_country LIKE' => '%' . $data . '%'
              ),
            ),
            'library_status'=>'active'
          ),
          'order' => array('Library.library_name' => 'ASC'),
        )
      );

      if(empty($libraries)){
        throw new SOAPFault('Soap:client', 'No library available for current location. Please try with other location.');
      }

      $list = array();
      foreach($libraries as $library){
        
        if( ('referral_url' == $library['Library']['library_authentication_method'] || 'ezproxy' == $library['Library']['library_authentication_method']) && ('' == trim($library['Library']['mobile_auth'])) ) {

        } else {
        
          $obj = new FreegalLibraryType;
          $obj->LibraryId = (int)$library['Library']['id'];
          $obj->LibraryName = $library['Library']['library_name'];
          $obj->LibraryApiKey = $library['Library']['library_apikey'];
          $identifier = $this->getLibraryIdentefierByLibraryMethod($library['Library']['library_authentication_method']);
          $obj->LibraryAuthenticationMethod = $identifier;

          $auth_url = trim(strtolower($library['Library']['mobile_auth']));
          if( ('referral_url' == $library['Library']['library_authentication_method'] || 'ezproxy' == $library['Library']['library_authentication_method']) && (false === stripos($auth_url, '=pin')) && ('' != $auth_url) ) {
            $obj->LibraryAuthenticationNum = 1;
          } else {
            $obj->LibraryAuthenticationNum = 0;
          }

          $obj->LibraryAuthenticationUrl = $library['Library']['library_authentication_url'];

          $list[] = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'FreegalLibraryType');
        
        }
        
      }

      return new SoapVar($list,SOAP_ENC_OBJECT,null,null,'ArrayOfFreegalLibraryType');


    }


  }


  /**
   * Function Name : getAlbumsFromArtistText
   * Desc : To get the albums list from artistText
   * @param string $authenticationToken
   * @param string $artistText
	 * @return AlbumDataByArtistType[]
   */
	function getAlbumsFromArtistText($authenticationToken, $artistText, $startFrom, $recordCount) {

    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }

    $libraryId = $this->getLibraryIdFromAuthenticationToken($authenticationToken);

    $libraryDetails = $this->Library->find('first',array(
      'conditions' => array('Library.id' => $libraryId),
      'fields' => array('library_territory', 'library_block_explicit_content'),
      'recursive' => -1
    ));

    $library_territory = $libraryDetails['Library']['library_territory'];
    $this->Session->write('territory', $library_territory);   
    $this->switchCpuntriesTable();
      
    $mem_artistText = strtolower(str_replace(' ', '_', $artistText));
    
    $albumDataCache = Cache::read('mobile_top_artist_' . $mem_artistText . '_' . $library_territory);
    if ( (($albumDataCache) === false) || ($albumDataCache === null) ) {
    
    
      if(1 == $libraryDetails['Library']['library_block_explicit_content']) {
        $cond = array('Song.Advisory' => 'F');
      }
      else{
        $cond = "";
      }
  
      $songs = $this->Song->find('all', array(
				'fields' => array('DISTINCT Song.ReferenceID', 'Song.provider_type', 'Country.SalesDate'),
				'conditions' => array(
          'LOWER(Song.ArtistText)' => strtolower($artistText),
          "Song.Sample_FileID != ''",
          "Song.FullLength_FIleID != ''" ,
          'Country.Territory' => $library_territory, 
          'Country.DownloadStatus' => 1, 
          $cond, 
          'Song.provider_type = Country.provider_type'
        ),
        'contain' => array(
          'Country' => array(
            'fields' => array(
              'Country.Territory'
            )
          )
        ), 
        'recursive' => 0,
        'order'=>array('Country.SalesDate DESC')        
      ));
    
      $val = '';
      $val_provider_type = '';
          
      foreach($songs as $k => $v){
        if (empty($val)) {
          $val .= $v['Song']['ReferenceID'];
          $val_provider_type .= "(" . $v['Song']['ReferenceID'] . ",'" . $v['Song']['provider_type'] . "')";
        } else {
          $val .= ',' . $v['Song']['ReferenceID'];
          $val_provider_type .= ',' . "(" . $v['Song']['ReferenceID'] . ",'" . $v['Song']['provider_type'] . "')";
        }
      }
      
      $condition = array("(Album.ProdID, Album.provider_type) IN (".rtrim($val_provider_type,",").") AND Album.provider_type = Genre.provider_type");
    		
      $albumData = $this->Album->find('all',array('conditions' =>
					array('and' =>
						array(
              $condition
						), "1 = 1 GROUP BY Album.ProdID, Album.provider_type"
					),
					'fields' => array(
						'Album.ProdID',
						'Album.Title',
						'Album.ArtistText',
						'Album.AlbumTitle',
						'Album.Artist',
						'Album.ArtistURL',
						'Album.Label',
						'Album.Copyright',
            'Album.Advisory',
						'Album.provider_type'
						),
					'contain' => array(
						'Genre' => array(
							'fields' => array(
								'Genre.Genre'
								)
							),
						'Files' => array(
							'fields' => array(
								'Files.CdnPath' ,
								'Files.SaveAsName',
								'Files.SourceURL'
							),
						)
					),
          'order' => array('FIELD(Album.ProdID, '.$val.') ASC'), 
          'chk' => 2,
         // 'cache' => 'yes'
		  ));
      
      if(empty($albumData)) {
        
        throw new SOAPFault('Soap:client', 'Freegal is unable to find Album for the Artist.');
      } else {
        
        Cache::write('mobile_top_artist_' . $mem_artistText . '_' . $library_territory, $albumData);
		$albumDataCache = Cache::read('mobile_top_artist_' . $mem_artistText . '_' . $library_territory);
	     }
    } 
    
	 
    $albumData = $albumDataCache;
    
    if(empty($albumData)) {
      throw new SOAPFault('Soap:client', 'Freegal is unable to find Album for the Artist.');
    }
      
      
    for( $cnt = $startFrom; $cnt < ($startFrom+$recordCount); $cnt++  ) {
      if(!(empty($albumData[$cnt]))) {
          $obj = new AlbumDataByArtistType;

          $obj->ProdID         = $this->getProductAutoID($albumData[$cnt]['Album']['ProdID'], $albumData[$cnt]['Album']['provider_type']);
          $obj->Genre          = $this->getTextUTF($albumData[$cnt]['Genre']['Genre']);
          $obj->AlbumTitle     = $this->getTextUTF($albumData[$cnt]['Album']['AlbumTitle']);
          $obj->Title          = $this->getTextUTF($albumData[$cnt]['Album']['Title']);
          $obj->Label          = $this->getTextUTF($albumData[$cnt]['Album']['Label']);

          $fileURL = $this->Token->regularToken( $albumData[$cnt]['Files']['CdnPath']."/".$albumData[$cnt]['Files']['SourceURL']);
          $fileURL = Configure::read('App.Music_Path').$fileURL;
          $obj->FileURL = $fileURL;

          if('T' == $albumData[$cnt]['Album']['Advisory']) { $obj->AlbumTitle = $obj->AlbumTitle.' (Explicit)'; $obj->Title = $obj->Title.' (Explicit)'; }
          
          $list[] = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'AlbumDataByArtistType');
      }
    }
    
    
    
    return new SoapVar($list,SOAP_ENC_OBJECT,null,null,'ArrayOfAlbumDataByArtistType');


  }


  /**
   * Function Name : getFeaturedAlbum
   * Desc : To get the featured artist
   * @param string $authenticationToken
   * @param string $append
	 * @return FreegalFeaturedAlbumType[]
   */
	function getFeaturedAlbum($authenticationToken, $append) {

    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }

    $libraryId = $this->getLibraryIdFromAuthenticationToken($authenticationToken);
    $library_terriotry = $this->getLibraryTerritory($libraryId);

    $artistCache = Cache::read("ssartists_".$library_terriotry.'_EN');
    if ( (($artistCache) === false)  || ($artistCache === null) ) {

      $Artist = $this->Artist->find('all',
        array(
          'fields'=>array(
            'Artist.artist_name',
            'Artist.artist_image',
            'Artist.territory',
            'Artist.language'
          ),
          'conditions'=>array(
            'Artist.territory' => $library_terriotry,
            'Artist.language'=> 'EN'
          ),
          'recursive' => -1,
          'limit' => 6
        )
      );

      Cache::write("ssartists_".$library_terriotry.'_EN', $Artist);

    } else {
      $Artist = $artistCache;
    }


    if(!(empty($Artist))) {

      foreach($Artist AS $key1 => $val1) {

        $obj = new FreegalFeaturedAlbumType;
        $obj->ProdId          = '';
        $obj->ProductId       = '';
        $obj->AlbumTitle      = '';
        $obj->Title           = '';
        $obj->ArtistText      = $this->getTextUTF($val1['Artist']['artist_name']);
        $obj->Artist          = '';
        $obj->ArtistURL       = '';
        $obj->Label           = '';
        $obj->FeaturedWebsiteTime           = '';

        if('' != trim($append)) {
          list($name, $ext) = explode('.', $val1['Artist']['artist_image']);
          $obj->FileURL = $this->artist_image_base_url . $name . '_' . $append . '.' . $ext;
        } else {
          $obj->FileURL = $this->artist_image_base_url . $val1['Artist']['artist_image'];
        }

        $list[] = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'FreegalAlbumDetailType');


      }

      $data = new SoapVar($list,SOAP_ENC_OBJECT,null,null,'ArrayOfFreegalAlbumDetailType');

      return $data;

    } else {

      throw new SOAPFault('Soap:client', 'No featured albums found for your library.');
    }

	}

  /**
   * Function Name : getFeaturedAlbumFreegal4
   * Desc : To get the featured albums for FReegal4.O
   * @param string $authenticationToken
	 * @return FreegalFeaturedAlbumFreegal4Type[]
   */
	function getFeaturedAlbumFreegal4($authenticationToken) {
  
    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }

    $libraryId = $this->getLibraryIdFromAuthenticationToken($authenticationToken);
    $library_territory = $this->getLibraryTerritory($libraryId);	
    $featuredCache = Cache::read("top_albums".$library_territory);
    if (($artists = $featuredCache) === false || $featuredCache == null) {
      	$featured = $this->Common->getTopAlbums($library_territory);
    }
    else {    
    	$featured = $featuredCache;
	}
    
    if(empty($featured)){
      throw new SOAPFault('Soap:client', 'No top albums found for your library.');
    }
 
    foreach($featured as $key => $val) {
      
      $obj = new FreegalFeaturedAlbumFreegal4Type;
      $obj->AlbumProdId      = $this->getProductAutoID($val['Album']['ProdID'], $val['Album']['provider_type']);
      $obj->AlbumTitle       = $this->getTextUTF($val['Album']['AlbumTitle']);
      $obj->FileURL          = $val['topAlbumImage'];
      
      if('T' == $val['Album']['Advisory']) { $obj->AlbumTitle = $obj->AlbumTitle.' (Explicit)'; }
      
      
      $list[] = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'FreegalFeaturedAlbumFreegal4Type');
    }
    
    return new SoapVar($list,SOAP_ENC_OBJECT,null,null,'ArrayOfFreegalFeaturedAlbumFreegal4Type');

  }

  /**
   * Function Name : getFeaturedArtistSlides
   * Desc : To get the featured artist slides show
   * @param string $authenticationToken
   * @param string $append
   * @param string $featured_mobile_time
	 * @return FreegalFeaturedAlbumType[]
   */
	function getFeaturedArtistSlides($authenticationToken, $append, $featured_mobile_time) {

    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }

    $libraryId = $this->getLibraryIdFromAuthenticationToken($authenticationToken);
    $library_terriotry = $this->getLibraryTerritory($libraryId);

    $artistCache = Cache::read("ssartists_".$library_terriotry.'_EN');
    if ( (($artistCache) === false)  || ($artistCache === null) ) {

      $Artist = $this->Artist->find('all',
        array(
          'fields'=>array(
            'Artist.artist_name',
            'Artist.artist_image',
            'Artist.territory',
            'Artist.language'
          ),
          'conditions'=>array(
            'Artist.territory' => $library_terriotry,
            'Artist.language'=> 'EN'
          ),
          'recursive' => -1,
          'limit' => 6
        )
      );

      Cache::write("ssartists_".$library_terriotry.'_EN', $Artist);

    } else {
      $Artist = $artistCache;
    }


    $updateSsdateMobile = Cache::read('update_ssdate_mobile');
    if ( (($updateSsdateMobile) === false)  || ($updateSsdateMobile === null) ) {

      Cache::write('update_ssdate_mobile', date('d/m/Y/H/i/s',time()));
    }



    if('' == trim($featured_mobile_time)) {

      if(!(empty($Artist))) {

        foreach($Artist AS $key1 => $val1) {

          $obj = new FreegalFeaturedAlbumType;
          $obj->ProdId          = '';
          $obj->ProductId       = '';
          $obj->AlbumTitle      = '';
          $obj->Title           = '';
          $obj->ArtistText      = $this->getTextUTF($val1['Artist']['artist_name']);
          $obj->Artist          = '';
          $obj->ArtistURL       = '';
          $obj->Label           = '';

          if('' != trim($append)) {
            list($name, $ext) = explode('.', $val1['Artist']['artist_image']);
            $obj->FileURL = $this->artist_image_base_url . $name . '_' . $append . '.' . $ext;
          } else {
            $obj->FileURL = $this->artist_image_base_url . $val1['Artist']['artist_image'];
          }

          $obj->FeaturedWebsiteTime = Cache::read('update_ssdate_mobile');


          $list[] = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'FreegalAlbumDetailType');

        }

        $data = new SoapVar($list,SOAP_ENC_OBJECT,null,null,'ArrayOfFreegalAlbumDetailType');

        return $data;

      } else {

        throw new SOAPFault('Soap:client', 'No featured albums found for your library.');
      }

    }


    $arrTmp = explode('/', Cache::read('update_ssdate_mobile'));
    $featured_website_timestamp = (int) strtotime($arrTmp[0].'-'.$arrTmp[1].'-'.$arrTmp[2].' '.$arrTmp[3].':'.$arrTmp[4].':'.$arrTmp[5]);

    $arrTmp = explode('/', $featured_mobile_time);
    $featured_mobile_timestamp = (int) strtotime($arrTmp[0].'-'.$arrTmp[1].'-'.$arrTmp[2].' '.$arrTmp[3].':'.$arrTmp[4].':'.$arrTmp[5]);




    if($featured_mobile_timestamp == $featured_website_timestamp) {

      $obj = new FreegalFeaturedAlbumType;
      $obj->ProdId          = '';
      $obj->ProductId       = '';
      $obj->AlbumTitle      = '';
      $obj->Title           = '';
      $obj->ArtistText      = '';
      $obj->Artist          = '';
      $obj->ArtistURL       = '';
      $obj->Label           = '';
      $obj->FileURL         = '';
      $obj->FeaturedWebsiteTime = '';
      $data = new SoapVar($list,SOAP_ENC_OBJECT,null,null,'ArrayOfFreegalAlbumDetailType');

      return $data;

    }



    if($featured_mobile_timestamp < $featured_website_timestamp) {

      if(!(empty($Artist))) {

        foreach($Artist AS $key1 => $val1) {

          $obj = new FreegalFeaturedAlbumType;
          $obj->ProdId          = '';
          $obj->ProductId       = '';
          $obj->AlbumTitle      = '';
          $obj->Title           = '';
          $obj->ArtistText      = $this->getTextUTF($val1['Artist']['artist_name']);
          $obj->Artist          = '';
          $obj->ArtistURL       = '';
          $obj->Label           = '';

          if('' != trim($append)) {
            list($name, $ext) = explode('.', $val1['Artist']['artist_image']);
            $obj->FileURL = $this->artist_image_base_url . $name . '_' . $append . '.' . $ext;
          } else {
            $obj->FileURL = $this->artist_image_base_url . $val1['Artist']['artist_image'];
          }

          $obj->FeaturedWebsiteTime = Cache::read('update_ssdate_mobile');

          $list[] = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'FreegalAlbumDetailType');

        }

        $data = new SoapVar($list,SOAP_ENC_OBJECT,null,null,'ArrayOfFreegalAlbumDetailType');

        return $data;

      } else {

        throw new SOAPFault('Soap:client', 'No featured albums found for your library.');
      }

    }

	}
  

  /**
   * Function Name : getNationalTopTen
   * Desc : To get the national top ten songs
   * @param string $authenticationToken
   * @param int $libraryId
	 * @return NationalTopTenType[]
   */
	function getNationalTopTen($authenticationToken, $libraryId) {

    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }

    $libraryData = $this->Library->find('first', array('conditions' => array('AND'=>array('Library.id' => $libraryId, 'library_status' => 'active')), 'fields' => array('library_territory')));
    $territory = $libraryData['Library']['library_territory'];

    $nationalTopDownloadTmp = Cache::read("top_singles".$territory);
	
	if ($nationalTopDownloadTmp === false || $nationalTopDownloadTmp == null) {
            $nationalTopDownloadTmp = $this->Common->getTopSingles($territory);
    }

    $nationalTopDownload = array_splice($nationalTopDownloadTmp,0,10);
    
    
    if(!(empty($nationalTopDownload))) {

      foreach($nationalTopDownload as $key => $data) {

          $obj = new NationalTopTenType;
          
          $obj->ProdId                    = (int) $data['PRODUCT']['pid'];
          $obj->ProductId                 = (string)'';
          $obj->ReferenceId               = (int)$this->getProductAutoID($data['Song']['ReferenceID'], $data['Song']['provider_type']);
          $obj->Title                     = $this->getTextUTF((string)$data['Song']['Title']);
          $obj->SongTitle                 = $this->getTextUTF((string)$data['Song']['SongTitle']);
          $obj->ArtistText                = $this->getTextUTF((string)$data['Song']['ArtistText']);
          $obj->Artist                    = $this->getTextUTF((string)$data['Song']['Artist']);
          $obj->ISRC                      = (string)'';
          $obj->Composer                  = (string)'';
          $obj->Genre                     = $this->getTextUTF((string)$data['Genre']['Genre']);
          $obj->Territory                 = (string)$data['Country']['Territory'];
          $obj->Sample_Duration           = $this->getSongDurationTime($data['Song']['Sample_Duration']);
          $obj->FullLength_Duration       = $this->getSongDurationTime($data['Song']['FullLength_Duration']);
          $this->Album->recursive = -1;
          $album = $this->Album->find('first',array('fields' => array('AlbumTitle'),'conditions' => array("ProdId = ".$data['Song']['ReferenceID'], "provider_type" => $data['Song']['provider_type'])));
          $obj->AlbumTitle = $this->getTextUTF($album['Album']['AlbumTitle']);
          $fileURL = $this->Token->regularToken( $data['Sample_Files']['CdnPath']."/".$data['Sample_Files']['SaveAsName']);
          $fileURL = Configure::read('App.Music_Path').$fileURL;
          
          if($this->IsDownloadable($data['Song']['ProdID'], $territory, $data['Song']['provider_type'])) {
            $obj->fileURL                 = 'nostring';
            $obj->FullLength_FIleURL      = 'nostring';
          } else {
            $obj->fileURL                 = (string)$fileURL;
            $obj->FullLength_FIleURL      = Configure::read('App.Music_Path').$this->Token->regularToken( $data['Full_Files']['CdnPath']."/".$data['Full_Files']['SaveAsName']);
          }
          
          $obj->FullLength_FIleID         = (int)$data['Full_Files']['FileID'];
          
          $obj->playButtonStatus          = $this->getPlayButtonStatus($data['Song']['ProdID'], $territory, $data['Song']['provider_type']);         
          
          if('T' == $data['Song']['Advisory']) $obj->SongTitle = $obj->SongTitle.' (Explicit)';
          
          $list[] = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'NationalTopTenType');

      }

      $data = new SoapVar($list,SOAP_ENC_OBJECT,null,null,'ArrayOfNationalTopTenType');

      return $data;

    } else {

      throw new SOAPFault('Soap:client', 'NationalTopTen list is empty');
    }

  }

/**
   * Function Name : getTopsingles
   * Desc : To get the twenty top singles
   * @param string $authenticationToken
   * @param int $libraryId
   * @return TopSinglesType[]
*/
	function getTopSingles($authenticationToken, $libraryId) {

    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }

    $libraryData = $this->Library->find('first', array('conditions' => array('AND'=>array('Library.id' => $libraryId, 'library_status' => 'active')), 'fields' => array('library_territory')));
    $territory = $libraryData['Library']['library_territory'];

    $topSinglesTmp = Cache::read("top_singles".$territory);
	if($topSinglesTmp === false) {
		$topSinglesTmp = $this->Common->getTopSingles($territory);
	}    
	
    $topSingles = array_splice($topSinglesTmp,0,20);
        
    if(!(empty($topSingles))) {

      foreach($topSingles as $key => $data) {

          $obj = new TopSinglesType;
          
          $obj->ProdId                    = (int) $data['PRODUCT']['pid'];
          $obj->ProductId                 = (string)'';
          $obj->ReferenceId               = (int)$this->getProductAutoID($data['Song']['ReferenceID'], $data['Song']['provider_type']);
          $obj->Title                     = $this->getTextUTF((string)$data['Song']['Title']);
          $obj->SongTitle                 = $this->getTextUTF((string)$data['Song']['SongTitle']);
          $obj->ArtistText                = $this->getTextUTF((string)$data['Song']['ArtistText']);
          $obj->Artist                    = $this->getTextUTF((string)$data['Song']['Artist']);
          $obj->ISRC                      = (string)'';
          $obj->Composer                  = (string)'';
          $obj->Genre                     = $this->getTextUTF((string)$data['Genre']['Genre']);
          $obj->Territory                 = (string)$data['Country']['Territory'];
          $obj->Sample_Duration           = $this->getSongDurationTime($data['Song']['Sample_Duration']);
          $obj->FullLength_Duration       = $this->getSongDurationTime($data['Song']['FullLength_Duration']);
          $this->Album->recursive = -1;
          $album = $this->Album->find('first',array('fields' => array('AlbumTitle'),'conditions' => array("ProdId = ".$data['Song']['ReferenceID'], "provider_type" => $data['Song']['provider_type'])));
          $obj->AlbumTitle = $this->getTextUTF($album['Album']['AlbumTitle']);
          $fileURL = $this->Token->regularToken( $data['Sample_Files']['CdnPath']."/".$data['Sample_Files']['SaveAsName']);
          $fileURL = Configure::read('App.Music_Path').$fileURL;
          
          if($this->IsDownloadable($data['Song']['ProdID'], $territory, $data['Song']['provider_type'])) {
            $obj->fileURL                 = 'nostring';
            $obj->FullLength_FIleURL      = 'nostring';
          } else {
            $obj->fileURL                 = (string)$fileURL;
            $obj->FullLength_FIleURL      = Configure::read('App.Music_Path').$this->Token->regularToken( $data['Full_Files']['CdnPath']."/".$data['Full_Files']['SaveAsName']);
          }
          
          $obj->FullLength_FIleID         = (int)$data['Full_Files']['FileID'];
          
          $obj->playButtonStatus          = $this->getPlayButtonStatus($data['Song']['ProdID'], $territory, $data['Song']['provider_type']);         
          
          if('T' == $data['Song']['Advisory']) $obj->SongTitle = $obj->SongTitle.' (Explicit)';
          
          $list[] = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'TopSinglesType');

      }

      $data = new SoapVar($list,SOAP_ENC_OBJECT,null,null,'ArrayOfTopSinglesType');

      return $data;

    } else {

      throw new SOAPFault('Soap:client', 'Top Singles list is empty');
    }
  }


  /**
   * Function Name : getLibraryTopTen
   * Desc : To get the library top ten songs
   * @param string $authenticationToken
   * @param int $libraryId
	 * @return LibraryTopTenType[]
   */
	function getLibraryTopTen($authenticationToken, $libraryId) {

    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }

    $libraryDetails = $this->Library->find('first',array(
      'conditions' => array('Library.id' => $libraryId),
      'fields' => array('library_territory'),
      'recursive' => -1
      )
    );
    $library_territory = $libraryDetails['Library']['library_territory'];

    $topDownloadCache = Cache::read("lib".$libraryId);
    
	if( $topDownloadCache === false || $topDownloadCache == null ){
		$topDownload = $this->Common->getLibraryTopTenSongs($library_territory, $libraryId);
	}
	else {
		$topDownload = $topDownloadCache;
	}

    if(!(empty($topDownload))) {

      foreach($topDownload as $data) {

          $obj = new LibraryTopTenType;
          $obj->ProdId                    = (int)$data['PRODUCT']['pid'];
          $obj->ProductId                 = (string)$data['Song']['ProductID'];
          $obj->ReferenceId               = (int)$this->getProductAutoID($data['Song']['ReferenceID'], $data['Song']['provider_type']);
          $obj->Title                     = $this->getTextUTF((string)$data['Song']['Title']);
          $obj->SongTitle                 = $this->getTextUTF((string)$data['Song']['SongTitle']);
          $obj->ArtistText                = $this->getTextUTF((string)$data['Song']['ArtistText']);
          $obj->Artist                    = $this->getTextUTF((string)$data['Song']['Artist']);
          $obj->ISRC                      = (string)$data['Song']['ISRC'];
          $obj->Composer                  = $this->getTextUTF((string)$data['Song']['Composer']);
          $obj->Genre                     = $this->getTextUTF((string)$data['Genre']['Genre']);
          $obj->Territory                 = (string)$data['Country']['Territory'];
          $obj->Sample_Duration           = $this->getSongDurationTime($data['Song']['Sample_Duration']);
          $obj->FullLength_Duration       = $this->getSongDurationTime($data['Song']['FullLength_Duration']);
          $this->Album->recursive = -1;
          $album = $this->Album->find('first',array('fields' => array('AlbumTitle'),'conditions' => array("ProdId = ".$data['Song']['ReferenceID'], "provider_type" => $data['Song']['provider_type'])));
          $obj->AlbumTitle = $this->getTextUTF($album['Album']['AlbumTitle']);

          $fileURL = $this->Token->regularToken( $data['Sample_Files']['CdnPath']."/".$data['Sample_Files']['SaveAsName']);
          $fileURL = Configure::read('App.Music_Path').$fileURL;
          
          
          if($this->IsDownloadable($data['Song']['ProdID'], $library_territory, $data['Song']['provider_type'])) {
            $obj->fileURL                 = 'nostring';
            $obj->FullLength_FIleURL      = 'nostring';
          } else {
            $obj->fileURL                 = (string)$fileURL;
            $obj->FullLength_FIleURL      = $this->getFullLengthFileURL($data['Full_Files']['FileID']);
          }

          $obj->FullLength_FIleID         = (int)$data['Full_Files']['FileID'];

          $obj->playButtonStatus          = $this->getPlayButtonStatus($data['Song']['ProdID'], $library_territory, $data['Song']['provider_type']);
          
          if('T' == $data['Song']['Advisory']) $obj->SongTitle = $obj->SongTitle.' (Explicit)';    
          
          $list[] = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'LibraryTopTenType');

      }

      $data = new SoapVar($list,SOAP_ENC_OBJECT,null,null,'ArrayOfLibraryTopTenType');

      return $data;

    } else {

      throw new SOAPFault('Soap:client', 'LibraryTopTen list is empty.');
    }

	}

  /**
   * Function Name : getAlbumDetail
   * Desc : To get the details for an album
   * @param string $authenticationToken
   * @param int $prodId
	 * @return AlbumDataType[]
   */
	function getAlbumDetail($authenticationToken, $prodId) { 

    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }
    
    $product_detail = $this->getProductDetail($prodId);
    $prodId = $product_detail['Product']['ProdID'];
    $provider_type = $product_detail['Product']['provider_type'];
       
    $libraryId = $this->getLibraryIdFromAuthenticationToken($authenticationToken);
 
    $libraryDetails = $this->Library->find('first',array(
      'conditions' => array('Library.id' => $libraryId),
      'fields' => array('library_territory', 'library_block_explicit_content'),
      'recursive' => -1
      )
    );

    $library_territory = $libraryDetails['Library']['library_territory'];
    
    $this->Session->write('territory', $library_territory);
       
    $this->switchCpuntriesTable();
    
    $data = array();
    
    $albumData = $this->Album->find('first',
      array(
        'fields' => array(
          'Album.ProdID',
          'Album.ProductID',
          'Album.AlbumTitle',
          'Album.Title',
          'Album.ArtistText',
          'Album.Artist',
          'Album.ArtistURL',
          'Album.Label',
          'Album.Copyright',
          'Album.Advisory',
          'Album.DownloadStatus',
          'Album.TrackBundleCount',
          'Album.provider_type',
          'Album.FileID'
        ),
        'conditions' => array(
          'Album.ProdId' => $prodId,
          'Album.provider_type' => $provider_type,
        ),
        'recursive' => -1
      )  
    );
   

    if(1 == $libraryDetails['Library']['library_block_explicit_content']) {
			$cond = array('Song.Advisory' => 'F');
		}
		else{
			$cond = "";
		}
    
    $Song =  $this->Song->find('all',array(
						'conditions' =>
							array('and' =>
								array(
									array('Song.ReferenceID' => $prodId),
									array('Song.provider_type = Country.provider_type'),
									array('Country.DownloadStatus' => 1),
									array('Country.StreamingSalesDate < NOW()'),
									array("Song.Sample_FileID != ''"),
									array("Song.FullLength_FIleID != ''"),
									array("Song.provider_type" => $provider_type),
									array('Country.Territory' => $library_territory),$cond
								)
							),
						'fields' => array(
								'Song.ProdID',
								'Song.ProductID',
								'Song.ReferenceID',
								'Song.Title',
								'Song.SongTitle',
								'Song.ArtistText',
								'Song.Artist',
								'Song.Advisory',
								'Song.ISRC',
								'Song.Composer',
								'Song.Genre',
								'Song.Territory',
                'Song.Sample_Duration',
                'Song.FullLength_Duration',
                'Song.Sample_FileID',
                'Song.FullLength_FIleID',
                'Song.CreatedOn',
                'Song.UpdateOn',
                'Song.provider_type',
                'Song.sequence_number',
                'Song.TrackBundleCount'
								),
						'contain' => array(
							'Country' => array(
									'fields' => array(
											'Country.Territory',
											'Country.SalesDate'
										)
									),
						), 'group' => 'Song.ProdID, Song.provider_type', 'order' => array('Song.sequence_number','Song.ProdID')
				));
    

    $arr_album_songs = array();
    foreach($Song AS $key => $val) {
      
      $arr_album_songs[$key] = $val['Song'];
    }
    
    
    if(!empty($albumData)){

      $info_data = Array();
      $album_list = Array();
      $song_list = Array();
      $data['Album'] = $albumData['Album'];
      $data['Song'] = $arr_album_songs;

      $obj = new AlbumDataType;
      $obj->ProdID                    = (int)$this->getProductAutoID($data['Album']['ProdID'], $data['Album']['provider_type']);
      $obj->ProductID                 = (string)$data['Album']['ProductID'];
      $obj->AlbumTitle                = $this->getTextUTF((string)$data['Album']['AlbumTitle']);
      $obj->Title                     = $this->getTextUTF((string)$data['Album']['Title']);
      $obj->ArtistText                = $this->getTextUTF((string)$data['Album']['ArtistText']);
      $obj->Artist                    = $this->getTextUTF((string)$data['Album']['Artist']);
      $obj->ArtistURL                 = (string)$data['Album']['ArtistURL'];
      $obj->Label                     = $this->getTextUTF((string)$data['Album']['Label']);
      $obj->Copyright                 = (string)$data['Album']['Copyright'];
      $obj->Advisory                  = $data['Album']['Advisory'];
      $imgData = $this->Files->find('first',array('conditions' => array('FileID' => $data['Album']['FileID'])));
      $fileURL = $this->Token->regularToken( $imgData['Files']['CdnPath']."/".$imgData['Files']['SourceURL']);
      $fileURL = Configure::read('App.Music_Path').$fileURL;
      $obj->FileURL = $fileURL;
      $obj->DownloadStatus            = (int)$data['Album']['DownloadStatus'];
      $obj->TrackBundleCount          = (int)$data['Album']['TrackBundleCount'];
      
      if(empty($obj->AlbumTitle)) { $obj->AlbumTitle = ' '; }   
      if(empty($obj->Title)) { $obj->Title = ' '; }   
      if(empty($obj->ArtistText)) { $obj->ArtistText = ' '; }   
      if(empty($obj->Artist)) { $obj->Artist = ' '; }   
      if(empty($obj->ArtistURL)) { $obj->ArtistURL = ' '; }   
      if(empty($obj->Label)) { $obj->Label = ' '; }   
      if(empty($obj->Copyright)) { $obj->Copyright = ' '; }
      if(empty($obj->Advisory)) { $obj->Advisory = ' '; } 
      if(empty($obj->FileURL)) { $obj->FileURL = ' '; } 
      if(empty($obj->DownloadStatus)) { $obj->DownloadStatus = ' '; } 
      if(empty($obj->TrackBundleCount)) { $obj->TrackBundleCount = ' '; } 
      
      if('T' == $data['Album']['Advisory']) { $obj->AlbumTitle = $obj->AlbumTitle.' (Explicit)'; $obj->Title = $obj->Title.' (Explicit)'; } 
      
      foreach($data['Song'] AS $val){

          if($this->IsTerrotiry($val['ProdID'], $val['provider_type'], $libraryId)) {

            $sobj = new SongDataType;
            $sobj->ProdID                = (int)$this->getProductAutoID($val['ProdID'], $val['provider_type']);
            $sobj->ProductID             = (string)$val['ProductID'];
            $sobj->ReferenceID           = (int)$this->getProductAutoID($val['ReferenceID'], $val['provider_type']);
            $sobj->Title                 = $this->getTextUTF((string)$val['Title']);
            $sobj->SongTitle             = $this->getTextUTF((string)$val['SongTitle']);
            $sobj->ArtistText            = $this->getTextUTF((string)$val['ArtistText']);
            $sobj->Artist                = $this->getTextUTF((string)$val['Artist']);
            $sobj->Advisory              = (string)$val['Advisory'];
            $sobj->ISRC                  = (string)$val['ISRC'];
            $sobj->Composer              = $this->getTextUTF((string)$val['Composer']);
            $sobj->Genre                 = $this->getTextUTF((string)$val['Genre']);
            $sobj->Territory             = (string)$val['Territory'];
                      
            $sobj->DownloadStatus        = $this->IsDownloadable($val['ProdID'], $library_territory, $val['provider_type']);       
            
            $sobj->TrackBundleCount      = (int)$val['TrackBundleCount'];
            $sobj->Sample_Duration       = $this->getSongDurationTime($val['Sample_Duration']);
            $sobj->FullLength_Duration   = $this->getSongDurationTime($val['FullLength_Duration']);
            $sobj->Sample_FileID         = (int)$val['Sample_FileID'];
            $sampFileData = $this->Files->find('first',array('conditions' => array('FileID' => $val['Sample_FileID'])));
            $sampleFileURL = $this->Token->regularToken( $sampFileData['Files']['CdnPath']."/".$sampFileData['Files']['SaveAsName']);
                   
            if($sobj->DownloadStatus) {
              $sobj->Sample_FileURL        = 'nostring';
              $sobj->FullLength_FIleURL    = 'nostring';
            }else{
              $sobj->Sample_FileURL        = Configure::read('App.Music_Path').$sampleFileURL;
              $sobj->FullLength_FIleURL    = $this->getFullLengthFileURL($val['FullLength_FIleID']);
            }
            
            $sobj->FullLength_FIleID     = (int)$val['FullLength_FIleID'];           
            $sobj->CreatedOn             = (string)$val['CreatedOn'];
            $sobj->UpdateOn              = (string)$val['UpdateOn'];
            
            $sobj->playButtonStatus      = $this->getPlayButtonStatus($val['ProdID'], $library_territory, $val['provider_type']);
            
            
            if(empty($sobj->Title)) { $sobj->Title = ' '; } 
            if(empty($sobj->SongTitle)) { $sobj->SongTitle = ' '; } 
            if(empty($sobj->ArtistText)) { $sobj->ArtistText = ' '; } 
            if(empty($sobj->Artist)) { $sobj->Artist = ' '; } 
            if(empty($sobj->Advisory)) { $sobj->Advisory = ' '; } 
            if(empty($sobj->ISRC)) { $sobj->ISRC = ' '; } 
            if(empty($sobj->Composer)) { $sobj->Composer = ' '; } 
            if(empty($sobj->Genre)) { $sobj->Genre = ' '; } 
            if(empty($sobj->Territory)) { $sobj->Territory = ' '; } 
            if(empty($sobj->DownloadStatus)) { $sobj->DownloadStatus = ' '; } 
            if(empty($sobj->TrackBundleCount)) { $sobj->TrackBundleCount = ' '; } 
            if(empty($sobj->Sample_Duration)) { $sobj->Sample_Duration = ' '; } 
            if(empty($sobj->FullLength_Duration)) { $sobj->FullLength_Duration = ' '; } 
            if(empty($sobj->Sample_FileID)) { $sobj->Sample_FileID = ' '; } 
            if(empty($sobj->Sample_FileURL)) { $sobj->Sample_FileURL = ' '; } 
            if(empty($sobj->FullLength_FIleURL)) { $sobj->FullLength_FIleURL = ' '; } 
            if(empty($sobj->FullLength_FIleID)) { $sobj->FullLength_FIleID = ' '; } 
            if(empty($sobj->CreatedOn)) { $sobj->CreatedOn = ' '; } 
            if(empty($sobj->UpdateOn)) { $sobj->UpdateOn = ' '; } 
            
            
            if('T' == $val['Advisory']) $sobj->SongTitle = $sobj->SongTitle.' (Explicit)';
            $song_list[] = new SoapVar($sobj,SOAP_ENC_OBJECT,null,null,'SongDataType');

          }

      }
      $songData = new SoapVar($song_list,SOAP_ENC_OBJECT,null,null,'ArrayOfSongDataType');
      $obj->Songs = $songData;
      $album_list = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'AlbumDataType');
      return $album_list;

    }
    else {
      throw new SOAPFault('Soap:client', 'Freegal is unable to find details for the Album.');
    }
  }

  /**
   * Function Name : getUserCurrentDownload
   * Desc : To get the current download of a user
   * @param string $libraryId
   * @param string $authenticationToken
	 * @return UserCurrentDownloadDataType[]
   */
	function getUserCurrentDownload($libraryId, $authenticationToken) {
  
    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }

    $patronId = $this->getPatronIdFromAuthenticationToken($authenticationToken);
    
    $downloadCount = $this->getTotalDownloadCound($libraryId, $patronId);
    
    $this->Library->recursive = -1;
    $libraryDetails = $this->Library->find('first', array(
      'fields' => array('library_user_download_limit'),
      'conditions' => array('id' => $libraryId),
    ));

    $wishlist = 0;
    
    $obj = new UserCurrentDownloadDataType;
    $obj->currentDownloadCount                = (int)$downloadCount;
    $obj->totalDownloadLimit                  = (int)$libraryDetails['Library']['library_user_download_limit'];
    $obj->showWishlist                        = (int)$wishlist;


    $user_current_download_list = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'UserCurrentDownloadDataType');

    $data = new SoapVar($user_current_download_list,SOAP_ENC_OBJECT,null,null,'ArrayUserCurrentDownloadDataType');

    return $data;

  }



  /**
   * Function Name : addSongToWishlist
   * Desc : Add song to wish list
   * @param string $libraryId
   * @param int $prodId
   * @param string $authenticationToken
   * @param string $isrc
   * @param string $userAgent
	 * @return array
   */

	function addSongToWishlist($libraryId, $prodId, $authenticationToken, $isrc, $userAgent) {


    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }

    $product_detail = $this->getProductDetail($prodId);
    $prodId = $product_detail['Product']['ProdID'];
    $provider_type = $product_detail['Product']['provider_type'];
    
    $this->Library->recursive = -1;
    $libraryDetails = $this->Library->find('first', array('conditions' => array('id' => $libraryId)));
    
    
    if(0 == $this->getDownloadStatusOfSong($prodId, $provider_type, $libraryDetails['Library']['DownloadStatus'])) {
      throw new SOAPFault('Soap:client', 'Requested song is not allowed to add.');
    }

    $patronId = $this->getPatronIdFromAuthenticationToken($authenticationToken);

    $cnt =  $this->Wishlist->find('count',
              array('conditions' =>
                array('library_id' => $libraryId, 'patron_id' => $patronId, 'ProdID' => $prodId, 'provider_type' => $provider_type)
              )
            );

    if($cnt){
      $message = 'This song has been already added into your wishlist';
      return $this->createsSuccessResponseObject(false, $message);
    }

		$libraryDownload = $this->Downloads->checkLibraryDownload($libraryId);


		$patronDownload = $this->Downloads->checkPatronDownload($patronId,$libraryId);


    $this->Library->recursive = -1;
    $libraryDetails = $this->Library->find('all',array(
      'conditions' => array('Library.id' => $libraryId),
      'fields' => array('library_user_download_limit','library_status')
      )
    );

    $patronLimit = $libraryDetails[0]['Library']['library_user_download_limit'];

    if('inactive' == $libraryDetails[0]['Library']['library_status']) {
      throw new SOAPFault('Soap:client', 'Requested library is Inactive.');
    }

    if(!($this->IsTerrotiry($prodId, $provider_type, $libraryId))) {
      throw new SOAPFault('Soap:client', 'Song does not belong to current library territory.');
    }

    $wishlistCount =  $this->Wishlist->find('count',array('conditions' => array('library_id' => $libraryId,'patron_id' => $patronId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));

    if($wishlistCount >= $patronLimit && $libraryDownload != '1' && $patronDownload != '0') {
      throw new SOAPFault('Soap:client', 'You have reached the maximum allowed download count.');
    }
    else {


      $TrackData = $this->Song->find('first',
        array(
          'fields' => array(
            'Song.ProdID',
            'Song.ProductID',
            'Song.Title',
            'Song.SongTitle',
            'Song.Artist',
            'Song.ISRC'
          ),
          'conditions' => array(
            'Song.ProdID' => $prodId,
            'Song.provider_type' => $provider_type,
          ),
          'recursive' => -1,
        )
      );

      $insertArr = Array();
      $insertArr['library_id'] = $libraryId;
      $insertArr['patron_id'] = $patronId;
      $insertArr['ProdID'] = $prodId;
      $insertArr['artist'] = $TrackData['Song']['Artist'];
      $insertArr['album'] = $TrackData['Song']['Title'];
      $insertArr['track_title'] = $TrackData['Song']['SongTitle'];
      $insertArr['ProductID'] = $TrackData['Song']['ProductID'];
      $insertArr['ISRC'] = $TrackData['Song']['ISRC'];
			$insertArr['user_agent'] = $userAgent;
			$insertArr['ip'] = $_SERVER['REMOTE_ADDR'];
      $insertArr['provider_type'] = $provider_type;

      $row_save_status = $this->Wishlist->save($insertArr);

      if($row_save_status){

        $this->Library->setDataSource('master');
        $sql = "UPDATE `libraries` SET library_available_downloads=library_available_downloads-1 Where id=".$libraryId;
        $this->Library->query($sql);
        $this->Library->setDataSource('default');
	



        $message = 'Song added successfully';
        return $this->createsSuccessResponseObject(true, $message);

      }
      else{
        $message = 'Freegal is unable to update the information. Please try again later.';
        return $this->createsSuccessResponseObject(false, $message);
      }

    }

  }


  /**
   * Function Name : deleteSongFromWishlist
   * Desc : Delete song to wish list
   * @param string $deleteSongId
   * @param string $libraryId
   * @param string $authenticationToken
	 * @return SuccessResponseType[]
   */

	function deleteSongFromWishlist($deleteSongId, $libraryId, $authenticationToken) {

    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }

    if($this->Wishlist->delete($deleteSongId)) {
      $this->Library->setDataSource('master');
      $sql = "UPDATE `libraries` SET library_available_downloads=library_available_downloads+1 Where id=".$libraryId;
      $this->Library->query($sql);
      $this->Library->setDataSource('default');

      $message = 'Song deleted successfully';
      return $this->createsSuccessResponseObject(true, $message);
    }
		else {
      $message = 'Delete song from whislist failed';
      return $this->createsSuccessResponseObject(false, $message);
		}

  }


  /**
   * Function Name : getWishlist
   * Desc : To get the list of my wish list
   * @param string $authenticationToken
	 * @return WishlistDataType[]
   */
	function getWishlist($authenticationToken) {


    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }

    $patronId = $this->getPatronIdFromAuthenticationToken($authenticationToken);
    $libraryId = $this->getLibraryIdFromAuthenticationToken($authenticationToken);

    $wishlistResults = Array();
    $wishlistResults =  $this->Wishlist->find('all',
      array(
        'fields' => array(
          'Wishlist.id',
          'Wishlist.library_id',
          'Wishlist.patron_id',
          'Wishlist.ProdID',
          'Wishlist.ProductID',
          'Wishlist.ISRC',
          'Wishlist.artist',
          'Wishlist.album',
          'Wishlist.track_title',
          'Wishlist.user_agent',
          'Wishlist.ip',
          'Wishlist.created',
          'Wishlist.delete_on',
          'Wishlist.week_start_date',
          'Wishlist.week_end_date',
          'Wishlist.provider_type',
          'Song.Sample_Duration',
          'Song.FullLength_Duration',
          'Song.ReferenceID',
          'Files.CdnPath',
          'Files.SaveAsName'
        ),
        'conditions' => array(
          'library_id' => $libraryId,
          'patron_id' => $patronId
        ),
        'joins' => array(
          array(
            'table' => 'Songs',
            'alias' => 'Song',
            'type' => 'INNER',
            'foreignKey' => false,
            'conditions'=> array(
              'Wishlist.ProdID = Song.ProdID',
              'Wishlist.provider_type = Song.provider_type',
            )
          ),
          array(
            'table' => 'File',
            'alias' => 'Files',
            'type' => 'INNER',
            'foreignKey' => false,
            'conditions' => array(
              'Files.FileID = Song.Sample_FIleID',
              'Song.ProdID = Wishlist.ProdID'
            )
          )
        ),
      )
    );

    if(empty($wishlistResults)) {
      throw new SOAPFault('Soap:client', 'You have empty wishlist.');
    }

    foreach($wishlistResults AS $val){

      $obj = new WishlistDataType;
      $obj->song_id               = (int)$val['Wishlist']['id'];
      $obj->library_id            = (int)$val['Wishlist']['library_id'];
      $obj->patron_id             = (int)$val['Wishlist']['patron_id'];
      $obj->ProdID                = (int)$this->getProductAutoID($val['Wishlist']['ProdID'], $val['Wishlist']['provider_type']);
      $obj->ProductID             = (string)$val['Wishlist']['ProductID'];
      $obj->ISRC                  = (string)$val['Wishlist']['ISRC'];
      $obj->artist                = $this->getTextUTF((string)$val['Wishlist']['artist']);
      $obj->album                 = $this->getTextUTF((string)$val['Wishlist']['album']);
      $obj->track_title           = $this->getTextUTF((string)$val['Wishlist']['track_title']);
      $obj->user_agent            = (string)$val['Wishlist']['user_agent'];
      $obj->ip                    = (string)$val['Wishlist']['ip'];
      $obj->created               = (string)$val['Wishlist']['created'];
      $obj->delete_on             = (string)$val['Wishlist']['delete_on'];
      $obj->week_start_date       = (string)$val['Wishlist']['week_start_date'];
      $obj->week_end_date         = (string)$val['Wishlist']['week_end_date'];

      $obj->Sample_Duration       = (string)$val['Song']['Sample_Duration'];
      $obj->FullLength_Duration   = (string)$val['Song']['FullLength_Duration'];

      $obj->SongUrl               = Configure::read('App.Music_Path') . $this->Token->regularToken( $val['Files']['CdnPath'] . "/" . $val['Files']['SaveAsName']);


      $obj->AlbumProdID           = $this->getProductAutoID($val['Song']['ReferenceID'], $val['Wishlist']['provider_type']);

      $wish_list[] = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'WishlistDataType');

    }

    $info_data = new SoapVar($wish_list,SOAP_ENC_OBJECT,null,null,'ArrayWishlistDataType');


    return $info_data;
  }

  /**
   * Function Name : registerDevice
   * Desc : To register device
   * @param string deviceID
   * @param string registerID
   * @param string lang
   * @param string authenticationToken
   * @param string systemType
	 * @return SuccessResponseType[]
   */
  function registerDevice($deviceID, $registerID, $lang, $authenticationToken, $systemType = null){
  
    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      $msg = 'Your credentials seems to be changed or expired. Please logout and login again.';
      return $this->createsSuccessResponseObject(false, $msg);
    }
    
    $userID = $this->getPatronIdFromAuthenticationToken($authenticationToken);
    $libID = $this->getLibraryIdFromAuthenticationToken($authenticationToken);
    
    $arr_param = func_get_args();
    
    $arr_param_values['device_id'] = $arr_param[0];
    $arr_param_values['registration_id'] = $arr_param[1];
    $arr_param_values['patron_id'] = $userID;
    $arr_param_values['library_id'] = $libID;
    $arr_param_values['user_language'] = $arr_param[2];
    $arr_param_values['system_type'] = $arr_param[4];
    
    foreach($arr_param_values as $key => $val) {
    
      if('' == trim($val)){
        $msg = 'Freegal is currently facing some difficulties. Please try again';
        return $this->createsSuccessResponseObject(false, $msg);
      }
    }
    
    
    $data = $this->DeviceMaster->find('first', array('conditions' => array('patron_id' => $userID, 'library_id' => $libID)));
    
    if('' != trim($data['DeviceMaster']['id'])) {
      
      $this->DeviceMaster->read('id', $data['DeviceMaster']['id']);
      $this->DeviceMaster->set(array(
        'registration_id' => $registerID,
        'device_id' => $deviceID,
        'user_language' => $lang,
        'system_type' => $systemType,
      ));
      $sta = $this->DeviceMaster->save();
      
    } else {
      $sta = $this->DeviceMaster->save($arr_param_values);
    }
    
    if(false !== $sta){
      $msg = 'Freegal has registered you device successfully';
      return $this->createsSuccessResponseObject(true, $msg);  
    }else{
      $msg = 'Freegal is currently facing some difficulties. Please try again';
      return $this->createsSuccessResponseObject(false, $msg);  
    }
    
  }
    
  
  /**
   * Function Name : updateRegisterDeviceLang
   * Desc : To update language of given device & registration id
   * @param string authenticationToken
   * @param string deviceID
   * @param string registerID
   * @param string lang
	 * @return SuccessResponseType[]
   */

  function updateRegisterDeviceLang($authenticationToken, $deviceID, $registerID, $lang){
  
    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      $msg = 'Your credentials seems to be changed or expired. Please logout and login again.';
      return $this->createsSuccessResponseObject(false, $msg);
    }
    
    $arr_param = func_get_args();

    $arr_param_values['device_id'] = $arr_param[1];
    $arr_param_values['registration_id'] = $arr_param[2];
    $arr_param_values['user_language'] = $arr_param[3];

    foreach($arr_param_values as $key => $val) {
    
      if('' == trim($val)){
        $msg = 'Freegal is currently facing some difficulties. Please try again';
        return $this->createsSuccessResponseObject(false, $msg);
      }
    }
    
    $data = $this->DeviceMaster->find('first', array('conditions' => array('device_id' => $deviceID, 'registration_id' => $registerID)));

    
    if('' != trim($data['DeviceMaster']['id'])) {
      
      $this->DeviceMaster->read('id', $data['DeviceMaster']['id']);
      $this->DeviceMaster->set(array(
        'user_language' => $lang,
      ));
      $this->DeviceMaster->save();
      
      $msg = 'You have updated language successfully';
      return $this->createsSuccessResponseObject(true, $msg);  
      
    } else {
      $msg = "Freegal is currently facing some difficulties. Please try again";
      return $this->createsSuccessResponseObject(false, $msg);
    }
  
  
  }

  /**
   * Function Name : validateLibInTimezone
   * Desc : To validate that given lib has its timezone recorded
   * @param string authenticationToken
   * * @return SuccessResponseType[]
   */
  
  function validateLibInTimezone($authenticationToken){
  
    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      $msg = 'Your credentials seems to be changed or expired. Please logout and login again.';
      return $this->createsSuccessResponseObject(false, $msg);
    }
   
    $libID = $this->getLibraryIdFromAuthenticationToken($authenticationToken);
      
    $count = $this->LibrariesTimezone->find('count', array('conditions' => array('library_id' => $libID)));

    
    if($count) {
      $message = '';
      return $this->createsSuccessResponseObject(true, $message);
    } else {
      $message = '';
      return $this->createsSuccessResponseObject(false, $message);
    }
     
  }

  /**
   * Function Name : validateStreamRequest
   * Desc : To validate stream request & returning mp4 url
   * @param string authenticationToken
   * @param string ProdID
   * @param string agent
   * @param int actionID
   * @param int consumedTime
   * @param string songDuration
   * @return StreamingResponseType[]
   */
  
  function validateStreamRequest($authenticationToken, $ProdID, $agent, $actionID, $consumedTime, $songDuration, $queueID = null, $instanceToken = null){
    
    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }
    
    $patId = $this->getPatronIdFromAuthenticationToken($authenticationToken);
    $libId = $this->getLibraryIdFromAuthenticationToken($authenticationToken);
    
    $product_detail = $this->getProductDetail($ProdID);
    $prodId = $product_detail['Product']['ProdID'];
    $provider_type = $product_detail['Product']['provider_type'];    
      
    if( (1 == $actionID) || (2 == $actionID) ){
      $consumedTime = 0;
    }    
    
    $song_duration = $this->Streaming->getSeconds($songDuration);
    
    // Library ID | Patron Id | ProdID | Provider Type | Streaming time used by user | Action Type | Agent | Song Duration
    $response = $this->Streaming->validateSongStreaming($libId, $patId, $prodId, $provider_type, $consumedTime, $actionID, $agent, $song_duration, $queueID, $instanceToken);
    // 0/1 | message | Remaining time | 3 message identification number for mobile | timerCallTime | timerCallDuration
    
    
    switch($response[0]){
      case '1': 
      
        $streaming_url = null;
        if( (1 == $actionID) || (2 == $actionID) ) {
          $streaming_url = $this->getStreamngURL($prodId, $provider_type);    
        }
        return $this->createsStreamingResponseObject(true, $response[1], $response[2], $streaming_url, $response[4], $response[5]); 
        // success | message | remaningtime | mp4url | timerCallTime | timerCallDuration
      break;
      case '0'  : 
      
        return $this->createsStreamingResponseObject(false, $response[1], $response[2], null, $response[4], $response[5]);  
      break;
      default:  throw new SOAPFault('Soap:stream', 'Sorry, Server is facing some technical challenges in streaming this Song.');
    
    }
    
      
  }

   /**
   * Function Name : loginByWebservice
   * Desc : To authnticate user by web service
   * @param string $authtype
   * @param string $email
   * @param string $password
   * @param string $card
   * @param string $pin
   * @param string $last_name
   * @param string $library_id
   * @param string $agent
   * @param string $cron_call
	 * @return AuthenticationResponseDataType[]
   */


 	function loginByWebservice($authtype, $email, $password, $card, $pin, $last_name, $library_id, $agent, $cron_call = 0) {

    switch($authtype){

      case '1':  {
        $resp = $this->loginAuthinticate($email, $password, $library_id, $agent, $authtype, $cron_call);
      }
      break;

      case '2':  {
        $resp = $this->iloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
      }
      break;

      case '3':  {
        $resp = $this->inloginAuthinticate($card, $library_id, $agent, $authtype, $cron_call);
      }
      break;

      case '4':  {
        $resp = $this->inhloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
      }
      break;

      case '5':  {
        $resp = $this->ihdloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
      }
      break;

      case '6':  {
        $resp = $this->ildloginAuthinticate($card, $last_name, $library_id, $agent, $authtype, $cron_call);
      }
      break;

      case '7':  {
        $resp = $this->ilhdloginAuthinticate($card, $last_name, $library_id, $agent, $authtype, $cron_call);
      }
      break;

      case '8':  {
        $resp = $this->sloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
      }
      break;

      case '9':  {
        $resp = $this->sdloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
      }
      break;

      case '10':  {
        $resp = $this->ploginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
      }
      break;

      case '11':  {
        $resp = $this->indloginAuthinticate($card, $library_id, $agent, $authtype, $cron_call);
      }
      break;

      case '12':  {
        $resp = $this->inhdloginAuthinticate($card, $library_id, $agent, $authtype, $cron_call);
      }
      break;

      case '13':  {
        $resp = $this->snloginAuthinticate($card, $library_id, $agent, $authtype, $cron_call);
      }
      break;

      case '14':  {
        $resp = $this->sndloginAuthinticate($card, $library_id, $agent, $authtype, $cron_call);
      }
      break;

      case '15':  {
        $resp = $this->cloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
      }
      break;

      case '16':  {
        $resp = $this->referralAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
      }
      break;

      case '17':  {
	$auth_method_name = $this->Library->find('first',array(
	      'conditions' => array('Library.id' => $library_id),
	      'fields' => array('library_authentication_method'),
	      'recursive' => -1
	      )
	    );
	if($auth_method_name['Library']['library_authentication_method'] == 'capita'){
	 	$resp = $this->capitaAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
  	}
	elseif($auth_method_name['Library']['library_authentication_method'] == 'symws'){
		$resp = $this->symwsAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
	}
	else{
        	$resp = $this->idloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
      	}
      }
      break;

      case '18':  {
        $resp = $this->mndloginAuthinticate($card, $library_id, $agent, $authtype, $cron_call);
      }
      break;
      
      case '19':  {
        $resp = $this->mdloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
      }
      break;
      
      default:
    }

    
    $resp->enc_value->enc_value->user_type = $this->getUserType('', $library_id, 1);
    
    return $resp;
  }



  /**
   * Function Name : callingFreegalMusic
   * Desc : To authnticate users from third party applications
   * @param string $lib_id
   * @param string $username
   * @param string $passcode
   * @param string $agent
   * @return AuthenticationResponseDataType[]
   */


 	function callingFreegalMusic($lib_id, $username, $passcode, $agent ) {
     
	  $library_id = $lib_id;
      	  $email = $username;
	  	  $card = $username;
	      $password = $passcode;
          $pin = $passcode;
          $last_name = $passcode;
          $auth_method = $this->Library->find('first',array(
	      'conditions' => array('Library.id' => $library_id),
	      'fields' => array('library_authentication_method'),
	      'recursive' => -1
	      )
	    );

 	  $authtype = $this->getLibraryIdentefierByLibraryMethod($auth_method['Library']['library_authentication_method']);
     
	switch($authtype){
	
      	case '1':  {
        	$resp = $this->loginAuthinticate($email, $password, $library_id, $agent, $authtype, $cron_call);
    	}
      	break;

      	case '2':  {
       		$resp = $this->iloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
      	}
      	break;

      	case '3':  {		
        	$resp = $this->inloginAuthinticate($card, $library_id, $agent, $authtype, $cron_call);
      	}
      	break;

      	case '4':  {
        	$resp = $this->inhloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
      	}
      	break;

      	case '5':  {
        	$resp = $this->ihdloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
      	}
      	break;

      	case '6':  {
        	$resp = $this->ildloginAuthinticate($card, $last_name, $library_id, $agent, $authtype, $cron_call);
      	}
      	break;

      	case '7':  {
        	$resp = $this->ilhdloginAuthinticate($card, $last_name, $library_id, $agent, $authtype, $cron_call);
      	}
      	break;

      	case '8':  {
        	$resp = $this->sloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
      	}
      	break;

      	case '9':  {
        	$resp = $this->sdloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
      	}
      	break;

      	case '10':  {
        	$resp = $this->ploginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
      	}
      	break;

      	case '11':  {
        	$resp = $this->indloginAuthinticate($card, $library_id, $agent, $authtype, $cron_call);
      	}
      	break;

      	case '12':  {
        	$resp = $this->inhdloginAuthinticate($card, $library_id, $agent, $authtype, $cron_call);
      	}
      	break;

      	case '13':  {
        	$resp = $this->snloginAuthinticate($card, $library_id, $agent, $authtype, $cron_call);
      	}
      	break;

      	case '14':  {
        	$resp = $this->sndloginAuthinticate($card, $library_id, $agent, $authtype, $cron_call);
      	}
      	break;

      	case '15':  {
        	$resp = $this->cloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
      	}
      	break;

      	case '16':  {
        	$resp = $this->referralAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
      	}
      	break;

      	case '17':  {
			$auth_method_name = $this->Library->find('first',array(
	      'conditions' => array('Library.id' => $library_id),
	      'fields' => array('library_authentication_method'),
	      'recursive' => -1
	      )
	    );
		if($auth_method_name['Library']['library_authentication_method'] == 'capita'){
	 		$resp = $this->capitaAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
  		}
		elseif($auth_method_name['Library']['library_authentication_method'] == 'symws'){
			$resp = $this->symwsAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
		}
		else{
        	$resp = $this->idloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
      	}
      	}
      	break;

      	case '18':  {
        	$resp = $this->mndloginAuthinticate($card, $library_id, $agent, $authtype, $cron_call);
      	}
      	break;
      
      	case '19':  {
        	$resp = $this->mdloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call);
      	}
      	break;
      
      	default:
    }

    
    $resp->enc_value->enc_value->user_type = $this->getUserType('', $library_id, 1);
    $resp->enc_value->enc_value->authentication_type = $authtype;
    return $resp;
		
	}

  
  /**
   * Function Name : getUserType
   * Desc : Returns user type i.e. whether it belongs to Freegal4.O or Freegal4.O+  
   * @param int innerCall
   * @param string token
   * @param int libID
   * @return UserTypeResponseType[]
   */
  function getUserType($token, $libID, $innerCall = 0) {
    
    if(1 != $innerCall){
      if(!($this->isValidAuthenticationToken($token))) {
        throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
      }
    }
    
    $libraryDetails = $this->Library->find('first',array(
      'conditions' => array('Library.id' => $libID),
      'fields' => array('library_type'),
      'recursive' => -1
      )
    );

    if(1 != $innerCall){
      return $this->createsUserTypeResponseObject($libraryDetails['Library']['library_type']);
    }else{
      return $libraryDetails['Library']['library_type'];
    }
    

  }
  
  
  /**
   * Function Name : getStreamSongsList
   * Desc : Returns list of streamed songs list for given patron
   * @param string authenticationToken
   * @param string startFrom
   * @param string recordCount
   * @return SongDataType[]
   */
  function getStreamSongsList($authenticationToken, $startFrom, $recordCount) {
  
    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }
    
    $patronID = $this->getPatronIdFromAuthenticationToken($authenticationToken);
    $libraryID = $this->getLibraryIdFromAuthenticationToken($authenticationToken);
    
    $song_data = array();
    $song_data = $this->Song->find('all', array(
      'fields' => array('DISTINCT(CONCAT(Song.ProdID, "-", Song.provider_type))', 'Song.ProdID', 'Song.ReferenceID', 'Song.Title', 'Song.SongTitle', 'Song.ArtistText', 'Song.Artist', 'Song.FullLength_Duration', 'Song.provider_type', 'Song.Advisory'),
      'joins' =>  array(
        array(
          'table' => 'streaming_histories',
          'alias' => 'sh',
          'type' => 'INNER',
          'foreignKey' => false,
          'conditions'=> array('Song.ProdID = sh.ProdID', 'Song.provider_type = sh.provider_type')
        )
      ),
      'conditions' => array(
        'sh.patron_id' => $patronID, 'sh.library_id' => $libraryID, 'DATE(sh.createdOn) = CURDATE()' 
      ),
      'limit' => $startFrom . ', ' . $recordCount,
      'recursive' => -1,
      'order' => 'sh.createdOn DESC'
    ));
				
    if(empty($song_data)) {
      throw new SOAPFault('Soap:client', 'You do not have any songs for this page.');
    }
    
    
    foreach($song_data as $key => $val) {
      
      $sobj = new SongDataType;
      
      $sobj->ProdID               =    $this->getProductAutoID($val['Song']['ProdID'], $val['Song']['provider_type']);
      $sobj->ReferenceID          =    $this->getProductAutoID($val['Song']['ReferenceID'], $val['Song']['provider_type']);
      $sobj->Title                =    $val['Song']['Title'];
      $sobj->SongTitle            =    $val['Song']['SongTitle'];
      $sobj->ArtistText           =    $val['Song']['ArtistText'];
      $sobj->Artist               =    $val['Song']['Artist'];
      $sobj->FullLength_Duration  =    $this->getSongDurationTime($val['Song']['FullLength_Duration']);

      if( 'T' == $val['Song']['Advisory'] ) {    
        
        $sobj->Title  = $sobj->Title . ' (Explicit)';
        $sobj->SongTitle  = $sobj->SongTitle . ' (Explicit)';     
      }
          
      $sobj->playButtonStatus    = $this->getPlayButtonStatus($val['Song']['ProdID'], $this->getLibraryTerritory($libraryID), $val['Song']['provider_type']);
        
      $song_list[] = new SoapVar($sobj,SOAP_ENC_OBJECT,null,null,'SongDataType');
    
    }
    
    return new SoapVar($song_list,SOAP_ENC_OBJECT,null,null,'ArraySongDataType');
    
    
  }
  
  

  /**
  * Authenticates user by login method
  * @param $email
  * @param $password
  * @param $library_id
  * @param $agent
  * @return AuthenticationResponseDataType[]
  */

  private function loginAuthinticate($email, $password, $library_id, $agent, $authtype, $cron_call){
	$encodingKey = 'bGlicmFyeWlkZWFzMjAxNA==';
    $retVal = FALSE;

    $conditions = array(
      'email'=>$email,
      'user_status' => 'active'
    );


    $user = $this->User->find('first',array(
        'fields' => array('id', 'email', 'library_id', 'password'),
        'conditions' => $conditions,
      )
    );

    $library = $this->Library->find('first',array(
      'conditions' => array('Library.id' => $library_id),
      'fields' => array('library_authentication_method','library_status'),
      'recursive' => -1,
      )
    );

    $library_authentication_method = $library['Library']['library_authentication_method'];

    if('inactive' == $library['Library']['library_status']) {
      throw new SOAPFault('Soap:client', 'Requested library is Inactive.');
    }

    if(is_array($user)) {

      $check_password = Security::hash(Configure::read('Security.salt').$password);

      while(!$retVal){


        if($library_id != $user['User']['library_id']){
          $response_msg = "Invalid Email and password combination provided";
          break;
        }

        if($user['User']['password'] != $check_password){
          $response_msg = "Invalid email or password";
          break;
        }

        $retVal = TRUE;
        break;
      }
    }
    else{
      $response = false;
      $token = null;
      $response_msg = 'Invalid email or password';
      $patron_id = null;

    }

    if($retVal){
      $token = md5(time());
      $insertArr['patron_id'] = $user['User']['id'];
			$insertArr['library_id'] = $user['User']['library_id'];
			$insertArr['token'] = $token;
			$insertArr['auth_time'] = time();
			$insertArr['agent'] = $agent;
			$insertArr['auth_method'] = $library_authentication_method;
      
      $insertArr['authtype'] = $authtype;
      $insertArr['email'] = $email;
      $insertArr['password'] = $this->Common->freegalEncode($password,$encodingKey);
      
      if(0 == $cron_call) {
        $this->AuthenticationToken->save($insertArr);
      }  

      $response = true;
      $response_msg = 'Login Successfull';
      $patron_id = $insertArr['patron_id'];

    }
    else{
      $response = false;
      $token = null;
      $response_msg = 'Invalid email or password';
      $patron_id = null;
    }


    return $this->createsAuthenticationResponseDataObject($response, $response_msg, $token, $patron_id);

  }

  /**
   * sends library details for Auth methods
   * @param $library_id
   * @return array[]
   */

  private function authLibraryDetails($library_id) {
  
    $library_data = $this->Library->find('first', array(
      'fields' => array('library_authentication_num', 'mobile_auth', 'library_authentication_method', 'minimum_card_length'),
      'conditions' => array('id' => $library_id),
      'recursive' => -1
    ));
  
    return $library_data;
  
  }

  /**
   * Authenticates user by ilogin method
   * @param $card
   * @param $pin
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

  private function iloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call) {
	$encodingKey = 'bGlicmFyeWlkZWFzMjAxNA==';
    $card = str_replace(" ", "", $card);
    $card = strtolower($card);
    $data['card'] = $card;
    $data['pin'] = $pin;
    $patronId = $card;
    $data['patronId'] = $patronId;

    $data['wrongReferral'] = '';
    $data['referral'] = '';

    $library_data = $this->authLibraryDetails($library_id);
  
    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    elseif(strlen($card) < $library_data['Library']['minimum_card_length']){


      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);

    }
    elseif($pin == ''){


      $response_msg = 'Pin not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    else{

      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }

      $cardNo = substr($card,0,5);
      $data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
      $this->Library->Behaviors->attach('Containable');

      $data['library_cond'] = $library_id;

      $existingLibraries = $this->Library->find('all',array(
                    'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                                          'library_authentication_method' => 'innovative'),
                    'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory','Library.library_authentication_url',
                                      'Library.library_logout_url','Library.library_territory','Library.library_user_download_limit',
                                      'Library.library_block_explicit_content','Library.library_language, library_subdomain'))
      );

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];

      if(count($existingLibraries) == 0){


        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
      else{

        $authUrl = $existingLibraries['0']['Library']['library_authentication_url'];
        $data['url'] = $authUrl."/PATRONAPI/".$card."/".$pin."/pintest";
        $data['database'] = 'freegal';

        if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
					$authUrl1 = Configure::read('App.AuthUrl_AU')."ilogin_validation";
        }
        else{
					$authUrl1 = Configure::read('App.AuthUrl')."ilogin_validation";
				}

        $result = $this->AuthRequest->getAuthResponse($data,$authUrl1);

        $resultAnalysis[0] = $result['Posts']['status'];
				$resultAnalysis[1] = $result['Posts']['message'];

        if($resultAnalysis[0] == "fail"){


          $response_msg = $resultAnalysis[1];
          return $this->createsAuthenticationResponseDataObject(false, $response_msg);
        }
        elseif($resultAnalysis[0] == "success"){
          $token = md5(time());
          $insertArr['patron_id'] = $data['patronId'];
					$insertArr['library_id'] = $library_id;
					$insertArr['token'] = $token;
					$insertArr['auth_time'] = time();
					$insertArr['agent'] = $agent;
					$insertArr['auth_method'] = $library_authentication_method;
          
          $insertArr['authtype'] = $authtype;
          $insertArr['card'] = $this->Common->freegalEncode($card,$encodingKey);
         $insertArr['pin'] = $this->Common->freegalEncode($pin,$encodingKey);
          
          if(0 == $cron_call) {
            $this->AuthenticationToken->save($insertArr);
          }  

          $patron_id = $insertArr['patron_id'];
          $response_msg = 'Login Successfull';
          return $this->createsAuthenticationResponseDataObject(true, $response_msg, $token, $patron_id);

        }
      }
    }
  }


  /**
   * Authenticates user by inlogin method
   * @param $card
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

	private function inloginAuthinticate($card, $library_id, $agent, $authtype, $cron_call){
	$encodingKey = 'bGlicmFyeWlkZWFzMjAxNA==';
    $patronId = $card;
    $data['patronId'] = $patronId;
    $data['card'] = $card;

    $data['wrongReferral'] = '';
    
    $library_data = $this->authLibraryDetails($library_id);

    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    elseif(strlen($card) < $library_data['Library']['minimum_card_length']){

      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    else{

      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }

      $cardNo = substr($card,0,5);
      $data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
      $this->Library->Behaviors->attach('Containable');

      $data['library_cond'] = $library_id;

      $existingLibraries = $this->Library->find('all',array(
                      'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                                            'library_authentication_method' => 'innovative_wo_pin'),
                      'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory',
                                        'Library.library_authentication_url','Library.library_logout_url',
                                        'Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content',
                                        'Library.library_language,library_subdomain'))
      );

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];

      if(count($existingLibraries) == 0){


        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
			else{

        $authUrl = $existingLibraries['0']['Library']['library_authentication_url'];
        $data['url'] = $authUrl."/PATRONAPI/".$card."/dump";

        $data['database'] = 'freegal';
        if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
					$authUrl = Configure::read('App.AuthUrl_AU')."inlogin_validation";
					}
        else{
					$authUrl = Configure::read('App.AuthUrl')."inlogin_validation";
				}
        $result = $this->AuthRequest->getAuthResponse($data,$authUrl);

        $resultAnalysis[0] = $result['Posts']['status'];
				$resultAnalysis[1] = $result['Posts']['message'];

        if($resultAnalysis[0] == "fail"){


          $response_msg = $resultAnalysis[1];
          return $this->createsAuthenticationResponseDataObject(false, $response_msg);
        }
        elseif($resultAnalysis[0] == "success"){
          $token = md5(time());
          $insertArr['patron_id'] = $data['patronId'];
					$insertArr['library_id'] = $library_id;
					$insertArr['token'] = $token;
					$insertArr['auth_time'] = time();
					$insertArr['agent'] = $agent;
					$insertArr['auth_method'] = $library_authentication_method;
          
          $insertArr['authtype'] = $authtype;
          $insertArr['card'] = $this->Common->freegalEncode($card,$encodingKey);
          
          if(0 == $cron_call) {
            $this->AuthenticationToken->save($insertArr);
          }  

          $patron_id = $insertArr['patron_id'];
          $response_msg = 'Login Successfull';
          return $this->createsAuthenticationResponseDataObject(true, $response_msg, $token, $patron_id);
        }
      }
    }

	}

  /**
   * Authenticates user by inhlogin method
   * @param $card
   * @param $pin
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

  private function inhloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call) {

		$encodingKey = 'bGlicmFyeWlkZWFzMjAxNA==';
		$card = str_replace(" ","", $card);
		$card = strtolower($card);
		$data['card'] = $card;

		$data['pin'] = $pin;
    $patronId = $card;
		$data['patronId'] = $patronId;

    $data['wrongReferral'] = '';
    $data['referral'] = '';
          
    $library_data = $this->authLibraryDetails($library_id);

    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
		elseif(strlen($card) < $library_data['Library']['minimum_card_length']){


      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);

		}
    elseif($pin == ''){


      $response_msg = 'Pin not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    else{

      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }

			$cardNo = substr($card,0,5);
			$data['cardNo'] = $cardNo;

			$this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');

      $data['library_cond'] = $library_id;
      $existingLibraries = $this->Library->find('all',array(
														'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                                                  'library_authentication_method' => 'innovative_https'),
														'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory',
                                              'Library.library_authentication_url','Library.library_logout_url',
                                              'Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content',
                                              'Library.library_language,library_subdomain'))
			);

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];

		}

    if(count($existingLibraries) == 0){

      $response_msg = 'Invalid credentials provided.';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
		else{
			$matches = array();
			$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];
			$data['url'] = $authUrl."/PATRONAPI/".$card."/".$pin."/pintest";

			$data['database'] = 'freegal';
      if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
        $authUrl = Configure::read('App.AuthUrl_AU')."inhlogin_validation";
      }
			else{
        $authUrl = Configure::read('App.AuthUrl')."inhlogin_validation";
			}
			$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

      $resultAnalysis[0] = $result['Posts']['status'];
			$resultAnalysis[1] = $result['Posts']['message'];

			if($resultAnalysis[0] == "fail"){


        $response_msg = $resultAnalysis[1];
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
			}elseif($resultAnalysis[0] == "success"){
        $token = md5(time());
        $insertArr['patron_id'] = $data['patronId'];
				$insertArr['library_id'] = $library_id;
				$insertArr['token'] = $token;
				$insertArr['auth_time'] = time();
				$insertArr['agent'] = $agent;
				$insertArr['auth_method'] = $library_authentication_method;
        
        $insertArr['authtype'] = $authtype;
        $insertArr['card'] = $this->Common->freegalEncode($card,$encodingKey);
       $insertArr['pin'] = $this->Common->freegalEncode($pin,$encodingKey);
        
        if(0 == $cron_call) {
          $this->AuthenticationToken->save($insertArr);
        }
        
        $patron_id = $insertArr['patron_id'];
        $response_msg = 'Login Successfull';
        return $this->createsAuthenticationResponseDataObject(true, $response_msg, $token, $patron_id);
      }
		}
	}

  /**
   * Authenticates user by ihdlogin method
   * @param $card
   * @param $pin
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

  private function ihdloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call) {

	$encodingKey = 'bGlicmFyeWlkZWFzMjAxNA==';
    $card = str_replace(" ","",$card);
		$card = strtolower($card);
		$data['card'] = $card;
		$data['pin'] = $pin;
		$patronId = $card;
		$data['patronId'] = $patronId;

    $data['wrongReferral'] = '';
    $data['referral'] = '';
    
    $library_data = $this->authLibraryDetails($library_id);
          
    if($card == ''){


      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif(strlen($card) < $library_data['Library']['minimum_card_length']){


      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif($pin == ''){


      $response_msg = 'Pin not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
		else{

      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }


			$cardNo = substr($card,0,5);
			$data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');
      $data['library_cond'] = $library_id;
			$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                                                  'library_authentication_method' => 'innovative_var_https'),
														'fields' => array('Library.id','Library.library_authentication_method','Library.library_authentication_url',
                                              'Library.library_logout_url','Library.library_territory','Library.library_user_download_limit',                                              'Library.library_block_explicit_content','Library.library_language,library_subdomain'))
			);

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];


			if(count($existingLibraries) == 0){

        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
			else{
				$matches = array();
				$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];
				$data['url'] = $authUrl."/PATRONAPI/".$card."/".$pin."/pintest";

				$data['database'] = 'freegal';
        if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
					$authUrl = Configure::read('App.AuthUrl_AU')."ihdlogin_validation";
				}
				else{
					$authUrl = Configure::read('App.AuthUrl')."ihdlogin_validation";
				}

				$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

        $resultAnalysis[0] = $result['Posts']['status'];
				$resultAnalysis[1] = $result['Posts']['message'];

        if($resultAnalysis[0] == "fail"){


          $response_msg = $resultAnalysis[1];
          return $this->createsAuthenticationResponseDataObject(false, $response_msg);
        }elseif($resultAnalysis[0] == "success"){
          $token = md5(time());
          $insertArr['patron_id'] = $data['patronId'];
					$insertArr['library_id'] = $library_id;
					$insertArr['token'] = $token;
					$insertArr['auth_time'] = time();
					$insertArr['agent'] = $agent;
					$insertArr['auth_method'] = $library_authentication_method;
					
          $insertArr['authtype'] = $authtype;
          $insertArr['card'] = $this->Common->freegalEncode($card,$encodingKey);
          $insertArr['pin'] = $this->Common->freegalEncode($pin,$encodingKey); 
          
          if(0 == $cron_call) { 
            $this->AuthenticationToken->save($insertArr);
          }  

          $patron_id = $insertArr['patron_id'];
          $response_msg = 'Login Successfull';
          return $this->createsAuthenticationResponseDataObject(true, $response_msg, $token, $patron_id);
        }
			}
    }
  }

  /**
   * Authenticates user by ildlogin method
   * @param $card
   * @param $last_name
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

  private function ildloginAuthinticate($card, $last_name, $library_id, $agent, $authtype, $cron_call) {

		$encodingKey = 'bGlicmFyeWlkZWFzMjAxNA==';
		$card = str_replace(" ","",$card);
		$card = strtolower($card);
		$data['card'] = $card;
    $data['name'] = $last_name;
		$patronId = $card;
		$data['patronId'] = $patronId;

    $data['wrongReferral'] = '';
    $data['referral'] = '';
 
    $library_data = $this->authLibraryDetails($library_id);
    
    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
		elseif(strlen($card) < $library_data['Library']['minimum_card_length']){


      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif($last_name == ''){


      $response_msg = 'Last name not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		else{

      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }


			$cardNo = substr($card,0,5);
			$data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');

      $data['library_cond'] = $library_id;
      $existingLibraries = $this->Library->find('all',array(
        'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                              'library_authentication_method' => 'innovative_var_name'),
        'fields' => array('Library.id','Library.library_authentication_method','Library.library_authentication_url','Library.library_logout_url',
                          'Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content',
                          'Library.library_language,library_subdomain'))
			);

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];

			if(count($existingLibraries) == 0){

        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
			}
			else{

				$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];
				$url = $authUrl."/PATRONAPI/".$card."/dump";

				$data['url'] = $url;
				$data['database'] = 'freegal';

        if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
					$authUrl = Configure::read('App.AuthUrl_AU')."ildlogin_validation";
				}
				else{
					$authUrl = Configure::read('App.AuthUrl')."ildlogin_validation";
				}

				$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

        $resultAnalysis[0] = $result['Posts']['status'];
				$resultAnalysis[1] = $result['Posts']['message'];

        if($resultAnalysis[0] == "fail"){


          $response_msg = $resultAnalysis[1];
          return $this->createsAuthenticationResponseDataObject(false, $response_msg);

				}elseif($resultAnalysis[0] == "success"){
          $token = md5(time());
					$insertArr['patron_id'] = $data['patronId'];
					$insertArr['library_id'] = $library_id;
					$insertArr['token'] = $token;
					$insertArr['auth_time'] = time();
					$insertArr['agent'] = $agent;
					$insertArr['auth_method'] = $library_authentication_method;
          
          $insertArr['authtype'] = $authtype;
          $insertArr['card'] = $this->Common->freegalEncode($card,$encodingKey);
          $insertArr['last_name'] = $last_name;
          
          if(0 == $cron_call) {
            $this->AuthenticationToken->save($insertArr);
          }  

          $patron_id = $insertArr['patron_id'];
          $response_msg = 'Login Successfull';
          return $this->createsAuthenticationResponseDataObject(true, $response_msg, $token, $patron_id );
				}
			}
		}

  }


  /**
   * Authenticates user by ilhdlogin method
   * @param $card
   * @param $last_name
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

  private function ilhdloginAuthinticate($card, $last_name, $library_id, $agent, $authtype, $cron_call) {

		$encodingKey = 'bGlicmFyeWlkZWFzMjAxNA==';
		$card = str_replace(" ","",$card);
		$card = strtolower($card);
		$data['card'] = $card;
    $data['name'] = $last_name;
		$patronId = $card;
		$data['patronId'] = $patronId;

    $data['wrongReferral'] = '';
    $data['referral'] = '';
    
    $library_data = $this->authLibraryDetails($library_id);
    
    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
		elseif(strlen($card) < $library_data['Library']['minimum_card_length']){

      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif($last_name == ''){

      $response_msg = 'Last name not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		else{

      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }


			$cardNo = substr($card,0,5);
			$data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');

      $data['library_cond'] = $library_id;

      $existingLibraries = $this->Library->find('all',array(
        'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                              'library_authentication_method' => 'innovative_var_https_name'),
        'fields' => array('Library.id','Library.library_authentication_method','Library.library_authentication_url','Library.library_logout_url',
                          'Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content',
                          'Library.library_language,library_subdomain'))
			);

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];

			if(count($existingLibraries) == 0){


        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
			}
			else{

				$matches = array();
				$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];
				$data['url'] = $authUrl."/PATRONAPI/".$card."/dump";

        $data['database'] = 'freegal';

        if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
					$authUrl = Configure::read('App.AuthUrl_AU')."ilhdlogin_validation";
        }
				else{
					$authUrl = Configure::read('App.AuthUrl')."ilhdlogin_validation";
				}

				$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

        $resultAnalysis[0] = $result['Posts']['status'];
				$resultAnalysis[1] = $result['Posts']['message'];

        if($resultAnalysis[0] == "fail"){

          $response_msg = $resultAnalysis[1];
          return $this->createsAuthenticationResponseDataObject(false, $response_msg);
				}elseif($resultAnalysis[0] == "success"){
          $token = md5(time());
					$insertArr['patron_id'] = $data['patronId'];
					$insertArr['library_id'] = $library_id;
					$insertArr['token'] = $token;
					$insertArr['auth_time'] = time();
					$insertArr['agent'] = $agent;
					$insertArr['auth_method'] = $library_authentication_method;
          
          $insertArr['authtype'] = $authtype;
          $insertArr['card'] = $this->Common->freegalEncode($card,$encodingKey);
          $insertArr['last_name'] = $last_name;
          
          if(0 == $cron_call) {
            $this->AuthenticationToken->save($insertArr);
          }  

          $patron_id = $insertArr['patron_id'];
          $response_msg = 'Login Successfull';
          return $this->createsAuthenticationResponseDataObject(true, $response_msg, $token, $patron_id);

				}
			}
		}

  }


  /**
   * Authenticates user by slogin method
   * @param $card
   * @param $pin
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

  private function sloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call) {

	$encodingKey = 'bGlicmFyeWlkZWFzMjAxNA==';
    $card = str_replace(" ","",$card);
		$card = strtolower($card);
		$data['card'] = $card;
		$data['pin'] = $pin;
		$patronId = $card;
		$data['patronId'] = $patronId;

    $data['wrongReferral'] = '';
     
    $library_data = $this->authLibraryDetails($library_id);
    
    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif(strlen($card) < $library_data['Library']['minimum_card_length']){

      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif($pin == ''){

      $response_msg = 'Pin not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
		else{

      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }

			$cardNo = substr($card,0,5);
			$data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');

      $data['library_cond'] = $library_id;
			$existingLibraries = $this->Library->find('all',array(
        'conditions' => array('Library.id' => $library_id, 'library_status' => 'active','library_authentication_method' => 'sip2'),
				'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language,library_subdomain'))
      );

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];

			if(count($existingLibraries) == 0){


        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
			else{

        $data['database'] = 'freegal';
        if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
          $authUrl = Configure::read('App.AuthUrl_AU')."slogin_validation";
				}
				else{
					$authUrl = Configure::read('App.AuthUrl')."slogin_validation";
				}
				$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

        $resultAnalysis[0] = $result['Posts']['status'];
				$resultAnalysis[1] = $result['Posts']['message'];

        if($resultAnalysis[0] == "fail"){

          $response_msg = $resultAnalysis[1];
          return $this->createsAuthenticationResponseDataObject(false, $response_msg);
        }elseif($resultAnalysis[0] == "success"){
          $token = md5(time());
          $insertArr['patron_id'] = $data['patronId'];
					$insertArr['library_id'] = $library_id;
					$insertArr['token'] = $token;
					$insertArr['auth_time'] = time();
					$insertArr['agent'] = $agent;
					$insertArr['auth_method'] = $library_authentication_method;
          
          $insertArr['authtype'] = $authtype;
          $insertArr['card'] = $this->Common->freegalEncode($card,$encodingKey);
         $insertArr['pin'] = $this->Common->freegalEncode($pin,$encodingKey);
          
          if(0 == $cron_call) {
            $this->AuthenticationToken->save($insertArr);
          }  

          $patron_id = $insertArr['patron_id'];
          $response_msg = $resultAnalysis[1];
          return $this->createsAuthenticationResponseDataObject(true, $response_msg, $token, $patron_id);

        }
			}
    }
  }


  /**
   * Authenticates user by sdlogin method
   * @param $card
   * @param $pin
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

  private function sdloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call) {
	$encodingKey = 'bGlicmFyeWlkZWFzMjAxNA==';
    $data['card_orig'] = $card;

    $card = str_replace(" ","",$card);
		$data['card'] = $card;
		$data['pin'] = $pin;
		$patronId = $card;
		$data['patronId'] = $patronId;

    $library_data = $this->authLibraryDetails($library_id);

    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif(strlen($card) < $library_data['Library']['minimum_card_length']){

      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif($pin == ''){

      $response_msg = 'Pin not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
		else{

      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }


			$cardNo = substr($card,0,5);
			$data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');

      $data['library_cond'] = $library_id;
			$existingLibraries = $this->Library->find('all',array(
        'conditions' => array('Library.id' => $library_id, 'library_status' => 'active','library_authentication_method' => 'sip2_var'),
				'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory','Library.library_logout_url','Library.library_authentication_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_sip_version','Library.library_sip_error','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','library_subdomain'))
      );

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];

      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];
      $data['referral'] = '';
      $data['wrongReferral'] = '';

			if(count($existingLibraries) == 0){

        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
			else{

        if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
					$authUrl = Configure::read('App.AuthUrl_AU')."sdlogin_validation";
				}
				else{
					$authUrl = Configure::read('App.AuthUrl')."sdlogin_validation";
				}
				$data['database'] = 'freegal';
				$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

        $resultAnalysis[0] = $result['Posts']['status'];
				$resultAnalysis[1] = $result['Posts']['message'];

        if($resultAnalysis[0] == "fail"){


          $response_msg = $resultAnalysis[1];
          return $this->createsAuthenticationResponseDataObject(false, $response_msg);
        }elseif($resultAnalysis[0] == "success"){
          $token = md5(time());
          $insertArr['patron_id'] = $data['patronId'];
					$insertArr['library_id'] = $library_id;
					$insertArr['token'] = $token;
					$insertArr['auth_time'] = time();
					$insertArr['agent'] = $agent;
					$insertArr['auth_method'] = $library_authentication_method;
          
          $insertArr['authtype'] = $authtype;
          $insertArr['card'] = $this->Common->freegalEncode($card,$encodingKey);
         $insertArr['pin'] = $this->Common->freegalEncode($pin,$encodingKey);
          
          if(0 == $cron_call) {
            $this->AuthenticationToken->save($insertArr);
          }  

          $patron_id = $insertArr['patron_id'];
          $response_msg = 'Login Successfull';
          return $this->createsAuthenticationResponseDataObject(true, $response_msg, $token, $patron_id);



        }
			}
    }
  }


  /**
   * Authenticates user by plogin method
   * @param $card
   * @param $pin
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

  private function ploginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call) {
	$encodingKey = 'bGlicmFyeWlkZWFzMjAxNA==';

    $card = str_replace(" ","",$card);
		$card = strtolower($card);
		$data['card'] = $card;
		$data['pin'] = $pin;
		$patronId = $card;
		$data['patronId'] = $patronId;

    $data['wrongReferral'] = '';
    $data['referral'] = '';
           
    $library_data = $this->authLibraryDetails($library_id);
                      
    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif(strlen($card) < $library_data['Library']['minimum_card_length']){


      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif($pin == ''){


      $response_msg = 'Pin not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
		else{

      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }

			$cardNo = substr($card,0,5);
			$data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');

      $data['library_cond'] = $library_id;
			$existingLibraries = $this->Library->find('all',array(
        'conditions' => array('Library.id' => $library_id, 'library_status' => 'active','library_authentication_method' => 'soap'),
				'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory','Library.library_soap_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language,library_subdomain'))
			);

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];

			if(count($existingLibraries) == 0){

        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
			else{

        $data['soapUrl'] = $existingLibraries['0']['Library']['library_soap_url'];
				$data['database'] = 'freegal';
				if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
          $authUrl = Configure::read('App.AuthUrl_AU')."plogin_validation";
				}
				else{
					$authUrl = Configure::read('App.AuthUrl')."plogin_validation";
				}
				$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

        $resultAnalysis[0] = $result['Posts']['status'];
				$resultAnalysis[1] = $result['Posts']['message'];

        if($resultAnalysis[0] == "fail"){


          $response_msg = $resultAnalysis[1];
          return $this->createsAuthenticationResponseDataObject(false, $response_msg);
        }elseif($resultAnalysis[0] == "success"){
          $token = md5(time());
          $insertArr['patron_id'] = $data['patronId'];
					$insertArr['library_id'] = $library_id;
					$insertArr['token'] = $token;
					$insertArr['auth_time'] = time();
					$insertArr['agent'] = $agent;
					$insertArr['auth_method'] = $library_authentication_method;
          
          $insertArr['authtype'] = $authtype;
          $insertArr['card'] = $this->Common->freegalEncode($card,$encodingKey);
         $insertArr['pin'] = $this->Common->freegalEncode($pin,$encodingKey);
          
          if(0 == $cron_call) {
            $this->AuthenticationToken->save($insertArr);
          }  

          $patron_id = $insertArr['patron_id'];
          $response_msg = 'Login Successfull';
          return $this->createsAuthenticationResponseDataObject(true, $response_msg, $token, $patron_id);
        }
			}
    }
  }


  /**
   * Authenticates user by indlogin method
   * @param $card
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

	private function indloginAuthinticate($card, $library_id, $agent, $authtype, $cron_call){
	
	$encodingKey = 'bGlicmFyeWlkZWFzMjAxNA==';
    $patronId = $card;
    $data['patronId'] = $patronId;
    $data['card'] = $card;

    $data['wrongReferral'] = '';
    
    $library_data = $this->authLibraryDetails($library_id);
    
    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    elseif(strlen($card) < $library_data['Library']['minimum_card_length']){


      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    else{

      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }

      $cardNo = substr($card,0,5);
      $data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
      $this->Library->Behaviors->attach('Containable');

      $data['library_cond'] = $library_id;
      $existingLibraries = $this->Library->find('all',array(
        'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                              'library_authentication_method' => 'innovative_var_wo_pin'),
        'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory','Library.library_authentication_url',
                          'Library.library_logout_url',
                          'Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content',
                          'Library.library_language,library_subdomain'))
      );

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];

      if(count($existingLibraries) == 0){

        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
			else{

        $authUrl = $existingLibraries['0']['Library']['library_authentication_url'];
				$data['url'] = $authUrl."/PATRONAPI/".$card."/dump";

				$data['database'] = 'freegal';
				if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
          $authUrl = Configure::read('App.AuthUrl_AU')."indlogin_validation";
				}
				else{
					$authUrl = Configure::read('App.AuthUrl')."indlogin_validation";
				}
				$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

        $resultAnalysis[0] = $result['Posts']['status'];
				$resultAnalysis[1] = $result['Posts']['message'];

        if($resultAnalysis[0] == "fail"){


          $response_msg = $resultAnalysis[1];
          return $this->createsAuthenticationResponseDataObject(false, $response_msg);
        }
        elseif($resultAnalysis[0] == "success"){
          $token = md5(time());
          $insertArr['patron_id'] = $data['patronId'];
					$insertArr['library_id'] = $library_id;
					$insertArr['token'] = $token;
					$insertArr['auth_time'] = time();
					$insertArr['agent'] = $agent;
					$insertArr['auth_method'] = $library_authentication_method;
          
          $insertArr['authtype'] = $authtype;
          $insertArr['card'] = $this->Common->freegalEncode($card,$encodingKey);
          
          if(0 == $cron_call) {
            $this->AuthenticationToken->save($insertArr);
          }  

          $patron_id = $insertArr['patron_id'];
          $response_msg = 'Login Successfull';
          return $this->createsAuthenticationResponseDataObject(true, $response_msg, $token, $patron_id);


        }
      }
    }

	}


  /**
   * Authenticates user by inhdlogin method
   * @param $card
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

	private function inhdloginAuthinticate($card, $library_id, $agent, $authtype, $cron_call){

	$encodingKey = 'bGlicmFyeWlkZWFzMjAxNA==';
    $patronId = $card;
    $data['patronId'] = $patronId;
    $data['card'] = $card;

    $data['wrongReferral'] = '';
    $data['referral'] = '';
    
    $library_data = $this->authLibraryDetails($library_id);
    
    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    elseif(strlen($card) < $library_data['Library']['minimum_card_length']){


      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    else{

      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }

      $cardNo = substr($card,0,5);
      $data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
      $this->Library->Behaviors->attach('Containable');

      $data['library_cond'] = $library_id;
      $existingLibraries = $this->Library->find('all',array(
        'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
        'library_authentication_method' => 'innovative_var_https_wo_pin'),
				'fields' => array('Library.id','Library.library_authentication_method','Library.library_authentication_url','Library.library_logout_url',
                          'Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content',
                          'Library.library_language,library_subdomain'))
			);

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];

      if(count($existingLibraries) == 0){

        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
			else{

        $matches = array();
				$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];
				$data['url'] = $authUrl."/PATRONAPI/".$card."/dump";

				$data['database'] = 'freegal';
				if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
					$authUrl = Configure::read('App.AuthUrl_AU')."inhdlogin_validation";
				}
				else{
					$authUrl = Configure::read('App.AuthUrl')."inhdlogin_validation";
				}
				$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

        $resultAnalysis[0] = $result['Posts']['status'];
				$resultAnalysis[1] = $result['Posts']['message'];

        if($resultAnalysis[0] == "fail"){


          $response_msg = $resultAnalysis[1];
          return $this->createsAuthenticationResponseDataObject(false, $response_msg);
        }
        elseif($resultAnalysis[0] == "success"){
          $token = md5(time());
          $insertArr['patron_id'] = $data['patronId'];
					$insertArr['library_id'] = $library_id;
					$insertArr['token'] = $token;
					$insertArr['auth_time'] = time();
					$insertArr['agent'] = $agent;
					$insertArr['auth_method'] = $library_authentication_method;
          
          $insertArr['authtype'] = $authtype;
          $insertArr['card'] = $this->Common->freegalEncode($card,$encodingKey);
          
          if(0 == $cron_call) {
            $this->AuthenticationToken->save($insertArr);
          }
          
          $patron_id = $insertArr['patron_id'];
          $response_msg = 'Login Successfull';
          return $this->createsAuthenticationResponseDataObject(true, $response_msg, $token, $patron_id);
        }
      }
    }

	}


    /**
   * Authenticates user by snlogin method
   * @param $card
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

	private function snloginAuthinticate($card, $library_id, $agent, $authtype, $cron_call){

	$encodingKey = 'bGlicmFyeWlkZWFzMjAxNA==';
    $patronId = $card;
    $data['patronId'] = $patronId;
    $data['card'] = $card;
    $data['wrongReferral'] = '';
                
    $library_data = $this->authLibraryDetails($library_id);
                
    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    elseif(strlen($card) < $library_data['Library']['minimum_card_length']){


      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    else{

      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }

      $cardNo = substr($card,0,5);
      $data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
      $this->Library->Behaviors->attach('Containable');

      $data['library_cond'] = $library_id;
      $existingLibraries = $this->Library->find('all',array(
        'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                              'library_authentication_method' => 'sip2_wo_pin'),
        'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language,library_subdomain'))
			);

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];

      if(count($existingLibraries) == 0){

        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
			else{

				$data['database'] = 'freegal';

        if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
          $authUrl = Configure::read('App.AuthUrl_AU')."snlogin_validation";
				}
				else{
					$authUrl = Configure::read('App.AuthUrl')."snlogin_validation";
				}

				$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

        $resultAnalysis[0] = $result['Posts']['status'];
				$resultAnalysis[1] = $result['Posts']['message'];

        if($resultAnalysis[0] == "fail"){

          $response_msg = $resultAnalysis[1];
          return $this->createsAuthenticationResponseDataObject(false, $response_msg);
        }
        elseif($resultAnalysis[0] == "success"){
          $token = md5(time());
          $insertArr['patron_id'] = $data['patronId'];
					$insertArr['library_id'] = $library_id;
					$insertArr['token'] = $token;
					$insertArr['auth_time'] = time();
					$insertArr['agent'] = $agent;
					$insertArr['auth_method'] = $library_authentication_method;
          
          $insertArr['authtype'] = $authtype;
          $insertArr['card'] = $this->Common->freegalEncode($card,$encodingKey);
          
          if(0 == $cron_call) {
            $this->AuthenticationToken->save($insertArr);
          }
          
          $patron_id = $insertArr['patron_id'];
          $response_msg = 'Login Successfull';
          return $this->createsAuthenticationResponseDataObject(true, $response_msg, $token, $patron_id);

        }
      }
    }

	}


  /**
   * Authenticates user by sndlogin method
   * @param $card
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

	private function sndloginAuthinticate($card, $library_id, $agent, $authtype, $cron_call){
	$encodingKey = 'bGlicmFyeWlkZWFzMjAxNA==';
    $data['card_orig'] = $card;

    $card = str_replace(" ","",$card);
    $patronId = $card;
    $data['patronId'] = $patronId;
    $data['card'] = $card;
    
    $library_data = $this->authLibraryDetails($library_id);

    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    elseif(strlen($card) < $library_data['Library']['minimum_card_length']){

      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    else{

      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }


      $cardNo = substr($card,0,5);
      $data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
      $this->Library->Behaviors->attach('Containable');

      $data['library_cond'] = $library_id;
      $existingLibraries = $this->Library->find('all',array(
        'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                              'library_authentication_method' => 'sip2_var_wo_pin'),
				'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_sip_version','Library.library_sip_error','Library.library_user_download_limit', 'library_subdomain','Library.library_block_explicit_content','Library.library_language'))
			);

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];
      $data['referral'] = '';

      if(count($existingLibraries) == 0){

        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
			else{

        if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
					$authUrl = Configure::read('App.AuthUrl_AU')."sndlogin_validation";
				}
				else{
					$authUrl = Configure::read('App.AuthUrl')."sndlogin_validation";
				}
				$data['database'] = 'freegal';
				$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

        $resultAnalysis[0] = $result['Posts']['status'];
				$resultAnalysis[1] = $result['Posts']['message'];

        if($resultAnalysis[0] == "fail"){


          $response_msg = $resultAnalysis[1];
          return $this->createsAuthenticationResponseDataObject(false, $response_msg);
        }
        elseif($resultAnalysis[0] == "success"){
          $token = md5(time());
          $insertArr['patron_id'] = $data['patronId'];
					$insertArr['library_id'] = $library_id;
					$insertArr['token'] = $token;
					$insertArr['auth_time'] = time();
					$insertArr['agent'] = $agent;
					$insertArr['auth_method'] = $library_authentication_method;
          
          $insertArr['authtype'] = $authtype;
          $insertArr['card'] = $this->Common->freegalEncode($card,$encodingKey);
          
          if(0 == $cron_call) {
            $this->AuthenticationToken->save($insertArr);
          }  

          $patron_id = $insertArr['patron_id'];
          $response_msg = 'Login Successfull.';
          return $this->createsAuthenticationResponseDataObject(true, $response_msg, $token, $patron_id);
        }
      }
    }

	}

  /**
   * Authenticates user by clogin method
   * @param $card
   * @param $pin
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */
	private function cloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call){

	$encodingKey = 'bGlicmFyeWlkZWFzMjAxNA==';
    $patronId = $card;
    $data['patronId'] = $patronId;
    $data['card'] = $card;
    $data['wrongReferral'] = '';

    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    elseif(strlen($card) < 5){

      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    else{

      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }

      $cardNo = substr($card,0,5);
      $data['cardNo'] = $cardNo;
      $data['referral']='';
      $data['pin'] = $pin;
      $this->Library->recursive = -1;
      $library_authentication_method = 'curl_method';
      $data['database'] = 'freegal';

      $library_cond = $library_id;
      $data['library_cond'] = $library_cond;
      $existingLibraries = $this->Library->find('all',array(
              'conditions' => array('library_status' => 'active','library_authentication_method' => 'curl_method','id' => $library_cond),
              'fields' => array('Library.id','Library.library_territory','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language,library_subdomain')
              )
             );


      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];

      if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
				$authUrl1 = Configure::read('App.AuthUrl_AU')."clogin_validation";
			}
			else{
				$authUrl1 = Configure::read('App.AuthUrl')."clogin_validation";
			}

      $result = $this->AuthRequest->getAuthResponse($data,$authUrl1);
      $resultAnalysis[0] = $result['Posts']['status'];
      $resultAnalysis[1] = $result['Posts']['message'];

      if($resultAnalysis[0] == "fail"){

        $response_msg = $resultAnalysis[1];
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
      elseif($resultAnalysis[0] == "success"){
        $token = md5(time());
        $insertArr['patron_id'] = $data['patronId'];
        $insertArr['library_id'] = $library_id;
        $insertArr['token'] = $token;
        $insertArr['auth_time'] = time();
        $insertArr['agent'] = $agent;
        $insertArr['auth_method'] = $library_authentication_method;
        
        $insertArr['authtype'] = $authtype;
        $insertArr['card'] = $this->Common->freegalEncode($card,$encodingKey);
       $insertArr['pin'] = $this->Common->freegalEncode($pin,$encodingKey);
        
        if(0 == $cron_call) {
          $this->AuthenticationToken->save($insertArr);
        }
        
        $patron_id = $insertArr['patron_id'];
        $response_msg = 'Login Successfull';
        return $this->createsAuthenticationResponseDataObject(true, $response_msg, $token, $patron_id);

      }

    }
  }



    /**
   * Authenticates user by referral_url & ezproxy method
   * @param $card
   * @param $pin
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */
	private function referralAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call){
    $encodingKey = 'bGlicmFyeWlkZWFzMjAxNA==';
    $card = trim($card);
    $data['card'] = $card;
    $data['pin'] = $pin;
    $patronId = $card;
    $data['patronId'] = $patronId;
    
    $library_data = $this->authLibraryDetails($library_id);
   
    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    elseif(strlen($card) < $library_data['Library']['minimum_card_length']){

      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    else{

      if( ('' == trim($library_data['Library']['mobile_auth'])) ) {

        $response_msg = 'Sorry, your library authentication is not supported at this time.  Please contact your library for further information.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }

      $cardNo = substr($card,0,5);
      $data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
      $this->Library->Behaviors->attach('Containable');

      if( ('referral_url' == trim($library_data['Library']['library_authentication_method'])) ) {
      
        $existingLibraries = $this->Library->find('all',array(
                    'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                                          'library_authentication_method' => 'referral_url'),
                    'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory','Library.library_authentication_url',
                                      'Library.library_logout_url','Library.library_territory','Library.library_user_download_limit',
                                      'Library.library_block_explicit_content','Library.library_language', 'mobile_auth'))
        );
      } else {
        
        $existingLibraries = $this->Library->find('all',array(
                    'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                                          'library_authentication_method' => 'ezproxy'),
                    'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory','Library.library_authentication_url',
                                      'Library.library_logout_url','Library.library_territory','Library.library_user_download_limit',
                                      'Library.library_block_explicit_content','Library.library_language', 'mobile_auth'))
        );
          
      } 


      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $mobile_auth = trim($existingLibraries[0]['Library']['mobile_auth']);

      $auth_url = str_ireplace('=CARDNUMBER', '='.$data['patronId'], $mobile_auth);
      $auth_url = str_ireplace('=PIN', '='.$data['pin'], $auth_url);

      if(count($existingLibraries) == 0){

        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
      else{

        if($existingLibraries[0]['Library']['library_territory'] == 'AU'){
          $methodUrl = Configure::read('App.AuthUrl_AU')."ezproxylogin_validation";
        }
				else{
					$methodUrl = Configure::read('App.AuthUrl')."ezproxylogin_validation";
				}
          
        $data['auth_url'] = $auth_url;
        $data['database'] = 'freegal';

        $resp = $this->AuthRequest->getAuthResponse($data, $methodUrl);
        $resp = $resp['Posts']['message'];
	
		if(is_array($resp)){
        	if(empty($resp)){
                $resp = "";
            }
        }
          $checkValidXml = null;
	  	if(strpos($resp,'<')) {
          $checkValidXml = simplexml_load_string($resp);
        }
          if( isset($checkValidXml->Status) ) {

            if( ( isset($checkValidXml->Status) && ('' != $checkValidXml->Status) ) &&  ( isset($checkValidXml->LibraryCard) && ('' != $checkValidXml->LibraryCard) ) ) {
				
              if(1 == $checkValidXml->Status) {
							
                $response_patron_id = $checkValidXml->LibraryCard;
				  
                $token = md5(time());
                $insertArr['patron_id'] = trim($response_patron_id);
                $insertArr['library_id'] = $library_id;
                $insertArr['token'] = $token;
                $insertArr['auth_time'] = time();
                $insertArr['agent'] = $agent;
                $insertArr['auth_method'] = $library_authentication_method;
                
                $insertArr['authtype'] = $authtype;
                $insertArr['card'] = $this->Common->freegalEncode($card,$encodingKey);
               $insertArr['pin'] = $this->Common->freegalEncode($pin,$encodingKey);
                
                if(0 == $cron_call) {
                  $this->AuthenticationToken->save($insertArr);
                }  

                $patron_id = $insertArr['patron_id'];
                $response_msg = 'Login Successfull';
                return $this->createsAuthenticationResponseDataObject(true, $response_msg, $token, $patron_id);
			
              } else {
                $response_msg = 'Login Failed';
                return $this->createsAuthenticationResponseDataObject(false, $response_msg);
              }			
			
            }else{
              $response_msg = 'Login Failed';
              return $this->createsAuthenticationResponseDataObject(false, $response_msg);
            } 	  
          }  
 
        $resp = trim(strip_tags($resp));
        $resp = preg_replace("/\s+/", "", $resp);
        
        if(false === stripos(strtolower($resp), 'ok')) {
          $response_msg = 'Login Failed';
          return $this->createsAuthenticationResponseDataObject(false, $response_msg);
        } else {
          
          $response_patron_id = $this->getTmpPatronID($library_id, $card, $resp);
                    
          $token = md5(time());
          $insertArr['patron_id'] = trim($response_patron_id);
					$insertArr['library_id'] = $library_id;
					$insertArr['token'] = $token;
					$insertArr['auth_time'] = time();
					$insertArr['agent'] = $agent;
					$insertArr['auth_method'] = $library_authentication_method;
          
          $insertArr['authtype'] = $authtype;
          $insertArr['card'] = $this->Common->freegalEncode($card,$encodingKey);
         $insertArr['pin'] = $this->Common->freegalEncode($pin,$encodingKey);
        
          if(0 == $cron_call) {
            $this->AuthenticationToken->save($insertArr);
          }
          
          $patron_id = $insertArr['patron_id'];
          $response_msg = 'Login Successfull';
          return $this->createsAuthenticationResponseDataObject(true, $response_msg, $token, $patron_id);

        }
      }
    }

  }


  /**
   * Authenticates user by idloginAuthinticate method
   * @param $card
   * @param $pin
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

  private function idloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call) {

	$encodingKey = 'bGlicmFyeWlkZWFzMjAxNA==';
    $card = str_replace(" ","",$card);
		$card = strtolower($card);
		$data['card'] = $card;
		$data['pin'] = $pin;
		$patronId = $card;
    $data['wrongReferral'] = '';
    $data['referral'] = '';
             
    $library_data = $this->authLibraryDetails($library_id);
                            
    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif(strlen($card) < $library_data['Library']['minimum_card_length']){

      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif($pin == ''){


      $response_msg = 'Pin not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
		else{

      if( ('' == trim(Configure::read('App.AuthUrl'))) ) {

        $response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }

			$cardNo = substr($card,0,5);
			$data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');


			$existingLibraries = $this->Library->find('all',array(
        'conditions' => array('Library.id' => $library_id, 'library_status' => 'active','library_authentication_method' => 'innovative_var'),
				'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory','Library.library_soap_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_authentication_url,library_subdomain'))
			);

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];

			if(count($existingLibraries) == 0){

        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
			else{


		  $data['library_cond'] = $library_id;

          $authUrl = $existingLibraries['0']['Library']['library_authentication_url'];
		  		$data['url'] = $authUrl."/PATRONAPI/".$card."/".$pin."/pintest";

					$data['database'] = 'freegal';
					if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
						$authUrl = Configure::read('App.AuthUrl_AU')."idlogin_validation";
					}
					else{
						$authUrl = Configure::read('App.AuthUrl')."idlogin_validation";
					}



					$result = $this->AuthRequest->getAuthResponse($data,$authUrl);




        $resultAnalysis[0] = $result['Posts']['status'];
				$resultAnalysis[1] = $result['Posts']['message'];

        if($resultAnalysis[0] == "fail"){


          $response_msg = $resultAnalysis[1];
          return $this->createsAuthenticationResponseDataObject(false, $response_msg);
        }elseif($resultAnalysis[0] == "success"){
          $token = md5(time());
          $insertArr['patron_id'] = $patronId;
					$insertArr['library_id'] = $library_id;
					$insertArr['token'] = $token;
					$insertArr['auth_time'] = time();
					$insertArr['agent'] = $agent;
					$insertArr['auth_method'] = $library_authentication_method;
          
          $insertArr['authtype'] = $authtype;
          $insertArr['card'] = $this->Common->freegalEncode($card,$encodingKey);
         $insertArr['pin'] = $this->Common->freegalEncode($pin,$encodingKey);
          
          if(0 == $cron_call) {
            $this->AuthenticationToken->save($insertArr);
          }
          
          $patron_id = $insertArr['patron_id'];
          $response_msg = 'Login Successfull';
          return $this->createsAuthenticationResponseDataObject(true, $response_msg, $token, $patron_id);
        }
			}
    }
  }

  /**
   * Authenticates user by mndlogin_referrance method
   * @param $card
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

	private function mndloginAuthinticate($card, $library_id, $agent, $authtype, $cron_call){

	$encodingKey = 'bGlicmFyeWlkZWFzMjAxNA==';
    $data['wrongReferral'] = '';

    $card = str_replace(" ","",$card);
    $card = strtolower($card);
		$data['card'] = $card;

    $patronId = $card;
		$data['patronId'] = $patronId;


    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    else{

      $cardNo = substr($card,0,5);
      $data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
      $this->Library->Behaviors->attach('Containable');

      $data['library_cond'] = $library_id;
      $existingLibraries = $this->Library->find('all',array(
        'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                              'library_authentication_method' => 'mndlogin_reference'),
				'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_sip_version','Library.library_sip_error','Library.library_user_download_limit', 'library_subdomain','Library.library_block_explicit_content','Library.library_language'))
			);

      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];
      $data['referral'] = '';

      if(count($existingLibraries) == 0){

        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
			else{

        $login_res = $this->Card->find('first',array('conditions' => array('Card.card_number' => $card , 'Card.library_id' =>  $library_id) , 'fields' => array('id')));

					if(isset($login_res['Card']['id'])) {

            $token = md5(time());
            $insertArr['patron_id'] = $data['patronId'];
            $insertArr['library_id'] = $library_id;
            $insertArr['token'] = $token;
            $insertArr['auth_time'] = time();
            $insertArr['agent'] = $agent;
            $insertArr['auth_method'] = $library_authentication_method;
            
            $insertArr['authtype'] = $authtype;
            $insertArr['card'] = $this->Common->freegalEncode($card,$encodingKey);
          
          if(0 == $cron_call) {
            $this->AuthenticationToken->save($insertArr);
          }
          
            $patron_id = $insertArr['patron_id'];
            $response_msg = 'Login Successfull.';
            return $this->createsAuthenticationResponseDataObject(true, $response_msg, $token, $patron_id);

					} else {

            $response_msg = 'Login Failed.';
            return $this->createsAuthenticationResponseDataObject(false, $response_msg);
          }

      }
    }

	}

  /**
   * Authenticates user by mdlogin_reference method
   * @param $card
   * @param $pin
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

	private function mdloginAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call){

	$encodingKey = 'bGlicmFyeWlkZWFzMjAxNA==';   
    $data['wrongReferral'] = '';
    
    $card = str_replace(" ","",$card);
    $card = strtolower($card);			
		$data['card'] = $card;
    
    $data['pin'] = $pin;
  
    $patronId = $card; 
		$data['patronId'] = $patronId;
      
    
    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    else {
    
      if($pin == ''){

        $response_msg = 'Pin not provided';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
      else{    
      
        $cardNo = substr($card,0,5);
        $data['cardNo'] = $cardNo;

        $this->Library->recursive = -1;
        $this->Library->Behaviors->attach('Containable');
        
        $data['library_cond'] = $library_id; 
        $existingLibraries = $this->Library->find('all',array(
          'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                                'library_authentication_method' => 'mdlogin_reference'),
          'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_sip_version','Library.library_sip_error','Library.library_user_download_limit', 'library_subdomain','Library.library_block_explicit_content','Library.library_language'))
        );

        $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
        $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];
        $data['referral'] = '';

        if(count($existingLibraries) == 0){

          $response_msg = 'Invalid credentials provided.';
          return $this->createsAuthenticationResponseDataObject(false, $response_msg);
        }
        else{
       
          $login_res = $this->Card->find('first',array('conditions' => array('Card.card_number' => $card , 'Card.pin' => $pin , 'Card.library_id' =>  $library_id) , 'fields' => array('id')));
                  
            if(isset($login_res['Card']['id'])) {
          
              $token = md5(time());
              $insertArr['patron_id'] = $data['patronId'];
              $insertArr['library_id'] = $library_id;
              $insertArr['token'] = $token;
              $insertArr['auth_time'] = time();
              $insertArr['agent'] = $agent;
              $insertArr['auth_method'] = $library_authentication_method;
              
              $insertArr['authtype'] = $authtype;
              $insertArr['card'] = $this->Common->freegalEncode($card,$encodingKey);
             $insertArr['pin'] = $this->Common->freegalEncode($pin,$encodingKey);

              if(0 == $cron_call) {
                $this->AuthenticationToken->save($insertArr);
              }  

              $patron_id = $insertArr['patron_id'];
              $response_msg = 'Login Successfull.';
              return $this->createsAuthenticationResponseDataObject(true, $response_msg, $token, $patron_id);
              
            } else {
              
              $response_msg = 'Login Failed.';
              return $this->createsAuthenticationResponseDataObject(false, $response_msg);
            }
        
        }
      }
    }
	}  
  
/**
   * Authenticates user by capita method
   * @param $card
   * @param $pin
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

    private function capitaAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call){
	$encodingKey = 'bGlicmFyeWlkZWFzMjAxNA==';	
	$card = str_replace(" ","", $card);
	$card = strtolower($card);
	$data['card'] = $card;
	$data['pin'] = $pin;
    	$patronId = $card;
	$data['patronId'] = $patronId;
	$data['wrongReferral'] = '';
   	$data['referral'] = '';
          
	$library_data = $this->authLibraryDetails($library_id);

    	if($card == ''){
		$response_msg = 'Card number not provided';
      		return $this->createsAuthenticationResponseDataObject(false, $response_msg);
   	}
	elseif(strlen($card) < $library_data['Library']['minimum_card_length']){
		$response_msg = 'Invalid Card number';
      		return $this->createsAuthenticationResponseDataObject(false, $response_msg);
	}
    	elseif($pin == ''){
		$response_msg = 'Pin not provided';
      		return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    	}
    	else{

      	    if( ('' == trim(Configure::read('App.AuthUrl'))) ) {
		$response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
       		 return $this->createsAuthenticationResponseDataObject(false, $response_msg);
     	     }
	     $cardNo = substr($card,0,5);
	     $data['cardNo'] = $cardNo;
	     $this->Library->recursive = -1;
	     $this->Library->Behaviors->attach('Containable');
             $data['library_cond'] = $library_id;
	
	     $existingLibraries = $this->Library->find('all',array('conditions' => array('library_status' => 'active','library_authentication_method' => 'capita','Library.id' => $library_id),'fields' => array ('Library.id','Library.library_authentication_method','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type','Library.library_host_name' ,'Library.library_port_no','Library.library_subdomain')
		)
	     );
	   $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
	   $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];
	    if(count($existingLibraries) == 0){
		 $response_msg = 'Invalid credentials provided.';
    		 return $this->createsAuthenticationResponseDataObject(false, $response_msg);
	    }
	    else{
		$matches = array();
		$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];
		$data['database'] = 'freegal';
		$data['library_authentication_url'] = $existingLibraries['0']['Library']['library_authentication_url'];
		
		if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
			$authUrl = Configure::read('App.AuthUrl_AU')."capita_validation";
		}
		else{
			$authUrl = Configure::read('App.AuthUrl')."capita_validation";
		}
		$result = $this->AuthRequest->getAuthResponse($data,$authUrl);
		$resultAnalysis[0] = $result['Posts']['status'];
		$resultAnalysis[1] = $result['Posts']['message'];
		if($resultAnalysis[0] == "fail"){
			$response_msg = $resultAnalysis[1];
        		return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif($resultAnalysis[0] == "success"){
			$token = md5(time());
       			$insertArr['patron_id'] = $data['patronId'];
			$insertArr['library_id'] = $library_id;
			$insertArr['token'] = $token;
			$insertArr['auth_time'] = time();
			$insertArr['agent'] = $agent;
			$insertArr['auth_method'] = $library_authentication_method;
			$insertArr['authtype'] = $authtype;
       	 		$insertArr['card'] = $this->Common->freegalEncode($card,$encodingKey);
        		$insertArr['pin'] = $pin;
			if(0 == $cron_call) {
          			$this->AuthenticationToken->save($insertArr);
        		}
        
      			$patron_id = $insertArr['patron_id'];
        		$response_msg = 'Login Successfull';
        		return $this->createsAuthenticationResponseDataObject(true, $response_msg, $token, $patron_id);
		}
	    }
		
	}

    }

/**
   * Authenticates user by symws method
   * @param $card
   * @param $pin
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */

    private function symwsAuthinticate($card, $pin, $library_id, $agent, $authtype, $cron_call){
	$encodingKey = 'bGlicmFyeWlkZWFzMjAxNA==';	
	$card = str_replace(" ","", $card);
	$card = strtolower($card);
	$data['card'] = $card;
	$data['pin'] = $pin;
    	$patronId = $card;
	$data['patronId'] = $patronId;
	$data['wrongReferral'] = '';
   	$data['referral'] = '';
          
	$library_data = $this->authLibraryDetails($library_id);

    	if($card == ''){
		$response_msg = 'Card number not provided';
      		return $this->createsAuthenticationResponseDataObject(false, $response_msg);
   	}
	elseif(strlen($card) < $library_data['Library']['minimum_card_length']){
		$response_msg = 'Invalid Card number';
      		return $this->createsAuthenticationResponseDataObject(false, $response_msg);
	}
    	elseif($pin == ''){
		$response_msg = 'Pin not provided';
      		return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    	}
    	else{

      	    if( ('' == trim(Configure::read('App.AuthUrl'))) ) {
		$response_msg = 'Sorry, your libraries authentication is not currently support at this time.';
       		 return $this->createsAuthenticationResponseDataObject(false, $response_msg);
     	     }
	     $cardNo = substr($card,0,5);
	     $data['cardNo'] = $cardNo;
	     $this->Library->recursive = -1;
	     $this->Library->Behaviors->attach('Containable');
             $data['library_cond'] = $library_id;
	
	     $existingLibraries = $this->Library->find('all',array('conditions' => array('library_status' => 'active','library_authentication_method' => 'symws','Library.id' => $library_id),'fields' => array ('Library.id','Library.library_authentication_method','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type','Library.library_host_name' ,'Library.library_port_no','Library.library_subdomain')
		)
	     );
	   $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
	   $data['subdomain'] = $existingLibraries[0]['Library']['library_subdomain'];
	    if(count($existingLibraries) == 0){
		 $response_msg = 'Invalid credentials provided.';
    		 return $this->createsAuthenticationResponseDataObject(false, $response_msg);
	    }
	    else{
		$matches = array();
		$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];
		$data['database'] = 'freegal';
		$data['library_host_name'] = $existingLibraries['0']['Library']['library_host_name'];
		$data['library_authentication_url'] = $existingLibraries['0']['Library']['library_authentication_url'];
		
		if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
			$authUrl = Configure::read('App.AuthUrl_AU')."symws_validation";
		}
		else{
			$authUrl = Configure::read('App.AuthUrl')."symws_validation";
		}
		$result = $this->AuthRequest->getAuthResponse($data,$authUrl);
		$resultAnalysis[0] = $result['Posts']['status'];
		$resultAnalysis[1] = $result['Posts']['message'];
		if($resultAnalysis[0] == "fail"){
			$response_msg = $resultAnalysis[1];
        		return $this->createsAuthenticationResponseDataObject(false, $response_msg);
		}
		elseif($resultAnalysis[0] == "success"){
			$token = md5(time());
       			$insertArr['patron_id'] = $data['patronId'];
			$insertArr['library_id'] = $library_id;
			$insertArr['token'] = $token;
			$insertArr['auth_time'] = time();
			$insertArr['agent'] = $agent;
			$insertArr['auth_method'] = $library_authentication_method;
			$insertArr['authtype'] = $authtype;
       	 		$insertArr['card'] = $this->Common->freegalEncode($card,$encodingKey);
        		$insertArr['pin'] = $pin;
			if(0 == $cron_call) {
          			$this->AuthenticationToken->save($insertArr);
        		}
        
      			$patron_id = $insertArr['patron_id'];
        		$response_msg = 'Login Successfull';
        		return $this->createsAuthenticationResponseDataObject(true, $response_msg, $token, $patron_id);
		}
	    }
		
	}

    }


  /**
   * Function Name : updateUserDetails
   * Desc : To update users details
   * @param string $authentication_token
   * @param string $fname
   * @param string $lname
   * @param string $mail
   * @param string $new_pass
   * @param string $old_pass
	 * @return SuccessResponseType[]
   */
	function updateUserDetails($authentication_token, $fname, $lname, $mail, $new_pass, $old_pass) {

    if(!($this->isValidAuthenticationToken($authentication_token))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }

    $user_id = $this->getPatronIdFromAuthenticationToken($authentication_token);

    $arr_fields_update = array('first_name' => $fname, 'last_name' => $lname, 'email' => $mail, 'password' => $new_pass);

    $arr_status = array();

    if(is_array($arr_fields_update)) {
      foreach($arr_fields_update AS $key => $val) {

        if('' != trim($val)){
          if('password' == $key) {

            $password = $this->User->find('first',array(
                'fields' => array('password'),
                'conditions' => array('id' => $user_id),
                'recursive' => -1,
              )
            );

            $check_password   = Security::hash(Configure::read('Security.salt').$old_pass);
            if($password['User']['password'] != $check_password) {

              throw new SOAPFault('Soap:client', 'Invalid Old Password');
            }

            $encrypt_password = Security::hash(Configure::read('Security.salt').$val);
            $arr_status[] = $this->User->query("UPDATE users SET " . $key." = '" . $encrypt_password ."' WHERE id = '".$user_id."'");
          }
          else {
            $arr_status[] = $this->User->query("UPDATE users SET ".$key." = '".$val."' WHERE id = '".$user_id."'");
          }
        }
      }
    }

    foreach($arr_status AS $val) {
      if(0 == $val) {
        throw new SOAPFault('Soap:client', 'Freegal is unable to update the information. Please try again later.');
        break;
      }
    }


    $msg = 'User updated successfully';
    return $this->createsSuccessResponseObject(true, $msg);

  }



  /**
   * Function Name : getUserDetails
   * Desc : To get users details
   * @param string $authentication_token
	 * @return UserDataType[]
   */
	function getUserDetails($authentication_token) {


    if(!($this->isValidAuthenticationToken($authentication_token))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }

    $user_id = $this->getPatronIdFromAuthenticationToken($authentication_token);

    $user = $this->User->find('first',array(
      'fields' => array('first_name', 'last_name', 'email'),
      'conditions' => array('User.id' => $user_id),
      )
    );


    $obj = new UserDataType;
    $obj->first_name               = (string)$user['User']['first_name'];
    $obj->last_name                = (string)$user['User']['last_name'];
    $obj->email                    = (string)$user['User']['email'];

    $user_list = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'UserDataType');
    $data = new SoapVar($user_list,SOAP_ENC_OBJECT,null,null,'ArrayUserDataType');

    return $data;

  }

  /**
   * Function Name : getPatronIdFromAuthenticationToken
   * Desc : To fetch patron id from authentication token
   * @param string $token
	 * @return array
   */

  private function getPatronIdFromAuthenticationToken($token){


    $authenticationtoken = $this->AuthenticationToken->find('first',array(
      'fields' => array('patron_id'),
      'conditions' => array('token' => $token))
    );

    return $authenticationtoken['AuthenticationToken']['patron_id'];

  }


  /**
   * Function Name : getLibraryIdFromAuthenticationToken
   * Desc : To fetch library id from authentication token
   * @param string $token
	 * @return array
   */

  private function getLibraryIdFromAuthenticationToken($token){


    $authenticationtoken = $this->AuthenticationToken->find('first',array(
      'fields' => array('library_id'),
      'conditions' => array('token' => $token))
    );

    return $authenticationtoken['AuthenticationToken']['library_id'];

  }

  /**
   * Function Name : getAuthMethodFromAuthenticationToken
   * Desc : To fetch library's auth method from authentication token
   * @param string $token
	 * @return array
   */

  private function getAuthMethodFromAuthenticationToken($token){


    $authenticationtoken = $this->AuthenticationToken->find('first',array(
      'fields' => array('auth_method'),
      'conditions' => array('token' => $token))
    );

    return $authenticationtoken['AuthenticationToken']['auth_method'];

  }
  
  
  /**
   * Function Name : getLibraryTerritory
   * Desc : To fetch library's Territory
   * @param string $libid
	 * @return string
   */

  private function getLibraryTerritory($libid){


    $Library = $this->Library->find('first',array(
      'fields' => array('Library.library_territory'),
      'conditions' => array('Library.id' => $libid),
      'recursive' => -1,
    )
    );

    return $Library['Library']['library_territory'];

  }


  /**
   * Function Name : isValidAuthenticationToken
   * Desc : To validate authentication token
	 * @return int
   */

  private function isValidAuthenticationToken($token){

	return $this->AuthenticationToken->find('count', array('conditions' => array('token' => $token)));

  }


  /**
   * Function Name : regenerateSongDownloadRequest
   * Desc : Actions that is used for regenerating download URL download
   * @param string $authentication_token
   * @param string $prodId
   * @param string $agent
	 * @return SongDownloadSuccessType[]
   */

  function regenerateSongDownloadRequest($authentication_token, $prodId, $agent) {

    if(!($this->isValidAuthenticationToken($authentication_token))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }

    $product_detail = $this->getProductDetail($prodId);
    $prodId = $product_detail['Product']['ProdID'];
    $provider_type = $product_detail['Product']['provider_type'];
    
    
    $data = $this->Song->find('first',
      array('joins' =>
        array(
          array(
            'table' => 'File',
            'alias' => 'f',
            'type' => 'inner',
            'foreignKey' => false,

            'conditions'=> array('f.FileID = Song.FullLength_FIleID', 'Song.ProdID = ' . $prodId, 'Song.provider_type' => $provider_type)
          )
        )
      )
    );

    $CdnPath = $data['Full_Files']['CdnPath'];
    $SaveAsName = $data['Full_Files']['SaveAsName'];

    $songUrl = $this->Token->regularToken( $CdnPath . "/" . $SaveAsName);
    $finalSongUrl = Configure::read('App.Music_Path') . $songUrl;
    
    $wishlist = 0;
    return $this->createSongDownloadSuccessObject('Download permitted.', $finalSongUrl, true, 0, 0, 0);


  }

  /**
   * Function Name : regenerateVideoDownloadRequest
   * Desc : Actions that is used for regenerating download URL for video
   * @param string $authentication_token
   * @param string $prodId
   * @param string $agent
	 * @return VideoDownloadSuccessType[]
   */

  function regenerateVideoDownloadRequest($authentication_token, $prodId, $agent) {

    if(!($this->isValidAuthenticationToken($authentication_token))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }

    $product_detail = $this->getProductDetail($prodId);
    $prodId = $product_detail['Product']['ProdID'];
    $provider_type = $product_detail['Product']['provider_type'];
    
    
    $data = $this->Video->find('first',
      array('joins' =>
        array(
          array(
            'table' => 'File',
            'alias' => 'f',
            'type' => 'inner',
            'foreignKey' => false,

            'conditions'=> array('f.FileID = Video.FullLength_FIleID', 'Video.ProdID = ' . $prodId, 'Video.provider_type' => $provider_type)
          )
        )
      )
    );

    $CdnPath = $data['Full_Files']['CdnPath'];
    $SaveAsName = $data['Full_Files']['SaveAsName'];

    $songUrl = $this->Token->regularToken($CdnPath . "/" . $SaveAsName);
    $finalSongUrl = Configure::read('App.Music_Path') . $songUrl;

    return $this->createVideoDownloadSuccessObject('Download permitted.', $finalSongUrl, true, 0, 0, 0);
    

  }
  
  
  /**
   * Function Name : songDownloadRequest
   * Desc : Actions that is used for updating user download request for audio
   * @param string $authentication_token
   * @param string $prodId
   * @param string $agent
	 * @return SongDownloadSuccessType[]
   */

  function songDownloadRequest($authentication_token, $prodId, $agent) {
    
    
    if(!($this->isValidAuthenticationToken($authentication_token))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }
    
    $product_detail = $this->getProductDetail($prodId);
    $prodId = $product_detail['Product']['ProdID'];
    $provider_type = $product_detail['Product']['provider_type'];

    $patId = $this->getPatronIdFromAuthenticationToken($authentication_token);
    $libId = $this->getLibraryIdFromAuthenticationToken($authentication_token);
    
    $this->Library->recursive = -1;
    $libraryDetails = $this->Library->find('first', array('conditions' => array('id' => $libId)));
    
    
    $siteConfigSQL = "SELECT * from siteconfigs WHERE soption = 'maintain_ldt'";
    $siteConfigData = $this->Album->query($siteConfigSQL);
    $maintainLatestDownload = (($siteConfigData[0]['siteconfigs']['svalue']==1)?true:false);
		 
    $siteConfigSQL = "SELECT * from siteconfigs WHERE soption = 'single_channel'";
    $siteConfigData = $this->Album->query($siteConfigSQL);
    $checkValidation = (($siteConfigData[0]['siteconfigs']['svalue']==1)?true:false);  
        
    $log_name = 'stored_procedure_app_log_'.date('Y_m_d');
    $log_id = md5(time());
    $log_data = PHP_EOL."----------Request (".$log_id.") Start----------------".PHP_EOL;
        
    if($checkValidation){
      
      $validationResult = $this->Downloads->validateDownload($prodId, $provider_type, true, $libraryDetails['Library']['library_territory'], $patId, $agent, $libId);
      
      $log_data .=  "DownloadComponentParameters-ProdId= '".$prodId."':DownloadComponentParameters-Provider_type= '".$provider_type."':DownloadComponentParameters-isMobileDownload= 'true':DownloadComponentParameters-Territory= '".$libraryDetails['Library']['library_territory']."':DownloadComponentParameters-PatronID='".$patId."':DownloadComponentParameters-Agent='".$agent."':DownloadComponentParameters-LibID='".$libId."':DownloadComponentResponse-Status='".$validationResult[0]."':DownloadComponentResponse-Msg='".$validationResult[1]."':DownloadComponentResponse-ErrorTYpe='".$validationResult[2]."'"; 

      if(false === $validationResult[0])  {
        
        $log_data .= PHP_EOL."---------Request (".$log_id.") End----------------".PHP_EOL;
        $this->log($log_data, $log_name);
        
        if(5 == $validationResult[2]) {
          throw new SOAPFault('Soap:client', 'Requested song is not allowed to download.');
        }
            
        if(4 == $validationResult[2]) {
          throw new SOAPFault('Soap:client', 'Requested song is not allowed to download.');
        }
        
        if(3 == $validationResult[2]) {
          throw new SOAPFault('Soap:client', 'Requested song is not allowed to download.');
        }  
        
        if(2 == $validationResult[2]) {
          throw new SOAPFault('Soap:client', 'Requested song is not allowed to download.');
        }
        
        
      }
    } else {
      
      if(0 == $this->getDownloadStatusOfSong($prodId, $provider_type, $libraryDetails['Library']['library_territory'])) {
        throw new SOAPFault('Soap:client', 'Requested song is not allowed to download.');
      }
      
      if('inactive' == $libraryDetails['Library']['library_status']) {
        throw new SOAPFault('Soap:client', 'Requested library is Inactive.');
      }    

      if(!($this->IsTerrotiry($prodId, $provider_type, $libId))) {
        throw new SOAPFault('Soap:client', 'Song does not belong to current library territory.');
      }
      
      if($this->IsDownloadable($prodId, $libraryDetails['Library']['library_territory'], $provider_type)) {
        throw new SOAPFault('Soap:client', 'Requested song is not allowed to download.');
      }
      
    }
    
    $currentDownloadCount = $this->getTotalDownloadCound($libId, $patId);

    $totalDownloadLimit  =  $libraryDetails['Library']['library_user_download_limit'];

    $TrackData = $this->Song->find('first',
        array(
          'fields' => array(
            'Song.ProdID',
            'Song.ProductID',
            'Song.Title',
            'Song.SongTitle',
            'Song.Artist',
            'Song.ISRC',
            'Song.FullLength_FIleID'
          ),
          'conditions' => array(
            'Song.ProdID' => $prodId,
            'Song.provider_type' => $provider_type,
          ),
          'recursive' => -1,
        )
    );
    
    $insertArr = Array();
    $insertArr['library_id'] = $libId;
    $insertArr['patron_id'] = $patId;
    $insertArr['ProdID'] = $prodId;
    $insertArr['artist'] = addslashes($TrackData['Song']['Artist']);
    $insertArr['track_title'] = addslashes($TrackData['Song']['SongTitle']);
    $insertArr['ProductID'] = $TrackData['Song']['ProductID'];
    $insertArr['ISRC'] = $TrackData['Song']['ISRC'];

    $library_authentication_method = $libraryDetails['Library']['library_authentication_method'];

    if('user_account' == $library_authentication_method) {

      $user = $this->User->find('first',array(
          'fields' => array('email'),
          'conditions' => array('User.id' => $patId),
        )
      );

      $insertArr['email'] = $user['User']['email'];
    } else {
      $insertArr['email'] = '';
    }

    $insertArr['user_login_type'] = $library_authentication_method;
    $insertArr['user_agent'] = mysql_real_escape_string($agent);
    $insertArr['ip'] = $_SERVER['REMOTE_ADDR'];

	  $this->Library->setDataSource('master'); 
    
    if($maintainLatestDownload){     
	$procedure = 'downloadsong';
    $sql = "CALL downloadsong('".$libId."','".$patId."', '".$prodId."', '".$TrackData['Song']['ProductID']."', '".$TrackData['Song']['ISRC']."', '".addslashes($TrackData['Song']['Artist'])."', '".addslashes($TrackData['Song']['SongTitle'])."', '".$insertArr['user_login_type']."', '" .$provider_type."', '".$insertArr['email']."', '".addslashes($insertArr['user_agent'])."', '".$insertArr['ip']."', '".Configure::read('App.curWeekStartDate')."', '".Configure::read('App.curWeekEndDate')."',@ret)";
      
    }else{
      $procedure = 'sonyproc_ioda';
      $sql = "CALL sonyproc_ioda('".$libId."','".$patId."', '".$prodId."', '".$TrackData['Song']['ProductID']."', '".$TrackData['Song']['ISRC']."', '".addslashes($TrackData['Song']['Artist'])."', '".addslashes($TrackData['Song']['SongTitle'])."', '".$insertArr['user_login_type']."', '" .$provider_type."', '".$insertArr['email']."', '".addslashes($insertArr['user_agent'])."', '".$insertArr['ip']."', '".Configure::read('App.curWeekStartDate')."', '".Configure::read('App.curWeekEndDate')."',@ret)";
    }
    
    

    $this->Library->query($sql);
		$sql = "SELECT @ret";
		$data = $this->Library->query($sql);
		$return = $data[0][0]['@ret'];
    
    $log_data .= ":StoredProcedureParameters-LibID='".$libId."':StoredProcedureParameters-Patron='".$patId."':StoredProcedureParameters-ProdID='".$prodId."':StoredProcedureParameters-ProductID='".$TrackData['Song']['ProductID']."':StoredProcedureParameters-ISRC='".$TrackData['Song']['ISRC']."':StoredProcedureParameters-Artist='".addslashes($TrackData['Song']['Artist'])."':StoredProcedureParameters-SongTitle='".addslashes($TrackData['Song']['SongTitle'])."':StoredProcedureParameters-UserLoginType='".$insertArr['user_login_type']."':StoredProcedureParameters-ProviderType='".$provider_type."':StoredProcedureParameters-Email='".$insertArr['email']."':StoredProcedureParameters-UserAgent='".addslashes($insertArr['user_agent'])."':StoredProcedureParameters-IP='".$insertArr['ip']."':StoredProcedureParameters-CurWeekStartDate='".Configure::read('App.curWeekStartDate')."':StoredProcedureParameters-CurWeekEndDate='".Configure::read('App.curWeekEndDate')."':StoredProcedureParameters-Name='".$procedure."':StoredProcedureParameters-@ret='".$return."'";
    
    if(is_numeric($return)){
      
      $this->LatestDownload->setDataSource('master'); 

      $data = $this->LatestDownload->find('count', array(
        'conditions'=> array(
            "LatestDownload.library_id " => $libId,
            "LatestDownload.patron_id " => $patId, 
            "LatestDownload.ProdID " => $prodId,
            "LatestDownload.provider_type " => $provider_type,     
            "DATE(LatestDownload.created) " => date('Y-m-d'), 
        ),
        'recursive' => -1,
      ));
      

      if(0 === $data){
        $log_data .= ":NotInLD";
      }
      
      if(false === $data){
        $log_data .= ":SelectLDFail";
      }
      $this->LatestDownload->setDataSource('default'); 
    }
    
    $log_data .= PHP_EOL."---------Request (".$log_id.") End----------------";
    
    
    $this->log($log_data, $log_name);
    
    
		$this->Library->setDataSource('default');
		
    $wishlist = 0;
		if(is_numeric($return)){
      
      $data = $this->Files->find('first',
        array(
          'fields' => array(
            'CdnPath',
            'SaveAsName'
          ),
          'conditions' => array(
            'FileID' => $TrackData['Song']['FullLength_FIleID']
          ),
          'recursive' => -1
        )
      );

      $CdnPath = $data['Files']['CdnPath'];
      $SaveAsName = $data['Files']['SaveAsName'];

      $songUrl = $this->Token->regularToken( $CdnPath . "/" . $SaveAsName);
      $finalSongUrl = Configure::read('App.Music_Path') . $songUrl;
      
      $wishlist = 0;
      return $this->createSongDownloadSuccessObject('Download permitted.', $finalSongUrl, true, $currentDownloadCount+1, $totalDownloadLimit, $wishlist);

		}
		else{

      if('incld' == $return) {
      
        $wishlist = 0;
        return $this->createSongDownloadSuccessObject('Already downloaded.', '',false, $currentDownloadCount, $totalDownloadLimit, $wishlist);
      } else {
        if('error' == $return) {

          $wishlist = 0;
          return $this->createSongDownloadSuccessObject('Library limit exceeded.', '', false, $currentDownloadCount, $totalDownloadLimit, $wishlist);
        }
      }

    }

  }
  
  /**
   * Function Name : videoDownloadRequest
   * Desc : Actions that is used for updating user download request for video
   * @param string $authentication_token
   * @param string $prodId
   * @param string $agent
	 * @return VideoDownloadSuccessType[]
   */

  function videoDownloadRequest($authentication_token, $prodId, $agent) {
    
    
    if(!($this->isValidAuthenticationToken($authentication_token))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }
    
    $product_detail = $this->getProductDetail($prodId);
    $prodId = $product_detail['Product']['ProdID'];
    $provider_type = $product_detail['Product']['provider_type'];

    $patId = $this->getPatronIdFromAuthenticationToken($authentication_token);
    $libId = $this->getLibraryIdFromAuthenticationToken($authentication_token);
    
    $this->Library->recursive = -1;
    $libraryDetails = $this->Library->find('first', array('conditions' => array('id' => $libId)));
    
    
    $siteConfigSQL = "SELECT * from siteconfigs WHERE soption = 'maintain_ldt'";
    $siteConfigData = $this->Album->query($siteConfigSQL);
    $maintainLatestDownload = (($siteConfigData[0]['siteconfigs']['svalue']==1)?true:false);
		 
    $siteConfigSQL = "SELECT * from siteconfigs WHERE soption = 'single_channel'";
    $siteConfigData = $this->Album->query($siteConfigSQL);
    $checkValidation = (($siteConfigData[0]['siteconfigs']['svalue']==1)?true:false);  
        
    $log_name = 'video_stored_procedure_app_log_'.date('Y_m_d');
    $log_id = md5(time());
    $log_data = PHP_EOL."----------Request (".$log_id.") Start----------------".PHP_EOL;
        
    if($checkValidation){
      
      $validationResult = $this->Downloadsvideos->validateDownloadVideos($prodId, $provider_type, true, $libraryDetails['Library']['library_territory'], $patId, $agent, $libId);
      
      $log_data .=  "DownloadComponentParameters-ProdId= '".$prodId."':DownloadComponentParameters-Provider_type= '".$provider_type."':DownloadComponentParameters-isMobileDownload= 'true':DownloadComponentParameters-Territory= '".$libraryDetails['Library']['library_territory']."':DownloadComponentParameters-PatronID='".$patId."':DownloadComponentParameters-Agent='".$agent."':DownloadComponentParameters-LibID='".$libId."':DownloadComponentResponse-Status='".$validationResult[0]."':DownloadComponentResponse-Msg='".$validationResult[1]."':DownloadComponentResponse-ErrorTYpe='".$validationResult[2]."'"; 

      if(false === $validationResult[0])  {
        
        $log_data .= PHP_EOL."---------Request (".$log_id.") End----------------".PHP_EOL;
        $this->log($log_data, $log_name);
        
        if(5 == $validationResult[2]) {
          throw new SOAPFault('Soap:client', 'Requested video is not allowed to download.');
        }
            
        if(4 == $validationResult[2]) {
          throw new SOAPFault('Soap:client', 'Requested video is not allowed to download.');
        }
        
        if(3 == $validationResult[2]) {
          throw new SOAPFault('Soap:client', 'Requested video is not allowed to download.');
        }  

        if(2 == $validationResult[2]) {
          throw new SOAPFault('Soap:client', 'Requested video is not allowed to download.');
        }
        
      }
    } else {
      
      if(0 == $this->getDownloadStatusOfSong($prodId, $provider_type, $libraryDetails['Library']['library_territory'])) {
        throw new SOAPFault('Soap:client', 'Requested video is not allowed to download.');
      }
           
      if( 
          ('inactive' == $libraryDetails['Library']['library_status']) || 
          ($libraryDetails['Library']['library_download_limit'] <= $libraryDetails['Library']['library_current_downloads']) || 
          ($libraryDetails['Library']['library_available_downloads'] > 1) 
        ) {
        throw new SOAPFault('Soap:client', 'Requested library is Inactive.');
      }   

      if(!($this->IsTerrotiry($prodId, $provider_type, $libId))) {
        throw new SOAPFault('Soap:client', 'Video does not belong to current library territory.');
      }
      
      if($this->IsDownloadable($prodId, $libraryDetails['Library']['library_territory'], $provider_type)) {
        throw new SOAPFault('Soap:client', 'Requested video is not allowed to download.');
      }
      
    }

    $currentDownloadCount =  $this->getTotalDownloadCound($libId, $patId);

    $totalDownloadLimit  =  $libraryDetails['Library']['library_user_download_limit'];

    $TrackData = $this->Video->find('first',
        array(
          'fields' => array(
            'Video.ProdID',
            'Video.ProductID',
            'Video.Title',
            'Video.VideoTitle',
            'Video.Artist',
            'Video.ISRC',
            'Video.FullLength_FIleID'
          ),
          'conditions' => array(
            'Video.ProdID' => $prodId,
            'Video.provider_type' => $provider_type,
          ),
          'recursive' => -1,
        )
    );
    
    $insertArr = Array();
    $insertArr['library_id'] = $libId;
    $insertArr['patron_id'] = $patId;
    $insertArr['ProdID'] = $prodId;
    $insertArr['artist'] = addslashes($TrackData['Video']['Artist']);
    $insertArr['track_title'] = addslashes($TrackData['Video']['VideoTitle']);
    $insertArr['ProductID'] = $TrackData['Video']['ProductID'];
    $insertArr['ISRC'] = $TrackData['Video']['ISRC'];

    $library_authentication_method = $libraryDetails['Library']['library_authentication_method'];

    if('user_account' == $library_authentication_method) {

      $user = $this->User->find('first',array(
          'fields' => array('email'),
          'conditions' => array('User.id' => $patId),
        )
      );

      $insertArr['email'] = $user['User']['email'];
    } else {
      $insertArr['email'] = '';
    }

    $insertArr['user_login_type'] = $library_authentication_method;
    $insertArr['user_agent'] = mysql_real_escape_string($agent);
    $insertArr['ip'] = $_SERVER['REMOTE_ADDR'];

	  $this->Library->setDataSource('master');
 
    if($maintainLatestDownload){
      $procedure = 'videos_proc_d_ld';
      $sql = "CALL videos_proc_d_ld('".$libId."','".$patId."', '".$prodId."', '".$TrackData['Video']['ProductID']."', '".$TrackData['Video']['ISRC']."', '".addslashes($TrackData['Video']['Artist'])."', '".addslashes($TrackData['Video']['VideoTitle'])."', '".$insertArr['user_login_type']."', '" .$provider_type."', '".$insertArr['email']."', '".addslashes($insertArr['user_agent'])."', '".$insertArr['ip']."', '".Configure::read('App.curWeekStartDate')."', '".Configure::read('App.curWeekEndDate')."',@ret)";
      
    }else{
      $procedure = 'videos_proc_d';
      $sql = "CALL videos_proc_d('".$libId."','".$patId."', '".$prodId."', '".$TrackData['Video']['ProductID']."', '".$TrackData['Video']['ISRC']."', '".addslashes($TrackData['Video']['Artist'])."', '".addslashes($TrackData['Video']['VideoTitle'])."', '".$insertArr['user_login_type']."', '" .$provider_type."', '".$insertArr['email']."', '".addslashes($insertArr['user_agent'])."', '".$insertArr['ip']."', '".Configure::read('App.curWeekStartDate')."', '".Configure::read('App.curWeekEndDate')."',@ret)";
    }
    
    

    $this->Library->query($sql);
		$sql = "SELECT @ret";
		$data = $this->Library->query($sql);
		$return = $data[0][0]['@ret'];
    
    $log_data .= ":StoredProcedureParameters-LibID='".$libId."':StoredProcedureParameters-Patron='".$patId."':StoredProcedureParameters-ProdID='".$prodId."':StoredProcedureParameters-ProductID='".$TrackData['Video']['ProductID']."':StoredProcedureParameters-ISRC='".$TrackData['Video']['ISRC']."':StoredProcedureParameters-Artist='".addslashes($TrackData['Video']['Artist'])."':StoredProcedureParameters-VideoTitle='".addslashes($TrackData['Video']['VideoTitle'])."':StoredProcedureParameters-UserLoginType='".$insertArr['user_login_type']."':StoredProcedureParameters-ProviderType='".$provider_type."':StoredProcedureParameters-Email='".$insertArr['email']."':StoredProcedureParameters-UserAgent='".addslashes($insertArr['user_agent'])."':StoredProcedureParameters-IP='".$insertArr['ip']."':StoredProcedureParameters-CurWeekStartDate='".Configure::read('App.curWeekStartDate')."':StoredProcedureParameters-CurWeekEndDate='".Configure::read('App.curWeekEndDate')."':StoredProcedureParameters-Name='".$procedure."':StoredProcedureParameters-@ret='".$return."'";
    
    if(is_numeric($return)){
      
      $this->LatestVideodownload->setDataSource('master');
	
      $data = $this->LatestVideodownload->find('count', array(
        'conditions'=> array(
            "LatestVideodownload.library_id " => $libId,
            "LatestVideodownload.patron_id " => $patId, 
            "LatestVideodownload.ProdID " => $prodId,
            "LatestVideodownload.provider_type " => $provider_type,     
            "DATE(LatestVideodownload.created) " => date('Y-m-d'),
        ),
        'recursive' => -1,
      ));
      

      if(0 === $data){
        $log_data .= ":NotInLD";
      }
      
      if(false === $data){
        $log_data .= ":SelectLDFail";
      }
      $this->LatestVideodownload->setDataSource('default');

    }
    
    $log_data .= PHP_EOL."---------Request (".$log_id.") End----------------";
    
    
    $this->log($log_data, $log_name);
    
    
		$this->Library->setDataSource('default');
		
    $wishlist = 0;
		if(is_numeric($return)){
      
      $data = $this->Files->find('first',
        array(
          'fields' => array(
            'CdnPath',
            'SaveAsName'
          ),
          'conditions' => array(
            'FileID' => $TrackData['Video']['FullLength_FIleID']
          ),
          'recursive' => -1
        )
      );

      $CdnPath = $data['Files']['CdnPath'];
      $SaveAsName = $data['Files']['SaveAsName'];

      $songUrl = $this->Token->regularToken($CdnPath . "/" . $SaveAsName);
      
      $finalSongUrl = Configure::read('App.Music_Path') . $songUrl;      
      
      $wishlist = 0;
      return $this->createVideoDownloadSuccessObject('Download permitted.', $finalSongUrl, true, $currentDownloadCount+2, $totalDownloadLimit, $wishlist);

		}
		else{

      if('incld' == $return) {
      
        $wishlist = 0;
        return $this->createVideoDownloadSuccessObject('Already downloaded.', '',false, $currentDownloadCount, $totalDownloadLimit, $wishlist);
      } else {
        if('error' == $return) {

          $wishlist = 0;
          return $this->createVideoDownloadSuccessObject('Library limit exceeded.', '', false, $currentDownloadCount, $totalDownloadLimit, $wishlist);
        }
      }

    }

  }
  
 /**
 * Function Name : getPageContent
 * Desc : To get page content based on page type
 * @param string $type
 * @return PageContentType[]
 */
  function getPageContent($type) {

    $language = 'en';

    $pageDetailsCache = Cache::read("getPageContentWebService");
    if ( (($pageDetailsCache) === false) || ($pageDetailsCache === null) ) {

      $pageInstance = ClassRegistry::init('Page');
      $pageDetails = $pageInstance->find('all', array('conditions' => array('page_name' => $type, 'language' => $language)));


      Cache::write("getPageContentWebService", $pageDetails);

    } else {

      $pageDetails = $pageDetailsCache;
    }


    if(count($pageDetails) != 0) {

      $obj = new PageContentType;
      $obj->id = (int)$pageDetails[0]['Page']['id'];
      $obj->page_name = $pageDetails[0]['Page']['page_name'];
      $obj->page_content = strip_tags($pageDetails[0]['Page']['page_content']);
      $obj->language = $pageDetails[0]['Page']['language'];
      $obj->created = $pageDetails[0]['Page']['created'];
      $obj->modified = $pageDetails[0]['Page']['modified'];
      $data = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'PageContentType');

      return $data;
    }
    else {
        throw new SOAPFault('Soap:client', 'No Data Found');
    }
  }


  /**
   * Function Name : searchLibrary
   * Desc : To get the libraries searched
   * @param string $authenticationToken
   * @param string $searchText
   * @param int $startFrom
   * @param int $recordCount
   * @param int $searchType
	 * @return SearchDataType[]
   */
	function searchLibrary($authentication_token, $searchText, $startFrom, $recordCount,  $searchType) {
  
  
    if(!($this->isValidAuthenticationToken($authentication_token))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }

    $libraryId = $this->getLibraryIdFromAuthenticationToken($authentication_token);
    $library_terriotry = $this->getLibraryTerritory($libraryId);

    switch($searchType){
      case '1': {
        $searchData = $this->getSearchAllList($searchText, $startFrom, $recordCount, $searchType, $libraryId, $library_terriotry);
      }
      break;
      case '2': {
        $searchData = $this->getSearchArtistList($searchText, $startFrom, $recordCount, $searchType, $libraryId, $library_terriotry);
      }
      break;
      case '3': {
        $searchData = $this->getSearchAlbumList($searchText, $startFrom, $recordCount, $searchType, $libraryId, $library_terriotry);
      }
      break;
      case '4': {
        $searchData = $this->getSearchSongList($searchText, $startFrom, $recordCount, $searchType, $libraryId, $library_terriotry);
      }
      break;     
      case '5': {
        $searchData = $this->getSearchVideoSongList($searchText, $startFrom, $recordCount, $searchType, $libraryId, $library_terriotry);
      }
      break;
      
      default:

    }
    return $searchData;

  }


  /**
   * Function Name : getAllGenre
   * Desc : To get all genre list
   * @param string $authenticationToken
   * @param int $startfrom
   * @param int $count
	 * @return GenreDataType[]
   */
	function getAllGenre($authenticationToken, $startfrom, $count) {

    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }

    $libraryId = $this->getLibraryIdFromAuthenticationToken($authenticationToken);

    $libraryDetails = $this->Library->find('first',array(
      'conditions' => array('Library.id' => $libraryId),
      'fields' => array('library_territory'),
      'recursive' => -1
      )
    );
    $library_territory = $libraryDetails['Library']['library_territory'];

	$genreAllCache = Cache::read("genre_".$library_territory.'_'.$startfrom.'_'.$count . '_WebService');
    if ( (($genreAllCache) === false) || ($genreAllCache === null) )  {

      $this->Genre->Behaviors->attach('Containable');
      $this->Genre->recursive = 2;

      $genreAll = $this->Genre->find('all',array(
        'conditions' =>
          array('and' =>
            array(
              array('Country.Territory' => $library_territory)
            )
          ),
          'fields' => array(
            'Genre.Genre'
          ),
          'contain' => array(
            'Country' => array(
              'fields' => array(
                'Country.Territory'
              )
            ),
          ),
          'group' => 'Genre.Genre',
          'limit' => $startfrom . ', ' . $count
      ));

      Cache::write("genre_".$library_territory.'_'.$startfrom.'_'.$count . '_WebService', $genreAll);

    } else {

      $genreAll = $genreAllCache;
    }

    foreach($genreAll AS $key => $val){

      $obj = new GenreDataType;
      $obj->GenreTitle               = $this->getTextUTF((string)$val['Genre']['Genre']);

      $genre_list[] = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'GenreDataType');
    }

    $data = new SoapVar($genre_list,SOAP_ENC_OBJECT,null,null,'ArrayGenreDataType');

    return $data;

  }

  /**
   * Function Name : getTopGenre
   * Desc : To get all genre list
   * @param string $authenticationToken
	 * @return GenreDataType[]
   */
	function getTopGenre($authenticationToken) {

    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }

    $genres = array("Pop", "Rock", "Country", "Alternative", "Classical", "Gospel/Christian", "R&B", "Jazz", "Soundtracks", "Rap", "Blues", "Folk",
                    "Latin", "Children's", "Dance", "Metal/Hard Rock", "Classic Rock", "Soundtrack", "Easy Listening", "New Age");

    foreach($genres AS $val){

      $obj = new GenreDataType;
      $obj->GenreTitle               = $this->getTextUTF((string)$val);

      $genre_list[] = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'GenreDataType');
    }

    $data = new SoapVar($genre_list,SOAP_ENC_OBJECT,null,null,'ArrayGenreDataType');

    return $data;

  }

  /**
   * Function Name : getTopSongs
   * Desc : To get all top songs list (National Top 100)
   * @param string $authenticationToken
   * @param int $startFrom
   * @param int $recordCount
	 * @return SongDataType[]
   */
	function getTopSongs($authenticationToken, $startFrom, $recordCount) {

    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }

    $libraryId = $this->getLibraryIdFromAuthenticationToken($authenticationToken);

    $libraryDetails = $this->Library->find('first',array(
      'conditions' => array('Library.id' => $libraryId),
      'fields' => array('library_territory'),
      'recursive' => -1
      )
    );
    $library_territory = $libraryDetails['Library']['library_territory'];

	$topSinglesCache = Cache::read("top_singles".$library_territory);
    if ( (($topSinglesCache) !== false) && ($topSinglesCache !== null) ) {

      $arrTemp = $topSinglesCache;

      for( $cnt = $startFrom; $cnt < ($startFrom+$recordCount); $cnt++  ) {
        if(!(empty($arrTemp[$cnt]))) {
          $sobj = new SongDataType;
          $sobj->ProdID                = (int)    $arrTemp[$cnt]['PRODUCT']['pid'];
          $sobj->ProductID             = (string) '';
          $sobj->ReferenceID           = (int)$this->getProductAutoID($arrTemp[$cnt]['Song']['ReferenceID'], $arrTemp[$cnt]['Song']['provider_type']);
          $sobj->Title                 = $this->getTextUTF((string) $arrTemp[$cnt]['Song']['Title']);
          $sobj->SongTitle             = $this->getTextUTF((string) $arrTemp[$cnt]['Song']['SongTitle']);
          $sobj->ArtistText            = $this->getTextUTF((string) $arrTemp[$cnt]['Song']['ArtistText']);
          $sobj->Artist                = $this->getTextUTF((string) $arrTemp[$cnt]['Song']['Artist']);
          $sobj->Advisory              = (string) $arrTemp[$cnt]['Song']['Advisory'];
          $sobj->ISRC                  = (string) '';
          $sobj->Composer              = (string) '';
          $sobj->Genre                 = $this->getTextUTF((string) $arrTemp[$cnt]['Genre']['Genre']);
          $sobj->Territory             = (string) $arrTemp[$cnt]['Country']['Territory'];       
          
          ($arrTemp[$cnt]['Country']['SalesDate'] <= date('Y-m-d')) ? $sobj->DownloadStatus = 0 : $sobj->DownloadStatus = 1;
          
          $sobj->TrackBundleCount      = (int)    '';
          $sobj->Sample_Duration       =  $this->getSongDurationTime($arrTemp[$cnt]['Song']['Sample_Duration']);
          $sobj->FullLength_Duration   = $this->getSongDurationTime($arrTemp[$cnt]['Song']['FullLength_Duration']);
          $sobj->Sample_FileID         = (int)    '';

          $sampleFileURL = $this->Token->regularToken( $arrTemp[$cnt]['Sample_Files']['CdnPath'] . "/" . $arrTemp[$cnt]['Sample_Files']['SaveAsName']);
          
          if($sobj->DownloadStatus) {
            $sobj->Sample_FileURL        = 'nostring';
            $sobj->FullLength_FIleURL    = 'nostring';
          } else {
            $sobj->Sample_FileURL        = Configure::read('App.Music_Path').$sampleFileURL;
            $sobj->FullLength_FIleURL    = Configure::read('App.Music_Path').$this->Token->regularToken( $arrTemp[$cnt]['Full_Files']['CdnPath'] . "/" . $arrTemp[$cnt]['Full_Files']['SaveAsName']);
          }
          
          $sobj->playButtonStatus      = $this->getPlayButtonStatus($arrTemp[$cnt]['Song']['ProdID'], $library_territory, $arrTemp[$cnt]['Song']['provider_type']);

          $sobj->FullLength_FIleID     = (int)    '';
          
          $sobj->CreatedOn             = (string) '';
          $sobj->UpdateOn              = (string) '';

          if('T' == $arrTemp[$cnt]['Song']['Advisory']) $sobj->SongTitle = $sobj->SongTitle.' (Explicit)';
          
          $song_list[] = new SoapVar($sobj,SOAP_ENC_OBJECT,null,null,'SongDataType');
        }
      }

      $data = new SoapVar($song_list,SOAP_ENC_OBJECT,null,null,'ArraySongDataType');

      return $data;


    } else {

      throw new SOAPFault('Soap:client', 'Freegal is unable to update the information. Please try again later.');
    }

  }
  
  /**
   * Function Name : getMyMusicVideos
   * Desc : To get My Music Videos list
   * @param string $authenticationToken
   * @param int $startFrom
   * @param int $recordCount
	 * @return VideoSongDataType[]
   */
  function getMyMusicVideos($authenticationToken, $startFrom, $recordCount) { 
    
    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }
    
    $libraryId = $this->getLibraryIdFromAuthenticationToken($authenticationToken);

    $libraryDetails = $this->Library->find('first',array(
      'conditions' => array('Library.id' => $libraryId),
      'fields' => array('library_territory'),
      'recursive' => -1
      )
    );
    $library_territory = $libraryDetails['Library']['library_territory'];
    
    if(1 == $libraryDetails['Library']['library_block_explicit_content']) {
			$cond = 'AND v.Advisory = "F"';
		}
		else{
			$cond = "";
		}
    
    
 	$myMusicCache = Cache::read("AppMyMusicVideosList_".$library_territory);   
    if ( (($myMusicCache) === false) || ($myMusicCache === null) ) {
      
      $this->Session->write('territory', $library_territory); 
      $this->switchCpuntriesTable();
      $breakdown_table = $this->Session->read('multiple_countries').'countries';
      
      $str_query = 'SELECT v.ProdID, v.ReferenceID, v.Title, v.VideoTitle, v.ArtistText, v.Artist, v.Advisory, v.ISRC, v.Composer,
        v.FullLength_Duration, v.DownloadStatus, v.Advisory, c.SalesDate, gr.Genre, ff.CdnPath AS VideoCdnPath, ff.SaveAsName AS VideoSaveAsName,
        imgf.CdnPath AS ImgCdnPath, imgf.SourceURL AS ImgSourceURL, prd.pid, COUNT(vd.id) AS cnt
        FROM video AS v
        INNER JOIN '.$breakdown_table.' AS c ON v.ProdID = c.ProdID AND v.provider_type = c.provider_type
        INNER JOIN Genre AS gr ON gr.ProdID = v.ProdID AND gr.provider_type = v.provider_type
        INNER JOIN File AS ff ON v.FullLength_FileID = ff.FileID
        INNER JOIN File AS imgf ON v.Image_FileID = imgf.FileID
        INNER JOIN PRODUCT AS prd ON prd.ProdID = v.ProdID AND prd.provider_type = v.provider_type
        LEFT JOIN videodownloads AS vd ON vd.ProdID = v.ProdID AND vd.provider_type = v.provider_type
        WHERE c.Territory = "'.$library_territory.'" AND v.DownloadStatus = "1" '.$cond.' GROUP BY v.ProdID
        ORDER BY cnt DESC LIMIT 100';
        
      $arr_video = array();  
      $arr_video = $this->Video->query($str_query);
      
      if(!(empty($arr_video))){
        Cache::write("AppMyMusicVideosList_".$library_territory, $arr_video);
      }else{
        throw new SOAPFault('Soap:client', 'Freegal is unable to update the information. Please try again later.');
      }
   
    }
    
    $arrTemp = $myMusicCache;

    for( $cnt = $startFrom; $cnt < ($startFrom+$recordCount); $cnt++  ) {
      if(!(empty($arrTemp[$cnt]))) {
        $sobj = new VideoSongDataType;
        $sobj->VideoProdID           = $arrTemp[$cnt]['prd']['pid'];
        $sobj->VideoReferenceID      = $arrTemp[$cnt]['v']['ReferenceID'];
        $sobj->VideoTitle            = $this->getTextUTF($arrTemp[$cnt]['v']['Title']);
        $sobj->VideoSongTitle        = $this->getTextUTF($arrTemp[$cnt]['v']['VideoTitle']);
        $sobj->VideoArtistText       = $this->getTextUTF($arrTemp[$cnt]['v']['ArtistText']);
        $sobj->VideoArtist           = $this->getTextUTF($arrTemp[$cnt]['v']['Artist']);
        $sobj->VideoAdvisory         = $arrTemp[$cnt]['v']['Advisory'];
        $sobj->VideoISRC             = $arrTemp[$cnt]['v']['ISRC'];
        $sobj->VideoComposer         = $this->getTextUTF($arrTemp[$cnt]['v']['Composer']);
        $sobj->VideoGenre            = $this->getTextUTF($arrTemp[$cnt]['gr']['Genre']);
        $sobj->VideoDownloadStatus   = $arrTemp[$cnt]['v']['DownloadStatus'];                     
        ($arrTemp[$cnt]['c']['SalesDate'] <= date('Y-m-d')) ? $sobj->VideoSalesStatus = 0 : $sobj->VideoSalesStatus = 1;
        $sobj->VideoFullLength_Duration   = $this->getSongDurationTime($arrTemp[$cnt]['v']['FullLength_Duration']);          
        $sobj->VideoFullLength_FileURL = '';
        $sobj->VideoImage_FileURL      = Configure::read('App.Music_Path').$this->Token->artworkToken( $arrTemp[$cnt]['imgf']['ImgCdnPath']."/".$arrTemp[$cnt]['imgf']['ImgSourceURL']);       
          
        if('T' == $arrTemp[$cnt]['v']['Advisory']) {
      
          $sobj->VideoTitle = $sobj->VideoTitle.' (Explicit)';
          $sobj->VideoSongTitle = $sobj->VideoSongTitle.' (Explicit)';
        }   
          
        $video_list[] = new SoapVar($sobj,SOAP_ENC_OBJECT,null,null,'VideoSongDataType');
      }
    }

    $data = new SoapVar($video_list,SOAP_ENC_OBJECT,null,null,'ArrayVideoSongData');

    return $data;

  }
   

  /**
   * Function Name : getTopArtist
   * Desc : To get all artist list
   * @param string $authenticationToken
   * @param int $startFrom
   * @param int $recordCount
	 * @return SongDataType[]
   */
	function getTopArtist($authenticationToken, $startFrom, $recordCount) {

    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }

    $libraryId = $this->getLibraryIdFromAuthenticationToken($authenticationToken);

    $libraryDetails = $this->Library->find('first',array(
      'conditions' => array('Library.id' => $libraryId),
      'fields' => array('library_territory'),
      'recursive' => -1
      )
    );
    $library_territory = $libraryDetails['Library']['library_territory'];


	$topSinglesCache = Cache::read("top_singles".$library_territory);
    if ( (( $topSinglesCache) !== false) && ($topSinglesCache !== null) ) {


        $arrTmp = $arrData = $arrFinal = $arrArtist = array();

        $arrTmp = $topSinglesCache;

        foreach($arrTmp AS $key => $val){
          $arrData[] = trim($val['Song']['ArtistText']);
        }

        $arrFinal = array_count_values($arrData);
        arsort($arrFinal, SORT_NUMERIC);

        foreach($arrFinal AS $key => $val){
          $arrArtist[] = $key;
        }

        $arrTemp = $arrArtist;

        for( $cnt = $startFrom; $cnt < ($startFrom+$recordCount); $cnt++  ) {
          if(!(empty($arrTemp[$cnt]))) {

            $sobj = new SongDataType;
            $sobj->ProdID                = (int)    '';
            $sobj->ProductID             = (string) '';
            $sobj->ReferenceID           = (int)    '';
            $sobj->Title                 = (string) '';
            $sobj->SongTitle             = (string) '';
            $sobj->ArtistText            = $this->getTextUTF((string) $arrTemp[$cnt]);
            $sobj->Artist                = (string) '';
            $sobj->Advisory              = (string) '';
            $sobj->ISRC                  = (string) '';
            $sobj->Composer              = (string) '';
            $sobj->Genre                 = (string) '';
            $sobj->Territory             = (string) '';
            $sobj->DownloadStatus        = (int)    '';
            $sobj->TrackBundleCount      = (int)    '';
            $sobj->Sample_Duration       = (string) '';
            $sobj->FullLength_Duration   = (string) '';
            $sobj->Sample_FileID         = (int)    '';
            $sobj->Sample_FileURL         =(string) '';
            $sobj->FullLength_FIleID     = (int)    '';
            $sobj->CreatedOn             = (string) '';
            $sobj->UpdateOn              = (string) '';

            $song_list[] = new SoapVar($sobj,SOAP_ENC_OBJECT,null,null,'SongDataType');
          }
        }

        $data = new SoapVar($song_list,SOAP_ENC_OBJECT,null,null,'ArraySongDataType');

        return $data;

    } else {
      throw new SOAPFault('Soap:client', 'Freegal is unable to update the information. Please try again later.');
    }

  }

  /**
   * Function Name : getGenreSongs
   * Desc : To get the genre song list
   * @param string $authenticationToken
   * @param string $genreTitle
	 * @return array
   */
	function getGenreSongs($authenticationToken, $genreTitle) {
  
    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }

    $libraryId = $this->getLibraryIdFromAuthenticationToken($authenticationToken);

    $libraryDetails = $this->Library->find('first',array(
      'conditions' => array('Library.id' => $libraryId),
      'fields' => array('library_territory'),
      'recursive' => -1
      )
    );
    $library_territory = $libraryDetails['Library']['library_territory'];   

	$genreSongsCache = Cache::read($genreTitle.$library_territory);
	
 	if($genreSongsCache == false || $genreSongsCache == null){
        $this->Common->getGenreData($library_territory,$genreTitle);
        $genreSongsCache = Cache::read($genreTitle.$library_territory);
    }
	
    if ( (($genreSongsCache ) !== false) && ($genreSongsCache !== null) ) {

      foreach($genreSongsCache AS $key => $val) {

        $sobj = new SongDataType;
        $sobj->ProdID                = (int)    $val['PRODUCT']['pid'];
        $sobj->ProductID             = (string) '';
        $sobj->ReferenceID           = (int)    $this->getProductAutoID($val['Song']['ReferenceID'], $val['Song']['provider_type']);
        $sobj->Title                 = $this->getTextUTF((string) $val['Song']['Title']);
        $sobj->SongTitle             = $this->getTextUTF((string) $val['Song']['SongTitle']);
        $sobj->ArtistText            = $this->getTextUTF((string) $val['Song']['ArtistText']);
        $sobj->Artist                = $this->getTextUTF((string) $val['Song']['Artist']);
        $sobj->Advisory              = (string) $val['Song']['Advisory'];
        $sobj->ISRC                  = (string) '';
        $sobj->Composer              = (string) '';
        $sobj->Genre                 = $this->getTextUTF((string) $val['Genre']['Genre']);
        $sobj->Territory             = (string) $val['Country']['Territory'];
                        
        ($val['Country']['SalesDate'] <= date('Y-m-d')) ? $sobj->DownloadStatus = 0 : $sobj->DownloadStatus = 1;
        
        $sobj->TrackBundleCount      = (int)    '';
        $sobj->Sample_Duration       = $this->getSongDurationTime($val['Song']['Sample_Duration']);
        $sobj->FullLength_Duration   = $this->getSongDurationTime($val['Song']['FullLength_Duration']);
        $sobj->Sample_FileID         = (int)    '';

        $sampleFileURL = $this->Token->regularToken( $val['Sample_Files']['CdnPath']."/".$val['Sample_Files']['SaveAsName']);
        
        if($sobj->DownloadStatus) {
          $sobj->Sample_FileURL         = 'nostring';
          $sobj->FullLength_FIleURL     = 'nostring';
        } else {
          $sobj->Sample_FileURL        = Configure::read('App.Music_Path').$sampleFileURL;
          $sobj->FullLength_FIleURL    = Configure::read('App.Music_Path').$this->Token->regularToken( $val['Full_Files']['CdnPath']."/".$val['Full_Files']['SaveAsName']);
        }
        
        $sobj->playButtonStatus      = $this->getPlayButtonStatus($val['Song']['ProdID'], $library_territory, $val['Song']['provider_type']);
        
        $sobj->FullLength_FIleID     = (int)    '';

        $sobj->CreatedOn             = (string) '';
        $sobj->UpdateOn              = (string) '';
        
        if('T' == $val['Song']['Advisory']) $sobj->SongTitle = $sobj->SongTitle.' (Explicit)';
        
        $song_list[] = new SoapVar($sobj,SOAP_ENC_OBJECT,null,null,'SongDataType');

      }

      $data = new SoapVar($song_list,SOAP_ENC_OBJECT,null,null,'ArraySongDataType');

      return $data;


    } else {

      throw new SOAPFault('Soap:client', 'Freegal is unable to update the information. Please try again later.');
    }

  }


  /**
   * Function Name : logoutAuthinticate
   * Desc : Delete authenticationToken record
   * @param string $authenticationToken
   * @param string $registerID
	 * @return SuccessResponseType[]
   */
  
	function logoutAuthinticate($authenticationToken, $registerID = null) {

    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }
    
    if('' != trim($registerID)) {
      $this->deleteRegisterDevice($registerID);
    }  
    
    $status = $this->AuthenticationToken->deleteAll(array('token' => $authenticationToken));

    if($status) {
      $message = 'Token deleted successfully';
      return $this->createsSuccessResponseObject(true, $message);
    }
    else {
      $message = 'Delete Token failed';
      return $this->createsSuccessResponseObject(false, $message);
    }
      
  }

  /**
   * Function Name : getQueueList
   * Desc : returns default/custom queue list
   * @param string $authenticationToken
   * @param int $startFrom
   * @param int $recordCount
   * @param int $uid
	 * @return QueueListDataType[]
   */
   
  function getQueueList($authenticationToken, $startFrom, $recordCount, $uid){

    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    } 
             
    $library_id = $this->getLibraryIdFromAuthenticationToken($authenticationToken);

    if(2 != $this->getUserType('', $library_id, 1)) {
       throw new SOAPFault('Soap:InvalidRequest', 'You do not have permission to access Queue.');
    }
 
    if( 1 == $uid ){
    
      $cond = array('status' => '1', 'queue_type' => '1');
    }else{
            
      $patronID = $this->getPatronIdFromAuthenticationToken($authenticationToken); 
      $cond = array('patron_id' => $patronID, 'status' => '1', 'queue_type' => '0');
    }
    
    $QueueList = $this->QueueList->find('all', array(
      'conditions' => $cond,
      'recursive' => -1,
      'order' => 'created DESC'
    ));

    if( empty($QueueList) ) {
      throw new SOAPFault('Soap:DefaultQueueList', 'No Queue lists found');
    } else {
      
      for( $cnt = $startFrom; $cnt < ($startFrom+$recordCount); $cnt++  ) {
        if(!(empty($QueueList[$cnt]['QueueList']['queue_id']))) {
          $obj = new QueueListDataType;
      
          $obj->QueueID                    = $QueueList[$cnt]['QueueList']['queue_id'];
          $obj->QueueName                  = $QueueList[$cnt]['QueueList']['queue_name'];
          $obj->QueueCreated               = $QueueList[$cnt]['QueueList']['created'];
          $obj->QueueModified              = $QueueList[$cnt]['QueueList']['modified'];          

    	  $songsCount = $this->QueueDetail->find('count', array(
     	 	'conditions' => array('queue_id' => $QueueList[$cnt]['QueueList']['queue_id']),
	        'recursive' => -1,
   	  ));

          $obj->QueueUser                  = $songsCount;
       
          $queue_list[] = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'QueueListDataType');
        }
      }
    
      $data = new SoapVar($queue_list,SOAP_ENC_OBJECT,null,null,'ArrayQueueListDataType');    

      return $data;
      
    }
        
    
  }
  
  /**
   * Function Name : manageQueue
   * Desc : returns operation status
   * @param string $authenticationToken
   * @param string $queueName
   * @param string $queueID
   * @param string $arrsongs
   * @param string $action
   * @param string $queueDescription
	 * @return QueueOperationType[]
   */
  function manageQueue($authenticationToken, $queueName, $queueID, $arrsongs, $action, $queueDescription = null){
 
    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }

    $library_id = $this->getLibraryIdFromAuthenticationToken($authenticationToken);

    if(2 != $this->getUserType('', $library_id, 1)) {
       throw new SOAPFault('Soap:InvalidRequest', 'You do not have permission to access Queue.');
    }
 
    $arrsongs = explode(',', $arrsongs);
    
    $patronId = $this->getPatronIdFromAuthenticationToken($authenticationToken);
    
    
    switch($action){
    
      case 'c':
      
        $cnt = 0;
        $cnt = $this->QueueList->find('count', array(
          'conditions' => array( 'queue_name' => $queueName, 'patron_id' => $patronId),
          'recursive' => -1      
        ));
        if(0 != $cnt) {
          return $this->createsQueueOperationObject(false, 'Name already exist');
        }
    
        $insertArr['queue_name'] = $queueName;
        $insertArr['patron_id'] = $patronId;
        $insertArr['status'] = 1;
        $insertArr['queue_type'] = '0';
        $insertArr['description'] = $queueDescription;
        $this->QueueList->save($insertArr);
      
        $queueID = $this->QueueList->getLastInsertID();

        $this->setQueueSongs($arrsongs, $queueID);
        
        return $this->createsQueueOperationObject(true, 'You have successfully created your Queue');
      
      break;
      case 'r':

        $cnt = 0;
        $cnt = $this->QueueList->find('count', array(
          'conditions' => array( 'queue_name' => $queueName, 'patron_id' => $patronId),
          'recursive' => -1      
        ));
        if(0 != $cnt) {
          return $this->createsQueueOperationObject(false, 'Name already exist');
        }
        
        $this->QueueList->read('queue_id', $queueID);
        $this->QueueList->set(array(
          'queue_name' => $queueName,
	  'description' => $queueDescription
        ));     
        $this->QueueList->save();
        
        return $this->createsQueueOperationObject(true, "You have successfully updated your Queue");
      
      break;
      case 'u':
      
        $cnt = 0;
        $cnt = $this->QueueList->find('count', array(
          'conditions' => array( 'queue_name' => $queueName, 'patron_id' => $patronId, 'queue_id != "'.$queueID.'"'),
          'recursive' => -1      
        ));
        if(0 != $cnt) {
          return $this->createsQueueOperationObject(false, 'Name already exist');
        }         
        
        $this->QueueList->read('queue_id', $queueID);
        $this->QueueList->set(array(
          'queue_name' => $queueName,
	  'description' => $queueDescription
        ));
        $this->QueueList->save();
        $this->setQueueSongs($arrsongs, $queueID);
      
        return $this->createsQueueOperationObject(true, "You have successfully updated your Queue");
      
      break;
      default:
        
    }
    

  }
   
  /**
   * Function Name : deleteQueue
   * Desc : delete queue 
   * @param string $authenticationToken
   * @param string $queueID
	 * @return QueueOperationType[]
   */
  function deleteQueue($authenticationToken, $queueID){
  
    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }
    
    $st2 = $st1 = false;
    $st2 = $this->QueueDetail->deleteAll(array('queue_id' => $queueID), false);
    if(true === $st2){
      $st1 = $this->QueueList->deleteAll(array('queue_id' => $queueID), false);
    }
      
    if( (true === $st1) ) {
      return $this->createsQueueOperationObject(true, 'You have deleted Queue successfully');
    }else{
      return $this->createsQueueOperationObject(false, 'Failed to delete Queue');
    }
  
  } 
  
  /**
   * Function Name : getQueueDetails
   * Desc : returns default/custom queue details
   * @param string $authenticationToken
   * @param string $queueID
   * @param int $startFrom
   * @param int $recordCount
	 * @return QueueDetailDataType[]
   */
  function getQueueDetails($authenticationToken, $queueID, $startFrom, $recordCount){
   
    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    } 

    $library_id = $this->getLibraryIdFromAuthenticationToken($authenticationToken);

    if(2 != $this->getUserType('', $library_id, 1)) {
       throw new SOAPFault('Soap:InvalidRequest', 'You do not have permission to access Queue.');
    } 
    
    $lib_territory = $this->getLibraryTerritory( $this->getLibraryIdFromAuthenticationToken($authenticationToken) );
    
    $isDefault = $this->QueueList->find('count',array(
      'conditions' => array('queue_type' => '1', 'queue_id' => $queueID),
      'recursive' => -1
      )
    );
    
    if(1 == $isDefault) {
    
	  $queueDetailsCache = Cache::read("defaultqueuelistdetails" . $queueID);
      if ($data = $queueDetailsCache === false) {
        $data   =   $this->Queue->getQueueDetails($queueID, $lib_territory);
        if (!empty($data)) {                   
          Cache::write("defaultqueuelistdetails" . $queueID, $data);
        }else{
          throw new SOAPFault('Soap:EmptyQueue', 'You do not have any song in this Queue.');
        }
      }else {
        $data = $queueDetailsCache;
      }
      
    } else {
      $data   =   $this->Queue->getQueueDetails($queueID, $lib_territory);
    }
    
    if(empty($data)) {
      throw new SOAPFault('Soap:EmptyQueue', 'You do not have any song in this Queue.');
    }   
       
    //filters array value   
    $tmp_data = array();    
    for( $cnt = 0; $cnt < (count($data)); $cnt++  ) {
 
        if(0 == $this->IsDownloadable($data[$cnt]['Songs']['ProdID'], $lib_territory, $data[$cnt]['Songs']['provider_type'])) { 
          
          if(1 == $this->getPlayButtonStatus($data[$cnt]['Songs']['ProdID'], $lib_territory, $data[$cnt]['Songs']['provider_type'])) {
            
            $tmp_data[] = $data[$cnt];
          }
        }      
    }
    $data = $tmp_data;     
    //End 

 
    for( $cnt = 0; $cnt < (count($data)); $cnt++ ) {
      
      if(!(empty($data[$cnt]['QueueList']['queue_name']))) { 
            
            $obj = new QueueDetailDataType;
        
            $obj->QueueName                    = $data[$cnt]['QueueList']['queue_name'];
            $obj->QueueSongSongTitle           = $data[$cnt]['Songs']['SongTitle'];
            $obj->QueueSongTitle               = $data[$cnt]['Songs']['STitle'];
            $obj->QueueSongArtistText          = $data[$cnt]['Songs']['ArtistText'];          
            $obj->QueueSongArtist              = $data[$cnt]['Songs']['Artist'];
            $obj->QueueSongFullLengthURL       = Configure::read('App.Music_Path').$this->Token->regularToken($data[$cnt]['SongFile']['SCdnPath']."/".$data[$cnt]['SongFile']['SSaveAsName']);
            $obj->QueueAlbumProdID             = $data[$cnt]['AProduct']['AlbumProdID'];
            $obj->QueueSongProdID              = $data[$cnt]['SProduct']['SongProdID'];
            $obj->QueueAlbumTitle              = $data[$cnt]['Albums']['ATitle'];
            $obj->QueueAlbumAlbumTitle         = $data[$cnt]['Albums']['AlbumTitle'];
            $obj->QueueAlbumImage              = Configure::read('App.Music_Path').$this->Token->regularToken( $data[$cnt]['AlbumFile']['ACdnPath']."/".$data[$cnt]['AlbumFile']['ASourceURL']);
            $obj->QueueFullLength_Duration     = $data[$cnt]['Songs']['FullLength_Duration'];
          
            if( 'T' == $data[$cnt]['Songs']['Advisory']) {
          
              $obj->QueueSongSongTitle  = $obj->QueueSongSongTitle . ' (Explicit)';
              $obj->QueueSongTitle  = $obj->QueueSongTitle . ' (Explicit)';     
            }
              
            $queue[] = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'QueueDetailDataType');
          
      }     
    }
    
    $queueDetail = new SoapVar($queue,SOAP_ENC_OBJECT,null,null,'ArrayQueueDetailDataType'); 
    
    return $queueDetail;
   
   
   
  }

  
  /**
   * Function Name : getLiveSearchSongList
   * Desc : To get the songs searched
   * @param string $authenticationToken
   * @param string $searchKey
   * @param string $searchType
	 * @return SearchDataType[]
   */
	function getLiveSearchSongList($authenticationToken, $searchKey, $searchType) {

    if(!($this->isValidAuthenticationToken($authenticationToken))) {
      throw new SOAPFault('Soap:logout', 'Your credentials seems to be changed or expired. Please logout and login again.');
    }

    $libraryId = $this->getLibraryIdFromAuthenticationToken($authenticationToken);

    $libraryDetails = $this->Library->find('first',array(
      'conditions' => array('Library.id' => $libraryId),
      'fields' => array('library_territory'),
      'recursive' => -1
      )
    );
    $library_territory = $libraryDetails['Library']['library_territory'];

    $searchText = $searchKey;

    $searchKey = str_replace("^", " ", $searchKey);
		$searchKey = str_replace("$", " ", $searchKey);
		$searchKey = '"^'.addslashes($searchKey).'"';
		App::import('vendor', 'sphinxapi', array('file' => 'sphinxapi.php'));

    switch($searchType){
      case '1': {
        $searchParam = "@ArtistText " . $searchKey . " | @Title " . $searchKey . " | @SongTitle " . $searchKey;
      }
      break;
      case '2': {
        $searchParam = "@ArtistText ".$searchKey;
      }
      break;
      case '3': {
        $searchParam = "@Title ".$searchKey;
      }
      break;
      case '4': {
        $searchParam = "@SongTitle ".$searchKey;
      }
      break;
      default:

    }

    $sphinxFinalCondition = $searchParam." & "."@Territory '".$library_territory."' & @DownloadStatus 1";

    $condSphinx = '';
		$sphinxSort = "";
		$sphinxDirection = "";
		$this->paginate = array('Song' => array(
						'sphinx' => 'yes', 'sphinxcheck' => $sphinxFinalCondition, 'sphinxsort' => $sphinxSort, 'sphinxdirection' => $sphinxDirection, 'extra' => 1
					));

		$searchResults = $this->paginate('Song');

    
    $array_uniques = array();

    if(!empty($searchResults)){

      switch($searchType){
        case '1': {
          foreach($searchResults AS $key => $val) {
            $array_test[$key] = trim($val['Song']['ArtistText']);
          }

          $array_uniques_keys_artist = array_keys(array_unique($array_test));

          foreach($searchResults AS $key => $val) {
            $array_test[$key] = trim($val['Song']['Title']);
          }

          $array_uniques_keys_album = array_keys(array_unique($array_test));

          foreach($searchResults AS $key => $val) {
            $array_test[$key] = trim($val['Song']['SongTitle']);
          }

          $array_uniques_keys_SongTitle = array_keys(array_unique($array_test));

          $array_uniques = array_unique(array_merge($array_uniques_keys_artist, $array_uniques_keys_album, $array_uniques_keys_SongTitle));

        }
        break;
        case '2': {
          foreach($searchResults AS $key => $val) {
            $array_uniques[$key] = trim($val['Song']['ArtistText']);
          }

          $array_uniques = array_keys(array_unique($array_uniques));
        }
        break;
        case '3': {
          foreach($searchResults AS $key => $val) {
            $array_uniques[$key] = trim($val['Song']['Title']);
          }

          $array_uniques = array_keys(array_unique($array_uniques));
        }
        break;
        case '4': {
          foreach($searchResults AS $key => $val) {
            $array_uniques[$key] = trim($val['Song']['SongTitle']);
          }

          $array_uniques = array_keys(array_unique($array_uniques));
        }
        break;
        default:

      }

      foreach($searchResults AS $key => $val){

        if(true === in_array( $key, $array_uniques) ) {

          $sobj = new SearchDataType;
          $sobj->SongProdID           = $this->getProductAutoID($val['Song']['ProdID'], $val['Song']['provider_type']);
          $sobj->SongTitle            = $this->getTextUTF($val['Song']['SongTitle']);
          $sobj->Title                = $this->getTextUTF($val['Song']['Title']);
          $sobj->SongArtist           = $this->getTextUTF($val['Song']['Artist']);
          $sobj->ArtistText           = $this->getTextUTF($val['Song']['ArtistText']);
          $sobj->Sample_Duration      = $val['Song']['Sample_Duration'];
          $sobj->FullLength_Duration  = $val['Song']['FullLength_Duration'];
          $sobj->ISRC                 = $val['Song']['ISRC'];

          $sobj->DownloadStatus       = $this->IsDownloadable($val['Song']['ProdID'], $library_territory, $val['Song']['provider_type']);           
           
          if($sobj->DownloadStatus){
            $sobj->fileURL              = 'nostring';
          }else{
            $sobj->fileURL            = Configure::read('App.Music_Path').$this->Token->regularToken($val['Sample_Files']['CdnPath']."/".$val['Sample_Files']['SaveAsName']);
          }          
           
           
          $albumData = $this->Album->find('first',
            array(
              'fields' => array('ProdID', 'AlbumTitle', 'Artist', 'provider_type'),
              'conditions' => array('ProdID' => $val['Song']['ReferenceID'], 'provider_type' => $val['Song']['provider_type']),
              'recursive' => -1,
            )
          );

          $sobj->AlbumProdID          = $this->getProductAutoID($albumData['Album']['ProdID'], $albumData['Album']['provider_type']);
          $sobj->AlbumTitle           = $this->getTextUTF($albumData['Album']['AlbumTitle']);
          $sobj->AlbumArtist          = $this->getTextUTF($albumData['Album']['Artist']);

          $search_list[] = new SoapVar($sobj,SOAP_ENC_OBJECT,null,null,'SearchDataType');
        }
      }

      $data = new SoapVar($search_list,SOAP_ENC_OBJECT,null,null,'ArraySearchDataType');

      return $data;
    }
    else {
      throw new SOAPFault('Soap:client', 'Freegal is unable to find any song containing the provided keyword.');
    }

  }

  
  /**
   * @function getSearchLibraryExplicitStatus
   * @desc returns Advisory condition for library
   * @param $libID : ID of Library
   * @return integer 
  */
  
  private function getSearchLibraryExplicitStatus($libID){
    
    $libraryData = $this->Library->find('first', array(
      'fields' => array('library_block_explicit_content'),
      'conditions' => array(
        'id' => $libID
      ),
     'recursive' => -1

    ));
    
    return $libraryData['Library']['library_block_explicit_content'];
    
  }
  
  
  /**
   * Function Name : getSearchAllList
   * Desc : To get the libraries searched
   * @param string $searchText
   * @param string $startFrom
   * @param string $recordCount
   * @param string $searchType
   * @param string $libraryId
   * @param string $library_terriotry
	 * @return SearchDataType[]
   */

	private function getSearchAllList($searchText, $startFrom, $recordCount, $searchType, $libraryId, $library_terriotry) {


    $queryVar   = $searchText;
    $typeVar    = 'all';
    $sortVar    = 'ArtistText';
    $sortOrder  = 'asc';
    $limit      = $recordCount;
    
    $page = ceil(($startFrom + $recordCount)/$recordCount); 
    
    $mobileExplicitStatus = $this->getSearchLibraryExplicitStatus($libraryId);
    
    $AllData = $this->Solr->search($queryVar, $typeVar, $sortVar, $sortOrder, $page, $limit, $library_terriotry, false, $mobileExplicitStatus);
	unset($AllData['lastPage']);
    $total = $this->Solr->total;
    $totalPages = ceil($total/$limit);

    
    foreach($AllData AS $key => $val){
        
      $sobj = new SearchDataType;
      $sobj->SongProdID           = $this->getProductAutoID($val->ProdID, $val->provider_type);
      $sobj->SongTitle            = $this->getTextUTF($val->SongTitle);
      $sobj->Title                = $this->getTextUTF($val->AlbumTitle);
      $sobj->SongArtist           = $this->getTextUTF($val->ArtistText);
      $sobj->ArtistText           = $this->getTextUTF($val->ArtistText);
      $sobj->Sample_Duration      = $this->getSongDurationTime($val->Sample_Duration);
      $sobj->FullLength_Duration  = $this->getSongDurationTime($val->FullLength_Duration); 
      $sobj->ISRC                 = $val->ISRC;
      
  
      $sobj->DownloadStatus       = $this->IsDownloadable($val->ProdID, $library_terriotry, $val->provider_type);
        
      if($sobj->DownloadStatus) {
        $sobj->fileURL            = 'nostring';
        $sobj->FullLengthFileURL  = 'nostring';
      }else{
        $sobj->fileURL            = Configure::read('App.Music_Path').$this->Token->regularToken($val->CdnPath."/".$val->SaveAsName);
        $sobj->FullLengthFileURL  = $this->getFullLengthFileURL($val->FullLength_FIleID);
      }
        
      $albumData = $this->Album->find('first',
        array(
          'fields' => array('ProdID', 'AlbumTitle', 'Artist', 'provider_type'),
          'conditions' => array('ProdID' => $val->ReferenceID, 'provider_type' => $val->provider_type),
          'recursive' => -1,
        )
      ); 

      $sobj->AlbumProdID          = $this->getProductAutoID($albumData['Album']['ProdID'], $albumData['Album']['provider_type']);
      $sobj->AlbumTitle           = $this->getTextUTF($albumData['Album']['AlbumTitle']);
      $sobj->AlbumArtist          = $this->getTextUTF($albumData['Album']['Artist']);

      $sobj->playButtonStatus     = $this->getPlayButtonStatus($val->ProdID, $library_terriotry, $val->provider_type);
      
      if('T' == $val->Advisory) $sobj->SongTitle = $sobj->SongTitle.' (Explicit)';
      
      $search_list[] = new SoapVar($sobj,SOAP_ENC_OBJECT,null,null,'SearchDataType');

    }

    $data = new SoapVar($search_list,SOAP_ENC_OBJECT,null,null,'ArraySearchDataType');


    if(!empty($AllData)){
      return $data;
    }
    else {
      throw new SOAPFault('Soap:client', 'Freegal is unable to find any information containing provided keyword.');
    }

  }


  /**
   * Function Name : getSearchArtistList
   * Desc : To get the libraries searched
   * @param string $searchText
   * @param string $startFrom
   * @param string $recordCount
   * @param string $searchType
   * @param string $libraryId
   * @param string $library_terriotry
	 * @return SearchDataType[]
   */
	private function getSearchArtistList($searchText, $startFrom, $recordCount, $searchType, $libraryId, $library_terriotry) {

    $queryVar   = $searchText;
    $typeVar    = 'artist';
    $sortVar    = 'ArtistText';
    $sortOrder  = 'asc';
    $limit      = $recordCount;
    
    $page = ceil(($startFrom + $recordCount)/$recordCount); 
    
    $mobileExplicitStatus = $this->getSearchLibraryExplicitStatus($libraryId);
    
    $ArtistData = $this->Solr->search($queryVar, $typeVar, $sortVar, $sortOrder, $page, $limit, $library_terriotry, false, $mobileExplicitStatus);
	unset($ArtistData['lastPage']);
    $total = $this->Solr->total;
    $totalPages = ceil($total/$limit);
      
    
    $search_list = array();  
    foreach($ArtistData AS $key => $val){    
        
      $sobj = new SearchDataType;
      $sobj->SongProdID           = $this->getProductAutoID($val->ProdID, $val->provider_type);
      $sobj->SongTitle            = $this->getTextUTF($val->SongTitle);
      $sobj->Title                = $this->getTextUTF($val->AlbumTitle);
      $sobj->SongArtist           = $this->getTextUTF($val->ArtistText);
      $sobj->ArtistText           = $this->getTextUTF($val->ArtistText);
      $sobj->Sample_Duration      = $this->getSongDurationTime($val->Sample_Duration);
      $sobj->FullLength_Duration  = $this->getSongDurationTime($val->FullLength_Duration); 
      $sobj->ISRC                 = $val->ISRC;
      
  
      $sobj->DownloadStatus       = $this->IsDownloadable($val->ProdID, $library_terriotry, $val->provider_type);
      
      if($sobj->DownloadStatus) {
        $sobj->fileURL            = 'nostring';
        $sobj->FullLengthFileURL  = 'nostring';
      }else{
        $sobj->fileURL            = Configure::read('App.Music_Path').$this->Token->regularToken($val->CdnPath."/".$val->SaveAsName);
        $sobj->FullLengthFileURL  = $this->getFullLengthFileURL($val->FullLength_FIleID);
      }
        
      $albumData = $this->Album->find('first',
        array(
          'fields' => array('ProdID', 'AlbumTitle', 'Artist', 'provider_type'),
          'conditions' => array('ProdID' => $val->ReferenceID, 'provider_type' => $val->provider_type),
          'recursive' => -1,
        )
      ); 

      $sobj->AlbumProdID          = $this->getProductAutoID($albumData['Album']['ProdID'], $albumData['Album']['provider_type']);
      $sobj->AlbumTitle           = $this->getTextUTF($albumData['Album']['AlbumTitle']);
      $sobj->AlbumArtist          = $this->getTextUTF($albumData['Album']['Artist']);

      $sobj->playButtonStatus     = $this->getPlayButtonStatus($val->ProdID, $library_terriotry, $val->provider_type);
      
      if('T' == $val->Advisory) $sobj->SongTitle = $sobj->SongTitle.' (Explicit)';
      
      $search_list[] = new SoapVar($sobj,SOAP_ENC_OBJECT,null,null,'SearchDataType');
      
              
    }

    $data = new SoapVar($search_list,SOAP_ENC_OBJECT,null,null,'ArraySearchDataType');

    
    if(!empty($ArtistData)){
      return $data;
    }
    else {
      throw new SOAPFault('Soap:client', 'Freegal is unable to find any Artist containing the provided keyword.');
    }

  }


  /**
   * Function Name : getSearchAlbumList
   * Desc : To get the libraries searched
   * @param string $searchText
   * @param string $startFrom
   * @param string $recordCount
   * @param string $searchType
   * @param string $libraryId
   * @param string $library_terriotry
	 * @return SearchDataType[]
   */
	private function getSearchAlbumList($searchText, $startFrom, $recordCount, $searchType, $libraryId, $library_terriotry) {


    $queryVar   = $searchText;
    $typeVar    = 'album'; 
    $limit      = $recordCount;
    $page = ceil(($startFrom + $recordCount)/$recordCount); 
        
    $mobileExplicitStatus = $this->getSearchLibraryExplicitStatus($libraryId);

    $Albumlist = $this->Solr->groupSearch($queryVar, $typeVar, $page, $limit, $mobileExplicitStatus, $library_terriotry);
 
      
    foreach($Albumlist AS $key => $val){

      $sobj = new SearchDataType;
      $sobj->SongProdID           = '';
      $sobj->SongTitle            = '';
      $sobj->Title                = '';
      $sobj->SongArtist           = '';
      $sobj->ArtistText           = '';
      $sobj->Sample_Duration      = '';
      $sobj->FullLength_Duration  = '';
      $sobj->ISRC                 = '';
      $sobj->DownloadStatus       = '';
      $sobj->fileURL              = '';
      $sobj->playButtonStatus     = '';
      
      $albumData = $this->Album->find('first',
        array(
          'fields' => array('ProdID', 'AlbumTitle', 'Artist', 'provider_type', 'Advisory', 'FileID'),
          'conditions' => array('ProdID' => $val->ReferenceID, 'provider_type' => $val->provider_type),
          'recursive' => -1,
        )
      );

      $sobj->AlbumProdID          = $this->getProductAutoID($albumData['Album']['ProdID'], $albumData['Album']['provider_type']);
      $sobj->AlbumTitle           = $this->getTextUTF($albumData['Album']['AlbumTitle']);
      $sobj->AlbumArtist          = $this->getTextUTF($albumData['Album']['Artist']);

      $imgData = $this->Files->find('first',array('conditions' => array('FileID' => $albumData['Album']['FileID']))); 
      $sobj->FullLengthFileURL    = Configure::read('App.Music_Path') . $this->Token->regularToken( $imgData['Files']['CdnPath']."/".$imgData['Files']['SourceURL']);;
      
            
      if('T' == $albumData['Album']['Advisory']) { $sobj->AlbumTitle = $sobj->AlbumTitle.' (Explicit)'; }
      
      $search_list[] = new SoapVar($sobj,SOAP_ENC_OBJECT,null,null,'SearchDataType');

      
    }

    $data = new SoapVar($search_list,SOAP_ENC_OBJECT,null,null,'ArraySearchDataType');
    
    if(!empty($Albumlist)){
      return $data;
    }
    else {
      throw new SOAPFault('Soap:client', 'Freegal is unable to find any Album containing the provided keyword.');
    }

  }

  /**
   * Function Name : getSearchSongList
   * Desc : To get the libraries searched
   * @param string $searchText
   * @param string $startFrom
   * @param string $recordCount
   * @param string $searchType
   * @param string $libraryId
   * @param string $library_terriotry
	 * @return SearchDataType[]
   */
	private function getSearchSongList($searchText, $startFrom, $recordCount, $searchType, $libraryId, $library_terriotry) {

    $queryVar   = $searchText;
    $typeVar    = 'song';
    $sortVar    = 'ArtistText';
    $sortOrder  = 'asc';
    $limit      = $recordCount;
    
    $page = ceil(($startFrom + $recordCount)/$recordCount); 
    
    $mobileExplicitStatus = $this->getSearchLibraryExplicitStatus($libraryId);
    
    $SongData = $this->Solr->search($queryVar, $typeVar, $sortVar, $sortOrder, $page, $limit, $library_terriotry, false, $mobileExplicitStatus);
	unset($SongData['lastPage']);
    $total = $this->Solr->total;
    $totalPages = ceil($total/$limit);


    foreach($SongData AS $key => $val){        
        
      $sobj = new SearchDataType;
      $sobj->SongProdID           = $this->getProductAutoID($val->ProdID, $val->provider_type);
      $sobj->SongTitle            = $this->getTextUTF($val->SongTitle);
      $sobj->Title                = $this->getTextUTF($val->AlbumTitle);
      $sobj->SongArtist           = $this->getTextUTF($val->ArtistText);
      $sobj->ArtistText           = $this->getTextUTF($val->ArtistText);
      $sobj->Sample_Duration      = $this->getSongDurationTime($val->Sample_Duration);
      $sobj->FullLength_Duration  = $this->getSongDurationTime($val->FullLength_Duration);
      $sobj->ISRC                 = $val->ISRC;

      $sobj->DownloadStatus       = $this->IsDownloadable($val->ProdID, $library_terriotry, $val->provider_type);
        
      if($sobj->DownloadStatus) {
        $sobj->fileURL            = 'nostring';
        $sobj->FullLengthFileURL  = 'nostring';
      }else{
        $sobj->fileURL            = Configure::read('App.Music_Path').$this->Token->regularToken($val->CdnPath."/".$val->SaveAsName);
        $sobj->FullLengthFileURL  = $this->getFullLengthFileURL($val->FullLength_FIleID);
      }
        
        
      $albumData = $this->Album->find('first',
        array(
          'fields' => array('ProdID', 'AlbumTitle', 'Artist', 'provider_type'),
          'conditions' => array('ProdID' => $val->ReferenceID, 'provider_type' => $val->provider_type),
          'recursive' => -1,
        )
      );

      $sobj->AlbumProdID          = $this->getProductAutoID($albumData['Album']['ProdID'], $albumData['Album']['provider_type']);
      $sobj->AlbumTitle           = $this->getTextUTF($albumData['Album']['AlbumTitle']);
      $sobj->AlbumArtist          = $this->getTextUTF($albumData['Album']['Artist']);

      $sobj->playButtonStatus     = $this->getPlayButtonStatus($val->ProdID, $library_terriotry, $val->provider_type);
      
      if('T' == $val->Advisory) $sobj->SongTitle = $sobj->SongTitle.' (Explicit)';
      
      $search_list[] = new SoapVar($sobj,SOAP_ENC_OBJECT,null,null,'SearchDataType');

    }

    $data = new SoapVar($search_list,SOAP_ENC_OBJECT,null,null,'ArraySearchDataType');


    if(!empty($SongData)){
      return $data;
    }
    else {
      throw new SOAPFault('Soap:client', 'Freegal is unable to find any Song containing the provided keyword.');
    }

  }

  
  /**
   * Function Name : getSearchVideoSongList
   * Desc : return sole serach result for videos
   * @param string $searchText
   * @param string $startFrom
   * @param string $recordCount
   * @param string $searchType
   * @param string $libraryId
   * @param string $library_terriotry
	 * @return SearchDataType[]
   */
	private function getSearchVideoSongList($searchText, $startFrom, $recordCount, $searchType, $libraryId, $library_terriotry) {

    $queryVar   = $searchText;
    $typeVar    = 'video';
    $sortVar    = 'VideoTitle';
    $sortOrder  = 'asc';
    $limit      = $recordCount;
    
    $page = ceil(($startFrom + $recordCount)/$recordCount); 
    
    $VideoData = $this->Solr->search($queryVar, $typeVar, $sortVar, $sortOrder, $page, $limit, $library_terriotry);
	unset($VideoData['lastPage']);
    $total = $this->Solr->total;
    $totalPages = ceil($total/$limit);
    

    foreach($VideoData AS $key => $val){        
        
      $sobj = new SearchDataType;
      $sobj->SongProdID           = $this->getProductAutoID($val->ProdID, $val->provider_type);
      $sobj->SongTitle            = $this->getTextUTF($val->VideoTitle);
      $sobj->Title                = $this->getTextUTF($val->VideoTitle);
      $sobj->SongArtist           = $this->getTextUTF($val->ArtistText);
      $sobj->ArtistText           = $this->getTextUTF($val->ArtistText);
      $sobj->Sample_Duration      = '';
      $sobj->FullLength_Duration  = $this->getSongDurationTime($val->FullLength_Duration);
      $sobj->ISRC                 = $val->ISRC;

      $sobj->DownloadStatus       = $this->IsDownloadable($val->ProdID, $library_terriotry, $val->provider_type);       
        
      if($sobj->DownloadStatus) {
        $sobj->fileURL            = 'nostring';
        $sobj->FullLengthFileURL  = 'nostring';
      }else{
        $sobj->fileURL            = Configure::read('App.Music_Path').$this->Token->artworkToken( $val->ACdnPath."/".$val->ASourceURL);
        $vdata = $this->Files->find('first',
          array(
            'fields' => array(
              'CdnPath',
              'SaveAsName'
            ),
            'conditions' => array(
              'FileID' => $val->FullLength_FIleID
            ),
            'recursive' => -1
        ));      
        $sobj->FullLengthFileURL  = Configure::read('App.Music_Path') . $this->Token->regularToken( $vdata['Files']['CdnPath'] . "/" . $vdata['Files']['SaveAsName']);
        
      } 

      $sobj->AlbumProdID          = '';
      $sobj->AlbumTitle           = '';
      $sobj->AlbumArtist          = '';

      $sobj->playButtonStatus     = 0;
      
      if('T' == $val->Advisory) {
      
        $sobj->SongTitle = $sobj->SongTitle.' (Explicit)';
        $sobj->Title = $sobj->Title.' (Explicit)';
      }  
           
      $search_list[] = new SoapVar($sobj,SOAP_ENC_OBJECT,null,null,'SearchDataType');

    }

    $data = new SoapVar($search_list,SOAP_ENC_OBJECT,null,null,'ArraySearchDataType');

    if(!empty($VideoData)){
      return $data;
    }
    else {
      throw new SOAPFault('Soap:client', 'Freegal is unable to find any Video containing the provided keyword.');
    }

  }
  
  
  /**
   * return class(AuthenticationResponseData) object with response data
   * @param bool response
   * @param string response_msg
   * @param string authentication_token
   * @return AuthenticationResponseDataType[]
   */

  private function createsAuthenticationResponseDataObject($response, $response_msg, $authentication_token = null, $patron_id = null){


      $obj = new AuthenticationResponseDataType;
      $obj->response                = $response;
      $obj->response_msg            = $response_msg;
      $obj->authentication_token    = $authentication_token;
      $obj->patron_id               = $patron_id;

      $auth_list = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'AuthenticationResponseDataType');
      $data = new SoapVar($auth_list,SOAP_ENC_OBJECT,null,null,'ArrayAuthenticationResponseDataType');

      return $data;
  }

  /**
   * return class(SuccessResponse) object with response data
   * @param bool $success
   * @param string $message
   * @return SuccessResponseType[]
   */

  private function createsSuccessResponseObject($success, $message){


    $obj = new SuccessResponseType;
    $obj->success       = $success;
    $obj->message       = $message;

    $success_list = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'SuccessResponseType');
    $data = new SoapVar($success_list,SOAP_ENC_OBJECT,null,null,'ArraySuccessResponseType');

    return $data;
  }
  
  /**
   * return class(StreamingResponse) object with response data
   * @param bool $success
   * @param string $message
   * @param string $remaningtime
   * @param string $mp4url
   * @param string $timerCallTime
   * @param string $timerCallDuration
   * @return StreamingResponseType[]
   */

  private function createsStreamingResponseObject($success, $message, $remaningtime, $mp4url, $timerCallTime, $timerCallDuration){
    //// success | message | remaningtime | mp4url | timerCallTime | timerCallDuration
    $obj = new StreamingResponseType;
    $obj->success             = $success;
    $obj->message             = $message;
    $obj->remaningtime        = $remaningtime;
    $obj->mp4url              = $mp4url;
    $obj->timerCallTime       = $timerCallTime;
    $obj->timerCallDuration   = $timerCallDuration;

    $success_list = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'StreamingResponseType');
    $data = new SoapVar($success_list,SOAP_ENC_OBJECT,null,null,'ArrayStreamingResponseType');

    return $data;
  }
  
  /**
   * return class(QueueOperation) object with response data
   * @param bool $success
   * @param string $message
   * @return QueueOperationType[]
   */

  private function createsQueueOperationObject($success, $message){


    $obj = new QueueOperationType;
    $obj->success       = $success;
    $obj->message       = $message;

    $success_list = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'QueueOperationType');
    $data = new SoapVar($success_list,SOAP_ENC_OBJECT,null,null,'ArrayQueueOperationType');

    return $data;
  }

  /** 
   * return class(UserTypeResponse) object with response data
   * @param integer user_type
   * @return UserTypeResponseType[]
   */

  private function createsUserTypeResponseObject($user_type){

  
    $obj = new UserTypeResponseType;
    $obj->usertype       = $user_type;

    $success_list = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'UserTypeResponseType');
    $data = new SoapVar($success_list,SOAP_ENC_OBJECT,null,null,'ArrayUserTypeResponseType');

    return $data;
  }
  
  
  /**
   * return int (0,1)
   * @param int $ProdID
   * @param string $provider_type
   * @param string $library_territory
   * @return int
   */

  private function getDownloadStatusOfSong($ProdID, $provider_type, $library_territory) {

    $this->Session->write('territory', $library_territory);
       
    $this->switchCpuntriesTable();
    
    $DownloadStatus = $this->Country->find('first',
          array(
            'fields' => array(
              'Country.DownloadStatus',
            ),
            'conditions' => array('Country.ProdID' => $ProdID, 'Country.Territory' => $library_territory, 'Country.provider_type' => $provider_type),
            'recursive' => -1,
          )
        );
        
    return $DownloadStatus['Country']['DownloadStatus'];
 
  }

  /**
   * return library identifier from library method
   * @param string $method
   * @return int
   */

  private function getLibraryIdentefierByLibraryMethod($method){

    $arr_library = array(
      'user_account' => '1',
      'innovative' => '2',
      'innovative_wo_pin' => '3',
      'innovative_https' => '4',
      'innovative_var_https' => '5',
      'innovative_var_name' => '6',
      'innovative_var_https_name' => '7',
      'sip2' => '8',
      'sip2_var' => '9',
      'soap' => '10',
      'innovative_var_wo_pin' => '11',
      'innovative_var_https_wo_pin' => '12',
      'sip2_wo_pin' => '13',
      'sip2_var_wo_pin' => '14',
      'curl_method' => '15',
      'referral_url' => '16',
      'innovative_var' => '17',
      'mndlogin_reference' => '18',
      'mdlogin_reference' => '19',
      'ezproxy' => '16',
      'capita' => '17',
      'symws' => '17',

    );

    return $arr_library[$method];

  }

  /**
   * return SongDownloadSuccessType object
   * @param string $method
   * @return SongDownloadSuccessType[]
   */
  private function createSongDownloadSuccessObject($message, $song_url, $success, $currentDownloadCount, $totalDownloadLimit, $showWishlist){

    $obj = new SongDownloadSuccessType;

    $obj->message                   = $message;
    $obj->song_url                  = $song_url;
    $obj->success                   = $success;
    $obj->currentDownloadCount      = $currentDownloadCount;
    $obj->totalDownloadLimit        = $totalDownloadLimit;
    $obj->showWishlist              = $showWishlist;


    $download_list = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'SongDownloadSuccessType');
    return new SoapVar($download_list,SOAP_ENC_OBJECT,null,null,'ArraySongDownloadSuccessType');

  }

  /**
   * return VideoDownloadSuccessType object
   * @param string $method
   * @return VideoDownloadSuccessType[]
   */
  private function createVideoDownloadSuccessObject($message, $video_url, $success, $currentDownloadCount, $totalDownloadLimit, $showWishlist){

    $obj = new VideoDownloadSuccessType;

    $obj->message                   = $message;
    $obj->video_url                 = $video_url;
    $obj->success                   = $success;
    $obj->currentDownloadCount      = $currentDownloadCount;
    $obj->totalDownloadLimit        = $totalDownloadLimit;
    $obj->showWishlist              = $showWishlist;


    $download_list = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'VideoDownloadSuccessType');
    return new SoapVar($download_list,SOAP_ENC_OBJECT,null,null,'ArrayVideoDownloadSuccessType');

  }
  
  /**
   * return int (0,1)
   * @param int $songProdID
   * @param string $provider_type
   * @param string $libraryId
   * @return int
   */

  private function IsTerrotiry($songProdID, $provider_type, $libraryId) {


    $libraryDetails = $this->Library->find('first',array(
      'conditions' => array('Library.id' => $libraryId),
      'fields' => array('library_territory'),
      'recursive' => -1
      )
    );

    $library_territory = $libraryDetails['Library']['library_territory'];

    $this->Session->write('territory', $library_territory);
       
    $this->switchCpuntriesTable();
    
    $count = $this->Country->find('count',
          array(
            'conditions' => array('Country.ProdID' => $songProdID, 'Country.Territory' => $library_territory, 'Country.provider_type' => $provider_type),
            'recursive' => -1,
          )
        );

    if(0 == $count) {
      return 0;
    } else {
      return 1;
    }

  }
  
  /**
   * return int (0,1)
   * @param int $songProdID
   * @param string $territory
   * @param string $provider_type
   * @return int
   */

	private function IsDownloadable($songProdID, $territory, $provider_type) {	
		
    $this->Session->write('territory', $territory);
       
    $this->switchCpuntriesTable();
    
    $Country_array = $this->Country->find('first',
			  array(
				'fields' => array('SalesDate'),
				'conditions' => array('Country.ProdID' => $songProdID, 'Country.Territory' => $territory, 'Country.provider_type' => $provider_type),
				'recursive' => -1,
			  )
			);
      
    
		$SalesDate = $Country_array['Country']['SalesDate'];
		if($SalesDate <= date('Y-m-d')) {
			$IsNotDownloadable = 0;
		} else {
			$IsNotDownloadable = 1;
		}
		
		return $IsNotDownloadable;
	} 
  
  /**
   * Function Name : getPlayButtonStatus
   * return int (0,1)
   * @param int $songProdID
   * @param string $territory
   * @param string $provider_type
	 * @return int
   */
	private function getPlayButtonStatus($songProdID, $territory, $provider_type) {

    $this->Session->write('territory', $territory);
       
    $this->switchCpuntriesTable();
    
    $Country_array = $this->Country->find('first',
			  array(
				'fields' => array('StreamingSalesDate', 'StreamingStatus'),
				'conditions' => array('Country.ProdID' => $songProdID, 'Country.Territory' => $territory, 'Country.provider_type' => $provider_type),
				'recursive' => -1,
			  )
			);
        
		$StreamingSalesDate = $Country_array['Country']['StreamingSalesDate'];
		$StreamingStatus = $Country_array['Country']['StreamingStatus'];

		if( ($StreamingSalesDate <= date('Y-m-d')) && ($StreamingStatus == 1) ) {
			return 1;
		} else {
			return 0;
		}
		
  }
  

  /**
   * return int AutoID in Product table
   * @param int $albumProdID
   * @param string $albumProviderType
   * @return int
   */
     
  private function getProductAutoID($albumProdID, $albumProviderType) {	
  
    $productDetails = $this->Product->find('first',array(
      'conditions' => array('Product.ProdID' => $albumProdID, 'Product.provider_type' => $albumProviderType),
      'fields' => array('Product.pid'),
      'recursive' => -1
      )
    );
    
    return $productDetails['Product']['pid'];
    
  }

  
  /**
   * return Product detail
   * @param int $AutoID
   * @return array
   */
     
  private function getProductDetail($AutoID) {	
  
    $productDetails = $this->Product->find('first',array(
      'conditions' => array('Product.pid' => $AutoID),
      'fields' => array('Product.ProdID', 'Product.provider_type'),
      'recursive' => -1
      )
    );
    
    return $productDetails;
    
  }
  
  /**
   * Function Name : deleteRegisterDevice
   * Desc : To remove device id for given registerID
   * @param string registerID
	 * @return SuccessResponseType[]
   */
  private function deleteRegisterDevice($registerID){

        
    $data = $this->DeviceMaster->find('first', array('conditions' => array('registration_id' => $registerID)));
    
    if('' != trim($data['DeviceMaster']['id'])) {
      $sta = $this->DeviceMaster->delete($data['DeviceMaster']['id']);
      if(false !== $sta) {
        return true;
      }else{
        return false;
      }
    }else{
      return true; 
    }

  
  }
  
  /**
   * Function Name : getTmpPatronID
   * Desc : To send hard code PatronID for given library
   * @param string library_id
   * @param string card
   * @param string resp
	 * @return string
   */
   
  private function getTmpPatronID($library_id, $card, $resp){
    
    switch($library_id) {
              
      case '187': {
      
        $response_patron_id = $card;
   
      }break;
      case '269': {
      
        $response_patron_id = $card;
   
      }break;
      
      case '297': {
      
        $response_patron_id = $card;
   
      }break;
      case '612': {
      
        $response_patron_id = $card;
   
      }break;
      
      default: {
        $response_patron_id = str_ireplace('OK:', '', $resp);
      }
    }
    
    return $response_patron_id;
          
  }
  
  /**
   * Function Name : getTextUTF
   * Desc : To return UTF8 string
   * @param string text
   * @return string
   */
  private function getTextUTF($text) {

    $text = iconv(mb_detect_encoding($text), "WINDOWS-1252//IGNORE", $text);
    return iconv(mb_detect_encoding($text), "UTF-8//IGNORE", $text);
  }
  
  /**
   * Function Name : getFullLengthFileURL
   * Desc : returns full length file url for music audio
   * @param string FullLengthFileID
   * @return string
   */
  private function getFullLengthFileURL($FullLengthFileID) {
  
    $FileData = $this->Files->find('first',array('conditions' => array('FileID' => $FullLengthFileID)));
    return Configure::read('App.Music_Path').$this->Token->regularToken( $FileData['Files']['CdnPath']."/".$FileData['Files']['SaveAsName']);
    
  }  
  
  /**
   * Function Name : getTotalDownloadCound
   * Desc : returns total download count for login patron
   * @param int libraryId
   * @param string patronId
   * @return int
   */
  private function getTotalDownloadCound($libraryId, $patronId) {
  
    $this->Download->recursive = -1;
    $downloadCount =  $this->Download->find('count',array('conditions' => array('library_id' => $libraryId,'patron_id' => $patronId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));

    $this->Videodownload->recursive = -1;
    $videoDownloadCount = $this->Videodownload->find('count',array('conditions' => array('library_id' => $libraryId,'patron_id' => $patronId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
    $videoDownloadCount = $videoDownloadCount *2;
    return $downloadCount + $videoDownloadCount;
    
  }
 
  
  /**
   * Function Name : setQueueSongs
   * Desc : delete all & insert given songs for queue
   * @param int arrSongs
   * @return int
   */
  private function setQueueSongs($arrSongs, $queueID) {
    
    $this->QueueDetail->deleteAll(array('queue_id' => $queueID), false);
    
    if(!(empty($arrSongs))) {  
      foreach($arrSongs as $Spid){
        $Spid = trim($Spid);
        if('' != $Spid) {
          $productDetails = $this->Product->find('first',array(
            'conditions' => array('pid' => $Spid),
            'fields' => array('ProdID', 'provider_type'),
            'recursive' => -1
          ));
      
          $albumDetails = $this->Song->find('first',array(
            'joins' => array(
                array(
                    'type' => 'INNER',
                    'table' => 'Albums',
                    'alias' => 'Albums',
                    'foreignKey' => false,
                    'conditions' => array('Song.ReferenceID = Albums.ProdID', 'Song.provider_type = Albums.provider_type'),        
                )
            ),  
            'conditions' => array(
              'Song.ProdID' => $productDetails['Product']['ProdID'], 
              'Song.provider_type' => $productDetails['Product']['provider_type']
            ),
            'fields' => array('Albums.ProdID', 'Albums.provider_type'),
            'recursive' => -1
          ));
          
          $this->QueueDetail->create();
          $insertArr['queue_id'] = $queueID;
          $insertArr['song_prodid'] = $productDetails['Product']['ProdID'];
          $insertArr['song_providertype'] = $productDetails['Product']['provider_type'];
          $insertArr['album_prodid'] = $albumDetails['Albums']['ProdID'];
          $insertArr['album_providertype'] = $albumDetails['Albums']['provider_type'];
          $status = $this->QueueDetail->save($insertArr);
        }
      }
    }
  
    return true;
  }
 
  /**
   * Function Name : getStreamngURL
   * Desc : Returns m3ui8 or mp3 URL for given Song  
   * @param int ProdID
   * @param string provider_type
   * @return string
   */  
  private function getStreamngURL($ProdID, $provider_type) {
  
    // fetches mp4 data
    $FileData = array();
    $FileData = $this->Song->find('first', array(
      'fields' => array('f4.FileiD', 'f4.SaveAsName', 'f4.CdnPath'),
      'joins' =>  array(
        array(
          'table' => 'File_mp4',
          'alias' => 'f4',
          'type' => 'INNER',
          'foreignKey' => false,
          'conditions'=> array('Song.MP4_FileID = f4.FileiD')
        )
      ),
      'conditions' => array('ProdID' => $ProdID, 'provider_type' => $provider_type ),
      'recursive' => -1
    ));

    if(empty($FileData)) {
      //sends mp3
      return $this->sendMp3Url($ProdID, $provider_type);

    }else{
            
      if(true === $this->validateMp4FileExist($FileData['f4']['SaveAsName'], $FileData['f4']['CdnPath'])) {
        //creates mp4
        return Configure::read('App.App_Streaming_Path').$this->Token->hlsToken($FileData['f4']['CdnPath'],$FileData['f4']['SaveAsName']);
       
      }else{
        return $this->sendMp3Url($ProdID, $provider_type);
      }
            
    }

  }
  
  /**
   * Function Name : getSongDurationTime
   * Desc : Returns hh:mm:ss format to mm:ss
   * @param int totalTime
   * @return string
   */ 
   
  private function getSongDurationTime($totalTime)  {
        
    if(strlen($totalTime)>5)  {
      $totalTime =  ltrim(date('i:s', strtotime($totalTime)), 0);
    }       
        
    return $totalTime;
        
  }
  
  /**
   * Function Name : sendMp3Url
   * Desc : Returns mp3 url for streaming
   * @param int ProdID
   * @param string provider_type
   * @return string
   */
   
  private function sendMp3Url($ProdID, $provider_type)  {
    
    $data = $this->Song->find('first', array(
      'fields' => array('f.FileiD', 'f.SaveAsName', 'f.CdnPath'),  
      'joins' => array(
        array(
          'table' => 'File',
          'alias' => 'f',
          'type' => 'inner',
          'foreignKey' => false,
          'conditions'=> array( 'f.FileID = Song.FullLength_FIleID', 'Song.ProdID' => $ProdID, 'Song.provider_type' => $provider_type )
        )
      ),
      'recursive' => -1,
    ));
	
    return Configure::read('App.Music_Path'). $this->Token->regularToken( $data['f']['CdnPath'] . "/" . $data['f']['SaveAsName']);
  }

  /**
   * Function Name : validateMp4FileExist
   * Desc : Returns mp3 url for streaming
   * @param int ProdID
   * @param string provider_type
   * @return string
   */
   
  private function validateMp4FileExist($SaveAsName, $CdnPath)  {

   	$url = Configure::read('App.Music_Path').$this->Token->regularToken($SaveAsName.'/'.$CdnPath);

	if($this->Common->file_exists_remote($url)){
		return true;
	}
	else{
		return false;
	}
  
  }

}

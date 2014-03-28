<?php

Configure::write('debug', 0); 

App::import('Model', 'AuthenticationToken');
App::import('Model', 'Zipusstate');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'FreegalLibrary.php');
include_once(ROOT.DS.APP_DIR.DS.'controllers'.DS.'classes'.DS.'NationalTopTen.php');
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

  private $authenticated = false;
  var $uses = array('User','Library','Download','Song','Wishlist','Album','Url','Language','Credentials','Files', 'Zipusstate', 'Artist', 'Genre','AuthenticationToken','Country','Card','Currentpatron','Product', 'DeviceMaster', 'LibrariesTimezone', 'LatestDownload', 'Video', 'LatestVideodownload', 'Videodownload', 'QueueList', 'QueueDetail', 'Featuredartist', 'File_mp4', 'Token'); 
  var $components = array('Downloads', 'AuthRequest', 'Downloadsvideos', 'Streaming', 'Solr', 'Queue'); 

  
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
    
    if ( (( Cache::read('mobile_top_artist_' . $mem_artistText . '_' . $library_territory)) === false) || (Cache::read('mobile_top_artist_' . $mem_artistText . '_' . $library_territory) === null) ) {
    
    
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
      }
    } 
    
    $albumData = Cache::read('mobile_top_artist_' . $mem_artistText . '_' . $library_territory);
    
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


    if ( ((Cache::read("ssartists_".$library_terriotry.'_EN')) === false)  || (Cache::read("ssartists_".$library_terriotry.'_EN') === null) ) {

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
      $Artist = Cache::read("ssartists_".$library_terriotry.'_EN');
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
    $library_terriotry = $this->getLibraryTerritory($libraryId);
 
    if (($artists = Cache::read("featured".$library_terriotry)) === false) {
      
      //get all featured artist and make array
     $featured = $this->Featuredartist->find('all', array('conditions' => array('Featuredartist.territory' => $library_terriotry,'Featuredartist.language' => Configure::read('App.LANGUAGE')), 'recursive' => -1));

      foreach($featured as $k => $v){
        if($v['Featuredartist']['album'] != 0){
          if(empty($ids)){
            $ids .= $v['Featuredartist']['album'];
            $ids_provider_type .= "(" . $v['Featuredartist']['album'] .",'" . $v['Featuredartist']['provider_type'] ."')";
          } else {
            $ids .= ','.$v['Featuredartist']['album'];
            $ids_provider_type .= ','. "(" . $v['Featuredartist']['album'] .",'" . $v['Featuredartist']['provider_type'] ."')";
          }	
        }
      }

      $featured = array();
      if($ids != ''){     
        $this->Album->recursive = 2;
        $featured =  $this->Album->find('all',array('conditions' =>
          array('and' =>
            array(
              array("Country.Territory" => $library_terriotry, "(Album.ProdID, Album.provider_type) IN (".rtrim($ids_provider_type,",'").")" ,"Album.provider_type = Country.provider_type"),
            ), "1 = 1 GROUP BY Album.ProdID"
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
            'Country' => array(
              'fields' => array(
                'Country.Territory'
              )
            ),
            'Files' => array(
              'fields' => array(
                'Files.CdnPath' ,
                'Files.SaveAsName',
                'Files.SourceURL'
              ),
            )
          ), 'order' => array('Country.SalesDate' => 'DESC'), 'limit' => 20
        ));
                    
      }
        
      if(!(empty($featured))) {     
        foreach($featured as $k => $v){

          $albumArtwork = $this->Token->artworkToken( $v['Files']['CdnPath']."/".$v['Files']['SourceURL']);
          $image =  Configure::read('App.Music_Path').$albumArtwork;
          $featured[$k]['featuredImage'] = $image;
        }
      }  
                     
      Cache::write("featured".$library_terriotry, $featured);
    }
        
    $featured = Cache::read("featured".$library_terriotry);
    
    if(empty($featured)){
      throw new SOAPFault('Soap:client', 'No featured albums found for your library.');
    }
 
    foreach($featured as $key => $val) {
      
      $obj = new FreegalFeaturedAlbumFreegal4Type;
      $obj->AlbumProdId      = $this->getProductAutoID($val['Album']['ProdID'], $val['Album']['provider_type']);
      $obj->AlbumTitle       = $this->getTextUTF($val['Album']['AlbumTitle']);
      $obj->FileURL          = $val['featuredImage'];
      
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

    if ( ((Cache::read("ssartists_".$library_terriotry.'_EN')) === false)  || (Cache::read("ssartists_".$library_terriotry.'_EN') === null) ) {

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
      $Artist = Cache::read("ssartists_".$library_terriotry.'_EN');
    }



    if ( ((Cache::read('update_ssdate_mobile')) === false)  || (Cache::read('update_ssdate_mobile') === null) ) {

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

    $nationalTopDownloadTmp = Cache::read("national".$territory);
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

    if (($libDownload = Cache::read("lib".$libraryId)) === false) {
      
      $this->Session->write('territory', $library_territory); 
      $this->switchCpuntriesTable();
      $breakdown_table = $this->Session->read('multiple_countries').'countries';
      
			$topDownloaded = $this->Download->find('all', array('conditions' => array('library_id' => $libraryId,'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct', 'provider_type'), 'order' => 'countProduct DESC', 'limit'=> '15'));
			$ids = '';

			foreach($topDownloaded as $k => $v){
				if(empty($ids)){
				  $ids .= $v['Download']['ProdID'];
				  $ids_provider_type .= "(" . $v['Download']['ProdID'] .",'" . $v['Download']['provider_type'] ."')";
				} else {
				  $ids .= ','.$v['Download']['ProdID'];
				   $ids_provider_type .= ','. "(" . $v['Download']['ProdID'] .",'" . $v['Download']['provider_type'] ."')";
				}
			}

			if($ids != ''){
				$this->Song->recursive = 2;
				 $topDownloaded_query =<<<STR
				SELECT
					Song.ProdID,
					Song.ReferenceID,
					Song.Title,
					Song.ArtistText,
					Song.SongTitle,
					Song.Artist,
					Song.Advisory,
					Song.Sample_Duration,
					Song.FullLength_Duration,
					Song.provider_type,
					Genre.Genre,
					Country.Territory,
					Country.SalesDate,
					Sample_Files.CdnPath,
					Sample_Files.SaveAsName,
					Full_Files.CdnPath,
					Full_Files.SaveAsName,
					Sample_Files.FileID,
					Full_Files.FileID,
					PRODUCT.pid
				FROM
					Songs AS Song
						LEFT JOIN
					File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
						LEFT JOIN
					File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
						LEFT JOIN
					Genre AS Genre ON (Genre.ProdID = Song.ProdID)
						LEFT JOIN
					$breakdown_table AS Country ON (Country.ProdID = Song.ProdID) AND (Country.Territory = '$library_territory') AND (Song.provider_type = Country.provider_type)
						LEFT JOIN
					PRODUCT ON (PRODUCT.ProdID = Song.ProdID)
				WHERE
					( (Country.DownloadStatus = '1') AND ((Song.ProdID, Song.provider_type) IN ($ids_provider_type)) AND (Song.provider_type = Genre.provider_type) AND (PRODUCT.provider_type = Song.provider_type)) AND (Country.Territory = '$library_territory') AND Country.SalesDate != '' AND Country.SalesDate < NOW() AND 1 = 1
				GROUP BY Song.ProdID
				ORDER BY FIELD(Song.ProdID,
						$ids) ASC
				LIMIT 10
STR;



			$topDownload = $this->Album->query($topDownloaded_query);

			} else {
				$topDownload = array();
			}
			Cache::write("lib".$libraryId, $topDownload);
		}
		$topDownload = Cache::read("lib".$libraryId);


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
								//	array('Country.StreamingStatus' => 1),
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
    

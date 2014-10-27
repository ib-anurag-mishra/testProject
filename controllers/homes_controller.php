<?php

/* File Name: homes_controller.php
  File Description: Displays the home page for each patron
  Author: FreegalMusic
 * Modified: 21-06-2013
 */

class HomesController extends AppController {

    var $name = 'Homes';
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Form', 'Library', 'Page', 'Wishlist', 'WishlistVideo', 'Song', 'Language', 'Session', 'Mvideo', 'Download', 'Videodownload', 'Queue', 'Token');
    var $components = array('RequestHandler','CacheHandler', 'ValidatePatron', 'Downloads', 'PasswordHelper', 'Email', 'SuggestionSong', 'Cookie', 'Session', 'Auth', 'Downloadsvideos', 'Common', 'Streaming');
    var $uses = array('Home', 'User', 'Featuredartist', 'Artist', 'Library', 'Download', 'Genre', 'Currentpatron', 'Page', 'Wishlist', 'WishlistVideo', 'Album', 'Song', 'Language', 'Searchrecord', 'LatestDownload', 'Siteconfig', 'Country', 'LatestVideodownload', 'News', 'Video', 'Videodownload', 'Zipcode', 'StreamingHistory', 'MemDatas', 'Token');

    /*
      Function Name : beforeFilter
      Desc : actions that needed before other functions are getting called
     */

    function beforeFilter() {

        parent::beforeFilter();

        // For super Admin while accesing convertString action
        if ($this->params['action'] == 'convertString' && ($this->Session->read('Auth.User.type_id') == 1)) {
            $pat_id = $this->Session->read('Auth.User.id');
        } else {       //  For Front End
            $pat_id = $this->Session->read('patron');
        }

        if (!empty($pat_id)) { //  After Login
            $this->Auth->allow('*');
        } else { //  Before Login 
            $this->Auth->allow('display', 'aboutus', 'index', 'us_top_10', 'chooser', 'forgot_password', 'new_releases', 'language', 'checkPatron', 'approvePatron', 'my_lib_top_10', 'terms');
        }

        $this->Cookie->name = 'baker_id';
        $this->Cookie->time = 3600; // or '1 hour'
        $this->Cookie->path = '/';
        $this->Cookie->domain = 'freegalmusic.com';
    }

    /* Function Name    : index
     * 
     * Responsible for display all the index page content
     *  
     */

    function index() {
        
        //check the server port and redirect to index page
        if ($_SERVER['SERVER_PORT'] == 443) {
            $this->redirect('http://' . $_SERVER['HTTP_HOST'] . '/index');
        }
        $this->layout = 'home';
        // Local Top Downloads functionality
        $libId = $this->Session->read('library');
        $patId = $this->Session->read('patron');
        $country = $this->Session->read('territory');
        $territory = $this->Session->read('territory');

        $nationalTopDownload = array();
        if (!empty($patId)) {
            $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
            $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);

            $this->set('libraryDownload', $libraryDownload);
            $this->set('patronDownload', $patronDownload);
        }

        /* Top Singles Starts */ 
        $nationalTopDownload = Cache::read("top_singles" . $territory);
        
        //print_r($nationalTopDownload);die;
        
        if ($nationalTopDownload === false) {
            
             //check variable data in to mem_datas table
            $nationalTopDownload = $this->CacheHandler->checkMemData("top_singles" . $territory);
            //if not found then run query in the table
            if( $nationalTopDownload === false ){
                $nationalTopDownload = $this->Common->getTopSingles($territory);
            }            
        }
        $this->set('top_singles', $nationalTopDownload);
       
        
        /* Top Singles Ends */ 
        
        /* National Top 100 Albums slider start */
        $TopAlbums = Cache::read("top_albums" . $territory);
        
       // print_r($TopAlbums);die;

        
        if ($TopAlbums === false) {            
             //check variable data in to mem_datas table
            $TopAlbums = $this->CacheHandler->checkMemData("top_albums" . $territory);
            //if not found then run query in the table
            if( $TopAlbums === false ){
                $TopAlbums = $this->Common->getTopAlbums($territory);
            }          
        }
        $this->set('nationalTopAlbums', $TopAlbums);

        /* featured artist slideshow code start */
        $featuresArtists = Cache::read("featured_artists_" . $territory.'_'.'1');
        if ($featuresArtists === false) {
            //check variable data in to mem_datas table
            $featuresArtists = $this->CacheHandler->checkMemData("featured_artists_" . $territory.'_'.'1');
            //if not found then run query in the table
            if( $featuresArtists === false ){
                $featuresArtists = $this->Common->getFeaturedArtists($territory,1);
                Cache::write("featured_artists_" . $territory.'_'.'1', $featuresArtists);
            }
        }        
        $this->set('featuredArtists', $featuresArtists);
        /* featured artist slideshow code Ends */

    }

    function get_genre_tab_content($tab_no, $genre) {

        //Cachec results for Rock Genre
        $this->layout = 'ajax';
        $libId = $this->Session->read('library');
        $patId = $this->Session->read('patron');
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
        $this->set('libraryDownload', $libraryDownload);
        $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);
        $this->set('patronDownload', $patronDownload);
        $this->set('tab_no', $tab_no);
        $genre_info = Cache::read($genre . $territory);
        if ($genre_info === false) {
            $SiteMaintainLDT = $this->Siteconfig->find('first', array('conditions' => array('soption' => 'maintain_ldt')));
            if ($SiteMaintainLDT['Siteconfig']['svalue'] == 1) {
                $restoregenre_query = "
                        SELECT
                            COUNT(DISTINCT latest_downloads.id) AS countProduct,
                            Song.ProdID,
                            Song.ReferenceID,
                            Song.Title,
                            Song.ArtistText,
                            Song.DownloadStatus,
                            Song.SongTitle,
                            Song.Artist,
                            Song.Advisory,
                            Song.Sample_Duration,
                            Song.FullLength_Duration,
                            Song.provider_type,
                            Song.Genre,
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
                            latest_downloads,
                            Songs AS Song
                                LEFT JOIN
                            " . $this->Session->read('multiple_countries') . "countries AS Country ON Country.ProdID = Song.ProdID
                                LEFT JOIN
                            File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
                                LEFT JOIN
                            File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
                                LEFT JOIN
                            PRODUCT ON (PRODUCT.ProdID = Song.ProdID) 
                        WHERE
                            latest_downloads.ProdID = Song.ProdID
                            AND latest_downloads.provider_type = Song.provider_type
                            AND Song.Genre LIKE '%" . $genre . "%'
                            AND (PRODUCT.provider_type = Song.provider_type)
                            AND Country.Territory LIKE '%" . $territory . "%'
                            AND Country.SalesDate != ''
                            AND Country.SalesDate < NOW()
                            AND Song.DownloadStatus = '1'
                            AND created BETWEEN '" . Configure::read('App.tenWeekStartDate') . "' AND '" . Configure::read('App.curWeekEndDate') . "'
                        GROUP BY latest_downloads.ProdID
                        ORDER BY countProduct DESC
                        LIMIT 10
                        ";
            } else {
                $restoregenre_query = "
                        SELECT
                            COUNT(DISTINCT downloads.id) AS countProduct,
                            Song.ProdID,
                            Song.ReferenceID,
                            Song.Title,
                            Song.ArtistText,
                            Song.DownloadStatus,
                            Song.SongTitle,
                            Song.Artist,
                            Song.Advisory,
                            Song.Sample_Duration,
                            Song.FullLength_Duration,
                            Song.provider_type,
                            Song.Genre,
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
                            downloads,
                            Songs AS Song
                                LEFT JOIN
                            " . $this->Session->read('multiple_countries') . "countries AS Country ON Country.ProdID = Song.ProdID
                                LEFT JOIN
                            File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
                                LEFT JOIN
                            File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
                                LEFT JOIN
                            PRODUCT ON (PRODUCT.ProdID = Song.ProdID) 
                        WHERE
                            downloads.ProdID = Song.ProdID
                            AND downloads.provider_type = Song.provider_type
                            AND Song.Genre LIKE '%" . $genre . "%'
                            AND (PRODUCT.provider_type = Song.provider_type)
                            AND Country.Territory LIKE '%" . $territory . "%'
                            AND Country.SalesDate != ''
                            AND Country.SalesDate < NOW()
                            AND Song.DownloadStatus = '1'
                            AND created BETWEEN '" . Configure::read('App.tenWeekStartDate') . "' AND '" . Configure::read('App.curWeekEndDate') . "'
                        GROUP BY downloads.ProdID
                        ORDER BY countProduct DESC
                        LIMIT 10
                        ";
            }

            $genre_info = $this->Album->query($restoregenre_query);
            if (!empty($data)) {
                Cache::write($genre . $territory, $genre_info);
            }
        }
        // Checking for download status
        $this->set('genre_info', $genre_info);
    }

    function my_lib_top_10() {
        Configure::write('debug', 0);
        $this->layout = 'home';
        $patId = $this->Session->read('patron');
        $country = $this->Session->read('territory');
        $url = $_SERVER['SERVER_NAME'];
        $host = explode('.', $url);
        $subdomains = array_slice($host, 0, count($host) - 2);
        $subdomains = $subdomains[0];
        $libId = $this->Session->read('library');

        if ($subdomains == '' || $subdomains == 'www' || $subdomains == 'freegalmusic') {
            if (!$this->Session->read("patron")) {
                $this->redirect(array('controller' => 'homes', 'action' => 'index'));
            }
        }

        /////////////////////////////////////Songs///////////////////////////////////////////////

        $ids = '';
        $ids_provider_type = '';
        if (!empty($libId) && !empty($patId)) {
            $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
            $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);
            $this->set('libraryDownload', $libraryDownload);
            $this->set('patronDownload', $patronDownload);
        }
        
                    
        
        $topDownload_songs = Cache::read("lib" . $libId);
        if ($topDownload_songs === false) {
            
            $topDownload_songs = $this->CacheHandler->checkMemData("lib" . $libId);
            //if not found then run query in the table
            if( $topDownload_songs === false ){
               $topDownload_songs = $this->Common->getLibraryTopTenSongs($country, $libId);
            }       
        }
        $this->set('top_10_songs', $topDownload_songs);

        ////////////////////////////////////////////////Albums///////////////////////////////////////////////////

        $ids_provider_type_album = '';
        $topDownload_albums = Cache::read("lib_album" . $libId);
        if ($topDownload_albums === false) {            
             $topDownload_albums = $this->CacheHandler->checkMemData("lib_album" . $libId);
            //if not found then run query in the table
            if( $topDownload_albums === false ){
               $topDownload_albums = $this->Common->getLibraryTop10Albums($country, $libId);
            } 
        }
        $this->set('topDownload_albums', $topDownload_albums);


        ////////////////////////////////////////////////Videos///////////////////////////////////////////////////
        $topDownload_videos_data = Cache::read("lib_video" . $libId);
        if ($topDownload_videos_data === false) {            
            $topDownload_videos_data = $this->CacheHandler->checkMemData("lib_video" . $libId);
            //if not found then run query in the table
            if( $topDownload_videos_data === false ){
                   $topDownload_videos_data = $this->Common->getLibraryTop10Videos($country, $libId);
            }            
        }
        $this->set('topDownload_videos_data', $topDownload_videos_data);
    }

    function us_top_10() {

        $this->layout = 'home';

        $libId = $this->Session->read('library');
        $patId = $this->Session->read('patron');
        $territory = $this->Session->read('territory');
        if (!empty($libId) && !empty($patId)) {
            $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
            $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);
            $this->set('libraryDownload', $libraryDownload);
            $this->set('patronDownload', $patronDownload);
        }

        $siteConfigSQL = "SELECT * from siteconfigs WHERE soption = 'maintain_ldt'";
        $siteConfigData = $this->Album->query($siteConfigSQL);
        $maintainLatestDownload = (($siteConfigData[0]['siteconfigs']['svalue'] == 1) ? true : false);

        //////////////////////////////////////////////Songs//////////////////////////////////////////////////////////////////////////
        // National Top Downloads functionality
        if (!empty($territory)) {
            $national_us_top10_record = Cache::read("national_us_top10_songs" . $territory);
            if ($national_us_top10_record === false) {
                $national_us_top10_record = $this->CacheHandler->checkMemData("national_us_top10_songs" . $territory);
                //if not found then run query in the table
                if( $national_us_top10_record === false ){
                      $national_us_top10_record = $this->Common->getUsTop10Songs($territory);
                }               
            } 
        }
        $this->set('nationalTopDownload', $national_us_top10_record);

        //////////////////////////////////////////////Albums//////////////////////////////////////////////////////////////////////////

        $country = $this->Session->read('territory');

        if (!empty($country)) {
            $ustop10Albums = Cache::read("national_us_top10_albums" . $territory);
            if ($ustop10Albums === false) {
                 $ustop10Albums = $this->CacheHandler->checkMemData("national_us_top10_albums" . $territory);
                //if not found then run query in the table
                if( $ustop10Albums === false ){
                      $ustop10Albums = $this->Common->getUsTop10Albums($territory);
                }   
            } 
        }
        $this->set('ustop10Albums', $ustop10Albums);

        //////////////////////////////////////////////Videos//////////////////////////////////////////////////////////////////////////

        $country = $this->Session->read('territory');

        if (!empty($country)) {
            $usTop10VideoDownload = Cache::read("national_us_top10_videos" . $territory);
            if ($usTop10VideoDownload === false) {               
                $usTop10VideoDownload = $this->CacheHandler->checkMemData("national_us_top10_videos" . $territory);
                //if not found then run query in the table
                if( $usTop10VideoDownload === false ){
                      $usTop10VideoDownload = $this->Common->getUsTop10Videos($territory);
                } 
            }
        }
        $this->set('usTop10VideoDownload', $usTop10VideoDownload);
    }

    function national_top_download() {

        $libId = $this->Session->read('library');
        $territory = $this->Session->read('territory');

        // National Top Downloads functionality
        $nationalTopDownload = Cache::read("national" . $territory);
        if ($nationalTopDownload === false) {

            $country = $territory;

            $siteConfigSQL = "SELECT * from siteconfigs WHERE soption = 'maintain_ldt'";
            $siteConfigData = $this->Album->query($siteConfigSQL);
            $maintainLatestDownload = (($siteConfigData[0]['siteconfigs']['svalue'] == 1) ? true : false);

            if ($maintainLatestDownload) {
                $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type 
                            FROM `latest_downloads` AS `Download` 
                            LEFT JOIN libraries ON libraries.id=Download.library_id
                            WHERE libraries.library_territory = '" . $country . "' 
                            AND `Download`.`created` BETWEEN '" . Configure::read('App.lastWeekStartDate') . "' AND '" . Configure::read('App.lastWeekEndDate') . "' 
                            GROUP BY Download.ProdID 
                            ORDER BY `countProduct` DESC 
                            LIMIT 110";
            } else {
                $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type 
                            FROM `downloads` AS `Download` 
                            LEFT JOIN libraries ON libraries.id=Download.library_id
                            WHERE libraries.library_territory = '" . $country . "' 
                            AND `Download`.`created` BETWEEN '" . Configure::read('App.lastWeekStartDate') . "' AND '" . Configure::read('App.lastWeekEndDate') . "' 
                            GROUP BY Download.ProdID 
                            ORDER BY `countProduct` DESC 
                            LIMIT 110";
            }

            $ids = '';
            $natTopDownloaded = $this->Album->query($sql);
            foreach ($natTopDownloaded as $natTopSong) {
                if (empty($ids)) {
                    $ids .= $natTopSong['Download']['ProdID'];
                    $ids_provider_type .= "(" . $natTopSong['Download']['ProdID'] . ",'" . $natTopSong['Download']['provider_type'] . "')";
                } else {
                    $ids .= ',' . $natTopSong['Download']['ProdID'];
                    $ids_provider_type .= ',' . "(" . $natTopSong['Download']['ProdID'] . ",'" . $natTopSong['Download']['provider_type'] . "')";
                }
            }
            $data = array();

            $countryPrefix = $this->Session->read('multiple_countries');
            $sql_national_100 = <<<STR
	SELECT 
		Song.ProdID,
		Song.ReferenceID,
		Song.Title,
		Song.ArtistText,
		Song.DownloadStatus,
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
		Genre AS Genre ON (Genre.ProdID = Song.ProdID) AND (Song.provider_type = Genre.provider_type)
			LEFT JOIN
		{$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Country.Territory = '$country') AND (Song.provider_type = Country.provider_type) AND (Country.SalesDate != '') AND (Country.SalesDate < NOW())
			LEFT JOIN
		PRODUCT ON (PRODUCT.ProdID = Song.ProdID) AND (PRODUCT.provider_type = Song.provider_type)
	WHERE
		( (Song.DownloadStatus = '1') AND ((Song.ProdID, Song.provider_type) IN ($ids_provider_type))  )  AND 1 = 1
	GROUP BY Song.ProdID
	ORDER BY FIELD(Song.ProdID,
			$ids) ASC
	LIMIT 100 
	  
STR;


            $nationalTopDownload = $this->Album->query($sql_national_100);
            // Checking for download status
            Cache::write("national" . $territory, $nationalTopDownload);
        }

        $this->set('nationalTopDownload', $nationalTopDownload);
    }

    /*
      Function Name : userDownload
      Desc : actions that is used for updating user download
     */

    function userDownload() {
        $this->layout = false;
        $prodId = $this->params['form']['ProdID'];
        $provider = $this->params['form']['ProviderType'];

        /*
          creates log file name
         */
        $log_name = 'stored_procedure_web_log_' . date('Y_m_d');
        $log_id = md5(time());
        $log_data = PHP_EOL . "----------Request (" . $log_id . ") Start----------------" . PHP_EOL;

        $Setting = $this->Siteconfig->find('first', array('conditions' => array('soption' => 'single_channel')));
        $checkValidation = $Setting['Siteconfig']['svalue'];
        if ($checkValidation == 1) {

            $validationResult = $this->Downloads->validateDownload($prodId, $provider);
            /*
              records download component request & response
             */
            $log_data .= "DownloadComponentParameters-ProdId= '" . $prodId . "':DownloadComponentParameters-Provider_type= '" . $provider . "':DownloadComponentResponse-Status='" . $validationResult[0] . "':DownloadComponentResponse-Msg='" . $validationResult[1] . "':DownloadComponentResponse-ErrorTYpe='" . $validationResult[2] . "'";

            $checked = "true";
            $validationPassed = $validationResult[0];
            $validationPassedMessage = (($validationResult[0] == 0) ? 'false' : 'true');
            $validationMessage = $validationResult[1];
        } else {
            $checked = "false";
            $validationPassed = true;
            $validationPassedMessage = "Not Checked";
            $validationMessage = '';
        }
        $user = $this->Session->read('Auth.User.id');
        if (empty($user)) {
            $user = $this->Session->read('patron');
        }

        if ($validationPassed == true) {
            $this->log("Validation Checked : " . $checked . " Valdition Passed : " . $validationPassedMessage . " Validation Message : " . $validationMessage . " for ProdID :" . $prodId . " and Provider : " . $provider . " for library id : " . $this->Session->read('library') . " and user id : " . $user, 'download');
            $libId = $this->Session->read('library');
            $patId = $this->Session->read('patron');
            $prodId = $this->params['form']['ProdID'];
            if ($prodId == '' || $prodId == 0) {
                $this->redirect(array('controller' => 'homes', 'action' => 'index'));
            }
            $downloadsDetail = array();

            $provider = $this->params['form']['ProviderType'];
            $trackDetails = $this->Song->getdownloaddata($prodId, $provider);
            $insertArr = Array();
            $insertArr['library_id'] = $libId;
            $insertArr['patron_id'] = $patId;
            $insertArr['ProdID'] = $prodId;
            $insertArr['artist'] = addslashes($trackDetails['0']['Song']['Artist']);
            $insertArr['track_title'] = addslashes($trackDetails['0']['Song']['SongTitle']);

            if ($provider != 'sony') {
                $provider = 'ioda';
            }
            $insertArr['provider_type'] = $provider;

            $insertArr['ProductID'] = $trackDetails['0']['Song']['ProductID'];
            $insertArr['ISRC'] = $trackDetails['0']['Song']['ISRC'];            
            $songUrl  = $this->Token->regularToken($trackDetails['0']['Full_Files']['CdnPath'] . "/" . $trackDetails['0']['Full_Files']['SaveAsName']);                
            $finalSongUrl = Configure::read('App.Music_Path') . $songUrl;

            if ($this->Session->read('referral_url') && ($this->Session->read('referral_url') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'referral_url';
            } elseif ($this->Session->read('innovative') && ($this->Session->read('innovative') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative';
            } elseif ($this->Session->read('mdlogin_reference') && ($this->Session->read('mdlogin_reference') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'mdlogin_reference';
            } elseif ($this->Session->read('mndlogin_reference') && ($this->Session->read('mndlogin_reference') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'mndlogin_reference';
            } elseif ($this->Session->read('innovative_var') && ($this->Session->read('innovative_var') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_var';
            } elseif ($this->Session->read('innovative_var_name') && ($this->Session->read('innovative_var_name') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_var_name';
            } elseif ($this->Session->read('innovative_var_https_name') && ($this->Session->read('innovative_var_https_name') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_var_https_name';
            } elseif ($this->Session->read('innovative_var_https') && ($this->Session->read('innovative_var_https') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_var_https';
            } elseif ($this->Session->read('innovative_var_https_wo_pin') && ($this->Session->read('innovative_var_https_wo_pin') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_var_https_wo_pin';
            } elseif ($this->Session->read('innovative_https') && ($this->Session->read('innovative_https') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_https';
            } elseif ($this->Session->read('innovative_wo_pin') && ($this->Session->read('innovative_wo_pin') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_wo_pin';
            } elseif ($this->Session->read('sip2') && ($this->Session->read('sip2') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'sip2';
            } elseif ($this->Session->read('sip') && ($this->Session->read('sip') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'sip';
            } elseif ($this->Session->read('innovative_var_wo_pin') && ($this->Session->read('innovative_var_wo_pin') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_var_wo_pin';
            } elseif ($this->Session->read('sip2_var') && ($this->Session->read('sip2_var') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'sip2_var';
            } elseif ($this->Session->read('sip2_var') && ($this->Session->read('sip2_var') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'sip2_var_wo_pin';
            } elseif ($this->Session->read('sip2_var_wo_pin') && ($this->Session->read('sip2_var_wo_pin') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'sip2_var_wo_pin';
            } elseif ($this->Session->read('ezproxy') && ($this->Session->read('ezproxy') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'ezproxy';
            } elseif ($this->Session->read('soap') && ($this->Session->read('soap') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'soap';
            } elseif ($this->Session->read('curl_method') && ($this->Session->read('curl_method') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'curl_method';
            } else {
                $insertArr['email'] = $this->Session->read('patronEmail');
                $insertArr['user_login_type'] = 'user_account';
            }

            $insertArr['user_agent'] = str_replace(";", "", $_SERVER['HTTP_USER_AGENT']);
            $insertArr['ip'] = $_SERVER['REMOTE_ADDR'];
            $this->Library->setDataSource('master');

            $siteConfigSQL = "SELECT * from siteconfigs WHERE soption = 'maintain_ldt'";
            $siteConfigData = $this->Album->query($siteConfigSQL);
            $maintainLatestDownload = (($siteConfigData[0]['siteconfigs']['svalue'] == 1) ? true : false);

            if ($maintainLatestDownload) {
                $this->log("sonyproc_new called", 'download');
                $procedure = 'sonyproc_new';
                $sql = "CALL sonyproc_new('" . $libId . "','" . $patId . "', '" . $prodId . "', '" . $trackDetails['0']['Song']['ProductID'] . "', '" . $trackDetails['0']['Song']['ISRC'] . "', '" . addslashes($trackDetails['0']['Song']['Artist']) . "', '" . addslashes($trackDetails['0']['Song']['SongTitle']) . "', '" . $insertArr['user_login_type'] . "', '" . $insertArr['provider_type'] . "', '" . $insertArr['email'] . "', '" . addslashes($insertArr['user_agent']) . "', '" . $insertArr['ip'] . "', '" . Configure::read('App.curWeekStartDate') . "', '" . Configure::read('App.curWeekEndDate') . "',@ret)";
            } else {
                $procedure = 'sonyproc_ioda';
                $sql = "CALL sonyproc_ioda('" . $libId . "','" . $patId . "', '" . $prodId . "', '" . $trackDetails['0']['Song']['ProductID'] . "', '" . $trackDetails['0']['Song']['ISRC'] . "', '" . addslashes($trackDetails['0']['Song']['Artist']) . "', '" . addslashes($trackDetails['0']['Song']['SongTitle']) . "', '" . $insertArr['user_login_type'] . "', '" . $insertArr['provider_type'] . "', '" . $insertArr['email'] . "', '" . addslashes($insertArr['user_agent']) . "', '" . $insertArr['ip'] . "', '" . Configure::read('App.curWeekStartDate') . "', '" . Configure::read('App.curWeekEndDate') . "',@ret)";
            }

            $this->Library->query($sql);
            $sql = "SELECT @ret";
            $data = $this->Library->query($sql);
            $return = $data[0][0]['@ret'];

            $log_data .= ":StoredProcedureParameters-LibID='" . $libId . "':StoredProcedureParameters-Patron='" . $patId . "':StoredProcedureParameters-ProdID='" . $prodId . "':StoredProcedureParameters-ProductID='" . $trackDetails['0']['Song']['ProductID'] . "':StoredProcedureParameters-ISRC='" . $trackDetails['0']['Song']['ISRC'] . "':StoredProcedureParameters-Artist='" . addslashes($trackDetails['0']['Song']['Artist']) . "':StoredProcedureParameters-SongTitle='" . addslashes($trackDetails['0']['Song']['SongTitle']) . "':StoredProcedureParameters-UserLoginType='" . $insertArr['user_login_type'] . "':StoredProcedureParameters-ProviderType='" . $insertArr['provider_type'] . "':StoredProcedureParameters-Email='" . $insertArr['email'] . "':StoredProcedureParameters-UserAgent='" . addslashes($insertArr['user_agent']) . "':StoredProcedureParameters-IP='" . $insertArr['ip'] . "':StoredProcedureParameters-CurWeekStartDate='" . Configure::read('App.curWeekStartDate') . "':StoredProcedureParameters-CurWeekEndDate='" . Configure::read('App.curWeekEndDate') . "':StoredProcedureParameters-Name='" . $procedure . "':StoredProcedureParameters-@ret='" . $return . "'";

            if (is_numeric($return)) {

                $this->LatestDownload->setDataSource('master');
                $data = $this->LatestDownload->find('count', array(
                    'conditions' => array(
                        "LatestDownload.library_id " => $libId,
                        "LatestDownload.patron_id " => $patId,
                        "LatestDownload.ProdID " => $prodId,
                        "LatestDownload.provider_type " => $insertArr['provider_type'],
                        "DATE(LatestDownload.created) " => date('Y-m-d'),
                    ),
                    'recursive' => -1,
                ));

                if (0 === $data) {
                    $log_data .= ":NotInLD";
                }

                if (false === $data) {
                    $log_data .= ":SelectLDFail";
                }
                $this->LatestDownload->setDataSource('default');
            }

            $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------";

            $this->log($log_data, $log_name);

            $this->Library->setDataSource('default');
            if (is_numeric($return)) {

                header("Location: " . $finalSongUrl);
                exit;
            } else {
                if ($return == 'incld') {
                    $this->Session->setFlash("You have already downloaded this song. Get it from your recent downloads.");
                    $this->redirect(array('controller' => 'homes', 'action' => 'my_history'));
                } else {
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit;
                }
            }
        } else {
            /*
              complete records with validation fail
             */
            $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------" . PHP_EOL;
            $this->log($log_data, $log_name);

            $this->Session->setFlash($validationResult[1]);
            $this->redirect(array('controller' => 'homes', 'action' => 'index'));
        }
    }

    /*
      Function Name : checkPatron
      Desc : actions used for validating patron access
     */

    function checkPatron() {
        $this->layout = false;
        
        if ( $this->RequestHandler->isPost() ) {
        	$index = 'form';
        } else if ( $this->RequestHandler->isGet() ) {
        	$index = 'url';
        }

        $libid = $this->params[$index]['libid'];
        $patronid = $this->params[$index]['patronid'];
        $patronid = str_replace("_", "+", $this->params[$index]['patronid']);
        $userCache = Cache::read("login_" . $this->Session->read('territory') . "_" . $libid . "_" . $patronid);
        $date = time();
        $modifiedTime = $userCache[0];
        //checking form db if session exists
        $sql = mysql_query("SELECT id FROM `sessions` Where id='" . session_id() . "'");
        $count = mysql_num_rows($sql);
        $values = array(0 => $date, 1 => session_id());

        $date = time();
        $name = $_SERVER['SERVER_ADDR'];
        $values = array(0 => $date, 1 => session_id());
        //writing to memcache and writing to both the memcached servers
        Cache::write("login_" . $this->Session->read('territory') . "_" . $libid . "_" . $patronid, $values);
        echo "success" . $name;
        exit;
    }

    /*
      Function Name : approvePatron
      Desc : actions used for approve terms access
     */

    function approvePatron() {
        $this->layout = false;
        
        if ( $this->RequestHandler->isPost() ) {
        	$index = 'form';
        } else if ( $this->RequestHandler->isGet() ) {
        	$index = 'url';
        }

        $libid = $this->params[$index]['libid'];
        $patronid = base64_decode($this->params[$index]['patronid']);
        $currentPatron = $this->Currentpatron->find('all', array('conditions' => array('libid' => $libid, 'patronid' => $patronid)));
        if (count($currentPatron) > 0){
            $updateArr = array();
            $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];
            if ($this->Session->read('consortium') && $this->Session->read('consortium') != '') {
                $updateArr['consortium'] = $this->Session->read('consortium');
            }
            $updateArr['is_approved'] = 'yes';
            $this->Currentpatron->setDataSource('master');
            $this->Currentpatron->save($updateArr);
            $this->Currentpatron->setDataSource('default');
            $this->Session->write('approved', 'yes');
            $this->log("homes/approvePatron: [libid=".$libid.", patronid=".$patronid.", id=".$currentPatron[0]['Currentpatron']['id'].", CNT=".count($currentPatron).", last inserted ID=".$this->Currentpatron->getLastInsertId()."]", "currentpatrons");
        }
        echo "Success";
        exit;
    }

    /*
      Function Name : admin_aboutusform
      Desc : actions used for admin about us form
     */

    function admin_aboutusform() {

        // allwoes only admin
        if ((!$this->Session->read('Auth.User.type_id')) && ($this->Session->read('Auth.User.type_id') != 1)) {
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }

        if (isset($this->data) && ($this->data['Home']['language_change']) == 1) {
            $language = $this->data['Home']['language'];
            $this->set('formAction', 'admin_aboutusform');
            $this->set('formHeader', 'Manage About Us Page Content');
            $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'aboutus', 'language' => $this->data['Home']['language'])));
            if (count($getPageData) != 0) {
                $getData['Home']['id'] = $getPageData[0]['Page']['id'];
                $getData['Home']['page_name'] = $getPageData[0]['Page']['page_name'];
                $getData['Home']['page_content'] = $getPageData[0]['Page']['page_content'];
                $getData['Home']['language'] = $language;
                $this->set('getData', $getData);
            } else {
                $getData['Home']['language'] = $language;
                $getData['Home']['id'] = null;
                $getData['Home']['page_name'] = null;
                $getData['Home']['page_content'] = null;
                $this->set('getData', $getData);
            }
        } else {
            if (isset($this->data)) {
                $findData = $this->Page->find('all', array('conditions' => array('page_name' => 'aboutus', 'language' => $this->data['Home']['language'])));
                if (count($findData) == 0) {
                    $pageData['Page']['page_name'] = $this->data['Home']['page_name'];
                    $pageData['Page']['page_content'] = $this->data['Home']['page_content'];
                    $pageData['Page']['language'] = $this->data['Home']['language'];
                    $this->Page->set($pageData['Page']);
                    $this->Page->setDataSource('master');
                    if ($this->Page->save()) {
                        $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                    }
                    $this->Page->setDataSource('default');
                } elseif (count($findData) > 0) {
                    $this->Page->id = $this->data['Home']['id'];
                    $pageData['Page']['page_name'] = $this->data['Home']['page_name'];
                    $pageData['Page']['page_content'] = $this->data['Home']['page_content'];
                    $pageData['Page']['language'] = $this->data['Home']['language'];
                    $this->Page->set($pageData['Page']);
                    $this->Page->setDataSource('master');
                    if ($this->Page->save()) {
                        $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                    } else {
                        $this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
                    }
                    $this->Page->setDataSource('default');
                }
            }
            $this->set('formAction', 'admin_aboutusform');
            $this->set('formHeader', 'Manage About Us Page Content');
            $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'aboutus', 'language' => 'en')));
            if (count($getPageData) != 0) {
                $getData['Home']['id'] = $getPageData[0]['Page']['id'];
                $getData['Home']['page_name'] = $getPageData[0]['Page']['page_name'];
                $getData['Home']['page_content'] = $getPageData[0]['Page']['page_content'];
                $getData['Home']['language'] = $getPageData[0]['Page']['language'];
                $this->set('getData', $getData);
            } else {
                $arr = array();
                $this->set('getData', $arr);
            }
        }
        $this->set('languages', $this->Language->find('list', array('fields' => array('short_name', 'full_name'))));
        $this->layout = 'admin';
    }

    /*
      Function Name : admin_termsform
      Desc : actions used for admin terms form
     */

    function admin_termsform() {

        // allwoes only admin
        if ((!$this->Session->read('Auth.User.type_id')) && ($this->Session->read('Auth.User.type_id') != 1)) {
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }

        if (isset($this->data) && ($this->data['Home']['language_change']) == 1) {
            $language = $this->data['Home']['language'];
            $this->set('formAction', 'admin_termsform');
            $this->set('formHeader', 'Manage Terms & Condition Page Content');
            $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'terms', 'language' => $this->data['Home']['language'])));
            if (count($getPageData) != 0) {
                $getData['Home']['id'] = $getPageData[0]['Page']['id'];
                $getData['Home']['page_name'] = $getPageData[0]['Page']['page_name'];
                $getData['Home']['page_content'] = $getPageData[0]['Page']['page_content'];
                $getData['Home']['language'] = $language;
                $this->set('getData', $getData);
            } else {
                $getData['Home']['language'] = $language;
                $getData['Home']['id'] = null;
                $getData['Home']['page_name'] = null;
                $getData['Home']['page_content'] = null;
                $this->set('getData', $getData);
            }
        } else {
            if (isset($this->data)) {
                $findData = $this->Page->find('all', array('conditions' => array('page_name' => 'terms', 'language' => $this->data['Home']['language'])));
                if (count($findData) == 0) {
                    $pageData['Page']['page_name'] = $this->data['Home']['page_name'];
                    $pageData['Page']['page_content'] = $this->data['Home']['page_content'];
                    $pageData['Page']['language'] = $this->data['Home']['language'];
                    $this->Page->set($pageData['Page']);
                    $this->Page->setDataSource('master');
                    if ($this->Page->save()) {
                        $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                    }
                    $this->Page->setDataSource('default');
                } elseif (count($findData) > 0) {
                    $this->Page->id = $this->data['Home']['id'];
                    $pageData['Page']['page_name'] = $this->data['Home']['page_name'];
                    $pageData['Page']['page_content'] = $this->data['Home']['page_content'];
                    $pageData['Page']['language'] = $this->data['Home']['language'];
                    $this->Page->set($pageData['Page']);
                    $this->Page->setDataSource('master');
                    if ($this->Page->save()) {
                        $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                    } else {
                        $this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
                    }
                    $this->Page->setDataSource('default');
                }
            }
            $this->set('formAction', 'admin_termsform');
            $this->set('formHeader', 'Manage Terms & Condition Page Content');
            $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'terms', 'language' => 'en')));
            if (count($getPageData) != 0) {
                $getData['Home']['id'] = $getPageData[0]['Page']['id'];
                $getData['Home']['page_name'] = $getPageData[0]['Page']['page_name'];
                $getData['Home']['page_content'] = $getPageData[0]['Page']['page_content'];
                $getData['Home']['language'] = $getPageData[0]['Page']['language'];
                $this->set('getData', $getData);
            } else {
                $arr = array();
                $this->set('getData', $arr);
            }
        }
        $this->set('languages', $this->Language->find('list', array('fields' => array('short_name', 'full_name'))));
        $this->layout = 'admin';
    }

    /*
      Function Name : admin_loginform
      Desc : actions used for admin login form
     */

    function admin_loginform() {

        // allwoes only admin
        if ((!$this->Session->read('Auth.User.type_id')) && ($this->Session->read('Auth.User.type_id') != 1)) {
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }

        if (isset($this->data) && ($this->data['Home']['language_change']) == 1) {
            $language = $this->data['Home']['language'];
            $this->set('formAction', 'admin_loginform');
            $this->set('formHeader', 'Manage Login Page Text');
            $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'login', 'language' => $this->data['Home']['language'])));
            if (count($getPageData) != 0) {
                $getData['Home']['id'] = $getPageData[0]['Page']['id'];
                $getData['Home']['page_name'] = $getPageData[0]['Page']['page_name'];
                $getData['Home']['page_content'] = $getPageData[0]['Page']['page_content'];
                $getData['Home']['language'] = $language;
                $this->set('getData', $getData);
            } else {
                $getData['Home']['language'] = $language;
                $getData['Home']['id'] = null;
                $getData['Home']['page_name'] = null;
                $getData['Home']['page_content'] = null;
                $this->set('getData', $getData);
            }
        } else {
            if (isset($this->data)) {
                $findData = $this->Page->find('all', array('conditions' => array('page_name' => 'login', 'language' => $this->data['Home']['language'])));
                
                if (count($findData) == 0) {
                    $pageData['Page']['page_name'] = $this->data['Home']['page_name'];
                    $pageData['Page']['page_content'] = $this->data['Home']['page_content'];
                    $pageData['Page']['language'] = $this->data['Home']['language'];
                    $this->Page->set($pageData['Page']);
                    $this->Page->setDataSource('master');
                    if ($this->Page->save()) {
                        $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                    }
                    $this->Page->setDataSource('default');
                } elseif (count($findData) > 0) {
                    $this->Page->id = $this->data['Home']['id'];
                    $pageData['Page']['page_name'] = $this->data['Home']['page_name'];
                    $pageData['Page']['page_content'] = $this->data['Home']['page_content'];
                    $pageData['Page']['language'] = $this->data['Home']['language'];
                    $this->Page->set($pageData['Page']);
                    $this->Page->setDataSource('master');
                    if ($this->Page->save()) {
                        $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                    } else {
                        $this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
                    }
                    $this->Page->setDataSource('default');
                }
            }
            $this->set('formAction', 'admin_loginform');
            $this->set('formHeader', 'Manage Login Page Text');
            $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'login', 'language' => 'en')));
            if (count($getPageData) != 0) {
                $getData['Home']['id'] = $getPageData[0]['Page']['id'];
                $getData['Home']['page_name'] = $getPageData[0]['Page']['page_name'];
                $getData['Home']['page_content'] = $getPageData[0]['Page']['page_content'];
                $getData['Home']['language'] = $getPageData[0]['Page']['language'];
                $this->set('getData', $getData);
            } else {
                $arr = array();
                $this->set('getData', $arr);
            }
        }
        $this->set('languages', $this->Language->find('list', array('fields' => array('short_name', 'full_name'))));
        $this->layout = 'admin';
    }

    /*
      Function Name : admin_wishlistform
      Desc : actions used for admin wishlist form
     */

    function admin_wishlistform() {

        // allwoes only admin
        if ((!$this->Session->read('Auth.User.type_id')) && ($this->Session->read('Auth.User.type_id') != 1)) {
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }

        if (isset($this->data) && ($this->data['Home']['language_change']) == 1) {
            $language = $this->data['Home']['language'];
            $this->set('formAction', 'admin_wishlistform');
            $this->set('formHeader', 'Manage Wishlist Page Content');
            $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'wishlist', 'language' => $this->data['Home']['language'])));
            if (count($getPageData) != 0) {
                $getData['Home']['id'] = $getPageData[0]['Page']['id'];
                $getData['Home']['page_name'] = $getPageData[0]['Page']['page_name'];
                $getData['Home']['page_content'] = $getPageData[0]['Page']['page_content'];
                $getData['Home']['language'] = $language;
                $this->set('getData', $getData);
            } else {
                $getData['Home']['language'] = $language;
                $getData['Home']['id'] = null;
                $getData['Home']['page_name'] = null;
                $getData['Home']['page_content'] = null;
                $this->set('getData', $getData);
            }
        } else {
            if (isset($this->data)) {
                $findData = $this->Page->find('all', array('conditions' => array('page_name' => 'wishlist', 'language' => $this->data['Home']['language'])));
                
                if (count($findData) == 0) {
                    $pageData['Page']['page_name'] = $this->data['Home']['page_name'];
                    $pageData['Page']['page_content'] = $this->data['Home']['page_content'];
                    $pageData['Page']['language'] = $this->data['Home']['language'];
                    $this->Page->set($pageData['Page']);
                    $this->Page->setDataSource('master');
                    if ($this->Page->save()) {
                        $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                    }
                    $this->Page->setDataSource('default');
                } elseif (count($findData) > 0) {
                    $this->Page->id = $this->data['Home']['id'];
                    $pageData['Page']['page_name'] = $this->data['Home']['page_name'];
                    $pageData['Page']['page_content'] = $this->data['Home']['page_content'];
                    $pageData['Page']['language'] = $this->data['Home']['language'];
                    $this->Page->set($pageData['Page']);
                    $this->Page->setDataSource('master');
                    if ($this->Page->save()) {
                        $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                    } else {
                        $this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
                    }
                    $this->Page->setDataSource('default');
                }
            }
            $this->set('formAction', 'admin_wishlistform');
            $this->set('formHeader', 'Manage Wishlist Page Text');
            $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'wishlist', 'language' => 'en')));
            if (count($getPageData) != 0) {
                $getData['Home']['id'] = $getPageData[0]['Page']['id'];
                $getData['Home']['page_name'] = $getPageData[0]['Page']['page_name'];
                $getData['Home']['page_content'] = $getPageData[0]['Page']['page_content'];
                $getData['Home']['language'] = $getPageData[0]['Page']['language'];
                $this->set('getData', $getData);
            } else {
                $arr = array();
                $this->set('getData', $arr);
            }
        }
        $this->set('languages', $this->Language->find('list', array('fields' => array('short_name', 'full_name'))));
        $this->layout = 'admin';
    }

    /*
      Function Name : admin_limitsform
      Desc : actions used for admin limits form
     */

    function admin_limitsform() {

        // allwoes only admin
        if ((!$this->Session->read('Auth.User.type_id')) && ($this->Session->read('Auth.User.type_id') != 1)){
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }

        if (isset($this->data) && ($this->data['Home']['language_change']) == 1) {
            $language = $this->data['Home']['language'];
            $this->set('formAction', 'admin_limitsform');
            $this->set('formHeader', 'Manage Download Limits Page Content');
            $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'limits', 'language' => $this->data['Home']['language'])));
            
            if (count($getPageData) != 0) {
                $getData['Home']['id'] = $getPageData[0]['Page']['id'];
                $getData['Home']['page_name'] = $getPageData[0]['Page']['page_name'];
                $getData['Home']['page_content'] = $getPageData[0]['Page']['page_content'];
                $getData['Home']['language'] = $language;
                $this->set('getData', $getData);
            } else {
                $getData['Home']['language'] = $language;
                $getData['Home']['id'] = null;
                $getData['Home']['page_name'] = null;
                $getData['Home']['page_content'] = null;
                $this->set('getData', $getData);
            }
        } else {
            if (isset($this->data)) {
                $findData = $this->Page->find('all', array('conditions' => array('page_name' => 'limits', 'language' => $this->data['Home']['language'])));
                if (count($findData) == 0) {
                    $pageData['Page']['page_name'] = $this->data['Home']['page_name'];
                    $pageData['Page']['page_content'] = $this->data['Home']['page_content'];
                    $pageData['Page']['language'] = $this->data['Home']['language'];
                    $this->Page->set($pageData['Page']);
                    $this->Page->setDataSource('master');
                    if ($this->Page->save()) {
                        $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                    }
                    $this->Page->setDataSource('default');
                } elseif (count($findData) > 0) {
                    $this->Page->id = $this->data['Home']['id'];
                    $pageData['Page']['page_name'] = $this->data['Home']['page_name'];
                    $pageData['Page']['page_content'] = $this->data['Home']['page_content'];
                    $pageData['Page']['language'] = $this->data['Home']['language'];
                    $this->Page->set($pageData['Page']);
                    $this->Page->setDataSource('master');
                    if ($this->Page->save()) {
                        $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                    } else {
                        $this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
                    }
                    $this->Page->setDataSource('default');
                }
            }
            $this->set('formAction', 'admin_limitsform');
            $this->set('formHeader', 'Manage Download Limits Page Text');
            $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'limits', 'language' => 'en')));
            
            if (count($getPageData) != 0) {
                $getData['Home']['id'] = $getPageData[0]['Page']['id'];
                $getData['Home']['page_name'] = $getPageData[0]['Page']['page_name'];
                $getData['Home']['page_content'] = $getPageData[0]['Page']['page_content'];
                $getData['Home']['language'] = $getPageData[0]['Page']['language'];
                $this->set('getData', $getData);
            } else {
                $arr = array();
                $this->set('getData', $arr);
            }
        }
        $this->set('languages', $this->Language->find('list', array('fields' => array('short_name', 'full_name'))));

        $this->layout = 'admin';
    }

    /*
      Function Name : aboutus
      Desc : actions used for User end checking for cookie and javascript enable
     */

    function aboutus() {
        if (isset($this->params['pass'][0]) && $this->params['pass'][0] == "js_err") {
            
            if ($this->Session->read('referral_url') && ($this->Session->read('referral_url') != '')) {
                $url = $this->Session->read('referral_url');
            } elseif ($this->Session->read('innovative') && ($this->Session->read('innovative') != '')) {
                $url = $this->webroot . 'users/ilogin';
            } elseif ($this->Session->read('mdlogin_reference') && ($this->Session->read('mdlogin_reference') != '')) {
                $url = $this->webroot . 'users/mdlogin';
            } elseif ($this->Session->read('mndlogin_reference') && ($this->Session->read('mndlogin_reference') != '')) {
                $url = $this->webroot . 'users/mndlogin';
            } elseif ($this->Session->read('innovative_var') && ($this->Session->read('innovative_var') != '')) {
                $url = $this->webroot . 'users/idlogin';
            } elseif ($this->Session->read('innovative_var_name') && ($this->Session->read('innovative_var_name') != '')) {
                $url = $this->webroot . 'users/ildlogin';
            } elseif ($this->Session->read('innovative_var_https_name') && ($this->Session->read('innovative_var_https_name') != '')) {
                $url = $this->webroot . 'users/ilhdlogin';
            } elseif ($this->Session->read('innovative_var_https') && ($this->Session->read('innovative_var_https') != '')) {
                $url = $this->webroot . 'users/ihdlogin';
            } elseif ($this->Session->read('innovative_var_https_wo_pin') && ($this->Session->read('innovative_var_https_wo_pin') != '')) {
                $url = $this->webroot . 'users/inhdlogin';
            } elseif ($this->Session->read('innovative_https') && ($this->Session->read('innovative_https') != '')) {
                $url = $this->webroot . 'users/inhlogin';
            } elseif ($this->Session->read('innovative_wo_pin') && ($this->Session->read('innovative_wo_pin') != '')) {
                $url = $this->webroot . 'users/inlogin';
            } elseif ($this->Session->read('innovative_var_wo_pin') && ($this->Session->read('innovative_var_wo_pin') != '')) {
                $url = $this->webroot . 'users/indlogin';
            } elseif ($this->Session->read('sip2') && ($this->Session->read('sip2') != '')) {
                $url = $this->webroot . 'users/slogin';
            } elseif ($this->Session->read('sip') && ($this->Session->read('sip') != '')) {
                $url = $this->webroot . 'users/snlogin';
            } elseif ($this->Session->read('sip2_var') && ($this->Session->read('sip2_var') != '')) {
                $url = $this->webroot . 'users/sdlogin';
            } elseif ($this->Session->read('sip2_var_wo_pin') && ($this->Session->read('sip2_var_wo_pin') != '')) {
                $url = $this->webroot . 'users/sndlogin';
            } elseif ($this->Session->read('ezproxy') && ($this->Session->read('ezproxy') != '')) {
                $url = $this->webroot . 'users/sso';
            } elseif ($this->Session->read('soap') && ($this->Session->read('soap') != '')) {
                $url = $this->webroot . 'users/plogin';
            } elseif ($this->Session->read('curl_method') && ($this->Session->read('curl_method') != '')) {
                $url = $this->webroot . 'users/clogin';
            } else {
                $url = $this->webroot . 'users/login';
            }
            $patronId = $this->Session->read('patron');
            $libraryId = $this->Session->read('library');
            $patronDetails = $this->Currentpatron->find('all', array('conditions' => array('patronid' => $patronId, 'libid' => $libraryId)));
            if (count($patronDetails) > 0) {
                $updateTime = date("Y-m-d H:i:s", time() - 60);
                $this->Currentpatron->id = $patronDetails[0]['Currentpatron']['id'];
                $this->Currentpatron->setDataSource('master');
                $this->Currentpatron->saveField('modified', $updateTime, false);
                $this->Currentpatron->setDataSource('default');
                $this->log("homes/aboutus: [update][libid=".$libraryId.", patronid=".$patronId.", id=".$patronDetails[0]['Currentpatron']['id'].", CNT=".count($patronDetails).", last inserted ID=".$this->Currentpatron->getLastInsertId()."]", "currentpatrons");
            }
            $this->Session->destroy();
            $this->Session->setFlash("Javascript is required to use this website. For the best experience, please enable javascript and <a href='" . $url . "'>Click Here</a> to try again. <a href='https://www.google.com/adsense/support/bin/answer.py?hl=en&answer=12654' target='_blank'>Click Here</a> for the steps to enable javascript in different type of browsers.");
        }
        if (isset($this->params['pass'][0]) && $this->params['pass'][0] == "cookie_err") {
            $this->Session->destroy();
            $this->Session->setFlash("Cookies must be enabled to use this site. <a href='http://www.google.com/support/accounts/bin/answer.py?&answer=61416' target='_blank'>Click Here</a> for the steps to enable cookies in the different browser types.");
        }
        if ($this->Session->read('lib_status') == 'invalid') {
            $this->Session->setFlash("The library you are trying to access is not registered with us");
            $this->Session->delete('lib_status');
        }
        if ($this->Cookie->read('msg') != '') {
            $this->Session->setFlash("This account is already active");
            $this->Cookie->delete('msg');
        }

        $this->layout = 'home';
    }

    /*
      Function Name : aboutus
      Desc : actions used for Admin end checking for cookie and javascript enable
     */

    function admin_aboutus() {
        if (isset($this->params['pass'][0]) && $this->params['pass'][0] == "js_err") {
            $url = $this->webroot . 'admin/users/login';
            $this->Session->destroy();
            $this->Session->setFlash("Javascript is required to use this website. For the best experience, please enable javascript and <a href='" . $url . "'>Click Here</a> to try again. <a href='https://www.google.com/adsense/support/bin/answer.py?hl=en&answer=12654' target='_blank'>Click Here</a> for the steps to enable javascript in different type of browsers.");
        }
        if (isset($this->params['pass'][0]) && $this->params['pass'][0] == "cookie_err") {
            $this->Session->destroy();
            $this->Session->setFlash("Cookies must be enabled to use this site. <a href='http://www.google.com/support/accounts/bin/answer.py?&answer=61416' target='_blank'>Click Here</a> for the steps to enable cookies in the different browser types.");
        }
        $this->layout = 'admin';
    }

    /*
      Function Name : terms
      Desc : actions used for terms page
     */

    function terms() {
        $this->layout = 'home';
    }

    /*
      Function Name : limits
      Desc : actions used for limits page
     */

    function limits() {
        $this->layout = 'home';
    }

    /*
      Function Name : check_email
      Desc : check for a valid email
     */

    function check_email($email) {
        $email_regexp = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$";
        return eregi($email_regexp, $email);
    }

    /*
      Function Name : _sendForgotPasswordMail
      Desc : email function for forgot password
     */

    function _sendForgotPasswordMail($id, $password) {
        $this->Email->template = 'email/forgotPasswordEmail';
        $this->User->recursive = -1;
        $Patron = $this->User->read(null, $id);
        $this->set('Patron', $Patron);
        $this->set('password', $password);
        $this->Email->to = $Patron['User']['email'];
        $this->Email->from = Configure::read('App.adminEmail');
        $this->Email->fromName = Configure::read('App.fromName');
        $this->Email->subject = 'FreegalMusic - New Password information';
        $this->Email->smtpHostNames = Configure::read('App.SMTP');
        $this->Email->smtpAuth = Configure::read('App.SMTP_AUTH');
        $this->Email->smtpUserName = Configure::read('App.SMTP_USERNAME');
        $this->Email->smtpPassword = Configure::read('App.SMTP_PASSWORD');
        $result = $this->Email->send();
    }

    /*
      Function Name : forgot_password
      Desc : To send mail to patrons with new password
     */

    function forgot_password() {

        if ($this->Session->read('layout_option') == 'login_new') {
            $this->layout = 'login_new';
        } else {
            $this->layout = 'home';
        }
        $errorMsg = '';
        if (isset($this->params['form']['lang'])) {
            $language = $this->params['form']['lang'];
            $langDetail = $this->Language->find('first', array('conditions' => array('id' => $language)));
            $this->Session->write('Config.language', $langDetail['Language']['short_name']);
        } else {
            $this->Session->write('Config.language', 'en');
        }
        if ($this->params['form']['hid_action'] == 1) {

            $email = $this->params['form']['email'];
            if ($email == '') {
                $errorMsg = "Please provide your email address.";
            } elseif (!($this->check_email($email))){
                $errorMsg = "This is not a valid email.";
            } else {
                $email_exists = $this->User->find('all', array('conditions' => array('email' => $email, 'type_id' => '5')));
                if (count($email_exists) == 0) {
                    $errorMsg = "This is not a valid patron email.";
                }
            }
            if ($errorMsg != '') {
                $this->Session->setFlash($errorMsg);
            } else {
                $temp_password = $this->PasswordHelper->generatePassword(8);
                $this->User->id = $email_exists[0]['User']['id'];
                $this->data['User']['email'] = $email;
                $this->data['User']['type_id'] = '5';
                $this->data['User']['password'] = Security::hash(Configure::read('Security.salt') . $temp_password);
                $this->User->set($this->data['User']);
                $this->User->setDataSource('master');
                if ($this->User->save()) {
                    $this->_sendForgotPasswordMail($this->User->id, $temp_password);
                    $this->Session->setFlash("An email with your new password has been sent to your email account.");
                }
                $this->User->setDataSource('default');
                $this->redirect($this->webroot . 'homes/forgot_password');
            }
        }
    }

    /*
      Function Name : addToWishlist
      Desc : To let the patron add songs to wishlist
     */

    function addToWishlist() {
        //creates log for Add to wishlist method when it is called
    	if ( $this->RequestHandler->isPost() ) {
    		$index = 'form';
    	} else if ( $this->RequestHandler->isGet() ) {
    		$index = 'url';
    	}

        $log_name = 'stored_procedure_web_wishlist_log_' . date('Y_m_d');
        $log_id = md5(time());
        $log_data = PHP_EOL . "----------Request (" . $log_id . ") Start----------------" . PHP_EOL;
        $log_data .= "Library ID:" . $this->Session->read('library') . " :PatronID:" . $this->Session->read('patron')
                . " ProdID:" . $this->params[$index]['prodId'] . "  :ProviderId:" . $this->params[$index]['provider'];

        if ($this->Session->read('library') && $this->Session->read('patron') && isset($this->params[$index]['prodId']) && isset($this->params[$index]['provider'])) {
            $libraryId = $this->Session->read('library');
            $patronId = $this->Session->read('patron');

            $wishlistCount = $this->Wishlist->find('count', array('conditions' => array('library_id' => $libraryId, 'patron_id' => $patronId, 'ProdID' => $this->params[$index]['prodId'])));
            if (!$wishlistCount) {
                //get song details
                $prodId = $this->params[$index]['prodId'];
                $provider = $this->params[$index]['provider'];
                if ($provider != 'sony') {
                    $provider = 'ioda';
                }

                $trackDetails = $this->Song->getdownloaddata($prodId, $provider);

                $insertArr = Array();
                $insertArr['library_id'] = $libraryId;
                $insertArr['patron_id'] = $patronId;
                $insertArr['ProdID'] = $prodId;
                $insertArr['artist'] = $trackDetails['0']['Song']['Artist'];
                $insertArr['album'] = $trackDetails['0']['Song']['Title'];
                $insertArr['track_title'] = $trackDetails['0']['Song']['SongTitle'];
                $insertArr['ProductID'] = $trackDetails['0']['Song']['ProductID'];
                $insertArr['provider_type'] = $provider;
                $insertArr['ISRC'] = $trackDetails['0']['Song']['ISRC'];
                $insertArr['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $insertArr['ip'] = $_SERVER['REMOTE_ADDR'];

                $this->Wishlist->setDataSource('master');
                //insert into wishlist table
                $this->Wishlist->create();      //Prepare model to save record
                //check the inserting values
                $log_data .= "  :InsertArray Beofre Save:" . serialize($insertArr);
                if ($this->Wishlist->save($insertArr)) {
                    $log_data .= "  :TracklistDetails:" . serialize($trackDetails) . " :InsertArrayDetails:" . serialize($insertArr);

                    $this->Wishlist->setDataSource('default');

                    //add the wishlist songs in the session array
                    if ($this->Session->read('wishlistVariArray')) {
                        $wishlistVariArray = $this->Session->read('wishlistVariArray');
                        $wishlistVariArray[] = $prodId;
                        $this->Session->write('wishlistVariArray', $wishlistVariArray);
                    }

                    $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------";
                    $this->log($log_data, $log_name);

                    echo "Success";
                    exit;
                } else {
                    $logs = $this->Wishlist->getDataSource()->getLog();
                    $lastLog = end($logs['log']);
                    $query = $lastLog['query'];
                    $log_data .= "  :InsertArray During Save:" . serialize($insertArr) . "  :Mysql Error :" . mysql_error() . " Mysql query:" . $query;

                    $log_data .= "   Some values not found..";
                    $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------";
                    $this->log($log_data, $log_name);

                    echo 'error';
                    exit;
                }
            } else {
                $log_data .= "   TracklistDetails:Track Details not found..";
                $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------";
                $this->log($log_data, $log_name);

                echo 'error1';
                exit;
            }
        } else {
            $log_data .= "   Some values not found..";
            $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------";
            $this->log($log_data, $log_name);

            echo 'error';
            exit;
        }
    }

    /*
      Function Name : addAlbumToWishlist
      Desc : To let the patron add Albums to wishlist
     */

    function addAlbumToWishlist() {
        //creates log for Add to wishlist method when it is called
    	if ( $this->RequestHandler->isPost() ) {
    		$index = 'form';
    	} else if ( $this->RequestHandler->isGet() ) {
    		$index = 'url';
    	}

        $log_name = 'stored_procedure_web_album_wishlist_log_' . date('Y_m_d');
        $log_id = md5(time());
        $log_data = PHP_EOL . "----------Request (" . $log_id . ") Start----------------" . PHP_EOL;
        $log_data .= "Library ID:" . $this->Session->read('library') . " :PatronID:" . $this->Session->read('patron')
                . " ProdID:" . $this->params[$index]['prodId'] . "  :ProviderType:" . $this->params[$index]['providerType'] . "  :artistText:" . $this->params[$index]['artistText'];

        if ($this->Session->read('library') && $this->Session->read('patron') && isset($this->params[$index]['prodId']) && isset($this->params[$index]['providerType']) && isset($this->params[$index]['artistText'])) {
            $libraryId = $this->Session->read('library');
            $patronId = $this->Session->read('patron');

            $wishlistCount = $this->Wishlist->find('count', array('conditions' => array('library_id' => $libraryId, 'patron_id' => $patronId, 'ProdID' => $this->params[$index]['prodId'])));
            if (!$wishlistCount) {
                //get song details
                $prodId = $this->params[$index]['prodId'];
                $provider = $this->params[$index]['provider'];
                if ($provider != 'sony') {
                    $provider = 'ioda';
                }

                $trackDetails = $this->Song->getdownloaddata($prodId, $provider);

                $insertArr = Array();
                $insertArr['library_id'] = $libraryId;
                $insertArr['patron_id'] = $patronId;
                $insertArr['ProdID'] = $prodId;
                $insertArr['artist'] = $trackDetails['0']['Song']['Artist'];
                $insertArr['album'] = $trackDetails['0']['Song']['Title'];
                $insertArr['track_title'] = $trackDetails['0']['Song']['SongTitle'];
                $insertArr['ProductID'] = $trackDetails['0']['Song']['ProductID'];
                $insertArr['provider_type'] = $provider;
                $insertArr['ISRC'] = $trackDetails['0']['Song']['ISRC'];
                $insertArr['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $insertArr['ip'] = $_SERVER['REMOTE_ADDR'];

                $this->Wishlist->setDataSource('master');
                //insert into wishlist table
                $this->Wishlist->create();      //Prepare model to save record
                //check the inserting values
                $log_data .= "  :InsertArray Beofre Save:" . serialize($insertArr);
                if ($this->Wishlist->save($insertArr)) {
                    $log_data .= "  :TracklistDetails:" . serialize($trackDetails) . " :InsertArrayDetails:" . serialize($insertArr);

                    $this->Wishlist->setDataSource('default');

                    //add the wishlist songs in the session array
                    if ($this->Session->read('wishlistVariArray')) {
                        $wishlistVariArray = $this->Session->read('wishlistVariArray');
                        $wishlistVariArray[] = $prodId;
                        $this->Session->write('wishlistVariArray', $wishlistVariArray);
                    }

                    $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------";
                    $this->log($log_data, $log_name);

                    echo "Success";
                    exit;
                } else {
                    $logs = $this->Wishlist->getDataSource()->getLog();
                    $lastLog = end($logs['log']);
                    $query = $lastLog['query'];
                    $log_data .= "  :InsertArray During Save:" . serialize($insertArr) . "  :Mysql Error :" . mysql_error() . " Mysql query:" . $query;

                    $log_data .= "   Some values not found..";
                    $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------";
                    $this->log($log_data, $log_name);

                    echo 'error';
                    exit;
                }
            } else {
                $log_data .= "   TracklistDetails:Track Details not found..";
                $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------";
                $this->log($log_data, $log_name);

                echo 'error1';
                exit;
            }
        } else {
            $log_data .= "   Some values not found..";
            $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------";
            $this->log($log_data, $log_name);

            echo 'error';
            exit;
        }
    }

    /*
      Function Name : addToWishlistVideo
      Desc : To let the patron add video to wishlist
     */

    function addToWishlistVideo() {

    	if ( $this->RequestHandler->isPost() ) {
    		$index = 'form';
    	} else if ( $this->RequestHandler->isGet() ) {
    		$index = 'url';
    	}

        $log_name = 'stored_procedure_web_wishlist_log_' . date('Y_m_d');
        $log_id = md5(time());
        $log_data = PHP_EOL . "----------Request (" . $log_id . ") Start----------------" . PHP_EOL;


        if ($this->Session->read('library') && $this->Session->read('patron') && isset($this->params[$index]['prodId']) && isset($this->params[$index]['provider'])) {
            $libraryId = $this->Session->read('library');
            $patronId = $this->Session->read('patron');

            $wishlistCount = $this->WishlistVideo->find('count', array('conditions' => array('library_id' => $libraryId, 'patron_id' => $patronId, 'ProdID' => $this->params[$index]['prodId'])));
            if (!$wishlistCount) {
                $prodId = $this->params[$index]['prodId'];
                $provider = $this->params[$index]['provider'];
                if ($provider != 'sony') {
                    $provider = 'ioda';
                }

                $trackDetails = $this->Video->getVideoData($prodId, $provider);
                $insertArr = Array();
                $insertArr['library_id'] = $libraryId;
                $insertArr['patron_id'] = $patronId;
                $insertArr['ProdID'] = $prodId;
                $insertArr['artist'] = $trackDetails['0']['Video']['Artist'];
                $insertArr['album'] = $trackDetails['0']['Video']['Title'];
                $insertArr['track_title'] = $trackDetails['0']['Video']['VideoTitle'];
                $insertArr['ProductID'] = $trackDetails['0']['Video']['ProductID'];
                $insertArr['provider_type'] = $provider;
                $insertArr['ISRC'] = $trackDetails['0']['Video']['ISRC'];
                $insertArr['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $insertArr['ip'] = $_SERVER['REMOTE_ADDR'];

                $this->WishlistVideo->setDataSource('master');
                //insert into wishlist table
                $this->WishlistVideo->save($insertArr);
                $this->WishlistVideo->setDataSource('default');
                //add the wishlist videos ProdID in the session array
                if ($this->Session->read('wishlistVideoArray')) {
                    $wishlistVideoArray = $this->Session->read('wishlistVideoArray');
                    $wishlistVideoArray[] = $prodId;
                    $this->Session->write('wishlistVideoArray', $wishlistVideoArray);
                }

                $log_data .= "Library ID:" . $this->Session->read('library') . " :PatronID:" . $this->Session->read('patron')
                        . "  :ProdID:" . $this->params[$index]['prodId'] . "  :ProviderId:" . $this->params[$index]['provider'];
                $log_data .= "  :TracklistDetails:" . serialize($trackDetails) . "  :InsertArrayDetails:" . serialize($insertArr);
                $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------";
                $this->log($log_data, $log_name);

                echo "Success";
                exit;
            } else {
                $log_data .= "Library ID:" . $this->Session->read('library') . " :PatronID:" . $this->Session->read('patron')
                        . "  :ProdID:" . $this->params[$index]['prodId'] . "  :ProviderId:" . $this->params[$index]['provider'];
                $log_data .= "  :TracklistDetails:Track Details not found..";
                $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------";
                $this->log($log_data, $log_name);

                echo 'error1';
                exit;
            }
        } else {
            $log_data .= "Library ID:" . $this->Session->read('library') . " :PatronID:" . $this->Session->read('patron')
                    . "  :ProdID:" . $this->params[$index]['prodId'] . "  :ProviderId:" . $this->params[$index]['provider'];
            $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------";
            $this->log($log_data, $log_name);

            echo 'error';
            exit;
        }
    }

    /*
      Function Name : my_wishlist
      Desc : To show songs present in wishlist
     */

    function my_wishlist() {
        //set the layout
        $this->layout = 'home';
        $countryPrefix = $this->Session->read('multiple_countries');
        //fetch the session variables
        $libraryId = $this->Session->read('library');
        $patronId = $this->Session->read('patron');

        //create logics for sorting
        $sortArray = array('date', 'song', 'artist', 'album');
        $sortOrderArray = array('asc', 'desc');
        $sortOrder = '';
        $sort = '';
        if ( $this->RequestHandler->isPost() ) {
            $sort = $this->params['form']['sort'];
            $sortOrder = $this->params['form']['sortOrder'];
        }

        if (!in_array($sort, $sortArray)) {
            $sort = 'date';
        }

        if (!in_array($sortOrder, $sortOrderArray)) {
            $sortOrder = 'desc';
        }

        switch ($sort) {
            case 'date':
                $songSortBy = 'wishlists.created';
                $videoSortBy = 'WishlistVideo.created';
                $sortType = $sortOrder;
                break;
            case 'song':
                $songSortBy = 'wishlists.track_title';
                $videoSortBy = 'WishlistVideo.track_title';
                $sortType = $sortOrder;
                break;
            case 'artist':
                $songSortBy = 'wishlists.artist';
                $videoSortBy = 'WishlistVideo.artist';
                $sortType = $sortOrder;
                break;
            case 'album':
                $songSortBy = 'wishlists.album';
                $videoSortBy = 'Video.Title';
                $sortType = $sortOrder;
                break;
        }

        //check library and patron download limit  
        $libraryDownload = $this->Downloads->checkLibraryDownload($libraryId);
        $patronDownload = $this->Downloads->checkPatronDownload($patronId, $libraryId);
        $this->set('libraryDownload', $libraryDownload);
        $this->set('patronDownload', $patronDownload);

        $wishlistResults = Array();

        $wishlistQuery = <<<STR
                    SELECT 
                            wishlists.*,
                            Song.ReferenceID,
                            Song.ProdID,                            
                            Song.provider_type,
                            Song.Advisory,
                            Song.ArtistText,
                            Song.FullLength_Duration,
                            Albums.ProdID,
                            Albums.provider_type,
                            File.CdnPath,
                            File.SourceURL,
                            Country.Territory,
                            Country.SalesDate,
                            Country.StreamingSalesDate,
                            Country.StreamingStatus,
                            Country.DownloadStatus,
                            Full_Files.CdnPath,
                            Full_Files.SaveAsName,
                            File.SaveAsName 
                            
                            
                    FROM
                            Songs AS Song
                                    LEFT JOIN
                            wishlists AS wishlists ON ( (wishlists.ProdID = Song.ProdID) && (wishlists.provider_type = Song.provider_type) )
                                    LEFT JOIN 
                            File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)                                
                                    LEFT JOIN
                            {$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Song.provider_type = Country.provider_type) AND (Country.SalesDate != '')
                                    INNER JOIN Albums ON (Song.ReferenceID=Albums.ProdID) INNER JOIN File ON (Albums.FileID = File.FileID)
                    WHERE
                            library_id='$libraryId' and patron_id='$patronId' order by $songSortBy $sortType                               

      
	  
STR;
        //execute the query
        $wishlistResults = $this->Wishlist->query($wishlistQuery);

        $wishlistResultsVideos = $this->WishlistVideo->find('all', array('joins' => array(array('table' => 'video', 'alias' => 'Video', 'type' => 'LEFT', 'conditions' => array('WishlistVideo.ProdID = Video.ProdID', 'WishlistVideo.provider_type = Video.provider_type')), array('table' => 'File', 'alias' => 'File', 'type' => 'LEFT', 'conditions' => array('Video.Image_FileID = File.FileID')), array('table' => $countryPrefix . 'countries', 'alias' => 'Country', 'type' => 'LEFT', 'conditions' => array('Country.ProdID = Video.ProdID', 'Video.provider_type = Country.provider_type', 'Country.SalesDate != ""'))), 'group' => 'WishlistVideo.id', 'conditions' => array('library_id' => $libraryId, 'patron_id' => $patronId), 'fields' => array('WishlistVideo.id', 'WishlistVideo.ProdID', 'WishlistVideo.provider_type', 'WishlistVideo.track_title', 'WishlistVideo.created', 'WishlistVideo.patron_id', 'WishlistVideo.library_id', 'WishlistVideo.artist', 'Video.Title', 'Video.ReferenceID', 'Video.ArtistText', 'Video.Advisory', 'Video.provider_type', 'File.CdnPath', 'File.SourceURL', 'Country.Territory', 'Country.SalesDate'), 'order' => "$videoSortBy $sortType"));

        $this->set('wishlistResults', $wishlistResults);
        $this->set('wishlistResultsVideos', $wishlistResultsVideos);
        $this->set('sort', $sort);
        $this->set('sortOrder', $sortOrder);
    }

    /*
      Function Name : my_history
      Desc : To show songs user downloaded in last 2 weeks
     */

    function my_history() {
        $this->layout = 'home';
        $libraryId = $this->Session->read('library');
        $patronId = $this->Session->read('patron');

        $countryPrefix = $this->Session->read('multiple_countries');

        $sortArray = array('date', 'song', 'artist', 'album');
        $sortOrderArray = array('asc', 'desc');
        $sortOrder = '';
        $sort = '';
        if ( $this->RequestHandler->isPost() ) {
            $sort = $this->params['form']['sort'];
            $sortOrder = $this->params['form']['sortOrder'];
        }

        if (!in_array($sort, $sortArray)) {
            $sort = 'date';
        }

        if (!in_array($sortOrder, $sortOrderArray)) {
            $sortOrder = 'asc';
        }

        switch ($sort) {
            case 'date':
                $songSortBy = 'Download.created';
                $videoSortBy = 'Videodownload.created';
                $sortType = $sortOrder;
                break;
            case 'song':
                $songSortBy = 'Download.track_title';
                $videoSortBy = 'Videodownload.track_title';
                $sortType = $sortOrder;
                break;
            case 'artist':
                $songSortBy = 'Download.artist';
                $videoSortBy = 'Videodownload.artist';
                $sortType = $sortOrder;
                break;
            case 'album':
                $songSortBy = 'Song.Title';
                $videoSortBy = 'Video.Title';
                $sortType = $sortOrder;
                break;
        }

        $countryTableName = $countryPrefix . 'countries';
        $downloadResults = Array();
        $downloadResults = $this->Download->find('all', array('joins' => array(array('table' => 'Songs', 'alias' => 'Song', 'type' => 'LEFT', 'conditions' => array('Download.ProdID = Song.ProdID', 'Download.provider_type = Song.provider_type')), array('table' => $countryTableName, 'alias' => 'Country', 'type' => 'INNER', 'conditions' => array('Country.ProdID = Song.ProdID', 'Country.provider_type = Song.provider_type')), array('table' => 'Albums', 'alias' => 'Album', 'type' => 'LEFT', 'conditions' => array('Song.ReferenceID = Album.ProdID', 'Song.provider_type = Album.provider_type')), array('table' => 'File', 'alias' => 'File', 'type' => 'LEFT', 'conditions' => array('Album.FileID = File.FileID')), array('table' => 'File', 'alias' => 'Full_Files', 'type' => 'LEFT', 'conditions' => array('Song.FullLength_FileID = Full_Files.FileID'))), 'group' => 'Download.id', 'conditions' => array('library_id' => $libraryId, 'patron_id' => $patronId, 'history < 2', 'created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'))), 'fields' => array('Download.ProdID', 'Download.provider_type', 'Download.track_title', 'Download.created', 'Download.patron_id', 'Download.library_id', 'Download.artist, Song.Title, Song.SongTitle, Song.FullLength_Duration, Song.ProdID,Song.Advisory,Song.ArtistText,Song.ReferenceID,Song.provider_type,Album.ProdID,Album.provider_type, File.CdnPath, File.SourceURL', 'Country.StreamingSalesDate', 'Country.StreamingStatus', 'Full_Files.CdnPath', 'Full_Files.SaveAsName'), 'order' => "$songSortBy $sortType"));

        $this->set('downloadResults', $downloadResults);
        $videoDownloadResults = $this->Videodownload->find('all', array('joins' => array(array('table' => 'video', 'alias' => 'Video', 'type' => 'LEFT', 'conditions' => array('Videodownload.ProdID = Video.ProdID', 'Videodownload.provider_type = Video.provider_type')), array('table' => 'File', 'alias' => 'File', 'type' => 'LEFT', 'conditions' => array('Video.Image_FileID = File.FileID'))), 'group' => 'Videodownload.id', 'conditions' => array('library_id' => $libraryId, 'patron_id' => $patronId, 'history < 2', 'created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'))), 'fields' => array('Videodownload.ProdID', 'Videodownload.provider_type', 'Videodownload.track_title', 'Videodownload.created', 'Videodownload.patron_id', 'Videodownload.library_id', 'Videodownload.artist', 'Video.ReferenceID', 'Video.ArtistText', 'Video.Advisory', 'Video.provider_type', 'Video.Title', 'File.CdnPath', 'File.SourceURL'), 'order' => "$videoSortBy $sortType"));

        $this->set('videoDownloadResults', $videoDownloadResults);
        $this->set('sort', $sort);
        $this->set('sortOrder', $sortOrder);
    }

    /*
      Function Name : removeWishlistSong
      Desc : For removing a song from wishlist page
     */

    function removeWishlistSong() {

        $this->layout = false;
        
        if ( $this->RequestHandler->isPost() ) {
        	$index = 'form';
        } else if ( $this->RequestHandler->isGet() ) {
        	$index = 'url';
        }

        if (isset($this->params[$index]['ajax']) && isset($this->params[$index]['delete']) && $this->params[$index]['delete'] != '') {
            $temp = explode('-', trim($this->params[$index]['delete']));
            $deleteSongId = $temp[0];

            $this->Wishlist->setDataSource('master');
            if ($this->Wishlist->delete($deleteSongId)) {
                $this->Wishlist->setDataSource('default');

                $wishlistarryTemp = array();
                if ($this->Session->read('wishlistVariArray')) {
                    $wishlistVariArray = $this->Session->read('wishlistVariArray');
                    
                    if (!empty($wishlistVariArray)) {
                        
                        foreach ($wishlistVariArray as $key => $value) {
                            
                            if ($value != $temp[1]) {
                                $wishlistarryTemp[] = $wishlistVariArray[$key];
                            }
                        }
                        $this->Session->write('wishlistVariArray', $wishlistarryTemp);
                    }
                }
                echo 1;
            }
            $this->Wishlist->setDataSource('default');
            echo 0;
        }
        exit;
    }

    /*
      Function Name : removeWishlistVideo
      Desc : For removing a song from wishlist page
     */

    function removeWishlistVideo() {

        $this->layout = false;
        
        if ( $this->RequestHandler->isPost() ) {
        	$index = 'form';
        } else if ( $this->RequestHandler->isGet() ) {
        	$index = 'url';
        }

        if (isset($this->params[$index]['ajax']) && isset($this->params[$index]['delete']) && $this->params[$index]['delete'] != '') {
            $temp = explode('-', trim($this->params[$index]['delete']));
            $deleteSongId = $temp[0];
            $this->WishlistVideo->setDataSource('master');
            if ($this->WishlistVideo->delete($deleteSongId)) {
                $this->WishlistVideo->setDataSource('default');

                $wishlistarryTemp = array();
                if ($this->Session->check('wishlistVideoArray')) {
                    $wishlistVariArray = $this->Session->read('wishlistVideoArray');

                    if (!empty($wishlistVariArray)) {
                        
                        foreach ($wishlistVariArray as $key => $value) {
                            
                            if ($value != $temp[1]) {
                                $wishlistarryTemp[] = $wishlistVariArray[$key];
                            }
                        }
                        $this->Session->write('wishlistVideoArray', $wishlistarryTemp);

                    }
                }
                echo 1;
            }
            $this->WishlistVideo->setDataSource('default');
            echo 0;
        }
        exit;
    }

    /*
      Function Name : wishlistVideoDownloadToken
      Desc : For downloading a video from website 
     */
    function wishlistVideoDownloadToken()
    {

        $this->layout = false;
        if ( $this->RequestHandler->isPost() ) {
        	$index = 'form';
        } else if ( $this->RequestHandler->isGet() ) {
        	$index = 'url';
        }
        $libId = $this->Session->read('library');
        $patId = $this->Session->read('patron');

        $prodId = $this->params[$index]['prodId'];
        $CdnPath = $this->params[$index]['CdnPath'];
        $SaveAsName = $this->params[$index]['SaveAsName'];
        
        $videoUrl  = $this->Token->regularToken($CdnPath . "/" . $SaveAsName);
        $finalVideoUrl = Configure::read('App.Music_Path') . $videoUrl;
        $finalVideoUrlArr = str_split($finalVideoUrl, ceil(strlen($finalVideoUrl) / 3));
        $finalURL = urlencode($finalVideoUrlArr[0]) . urlencode($finalVideoUrlArr[1]) . urlencode($finalVideoUrlArr[2]);


        $downloadsDetail = array();
        $libraryDownload = $this->Downloadsvideos->checkLibraryDownloadVideos($libId);
        $patronDownload = $this->Downloadsvideos->checkPatronDownloadVideos($patId, $libId);

        //check for download availability
        if ($libraryDownload != '1' || $patronDownload != '1')
        {
            echo "error";
            exit;
        }
        
        //check id comming from request
        //if comming then this request will also remove records from wishlist
        $id =0;
        if(isset($this->params[$index]['id'])){
             $id = $this->params[$index]['id'];
        }
        $provider = $this->params[$index]['provider'];        
        
        //get details for this song
        $trackDetails = $this->Video->getVideoData($prodId, $provider);
        $insertArr = Array();
        $insertArr['library_id'] = $libId;
        $insertArr['patron_id'] = $patId;
        $insertArr['ProdID'] = $prodId;
        $insertArr['artist'] = $trackDetails['0']['Video']['Artist'];
        $insertArr['track_title'] = $trackDetails['0']['Video']['VideoTitle'];
        $insertArr['ProductID'] = $trackDetails['0']['Video']['ProductID'];
        $insertArr['ISRC'] = $trackDetails['0']['Video']['ISRC'];
        $insertArr['provider_type'] = $provider;

        /**
          creates log file name
        */
        $log_name = 'stored_procedure_web_wishlist_log_' . date('Y_m_d');
        $log_id = md5(time());
        $log_data = PHP_EOL . "----------Request (" . $log_id . ") Start----------------" . PHP_EOL;

        $Setting = $this->Siteconfig->find('first', array('conditions' => array('soption' => 'single_channel')));
        $checkValidation = $Setting['Siteconfig']['svalue'];
        if ($checkValidation == 1)
        {

            $validationResult = $this->Downloadsvideos->validateDownloadVideos($prodId, $provider);
            
            /**
              records download component request & response
             */
            $log_data .= "DownloadComponentParameters-ProdId= '" . $prodId . "':DownloadComponentParameters-Provider_type= '" . $provider . "':DownloadComponentResponse-Status='" . $validationResult[0] . "':DownloadComponentResponse-Msg='" . $validationResult[1] . "':DownloadComponentResponse-ErrorTYpe='" . $validationResult[2] . "'";

            $checked = "true";
            $validationPassed = $validationResult[0];
            $validationPassedMessage = (($validationResult[0] == 0) ? 'false' : 'true');
            $validationMessage = $validationResult[1];
        }
        else
        {
            $checked = "false";
            $validationPassed = true;
            $validationPassedMessage = "Not Checked";
            $validationMessage = '';
        }

        $user = $this->Session->read('Auth.User.id');
        if (empty($user))
        {
            $user = $this->Session->read('patron');
        }
        if ($validationPassed == true)
        {
            $this->log("Validation Checked : " . $checked . " Valdition Passed : " . $validationPassedMessage . " Validation Message : " . $validationMessage . " for ProdID :" . $prodId . " and Provider : " . $provider . " for library id : " . $this->Session->read('library') . " and user id : " . $user, 'download');

            if ($this->Session->read('referral_url') && ($this->Session->read('referral_url') != ''))
            {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'referral_url';
            }
            elseif ($this->Session->read('innovative') && ($this->Session->read('innovative') != ''))
            {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative';
            }
            elseif ($this->Session->read('mdlogin_reference') && ($this->Session->read('mdlogin_reference') != ''))
            {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'mdlogin_reference';
            }
            elseif ($this->Session->read('mndlogin_reference') && ($this->Session->read('mndlogin_reference') != ''))
            {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'mndlogin_reference';
            }
            elseif ($this->Session->read('innovative_var') && ($this->Session->read('innovative_var') != ''))
            {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_var';
            }
            elseif ($this->Session->read('innovative_var_name') && ($this->Session->read('innovative_var_name') != ''))
            {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_var_name';
            }
            elseif ($this->Session->read('innovative_var_https_name') && ($this->Session->read('innovative_var_https_name') != ''))
            {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_var_https_name';
            }
            elseif ($this->Session->read('innovative_var_https') && ($this->Session->read('innovative_var_https') != ''))
            {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_var_https';
            }
            elseif ($this->Session->read('innovative_var_https_wo_pin') && ($this->Session->read('innovative_var_https_wo_pin') != ''))
            {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_var_https_wo_pin';
            }
            elseif ($this->Session->read('innovative_https') && ($this->Session->read('innovative_https') != ''))
            {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_https';
            }
            elseif ($this->Session->read('innovative_wo_pin') && ($this->Session->read('innovative_wo_pin') != ''))
            {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_wo_pin';
            }
            elseif ($this->Session->read('sip2') && ($this->Session->read('sip2') != ''))
            {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'sip2';
            }
            elseif ($this->Session->read('sip') && ($this->Session->read('sip') != ''))
            {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'sip';
            }
            elseif ($this->Session->read('innovative_var_wo_pin') && ($this->Session->read('innovative_var_wo_pin') != ''))
            {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_var_wo_pin';
            }
            elseif ($this->Session->read('sip2_var') && ($this->Session->read('sip2_var') != ''))
            {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'sip2_var';
            }
            elseif ($this->Session->read('sip2_var') && ($this->Session->read('sip2_var') != ''))
            {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'sip2_var_wo_pin';
            }
            elseif ($this->Session->read('sip2_var_wo_pin') && ($this->Session->read('sip2_var_wo_pin') != ''))
            {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'sip2_var_wo_pin';
            }
            elseif ($this->Session->read('ezproxy') && ($this->Session->read('ezproxy') != ''))
            {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'ezproxy';
            }
            elseif ($this->Session->read('soap') && ($this->Session->read('soap') != ''))
            {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'soap';
            }
            elseif ($this->Session->read('curl_method') && ($this->Session->read('curl_method') != ''))
            {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'curl_method';
            }
            else
            {
                $insertArr['email'] = $this->Session->read('patronEmail');
                $insertArr['user_login_type'] = 'user_account';
            }

           $insertArr['user_agent'] = str_replace(";", "", $_SERVER['HTTP_USER_AGENT']);
            $insertArr['ip'] = $_SERVER['REMOTE_ADDR'];

            //on/off latest-download functionality
            $this->Library->setDataSource('master');
            $siteConfigSQL = "SELECT * from siteconfigs WHERE soption = 'maintain_ldt'";
            $siteConfigData = $this->Album->query($siteConfigSQL);
            $maintainLatestDownload = (($siteConfigData[0]['siteconfigs']['svalue'] == 1) ? true : false);
            $this->Library->setDataSource('master');
            if ($maintainLatestDownload)
            {
                //logs in downloadvideos.log
                $this->log("videos_proc_d_ld called", 'downloadvideos');
                $procedure = 'videos_proc_d_ld';
                //calls procedure
                $sql = "CALL videos_proc_d_ld('" . $libId . "','" . $patId . "', '" . $prodId . "', '" . $trackDetails['0']['Video']['ProductID'] . "', '" . $trackDetails['0']['Video']['ISRC'] . "', '" . addslashes($trackDetails['0']['Video']['Artist']) . "', '" . addslashes($trackDetails['0']['Video']['VideoTitle']) . "', '" . $insertArr['user_login_type'] . "', '" . $insertArr['provider_type'] . "', '" . $insertArr['email'] . "', '" . addslashes($insertArr['user_agent']) . "', '" . $insertArr['ip'] . "', '" . Configure::read('App.curWeekStartDate') . "', '" . Configure::read('App.curWeekEndDate') . "',@ret)";
            }
            else
            {
                $procedure = 'videos_proc_d';
                //calls procedure
                $sql = "CALL videos_proc_d('" . $libId . "','" . $patId . "', '" . $prodId . "', '" . $trackDetails['0']['Video']['ProductID'] . "', '" . $trackDetails['0']['Video']['ISRC'] . "', '" . addslashes($trackDetails['0']['Video']['Artist']) . "', '" . addslashes($trackDetails['0']['Video']['VideoTitle']) . "', '" . $insertArr['user_login_type'] . "', '" . $insertArr['provider_type'] . "', '" . $insertArr['email'] . "', '" . addslashes($insertArr['user_agent']) . "', '" . $insertArr['ip'] . "', '" . Configure::read('App.curWeekStartDate') . "', '" . Configure::read('App.curWeekEndDate') . "',@ret)";
            }

            //get procedure response
            $this->Library->query($sql);
            $sql = "SELECT @ret";
            $data = $this->Library->query($sql);
            $return = $data[0][0]['@ret'];

            
            //logs in downloadvideos.log
            $log_data .= ":StoredProcedureParameters-LibID='" . $libId . "':StoredProcedureParameters-Patron='" . $patId . "':StoredProcedureParameters-ProdID='" . $prodId . "':StoredProcedureParameters-ProductID='" . $trackDetails['0']['Video']['ProductID'] . "':StoredProcedureParameters-ISRC='" . $trackDetails['0']['Video']['ISRC'] . "':StoredProcedureParameters-Artist='" . addslashes($trackDetails['0']['Video']['Artist']) . "':StoredProcedureParameters-SongTitle='" . addslashes($trackDetails['0']['Video']['VideoTitle']) . "':StoredProcedureParameters-UserLoginType='" . $insertArr['user_login_type'] . "':StoredProcedureParameters-ProviderType='" . $insertArr['provider_type'] . "':StoredProcedureParameters-Email='" . $insertArr['email'] . "':StoredProcedureParameters-UserAgent='" . addslashes($insertArr['user_agent']) . "':StoredProcedureParameters-IP='" . $insertArr['ip'] . "':StoredProcedureParameters-CurWeekStartDate='" . Configure::read('App.curWeekStartDate') . "':StoredProcedureParameters-CurWeekEndDate='" . Configure::read('App.curWeekEndDate') . "':StoredProcedureParameters-Name='" . $procedure . "':StoredProcedureParameters-@ret='" . $return . "'";

            //executes IF on success
            if (is_numeric($return))
            {
                //make in LatestDownloadVideo entry
                $this->LatestVideodownload->setDataSource('master');
                $data = $this->LatestVideodownload->find('count', array(
                    'conditions' => array(
                        "LatestDownloadVideo.library_id " => $libId,
                        "LatestDownloadVideo.patron_id " => $patId,
                        "LatestDownloadVideo.ProdID " => $prodId,
                        "LatestDownloadVideo.provider_type " => $insertArr['provider_type'],
                        "DATE(LatestDownloadVideo.created) " => date('Y-m-d'),
                    ),
                    'recursive' => -1,
                ));

                // logs data
                if (0 === $data)
                {
                    $log_data .= ":NotInLD";
                }
                // logs data
                if (false === $data)
                {
                    $log_data .= ":SelectLDFail";
                }                
            }
            
            // logs data
            $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------";

            //writes in log
            $this->log($log_data, $log_name);
            
            if ($id > 0)
            {
                //delete from wishlist table              
                $this->WishlistVideo->delete($id);
                //get no of downloads for this week
            }
            
            $this->Library->setDataSource('default');
            if (is_numeric($return))
            {                
                $this->LatestVideodownload->recursive = -1;
                $videodownloadsUsed = $this->LatestVideodownload->find('count', array('conditions' => array('library_id' => $libId, 'patron_id' => $patId, 'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
                $this->LatestDownload->recursive = -1;
                $downloadscount = $this->LatestDownload->find('count', array('conditions' => array('library_id' => $libId, 'patron_id' => $patId, 'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
                $downloadsUsed = ($videodownloadsUsed * 2) + $downloadscount;
                //updating session for VideoDown load status
                $this->Common->getVideodownloadStatus($libId, $patId, Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'), true);

                 //updating session for VideoDownload status                
                echo "suces|" . $downloadsUsed . "|" . $finalURL;
                exit;
            }
            
        }
        else
        {
            /**
              complete records with validation fail
             */
            $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------" . PHP_EOL;
            $this->log($log_data, $log_name);

            echo "invalid|" . $validationResult[1];
            exit;
        }
       
     
    }

    /*
      Function Name : historyDownload
      Desc : For getting download count on My History
     */

    function historyDownload() {
        $this->layout = false;

        if ( $this->RequestHandler->isPost() ) {
        	$index = 'form';
        } else if ( $this->RequestHandler->isGet() ) {
        	$index = 'url';
        }

        $id = $this->params[$index]['id'];
        $libId = $this->params[$index]['libid'];
        $patId = $this->params[$index]['patronid'];

        $CdnPath = $this->params[$index]['CdnPath'];
        $SaveAsName = $this->params[$index]['SaveAsName'];
        
        $songUrl  = $this->Token->regularToken($CdnPath . "/" . $SaveAsName);                
        $finalSongUrl = Configure::read('App.Music_Path') . $songUrl;
        $finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl) / 3));
        $finalURL = urlencode($finalSongUrlArr[0]) . urlencode($finalSongUrlArr[1]) . urlencode($finalSongUrlArr[2]);

        $this->Download->recursive = -1;
        $downloadsUsed = $this->Download->find('all', array('conditions' => array('ProdID' => $id, 'library_id' => $libId, 'patron_id' => $patId, 'created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'))), 'order' => 'created DESC', 'limit' => '1'));
        $downloadCount = $downloadsUsed[0]['Download']['history'];
        //check for download availability
        if ($downloadCount < 2) {
            $this->Download->setDataSource('master');
            $sql = "UPDATE `downloads` SET history=history+1 Where ProdID='" . $id . "' AND library_id = '" . $libId . "' AND patron_id = '" . $patId . "' AND history < 2 AND created BETWEEN '" . Configure::read('App.twoWeekStartDate') . "' AND '" . Configure::read('App.twoWeekEndDate') . "' ORDER BY created DESC";
            $this->Download->query($sql);
            $this->Download->setDataSource('default');
            $downloadsUsed = $this->Download->find('all', array('conditions' => array('ProdID' => $id, 'library_id' => $libId, 'patron_id' => $patId, 'created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'))), 'order' => 'created DESC', 'limit' => '1'));
            $downloadCount = $downloadsUsed[0]['Download']['history'];
            echo "suces|" . $downloadCount . "|" . $finalURL;
        } else {
            echo "error";
        }
        exit;
    }

    /*
      Function Name : historyDownload
      Desc : For getting download count on My History
     */

    function historyDownloadVideo() {
        $this->layout = false;

        if ( $this->RequestHandler->isPost() ) {
        	$index = 'form';
        } else if ( $this->RequestHandler->isGet() ) {
        	$index = 'url';
        }

        $id = $this->params[$index]['id'];
        $libId = $this->params[$index]['libid'];
        $patId = $this->params[$index]['patronid'];
        $this->Videodownload->recursive = -1;
        $downloadsUsed = $this->Videodownload->find('all', array('conditions' => array('ProdID' => $id, 'library_id' => $libId, 'patron_id' => $patId, 'created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'))), 'order' => 'created DESC', 'limit' => '1'));
        $downloadCount = $downloadsUsed[0]['Videodownload']['history'];
        //check for download availability
        if ($downloadCount < 2) {
            $this->Videodownload->setDataSource('master');
            $sql = "UPDATE `videodownloads` SET history=history+1 Where ProdID='" . $id . "' AND library_id = '" . $libId . "' AND patron_id = '" . $patId . "' AND history < 2 AND created BETWEEN '" . Configure::read('App.twoWeekStartDate') . "' AND '" . Configure::read('App.twoWeekEndDate') . "' ORDER BY created DESC";
            $this->Videodownload->query($sql);
            $this->Videodownload->setDataSource('default');
            $downloadsUsed = $this->Videodownload->find('all', array('conditions' => array('ProdID' => $id, 'library_id' => $libId, 'patron_id' => $patId, 'created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'))), 'order' => 'created DESC', 'limit' => '1'));
            $downloadCount = $downloadsUsed[0]['Videodownload']['history'];
            echo "suces|" . $downloadCount;
        } else {
            echo "error";
        }
        exit;
    }

    /*
      Function Name : admin_historyform
      Desc : actions used for admin history form
     */

    function admin_historyform() {

        // allwoes only admin
        if ((!$this->Session->read('Auth.User.type_id')) && ($this->Session->read('Auth.User.type_id') != 1)) {
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }

        if (isset($this->data) && ($this->data['Home']['language_change']) == 1) {
            $language = $this->data['Home']['language'];
            $this->set('formAction', 'admin_historyform');
            $this->set('formHeader', 'Manage History Page Text');
            $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'history', 'language' => $this->data['Home']['language'])));
            if (count($getPageData) != 0) {
                $getData['Home']['id'] = $getPageData[0]['Page']['id'];
                $getData['Home']['page_name'] = $getPageData[0]['Page']['page_name'];
                $getData['Home']['page_content'] = $getPageData[0]['Page']['page_content'];
                $getData['Home']['language'] = $language;
                $this->set('getData', $getData);
            } else {
                $getData['Home']['language'] = $language;
                $getData['Home']['id'] = null;
                $getData['Home']['page_name'] = null;
                $getData['Home']['page_content'] = null;
                $this->set('getData', $getData);
            }
        } else {
            if (isset($this->data)) {
                $findData = $this->Page->find('all', array('conditions' => array('page_name' => 'history', 'language' => $this->data['Home']['language'])));
                if (count($findData) == 0) {
                    $pageData['Page']['page_name'] = $this->data['Home']['page_name'];
                    $pageData['Page']['page_content'] = $this->data['Home']['page_content'];
                    $pageData['Page']['language'] = $this->data['Home']['language'];
                    $this->Page->set($pageData['Page']);
                    $this->Page->setDataSource('master');
                    if ($this->Page->save()){
                        $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                    }
                } elseif (count($findData) > 0) {
                    $this->Page->id = $this->data['Home']['id'];
                    $pageData['Page']['page_name'] = $this->data['Home']['page_name'];
                    $pageData['Page']['page_content'] = $this->data['Home']['page_content'];
                    $pageData['Page']['language'] = $this->data['Home']['language'];
                    $this->Page->set($pageData['Page']);
                    if ($this->Page->save())
                    {
                        $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                    } else {
                        $this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
                    }
                }
                $this->Page->setDataSource('default');
            }
            $this->set('formAction', 'admin_historyform');
            $this->set('formHeader', 'Manage History Page Text');
            $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'history', 'language' => 'en')));
            if (count($getPageData) != 0) {
                $getData['Home']['id'] = $getPageData[0]['Page']['id'];
                $getData['Home']['page_name'] = $getPageData[0]['Page']['page_name'];
                $getData['Home']['page_content'] = $getPageData[0]['Page']['page_content'];
                $getData['Home']['language'] = $getPageData[0]['Page']['language'];
                $this->set('getData', $getData);
            } else {
                $arr = array();
                $this->set('getData', $arr);
            }
        }
        $this->set('languages', $this->Language->find('list', array('fields' => array('short_name', 'full_name'))));
        $this->layout = 'admin';
    }

    /*
      Function Name : music_box
      Desc : For getting Top Downloads and FreegalMusic records for home page
     */

    function music_box() {
        $this->layout = false;
        $callType = $this->params['form']['type'];
        if ($callType == 'top') {
            // Top Downloads functionality
            $libId = $this->Session->read('library');
            $this->Download->recursive = -1;
            $wk = date('W') - 10;
            $startDate = date('Y-m-d', mktime(1, 0, 0, date('m'), (date('d') - date('w')) - 70, date('Y'))) . ' 00:00:00';
            $endDate = date('Y-m-d', mktime(1, 0, 0, date('m'), (date('d') - date('w')) + 7, date('Y'))) . ' 23:59:59';
            $topDownloaded = $this->Download->find('all', array('conditions' => array('library_id' => $libId, 'created BETWEEN ? AND ?' => array($startDate, $endDate)), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct'), 'order' => 'countProduct DESC', 'limit' => '8'));
            $prodIds = '';
            foreach ($topDownloaded as $k => $v) {
                $prodIds .= $v['Download']['ProdID'] . "','";
            }
        } else {
            // FreegalMusic Downloads functionality
            $this->Download->recursive = -1;
            $wk = date('W') - 10;
            $startDate = date('Y-m-d', mktime(1, 0, 0, date('m'), (date('d') - date('w')) - 70, date('Y'))) . ' 00:00:00';
            $endDate = date('Y-m-d', mktime(1, 0, 0, date('m'), (date('d') - date('w')) + 7, date('Y'))) . ' 23:59:59';
            $topDownloaded = $this->Download->find('all', array('conditions' => array('created BETWEEN ? AND ?' => array($startDate, $endDate)), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct'), 'order' => 'countProduct DESC', 'limit' => '8'));
            $prodIds = '';
            foreach ($topDownloaded as $k => $v) {
                $prodIds .= $v['Download']['ProdID'] . "','";
            }
        }

        if ($prodIds != '') {
            $this->Song->recursive = 2;
            $topDownload = $this->Song->find('all', array('conditions' =>
                array('and' =>
                    array(
                        array("Song.ProdID IN ('" . rtrim($prodIds, ",'") . "')"),
                    ), "1 = 1 GROUP BY Song.ProdID"
                ),
                'fields' => array(
                    'Song.ProdID',
                    'Song.ReferenceID',
                    'Song.Title',
                    'Song.ArtistText',
                    'Song.DownloadStatus',
                    'Song.SongTitle',
                    'Song.Artist',
                    'Song.Advisory',
                    'Song.Sample_Duration',
                    'Song.FullLength_Duration',
                ),
                'contain' => array(
                    'Genre' => array(
                        'fields' => array(
                            'Genre.Genre'
                        )
                    ),
                    'Country' => array(
                        'fields' => array(
                            'Country.Territory',
                            'Country.SalesDate'
                        )
                    ),
                    'Sample_Files' => array(
                        'fields' => array(
                            'Sample_Files.CdnPath',
                            'Sample_Files.SaveAsName'
                        )
                    ),
                ), 'order' => array('Country.SalesDate' => 'desc')
                    )
            );
        } else {
            $topDownload = array();
        }
        $this->set('songs', $topDownload);
    }

    /*
      Function Name : language
      Desc : actions that is invoked when a particular language is selected
     */

    function language() {

        if(!empty($this->params['url']['langType']) && $this->params['url']['langType']>=1 && $this->params['url']['langType']<=4)
        {           
            $language = $this->params['url']['langType'];
            $langDetail = $this->Language->find('first', array('conditions' => array('id' => $language)));
            $this->Session->write('Config.language', $langDetail['Language']['short_name']);
            $page = $this->Session->read('Config.language');           
        }
        else 
        {
            $this->layout = false;
            $language = $this->params['form']['lang'];
            $langDetail = $this->Language->find('first', array('conditions' => array('id' => $language)));
            $this->Session->write('Config.language', $langDetail['Language']['short_name']);
            $page = $this->Session->read('Config.language');
            $pageDetails = $this->Page->find('all', array('conditions' => array('page_name' => 'login', 'language' => $page)));
            if (count($pageDetails) != 0) {
                print $pageDetails[0]['Page']['page_content'];
            } else {
                print "Coming Soon....";
            }
            exit;
        }
    }

    /*
      Function Name : admin_language
      Desc : Adding languages at admin end
     */

    function admin_language() {

        // allwoes only admin
        if ((!$this->Session->read('Auth.User.type_id')) && ($this->Session->read('Auth.User.type_id') != 1)) {
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }

        if (!empty($this->data)) {
            $data['Language']['id'] = '';
            $data['Language']['short_name'] = $this->data['Homes']['short_name'];
            $data['Language']['full_name'] = $this->data['Homes']['full_name'];
            $this->Language->setDataSource('master');
            if ($this->Language->save($data['Language'])) {
                $this->Session->setFlash('Your Language has been saved.', 'modal', array('class' => 'modal success'));
                $this->redirect('/admin/homes/language');
            } else {
                $this->Session->setFlash('Your Language has not been saved.', 'modal', array('class' => 'modal failure'));
                $this->redirect('/admin/homes/language');
            }
            $this->Language->setDataSource('default');
            $this->set('languages', $this->Language->find('all'));
        } else {
            $this->set('languages', $this->Language->find('all'));
        }
        $this->set('formAction', 'admin_language');
        $this->set('formHeader', 'Add Languages');
        $this->layout = 'admin';
    }

    /*
      Function Name : admin_language_activate
      Desc : For activating a Language
     */

    function admin_language_activate() {
        $languageID = $this->params['named']['id'];
        if (trim($languageID) != "" && is_numeric($languageID)) {
            $this->Language->id = $languageID;
            $this->Language->set(array('status' => 'active'));
            $this->Language->setDataSource('master');
            if ($this->Language->save()) {
                $this->Session->setFlash('Language activated successfully!', 'modal', array('class' => 'modal success'));
            }
            $this->Language->setDataSource('default');
            $this->autoRender = false;
            $this->redirect('/admin/homes/language');
        } else {
            $this->Session->setFlash('Error occured while activating the Langauge', 'modal', array('class' => 'modal problem'));
            $this->autoRender = false;
            $this->redirect('/admin/homes/language');
        }
    }

    /*
      Function Name : admin_language_deactivate
      Desc : For deactivating a Language
     */

    function admin_language_deactivate() {
        $languageID = $this->params['named']['id'];
        if (trim($languageID) != "" && is_numeric($languageID)) {
            $this->Language->id = $languageID;
            $this->Language->set(array('status' => 'inactive'));
            $this->Language->setDataSource('master');
            if ($this->Language->save()) {
                $this->Session->setFlash('Language deactivated successfully!', 'modal', array('class' => 'modal success'));
            }
            $this->Language->setDataSource('default');
            $this->autoRender = false;
            $this->redirect('/admin/homes/language');
        } else {
            $this->Session->setFlash('Error occured while deactivating the Language', 'modal', array('class' => 'modal problem'));
            $this->autoRender = false;
            $this->redirect('/admin/homes/language');
        }
    }

    /*
      Function Name : auto_check
      Desc : For checking if user session is Active ro Not
     */

    function auto_check() {
        $this->layout = false;
        if (!$this->Session->read('library') || !$this->Session->read('patron')) {
            print "error";
            exit;
        } else {
            echo "success";
            exit;
        }
    }

    function convertString() {
        $this->layout = false;
        $str = $this->params['form']['str'];
        echo sha1($str);
        exit;
    }

    //Used to get Sample Song url
    function userSample() {
        $this->layout = false;
        $prodId = $this->params['form']['prodId'];
        $pt = base64_decode($this->params['form']['pt']);
        $this->Song->recursive = 2;
        $data = $this->Song->find('first', array('conditions' => array('Song.ProdID' => $prodId, 'Song.provider_type' => $pt),
            'contain' => array(
                'Sample_Files' => array(
                    'fields' => array(
                        'Sample_Files.CdnPath',
                        'Sample_Files.SaveAsName'
                    )
                )
            )
                )
        );

        
        $songUrl  = $this->Token->regularToken($data['Sample_Files']['CdnPath'] . "/" . $data['Sample_Files']['SaveAsName']);                
        $finalSongUrl = Configure::read('App.Music_Path') . $songUrl;
        echo $finalSongUrl;
        exit;
    }

    /*
     * Function Name : chooser
     * Desc : action for thelibrary login page
     * 
     * Function added by Mangesh
     */

    function chooser() {
        $this->layout = 'home';
        $territories = $this->Library->find('all', array('fields' => array('DISTINCT Library.library_territory', 'Library.library_country'), 'conditions' => array("Library.library_territory <> 'US' AND Library.library_territory <> 'UNITE'")));
        $territorylist[''] = '';
        foreach ($territories as $territory) {
            $territorylist["{$territory['Library']['library_territory']}"] = $territory['Library']['library_country'];
        }
        $this->set('territorylist', $territorylist);

        if ($this->data) {

            if (isset($this->data['Library_details1']['zipcode'])) {
                $zip = mysql_real_escape_string($this->data['Library_details1']['zipcode']);
                $city = mysql_real_escape_string($this->data['Library_details1']['city']);
                $state = mysql_real_escape_string($this->data['Library_details1']['state']);
                $library_name = mysql_real_escape_string($this->data['Library_details1']['library_name']);
                $country = mysql_real_escape_string($this->data['Library_details1']['country']);
                if ($zip != '' || $city != '' || $state != '' || $library_name != '' || $country != '') {
                    //Check for Library name should not start with Free, Public or Library
                    $pos1 = stripos('Free Library', $library_name);
                    $pos2 = stripos('Public Library', $library_name);
                    if ((is_numeric($pos1)) || (is_numeric($pos2))) {
                        $this->Session->setFlash('Please Enter a valid Library name');
                    } else {

                        //Added code for City
                        $other_condition = '';
                        if (!empty($city)) {
                            if ($other_condition != '') {
                                $other_condition = 'OR library_city like "%' . $city . '%" ';
                            } else {
                                $other_condition .= ' library_city like "%' . $city . '%" ';
                            }
                        }
                        //Added code for state
                        if (!empty($state)) {
                            if ($other_condition != '') {
                                $other_condition .= ' OR library_state like "%' . $state . '%" ';
                            } else {
                                $other_condition .= 'library_state like "%' . $state . '%" ';
                            }
                        }
                        //Added code for library name
                        if (!empty($library_name)) {
                            if ($other_condition != '') {
                                $other_condition .= ' OR library_name like "%' . $library_name . '%" ';
                            } else {
                                $other_condition .= 'library_name like "%' . $library_name . '%" ';
                            }
                        }
                        if (!empty($country)) {
                            if ($other_condition != '') {
                                $other_condition .= ' OR library_territory = "' . $country . '" ';
                            } else {
                                $other_condition .= 'library_territory = "' . $country . '" ';
                            }
                        }

                        if ($zip == '') {
                            $result = $this->Library->find('all', array('conditions' => array('library_status' => 'active', 'OR' => array($other_condition)), 'order' => 'library_name ASC'));


                            if (!empty($result)) {
                                $this->set('libraries', $result);
                            } else {
                                $this->set('msg', 'Sorry, currently there are no libraries in your area that subscribe to Freading.');
                            }
                        } else {
                            $zipRows = $this->Zipcode->find('first', array('fields' => 'DISTINCT(ZipCode)', 'conditions' => array('ZipCode' => $zip)));

                            if (!empty($zipRows)) {
                                App::import('vendor', 'zipcode_class', array('file' => 'zipcode.php'));
                                $zipcode = new zipcode_class();

                                $result = $zipcode->get_zips_in_range($zipRows['Zipcode']['ZipCode'], 60, _ZIPS_SORT_BY_DISTANCE_ASC, true);

                                $this->Library->recursive = -1;
                                $condition = implode("',library_zipcode) OR find_in_set('", explode(',', $result));


                                $result = $this->Library->find('all', array(
                                    'conditions' => array(
                                        'library_status' => 'active',
                                        'OR' => array(
                                            "substring(library_zipcode,1,5) in ($result)", "find_in_set('" . $condition . "',library_zipcode)", $other_condition)), 'order' => 'library_name ASC'));

                                if (!empty($result)) {
                                    $this->set('libraries', $result);
                                } else {
                                    $this->set('msg', 'Sorry, currently there are no libraries in your area that subscribe to 	Freading.');
                                }
                            } else {
                                $this->Session->setFlash('Please Enter a valid zip code');
                            }
                        }
                    }
                } else {
                    $this->Session->setFlash('Please enter either your Library Name, Zip Code, City, State or Country.');
                }
            } else if (isset($this->data['Library_details1']['country'])) {
                if ($this->data['Library_details1']['country'] != '') {
                    $territory = $this->data['Library_details1']['country'];
                    $this->Library->recursive = -1;
                    $result = $this->Library->find('all', array('conditions' => "library_territory = '$territory' and library_status = 'active'", 'order' => 'library_name ASC'));
                    if (!empty($result))
                        $this->set('libraries', $result);
                    else
                        $this->Session->setFlash('Sorry no libraries found in the country');
                } else {
                    $this->Session->setFlash('Please select a country');
                }
            } else {
                $this->Session->setFlash('Please enter something valid');
            }
        } else {
            if ($this->Cookie->read('UrlReferer') == '') {
                $this->Cookie->write('UrlReferer', $this->referer(), false);
            } else if (strpos($this->Cookie->read('UrlReferer'), '?fb_xd_fragment')) {
                $this->Cookie->write('UrlReferer', $this->referer(), false);
            } else if (strpos($this->Cookie->read('UrlReferer'), 'internet_explorer')) {
                $this->Cookie->write('UrlReferer', $this->referer(), false);
            }
        }
    }

    function new_releases() {
        $this->layout = 'home';

        //fetch the session variables
        $libraryId = $this->Session->read('library');
        $patronId = $this->Session->read('patron');

        if (!empty($libraryId) && !empty($patronId)) {
            $libraryDownload = $this->Downloads->checkLibraryDownload($libraryId);
            $patronDownload = $this->Downloads->checkPatronDownload($patronId, $libraryId);
            $this->set('libraryDownload', $libraryDownload);
            $this->set('patronDownload', $patronDownload);
        }

        $territory = $this->Session->read('territory');
        //get Advisory condition
        //////////////////////////////////Videos/////////////////////////////////////////////////////////            
        $coming_soon_videos = Cache::read("new_releases_videos" . $territory);
        if ($coming_soon_videos === false) {           
            $coming_soon_videos = $this->CacheHandler->checkMemData("new_releases_videos" . $territory);
            //if not found then run query in the table
            if( $coming_soon_videos === false ){
                $coming_soon_videos = $this->Common->getNewReleaseVideos($territory);
            } 
        }

        $this->set('new_releases_videos', $coming_soon_videos);
        
        

        //////////////////////////////////Albums/////////////////////////////////////////////////////////
        
        if( $this->Session->read('block') == 'yes' ) {
        	$new_releases_albums_rs = Cache::read("new_releases_albums_none_explicit" . $territory);                        
        	if ($new_releases_albums_rs === false) {
                    //check variable data in to mem_datas table
                    $new_releases_albums_rs = $this->CacheHandler->checkMemData("new_releases_albums_none_explicit" . $territory);
                    //if not found then run query in the table
                    if( $new_releases_albums_rs === false ){
                        $new_releases_albums_rs = $this->Common->getNewReleaseAlbums($territory, true);
                    }                   
        	}
                
        } else {
        
                //fetch record in cache
        	$new_releases_albums_rs = Cache::read("new_releases_albums" . $territory);                
                //if cache not set
        	if ( $new_releases_albums_rs === false ) {                 
                        //check variable data in to mem_datas table
                        $new_releases_albums_rs = $this->CacheHandler->checkMemData("new_releases_albums" . $territory);
                        //if not found then run query in the table
                        if( $new_releases_albums_rs === false ){
                            $new_releases_albums_rs = $this->Common->getNewReleaseAlbums($territory);
                        }      		
        	}
        }

        $this->set('new_releases_albums', $new_releases_albums_rs);
    }

    /*
     * Function Name : wishlistDownloadHome
     * Desc : This function is responsible for download functionality for songs   
     * 
     * @param   prodId  int 'ProdID'
     * @param   CdnPath varchar 'CdnPath'
     * @param   SaveAsName  varchar 'SaveAsName'
     * @param   provider    varchar 'provider'
     * @param   id    int   'id'
     * 
     * 
     * @return string 
     */

    function wishlistDownloadHome() {
        //set the layout fales because this is ajax call
        $this->layout = false;

        if ( $this->RequestHandler->isPost() ) {
        	$index = 'form';
        } else if ( $this->RequestHandler->isGet() ) {
        	$index = 'url';
        }

        //get all required variables
        $libId = $this->Session->read('library');
        $patId = $this->Session->read('patron');
        $prodId = $this->params[$index]['prodId'];
        $CdnPath = $this->params[$index]['CdnPath'];
        $SaveAsName = $this->params[$index]['SaveAsName'];
        $id = $this->params[$index]['id'];
        $provider = $this->params[$index]['provider'];

        //start the logs        
        $log_name = 'stored_procedure_web_wishlist_log_' . date('Y_m_d');
        $log_id = md5(time());
        $log_data = PHP_EOL . "----------Request (" . $log_id . ") Start----------------" . PHP_EOL;

        /*
         * Check if any of the required parameter is null or not set then log details and return and error message to client         *
         */

        if (empty($prodId) || empty($CdnPath) || empty($SaveAsName) || empty($provider) || empty($libId) || empty($patId)) {
            $log_data .= "DownloadComponentParameters-ProdId= '" . $prodId . "':DownloadComponentParameters-Provider_type= '" . $provider
                    . "':DownloadComponentParameters-CDNPath= '" . $CdnPath . "':DownloadComponentParameters-SaveAsName= '" . $SaveAsName
                    . "':DownloadComponentParameters-id= '" . $id . "':DownloadComponentParameters-Library= '" . $libId
                    . "':DownloadComponentParameters-PatronId= '" . $patId . "':DownloadCompleteStatus=Fail";
            $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------";
            $this->log($log_data, $log_name);

            echo "empty|Something went wrong during download.Please try again later.";
            exit;
        }
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
        //check for download availability
        if (empty($libraryDownload)) {
            echo "error";
            exit;
        }
        
        $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);
        if (empty($patronDownload)) {
            echo "error";
            exit;
        }        
        /*
         * if all required field are not null then we continue 
         * for download and  insert record in download table
         */
        
        $songUrl  = $this->Token->regularToken($CdnPath . "/" . $SaveAsName);   
        $finalSongUrl = Configure::read('App.Music_Path') . $songUrl;
        $finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl) / 3));
        $finalURL = urlencode($finalSongUrlArr[0]) . urlencode($finalSongUrlArr[1]) . urlencode($finalSongUrlArr[2]);

        $downloadsDetail = array();

        //get details for this song
        $trackDetails = $this->Song->getdownloaddata($prodId, $provider);
        $insertArr = Array();
        $insertArr['library_id'] = $libId;
        $insertArr['patron_id'] = $patId;
        $insertArr['ProdID'] = $prodId;
        $insertArr['artist'] = $trackDetails['0']['Song']['Artist'];
        $insertArr['track_title'] = $trackDetails['0']['Song']['SongTitle'];
        $insertArr['ProductID'] = $trackDetails['0']['Song']['ProductID'];
        $insertArr['ISRC'] = $trackDetails['0']['Song']['ISRC'];
        $insertArr['provider_type'] = $provider;

        //check the validation
        $Setting = $this->Siteconfig->find('first', array('conditions' => array('soption' => 'single_channel')));
        $checkValidation = $Setting['Siteconfig']['svalue'];
        if ($checkValidation == 1) {

            $validationResult = $this->Downloads->validateDownload($prodId, $provider);

            /*
              records download component request & response
             */
            $log_data .= "DownloadComponentParameters-ProdId= '" . $prodId . "':DownloadComponentParameters-Provider_type= '" . $provider . "':DownloadComponentResponse-Status='" . $validationResult[0] . "':DownloadComponentResponse-Msg='" . $validationResult[1] . "':DownloadComponentResponse-ErrorTYpe='" . $validationResult[2] . "'";

            $checked = "true";
            $validationPassed = $validationResult[0];
            $validationPassedMessage = (($validationResult[0] == 0) ? 'false' : 'true');
            $validationMessage = $validationResult[1];
        } else {
            $checked = "false";
            $validationPassed = true;
            $validationPassedMessage = "Not Checked";
            $validationMessage = '';
        }

        $user = $this->Session->read('Auth.User.id');
        if (empty($user)) {
            $user = $this->Session->read('patron');
        }

        //if validation pass than
        if ($validationPassed == true) {
            $this->log("Validation Checked : " . $checked . " Valdition Passed : " . $validationPassedMessage . " Validation Message : " . $validationMessage . " for ProdID :" . $prodId . " and Provider : " . $provider . " for library id : " . $this->Session->read('library') . " and user id : " . $user, 'download');

            if ($this->Session->read('referral_url') && ($this->Session->read('referral_url') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'referral_url';
            } elseif ($this->Session->read('innovative') && ($this->Session->read('innovative') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative';
            } elseif ($this->Session->read('mdlogin_reference') && ($this->Session->read('mdlogin_reference') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'mdlogin_reference';
            } elseif ($this->Session->read('mndlogin_reference') && ($this->Session->read('mndlogin_reference') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'mndlogin_reference';
            } elseif ($this->Session->read('innovative_var') && ($this->Session->read('innovative_var') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_var';
            } elseif ($this->Session->read('innovative_var_name') && ($this->Session->read('innovative_var_name') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_var_name';
            } elseif ($this->Session->read('innovative_var_https_name') && ($this->Session->read('innovative_var_https_name') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_var_https_name';
            } elseif ($this->Session->read('innovative_var_https') && ($this->Session->read('innovative_var_https') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_var_https';
            } elseif ($this->Session->read('innovative_var_https_wo_pin') && ($this->Session->read('innovative_var_https_wo_pin') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_var_https_wo_pin';
            } elseif ($this->Session->read('innovative_https') && ($this->Session->read('innovative_https') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_https';
            } elseif ($this->Session->read('innovative_wo_pin') && ($this->Session->read('innovative_wo_pin') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_wo_pin';
            } elseif ($this->Session->read('sip2') && ($this->Session->read('sip2') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'sip2';
            } elseif ($this->Session->read('sip') && ($this->Session->read('sip') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'sip';
            } elseif ($this->Session->read('innovative_var_wo_pin') && ($this->Session->read('innovative_var_wo_pin') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'innovative_var_wo_pin';
            } elseif ($this->Session->read('sip2_var') && ($this->Session->read('sip2_var') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'sip2_var';
            } elseif ($this->Session->read('sip2_var') && ($this->Session->read('sip2_var') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'sip2_var_wo_pin';
            } elseif ($this->Session->read('sip2_var_wo_pin') && ($this->Session->read('sip2_var_wo_pin') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'sip2_var_wo_pin';
            } elseif ($this->Session->read('ezproxy') && ($this->Session->read('ezproxy') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'ezproxy';
            } elseif ($this->Session->read('soap') && ($this->Session->read('soap') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'soap';
            } elseif ($this->Session->read('curl_method') && ($this->Session->read('curl_method') != '')) {
                $insertArr['email'] = '';
                $insertArr['user_login_type'] = 'curl_method';
            } else {
                $insertArr['email'] = $this->Session->read('patronEmail');
                $insertArr['user_login_type'] = 'user_account';
            }

            $insertArr['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $insertArr['ip'] = $_SERVER['REMOTE_ADDR'];


            $siteConfigSQL = "SELECT * from siteconfigs WHERE soption = 'maintain_ldt'";
            $siteConfigData = $this->Album->query($siteConfigSQL);
            $maintainLatestDownload = (($siteConfigData[0]['siteconfigs']['svalue'] == 1) ? true : false);

            if ($maintainLatestDownload) {
                $this->log("downloadsong called", 'download');
                $procedure = 'downloadsong';
                $sql = "CALL downloadsong('" . $libId . "','" . $patId . "', '" . $prodId . "', '" . $trackDetails['0']['Song']['ProductID'] . "', '" . $trackDetails['0']['Song']['ISRC'] . "', '" . addslashes($trackDetails['0']['Song']['Artist']) . "', '" . addslashes($trackDetails['0']['Song']['SongTitle']) . "', '" . $insertArr['user_login_type'] . "', '" . $insertArr['provider_type'] . "', '" . $insertArr['email'] . "', '" . addslashes($insertArr['user_agent']) . "', '" . $insertArr['ip'] . "', '" . Configure::read('App.curWeekStartDate') . "', '" . Configure::read('App.curWeekEndDate') . "',@ret)";
            } else {
                $this->log("sonyproc_ioda called", 'download');
                $procedure = 'sonyproc_ioda';
                $sql = "CALL sonyproc_ioda('" . $libId . "','" . $patId . "', '" . $prodId . "', '" . $trackDetails['0']['Song']['ProductID'] . "', '" . $trackDetails['0']['Song']['ISRC'] . "', '" . addslashes($trackDetails['0']['Song']['Artist']) . "', '" . addslashes($trackDetails['0']['Song']['SongTitle']) . "', '" . $insertArr['user_login_type'] . "', '" . $insertArr['provider_type'] . "', '" . $insertArr['email'] . "', '" . addslashes($insertArr['user_agent']) . "', '" . $insertArr['ip'] . "', '" . Configure::read('App.curWeekStartDate') . "', '" . Configure::read('App.curWeekEndDate') . "',@ret)";
            }
            
            $this->Library->setDataSource('master');

            $this->Library->query($sql);
            $sql = "SELECT @ret";
            $data = $this->Library->query($sql);
            $return = $data[0][0]['@ret'];
            $this->LatestDownload->setDataSource('default');
            $log_data .= ":StoredProcedureParameters-LibID='" . $libId . "':StoredProcedureParameters-Patron='" . $patId . "':StoredProcedureParameters-ProdID='" . $prodId . "':StoredProcedureParameters-ProductID='" . $trackDetails['0']['Song']['ProductID'] . "':StoredProcedureParameters-ISRC='" . $trackDetails['0']['Song']['ISRC'] . "':StoredProcedureParameters-Artist='" . addslashes($trackDetails['0']['Song']['Artist']) . "':StoredProcedureParameters-SongTitle='" . addslashes($trackDetails['0']['Song']['SongTitle']) . "':StoredProcedureParameters-UserLoginType='" . $insertArr['user_login_type'] . "':StoredProcedureParameters-ProviderType='" . $insertArr['provider_type'] . "':StoredProcedureParameters-Email='" . $insertArr['email'] . "':StoredProcedureParameters-UserAgent='" . addslashes($insertArr['user_agent']) . "':StoredProcedureParameters-IP='" . $insertArr['ip'] . "':StoredProcedureParameters-CurWeekStartDate='" . Configure::read('App.curWeekStartDate') . "':StoredProcedureParameters-CurWeekEndDate='" . Configure::read('App.curWeekEndDate') . "':StoredProcedureParameters-Name='" . $procedure . "':StoredProcedureParameters-@ret='" . $return . "'";
            $log_data .= ":StoredProcedureParameters-HTTP_REFERER='" . $_SERVER['HTTP_REFERER'];

            //check the new entry is available in the latest download table or not
            if (is_numeric($return)) {
                $this->LatestDownload->setDataSource('master');
                $data = $this->LatestDownload->find('count', array(
                    'conditions' => array(
                        "LatestDownload.library_id " => $libId,
                        "LatestDownload.patron_id " => $patId,
                        "LatestDownload.ProdID " => $prodId,
                        "LatestDownload.provider_type " => $insertArr['provider_type'],
                        "DATE(LatestDownload.created) " => date('Y-m-d'),
                    ),
                    'recursive' => -1,
                ));

                if (0 === $data) {
                    $log_data .= ":NotInLD";
                }

                if (false === $data) {
                    $log_data .= ":SelectLDFail";
                }

                $this->LatestDownload->setDataSource('default');
            }

            $this->Library->setDataSource('default');

            //if stored procedure run correctly and retrun numric value
            if (is_numeric($return)) {

                //add the download songs in the session array
                if ($this->Session->read('downloadVariArray')) {
                    $downloadVariArray = $this->Session->read('downloadVariArray');
                    $downloadVariArray[] = $prodId . '~' . $provider;
                    $this->Session->write('downloadVariArray', $downloadVariArray);
                }

                if ($id > 0) {
                    //delete from wishlist table
                    $deleteSongId = $id;
                    $this->Wishlist->delete($deleteSongId);
                    //get no of downloads for this week
                }

                $this->LatestVideodownload->recursive = -1;
                $videodownloadsUsed = $this->LatestVideodownload->find('count', array('conditions' => array('library_id' => $libId, 'patron_id' => $patId, 'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
                $this->LatestDownload->recursive = -1;
                $downloadscount = $this->LatestDownload->find('count', array('conditions' => array('library_id' => $libId, 'patron_id' => $patId, 'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
                $downloadsUsed = ($videodownloadsUsed * 2) + $downloadscount;
                $this->Session->write('downloadCount', $downloadsUsed);

                $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------";
                $this->log($log_data, $log_name);

                echo "suces|" . $downloadsUsed . "|" . $finalURL;
                exit;
            } else {

                //check if this songs is already downloaded
                if ($return == 'incld') {
                    $log_data .= PHP_EOL . "empty|Something went wrong during download. Please try again later." . $return;
                    $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------";
                    $this->log($log_data, $log_name);
                    echo "empty|You have already downloaded this song. Get it from your recent downloads.";
                    exit;
                }

                //if store procedure return something else                 
                $log_data .= PHP_EOL . "empty|Something went wrong during download. Please try again later." . $return;
                $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------";
                $this->log($log_data, $log_name);
                
                echo "empty|Something went wrong during download. Please try again later.";
                exit;
            }
        } else {
            //if validation fail than
            $log_data .= PHP_EOL . "invalid|" . $validationResult[1];
            $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------" . PHP_EOL;
            $this->log($log_data, $log_name);

            echo "invalid|" . $validationResult[1];
            exit;
        }
    }

    /**
     * This method is called by Ajax call for storing the 
     * National Top Albums and Song(s) to wishlist from Home page
     * 
     * @return null
     */
    function addToWishlistNewHome() {
        $this->layout = 'ajax';

        //check if its called for adding Album  / Song(s) to Wishlist        
        $type = $this->params["form"]["type"];
        $selectedSongs = array();
        //Check is patron is logged in or not
        if ($type == 'album') {
            $prodID = $this->params["form"]["prodID"];
            $provider = $this->params["form"]["provider_type"];
            
            if(empty($prodID) && empty($provider)){
                $selectedSongs = $this->params["form"]["songs"];
                foreach ($selectedSongs as $song) {
                    $songInfo = explode('&', $song);
                    $prodID = $songInfo[0];
                    $provider = $songInfo[1]; 
                }   
            }
            if ($provider != 'sony') {
                $provider = 'ioda';
            }

            if ($this->Session->read('library') && $this->Session->read('patron') && isset($prodID) && isset($provider)) {
                $log_name = 'stored_procedure_web_album_wishlist_log_' . date('Y_m_d');
                $log_id = md5(time());
                $log_data = PHP_EOL . "----------Request (" . $log_id . ") Start----------------" . PHP_EOL;
                $log_data .= "Library ID:" . $this->Session->read('library') . " :PatronID:" . $this->Session->read('patron')
                        . " ProdID:$prodID  :ProviderType:$provider ";

                $albumSongs = $this->Common->getAlbumSongs($prodID, $provider);
                if (!empty($albumSongs)) {
                    $wishlistResult = $this->addsToWishlist($albumSongs);
                    if (!is_array($wishlistResult)) {
                        $log_data .= $wishlistResult;
                    } else {
                        $log_data .= $wishlistResult[1];
                    } 
                } else {
                    echo "error|There are no songs found in this Album.";
                    exit;
                }

                $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------";
                $this->log($log_data, $log_name);
                echo "success|Album successfully added to your wishlist.";
            } else {
                echo "error|You have been logged out. Please reload and login again.";
            }
        } elseif ($type == 'song') {
            if ($this->Session->read('library') && $this->Session->read('patron')) {
                $log_name = 'stored_procedure_web_album_wishlist_log_' . date('Y_m_d');
                $log_id = md5(time());
                $log_data = PHP_EOL . "----------Request (" . $log_id . ") Start----------------" . PHP_EOL;
                $log_data .= "Library ID:" . $this->Session->read('library') . " :PatronID:" . $this->Session->read('patron');

                $prodID     = $this->params["form"]["prodID"];
                $provider   = $this->params["form"]["provider_type"];
                $songsArray = array();

                $log_data .= " ProdID:$prodID  :ProviderType:$provider ";
                $songDetails = $this->Song->getdownloaddata( $prodID, $provider );

                if ( empty( $prodID ) && empty( $provider ) ) {

	                $selectedSongs = $this->params["form"]["songs"];
	                foreach ($selectedSongs as $song) {
	                    $songInfo = explode('&', $song);
	                    $log_data .= " ProdID:$songInfo[0]  :ProviderType:$songInfo[1] ";
	                    $songDetails = $this->Song->getdownloaddata($songInfo[0], $songInfo[1]);
	                }
                }

                array_push($songsArray, array_pop($songDetails));
                
                $wishlistResult = $this->addsToWishlist($songsArray);
                
                if (!is_array($wishlistResult)) {
                    $log_data .= $wishlistResult;
                    $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------";
                    $this->log($log_data, $log_name);
                    echo "success|" . ((count($selectedSongs) > 1) ? "Songs are " : "Song is ") . " added succesfully to wishlist.";
                } else {
                    $log_data .= $wishlistResult[1];
                    $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------";
                    $this->log($log_data, $log_name);
                    echo "error|" . ((count($selectedSongs) > 1) ? "Songs are " : "Song is ") . " already added to wishlist.";                    
                }
            } else {
                echo "error|You have been logged out. Please reload and login again..";
            }
        } else {
            echo "error|Something went wrong. Please try again.";
        }
        exit;
    }

    /**
     * This method is used for storing the songs into the 
     * wishlist for Patron's 
     * @param type $songsArray
     * @return string
     */
    function addsToWishlist($songsArray) {
        $log_data = "";
        //check if the album is already add  to wishlist
        $libraryId = $this->Session->read('library');
        $patronId = $this->Session->read('patron');
        foreach ($songsArray as $song) {
            $wishlistCount = $this->Wishlist->find(
                    'count', array(
                'conditions' =>
                array(
                    'library_id' => $libraryId,
                    'patron_id' => $patronId,
                    'ProdID' => $song['Song']['ProdID']
                )
            ));
            $allreadyAdded = 0;
            if (!$wishlistCount) {
                $insertArr = Array();
                $insertArr['library_id'] = $libraryId;
                $insertArr['patron_id'] = $patronId;
                $insertArr['ProdID'] = $song['Song']['ProdID'];
                $insertArr['artist'] = $song['Song']['Artist'];
                $insertArr['album'] = $song['Song']['Title'];
                $insertArr['track_title'] = $song['Song']['SongTitle'];
                $insertArr['ProductID'] = $song['Song']['ProductID'];
                $insertArr['provider_type'] = $song['Country']['provider_type'];
                $insertArr['ISRC'] = $song['Song']['ISRC'];
                $insertArr['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $insertArr['ip'] = $_SERVER['REMOTE_ADDR'];


                $this->Wishlist->setDataSource('master');
                //insert into wishlist table
                $this->Wishlist->create();      //Prepare model to save record
                
                if ($this->Wishlist->save($insertArr)) {
                    $log_data .= ' library_id:' . $libraryId . '  patron_id:' . $patronId . '  ProdID:' . $song['Song']['ProdID'] . " is added.";
                   
                    //add the wishlist songs in the session array
                    if ($this->Session->read('wishlistVariArray')) {
                        $wishlistVariArray = $this->Session->read('wishlistVariArray');
                        $wishlistVariArray[] = $song['Song']['ProdID'];
                        $this->Session->write('wishlistVariArray', $wishlistVariArray);
                    }
                } else {
                    $log_data .= ' library_id:' . $libraryId . '  patron_id:' . $patronId . '  ProdID:' . $song['Song']['ProdID'] . " is not added.";
                }
                
                 $this->Wishlist->setDataSource('default');
            } else {
                
                $allreadyAdded = 1;
                $log_data .= ' library_id:' . $libraryId . '  patron_id:' . $patronId . '  ProdID:' . $song['Song']['ProdID'] . " is already added to wishlist";
            }
        }
        
        if (!empty($allreadyAdded)) {
            $wishlistalreadyadded = array();
            $wishlistalreadyadded[] = 'You have already added this song to your wishlist';
            $wishlistalreadyadded[] = $log_data;
            return $wishlistalreadyadded;
        } else {
            return $log_data;
        }    
    }

    /*
    Function Name : getLanguage
    Desc : Called by the player upon load and returns the current language.
    */
    function getLanguage() {

        $this->layout = false;
        $locale = Configure::read('Config.language');
        if ($locale) {
            echo substr($locale, 0, 2);
        } else {
            echo 'en';
        }
        exit;
    }

}
<?php

class VideosController extends AppController {

    var $uses 		= array('Album', 'Genre', 'Siteconfig', 'Country', 'Video', 'LatestVideodownload', 'Videodownload', 'Library', 'WishlistVideo', 'Download', 'Language', 'Token', 'FeaturedVideo');
    var $helpers 	= array('WishlistVideo', 'Language', 'Videodownload', 'Mvideo', 'Token');
    var $components = array('Downloadsvideos', 'Session', 'Downloads', 'Common');
    var $layout 	= 'home';

    /*
      Function Name : beforeFilter
      Desc : actions that needed before other functions are getting called
     */

    function beforeFilter() {

        parent::beforeFilter();

        $this->Cookie->name   = 'baker_id';
        $this->Cookie->time   = 3600; // or '1 hour'
        $this->Cookie->path   = '/';
        $this->Cookie->domain = 'freegalmusic.com';
    }

    function index() {

		//Configure::write('debug', 0);

        $this->layout = 'home';

        $libId 	   = $this->Session->read('library');
        $patId 	   = $this->Session->read('patron');
        $territory = $this->Session->read('territory');
        
        $this->set('libId', $libId);
        $this->set('patId', $patId);

        $prefix = strtolower($territory) . "_";

        $featuredVideos = array();
        $topDownloads 	= array();

        if (!empty($patId)) {

            $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
            $patronDownload  = $this->Downloads->checkPatronDownload($patId, $libId);

            $this->set('libraryDownload', $libraryDownload);
            $this->set('patronDownload',  $patronDownload);
        }

        if (($featuredVideos = Cache::read("featured_videos" . $territory)) === false) {

        	$featuredVideos = $this->FeaturedVideo->fetchFeaturedVideoByTerritoryAndSalesDate($prefix, $territory);

            if (!empty($featuredVideos)) {

                foreach ($featuredVideos as $key => $featureVideo) {

                    $videoArtwork = $this->Token->artworkToken($featureVideo['File']['CdnPath'] . "/" . $featureVideo['File']['SourceURL']);
                    $videoImage   = Configure::read('App.Music_Path') . $videoArtwork;

                    $featuredVideos[$key]['videoImage'] = $videoImage;
                }

                Cache::write("featured_videos" . $territory, $featuredVideos);
            }
        } else {
            
        	$featuredVideos = Cache::read("featured_videos" . $territory);
        }
        
        $this->set('featuredVideos', $featuredVideos);

        if ( ($topDownloads = Cache::read("top_download_videos" . $territory)) === false) {

            $topDownloads = $this->Videodownload->fetchVideodownloadTopDownloadedVideosBySalesDateAndDownloadStatus($prefix);

            if (!empty($topDownloads)) {

                foreach ($topDownloads as $key => $topDownload) {

                    $videoArtwork = $this->Token->artworkToken($topDownload['File']['CdnPath'] . "/" . $topDownload['File']['SourceURL']);
                    $videoImage   = Configure::read('App.Music_Path') . $videoArtwork;

                    $topDownloads[$key]['videoImage'] = $videoImage;
                }

                Cache::write("top_download_videos" . $territory, $topDownloads);
            }
        } else {

            $topDownloads = Cache::read("top_download_videos" . $territory) ;
        }
        
        $this->set('topVideoDownloads',  $topDownloads);
        
        $this->Common->getVideodownloadStatus( $libId, $patId, Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'));
    }

    /**
     * Function Name : download
     * Desc : Actions that is used for user download request for video
     * @param nil
     */
    function download() {

        //settings
        //Configure::write('debug', 2);
        
        $this->layout = false;

        //set required params
        $prodId   = $_POST['ProdID'];
        $provider = $_POST['ProviderType'];

        /** creates log file name */
        $log_name = 'videos_stored_procedure_web_log_' . date('Y_m_d');
        $log_id   = md5(time());
        $log_data = PHP_EOL . "----------Request (" . $log_id . ") Start----------------" . PHP_EOL;

        //on/off single channel functionality    
        $Setting 		 = $this->Siteconfig->find('first', array('conditions' => array('soption' => 'single_channel')));
        $checkValidation = $Setting['Siteconfig']['svalue'];

        if ($checkValidation == 1) {

            // calls Downloadsvideos component for validation
            $validationResult = $this->Downloadsvideos->validateDownloadVideos($prodId, $provider);

            /** records download component request & response */
            $log_data .= "DownloadComponentParameters-ProdId= '" . $prodId . "':DownloadComponentParameters-Provider_type= '" . $provider . "':DownloadComponentResponse-Status='" . $validationResult[0] . "':DownloadComponentResponse-Msg='" . $validationResult[1] . "':DownloadComponentResponse-ErrorTYpe='" . $validationResult[2] . "'";

            $checked 				 = "true";
            $validationPassed 		 = $validationResult[0];
            $validationPassedMessage = (($validationResult[0] == 0) ? 'false' : 'true');
            $validationMessage 		 = $validationResult[1];

        } else {

            $checked 				 = "false";
            $validationPassed 		 = true;
            $validationPassedMessage = "Not Checked";
            $validationMessage 		 = '';
        }

        // sets user id
        $user = $this->Session->read('Auth.User.id');

        if (empty($user)) {
            $user = $this->Session->read('patron');
        }

        // executes IF for valid request
        if ($validationPassed == true) {

            // logs in downloadvideos.log
            $this->log("Validation Checked : " . $checked . " Valdition Passed : " . $validationPassedMessage . " Validation Message : " . $validationMessage . " for ProdID :" . $prodId . " and Provider : " . $provider . " for library id : " . $this->Session->read('library') . " and user id : " . $user, 'downloadvideos');

            //set required params
            $libId    = $this->Session->read('library');
            $patId    = $this->Session->read('patron');

            $prodId	  = $_POST['ProdID'];
            $provider = $_POST['ProviderType'];

            //redirects user to home on null ProdID
            if ($prodId == '' || $prodId == 0) {
                $this->redirect(array('controller' => 'homes', 'action' => 'index'));
            }

            //get video data  
            $trackDetails = $this->Video->getVideoData($prodId, $provider);

            //collects video data  
            $insertArr = Array();

            $insertArr['library_id'] 	= $libId;
            $insertArr['patron_id'] 	= $patId;
            $insertArr['ProdID'] 		= $prodId;
            $insertArr['artist'] 		= addslashes($trackDetails['0']['Video']['Artist']);
            $insertArr['track_title'] 	= addslashes($trackDetails['0']['Video']['VideoTitle']);
            $insertArr['provider_type'] = $trackDetails['0']['Video']['provider_type'];
            $insertArr['ProductID'] 	= $trackDetails['0']['Video']['ProductID'];
            $insertArr['ISRC'] 			= $trackDetails['0']['Video']['ISRC'];

            // creates download url            
            $videoUrl 	   = $this->Token->regularToken($trackDetails['0']['Full_Files']['CdnPath'] . "/" . $trackDetails['0']['Full_Files']['SaveAsName']);
            $finalVideoUrl = Configure::read('App.Music_Path') . $videoUrl;

            //collects video data 
            if ($this->Session->read('referral_url') && ($this->Session->read('referral_url') != '')) {

                $insertArr['email'] 		  = '';
                $insertArr['user_login_type'] = 'referral_url';

            } elseif ($this->Session->read('innovative') && ($this->Session->read('innovative') != '')) {

                $insertArr['email'] 		  = '';
                $insertArr['user_login_type'] = 'innovative';

            } elseif ($this->Session->read('mdlogin_reference') && ($this->Session->read('mdlogin_reference') != '')) {

                $insertArr['email'] 		  = '';
                $insertArr['user_login_type'] = 'mdlogin_reference';

            } elseif ($this->Session->read('mndlogin_reference') && ($this->Session->read('mndlogin_reference') != '')) {

                $insertArr['email'] 		  = '';
                $insertArr['user_login_type'] = 'mndlogin_reference';

            } elseif ($this->Session->read('innovative_var') && ($this->Session->read('innovative_var') != '')) {

                $insertArr['email'] 		  = '';
                $insertArr['user_login_type'] = 'innovative_var';

            } elseif ($this->Session->read('innovative_var_name') && ($this->Session->read('innovative_var_name') != '')) {

            	$insertArr['email'] 		  = '';
                $insertArr['user_login_type'] = 'innovative_var_name';

            } elseif ($this->Session->read('innovative_var_https_name') && ($this->Session->read('innovative_var_https_name') != '')) {

                $insertArr['email'] 		  = '';
                $insertArr['user_login_type'] = 'innovative_var_https_name';

            } elseif ($this->Session->read('innovative_var_https') && ($this->Session->read('innovative_var_https') != '')) {

                $insertArr['email'] 		  = '';
                $insertArr['user_login_type'] = 'innovative_var_https';

            } elseif ($this->Session->read('innovative_var_https_wo_pin') && ($this->Session->read('innovative_var_https_wo_pin') != '')) {

                $insertArr['email'] 		  = '';
                $insertArr['user_login_type'] = 'innovative_var_https_wo_pin';

            } elseif ($this->Session->read('innovative_https') && ($this->Session->read('innovative_https') != '')) {

            	$insertArr['email']			  = '';
                $insertArr['user_login_type'] = 'innovative_https';

            } elseif ($this->Session->read('innovative_wo_pin') && ($this->Session->read('innovative_wo_pin') != '')) {

                $insertArr['email'] 		  = '';
                $insertArr['user_login_type'] = 'innovative_wo_pin';

            } elseif ($this->Session->read('sip2') && ($this->Session->read('sip2') != '')) {

                $insertArr['email'] 		  = '';
                $insertArr['user_login_type'] = 'sip2';
            
            } elseif ($this->Session->read('sip') && ($this->Session->read('sip') != '')) {

            	$insertArr['email'] 		  = '';
                $insertArr['user_login_type'] = 'sip';

            } elseif ($this->Session->read('innovative_var_wo_pin') && ($this->Session->read('innovative_var_wo_pin') != '')) {

                $insertArr['email'] 		  = '';
                $insertArr['user_login_type'] = 'innovative_var_wo_pin';

            } elseif ($this->Session->read('sip2_var') && ($this->Session->read('sip2_var') != '')) {

            	$insertArr['email'] 		  = '';
                $insertArr['user_login_type'] = 'sip2_var';

            } elseif ($this->Session->read('sip2_var') && ($this->Session->read('sip2_var') != '')) {

                $insertArr['email']			  = '';
                $insertArr['user_login_type'] = 'sip2_var_wo_pin';

            } elseif ($this->Session->read('sip2_var_wo_pin') && ($this->Session->read('sip2_var_wo_pin') != '')) {

                $insertArr['email']			  = '';
                $insertArr['user_login_type'] = 'sip2_var_wo_pin';

            } elseif ($this->Session->read('ezproxy') && ($this->Session->read('ezproxy') != '')) {

            	$insertArr['email'] 		  = '';
                $insertArr['user_login_type'] = 'ezproxy';

            } elseif ($this->Session->read('soap') && ($this->Session->read('soap') != '')) {
                $insertArr['email'] 		  = '';
                $insertArr['user_login_type'] = 'soap';

            } elseif ($this->Session->read('curl_method') && ($this->Session->read('curl_method') != '')) {

                $insertArr['email'] 		  = '';
                $insertArr['user_login_type'] = 'curl_method';

            } else {

                $insertArr['email'] 		  = $this->Session->read('patronEmail');
                $insertArr['user_login_type'] = 'user_account';
            }

            $insertArr['user_agent'] = str_replace(";", "", $_SERVER['HTTP_USER_AGENT']);
            $insertArr['ip'] 		 = $_SERVER['REMOTE_ADDR'];

            //on/off latest-download functionality
            $this->Library->setDataSource('master');

            $siteConfigData = $this->Siteconfig->fetchSiteconfigDataBySoption();

            $maintainLatestDownload = (($siteConfigData[0]['siteconfig']['svalue'] == 1) ? true : false);

            if ($maintainLatestDownload) {

                //logs in downloadvideos.log
                $this->log("videos_proc_d_ld called", 'downloadvideos');

                $procedure = 'videos_proc_d_ld';

                //calls procedure
                $sql = "CALL videos_proc_d_ld('" . $libId . "','" . $patId . "', '" . $prodId . "', '" . $trackDetails['0']['Video']['ProductID'] . "', '" . $trackDetails['0']['Video']['ISRC'] . "', '" . addslashes($trackDetails['0']['Video']['Artist']) . "', '" . addslashes($trackDetails['0']['Video']['VideoTitle']) . "', '" . $insertArr['user_login_type'] . "', '" . $insertArr['provider_type'] . "', '" . $insertArr['email'] . "', '" . addslashes($insertArr['user_agent']) . "', '" . $insertArr['ip'] . "', '" . Configure::read('App.curWeekStartDate') . "', '" . Configure::read('App.curWeekEndDate') . "',@ret)";
            } else {

                $procedure = 'videos_proc_d';

                //calls procedure
                $sql = "CALL videos_proc_d('" . $libId . "','" . $patId . "', '" . $prodId . "', '" . $trackDetails['0']['Video']['ProductID'] . "', '" . $trackDetails['0']['Video']['ISRC'] . "', '" . addslashes($trackDetails['0']['Video']['Artist']) . "', '" . addslashes($trackDetails['0']['Video']['VideoTitle']) . "', '" . $insertArr['user_login_type'] . "', '" . $insertArr['provider_type'] . "', '" . $insertArr['email'] . "', '" . addslashes($insertArr['user_agent']) . "', '" . $insertArr['ip'] . "', '" . Configure::read('App.curWeekStartDate') . "', '" . Configure::read('App.curWeekEndDate') . "',@ret)";
            }

            //get procedure response
            $this->Library->query($sql);
            
            $sql 	= "SELECT @ret";
            $data 	= $this->Library->query($sql);
            $return = $data[0][0]['@ret'];

            //logs in downloadvideos.log
            $log_data .= ":StoredProcedureParameters-LibID='" . $libId . "':StoredProcedureParameters-Patron='" . $patId . "':StoredProcedureParameters-ProdID='" . $prodId . "':StoredProcedureParameters-ProductID='" . $trackDetails['0']['Video']['ProductID'] . "':StoredProcedureParameters-ISRC='" . $trackDetails['0']['Video']['ISRC'] . "':StoredProcedureParameters-Artist='" . addslashes($trackDetails['0']['Video']['Artist']) . "':StoredProcedureParameters-SongTitle='" . addslashes($trackDetails['0']['Video']['VideoTitle']) . "':StoredProcedureParameters-UserLoginType='" . $insertArr['user_login_type'] . "':StoredProcedureParameters-ProviderType='" . $insertArr['provider_type'] . "':StoredProcedureParameters-Email='" . $insertArr['email'] . "':StoredProcedureParameters-UserAgent='" . addslashes($insertArr['user_agent']) . "':StoredProcedureParameters-IP='" . $insertArr['ip'] . "':StoredProcedureParameters-CurWeekStartDate='" . Configure::read('App.curWeekStartDate') . "':StoredProcedureParameters-CurWeekEndDate='" . Configure::read('App.curWeekEndDate') . "':StoredProcedureParameters-Name='" . $procedure . "':StoredProcedureParameters-@ret='" . $return . "'";

            //executes IF on success
            if (is_numeric($return)) {

                //make in LatestDownloadVideo entry
                $this->LatestVideodownload->setDataSource('master');
                
                $data = $this->LatestVideodownload->fetchLatestVideoDownloadCountByLibraryIdAndPatronIdAndProdIdAndProviderTypeAndDate($libId, $patId, $prodId, $insertArr['provider_type']);

                // logs data
                if (0 === $data) {
                    $log_data .= ":NotInLD";
                }

                // logs data
                if (false === $data) {
                    $log_data .= ":SelectLDFail";
                }

                $this->LatestVideodownload->setDataSource('default');
            }

            // logs data
            $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------";

            //writes in log
            $this->log($log_data, $log_name);
            
            $this->Library->setDataSource('default');           
           
            //updating session for VideoDown load status
            $this->Common->getVideodownloadStatus( $libId, $patId, Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate') , true );
            
            if (is_numeric($return)) { //succcess

                header("Content-Type: application/force-download");
                header("Content-Disposition: attachment; filename=\"" . basename($trackDetails['0']['Full_Files']['SaveAsName']) . "\";");
                header("Location: " . $finalVideoUrl);
                exit;
            } else { //fail

                if ($return == 'incld') {

                    $this->Session->setFlash("You have already downloaded this Videos. Get it from your recent downloads.");
                    $this->redirect(array('controller' => 'homes', 'action' => 'my_history'));
                } else {

                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit;
                }
            }
        } else { // executes ELSE for vinalid request

            /** complete records with validation fail */
            $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------" . PHP_EOL;

            $this->log($log_data, $log_name);

            $this->Session->setFlash($validationResult[1]);
            $this->redirect(array('controller' => 'homes', 'action' => 'index'));
        }
    }

    function my_lib_top_10_videos() {

        $libId 			 = $this->Session->read('library');
        $patId 			 = $this->Session->read('patron');
        $country 		 = $this->Session->read('territory');
        $advisory_status = '';

        //get Advisory condition
        $advisory_status = $this->getLibraryExplicitStatus($libId);

        $ids_provider_type_video = '';

        if (($libDownload = Cache::read("lib_videos" . $libId)) === false) {

            $SiteMaintainLDT = $this->Siteconfig->fetchSiteconfigDataBySoption();

            if ($SiteMaintainLDT['Siteconfig']['svalue'] == 1) {

                $topDownloaded_videos = $this->LatestVideodownload->fetchLatestVideodownloadTopDownloadedVideosByLibraryIdAndCreated($libId);

            } else {
                $topDownloaded_videos = $this->Videodownload->fetchVideodownloadTopDownloadedVideosByLibraryIdAndCreated($libId);
            }

            $ioda_ids 	  = array();
            $sony_ids 	  = array();
            $ids 	  	  = '';

            foreach ($topDownloaded_videos as $k => $v) {

                if ($SiteMaintainLDT['Siteconfig']['svalue'] == 1) {

                    if (empty($ids)) {

                        $ids .= $v['LatestVideodownload']['ProdID'];
                        $ids_provider_type_video .= "(" . $v['LatestVideodownload']['ProdID'] . ",'" . $v['LatestVideodownload']['provider_type'] . "')";
                    } else {

                        $ids .= ',' . $v['LatestVideodownload']['ProdID'];
                        $ids_provider_type_video .= ',' . "(" . $v['LatestVideodownload']['ProdID'] . ",'" . $v['LatestVideodownload']['provider_type'] . "')";
                    }

                    if ($v['LatestVideodownload']['provider_type'] == 'sony') {
                        $sony_ids[] = $v['LatestVideodownload']['ProdID'];
                    } else {

                        $ioda_ids[] = $v['LatestVideodownload']['ProdID'];
                    }
                } else {
                    if (empty($ids)) {

                        $ids .= $v['Download']['ProdID'];
                        $ids_provider_type_video .= "(" . $v['Videodownload']['ProdID'] . ",'" . $v['Videodownload']['provider_type'] . "')";
                    } else {

                        $ids .= ',' . $v['Download']['ProdID'];
                        $ids_provider_type_video .= ',' . "(" . $v['Videodownload']['ProdID'] . ",'" . $v['Videodownload']['provider_type'] . "')";
                    }

                    if ($v['Download']['provider_type'] == 'sony') {

                        $sony_ids[] = $v['Videodownload']['ProdID'];
                    } else {

                        $ioda_ids[] = $v['Videodownload']['ProdID'];
                    }
                }
            }

            if ($ids != '') {

                $this->Video->recursive = 2;
                $countryPrefix = $this->Session->read('multiple_countries');

                $topDownload_video = $this->Video->fetchVideoTopDownloaedVideosByProdIdAndProviderTypeAndTerritoryAndDownloadStatus($countryPrefix, $country, $ids, $sony_ids, $ioda_ids);

                foreach ($topDownload_video as $key => $value) {

                    $albumArtwork = $this->Token->artworkToken($value['File']['CdnPath'] . "/" . $value['File']['SourceURL']);
                    $videoAlbumImage = Configure::read('App.Music_Path') . $albumArtwork;
                    $topDownload_video[$key]['videoAlbumImage'] = $videoAlbumImage;
                }
            } else {
                $topDownload_video = array();
            }

            Cache::write("lib_video" . $libId, $topDownload_video);
        } else {
            $topDownload_video = Cache::read("lib_video" . $libId);
        }

        return $topDownload_video;
    }

    function details() {
        
        $this->layout 	 = 'home';
        $libId 			 = $this->Session->read('library');
        $patId 			 = $this->Session->read('patron');
        $territory 		 = $this->Session->read('territory');

        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
        $patronDownload  = $this->Downloads->checkPatronDownload($patId, $libId);

        $this->set('libraryDownload', $libraryDownload);
        $this->set('patronDownload', $patronDownload);

        $prefix = strtolower($territory) . "_";

        if (isset($this->params['pass'][0])) {

            $VideosData = $this->Video->fetchVideoDataByDownloadStatusAndProdId($prefix, $this->params[pass][0]);
            
            $videoArtwork = $this->Token->artworkToken($VideosData[0]['File']['CdnPath'] . "/" . $VideosData[0]['File']['SourceURL']);
            
            $VideosData[0]['videoImage'] = Configure::read('App.Music_Path') . $videoArtwork;

        } else {
            $VideosData = array();
        }

        $this->set('VideosData', $VideosData);

        //  More Videos By Artist            
        $MoreVideosData = array();

        if (count($VideosData) > 0) {

            $country = $territory;

            $decodedId = trim($VideosData[0]['Video']['ArtistText']);
            $decodedId = str_replace('@', '/', $decodedId);

            if (!empty($country)) {

                $MoreVideosData = $this->Common->getAllVideoByArtist($country, $decodedId);
                Cache::write("videolist_" . $country . "_" . $decodedId, $MoreVideosData);
                $MoreVideosData = Cache::read("videolist_" . $country . "_" . $decodedId);
            } else {

                $MoreVideosData = Cache::read("videolist_" . $country . "_" . $decodedId);
            }
        } else {
            $MoreVideosData = array();
        }

        $this->set('MoreVideosData', $MoreVideosData);

        if (count($VideosData) > 0) {
            
            if($prefix === '_') {
                $this->log("Empty prefix:".$prefix." in getComingSoonSongs for : ".$territory, "cache");
                exit;
            }

            if ($TopVideoGenreData = Cache::read("top_videos_genre_" . $territory . '_' . $VideosData[0]['Video']['Genre']) === false) {

                $TopVideoGenreData = $this->Videodownload->fetchVideodownloadTopVideoGenreByLibraryIdAndLibraryTerritoryAndSaleDateAndGenreAndProviderType($prefix, $territory, addslashes($VideosData[0]['Video']['Genre']));

                foreach ($TopVideoGenreData as $key => $value) {

                    $videoArtwork = $this->Token->artworkToken($value['File']['CdnPath'] . "/" . $value['File']['SourceURL']);
                    
                    $videoImage  = Configure::read('App.Music_Path') . $videoArtwork;
                    
                    $TopVideoGenreData[$key]['videoImage'] = $videoImage;
                }

                Cache::write("top_videos_genre_" . $territory . '_' . $VideosData[0]['Video']['Genre'], $TopVideoGenreData);
            } else {

                $TopVideoGenreData = Cache::read("top_videos_genre_" . $territory . '_' . $VideosData[0]['Video']['Genre']);
            }
        } else {

            $TopVideoGenreData = array();
        }

        $this->set('TopVideoGenreData', $TopVideoGenreData);
        $this->set('VideoGenre', $VideosData[0]['Video']['Genre']);
    }
}
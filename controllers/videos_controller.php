<?php
class VideosController extends AppController {

    var $uses 		= array('Siteconfig', 'Video', 'LatestVideodownload', 'Videodownload', 'Library', 'Token', 'FeaturedVideo');
    var $helpers 	= array('WishlistVideo', 'Language', 'Videodownload', 'Mvideo', 'Token');
    var $components = array('Downloadsvideos', 'Session', 'Downloads', 'Common', 'Checkloginusers');
    var $layout 	= 'home';

    /**
     * Called before the controller action. You can use this method to configure and customize components
     * or perform logic that needs to happen before each controller action.
     *
     * @return void
     * @link http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
     */

    function beforeFilter() {

        parent::beforeFilter();

        $this->Cookie->name   = 'baker_id';
        $this->Cookie->time   = 3600; // or '1 hour'
        $this->Cookie->path   = '/';
        $this->Cookie->domain = 'freegalmusic.com';
    }

    /**
     * Function Name: index
     * Desc: Action that is used to display Featured & Top Videos
     * 
     * @param: Nil
     * @return: void
     */

    function index() {

        $libraryId = $this->Session->read('library');
        $patronId  = $this->Session->read('patron');
        $territory = $this->Session->read('territory');
        
        $this->set('libraryId', $libraryId);
        $this->set('patronId', $patronId);

        $prefix = strtolower( $territory ) . '_';

        if ( !empty( $patronId ) ) {

            $libraryDownload = $this->Downloads->checkLibraryDownload( $libraryId );
            $patronDownload  = $this->Downloads->checkPatronDownload( $patronId, $libraryId );

            $this->set( 'libraryDownload', $libraryDownload );
            $this->set( 'patronDownload',  $patronDownload );
        }

        $featuredVideos = $this->featuredVideos( $prefix, $territory );
        $topDownloads   = $this->topDownloadVideos( $prefix, $territory );

        $this->set( 'featuredVideos', $featuredVideos );
        $this->set( 'topVideoDownloads',  $topDownloads );

        $this->getVideosDownloadStatus( $featuredVideos, $libraryId, $patronId );
        /**
         * As per my understanding
         * we need to remove this function call here 
         * Because this function is alreay calling in beforeFilter function's of app_controller.php file
         * */
        
        //$this->Common->getVideodownloadStatus( $libraryId, $patronId, Configure::read( 'App.twoWeekStartDate' ), Configure::read( 'App.twoWeekEndDate' ) );
    }

    /**
     * Function Name : download
     * Desc : Actions that is used for user download request for video
     * 
     * @param nil
     * @return void
     */

    function download() {

        $this->layout = false;

        //set required params
        $prodId	  = $this->params['form']['ProdID'];
        $provider = $this->params['form']['ProviderType'];

        /** creates log file name */
        $log_name = 'videos_stored_procedure_web_log_' . date('Y_m_d');
        $log_id   = md5(time());
        $log_data = PHP_EOL . '----------Request (' . $log_id . ') Start----------------' . PHP_EOL;

        //on/off single channel functionality    
        $Setting 		 = $this->Siteconfig->fetchSiteconfigDataBySoption( 'single_channel' );
        $checkValidation = $Setting['Siteconfig']['svalue'];

        if ( $checkValidation == 1 ) {

            // calls Downloadsvideos component for validation
            $validationResult = $this->Downloadsvideos->validateDownloadVideos( $prodId, $provider );

            /** records download component request & response */
            $log_data .= "DownloadComponentParameters-ProdId= '" . $prodId . "':DownloadComponentParameters-Provider_type= '" . $provider . "':DownloadComponentResponse-Status='" . $validationResult[0] . "':DownloadComponentResponse-Msg='" . $validationResult[1] . "':DownloadComponentResponse-ErrorTYpe='" . $validationResult[2] . "'";

            $checked 				 = 'true';
            $validationPassed 		 = $validationResult[0];
            $validationPassedMessage = $validationResult[0] == 0  ? 'false' : 'true';
            $validationMessage 		 = $validationResult[1];

        } else {
            $checked 				 = 'false';
            $validationPassed 		 = true;
            $validationPassedMessage = 'Not Checked';
            $validationMessage 		 = '';
        }

        // sets user id
        $user = $this->Session->read( 'Auth.User.id' );

        if ( empty( $user ) ) {
            $user = $this->Session->read( 'patron' );
        }

        // executes IF for valid request
        if ( $validationPassed == true ) {

            // logs in downloadvideos.log
            $this->log( 'Validation Checked : ' . $checked . ' Valdition Passed : ' . $validationPassedMessage . ' Validation Message : ' . $validationMessage . ' for ProdID :' . $prodId . ' and Provider : ' . $provider . ' for library id : ' . $this->Session->read( 'library' ) . ' and user id : ' . $user, 'downloadvideos' );

            //set required params
            $libId  = $this->Session->read( 'library' );
            $patId  = $this->Session->read( 'patron' );

            $prodId	  = $this->params['form']['ProdID'];
            $provider = $this->params['form']['ProviderType'];

            //redirects user to home on null ProdID
            if ( $prodId == '' || $prodId == 0 ) {
                $this->redirect( array( 'controller' => 'homes', 'action' => 'index' ) );
            }

            //get video data  
            $trackDetails = $this->Video->getVideoData( $prodId, $provider );

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
            $videoUrl 	   = $this->Token->regularToken( $trackDetails['0']['Full_Files']['CdnPath'] . '/' . $trackDetails['0']['Full_Files']['SaveAsName'] );
            $finalVideoUrl = Configure::read( 'App.Music_Path' ) . $videoUrl;

            //collects video data 
            $userArr = $this->Checkloginusers->checkLoginUser();

            $insertArr['email'] 		  = $userArr['email'];
            $insertArr['user_login_type'] = $userArr['user_login_type'];
            $insertArr['user_agent'] 	  = str_replace(';', '', $_SERVER['HTTP_USER_AGENT']);
            $insertArr['ip'] 		 	  = $_SERVER['REMOTE_ADDR'];

            //on/off latest-download functionality
            $this->Library->setDataSource( 'master' );

            $siteConfigData = $this->Siteconfig->fetchSiteconfigDataBySoption( 'maintain_ldt' );

            $maintainLatestDownload = $siteConfigData[0]['siteconfig']['svalue'] == 1 ? true : false;

            if ( $maintainLatestDownload ) {

                //logs in downloadvideos.log
                $this->log( 'videos_proc_d_ld called', 'downloadvideos' );

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
            if ( is_numeric( $return ) ) {

                //make in LatestDownloadVideo entry
                $this->LatestVideodownload->setDataSource( 'master' );
                
                $data = $this->LatestVideodownload->fetchLatestVideoDownloadCount( $libId, $patId, $prodId, $insertArr['provider_type'] );

                // logs data
                if ( $data === 0 ) {
                    $log_data .= ':NotInLD';
                }

                // logs data
                if ( $data === false ) {
                    $log_data .= ':SelectLDFail';
                }

                $this->LatestVideodownload->setDataSource( 'default' );
            }

            // logs data
            $log_data .= PHP_EOL . '---------Request (' . $log_id . ') End----------------';

            //writes in log
            $this->log( $log_data, $log_name );
            
            $this->Library->setDataSource( 'default' );           
           
            //updating session for VideoDown load status
            $this->Common->getVideodownloadStatus( $libId, $patId, Configure::read( 'App.twoWeekStartDate' ), Configure::read( 'App.twoWeekEndDate' ) , true );
            
            if ( is_numeric( $return ) ) { //succcess

                header("Content-Type: application/force-download");
                header("Content-Disposition: attachment; filename=\"" . basename($trackDetails['0']['Full_Files']['SaveAsName']) . "\";");
                header("Location: " . $finalVideoUrl);
                exit;
            } else { //fail

                if ( $return == 'incld' ) {

                    $this->Session->setFlash( 'You have already downloaded this Videos. Get it from your recent downloads.' );
                    $this->redirect(array('controller' => 'homes', 'action' => 'my_history'));
                } else {

                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                    exit;
                }
            }
        } else { // executes ELSE for vinalid request

            /** complete records with validation fail */
            $log_data .= PHP_EOL . '---------Request (' . $log_id . ') End----------------' . PHP_EOL;

            $this->log( $log_data, $log_name );

            $this->Session->setFlash( $validationResult[1] );
            $this->redirect( array( 'controller' => 'homes', 'action' => 'index' ) );
        }
    }

    function my_lib_top_10_videos() {

        $libId	 = $this->Session->read( 'library' );
        $patId	 = $this->Session->read( 'patron' );
        $country = $this->Session->read( 'territory' );
        
        $ids_provider_type_video = '';
        $advisory_status 		 = '';

        //get Advisory condition
        $advisory_status = $this->getLibraryExplicitStatus( $libId );

        $topDownload_video = Cache::read( 'lib_video' . $libId );
        
        if ( $topDownload_video === false ) {

            $SiteMaintainLDT = $this->Siteconfig->fetchSiteconfigDataBySoption( 'maintain_ldt' );

            if ( $SiteMaintainLDT['Siteconfig']['svalue'] == 1 ) {

                $topDownloaded_videos = $this->LatestVideodownload->fetchLatestVideodownloadTopDownloadedVideos( $libId );

            } else {
                $topDownloaded_videos = $this->Videodownload->fetchVideodownloadTopDownloadedVideosByLibraryIdAndCreated( $libId );
            }

            $ioda_ids 	  = array();
            $sony_ids 	  = array();
            $ids 	  	  = '';

            foreach ( $topDownloaded_videos as $k => $v ) {

                if ( $SiteMaintainLDT['Siteconfig']['svalue'] == 1 ) {

                    if ( empty( $ids ) ) {

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

            if ( $ids != '' ) {

                $this->Video->recursive = 2;
                $countryPrefix = $this->Session->read( 'multiple_countries' );

                $topDownload_video = $this->Video->fetchVideoTopDownloaedVideos( $countryPrefix, $country, $ids, $sony_ids, $ioda_ids );

                foreach ( $topDownload_video as $key => $value ) {

                    $albumArtwork    = $this->Token->artworkToken( $value['File']['CdnPath'] . '/' . $value['File']['SourceURL'] );
                    $videoAlbumImage = Configure::read( 'App.Music_Path' ) . $albumArtwork;

                    $topDownload_video[$key]['videoAlbumImage'] = $videoAlbumImage;
                }
            } else {
                $topDownload_video = array();
            }

            Cache::write( 'lib_video' . $libId, $topDownload_video );
        }

        return $topDownload_video;
    }

    /**
     * Function Name: details
     * Desc: Action that is used to display video details
     *
     * @param: nil
     * @return: void
     */

    function details() {

        $libraryId = $this->Session->read( 'library' );
        $patronId  = $this->Session->read( 'patron' );
        $territory = $this->Session->read( 'territory' );

        $libraryDownload = $this->Downloads->checkLibraryDownload( $libraryId );
        $patronDownload  = $this->Downloads->checkPatronDownload( $patronId, $libraryId );

        $this->set('libraryId', $libraryId);
        $this->set('patronId', $patronId);
        $this->set( 'libraryDownload', $libraryDownload );
        $this->set( 'patronDownload', $patronDownload );

        $prefix = strtolower( $territory ) . '_';

        $videosData = array();

        if ( isset( $this->params['pass'][0] ) ) {

            $videosData = $this->Video->fetchVideoDataByDownloadStatusAndProdId( $prefix, $this->params[pass][0] );
            
            $videoArtwork = $this->Token->artworkToken( $videosData[0]['File']['CdnPath'] . '/' . $videosData[0]['File']['SourceURL'] );
            
            $videosData[0]['videoImage'] = Configure::read( 'App.Music_Path' ) . $videoArtwork;
        }

        $this->set( 'videosData', $videosData );

        if ( count( $videosData ) > 0 ) {

			$this->moreVideosData($territory, $videosData[0]['Video']['ArtistText'], $decodedId);
			$this->topVideoGenre( $prefix, $territory, $videosData[0]['Video']['Genre'] );
        }

        $this->set( 'videoGenre', $videosData[0]['Video']['Genre'] );
    }
    
    /**
     * Function Name: featuredVideos
     * Desc: cache read & write featured videos for index action
     *
     * @param: Two and type String
     * @return: array
     */

    public function featuredVideos( $prefix, $territory ) {
    	
    	$cacheVariableSuffix = '';
    	$explicitContent     = true;

    	if( $this->Session->read('block') == 'yes' ) {

    		$cacheVariableSuffix = '_none_explicit';
    		$explicitContent     = false;
    	}

    	$featuredVideos = Cache::read( 'featured_videos' . $cacheVariableSuffix . $territory );
    	
    	if ( $featuredVideos === false ) {
    	
    		$featuredVideos = $this->FeaturedVideo->fetchFeaturedVideo( $prefix, $territory, $explicitContent );
    	
    		if ( !empty( $featuredVideos ) ) {
    	
    			foreach ( $featuredVideos as $key => $featureVideo ) {
    	
    				$videoArtwork = $this->Token->artworkToken( $featureVideo['File']['CdnPath'] . '/' . $featureVideo['File']['SourceURL'] );
    				$videoImage   = Configure::read( 'App.Music_Path' ) . $videoArtwork;
    	
    				$featuredVideos[$key]['videoImage'] = $videoImage;
    			}
    	
    			Cache::write( 'featured_videos' . $cacheVariableSuffix . $territory, $featuredVideos );
    		}
    	}
    	
    	return $featuredVideos;
    }
    
    /**
     * Function Name: topDownloadVideos
     * Desc: cache read & write top download videos for index action
     *
     * @param: Two and type String
     * @return: array
     */

    public function topDownloadVideos( $prefix, $territory ) {

    	$topDownloads = Cache::read( 'top_download_videos' . $territory );
    	
    	if ( $topDownloads === false ) {
    	
    		$topDownloads = $this->Videodownload->fetchVideodownloadTopDownloadedVideos( $prefix );
    	
    		if ( !empty( $topDownloads ) ) {
    	
    			foreach ( $topDownloads as $key => $topDownload ) {
    	
    				$videoArtwork = $this->Token->artworkToken( $topDownload['File']['CdnPath'] . '/' . $topDownload['File']['SourceURL'] );
    				$videoImage   = Configure::read( 'App.Music_Path' ) . $videoArtwork;
    	
    				$topDownloads[$key]['videoImage'] = $videoImage;
    			}
    	
    			Cache::write( 'top_download_videos' . $territory, $topDownloads );
    		}
    	}

    	return $topDownloads;
    }
    
    /**
     * Function Name: topVideoGenre
     * Desc: Cache Read & Write top videos genre data
     *
     * @param: Three and type String
     * @return: void
     */

    public function topVideoGenre( $prefix, $territory, $videoGenre ) {
    	 
    	if( $prefix === '_' ) {
    		$this->log( 'Empty prefix:'.$prefix.' in getComingSoonSongs for : '.$territory, 'cache' );
    		exit;
    	}
    	
    	$cacheVariableSuffix = '';
    	$explicitContent     = true;
    	
    	if( $this->Session->read('block') == 'yes' ) {
    	
    		$cacheVariableSuffix = '_none_explicit';
    		$explicitContent     = false;
    	}
    	 
    	$topVideoGenreData = Cache::read( 'top_videos_genre_' . $territory . '_' . $videoGenre . $cacheVariableSuffix );
    	 
    	if ( $topVideoGenreData === false ) {
    		 
    		$topVideoGenreData = $this->Videodownload->fetchVideodownloadTopVideoGenre( $prefix, $territory, addslashes( $videoGenre ), $explicitContent );
    		 
    		foreach ( $topVideoGenreData as $key => $value ) {
    			 
    			$videoArtwork = $this->Token->artworkToken( $value['File']['CdnPath'] . '/' . $value['File']['SourceURL'] );
    			 
    			$videoImage  = Configure::read( 'App.Music_Path' ) . $videoArtwork;
    			 
    			$topVideoGenreData[$key]['videoImage'] = $videoImage;
    		}
    		 
    		Cache::write( 'top_videos_genre_' . $territory . '_' . $videoGenre . $cacheVariableSuffix, $topVideoGenreData );
    	}
    	
    	$this->set( 'topVideoGenreData', $topVideoGenreData );
    }
    
    /**
     * Function Name: moreVideosData
     * Desc: Cache Read & write for videos data
     *
     * @param: three
     * @return: void
     */

    public function moreVideosData( $territory, $artistText, $decodedId ) {
    	 
    	$moreVideosData = array();
    	$country 		= $territory;
    	 
    	$decodedId = trim( $artistText );
    	$decodedId = str_replace( '@', '/', $decodedId );
    	
    	$cacheVariableSuffix = '';
    	$explicitContent     = true;
    	
    	if( $this->Session->read('block') == 'yes' ) {
    	
    		$cacheVariableSuffix = '_none_explicit';
    		$explicitContent     = false;
    	}

    	if ( !empty( $country ) ) {

    		$moreVideosData = $this->Common->getAllVideoByArtist( $country, $decodedId, $explicitContent );
    		Cache::write( 'videolist_' . $country . '_' . $decodedId . $cacheVariableSuffix, $moreVideosData );
    		 
    	} else {
    		$moreVideosData = Cache::read( 'videolist_' . $country . '_' . $decodedId . $cacheVariableSuffix );
    	}
    	
    	$this->set( 'moreVideosData', $moreVideosData );
    }
    
    public function getVideosDownloadStatus( $featuredVideos, $libraryId, $patronId ) {

    	$videoDownloadStatus = array();
    	
    	if ( $this->Session->check( 'videodownloadCountArray' ) ) {

    		$videodownloadCountArray = $this->Session->read( 'videodownloadCountArray' );
    		foreach ( $featuredVideos as $key => $featureVideo ) {
    			
    			if ( isset( $videodownloadCountArray[$featureVideo['FeaturedVideo']['ProdID']] ) && $videodownloadCountArray[$featureVideo['FeaturedVideo']['ProdID']]['provider_type'] == $featureVideo['Video']['provider_type'] ) {
    				$videoDownloadStatus[$featureVideo['FeaturedVideo']['ProdID']][$featureVideo['Video']['provider_type']] = $videodownloadCountArray[$featureVideo['FeaturedVideo']['ProdID']]['totalProds'];
    			} else {
    				$videoDownloadStatus[$featureVideo['FeaturedVideo']['ProdID']][$featureVideo['Video']['provider_type']] = 0;
    			}
    		}

    	} else {

    		$idsProviderType = '';

    		foreach ( $featuredVideos as $key => $featureVideo ) {

    			if ( empty( $idsProviderType ) ) {

    				$idsProviderType .= "(" . $featureVideo['FeaturedVideo']['ProdID'] . ",'" . $featureVideo['Video']['provider_type'] . "')";
    			} else {
    				
    				$idsProviderType .= ',' . "(" . $featureVideo['FeaturedVideo']['ProdID'] . ",'" . $featureVideo['Video']['provider_type'] . "')";
    			}
    		}
    		$resultSet = $this->Videodownload->getDownloadStatusOfVideos( $idsProviderType, $libraryId , $patronId, Configure::read( 'App.twoWeekStartDate' ), Configure::read( 'App.twoWeekEndDate' ) );
    	}
    	
    }
}
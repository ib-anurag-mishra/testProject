<?php

class VideosController extends AppController
{

    var $uses = array('Album', 'Genre', 'Siteconfig', 'Country', 'Video', 'LatestVideodownload', 'Videodownload', 'Library', 'WishlistVideo', 'Download', 'Language', 'Token');
    var $helpers = array('WishlistVideo', 'Language', 'Videodownload', 'Mvideo', 'Token');
    var $components = array('Downloadsvideos', 'Session', 'Downloads', 'Common');
    var $layout = 'home';

    /*
      Function Name : beforeFilter
      Desc : actions that needed before other functions are getting called
     */

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Cookie->name = 'baker_id';
        $this->Cookie->time = 3600; // or '1 hour'
        $this->Cookie->path = '/';
        $this->Cookie->domain = 'freegalmusic.com';
        //$this->Cookie->key = 'qSI232qs*&sXOw!';
    }

    function index()
    {
       Configure::write('debug', 0);
       
        $this->layout = 'home';

        $libId = $this->Session->read('library');
        $this->set('libId', $libId);

        $patId = $this->Session->read('patron');
        $this->set('patId', $patId);


        $territory = $this->Session->read('territory');

        $prefix = strtolower($territory) . "_";

        $featuredVideos = array();
        $topDownloads = array();

        //get Advisory condition
        //$advisory_status = $this->getLibraryExplicitStatus($libId);

        if (!empty($patId))
        {
            $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
            $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);
            $this->set('libraryDownload', $libraryDownload);
            $this->set('patronDownload', $patronDownload);
        }

        // Cache::delete("featured_videos".$territory);
        if (($featuredVideos = Cache::read("featured_videos" . $territory)) === false)
        {
            $featuredVideosSql = "SELECT 
                                    `FeaturedVideo`.`id`,
                                    `FeaturedVideo`.`ProdID`,
                                    `Video`.`ProdID`,
                                    `Video`.`Image_FileID`, 
                                    `Video`.`VideoTitle`, 
                                    `Video`.`ArtistText`, 
                                    `Video`.`provider_type`, 
                                    Video.Advisory, 
                                    `File`.`CdnPath`, 
                                    `File`.`SourceURL`,
                                    Video_file.SaveAsName,
                                    `Country`.`SalesDate` 
                                    FROM featured_videos as FeaturedVideo 
                                    LEFT JOIN video as Video on FeaturedVideo.ProdID = Video.ProdID and FeaturedVideo.provider_type = Video.provider_type 
                                    LEFT JOIN File as File on File.FileID = Video.Image_FileID 
                                    LEFT JOIN File as Video_file on (Video_file.FileID = Video.FullLength_FileID)
                                    LEFT JOIN {$prefix}countries as Country on (`Video`.`ProdID`=`Country`.`ProdID` AND `Video`.`provider_type`=`Country`.`provider_type`) 
                                    WHERE `FeaturedVideo`.`territory` = '" . $territory . "' AND `Country`.`SalesDate` <= NOW() ";
            //print_r($featuredVideosSql);
            
            $featuredVideos = $this->Album->query($featuredVideosSql);

            if (!empty($featuredVideos))
            {
                foreach ($featuredVideos as $key => $featureVideo)
                {                    
                    $videoArtwork = $this->Token->artworkToken($featureVideo['File']['CdnPath'] . "/" . $featureVideo['File']['SourceURL']);
                    $videoImage = Configure::read('App.Music_Path') . $videoArtwork;
                    $featuredVideos[$key]['videoImage'] = $videoImage;
                }
                Cache::write("featured_videos" . $territory, $featuredVideos);
            }
        }
        else
        {
            $featuredVideos = Cache::read("featured_videos" . $territory);
        }
        
        $this->set('featuredVideos', $featuredVideos);
        
        
        
       //Cache::delete("top_download_videos".$territory);
        if ( ($topDownloads = Cache::read("top_download_videos" . $territory)) === false)       
        {
            $topDownloadSQL = "SELECT 
                                    Videodownloads.ProdID, 
                                    Video.ProdID, 
                                    Video.provider_type, 
                                    Video.VideoTitle, 
                                    Video.ArtistText, 
                                    Video.Advisory, 
                                    File.CdnPath, 
                                    File.SourceURL,
                                    Video_file.SaveAsName,
                                    COUNT(DISTINCT(Videodownloads.id)) AS COUNT, 
                                    `Country`.`SalesDate` 
                            FROM videodownloads as Videodownloads 
                            LEFT JOIN video as Video ON (Videodownloads.ProdID = Video.ProdID AND Videodownloads.provider_type = Video.provider_type) 
                            LEFT JOIN File as File ON (Video.Image_FileID = File.FileID) 
                            LEFT JOIN File as Video_file on (Video_file.FileID = Video.FullLength_FileID) 
                            LEFT JOIN {$prefix}countries as Country on (`Video`.`ProdID`=`Country`.`ProdID` AND `Video`.`provider_type`=`Country`.`provider_type`) 
                                WHERE `Country`.`SalesDate` <= NOW() AND Video.DownloadStatus = '1' GROUP BY Videodownloads.ProdID ORDER BY COUNT DESC limit 100";
               //         print_r($topDownloadSQL);
            $topDownloads = $this->Album->query($topDownloadSQL);
            
            if (!empty($topDownloads))
            {
                foreach ($topDownloads as $key => $topDownload)
                {                    
                    $videoArtwork = $this->Token->artworkToken($topDownload['File']['CdnPath'] . "/" . $topDownload['File']['SourceURL']);
                    $videoImage = Configure::read('App.Music_Path') . $videoArtwork;
                    $topDownloads[$key]['videoImage'] = $videoImage;
                }
                Cache::write("top_download_videos" . $territory, $topDownloads);
            }
        }
        else
        {
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
    function download()
    {

        //settings
        Configure::write('debug', 2);
        
        $this->layout = false;

        //set required params
        $prodId = $_POST['ProdID'];
        $provider = $_POST['ProviderType'];

        /** creates log file name */
        $log_name = 'videos_stored_procedure_web_log_' . date('Y_m_d');
        $log_id = md5(time());
        $log_data = PHP_EOL . "----------Request (" . $log_id . ") Start----------------" . PHP_EOL;

        //on/off single channel functionality    
        $Setting = $this->Siteconfig->find('first', array('conditions' => array('soption' => 'single_channel')));
        $checkValidation = $Setting['Siteconfig']['svalue'];
        if ($checkValidation == 1)
        {

            // calls Downloadsvideos component for validation
            $validationResult = $this->Downloadsvideos->validateDownloadVideos($prodId, $provider);
            /** records download component request & response */
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
        /* echo $prodId;
          echo "<br />";
          echo $provider;
          echo "<br />";
          print_r($validationResult); die; */
        // sets user id
        $user = $this->Session->read('Auth.User.id');
        if (empty($user))
        {
            $user = $this->Session->read('patron');
        }

        // executes IF for valid request
        if ($validationPassed == true)
        {

            // logs in downloadvideos.log
            $this->log("Validation Checked : " . $checked . " Valdition Passed : " . $validationPassedMessage . " Validation Message : " . $validationMessage . " for ProdID :" . $prodId . " and Provider : " . $provider . " for library id : " . $this->Session->read('library') . " and user id : " . $user, 'downloadvideos');

            //set required params
            $libId = $this->Session->read('library');
            $patId = $this->Session->read('patron');
            $prodId = $_POST['ProdID'];
            $provider = $_POST['ProviderType'];

            //redirects user to home on null ProdID
            if ($prodId == '' || $prodId == 0)
            {
                $this->redirect(array('controller' => 'homes', 'action' => 'index'));
            }
            //get video data  
            $trackDetails = $this->Video->getVideoData($prodId, $provider);

            //collects video data  
            $insertArr = Array();
            $insertArr['library_id'] = $libId;
            $insertArr['patron_id'] = $patId;
            $insertArr['ProdID'] = $prodId;
            $insertArr['artist'] = addslashes($trackDetails['0']['Video']['Artist']);
            $insertArr['track_title'] = addslashes($trackDetails['0']['Video']['VideoTitle']);
            $insertArr['provider_type'] = $trackDetails['0']['Video']['provider_type'];
            $insertArr['ProductID'] = $trackDetails['0']['Video']['ProductID'];
            $insertArr['ISRC'] = $trackDetails['0']['Video']['ISRC'];

            // creates download url            
            $videoUrl = $this->Token->regularToken($trackDetails['0']['Full_Files']['CdnPath'] . "/" . $trackDetails['0']['Full_Files']['SaveAsName']);
            $finalVideoUrl = Configure::read('App.Music_Path') . $videoUrl;

            //collects video data 
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
                $this->LatestVideodownload->setDataSource('default');
            }
            // logs data
            $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------";

            //writes in log
            $this->log($log_data, $log_name);
            
            $this->Library->setDataSource('default');           
           
            //updating session for VideoDown load status
            $this->Common->getVideodownloadStatus( $libId, $patId, Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate') , true );
            
            if (is_numeric($return))
            {
                header("Content-Type: application/force-download");
                header("Content-Disposition: attachment; filename=\"" . basename($trackDetails['0']['Full_Files']['SaveAsName']) . "\";");
                header("Location: " . $finalVideoUrl);
                exit;
            }//succcess
            else
            {
                if ($return == 'incld')
                {
                    $this->Session->setFlash("You have already downloaded this Videos. Get it from your recent downloads.");
                    $this->redirect(array('controller' => 'homes', 'action' => 'my_history'));
                }
                else
                {
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit;
                }
            }//fail
        } //executes IF for valid request
        else
        {

            /** complete records with validation fail */
            $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------" . PHP_EOL;
            $this->log($log_data, $log_name);

            $this->Session->setFlash($validationResult[1]);
            $this->redirect(array('controller' => 'homes', 'action' => 'index'));
        }// executes ELSE for vinalid request
    }

    function my_lib_top_10_videos()
    {
        $libId = $this->Session->read('library');
        $patId = $this->Session->read('patron');
        $country = $this->Session->read('territory');
        $advisory_status = '';
        //get Advisory condition
        $advisory_status = $this->getLibraryExplicitStatus($libId);
        $condition = array('library_id' => $libId, 'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate')));

        $ids_provider_type_video = '';
        // Cache::delete("lib_videos".$libId);
        if (($libDownload = Cache::read("lib_videos" . $libId)) === false)
        {
            $SiteMaintainLDT = $this->Siteconfig->find('first', array('conditions' => array('soption' => 'maintain_ldt')));

            if ($SiteMaintainLDT['Siteconfig']['svalue'] == 1)
            {
                $topDownloaded_videos = $this->LatestVideodownload->find('all', array('conditions' => $condition, 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct', 'provider_type'), 'order' => 'countProduct DESC', 'limit' => '15'));
            }
            else
            {
                $topDownloaded_videos = $this->Videodownload->find('all', array('conditions' => $condition, 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct', 'provider_type'), 'order' => 'countProduct DESC', 'limit' => '15'));
            }

            $ids = '';
            $ioda_ids = array();
            $sony_ids = array();
            $sony_ids_str = '';
            $ioda_ids_str = '';

//			$topDownloaded_videos = Cache::read("lib".$libId); 
            foreach ($topDownloaded_videos as $k => $v)
            {
                if ($SiteMaintainLDT['Siteconfig']['svalue'] == 1)
                {
                    if (empty($ids))
                    {
                        $ids .= $v['LatestVideodownload']['ProdID'];
                        $ids_provider_type_video .= "(" . $v['LatestVideodownload']['ProdID'] . ",'" . $v['LatestVideodownload']['provider_type'] . "')";
                    }
                    else
                    {
                        $ids .= ',' . $v['LatestVideodownload']['ProdID'];
                        $ids_provider_type_video .= ',' . "(" . $v['LatestVideodownload']['ProdID'] . ",'" . $v['LatestVideodownload']['provider_type'] . "')";
                    }
                    if ($v['LatestVideodownload']['provider_type'] == 'sony')
                    {
                        $sony_ids[] = $v['LatestVideodownload']['ProdID'];
                    }
                    else
                    {
                        $ioda_ids[] = $v['LatestVideodownload']['ProdID'];
                    }
                }
                else
                {
                    if (empty($ids))
                    {
                        $ids .= $v['Download']['ProdID'];
                        $ids_provider_type_video .= "(" . $v['Videodownload']['ProdID'] . ",'" . $v['Videodownload']['provider_type'] . "')";
                    }
                    else
                    {
                        $ids .= ',' . $v['Download']['ProdID'];
                        $ids_provider_type_video .= ',' . "(" . $v['Videodownload']['ProdID'] . ",'" . $v['Videodownload']['provider_type'] . "')";
                    }
                    if ($v['Download']['provider_type'] == 'sony')
                    {
                        $sony_ids[] = $v['Videodownload']['ProdID'];
                    }
                    else
                    {
                        $ioda_ids[] = $v['Videodownload']['ProdID'];
                    }
                }
            }


            if ($ids != '')
            {
                if (!empty($sony_ids))
                {
                    $sony_ids_str = implode(',', $sony_ids);
                }
                if (!empty($ioda_ids))
                {
                    $ioda_ids_str = implode(',', $ioda_ids);
                }
                if (!empty($sony_ids_str) && !empty($ioda_ids_str))
                {
                    $top_ten_condition_videos = "((Video.ProdID IN (" . $sony_ids_str . ") AND Video.provider_type='sony') OR (Video.ProdID IN (" . $ioda_ids_str . ") AND Video.provider_type='ioda'))";
                }
                else if (!empty($sony_ids_str))
                {
                    $top_ten_condition_videos = "(Video.ProdID IN (" . $sony_ids_str . ") AND Video.provider_type='sony')";
                }
                else if (!empty($ioda_ids_str))
                {
                    $top_ten_condition_videos = "(Video.ProdID IN (" . $ioda_ids_str . ") AND Video.provider_type='ioda')";
                }


                $this->Video->recursive = 2;
                $countryPrefix = $this->Session->read('multiple_countries');

                //  videos                             

                $topDownloaded_query_videos = <<<STR
				SELECT 
					Video.ProdID,
					Video.ReferenceID,
					Video.Title,
					Video.ArtistText,
					Video.DownloadStatus,
					Video.VideoTitle,
					Video.Artist,
					Video.Advisory,
					Video.Sample_Duration,
					Video.FullLength_Duration,
					Video.provider_type,
					Genre.Genre,
					Country.Territory,
					Country.SalesDate,
					Sample_Files.CdnPath,
					Sample_Files.SaveAsName,
					Full_Files.CdnPath,
                                        Full_Files.SaveAsName,
                                        File.CdnPath,
                                        File.SourceURL,
                                        File.SaveAsName,
                                        Sample_Files.FileID
				FROM video AS Video
				LEFT JOIN File AS Sample_Files ON (Video.Sample_FileID = Sample_Files.FileID)
				LEFT JOIN File AS Full_Files ON (Video.FullLength_FileID = Full_Files.FileID)
				LEFT JOIN Genre AS Genre ON (Genre.ProdID = Video.ProdID)
				LEFT JOIN {$countryPrefix}countries AS Country ON (Country.ProdID = Video.ProdID) AND (Video.provider_type = Country.provider_type)
                                INNER JOIN File ON (Video.Image_FileID = File.FileID)
				WHERE((Video.DownloadStatus = '1') AND ($top_ten_condition_videos) AND (Country.Territory = '$country') AND Country.SalesDate != '' AND Country.SalesDate < NOW() AND 1 = 1)
				GROUP BY Video.ProdID
				ORDER BY FIELD(Video.ProdID, $ids) ASC
				LIMIT 10
STR;
                $topDownload_video = $this->Video->query($topDownloaded_query_videos);

                foreach ($topDownload_video as $key => $value)
                {                    
                    $albumArtwork = $this->Token->artworkToken($value['File']['CdnPath'] . "/" . $value['File']['SourceURL']);
                    $videoAlbumImage = Configure::read('App.Music_Path') . $albumArtwork;
                    $topDownload_video[$key]['videoAlbumImage'] = $videoAlbumImage;
                }
            }
            else
            {
                $topDownload_video = array();
            }

            Cache::write("lib_video" . $libId, $topDownload_video);
        }
        else
        {
            $topDownload_video = Cache::read("lib_video" . $libId);
        }

        return $topDownload_video;
    }

    function details()
    {
        //Configure::write('default' , 0);
        
        $this->layout = 'home';
        $libId = $this->Session->read('library');
        $patId = $this->Session->read('patron');
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
        $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);

        $this->set('libraryDownload', $libraryDownload);
        $this->set('patronDownload', $patronDownload);


        $territory = $this->Session->read('territory');

        $prefix = strtolower($territory) . "_";


        //  Video Details   //
        // Cache::delete("musicVideoDetails" . $this->params['pass'][0]);
        if (isset($this->params['pass'][0]))
        {

            $prefix = strtolower($this->Session->read('territory')) . '_';
            $VideosSql =
                    "SELECT 
                            Video.ProdID,
                            Video.Advisory, 
                            Video.ReferenceID,  
                            Video.VideoTitle, 
                            Video.ArtistText, 
                            Video.FullLength_Duration, 
                            Video.CreatedOn, 
                            Video.Image_FileID, 
                            Video.provider_type, 
                            Video.Genre,  
                            Sample_Files.CdnPath,
                            Sample_Files.SaveAsName,
                            Full_Files.CdnPath,
                            Full_Files.SaveAsName,
                            File.CdnPath,
                            File.SourceURL,
                            File.SaveAsName,
                            Sample_Files.FileID,
                            Country.Territory,
                            Country.SalesDate
            FROM video as Video
            LEFT JOIN 
            {$prefix}countries As Country ON (Video.ProdID = Country.ProdID AND Video.provider_type = Country.provider_type)
            LEFT JOIN
            File AS Sample_Files ON (Video.Sample_FileID = Sample_Files.FileID)
            LEFT JOIN
            File AS Full_Files ON (Video.FullLength_FileID = Full_Files.FileID)                                 
            LEFT JOIN
            PRODUCT ON (PRODUCT.ProdID = Video.ProdID)  AND (PRODUCT.provider_type = Video.provider_type)
            INNER JOIN File ON (Video.Image_FileID = File.FileID)
            Where Video.DownloadStatus = '1' AND Video.ProdID = " . $this->params[pass][0];

            $VideosData = $this->Album->query($VideosSql);
            
            $videoArtwork = $this->Token->artworkToken($VideosData[0]['File']['CdnPath'] . "/" . $VideosData[0]['File']['SourceURL']);
            $VideosData[0]['videoImage'] = Configure::read('App.Music_Path') . $videoArtwork;


//            if ($VideosData = Cache::read("musicVideoDetails" . $this->params['pass'][0]) === false) {
//                $prefix = strtolower($this->Session->read('territory')).'_';  
//                $VideosSql  =
//                "SELECT Video.ProdID,Video.Advisory, Video.ReferenceID,  Video.VideoTitle, Video.ArtistText, Video.FullLength_Duration, Video.CreatedOn, Video.Image_FileID, Video.provider_type, Video.Genre,  Sample_Files.CdnPath,
//                Sample_Files.SaveAsName,
//                Full_Files.CdnPath,
//                Full_Files.SaveAsName,
//                File.CdnPath,
//                File.SourceURL,
//                File.SaveAsName,
//                Sample_Files.FileID,
//                Country.Territory,
//                Country.SalesDate
//                FROM video as Video
//                LEFT JOIN 
//                {$prefix}countries As Country ON (Video.ProdID = Country.ProdID AND Video.provider_type = Country.provider_type)
//                LEFT JOIN
//                File AS Sample_Files ON (Video.Sample_FileID = Sample_Files.FileID)
//                LEFT JOIN
//                File AS Full_Files ON (Video.FullLength_FileID = Full_Files.FileID)                                 
//                LEFT JOIN
//                PRODUCT ON (PRODUCT.ProdID = Video.ProdID)  AND (PRODUCT.provider_type = Video.provider_type)
//                INNER JOIN File ON (Video.Image_FileID = File.FileID)
//                Where Video.DownloadStatus = '1' AND Video.ProdID = ".$this->params[pass][0];
//
//                $VideosData = $this->Album->query($VideosSql);
//                $videoArtwork = shell_exec('perl files/tokengen_artwork ' .$VideosData[0]['File']['CdnPath']."/".$VideosData[0]['File']['SourceURL']);
//                $VideosData[0]['videoImage'] = Configure::read('App.Music_Path').$videoArtwork;
//                //echo "<pre>"; print_r($VideosData); die;
//
//                if (!empty($VideosData)) {
//                    Cache::write("musicVideoDetails" . $this->params['pass'][0], $VideosData);
//                }
//            }
        }
        else
        {
            $VideosData = array();
        }

        // $VideosData = Cache::read("musicVideoDetails".$this->params['pass'][0]);

        $this->set('VideosData', $VideosData);


        //  More Videos By Artist            
        $MoreVideosData = array();
        if (count($VideosData) > 0)
        {

            $country = $territory;

            $decodedId = trim($VideosData[0]['Video']['ArtistText']);
            $decodedId = str_replace('@', '/', $decodedId);
            if (!empty($country))
            {

                //if ( ((Cache::read("videolist_".$country."_".$decodedId)) === false)  || (Cache::read("videolist_".$country."_".$decodedId)=== null) ) { 
                $MoreVideosData = $this->Common->getAllVideoByArtist($country, $decodedId);
                Cache::write("videolist_" . $country . "_" . $decodedId, $MoreVideosData);
                // }else{
                $MoreVideosData = Cache::read("videolist_" . $country . "_" . $decodedId);
                // }
            }
            else
            {
                $MoreVideosData = Cache::read("videolist_" . $country . "_" . $decodedId);
            }






//                if ($MoreVideosData = Cache::read("musicVideoMoreDetails_" .$territory.'_'.$VideosData[0]['Video']['ArtistText']) === false) {
//                   $MoreVideosSql  =
//                    "SELECT Video.ProdID,
//                    Video.ReferenceID,
//                    Video.Advisory,
//                    Video.VideoTitle,
//                    Video.ArtistText,
//                    Video.FullLength_Duration,
//                    Video.CreatedOn,
//                    Video.Image_FileID,
//                    Video.provider_type,
//                    Sample_Files.CdnPath,
//                    Sample_Files.SaveAsName,
//                    Full_Files.CdnPath,
//                    Full_Files.SaveAsName,
//                    File.CdnPath,
//                    File.SourceURL,
//                    File.SaveAsName,
//                    Sample_Files.FileID,
//                    Country.Territory,
//                    Country.SalesDate
//                    FROM video as Video
//                    LEFT JOIN
//                    File AS Sample_Files ON (Video.Sample_FileID = Sample_Files.FileID)
//                    LEFT JOIN
//                    File AS Full_Files ON (Video.FullLength_FileID = Full_Files.FileID)   
//                    LEFT JOIN
//                    {$prefix}countries AS Country ON (Country.ProdID = Video.ProdID) AND (Country.Territory = '$territory') AND (Video.provider_type = Country.provider_type)
//                    LEFT JOIN
//                    PRODUCT ON (PRODUCT.ProdID = Video.ProdID)
//                    INNER JOIN File ON (Video.Image_FileID = File.FileID)
//                    Where Video.DownloadStatus = '1' AND PRODUCT.provider_type = Video.provider_type  AND Video.ArtistText = '".$VideosData[0]['Video']['ArtistText']."'   ORDER BY Country.SalesDate desc limit 0,10";
//
//                    $MoreVideosData = $this->Album->query($MoreVideosSql);
//                    foreach($MoreVideosData as $key => $value)
//                    {		
//                        $videoArtwork = shell_exec('perl files/tokengen_artwork ' .$value['File']['CdnPath']."/".$value['File']['SourceURL']);
//                        $videoImage = Configure::read('App.Music_Path').$videoArtwork;
//                        $MoreVideosData[$key]['videoImage'] = $videoImage;
//                    }
//                    if (!empty($MoreVideosData)) {
//                        Cache::write("musicVideoMoreDetails_" .$territory.'_'.$VideosData[0]['Video']['ArtistText'], $MoreVideosData);
//                    }                    
//                    
//                    // echo "<pre>"; print_r($MoreVideosData); die;
//                }else{
//                    $MoreVideosData = Cache::read("musicVideoMoreDetails_" .$territory.'_'.$VideosData[0]['Video']['ArtistText']);                    
//                }   
        }
        else
        {
            $MoreVideosData = array();
        }

        $this->set('MoreVideosData', $MoreVideosData);

        // Cache::delete("top_videos_genre_" . $territory.'_'.$VideosData[0]['Video']['Genre']);
        //Top Genre Videos By Artist 
        //if ($topDownloads = Cache::read("top_videos_genre" . $territory) === false)

        if (count($VideosData) > 0)
        {
            
            if($prefix === '_'){
                $this->log("Empty prefix:".$prefix." in getComingSoonSongs for : ".$territory, "cache");
                die;
            }
            
            
            if ($TopVideoGenreData = Cache::read("top_videos_genre_" . $territory . '_' . $VideosData[0]['Video']['Genre']) === false)
            {
                $TopVideoGenreSql = "SELECT Videodownloads.ProdID, Video.ProdID,Video.Advisory, Video.ReferenceID, Video.provider_type, Video.VideoTitle, Video.Genre, Video.ArtistText, File.CdnPath, File.SourceURL,  COUNT(DISTINCT(Videodownloads.id)) AS COUNT,
                            `Country`.`SalesDate` FROM videodownloads as Videodownloads LEFT JOIN video as Video ON (Videodownloads.ProdID = Video.ProdID AND Videodownloads.provider_type = Video.provider_type) 
                            LEFT JOIN File as File ON (Video.Image_FileID = File.FileID) LEFT JOIN Genre AS Genre ON (Genre.ProdID = Video.ProdID) LEFT JOIN {$prefix}countries as Country on (`Video`.`ProdID`=`Country`.`ProdID` AND `Video`.`provider_type`=`Country`.`provider_type`)
                            LEFT JOIN libraries as Library ON Library.id=Videodownloads.library_id 
                            WHERE library_id=1 AND Library.library_territory='" . $territory . "' AND `Country`.`SalesDate` <= NOW() AND Video.Genre = '" . addslashes($VideosData[0]['Video']['Genre']) . "' AND (Video.provider_type = Genre.provider_type)  GROUP BY Videodownloads.ProdID ORDER BY COUNT DESC limit 0,10";

                $TopVideoGenreData = $this->Album->query($TopVideoGenreSql);
                foreach ($TopVideoGenreData as $key => $value)
                {                    
                    $videoArtwork = $this->Token->artworkToken($value['File']['CdnPath'] . "/" . $value['File']['SourceURL']);
                    $videoImage = Configure::read('App.Music_Path') . $videoArtwork;
                    $TopVideoGenreData[$key]['videoImage'] = $videoImage;
                }
                Cache::write("top_videos_genre_" . $territory . '_' . $VideosData[0]['Video']['Genre'], $TopVideoGenreData);
            }
            else
            {
                $TopVideoGenreData = Cache::read("top_videos_genre_" . $territory . '_' . $VideosData[0]['Video']['Genre']);
            }
            //echo "<pre>"; print_r($TopVideoGenreData); die;
        }
        else
        {
            $TopVideoGenreData = array();
        }
        $this->set('TopVideoGenreData', $TopVideoGenreData);
        $this->set('VideoGenre', $VideosData[0]['Video']['Genre']);
    }

}
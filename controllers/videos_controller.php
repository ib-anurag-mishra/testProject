<?php

class VideosController extends AppController {

     var $uses = array('Album', 'Genre', 'Siteconfig','Country', 'Video', 'LatestVideodownload', 'Videodownload', 'Session');
    var $components = array('Downloadsvideos', 'Session');
    var $layout = 'home';
   
    
    /*
    Function Name : beforeFilter
    Desc : actions that needed before other functions are getting called
   */
	function beforeFilter(){
		/*parent::beforeFilter();
		$this->Cookie->name = 'baker_id';
		$this->Cookie->time = 3600; // or '1 hour'
		$this->Cookie->path = '/';
		$this->Cookie->domain = 'freegalmusic.com';
		//$this->Cookie->key = 'qSI232qs*&sXOw!';*/
	}
    
    function index() {
        $prefix = strtolower($this->Session->read('territory'))."_";
        
        $featuredVideos = array();
        $topDownloads = array();
        
        if ($featuredVideos = Cache::read("featured_videos" . $this->Session->read('territory')) === false) {
            $featuredVideosSql = "SELECT `FeaturedVideo`.`id`,`FeaturedVideo`.`ProdID`,`Video`.`Image_FileID`, `Video`.`VideoTitle`, `Video`.`ArtistText`, `Video`.`provider_type`, `File`.`CdnPath`, `File`.`SourceURL`, `File`.`SaveAsName`,`Country`.`SalesDate` FROM featured_videos as FeaturedVideo LEFT JOIN video as Video on FeaturedVideo.ProdID = Video.ProdID LEFT JOIN File as File on File.FileID = Video.Image_FileID LEFT JOIN {$prefix}countries as Country on (`Video`.`ProdID`=`Country`.`ProdID` AND `Video`.`provider_type`=`Country`.`provider_type`) WHERE `FeaturedVideo`.`territory` = '" . $this->Session->read('territory') . "' AND `Country`.`SalesDate` <= NOW()";
            $featuredVideos = $this->Album->query($featuredVideosSql);
            if (!empty($featuredVideos)) {
                Cache::write("featured_videos" . $this->Session->read('territory'), $featuredVideos);
            }
        }

        

        if ($topDownloads = Cache::read("top_download_videos" . $this->Session->read('territory')) === false) {
            $topDownloadSQL = "SELECT Videodownloads.ProdID, Video.VideoTitle, Video.ArtistText, File.CdnPath, File.SourceURL, COUNT(DISTINCT(Videodownloads.id)) AS COUNT, `Country`.`SalesDate` FROM videodownloads as Videodownloads LEFT JOIN video as Video ON (Videodownloads.ProdID = Video.ProdID AND Videodownloads.provider_type = Video.provider_type) LEFT JOIN File as File ON (Video.Image_FileID = File.FileID) LEFT JOIN {$prefix}countries as Country on (`Video`.`ProdID`=`Country`.`ProdID` AND `Video`.`provider_type`=`Country`.`provider_type`) LEFT JOIN libraries as Library ON Library.id=Videodownloads.library_id WHERE library_id=1 AND Library.library_territory='" . $this->Session->read('territory') . "' AND `Country`.`SalesDate` <= NOW() GROUP BY Videodownloads.ProdID ORDER BY COUNT DESC";
            $topDownloads = $this->Album->query($topDownloadSQL);
            if (!empty($topDownloads)) {
                Cache::write("top_download_videos" . $this->Session->read('territory'), $topDownloads);
            }
        }

        $featuredVideos = Cache::read("featured_videos" . $this->Session->read('territory'));

        $topDownloads = Cache::read("top_download_videos" . $this->Session->read('territory'));

        $this->set('featuredVideos', $featuredVideos);

        $this->set('topVideoDownloads', $topDownloads);
    }

    function download() {

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
        if ($checkValidation == 1) {
            $validationResult = $this->Downloadsvideos->validateDownloadVideos($prodId, $provider);
            /** records download component request & response */
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
            $libId = $this->Session->read('library');
            $patId = $this->Session->read('patron');
            $prodId = $_POST['ProdID'];
            $provider = $_POST['ProviderType'];

            //redirects user to home on null ProdID
            if ($prodId == '' || $prodId == 0) {
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
            $videoUrl = shell_exec('perl files/tokengen ' . "sony_test/".$trackDetails['0']['Full_Files']['CdnPath'] . "/" . $trackDetails['0']['Full_Files']['SaveAsName']);
            $finalVideoUrl = Configure::read('App.Music_Path') . $videoUrl;

            //collects video data 
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

            //on/off latest-download functionality
            $this->Library->setDataSource('master');
            $siteConfigSQL = "SELECT * from siteconfigs WHERE soption = 'maintain_ldt'";
            $siteConfigData = $this->Album->query($siteConfigSQL);
            $maintainLatestDownload = (($siteConfigData[0]['siteconfigs']['svalue'] == 1) ? true : false);

            if ($maintainLatestDownload) {
                //logs in downloadvideos.log
                $this->log("videos_sony_proc called", 'downloadvideos');
                $procedure = 'videos_sony_proc';
                //calls procedure
                $sql = "CALL videos_sony_proc('" . $libId . "','" . $patId . "', '" . $prodId . "', '" . $trackDetails['0']['Video']['ProductID'] . "', '" . $trackDetails['0']['Video']['ISRC'] . "', '" . addslashes($trackDetails['0']['Video']['Artist']) . "', '" . addslashes($trackDetails['0']['Video']['VideoTitle']) . "', '" . $insertArr['user_login_type'] . "', '" . $insertArr['provider_type'] . "', '" . $insertArr['email'] . "', '" . addslashes($insertArr['user_agent']) . "', '" . $insertArr['ip'] . "', '" . Configure::read('App.curWeekStartDate') . "', '" . Configure::read('App.curWeekEndDate') . "',@ret)";
            }

            //get procedure response
            $this->Library->query($sql);
            $sql = "SELECT @ret";
            $data = $this->Library->query($sql);
            $return = $data[0][0]['@ret'];

            //logs in downloadvideos.log
            $log_data .= ":StoredProcedureParameters-LibID='" . $libId . "':StoredProcedureParameters-Patron='" . $patId . "':StoredProcedureParameters-ProdID='" . $prodId . "':StoredProcedureParameters-ProductID='" . $trackDetails['0']['Video']['ProductID'] . "':StoredProcedureParameters-ISRC='" . $trackDetails['0']['Video']['ISRC'] . "':StoredProcedureParameters-Artist='" . addslashes($trackDetails['0']['Video']['Artist']) . "':StoredProcedureParameters-SongTitle='" . addslashes($trackDetails['0']['Video']['VideoTitle']) . "':StoredProcedureParameters-UserLoginType='" . $insertArr['user_login_type'] . "':StoredProcedureParameters-ProviderType='" . $insertArr['provider_type'] . "':StoredProcedureParameters-Email='" . $insertArr['email'] . "':StoredProcedureParameters-UserAgent='" . addslashes($insertArr['user_agent']) . "':StoredProcedureParameters-IP='" . $insertArr['ip'] . "':StoredProcedureParameters-CurWeekStartDate='" . Configure::read('App.curWeekStartDate') . "':StoredProcedureParameters-CurWeekEndDate='" . Configure::read('App.curWeekEndDate') . "':StoredProcedureParameters-Name='" . $procedure . "':StoredProcedureParameters-@ret='" . $return . "'";

            //executes IF on success
            if (is_numeric($return)) {

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
            if (is_numeric($return)) {
                header("Location: " . $finalVideoUrl);
                exit;
            }//succcess
            else {
                if ($return == 'incld') {
                    $this->Session->setFlash("You have already downloaded this Videos. Get it from your recent downloads.");
                    $this->redirect(array('controller' => 'homes', 'action' => 'my_history'));
                } else {
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit;
                }
            }//fail
        } //executes IF for valid request
        else {

            /** complete records with validation fail */
            $log_data .= PHP_EOL . "---------Request (" . $log_id . ") End----------------" . PHP_EOL;
            $this->log($log_data, $log_name);

            $this->Session->setFlash($validationResult[1]);
            $this->redirect(array('controller' => 'homes', 'action' => 'index'));
        }// executes ELSE for vinalid request
    }
    
    
     function my_lib_top_10_videos()
    {        
        
                $libId  =1;
                $patId= 8389;
                $country=   'us';
        
                    $ids_provider_type_video = '';
                    //if (($libDownload = Cache::read("lib_videos".$libId)) === false)
                    if(1)
                    {
			$SiteMaintainLDT = $this->Siteconfig->find('first',array('conditions'=>array('soption'=>'maintain_ldt')));
                        
                        if($SiteMaintainLDT['Siteconfig']['svalue'] == 1){
                            $topDownloaded_videos = $this->LatestVideodownload->find('all', array('conditions' => array('library_id' => $libId,'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct', 'provider_type'), 'order' => 'countProduct DESC', 'limit'=> '15'));                                                        
                        } else {
                            $topDownloaded_videos = $this->Videodownload->find('all', array('conditions' => array('library_id' => $libId,'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct', 'provider_type'), 'order' => 'countProduct DESC', 'limit'=> '15'));
                        }
                        
//                        echo "<pre>11";
//                        print_r($topDownloaded_videos);
//                        die;
                                               
			$ids = '';
			$ioda_ids = array();
			$sony_ids = array();
			$sony_ids_str = '';
			$ioda_ids_str = ''; 
                                                

                        
//			$topDownloaded_videos = Cache::read("lib".$libId); 
			foreach($topDownloaded_videos as $k => $v){
			if($SiteMaintainLDT['Siteconfig']['svalue'] == 1){
			   	if(empty($ids)){
				  $ids .= $v['LatestVideodownload']['ProdID'];
				  $ids_provider_type_video .= "(" . $v['LatestVideodownload']['ProdID'] .",'" . $v['LatestVideodownload']['provider_type'] ."')";
				} else {
				  $ids .= ','.$v['LatestVideodownload']['ProdID'];
				  $ids_provider_type_video .= ','. "(" . $v['LatestVideodownload']['ProdID'] .",'" . $v['LatestVideodownload']['provider_type'] ."')";
				}
				if($v['LatestVideodownload']['provider_type'] == 'sony'){
				  $sony_ids[] = $v['LatestVideodownload']['ProdID'];
				} else {
				  $ioda_ids[] = $v['LatestVideodownload']['ProdID'];
				}
			} else {
				if(empty($ids)){
				  $ids .= $v['Download']['ProdID'];
				  $ids_provider_type_video .= "(" . $v['Videodownload']['ProdID'] .",'" . $v['Videodownload']['provider_type'] ."')";
				} else {
				  $ids .= ','.$v['Download']['ProdID'];
				   $ids_provider_type_video .= ','. "(" . $v['Videodownload']['ProdID'] .",'" . $v['Videodownload']['provider_type'] ."')";
				}
				if($v['Download']['provider_type'] == 'sony'){
				  $sony_ids[] = $v['Videodownload']['ProdID'];
				} else {
				  $ioda_ids[] = $v['Videodownload']['ProdID'];
				}
			  }				
			}

//                        echo "<pre>22";
//                        print_r($topDownloaded_videos);
//                        die;                    
                        
			if($ids != ''){ 
				if(!empty($sony_ids)){
					$sony_ids_str = implode(',',$sony_ids);
				}
				if(!empty($ioda_ids)){
					$ioda_ids_str = implode(',',$ioda_ids);
				}
				if(!empty($sony_ids_str) && !empty($ioda_ids_str)){
					$top_ten_condition_videos = "((Video.ProdID IN (".$sony_ids_str.") AND Video.provider_type='sony') OR (Video.ProdID IN (".$ioda_ids_str.") AND Video.provider_type='ioda'))";                                       
				} else if(!empty($sony_ids_str)){
					$top_ten_condition_videos = "(Video.ProdID IN (".$sony_ids_str.") AND Video.provider_type='sony')";                                        
                                        
                                } else if(!empty($ioda_ids_str)){
					$top_ten_condition_videos = "(Video.ProdID IN (".$ioda_ids_str.") AND Video.provider_type='ioda')";                                        
				}
				
				
				$this->Video->recursive = 2;
                                $countryPrefix = $this->Session->read('multiple_countries');
                                
                                //  Songs                             
                                
				$topDownloaded_query_videos =<<<STR
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
                                        Sample_Files.FileID,
					PRODUCT.pid
				FROM
					video AS Video
						LEFT JOIN
					File AS Sample_Files ON (Video.Sample_FileID = Sample_Files.FileID)
						LEFT JOIN
					File AS Full_Files ON (Video.FullLength_FileID = Full_Files.FileID)
						LEFT JOIN
					Genre AS Genre ON (Genre.ProdID = Video.ProdID)
						LEFT JOIN
                                 {$countryPrefix}countries AS Country ON (Country.ProdID = Video.ProdID) AND (Country.Territory = '$country') AND (Video.provider_type = Country.provider_type)
						LEFT JOIN
					PRODUCT ON (PRODUCT.ProdID = Video.ProdID)  INNER JOIN File ON (Video.Image_FileID = File.FileID)
				WHERE
					((Video.DownloadStatus = '1') AND (($top_ten_condition_videos) AND (Video.provider_type = Genre.provider_type) AND (PRODUCT.provider_type = Video.provider_type)) AND (Country.Territory = '$country') AND Country.SalesDate != '' AND Country.SalesDate < NOW() AND 1 = 1)
				GROUP BY Video.ProdID
				ORDER BY FIELD(Video.ProdID,
						$ids) ASC
				LIMIT 10
STR;
                               
                                 
                                // echo "Query: ".$topDownloaded_query_videos;
                                 
                            $topDownload_video = $this->Video->query($topDownloaded_query_videos);
//                            echo "<pre>";
//                            print_r($topDownload_video);
//                            die;
                            
			
			} else { 
				$topDownload_video = array();                               
			}

			Cache::write("lib_video".$libId, $topDownload_video);
		}
                else
                { 
                    $topDownload_video = Cache::read("lib_video".$libId);
                }
		
                return $topDownload_video;
        
        
        
    }

}
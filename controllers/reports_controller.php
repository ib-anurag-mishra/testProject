<?php
/*
 File Name : reports_controller.php
 File Description : Report controller page
 Author : maycreate
 */
ini_set('memory_limit', '1024M');
Class ReportsController extends AppController
{
    var $name = 'Reports';
    var $layout = 'admin';
    var $helpers = array( 'Html', 'Ajax', 'Javascript', 'Form', 'Session', 'Library', 'Csv');
    var $components = array( 'Session', 'Auth', 'Acl', 'RequestHandler' );
    var $uses = array( 'Library', 'User', 'Download', 'Report', 'SonyReport', 'Wishlist', 'Genre', 'Currentpatron','Consortium' );

	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('admin_consortium');
	}    
    /*
     Function Name : admin_index
     Desc : actions for library reports page
    */
    function admin_index() {
	//	print_r($this->data);exit;
        if($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") == '') {
            $libraryAdminID = $this->Library->find("first", array("conditions" => array('library_admin_id' => $this->Session->read("Auth.User.id")), 'fields' => array('id', 'library_name','library_territory'), 'recursive' => -1));
            $this->set('libraryID', $libraryAdminID["Library"]["id"]);
            $this->set('libraryname', $libraryAdminID["Library"]["library_name"]);
        }
        else {
			if($this->data['Report']['Territory'] == ''){
				//$this->set('libraries', $this->Library->find('list', array('fields' => array('Library.library_name'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
				$this->set('libraries', array());	
            } else {
				$this->set('libraries', $this->Library->find('list', array('fields' => array('Library.library_name'),'conditions' => array('Library.library_territory= "'.$this->data['Report']['Territory'].'"'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));			
			}
			$this->set('libraryID', "");
        }
        if(isset($this->data)) {
			//Configure::write('debug',0); // Otherwise we cannot use this method while developing 
			$all_Ids = '';
            $this->Report->set($this->data);
			if(isset($_REQUEST['library_id'])){
				$library_id = $_REQUEST['library_id'];
			}else{
				$library_id = $this->data['Report']['library_id'];
			}
			$this->set('library_id', $library_id);
			if($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") == ''){
				$territory = $libraryAdminID["Library"]["library_territory"];
			} else {
				$territory = $this->data['Report']['Territory'];				
			}
            if($this->data['Report']['reports_daterange'] != 'manual') {
                $this->Report->setValidation('reports_date');
            }
            else {
                $this->Report->setValidation('reports_manual');
            }
			if($territory != ''){
				if($library_id == 'all'){
					$sql = "SELECT id from libraries where library_territory = '".$territory."'";
					$result = mysql_query($sql);
					while ($row = mysql_fetch_assoc($result)) {
						$all_Ids = $all_Ids.$row["id"].",";
					}		  
					$lib_condition = "and library_id IN (".rtrim($all_Ids,",'").")";
					$this->set('libraries_download', $this->Library->find('all', array('fields' => array('Library.library_name','Library.library_unlimited','Library.library_available_downloads'),'conditions' => array('Library.id IN ('.rtrim($all_Ids,",").')'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
				}
				else{
					$this->set('libraries_download', $this->Library->find('all', array('fields' => array('Library.library_name','Library.library_unlimited','Library.library_available_downloads'),'conditions' => array('Library.id = '.$library_id,'Library.library_territory= "'.$territory.'"'),'order' => 'Library.library_name ASC', 'recursive' => -1)));			
				}
			}
            if($this->Report->validates()) {
                if($this->data['Report']['reports_daterange'] == 'day') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                }
                elseif($this->data['Report']['reports_daterange'] == 'week') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                }
                elseif($this->data['Report']['reports_daterange'] == 'month') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                }
                elseif($this->data['Report']['reports_daterange'] == 'year') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                }
                elseif($this->data['Report']['reports_daterange'] == 'manual') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                }
                
                $this->set('downloads', $downloads);
                $this->set('patronDownloads', $patronDownloads);
                $this->set('genreDownloads', $genreDownloads);
                $arr = array();
                $this->set('errors', $arr);
            }
            else {
                $this->Session->setFlash( 'Error occured while entering the Reports Setting fields', 'modal', array( 'class' => 'modal problem' ) );
                $arr = array();
                $this->set('downloads', $arr);
                $this->set('errors', $this->Report->invalidFields());
            }
            $this -> set( 'formAction', 'admin_index' );
            $this->set('getData', $this->data);
        }
        else {
            $this -> set( 'formAction', 'admin_index' );
			$this->set('library_id', '');
            $arr = array();
            $this->set('getData', $arr);
            $this->set('downloads', $arr);
            $this->set('errors', $arr);
        }
    }
    
    /*
     Function Name : admin_downloadAsCsv
     Desc : actions for library reports download as CSV page
    */
    function admin_downloadAsCsv() {
        Configure::write('debug', 0);
        $this->layout = false;
        if(isset($this->data)) {
			$all_Ids = '';		
            $this->Report->set($this->data);
			if(isset($_REQUEST['library_id'])){
				$library_id = $_REQUEST['library_id'];
			}else{
				$library_id = $this->data['Report']['library_id'];
			}
			$this->set('library_id', $library_id);
			if($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") == ''){
				$libraryAdminID = $this->Library->find("first", array("conditions" => array('library_admin_id' => $this->Session->read("Auth.User.id")), 'fields' => array('id', 'library_name','library_territory'), 'recursive' => -1));
				$territory = $libraryAdminID["Library"]["library_territory"];
			} else {
				$territory = $this->data['Report']['Territory'];
			}
            if($this->data['Report']['reports_daterange'] != 'manual') {
                $this->Report->setValidation('reports_date');
            }
            else {
                $this->Report->setValidation('reports_manual');
            }
			if($territory != ''){
				if($library_id == 'all'){
					$sql = "SELECT id from libraries where library_territory = '".$territory."'";
					$result = mysql_query($sql);
					while ($row = mysql_fetch_assoc($result)) {
						$all_Ids = $all_Ids.$row["id"].",";
					}		  
					$lib_condition = "and library_id IN (".rtrim($all_Ids,",'").")";
					$this->set('libraries_download', $this->Library->find('all', array('fields' => array('Library.library_name','Library.library_unlimited','Library.library_available_downloads'),'conditions' => array('Library.id IN ('.rtrim($all_Ids,",").')'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
				}
				else{
					$this->set('libraries_download', $this->Library->find('all', array('fields' => array('Library.library_name','Library.library_unlimited','Library.library_available_downloads'),'conditions' => array('Library.id = '.$library_id,'Library.library_territory= "'.$territory.'"'),'order' => 'Library.library_name ASC', 'recursive' => -1)));			
				}
			}			
            if($this->Report->validates()) {
                if($this->data['Report']['reports_daterange'] == 'day') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                }
                elseif($this->data['Report']['reports_daterange'] == 'week') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                }
                elseif($this->data['Report']['reports_daterange'] == 'month') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                }
                elseif($this->data['Report']['reports_daterange'] == 'year') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                }
                elseif($this->data['Report']['reports_daterange'] == 'manual') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                }
                $this->set('downloads', $downloads);
                $this->set('patronDownloads', $patronDownloads);
                $this->set('genreDownloads', $genreDownloads);
            }
            else {
                $this->Session->setFlash( 'Error occured while entering the Reports Setting fields', 'modal', array( 'class' => 'modal problem' ) );
                $this->redirect(array('action'=>'index'), null, true);
            }
        }
        else {
            $this->Session->setFlash( 'Error occured while entering the Reports Setting fields', 'modal', array( 'class' => 'modal problem' ) );
            $this->redirect(array('action'=>'index'), null, true);
        }
    }
    
    /*
     Function Name : admin_downloadAsPdf
     Desc : actions for library reports download as PDF page
    */
    function admin_downloadAsPdf() {
        Configure::write('debug',0); // Otherwise we cannot use this method while developing 
        if(isset($this->data)) {
			$all_Ids = '';
            $this->Report->set($this->data);
			if(isset($_REQUEST['library_id'])){
				$library_id = $_REQUEST['library_id'];
			}else{
				$library_id = $this->data['Report']['library_id'];
			}
			$this->set('library_id', $library_id);
			if($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") == ''){
				$libraryAdminID = $this->Library->find("first", array("conditions" => array('library_admin_id' => $this->Session->read("Auth.User.id")), 'fields' => array('id', 'library_name','library_territory'), 'recursive' => -1));
				$territory = $libraryAdminID["Library"]["library_territory"];
			} else {
				$territory = $this->data['Report']['Territory'];
			}
            if($this->data['Report']['reports_daterange'] != 'manual') {
                $this->Report->setValidation('reports_date');
            }
            else {
                $this->Report->setValidation('reports_manual');
            }
			if($territory != ''){
				if($library_id == 'all'){
					$sql = "SELECT id from libraries where library_territory = '".$territory."'";
					$result = mysql_query($sql);
					while ($row = mysql_fetch_assoc($result)) {
						$all_Ids = $all_Ids.$row["id"].",";
					}		  
					$lib_condition = "and library_id IN (".rtrim($all_Ids,",'").")";
					$this->set('libraries_download', $this->Library->find('all', array('fields' => array('Library.library_name','Library.library_unlimited','Library.library_available_downloads'),'conditions' => array('Library.id IN ('.rtrim($all_Ids,",").')'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
				}
				else{
					$this->set('libraries_download', $this->Library->find('all', array('fields' => array('Library.library_name','Library.library_unlimited','Library.library_available_downloads'),'conditions' => array('Library.id = '.$library_id,'Library.library_territory= "'.$territory.'"'),'order' => 'Library.library_name ASC', 'recursive' => -1)));			
				}
			}		
            if($this->Report->validates()) {
                if($this->data['Report']['reports_daterange'] == 'day') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                }
                elseif($this->data['Report']['reports_daterange'] == 'week') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                }
                elseif($this->data['Report']['reports_daterange'] == 'month') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                }
                elseif($this->data['Report']['reports_daterange'] == 'year') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                }
                elseif($this->data['Report']['reports_daterange'] == 'manual') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                }
                $this->set('downloads', $downloads);
                $this->set('patronDownloads', $patronDownloads);
                $this->set('genreDownloads', $genreDownloads);
                $this->layout = 'pdf';
                $this->render();
            }
            else {
                $this->Session->setFlash( 'Error occured while entering the Reports Setting fields', 'modal', array( 'class' => 'modal problem' ) );
                $this->redirect(array('action'=>'index'), null, true);
            }
        }
        else {
            $this->Session->setFlash( 'Error occured while entering the Reports Setting fields', 'modal', array( 'class' => 'modal problem' ) );
            $this->redirect(array('action'=>'index'), null, true);
        }
    }
    
    /*
     Function Name : admin_libraryrenewalreport
     Desc : actions for library renewal reports page
    */
    function admin_libraryrenewalreport() {
        if(isset($this->data)) {
            Configure::write('debug',0); // Otherwise we cannot use this method while developing 
            $this->set("sitelibraries", $this->Library->find("all", array('order' => 'library_contract_start_date ASC', 'recursive' => -1)));
            if($this->data['downloadType'] == 'pdf') {
                $this->layout = 'pdf';
                $this->render("/reports/admin_downloadLibraryRenewalReportAsPdf");
            }
            elseif($this->data['downloadType'] == 'csv') {
                $this->layout = false;
                $this->render("/reports/admin_downloadLibraryRenewalReportAsCsv");
            }
        }
        $this->set("sitelibraries", $this->Library->find("all", array('order' => 'library_contract_start_date ASC', 'recursive' => -1)));
    }
    
    /*
     Function Name : admin_librarywishlistreport
     Desc : actions for library wishlist reports page
    */
    function admin_librarywishlistreport() {
        if($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") == ''){
            $libraryAdminID = $this->Library->find("first", array("conditions" => array('library_admin_id' => $this->Session->read("Auth.User.id")), 'fields' => array('id', 'library_name'), 'recursive' => -1));
            $this->set('libraryID', $libraryAdminID["Library"]["id"]);
            $this->set('libraryname', $libraryAdminID["Library"]["library_name"]);
        }
		elseif($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") != ''){
            $this->set('libraries', $this->Library->find('list', array("conditions" => array('Library.library_apikey' => $this->Session->read("Auth.User.consortium")),'fields' => array('Library.library_name'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
            $this->set('libraryID', "");		
		}
        else {
            $this->set('libraries', $this->Library->find('list', array('fields' => array('Library.library_name'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
            $this->set('libraryID', "");
        }
        if(isset($this->data)) {
            Configure::write('debug',0); // Otherwise we cannot use this method while developing 
            $this->Report->set($this->data);
            if($this->data['Report']['reports_daterange'] != 'manual') {
                $this->Report->setValidation('reports_date');
            }
            else {
                $this->Report->setValidation('reports_manual');
            }
            if($this->Report->validates()) {
                $wishlists = $this->Wishlist->getWishListInformation($this->data['Report']['library_id'], $this->data['Report']['reports_daterange'], $this->data['Report']['date'], $this->data['Report']['date_from'], $this->data['Report']['date_to']);
                $this->set('wishlists', $wishlists);
                $arr = array();
                $this->set('errors', $arr);
                if($this->data['Report']['downloadType'] == 'pdf') {
                    $this->layout = 'pdf';
                    $this->render("/reports/admin_downloadLibraryWishListReportAsPdf");
                }
                elseif($this->data['Report']['downloadType'] == 'csv') {
                    $this->layout = false;
                    $this->render("/reports/admin_downloadLibraryWishListReportAsCsv");
                }
            }
            else {
                $this->Session->setFlash( 'Error occured while entering the Reports Setting fields', 'modal', array( 'class' => 'modal problem' ) );
                $arr = array();
                $this->set('wishlists', $arr);
                $this->set('errors', $this->Report->invalidFields());
            }
            $this -> set( 'formAction', 'admin_librarywishlistreport' );
            $this->set('getData', $this->data);
        }
        else {
            $this -> set( 'formAction', 'admin_librarywishlistreport' );
            $arr = array();
            $this->set('getData', $arr);
            $this->set('wishlists', $arr);
            $this->set('errors', $arr);
        }
    }
    
    /*
     Function Name : admin_sonyreports
     Desc : actions for library download for sony reports page
    */
    function admin_sonyreports() {
        if(!empty($this->params['named']['id']))//gets the values from the url in form  of array
        {
            Configure::write('debug',0); // Otherwise we cannot use this method while developing 
            $sonyReport = $this->SonyReport->find("first", array('conditions' => array('id' => base64_decode($this->params['named']['id']))));
            $path = "http://music.freegalmusic.com/freegalmusic/prod/EN/sony_reports"; // change the path to fit your websites document structure
            $fullPath = $path."/".$sonyReport['SonyReport']['report_name'];
            if ($fd = fopen ($fullPath, "r")) {
                $fsize = filesize($fullPath);
                header("Content-type: application/octet-stream");
                header("Content-Disposition: filename=\"".$sonyReport['SonyReport']['report_name']."\"");
                header("Content-length: $fsize");
                header("Cache-control: private"); //use this to open files directly
                while(!feof($fd)) {
                    $buffer = fread($fd, 2048);
                    echo $buffer;
                }
            }
            fclose ($fd);
            exit;
        }
        $this->set("sonyReports", $this->paginate('SonyReport'));
    }
	function admin_getLibraryIds(){
        Configure::write('debug', 0);
        if($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") == '') {
            $var = $this->Library->find("list", array("conditions" => array('Library.library_admin_id' => $this->Session->read("Auth.User.id"),'Library.library_territory' => $_REQUEST['Territory']), 'fields' => array('Library.id','Library.library_name'),'order' => 'Library.library_name ASC', 'recursive' => -1));
        }elseif($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") != ''){
            $var = $this->Library->find("list", array("conditions" => array('Library.library_apikey' => $this->Session->read("Auth.User.consortium"),'Library.library_territory' => $_REQUEST['Territory']), 'fields' => array('Library.id','Library.library_name'),'order' => 'Library.library_name ASC', 'recursive' => -1));			
		}
        else {
			$var = $this->Library->find('list', array('conditions' => array('Library.library_territory' => $_REQUEST['Territory']),'fields' => array('Library.id','Library.library_name'),'order' => 'Library.library_name ASC','recursive' => -1));
        }
		$data = "<option value='all'>All Libraries</option>";
		foreach($var as $k=>$v){
			$data = $data."<option value=".$k.">".$v."</option>";
		}
		print "<select class='select_fields' name='library_id'>".$data."</select>";exit;
	}
	function admin_unlimited(){
        if($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") == ''){
            $libraryAdminID = $this->Library->find("first", array("conditions" => array('library_admin_id' => $this->Session->read("Auth.User.id")), 'fields' => array('id', 'library_name','library_territory'), 'recursive' => -1));
            $this->set('libraryID', $libraryAdminID["Library"]["id"]);
            $this->set('libraryname', $libraryAdminID["Library"]["library_name"]);
        }
        else {
			$this->set('libraryID', "");
        }	
		if(isset($this->data)) {
			$all_Ids = '';
			$sql = "SELECT id from libraries where library_unlimited = '1'";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result)) {
				$all_Ids = $all_Ids.$row["id"].",";
			}
			$lib_condition = "and library_id IN (".rtrim($all_Ids,",").")";
			$date_arr = explode("/", $this->data['Report']['date']);
			
			$startDate = date("Y-m-d", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))." 00:00:00";
			$endDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))))." 23:59:59";
			$conditions = array(
			  'created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY id"
			);
			$downloadResult = $this->Download->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition, '1 = 1 GROUP BY library_id'), 'fields' => array('library_id','COUNT(id) AS totalDownloads'),'recursive' => -1));
			foreach($downloadResult as $k =>$v){
				$nameQuery = "SELECT library_name FROM libraries WHERE id=".$v['Download']['library_id'];
				$row = mysql_fetch_assoc(mysql_query($nameQuery));
				$downloadResult[$k]['Download']['library_name'] = $row['library_name'];
				$purchaseQuery = "SELECT purchased_amount FROM library_purchases WHERE 
								  library_id='".$v['Download']['library_id']."' ORDER BY created DESC";
				$row = mysql_fetch_assoc(mysql_query($purchaseQuery));
				$downloadResult[$k]['Download']['library_price'] = $row['purchased_amount'];
				$downloadResult[$k]['Download']['monthly_price'] = $row['purchased_amount']/12;
				$downloadResult[$k]['Download']['download_price'] = ($row['purchased_amount']/12)/$v[0]['totalDownloads'];
				$downloadResult[$k]['Download']['mechanical_royalty'] = ($v[0]['totalDownloads']* (.091/2));
			}
			$this->set( 'formAction', 'admin_unlimited');
			$this->set( 'date', $this->data['Report']['date']);
			$this->set('downloadResult', $downloadResult);
			$this->set('month', date("F", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))));
		} else {
			$this -> set( 'formAction', 'admin_unlimited');
		}
	}
	function admin_unlimitedcsv(){
//        Configure::write('debug', 0);
        $this->layout = false;	
		$all_Ids = '';
		$sql = "SELECT id from libraries where library_unlimited = '1'";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result)) {
			$all_Ids = $all_Ids.$row["id"].",";
		}
		$lib_condition = "and library_id IN (".rtrim($all_Ids,",").")";
		$date_arr = explode("/", $this->data['Report']['date']);
		$startDate = date("Y-m-d", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))." 00:00:00";
		$endDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))))." 23:59:59";
		$conditions = array(
		  'created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY id"
		);
		$downloadResult = $this->Download->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition, '1 = 1 GROUP BY library_id'), 'fields' => array('library_id','COUNT(id) AS totalDownloads'),'recursive' => -1));
		foreach($downloadResult as $k =>$v){
			$nameQuery = "SELECT library_name FROM libraries WHERE id=".$v['Download']['library_id'];
			$row = mysql_fetch_assoc(mysql_query($nameQuery));
			$downloadResult[$k]['Download']['library_name'] = $row['library_name'];
			$purchaseQuery = "SELECT purchased_amount FROM library_purchases WHERE 
							  library_id='".$v['Download']['library_id']."' ORDER BY created DESC";
			$row = mysql_fetch_assoc(mysql_query($purchaseQuery));
			$downloadResult[$k]['Download']['library_price'] = $row['purchased_amount'];
			$downloadResult[$k]['Download']['monthly_price'] = $row['purchased_amount']/12;
			$downloadResult[$k]['Download']['download_price'] = ($row['purchased_amount']/12)/$v[0]['totalDownloads'];
			$downloadResult[$k]['Download']['mechanical_royalty'] = ($v[0]['totalDownloads']* (.091/2));
		}
		$this->set( 'date', $this->data['Report']['date']);
		$this->set('month', date("F", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))));
		$this->set('downloadResult', $downloadResult);
	}
	function admin_unlimitedpdf(){
        Configure::write('debug', 0);
        $this->layout = false;	
		$all_Ids = '';
		$sql = "SELECT id from libraries where library_unlimited = '1'";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result)) {
			$all_Ids = $all_Ids.$row["id"].",";
		}
		$lib_condition = "and library_id IN (".rtrim($all_Ids,",").")";
		$date_arr = explode("/", $this->data['Report']['date']);
		$startDate = date("Y-m-d", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))." 00:00:00";
		$endDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))))." 23:59:59";
		$conditions = array(
		  'created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY id"
		);
		$downloadResult = $this->Download->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition, '1 = 1 GROUP BY library_id'), 'fields' => array('library_id','COUNT(id) AS totalDownloads'),'recursive' => -1));
		foreach($downloadResult as $k =>$v){
			$nameQuery = "SELECT library_name FROM libraries WHERE id=".$v['Download']['library_id'];
			$row = mysql_fetch_assoc(mysql_query($nameQuery));
			$downloadResult[$k]['Download']['library_name'] = $row['library_name'];
			$purchaseQuery = "SELECT purchased_amount FROM library_purchases WHERE 
							  library_id='".$v['Download']['library_id']."' ORDER BY created DESC";
			$row = mysql_fetch_assoc(mysql_query($purchaseQuery));
			$downloadResult[$k]['Download']['library_price'] = $row['purchased_amount'];
			$downloadResult[$k]['Download']['monthly_price'] = $row['purchased_amount']/12;
			$downloadResult[$k]['Download']['download_price'] = ($row['purchased_amount']/12)/$v[0]['totalDownloads'];
			$downloadResult[$k]['Download']['mechanical_royalty'] = ($v[0]['totalDownloads']* (.091/2));
		}
		$this->set( 'date', $this->data['Report']['date']);
		$this->set('month', date("F", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))));		
		$this->set('downloadResult', $downloadResult);
	}
	function admin_consortium(){
		if($this->Session->read("Auth.User.type_id") == 1) {
			$consortium = $this->Consortium->find('list', array('fields' => array('consortium_name','consortium_name'), 'order' => 'consortium_name', 'recursive' => -1,'group' => 'consortium_name'));
			$this->set('consortium', $consortium);
		}else{
			$consortium = $this->Consortium->find('list', array('conditions' => array('consortium_name' => $this->Session->read("Auth.User.consortium")),'fields' => array('consortium_name','consortium_name'), 'order' => 'consortium_name', 'recursive' => -1,'group' => 'consortium_name'));
			$this->set('consortium', $consortium);		
		}
		$this->set('libraryID', "");
        if(isset($this->data)) {
			$consortium_id = $this->data['Report']['library_apikey'];		
            $this->Report->set($this->data);
            if($this->data['Report']['reports_daterange'] != 'manual') {
                $this->Report->setValidation('reports_date');
            }
            else {
                $this->Report->setValidation('reports_manual');
            }
			$currentPatron = $this->Currentpatron->find('all', array('conditions' => array('Currentpatron.consortium' => $consortium_id)));
			$patronIds = '';
			foreach($currentPatron as $k => $v){
				$patronIds .= $v['Currentpatron']['patronid']."','"; 
			}
            if($this->Report->validates()) {
                if($this->data['Report']['reports_daterange'] == 'day') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getConsortiumDaysDownloadInformation("'".rtrim($patronIds,",'")."'", $this->data['Report']['date']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'week') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getConsortiumWeeksDownloadInformation("'".rtrim($patronIds,",'")."'", $this->data['Report']['date']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'month') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getConsortiumMonthsDownloadInformation("'".rtrim($patronIds,",'")."'", $this->data['Report']['date']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'year') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getConsortiumYearsDownloadInformation("'".rtrim($patronIds,",'")."'", $this->data['Report']['date']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'manual') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getConsortiumManualDownloadInformation("'".rtrim($patronIds,",'")."'", $this->data['Report']['date']);
                }
                $this->set('downloads', $downloads);
                $this->set('patronDownloads', $patronDownloads);
                $this->set('genreDownloads', $genreDownloads);
            }
            else {
                $this->Session->setFlash( 'Error occured while entering the Reports Setting fields', 'modal', array( 'class' => 'modal problem' ) );
                $arr = array();
                $this->set('wishlists', $arr);
                $this->set('errors', $this->Report->invalidFields());
                if($this->data['Report']['downloadType'] == 'pdf') {
                    $this->layout = 'pdf';
                    $this->render("/reports/admin_downloadConsortiumWishListReportAsPdf");
                }
                elseif($this->data['Report']['downloadType'] == 'csv') {
                    $this->layout = false;
                    $this->render("/reports/admin_downloadConsortiumWishListReportAsCsv");
                }				
            }
            $this -> set( 'formAction', 'admin_consortium' );
            $this->set('getData', $this->data);
        }
        else {
            $this -> set( 'formAction', 'admin_consortium' );
            $arr = array();
            $this->set('getData', $arr);
            $this->set('consortiumData', $arr);
            $this->set('errors', $arr);
        }		
	}
}
?>
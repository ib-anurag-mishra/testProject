<?php
/*
 File Name : reports_controller.php
 File Description : Report controller page
 Author : maycreate
 */

Class ReportsController extends AppController
{
    var $name = 'Reports';
    var $layout = 'admin';
    var $helpers = array( 'Html', 'Ajax', 'Javascript', 'Form', 'Session', 'Library', 'Csv');
    var $components = array( 'Session', 'Auth', 'Acl', 'RequestHandler' );
    var $uses = array( 'Library', 'User', 'Download', 'Report', 'SonyReport', 'Wishlist', 'Genre' );
    
    /*
     Function Name : admin_index
     Desc : actions for library reports page
    */
    function admin_index() {
        if($this->Session->read("Auth.User.type_id") == 4) {
            $libraryAdminID = $this->Library->find("first", array("conditions" => array('library_admin_id' => $this->Session->read("Auth.User.id")), 'fields' => array('id', 'library_name'), 'recursive' => -1));
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
			Configure::write('debug',0); // Otherwise we cannot use this method while developing 
			$all_Ids = '';
            $this->Report->set($this->data);
			if(isset($_REQUEST['library_id'])){
				$library_id = $_REQUEST['library_id'];
			}else{
				$library_id = $this->data['Report']['library_id'];
			}
			$this->set('library_id', $library_id);
			$territory = $this->data['Report']['Territory'];
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
					$this->set('libraries_download', $this->Library->find('list', array('fields' => array('Library.library_name','Library.library_available_downloads'),'conditions' => array('Library.id IN ('.rtrim($all_Ids,",").')'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
				}
				else{
					$this->set('libraries_download', $this->Library->find('list', array('fields' => array('Library.library_name','Library.library_available_downloads'),'conditions' => array('Library.id = '.$library_id,'Library.library_territory= "'.$territory.'"'),'order' => 'Library.library_name ASC', 'recursive' => -1)));			
				}
			}
            if($this->Report->validates()) {
                if($this->data['Report']['reports_daterange'] == 'day') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $this->data['Report']['Territory']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'week') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $this->data['Report']['Territory']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'month') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $this->data['Report']['Territory']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'year') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $this->data['Report']['Territory']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'manual') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $this->data['Report']['Territory']);
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
			$territory = $this->data['Report']['Territory'];
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
					$this->set('libraries_download', $this->Library->find('list', array('fields' => array('Library.library_name','Library.library_available_downloads'),'conditions' => array('Library.id IN ('.rtrim($all_Ids,",").')'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
				}
				else{
					$this->set('libraries_download', $this->Library->find('list', array('fields' => array('Library.library_name','Library.library_available_downloads'),'conditions' => array('Library.id = '.$library_id,'Library.library_territory= "'.$territory.'"'),'order' => 'Library.library_name ASC', 'recursive' => -1)));			
				}
			}			
            if($this->Report->validates()) {
                if($this->data['Report']['reports_daterange'] == 'day') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $this->data['Report']['Territory']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'week') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $this->data['Report']['Territory']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'month') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $this->data['Report']['Territory']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'year') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $this->data['Report']['Territory']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'manual') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $this->data['Report']['Territory']);
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
			$territory = $this->data['Report']['Territory'];
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
					$this->set('libraries_download', $this->Library->find('list', array('fields' => array('Library.library_name','Library.library_available_downloads'),'conditions' => array('Library.id IN ('.rtrim($all_Ids,",").')'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
				}
				else{
					$this->set('libraries_download', $this->Library->find('list', array('fields' => array('Library.library_name','Library.library_available_downloads'),'conditions' => array('Library.id = '.$library_id,'Library.library_territory= "'.$territory.'"'),'order' => 'Library.library_name ASC', 'recursive' => -1)));			
				}
			}		
            if($this->Report->validates()) {
                if($this->data['Report']['reports_daterange'] == 'day') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $this->data['Report']['Territory']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'week') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $this->data['Report']['Territory']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'month') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $this->data['Report']['Territory']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'year') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $this->data['Report']['Territory']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'manual') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $this->data['Report']['Territory']);
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
        if($this->Session->read("Auth.User.type_id") == 4) {
            $libraryAdminID = $this->Library->find("first", array("conditions" => array('library_admin_id' => $this->Session->read("Auth.User.id")), 'fields' => array('id', 'library_name'), 'recursive' => -1));
            $this->set('libraryID', $libraryAdminID["Library"]["id"]);
            $this->set('libraryname', $libraryAdminID["Library"]["library_name"]);
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
            $path = $sonyReport['SonyReport']['report_location']; // change the path to fit your websites document structure
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
        if($this->Session->read("Auth.User.type_id") == 4) {
            $var = $this->Library->find("list", array("conditions" => array('Library.library_admin_id' => $this->Session->read("Auth.User.id"),'Library.library_territory' => $_REQUEST['Territory']), 'fields' => array('Library.id','Library.library_name'), 'recursive' => -1));
        }
        else {
			$var = $this->Library->find('list', array('conditions' => array('Library.library_territory' => $_REQUEST['Territory']),'fields' => array('Library.id','Library.library_name'),'recursive' => -1));
        }		
		$data = "<option value='all'>All Libraries</option>";
		foreach($var as $k=>$v){
			$data = $data."<option value=".$k.">".$v."</option>";
		}
		print "<select class='select_fields' name='library_id'>".$data."</select>";exit;
	}
}
?>
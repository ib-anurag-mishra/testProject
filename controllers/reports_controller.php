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
    
    function admin_index() {
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
            $this->Report->set($this->data);
            if($this->data['Report']['reports_daterange'] != 'manual') {
                $this->Report->setValidation('reports_date');
            }
            else {
                $this->Report->setValidation('reports_manual');
            }
            if($this->Report->validates()) {
                if($this->data['Report']['library_id'] == "all") {
                    $lib_condition = "";
                }
                else {
                    $lib_condition = "and library_id = ".$this->data['Report']['library_id'];
                }
                if($this->data['Report']['reports_daterange'] == 'day') {
                    list($downloads, $patronDownloads) = $this->Download->getDaysDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                    $date_arr = explode("/", $this->data['Report']['date']);
                    $startDate = $date_arr[2]."-".$date_arr[0]."-".$date_arr[1]." 00:00:00";
                    $endDate = $date_arr[2]."-".$date_arr[0]."-".$date_arr[1]." 23:59:59";
                    $conditions = 'WHERE created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition;
                    $this->Genre->recursive = -1;
                    $genreDownloads = $this->Genre->find('all', array('conditions' => array("Genre.ProdID IN(SELECT Distinct ProdID  FROM `downloads` $conditions)"),
                                                          'group' => array('Genre'), 'fields' => array('Genre', 'COUNT(Genre.ProdID) AS totalProds'), 'order' => 'Genre'));
                }
                elseif($this->data['Report']['reports_daterange'] == 'week') {
                    list($downloads, $patronDownloads) = $this->Download->getWeeksDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                    $date_arr = explode("/", $this->data['Report']['date']);
                    $startDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], $date_arr[1]-(date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]))-1), $date_arr[2]))." 00:00:00";
                    $endDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], $date_arr[1]+(7-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2]))." 23:59:59";
                    $conditions = 'WHERE created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition;
                    $this->Genre->recursive = -1;
                    $genreDownloads = $this->Genre->find('all', array('conditions' => array("Genre.ProdID IN(SELECT Distinct ProdID  FROM `downloads` $conditions)"),
                                                          'group' => array('Genre'), 'fields' => array('Genre', 'COUNT(Genre.ProdID) AS totalProds'), 'order' => 'Genre'));
                }
                elseif($this->data['Report']['reports_daterange'] == 'month') {
                    list($downloads, $patronDownloads) = $this->Download->getMonthsDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                    $date_arr = explode("/", $this->data['Report']['date']);
                    $startDate = date("Y-m-d", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))." 00:00:00";
                    $endDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))))." 23:59:59";
                    $conditions = 'WHERE created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition;
                    $this->Genre->recursive = -1;
                    $genreDownloads = $this->Genre->find('all', array('conditions' => array("Genre.ProdID IN(SELECT Distinct ProdID  FROM `downloads` $conditions)"),
                                                          'group' => array('Genre'), 'fields' => array('Genre', 'COUNT(Genre.ProdID) AS totalProds'), 'order' => 'Genre'));
                }
                elseif($this->data['Report']['reports_daterange'] == 'year') {
                    list($downloads, $patronDownloads) = $this->Download->getYearsDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                    $date_arr = explode("/", $this->data['Report']['date']);
                    $startDate = date('Y-m-d', mktime(0, 0, 0, 1, 1, $date_arr[2]))." 00:00:00";
                    $endDate = date('Y-m-d', mktime(0, 0, 0, 12, 31, $date_arr[2]))." 23:59:59";
                    $conditions = 'WHERE created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition;
                    $this->Genre->recursive = -1;
                    $genreDownloads = $this->Genre->find('all', array('conditions' => array("Genre.ProdID IN(SELECT Distinct ProdID  FROM `downloads` $conditions)"),
                                                          'group' => array('Genre'), 'fields' => array('Genre', 'COUNT(Genre.ProdID) AS totalProds'), 'order' => 'Genre'));
                }
                elseif($this->data['Report']['reports_daterange'] == 'manual') {
                    list($downloads, $patronDownloads) = $this->Download->getManualDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date_from'], $this->data['Report']['date_to']);
                    $date_arr_from = explode("/", $this->data['Report']['date_from']);
                    $date_arr_to = explode("/", $this->data['Report']['date_to']);
                    $startDate = $date_arr_from[2]."-".$date_arr_from[0]."-".$date_arr_from[1]." 00:00:00";
                    $endDate = $date_arr_to[2]."-".$date_arr_to[0]."-".$date_arr_to[1]." 23:59:59";
                    $conditions = 'WHERE created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition;
                    $this->Genre->recursive = -1;
                    $genreDownloads = $this->Genre->find('all', array('conditions' => array("Genre.ProdID IN(SELECT Distinct ProdID  FROM `downloads` $conditions)"),
                                                          'group' => array('Genre'), 'fields' => array('Genre', 'COUNT(Genre.ProdID) AS totalProds'), 'order' => 'Genre'));
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
            $arr = array();
            $this->set('getData', $arr);
            $this->set('downloads', $arr);
            $this->set('errors', $arr);
        }
    }
    
    function admin_downloadAsCsv() {
        Configure::write('debug', 0);
        $this->layout = false;
        if(isset($this->data)) {
            $this->Report->set($this->data);
            if($this->data['Report']['reports_daterange'] != 'manual') {
                $this->Report->setValidation('reports_date');
            }
            else {
                $this->Report->setValidation('reports_manual');
            }
            if($this->Report->validates()) {
                if($this->data['Report']['library_id'] == "all") {
                    $lib_condition = "";
                }
                else {
                    $lib_condition = "and library_id = ".$this->data['Report']['library_id'];
                }
                if($this->data['Report']['reports_daterange'] == 'day') {
                    list($downloads, $patronDownloads) = $this->Download->getDaysDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                    $date_arr = explode("/", $this->data['Report']['date']);
                    $startDate = $date_arr[2]."-".$date_arr[0]."-".$date_arr[1]." 00:00:00";
                    $endDate = $date_arr[2]."-".$date_arr[0]."-".$date_arr[1]." 23:59:59";
                    $conditions = 'WHERE created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition;
                    $this->Genre->recursive = -1;
                    $genreDownloads = $this->Genre->find('all', array('conditions' => array("Genre.ProdID IN(SELECT Distinct ProdID  FROM `downloads` $conditions)"),
                                                          'group' => array('Genre'), 'fields' => array('Genre', 'COUNT(Genre.ProdID) AS totalProds'), 'order' => 'Genre'));
                }
                elseif($this->data['Report']['reports_daterange'] == 'week') {
                    list($downloads, $patronDownloads) = $this->Download->getWeeksDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                    $date_arr = explode("/", $this->data['Report']['date']);
                    $startDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], $date_arr[1]-(date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]))-1), $date_arr[2]))." 00:00:00";
                    $endDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], $date_arr[1]+(7-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2]))." 23:59:59";
                    $conditions = 'WHERE created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition;
                    $this->Genre->recursive = -1;
                    $genreDownloads = $this->Genre->find('all', array('conditions' => array("Genre.ProdID IN(SELECT Distinct ProdID  FROM `downloads` $conditions)"),
                                                          'group' => array('Genre'), 'fields' => array('Genre', 'COUNT(Genre.ProdID) AS totalProds'), 'order' => 'Genre'));
                }
                elseif($this->data['Report']['reports_daterange'] == 'month') {
                    list($downloads, $patronDownloads) = $this->Download->getMonthsDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                    $date_arr = explode("/", $this->data['Report']['date']);
                    $startDate = date("Y-m-d", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))." 00:00:00";
                    $endDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))))." 23:59:59";
                    $conditions = 'WHERE created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition;
                    $this->Genre->recursive = -1;
                    $genreDownloads = $this->Genre->find('all', array('conditions' => array("Genre.ProdID IN(SELECT Distinct ProdID  FROM `downloads` $conditions)"),
                                                          'group' => array('Genre'), 'fields' => array('Genre', 'COUNT(Genre.ProdID) AS totalProds'), 'order' => 'Genre'));
                }
                elseif($this->data['Report']['reports_daterange'] == 'year') {
                    list($downloads, $patronDownloads) = $this->Download->getYearsDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                    $date_arr = explode("/", $this->data['Report']['date']);
                    $startDate = date('Y-m-d', mktime(0, 0, 0, 1, 1, $date_arr[2]))." 00:00:00";
                    $endDate = date('Y-m-d', mktime(0, 0, 0, 12, 31, $date_arr[2]))." 23:59:59";
                    $conditions = 'WHERE created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition;
                    $this->Genre->recursive = -1;
                    $genreDownloads = $this->Genre->find('all', array('conditions' => array("Genre.ProdID IN(SELECT Distinct ProdID  FROM `downloads` $conditions)"),
                                                          'group' => array('Genre'), 'fields' => array('Genre', 'COUNT(Genre.ProdID) AS totalProds'), 'order' => 'Genre'));
                }
                elseif($this->data['Report']['reports_daterange'] == 'manual') {
                    list($downloads, $patronDownloads) = $this->Download->getManualDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date_from'], $this->data['Report']['date_to']);
                    $date_arr_from = explode("/", $this->data['Report']['date_from']);
                    $date_arr_to = explode("/", $this->data['Report']['date_to']);
                    $startDate = $date_arr_from[2]."-".$date_arr_from[0]."-".$date_arr_from[1]." 00:00:00";
                    $endDate = $date_arr_to[2]."-".$date_arr_to[0]."-".$date_arr_to[1]." 23:59:59";
                    $conditions = 'WHERE created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition;
                    $this->Genre->recursive = -1;
                    $genreDownloads = $this->Genre->find('all', array('conditions' => array("Genre.ProdID IN(SELECT Distinct ProdID  FROM `downloads` $conditions)"),
                                                          'group' => array('Genre'), 'fields' => array('Genre', 'COUNT(Genre.ProdID) AS totalProds'), 'order' => 'Genre'));
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
    
    function admin_downloadAsPdf() {
        Configure::write('debug',0); // Otherwise we cannot use this method while developing 
        if(isset($this->data)) {
            $this->Report->set($this->data);
            if($this->data['Report']['reports_daterange'] != 'manual') {
                $this->Report->setValidation('reports_date');
            }
            else {
                $this->Report->setValidation('reports_manual');
            }
            if($this->Report->validates()) {
                if($this->data['Report']['library_id'] == "all") {
                    $lib_condition = "";
                }
                else {
                    $lib_condition = "and library_id = ".$this->data['Report']['library_id'];
                }
                if($this->data['Report']['reports_daterange'] == 'day') {
                    list($downloads, $patronDownloads) = $this->Download->getDaysDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                    $date_arr = explode("/", $this->data['Report']['date']);
                    $startDate = $date_arr[2]."-".$date_arr[0]."-".$date_arr[1]." 00:00:00";
                    $endDate = $date_arr[2]."-".$date_arr[0]."-".$date_arr[1]." 23:59:59";
                    $conditions = 'WHERE created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition;
                    $this->Genre->recursive = -1;
                    $genreDownloads = $this->Genre->find('all', array('conditions' => array("Genre.ProdID IN(SELECT Distinct ProdID  FROM `downloads` $conditions)"),
                                                          'group' => array('Genre'), 'fields' => array('Genre', 'COUNT(Genre.ProdID) AS totalProds'), 'order' => 'Genre'));
                }
                elseif($this->data['Report']['reports_daterange'] == 'week') {
                    list($downloads, $patronDownloads) = $this->Download->getWeeksDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                    $date_arr = explode("/", $this->data['Report']['date']);
                    $startDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], $date_arr[1]-(date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]))-1), $date_arr[2]))." 00:00:00";
                    $endDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], $date_arr[1]+(7-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2]))." 23:59:59";
                    $conditions = 'WHERE created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition;
                    $this->Genre->recursive = -1;
                    $genreDownloads = $this->Genre->find('all', array('conditions' => array("Genre.ProdID IN(SELECT Distinct ProdID  FROM `downloads` $conditions)"),
                                                          'group' => array('Genre'), 'fields' => array('Genre', 'COUNT(Genre.ProdID) AS totalProds'), 'order' => 'Genre'));
                }
                elseif($this->data['Report']['reports_daterange'] == 'month') {
                    list($downloads, $patronDownloads) = $this->Download->getMonthsDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                    $date_arr = explode("/", $this->data['Report']['date']);
                    $startDate = date("Y-m-d", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))." 00:00:00";
                    $endDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))))." 23:59:59";
                    $conditions = 'WHERE created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition;
                    $this->Genre->recursive = -1;
                    $genreDownloads = $this->Genre->find('all', array('conditions' => array("Genre.ProdID IN(SELECT Distinct ProdID  FROM `downloads` $conditions)"),
                                                          'group' => array('Genre'), 'fields' => array('Genre', 'COUNT(Genre.ProdID) AS totalProds'), 'order' => 'Genre'));
                }
                elseif($this->data['Report']['reports_daterange'] == 'year') {
                    list($downloads, $patronDownloads) = $this->Download->getYearsDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                    $date_arr = explode("/", $this->data['Report']['date']);
                    $startDate = date('Y-m-d', mktime(0, 0, 0, 1, 1, $date_arr[2]))." 00:00:00";
                    $endDate = date('Y-m-d', mktime(0, 0, 0, 12, 31, $date_arr[2]))." 23:59:59";
                    $conditions = 'WHERE created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition;
                    $this->Genre->recursive = -1;
                    $genreDownloads = $this->Genre->find('all', array('conditions' => array("Genre.ProdID IN(SELECT Distinct ProdID  FROM `downloads` $conditions)"),
                                                          'group' => array('Genre'), 'fields' => array('Genre', 'COUNT(Genre.ProdID) AS totalProds'), 'order' => 'Genre'));
                }
                elseif($this->data['Report']['reports_daterange'] == 'manual') {
                    list($downloads, $patronDownloads) = $this->Download->getManualDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date_from'], $this->data['Report']['date_to']);
                    $date_arr_from = explode("/", $this->data['Report']['date_from']);
                    $date_arr_to = explode("/", $this->data['Report']['date_to']);
                    $startDate = $date_arr_from[2]."-".$date_arr_from[0]."-".$date_arr_from[1]." 00:00:00";
                    $endDate = $date_arr_to[2]."-".$date_arr_to[0]."-".$date_arr_to[1]." 23:59:59";
                    $conditions = 'WHERE created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition;
                    $this->Genre->recursive = -1;
                    $genreDownloads = $this->Genre->find('all', array('conditions' => array("Genre.ProdID IN(SELECT Distinct ProdID  FROM `downloads` $conditions)"),
                                                          'group' => array('Genre'), 'fields' => array('Genre', 'COUNT(Genre.ProdID) AS totalProds'), 'order' => 'Genre'));
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
}
?>
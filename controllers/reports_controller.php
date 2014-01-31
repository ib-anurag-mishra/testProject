<?php

/*
  File Name : reports_controller.php
  File Description : Report controller page
  Author : m68interactive
 */
ini_set('memory_limit', '1024M');

Class ReportsController extends AppController {

    var $name = 'Reports';
    var $layout = 'admin';
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Form', 'Session', 'Library', 'Csv');
    var $components = array('Session', 'Auth', 'Acl', 'RequestHandler');
    var $uses = array('Library', 'User', 'Download', 'Report', 'SonyReport', 'Wishlist', 'Genre', 'Currentpatron', 'Consortium', 'Territory', 'Downloadpatron', 'Downloadgenre', 'Videodownload', 'DownloadVideoPatron', 'DownloadVideoGenre','StreamingHistory');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('admin_consortium','admin_streamingreport','admin_downloadStreamingReportAsCsv','admin_getLibraryIdsStream');
    }

    /*
      Function Name : admin_index
      Desc : actions for library reports page
     */

    function admin_index() {
//        Configure::write('debug',2);
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        //	print_r($this->data);exit;
        if ($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") == '') {
            $libraryAdminID = $this->Library->find("first", array("conditions" => array('library_admin_id' => $this->Session->read("Auth.User.id")), 'fields' => array('id', 'library_name', 'library_territory'), 'recursive' => -1));
            $this->set('libraryID', $libraryAdminID["Library"]["id"]);
            $this->set('libraryname', $libraryAdminID["Library"]["library_name"]);
        } else {
            if ($this->data['Report']['Territory'] == '') {
                //$this->set('libraries', $this->Library->find('list', array('fields' => array('Library.library_name'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
                $this->set('libraries', $this->admin_getLibraryIds());
            } else {
                $this->set('libraries', $this->Library->find('list', array('fields' => array('Library.library_name'), 'conditions' => array('Library.library_territory= "' . $this->data['Report']['Territory'] . '"'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
            }
            $this->set('libraryID', "");
        }
        if (isset($this->data)) {
            //Configure::write('debug',0); // Otherwise we cannot use this method while developing
            $all_Ids = '';
            $this->Report->set($this->data);
            if (isset($_REQUEST['library_id'])) {
                $library_id = $_REQUEST['library_id'];
            } else {
                $library_id = $this->data['Report']['library_id'];
            }
            $this->set('library_id', $library_id);
            if ($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") == '') {
                $territory = $libraryAdminID["Library"]["library_territory"];
            } else {
                $territory = $this->data['Report']['Territory'];
            }
            if ($this->data['Report']['reports_daterange'] != 'manual') {
                $this->Report->setValidation('reports_date');
            } else {
                $this->Report->setValidation('reports_manual');
            }
            if ($territory != '') {
                if ($library_id == 'all') {
                    $sql = "SELECT id from libraries where library_territory = '" . $territory . "'";
                    $result = mysql_query($sql);
                    while ($row = mysql_fetch_assoc($result)) {
                        $all_Ids = $all_Ids . $row["id"] . ",";
                    }
                    $lib_condition = "and library_id IN (" . rtrim($all_Ids, ",'") . ")";
                    $this->set('libraries_download', $this->Library->find('all', array('fields' => array('Library.library_name', 'Library.library_unlimited', 'Library.library_available_downloads'), 'conditions' => array('Library.id IN (' . rtrim($all_Ids, ",") . ')'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
                } else {
                    $this->set('libraries_download', $this->Library->find('all', array('fields' => array('Library.library_name', 'Library.library_unlimited', 'Library.library_available_downloads'), 'conditions' => array('Library.id = ' . $library_id, 'Library.library_territory= "' . $territory . '"'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
                }
            }
            if ($this->Report->validates()) {
                if ($this->data['Report']['reports_daterange'] == 'day') {
                    $date_arr = explode("/", $this->data['Report']['date']);
                    $compareDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1];
                    $downloads = $this->Download->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $arr_all_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_library_downloads = $this->Download->getAllLibraryDownloadsDay($library_id, $this->data['Report']['date'], $territory);
                    }
                    $arr_all_video_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_video_library_downloads = $this->Videodownload->getAllLibraryDownloadsDay($library_id, $this->data['Report']['date'], $territory);
                    }
                    $videoDownloads = $this->Videodownload->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    $patronDownloads = $this->Downloadpatron->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    if ($library_id != "all") {
                        $patronBothDownloads = $this->Downloadpatron->getDaysBothDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    }
                    $patronVideoDownloads = $this->DownloadVideoPatron->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    $genreVideoDownloads = $this->DownloadVideoGenre->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    $arr_all_patron_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_patron_downloads = $this->Downloadpatron->getTotalPatronDownloadDay($library_id, $this->data['Report']['date'], $territory);
                    }

                    $genreDownloads = $this->Downloadgenre->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                } elseif ($this->data['Report']['reports_daterange'] == 'week') {
                    $date_arr = explode("/", $this->data['Report']['date']);
                    if (date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) == 0) {
                        if (mktime(23, 59, 59, $date_arr[0], ($date_arr[1] - date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2]) > time()) {
                            $compareDate = date('Y-m-d', time());
                        } else {
                            $compareDate = date('Y-m-d', mktime(23, 59, 59, $date_arr[0], ($date_arr[1] - date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2]));
                        }
                    } else {
                        if (mktime(23, 59, 59, $date_arr[0], ($date_arr[1] - date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2]))) + 7, $date_arr[2]) > time()) {
                            $compareDate = date('Y-m-d', time());
                        } else {
                            $compareDate = date('Y-m-d', mktime(23, 59, 59, $date_arr[0], ($date_arr[1] - date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2]))) + 7, $date_arr[2]));
                        }
                    }
                    $downloads = $this->Download->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $arr_all_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_library_downloads = $this->Download->getAllLibraryDownloadsWeek($library_id, $this->data['Report']['date'], $territory);
                    }
                    $arr_all_video_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_video_library_downloads = $this->Videodownload->getAllLibraryDownloadsWeek($library_id, $this->data['Report']['date'], $territory);
                    }

                    $videoDownloads = $this->Videodownload->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    $patronDownloads = $this->Downloadpatron->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    if ($library_id != "all") {
                        $patronBothDownloads = $this->Downloadpatron->getWeeksBothDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    }
                    $patronVideoDownloads = $this->DownloadVideoPatron->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    $genreVideoDownloads = $this->DownloadVideoGenre->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $arr_all_patron_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_patron_downloads = $this->Downloadpatron->getTotalPatronDownloadWeek($library_id, $this->data['Report']['date'], $territory);
                    }
                    $genreDownloads = $this->Downloadgenre->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                } elseif ($this->data['Report']['reports_daterange'] == 'month') {
                    $date_arr = explode("/", $this->data['Report']['date']);
                    $compareDate = $date_arr[2] . "-" . $date_arr[0] . "-" . date('d', time());

                    $downloads = $this->Download->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    $arr_all_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_library_downloads = $this->Download->getAllLibraryDownloadsMonth($library_id, $this->data['Report']['date'], $territory);
                    }
                    $arr_all_video_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_video_library_downloads = $this->Videodownload->getAllLibraryDownloadsMonth($library_id, $this->data['Report']['date'], $territory);
                    }
                    $videoDownloads = $this->Videodownload->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    $patronDownloads = $this->Downloadpatron->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    if ($library_id != "all") {
                        $patronBothDownloads = $this->Downloadpatron->getMonthsBothDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    }
                    $patronVideoDownloads = $this->DownloadVideoPatron->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    $genreVideoDownloads = $this->DownloadVideoGenre->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $arr_all_patron_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_patron_downloads = $this->Downloadpatron->getTotalPatronDownloadMonth($library_id, $this->data['Report']['date'], $territory);
                    }

                    $genreDownloads = $this->Downloadgenre->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                } elseif ($this->data['Report']['reports_daterange'] == 'year') {
                    $date_arr = explode("/", $this->data['Report']['date']);
                    $compareDate = $date_arr[2] . "-" . date('m-d', time());

                    $downloads = $this->Download->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $arr_all_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_library_downloads = $this->Download->getAllLibraryDownloadsYear($library_id, $this->data['Report']['date'], $territory);
                    }
                    $arr_all_video_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_video_library_downloads = $this->Videodownload->getAllLibraryDownloadsYear($library_id, $this->data['Report']['date'], $territory);
                    }

                    $videoDownloads = $this->Videodownload->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $patronDownloads = $this->Downloadpatron->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    if ($library_id != "all") {
                        $patronBothDownloads = $this->Downloadpatron->getYearsBothDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    }
                    $patronVideoDownloads = $this->DownloadVideoPatron->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    $genreVideoDownloads = $this->DownloadVideoGenre->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    $arr_all_patron_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_patron_downloads = $this->Downloadpatron->getTotalPatronDownloadYear($library_id, $this->data['Report']['date'], $territory);
                    }

                    $genreDownloads = $this->Downloadgenre->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                } elseif ($this->data['Report']['reports_daterange'] == 'manual') {
                    $date_arr = explode("/", $this->data['Report']['date_to']);
                    $compareDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1];

                    $downloads = $this->Download->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                    $arr_all_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_library_downloads = $this->Download->getAllLibraryDownloadsManual($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                    }
                    $arr_all_video_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_video_library_downloads = $this->Videodownload->getAllLibraryDownloadsManual($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                    }

                    $videoDownloads = $this->Videodownload->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);

                    $patronDownloads = $this->Downloadpatron->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                    if ($library_id != "all") {
                        $patronBothDownloads = $this->Downloadpatron->getManualBothDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                    }
                    $patronVideoDownloads = $this->DownloadVideoPatron->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                    $genreVideoDownloads = $this->DownloadVideoGenre->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                    $arr_all_patron_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_patron_downloads = $this->Downloadpatron->getTotalPatronDownloadManual($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                    }

                    $genreDownloads = $this->Downloadgenre->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                }

                $date = date('Y-m-d', time());
                //$date = '2013-06-28';
                if ($compareDate == $date) {
                    $currentPatronDownload = $this->Download->getCurrentPatronDownloads($library_id, $date, $territory, $all_Ids);
                    if ($library_id != "all") {
                        $currentPatronBothDownload = $this->Download->getCurrentPatronBothDownloads($library_id, $date, $territory, $all_Ids);
                    }
                    $currentGenreDownload = $this->Download->getCurrentGenreDownloads($library_id, $date, $territory, $all_Ids);
                    $currentVideoPatronDownload = $this->Videodownload->getCurrentPatronDownloads($library_id, $date, $territory, $all_Ids);
                    $currentVideoGenreDownload = $this->Videodownload->getCurrentGenreDownloads($library_id, $date, $territory, $all_Ids);
                } else {
                    $currentPatronDownload = array();
                    $currentGenreDownload = array();
                    $currentVideoPatronDownload = array();
                    $currentVideoGenreDownload = array();
                    $currentPatronBothDownload = array();
                }

                $this->set('downloads', $downloads);
                $this->set('arr_all_library_downloads', $arr_all_library_downloads);
                $this->set('videoDownloads', $videoDownloads);
                $this->set('arr_all_video_library_downloads', $arr_all_video_library_downloads);
                $this->set('arr_all_patron_downloads', $arr_all_patron_downloads);

                if ($this->data['Report']['reports_daterange'] == 'day') {
                    if (!empty($currentPatronDownload)) {
                        foreach ($currentPatronDownload as $patronRecord) {
                            $i = count($patronDownloads);
                            $patronDownloads[$i]['Downloadpatron']['library_id'] = $patronRecord['Download']['library_id'];
                            $patronDownloads[$i]['Downloadpatron']['patron_id'] = $patronRecord['Download']['patron_id'];
                            $patronDownloads[$i]['Downloadpatron']['total'] = $patronRecord[0]['total'];
                        }
                    }
                    $this->set('patronDownloads', $patronDownloads);
                } else {
                    if (!empty($currentPatronDownload)) {
                        foreach ($currentPatronDownload as $patronRecord) {
                            if (!empty($patronDownloads[0])) {
                                $i = count($patronDownloads[0]);
                                $flag = false;
                                foreach ($patronDownloads[0] as $pkey => $patronDownload) {
                                    if ($patronRecord['Download']['patron_id'] == $patronDownload['Downloadpatron']['patron_id']) {
                                        $patronDownloads[0][$pkey][0]['total'] += $patronRecord[0]['total'];
                                        $flag = true;
                                        break;
                                    }
                                }
                                if ($flag == false) {
                                    $patronDownloads[0][$i]['Downloadpatron']['library_id'] = $patronRecord['Download']['library_id'];
                                    $patronDownloads[0][$i]['Downloadpatron']['patron_id'] = $patronRecord['Download']['patron_id'];
                                    $patronDownloads[0][$i][0]['total'] = $patronRecord[0]['total'];
                                }
                            } else {
                                $i = count($patronDownloads[0]);
                                $patronDownloads[0][$i]['Downloadpatron']['library_id'] = $patronRecord['Download']['patron_id'];
                                $patronDownloads[0][$i]['Downloadpatron']['patron_id'] = $patronRecord['Download']['patron_id'];
                                $patronDownloads[0][$i][0]['total'] = $patronRecord[0]['total'];
                            }
                        }
                        //die;
                    }
                    $this->set('patronDownloads', $patronDownloads[0]);
                }
                if ($library_id != "all") {
                    if ($this->data['Report']['reports_daterange'] == 'day') {
                        if (!empty($currentPatronBothDownload)) {
                            foreach ($currentPatronBothDownload as $patronRecord) {
                                $i = count($patronBothDownloads);
                                $patronBothDownloads[$i]['table1']['patron_id'] = $patronRecord['table1']['patron_id'];
                            }
                        }
                        $this->set('patronBothDownloads', $patronBothDownloads);
                    } else {
                        if (!empty($currentPatronBothDownload)) {
                            foreach ($currentPatronBothDownload as $patronRecord) {
                                if (!empty($patronBothDownloads)) {
                                    $i = count($patronBothDownloads);
                                    $flag = false;
                                    foreach ($patronBothDownloads as $pkey => $patronDownload) {
                                        if ($patronRecord['table1']['patron_id'] == $patronDownload['table1']['patron_id']) {
                                            $flag = true;
                                            break;
                                        }
                                    }
                                    if ($flag == false) {
                                        $patronBothDownloads[$i]['table1']['patron_id'] = $patronRecord['table1']['patron_id'];
                                    }
                                } else {
                                    $i = count($patronBothDownloads);
                                    $patronBothDownloads[$i]['table1']['patron_id'] = $patronRecord['table1']['patron_id'];
                                }
                            }
                            //die;
                        }
                        $this->set('patronBothDownloads', $patronBothDownloads);
                    }
                }
                if ($this->data['Report']['reports_daterange'] == 'day') {
                    if (!empty($currentVideoPatronDownload)) {
                        foreach ($currentVideoPatronDownload as $patronRecord) {
                            $i = count($patronVideoDownloads);
                            $patronVideoDownloads[$i]['DownloadVideoPatron']['library_id'] = $patronRecord['Videodownload']['library_id'];
                            $patronVideoDownloads[$i]['DownloadVideoPatron']['patron_id'] = $patronRecord['Videodownload']['patron_id'];
                            $patronVideoDownloads[$i]['DownloadVideoPatron']['total'] = $patronRecord[0]['total'];
                        }
                    }
                    $this->set('patronVideoDownloads', $patronVideoDownloads);
                } else {
                    if (!empty($currentVideoPatronDownload)) {
                        foreach ($currentVideoPatronDownload as $patronRecord) {
                            if (!empty($patronVideoDownloads[0])) {
                                $i = count($patronVideoDownloads[0]);
                                $flag = false;
                                foreach ($patronVideoDownloads[0] as $pkey => $patronDownload) {
                                    if ($patronRecord['Videodownload']['patron_id'] == $patronDownload['Videodownload']['patron_id']) {
                                        $patronVideoDownloads[0][$pkey][0]['total'] += $patronRecord[0]['total'];
                                        $flag = true;
                                        break;
                                    }
                                }
                                if ($flag == false) {
                                    $patronVideoDownloads[0][$i]['DownloadVideoPatron']['library_id'] = $patronRecord['Videodownload']['library_id'];
                                    $patronVideoDownloads[0][$i]['DownloadVideoPatron']['patron_id'] = $patronRecord['Videodownload']['patron_id'];
                                    $patronVideoDownloads[0][$i][0]['total'] = $patronRecord[0]['total'];
                                }
                            } else {
                                $i = count($patronVideoDownloads[0]);
                                $patronVideoDownloads[0][$i]['DownloadVideoPatron']['library_id'] = $patronRecord['Videodownload']['patron_id'];
                                $patronVideoDownloads[0][$i]['DownloadVideoPatron']['patron_id'] = $patronRecord['Videodownload']['patron_id'];
                                $patronVideoDownloads[0][$i][0]['total'] = $patronRecord[0]['total'];
                            }
                        }
                        //die;
                    }
                    $this->set('patronVideoDownloads', $patronVideoDownloads[0]);
                }
                if ($this->data['Report']['reports_daterange'] == 'day') {
                    if (!empty($currentGenreDownload)) {
                        foreach ($currentGenreDownload as $genreRecord) {
                            $i = count($genreDownloads);
                            $genreDownloads[$i]['Downloadgenre']['download_date'] = $genreRecord['table1']['day_downloaded'];
                            $genreDownloads[$i]['Downloadgenre']['library_id'] = $genreRecord['table1']['library_id'];
                            $genreDownloads[$i]['Downloadgenre']['genre_name'] = $genreRecord['table1']['Genre'];
                            $genreDownloads[$i]['Downloadgenre']['total'] = $genreRecord[0]['total'];
                        }
                    }
                    $this->set('genreDownloads', $genreDownloads);
                } else {
                    if (!empty($currentGenreDownload)) {
                        foreach ($currentGenreDownload as $genreRecord) {
                            if (!empty($genreDownloads[0])) {
                                $i = count($genreDownloads[0]);
                                $flag = false;
                                foreach ($genreDownloads[0] as $gkey => $genreDownload) {
                                    if ($genreRecord['table1']['Genre'] == $genreDownload['Downloadgenre']['genre_name']) {
                                        $genreDownloads[0][$gkey][0]['total'] += $genreRecord[0]['total'];
                                        $flag = true;
                                        break;
                                    }
                                }
                                if ($flag == false) {
                                    $genreDownloads[0][$i]['Downloadgenre']['download_date'] = $genreRecord['table1']['day_downloaded'];
                                    $genreDownloads[0][$i]['Downloadgenre']['library_id'] = $genreRecord['table1']['library_id'];
                                    $genreDownloads[0][$i]['Downloadgenre']['genre_name'] = $genreRecord['table1']['Genre'];
                                    $genreDownloads[0][$i][0]['total'] = $genreRecord[0]['total'];
                                }
                            } else {
                                $i = count($genreDownloads[0]);
                                $genreDownloads[0][$i]['Downloadgenre']['download_date'] = $genreRecord['table1']['day_downloaded'];
                                $genreDownloads[0][$i]['Downloadgenre']['library_id'] = $genreRecord['table1']['library_id'];
                                $genreDownloads[0][$i]['Downloadgenre']['genre_name'] = $genreRecord['table1']['Genre'];
                                $genreDownloads[0][$i][0]['total'] = $genreRecord[0]['total'];
                            }
                        }
                        //die;
                    }
                    $this->set('genreDownloads', $genreDownloads[0]);
                }
                if ($this->data['Report']['reports_daterange'] == 'day') {
                    if (!empty($currentVideoGenreDownload)) {
                        foreach ($currentVideoGenreDownload as $genreRecord) {
                            $i = count($genreVideoDownloads);
                            $genreVideoDownloads[$i]['DownloadVideoGenre']['download_date'] = $genreRecord['table1']['day_downloaded'];
                            $genreVideoDownloads[$i]['DownloadVideoGenre']['library_id'] = $genreRecord['table1']['library_id'];
                            $genreVideoDownloads[$i]['DownloadVideoGenre']['genre_name'] = $genreRecord['table1']['Genre'];
                            $genreVideoDownloads[$i]['DownloadVideoGenre']['total'] = $genreRecord[0]['total'];
                        }
                    }
                    $this->set('genreVideoDownloads', $genreVideoDownloads);
                } else {
                    if (!empty($currentVideoGenreDownload)) {
                        foreach ($currentVideoGenreDownload as $genreRecord) {
                            if (!empty($genreVideoDownloads[0])) {
                                $i = count($genreVideoDownloads[0]);
                                $flag = false;
                                foreach ($genreVideoDownloads[0] as $gkey => $genreDownload) {
                                    if ($genreRecord['table1']['Genre'] == $genreDownload['DownloadVideoGenre']['genre_name']) {
                                        $genreVideoDownloads[0][$gkey][0]['total'] += $genreRecord[0]['total'];
                                        $flag = true;
                                        break;
                                    }
                                }
                                if ($flag == false) {
                                    $genreVideoDownloads[0][$i]['DownloadVideoGenre']['download_date'] = $genreRecord['table1']['day_downloaded'];
                                    $genreVideoDownloads[0][$i]['DownloadVideoGenre']['library_id'] = $genreRecord['table1']['library_id'];
                                    $genreVideoDownloads[0][$i]['DownloadVideoGenre']['genre_name'] = $genreRecord['table1']['Genre'];
                                    $genreVideoDownloads[0][$i][0]['total'] = $genreRecord[0]['total'];
                                }
                            } else {
                                $i = count($genreVideoDownloads[0]);
                                $genreVideoDownloads[0][$i]['DownloadVideoGenre']['download_date'] = $genreRecord['table1']['day_downloaded'];
                                $genreVideoDownloads[0][$i]['DownloadVideoGenre']['library_id'] = $genreRecord['table1']['library_id'];
                                $genreVideoDownloads[0][$i]['DownloadVideoGenre']['genre_name'] = $genreRecord['table1']['Genre'];
                                $genreVideoDownloads[0][$i][0]['total'] = $genreRecord[0]['total'];
                            }
                        }
                        //die;
                    }
                    $this->set('genreVideoDownloads', $genreVideoDownloads[0]);
                }
                $arr = array();
                $this->set('errors', $arr);
            } else {
                $this->Session->setFlash('Error occured while entering the Reports Setting fields', 'modal', array('class' => 'modal problem'));
                $arr = array();
                $this->set('downloads', $arr);
                $this->set('errors', $this->Report->invalidFields());
            }
            $this->set('formAction', 'admin_index');
            $this->set('getData', $this->data);
        } else {
            $this->set('formAction', 'admin_index');
            $this->set('library_id', '');
            $arr = array();
            $this->set('getData', $arr);
            $this->set('downloads', $arr);
            $this->set('errors', $arr);
        }
        $this->set('territory', $this->Territory->find('list', array('fields' => array('Territory', 'Territory'))));
    }

    /*
      Function Name : admin_downloadAsCsv
      Desc : actions for library reports download as CSV page
     */

    function admin_downloadAsCsv() {
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
//        Configure::write('debug', 0);
        $this->layout = false;
        if (isset($this->data)) {
            $all_Ids = '';
            $this->Report->set($this->data);
            if (isset($_REQUEST['library_id'])) {
                $library_id = $_REQUEST['library_id'];
            } else {
                $library_id = $this->data['Report']['library_id'];
            }
            $this->set('library_id', $library_id);
            if ($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") == '') {
                $libraryAdminID = $this->Library->find("first", array("conditions" => array('library_admin_id' => $this->Session->read("Auth.User.id")), 'fields' => array('id', 'library_name', 'library_territory'), 'recursive' => -1));
                $territory = $libraryAdminID["Library"]["library_territory"];
            } else {
                $territory = $this->data['Report']['Territory'];
            }
            if ($this->data['Report']['reports_daterange'] != 'manual') {
                $this->Report->setValidation('reports_date');
            } else {
                $this->Report->setValidation('reports_manual');
            }
            if ($territory != '') {
                if ($library_id == 'all') {
                    $sql = "SELECT id from libraries where library_territory = '" . $territory . "'";
                    $result = mysql_query($sql);
                    while ($row = mysql_fetch_assoc($result)) {
                        $all_Ids = $all_Ids . $row["id"] . ",";
                    }
                    $lib_condition = "and library_id IN (" . rtrim($all_Ids, ",'") . ")";
                    $this->set('libraries_download', $this->Library->find('all', array('fields' => array('Library.library_name', 'Library.library_unlimited', 'Library.library_available_downloads'), 'conditions' => array('Library.id IN (' . rtrim($all_Ids, ",") . ')'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
                } else {
                    $this->set('libraries_download', $this->Library->find('all', array('fields' => array('Library.library_name', 'Library.library_unlimited', 'Library.library_available_downloads'), 'conditions' => array('Library.id = ' . $library_id, 'Library.library_territory= "' . $territory . '"'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
                }
            }
            if ($this->Report->validates()) {
                if ($this->data['Report']['reports_daterange'] == 'day') {
                    $date_arr = explode("/", $this->data['Report']['date']);
                    $compareDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1];
                    $downloads = $this->Download->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $arr_all_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_library_downloads = $this->Download->getAllLibraryDownloadsDay($library_id, $this->data['Report']['date'], $territory);
                    }
                    $arr_all_video_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_video_library_downloads = $this->Videodownload->getAllLibraryDownloadsDay($library_id, $this->data['Report']['date'], $territory);
                    }
                    $videoDownloads = $this->Videodownload->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $patronDownloads = $this->Downloadpatron->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    if ($library_id != "all") {
                        $patronBothDownloads = $this->Downloadpatron->getDaysBothDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    }
                    $patronVideoDownloads = $this->DownloadVideoPatron->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    $genreVideoDownloads = $this->DownloadVideoGenre->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $arr_all_patron_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_patron_downloads = $this->Downloadpatron->getTotalPatronDownloadDay($library_id, $this->data['Report']['date'], $territory);
                    }

                    $genreDownloads = $this->Downloadgenre->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                } elseif ($this->data['Report']['reports_daterange'] == 'week') {
                    $date_arr = explode("/", $this->data['Report']['date']);
                    if (date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) == 0) {
                        if (mktime(23, 59, 59, $date_arr[0], ($date_arr[1] - date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2]) > time()) {
                            $compareDate = date('Y-m-d', time());
                        } else {
                            $compareDate = date('Y-m-d', mktime(23, 59, 59, $date_arr[0], ($date_arr[1] - date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2]));
                        }
                    } else {
                        if (mktime(23, 59, 59, $date_arr[0], ($date_arr[1] - date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2]))) + 7, $date_arr[2]) > time()) {
                            $compareDate = date('Y-m-d', time());
                        } else {
                            $compareDate = date('Y-m-d', mktime(23, 59, 59, $date_arr[0], ($date_arr[1] - date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2]))) + 7, $date_arr[2]));
                        }
                    }

                    $downloads = $this->Download->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $arr_all_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_library_downloads = $this->Download->getAllLibraryDownloadsWeek($library_id, $this->data['Report']['date'], $territory);
                    }
                    $arr_all_video_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_video_library_downloads = $this->Videodownload->getAllLibraryDownloadsWeek($library_id, $this->data['Report']['date'], $territory);
                    }
                    $videoDownloads = $this->Videodownload->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $patronDownloads = $this->Downloadpatron->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    if ($library_id != "all") {
                        $patronBothDownloads = $this->Downloadpatron->getWeeksBothDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    }
                    $patronVideoDownloads = $this->DownloadVideoPatron->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    $genreVideoDownloads = $this->DownloadVideoGenre->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $arr_all_patron_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_patron_downloads = $this->Downloadpatron->getTotalPatronDownloadWeek($library_id, $this->data['Report']['date'], $territory);
                    }

                    $genreDownloads = $this->Downloadgenre->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                } elseif ($this->data['Report']['reports_daterange'] == 'month') {
                    $date_arr = explode("/", $this->data['Report']['date']);
                    $compareDate = $date_arr[2] . "-" . $date_arr[0] . "-" . date('d', time());

                    $downloads = $this->Download->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $arr_all_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_library_downloads = $this->Download->getAllLibraryDownloadsMonth($library_id, $this->data['Report']['date'], $territory);
                    }
                    $arr_all_video_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_video_library_downloads = $this->Videodownload->getAllLibraryDownloadsMonth($library_id, $this->data['Report']['date'], $territory);
                    }
                    $videoDownloads = $this->Videodownload->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    $patronDownloads = $this->Downloadpatron->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    if ($library_id != "all") {
                        $patronBothDownloads = $this->Downloadpatron->getMonthsBothDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    }
                    $patronVideoDownloads = $this->DownloadVideoPatron->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    $genreVideoDownloads = $this->DownloadVideoGenre->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $arr_all_patron_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_patron_downloads = $this->Downloadpatron->getTotalPatronDownloadMonth($library_id, $this->data['Report']['date'], $territory);
                    }

                    $genreDownloads = $this->Downloadgenre->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                } elseif ($this->data['Report']['reports_daterange'] == 'year') {
                    $date_arr = explode("/", $this->data['Report']['date']);
                    $compareDate = $date_arr[2] . "-" . date('m-d', time());

                    $downloads = $this->Download->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $arr_all_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_library_downloads = $this->Download->getAllLibraryDownloadsYear($library_id, $this->data['Report']['date'], $territory);
                    }
                    $arr_all_video_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_video_library_downloads = $this->Videodownload->getAllLibraryDownloadsYear($library_id, $this->data['Report']['date'], $territory);
                    }
                    $videoDownloads = $this->Videodownload->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $patronDownloads = $this->Downloadpatron->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    if ($library_id != "all") {
                        $patronBothDownloads = $this->Downloadpatron->getYearsBothDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    }
                    $patronVideoDownloads = $this->DownloadVideoPatron->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    $genreVideoDownloads = $this->DownloadVideoGenre->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $arr_all_patron_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_patron_downloads = $this->Downloadpatron->getTotalPatronDownloadYear($library_id, $this->data['Report']['date'], $territory);
                    }

                    $genreDownloads = $this->Downloadgenre->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                } elseif ($this->data['Report']['reports_daterange'] == 'manual') {
                    $date_arr = explode("/", $this->data['Report']['date_to']);
                    $compareDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1];

                    $downloads = $this->Download->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                    $arr_all_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_library_downloads = $this->Download->getAllLibraryDownloadsManual($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                    }
                    $arr_all_video_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_video_library_downloads = $this->Videodownload->getAllLibraryDownloadsManual($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                    }
                    $videoDownloads = $this->Videodownload->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);

                    $patronDownloads = $this->Downloadpatron->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                    if ($library_id != "all") {
                        $patronBothDownloads = $this->Downloadpatron->getManualBothDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                    }
                    $patronVideoDownloads = $this->DownloadVideoPatron->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                    $genreVideoDownloads = $this->DownloadVideoGenre->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);

                    $arr_all_patron_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_patron_downloads = $this->Downloadpatron->getTotalPatronDownloadManual($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                    }

                    $genreDownloads = $this->Downloadgenre->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                }

                $date = date('Y-m-d', time());
                if ($compareDate == $date) {
                    $currentPatronDownload = $this->Download->getCurrentPatronDownloads($library_id, $date, $territory, $all_Ids);
                    $currentGenreDownload = $this->Download->getCurrentGenreDownloads($library_id, $date, $territory, $all_Ids);
                    $currentVideoPatronDownload = $this->Videodownload->getCurrentPatronDownloads($library_id, $date, $territory, $all_Ids);
                    $currentVideoGenreDownload = $this->Videodownload->getCurrentGenreDownloads($library_id, $date, $territory, $all_Ids);
                    if ($library_id != "all") {
                        $currentPatronBothDownload = $this->Download->getCurrentPatronBothDownloads($library_id, $date, $territory, $all_Ids);
                    }
                } else {
                    $currentPatronDownload = array();
                    $currentGenreDownload = array();
                    $currentVideoPatronDownload = array();
                    $currentVideoGenreDownload = array();
                    $currentPatronBothDownload = array();
                }
                $this->set('dataRange', $this->data['Report']['reports_daterange']);
                $this->set('downloads', $downloads);
                $this->set('arr_all_library_downloads', $arr_all_library_downloads);
                $this->set('videoDownloads', $videoDownloads);
                $this->set('arr_all_video_library_downloads', $arr_all_video_library_downloads);
                $this->set('arr_all_patron_downloads', $arr_all_patron_downloads);
                if ($this->data['Report']['reports_daterange'] == 'day') {
                    if (!empty($currentPatronDownload)) {
                        foreach ($currentPatronDownload as $patronRecord) {
                            $i = count($patronDownloads);
                            $patronDownloads[$i]['Downloadpatron']['library_id'] = $patronRecord['Download']['library_id'];
                            $patronDownloads[$i]['Downloadpatron']['patron_id'] = $patronRecord['Download']['patron_id'];
                            $patronDownloads[$i]['Downloadpatron']['total'] = $patronRecord[0]['total'];
                        }
                    }
                    $this->set('patronDownloads', $patronDownloads);
                } else {
                    if (!empty($currentPatronDownload)) {
                        foreach ($currentPatronDownload as $patronRecord) {
                            if (!empty($patronDownloads[0])) {
                                $i = count($patronDownloads[0]);
                                $flag = false;
                                foreach ($patronDownloads[0] as $pkey => $patronDownload) {
                                    if ($patronRecord['Download']['patron_id'] == $patronDownload['Downloadpatron']['patron_id']) {
                                        $patronDownloads[0][$pkey][0]['total'] += $patronRecord[0]['total'];
                                        $flag = true;
                                        break;
                                    }
                                }
                                if ($flag == false) {
                                    $patronDownloads[0][$i]['Downloadpatron']['library_id'] = $patronRecord['Download']['library_id'];
                                    $patronDownloads[0][$i]['Downloadpatron']['patron_id'] = $patronRecord['Download']['patron_id'];
                                    $patronDownloads[0][$i][0]['total'] = $patronRecord[0]['total'];
                                }
                            } else {
                                $i = count($patronDownloads[0]);
                                $patronDownloads[0][$i]['Downloadpatron']['library_id'] = $patronRecord['Download']['patron_id'];
                                $patronDownloads[0][$i]['Downloadpatron']['patron_id'] = $patronRecord['Download']['patron_id'];
                                $patronDownloads[0][$i][0]['total'] = $patronRecord[0]['total'];
                            }
                        }
                        //die;
                    }
                    $this->set('patronDownloads', $patronDownloads[0]);
                }
                if ($library_id != "all") {
                    if ($this->data['Report']['reports_daterange'] == 'day') {
                        if (!empty($currentPatronBothDownload)) {
                            foreach ($currentPatronBothDownload as $patronRecord) {
                                $i = count($patronBothDownloads);
                                $patronBothDownloads[$i]['table1']['patron_id'] = $patronRecord['table1']['patron_id'];
                            }
                        }
                        $this->set('patronBothDownloads', $patronBothDownloads);
                    } else {
                        if (!empty($currentPatronBothDownload)) {
                            foreach ($currentPatronBothDownload as $patronRecord) {
                                if (!empty($patronBothDownloads)) {
                                    $i = count($patronBothDownloads);
                                    $flag = false;
                                    foreach ($patronBothDownloads as $pkey => $patronDownload) {
                                        if ($patronRecord['table1']['patron_id'] == $patronDownload['table1']['patron_id']) {
                                            $flag = true;
                                            break;
                                        }
                                    }
                                    if ($flag == false) {
                                        $patronBothDownloads[$i]['table1']['patron_id'] = $patronRecord['table1']['patron_id'];
                                    }
                                } else {
                                    $i = count($patronBothDownloads);
                                    $patronBothDownloads[$i]['table1']['patron_id'] = $patronRecord['table1']['patron_id'];
                                }
                            }
                            //die;
                        }
                        $this->set('patronBothDownloads', $patronBothDownloads);
                    }
                }

                if ($this->data['Report']['reports_daterange'] == 'day') {
                    if (!empty($currentVideoPatronDownload)) {
                        foreach ($currentVideoPatronDownload as $patronRecord) {
                            $i = count($patronVideoDownloads);
                            $patronVideoDownloads[$i]['DownloadVideoPatron']['library_id'] = $patronRecord['Videodownload']['library_id'];
                            $patronVideoDownloads[$i]['DownloadVideoPatron']['patron_id'] = $patronRecord['Videodownload']['patron_id'];
                            $patronVideoDownloads[$i]['DownloadVideoPatron']['total'] = $patronRecord[0]['total'];
                        }
                    }
                    $this->set('patronVideoDownloads', $patronVideoDownloads);
                } else {
                    if (!empty($currentVideoPatronDownload)) {
                        foreach ($currentVideoPatronDownload as $patronRecord) {
                            if (!empty($patronVideoDownloads[0])) {
                                $i = count($patronVideoDownloads[0]);
                                $flag = false;
                                foreach ($patronVideoDownloads[0] as $pkey => $patronDownload) {
                                    if ($patronRecord['Videodownload']['patron_id'] == $patronDownload['Videodownload']['patron_id']) {
                                        $patronVideoDownloads[0][$pkey][0]['total'] += $patronRecord[0]['total'];
                                        $flag = true;
                                        break;
                                    }
                                }
                                if ($flag == false) {
                                    $patronVideoDownloads[0][$i]['DownloadVideoPatron']['library_id'] = $patronRecord['Videodownload']['library_id'];
                                    $patronVideoDownloads[0][$i]['DownloadVideoPatron']['patron_id'] = $patronRecord['Videodownload']['patron_id'];
                                    $patronVideoDownloads[0][$i][0]['total'] = $patronRecord[0]['total'];
                                }
                            } else {
                                $i = count($patronVideoDownloads[0]);
                                $patronVideoDownloads[0][$i]['DownloadVideoPatron']['library_id'] = $patronRecord['Videodownload']['patron_id'];
                                $patronVideoDownloads[0][$i]['DownloadVideoPatron']['patron_id'] = $patronRecord['Videodownload']['patron_id'];
                                $patronVideoDownloads[0][$i][0]['total'] = $patronRecord[0]['total'];
                            }
                        }
                        //die;
                    }
                    $this->set('patronVideoDownloads', $patronVideoDownloads[0]);
                }

                if ($this->data['Report']['reports_daterange'] == 'day') {
                    if (!empty($currentGenreDownload)) {
                        foreach ($currentGenreDownload as $genreRecord) {
                            $i = count($genreDownloads);
                            $genreDownloads[$i]['Downloadgenre']['download_date'] = $genreRecord['table1']['day_downloaded'];
                            $genreDownloads[$i]['Downloadgenre']['library_id'] = $genreRecord['table1']['library_id'];
                            $genreDownloads[$i]['Downloadgenre']['genre_name'] = $genreRecord['table1']['Genre'];
                            $genreDownloads[$i]['Downloadgenre']['total'] = $genreRecord[0]['total'];
                        }
                    }
                    $this->set('genreDownloads', $genreDownloads);
                } else {
                    if (!empty($currentGenreDownload)) {
                        foreach ($currentGenreDownload as $genreRecord) {
                            if (!empty($genreDownloads[0])) {
                                $i = count($genreDownloads[0]);
                                $flag = false;
                                foreach ($genreDownloads[0] as $gkey => $genreDownload) {
                                    if ($genreRecord['table1']['Genre'] == $genreDownload['Downloadgenre']['genre_name']) {
                                        $genreDownloads[0][$gkey][0]['total'] += $genreRecord[0]['total'];
                                        $flag = true;
                                        break;
                                    }
                                }
                                if ($flag == false) {
                                    $genreDownloads[0][$i]['Downloadgenre']['download_date'] = $genreRecord['table1']['day_downloaded'];
                                    $genreDownloads[0][$i]['Downloadgenre']['library_id'] = $genreRecord['table1']['library_id'];
                                    $genreDownloads[0][$i]['Downloadgenre']['genre_name'] = $genreRecord['table1']['Genre'];
                                    $genreDownloads[0][$i][0]['total'] = $genreRecord[0]['total'];
                                }
                            } else {
                                $i = count($genreDownloads[0]);
                                $genreDownloads[0][$i]['Downloadgenre']['download_date'] = $genreRecord['table1']['day_downloaded'];
                                $genreDownloads[0][$i]['Downloadgenre']['library_id'] = $genreRecord['table1']['library_id'];
                                $genreDownloads[0][$i]['Downloadgenre']['genre_name'] = $genreRecord['table1']['Genre'];
                                $genreDownloads[0][$i][0]['total'] = $genreRecord[0]['total'];
                            }
                        }
                        //die;
                    }
                    $this->set('genreDownloads', $genreDownloads[0]);
                }
                if ($this->data['Report']['reports_daterange'] == 'day') {
                    if (!empty($currentVideoGenreDownload)) {
                        foreach ($currentVideoGenreDownload as $genreRecord) {
                            $i = count($genreVideoDownloads);
                            $genreVideoDownloads[$i]['DownloadVideoGenre']['download_date'] = $genreRecord['table1']['day_downloaded'];
                            $genreVideoDownloads[$i]['DownloadVideoGenre']['library_id'] = $genreRecord['table1']['library_id'];
                            $genreVideoDownloads[$i]['DownloadVideoGenre']['genre_name'] = $genreRecord['table1']['Genre'];
                            $genreVideoDownloads[$i]['DownloadVideoGenre']['total'] = $genreRecord[0]['total'];
                        }
                    }
                    $this->set('genreVideoDownloads', $genreVideoDownloads);
                } else {
                    if (!empty($currentVideoGenreDownload)) {
                        foreach ($currentVideoGenreDownload as $genreRecord) {
                            if (!empty($genreVideoDownloads[0])) {
                                $i = count($genreVideoDownloads[0]);
                                $flag = false;
                                foreach ($genreVideoDownloads[0] as $gkey => $genreDownload) {
                                    if ($genreRecord['table1']['Genre'] == $genreDownload['DownloadVideoGenre']['genre_name']) {
                                        $genreVideoDownloads[0][$gkey][0]['total'] += $genreRecord[0]['total'];
                                        $flag = true;
                                        break;
                                    }
                                }
                                if ($flag == false) {
                                    $genreVideoDownloads[0][$i]['DownloadVideoGenre']['download_date'] = $genreRecord['table1']['day_downloaded'];
                                    $genreVideoDownloads[0][$i]['DownloadVideoGenre']['library_id'] = $genreRecord['table1']['library_id'];
                                    $genreVideoDownloads[0][$i]['DownloadVideoGenre']['genre_name'] = $genreRecord['table1']['Genre'];
                                    $genreVideoDownloads[0][$i][0]['total'] = $genreRecord[0]['total'];
                                }
                            } else {
                                $i = count($genreVideoDownloads[0]);
                                $genreVideoDownloads[0][$i]['DownloadVideoGenre']['download_date'] = $genreRecord['table1']['day_downloaded'];
                                $genreVideoDownloads[0][$i]['DownloadVideoGenre']['library_id'] = $genreRecord['table1']['library_id'];
                                $genreVideoDownloads[0][$i]['DownloadVideoGenre']['genre_name'] = $genreRecord['table1']['Genre'];
                                $genreVideoDownloads[0][$i][0]['total'] = $genreRecord[0]['total'];
                            }
                        }
                        //die;
                    }
                    $this->set('genreVideoDownloads', $genreVideoDownloads[0]);
                }
            } else {
                $this->Session->setFlash('Error occured while entering the Reports Setting fields', 'modal', array('class' => 'modal problem'));
                $this->redirect(array('action' => 'index'), null, true);
            }
        } else {
            $this->Session->setFlash('Error occured while entering the Reports Setting fields', 'modal', array('class' => 'modal problem'));
            $this->redirect(array('action' => 'index'), null, true);
        }
    }

    /*
      Function Name : admin_downloadAsPdf
      Desc : actions for library reports download as PDF page
     */

    function admin_downloadAsPdf() {
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
//        Configure::write('debug', 0); // Otherwise we cannot use this method while developing
        if (isset($this->data)) {
            $all_Ids = '';
            $this->Report->set($this->data);
            if (isset($_REQUEST['library_id'])) {
                $library_id = $_REQUEST['library_id'];
            } else {
                $library_id = $this->data['Report']['library_id'];
            }
            $this->set('library_id', $library_id);
            if ($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") == '') {
                $libraryAdminID = $this->Library->find("first", array("conditions" => array('library_admin_id' => $this->Session->read("Auth.User.id")), 'fields' => array('id', 'library_name', 'library_territory'), 'recursive' => -1));
                $territory = $libraryAdminID["Library"]["library_territory"];
            } else {
                $territory = $this->data['Report']['Territory'];
            }
            if ($this->data['Report']['reports_daterange'] != 'manual') {
                $this->Report->setValidation('reports_date');
            } else {
                $this->Report->setValidation('reports_manual');
            }
            if ($territory != '') {
                if ($library_id == 'all') {
                    $sql = "SELECT id from libraries where library_territory = '" . $territory . "'";
                    $result = mysql_query($sql);
                    while ($row = mysql_fetch_assoc($result)) {
                        $all_Ids = $all_Ids . $row["id"] . ",";
                    }
                    $lib_condition = "and library_id IN (" . rtrim($all_Ids, ",'") . ")";
                    $this->set('libraries_download', $this->Library->find('all', array('fields' => array('Library.library_name', 'Library.library_unlimited', 'Library.library_available_downloads'), 'conditions' => array('Library.id IN (' . rtrim($all_Ids, ",") . ')'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
                } else {
                    $this->set('libraries_download', $this->Library->find('all', array('fields' => array('Library.library_name', 'Library.library_unlimited', 'Library.library_available_downloads'), 'conditions' => array('Library.id = ' . $library_id, 'Library.library_territory= "' . $territory . '"'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
                }
            }
            if ($this->Report->validates()) {
                if ($this->data['Report']['reports_daterange'] == 'day') {
                    $date_arr = explode("/", $this->data['Report']['date']);
                    $compareDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1];

                    $downloads = $this->Download->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $arr_all_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_library_downloads = $this->Download->getAllLibraryDownloadsDay($library_id, $this->data['Report']['date'], $territory);
                    }
                    $arr_all_video_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_video_library_downloads = $this->Videodownload->getAllLibraryDownloadsDay($library_id, $this->data['Report']['date'], $territory);
                    }
                    $videoDownloads = $this->Videodownload->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $patronDownloads = $this->Downloadpatron->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    if ($library_id != "all") {
                        $patronBothDownloads = $this->Downloadpatron->getDaysBothDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    }
                    $patronVideoDownloads = $this->DownloadVideoPatron->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    $genreVideoDownloads = $this->DownloadVideoGenre->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $arr_all_patron_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_patron_downloads = $this->Downloadpatron->getTotalPatronDownloadDay($library_id, $this->data['Report']['date'], $territory);
                    }

                    $genreDownloads = $this->Downloadgenre->getDaysDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                } elseif ($this->data['Report']['reports_daterange'] == 'week') {
                    $date_arr = explode("/", $this->data['Report']['date']);
                    if (date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) == 0) {
                        if (mktime(23, 59, 59, $date_arr[0], ($date_arr[1] - date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2]) > time()) {
                            $compareDate = date('Y-m-d', time());
                        } else {
                            $compareDate = date('Y-m-d', mktime(23, 59, 59, $date_arr[0], ($date_arr[1] - date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2]));
                        }
                    } else {
                        if (mktime(23, 59, 59, $date_arr[0], ($date_arr[1] - date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2]))) + 7, $date_arr[2]) > time()) {
                            $compareDate = date('Y-m-d', time());
                        } else {
                            $compareDate = date('Y-m-d', mktime(23, 59, 59, $date_arr[0], ($date_arr[1] - date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2]))) + 7, $date_arr[2]));
                        }
                    }

                    $downloads = $this->Download->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $arr_all_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_library_downloads = $this->Download->getAllLibraryDownloadsWeek($library_id, $this->data['Report']['date'], $territory);
                    }
                    $arr_all_video_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_video_library_downloads = $this->Videodownload->getAllLibraryDownloadsWeek($library_id, $this->data['Report']['date'], $territory);
                    }
                    $videoDownloads = $this->Videodownload->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $patronDownloads = $this->Downloadpatron->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    if ($library_id != "all") {
                        $patronBothDownloads = $this->Downloadpatron->getWeeksBothDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    }
                    $patronVideoDownloads = $this->DownloadVideoPatron->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    $genreVideoDownloads = $this->DownloadVideoGenre->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $arr_all_patron_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_patron_downloads = $this->Downloadpatron->getTotalPatronDownloadWeek($library_id, $this->data['Report']['date'], $territory);
                    }

                    $genreDownloads = $this->Downloadgenre->getWeeksDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                } elseif ($this->data['Report']['reports_daterange'] == 'month') {
                    $date_arr = explode("/", $this->data['Report']['date']);
                    $compareDate = $date_arr[2] . "-" . $date_arr[0] . "-" . date('d', time());

                    $downloads = $this->Download->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $arr_all_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_library_downloads = $this->Download->getAllLibraryDownloadsMonth($library_id, $this->data['Report']['date'], $territory);
                    }
                    $arr_all_video_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_video_library_downloads = $this->Videodownload->getAllLibraryDownloadsMonth($library_id, $this->data['Report']['date'], $territory);
                    }
                    $videoDownloads = $this->Videodownload->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $patronDownloads = $this->Downloadpatron->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    if ($library_id != "all") {
                        $patronBothDownloads = $this->Downloadpatron->getMonthsBothDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    }
                    $patronVideoDownloads = $this->DownloadVideoPatron->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    $genreVideoDownloads = $this->DownloadVideoGenre->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $arr_all_patron_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_patron_downloads = $this->Downloadpatron->getTotalPatronDownloadMonth($library_id, $this->data['Report']['date'], $territory);
                    }

                    $genreDownloads = $this->Downloadgenre->getMonthsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                } elseif ($this->data['Report']['reports_daterange'] == 'year') {
                    $date_arr = explode("/", $this->data['Report']['date']);
                    $compareDate = $date_arr[2] . "-" . date('m-d', time());

                    $downloads = $this->Download->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $arr_all_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_library_downloads = $this->Download->getAllLibraryDownloadsYear($library_id, $this->data['Report']['date'], $territory);
                    }
                    $arr_all_video_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_video_library_downloads = $this->Videodownload->getAllLibraryDownloadsYear($library_id, $this->data['Report']['date'], $territory);
                    }
                    $videoDownloads = $this->Videodownload->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $patronDownloads = $this->Downloadpatron->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    if ($library_id != "all") {
                        $patronBothDownloads = $this->Downloadpatron->getYearsBothDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    }
                    $patronVideoDownloads = $this->DownloadVideoPatron->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                    $genreVideoDownloads = $this->DownloadVideoGenre->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $territory);

                    $arr_all_patron_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_patron_downloads = $this->Downloadpatron->getTotalPatronDownloadYear($library_id, $this->data['Report']['date'], $territory);
                    }

                    $genreDownloads = $this->Downloadgenre->getYearsDownloadInformation($library_id, $this->data['Report']['date'], $territory);
                } elseif ($this->data['Report']['reports_daterange'] == 'manual') {
                    $date_arr = explode("/", $this->data['Report']['date_to']);
                    $compareDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1];

                    $downloads = $this->Download->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);

                    $arr_all_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_library_downloads = $this->Download->getAllLibraryDownloadsManual($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                    }
                    $arr_all_video_library_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_video_library_downloads = $this->Videodownload->getAllLibraryDownloadsManual($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                    }
                    $videoDownloads = $this->Videodownload->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);

                    $patronDownloads = $this->Downloadpatron->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                    if ($library_id != "all") {
                        $patronBothDownloads = $this->Downloadpatron->getManualBothDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                    }
                    $patronVideoDownloads = $this->DownloadVideoPatron->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                    $genreVideoDownloads = $this->DownloadVideoGenre->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);

                    $arr_all_patron_downloads = array();
                    if ($library_id == "all") {
                        $arr_all_patron_downloads = $this->Downloadpatron->getTotalPatronDownloadManual($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                    }

                    $genreDownloads = $this->Downloadgenre->getManualDownloadInformation($library_id, $this->data['Report']['date_from'], $this->data['Report']['date_to'], $territory);
                }
                $date = date('Y-m-d', time());
                if ($compareDate == $date) {
                    $currentPatronDownload = $this->Download->getCurrentPatronDownloads($library_id, $date, $territory, $all_Ids);
                    $currentGenreDownload = $this->Download->getCurrentGenreDownloads($library_id, $date, $territory, $all_Ids);
                    $currentVideoPatronDownload = $this->Videodownload->getCurrentPatronDownloads($library_id, $date, $territory, $all_Ids);
                    $currentVideoGenreDownload = $this->Videodownload->getCurrentGenreDownloads($library_id, $date, $territory, $all_Ids);
                    if ($library_id != "all") {
                        $currentPatronBothDownload = $this->Download->getCurrentPatronBothDownloads($library_id, $date, $territory, $all_Ids);
                    }
                } else {
                    $currentPatronDownload = array();
                    $currentGenreDownload = array();
                    $currentVideoPatronDownload = array();
                    $currentVideoGenreDownload = array();
                    $currentPatronBothDownload = array();
                }
                $this->set('dataRange', $this->data['Report']['reports_daterange']);
                $this->set('downloads', $downloads);
                $this->set('arr_all_library_downloads', $arr_all_library_downloads);
                $this->set('videoDownloads', $videoDownloads);
                $this->set('arr_all_video_library_downloads', $arr_all_video_library_downloads);
                $this->set('arr_all_patron_downloads', $arr_all_patron_downloads);
                if ($this->data['Report']['reports_daterange'] == 'day') {
                    if (!empty($currentPatronDownload)) {
                        foreach ($currentPatronDownload as $patronRecord) {
                            $i = count($patronDownloads);
                            $patronDownloads[$i]['Downloadpatron']['library_id'] = $patronRecord['Download']['library_id'];
                            $patronDownloads[$i]['Downloadpatron']['patron_id'] = $patronRecord['Download']['patron_id'];
                            $patronDownloads[$i]['Downloadpatron']['total'] = $patronRecord[0]['total'];
                        }
                    }
                    $this->set('patronDownloads', $patronDownloads);
                } else {
                    if (!empty($currentPatronDownload)) {
                        foreach ($currentPatronDownload as $patronRecord) {
                            if (!empty($patronDownloads[0])) {
                                $i = count($patronDownloads[0]);
                                $flag = false;
                                foreach ($patronDownloads[0] as $pkey => $patronDownload) {
                                    if ($patronRecord['Download']['patron_id'] == $patronDownload['Downloadpatron']['patron_id']) {
                                        $patronDownloads[0][$pkey][0]['total'] += $patronRecord[0]['total'];
                                        $flag = true;
                                        break;
                                    }
                                }
                                if ($flag == false) {
                                    $patronDownloads[0][$i]['Downloadpatron']['library_id'] = $patronRecord['Download']['library_id'];
                                    $patronDownloads[0][$i]['Downloadpatron']['patron_id'] = $patronRecord['Download']['patron_id'];
                                    $patronDownloads[0][$i][0]['total'] = $patronRecord[0]['total'];
                                }
                            } else {
                                $i = count($patronDownloads[0]);
                                $patronDownloads[0][$i]['Downloadpatron']['library_id'] = $patronRecord['Download']['patron_id'];
                                $patronDownloads[0][$i]['Downloadpatron']['patron_id'] = $patronRecord['Download']['patron_id'];
                                $patronDownloads[0][$i][0]['total'] = $patronRecord[0]['total'];
                            }
                        }
                        //die;
                    }
                    $this->set('patronDownloads', $patronDownloads[0]);
                }
                if ($library_id != "all") {
                    if ($this->data['Report']['reports_daterange'] == 'day') {
                        if (!empty($currentPatronBothDownload)) {
                            foreach ($currentPatronBothDownload as $patronRecord) {
                                $i = count($patronBothDownloads);
                                $patronBothDownloads[$i]['table1']['patron_id'] = $patronRecord['table1']['patron_id'];
                            }
                        }
                        $this->set('patronBothDownloads', $patronBothDownloads);
                    } else {
                        if (!empty($currentPatronBothDownload)) {
                            foreach ($currentPatronBothDownload as $patronRecord) {
                                if (!empty($patronBothDownloads)) {
                                    $i = count($patronBothDownloads);
                                    $flag = false;
                                    foreach ($patronBothDownloads as $pkey => $patronDownload) {
                                        if ($patronRecord['table1']['patron_id'] == $patronDownload['table1']['patron_id']) {
                                            $flag = true;
                                            break;
                                        }
                                    }
                                    if ($flag == false) {
                                        $patronBothDownloads[$i]['table1']['patron_id'] = $patronRecord['table1']['patron_id'];
                                    }
                                } else {
                                    $i = count($patronBothDownloads);
                                    $patronBothDownloads[$i]['table1']['patron_id'] = $patronRecord['table1']['patron_id'];
                                }
                            }
                            //die;
                        }
                        $this->set('patronBothDownloads', $patronBothDownloads);
                    }
                }

                if ($this->data['Report']['reports_daterange'] == 'day') {
                    if (!empty($currentVideoPatronDownload)) {
                        foreach ($currentVideoPatronDownload as $patronRecord) {
                            $i = count($patronVideoDownloads);
                            $patronVideoDownloads[$i]['DownloadVideoPatron']['library_id'] = $patronRecord['Videodownload']['library_id'];
                            $patronVideoDownloads[$i]['DownloadVideoPatron']['patron_id'] = $patronRecord['Videodownload']['patron_id'];
                            $patronVideoDownloads[$i]['DownloadVideoPatron']['total'] = $patronRecord[0]['total'];
                        }
                    }
                    $this->set('patronVideoDownloads', $patronVideoDownloads);
                } else {
                    if (!empty($currentVideoPatronDownload)) {
                        foreach ($currentVideoPatronDownload as $patronRecord) {
                            if (!empty($patronVideoDownloads[0])) {
                                $i = count($patronVideoDownloads[0]);
                                $flag = false;
                                foreach ($patronVideoDownloads[0] as $pkey => $patronDownload) {
                                    if ($patronRecord['Videodownload']['patron_id'] == $patronDownload['Videodownload']['patron_id']) {
                                        $patronVideoDownloads[0][$pkey][0]['total'] += $patronRecord[0]['total'];
                                        $flag = true;
                                        break;
                                    }
                                }
                                if ($flag == false) {
                                    $patronVideoDownloads[0][$i]['DownloadVideoPatron']['library_id'] = $patronRecord['Videodownload']['library_id'];
                                    $patronVideoDownloads[0][$i]['DownloadVideoPatron']['patron_id'] = $patronRecord['Videodownload']['patron_id'];
                                    $patronVideoDownloads[0][$i][0]['total'] = $patronRecord[0]['total'];
                                }
                            } else {
                                $i = count($patronVideoDownloads[0]);
                                $patronVideoDownloads[0][$i]['DownloadVideoPatron']['library_id'] = $patronRecord['Videodownload']['patron_id'];
                                $patronVideoDownloads[0][$i]['DownloadVideoPatron']['patron_id'] = $patronRecord['Videodownload']['patron_id'];
                                $patronVideoDownloads[0][$i][0]['total'] = $patronRecord[0]['total'];
                            }
                        }
                        //die;
                    }
                    $this->set('patronVideoDownloads', $patronVideoDownloads[0]);
                }

                if ($this->data['Report']['reports_daterange'] == 'day') {
                    if (!empty($currentGenreDownload)) {
                        foreach ($currentGenreDownload as $genreRecord) {
                            $i = count($genreDownloads);
                            $genreDownloads[$i]['Downloadgenre']['download_date'] = $genreRecord['table1']['day_downloaded'];
                            $genreDownloads[$i]['Downloadgenre']['library_id'] = $genreRecord['table1']['library_id'];
                            $genreDownloads[$i]['Downloadgenre']['genre_name'] = $genreRecord['table1']['Genre'];
                            $genreDownloads[$i]['Downloadgenre']['total'] = $genreRecord[0]['total'];
                        }
                    }
                    $this->set('genreDownloads', $genreDownloads);
                } else {
                    if (!empty($currentGenreDownload)) {
                        foreach ($currentGenreDownload as $genreRecord) {
                            if (!empty($genreDownloads[0])) {
                                $i = count($genreDownloads[0]);
                                $flag = false;
                                foreach ($genreDownloads[0] as $gkey => $genreDownload) {
                                    if ($genreRecord['table1']['Genre'] == $genreDownload['Downloadgenre']['genre_name']) {
                                        $genreDownloads[0][$gkey][0]['total'] += $genreRecord[0]['total'];
                                        $flag = true;
                                        break;
                                    }
                                }
                                if ($flag == false) {
                                    $genreDownloads[0][$i]['Downloadgenre']['download_date'] = $genreRecord['table1']['day_downloaded'];
                                    $genreDownloads[0][$i]['Downloadgenre']['library_id'] = $genreRecord['table1']['library_id'];
                                    $genreDownloads[0][$i]['Downloadgenre']['genre_name'] = $genreRecord['table1']['Genre'];
                                    $genreDownloads[0][$i][0]['total'] = $genreRecord[0]['total'];
                                }
                            } else {
                                $i = count($genreDownloads[0]);
                                $genreDownloads[0][$i]['Downloadgenre']['download_date'] = $genreRecord['table1']['day_downloaded'];
                                $genreDownloads[0][$i]['Downloadgenre']['library_id'] = $genreRecord['table1']['library_id'];
                                $genreDownloads[0][$i]['Downloadgenre']['genre_name'] = $genreRecord['table1']['Genre'];
                                $genreDownloads[0][$i][0]['total'] = $genreRecord[0]['total'];
                            }
                        }
                        //die;
                    }
                    $this->set('genreDownloads', $genreDownloads[0]);
                }
                if ($this->data['Report']['reports_daterange'] == 'day') {
                    if (!empty($currentVideoGenreDownload)) {
                        foreach ($currentVideoGenreDownload as $genreRecord) {
                            $i = count($genreVideoDownloads);
                            $genreVideoDownloads[$i]['DownloadVideoGenre']['download_date'] = $genreRecord['table1']['day_downloaded'];
                            $genreVideoDownloads[$i]['DownloadVideoGenre']['library_id'] = $genreRecord['table1']['library_id'];
                            $genreVideoDownloads[$i]['DownloadVideoGenre']['genre_name'] = $genreRecord['table1']['Genre'];
                            $genreVideoDownloads[$i]['DownloadVideoGenre']['total'] = $genreRecord[0]['total'];
                        }
                    }
                    $this->set('genreVideoDownloads', $genreVideoDownloads);
                } else {
                    if (!empty($currentVideoGenreDownload)) {
                        foreach ($currentVideoGenreDownload as $genreRecord) {
                            if (!empty($genreVideoDownloads[0])) {
                                $i = count($genreVideoDownloads[0]);
                                $flag = false;
                                foreach ($genreVideoDownloads[0] as $gkey => $genreDownload) {
                                    if ($genreRecord['table1']['Genre'] == $genreDownload['DownloadVideoGenre']['genre_name']) {
                                        $genreVideoDownloads[0][$gkey][0]['total'] += $genreRecord[0]['total'];
                                        $flag = true;
                                        break;
                                    }
                                }
                                if ($flag == false) {
                                    $genreVideoDownloads[0][$i]['DownloadVideoGenre']['download_date'] = $genreRecord['table1']['day_downloaded'];
                                    $genreVideoDownloads[0][$i]['DownloadVideoGenre']['library_id'] = $genreRecord['table1']['library_id'];
                                    $genreVideoDownloads[0][$i]['DownloadVideoGenre']['genre_name'] = $genreRecord['table1']['Genre'];
                                    $genreVideoDownloads[0][$i][0]['total'] = $genreRecord[0]['total'];
                                }
                            } else {
                                $i = count($genreVideoDownloads[0]);
                                $genreVideoDownloads[0][$i]['DownloadVideoGenre']['download_date'] = $genreRecord['table1']['day_downloaded'];
                                $genreVideoDownloads[0][$i]['DownloadVideoGenre']['library_id'] = $genreRecord['table1']['library_id'];
                                $genreVideoDownloads[0][$i]['DownloadVideoGenre']['genre_name'] = $genreRecord['table1']['Genre'];
                                $genreVideoDownloads[0][$i][0]['total'] = $genreRecord[0]['total'];
                            }
                        }
                        //die;
                    }
                    $this->set('genreVideoDownloads', $genreVideoDownloads[0]);
                }

                $this->layout = 'pdf';
                $this->render();
            } else {
                $this->Session->setFlash('Error occured while entering the Reports Setting fields', 'modal', array('class' => 'modal problem'));
                $this->redirect(array('action' => 'index'), null, true);
            }
        } else {
            $this->Session->setFlash('Error occured while entering the Reports Setting fields', 'modal', array('class' => 'modal problem'));
            $this->redirect(array('action' => 'index'), null, true);
        }
    }

    /*
      Function Name : admin_libraryrenewalreport
      Desc : actions for library renewal reports page
     */

    function admin_libraryrenewalreport() {
        if (isset($this->data)) {
//            Configure::write('debug', 0); // Otherwise we cannot use this method while developing
            $this->set("sitelibraries", $this->Library->find("all", array('order' => 'library_contract_start_date ASC', 'recursive' => -1)));
            if ($this->data['downloadType'] == 'pdf') {
                $this->layout = 'pdf';
                $this->render("/reports/admin_downloadLibraryRenewalReportAsPdf");
            } elseif ($this->data['downloadType'] == 'csv') {
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
        if ($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") == '') {
            $libraryAdminID = $this->Library->find("first", array("conditions" => array('library_admin_id' => $this->Session->read("Auth.User.id")), 'fields' => array('id', 'library_name'), 'recursive' => -1));
            $this->set('libraryID', $libraryAdminID["Library"]["id"]);
            $this->set('libraryname', $libraryAdminID["Library"]["library_name"]);
        } elseif ($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") != '') {
            $this->set('libraries', $this->Library->find('list', array("conditions" => array('Library.library_apikey' => $this->Session->read("Auth.User.consortium")), 'fields' => array('Library.library_name'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
            $this->set('libraryID', "");
        } else {
            $this->set('libraries', $this->Library->find('list', array('fields' => array('Library.library_name'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
            $this->set('libraryID', "");
        }
        if (isset($this->data)) {
//            Configure::write('debug', 0); // Otherwise we cannot use this method while developing
            $this->Report->set($this->data);
            if ($this->data['Report']['reports_daterange'] != 'manual') {
                $this->Report->setValidation('reports_date');
            } else {
                $this->Report->setValidation('reports_manual');
            }
            if ($this->Report->validates()) {
                $wishlists = $this->Wishlist->getWishListInformation($this->data['Report']['library_id'], $this->data['Report']['reports_daterange'], $this->data['Report']['date'], $this->data['Report']['date_from'], $this->data['Report']['date_to']);
                $this->set('wishlists', $wishlists);
                $arr = array();
                $this->set('errors', $arr);
                if ($this->data['Report']['downloadType'] == 'pdf') {
                    $this->layout = 'pdf';
                    $this->render("/reports/admin_downloadLibraryWishListReportAsPdf");
                } elseif ($this->data['Report']['downloadType'] == 'csv') {
                    $this->layout = false;
                    $this->render("/reports/admin_downloadLibraryWishListReportAsCsv");
                }
            } else {
                $this->Session->setFlash('Error occured while entering the Reports Setting fields', 'modal', array('class' => 'modal problem'));
                $arr = array();
                $this->set('wishlists', $arr);
                $this->set('errors', $this->Report->invalidFields());
            }
            $this->set('formAction', 'admin_librarywishlistreport');
            $this->set('getData', $this->data);
        } else {
            $this->set('formAction', 'admin_librarywishlistreport');
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
        if (!empty($this->params['named']['id'])) {//gets the values from the url in form  of array
//            Configure::write('debug', 0); // Otherwise we cannot use this method while developing
            $sonyReport = $this->SonyReport->find("first", array('conditions' => array('id' => base64_decode($this->params['named']['id']))));
            $path = "http://music.freegalmusic.com/freegalmusic/prod/EN/sony_reports"; // change the path to fit your websites document structure
            $fullPath = $path . "/" . $sonyReport['SonyReport']['report_name'];
            if ($fd = fopen($fullPath, "r")) {
                $fsize = filesize($fullPath);
                header("Content-type: application/octet-stream");
                header("Content-Disposition: filename=\"" . $sonyReport['SonyReport']['report_name'] . "\"");
                header("Content-length: $fsize");
                header("Cache-control: private"); //use this to open files directly
                while (!feof($fd)) {
                    $buffer = fread($fd, 2048);
                    echo $buffer;
                }
            }
            fclose($fd);
            exit;
        }
        $this->set("sonyReports", $this->paginate('SonyReport'));
    }

    function admin_getLibraryIds() {
//        Configure::write('debug', 0);
         //$libValue = isset($_REQUEST['lib_id'])? $_REQUEST['lib_id']:'';
        $data = '';
        if ($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") == '') {
            $var = $this->Library->find("list", array(
                "conditions" => array(
                    'Library.library_admin_id' => $this->Session->read("Auth.User.id"), 
//                    'Library.library_territory' => $_REQUEST['Territory']
                    ), 
                'fields' => array('Library.id', 'Library.library_name'), 
                'order' => 'Library.library_name ASC', 
                'recursive' => -1)
                    );
        } elseif ($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") != '') {
                          
            $var = $this->Library->find("list", array(
                "conditions" => array(
                    'Library.library_apikey' => $this->Session->read("Auth.User.consortium"), 
//                    'Library.library_territory' => $_REQUEST['Territory']
                    ), 
                'fields' => array('Library.id', 'Library.library_name'), 
                'order' => 'Library.library_name ASC', 
                'recursive' => -1));
        } else {
            $var = $this->Library->find('list', array(
//                'conditions' => array(
//                    'Library.library_territory' => $_REQUEST['Territory']
//                    ), 
                'fields' => array('Library.id', 'Library.library_name'),
                'order' => 'Library.library_name ASC', 
                'recursive' => -1));
            $data = "<option value='all'>All Libraries</option>";
        }
        
        return $var;
//        foreach ($var as $k => $v) {
//            
//            $selected= '';
//            if(isset($libValue) && $libValue == $k){
//                 $selected= 'selected';
//            }
//            
//            $data = $data . "<option value=" . $k . " ".$selected.">" . $v . "</option>";
//        }
//        print "<select class='select_fields' name='library_id' id='library_id'>" . $data . "</select>";
//        exit;
    }
    
    function admin_getLibraryIdsStream() {
      //Configure::write('debug', 2);
      
        //$territory = $_REQUEST['Territory'];        
       // $libValue = isset($_REQUEST['lib_id'])? $_REQUEST['lib_id']:'';
        $data = '';
        
        if ($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") == '') {
          echo '1';
            $var = $this->Library->find("list", array(
                "conditions" => array(
                    'Library.library_admin_id' => $this->Session->read("Auth.User.id"), 
                    'Library.library_type = 2'),  
                'fields' => array('Library.id', 'Library.library_name'), 
                'order' => 'Library.library_name ASC', 
                'recursive' => -1)
                    );
            
        } elseif ($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") != '') {
          echo '2';
               $var = $this->Library->find("list", array(
                "conditions" => array(
                    'Library.library_apikey' => $this->Session->read("Auth.User.consortium"), 
                    'Library.library_type = 2'), 
                'fields' => array('Library.id', 'Library.library_name'), 
                'order' => 'Library.library_name ASC', 
                'recursive' => -1));
               echo '<pre>';
        print_r($var);
        die;
        } else {
         echo '3';
            $var = $this->Library->find('list', array(
                'conditions' => array(
                   // 'Library.library_territory' => $territory, 
                    'Library.library_type =2'), 
                'fields' => array('Library.id', 'Library.library_name'), 
                'order' => 'Library.library_name ASC', 
                'recursive' => -1)
                    );
            $data = "<option value='all'>All Libraries</option>";
        }
        
         return $var;
         
//        foreach ($var as $k => $v) {
//            
//            $selected= '';
//            if(isset($libValue) && $libValue == $k){
//                 $selected= 'selected';
//            }
//            
//            $data = $data . "<option value=" . $k . " ".$selected.">" . $v . "</option>";
//        }
//        print "<select class='select_fields' name='library_id' id='library_id'>" . $data . "</select>";
//        exit;
    }

    function admin_unlimited() {
        if ($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") == '') {
            $libraryAdminID = $this->Library->find("first", array("conditions" => array('library_admin_id' => $this->Session->read("Auth.User.id")), 'fields' => array('id', 'library_name', 'library_territory'), 'recursive' => -1));
            $this->set('libraryID', $libraryAdminID["Library"]["id"]);
            $this->set('libraryname', $libraryAdminID["Library"]["library_name"]);
        } else {
            $this->set('libraryID', "");
        }
        if (isset($this->data)) {
            $all_Ids = '';
            $sql = "SELECT id from libraries where library_unlimited = '1'";
            $result = mysql_query($sql);
            while ($row = mysql_fetch_assoc($result)) {
                $all_Ids = $all_Ids . $row["id"] . ",";
            }
            $lib_condition = "and library_id IN (" . rtrim($all_Ids, ",") . ")";
            $date_arr = explode("/", $this->data['Report']['date']);

            $startDate = date("Y-m-d", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) . '/01/' . date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) . ' 00:00:00')) . " 00:00:00";
            $endDate = date("Y-m-d", strtotime('-1 second', strtotime('+1 month', strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) . '/01/' . date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) . ' 00:00:00')))) . " 23:59:59";
            $conditions = array(
                'created BETWEEN "' . $startDate . '" and "' . $endDate . '" ' . $lib_condition . " AND 1 = 1 GROUP BY id"
            );
            $downloadResult = $this->Download->find('all', array('conditions' => array('created BETWEEN "' . $startDate . '" and "' . $endDate . '" ' . $lib_condition, '1 = 1 GROUP BY library_id'), 'fields' => array('library_id', 'COUNT(id) AS totalDownloads'), 'recursive' => -1));
            foreach ($downloadResult as $k => $v) {
                $nameQuery = "SELECT library_name FROM libraries WHERE id=" . $v['Download']['library_id'];
                $row = mysql_fetch_assoc(mysql_query($nameQuery));
                $downloadResult[$k]['Download']['library_name'] = $row['library_name'];
                $purchaseQuery = "SELECT purchased_amount FROM library_purchases WHERE
								  library_id='" . $v['Download']['library_id'] . "' ORDER BY created DESC";
                $row = mysql_fetch_assoc(mysql_query($purchaseQuery));
                $downloadResult[$k]['Download']['library_price'] = $row['purchased_amount'];
                $downloadResult[$k]['Download']['monthly_price'] = $row['purchased_amount'] / 12;
                $downloadResult[$k]['Download']['download_price'] = ($row['purchased_amount'] / 12) / $v[0]['totalDownloads'];
                $downloadResult[$k]['Download']['mechanical_royalty'] = ($v[0]['totalDownloads'] * (.091 / 2));
            }
            $this->set('formAction', 'admin_unlimited');
            $this->set('date', $this->data['Report']['date']);
            $this->set('downloadResult', $downloadResult);
            $this->set('month', date("F", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) . '/01/' . date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))));
        } else {
            $this->set('formAction', 'admin_unlimited');
        }
    }

    function admin_unlimitedcsv() {
//        Configure::write('debug', 0);
        $this->layout = false;
        $all_Ids = '';
        $sql = "SELECT id from libraries where library_unlimited = '1'";
        $result = mysql_query($sql);
        while ($row = mysql_fetch_assoc($result)) {
            $all_Ids = $all_Ids . $row["id"] . ",";
        }
        $lib_condition = "and library_id IN (" . rtrim($all_Ids, ",") . ")";
        $date_arr = explode("/", $this->data['Report']['date']);
        $startDate = date("Y-m-d", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) . '/01/' . date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) . ' 00:00:00')) . " 00:00:00";
        $endDate = date("Y-m-d", strtotime('-1 second', strtotime('+1 month', strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) . '/01/' . date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) . ' 00:00:00')))) . " 23:59:59";
        $conditions = array(
            'created BETWEEN "' . $startDate . '" and "' . $endDate . '" ' . $lib_condition . " AND 1 = 1 GROUP BY id"
        );
        $downloadResult = $this->Download->find('all', array('conditions' => array('created BETWEEN "' . $startDate . '" and "' . $endDate . '" ' . $lib_condition, '1 = 1 GROUP BY library_id'), 'fields' => array('library_id', 'COUNT(id) AS totalDownloads'), 'recursive' => -1));
        foreach ($downloadResult as $k => $v) {
            $nameQuery = "SELECT library_name FROM libraries WHERE id=" . $v['Download']['library_id'];
            $row = mysql_fetch_assoc(mysql_query($nameQuery));
            $downloadResult[$k]['Download']['library_name'] = $row['library_name'];
            $purchaseQuery = "SELECT purchased_amount FROM library_purchases WHERE
							  library_id='" . $v['Download']['library_id'] . "' ORDER BY created DESC";
            $row = mysql_fetch_assoc(mysql_query($purchaseQuery));
            $downloadResult[$k]['Download']['library_price'] = $row['purchased_amount'];
            $downloadResult[$k]['Download']['monthly_price'] = $row['purchased_amount'] / 12;
            $downloadResult[$k]['Download']['download_price'] = ($row['purchased_amount'] / 12) / $v[0]['totalDownloads'];
            $downloadResult[$k]['Download']['mechanical_royalty'] = ($v[0]['totalDownloads'] * (.091 / 2));
        }
        $this->set('date', $this->data['Report']['date']);
        $this->set('month', date("F", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) . '/01/' . date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))));
        $this->set('downloadResult', $downloadResult);
    }

    function admin_unlimitedpdf() {
//        Configure::write('debug', 0);
        $this->layout = false;
        $all_Ids = '';
        $sql = "SELECT id from libraries where library_unlimited = '1'";
        $result = mysql_query($sql);
        while ($row = mysql_fetch_assoc($result)) {
            $all_Ids = $all_Ids . $row["id"] . ",";
        }
        $lib_condition = "and library_id IN (" . rtrim($all_Ids, ",") . ")";
        $date_arr = explode("/", $this->data['Report']['date']);
        $startDate = date("Y-m-d", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) . '/01/' . date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) . ' 00:00:00')) . " 00:00:00";
        $endDate = date("Y-m-d", strtotime('-1 second', strtotime('+1 month', strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) . '/01/' . date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) . ' 00:00:00')))) . " 23:59:59";
        $conditions = array(
            'created BETWEEN "' . $startDate . '" and "' . $endDate . '" ' . $lib_condition . " AND 1 = 1 GROUP BY id"
        );
        $downloadResult = $this->Download->find('all', array('conditions' => array('created BETWEEN "' . $startDate . '" and "' . $endDate . '" ' . $lib_condition, '1 = 1 GROUP BY library_id'), 'fields' => array('library_id', 'COUNT(id) AS totalDownloads'), 'recursive' => -1));
        foreach ($downloadResult as $k => $v) {
            $nameQuery = "SELECT library_name FROM libraries WHERE id=" . $v['Download']['library_id'];
            $row = mysql_fetch_assoc(mysql_query($nameQuery));
            $downloadResult[$k]['Download']['library_name'] = $row['library_name'];
            $purchaseQuery = "SELECT purchased_amount FROM library_purchases WHERE
							  library_id='" . $v['Download']['library_id'] . "' ORDER BY created DESC";
            $row = mysql_fetch_assoc(mysql_query($purchaseQuery));
            $downloadResult[$k]['Download']['library_price'] = $row['purchased_amount'];
            $downloadResult[$k]['Download']['monthly_price'] = $row['purchased_amount'] / 12;
            $downloadResult[$k]['Download']['download_price'] = ($row['purchased_amount'] / 12) / $v[0]['totalDownloads'];
            $downloadResult[$k]['Download']['mechanical_royalty'] = ($v[0]['totalDownloads'] * (.091 / 2));
        }
        $this->set('date', $this->data['Report']['date']);
        $this->set('month', date("F", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) . '/01/' . date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))));
        $this->set('downloadResult', $downloadResult);
    }

    function admin_consortium() {
        ini_set('memory_limit', '512M');
        set_time_limit(0);
        if ((!$this->Session->read('Auth.User.type_id')) && ($this->Session->read('Auth.User.type_id') != 1)) {
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }

        if ($this->Session->read("Auth.User.type_id") == 1) {
            $consortium = $this->Consortium->find('list', array('fields' => array('consortium_name', 'consortium_name'), 'order' => 'consortium_name', 'recursive' => -1, 'group' => 'consortium_name'));
            $this->set('consortium', $consortium);
        } else {
            $consortium = $this->Consortium->find('list', array('conditions' => array('consortium_name' => $this->Session->read("Auth.User.consortium")), 'fields' => array('consortium_name', 'consortium_name'), 'order' => 'consortium_name', 'recursive' => -1, 'group' => 'consortium_name'));
            $this->set('consortium', $consortium);
        }

        $this->set('libraryID', "");
        if (isset($this->data)) {
            $consortium_id = $this->data['Report']['library_apikey'];
            $this->Report->set($this->data);
            if ($this->data['Report']['reports_daterange'] != 'manual') {
                $this->Report->setValidation('reports_date');
            } else {
                $this->Report->setValidation('reports_manual');
            }
            $all_Ids = '';
            $sql = "SELECT id from libraries where library_apikey = '" . $consortium_id . "'";
            $result = mysql_query($sql);
            while ($row = mysql_fetch_assoc($result)) {
                $all_Ids = $all_Ids . $row["id"] . ",";
            }
            $libraryData = $this->Library->find('all', array('fields' => array('Library.library_name', 'Library.library_unlimited', 'Library.library_available_downloads'), 'conditions' => array('Library.id IN (' . rtrim($all_Ids, ",") . ')'), 'order' => 'Library.library_name ASC', 'recursive' => -1));
            $this->set('libraries_download', $libraryData);
            if ($this->Report->validates()) {
                if ($this->data['Report']['reports_daterange'] == 'day') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getConsortiumDaysDownloadInformation(rtrim($all_Ids, ",'"), $this->data['Report']['date']);
                } elseif ($this->data['Report']['reports_daterange'] == 'week') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getConsortiumWeeksDownloadInformation(rtrim($all_Ids, ",'"), $this->data['Report']['date']);
                } elseif ($this->data['Report']['reports_daterange'] == 'month') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getConsortiumMonthsDownloadInformation(rtrim($all_Ids, ",'"), $this->data['Report']['date']);
                } elseif ($this->data['Report']['reports_daterange'] == 'year') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getConsortiumYearsDownloadInformation(rtrim($all_Ids, ",'"), $this->data['Report']['date']);
                } elseif ($this->data['Report']['reports_daterange'] == 'manual') {
                    list($downloads, $patronDownloads, $genreDownloads) = $this->Download->getConsortiumManualDownloadInformation(rtrim($all_Ids, ",'"), $this->data['Report']['date']);
                }
                $this->set('downloads', $downloads);
                $this->set('patronDownloads', $patronDownloads);
                $this->set('genreDownloads', $genreDownloads);
                if ($this->data['Report']['downloadType'] == 'pdf') {
                    $this->layout = 'pdf';
                    $this->render("/reports/admin_downloadConsortiumRenewalReportAsPdf");
                } elseif ($this->data['Report']['downloadType'] == 'csv') {
                    Configure::write('debug', 0);
                    $this->layout = false;
                    $this->render("/reports/admin_downloadConsortiumRenewalReportAsCsv");
                }
            } else {
                $this->Session->setFlash('Error occured while entering the Reports Setting fields', 'modal', array('class' => 'modal problem'));
                $arr = array();
                $this->set('wishlists', $arr);
                $this->set('errors', $this->Report->invalidFields());
            }
            $this->set('formAction', 'admin_consortium');
            $this->set('getData', $this->data);
        } else {
            $this->set('formAction', 'admin_consortium');
            $arr = array();
            $this->set('getData', $arr);
            $this->set('consortiumData', $arr);
            $this->set('errors', $arr);
        }
    }

    /*
      Function Name : admin_streamingreport
      Desc : actions for streaming report page
     */

    function admin_streamingreport() {       
       
       ini_set('memory_limit', '512M');
        set_time_limit(0);
        if ((!$this->Session->read('Auth.User.type_id')) && ($this->Session->read('Auth.User.type_id') != 1)) {
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }
        
        
        if ($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") == '') {
            $libraryAdminID = $this->Library->find("first", array("conditions" => array('library_admin_id' => $this->Session->read("Auth.User.id"),'library_type' => '2'), 'fields' => array('id', 'library_name', 'library_territory'), 'recursive' => -1));
            $this->set('libraryID', $libraryAdminID["Library"]["id"]);
            $this->set('libraryname', $libraryAdminID["Library"]["library_name"]);
            
        } else {
           
            if ($this->data['Report']['Territory'] == '') {
                
                //$this->set('libraries', $this->Library->find('list', array('fields' => array('Library.library_name'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
                $this->set('libraries', $this->admin_getLibraryIdsStream());
            } else {
                
                $this->set('libraries', $this->admin_getLibraryIdsStream());
            }
            $this->set('libraryID', "");
        }
        
       
        if (isset($this->data)) {
            //Configure::write('debug',0); // Otherwise we cannot use this method while developing
            $all_Ids = '';
            $this->Report->set($this->data);
            if (isset($_REQUEST['library_id'])) {
                $library_id = $_REQUEST['library_id'];
            } else {
                $library_id = $this->data['Report']['library_id'];
            }
            $this->set('library_id', $library_id);
            if ($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") == '') {
                $territory = $libraryAdminID["Library"]["library_territory"];
            } else {
                $territory = $this->data['Report']['Territory'];
            }
            if ($this->data['Report']['reports_daterange'] != 'manual') {
                $this->Report->setValidation('reports_date');
            } else {
                $this->Report->setValidation('reports_manual');
            }
            /*if ($territory != '') {
                if ($library_id == 'all') {
                    $sql = "SELECT id from libraries where library_territory = '" . $territory . "'";
                    $result = mysql_query($sql);
                    while ($row = mysql_fetch_assoc($result)) {
                        $all_Ids = $all_Ids . $row["id"] . ",";
                    }
                    $lib_condition = "and library_id IN (" . rtrim($all_Ids, ",'") . ")";
                    $this->set('libraries_download', $this->Library->find('all', array('fields' => array('Library.library_name', 'Library.library_unlimited', 'Library.library_available_downloads'), 'conditions' => array('Library.id IN (' . rtrim($all_Ids, ",") . ')'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
                } else {
                    $this->set('libraries_download', $this->Library->find('all', array('fields' => array('Library.library_name', 'Library.library_unlimited', 'Library.library_available_downloads'), 'conditions' => array('Library.id = ' . $library_id, 'Library.library_territory= "' . $territory . '"'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
                }
            }*/
            if ($this->Report->validates()) {
                if ($this->data['Report']['reports_daterange'] == 'day') {
                    /*$date_arr = explode("/", $this->data['Report']['date']);
                    $compareDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1];*/
                    $streamingInfo = $this->StreamingHistory->getDaysStreamedInformation($library_id, $this->data['Report']['date'], $territory,'day');
                    if ($library_id != "all") {
                        $streamingHours = $streamingInfo[0][0]['total_streamed'];
                    }else{
                        $streamingHours = $streamingInfo;
                    }
                   
                    $patronStreaminInfoRes = $this->StreamingHistory->getDaysStreamedByPetronInformation($library_id, $this->data['Report']['date'], $territory,'day');
                    if ($library_id != "all") {
                        $patronStreaminInfo = $patronStreaminInfoRes[0][0]['total_patrons'];
                    }else{
                        $patronStreaminInfo = $patronStreaminInfoRes;
                    }
//                    echo "<pre>";print_r($patronStreaminInfo);exit;
                    //commenting since don't need to display this information
                    $arr_day_streaming_report = array();
                    //$arr_day_streaming_report = $this->StreamingHistory->getDayStreamingReportingPeriod($library_id, $this->data['Report']['date'], $territory,'day');

                    $patronStreamedInformation = $this->StreamingHistory->getPatronStreamingDay($library_id, $this->data['Report']['date'], $territory,'day');

                    $genreDayStremed = $this->StreamingHistory->getDaysGenreStramedInformation($library_id, $this->data['Report']['date'], $territory,'day');
                    
                } elseif ($this->data['Report']['reports_daterange'] == 'week') {
                    $date_arr = explode("/", $this->data['Report']['date']);
                    if (date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) == 0) {
                        if (mktime(23, 59, 59, $date_arr[0], ($date_arr[1] - date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2]) > time()) {
                            $compareDate = date('Y-m-d', time());
                        } else {
                            $compareDate = date('Y-m-d', mktime(23, 59, 59, $date_arr[0], ($date_arr[1] - date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2]));
                        }
                    } else {
                        if (mktime(23, 59, 59, $date_arr[0], ($date_arr[1] - date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2]))) + 7, $date_arr[2]) > time()) {
                            $compareDate = date('Y-m-d', time());
                        } else {
                            $compareDate = date('Y-m-d', mktime(23, 59, 59, $date_arr[0], ($date_arr[1] - date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2]))) + 7, $date_arr[2]));
                        }
                    }
                    $streamingInfo = $this->StreamingHistory->getDaysStreamedInformation($library_id, $this->data['Report']['date'], $territory,'week');
                    if ($library_id != "all") {
                        $streamingHours = $streamingInfo[0][0]['total_streamed'];
                    }else{
                        $streamingHours = $streamingInfo;
                    }
                   
                    $patronStreaminInfoRes = $this->StreamingHistory->getDaysStreamedByPetronInformation($library_id, $this->data['Report']['date'], $territory,'week');
                    if ($library_id != "all") {
                        $patronStreaminInfo = $patronStreaminInfoRes[0][0]['total_patrons'];
                    }else{
                        $patronStreaminInfo = $patronStreaminInfoRes;
                    }
//                    echo "<pre>";print_r($patronStreaminInfo);exit;
                    //commenting since don't need to display this information
                    $arr_day_streaming_report = array();
                    //$arr_day_streaming_report = $this->StreamingHistory->getDayStreamingReportingPeriod($library_id, $this->data['Report']['date'], $territory,'week');

                    $patronStreamedInformation = $this->StreamingHistory->getPatronStreamingDay($library_id, $this->data['Report']['date'], $territory,'week');

                    $genreDayStremed = $this->StreamingHistory->getDaysGenreStramedInformation($library_id, $this->data['Report']['date'], $territory,'week');
                } elseif ($this->data['Report']['reports_daterange'] == 'month') {
                    $date_arr = explode("/", $this->data['Report']['date']);
                    $compareDate = $date_arr[2] . "-" . $date_arr[0] . "-" . date('d', time());

                    $streamingInfo = $this->StreamingHistory->getDaysStreamedInformation($library_id, $this->data['Report']['date'], $territory,'month');
                    if ($library_id != "all") {
                        $streamingHours = $streamingInfo[0][0]['total_streamed'];
                    }else{
                        $streamingHours = $streamingInfo;
                    }
                   
                    $patronStreaminInfoRes = $this->StreamingHistory->getDaysStreamedByPetronInformation($library_id, $this->data['Report']['date'], $territory,'month');
                    if ($library_id != "all") {
                        $patronStreaminInfo = $patronStreaminInfoRes[0][0]['total_patrons'];
                    }else{
                        $patronStreaminInfo = $patronStreaminInfoRes;
                    }
//                    echo "<pre>";print_r($patronStreaminInfo);exit;
                    //commenting since don't need to display this information
                    $arr_day_streaming_report = array();
                    //$arr_day_streaming_report = $this->StreamingHistory->getDayStreamingReportingPeriod($library_id, $this->data['Report']['date'], $territory,'month');

                    $patronStreamedInformation = $this->StreamingHistory->getPatronStreamingDay($library_id, $this->data['Report']['date'], $territory,'month');

                    $genreDayStremed = $this->StreamingHistory->getDaysGenreStramedInformation($library_id, $this->data['Report']['date'], $territory,'month');
                } elseif ($this->data['Report']['reports_daterange'] == 'manual') {
                    $date_arr = explode("/", $this->data['Report']['date_to']);
                    $compareDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1];
//$this->data['Report']['date_from'], $this->data['Report']['date_to']
                    $datesInfo=array($this->data['Report']['date_from'],$this->data['Report']['date_to']);
                    $streamingInfo = $this->StreamingHistory->getDaysStreamedInformation($library_id,$datesInfo, $territory,'manual');
                    if ($library_id != "all") {
                        $streamingHours = $streamingInfo[0][0]['total_streamed'];
                    }else{
                        $streamingHours = $streamingInfo;
                    }
                   
                    $patronStreaminInfoRes = $this->StreamingHistory->getDaysStreamedByPetronInformation($library_id, $datesInfo, $territory,'manual');
                    if ($library_id != "all") {
                        $patronStreaminInfo = $patronStreaminInfoRes[0][0]['total_patrons'];
                    }else{
                        $patronStreaminInfo = $patronStreaminInfoRes;
                    }
//                    echo "<pre>";print_r($patronStreaminInfo);exit;
                    //commenting since don't need to display this information
                    $arr_day_streaming_report = array();
                   // $arr_day_streaming_report = $this->StreamingHistory->getDayStreamingReportingPeriod($library_id, $datesInfo, $territory,'manual');

                    $patronStreamedInformation = $this->StreamingHistory->getPatronStreamingDay($library_id, $datesInfo, $territory,'manual');

                    $genreDayStremed = $this->StreamingHistory->getDaysGenreStramedInformation($library_id, $datesInfo, $territory,'manual');
                }

                $date = date('Y-m-d', time());
                //$date = '2013-06-28';
                if ($compareDate == $date) {
                    $currentPatronDownload = $this->Download->getCurrentPatronDownloads($library_id, $date, $territory, $all_Ids);
                    if ($library_id != "all") {
                        $currentPatronBothDownload = $this->Download->getCurrentPatronBothDownloads($library_id, $date, $territory, $all_Ids);
                    }
                    $currentGenreDownload = $this->Download->getCurrentGenreDownloads($library_id, $date, $territory, $all_Ids);
                } else {
                    $currentPatronDownload = array();
                    $currentGenreDownload = array();
                    $currentPatronBothDownload = array();
                }

                $this->set('streamingHours', $streamingHours);
//                $this->set('arr_all_library_downloads', $arr_all_library_downloads);
//                $this->set('arr_all_patron_downloads', $arr_all_patron_downloads);

                /*if ($this->data['Report']['reports_daterange'] == 'day') {
                    if (!empty($currentPatronDownload)) {
                        foreach ($currentPatronDownload as $patronRecord) {
                            $i = count($patronDownloads);
                            $patronDownloads[$i]['Downloadpatron']['library_id'] = $patronRecord['Download']['library_id'];
                            $patronDownloads[$i]['Downloadpatron']['patron_id'] = $patronRecord['Download']['patron_id'];
                            $patronDownloads[$i]['Downloadpatron']['total'] = $patronRecord[0]['total'];
                        }
                    }*/
                    $this->set('patronStreamedInfo', $patronStreaminInfo);
                    $this->set('dayStreamingInfo', $arr_day_streaming_report);
                    $this->set('patronStreamedDetailedInfo', $patronStreamedInformation);
                    $this->set('genreDayStremedInfo', $genreDayStremed);
                /*} else {
                    if (!empty($currentPatronDownload)) {
                        foreach ($currentPatronDownload as $patronRecord) {
                            if (!empty($patronDownloads[0])) {
                                $i = count($patronDownloads[0]);
                                $flag = false;
                                foreach ($patronDownloads[0] as $pkey => $patronDownload) {
                                    if ($patronRecord['Download']['patron_id'] == $patronDownload['Downloadpatron']['patron_id']) {
                                        $patronDownloads[0][$pkey][0]['total'] += $patronRecord[0]['total'];
                                        $flag = true;
                                        break;
                                    }
                                }
                                if ($flag == false) {
                                    $patronDownloads[0][$i]['Downloadpatron']['library_id'] = $patronRecord['Download']['library_id'];
                                    $patronDownloads[0][$i]['Downloadpatron']['patron_id'] = $patronRecord['Download']['patron_id'];
                                    $patronDownloads[0][$i][0]['total'] = $patronRecord[0]['total'];
                                }
                            } else {
                                $i = count($patronDownloads[0]);
                                $patronDownloads[0][$i]['Downloadpatron']['library_id'] = $patronRecord['Download']['patron_id'];
                                $patronDownloads[0][$i]['Downloadpatron']['patron_id'] = $patronRecord['Download']['patron_id'];
                                $patronDownloads[0][$i][0]['total'] = $patronRecord[0]['total'];
                            }
                        }
                        //die;
                    }
                    $this->set('patronStramedInfo', $patronDownloads[0]);
                }
                if ($library_id != "all") {
                    if ($this->data['Report']['reports_daterange'] == 'day') {
                        if (!empty($currentPatronBothDownload)) {
                            foreach ($currentPatronBothDownload as $patronRecord) {
                                $i = count($patronBothDownloads);
                                $patronBothDownloads[$i]['table1']['patron_id'] = $patronRecord['table1']['patron_id'];
                            }
                        }
                        $this->set('patronBothDownloads', $patronBothDownloads);
                    } else {
                        if (!empty($currentPatronBothDownload)) {
                            foreach ($currentPatronBothDownload as $patronRecord) {
                                if (!empty($patronBothDownloads)) {
                                    $i = count($patronBothDownloads);
                                    $flag = false;
                                    foreach ($patronBothDownloads as $pkey => $patronDownload) {
                                        if ($patronRecord['table1']['patron_id'] == $patronDownload['table1']['patron_id']) {
                                            $flag = true;
                                            break;
                                        }
                                    }
                                    if ($flag == false) {
                                        $patronBothDownloads[$i]['table1']['patron_id'] = $patronRecord['table1']['patron_id'];
                                    }
                                } else {
                                    $i = count($patronBothDownloads);
                                    $patronBothDownloads[$i]['table1']['patron_id'] = $patronRecord['table1']['patron_id'];
                                }
                            }
                            //die;
                        }
                        $this->set('patronBothDownloads', $patronBothDownloads);
                    }
                }
                
                if ($this->data['Report']['reports_daterange'] == 'day') {
                    if (!empty($currentGenreDownload)) {
                        foreach ($currentGenreDownload as $genreRecord) {
                            $i = count($genreDownloads);
                            $genreDownloads[$i]['Downloadgenre']['download_date'] = $genreRecord['table1']['day_downloaded'];
                            $genreDownloads[$i]['Downloadgenre']['library_id'] = $genreRecord['table1']['library_id'];
                            $genreDownloads[$i]['Downloadgenre']['genre_name'] = $genreRecord['table1']['Genre'];
                            $genreDownloads[$i]['Downloadgenre']['total'] = $genreRecord[0]['total'];
                        }
                    }
                    $this->set('genreDownloads', $genreDownloads);
                } else {
                    if (!empty($currentGenreDownload)) {
                        foreach ($currentGenreDownload as $genreRecord) {
                            if (!empty($genreDownloads[0])) {
                                $i = count($genreDownloads[0]);
                                $flag = false;
                                foreach ($genreDownloads[0] as $gkey => $genreDownload) {
                                    if ($genreRecord['table1']['Genre'] == $genreDownload['Downloadgenre']['genre_name']) {
                                        $genreDownloads[0][$gkey][0]['total'] += $genreRecord[0]['total'];
                                        $flag = true;
                                        break;
                                    }
                                }
                                if ($flag == false) {
                                    $genreDownloads[0][$i]['Downloadgenre']['download_date'] = $genreRecord['table1']['day_downloaded'];
                                    $genreDownloads[0][$i]['Downloadgenre']['library_id'] = $genreRecord['table1']['library_id'];
                                    $genreDownloads[0][$i]['Downloadgenre']['genre_name'] = $genreRecord['table1']['Genre'];
                                    $genreDownloads[0][$i][0]['total'] = $genreRecord[0]['total'];
                                }
                            } else {
                                $i = count($genreDownloads[0]);
                                $genreDownloads[0][$i]['Downloadgenre']['download_date'] = $genreRecord['table1']['day_downloaded'];
                                $genreDownloads[0][$i]['Downloadgenre']['library_id'] = $genreRecord['table1']['library_id'];
                                $genreDownloads[0][$i]['Downloadgenre']['genre_name'] = $genreRecord['table1']['Genre'];
                                $genreDownloads[0][$i][0]['total'] = $genreRecord[0]['total'];
                            }
                        }
                        //die;
                    }
                    $this->set('genreDownloads', $genreDownloads[0]);
                }
                */
                $arr = array();
                $this->set('errors', $arr);
            } else {
                $this->Session->setFlash('Error occured while entering the Reports Setting fields', 'modal', array('class' => 'modal problem'));
                $arr = array();
                $this->set('downloads', $arr);
                $this->set('errors', $this->Report->invalidFields());
            }
            $this->set('formAction', 'admin_streamingreport');
            $this->set('getData', $this->data);
        } else {
            $this->set('formAction', 'admin_streamingreport');
            $this->set('library_id', '');
            $arr = array();
            $this->set('getData', $arr);
            $this->set('downloads', $arr);
            $this->set('errors', $arr);
        }
       
        $this->set('territory', $this->Territory->find('list', array('fields' => array('Territory', 'Territory'))));
        if($this->params['pass'][0]=='csv'){
            if ($library_id != "all") {
                
            }
            $this->autoRender=false;
            $this->layout=NULL;
            $this->render('admin_download_streaming_report_as_csv');
        }
        if($this->params['pass'][0]=='pdf'){
            $this->autoRender=false;
            $this->layout=NULL;
            $this->render('admin_download_streaming_report_as_pdf');
        }
    }
    
}

?>
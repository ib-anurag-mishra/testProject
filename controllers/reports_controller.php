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
    var $uses = array( 'Library', 'User', 'Download', 'Report' );
    
    public function admin_index()
    {
        if(isset($this->data)) {
            $this->Report->set($this->data);
            if($this->Report->validates()) {
                if($this->data['Report']['reports_daterange'] == 'day') {
                    $downloads = $this->Download->getDaysDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'week') {
                    $downloads = $this->Download->getWeeksDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'month') {
                    $downloads = $this->Download->getMonthsDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'year') {
                    $downloads = $this->Download->getYearsDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'manual') {
                    $downloads = $this->Download->getManualDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date_from'], $this->data['Report']['date_to']);
                }
                $this->set('downloads', $downloads);
            }
            else {
                $this->Session->setFlash( 'Error occured while entering the Reports Setting fields', 'modal', array( 'class' => 'modal problem' ) );
                $arr = array();
                $this->set('downloads', $arr);
            }
            $this -> set( 'formAction', 'admin_index' );
            $this->set('libraries', $this->Library->find('list', array('fields' => array('Library.library_name'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
            $this->set('getData', $this->data);
        }
        else {
            $this -> set( 'formAction', 'admin_index' );
            $this->set('libraries', $this->Library->find('list', array('fields' => array('Library.library_name'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
            $arr = array();
            $this->set('getData', $arr);
            $this->set('downloads', $arr);
        }
    }
    
    public function admin_downloadAsCsv()
    {
        Configure::write('debug', 0);
        $this->layout = false;
        if(isset($this->data)) {
            $this->Report->set($this->data);
            if($this->Report->validates()) {
                if($this->data['Report']['reports_daterange'] == 'day') {
                    $downloads = $this->Download->getDaysDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'week') {
                    $downloads = $this->Download->getWeeksDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'month') {
                    $downloads = $this->Download->getMonthsDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'year') {
                    $downloads = $this->Download->getYearsDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'manual') {
                    $downloads = $this->Download->getManualDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date_from'], $this->data['Report']['date_to']);
                }
                $this->set('downloads', $downloads);
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
    
    public function admin_downloadAsPdf()
    {
        Configure::write('debug',0); // Otherwise we cannot use this method while developing 
        if(isset($this->data)) {
            $this->Report->set($this->data);
            if($this->Report->validates()) {
                if($this->data['Report']['reports_daterange'] == 'day') {
                    $downloads = $this->Download->getDaysDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'week') {
                    $downloads = $this->Download->getWeeksDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'month') {
                    $downloads = $this->Download->getMonthsDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'year') {
                    $downloads = $this->Download->getYearsDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date']);
                }
                elseif($this->data['Report']['reports_daterange'] == 'manual') {
                    $downloads = $this->Download->getManualDownloadInformation($this->data['Report']['library_id'], $this->data['Report']['date_from'], $this->data['Report']['date_to']);
                }
                $this->set('downloads', $downloads);
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
}
?>
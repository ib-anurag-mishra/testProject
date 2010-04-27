<?php
/*
 File Name : libraries_controller.php
 File Description : Library controller page
 Author : maycreate
 */
Class LibrariesController extends AppController
{
    var $name = 'Libraries';
    var $layout = 'admin';
    var $helpers = array( 'Html', 'Ajax', 'Javascript', 'Form', 'Session');
    var $components = array( 'Session', 'Auth', 'Acl', 'RequestHandler','ValidatePatron','Downloads');
    var $uses = array( 'Library', 'User', 'LibraryPurchase', 'Download', 'Currentpatron');
    
    function beforeFilter() {	  
        parent::beforeFilter(); 
        $this->Auth->allowedActions = array('patron');
    }
    /*
    Function Name : admin_managelibrary
    Desc : action for listing all the libraries
   */
    function admin_managelibrary() {
        $this->Library->recursive = -1;
        $this->set('libraries', $this->paginate('Library'));
    }
	
    /*
    Function Name : admin_libraryform
    Desc : action for adding the libraries
   */
    function admin_libraryform() {
        if( !empty( $this -> params[ 'named' ][ 'id' ] ) )//gets the values from the url in form  of array
        {
            $libraryId = $this -> params[ 'named' ][ 'id' ];
            $condition = 'edit';
            if( trim( $libraryId ) != '' && is_numeric( $libraryId ) )
            {
                $this -> set( 'formAction', 'admin_libraryform/id:' . $libraryId );
                $this -> set( 'formHeader', 'Edit Library' );
                $this->Library->Behaviors->attach('Containable');
                $getData = $this->Library->find('first', array('conditions' => array('Library.id' => $libraryId),
                                                               'fields' => array(
                                                                                'Library.id',
                                                                                'Library.library_name',
                                                                                'Library.library_domain_name',
                                                                                'Library.library_authentication_method',
                                                                                'Library.library_authentication_num',
                                                                                'Library.library_authentication_url',
                                                                                'Library.library_bgcolor',
                                                                                'Library.library_content_bgcolor',
                                                                                'Library.library_nav_bgcolor',
                                                                                'Library.library_boxheader_bgcolor',
                                                                                'Library.library_boxheader_text_color',
                                                                                'Library.library_text_color',
                                                                                'Library.library_links_color',
                                                                                'Library.library_links_hover_color',
                                                                                'Library.library_navlinks_color',
                                                                                'Library.library_navlinks_hover_color',
                                                                                'Library.library_contact_fname',
                                                                                'Library.library_contact_lname',
                                                                                'Library.library_contact_email',
                                                                                'Library.library_user_download_limit',
                                                                                'Library.library_admin_id',
                                                                                'Library.library_download_type',
                                                                                'Library.library_download_limit',
                                                                                'Library.library_image_name',
                                                                                'Library.library_block_explicit_content',
                                                                                'Library.library_available_downloads',
                                                                                'Library.library_contract_start_date'
                                                                                ),
                                                               'contain' => array(
                                                                            'User' => array(
                                                                                    'fields' => array(
                                                                                                    'User.id',
                                                                                                    'User.first_name',
                                                                                                    'User.last_name',
                                                                                                    'User.email',
                                                                                                    'User.password'
                                                                                                )
                                                                            )
                                                                )));
                $this -> set( 'getData', $getData );
                $this->LibraryPurchase->recursive = -1;
                $allPurchases = $this->LibraryPurchase->find('all', array('conditions' => array('library_id' => $libraryId)));
                $this->set('allPurchases', $allPurchases);
            }
        }
        else
        {
            $arr = array();
            $condition = 'add';
            $libraryId = '';
            $this -> set( 'getData', $arr );
            $this -> set( 'formAction', 'admin_libraryform' );
            $this -> set( 'formHeader', 'Library Setup' );
        }
    }
    
    function admin_ajax_validate() {
        Configure::write('debug', 0);
	$this->layout = false;
	if ($this->RequestHandler->isAjax()) {
            if(!empty($this->params['named']['id'])) {
                $libraryId = $this->params['named']['id'];
            }
            else {
                $libraryId = "";
            }
            if (!empty($this->data)) {
                if(trim($libraryId) != '' && is_numeric($libraryId)) {
                    $getData = $this->Library->find('first', array('conditions' => array('Library.id' => $libraryId),
                                                               'fields' => array(
                                                                                'Library.id',
                                                                                'Library.library_name',
                                                                                'Library.library_domain_name',
                                                                                'Library.library_authentication_method',
                                                                                'Library.library_authentication_num',
                                                                                'Library.library_authentication_url',
                                                                                'Library.library_bgcolor',
                                                                                'Library.library_content_bgcolor',
                                                                                'Library.library_nav_bgcolor',
                                                                                'Library.library_boxheader_bgcolor',
                                                                                'Library.library_boxheader_text_color',
                                                                                'Library.library_text_color',
                                                                                'Library.library_links_color',
                                                                                'Library.library_links_hover_color',
                                                                                'Library.library_navlinks_color',
                                                                                'Library.library_navlinks_hover_color',
                                                                                'Library.library_contact_fname',
                                                                                'Library.library_contact_lname',
                                                                                'Library.library_contact_email',
                                                                                'Library.library_user_download_limit',
                                                                                'Library.library_admin_id',
                                                                                'Library.library_download_type',
                                                                                'Library.library_download_limit',
                                                                                'Library.library_image_name',
                                                                                'Library.library_block_explicit_content',
                                                                                'Library.library_available_downloads',
                                                                                'Library.library_contract_start_date'
                                                                                ),
                                                               'contain' => array(
                                                                            'User' => array(
                                                                                    'fields' => array(
                                                                                                    'User.id',
                                                                                                    'User.first_name',
                                                                                                    'User.last_name',
                                                                                                    'User.email',
                                                                                                    'User.password'
                                                                                                )
                                                                            )
                                                                )));
                    $this->Library->id = $this->data['Library']['id'];
                }
                
                if($this->data['Library']['library_download_limit'] == 'manual') {
                    $this->data['Library']['library_download_limit'] = $this->data['Library']['library_download_limit_manual'];
                }
                
                if($this->data['Library']['libraryStepNum'] == '2') {
                    if($this->data['User']['password'] == "48d63321789626f8844afe7fdd21174eeacb5ee5") {
                     $this->data['User']['password'] = "";
                    }
                    if(trim($libraryId) != '' && is_numeric($libraryId)) {
                        $this->User->id = $getData['Library']['library_admin_id'];
                    }
                    else {
                        $this->User->create();
                    }
                    $this->User->set($this->data['User']);
                    $this->User->setValidation('library_step'.$this->data['Library']['libraryStepNum']);
                    if($this->User->validates()){
                        $message = __('You will be redirected to the next step shortly...', true);
                        $data = $this->data;
                        $this->set('success', compact('message', 'data'));
                    }
                    else {
                        $message = __('To proceed further please enter the data correctly.|2', true);
                        $User = $this->User->invalidFields();
                        $data = compact('User');
                        $this->set('errors', compact('message', 'data'));
                    }
                }
                elseif($this->data['Library']['libraryStepNum'] == '5') {
                    $this->Library->create();
                    $this->Library->set($this->data['Library']);
                    if($this->data['Library']['library_authentication_method'] == 'referral_url') {
                        $this->Library->setValidation('library_step1_referral_url');
                    }
                    elseif($this->data['Library']['library_authentication_method'] == 'user_account') {
                        $this->Library->setValidation('library_step1_user_account');
                    }
                    elseif($this->data['Library']['library_authentication_method'] == 'innovative') {
                        $this->Library->setValidation('library_step1_innovative');
                    }
		    elseif($this->data['Library']['library_authentication_method'] == 'innovative_wo_pin') {
                        $this->Library->setValidation('library_step1_innovative');
                    }
                    else {
                        $this->Library->setValidation('library_step1');
                    }
                    if($this->Library->validates()){
                        if($this->data['User']['password'] == "48d63321789626f8844afe7fdd21174eeacb5ee5") {
                            $this->data['User']['password'] = "";
                        }
                        if(trim($libraryId) != '' && is_numeric($libraryId)) {
                            $this->User->id = $getData['Library']['library_admin_id'];
                        }
                        else {
                            $this->User->create();
                        }
                        $this->User->set($this->data['User']);
                        $this->User->setValidation('library_step2');
                        if($this->User->validates()){
                            $this->Library->setValidation('library_step3');
                            if($this->Library->validates()){
                                $this->Library->setValidation('library_step4');
                                if($this->Library->validates()){
                                    $this->Library->setValidation('library_step_date');
                                    if($this->Library->validates()){
                                        $this->LibraryPurchase->create();
                                        $this->LibraryPurchase->set($this->data['LibraryPurchase']);
                                        if(trim($libraryId) != '' && is_numeric($libraryId)) {
                                            $this->LibraryPurchase->setValidation('library_step'.$this->data['Library']['libraryStepNum'].'_edit');
                                        }
                                        else {
                                            $this->LibraryPurchase->setValidation('library_step'.$this->data['Library']['libraryStepNum']);    
                                        }
                                        if($this->LibraryPurchase->validates()){
                                            if(trim($libraryId) != '' && is_numeric($libraryId)) {
                                                $this->User->id = $getData['Library']['library_admin_id'];
                                            }
                                            $this->data['User']['type_id'] = 4;
                                            if($this->User->save($this->data['User'])) {
                                                if(trim($libraryId) != '' && is_numeric($libraryId)) {
                                                    $this->data['Library']['library_available_downloads'] = $getData['Library']['library_available_downloads']+$this->data['LibraryPurchase']['purchased_tracks'];
                                                }
                                                else {
                                                    $this->data['Library']['library_available_downloads'] = $this->data['LibraryPurchase']['purchased_tracks'];
                                                }
                                                $this->data['Library']['library_admin_id'] = $this->User->id;
                                                if(strtotime(date('Y-m-d')) < strtotime($this->data['Library']['library_contract_start_date'])) {
                                                    $this->data['Library']['library_status'] = 'inactive';
                                                }
                                                if($this->Library->save($this->data['Library'])) {
                                                    if($this->data['LibraryPurchase']['purchased_order_num'] != "" && $this->data['LibraryPurchase']['purchased_tracks'] != "" && $this->data['LibraryPurchase']['purchased_amount'] != "") {
                                                        $this->data['LibraryPurchase']['library_id'] = $this->Library->id;
                                                        if($this->LibraryPurchase->save($this->data['LibraryPurchase'])) {
                                                            $message = __('You will be redirected to the next step shortly...', true);
                                                            $data = $this->data;
                                                            $this->set('success', compact('message', 'data'));
                                                        }
                                                        else {
                                                            $message = __('To proceed further please enter the data correctly.|5', true);
                                                            $LibraryPurchase = $this->LibraryPurchase->invalidFields();
                                                            $data = compact('LibraryPurchase');
                                                            $this->set('errors', compact('message', 'data'));
                                                        }
                                                    }
                                                    else {
                                                        $message = __('You will be redirected to the next step shortly...', true);
                                                        $data = $this->data;
                                                        $this->set('success', compact('message', 'data'));
                                                    }
                                                }
                                                else {
                                                    $message = __('To proceed further please enter the data correctly.|1', true);
                                                    $Library = $this->Library->invalidFields();
                                                    $data = compact('Library');
                                                    $this->set('errors', compact('message', 'data'));
                                                }
                                            }
                                            else {
                                                $message = __('To proceed further please enter the data correctly.|2', true);
                                                $User = $this->User->invalidFields();
                                                $data = compact('User');
                                                $this->set('errors', compact('message', 'data'));
                                            }
                                        }
                                        else {
                                            $message = __('To proceed further please enter the data correctly.|5', true);
                                            $LibraryPurchase = $this->LibraryPurchase->invalidFields();
                                            $data = compact('LibraryPurchase');
                                            $this->set('errors', compact('message', 'data'));
                                        }
                                    }
                                    else {
                                        $message = __('To proceed further please enter the data correctly.|5', true);
                                        $Library = $this->Library->invalidFields();
                                        $data = compact('Library');
                                        $this->set('errors', compact('message', 'data'));
                                    }
                                }
                                else {
                                    $message = __('To proceed further please enter the data correctly.|4', true);
                                    $Library = $this->Library->invalidFields();
                                    $data = compact('Library');
                                    $this->set('errors', compact('message', 'data'));
                                }
                            }
                            else {
                                $message = __('To proceed further please enter the data correctly.|3', true);
                                $Library = $this->Library->invalidFields();
                                $data = compact('Library');
                                $this->set('errors', compact('message', 'data'));
                            }
                        }
                        else {
                            $message = __('To proceed further please enter the data correctly.|2', true);
                            $User = $this->User->invalidFields();
                            $data = compact('User');
                            $this->set('errors', compact('message', 'data'));
                        }
                    }
                    else {
                        $message = __('To proceed further please enter the data correctly.|1', true);
                        $Library = $this->Library->invalidFields();
                        $data = compact('Library');
                        $this->set('errors', compact('message', 'data'));
                    }
                }
                else {
                    $this->Library->create();
                    $this->Library->set($this->data['Library']);
                    if($this->data['Library']['libraryStepNum'] == 1 && $this->data['Library']['library_authentication_method'] == 'referral_url') {
                        $this->Library->setValidation('library_step'.$this->data['Library']['libraryStepNum'].'_referral_url');
                    }
                    elseif($this->data['Library']['libraryStepNum'] == 1 && $this->data['Library']['library_authentication_method'] == 'user_account') {
                        $this->Library->setValidation('library_step'.$this->data['Library']['libraryStepNum'].'_user_account');
                    }
                    elseif($this->data['Library']['libraryStepNum'] == 1 && $this->data['Library']['library_authentication_method'] == 'innovative') {
                        $this->Library->setValidation('library_step'.$this->data['Library']['libraryStepNum'].'_innovative');
                    }
		    elseif($this->data['Library']['libraryStepNum'] == 1 && $this->data['Library']['library_authentication_method'] == 'innovative_wo_pin') {
                        $this->Library->setValidation('library_step'.$this->data['Library']['libraryStepNum'].'_innovative');
                    }
                    else {
                        $this->Library->setValidation('library_step'.$this->data['Library']['libraryStepNum']);
                    }
                    
                    if($this->Library->validates()){
                        $message = __('You will be redirected to the next step shortly...', true);
                        $data = $this->data;
                        $this->set('success', compact('message', 'data'));
                    }
                    else {
                        $message = __('To proceed further please enter the data correctly.|'.$this->data['Library']['libraryStepNum'], true);
                        $Library = $this->Library->invalidFields();
                        $data = compact('Library');
                        $this->set('errors', compact('message', 'data'));
                    }
                }
            }
        }
    }
    
    function admin_doajaxfileupload() {
        Configure::write('debug', 0);
	$this->layout = false;
        $error = "";
	$msg = "";
	$fileElementName = 'fileToUpload';
        if($_FILES[$fileElementName]['tmp_name'] != '')
	{
            $p = $_FILES[$fileElementName]['name'];
            $pos = strrpos($p,".");
            $ph = strtolower(substr($p,$pos+1,strlen($p)-$pos));
            
            if( $ph!="jpg" && $ph!="gif" && $ph!="png" && $ph!="jpeg" && $ph!="JPEG" && $ph!="tif" ) {
                $error = "Please select library image in Valid Format.";
            }
            
            if($error == "") {
                if($_REQUEST['LibraryStepNum'] == "5" && $_REQUEST['LibraryID'] != "") {
                    $upload_dir = '../webroot/img/libraryimg/';
                    $fileName = $_REQUEST['LibraryID'].".".$ph;
                    $upload_Path = $upload_dir . $fileName;
                    if(!file_exists($upload_dir)) {
                        mkdir($upload_dir);
                    }
                    move_uploaded_file($_FILES[$fileElementName]["tmp_name"], $upload_Path);
                    $this->Library->id = $_REQUEST['LibraryID'];
                    $this->Library->saveField('library_image_name', $fileName);
                }
            }
	}
	echo "{";
	echo				"error: '" . $error . "',\n";
	echo				"msg: '" . $msg . "'\n";
	echo "}";
    }
    
    /*
    Function Name : admin_deactivate
    Desc : For deactivating a library
   */

    public function admin_deactivate() {
        $libraryID = $this->params['named']['id'];
        if(trim($libraryID) != "" && is_numeric($libraryID))
        {
            $this->Session -> setFlash( 'Library deactivated successfully!', 'modal', array( 'class' => 'modal success' ) );
            $this->Library->id = $libraryID;
            $this->Library->saveField('library_status', 'inactive', false);
            $this->autoRender = false;            
            $this->redirect('managelibrary');
        }
        else
        {
            $this->Session->setFlash('Error occured while deactivating the library', 'modal', array('class' => 'modal problem'));
            $this->autoRender = false;            
            $this->redirect('managelibrary');
        }
    }
    
    /*
    Function Name : admin_activate
    Desc : For activating a library
   */
    function admin_activate() {
        $libraryID = $this->params['named']['id'];
        if(trim($libraryID) != "" && is_numeric($libraryID))
        {
            $this->Session -> setFlash( 'Library activated successfully!', 'modal', array( 'class' => 'modal success' ) );
            $this->Library->id = $libraryID;
            $this->Library->saveField('library_status', 'active', false);
            $this->autoRender = false;            
            $this->redirect('managelibrary');
        }
        else
        {
            $this->Session -> setFlash( 'Error occured while activating the library', 'modal', array( 'class' => 'modal problem' ) );
            $this->autoRender = false;            
            $this->redirect('managelibrary');
        }
    }

    /*
    Function Name : patron
    Desc : For validating the patrons for libraries
   */
    function patron() {        
        $this->layout = false;        
        if(isset($_REQUEST['url']))
        {
            $requestUrlArr = explode("/", $_REQUEST['url']);
            $patronId = $requestUrlArr['2'];          
        }
        $referrerUrl = strtolower($_SERVER['HTTP_REFERER']);        
        $this->Library->recursive = -1;
        $existingLibraries = $this->Library->find('all',array(
                                                'conditions' => array('LOWER(library_domain_name)' => $referrerUrl,'library_status' => 'active','library_authentication_method' => 'referral_url')
                                                //'conditions' => array('LOWER(library_domain_name)' => $referrerUrl,'library_status' => 'active')
                                                )
                                            );        
	if(count($existingLibraries) == 0)
        {
            $this -> Session -> setFlash("You are not authorized to view this location.");
            $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
        }        
        else
        {
            $currentPatron = $this->Currentpatron->find('all', array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'], 'patronid' => $patronId)));
            if(count($currentPatron) > 0)
            {
                $modifiedTime = strtotime($currentPatron[0]['Currentpatron']['modified']);                           
                $date = strtotime(date('Y-m-d H:i:s'));              
                if(!(isset($_SESSION['patron'])))
                {               
                    if(($date-$modifiedTime) > 60)
                    {
                        $updateArr = array();
                        $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                
                        $updateArr['created'] = date('Y-m-d H:i:s');
                        $updateArr['session_id'] = session_id();
                        $this->Currentpatron->save($updateArr);
                    }
                    else
                    {                
                        $this -> Session -> setFlash("This account is already active.");                        
                        $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
                    }
                }
                else
                {
                    $sessionId = session_id();                    
                    if($currentPatron[0]['Currentpatron']['session_id'] != $sessionId)
                    {                        
                        if(($date-$modifiedTime) > 60)
                        {                            
                            $updateArr = array();
                            $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                
                            $updateArr['created'] = date('Y-m-d H:i:s');
                            $updateArr['session_id'] = session_id();
                            $this->Currentpatron->save($updateArr);
                        }
                        else
                        {                            
                            $this -> Session -> setFlash("This account is already active.");                            
                            $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
                        }                  
                    }                    
                }
            }
            else
            {                
                $insertArr['libid'] = $existingLibraries['0']['Library']['id'];
                $insertArr['patronid'] = $patronId;
                $insertArr['session_id'] = session_id();
                $this->Currentpatron->save($insertArr);
            }
            $this->Session->write("library", $existingLibraries['0']['Library']['id']);
            $this->Session->write("patron", $patronId);
            $this->Session->write("referral_url",$existingLibraries['0']['Library']['library_domain_name']);
            $isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
            $this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
            $startDate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-(date('w', mktime(0, 0, 0, date('m'), date('d'), date('Y')))-1), date('Y')))." 00:00:00";
            $endDate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')+(7-date('w', mktime(0, 0, 0, date('m'), date('d'), date('Y')))), date('Y')))." 23:59:59";           
            $this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
            $results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array($startDate, $endDate))));
            $this ->Session->write("downloadsUsed", $results);
            if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1')
            {
                $this ->Session->write("block", 'yes');
            }
            else{
                $this ->Session->write("block", 'no');
            }
            $this->redirect(array('controller' => 'homes', 'action' => 'index'));
        }
    }
}
?>
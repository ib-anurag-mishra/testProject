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
    var $uses = array( 'Library', 'User', 'LibraryTemplate', 'LibraryPurchase' );
    
    /*
    Function Name : admin_managelibrary
    Desc : action for listing all the libraries
   */
    public function admin_managelibrary()
    {
        $this->Library->recursive = -1;
        $this->set('libraries', $this->Library->getalllibraries());
    }
	
    /*
    Function Name : admin_libraryform
    Desc : action for adding the libraries
   */
    public function admin_libraryform()
    {
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
                                                                                'Library.library_template_id',
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
        $this->LibraryTemplate->recursive = -1;
        $allTemplates = $this->LibraryTemplate->find('all');
        $this -> set( 'allTemplates', $allTemplates );
    }
    
    public function admin_ajax_validate()
    {
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
                                                                                'Library.library_template_id',
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
                    if($this->data['User']['password'] == "7f86df28b26af363bb0d519f137a4e22ec6e64a6") {
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
                        $message = __('To proceed further please enter the data correctly.', true);
                        $User = $this->User->invalidFields();
                        $data = compact('User');
                        $this->set('errors', compact('message', 'data'));
                    }
                }
                elseif($this->data['Library']['libraryStepNum'] == '5') {
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
                            if($this->Library->save($this->data['Library'])) {
                                if($this->data['LibraryPurchase']['purchased_order_num'] != "" && $this->data['LibraryPurchase']['purchased_tracks'] != "" && $this->data['LibraryPurchase']['purchased_amount'] != "") {
                                    $this->data['LibraryPurchase']['library_id'] = $this->Library->id;
                                    if($this->LibraryPurchase->save($this->data['LibraryPurchase'])) {
                                        $message = __('You will be redirected to the next step shortly...', true);
                                        $data = $this->data;
                                        $this->set('success', compact('message', 'data'));
                                    }
                                    else {
                                        $message = __('To proceed further please enter the data correctly.', true);
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
                                $message = __('To proceed further please enter the data correctly.', true);
                                $Library = $this->Library->invalidFields();
                                $data = compact('Library');
                                $this->set('errors', compact('message', 'data'));
                            }
                        }
                        else {
                            $message = __('To proceed further please enter the data correctly.', true);
                            $User = $this->User->invalidFields();
                            $data = compact('User');
                            $this->set('errors', compact('message', 'data'));
                        }
                    }
                    else {
                        $message = __('To proceed further please enter the data correctly.', true);
                        $LibraryPurchase = $this->LibraryPurchase->invalidFields();
                        $data = compact('LibraryPurchase');
                        $this->set('errors', compact('message', 'data'));
                    }
                }
                else {
                    $this->Library->create();
                    $this->Library->set($this->data['Library']);
                    $this->Library->setValidation('library_step'.$this->data['Library']['libraryStepNum']);
                    if($this->Library->validates()){
                        $message = __('You will be redirected to the next step shortly...', true);
                        $data = $this->data;
                        $this->set('success', compact('message', 'data'));
                    }
                    else {
                        $message = __('To proceed further please enter the data correctly.', true);
                        $Library = $this->Library->invalidFields();
                        $data = compact('Library');
                        $this->set('errors', compact('message', 'data'));
                    }
                }
            }
        }
    }
    
    public function admin_doajaxfileupload()
    {
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
    public function admin_activate() {
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
    function patron()
    {        
        $this->layout = false;        
        if(isset($_REQUEST['url']))
        {
          $requestUrlArr = explode("/", $_REQUEST['url']);
          $patronId = $requestUrlArr['2'];          
        }       
        $referrerUrl = $_SERVER['HTTP_REFERER'];
        $this->Library->recursive = -1;
        $existingLibraries = $this->Library->find('all',array(
                                                'conditions' => array('library_domain_name' => $referrerUrl,'library_status' => 'active')
                                                )
                                            );        
        if(count($existingLibraries) == 0)
        {            
            $this->redirect(array('controller' => 'homes', 'action' => 'error'));
        }        
        else
        {          
            $this ->Session->write("library", $existingLibraries['0']['Library']['id']);
            $this ->Session->write("patron", $patronId);
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
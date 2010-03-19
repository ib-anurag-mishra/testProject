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
    var $helpers = array( 'Html', 'Ajax', 'Javascript', 'Form' );
    var $components = array( 'Session', 'Auth', 'Acl', 'RequestHandler' );
    var $uses = array( 'Library', 'User', 'LibraryTemplate', 'LibraryPurchase' );
    
    /*
    Function Name : admin_managelibrary
    Desc : action for listing all the libraries
   */
    public function admin_managelibrary()
    {
        $this->Library->recursive = -1;
        $this -> set('libraries', $this->Library->getalllibraries());
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
                $getLibraryDataObj = new Library();
                $getData = $getLibraryDataObj -> getlibrarydata( $libraryId );
                $this -> set( 'getData', $getData );//editting a value
                if( isset( $this -> data ) )
                {
                    $updateObj = new Library();
                    $getData[ 'Library' ] = $this -> data[ 'Library' ];
                    $this -> set( 'getData', $getData );
                    $this -> Library -> id = $this -> data[ 'Library' ][ 'id' ];
                    
                    /*if( trim( $this -> data[ 'Library' ][ 'password' ] ) != '' )
                    {
                            $this -> data[ 'Library' ][ 'password' ] = md5( $this -> data[ 'Library' ][ 'password' ] );
                    }
                    else
                    {// do not update the password
                            $this -> data[ 'Library' ] = $updateObj -> arrayremovekey( $this -> data[ 'Library' ], 'password' );
                    }*/
                    
                    $this -> Library -> set( $this -> data[ 'Library' ] );
                    
                    if( $this -> Library -> save() )
                    {
                        $this -> Session -> setFlash( 'Data has been saved Sucessfully!', 'modal', array( 'class' => 'modal success' ) );
                        $this -> redirect( 'managelibrary' );
                    }//}
                    else {
                        $this -> Session -> setFlash( 'Data could not be updated.', 'modal', array( 'class' => 'modal problem' ) );
                    }
                }//editting a value
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
            if (!empty($this->data)) {
                
                if($this->data['Library']['library_download_limit'] == 'manual') {
                    $this->data['Library']['library_download_limit'] = $this->data['Library']['library_download_limit_manual'];
                }
                
                if($this->data['Library']['libraryStepNum'] == '2') {
                    if($this->data['User']['password'] == "7f86df28b26af363bb0d519f137a4e22ec6e64a6") {
                     $this->data['User']['password'] = "";
                    }
                    $this->User->create();
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
                    $this->LibraryPurchase->setValidation('library_step'.$this->data['Library']['libraryStepNum']);
                    if($this->LibraryPurchase->validates()){
                        $this->data['User']['type_id'] = 4;
                        if($this->User->save($this->data['User'])) {
                            $this->data['Library']['library_available_downloads'] = $this->data['LibraryPurchase']['purchased_tracks'];
                            $this->data['Library']['library_admin_id'] = $this->User->id;
                            if($this->Library->save($this->data['Library'])) {
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
	if(!empty($_FILES[$fileElementName]['error']))
	{
            switch($_FILES[$fileElementName]['error'])
            {

                case '1':
                        $error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
                        break;
                case '2':
                        $error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
                        break;
                case '3':
                        $error = 'The uploaded file was only partially uploaded';
                        break;
                case '4':
                        $error = 'No file was uploaded.';
                        break;

                case '6':
                        $error = 'Missing a temporary folder';
                        break;
                case '7':
                        $error = 'Failed to write file to disk';
                        break;
                case '8':
                        $error = 'File upload stopped by extension';
                        break;
                case '999':
                default:
                        $error = 'No error code avaiable';
            }
	}
        elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none')
	{
	    $error = 'No file was uploaded..';
	}
        else 
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
    Function Name : admin_delete
    Desc : For deleting a library
   */
    public function admin_delete()
    {
        $this->Library->id = $this->params['named']['id'];
        if($this->Library->saveField('library_status', 'inactive'))
        {
            $this -> Session -> setFlash( 'Data deleted Sucessfully!', 'modal', array( 'class' => 'modal success' ) );
            $this -> redirect( 'managelibrary' );
        }
        else
        {
            $this -> Session -> setFlash( 'Error occured while deleteting the record', 'modal', array( 'class' => 'modal problem' ) );
            $this -> redirect( 'managelibrary' );
        }
    }
}
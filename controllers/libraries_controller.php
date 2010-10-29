<?php
/*
 File Name : libraries_controller.php
 File Description : Library controller
 Author : maycreate
 */
 
Class LibrariesController extends AppController
{
    var $name = 'Libraries';
    var $layout = 'admin';
    var $helpers = array( 'Html', 'Ajax', 'Javascript', 'Form', 'Session');
    var $components = array( 'Session', 'Auth', 'Acl', 'RequestHandler','ValidatePatron','Downloads','CdnUpload');
    var $uses = array( 'Library', 'User', 'LibraryPurchase', 'Download', 'Currentpatron','Variable', 'Url');
    
    /*
     Function Name : beforeFilter
     Desc : actions that needed before other functions are getting called
    */
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
																				'Library.library_home_url',
                                                                                'Library.library_authentication_method',
                                                                                'Library.library_authentication_num',
                                                                                'Library.library_authentication_url',
																				'Library.library_authentication_variable',
																				'Library.library_authentication_response',
																				'Library.library_host_name',
																				'Library.library_port_no',
																				'Library.library_sip_login',
																				'Library.library_sip_password',
																				'Library.library_sip_location',
																				'Library.library_ezproxy_secret',
																				'Library.library_ezproxy_referral',
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
																				'Library.library_box_header_color',
																				'Library.library_box_hover_color',
                                                                                'Library.library_contact_fname',
                                                                                'Library.library_contact_lname',
                                                                                'Library.library_contact_email',
                                                                                'Library.library_user_download_limit',
                                                                                'Library.library_admin_id',
                                                                                'Library.library_download_type',
                                                                                'Library.library_download_limit',
                                                                                'Library.library_image_name',
                                                                                'Library.library_block_explicit_content',
																				'Library.show_library_name',
																				'Library.library_territory',
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
                $allVariables = $this->Variable->find('all', array('conditions' => array('library_id' => $libraryId),'order' => array('id')));
                $this->set('allVariables', $allVariables);				
                $allUrls = $this->Url->find('all', array('conditions' => array('library_id' => $libraryId),'order' => array('id')));
                $this->set('allUrls', $allUrls);
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
    
    /*
     Function Name : admin_ajax_validate
     Desc : actions that for library data validation using Ajax
    */
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
																				'Library.library_home_url',
                                                                                'Library.library_authentication_method',
                                                                                'Library.library_authentication_num',
                                                                                'Library.library_authentication_url',
																				'Library.library_authentication_variable',
																				'Library.library_authentication_response',
																				'Library.library_host_name',
																				'Library.library_port_no',
																				'Library.library_sip_login',
																				'Library.library_sip_password',
																				'Library.library_sip_location',
																				'Library.library_ezproxy_secret',
																				'Library.library_ezproxy_referral',
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
																				'Library.library_box_header_color',
																				'Library.library_box_hover_color',																				
                                                                                'Library.library_contact_fname',
                                                                                'Library.library_contact_lname',
                                                                                'Library.library_contact_email',
                                                                                'Library.library_user_download_limit',
                                                                                'Library.library_admin_id',
                                                                                'Library.library_download_type',
                                                                                'Library.library_download_limit',
																				'Library.library_current_downloads',
																				'Library.library_total_downloads',
                                                                                'Library.library_image_name',
                                                                                'Library.library_block_explicit_content',
																				'Library.show_library_name',
																				'Library.library_territory',
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
					elseif($this->data['Library']['library_authentication_method'] == 'innovative_var') {
                        $this->Library->setValidation('library_step1_innovative_var');
                    }
					elseif($this->data['Library']['library_authentication_method'] == 'innovative_var_wo_pin') {
                        $this->Library->setValidation('library_step1_innovative_var');
                    }

					elseif($this->data['Library']['library_authentication_method'] == 'sip2') {
                        $this->Library->setValidation('library_step1_sip2');
                    }
					elseif($this->data['Library']['library_authentication_method'] == 'sip2_wo_pin') {
                        $this->Library->setValidation('library_step1_sip2_wo_pin');
                    }
					elseif($this->data['Library']['library_authentication_method'] == 'sip2_var') {
                        $this->Library->setValidation('library_step1_sip2_var');
                    }
					elseif($this->data['Library']['library_authentication_method'] == 'sip2_var_wo_pin') {
                        $this->Library->setValidation('library_step1_sip2_var_wo_pin');
                    }					
					elseif($this->data['Library']['library_authentication_method'] == 'ezproxy') {
                        $this->Library->setValidation('library_step1_ezproxy');
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
											if(trim($this->data['User']['password']) == ""){
											// do not update the password
											$this->data['User']['password'] = $getData['User']['password'];
											}
                                            if($this->User->save($this->data['User'])) {
                                                if(trim($libraryId) != '' && is_numeric($libraryId)) {
                                                    $this->data['Library']['library_available_downloads'] = $getData['Library']['library_available_downloads']+$this->data['LibraryPurchase']['purchased_tracks'];
													$this->data['Library']['library_current_downloads'] = $getData['Library']['library_current_downloads'];
													$this->data['Library']['library_total_downloads'] = $getData['Library']['library_total_downloads'];
                                                }
                                                else {
                                                    $this->data['Library']['library_available_downloads'] = $this->data['LibraryPurchase']['purchased_tracks'];
                                                }
                                                $this->data['Library']['library_admin_id'] = $this->User->id;
                                                if(strtotime(date('Y-m-d')) < strtotime($this->data['Library']['library_contract_start_date'])) {
                                                    $this->data['Library']['library_status'] = 'inactive';
                                                }
                                                if($this->Library->save($this->data['Library'])) {
														if($this->data['Library']['library_authentication_method'] == 'innovative_var_wo_pin' || $this->data['Library']['library_authentication_method'] == 'sip2_var' || $this->data['Library']['library_authentication_method'] == 'sip2_var_wo_pin'){
															foreach($this->data['Variable'] as $k=>$v){
																if($this->data['Variable'][$k]['authentication_variable'] !='' && $this->data['Variable'][$k]['authentication_response'] != '' && $this->data['Variable'][$k]['error_msg'] != ''){
																	$data[$k] = $v;
																}
															}
															$this->Variable->deleteAll(array('library_id' => $this->Library->id));
															foreach($data as $k=>$v){
																$data[$k]['library_id'] = $this->Library->id;
															}
															$this->Variable->saveAll($data);
														}
														if($this->data['Libraryurl'][0]['domain_name']){
															if($this->data['Library']['library_authentication_method'] != 'referral_url' || $this->data['Library']['library_authentication_method'] != 'user_account'){														
																foreach($this->data['Libraryurl'] as $k=>$v){
																	if($this->data['Libraryurl'][$k]['domain_name'] !=''){
																		$url[$k] = $v;
																	}
																}
																$this->Url->deleteAll(array('library_id' => $this->Library->id));
																foreach($url as $k=>$v){
																	$url[$k]['library_id'] = $this->Library->id;
																}
																$this->Url->saveAll($url);
															}
														}
														if($this->data['LibraryPurchase']['purchased_order_num'] != "" && $this->data['LibraryPurchase']['purchased_tracks'] != "" && $this->data['LibraryPurchase']['purchased_amount'] != "") {
                                                        $this->data['LibraryPurchase']['library_id'] = $this->Library->id;
														$this->data['Library']['id'] = $this->Library->id;

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
					elseif($this->data['Library']['libraryStepNum'] == 1 && $this->data['Library']['library_authentication_method'] == 'sip2') {
                        $this->Library->setValidation('library_step'.$this->data['Library']['libraryStepNum'].'_sip2');
                    }
					elseif($this->data['Library']['libraryStepNum'] == 1 && $this->data['Library']['library_authentication_method'] == 'sip2_wo_pin') {
                        $this->Library->setValidation('library_step'.$this->data['Library']['libraryStepNum'].'_sip2');
                    }				
					elseif($this->data['Library']['libraryStepNum'] == 1 && $this->data['Library']['library_authentication_method'] == 'innovative_var') {
                        $this->Library->setValidation('library_step'.$this->data['Library']['libraryStepNum'].'_innovative_var');
                    }				
					elseif($this->data['Library']['libraryStepNum'] == 1 && $this->data['Library']['library_authentication_method'] == 'innovative_var_wo_pin') {
                        $this->Library->setValidation('library_step'.$this->data['Library']['libraryStepNum'].'_innovative_var');
                    }				
					elseif($this->data['Library']['libraryStepNum'] == 1 && $this->data['Library']['library_authentication_method'] == 'sip2_var') {
                        $this->Library->setValidation('library_step'.$this->data['Library']['libraryStepNum'].'_sip2_var');
                    }
					elseif($this->data['Library']['libraryStepNum'] == 1 && $this->data['Library']['library_authentication_method'] == 'sip2_var_wo_pin') {
                        $this->Library->setValidation('library_step'.$this->data['Library']['libraryStepNum'].'_sip2_var_wo_pin');
                    }					
					elseif($this->data['Library']['libraryStepNum'] == 1 && $this->data['Library']['library_authentication_method'] == 'ezproxy') {
                        $this->Library->setValidation('library_step'.$this->data['Library']['libraryStepNum'].'_ezproxy');
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
    
    /*
     Function Name : admin_doajaxfileupload
     Desc : actions that for library picture upload using Ajax
    */
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
					src = WWW_ROOT.'img/libraryimg/'.$fileName;
					$dst = Configure::read('App.CDN_PATH').'libraryimg/'.$fileName;
					$error = $this->CdnUpload->sendFile($src, $dst);
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
    function admin_deactivate() {
        $libraryID = $this->params['named']['id'];
        if(trim($libraryID) != "" && is_numeric($libraryID)) {
            $this->Session -> setFlash( 'Library deactivated successfully!', 'modal', array( 'class' => 'modal success' ) );
            $this->Library->id = $libraryID;
	    $this->Library->set(array('library_status' => 'inactive', 'library_status_updated_by' => 'admin'));
            $this->Library->save();
            $this->autoRender = false;
            $this->redirect('managelibrary');
        }
        else {
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
        if(trim($libraryID) != "" && is_numeric($libraryID)) {
            $this->Session -> setFlash( 'Library activated successfully!', 'modal', array( 'class' => 'modal success' ) );
            $this->Library->id = $libraryID;
            $this->Library->set(array('library_status' => 'active', 'library_status_updated_by' => 'cron'));
            $this->Library->save();
            $this->autoRender = false;
            $this->redirect('managelibrary');
        }
        else {
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
                if(!$this->Session->read('patron'))
                {               
                    if(($date-$modifiedTime) > 60)
                    {
                        $updateArr = array();
                        $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];                
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
            $this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
			$this->Session->write("referral_url",$existingLibraries['0']['Library']['library_domain_name']);
            $isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
            $this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
            $startDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."1"))." 00:00:00";
            $endDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."7"))." 23:59:59";           
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
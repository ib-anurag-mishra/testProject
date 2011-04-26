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
    var $uses = array( 'Library', 'User', 'LibraryPurchase', 'Download', 'Currentpatron','Variable', 'Url','ContractLibraryPurchase');
    
    /*
     Function Name : beforeFilter
     Desc : actions that needed before other functions are getting called
    */
    function beforeFilter() {	  
        parent::beforeFilter(); 
        $this->Auth->allowedActions = array('patron', 'admin_ajax_preview');
    }
    
    /*
     Function Name : admin_managelibrary
     Desc : action for listing all the libraries
    */
    function admin_managelibrary() {
        $this->Library->recursive = -1;
		$this->paginate = array('order' => 'id');
		$this->paginate = array('cache' => 'no');
        $this->set('libraries', $this->paginate('Library'));
    }
	
	    /*
     Function Name : admin_preview
     Desc : action for showing preview of the layout in the admin end
    */
    function admin_ajax_preview() {
		Configure::write('debug', 0);		
		$this->layout = false;
		if(isset($_GET['bgColor']) &&
		   isset($_GET['navBgColor']) && isset($_GET['boxheaderBgColor']) &&
		   isset($_GET['boxheaderTextColor']) && isset($_GET['textColor']) &&
		   isset($_GET['linkColor']) && isset($_GET['linkHoverColor']) &&
		   isset($_GET['navLinksColor']) && isset($_GET['navLinksHoverColor'])) {
			$library_bgcolor = "#".$_GET['bgColor'];
			$library_content_bgcolor = "#FFFFFF";
			$library_nav_bgcolor = "#".$_GET['navBgColor'];
			$library_boxheader_bgcolor = "#".$_GET['boxheaderBgColor'];
			$library_boxheader_text_color = "#".$_GET['boxheaderTextColor'];
			$library_text_color = "#".$_GET['textColor'];
			$library_links_color = "#".$_GET['linkColor'];
			$library_links_hover_color = "#".$_GET['linkHoverColor'];
			$library_navlinks_color = "#".$_GET['navLinksColor'];
			$library_navlinks_hover_color = "#".$_GET['navLinksHoverColor'];
		}
		else {
			$library_bgcolor = "#606060";
			$library_content_bgcolor = "#FFFFFF";
			$library_nav_bgcolor = "#3F3F3F";
			$library_boxheader_bgcolor = "#CCCCCC";
			$library_boxheader_text_color = "#666666";
			$library_text_color = "#666666";
			$library_links_color = "#666666";
			$library_links_hover_color = "#000000";
			$library_navlinks_color = "#FFFFFF";
			$library_navlinks_hover_color = "#FFFFFF";
		}
		if(isset($_GET['boxheaderBgColor']) && isset($_GET['boxHoverColor'])){
			$library_box_header_color = "#".$_GET['boxheaderBgColor'];
			$library_box_hover_color = "#".$_GET['boxHoverColor'];
		}
		else{
			$library_box_header_color = "#FFFFFF";
			$library_box_hover_color = "#FFFFFF";
		}
		
		 //sets the library details which is set from the admin end
		$this->set('libraryName', $_GET['libraryName']);
		$this->set('imagePreview', $_GET['imagePreview']);
		$this->set('library_bgcolor', $library_bgcolor);
		$this->set('library_content_bgcolor', $library_content_bgcolor);
		$this->set('library_nav_bgcolor', $library_nav_bgcolor);
		$this->set('library_boxheader_bgcolor', $library_boxheader_bgcolor);
		$this->set('library_boxheader_text_color', $library_boxheader_text_color);
		$this->set('library_box_hover_color', $library_box_hover_color);
		$this->set('library_text_color', $library_text_color);
		$this->set('library_links_color',$library_links_color);
		$this->set('library_links_hover_color', $library_links_hover_color);
		$this->set('library_navlinks_color', $library_navlinks_color);
		$this->set('library_navlinks_hover_color', $library_navlinks_hover_color);
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
																				'Library.library_logout_url',
																				'Library.library_soap_url',
																				'Library.library_authentication_variable',
																				'Library.library_authentication_response',
																				'Library.library_host_name',
																				'Library.library_port_no',
																				'Library.library_sip_login',
																				'Library.library_sip_password',
																				'Library.library_sip_location',
																				'Library.library_sip_version',
																				'Library.library_ezproxy_secret',
																				'Library.library_ezproxy_referral',
																				'Library.library_ezproxy_name',
																				'Library.library_ezproxy_logout',
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
																				'Library.library_language',
                                                                                'Library.library_available_downloads',
                                                                                'Library.library_contract_start_date',
																				'Library.library_contract_end_date',
																				'Library.library_unlimited'
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
                $allPurchases = $this->LibraryPurchase->find('all', array('conditions' => array('library_id' => $libraryId), 'order' => array('created' => 'asc')));
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
																				'Library.library_logout_url',
																				'Library.library_soap_url',
																				'Library.library_authentication_variable',
																				'Library.library_authentication_response',
																				'Library.library_host_name',
																				'Library.library_port_no',
																				'Library.library_sip_login',
																				'Library.library_sip_password',
																				'Library.library_sip_location',
																				'Library.library_sip_version',
																				'Library.library_ezproxy_secret',
																				'Library.library_ezproxy_referral',
																				'Library.library_ezproxy_name',
																				'Library.library_ezproxy_logout',
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
																				'Library.library_box_hover_color',								'Library.library_contact_fname',
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
																				'Library.library_language',
                                                                                'Library.library_available_downloads',
                                                                                'Library.library_contract_start_date',
																				'Library.library_contract_end_date',
																				'Library.library_unlimited'			
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
                    elseif($this->data['Library']['library_authentication_method'] == 'innovative_https') {
                        $this->Library->setValidation('library_step1_innovative_https');
                    }					
					elseif($this->data['Library']['library_authentication_method'] == 'innovative_wo_pin') {
                        $this->Library->setValidation('library_step1_innovative');
                    }
					elseif($this->data['Library']['library_authentication_method'] == 'innovative_var') {
                        $this->Library->setValidation('library_step1_innovative_var');
                    }
					elseif($this->data['Library']['library_authentication_method'] == 'innovative_var_name') {
                        $this->Library->setValidation('library_step1_innovative_var_name');
                    }					
					elseif($this->data['Library']['library_authentication_method'] == 'innovative_var_https') {
                        $this->Library->setValidation('library_step1_innovative_var_https');
                    }
					elseif($this->data['Library']['library_authentication_method'] == 'innovative_var_https_wo_pin') {
                        $this->Library->setValidation('library_step1_innovative_var_https_wo_pin');
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
                    elseif($this->data['Library']['library_authentication_method'] == 'soap') {
                        $this->Library->setValidation('library_step1_soap');
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
                                //    $this->Library->setValidation('library_step_date');
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

													if($this->data['Library']['library_unlimited'] == 1){
														$this->data['Library']['library_available_downloads'] = Configure::read('unlimited');
													} else {
														$this->data['Library']['library_available_downloads'] = $getData['Library']['library_available_downloads']+$this->data['LibraryPurchase']['purchased_tracks'];	
													}
													$this->data['Library']['library_current_downloads'] = $getData['Library']['library_current_downloads'];
													$this->data['Library']['library_total_downloads'] = $getData['Library']['library_total_downloads'];
                                                }
                                                else {
													if($this->data['Library']['library_unlimited'] == 1){
														$this->data['Library']['library_available_downloads'] = Configure::read('unlimited');
													} else {
														$this->data['Library']['library_available_downloads'] = $this->data['LibraryPurchase']['purchased_tracks'];
													}												
                                                }
                                                $this->data['Library']['library_admin_id'] = $this->User->id;
                                                if(strtotime(date('Y-m-d')) < strtotime($this->data['Library']['library_contract_start_date'])) {
                                                    $this->data['Library']['library_status'] = 'inactive';
                                                }
                                                if($this->Library->save($this->data['Library'])) {
														if(count($this->data['Variable']) > 0){
															if($this->data['Library']['library_authentication_method'] == 'innovative_var_wo_pin' || $this->data['Library']['library_authentication_method'] == 'sip2_var' || $this->data['Library']['library_authentication_method'] == 'sip2_var_wo_pin' || $this->data['Library']['library_authentication_method'] == 'innovative_https' || $this->data['Library']['library_authentication_method'] == 'innovative_var' || $this->data['Library']['library_authentication_method'] == 'innovative_var_https' || $this->data['Library']['library_authentication_method'] == 'innovative_var_https_wo_pin' || $this->data['Library']['library_authentication_method'] == 'innovative_var_name'){
																foreach($this->data['Variable'] as $k=>$v){
																		$data[$k] = $v;
																		$data[$k]['library_id'] = $this->Library->id;
																}
																$this->Variable->deleteAll(array('library_id' => $this->Library->id));
																$this->Variable->saveAll($data);
															}
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
														if($this->data['LibraryPurchase']['purchased_order_num'] != "" && $this->data['LibraryPurchase']['purchased_amount'] != "") {
														if($this->data['Library']['library_unlimited'] == 1){
															$this->data['LibraryPurchase']['purchased_tracks'] = Configure::read('unlimited');
														} else {
															$this->data['LibraryPurchase']['purchased_tracks'] = $this->data['LibraryPurchase']['purchased_tracks'];
														}
														$this->data['LibraryPurchase']['library_id'] = $this->Library->id;
														$this->data['Library']['id'] = $this->Library->id;

                                                        if($this->LibraryPurchase->save($this->data['LibraryPurchase'])) {
															$contract['library_contract_start_date'] = $this->data['Library']['library_contract_start_date'];
															$contract['library_contract_end_date'] = $this->data['Library']['library_contract_end_date'];
															$contract['library_unlimited'] = $this->data['Library']['library_unlimited'];
															$contract['id_library_purchases'] = $this->LibraryPurchase->id;
															$contract['library_id'] = $this->Library->id;
															$this->ContractLibraryPurchase->save($contract);
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
                    elseif($this->data['Library']['libraryStepNum'] == 1 && $this->data['Library']['library_authentication_method'] == 'innovative_https') {
                        $this->Library->setValidation('library_step'.$this->data['Library']['libraryStepNum'].'_innovative_https');
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
					elseif($this->data['Library']['libraryStepNum'] == 1 && $this->data['Library']['library_authentication_method'] == 'innovative_var_wo_pin') {
                        $this->Library->setValidation('library_step'.$this->data['Library']['libraryStepNum'].'_innovative_var');
                    }				
					elseif($this->data['Library']['libraryStepNum'] == 1 && $this->data['Library']['library_authentication_method'] == 'sip2_var') {
                        $this->Library->setValidation('library_step'.$this->data['Library']['libraryStepNum'].'_sip2_var');
                    }
					elseif($this->data['Library']['libraryStepNum'] == 1 && $this->data['Library']['library_authentication_method'] == 'sip2_var_wo_pin') {
                        $this->Library->setValidation('library_step'.$this->data['Library']['libraryStepNum'].'_sip2_var_wo_pin');
                    }
					elseif($this->data['Library']['libraryStepNum'] == 1 && $this->data['Library']['library_authentication_method'] == 'innovative_var') {
                        $this->Library->setValidation('library_step'.$this->data['Library']['libraryStepNum'].'_innovative_var_name');
                    }
					elseif($this->data['Library']['libraryStepNum'] == 1 && $this->data['Library']['library_authentication_method'] == 'innovative_var_name') {
                        $this->Library->setValidation('library_step'.$this->data['Library']['libraryStepNum'].'_innovative_var');
                    }					
					elseif($this->data['Library']['libraryStepNum'] == 1 && $this->data['Library']['library_authentication_method'] == 'innovative_var_https') {
                        $this->Library->setValidation('library_step'.$this->data['Library']['libraryStepNum'].'_innovative_var_https');
                    }
					elseif($this->data['Library']['libraryStepNum'] == 1 && $this->data['Library']['library_authentication_method'] == 'innovative_var_https_wo_pin') {
                        $this->Library->setValidation('library_step'.$this->data['Library']['libraryStepNum'].'_innovative_var_https_wo_pin');
                    }					
					elseif($this->data['Library']['libraryStepNum'] == 1 && $this->data['Library']['library_authentication_method'] == 'ezproxy') {
                        $this->Library->setValidation('library_step'.$this->data['Library']['libraryStepNum'].'_ezproxy');
                    }
					elseif($this->data['Library']['libraryStepNum'] == 1 && $this->data['Library']['library_authentication_method'] == 'soap') {
                        $this->Library->setValidation('library_step'.$this->data['Library']['libraryStepNum'].'_soap');
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
	$success = "";
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
                    $upload_dir = '../webroot/img/';
                    $fileName = $_REQUEST['LibraryID'].".".$ph;
                    $upload_Path = $upload_dir . $fileName;
                    if(!file_exists($upload_dir)) {
                        mkdir($upload_dir);
                    }
                    move_uploaded_file($_FILES[$fileElementName]["tmp_name"], $upload_Path);
		      $this->Library->recursive = -1;
		      $data = $this->Library->find('all', array('conditions' => array('id' => $_REQUEST['LibraryID'])));
		      $deleteFileName = $data[0]['Library']['library_image_name'];
		      if($deleteFileName != null){
				$error = $this->CdnUpload->deleteFile(Configure::read('App.CDN_PATH').'libraryimg/'.$deleteFileName);
		      }
		      $src = WWW_ROOT.'img/'.$fileName;
		      $dst = Configure::read('App.CDN_PATH').'libraryimg/'.$fileName;
		      $success = $this->CdnUpload->sendFile($src, $dst);
		      unlink($upload_Path);
                    $this->Library->id = $_REQUEST['LibraryID'];
                    $this->Library->saveField('library_image_name', $fileName);
                }
            }
	}
		echo "{";
		echo "success: '" . $success . "',\n";
		echo "error: '" . $error . "',\n";
		echo "msg: '" . $msg . "'\n";
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
			//writing to memcache and writing to both the memcached servers
			$currentPatron = $this->Currentpatron->find('all', array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'], 'patronid' => $patronId)));
			if(count($currentPatron) > 0){
			// do nothing
			} else {
				$insertArr['libid'] = $existingLibraries['0']['Library']['id'];
				$insertArr['patronid'] = $patronId;
				$insertArr['session_id'] = session_id();
				$this->Currentpatron->save($insertArr);						
			}		
			if (($currentPatron = Cache::read("login_".$existingLibraries['0']['Library']['id'].$patronId)) === false) {
				$date = time();
				$values = array(0 => $date, 1 => session_id());			
				Cache::write("login_".$existingLibraries['0']['Library']['id'].$patronId, $values);
			} else {
				$userCache = Cache::read("login_".$existingLibraries['0']['Library']['id'].$patronId);
				$date = time();
				$modifiedTime = $userCache[0];
				if(!($this->Session->read('patron'))){
					if(($date-$modifiedTime) > 60){
						$values = array(0 => $date, 1 => session_id());	
						Cache::write("login_".$existingLibraries['0']['Library']['id'].$patronId, $values);
					}
					else{
						$this->Session->destroy('user');
						$this -> Session -> setFlash("This account is already active.");                              
						$this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
					}
				} else {
					if(($date-$modifiedTime) > 60){
						$values = array(0 => $date, 1 => session_id());	
						Cache::write("login_".$existingLibraries['0']['Library']['id'].$patronId, $values);
					}
					else{
						$this->Session->destroy('user');
						$this -> Session -> setFlash("This account is already active.");                              
						$this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
					}		
				}
				
			}
            $this->Session->write("library", $existingLibraries['0']['Library']['id']);
            $this->Session->write("patron", $patronId);
            $this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
			$this->Session->write("referral_url",$existingLibraries['0']['Library']['library_domain_name']);
			if($existingLibraries['0']['Library']['library_logout_url'] != ''){
				$this->Session->write("referral_url",$existingLibraries['0']['Library']['library_logout_url']);
			}
			if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
				$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
			}
            $isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
            $this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
            $this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
            $results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
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
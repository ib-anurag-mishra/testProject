<?php
 /*
 File Name : users_controller.php
 File Description : Controller page for the  login functionality.
 Author : maycreate
 */
 
Class UsersController extends AppController
{
	var $name = 'Users';
	var $helpers = array('Html','Ajax','Javascript','Form', 'User', 'Library', 'Page', 'Language');
	var $layout = 'admin';
	var $components = array('Session','Auth','Acl','PasswordHelper','Email','sip2','ezproxysso','AuthRequest','Ssl');
	var $uses = array('User','Group', 'Library', 'Currentpatron', 'Download','Variable','Url','Language');
   
   /*
    Function Name : beforeFilter
    Desc : actions that needed before other functions are getting called
   */
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('logout','ilogin','inlogin','ihdlogin','idlogin','ildlogin','indlogin','inhdlogin','inhlogin','slogin','snlogin','sdlogin','sndlogin','plogin','ilhdlogin','admin_user_deactivate','admin_user_activate','admin_patron_deactivate','admin_patron_activate','sso','admin_data');
		//$action = array( 'ilogin', 'idlogin','ildlogin','inlogin','indlogin','slogin','snlogin',
		//						'sdlogin','sndlogin','inhlogin','ihdlogin','ildlogin','plogin','admin_login','login' );
		/*$action = array( 'login','ilogin' );

		if(in_array($this->params['action'] , $action)){
			 $this->Ssl->force();
		} else{
			 $this->Ssl->unforce();
		}*/
		
	}
  /* public function beforeRender(){
		$action = array( 'ilogin', 'idlogin','ildlogin','inlogin','indlogin','slogin','snlogin','sdlogin','sndlogin','inhlogin','ihdlogin','ildlogin','plogin','admin_login','login' );

		if(in_array($this->params['action'] , $action)){
			 $this->Ssl->force();
		} else{
			 $this->Ssl->unforce();
		}
	}
   */
   /*
    Function Name : admin_index
    Desc : actions for welcome admin login
   */
   
	function admin_index($library = null) {
		$userType = $this->Session->read('Auth.User.type_id'); 
		if($this->Session->read('Auth.User.user_status')=='inactive'){
			$this->Session->destroy('user'); 
			$this -> Session -> setFlash("This account has been deactivated.  Please contact your administrator for further questions.");
			$this->redirect(array('controller' => 'users', 'action' => 'login'));	
		}
		if($userType == '5' && $this->Session->read('Auth.User.sales') != 'yes'){
			$this->redirect('/homes/index');
			$this->Auth->autoRedirect = false;
		}
		if($userType == '1' || $this->Session->read('Auth.User.sales') == 'yes'){
			if($library == 'special') {
				$condition = array("library_name REGEXP '^[^A-Za-z]'",'library_status' => 'active');			
			}
			elseif($library != '') {
				$condition = array('library_name LIKE' => $library.'%','library_status' => 'active');
			}
			else {
				$condition = array('library_status' => 'active');
			}
			$url = $_SERVER['REQUEST_URI'];
			header( "refresh:300;url=".$url);
			$this->Library->recursive = -1;
			if($condition != ''){
				$this->paginate = array('conditions' => $condition);
			} else {
				$this->paginate = array('order' => 'library_name');
			}
			$this->set('libraries', $this->paginate('Library'));
		}
		if($userType == '4'){
			$libraryDetail = $this->Library->find("first", array("conditions" => array('library_admin_id' => $this->Session->read("Auth.User.id")),'recursive' => -1)); 
			if($libraryDetail['Library']['library_unlimited'] != '1'){
				$this->set('libraryLimited', 1);
			}
		}
		//takes to the default admin home page
		$this->set('username', $this->Session->read('Auth.User.username'));  //setting the username to display on the header 
	}

   /*
    Function Name : admin_data
    Desc : method for jqgrid
   */	
	function admin_data($library = null) {
		$userType = $this->Session->read('Auth.User.type_id'); 
		if($userType == '1'){
			$this->Library->recursive = -1;
			if($library == 'special') {
				$condition = array('library_name REGEXP "^[^A-Za-z]"');
			}
			elseif($library != '') {
				$condition = array('library_name LIKE' => $library.'%');
			}
			else {
				$condition = "";
			}
			if($condition != ''){
				$cond = array('conditions' => $condition);
				$library = $this->Library->find('all',array( 'fields' => array(
																				'Library.id',
																				'Library.library_name',
																				'Library.library_total_downloads',
																				'Library.library_available_downloads',
																				'Library.library_contract_start_date',
																				'Library.library_available_downloads'
											),'conditions' => $condition));				
			} else {
				$cond = array('order' => 'library_name');
				$library = $this->Library->find('all',array( 'fields' => array(
																				'Library.id',
																				'Library.library_name',
																				'Library.library_total_downloads',
																				'Library.library_available_downloads',
																				'Library.library_contract_start_date',
																				'Library.library_available_downloads'
											),'order' => 'library_name'));					
			}
			$curStartDate = date("Y-m-d")." 00:00:00";
			$curStartDate = date("Y-m-d")." 23:59:59";
			$curWeekStartDate = Configure::read('App.curWeekStartDate');
			$curWeekEndDate = Configure::read('App.curWeekEndDate');
			$monthStartDate = date("Y-m-d", strtotime('this month',strtotime(date('m').'/01/'.date('Y').' 00:00:00')))." 00:00:00";
			$monthEndDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime('this month',strtotime(date('m').'/01/'.date('Y').' 00:00:00')))))." 23:59:59";
			$downloadInstance->recursive = -1;
			foreach($library as $k => $v){
				$library[$k]['Library']['today'] = $this->Download->find('count',array('conditions' => array('library_id' => $v['Library']['id'],'created BETWEEN ? AND ?' => array($curStartDate, $curStartDate))));
				
				$library[$k]['Library']['week'] = $this->Download->find('count',array('conditions' => array('library_id' => $v['Library']['id'],'created BETWEEN ? AND ?' => array($curWeekStartDate, $curWeekEndDate))));
				
				$library[$k]['Library']['month'] = $this->Download->find('count',array('conditions' => array('library_id' => $v['Library']['id'],'created BETWEEN ? AND ?' => array($monthStartDate, $monthEndDate))));
				
				$library[$k]['Library']['ytd'] = $this->Download->find('count',array('conditions' => array('library_id' => $v['Library']['id'],'created BETWEEN ? AND ?' => array($v['Library']['library_contract_start_date']." 00:00:00", date("Y-m-d",strtotime($v['Library']['library_contract_start_date'])+365*24*60*60)." 23:59:59"))));
				
				$library[$k]['Library']['library_contract_end_date'] = date("Y-m-d",strtotime($v['Library']['library_contract_start_date'])+365*24*60*60);
			}
			$this->autoRender=false;
			header ("Content-type: text/xml;charset=utf-8");
			$xml= "<?xml version='1.0' encoding='utf-8'?>"; 
			$xml .= "<rows>"; 
			if($library){						
				while(list($key,$value)= each($library)){

					while( list(,$row)=each($value)){
						$xml = $xml. "<row id='".$row['id']."'>";
						$xml = $xml. "<cell><![CDATA[".htmlentities($row['library_name'],ENT_QUOTES)."]]></cell>";
						$xml = $xml. "<cell><![CDATA[".htmlentities($row['library_contract_start_date'],ENT_QUOTES)."]]></cell>";
						$xml = $xml. "<cell><![CDATA[".htmlentities($row['library_contract_end_date'],ENT_QUOTES)."]]></cell>";
						$xml = $xml. "<cell><![CDATA[".htmlentities($row['today'],ENT_QUOTES)."]]></cell>";
						$xml = $xml. "<cell><![CDATA[".htmlentities($row['week'],ENT_QUOTES)."]]></cell>";
						$xml = $xml. "<cell><![CDATA[".htmlentities($row['month'],ENT_QUOTES)."]]></cell>";
						$xml = $xml. "<cell><![CDATA[".htmlentities($row['ytd'],ENT_QUOTES)."]]></cell>";
						$xml = $xml. "<cell><![CDATA[".htmlentities($row['library_available_downloads'],ENT_QUOTES)."]]></cell>";
						$xml .= "</row>";
					}
				}	
			}
			$xml .= "</rows>";
			return $xml;
			
//			echo json_encode($library);
			
		}
	}

    
   /*
    Function Name : login
    Desc : Validates admin login credentials
   */
   
	function admin_login() {
		$this->layout = 'admin';
		if (empty($this->data)) {
			$this->Session->delete('Message.auth');
		}
		if ($this->Session->read('Auth.User')) {
			$userType = $this->Session->read('Auth.User.type_id');
			if($userType == '5' && $this->Session->read('Auth.User.sales') != 'yes'){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;     
			}
			$this->redirect('/users/admin_index');
			$this->Auth->autoRedirect = false;
		}
	}
    
   /*
    Function Name : logout
    Desc : Logs admin out of the system
   */
   
	function admin_logout() {
		$this->redirect($this->Auth->logout()); 
	}
   
   /*
    Function Name : login
    Desc : Logs users/patrons in to the system
   */
   
	function login(){
		$this->layout = 'login';
		if(isset($_POST['lang'])){
			$language = $_POST['lang'];
			$langDetail = $this->Language->find('first', array('conditions' => array('id' => $language)));
			$this->Session->write('Config.language', $langDetail['Language']['short_name']);
		}
		if ($this->Session->read('patron')){
			$userType = $this->Session->read('patron');
			if($userType != ''){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;     
			}
		}
	}
   
   /*
    Function Name : index
    Desc : Users index to allow/disallow valid users
   */
   
	function index(){ 
		$patronId = $this->Session->read('Auth.User.id');      
		$typeId = $this->Session->read('Auth.User.type_id');
		if($this->Session->read('Auth.User.user_status')=='inactive'){
			$this->Session->destroy('user'); 
			$this -> Session -> setFlash("This account has been deactivated.  Please contact your administrator for further questions.");
			$this->redirect(array('controller' => 'users', 'action' => 'login'));	
		}	  
		if($typeId == '5'){
			$libraryId = $this->Session->read('Auth.User.library_id');
			$this->Library->recursive = -1;
			$libraryArr = $this->Library->find('first',array(                                                
										'conditions' => array('Library.id' => $libraryId)
										)
									);
			if($libraryArr['Library']['library_status'] =='inactive'){
				$this->Session->destroy('user'); 
				$this -> Session -> setFlash("Please see your Library Administrator for access to this Service.");
				$this->redirect(array('controller' => 'users', 'action' => 'login'));	
			}									
			$authMethod = $libraryArr['Library']['library_authentication_method'];        
			if($authMethod == 'user_account'){
				$currentPatron = $this->Currentpatron->find('all', array('conditions' => array('libid' => $libraryId, 'patronid' => $patronId)));
				if(count($currentPatron) > 0){
				// do nothing
				} else {
					$insertArr['libid'] = $libraryId;
					$insertArr['patronid'] = $patronId;
					$insertArr['session_id'] = session_id();
					$this->Currentpatron->save($insertArr);						
				}
				//writing to memcache and writing to both the memcached servers
				if (($currentPatron = Cache::read("login_".$libraryId.$patronId)) === false) {
					$date = time();
					$values = array(0 => $date, 1 => session_id());			
					Cache::write("login_".$libraryId.$patronId, $values);
				} else {
					$userCache = Cache::read("login_".$libraryId.$patronId);
					$date = time();
					$modifiedTime = $userCache[0];
					if(!($this->Session->read('patron'))){
						if(($date-$modifiedTime) > 60){
							$values = array(0 => $date, 1 => session_id());	
							Cache::write("login_".$libraryId.$patronId, $values);
						}
						else{
							$this->Session->destroy('user');
							$this -> Session -> setFlash("This account is already active."); 
							$this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
						}
					} else {
						if(($date-$modifiedTime) > 60){
							$values = array(0 => $date, 1 => session_id());	
							Cache::write("login_".$libraryId.$patronId, $values);
						}
						else{
							$this->Session->destroy('user');
							$this -> Session -> setFlash("This account is already active."); 
							$this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
						}		
					}
					
				}				
		
				$this->Session->write("library", $libraryId);
				$this->Session->write("patron", $patronId);
				$this->Session->write("territory", $libraryArr['Library']['library_territory']);
				$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $libraryId,'patronid' => $patronId)));            
				$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
				$this->Session->write("downloadsAllotted", $libraryArr['Library']['library_user_download_limit']);
				if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
					$this->Session->write('Config.language', $libraryArr['Library']['library_language']);
				}
				$this->Download->recursive = -1;
				$results =  $this->Download->find('count',array('conditions' => array('library_id' => $libraryId,'patron_id' => $patronId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
				$this ->Session->write("downloadsUsed", $results);            
				if($libraryArr['Library']['library_block_explicit_content'] == '1'){
					$this ->Session->write("block", 'yes');
				}
				else{
					$this ->Session->write("block", 'no');
				}
				$this->redirect(array('controller' => 'homes', 'action' => 'index'));
			}
			else{
				$this->redirect($this->Auth->logout());
			}
		}
		else{
			$this->redirect($this->Auth->logout());
		}
	}
   
   /*
    Function Name : logout
    Desc : Logs users/patron out of the system
   */
   
	function logout() {      
		$patronId = $this->Session->read('patron');
		$libraryId = $this->Session->read('library');
		$patronDetails = $this->Currentpatron->find('all',array('conditions' => array('patronid' => $patronId,'libid' => $libraryId)));
		if(count($patronDetails) > 0){         
			$updateTime = date( "Y-m-d H:i:s", time()-60 );
			$this->Currentpatron->id = $patronDetails[0]['Currentpatron']['id'];        
			$this->Currentpatron->saveField('modified',$updateTime, false);
			//writing to memcache and writing to both the memcached servers
			Cache::delete("login_".$libraryId.$patronId);			
			if($this->Session->read('referral_url') && ($this->Session->read('referral_url') != '')){            
				$redirectUrl = $this->Session->read('referral_url');
				$this->Session->destroy();
				$this->redirect($redirectUrl, null, true);
			}
			elseif($this->Session->read('innovative') && ($this->Session->read('innovative') != '')){            
				if($this->Session->read('referral')){
					$redirectUrl = $this->Session->read('referral');
					$this->Session->destroy();
					$this->redirect($redirectUrl, null, true);
				} else {
					$this->Session->destroy();
					$this->redirect(array('controller' => 'users', 'action' => 'ilogin'));				
				}
			}
			elseif($this->Session->read('innovative_var') && ($this->Session->read('innovative_var') != '')){            
				if($this->Session->read('referral')){
					$redirectUrl = $this->Session->read('referral');
					$this->Session->destroy();
					$this->redirect($redirectUrl, null, true);
				} else {
					$this->Session->destroy();
					$this->redirect(array('controller' => 'users', 'action' => 'idlogin'));				
				}
			}
			elseif($this->Session->read('innovative_var_name') && ($this->Session->read('innovative_var_name') != '')){            
				if($this->Session->read('referral')){
					$redirectUrl = $this->Session->read('referral');
					$this->Session->destroy();
					$this->redirect($redirectUrl, null, true);
				} else {
					$this->Session->destroy();
					$this->redirect(array('controller' => 'users', 'action' => 'ildlogin'));				
				}
			}
			elseif($this->Session->read('innovative_var_https_name') && ($this->Session->read('innovative_var_https_name') != '')){            
				if($this->Session->read('referral')){
					$redirectUrl = $this->Session->read('referral');
					$this->Session->destroy();
					$this->redirect($redirectUrl, null, true);
				} else {
					$this->Session->destroy();
					$this->redirect(array('controller' => 'users', 'action' => 'ilhdlogin'));				
				}
			}			
			elseif($this->Session->read('innovative_var_https') && ($this->Session->read('innovative_var_https') != '')){            
				if($this->Session->read('referral')){
					$redirectUrl = $this->Session->read('referral');
					$this->Session->destroy();
					$this->redirect($redirectUrl, null, true);
				} else {
					$this->Session->destroy();
					$this->redirect(array('controller' => 'users', 'action' => 'ihdlogin'));				
				}
			}
			elseif($this->Session->read('innovative_var_https_wo_pin') && ($this->Session->read('innovative_var_https_wo_pin') != '')){            
				if($this->Session->read('referral')){
					$redirectUrl = $this->Session->read('referral');
					$this->Session->destroy();
					$this->redirect($redirectUrl, null, true);
				} else {
					$this->Session->destroy();
					$this->redirect(array('controller' => 'users', 'action' => 'inhdlogin'));				
				}
			}			
			elseif($this->Session->read('innovative_https') && ($this->Session->read('innovative_https') != '')){            
				if($this->Session->read('referral')){
					$redirectUrl = $this->Session->read('referral');
					$this->Session->destroy();
					$this->redirect($redirectUrl, null, true);
				} else {
					$this->Session->destroy();
					$this->redirect(array('controller' => 'users', 'action' => 'inhlogin'));				
				}
			}		 
			elseif($this->Session->read('innovative_wo_pin') && ($this->Session->read('innovative_wo_pin') != '')){            
				if($this->Session->read('referral')){
					$redirectUrl = $this->Session->read('referral');
					$this->Session->destroy();
					$this->redirect($redirectUrl, null, true);
				} else {
					$this->Session->destroy();
					$this->redirect(array('controller' => 'users', 'action' => 'inlogin'));				
				}
			}
			elseif($this->Session->read('innovative_var_wo_pin') && ($this->Session->read('innovative_var_wo_pin') != '')){
				if($this->Session->read('referral')){
					$redirectUrl = $this->Session->read('referral');
					$this->Session->destroy();
					$this->redirect($redirectUrl, null, true);
				} else {
					$this->Session->destroy();
					$this->redirect(array('controller' => 'users', 'action' => 'indlogin'));				
				}
			}		 
			elseif($this->Session->read('sip2') && ($this->Session->read('sip2') != '')){            
				if($this->Session->read('referral')){
					$redirectUrl = $this->Session->read('referral');
					$this->Session->destroy();
					$this->redirect($redirectUrl, null, true);
				} else {
					$this->Session->destroy();
					$this->redirect(array('controller' => 'users', 'action' => 'slogin'));				
				}
			}
			elseif($this->Session->read('sip') && ($this->Session->read('sip') != '')){            
				if($this->Session->read('referral')){
					$redirectUrl = $this->Session->read('referral');
					$this->Session->destroy();
					$this->redirect($redirectUrl, null, true);
				} else {
					$this->Session->destroy();
					$this->redirect(array('controller' => 'users', 'action' => 'snlogin'));				
				}
			}
			elseif($this->Session->read('sip2_var') && ($this->Session->read('sip2_var') != '')){            
				if($this->Session->read('referral')){
					$redirectUrl = $this->Session->read('referral');
					$this->Session->destroy();
					$this->redirect($redirectUrl, null, true);
				} else {
					$this->Session->destroy();
					$this->redirect(array('controller' => 'users', 'action' => 'sdlogin'));				
				}
			}
			elseif($this->Session->read('sip2_var_wo_pin') && ($this->Session->read('sip2_var_wo_pin') != '')){            
				if($this->Session->read('referral')){
					$redirectUrl = $this->Session->read('referral');
					$this->Session->destroy();
					$this->redirect($redirectUrl, null, true);
				} else {
					$this->Session->destroy();
					$this->redirect(array('controller' => 'users', 'action' => 'sndlogin'));				
				}
			}
			elseif($this->Session->read('soap') && ($this->Session->read('soap') != '')){            
				if($this->Session->read('referral')){
					$redirectUrl = $this->Session->read('referral');
					$this->Session->destroy();
					$this->redirect($redirectUrl, null, true);
				} else {
					$this->Session->destroy();
					$this->redirect(array('controller' => 'users', 'action' => 'plogin'));				
				}
			}			
			elseif($this->Session->read('ezproxy') && ($this->Session->read('ezproxy') != '')){
				if($this->Session->read('ezproxy_logout') != ''){
					$redirectUrl = $this->Session->read('ezproxy_logout');
					$this->Session->destroy();
					$this->redirect($redirectUrl, null, true);				
				}
				else{
					$redirectUrl = $this->Session->read('referral');
					$redirectUrl = str_replace('login', 'logout',$redirectUrl);
					$this->Session->destroy();
					$this->redirect($redirectUrl, null, true);
				}
			}		
			else{            
			$this->Session->destroy();
			$this->redirect($this->Auth->logout());    
			}         
		}else{
			$this->redirect($this->Auth->logout());
		}     
	}
   
   /*
    Function Name : admin_manageuser
    Desc : action for listing all the admin users
   */
   
	function admin_manageuser($user = null){
		if($user == 'special') {
			$condition = "type_id <> 5 AND last_name REGEXP '^[^A-Za-z]'";			
		}
		elseif($user != '') {
			$condition = "type_id <> 5 AND last_name LIKE '".$user."%'";
		}
		else {
			$condition = "type_id <> 5";
		}
		$this->User->recursive = -1;
		$this->paginate = array('order' => 'created');
		$this->paginate = array('conditions' => array($condition));
		$this->set('admins', $this->paginate('User'));
	}
    
   /*
    Function Name : admin_userform
    Desc : action for displaying the add/edit user form
   */
   
	function admin_userform() {
		if(!empty($this->params['named']['id'])){ //gets the values from the url in form  of array
			$adminUserId = $this->params['named']['id'];
			if(trim($adminUserId) != "" && is_numeric($adminUserId)){
				$this->set('formAction','admin_userform/id:'.$adminUserId);
				$this->set('formHeader','Edit User');
				$this->set('getData', $this->User->getuserdata($adminUserId));
				//editting a value
				if(isset($this->data)){
					$updateObj = new User();
					$getData['User'] = $this->data['User'];
					$this->data['User']['library_id'] = Configure::read('LibraryIdeas');
					if($this->data['User']['type_id'] == 5){
						$this->data['User']['sales'] = 'yes';
					}					
					$getData['Group']['id'] = $this->data['User']['type_id'];
					$this->set('getData', $getData);
					$this->User->id = $this->data['User']['id'];
					if(trim($this->data['User']['password']) == "48d63321789626f8844afe7fdd21174eeacb5ee5"){
						// do not update the password
						$this->data['User']= $updateObj->arrayremovekey($this->data['User'],'password');
					}
					$this->User->set($this->data['User']);
					if($this->User->save()){
						$this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
						$this->redirect('manageuser');
					}                
				}               
			}
		}else{
			$arr = array();
			$this->set('getData',$arr);
			$this->set('formAction','admin_userform');
			$this->set('formHeader','Create User');               
			//insertion Operation
			if(isset($this->data)){
				$insertObj = new User();                    
				$getData['User'] = $this->data['User'];
				$this->data['User']['library_id'] = Configure::read('LibraryIdeas');;
				if($this->data['User']['type_id'] == 5){
					$this->data['User']['sales'] = 'yes';
				}
				$getData['Group']['id'] = $this->data['User']['type_id'];
				$this->set('getData', $getData);
				if($this->data['User']['password'] == "48d63321789626f8844afe7fdd21174eeacb5ee5"){                     
					$this->data['User']['password'] = "";                      
				}                  
				$this->User->set($this->data['User']);                  
				if($this->User->save()){                    
					$this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
					$this->redirect('manageuser');
				}
				else{
					$this->data['User']['password'] = '';
					$this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
				}             
			}               
		}
		$this->set('options',$this->Group->getallusertype());
	}
   
   /*
    Function Name : admin_managepatron
    Desc : action for listing all the patron users
   */
   
	function admin_managepatron($user = null){
		if($user == 'special') {
			$cond = "last_name REGEXP '^[^A-Za-z]'";			
		}
		elseif($user != '') {
			$cond = "last_name LIKE '".$user."%'";
		}
		else {
			$cond = "";
		}
		if($this->Session->read("Auth.User.type_id") == 4 && $this->Library->getAuthenticationType($this->Session->read('Auth.User.id')) == "referral_url") {
			$this->redirect('/admin/reports/index');
		}
		if($this->Session->read("Auth.User.type_id") == 4) {
			$libraryAdminID = $this->Library->find("first", array("conditions" => array('library_admin_id' => $this->Session->read("Auth.User.id")), 'fields' => array('id', 'library_name'), 'recursive' => -1));
			$this->set('libraryID', $libraryAdminID["Library"]["id"]);
			$this->set('libraryname', $libraryAdminID["Library"]["library_name"]);
			$this->paginate = array('conditions' => array('type_id' => 5, 'library_id' => $libraryAdminID["Library"]["id"],$cond),'order' => array('created'));
		}
		else {
			$this->set('libraryID', "");
			$this->paginate = array('conditions' => array('type_id' => 5,$cond),'order' => array('created'));
		}
		$this->User->recursive = -1;
		$this->set('patrons', $this->paginate('User'));
	}
   
   /*
    Function Name : admin_patronform
    Desc : action for displaying the add/edit patron form
   */
   
	function admin_patronform() {
		if($this->Session->read("Auth.User.type_id") == 4 && $this->Library->getAuthenticationType($this->Session->read('Auth.User.id')) == "referral_url") {
			$this->redirect('/admin/reports/index');
		}
		if(!empty($this->params['named']['id'])){ //gets the values from the url in form  of array
			$patronUserId = $this->params['named']['id'];
			if(trim($patronUserId) != "" && is_numeric($patronUserId)){
				$this->set('formAction','admin_patronform/id:'.$patronUserId);
				$this->set('formHeader','Edit Patron');     
				$this->set('getData', $this->User->getuserdata($patronUserId));
				//editting a value
				if(isset($this->data)){
					$updateObj = new User();
					$getData['User'] = $this->data['User'];
					$getData['Group']['id'] = $this->data['User']['type_id'];
					if(isset($this->data['Check']['sales'])){
						$this->data['User']['sales'] = 'yes';
					}					
					$this->set('getData', $getData);
					$this->User->id = $this->data['User']['id'];
					$password = trim($this->data['User']['password']);
					if(trim($this->data['User']['password']) == "48d63321789626f8844afe7fdd21174eeacb5ee5"){
						// do not update the password
						$this->data['User'] = $updateObj->arrayremovekey($this->data['User'],'password');
					}
					$this->User->set($this->data['User']);
					$this->User->setValidation('validate_patron_super_admin');
					if($this->User->validates()) {
						if($this->User->save()){
							if($password != "48d63321789626f8844afe7fdd21174eeacb5ee5"){
								$temp_password = $this->data['User']['original_password'];
								$this->_sendModifyPatronMail( $this->User->id, $temp_password );
							}
							$this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
							$this->redirect('managepatron');
						}
						else {
							$this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
						}
					}
					else {
						$this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
					}
				}                
			}
		}
		else{
			$arr = array();
			$this->set('getData',$arr);
			$this->set('formAction','admin_patronform');
			$this->set('formHeader','Create Patron');               
			//insertion Operation
			if(isset($this->data)){
				$insertObj = new User();
				$getData['User'] = $this->data['User'];
				$getData['Group']['id'] = $this->data['User']['type_id'];
				if(isset($this->data['Check']['sales'])){
					$this->data['User']['sales'] = 'yes';
				}
				
				$this->set('getData', $getData);                  
				$this->User->set($this->data['User']);
				$this->User->setValidation('validate_patron_super_admin');
				if($this->User->validates()){
					$temp_password = $this->PasswordHelper->generatePassword(8);
					$this->data['User']['password'] = Security::hash(Configure::read('Security.salt').$temp_password);
					$this->User->set($this->data['User']);
					if($this->User->save()){
						$receipt = $this->_sendNewPatronMail( $this->User->id, $temp_password );
						if($receipt == '1'){
							$this->Session->setFlash('Data has been saved successfully and the Email has been sent.', 'modal', array('class' => 'modal success'));
						} else {
							$this->Session->setFlash($receipt, 'modal', array('class' => 'modal problem'));					  
						}
						$this->redirect('managepatron');
					}
					else{
						$this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
					}
				}
				else{
					$this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
				}
			}
			//insertion operation
		}
		if($this->Session->read("Auth.User.type_id") == 4) {
			$libraryAdminID = $this->Library->find("first", array("conditions" => array('library_admin_id' => $this->Session->read("Auth.User.id")), 'fields' => array('id', 'library_name'), 'recursive' => -1));
			$this->set('libraryID', $libraryAdminID["Library"]["id"]);
			$this->set('libraryname', $libraryAdminID["Library"]["library_name"]);
		}
		else {
			$this->set('libraries', $this->Library->find('list', array("conditions" => array('library_authentication_method' => 'user_account'), 'fields' => array('Library.library_name'), 'order' => 'Library.library_name ASC', 'recursive' => -1)));
			$this->set('libraryID', "");
		}
		$this->set('options',$this->Group->getallusertype());
	}
     
   /*
    Function Name : delete
    Desc : For deleting a user
   */
   
	function admin_delete() {
		$deleteAdminUserId = $this->params['named']['id'];      
		if($this->User->delete($deleteAdminUserId)){
			$this->Session->setFlash('Data deleted successfully!', 'modal', array('class' => 'modal success'));
			$this->redirect('manageuser');
		}else{
			$this->Session->setFlash('Error occured while deleteting the record', 'modal', array('class' => 'modal problem'));
			$this->redirect('manageuser');
		}
	}
 
    /*
     Function Name : admin_user_activate
     Desc : For activating a User
    */
	
    function admin_user_activate() {
        $userID = $this->params['named']['id'];
        if(trim($userID) != "" && is_numeric($userID)) {
			$existingUser = $this->User->getuserdata($userID);
            $this->User->id = $userID;
			$this->User->set(array('user_status' => 'active','type_id'=>$existingUser['User']['type_id']));
            $this->Session -> setFlash( 'User activated successfully!', 'modal', array( 'class' => 'modal success' ) );
            $this->User->save();
            $this->autoRender = false;
            $this->redirect('manageuser');
        }
        else {
            $this->Session -> setFlash( 'Error occured while activating the library', 'modal', array( 'class' => 'modal problem' ) );
            $this->autoRender = false;
            $this->redirect('manageuser');
        }
    }
	
    /*
     Function Name : admin_user_deactivate
     Desc : For deactivating a User
    */
	
    function admin_user_deactivate() {
        $userID = $this->params['named']['id'];
        if(trim($userID) != "" && is_numeric($userID)) {
			$existingUser = $this->User->getuserdata($userID);
            $this->User->id = $userID;
			$this->User->set(array('user_status' => 'inactive','type_id'=>$existingUser['User']['type_id']));
            $this->Session -> setFlash( 'User deactivated successfully!', 'modal', array( 'class' => 'modal success' ) );
            $this->User->save();
            $this->autoRender = false;
            $this->redirect('manageuser');
        }
        else {
            $this->Session->setFlash('Error occured while deactivating the User', 'modal', array('class' => 'modal problem'));
            $this->autoRender = false;
            $this->redirect('manageuser');
        }
    }
    
    /*
     Function Name : admin_patron_activate
     Desc : For activating a Patron
    */
	
    function admin_patron_activate() {
        $userID = $this->params['named']['id'];
        if(trim($userID) != "" && is_numeric($userID)) {
			$existingUser = $this->User->getuserdata($userID);
            $this->User->id = $userID;
			$this->User->set(array('user_status' => 'active','type_id'=>$existingUser['User']['type_id']));
            $this->Session -> setFlash( 'Patron activated successfully!', 'modal', array( 'class' => 'modal success' ) );
            $this->User->save();
            $this->autoRender = false;
            $this->redirect('managepatron');
        }
        else {
            $this->Session -> setFlash( 'Error occured while activating the library', 'modal', array( 'class' => 'modal problem' ) );
            $this->autoRender = false;
            $this->redirect('managepatron');
        }
    }
	
    /*
     Function Name : admin_patron_deactivate
     Desc : For deactivating a Patron
    */
	
    function admin_patron_deactivate() {
        $userID = $this->params['named']['id'];
        if(trim($userID) != "" && is_numeric($userID)) {
			$existingUser = $this->User->getuserdata($userID);
            $this->User->id = $userID;
			$this->User->set(array('user_status' => 'inactive','type_id'=>$existingUser['User']['type_id']));
            $this->Session -> setFlash( 'Patron deactivated successfully!', 'modal', array( 'class' => 'modal success' ) );
            $this->User->save();
            $this->autoRender = false;
            $this->redirect('managepatron');
        }
        else {
            $this->Session->setFlash('Error occured while deactivating the User', 'modal', array('class' => 'modal problem'));
            $this->autoRender = false;
            $this->redirect('managepatron');
        }
    }
    
 
   /*
    Function Name : _sendNewPatronMail
    Desc : For sending new patron email
   */
   
	function _sendNewPatronMail($id, $password) {
		Configure::write('debug', 0);
		$this->Email->template = 'email/newPatronEmail';
		$this->User->recursive = -1;
		$Patron = $this->User->read(null,$id);
		$this->set('Patron', $Patron);
		$this->set('password', $password);
		$this->Email->to = $Patron['User']['email'];
		$this->Email->from = Configure::read('App.adminEmail');
		$this->Email->fromName = Configure::read('App.fromName');
		$this->Email->subject = 'FreegalMusic - New patron account information';
		$this->Email->smtpHostNames = Configure::read('App.SMTP');
		$this->Email->smtpAuth = Configure::read('App.SMTP_AUTH');
		$this->Email->smtpUserName = Configure::read('App.SMTP_USERNAME');
		$this->Email->smtpPassword = Configure::read('App.SMTP_PASSWORD');
		$result = $this->Email->send();
		return $result;
	}
   
   /*
    Function Name : _sendModifyPatronMail
    Desc : For sending modified patron email
   */
   
	function _sendModifyPatronMail($id, $password) {
		Configure::write('debug', 0);
		$this->Email->template = 'email/modifyPatronEmail';
		$this->User->recursive = -1;
		$Patron = $this->User->read(null,$id);
		$this->set('Patron', $Patron);
		$this->set('password', $password);
		$this->Email->to = $Patron['User']['email'];
		$this->Email->from = Configure::read('App.adminEmail');
		$this->Email->fromName = Configure::read('App.fromName');
		$this->Email->subject = 'FreegalMusic - Patron account password changed!!';
		$this->Email->smtpHostNames = Configure::read('App.SMTP');
		$this->Email->smtpAuth = Configure::read('App.SMTP_AUTH');
		$this->Email->smtpUserName = Configure::read('App.SMTP_USERNAME');
		$this->Email->smtpPassword = Configure::read('App.SMTP_PASSWORD');
		$result = $this->Email->send(); 
	}
   
   /*
    Function Name : my_account
    Desc : For patron my acount page
   */
   
	function my_account(){
		$this->layout = 'home';
		$patronId = $this->Session->read('patron');
		$this->set('getData', $this->User->getuserdata($patronId));
		if(isset($this->data)){
			$this->data['User']['type_id'] = 5;
			$getData['User'] = $this->data['User'];
			$getData['type_id'] = 5;        
			$this->set('getData', $getData);        
			if(trim($this->data['User']['password']) == "48d63321789626f8844afe7fdd21174eeacb5ee5"){            
				// do not update the password
				$this->data['User']= $this->User->arrayremovekey($this->data['User'],'password');
			}         
			$this->User->set($this->data['User']);        
			if($this->User->save()){         
				$this->Session->setFlash('Data has been saved successfully!');
				$this->redirect($this->webroot.'users/my_account');
			}
		}
	}
   
   /*
    Function Name : ilogin
    Desc : For patron ilogin(Innovative) login method
   */
   
	function ilogin(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
					}
				}
				else {
					$wrongReferral = 1;
				}	
			}
		}
		$this->layout = 'login';
		if(isset($_POST['lang'])){
			$language = $_POST['lang'];
			$langDetail = $this->Language->find('first', array('conditions' => array('id' => $language)));
			$this->Session->write('Config.language', $langDetail['Language']['short_name']);
		}		
		if ($this->Session->read('patron')){
			$userType = $this->Session->read('patron');
			if($userType != ''){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;     
			}
		}
		$this->set('pin',"");
		$this->set('card',"");
		if($this->data){         
			$card = $this->data['User']['card'];
			$pin = $this->data['User']['pin'];
			$patronId = $card;        
			if($card == ''){            
				$this -> Session -> setFlash("Please provide card number.");
				if($pin != ''){
					$this->set('pin',$pin);
				}
				else{
					$this->set('pin',"");
				}            
			}
			elseif($pin == ''){            
				$this -> Session -> setFlash("Please provide pin.");            
				if($card != ''){
					$this->set('card',$card);
				}
				else{
					$this->set('card',"");
				}            
			}
			else{
				$cardNo = substr($card,0,5);
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				if($this->Session->read('referral')){
					$library_cond = array('id' => $this->Session->read('lId'));
					$existingLibraries = $this->Library->find('all',array(
									'conditions' => array('library_status' => 'active','library_authentication_method' => 'innovative',$library_cond),
									'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
									)
								 );					
				} else {
					$library_cond = '';
					$existingLibraries = $this->Library->find('all',array(
									'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'innovative',$library_cond),
									'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
									)
								 );					
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "http://".$_SERVER['HTTP_HOST']."/users/ilogin"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
					$this->redirect(array('controller' => 'users', 'action' => 'ilogin'));
				}        
				else{
					$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];               
					$data['url'] = $authUrl."/PATRONAPI/".$card."/".$pin."/pintest";
					$authUrl = configure::read('App.dataHandlerUrl');
					$result = $this->AuthRequest->getAuthResponse($data,$authUrl);
					echo $result;exit;
					$dom= new DOMDocument();
					@$dom->loadHtml($result);
					$xpath = new DOMXPath($dom);
					$body = $xpath->query('/html/body');
					$retStr = $dom->saveXml($body->item(0));
					
					if(strpos($retStr,"P BARCODE[pb]")){
						if(strpos($retStr,$card)){
							$posVal = true;
						} else {
							$posVal = false;
						}
					} else {
						$posVal = true;
					}					
					$retMsgArr = explode("RETCOD=",$retStr);               
					@$retStatus = $retMsgArr['1'];               
					if($retStatus == ''){
						$errMsgArr =  explode("ERRNUM=",$retMsgArr['0']);
						@$errMsgCount = substr($errMsgArr['1'],0,1);
						if($errMsgCount == '1'){
							$this -> Session -> setFlash("Requested record not found.");
							$this->redirect(array('controller' => 'users', 'action' => 'ilogin'));
						}
						else{
							$this -> Session -> setFlash("Authentication server down.");
							$this->redirect(array('controller' => 'users', 'action' => 'ilogin'));
						}                  
					}
					elseif($retStatus == 0 && $posVal != false){
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
						$this->Session->write("innovative","innovative");
						if($existingLibraries['0']['Library']['library_logout_url'] != '' && $this->Session->read('referral') != ''){
							$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
						}
						if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
							$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
						}
						$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
						$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
						$this->Download->recursive = -1;
						$this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
						$results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
						$this ->Session->write("downloadsUsed", $results);
						if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
							$this ->Session->write("block", 'yes');
						}
						else{
							$this ->Session->write("block", 'no');
						}
						$this->redirect(array('controller' => 'homes', 'action' => 'index'));
					}
					else{
						$errStrArr = explode('ERRMSG=',$retStr);
						$errMsg = $errStrArr['1'];
						$this->Session->setFlash($errMsg);
						if($posVal == false){
							$this->Session->setFlash("Card number does not match Library record");
						}
						$this->redirect(array('controller' => 'users', 'action' => 'ilogin'));
					}
				}
			}         
		}
	}
   
   /*
    Function Name : idlogin
    Desc : For patron idlogin(Innovative pin) login method
   */
   
   function idlogin(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
					}
				}
				else {
					$wrongReferral = 1;
				}	
			}
		}
		$this->layout = 'login';
		if(isset($_POST['lang'])){
			$language = $_POST['lang'];
			$langDetail = $this->Language->find('first', array('conditions' => array('id' => $language)));
			$this->Session->write('Config.language', $langDetail['Language']['short_name']);
		}	
		if ($this->Session->read('patron')){
			$userType = $this->Session->read('patron');
			if($userType != ''){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;     
			}
		}
		$this->set('pin',"");
		$this->set('card',"");
		if($this->data){         
			$card = $this->data['User']['card'];
			$pin = $this->data['User']['pin'];
			$patronId = $card;        
			if($card == ''){            
				$this -> Session -> setFlash("Please provide card number.");
				if($pin != ''){
				   $this->set('pin',$pin);
				}
				else{
				   $this->set('pin',"");
				}            
			}
			elseif($pin == ''){            
				$this -> Session -> setFlash("Please provide pin.");            
				if($card != ''){
				   $this->set('card',$card);
				}
				else{
				   $this->set('card',"");
				}            
			}
			else{
				$cardNo = substr($card,0,5);
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				if($this->Session->read('referral')){
					$library_cond = array('id' => $this->Session->read('lId'));
					$existingLibraries = $this->Library->find('all',array(
															'conditions' => array('library_status' => 'active','library_authentication_method' => 'innovative_var',$library_cond),
															'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
															)
													 );
				} 
				else {
					$library_cond = '';
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'innovative_var',$library_cond),
														'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
														)
													 );
				}	
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "http://".$_SERVER['HTTP_HOST']."/users/idlogin"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
				   $this->redirect(array('controller' => 'users', 'action' => 'idlogin'));
				}        
				else{
					$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];               
					$data['url'] = $authUrl."/PATRONAPI/".$card."/".$pin."/pintest";
					$authUrl = configure::read('App.dataHandlerUrl');
					$result = $this->AuthRequest->getAuthResponse($data,$authUrl);
					$dom= new DOMDocument();
					@$dom->loadHtml($result);
					$xpath = new DOMXPath($dom);
					$body = $xpath->query('/html/body');
					$retStr = $dom->saveXml($body->item(0));
					if(strpos($retStr,"P BARCODE[pb]")){
						if(strpos($retStr,$card)){
							$posVal = true;
						} else {
							$posVal = false;
						}
					} else {
						$posVal = true;
					}					
					$retMsgArr = explode("RETCOD=",$retStr);               
					@$retStatus = $retMsgArr['1'];               
					if($retStatus == ''){
						$errMsgArr =  explode("ERRNUM=",$retMsgArr['0']);
						@$errMsgCount = substr($errMsgArr['1'],0,1);
						if($errMsgCount == '1'){
							$this -> Session -> setFlash("Requested record not found.");
							$this->redirect(array('controller' => 'users', 'action' => 'idlogin'));
						}
						else{
							$this -> Session -> setFlash("Authentication server down.");
							$this->redirect(array('controller' => 'users', 'action' => 'idlogin'));
						}                  
					}
					elseif($retStatus == 0 && $posVal != false){
						$authUrlDump = $existingLibraries['0']['Library']['library_authentication_url'];               
						$data['url'] = $authUrlDump."/PATRONAPI/".$card."/dump";
						$authUrl = configure::read('App.dataHandlerUrl');
						$result = $this->AuthRequest->getAuthResponse($data,$authUrl);						
						$domDump= new DOMDocument();
						@$domDump->loadHtml($result);
						$xpathDump = new DOMXPath($domDump);
						$bodyDump = $xpathDump->query('/html/body');
						$retStrDump = $domDump->saveXml($bodyDump->item(0));
						$this->Variable->recursive = -1;
						$allVariables = $this->Variable->find('all',array(
												'conditions' => array('library_id' => $existingLibraries['0']['Library']['id']),
												'fields' => array('authentication_variable','authentication_response','comparison_operator','error_msg',)
											)
										);
						$status = 1;
						foreach($allVariables as $k=>$v){
							$response = explode(",",$v['Variable']['authentication_response']);
							$retStatusArr = explode($v['Variable']['authentication_variable'],$retStrDump);
							$pos = strpos($retStatusArr['1'],"<br/>");
							$retStatus = substr($retStatusArr['1'],1,$pos-1);
							if($retStatus == ''){
								$status = '';
							}
							elseif($v['Variable']['comparison_operator'] == '='){
								$check = strpos($v['Variable']['authentication_response'],$retStatus);
								if(!($check === false)){
									$status = 1;
								}
								else{
									$status = 'error';
								}
							}
							elseif($v['Variable']['comparison_operator'] == '<'){
								foreach($response as $key => $val){
									$res = explode("$",$retStatus);
									if(isset($res[1])){
										$cmp = $res[1];
									} 
									else {
										$cmp = $res[0];
									}							
									if($cmp < $val){
										$status = 1;
										break;
									}else{
										$status = false;
									}
								}
							}
							elseif($v['Variable']['comparison_operator'] == '>'){
								foreach($response as $key => $val){
									$res = explode("$",$retStatus);
									if(isset($res[1])){
										$cmp = $res[1];
									} 
									else {
										$cmp = $res[0];
									}							
									if($cmp > $val){
										$status = 1;
										break;
									}else{
										$status = false;
									}
								}
							}
							elseif($v['Variable']['comparison_operator'] == '<>'){
								foreach($response as $key => $val){
									$res = explode("$",$retStatus);
									if(isset($res[1])){
										$cmp = $res[1];
									} 
									else {
										$cmp = $res[0];
									}							
									if($cmp != $val){
										$status = 1;
										break;
									}else{
										$status = false;
									}
								}
							}
							elseif($v['Variable']['comparison_operator'] == 'contains'){
								$res = explode("$",$retStatus);
								if(isset($res[1])){
									$cmp = $res[1];
								} 
								else {
									$cmp = $res[0];
								}
								$check = strpos($cmp,$v['Variable']['authentication_response']);
								if(!($check === false)){
									$status = 1;
								}
								else{
									$status = 'error';
								}
							}							
							elseif($v['Variable']['comparison_operator'] == 'date'){
								$res = explode("$",$retStatus);
								if(isset($res[1])){
									$cmp = $res[1];
								} 
								else {
									$cmp = $res[0];
								}							
								$resDateArr = explode("-",$cmp);
								$resDate = mktime(0,0,0,$resDateArr[0],$resDateArr[1],$resDateArr[2]);
								$libDate = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
								if($resDate > $libDate){
									$status = 1;
								}
								else{
									$status = 'error';
								}
							}							   
							else{
								$status = 'error';
							}
							if(!$status || $status == 'error'){
								$msg = $v['Variable']['error_msg'];
								$status = 'error';
								break;
							}
						}						
						if($status == ''){
							$errMsgArr =  explode("ERRNUM=",$retStr);
							@$errMsgCount = substr($errMsgArr['1'],0,1);
							if($errMsgCount == '1'){
								$this -> Session -> setFlash("Requested record not found.");
								$this->redirect(array('controller' => 'users', 'action' => 'idlogin'));
							}
							else{
								$this -> Session -> setFlash("Authentication server down.");
								$this->redirect(array('controller' => 'users', 'action' => 'idlogin'));
							}                  
						}
						elseif($status == 1){
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
							$this->Session->write("innovative_var","innovative_var");
							if($existingLibraries['0']['Library']['library_logout_url'] != '' && $this->Session->read('referral') != ''){
								$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
							}
							if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
								$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
							}
							$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
							$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);						
							$this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
							$this->Download->recursive = -1;
							$results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
							$this ->Session->write("downloadsUsed", $results);
							if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
								$this ->Session->write("block", 'yes');
							}
							else{
								$this ->Session->write("block", 'no');
							}
							$this->redirect(array('controller' => 'homes', 'action' => 'index'));
						}
						else{
							$this -> Session -> setFlash($msg);
							$this->redirect(array('controller' => 'users', 'action' => 'idlogin'));
						}
					} else {
						$errStrArr = explode('ERRMSG=',$retStr);
						$errMsg = $errStrArr['1'];
						$this->Session->setFlash($errMsg);
						if($posVal == false){
							$this->Session->setFlash("Card number does not match Library record");
						}						
						$this->redirect(array('controller' => 'users', 'action' => 'idlogin'));
					}
				}         
			}
		}
	}


   /*
    Function Name : ildlogin
    Desc : For patron ildlogin(Innovative Var with Name) login method
   */
   
   function ildlogin(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
					}
				}
				else {
					$wrongReferral = 1;
				}	
			}
		}
		$this->layout = 'login';
		if(isset($_POST['lang'])){
			$language = $_POST['lang'];
			$langDetail = $this->Language->find('first', array('conditions' => array('id' => $language)));
			$this->Session->write('Config.language', $langDetail['Language']['short_name']);
		}		
		if ($this->Session->read('patron')){
			$userType = $this->Session->read('patron');
			if($userType != ''){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;     
			}
		}
		$this->set('name',"");
		$this->set('card',"");
		if($this->data){         
			$card = $this->data['User']['card'];
			$name = $this->data['User']['name'];
			$patronId = $card;        
			if($card == ''){            
				$this -> Session -> setFlash("Please provide card number.");
				if($name != ''){
				   $this->set('name',$name);
				}
				else{
				   $this->set('name',"");
				}            
			}
			elseif($name == ''){            
				$this -> Session -> setFlash("Please provide patron Last Name.");            
				if($card != ''){
				   $this->set('card',$card);
				}
				else{
				   $this->set('card',"");
				}            
			}
			else{
				$cardNo = substr($card,0,5);
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				if($this->Session->read('referral')){
					$library_cond = array('id' => $this->Session->read('lId'));
					$existingLibraries = $this->Library->find('all',array(
															'conditions' => array('library_status' => 'active','library_authentication_method' => 'innovative_var_name',$library_cond),
															'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
															)
													 );
				} 
				else {
					$library_cond = '';
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'innovative_var_name',$library_cond),
														'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
														)
													 );
				}	
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "http://".$_SERVER['HTTP_HOST']."/users/ildlogin"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
				   $this->redirect(array('controller' => 'users', 'action' => 'ildlogin'));
				}        
				else{
					$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];               
					$data['url'] = $authUrl."/PATRONAPI/".$card."/dump";
					$authUrl = configure::read('App.dataHandlerUrl');
					$result = $this->AuthRequest->getAuthResponse($data,$authUrl);					
					$dom= new DOMDocument();
					@$dom->loadHtml($result);
					$xpath = new DOMXPath($dom);
					$body = $xpath->query('/html/body');
					$retStr = $dom->saveXml($body->item(0));               
					$retMsgArr = explode("PATRN NAME[pn]=",$retStr);               
					$pos = strpos($retMsgArr['1'],"<br/>");
					$retStatus = substr($retMsgArr['1'],0,$pos-1);
					$statusVal = stripos($retStatus,$name);
					if($statusVal == '' && $retStatus == ''){
						$errMsgArr =  explode("ERRNUM=",$retMsgArr['0']);
						@$errMsgCount = substr($errMsgArr['1'],0,1);
						if($errMsgCount == '1'){
							$this -> Session -> setFlash("Requested record not found.");
							$this->redirect(array('controller' => 'users', 'action' => 'ildlogin'));
						}
						else{
							$this -> Session -> setFlash("Authentication server down.");
							$this->redirect(array('controller' => 'users', 'action' => 'ildlogin'));
						}                  
					}
					elseif(!($statusVal === false)){
						$this->Variable->recursive = -1;
						$allVariables = $this->Variable->find('all',array(
												'conditions' => array('library_id' => $existingLibraries['0']['Library']['id']),
												'fields' => array('authentication_variable','authentication_response','comparison_operator','error_msg',)
											)
										);
						$status = 1;
						foreach($allVariables as $k=>$v){
							$response = explode(",",$v['Variable']['authentication_response']);
							$retStatusArr = explode($v['Variable']['authentication_variable'],$retStr);
							$pos = strpos($retStatusArr['1'],"<br/>");
							$retStatus = substr($retStatusArr['1'],1,$pos-1);
							if($retStatus == ''){
								$status = '';
							}
							elseif($v['Variable']['comparison_operator'] == '='){
								$check = strpos($v['Variable']['authentication_response'],$retStatus);
								if(!($check === false)){
									$status = 1;
								}
								else{
									$status = 'error';
								}
							}
							elseif($v['Variable']['comparison_operator'] == '<'){
								foreach($response as $key => $val){
									$res = explode("$",$retStatus);
									if(isset($res[1])){
										$cmp = $res[1];
									} 
									else {
										$cmp = $res[0];
									}							
									if($cmp < $val){
										$status = 1;
										break;
									}else{
										$status = false;
									}
								}
							}
							elseif($v['Variable']['comparison_operator'] == '>'){
								foreach($response as $key => $val){
									$res = explode("$",$retStatus);
									if(isset($res[1])){
										$cmp = $res[1];
									} 
									else {
										$cmp = $res[0];
									}							
									if($cmp > $val){
										$status = 1;
										break;
									}else{
										$status = false;
									}
								}
							}
							elseif($v['Variable']['comparison_operator'] == '<>'){
								foreach($response as $key => $val){
									$res = explode("$",$retStatus);
									if(isset($res[1])){
										$cmp = $res[1];
									} 
									else {
										$cmp = $res[0];
									}							
									if($cmp != $val){
										$status = 1;
										break;
									}else{
										$status = false;
									}
								}
							}
							elseif($v['Variable']['comparison_operator'] == 'contains'){
								$res = explode("$",$retStatus);
								if(isset($res[1])){
									$cmp = $res[1];
								} 
								else {
									$cmp = $res[0];
								}
								$check = strpos($cmp,$v['Variable']['authentication_response']);
								if(!($check === false)){
									$status = 1;
								}
								else{
									$status = 'error';
								}
							}							
							elseif($v['Variable']['comparison_operator'] == 'date'){
								$res = explode("$",$retStatus);
								if(isset($res[1])){
									$cmp = $res[1];
								} 
								else {
									$cmp = $res[0];
								}							
								$resDateArr = explode("-",$cmp);
								$resDate = mktime(0,0,0,$resDateArr[0],$resDateArr[1],$resDateArr[2]);
								$libDate = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
								if($resDate > $libDate){
									$status = 1;
								}
								else{
									$status = 'error';
								}
							}							   
							else{
								$status = 'error';
							}
							if(!$status || $status == 'error'){
								$msg = $v['Variable']['error_msg'];
								$status = 'error';
								break;
							}
						}						
						if($status == ''){
							$errMsgArr =  explode("ERRNUM=",$retStr);
							@$errMsgCount = substr($errMsgArr['1'],0,1);
							if($errMsgCount == '1'){
								$this -> Session -> setFlash("Requested record not found.");
								$this->redirect(array('controller' => 'users', 'action' => 'ildlogin'));
							}
							else{
								$this -> Session -> setFlash("Authentication server down.");
								$this->redirect(array('controller' => 'users', 'action' => 'ildlogin'));
							}                  
						}
						elseif($status == 1){
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
							$this->Session->write("innovative_var_name","innovative_var_name");
							if($existingLibraries['0']['Library']['library_logout_url'] != '' && $this->Session->read('referral') != ''){
								$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
							}
							if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
								$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
							}
							$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
							$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);						
							$this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
							$this->Download->recursive = -1;
							$results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
							$this ->Session->write("downloadsUsed", $results);
							if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
								$this ->Session->write("block", 'yes');
							}
							else{
								$this ->Session->write("block", 'no');
							}
							$this->redirect(array('controller' => 'homes', 'action' => 'index'));
						}
						else{
							$this -> Session -> setFlash($msg);
							$this->redirect(array('controller' => 'users', 'action' => 'ildlogin'));
						}
					} else {
						$this -> Session -> setFlash("Last Name does not match Library Card.");
						$this->redirect(array('controller' => 'users', 'action' => 'ildlogin'));
					}
				}         
			}
		}
	}
	
   /*
    Function Name : inlogin
    Desc : For patron inlogin(Innovative w/o PIN) login method
   */
   
	function inlogin(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
					}
				}
				else {
					$wrongReferral = 1;
				}	
			}
		}
		$this->layout = 'login';     
		if(isset($_POST['lang'])){
			$language = $_POST['lang'];
			$langDetail = $this->Language->find('first', array('conditions' => array('id' => $language)));
			$this->Session->write('Config.language', $langDetail['Language']['short_name']);
		}
		if ($this->Session->read('patron')){
			$userType = $this->Session->read('patron');
			if($userType != ''){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;     
			}
		}     
		$this->set('card',"");      
		if($this->data){         
			$card = $this->data['User']['card'];         
			$patronId = $card;        
			if($card == ''){            
				$this -> Session -> setFlash("Please provide card number.");            
			}         
			else{
				$cardNo = substr($card,0,5);
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				if($this->Session->read('referral')){
					$library_cond = array('id' => $this->Session->read('lId'));
					$existingLibraries = $this->Library->find('all',array(
												'conditions' => array('library_status' => 'active','library_authentication_method' => 'innovative_wo_pin',$library_cond),
												'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
												)
											 );            

				} 
				else {
					$library_cond = '';
					$existingLibraries = $this->Library->find('all',array(
												'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'innovative_wo_pin',$library_cond),
												'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
												)
											 );            
				}				
				
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "http://".$_SERVER['HTTP_HOST']."/users/inlogin"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
					$this->redirect(array('controller' => 'users', 'action' => 'inlogin'));
				}        
				else{
					$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];               
					$data['url'] = $authUrl."/PATRONAPI/".$card."/dump";
					$authUrl = configure::read('App.dataHandlerUrl');
					$result = $this->AuthRequest->getAuthResponse($data,$authUrl);
					$dom= new DOMDocument();
					@$dom->loadHtml($result);
					$xpath = new DOMXPath($dom);
					$body = $xpath->query('/html/body');
					$retStr = $dom->saveXml($body->item(0));
					$retCardArr = explode("P BARCODE[pb]",$retStr);
					$retPos = strpos($retCardArr['1'],"<br/>");
					$retCard = substr($retCardArr['1'],1,$retPos-1);
					$pos = strpos($retStr, "CREATED");
					if(strpos($retStr,"P BARCODE[pb]")){
						$retCardArr = explode("P BARCODE[pb]",$retStr);
						foreach($retCardArr as $k=>$v){
						$retPos = strpos($v,"<br/>");
						$retCard = substr($v,1,$retPos-1);
						$retCard = str_replace(" ","",$retCard);
						if(strpos($retStr,$card)){
							$posVal = true;
							break;
						} else {
							if(strcmp($card,$retCard) == 0){
								$posVal = true;
								break;		
							} else {
								$posVal = false;
								
							}
						}						
						}
					} else {
						if(strpos($retStr, "ERRMSG=")){
							$posVal = false;
						} else {
							$posVal = true;
						}		
					}					
					if ($posVal == false) {                 
						$retMsgArr = explode("ERRNUM=",$retStr);               
						if(count($retMsgArr) > 1){                    
							@$retStatus = $retMsgArr['1'];
							$retMsgArr = explode("<BR>",$retStatus);               
							if($retMsgArr[0] == 1){                         
								$this->set('card',$card); 
								$this -> Session -> setFlash("Requested record not found.");
								$this->redirect(array('controller' => 'users', 'action' => 'inlogin'));
							}   
						}
						else{                     
							$this -> Session -> setFlash("Authentication server down.");
							if($posVal == false){
								$this->Session->setFlash("Card number does not match Library record.");
							} else {
								$this->Session->setFlash("Authentication server down.");
							}
							$this->redirect(array('controller' => 'users', 'action' => 'inlogin'));   
						}
					}
					else{
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
						$this->Session->write("innovative_wo_pin","innovative_wo_pin");
						if($existingLibraries['0']['Library']['library_logout_url'] != '' && $this->Session->read('referral') != ''){
							$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
						}
						if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
							$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
						}
						$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
						$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
						$this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
						$this->Download->recursive = -1;
						$results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
						$this ->Session->write("downloadsUsed", $results);
						if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
							$this ->Session->write("block", 'yes');
						}
						else{
							$this ->Session->write("block", 'no');
						}
						$this->redirect(array('controller' => 'homes', 'action' => 'index'));
					}
				}
			}         
		}
	}

   /*
    Function Name : indlogin
    Desc : For patron indlogin(Innovative Var w/o Pin) login method
   */
   
   function indlogin(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
					}
				}
				else {
					$wrongReferral = 1;
				}	
			}
		}
		$this->layout = 'login';
		if(isset($_POST['lang'])){
			$language = $_POST['lang'];
			$langDetail = $this->Language->find('first', array('conditions' => array('id' => $language)));
			$this->Session->write('Config.language', $langDetail['Language']['short_name']);
		}		
		if ($this->Session->read('patron')){
			$userType = $this->Session->read('patron');
			if($userType != ''){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;     
			}
		} 
		$this->set('card',"");
		if($this->data){         
			$card = $this->data['User']['card'];
			$patronId = $card;
			if($card == ''){            
				$this -> Session -> setFlash("Please provide card number.");
			}
			else{				
				$cardNo = substr($card,0,5);
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				if($this->Session->read('referral')){
					$library_cond = array('id' => $this->Session->read('lId'));
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_status' => 'active','library_authentication_method' => 'innovative_var_wo_pin',$library_cond),
														'fields' => array('Library.id','Library.library_territory','Library.library_logout_url','Library.library_authentication_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
														)
													 );					
				} else {
					$library_cond = '';
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'innovative_var_wo_pin',$library_cond),
														'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
														)
													 );					
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "http://".$_SERVER['HTTP_HOST']."/users/indlogin"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
					$this->redirect(array('controller' => 'users', 'action' => 'indlogin'));
				}        
				else{
					$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];               
					$data['url'] = $authUrl."/PATRONAPI/".$card."/dump";
					$authUrl = configure::read('App.dataHandlerUrl');
					$result = $this->AuthRequest->getAuthResponse($data,$authUrl);
					$dom= new DOMDocument();
					@$dom->loadHtml($result);
					$xpath = new DOMXPath($dom);
					$body = $xpath->query('/html/body');
					$retStr = $dom->saveXml($body->item(0));
					$retCardArr = explode("P BARCODE[pb]",$retStr);
					$retPos = strpos($retCardArr['1'],"<br/>");
					$retCard = substr($retCardArr['1'],1,$retPos-1);
					
					$errStrArr = explode('ERRMSG=',$retStr);
					
					$errMsg = @$errStrArr['1'];
					if(strpos($retStr,"P BARCODE[pb]")){
						$retCardArr = explode("P BARCODE[pb]",$retStr);
						foreach($retCardArr as $k=>$v){
						$retPos = strpos($v,"<br/>");
						$retCard = substr($v,1,$retPos-1);
						$retCard = str_replace(" ","",$retCard);
						if(strpos($retStr,$card)){
							$posVal = true;
							break;
						} else {
							if(strcmp($card,$retCard) == 0){
								$posVal = true;
								break;		
							} else {
								$posVal = false;
								
							}
						}						
						}
					} else {
						if(strpos($retStr, "ERRMSG=")){
							$posVal = false;
						} else {
							$posVal = true;
						}		
					}					
					$this->Variable->recursive = -1;
					$allVariables = $this->Variable->find('all',array(
														'conditions' => array('library_id' => $existingLibraries['0']['Library']['id']),
														'fields' => array('authentication_variable','authentication_response','comparison_operator','error_msg',)
														)
													 );
					$status = 1;
					foreach($allVariables as $k=>$v){
						$response = explode(",",$v['Variable']['authentication_response']);
						$retStatusArr = explode($v['Variable']['authentication_variable'],$retStr);
						$pos = strpos($retStatusArr['1'],"<br/>");
						$retStatus = substr($retStatusArr['1'],1,$pos-1);
						if($retStatus == ''){
							$status = '';
						}
						elseif($v['Variable']['comparison_operator'] == '='){
							$check = strpos($v['Variable']['authentication_response'],$retStatus);
							if(!($check === false)){
								$status = 1;
							}
							else{
								$status = 'error';
							}
						}
						elseif($v['Variable']['comparison_operator'] == '<'){
							foreach($response as $key => $val){
								$res = explode("$",$retStatus);
								if(isset($res[1])){
									$cmp = $res[1];
								} 
								else {
									$cmp = $res[0];
								}							
								if($cmp < $val){
									$status = 1;
									break;
								}else{
									$status = false;
								}
							}
						}
						elseif($v['Variable']['comparison_operator'] == '>'){
							foreach($response as $key => $val){
								$res = explode("$",$retStatus);
								if(isset($res[1])){
									$cmp = $res[1];
								} 
								else {
									$cmp = $res[0];
								}							
								if($cmp > $val){
									$status = 1;
									break;
								}else{
									$status = false;
								}
							}
						}
						elseif($v['Variable']['comparison_operator'] == '<>'){
							foreach($response as $key => $val){
								$res = explode("$",$retStatus);
								if(isset($res[1])){
									$cmp = $res[1];
								} 
								else {
									$cmp = $res[0];
								}							
								if($cmp != $val){
									$status = 1;
									break;
								}else{
									$status = false;
								}
							}
						}
						elseif($v['Variable']['comparison_operator'] == 'contains'){
							$res = explode("$",$retStatus);
							if(isset($res[1])){
								$cmp = $res[1];
							} 
							else {
								$cmp = $res[0];
							}
							$check = strpos($cmp,$v['Variable']['authentication_response']);
							if(!($check === false)){
								$status = 1;
							}
							else{
								$status = 'error';
							}
						}							
						elseif($v['Variable']['comparison_operator'] == 'date'){
							$res = explode("$",$retStatus);
							if(isset($res[1])){
								$cmp = $res[1];
							} 
							else {
								$cmp = $res[0];
							}							
							$resDateArr = explode("-",$cmp);
							$resDate = mktime(0,0,0,$resDateArr[0],$resDateArr[1],$resDateArr[2]);
							$libDate = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
							if($resDate > $libDate){
								$status = 1;
							}
							else{
								$status = 'error';
							}
						}							   
						else{
							$status = 'error';
						}
						if(!$status || $status == 'error'){
							$msg = $v['Variable']['error_msg'];
							$status = 'error';
							break;
						}
					}						
					if($status == ''){
						$errMsgArr =  explode("ERRNUM=",$retStr);
						@$errMsgCount = substr($errMsgArr['1'],0,1);
						if($errMsgCount == '1'){
							$this -> Session -> setFlash("Requested record not found.");
							$this->redirect(array('controller' => 'users', 'action' => 'indlogin'));
						}
						else{
							$this->Session->setFlash("Authentication server down.");
							$this->redirect(array('controller' => 'users', 'action' => 'indlogin'));
						}                  
					}
					elseif($status == 1 && $posVal != false){
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
						$this->Session->write("innovative_var_wo_pin","innovative_var_wo_pin");
						if($existingLibraries['0']['Library']['library_logout_url'] != '' && $this->Session->read('referral') != ''){
							$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
						}
						if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
							$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
						}
						$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
						$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
						$this->Download->recursive = -1;
						$this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
						$results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
						$this ->Session->write("downloadsUsed", $results);
						if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
							$this ->Session->write("block", 'yes');
						}
						else{
							$this ->Session->write("block", 'no');
						}
						$this->redirect(array('controller' => 'homes', 'action' => 'index'));
					}
					else{
						$errStrArr = explode('ERRMSG=',$retStr);
						$errMsg = $errStrArr['1'];
						if(isset($errMsg)){
							$this->Session->setFlash($errMsg);
						} elseif(isset($msg)){
							$this->Session->setFlash($msg);
						} elseif($posVal == false){
								$this->Session->setFlash("Card number does not match Library record.");
						} else {
							$this->Session->setFlash("Authentication server down.");
						}
						$this->redirect(array('controller' => 'users', 'action' => 'indlogin'));
					}
				}         
			}
		}
	}	
	
	/*
		Function Name : slogin
		Desc : For patron slogin(SIP2 Authentication) login method
	*/ 
	   
	   
	function slogin(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
					}
				}
				else {
					$wrongReferral = 1;
				}	
			}
		}
		$this->layout = 'login';
		if(isset($_POST['lang'])){
			$language = $_POST['lang'];
			$langDetail = $this->Language->find('first', array('conditions' => array('id' => $language)));
			$this->Session->write('Config.language', $langDetail['Language']['short_name']);
		}		
		if ($this->Session->read('patron')){
			$userType = $this->Session->read('patron');
			if($userType != ''){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;     
			}
		}	            
		if($this->data){  
			$card = $this->data['User']['card'];
			$pin = $this->data['User']['pin'];
			$patronId = $card;        
			if($card == ''){            
				$this -> Session -> setFlash("Please provide card number.");            
		
				if($pin != ''){
				   $this->set('pin',$pin);
				}
				else{
				   $this->set('pin',"");
				}            
			}
			elseif($pin == ''){            
				$this -> Session -> setFlash("Please provide pin.");            
				if($card != ''){
				   $this->set('card',$card);
				}
				else{
				   $this->set('card',"");
				}            
			}
			else{
				$cardNo = substr($card,0,5);
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				if($this->Session->read('referral')){
					$library_cond = array('id' => $this->Session->read('lId'));
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_status' => 'active','library_authentication_method' => 'sip2',$library_cond),
														'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
														)
													 );					
				} else {
					$library_cond = '';
					$existingLibraries = $this->Library->find('all',array(
													'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'sip2',$library_cond),
													'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
													)
												 );				
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "http://".$_SERVER['HTTP_HOST']."/users/slogin"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
					$this->redirect(array('controller' => 'users', 'action' => 'slogin'));
				}        
				else{
						$data['hostname'] = $existingLibraries['0']['Library']['library_host_name'];
						$data['port'] = $existingLibraries['0']['Library']['library_port_no'];
						$data['sip_login'] = $existingLibraries['0']['Library']['library_sip_login'];
						$data['sip_password'] = $existingLibraries['0']['Library']['library_sip_password'];
						$data['sip_location'] = $existingLibraries['0']['Library']['library_sip_location'];
						$authUrl = configure::read('App.sipDataHandlerUrl');
						$mysip = new $this->sip2;
						
						if(!empty($mysip->sip_login)){
							$data['php']= '\$result = $mysip->msgLogin($mysip->sip_login,$mysip->sip_password,$mysip->sip_location);';
							
							$sc_login = $this->AuthRequest->getAuthResponse($data,$authUrl);
							$mysip->parseLoginResponse($mysip->get_message($sc_login));
						}
						
						//send selfcheck status message
						$data['php']='\$result = $mysip->msgSCStatus();';
						$in = $this->AuthRequest->getAuthResponse($data,$authUrl);
						
						$data['php']='\$result = $mysip->get_message("'.$in.'");';
						$msg_result = $this->AuthRequest->getAuthResponse($data,$authUrl);
						//echo $msg_result;
						
						// Make sure the response is 98 as expected
						if (preg_match("/^98/", $msg_result)) {
						  $result = $mysip->parseACSStatusResponse($msg_result);
						  //  Use result to populate SIP2 setings
						  $mysip->AO = $result['variable']['AO'][0]; /* set AO to value returned */
						  $data['AO'] = $result['variable']['AO'][0];
						  $mysip->AN = $result['variable']['AN'][0]; /* set AN to value returned */
						  $data['AN'] = $result['variable']['AN'][0];
						  $mysip->patron = $card;
						  $data['patron'] = $card;
						  $mysip->patronpwd = $pin;
						  $data['patronpwd'] = $pin;
						  $data['php']='\$result = $mysip->msgPatronStatusRequest();';
						  $in = $this->AuthRequest->getAuthResponse($data,$authUrl);
						  $data['php']='\$result = $mysip->get_message("'.$in.'");';
						  $msg_result = $this->AuthRequest->getAuthResponse($data,$authUrl);
						  
						  // Make sure the response is 24 as expected
						  if (preg_match("/^24/", $msg_result)) {
							  $result = $mysip->parsePatronStatusResponse( $msg_result );
								if ($result['variable']['BL'][0] == 'Y') {
								  // Successful Card!!!

								 if ($result['variable']['CQ'][0] == 'Y') {
									// Successful PIN !!!
									
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
										$this->Session->write("sip2","sip2");
										if($existingLibraries['0']['Library']['library_logout_url'] != '' && $this->Session->read('referral') != ''){
											$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
										}
										if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
											$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
										}
										$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
										$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
										$this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
										$this->Download->recursive = -1;
										$results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
										$this ->Session->write("downloadsUsed", $results);
										if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
										  $this ->Session->write("block", 'yes');
										}
										else{
										  $this ->Session->write("block", 'no');
										}
										$this->redirect(array('controller' => 'homes', 'action' => 'index'));
								} 
								else {
									$this->Session->setFlash("The PIN is Invalid.");
									$this->redirect(array('controller' => 'users', 'action' => 'slogin'));
								}
							}
							else{
								  $this -> Session -> setFlash("The Card Number is Invalid.");                              
								  $this->redirect(array('controller' => 'users', 'action' => 'slogin'));
							}								
						}
						else{
							  $this -> Session -> setFlash("Authentication server down.");                              
							  $this->redirect(array('controller' => 'users', 'action' => 'slogin'));
						}
					}
					else{
						  $this -> Session -> setFlash("Authentication server down.");                              
						  $this->redirect(array('controller' => 'users', 'action' => 'slogin'));
					}
				}
			}
		}
	}

	/*
		Function Name : snlogin
		Desc : For patron snlogin(SIP2 Authentication) login method without the pin no
	*/ 
	   
/*	   
function snlogin(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
					}
				}
				else {
					$wrongReferral = 1;
				}	
			}
		}
		$this->layout = 'login';
		if(isset($_POST['lang'])){
			$language = $_POST['lang'];
			$langDetail = $this->Language->find('first', array('conditions' => array('id' => $language)));
			$this->Session->write('Config.language', $langDetail['Language']['short_name']);
		}	
		if ($this->Session->read('patron')){
			$userType = $this->Session->read('patron');
			if($userType != ''){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;     
			}
		}	            
		if($this->data){  
			$card = $this->data['User']['card'];
			$patronId = $card;        
			if($card == ''){            
				$this -> Session -> setFlash("Please provide card number.");            
			}
			else{
				$cardNo = substr($card,0,5);
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				if($this->Session->read('referral')){
					$library_cond = array('id' => $this->Session->read('lId'));
					$existingLibraries = $this->Library->find('all',array(
													'conditions' => array('library_status' => 'active','library_authentication_method' => 'sip2_wo_pin',$library_cond),
													'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
													)
												 );					
				} else {
					$library_cond = '';
					$existingLibraries = $this->Library->find('all',array(
													'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'sip2_wo_pin',$library_cond),
													'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
													)
												 );				
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "http://".$_SERVER['HTTP_HOST']."/users/snlogin"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
					$this->redirect(array('controller' => 'users', 'action' => 'snlogin'));
				}        
				else{
						//Start
						$data['hostname'] = $existingLibraries['0']['Library']['library_host_name'];
						$data['port'] = $existingLibraries['0']['Library']['library_port_no'];
						$data['sip_login'] = $existingLibraries['0']['Library']['library_sip_login'];
						$data['sip_password'] = $existingLibraries['0']['Library']['library_sip_password'];
						$data['sip_location'] = $existingLibraries['0']['Library']['library_sip_location'];
						$authUrl = configure::read('App.sipDataHandlerUrl');
						$mysip = new $this->sip2;
							
							if(!empty($mysip->sip_login)){
								$data['php']= '\$result = $mysip->msgLogin($mysip->sip_login,$mysip->sip_password,$mysip->sip_location);';
								$sc_login = $this->AuthRequest->getAuthResponse($data,$authUrl);
								$mysip->parseLoginResponse($mysip->get_message($sc_login));
							}
							
							//send selfcheck status message
							$data['php']='\$result = $mysip->msgSCStatus();';
							$in = $this->AuthRequest->getAuthResponse($data,$authUrl);
							
							
							$data['php']='\$result = $mysip->get_message("'.$in.'");';
							$msg_result = $this->AuthRequest->getAuthResponse($data,$authUrl);
							// Make sure the response is 98 as expected
							if (preg_match("/^98/", $msg_result)) {
							
									
								   $result = $mysip->parseACSStatusResponse($msg_result);
								  //  Use result to populate SIP2 setings
								  $mysip->AO = $result['variable']['AO'][0]; /* set AO to value returned */
//								  $data['AO'] = $result['variable']['AO'][0];
//								  $mysip->AN = @$result['variable']['AN'][0]; /* set AN to value returned */
/*								  $data['AN'] = @$result['variable']['AN'][0];
								  $mysip->patron = $card;
								  $data['patron'] = $card;
								  $mysip->patronpwd = '';
								  $data['patronpwd'] = '';
								  $data['php']='\$result = $mysip->msgPatronStatusRequest();';
								  $in = $this->AuthRequest->getAuthResponse($data,$authUrl);
								  $data['php']='\$result = $mysip->get_message("'.$in.'");';
								  $msg_result = $this->AuthRequest->getAuthResponse($data,$authUrl);
								  // Make sure the response is 24 as expected
								  if(preg_match("/^24/", $msg_result)) {
									  $result = $mysip->parsePatronStatusResponse( $msg_result );
									
									  if(($result['variable']['BL'][0] == 'Y')){
										  // Success!!!
										  
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
										  $this->Session->write("sip","sip");
										  if($existingLibraries['0']['Library']['library_logout_url'] != '' && $this->Session->read('referral') != ''){
											  $this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
										  }
										  if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
											  $this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
										  }
										  $isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
										  $this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
										  $this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
										  $this->Download->recursive = -1;
										  $results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
										  $this ->Session->write("downloadsUsed", $results);
										  if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
											  $this ->Session->write("block", 'yes');
										  }
										  else{
											  $this ->Session->write("block", 'no');
										  }
										  $this->redirect(array('controller' => 'homes', 'action' => 'index'));
									}
									else{
										  $this -> Session -> setFlash("The Card Number is Invalid.");                              
										  $this->redirect(array('controller' => 'users', 'action' => 'snlogin'));
									}							
								}
								else{
									  $this -> Session -> setFlash("Authentication server down.");                              
									  $this->redirect(array('controller' => 'users', 'action' => 'snlogin'));
							}
						}
						else{
							  $this -> Session -> setFlash("Authentication server down.");                              
							  $this->redirect(array('controller' => 'users', 'action' => 'snlogin'));
						}
					
				}
			}
		}
	}*/
	
	
	function snlogin(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
					}
				}
				else {
					$wrongReferral = 1;
					$data['wrongReferral'] =$wrongReferral;
				}	
			}
		}
		$this->layout = 'login';
		if(isset($_POST['lang'])){
			$language = $_POST['lang'];
			$langDetail = $this->Language->find('first', array('conditions' => array('id' => $language)));
			$this->Session->write('Config.language', $langDetail['Language']['short_name']);
		}	
		if ($this->Session->read('patron')){
			$userType = $this->Session->read('patron');
			if($userType != ''){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;     
			}
		}	            
		if($this->data){  
			$card = $this->data['User']['card'];
			$data['card'] = $card;
			$patronId = $card;    
			$data['patronId'] = $patronId;
			if($card == ''){            
				$this -> Session -> setFlash("Please provide card number.");            
			}
			else{
				$cardNo = substr($card,0,5);
				$data['cardNo'] = $cardNo;
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				$data['referral'] = @$this->Session->read('referral');
				if($this->Session->read('referral')){
					$library_cond = array('id' => $this->Session->read('lId'));
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
													'conditions' => array('library_status' => 'active','library_authentication_method' => 'sip2_wo_pin',$library_cond),
													'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
													)
												 );					
				} else {
					$library_cond = '';
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
													'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'sip2_wo_pin',$library_cond),
													'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
													)
												 );				
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "http://".$_SERVER['HTTP_HOST']."/users/snlogin"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
					$this->redirect(array('controller' => 'users', 'action' => 'snlogin'));
				}        
				else{	
						$authUrl = "https://auth.libraryideas.com/snlogin_validation";
						$result = $this->AuthRequest->getAuthResponse($data,$authUrl);
						echo $result;echo 'hi';exit;
						$resultAnalysis = explode("|",$result);
						$resultAnalysis[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $resultAnalysis[0]);
						$resultAnalysis[1] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $resultAnalysis[1]);
						if($resultAnalysis[0] == "fail"){
							$this->Session->setFlash($resultAnalysis[1]);
							$this->redirect(array('controller' => 'users', 'action' => 'snlogin'));
						}elseif($resultAnalysis[0] == "success"){
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
								  $this->Session->write("sip","sip");
								  if($existingLibraries['0']['Library']['library_logout_url'] != '' && $this->Session->read('referral') != ''){
									  $this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
								  }
								  if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
									  $this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
								  }
								  $isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
								  $this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
								  $this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
								  $this->Download->recursive = -1;
								  $results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
								  $this ->Session->write("downloadsUsed", $results);
								  if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
									  $this ->Session->write("block", 'yes');
								  }
								  else{
									  $this ->Session->write("block", 'no');
								  }
								  $this->redirect(array('controller' => 'homes', 'action' => 'index'));
							
						}
						//echo $result;exit;
						
				}
			}
		}
	}
	/*
		Function Name : sdlogin
		Desc : For patron sdlogin(SIP2 Var) login method
	*/ 	   
	   
	function sdlogin(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
					}
				}
				else {
					$wrongReferral = 1;
				}	
			}
		}
		$this->layout = 'login';
		if(isset($_POST['lang'])){
			$language = $_POST['lang'];
			$langDetail = $this->Language->find('first', array('conditions' => array('id' => $language)));
			$this->Session->write('Config.language', $langDetail['Language']['short_name']);
		}		
		if ($this->Session->read('patron')){
			$userType = $this->Session->read('patron');
			if($userType != ''){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;     
			}
		}            
		if($this->data){  
			$card = $this->data['User']['card'];
			$pin = $this->data['User']['pin'];
			$patronId = $card;        
			if($card == ''){            
				$this -> Session -> setFlash("Please provide card number.");            
		
				if($pin != ''){
				   $this->set('pin',$pin);
				}
				else{
				   $this->set('pin',"");
				}            
			}
			elseif($pin == ''){            
				$this -> Session -> setFlash("Please provide pin.");            
				if($card != ''){
				   $this->set('card',$card);
				}
				else{
				   $this->set('card',"");
				}            
			}
			else{
				$cardNo = substr($card,0,5);
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				if($this->Session->read('referral')){
					$library_cond = array('id' => $this->Session->read('lId'));
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_status' => 'active','library_authentication_method' => 'sip2_var',$library_cond),
														'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_sip_version','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
														)
													 );					
				} else {
					$library_cond = '';
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'sip2_var',$library_cond),
														'fields' => array('Library.id','Library.library_territory','Library.library_logout_url','Library.library_authentication_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_sip_version','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
														)
													 );					
				}				

				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "http://".$_SERVER['HTTP_HOST']."/users/sdlogin"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
					$this->redirect(array('controller' => 'users', 'action' => 'sdlogin'));
				}        
				else{
						//Start
						$data['hostname'] = $existingLibraries['0']['Library']['library_host_name'];
						$data['port'] = $existingLibraries['0']['Library']['library_port_no'];
						$data['sip_login'] = $existingLibraries['0']['Library']['library_sip_login'];
						$data['sip_password'] = $existingLibraries['0']['Library']['library_sip_password'];
						$data['sip_location'] = $existingLibraries['0']['Library']['library_sip_location'];
						$authUrl = configure::read('App.sipDataHandlerUrl');
						$mysip = new $this->sip2;
						
						
							if(!empty($mysip->sip_login)){
								$data['php']= '\$result = $mysip->msgLogin($mysip->sip_login,$mysip->sip_password,$mysip->sip_location);';
								
								$sc_login = $this->AuthRequest->getAuthResponse($data,$authUrl);
								$mysip->parseLoginResponse($mysip->get_message($sc_login));
							}
							
							//send selfcheck status message
							$data['php']='\$result = $mysip->msgSCStatus("","","'.$existingLibraries['0']['Library']['library_sip_version'].'");';
							$in = $this->AuthRequest->getAuthResponse($data,$authUrl);
							
							
							$data['php']='\$result = $mysip->get_message("'.$in.'");';
							$msg_result = $this->AuthRequest->getAuthResponse($data,$authUrl);
							//echo $msg_result;
							

							// Make sure the response is 98 as expected
							if (preg_match("/98/", $msg_result)) {

									
								  $parseACSStatusResponse = $mysip->parseACSStatusResponse($msg_result);

								  //  Use result to populate SIP2 setings
								  $mysip->AO = $parseACSStatusResponse['variable']['AO'][0]; /* set AO to value returned */
								  $data['AO'] = $parseACSStatusResponse['variable']['AO'][0];
								  $mysip->AN = $parseACSStatusResponse['variable']['AN'][0]; /* set AN to value returned */
								  $data['AN'] = $parseACSStatusResponse['variable']['AN'][0];
								  $mysip->patron = $card;
								  $data['patron'] = $card;
								  $mysip->patronpwd = $pin;
								  $data['patronpwd'] = $pin;
								 $data['php']='\$result = $mysip->msgPatronStatusRequest();';
								  $in = $this->AuthRequest->getAuthResponse($data,$authUrl);
								  $data['php']='\$result = $mysip->get_message("'.$in.'");';
								  $msg_result = $this->AuthRequest->getAuthResponse($data,$authUrl);
								  // Make sure the response is 24 as expected
								  if (preg_match("/24/", $msg_result)) {
									  $parsePatronStatusResponse = $mysip->parsePatronStatusResponse( $msg_result );
									  $data['php']='\$result = $mysip->msgPatronInformation("none");';
									  $in = $this->AuthRequest->getAuthResponse($data,$authUrl);
									   $data['php']='\$result = $mysip->get_message("'.$in.'");';
									   $get_result = $this->AuthRequest->getAuthResponse($data,$authUrl);
									  $parsePatronInfoResponse = $mysip->parsePatronInfoResponse($get_result);
									  if ($parsePatronStatusResponse['variable']['BL'][0] == 'Y' || $parsePatronStatusResponse['variable']['BL'][0] == 'Y') {
										  // Successful Card!!!
										
										 if ($parsePatronStatusResponse['variable']['CQ'][0] == 'Y' || $parsePatronStatusResponse['variable']['CQ'][0] == 'Y') {
											// Successful PIN !!!
										  

											$this->Variable->recursive = -1;										
											$allVariables = $this->Variable->find('all',array(
																				'conditions' => array('library_id' => $existingLibraries['0']['Library']['id']),
																				'fields' => array('authentication_variable','authentication_response','message_no','comparison_operator','error_msg','result_arr')
																				)
																			 );
											$status = 1;
											foreach($allVariables as $k=>$v){
												$response = explode(",",$v['Variable']['authentication_response']);
												if($v['Variable']['message_no'] == 24){
													$info_status = $parsePatronStatusResponse;
												} 
												elseif($v['Variable']['message_no'] == 64){
													$info_status = $parsePatronInfoResponse;
												}
												elseif($v['Variable']['message_no'] == 98){
													$info_status = $parseACSStatusResponse;
												}
												if($v['Variable']['comparison_operator'] == '='){
													if(isset($info_status['variable'][$v['Variable']['authentication_variable']][0]))
													$status = strpos($v['Variable']['authentication_response'],$info_status['variable'][$v['Variable']['authentication_variable']][0]);
												}
												elseif($v['Variable']['comparison_operator'] == '<'){
													foreach($response as $key => $val){
														$res = explode("$",$info_status['variable'][$v['Variable']['authentication_variable']][0]);
														if(isset($res[1])){
															$cmp = $res[1];
														} else {
															$cmp = $res[0];
														}
														if($cmp < $val){
															$status = 1;
															break;
														}else{
															$status = false;
														}
													}
												}
												elseif($v['Variable']['comparison_operator'] == '>'){
														foreach($response as $key => $val){
														$res = explode("$",$info_status['variable'][$v['Variable']['authentication_variable']][0]);
														if(isset($res[1])){
															$cmp = $res[1];
														} else {
															$cmp = $res[0];
														}
														if($cmp > $val){
															$status = 1;
															break;
														}else{
															$status = false;
														}
													}
												}
												elseif($v['Variable']['comparison_operator'] == 'contains'){
													$res = explode("$",$info_status[$v['Variable']['result_arr']][$v['Variable']['authentication_variable']]);
													if(isset($res[1])){
														$cmp = $res[1];
													} 
													else {
														$cmp = $res[0];
													}
													$check = strpos($cmp,$v['Variable']['authentication_response']);
													if(!($check === false)){
														$status = false;
													}
													else{
														$status = 1;
													}
												}												
												elseif($v['Variable']['comparison_operator'] == '<>'){
														foreach($response as $key => $val){
														$res = explode("$",$info_status['variable'][$v['Variable']['authentication_variable']][0]);
														if(isset($res[1])){
															$cmp = $res[1];
														} else {
															$cmp = $res[0];
														}
														if($cmp != $val){
															$status = 1;
															break;
														}else{
															$status = false;
														}
													}
												}
												elseif($v['Variable']['comparison_operator'] == 'date'){
													$res = explode("$",$info_status['variable'][$v['Variable']['authentication_variable']][0]);
													if(isset($res[1])){
														$cmp = $res[1];
													} 
													else {
														$cmp = $res[0];
													}							
													$resDateArr = explode("-",date("Y-m-d",strtotime($cmp)));
													$resDate = mktime(0,0,0,$resDateArr[1],$resDateArr[2],$resDateArr[0]);
													$libDate = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
													if($resDate > $libDate){
														$status = 1;
													}
													else{
														$status = false;
													}
												}
												if($status === false){
													$msg = $v['Variable']['error_msg'];											
												}
												if(isset($msg)){
													break;
												}
											}
											if(!($status === false)){
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
												$this->Session->write("sip2_var","sip2_var");
												if($existingLibraries['0']['Library']['library_logout_url'] != '' && $this->Session->read('referral') != ''){
													$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
												}
												if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
													$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
												}
												$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
												$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
												$this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
												$this->Download->recursive = -1;
												$results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
												$this ->Session->write("downloadsUsed", $results);
												if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
													$this ->Session->write("block", 'yes');
												}
												else{
													$this ->Session->write("block", 'no');
												}
												$this->redirect(array('controller' => 'homes', 'action' => 'index'));
											} 
											else {
												$this->Session->setFlash($msg);
												$this->redirect(array('controller' => 'users', 'action' => 'sdlogin'));
											}											  
										}
										else{
											  $this -> Session -> setFlash("The PIN is Invalid.");
											  $this->redirect(array('controller' => 'users', 'action' => 'sdlogin'));

										}
									}
									else{
										  $this -> Session -> setFlash("The Card Number is Invalid.");                              
										  $this->redirect(array('controller' => 'users', 'action' => 'sdlogin'));

									}										
								}
								else{
									  $this -> Session -> setFlash("Authentication server down.");                              
									  $this->redirect(array('controller' => 'users', 'action' => 'sdlogin'));

								}
						}
						else{
							  $this -> Session -> setFlash("Authentication server down.");                              
							  $this->redirect(array('controller' => 'users', 'action' => 'sdlogin'));

						}
					
				}
			}
		}
	}

	/*
		Function Name : sndlogin
		Desc : For patron sndlogin(SIP2 Var w/o Pin) login method
	*/ 	   
	   
	function sndlogin(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
					}
				}
				else {
					$wrongReferral = 1;
				}	
			}
		}
		$this->layout = 'login';
		if(isset($_POST['lang'])){
			$language = $_POST['lang'];
			$langDetail = $this->Language->find('first', array('conditions' => array('id' => $language)));
			$this->Session->write('Config.language', $langDetail['Language']['short_name']);
		}		
		if ($this->Session->read('patron')){
			$userType = $this->Session->read('patron');
			if($userType != ''){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;     
			}
		}            
		if($this->data){  
			$card = $this->data['User']['card'];
			$patronId = $card;        
			if($card == ''){            
				$this -> Session -> setFlash("Please provide card number.");            
			}
			else{
				$cardNo = substr($card,0,5);
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				if($this->Session->read('referral')){
					$library_cond = array('id' => $this->Session->read('lId'));
					$existingLibraries = $this->Library->find('all',array(
													'conditions' => array('library_status' => 'active','library_authentication_method' => 'sip2_var_wo_pin',$library_cond),
													'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_sip_version','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
													)
												 );				
				} else {
					$library_cond = '';
					$existingLibraries = $this->Library->find('all',array(
													'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'sip2_var_wo_pin',$library_cond),
													'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_sip_version','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
													)
												 );				
				}				

				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "http://".$_SERVER['HTTP_HOST']."/users/sndlogin"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
					$this->redirect(array('controller' => 'users', 'action' => 'sndlogin'));
				}        
				else{
					//Start
						$data['hostname'] = $existingLibraries['0']['Library']['library_host_name'];
						$data['port'] = $existingLibraries['0']['Library']['library_port_no'];
						$data['sip_login'] = $existingLibraries['0']['Library']['library_sip_login'];
						$data['sip_password'] = $existingLibraries['0']['Library']['library_sip_password'];
						$data['sip_location'] = $existingLibraries['0']['Library']['library_sip_location'];
						$authUrl = configure::read('App.sipDataHandlerUrl');
						$mysip = new $this->sip2;
						
						if(!empty($mysip->sip_login)){
								$data['php']= '\$result = $mysip->msgLogin($mysip->sip_login,$mysip->sip_password,$mysip->sip_location);';
								
								$sc_login = $this->AuthRequest->getAuthResponse($data,$authUrl);
								$mysip->parseLoginResponse($mysip->get_message($sc_login));
							}
						
						//send selfcheck status message
							$data['php']='\$result = $mysip->msgSCStatus("","","'.$existingLibraries['0']['Library']['library_sip_version'].'");';
							$in = $this->AuthRequest->getAuthResponse($data,$authUrl);
							
							
							$data['php']='\$result = $mysip->get_message("'.$in.'");';
							$msg_result = $this->AuthRequest->getAuthResponse($data,$authUrl);

						// Make sure the response is 98 as expected
						if (preg_match("/^98/", $msg_result)) {
							$parseACSStatusResponse = $mysip->parseACSStatusResponse($msg_result);

							 //  Use result to populate SIP2 setings
								  $mysip->AO = $parseACSStatusResponse['variable']['AO'][0]; /* set AO to value returned */
								  $data['AO'] = $parseACSStatusResponse['variable']['AO'][0];
								  $mysip->AN = $parseACSStatusResponse['variable']['AN'][0]; /* set AN to value returned */
								  $data['AN'] = $parseACSStatusResponse['variable']['AN'][0];
								  $mysip->patron = $card;
								  $data['patron'] = $card;
								 // $mysip->patronpwd = $pin;
								 // $data['patronpwd'] = $pin;
							 $data['php']='\$result = $mysip->msgPatronStatusRequest();';
								  $in = $this->AuthRequest->getAuthResponse($data,$authUrl);
								  $data['php']='\$result = $mysip->get_message("'.$in.'");';
								  $msg_result = $this->AuthRequest->getAuthResponse($data,$authUrl);
								  
							// Make sure the response is 24 as expected
							if (preg_match("/^24/", $msg_result)) {
								  $parsePatronStatusResponse = $mysip->parsePatronStatusResponse( $msg_result );
								  $data['php']='\$result = $mysip->msgPatronInformation("none");';
								  $in = $this->AuthRequest->getAuthResponse($data,$authUrl);
								  $data['php']='\$result = $mysip->get_message("'.$in.'");';
								  $get_result = $this->AuthRequest->getAuthResponse($data,$authUrl);
								  $parsePatronInfoResponse = $mysip->parsePatronInfoResponse($get_result);							
								if ($parsePatronStatusResponse['variable']['BL'][0] == 'Y' || $parsePatronInfoResponse['variable']['BL'][0] == 'Y') {
									  // Successful Card!!!

									$this->Variable->recursive = -1;										
									$allVariables = $this->Variable->find('all',array(
																		'conditions' => array('library_id' => $existingLibraries['0']['Library']['id']),
																		'fields' => array('authentication_variable','authentication_response','comparison_operator','message_no','error_msg','result_arr')
																		)
																	 );
									$status = 1;
									foreach($allVariables as $k=>$v){
										$response = explode(",",$v['Variable']['authentication_response']);
										if($v['Variable']['message_no'] == 24){
											$info_status = $parsePatronStatusResponse;
										} 
										elseif($v['Variable']['message_no'] == 64){
											$info_status = $parsePatronInfoResponse;
										}
										elseif($v['Variable']['message_no'] == 98){
											$info_status = $parseACSStatusResponse;
										}										
										if($v['Variable']['comparison_operator'] == '='){
											if(isset($info_status['variable'][$v['Variable']['authentication_variable']][0]))
											$status = strpos($v['Variable']['authentication_response'],$info_status['variable'][$v['Variable']['authentication_variable']][0]);
										}
										elseif($v['Variable']['comparison_operator'] == '<'){
											foreach($response as $key => $val){
												$res = explode("$",$info_status['variable'][$v['Variable']['authentication_variable']][0]);
												if(isset($res[1])){
													$cmp = $res[1];
												} else {
													$cmp = $res[0];
												}
												if($cmp < $val){
													$status = 1;
												}else{
													$status = false;
													break;
												}
											}
										}
										elseif($v['Variable']['comparison_operator'] == '>'){
												foreach($response as $key => $val){
												$res = explode("$",$info_status['variable'][$v['Variable']['authentication_variable']][0]);
												if(isset($res[1])){
													$cmp = $res[1];
												} else {
													$cmp = $res[0];
												}
												if($cmp > $val){
													$status = 1;
												}else{
													$status = false;
													break;
												}
											}
										}
										elseif($v['Variable']['comparison_operator'] == 'contains'){
											$res = explode("$",$info_status[$v['Variable']['result_arr']][$v['Variable']['authentication_variable']]);
											if(isset($res[1])){
												$cmp = $res[1];
											} 
											else {
												$cmp = $res[0];
											}
											$check = strpos($cmp,$v['Variable']['authentication_response']);
											if(!($check === false)){
												$status = false;
											}
											else{
												$status = 1;
											}
										}										
										elseif($v['Variable']['comparison_operator'] == '<>'){
												foreach($response as $key => $val){
												$res = explode("$",$info_status['variable'][$v['Variable']['authentication_variable']][0]);
												if(isset($res[1])){
													$cmp = $res[1];
												} else {
													$cmp = $res[0];
												}
												if($cmp != $val){
													$status = 1;
												}else{
													$status = false;
													break;
												}
											}
										}
										elseif($v['Variable']['comparison_operator'] == 'date'){
											$res = explode("$",$info_status['variable'][$v['Variable']['authentication_variable']][0]);
											if(isset($res[1])){
												$cmp = $res[1];
											} 
											else {
												$cmp = $res[0];
											}							
											$resDateArr = explode("-",date("Y-m-d",strtotime($cmp)));
											$resDate = mktime(0,0,0,$resDateArr[1],$resDateArr[2],$resDateArr[0]);
											$libDate = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
											if($resDate > $libDate){
												$status = 1;
											}
											else{
												$status = 'error';
											}
										}										
										if($status === false){
											$msg = $v['Variable']['error_msg'];											
										}
										if(isset($msg)){
											break;
										}
									}
									if(!($status === false)){
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
										$this->Session->write("sip2_var_wo_pin","sip2_var_wo_pin");
										if($existingLibraries['0']['Library']['library_logout_url'] != '' && $this->Session->read('referral') != ''){
											$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
										}
										if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
											$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
										}
										$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
										$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
										$this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
										$this->Download->recursive = -1;
										$results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
										$this ->Session->write("downloadsUsed", $results);
										if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
											$this ->Session->write("block", 'yes');
										}
										else{
											$this ->Session->write("block", 'no');
										}
										$this->redirect(array('controller' => 'homes', 'action' => 'index'));
									} 
									else {
										$this->Session->setFlash($msg);
										$this->redirect(array('controller' => 'users', 'action' => 'sndlogin'));
									}
								}
								else{
									  $this -> Session -> setFlash("The Card Number is Invalid.");                              
									  $this->redirect(array('controller' => 'users', 'action' => 'sndlogin'));

								}										
							}
							else{
							  $this -> Session -> setFlash("Authentication server down.");                              
							  $this->redirect(array('controller' => 'users', 'action' => 'sndlogin'));

							}
						}
						else{
							  $this -> Session -> setFlash("Authentication server down.");                              
							  $this->redirect(array('controller' => 'users', 'action' => 'sndlogin'));

						}
					
				}
			}
		}
	}
	
	/*
		Function Name : sso
		Desc : For patron sso(EZProxy) login method
	*/ 
	   
	   
	function sso(){
		if(isset($_REQUEST['libname'])){
			$libName = $_REQUEST['libname'];
			$this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');	
			$existingLibraries = $this->Library->find('all',array(
												'conditions' => array('library_ezproxy_name' => $libName,'library_status' => 'active','library_authentication_method' => 'ezproxy'),
												'fields' => array('Library.id','Library.library_territory','Library.library_ezproxy_secret','library_ezproxy_logout','Library.library_ezproxy_referral','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
												)
											 );
			$this->Session->write("ezproxy_logout",$existingLibraries['0']['Library']['library_ezproxy_logout']);
		} 
		else {
			if(!$this->Session->read('referral')){
				if(isset($_SERVER['HTTP_REFERER'])){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
					}
				}
			}
			$this->layout = 'login';
			$referral = $this->Session->read('referral');
			$ref = explode("url=",$referral);
			$this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');	
			$existingLibraries = $this->Library->find('all',array(
												'conditions' => array('library_ezproxy_referral' => $referral,'library_status' => 'active','library_authentication_method' => 'ezproxy'),
												'fields' => array('Library.id','Library.library_territory','Library.library_ezproxy_secret','Library.library_ezproxy_referral','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
												)
											 );
		}
		if(count($existingLibraries) == 0){
			$this -> Session -> setFlash("This is not a valid credential.");
			$this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
		}        
		else{	
			$EZproxySSO = new EZproxySSOComponent($existingLibraries['0']['Library']['library_ezproxy_secret'],$existingLibraries['0']['Library']['library_ezproxy_referral']);
			if (! $EZproxySSO->valid()) {
				if ($EZproxySSO->expired()) {
					echo("This URL has expired\n");
				} else {
					echo("Invalid access attempt\n");
				}
				exit();
			}
			$user = $EZproxySSO->user();
			$card = $user;
			//writing to memcache and writing to both the memcached servers
			$currentPatron = $this->Currentpatron->find('all', array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'], 'patronid' => $card)));
			if(count($currentPatron) > 0){
			// do nothing
			} else {
				$insertArr['libid'] = $existingLibraries['0']['Library']['id'];
				$insertArr['patronid'] = $card;
				$insertArr['session_id'] = session_id();
				$this->Currentpatron->save($insertArr);						
			}
			if (($currentPatron = Cache::read("login_".$existingLibraries['0']['Library']['id'].$card)) === false) {
				$date = time();
				$values = array(0 => $date, 1 => session_id());			
				Cache::write("login_".$existingLibraries['0']['Library']['id'].$card, $values);
			} else {
				$userCache = Cache::read("login_".$existingLibraries['0']['Library']['id'].$card);
				$date = time();
				$modifiedTime = $userCache[0];
				if(!($this->Session->read('patron'))){
					if(($date-$modifiedTime) > 60){
						$values = array(0 => $date, 1 => session_id());	
						Cache::write("login_".$existingLibraries['0']['Library']['id'].$card, $values);
					}
					else{
						$this->Session->destroy('user');
						$this -> Session -> setFlash("This account is already active.");                              
						$this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
					}
				} else {
					if(($date-$modifiedTime) > 60){
						$values = array(0 => $date, 1 => session_id());	
						Cache::write("login_".$existingLibraries['0']['Library']['id'].$card, $values);
					}
					else{
						$this->Session->destroy('user');
						$this -> Session -> setFlash("This account is already active.");                              
						$this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
					}		
				}
				
			}
			$this->Session->write("library", $existingLibraries['0']['Library']['id']);
			$this->Session->write("patron", $user);
			$this->Session->write("ezproxy","ezproxy");
			$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
			$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $user)));
			if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
				$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
			}
			$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
			$this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
			$this->Download->recursive = -1;
			$results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $user,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
			$this ->Session->write("downloadsUsed", $results);
			if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
				$this ->Session->write("block", 'yes');
			}
			else{
				$this ->Session->write("block", 'no');
			}
			$this->redirect(array('controller' => 'homes', 'action' => 'index'));
		}
	}
   /*
    Function Name : inhlogin
    Desc : For patron Innovative Https login method
   */
   
   function inhlogin(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
					}
				}
				else {
					$wrongReferral = 1;
				}	
			}
		}
      $this->layout = 'login';
		if(isset($_POST['lang'])){
			$language = $_POST['lang'];
			$langDetail = $this->Language->find('first', array('conditions' => array('id' => $language)));
			$this->Session->write('Config.language', $langDetail['Language']['short_name']);
		}	  
	  if ($this->Session->read('patron')){
			$userType = $this->Session->read('patron');
			if($userType != ''){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;     
			}
	  }	
      $this->set('pin',"");
      $this->set('card',"");
      if($this->data){         
         $card = $this->data['User']['card'];
         $pin = $this->data['User']['pin'];
         $patronId = $card;        
         if($card == ''){            
            $this -> Session -> setFlash("Please provide card number.");
            if($pin != ''){
               $this->set('pin',$pin);
            }
            else{
               $this->set('pin',"");
            }            
         }
         elseif($pin == ''){            
            $this -> Session -> setFlash("Please provide pin.");            
            if($card != ''){
               $this->set('card',$card);
            }
            else{
               $this->set('card',"");
            }            
         }
         else{
            $cardNo = substr($card,0,5);
            $this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');
			if($this->Session->read('referral')){
				$library_cond = array('id' => $this->Session->read('lId'));
				$existingLibraries = $this->Library->find('all',array(
													'conditions' => array('library_status' => 'active','library_authentication_method' => 'innovative_https',$library_cond),
													'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
													)
												 );				
				
			} else {
				$existingLibraries = $this->Library->find('all',array(
													'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'innovative_https'),
													'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
													)
												 );
			}		
            if(count($existingLibraries) == 0){
				if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "http://".$_SERVER['HTTP_HOST']."/users/inhlogin"){
					$this->Session->setFlash("You are not authorized to view this location.");
				}
				else{
					$this->Session->setFlash("This is not a valid credential.");
				}
                $this->redirect(array('controller' => 'users', 'action' => 'inhlogin'));
            }        
            else{
				$matches = array();
				$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];               
				$data['url'] = $authUrl."/PATRONAPI/".$card."/".$pin."/pintest";
				$authUrl = configure::read('App.curlDataHandlerUrl');
				$response = $this->AuthRequest->getAuthResponse($data,$authUrl);
				if(strpos($retStr,"P BARCODE[pb]")){
					if(strpos($response,$card)){
						$posVal = true;
					} else {
						$posVal = false;
					}
				} else {
					$posVal = true;
				}				
                $retMsgArr = explode("RETCOD=",$response);               
                @$retStatus = $retMsgArr['1'];
				if($retStatus == ''){
					$errMsgArr =  explode("ERRNUM=",$response);
					@$errMsgCount = substr($errMsgArr['1'],0,1);
					if($errMsgCount == '1'){
					 $this -> Session -> setFlash("Requested record not found.");
					 $this->redirect(array('controller' => 'users', 'action' => 'inhlogin'));
					}
					else{
					 $this -> Session -> setFlash("Authentication server down.");
					 $this->redirect(array('controller' => 'users', 'action' => 'inhlogin'));
					}                  
               }
               elseif($retStatus == 0 && $posVal != false){
					$status =1;
					$this->Variable->recursive = -1;
					$allVariables = $this->Variable->find('all',array(
														'conditions' => array('library_id' => $existingLibraries['0']['Library']['id']),
														'fields' => array('authentication_variable','authentication_response','comparison_operator','error_msg',)
														)
													 );
					if(count($allVariables) > 0){
						foreach($allVariables as $k=>$v){
							$responseData = explode(",",$v['Variable']['authentication_response']);
							$retStatusArr = explode($v['Variable']['authentication_variable'],$response);
							$pos = strpos($retStatusArr['1'],"<br/>");
							$retStatus = substr($retStatusArr['1'],1,$pos-1);
							if($retStatus == ''){
								$status = '';
							}
							elseif($v['Variable']['comparison_operator'] == '='){
								$check = strpos($v['Variable']['authentication_response'],$retStatus);
								if(!($check === false)){
									$status = 1;
								}
								else{
									$status = 'error';
								}
							}
							elseif($v['Variable']['comparison_operator'] == '<'){
								foreach($responseData as $key => $val){
									$res = explode("$",$retStatus);
									if(isset($res[1])){
										$cmp = $res[1];
									} 
									else {
										$cmp = $res[0];
									}							
									if($cmp < $val){
										$status = 1;
										break;
									}else{
										$status = false;
									}
								}
							}
							elseif($v['Variable']['comparison_operator'] == '>'){
								foreach($responseData as $key => $val){
									$res = explode("$",$retStatus);
									if(isset($res[1])){
										$cmp = $res[1];
									} 
									else {
										$cmp = $res[0];
									}							
									if($cmp > $val){
										$status = 1;
										break;
									}else{
										$status = false;
									}
								}
							}
							elseif($v['Variable']['comparison_operator'] == '<>'){
								foreach($responseData as $key => $val){
									$res = explode("$",$retStatus);
									if(isset($res[1])){
										$cmp = $res[1];
									} 
									else {
										$cmp = $res[0];
									}							
									if($cmp != $val){
										$status = 1;
										break;
									}else{
										$status = false;
									}
								}
							}
							elseif($v['Variable']['comparison_operator'] == 'contains'){
								$res = explode("$",$retStatus);
								if(isset($res[1])){
									$cmp = $res[1];
								} 
								else {
									$cmp = $res[0];
								}
								$check = strpos($cmp,$v['Variable']['authentication_response']);
								if(!($check === false)){
									$status = 1;
								}
								else{
									$status = 'error';
								}
							}							
							elseif($v['Variable']['comparison_operator'] == 'date'){
								$res = explode("$",$retStatus);
								if(isset($res[1])){
									$cmp = $res[1];
								} 
								else {
									$cmp = $res[0];
								}							
								$resDateArr = explode("-",$cmp);
								$resDate = mktime(0,0,0,$resDateArr[0],$resDateArr[1],$resDateArr[2]);
								$libDate = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
								if($resDate > $libDate){
									$status = 1;
								}
								else{
									$status = 'error';
								}
							}							   
							else{
								$status = 'error';
							}
							if(!$status || $status == 'error'){
								$msg = $v['Variable']['error_msg'];
								$status = 'error';
								break;
							}
						}
					}
					if($status == ''){
						$errMsgArr =  explode("ERRNUM=",$response);
						@$errMsgCount = substr($errMsgArr['1'],0,1);
						if($errMsgCount == '1'){
							$this -> Session -> setFlash("Requested record not found.");
							$this->redirect(array('controller' => 'users', 'action' => 'inhlogin'));
						}
						else{
							$this -> Session -> setFlash("Authentication server down.");
							$this->redirect(array('controller' => 'users', 'action' => 'inhlogin'));
						}                  
					}
					elseif($status == 1){
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
						$this->Session->write("innovative_https","innovative_https");
						if($existingLibraries['0']['Library']['library_logout_url'] != '' && $this->Session->read('referral') != ''){
							$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
						}
						if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
							$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
						}
						$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
						$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
						$this->Download->recursive = -1;
						$this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
						$results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
						$this ->Session->write("downloadsUsed", $results);
						if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
						  $this ->Session->write("block", 'yes');
						}
						else{
						  $this ->Session->write("block", 'no');
						}
						$this->redirect(array('controller' => 'homes', 'action' => 'index'));
					   }
					   else{
						  $this -> Session -> setFlash($msg);
						  $this->redirect(array('controller' => 'users', 'action' => 'inhlogin'));	
						}
					}	
					else{
					  $errStrArr = explode('ERRMSG=',$response);
					  $errMsg = $errStrArr['1'];
					  $this->Session->setFlash($errMsg);
					  if($posVal == false){
						  $this->Session->setFlash("Card number does not match Library record");
					  }
					  $this->redirect(array('controller' => 'users', 'action' => 'inhlogin'));						
					}				
				}
			}         
		}
   }
   /*
    Function Name : ihdlogin
    Desc : For patron ihdlogin(Innovative Var with HTTPS) login method
   */
   
   function ihdlogin(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
					}
				}
				else {
					$wrongReferral = 1;
				}	
			}
		}
		$this->layout = 'login';
		if(isset($_POST['lang'])){
			$language = $_POST['lang'];
			$langDetail = $this->Language->find('first', array('conditions' => array('id' => $language)));
			$this->Session->write('Config.language', $langDetail['Language']['short_name']);
		}		
		if ($this->Session->read('patron')){
			$userType = $this->Session->read('patron');
			if($userType != ''){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;     
			}
		}	
		$this->set('pin',"");
		$this->set('card',"");
		if($this->data){         
			$card = $this->data['User']['card'];
			$pin = $this->data['User']['pin'];
			$patronId = $card;        
			if($card == ''){            
				$this -> Session -> setFlash("Please provide card number.");
				if($pin != ''){
				   $this->set('pin',$pin);
				}
				else{
				   $this->set('pin',"");
				}            
			}
			elseif($pin == ''){            
				$this -> Session -> setFlash("Please provide pin.");            
				if($card != ''){
				   $this->set('card',$card);
				}
				else{
				   $this->set('card',"");
				}            
			}
			else{
				$cardNo = substr($card,0,5);
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				if($this->Session->read('referral')){
					$library_cond = array('id' => $this->Session->read('lId'));
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_status' => 'active','library_authentication_method' => 'innovative_var_https',$library_cond),
														'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
														)
													 );					
				} 
				else {
					$library_cond = '';
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'innovative_var_https',$library_cond),
														'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
														)
													 );					
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "http://".$_SERVER['HTTP_HOST']."/users/ihdlogin"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
				   $this->redirect(array('controller' => 'users', 'action' => 'ihdlogin'));
				}        
				else{
					$matches = array();
					$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];               
					$data['url'] = $authUrl."/PATRONAPI/".$card."/".$pin."/pintest";
					$authUrl = configure::read('App.curlDataHandlerUrl');
					$response = $this->AuthRequest->getAuthResponse($data,$authUrl);
					if(strpos($response,"P BARCODE[pb]")){
						if(strpos($retStr,$card)){
							$posVal = true;
						} else {
							$posVal = false;
						}
					} else {
						$posVal = true;
					}					
					$retMsgArr = explode("RETCOD=",$response);               
					@$retStatus = $retMsgArr['1']; 
					if($retStatus == ''){
						$errMsgArr =  explode("ERRNUM=",$response);
						@$errMsgCount = substr($errMsgArr['1'],0,1);
						if($errMsgCount == '1'){
						 $this -> Session -> setFlash("Requested record not found.");
						 $this->redirect(array('controller' => 'users', 'action' => 'ihdlogin'));
						}
						else{
						 $this -> Session -> setFlash("Authentication server down.");
						 $this->redirect(array('controller' => 'users', 'action' => 'ihdlogin'));
						}                  
				    } 
					elseif($retStatus == 0 && $posVal != false){
						   $this->Variable->recursive = -1;
						   $allVariables = $this->Variable->find('all',array(
							     'conditions' => array('library_id' => $existingLibraries['0']['Library']['id']),
							     'fields' => array('authentication_variable','authentication_response','comparison_operator','error_msg',)
							     )
							   );
							$status = 1;
							foreach($allVariables as $k=>$v){
								$responseData = explode(",",$v['Variable']['authentication_response']);
								$retStatusArr = explode($v['Variable']['authentication_variable'],$response);
								$pos = strpos($retStatusArr['1'],"<br/>");
								$retStatus = substr($retStatusArr['1'],1,$pos-1);
								if($retStatus == ''){
									$status = '';
								}
								elseif($v['Variable']['comparison_operator'] == '='){
									$check = strpos($v['Variable']['authentication_response'],$retStatus);
									if(!($check === false)){
										$status = 1;
									}
									else{
										$status = 'error';
									}
								}
								elseif($v['Variable']['comparison_operator'] == '<'){
									foreach($responseData as $key => $val){
										$res = explode("$",$retStatus);
										if(isset($res[1])){
											$cmp = $res[1];
										} 
										else {
											$cmp = $res[0];
										}							
										if($cmp < $val){
											$status = 1;
											break;
										}else{
											$status = false;
										}
									}
								}
								elseif($v['Variable']['comparison_operator'] == '>'){
									foreach($responseData as $key => $val){
										$res = explode("$",$retStatus);
										if(isset($res[1])){
											$cmp = $res[1];
										} 
										else {
											$cmp = $res[0];
										}							
										if($cmp > $val){
											$status = 1;
											break;
										}else{
											$status = false;
										}
									}
								}
								elseif($v['Variable']['comparison_operator'] == '<>'){
									foreach($responseData as $key => $val){
										$res = explode("$",$retStatus);
										if(isset($res[1])){
											$cmp = $res[1];
										} 
										else {
											$cmp = $res[0];
										}							
										if($cmp != $val){
											$status = 1;
											break;
										}else{
											$status = false;
										}
									}
								}
								elseif($v['Variable']['comparison_operator'] == 'contains'){
									$res = explode("$",$retStatus);
									if(isset($res[1])){
										$cmp = $res[1];
									} 
									else {
										$cmp = $res[0];
									}
									$check = strpos($cmp,$v['Variable']['authentication_response']);
									if(!($check === false)){
										$status = 1;
									}
									else{
										$status = 'error';
									}
								}							
								elseif($v['Variable']['comparison_operator'] == 'date'){
									$res = explode("$",$retStatus);
									if(isset($res[1])){
										$cmp = $res[1];
									} 
									else {
										$cmp = $res[0];
									}							
									$resDateArr = explode("-",$cmp);
									$resDate = mktime(0,0,0,$resDateArr[0],$resDateArr[1],$resDateArr[2]);
									$libDate = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
									if($resDate > $libDate){
										$status = 1;
									}
									else{
										$status = 'error';
									}
								}							   
								else{
									$status = 'error';
								}
								if(!$status || $status == 'error'){
									$msg = $v['Variable']['error_msg'];
									$status = 'error';
									break;
								}
							}
						   if($status == ''){
							   $errMsgArr =  explode("ERRNUM=",$response);
							   @$errMsgCount = substr($errMsgArr['1'],0,1);
							   if($errMsgCount == '1'){
								   $this -> Session -> setFlash("Requested record not found.");
								   $this->redirect(array('controller' => 'users', 'action' => 'ihdlogin'));
							   }
							   else{
								   $this -> Session -> setFlash("Authentication server down.");
								   $this->redirect(array('controller' => 'users', 'action' => 'ihdlogin'));
							   }                  
						   }
						   elseif($status == 1){
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
							   $this->Session->write("innovative_var_https","innovative_var_https");
							   if($existingLibraries['0']['Library']['library_logout_url'] != '' && $this->Session->read('referral') != ''){
									$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
							   }
								if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
									$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
								}
							   $isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
							   $this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
							   $this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
							   $this->Download->recursive = -1;
							   $results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
							   $this ->Session->write("downloadsUsed", $results);
							   if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
								   $this ->Session->write("block", 'yes');
							   }
							   else{
								   $this ->Session->write("block", 'no');
							   }
							   $this->redirect(array('controller' => 'homes', 'action' => 'index'));
						   }
						   else{
							   $this -> Session -> setFlash($msg);
							   $this->redirect(array('controller' => 'users', 'action' => 'ihdlogin'));
						   }
					} 
					else{
					  $errStrArr = explode('ERRMSG=',$response);
					  $errMsg = $errStrArr['1'];
					  $this->Session->setFlash($errMsg);
					  if($posVal == false){
							$this->Session->setFlash("Card number does not match Library record");
					  }
					  $this->redirect(array('controller' => 'users', 'action' => 'ihdlogin'));					
					}
					
				}         
			}
		}
	}
   /*
    Function Name : inhdlogin
    Desc : For patron inhdlogin(Innovative Var with HTTPS and without PIN) login method
   */
   
   function inhdlogin(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
					}
				}
				else {
					$wrongReferral = 1;
				}	
			}
		}
		$this->layout = 'login';
		if(isset($_POST['lang'])){
			$language = $_POST['lang'];
			$langDetail = $this->Language->find('first', array('conditions' => array('id' => $language)));
			$this->Session->write('Config.language', $langDetail['Language']['short_name']);
		}		
		if ($this->Session->read('patron')){
			$userType = $this->Session->read('patron');
			if($userType != ''){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;     
			}
		}	
		$this->set('pin',"");
		$this->set('card',"");
		if($this->data){         
			$card = $this->data['User']['card'];
			$patronId = $card;        
			if($card == ''){            
				$this -> Session -> setFlash("Please provide card number.");          
			}
			else{
				$cardNo = substr($card,0,5);
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				if($this->Session->read('referral')){
					$library_cond = array('id' => $this->Session->read('lId'));
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_status' => 'active','library_authentication_method' => 'innovative_var_https_wo_pin',$library_cond),
														'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
														)
													 );					
				} 
				else {
					$library_cond = '';
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'innovative_var_https_wo_pin',$library_cond),
														'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
														)
													 );					
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "http://".$_SERVER['HTTP_HOST']."/users/inhdlogin"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
				   $this->redirect(array('controller' => 'users', 'action' => 'inhdlogin'));
				}        
				else{
					$matches = array();
					$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];               
					$data['url'] = $authUrl."/PATRONAPI/".$card."/dump";
					$authUrl = configure::read('App.curlDataHandlerUrl');
					$response = $this->AuthRequest->getAuthResponse($data,$authUrl);
					if(strpos($response,"P BARCODE[pb]")){
						$retCardArr = explode("P BARCODE[pb]",$response);
						foreach($retCardArr as $k=>$v){
						$retPos = strpos($v,"<br/>");
						$retCard = substr($v,1,$retPos-1);
						$retCard = str_replace(" ","",$retCard);
						if(strpos($response,$card)){
							$posVal = true;
							break;
						} else {
							if(strcmp($card,$retCard) == 0){
								$posVal = true;
								break;		
							} else {
								$posVal = false;
								
							}
						}						
						}
					} else {
						if(strpos($response, "ERRMSG=")){
							$posVal = false;
						} else {
							$posVal = true;
						}		
					}					
					$errStrArr = explode('ERRMSG=',$response);
					$errMsg = $errStrArr['1']; 
					if($errMsg != ''){
						$errMsgArr =  explode("ERRNUM=",$response);
						@$errMsgCount = substr($errMsgArr['1'],0,1);
						if($errMsgCount == '1'){
						 $this -> Session -> setFlash("Requested record not found.");
						 $this->redirect(array('controller' => 'users', 'action' => 'inhdlogin'));
						}
						else{
						 $this -> Session -> setFlash("Authentication server down.");
						 $this->redirect(array('controller' => 'users', 'action' => 'inhdlogin'));
						}                  
				    } 
					elseif($errMsg == ''){
						   $this->Variable->recursive = -1;
						   $allVariables = $this->Variable->find('all',array(
							     'conditions' => array('library_id' => $existingLibraries['0']['Library']['id']),
							     'fields' => array('authentication_variable','authentication_response','comparison_operator','error_msg',)
							     )
							   );
							$status = 1;
							foreach($allVariables as $k=>$v){
								$responseData = explode(",",$v['Variable']['authentication_response']);
								$retStatusArr = explode($v['Variable']['authentication_variable'],$response);
								$pos = strpos($retStatusArr['1'],"<br/>");
								$retStatus = substr($retStatusArr['1'],1,$pos-1);
								if($retStatus == ''){
									$status = '';
								}
								elseif($v['Variable']['comparison_operator'] == '='){
									$check = strpos($v['Variable']['authentication_response'],$retStatus);
									if(!($check === false)){
										$status = 1;
									}
									else{
										$status = 'error';
									}
								}
								elseif($v['Variable']['comparison_operator'] == '<'){
									foreach($responseData as $key => $val){
										$res = explode("$",$retStatus);
										if(isset($res[1])){
											$cmp = $res[1];
										} 
										else {
											$cmp = $res[0];
										}							
										if($cmp < $val){
											$status = 1;
											break;
										}else{
											$status = false;
										}
									}
								}
								elseif($v['Variable']['comparison_operator'] == '>'){
									foreach($responseData as $key => $val){
										$res = explode("$",$retStatus);
										if(isset($res[1])){
											$cmp = $res[1];
										} 
										else {
											$cmp = $res[0];
										}							
										if($cmp > $val){
											$status = 1;
											break;
										}else{
											$status = false;
										}
									}
								}
								elseif($v['Variable']['comparison_operator'] == '<>'){
									foreach($responseData as $key => $val){
										$res = explode("$",$retStatus);
										if(isset($res[1])){
											$cmp = $res[1];
										} 
										else {
											$cmp = $res[0];
										}							
										if($cmp != $val){
											$status = 1;
											break;
										}else{
											$status = false;
										}
									}
								}
								elseif($v['Variable']['comparison_operator'] == 'contains'){
									$res = explode("$",$retStatus);
									if(isset($res[1])){
										$cmp = $res[1];
									} 
									else {
										$cmp = $res[0];
									}
									$check = strpos($cmp,$v['Variable']['authentication_response']);
									if(!($check === false)){
										$status = 1;
									}
									else{
										$status = 'error';
									}
								}							
								elseif($v['Variable']['comparison_operator'] == 'date'){
									$res = explode("$",$retStatus);
									if(isset($res[1])){
										$cmp = $res[1];
									} 
									else {
										$cmp = $res[0];
									}							
									$resDateArr = explode("-",$cmp);
									$resDate = mktime(0,0,0,$resDateArr[0],$resDateArr[1],$resDateArr[2]);
									$libDate = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
									if($resDate > $libDate){
										$status = 1;
									}
									else{
										$status = 'error';
									}
								}							   
								else{
									$status = 'error';
								}
								if(!$status || $status == 'error'){
									$msg = $v['Variable']['error_msg'];
									$status = 'error';
									break;
								}
							}
					
						   if($status == ''){
							   $errMsgArr =  explode("ERRNUM=",$response);
							   @$errMsgCount = substr($errMsgArr['1'],0,1);
							   if($errMsgCount == '1'){
								   $this -> Session -> setFlash("Requested record not found.");
								   $this->redirect(array('controller' => 'users', 'action' => 'inhdlogin'));
							   }
							   else{
								   $this -> Session -> setFlash("Authentication server down.");
								   $this->redirect(array('controller' => 'users', 'action' => 'inhdlogin'));
							   }                  
						   }
						   elseif($status == 1 && $posVal != false){
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
							   $this->Session->write("innovative_var_https_wo_pin","innovative_var_https_wo_pin");
							   if($existingLibraries['0']['Library']['library_logout_url'] != '' && $this->Session->read('referral') != ''){
									$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
							   }
								if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
									$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
								}
							   $isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
							   $this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
							   $this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
							   $this->Download->recursive = -1;
							   $results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
							   $this ->Session->write("downloadsUsed", $results);
							   if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
								   $this ->Session->write("block", 'yes');
							   }
							   else{
								   $this ->Session->write("block", 'no');
							   }
							   $this->redirect(array('controller' => 'homes', 'action' => 'index'));
						   }
						   else{
							   $this -> Session -> setFlash($msg);
							   if($posVal == false){
								  $this->Session->setFlash("Card number does not match Library record");
							   }
							   $this->redirect(array('controller' => 'users', 'action' => 'inhdlogin'));
						   }
					} else{
					  $errStrArr = explode('ERRMSG=',$response);
					  $errMsg = $errStrArr['1'];
					  $this -> Session -> setFlash($errMsg);
					  $this->redirect(array('controller' => 'users', 'action' => 'inhdlogin'));					
					}
					
				}         
			}
		}
	}
 
   /*
    Function Name : plogin
    Desc : For patron login(Using SOAP web services) login method
   */
   
	function plogin(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
					}
				}
				else {
					$wrongReferral = 1;
				}	
			}
		}
		$this->layout = 'login';
		if(isset($_POST['lang'])){
			$language = $_POST['lang'];
			$langDetail = $this->Language->find('first', array('conditions' => array('id' => $language)));
			$this->Session->write('Config.language', $langDetail['Language']['short_name']);
		}
		if ($this->Session->read('patron')){
			$userType = $this->Session->read('patron');
			if($userType != ''){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;     
			}
		}
		$this->set('pin',"");
		$this->set('card',"");
		if($this->data){         
			$card = $this->data['User']['card'];
			$pin = $this->data['User']['pin'];
			$patronId = $card;        
			if($card == ''){            
				$this -> Session -> setFlash("Please provide card number.");
				if($pin != ''){
					$this->set('pin',$pin);
				}
				else{
					$this->set('pin',"");
				}            
			}
			elseif($pin == ''){            
				$this -> Session -> setFlash("Please provide pin.");            
				if($card != ''){
					$this->set('card',$card);
				}
				else{
					$this->set('card',"");
				}            
			}
			else{
				$cardNo = substr($card,0,5);
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				if($this->Session->read('referral')){
					$library_cond = array('id' => $this->Session->read('lId'));
					$existingLibraries = $this->Library->find('all',array(
									'conditions' => array('library_status' => 'active','library_authentication_method' => 'soap',$library_cond),
									'fields' => array('Library.id','Library.library_territory','Library.library_soap_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
									)
								 );					
				} else {
					$library_cond = '';
					$existingLibraries = $this->Library->find('all',array(
									'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'soap',$library_cond),
									'fields' => array('Library.id','Library.library_territory','Library.library_soap_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
									)
								 );					
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "http://".$_SERVER['HTTP_HOST']."/users/plogin"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
					$this->redirect(array('controller' => 'users', 'action' => 'plogin'));
				}        
				else{
					$authUrl = configure::read('App.ploginDataHandlerUrl');
					$data['soapUrl'] = $existingLibraries['0']['Library']['library_soap_url'];
					$data['card'] = $card;
					$data['pin'] = $pin;
					$result = $this->AuthRequest->getAuthResponse($data,$authUrl); 
					$retMsgArr = explode("<code>",$result);
					$pos = strpos($retMsgArr['1'] ,"</code>");
					$retStatus = substr($retMsgArr['1'],0,$pos);
					
					if($retStatus == 0){
							$this -> Session -> setFlash("Access denied to freegal site.");
							$this->redirect(array('controller' => 'users', 'action' => 'plogin'));            
					}
					else{
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
						$this->Session->write("soap","soap");
						if($existingLibraries['0']['Library']['library_logout_url'] != '' && $this->Session->read('referral') != ''){
							$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
						}
						if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
							$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
						}
						$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
						$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
						$this->Download->recursive = -1;
						$this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
						$results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
						$this ->Session->write("downloadsUsed", $results);
						if($retStatus == '1'){
							$this ->Session->write("block", 'yes');
						}
						else{
							$this ->Session->write("block", 'no');
						}
						$this->redirect(array('controller' => 'homes', 'action' => 'index'));
					}
				}
			}         
		}
	}
   /*
    Function Name : ilhdlogin
    Desc : For patron ilhdlogin(Innovative Var HTTPS with Name) login method
   */
   function ilhdlogin(){
		if(!$this->Session->read('referral')){
			if(isset($_SERVER['HTTP_REFERER'])){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
					}
				}
				else {
					$wrongReferral = 1;
				}	
			}
		}
		$this->layout = 'login';
		if(isset($_POST['lang'])){
			$language = $_POST['lang'];
			$langDetail = $this->Language->find('first', array('conditions' => array('id' => $language)));
			$this->Session->write('Config.language', $langDetail['Language']['short_name']);
		}	
		if ($this->Session->read('patron')){
			$userType = $this->Session->read('patron');
			if($userType != ''){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;     
			}
		}
		$this->set('name',"");
		$this->set('card',"");
		if($this->data){         
			$card = $this->data['User']['card'];
			$name = $this->data['User']['name'];
			$patronId = $card;        
			if($card == ''){            
				$this -> Session -> setFlash("Please provide card number.");
				if($name != ''){
				   $this->set('name',$name);
				}
				else{
				   $this->set('name',"");
				}            
			}
			elseif($name == ''){            
				$this -> Session -> setFlash("Please provide patron Last Name.");            
				if($card != ''){
				   $this->set('card',$card);
				}
				else{
				   $this->set('card',"");
				}            
			}
			else{
				$cardNo = substr($card,0,5);
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				if($this->Session->read('referral')){
					$library_cond = array('id' => $this->Session->read('lId'));
					$existingLibraries = $this->Library->find('all',array(
															'conditions' => array('library_status' => 'active','library_authentication_method' => 'innovative_var_https_name',$library_cond),
															'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
															)
													 );
				} 
				else {
					$library_cond = '';
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'innovative_var_https_name',$library_cond),
														'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
														)
													 );
				}	
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "http://".$_SERVER['HTTP_HOST']."/users/ilhdlogin"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
				   $this->redirect(array('controller' => 'users', 'action' => 'ilhdlogin'));
				}        
				else{
					$matches = array();
					$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];               
					$data['url'] = $authUrl."/PATRONAPI/".$card."/dump";
					$authUrl = configure::read('App.curlDataHandlerUrl');
					$response = $this->AuthRequest->getAuthResponse($data,$authUrl);
					if(strpos($response,"PATRN NAME[pn]=")){
						$retCardArr = explode("PATRN NAME[pn]=",$response);
						foreach($retCardArr as $k=>$v){
							$retPos = strpos($v,"<br/>");
							$retCard = substr($v,0,$retPos-1);
							$retCard = str_replace(" ","",$retCard);
							if(strpos(strtolower($response),strtolower($name))){
								$posVal = true;
								break;
							} else {
								if(strcmp($name,$retCard) == 0){
									$posVal = true;
									break;		
								} else {
									$posVal = false;								
								}
							}						
						}
					} else {
						if(strpos($response, "ERRMSG=")){
							$posVal = false;
						} else {
							$posVal = true;
						}		
					}					
					$retMsgArr = explode("ERRMSG=",$response);               
					@$retStatus = $retMsgArr['1']; 
					if($retStatus != ''){
						$errMsgArr =  explode("ERRNUM=",$retMsgArr['0']);
						@$errMsgCount = substr($errMsgArr['1'],0,1);
						if($errMsgCount == '1'){
							$this -> Session -> setFlash("Requested record not found.");
							$this->redirect(array('controller' => 'users', 'action' => 'ilhdlogin'));
						}
						else{
							$this -> Session -> setFlash("Authentication server down.");
							$this->redirect(array('controller' => 'users', 'action' => 'ilhdlogin'));
						}                  
					}
					elseif($retStatus == '' && $posVal != false){
						$this->Variable->recursive = -1;
						$allVariables = $this->Variable->find('all',array(
												'conditions' => array('library_id' => $existingLibraries['0']['Library']['id']),
												'fields' => array('authentication_variable','authentication_response','comparison_operator','error_msg',)
											)
										);
						$status = 1;
						foreach($allVariables as $k=>$v){
							$responseData = explode(",",$v['Variable']['authentication_response']);
							$retStatusArr = explode($v['Variable']['authentication_variable'],$response);
							$pos = strpos($retStatusArr['1'],"<br/>");
							$retStatus = substr($retStatusArr['1'],1,$pos-1);
							if($retStatus == ''){
								$status = '';
							}
							elseif($v['Variable']['comparison_operator'] == '='){
								$check = strpos($v['Variable']['authentication_response'],$retStatus);
								if(!($check === false)){
									$status = 1;
								}
								else{
									$status = 'error';
								}
							}
							elseif($v['Variable']['comparison_operator'] == '<'){
								foreach($responseData as $key => $val){
									$res = explode("$",$retStatus);
									if(isset($res[1])){
										$cmp = $res[1];
									} 
									else {
										$cmp = $res[0];
									}							
									if($cmp < $val){
										$status = 1;
										break;
									}else{
										$status = false;
									}
								}
							}
							elseif($v['Variable']['comparison_operator'] == '>'){
								foreach($responseData as $key => $val){
									$res = explode("$",$retStatus);
									if(isset($res[1])){
										$cmp = $res[1];
									} 
									else {
										$cmp = $res[0];
									}							
									if($cmp > $val){
										$status = 1;
										break;
									}else{
										$status = false;
									}
								}
							}
							elseif($v['Variable']['comparison_operator'] == '<>'){
								foreach($responseData as $key => $val){
									$res = explode("$",$retStatus);
									if(isset($res[1])){
										$cmp = $res[1];
									} 
									else {
										$cmp = $res[0];
									}							
									if($cmp != $val){
										$status = 1;
										break;
									}else{
										$status = false;
									}
								}
							}
							elseif($v['Variable']['comparison_operator'] == 'contains'){
								$res = explode("$",$retStatus);
								if(isset($res[1])){
									$cmp = $res[1];
								} 
								else {
									$cmp = $res[0];
								}
								$check = strpos($cmp,$v['Variable']['authentication_response']);
								if(!($check === false)){
									$status = 1;
								}
								else{
									$status = 'error';
								}
							}							
							elseif($v['Variable']['comparison_operator'] == 'date'){
								$res = explode("$",$retStatus);
								if(isset($res[1])){
									$cmp = $res[1];
								} 
								else {
									$cmp = $res[0];
								}							
								$resDateArr = explode("-",$cmp);
								$resDate = mktime(0,0,0,$resDateArr[0],$resDateArr[1],$resDateArr[2]);
								$libDate = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
								if($resDate > $libDate){
									$status = 1;
								}
								else{
									$status = 'error';
								}
							}							   
							else{
								$status = 'error';
							}
							if(!$status || $status == 'error'){
								$msg = $v['Variable']['error_msg'];
								$status = 'error';
								break;
							}
						}
						if($status == ''){
							$errMsgArr =  explode("ERRNUM=",$retStr);
							@$errMsgCount = substr($errMsgArr['1'],0,1);
							if($errMsgCount == '1'){
								$this -> Session -> setFlash("Requested record not found.");
								$this->redirect(array('controller' => 'users', 'action' => 'ilhdlogin'));
							}
							else{
								$this -> Session -> setFlash("Authentication server down.");
								$this->redirect(array('controller' => 'users', 'action' => 'ilhdlogin'));
							}                  
						}
						elseif($status == 1){
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
							$this->Session->write("innovative_var_https_name","innovative_var_name");
							if($existingLibraries['0']['Library']['library_logout_url'] != '' && $this->Session->read('referral') != ''){
								$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
							}
							if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
								$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
							}
							$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
							$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);						
							$this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
							$this->Download->recursive = -1;
							$results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
							$this ->Session->write("downloadsUsed", $results);
							if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
								$this ->Session->write("block", 'yes');
							}
							else{
								$this ->Session->write("block", 'no');
							}
							$this->redirect(array('controller' => 'homes', 'action' => 'index'));
						}
						else{
							$this -> Session -> setFlash($msg);
							$this->redirect(array('controller' => 'users', 'action' => 'ilhdlogin'));
						}
					} else {
						$this -> Session -> setFlash("Last Name does not match Library Card.");
						$this->redirect(array('controller' => 'users', 'action' => 'ilhdlogin'));
					}
				}         
			}
		}
	} 
	
}
?>
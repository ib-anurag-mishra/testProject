<?php
 /*
 File Name : users_controller.php
 File Description : Controller page for the  login functionality.
 Author : m68interactive
 */

Class UsersController extends AppController
{
	var $name = 'Users';
	var $helpers = array('Html','Ajax','Javascript','Form', 'User', 'Library', 'Page', 'Language');
	var $layout = 'admin';
	var $components = array('Session','Auth','Acl','PasswordHelper','Email','sip2','ezproxysso','AuthRequest','Cookie','Streaming', 'RequestHandler');
	var $uses = array('User','Group', 'Library', 'Currentpatron', 'Download','Variable','Url','Language','Consortium','Card','LibrariesTimezone','NotificationSubscriptions');
   
   /*
    Function Name : beforeFilter
    Desc : actions that needed before other functions are getting called
   */
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('libinactive','logout','ilogin','inlogin','ihdlogin','idlogin','ildlogin','indlogin','inhdlogin','inhlogin','slogin','snlogin','sdlogin','sndlogin','plogin','ilhdlogin','admin_user_deactivate','admin_user_activate','admin_patron_deactivate','admin_patron_activate','sso','admin_data','redirection_manager','redirection','method_action_mapper','clogin','mdlogin','mndlogin','admin_addmultipleusers','manage_notification','saveNotification','unsubscribe', 'isPatronLogin','savestreampopup', 'capita', 'symws');
		$this->Cookie->name = 'baker_id';
		$this->Cookie->time = 3600; // or '1 hour'
		$this->Cookie->path = '/';
		$this->Cookie->domain = 'freegalmusic.com';
	}
	/*
    Function Name : beforeFilter
    Desc : This function redirects the libraries to their corresponding login page basing on the
		   library subdomain name in the url.
   */
	function method_action_mapper($method = null)
	{
		$method_vs_action = array('sip2_var' => 'sdlogin',
								'sip2_var_wo_pin'=>'sndlogin',
								'sip2'=>'slogin',
								'sip2_wo_pin'=>'snlogin',
								'innovative_var_wo_pin'=>'indlogin',
								'innovative_https'=>'inhlogin',
								'innovative_wo_pin'=>'inlogin',
								'innovative_var'=>'idlogin',
								'innovative'=>'ilogin',
								'user_account'=>'login',
								'innovative_var_name'=>'ildlogin',
								'innovative_var_https_name'=>'ilhdlogin',
								'innovative_var_https'=>'ihdlogin',
								'innovative_var_https_wo_pin'=>'inhdlogin',
								'soap'=>'plogin',
								'mndlogin_reference'=>'mndlogin',
								'mdlogin_reference'=>'mdlogin',
								'curl_method'=>'clogin',
                                                                'capita'=>'capita', 'symws'=>'symws');
		return $method_vs_action[$method];
	}
	function redirection_manager($library = null)
	{

		if($library != null)
		{
			$library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));                      
                        
                                               
                        if($library_data['Library']['library_status'] == 'inactive'){
                           $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/libinactive'); 
                           exit;                           
                        }                        
                        
			$this->get_login_layout_name($library_data);                       
                        
                        
			if(!empty($library_data))
			{
                            if($this->Session->read('loginchk') == '' && $this->Session->read('patron')== '')
                            {

                                if($_SERVER['REQUEST_URI'] == '/users/redirection_manager'){
                                    if($library_data['Library']['library_authentication_method'] == 'referral_url')
                                    {
                                            $referral = explode(",",$library_data['Library']['library_domain_name']);
                                            $this->Cookie->write('referer', $referral[0], false);
                                            setcookie('referer',$referral[0],3600,'/','freegalmusic.com');
                                            $this->redirect($referral[0]);
                                    }
                                    else if($library_data['Library']['library_authentication_method'] == 'ezproxy')
                                    {
                                        $this->redirect($library_data['Library']['library_ezproxy_referral']);
                                    }
                                    else
                                    {
                                            $action = $this->method_action_mapper($library_data['Library']['library_authentication_method']);
                                            $this->Session->write("layout_option", 'login');
                                            $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/'.$action);
                                    }
                                } else {
                                    $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/homes/index');
                                }
                            }
                            else
                            {
                                    $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/homes/index');
                            }
			}
			else
			{
				$this->Session->write('lib_status', 'invalid');
				$this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/homes/aboutus');
			}
		} else {
            $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/homes/chooser');
        }
	}
        
        function redirection($library = null)
	{
            if($library != null)
		{
			$library_data = $this->Library->find('first', array('conditions' => array('Library.id' => $library)));                      
                        
                                            
                        if($library_data['Library']['library_status'] == 'inactive'){
                           $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/libinactive'); 
                           exit;                           
                        }                        
                        
			$this->get_login_layout_name($library_data);                       
                        
                        
			if(!empty($library_data))
			{
                            	if($this->Session->read('loginchk') == '' && $this->Session->read('patron')== '')
				{
                                    if($_SERVER['REQUEST_URI'] == '/users/redirection/'.$library){
                                        if($library_data['Library']['library_authentication_method'] == 'referral_url')
					{
						$referral = explode(",",$library_data['Library']['library_domain_name']);
						$this->redirect($referral[0]);
					}
                                        else if($library_data['Library']['library_authentication_method'] == 'ezproxy')
                                        {
                                            $this->redirect($library_data['Library']['library_ezproxy_referral']);
                                        }
					else
					{
						$action = $this->method_action_mapper($library_data['Library']['library_authentication_method']);
                                                $this->Session->write("layout_option", 'login');
                                                $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/'.$action);
					}
                                    } else {
                                        $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/homes/index');
                                    }
				}
				else
				{
                                    $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/homes/index');
				}
			}
			else
			{
				$this->Session->write('lib_status', 'invalid');
				$this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/homes/aboutus');
			}
		} else {
                    $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/homes/chooser');
                }
	}
	
     /*
    Function Name : libinactive
    Desc : actions for set message whenever a inative app call
   */
        function libinactive(){
            $this->layout = 'login';                        
            $this->Session->setFlash('The library you are trying to access is no longer registered with us. Please contact your local library for more information.', 'default', array(), 'inactivelib_message');
            $this->set('show_inactivelib',1); 
        }
   
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
			$memcache = new Memcache;
			$memcache->addServer(Configure::read('App.memcache_ip'), 11211);
                        $memcache->addServer(Configure::read('App.memcache_ip2'), 11211);
			$x = memcache_get($memcache,"librarydownload");
			if($library == 'special') {
				foreach($x as $k => $v){
					if(ord($v['library_name']{0}) > 122 ||  ord($v['library_name']{0}) < 65){
						$res[] = $v;
					}
				}
			}
			elseif($library != '') {
				foreach($x as $k => $v){
					if($v['library_name']{0} == $library){
						$res[] = $v;
					}
				}
			}
			else {
				$res = $x;
			}
			$this->set('x', $res);
		}
		if($userType == '4'){
			$libraryDetail = $this->Library->find("first", array("conditions" => array('library_admin_id' =>
			$this->Session->read("Auth.User.id")),'recursive' => -1));
			if($libraryDetail['Library']['library_unlimited'] != '1'){
				$this->set('libraryLimited', 1);
			}
		}
                             
                //checking for Consortium as any library is there or not which is allowed for streaming
                $is_having_streaming_libarry  = $this->Streaming->admin_getLibraryIdsStream();
                if(!empty($is_having_streaming_libarry))
                {
                    $this->set('isHavingStreaming' , 1);   
                }else{
                      $this->set('isHavingStreaming' , 0);   
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
			$memcache = new Memcache;
			$memcache->addServer('10.181.59.64', 11211);
			$memcache->addServer('10.181.59.94', 11211);
			$x = memcache_get($memcache,"librarydownload");
			if($library == 'special') {
				foreach($x as $k => $v){
					if(ord($v['library_name']{0}) > 122 ||  ord($v['library_name']{0}) < 65){
						$res[] = $v;
					}
				}
			}
			elseif($library != '') {
				foreach($x as $k => $v){
					if($v['library_name']{0} == $library){
						$res[] = $v;
					}
				}
			}
			else {
				$res = $x;
			}
			$this->autoRender=false;
			header ("Content-type: text/xml;charset=utf-8");
			$xml= "<?xml version='1.0' encoding='utf-8'?>";
			$xml .= "<rows>";
			if($res){
				while(list($key,$value)= each($res)){
						$xml = $xml. "<row id='".$row['id']."'>";
						$xml = $xml. "<cell><![CDATA[".htmlentities($value['library_name'],ENT_QUOTES)."]]></cell>";
						$xml = $xml. "<cell><![CDATA[".htmlentities($value['library_contract_start_date'],ENT_QUOTES)."]]></cell>";
						$xml = $xml. "<cell><![CDATA[".htmlentities($value['library_contract_end_date'],ENT_QUOTES)."]]></cell>";
						$xml = $xml. "<cell><![CDATA[".htmlentities($value['today'],ENT_QUOTES)."]]></cell>";
						$xml = $xml. "<cell><![CDATA[".htmlentities($value['week'],ENT_QUOTES)."]]></cell>";
						$xml = $xml. "<cell><![CDATA[".htmlentities($value['month'],ENT_QUOTES)."]]></cell>";
						$xml = $xml. "<cell><![CDATA[".htmlentities($value['ytd'],ENT_QUOTES)."]]></cell>";
						$xml = $xml. "<cell><![CDATA[".htmlentities($value['library_available_downloads'],ENT_QUOTES)."]]></cell>";
						$xml .= "</row>";
					}
			}
			$xml .= "</rows>";
			return $xml;

		}
	}


   /*
    Function Name : login
    Desc : Validates admin login credentials
   */

	function admin_login() {
            
          $this->layout = 'admin';
		$this->Auth->autoRedirect = false;
		if (empty($this->data)) {
			$this->Session->delete('Message.auth');
		}
		if ($this->Session->read('Auth.User')) {
			$userType = $this->Session->read('Auth.User.type_id');
			if($userType == '5' && $this->Session->read('Auth.User.sales') != 'yes'){
				$this->redirect('/homes/index');
				$this->Auth->autoRedirect = false;
			}
			$this->redirect(array('controller' => 'users', 'action' => 'index', 'admin' => true));
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

function login($library = null){
    $this->Session->write("layout_option", 'login');
    if($this->Session->read('layout_option') == 'login'){
            $this->layout = 'login';
    }
    else{
            $this->layout = 'login';
    }

    if(!$this->Session->read('referral') && !$this->Session->read("subdomain")){
            if(isset($_SERVER['HTTP_REFERER']) && $library == null){
                    $url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
                    if(count($url) > 0){
                            if($this->Session->read('referral') == ''){
                                    $this->Session->write("referral",$_SERVER['HTTP_REFERER']);
                                    $this->Session->write("lId",$url[0]['Url']['library_id']);
                                    $this->Session->write("login_action",'ilogin');
                            }
                    }
                    else {
                            $wrongReferral = 1;
                            $data['wrongReferral'] = $wrongReferral;
                    }
            }
            else if($library != null)
            {
                    $library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
                    $this->get_login_layout_name($library_data);
                    if($this->Session->read('layout_option') == 'login'){
                            $this->layout = 'login';
                    }
                    else{
                            $this->layout = 'login';
                    }
                    if(count($library_data) > 0)
                    {
                            if($this->Session->read('lId') == '')
                            {
                                    $this->Session->write("subdomain",$library);
                                    $this->Session->write("lId",$library_data['Library']['id']);
                            }
                    }
                    else
                    {
                            $wrongReferral = 1;
                    }
            }
    }
    if(isset($this->params['form']['lang'])){
            $language = $this->params['form']['lang'];
            $langDetail = $this->Language->find('first', array('conditions' => array('id' => $language)));
            $this->Session->write('Config.language', $langDetail['Language']['short_name']);
    }
    if ($this->Session->read('patron')){
            $userType = $this->Session->read('patron');
            if($userType != ''){
                    $this->Auth->autoRedirect = false;
                    $this->redirect('/index');

            }
    }
}

   /*
    Function Name : index
    Desc : Users index to allow/disallow valid users
   */

	function index(){
		$this->autoRender = false;
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
                                $librarySubdomain = $this->Session->read("subdomain");
                                if(!empty($librarySubdomain)){
                                    $domainLibraryId = $this->Session->read('library');
                                    if($domainLibraryId != $libraryId){
                                        $this -> Session -> setFlash("Authentication error, you are not allowed to login into the library using this method. Please use the link on your library website.");
                                        $this->redirect($this->Auth->logout());
                                    }
                                }
				$currentPatron = $this->Currentpatron->find('all', array('conditions' => array('libid' => $libraryId, 'patronid' => $patronId)));
				if(count($currentPatron) > 0){
				// do nothing
				} else {
					$insertArr['libid'] = $libraryId;
					$insertArr['patronid'] = $patronId;
					$insertArr['session_id'] = session_id();
					$this->Currentpatron->save($insertArr);
				}
				$date = time();
				$values = array(0 => $date, 1 => session_id());
				Cache::write("login_".$libraryArr['Library']['library_territory']."_".$libraryId."_".$patronId, $values);
				//writing to memcache and writing to both the memcached servers
			
                                $this->Session->write("library", $libraryId);
                                $this->Session->write("loginchk", 'Yes');
                                
                                //check this library exist is in the library timezone table
                                $countLibPicksSql ='select count(*) as total from libraries_timezone  where library_id = "'.$this->Session->read("library").'"';
                                $libPicksRecord = $this->LibrariesTimezone->query($countLibPicksSql);
                                if(isset($libPicksRecord[0][0]['total']) && ($libPicksRecord[0][0]['total'] > 0 )){
                                    $this->Session->write("isLibaryExistInTimzone", 1);                               
                                }else{
                                    $this->Session->write("isLibaryExistInTimzone", 0);                               
                                }        
                                
                                //check if the notification entry is already there in the notification_subscription table
                                $notificationSql ='select count(*) as total from notification_subscriptions  where patron_id ="'.$patronId.'" and library_id = "'.$this->Session->read("library").'"';
                                $emailNotificationRecord = $this->NotificationSubscriptions->query($notificationSql);
                                if(isset($emailNotificationRecord[0][0]['total']) && ($emailNotificationRecord[0][0]['total'] > 0 )){
                                    $this->Session->write("showNotificationPopup", 'yes');                               
                                }else{
                                    $this->Session->write("showNotificationPopup", 'no');                               
                                }
                                
                                
                           
				$this->Session->write("patron", $patronId);
				$this->Session->write("patronEmail", $this->Session->read('Auth.User.email'));
				$this->Session->write("territory", $libraryArr['Library']['library_territory']);
				if($this->Session->read('Auth.User.consortium') != ''){
					$this->Session->write("consortium", $this->Session->read('Auth.User.consortium'));
				}
				$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $libraryId,'patronid' => $patronId)));
                $this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);

		if($libraryArr['Library']['library_type'] == 2){
		    $this->Session->write("streamPopupShow", $isApproved['Currentpatron']['stream_popup']);
		    $this->Session->write("userlogin", 'yes');
                }

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
                                if ($this->Session->read('UrlReferer') != '') {
                                      $UrlReferer = $this->Session->read('UrlReferer');
                                      $this->Session->delete('UrlReferer');
                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .$UrlReferer);
                                } else {
                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/index');
                                }
			}
			else{
        $this -> Session -> setFlash("Authentication error, you are not allowed to login into the library using this method. Please use the link on your library website.");
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
                $this->Cookie->delete('redirecting');
		$patronDetails = $this->Currentpatron->find('all',array('conditions' => array('patronid' => $patronId,'libid' => $libraryId)));
		if(count($patronDetails) > 0){
			$updateTime = date( "Y-m-d H:i:s", time()-60 );
			$this->Currentpatron->id = $patronDetails[0]['Currentpatron']['id'];
			$this->Currentpatron->saveField('modified',$updateTime, false);
			//writing to memcache and writing to both the memcached servers
			Cache::delete("login_".$this->Session->read('library')."_".$libraryId."_".$patronId);
			if($this->Session->read('referral_url') && ($this->Session->read('referral_url') != '')){
				$redirectUrl = $this->Session->read('referral_url');
				$this->Session->destroy();
                                $this->Cookie->destroy();
                                setcookie('referer',$referral[0],-3600,'/','freegalmusic.com');
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
			elseif($this->Session->read('curl_method') && ($this->Session->read('curl_method') != '')){
				if($this->Session->read('referral')){
					$redirectUrl = $this->Session->read('referral');
					$this->Session->destroy();
					$this->redirect($redirectUrl, null, true);
				} else {
					$this->Session->destroy();
					$this->redirect(array('controller' => 'users', 'action' => 'clogin'));
				}
			}

			elseif($this->Session->read('mndlogin_reference') && ($this->Session->read('mndlogin_reference') != '')){
				if($this->Session->read('referral')){
					$redirectUrl = $this->Session->read('referral');
					$this->Session->destroy();
					$this->redirect($redirectUrl, null, true);
				} else {
					$this->Session->destroy();
					$this->redirect(array('controller' => 'users', 'action' => 'mndlogin'));
				}
			}

			elseif($this->Session->read('mdlogin_reference') && ($this->Session->read('mdlogin_reference') != '')){
				if($this->Session->read('referral')){
					$redirectUrl = $this->Session->read('referral');
					$this->Session->destroy();
					$this->redirect($redirectUrl, null, true);
				} else {
					$this->Session->destroy();
					$this->redirect(array('controller' => 'users', 'action' => 'mdlogin'));
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
                        elseif($this->Session->read('capita') && ($this->Session->read('capita') != '')){
				if($this->Session->read('referral')){
					$redirectUrl = $this->Session->read('referral');
					$this->Session->destroy();
					$this->redirect($redirectUrl, null, true);
				} else {
					$this->Session->destroy();
					$this->redirect(array('controller' => 'users', 'action' => 'capita'));
				}
			}
                         elseif($this->Session->read('symws') && ($this->Session->read('symws') != '')){
				if($this->Session->read('referral')){
					$redirectUrl = $this->Session->read('referral');
					$this->Session->destroy();
					$this->redirect($redirectUrl, null, true);
				} else {
					$this->Session->destroy();
					$this->redirect(array('controller' => 'users', 'action' => 'symws'));
				}
			}
			else{
			$this->Session->destroy();
			$this->redirect($this->Auth->logout());
			}
		}else{
                        $this->Session->destroy();
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
					if($this->data['User']['type_id'] == 5){
						$this->data['User']['sales'] = 'yes';
					}
					if($this->data['User']['type_id'] == 6 && $this->data['User']['consortium'] != ''){
						$this->data['User']['type_id'] = 4;
					}else{
						$this->data['User']['consortium'] = '';
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

				if($this->data['User']['type_id'] == 5){
					$this->data['User']['sales'] = 'yes';
				}
				if($this->data['User']['type_id'] == 6 && $this->data['User']['consortium'] != ''){
					$this->data['User']['type_id'] = 4;
				}else{
					$this->data['User']['consortium'] = '';
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
		$consortium = $this->Consortium->find('list', array('fields' => array('consortium_name','consortium_name'), 'order' => 'consortium_name ASC'));
		$this->set('consortium', $consortium);
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
                
                $this->Library->recursive = -1;
                //check the library subdomain
                if(isset($Patron['User']['library_id']) && ($Patron['User']['library_id']!=0) && ($Patron['User']['library_id']!='')){
                    $library_data = $this->Library->find('first', array('conditions' => array('Library.id' => $Patron['User']['library_id']),'fields'=>array('library_subdomain'))); 
                     
                    if(isset($library_data['Library']['library_subdomain']) && $library_data['Library']['library_subdomain']!=''){
                        $this->set('library_subdomain', $library_data['Library']['library_subdomain']);
                    }else{
                        $this->set('library_subdomain', 'www');
                    }                    
                }else{
                    $this->set('library_subdomain', 'www');
                }
                
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
                
                $this->Library->recursive = -1;
                //check the library subdomain
                if(isset($Patron['User']['library_id']) && ($Patron['User']['library_id']!=0) && ($Patron['User']['library_id']!='')){
                    $library_data = $this->Library->find('first', array('conditions' => array('Library.id' => $Patron['User']['library_id']),'fields'=>array('library_subdomain'))); 
                    
                    if(isset($library_data['Library']['library_subdomain']) && $library_data['Library']['library_subdomain']!=''){
                        $this->set('library_subdomain', $library_data['Library']['library_subdomain']);
                    }else{
                        $this->set('library_subdomain', 'www');
                    }                    
                }else{
                    $this->set('library_subdomain', 'www');
                }
                
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
               
                $this->Library->recursive = -1;  
                
                //check the library data and library authentication method
                $library_data = $this->Library->find('first', array('conditions' => array('id' => $this->Session->read('library')),'fields' => array('Library.library_authentication_method')));              
                if(count($library_data) > 0) {
                    if($library_data['Library']['library_authentication_method'] == 'user_account'){                     
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
                            $this->NotificationSubscriptions->setDataSource('master');  
                            if($this->User->save()){                                
                                $this->Session->setFlash('Account information has been save successfully!');
                                $this->redirect($this->webroot.'users/my_account');
                            }
                            $this->NotificationSubscriptions->setDataSource('default');
                        }                        
                    }
                }
	}
    
        /*
        Function Name : manage_notification
        Desc : For manage email notification information
    */
        function manage_notification(){
            
            $this->layout = 'home';
            $patronId = $this->Session->read('patron');                
            //display notification form when library exist in to the library timzone table
            if($this->Session->read('isLibaryExistInTimzone')==1){                    
                  $this->set('notificationShow', 1); 
                  $this->set('notificationAlreadySave', 'false');  
                  $this->set('notificationEmail', '');                      


                  $notidataRecord = $this->NotificationSubscriptions->find('first', array('conditions' => array('patron_id' => $patronId,'library_id' => $this->Session->read('library')),'fields'=>array('email_id')));                          

                  if(count($notidataRecord) > 0) {


                    if($notidataRecord['NotificationSubscriptions']['email_id']==''){
                        //get user email address if email not there
                        $getUserData = $this->User->find('first', array('conditions' => array('User.id' => $patronId),'fields'=>array('User.email')));
                        $this->set('notificationEmail', $getUserData['User']['email']);
                    }else{
                        $this->set('notificationAlreadySave', 'true');
                        $this->set('notificationEmail', $notidataRecord['NotificationSubscriptions']['email_id']);
                    }

                  }
            }            
            $patronId = $this->Session->read('patron');
            $libaryID = $this->Session->read('library');
             if(isset($this->data)){
               if($this->data['User']['sendNewsLetterCheck'] == 1){
                   $notificationEmail = $this->data['User']['NewsletterEmail'];                   
                   
                   $notificationSubscriptionsData = $this->NotificationSubscriptions->find('first', array('conditions' => array('library_id' => $libaryID,'patron_id' => $patronId)));
                   if(count($notificationSubscriptionsData) > 0) {
                       
                       //update record in to the table
                       
                       $this->NotificationSubscriptions->set(array(
                         'id' => $notificationSubscriptionsData['NotificationSubscriptions']['id'],
                         'library_id' => $libaryID,
                        'patron_id' => $patronId,
                       'email_id' => $notificationEmail                       
                        ));
                        $this->NotificationSubscriptions->setDataSource('master');       

                        $regex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/"; 
                        
                        if($notificationEmail!='' && preg_match($regex, $notificationEmail))
                        {
                            if($this->NotificationSubscriptions->save()){                                
                                $this->Session->setFlash('Notification information has been updated successfully!');
                                $this->NotificationSubscriptions->setDataSource('default');
                                $this->redirect($this->webroot.'users/manage_notification');
                            }
                            else
                            {
                                $this->Session->setFlash('There was problem saving Notification information');
                                $this->NotificationSubscriptions->setDataSource('default');
                                $this->redirect($this->webroot.'users/manage_notification');
                            }
                        }
                        else
                        {
                                $this->Session->setFlash('Invalid Email ID');                                
                                $this->redirect($this->webroot.'users/manage_notification');
                        }
                       
                       
                   }else{
                        //insert in to the table
                       
                        $this->NotificationSubscriptions->set(array(
                            'library_id' => $libaryID,
                            'patron_id' => $patronId,
                        'email_id' => $notificationEmail                       
                        )); 

                        $this->NotificationSubscriptions->setDataSource('master');
                        
                        if($this->NotificationSubscriptions->save()){                                
                            $this->Session->setFlash('Notification information has been saved successfully!');
                            $this->NotificationSubscriptions->setDataSource('default');
                            $this->redirect($this->webroot.'users/manage_notification');
                        }                       
                   }                    
                    
               }else{
                   $this->NotificationSubscriptions->setDataSource('master');
                   $this->NotificationSubscriptions->deleteAll(array('library_id' => $libaryID,'patron_id' => $patronId));
                   $this->NotificationSubscriptions->setDataSource('default');
                   $this->Session->setFlash('Notification information has been removed successfully!');
                   $this->redirect($this->webroot.'users/manage_notification'); 
                   
               }
                
             }

        }
        
        /*
        Function Name : unsubscribe
        Desc : For unsubscribe email notification information
        */
        function unsubscribe($email){
            
            //if email address exist then remove it from table and redirect user to login page with message
            if(isset($email) && $email!=''){
                $email = base64_decode($email);
                $this->NotificationSubscriptions->deleteAll(array('email_id' => $email));
                $this->Session->setFlash('You have successfully unsubscribed!');
                $this->redirect($this->webroot.'users/login'); 
            }else{
                $this->redirect($this->webroot.'users/login'); 
            }            
           
            exit;
        }
        
         /*
        Function Name : saveNotification
        Desc : For saving the notification informaiton using ajax call from the home.ctp popup
    */
        function saveNotification(){
           
            $this->layout = false;
            
            if ( $this->RequestHandler->isPost() ) {
            	$index = 'form';
            } else if ( $this->RequestHandler->isGet() ) {
            	$index = 'url';
            }
            
            if(isset($this->params[$index]['notificationClose']) && $this->params[$index]['notificationClose']==1){
                $this->Session->write('showNotificationPopup','yes');
                exit;
            }    
            
            
            if(isset($this->params[$index]['pid']) && isset($this->params[$index]['lid']) && isset($this->params[$index]['notificatinEmail']) 
                    && $this->params[$index]['lid']!=''  && $this->params[$index]['pid']!='' && $this->params[$index]['notificatinEmail']!=''){
                
                $patronId = $this->params[$index]['pid'];
                $libaryID = $this->params[$index]['lid'];
                $notificatinEmail = $this->params[$index]['notificatinEmail'];
                $this->NotificationSubscriptions->setDataSource('master');
                
                //check if record is already exist for this patron and library
                  $notificationSubscriptionsData = $this->NotificationSubscriptions->find('first', array('conditions' => array('library_id' => $libaryID,'patron_id' => $patronId)));
                  if(count($notificationSubscriptionsData) > 0) {
                       
                        //update record in to the table                       
                        $this->NotificationSubscriptions->set(array(
                         'id' => $notificationSubscriptionsData['NotificationSubscriptions']['id'],
                         'library_id' => $libaryID,
                        'patron_id' => $patronId,
                       'email_id' => $notificatinEmail                       
                        ));
                        $this->NotificationSubscriptions->setDataSource('master');
                        $this->NotificationSubscriptions->save(); 
                        $this->NotificationSubscriptions->setDataSource('default');
                        $this->Session->write('showNotificationPopup','yes');
                
                   }else{
                       
                        //insert new record in the table
                        $this->NotificationSubscriptions->set(array(
                            'library_id' => $libaryID,
                            'patron_id' => $patronId,
                        'email_id' => $notificatinEmail                       
                        )); 

                        $this->NotificationSubscriptions->setDataSource('master');
                        $this->NotificationSubscriptions->save();
                        $this->NotificationSubscriptions->setDataSource('default');
                        $this->Session->write('showNotificationPopup','yes');
                        
                   }
                   $this->NotificationSubscriptions->setDataSource('default');
                      
                echo 'success';
                exit;           
            }
        }
        
        /*
        Function Name : saveNotification
        Desc : For saving the notification informaiton using ajax call from the home.ctp popup
    */
        function savestreampopup(){

            $this->layout = false;
            $this->autoRender = false;
            
            if ( $this->RequestHandler->isPost() ) {
            	$index = 'form';
            } else if ( $this->RequestHandler->isGet() ) {
            	$index = 'url';
            }

            if(isset($this->params[$index]['pid']) && isset($this->params[$index]['lid']) && $this->params[$index]['lid']!=''  && $this->params[$index]['pid']!='') 
            {
                $patronId = $this->params[$index]['pid'];
                $libaryID = $this->params[$index]['lid'];
                
                $this->Currentpatron->setDataSource('master');
                
                //check if record is already exist for this patron and library
                $currentPatronData = $this->Currentpatron->find('first', array('conditions' => array('libid' => $libaryID,'patronid' => $patronId)));
                if(count($currentPatronData) > 0) {
                        $this->Currentpatron->setDataSource('master');
                        $currentPatronData['Currentpatron']['stream_popup'] = 'yes';
                        $this->Currentpatron->set($currentPatronData['Currentpatron']);
                        $this->Currentpatron->save();   
                        $this->Session->write('streamPopupShow','yes');
                }
                $this->Currentpatron->setDataSource('default');         
            }
        }
   
    /*
        Function Name : ilogin
        Desc : For patron ilogin(Innovative) login method
    */
   
	function ilogin($library = null){
            
            
             //code to check the library is inactive or not. if library is inactive then redirect user to library inactive page
            if($library){            
                $library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
                if(count($library_data) > 0)
                {
                    if($library_data['Library']['library_status'] == 'inactive'){
                        $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/libinactive'); 
                        exit;                           
                    }
                }
            }
            
                        
            $this->Session->write("layout_option", 'login');
		if($this->Session->read('login_action'))
		{
			if($this->action != $this->Session->read('login_action'))
			{
				$this->Session->destroy('referral');
				$this->Session->destroy('subdomain');
				$this->Session->destroy('login_action');
			}
		}
		if(!$this->Session->read('referral') && !$this->Session->read("subdomain")){
			if(isset($_SERVER['HTTP_REFERER']) && $library == null){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
						$this->Session->write("login_action",'ilogin');
					}
				}
				else {
					$wrongReferral = 1;
					$data['wrongReferral'] = $wrongReferral;
				}
			}
			else if($library != null)
			{
				$library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
				$this->get_login_layout_name($library_data);
				if(count($library_data) > 0)
				{
					if($this->Session->read('lId') == '')
					{
						$this->Session->write("subdomain",$library);
						$this->Session->write("lId",$library_data['Library']['id']);
					}
				}
				else
				{
					$wrongReferral = 1;
				}
			}
		}
		if($this->Session->read('layout_option') == 'login'){
			$this->layout = 'login';
		}
		else{
			$this->layout = 'login';
		}
		if(isset($this->params['form']['lang'])){
			$language = $this->params['form']['lang'];
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
			$card = str_replace(" ","",$card);
			$card = strtolower($card);
			$data['card'] = $card;
			$pin = $this->data['User']['pin'];
			$data['pin'] = $pin;
			$patronId = $card;
			$data['patronId'] = $patronId;
			if($card == ''){
				$this -> Session -> setFlash("Please provide card number.");
				if($pin != ''){
					$this->set('pin',$pin);
				}
				else{
					$this->set('pin',"");
				}
			}
         
                        elseif(strlen($card) < $library_data['Library']['minimum_card_length']){
				$this->Session->setFlash("Please provide a correct card number.");			
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
				$data['cardNo'] = $cardNo;
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				$data['referral'] = $this->Session->read('referral');
				$data['subdomain']=$this->Session->read("subdomain");
				if($this->Session->read('referral') || $this->Session->read("subdomain")){
					$library_cond = $this->Session->read('lId');
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
									'conditions' => array('library_status' => 'active','library_authentication_method' => 'innovative','id' => $library_cond),
									'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
									)
								 );
				} else {
					$library_cond = '';
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
									'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'innovative'),
									'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
									)
								 );
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "https://".$_SERVER['HTTP_HOST']."/users/ilogin"){
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
					$data['database'] = 'freegal';
					if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
						$authUrl1 = Configure::read('App.AuthUrl_AU')."ilogin_validation";
					}
					else{
						$authUrl1 = Configure::read('App.AuthUrl')."ilogin_validation";
					}
					$result = $this->AuthRequest->getAuthResponse($data,$authUrl1);

					$resultAnalysis[0] = $result['Posts']['status'];
					$resultAnalysis[1] = $result['Posts']['message'];
					if($resultAnalysis[0] == "fail"){
						$this->Session->setFlash($resultAnalysis[1]);
						$this->redirect(array('controller' => 'users', 'action' => 'ilogin'));
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
						$date = time();
						$values = array(0 => $date, 1 => session_id());
						Cache::write("login_".$existingLibraries['0']['Library']['library_territory']."_".$existingLibraries['0']['Library']['id']."_".$patronId, $values);
					
						$this->Session->write("library", $existingLibraries['0']['Library']['id']);
                                                $this->Session->write("loginchk", 'Yes');
                                                
                                                //check this library exist is in the library timezone table
                                                $countLibPicksSql ='select count(*) as total from libraries_timezone  where library_id = "'.$this->Session->read("library").'"';
                                                $libPicksRecord = $this->LibrariesTimezone->query($countLibPicksSql);
                                                if(isset($libPicksRecord[0][0]['total']) && ($libPicksRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("isLibaryExistInTimzone", 1);                               
                                                }else{
                                                $this->Session->write("isLibaryExistInTimzone", 0);                               
                                                }        

                                                //check if the notification entry is already there in the notification_subscription table
                                                $notificationSql ='select count(*) as total from notification_subscriptions  where patron_id ="'.$patronId.'" and library_id = "'.$this->Session->read("library").'"';
                                                $emailNotificationRecord = $this->NotificationSubscriptions->query($notificationSql);
                                                if(isset($emailNotificationRecord[0][0]['total']) && ($emailNotificationRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("showNotificationPopup", 'yes');                               
                                                }else{
                                                $this->Session->write("showNotificationPopup", 'no');                               
                                                }
                                                
						$this->Session->write("patron", $patronId);
						$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
						$this->Session->write("innovative","innovative");
						if($existingLibraries['0']['Library']['library_logout_url'] != '' && ($this->Session->read('referral') != '' || $this->Session->read("subdomain") != '')){
							$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
						}
						if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
							$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
						}
						$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));
						$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
						if($existingLibraries['0']['Library']['library_type'] == 2){
							$this->Session->write("streamPopupShow", $isApproved['Currentpatron']['stream_popup']);
							$this->Session->write("userlogin", 'no');
						}
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
                                                if ($this->Session->read('UrlReferer') != '') {
                                                      $UrlReferer = $this->Session->read('UrlReferer');
                                                      $this->Session->delete('UrlReferer');
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .$UrlReferer);
                                                } else {
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/index');
                                                }

					}
				}
			}
		}
	}

   /*
    Function Name : idlogin
    Desc : For patron idlogin(Innovative pin) login method
   */

   function idlogin($library = null){
       
       
      //code to check the library is inactive or not. if library is inactive then redirect user to library inactive page
        if($library){            
            $library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
            if(count($library_data) > 0)
            {
                if($library_data['Library']['library_status'] == 'inactive'){
                    $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/libinactive'); 
                    exit;                           
                }
            }
        }  

        $this->Session->write("layout_option", 'login');
        
        if($this->Session->read('login_action'))
        {
                if($this->action != $this->Session->read('login_action'))
                {
                        $this->Session->destroy('referral');
                        $this->Session->destroy('subdomain');
                        $this->Session->destroy('login_action');
                }
        }
        if(!$this->Session->read('referral') && !$this->Session->read("subdomain")){ 
                if(isset($_SERVER['HTTP_REFERER']) && $library == null){
                        $url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
                        if(count($url) > 0){
                                if($this->Session->read('referral') == ''){
                                        $this->Session->write("referral",$_SERVER['HTTP_REFERER']);
                                        $this->Session->write("lId",$url[0]['Url']['library_id']);
                                        $this->Session->write("login_action",'idlogin');
                                }
                        }
                        else {
                                $wrongReferral = 1;
                                $data['wrongReferral'] = $wrongReferral;
                        }	
                }
                else if($library != null)
                {
                        $library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
                        $this->get_login_layout_name($library_data);
                        if(count($library_data) > 0)
                        {
                                if($this->Session->read('lId') == '')
                                {
                                        $this->Session->write("subdomain",$library);
                                        $this->Session->write("lId",$library_data['Library']['id']);
                                }
                        }
                        else 
                        {
                                $wrongReferral = 1;
                        }	
                }
        }
		if($this->Session->read('layout_option') == 'login'){
			$this->layout = 'login';
		}
		else{
			$this->layout = 'login';
		}

		if(isset($this->params['form']['lang'])){
			$language = $this->params['form']['lang'];
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
			$card = str_replace(" ","",$card);
			$card = strtolower($card);
			$data['card'] = $card;
			$pin = $this->data['User']['pin'];
			$data['pin'] = $pin;
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
			elseif(strlen($card) < $library_data['Library']['minimum_card_length'] && !$this->Session->read("subdomain")){
				$this->Session->setFlash("Please provide a correct card number.");			
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
				$data['cardNo'] = $cardNo;
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				$data['referral'] = $this->Session->read('referral');
				$data['subdomain']=$this->Session->read("subdomain");
				if($this->Session->read('referral') || $this->Session->read("subdomain")){
					$library_cond = $this->Session->read('lId');
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
															'conditions' => array('library_status' => 'active','library_authentication_method' => 'innovative_var','id' => $library_cond),
															'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
															)
													 );
				}
				else {
					$library_cond = '';
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'innovative_var'),
														'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
														)
													 );
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "https://".$_SERVER['HTTP_HOST']."/users/idlogin"){
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
					$data['database'] = 'freegal';
					if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
						$authUrl = Configure::read('App.AuthUrl_AU')."idlogin_validation";
					}
					else{
						$authUrl = Configure::read('App.AuthUrl')."idlogin_validation";
					}
					$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

					$resultAnalysis[0] = $result['Posts']['status'];
					$resultAnalysis[1] = $result['Posts']['message'];
					if($resultAnalysis[0] == "fail"){
						$this->Session->setFlash($resultAnalysis[1]);
						$this->redirect(array('controller' => 'users', 'action' => 'idlogin'));
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
						$date = time();
						$values = array(0 => $date, 1 => session_id());
						Cache::write("login_".$existingLibraries['0']['Library']['library_territory']."_".$existingLibraries['0']['Library']['id']."_".$patronId, $values);
					
						$this->Session->write("library", $existingLibraries['0']['Library']['id']);
                                                $this->Session->write("loginchk", 'Yes');
                                                
                                                //check this library exist is in the library timezone table
                                                $countLibPicksSql ='select count(*) as total from libraries_timezone  where library_id = "'.$this->Session->read("library").'"';
                                                $libPicksRecord = $this->LibrariesTimezone->query($countLibPicksSql);
                                                if(isset($libPicksRecord[0][0]['total']) && ($libPicksRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("isLibaryExistInTimzone", 1);                               
                                                }else{
                                                $this->Session->write("isLibaryExistInTimzone", 0);                               
                                                }        

                                                //check if the notification entry is already there in the notification_subscription table
                                                $notificationSql ='select count(*) as total from notification_subscriptions  where patron_id ="'.$patronId.'" and library_id = "'.$this->Session->read("library").'"';
                                                $emailNotificationRecord = $this->NotificationSubscriptions->query($notificationSql);
                                                if(isset($emailNotificationRecord[0][0]['total']) && ($emailNotificationRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("showNotificationPopup", 'yes');                               
                                                }else{
                                                $this->Session->write("showNotificationPopup", 'no');                               
                                                }
                                                
						$this->Session->write("patron", $patronId);
						$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
						$this->Session->write("innovative_var","innovative_var");
						if($existingLibraries['0']['Library']['library_logout_url'] != '' && ($this->Session->read('referral') != '' || $this->Session->read("subdomain") != '')){
							$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
						}
						if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
							$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
						}
						$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));
						$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
						if($existingLibraries['0']['Library']['library_type'] == 2){
							$this->Session->write("streamPopupShow", $isApproved['Currentpatron']['stream_popup']);
							$this->Session->write("userlogin", 'no');
						}
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
                                                if ($this->Session->read('UrlReferer') != '') {
                                                      $UrlReferer = $this->Session->read('UrlReferer');
                                                      $this->Session->delete('UrlReferer');
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .$UrlReferer);
                                                } else {
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/index');
                                                }
					}
				}
			}
		}
	}

	/*
    Function Name : mdlogin
    Desc : For patron mdlogin login method
   */

   function mdlogin($library = null){
       
       
      //code to check the library is inactive or not. if library is inactive then redirect user to library inactive page
    if($library){            
        $library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
        if(count($library_data) > 0)
        {
            if($library_data['Library']['library_status'] == 'inactive'){
                $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/libinactive'); 
                exit;                           
            }
        }
    }
       
       
       
    $this->Session->write("layout_option", 'login');
		if($this->Session->read('login_action'))
		{
			if($this->action != $this->Session->read('login_action'))
			{
				$this->Session->destroy('referral');
				$this->Session->destroy('subdomain');
				$this->Session->destroy('login_action');
			}
		}
		if(!$this->Session->read('referral') && !$this->Session->read("subdomain")){
			if(isset($_SERVER['HTTP_REFERER']) && $library == null){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
						$this->Session->write("login_action",'mdlogin');
					}
				}
				else {
					$wrongReferral = 1;
					$data['wrongReferral'] = $wrongReferral;
				}
			}
			else if($library != null)
			{
				$library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
				$this->get_login_layout_name($library_data);
				if(count($library_data) > 0)
				{
					if($this->Session->read('lId') == '')
					{
						$this->Session->write("subdomain",$library);
						$this->Session->write("lId",$library_data['Library']['id']);
					}
				}
				else
				{
					$wrongReferral = 1;
				}
			}
		}
		if($this->Session->read('layout_option') == 'login'){
			$this->layout = 'login';
		}
		else{
			$this->layout = 'login';
		}

		if(isset($this->params['form']['lang'])){
			$language = $this->params['form']['lang'];
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
			$card = str_replace(" ","",$card);
			$card = strtolower($card);
			$data['card'] = $card;
			$pin = $this->data['User']['pin'];
			$data['pin'] = $pin;
			$patronId = $card;
			$data['patronId'] = $patronId;
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
				$data['cardNo'] = $cardNo;
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				$data['referral'] = $this->Session->read('referral');
				$data['subdomain']=$this->Session->read("subdomain");
				if($this->Session->read('referral') || $this->Session->read("subdomain")){
					$library_cond = $this->Session->read('lId');
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
															'conditions' => array('library_status' => 'active','library_authentication_method' => 'mdlogin_reference','id' => $library_cond),
															'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
															)
													 );
				}
				else {
					$library_cond = '';
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'mdlogin_reference'),
														'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
														)
													 );
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "https://".$_SERVER['HTTP_HOST']."/users/mdlogin"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
				   $this->redirect(array('controller' => 'users', 'action' => 'mdlogin'));
				}
				else{

					$login_res = $this->Card->find('first',array('conditions' => array('Card.card_number' => $card , 'Card.pin' => $pin , 'Card.library_id' => $existingLibraries['0']['Library']['id'] ) , 'fields' => array('id')));
					if(isset($login_res['Card']['id'])) {
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
						$date = time();
						$values = array(0 => $date, 1 => session_id());
						Cache::write("login_".$existingLibraries['0']['Library']['library_territory']."_".$existingLibraries['0']['Library']['id']."_".$patronId, $values);
						$this->Session->write("library", $existingLibraries['0']['Library']['id']);
                                                $this->Session->write("loginchk", 'Yes');
                                                
                                                //check this library exist is in the library timezone table
                                                $countLibPicksSql ='select count(*) as total from libraries_timezone  where library_id = "'.$this->Session->read("library").'"';
                                                $libPicksRecord = $this->LibrariesTimezone->query($countLibPicksSql);
                                                if(isset($libPicksRecord[0][0]['total']) && ($libPicksRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("isLibaryExistInTimzone", 1);                               
                                                }else{
                                                $this->Session->write("isLibaryExistInTimzone", 0);                               
                                                }        

                                                //check if the notification entry is already there in the notification_subscription table
                                                $notificationSql ='select count(*) as total from notification_subscriptions  where patron_id ="'.$patronId.'" and library_id = "'.$this->Session->read("library").'"';
                                                $emailNotificationRecord = $this->NotificationSubscriptions->query($notificationSql);
                                                if(isset($emailNotificationRecord[0][0]['total']) && ($emailNotificationRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("showNotificationPopup", 'yes');                               
                                                }else{
                                                $this->Session->write("showNotificationPopup", 'no');                               
                                                }
                                                
						$this->Session->write("patron", $patronId);
						$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
						$this->Session->write("mdlogin_reference","mdlogin_reference");
						if($existingLibraries['0']['Library']['library_logout_url'] != '' && ($this->Session->read('referral') != '' || $this->Session->read("subdomain") != '')){
							$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
						}
						if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
							$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
						}
						$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));
						$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
						if($existingLibraries['0']['Library']['library_type'] == 2){
							$this->Session->write("streamPopupShow", $isApproved['Currentpatron']['stream_popup']);
							$this->Session->write("userlogin", 'no');
						}
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
                                                if ($this->Session->read('UrlReferer') != '') {
                                                      $UrlReferer = $this->Session->read('UrlReferer');
                                                      $this->Session->delete('UrlReferer');
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .$UrlReferer);
                                                } else {
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/index');
                                                }
					}
					else
					{
						$this->Session->setFlash('Invalid Credentials');
						$this->redirect(array('controller' => 'users', 'action' => 'mdlogin'));
					}

				}
			}
		}
	}

	/*
    Function Name : mndlogin
    Desc : For patron mndlogin login method
   */

   function mndlogin($library = null){
       
       
      //code to check the library is inactive or not. if library is inactive then redirect user to library inactive page
    if($library){            
        $library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
        if(count($library_data) > 0)
        {
            if($library_data['Library']['library_status'] == 'inactive'){
                $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/libinactive'); 
                exit;                           
            }
        }
    }  
       
       
    $this->Session->write("layout_option", 'login');
		if($this->Session->read('login_action'))
		{
			if($this->action != $this->Session->read('login_action'))
			{
				$this->Session->destroy('referral');
				$this->Session->destroy('subdomain');
				$this->Session->destroy('login_action');
			}
		}
		if(!$this->Session->read('referral') && !$this->Session->read("subdomain")){
			if(isset($_SERVER['HTTP_REFERER']) && $library == null){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
						$this->Session->write("login_action",'mndlogin');
					}
				}
				else {
					$wrongReferral = 1;
					$data['wrongReferral'] = $wrongReferral;
				}
			}
			else if($library != null)
			{
				$library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
				$this->get_login_layout_name($library_data);
				if(count($library_data) > 0)
				{
					if($this->Session->read('lId') == '')
					{
						$this->Session->write("subdomain",$library);
						$this->Session->write("lId",$library_data['Library']['id']);
					}
				}
				else
				{
					$wrongReferral = 1;
				}
			}
		}
		if($this->Session->read('layout_option') == 'login'){
			$this->layout = 'login';
		}
		else{
			$this->layout = 'login';
		}

		if(isset($this->params['form']['lang'])){
			$language = $this->params['form']['lang'];
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
			$card = str_replace(" ","",$card);
			$card = strtolower($card);
			$data['card'] = $card;
			if($card != ''){
				   $this->set('card',$card);
				}
			else{
				   $this->set('card',"");
			}
			$patronId = $card;
			$data['patronId'] = $patronId;
			if($card == ''){
				$this -> Session -> setFlash("Please provide card number.");
				if($name != ''){
				   $this->set('name',$name);
				}
				else{
				   $this->set('name',"");
				}
			}
			else{
				$cardNo = substr($card,0,5);
				$data['cardNo'] = $cardNo;
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				$data['referral'] = $this->Session->read('referral');
				$data['subdomain']=$this->Session->read("subdomain");
				if($this->Session->read('referral') || $this->Session->read("subdomain")){
					$library_cond = $this->Session->read('lId');
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
															'conditions' => array('library_status' => 'active','library_authentication_method' => 'mndlogin_reference','id' => $library_cond),
															'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
															)
													 );
				}
				else {
					$library_cond = '';
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'mndlogin_reference'),
														'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
														)
													 );
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "https://".$_SERVER['HTTP_HOST']."/users/mndlogin"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
				   $this->redirect(array('controller' => 'users', 'action' => 'mndlogin'));
				}
				else{

				$login_res = $this->Card->find('first',array('conditions' => array('Card.card_number' => $card , 'Card.library_id' => $existingLibraries['0']['Library']['id'] ) , 'fields' => array('id')));
					if(isset($login_res['Card']['id'])) {
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
						$date = time();
						$values = array(0 => $date, 1 => session_id());
						Cache::write("login_".$existingLibraries['0']['Library']['library_territory']."_".$existingLibraries['0']['Library']['id']."_".$patronId, $values);
						$this->Session->write("library", $existingLibraries['0']['Library']['id']);
                                                $this->Session->write("loginchk", 'Yes');
                                                
                                                //check this library exist is in the library timezone table
                                                $countLibPicksSql ='select count(*) as total from libraries_timezone  where library_id = "'.$this->Session->read("library").'"';
                                                $libPicksRecord = $this->LibrariesTimezone->query($countLibPicksSql);
                                                if(isset($libPicksRecord[0][0]['total']) && ($libPicksRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("isLibaryExistInTimzone", 1);                               
                                                }else{
                                                $this->Session->write("isLibaryExistInTimzone", 0);                               
                                                }        

                                                //check if the notification entry is already there in the notification_subscription table
                                                $notificationSql ='select count(*) as total from notification_subscriptions  where patron_id ="'.$patronId.'" and library_id = "'.$this->Session->read("library").'"';
                                                $emailNotificationRecord = $this->NotificationSubscriptions->query($notificationSql);
                                                if(isset($emailNotificationRecord[0][0]['total']) && ($emailNotificationRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("showNotificationPopup", 'yes');                               
                                                }else{
                                                $this->Session->write("showNotificationPopup", 'no');                               
                                                }
                                                
						$this->Session->write("patron", $patronId);
						$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
						$this->Session->write("mndlogin_reference","mndlogin_reference");
						if($existingLibraries['0']['Library']['library_logout_url'] != '' && ($this->Session->read('referral') != '' || $this->Session->read("subdomain") != '')){
							$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
						}
						if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
							$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
						}
						$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));
						$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
						if($existingLibraries['0']['Library']['library_type'] == 2){
							$this->Session->write("streamPopupShow", $isApproved['Currentpatron']['stream_popup']);
							$this->Session->write("userlogin", 'no');
						}
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
                                                if ($this->Session->read('UrlReferer') != '') {
                                                      $UrlReferer = $this->Session->read('UrlReferer');
                                                      $this->Session->delete('UrlReferer');
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .$UrlReferer);
                                                } else {
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/index');
                                                }
					}
					else
					{
						$this->Session->setFlash('Invalid Credentials');
						$this->redirect(array('controller' => 'users', 'action' => 'mndlogin'));
					}

				}
			}
		}
	}


   /*
    Function Name : ildlogin
    Desc : For patron ildlogin(Innovative Var with Name) login method
   */

   function ildlogin($library = null){
    
        //code to check the library is inactive or not. if library is inactive then redirect user to library inactive page
    if($library){            
        $library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
        if(count($library_data) > 0)
        {
            if($library_data['Library']['library_status'] == 'inactive'){
                $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/libinactive'); 
                exit;                           
            }
        }
    }
       
       
       
       
       
       
       
       $this->Session->write("layout_option", 'login');
		if($this->Session->read('login_action'))
		{
			if($this->action != $this->Session->read('login_action'))
			{
				$this->Session->destroy('referral');
				$this->Session->destroy('subdomain');
				$this->Session->destroy('login_action');
			}
		}
		if(!$this->Session->read('referral') && !$this->Session->read("subdomain")){
			if(isset($_SERVER['HTTP_REFERER']) && $library == null){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
						$this->Session->write("login_action",'ildlogin');
					}
				}
				else {
					$wrongReferral = 1;
					$data['wrongReferral'] = $wrongReferral;
				}
			}
			else if($library != null)
			{
				$library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
				$this->get_login_layout_name($library_data);
				if(count($library_data) > 0)
				{
					if($this->Session->read('lId') == '')
					{
						$this->Session->write("subdomain",$library);
						$this->Session->write("lId",$library_data['Library']['id']);
					}
				}
				else
				{
					$wrongReferral = 1;
				}
			}
		}
		if($this->Session->read('layout_option') == 'login'){
			$this->layout = 'login';
		}
		else{
			$this->layout = 'login';
		}

		if(isset($this->params['form']['lang'])){
			$language = $this->params['form']['lang'];
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
			$card = str_replace(" ","",$card);
			$card = strtolower($card);
			$data['card'] = $card;
			$name = $this->data['User']['name'];
			$data['name'] = $name;
			$patronId = $card;
			$data['patronId'] = $patronId;
			if($card == ''){
				$this -> Session -> setFlash("Please provide card number.");
				if($name != ''){
				   $this->set('name',$name);
				}
				else{
				   $this->set('name',"");
				}
			}
			elseif(strlen($card) < $library_data['Library']['minimum_card_length']){
				$this->Session->setFlash("Please provide a correct card number.");			
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
				$data['cardNo'] = $cardNo;
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				$data['referral'] = $this->Session->read('referral');
				$data['subdomain']=$this->Session->read("subdomain");
				if($this->Session->read('referral') || $this->Session->read("subdomain")){
					$library_cond = $this->Session->read('lId');
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
															'conditions' => array('library_status' => 'active','library_authentication_method' => 'innovative_var_name','id' => $library_cond),
															'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
															)
													 );
				}
				else {
					$library_cond = '';
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'innovative_var_name'),
														'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
														)
													 );
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "https://".$_SERVER['HTTP_HOST']."/users/ildlogin"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
				   $this->redirect(array('controller' => 'users', 'action' => 'ildlogin'));
				}
				else{
					$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];
					$url = $authUrl."/PATRONAPI/".$card."/dump";
					$data['url'] = $url;
					$data['database'] = 'freegal';
					if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
						$authUrl = Configure::read('App.AuthUrl_AU')."ildlogin_validation";
					}
					else{
						$authUrl = Configure::read('App.AuthUrl')."ildlogin_validation";
					}
					$result = $this->AuthRequest->getAuthResponse($data,$authUrl);
					$resultAnalysis[0] = $result['Posts']['status'];
					$resultAnalysis[1] = $result['Posts']['message'];
					if($resultAnalysis[0] == "fail"){
						$this->Session->setFlash($resultAnalysis[1]);
						$this->redirect(array('controller' => 'users', 'action' => 'ildlogin'));
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
					$date = time();
					$values = array(0 => $date, 1 => session_id());
					Cache::write("login_".$existingLibraries['0']['Library']['library_territory']."_".$existingLibraries['0']['Library']['id']."_".$patronId, $values);	
					$this->Session->write("library", $existingLibraries['0']['Library']['id']);
                                        $this->Session->write("loginchk", 'Yes');
                                        
                                        //check this library exist is in the library timezone table
                                        $countLibPicksSql ='select count(*) as total from libraries_timezone  where library_id = "'.$this->Session->read("library").'"';
                                        $libPicksRecord = $this->LibrariesTimezone->query($countLibPicksSql);
                                        if(isset($libPicksRecord[0][0]['total']) && ($libPicksRecord[0][0]['total'] > 0 )){
                                        $this->Session->write("isLibaryExistInTimzone", 1);                               
                                        }else{
                                        $this->Session->write("isLibaryExistInTimzone", 0);                               
                                        }        

                                        //check if the notification entry is already there in the notification_subscription table
                                        $notificationSql ='select count(*) as total from notification_subscriptions  where patron_id ="'.$patronId.'" and library_id = "'.$this->Session->read("library").'"';
                                        $emailNotificationRecord = $this->NotificationSubscriptions->query($notificationSql);
                                        if(isset($emailNotificationRecord[0][0]['total']) && ($emailNotificationRecord[0][0]['total'] > 0 )){
                                        $this->Session->write("showNotificationPopup", 'yes');                               
                                        }else{
                                        $this->Session->write("showNotificationPopup", 'no');                               
                                        }                                        
                                        
					$this->Session->write("patron", $patronId);
					$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
					$this->Session->write("innovative_var_name","innovative_var_name");
					if($existingLibraries['0']['Library']['library_logout_url'] != '' && ($this->Session->read('referral') != '' || $this->Session->read("subdomain") != '')){
						$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
					}
					if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
						$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
					}
					$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));
					$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
					if($existingLibraries['0']['Library']['library_type'] == 2){
						$this->Session->write("streamPopupShow", $isApproved['Currentpatron']['stream_popup']);
						$this->Session->write("userlogin", 'no');
					}
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
                                        if ($this->Session->read('UrlReferer') != '') {
                                              $UrlReferer = $this->Session->read('UrlReferer');
                                              $this->Session->delete('UrlReferer');
                                              $this->redirect('http://'.$_SERVER['HTTP_HOST'] .$UrlReferer);
                                        } else {
                                              $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/index');
                                        }
					}
				}
			}
		}
	}

   /*
    Function Name : inlogin
    Desc : For patron inlogin(Innovative w/o PIN) login method
   */
   
  function inlogin($library = null){
            
            
            
             //code to check the library is inactive or not. if library is inactive then redirect user to library inactive page
    if($library){            
        $library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
        if(count($library_data) > 0)
        {
            if($library_data['Library']['library_status'] == 'inactive'){
                $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/libinactive'); 
                exit;                           
            }
        }
    }
    $this->Session->write("layout_option", 'login');
		if($this->Session->read('login_action'))
		{
			if($this->action != $this->Session->read('login_action'))
			{
				$this->Session->destroy('referral');
				$this->Session->destroy('subdomain');
				$this->Session->destroy('login_action');
			}
		}
		if(!$this->Session->read('referral') && !$this->Session->read("subdomain")){
			if(isset($_SERVER['HTTP_REFERER']) && $library == null){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
						$this->Session->write('login_action','inlogin');
					}
				}
				else {
					$wrongReferral = 1;
					$data['wrongReferral'] =$wrongReferral;
				}
			}
			else if($library != null)
			{
				$library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
				$this->get_login_layout_name($library_data);
				if(count($library_data) > 0)
				{
					if($this->Session->read('lId') == '')
					{
						$this->Session->write("subdomain",$library);
						$this->Session->write("lId",$library_data['Library']['id']);
					}
				}
				else
				{
					$wrongReferral = 1;
				}
			}
		}
		if($this->Session->read('layout_option') == 'login'){
			$this->layout = 'login';
		}
		else{
			$this->layout = 'login';
		}

		if(isset($this->params['form']['lang'])){
			$language = $this->params['form']['lang'];
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
			$card = str_replace(" ","",$card);
			$card = strtolower($card);
			$data['card'] = $card;
			$patronId = $card;
			$data['patronId'] = $patronId;
			if($card == ''){
				$this -> Session -> setFlash("Please provide card number.");
			}
         
                        elseif(strlen($card) < $library_data['Library']['minimum_card_length']){
				$this->Session->setFlash("Please provide a correct card number.");			
			}
			else{
				$cardNo = substr($card,0,5);
				$data['cardNo'] = $cardNo;
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				$data['subdomain']=$this->Session->read("subdomain");
				if($this->Session->read('referral') || $this->Session->read("subdomain")){
					$library_cond = $this->Session->read('lId');
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
												'conditions' => array('library_status' => 'active','library_authentication_method' => 'innovative_wo_pin','id' => $library_cond),
												'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
												)
											 );

				}
				else {
					$library_cond = '';
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
												'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'innovative_wo_pin'),
												'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
												)
											 );
				}

				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "https://".$_SERVER['HTTP_HOST']."/users/inlogin"){
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
					$data['database'] = 'freegal';
					if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
						$authUrl = Configure::read('App.AuthUrl_AU')."inlogin_validation";
					}
					else{
						$authUrl = Configure::read('App.AuthUrl')."inlogin_validation";
					}
					$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

					$resultAnalysis[0] = $result['Posts']['status'];
					$resultAnalysis[1] = $result['Posts']['message'];
					if($resultAnalysis[0] == "fail"){
						$this->Session->setFlash($resultAnalysis[1]);
						$this->redirect(array('controller' => 'users', 'action' => 'inlogin'));
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
						$date = time();
						$values = array(0 => $date, 1 => session_id());
						Cache::write("login_".$existingLibraries['0']['Library']['library_territory']."_".$existingLibraries['0']['Library']['id']."_".$patronId, $values);
					
						$this->Session->write("library", $existingLibraries['0']['Library']['id']);
                                                $this->Session->write("loginchk", 'Yes');
                                                
                                                //check this library exist is in the library timezone table
                                                $countLibPicksSql ='select count(*) as total from libraries_timezone  where library_id = "'.$this->Session->read("library").'"';
                                                $libPicksRecord = $this->LibrariesTimezone->query($countLibPicksSql);
                                                if(isset($libPicksRecord[0][0]['total']) && ($libPicksRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("isLibaryExistInTimzone", 1);                               
                                                }else{
                                                $this->Session->write("isLibaryExistInTimzone", 0);                               
                                                }        

                                                //check if the notification entry is already there in the notification_subscription table
                                                $notificationSql ='select count(*) as total from notification_subscriptions  where patron_id ="'.$patronId.'" and library_id = "'.$this->Session->read("library").'"';
                                                $emailNotificationRecord = $this->NotificationSubscriptions->query($notificationSql);
                                                if(isset($emailNotificationRecord[0][0]['total']) && ($emailNotificationRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("showNotificationPopup", 'yes');                               
                                                }else{
                                                $this->Session->write("showNotificationPopup", 'no');                               
                                                }
                                                
						$this->Session->write("patron", $patronId);
						$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
						$this->Session->write("innovative_wo_pin","innovative_wo_pin");
						if($existingLibraries['0']['Library']['library_logout_url'] != '' && ($this->Session->read('referral') != '' || $this->Session->read("subdomain") != '')){
							$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
						}
						if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
							$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
						}
						$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));
						$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
						if($existingLibraries['0']['Library']['library_type'] == 2){
							$this->Session->write("streamPopupShow", $isApproved['Currentpatron']['stream_popup']);
							$this->Session->write("userlogin", 'no');
						}
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
                                                if ($this->Session->read('UrlReferer') != '') {
                                                      $UrlReferer = $this->Session->read('UrlReferer');
                                                      $this->Session->delete('UrlReferer');
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .$UrlReferer);
                                                } else {
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/index');
                                                }
					}
				}
			}
		}
	}

   /*
    Function Name : indlogin
    Desc : For patron indlogin(Innovative Var w/o Pin) login method
   */

   function indlogin($library = null){
       
       
  
        //code to check the library is inactive or not. if library is inactive then redirect user to library inactive page
    if($library){            
        $library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
        if(count($library_data) > 0)
        {
            if($library_data['Library']['library_status'] == 'inactive'){
                $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/libinactive'); 
                exit;                           
            }
        }
    }
       
       
       
       
    $this->Session->write("layout_option", 'login');
		if($this->Session->read('login_action'))
		{
			if($this->action != $this->Session->read('login_action'))
			{
				$this->Session->destroy('referral');
				$this->Session->destroy('subdomain');
				$this->Session->destroy('login_action');
			}
		}
		if(!$this->Session->read('referral') && !$this->Session->read("subdomain")){
			if(isset($_SERVER['HTTP_REFERER']) && $library == null){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
						$this->Session->write("login_action",'indlogin');
					}
				}
				else {
					$wrongReferral = 1;
					$data['wrongReferral'] = $wrongReferral;
				}
			}
			else if($library != null)
			{
				$library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
				$this->get_login_layout_name($library_data);
				if(count($library_data) > 0)
				{
					if($this->Session->read('lId') == '')
					{
						$this->Session->write("subdomain",$library);
						$this->Session->write("lId",$library_data['Library']['id']);
					}
				}
				else
				{
					$wrongReferral = 1;
				}
			}
		}
		if($this->Session->read('layout_option') == 'login'){
			$this->layout = 'login';
		}
		else{
			$this->layout = 'login';
		}

		if(isset($this->params['form']['lang'])){
			$language = $this->params['form']['lang'];
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
			$card = str_replace(" ","",$card);
			$card = strtolower($card);
			$data['card'] = $card;
			$patronId = $card;
			$data['patronId'] = $card;
			if($card == ''){
				$this -> Session -> setFlash("Please provide card number.");
			}
			elseif(strlen($card) < $library_data['Library']['minimum_card_length']){
				$this->Session->setFlash("Please provide a correct card number.");			
			}			
			else{				
				$cardNo = substr($card,0,5);
				$data['cardNo'] = $cardNo;
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				$data['subdomain']=$this->Session->read("subdomain");
				if($this->Session->read('referral') || $this->Session->read("subdomain")){
					$library_cond = $this->Session->read('lId');
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_status' => 'active','library_authentication_method' => 'innovative_var_wo_pin','id' => $library_cond),
														'fields' => array('Library.id','Library.library_territory','Library.library_logout_url','Library.library_authentication_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type','Library.library_type')
														)
													 );
				} else {
					$library_cond = '';
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'innovative_var_wo_pin'),
														'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type','Library.library_type')
														)
													 );
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "https://".$_SERVER['HTTP_HOST']."/users/indlogin"){
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
					$data['database'] = 'freegal';
					if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
						$authUrl = Configure::read('App.AuthUrl_AU')."indlogin_validation";
					}
					else{
						$authUrl = Configure::read('App.AuthUrl')."indlogin_validation";
					}
					$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

					$resultAnalysis[0] = $result['Posts']['status'];
					$resultAnalysis[1] = $result['Posts']['message'];
					if($resultAnalysis[0] == "fail"){
						$this->Session->setFlash($resultAnalysis[1]);
						$this->redirect(array('controller' => 'users', 'action' => 'indlogin'));
					} elseif($resultAnalysis[0] == "success"){
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
						$date = time();
						$values = array(0 => $date, 1 => session_id());
						Cache::write("login_".$existingLibraries['0']['Library']['library_territory']."_".$existingLibraries['0']['Library']['id']."_".$patronId, $values);
					
						$this->Session->write("library", $existingLibraries['0']['Library']['id']);
                                                $this->Session->write("loginchk", 'Yes');
                                                
                                                //check this library exist is in the library timezone table
                                                $countLibPicksSql ='select count(*) as total from libraries_timezone  where library_id = "'.$this->Session->read("library").'"';
                                                $libPicksRecord = $this->LibrariesTimezone->query($countLibPicksSql);
                                                if(isset($libPicksRecord[0][0]['total']) && ($libPicksRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("isLibaryExistInTimzone", 1);                               
                                                }else{
                                                $this->Session->write("isLibaryExistInTimzone", 0);                               
                                                }        

                                                //check if the notification entry is already there in the notification_subscription table
                                                $notificationSql ='select count(*) as total from notification_subscriptions  where patron_id ="'.$patronId.'" and library_id = "'.$this->Session->read("library").'"';
                                                $emailNotificationRecord = $this->NotificationSubscriptions->query($notificationSql);
                                                if(isset($emailNotificationRecord[0][0]['total']) && ($emailNotificationRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("showNotificationPopup", 'yes');                               
                                                }else{
                                                $this->Session->write("showNotificationPopup", 'no');                               
                                                }
                                                
						$this->Session->write("patron", $patronId);
						$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
						$this->Session->write("innovative_var_wo_pin","innovative_var_wo_pin");
						if($existingLibraries['0']['Library']['library_logout_url'] != '' && ($this->Session->read('referral') != '' || $this->Session->read("subdomain") != '')){
							$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
						}
						if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
							$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
						}
						$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));
						$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
						if($existingLibraries['0']['Library']['library_type'] == 2) {
							$this->Session->write("streamPopupShow", $isApproved['Currentpatron']['stream_popup']);
							$this->Session->write("userlogin", 'no');
						}
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
                                                if ($this->Session->read('UrlReferer') != '') {
                                                      $UrlReferer = $this->Session->read('UrlReferer');
                                                      $this->Session->delete('UrlReferer');
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .$UrlReferer);
                                                } else {
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/index');
                                                }
					}
				}
			}
		}
	}

	/*
		Function Name : slogin
		Desc : For patron slogin(SIP2 Authentication) login method
	*/


	function slogin($library = null){
            
            
        //code to check the library is inactive or not. if library is inactive then redirect user to library inactive page
        if($library){            
            $library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
            if(count($library_data) > 0)
            {
                if($library_data['Library']['library_status'] == 'inactive'){
                    $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/libinactive'); 
                    exit;                           
                }
            }
        }
    
    
    $this->Session->write("layout_option", 'login');
		if($this->Session->read('login_action'))
		{
			if($this->action != $this->Session->read('login_action'))
			{
				$this->Session->destroy('referral');
				$this->Session->destroy('subdomain');
				$this->Session->destroy('login_action');
			}
		}
		if(!$this->Session->read('referral') && !$this->Session->read("subdomain")){
			if(isset($_SERVER['HTTP_REFERER']) && $library == null){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
						$this->Session->write("login_action",'slogin');
					}
				}
				else {
					$wrongReferral = 1;
					$data['wrongReferral'] = 1;
				}
			}
			else if($library != null)
			{
				$library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
				$this->get_login_layout_name($library_data);
				if(count($library_data) > 0)
				{
					if($this->Session->read('lId') == '')
					{
						$this->Session->write("subdomain",$library);
						$this->Session->write("lId",$library_data['Library']['id']);
					}
				}
				else
				{
					$wrongReferral = 1;
				}
			}
		}
		if($this->Session->read('layout_option') == 'login'){
			$this->layout = 'login';
		}
		else{
			$this->layout = 'login';
		}

		if(isset($this->params['form']['lang'])){
			$language = $this->params['form']['lang'];
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
			$card = str_replace(" ","",$card);
			$card = strtolower($card);
			$data['card'] = $card;
			$pin = $this->data['User']['pin'];
			$data['pin'] = $pin;
			$patronId = $card;
			$data['patronId'] = $patronId;
			if($card == ''){
				$this -> Session -> setFlash("Please provide card number.");

				if($pin != ''){
				   $this->set('pin',$pin);
				}
				else{
				   $this->set('pin',"");
				}
			}
                        
                        elseif(strlen($card) < $library_data['Library']['minimum_card_length']){
				$this->Session->setFlash("Please provide a correct card number.");			
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
				$data['cardNo'] = $cardNo;
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				$data['subdomain']=$this->Session->read("subdomain");
				if($this->Session->read('referral') || $this->Session->read("subdomain")){
					$library_cond = $this->Session->read('lId');
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_status' => 'active','library_authentication_method' => 'sip2','id' => $library_cond),
														'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
														)
													 );
				} else {
					$library_cond = '';
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
													'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'sip2'),
													'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
													)
												 );
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "https://".$_SERVER['HTTP_HOST']."/users/slogin"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
					$this->redirect(array('controller' => 'users', 'action' => 'slogin'));
				}
				else{
						if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
							$authUrl = Configure::read('App.AuthUrl_AU')."slogin_validation";
						}
						else{
							$authUrl = Configure::read('App.AuthUrl')."slogin_validation";
						}
						$data['database'] = 'freegal';
						$result = $this->AuthRequest->getAuthResponse($data,$authUrl);

						$resultAnalysis[0] = $result['Posts']['status'];
						$resultAnalysis[1] = $result['Posts']['message'];
						if($resultAnalysis[0] == "fail"){
							$this->Session->setFlash($resultAnalysis[1]);
							$this->redirect(array('controller' => 'users', 'action' => 'slogin'));
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
								$date = time();
								$values = array(0 => $date, 1 => session_id());
								Cache::write("login_".$existingLibraries['0']['Library']['library_territory']."_".$existingLibraries['0']['Library']['id']."_".$patronId, $values);

								$this->Session->write("library", $existingLibraries['0']['Library']['id']);
                                                                $this->Session->write("loginchk", 'Yes');
                                                                
                                                                //check this library exist is in the library timezone table
                                                                $countLibPicksSql ='select count(*) as total from libraries_timezone  where library_id = "'.$this->Session->read("library").'"';
                                                                $libPicksRecord = $this->LibrariesTimezone->query($countLibPicksSql);
                                                                if(isset($libPicksRecord[0][0]['total']) && ($libPicksRecord[0][0]['total'] > 0 )){
                                                                $this->Session->write("isLibaryExistInTimzone", 1);                               
                                                                }else{
                                                                $this->Session->write("isLibaryExistInTimzone", 0);                               
                                                                }        

                                                                //check if the notification entry is already there in the notification_subscription table
                                                                $notificationSql ='select count(*) as total from notification_subscriptions  where patron_id ="'.$patronId.'" and library_id = "'.$this->Session->read("library").'"';
                                                                $emailNotificationRecord = $this->NotificationSubscriptions->query($notificationSql);
                                                                if(isset($emailNotificationRecord[0][0]['total']) && ($emailNotificationRecord[0][0]['total'] > 0 )){
                                                                $this->Session->write("showNotificationPopup", 'yes');                               
                                                                }else{
                                                                $this->Session->write("showNotificationPopup", 'no');                               
                                                                }
                                                                
								$this->Session->write("patron", $patronId);
								$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
								$this->Session->write("sip2","sip2");
								if($existingLibraries['0']['Library']['library_logout_url'] != '' && ($this->Session->read('referral') != '' || $this->Session->read("subdomain") != '')){
									$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
								}
								if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
									$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
								}
								$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));
								$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
								if($existingLibraries['0']['Library']['library_type'] == 2){
									$this->Session->write("streamPopupShow", $isApproved['Currentpatron']['stream_popup']);
									$this->Session->write("userlogin", 'no');
								}
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
                                                                if ($this->Session->read('UrlReferer') != '') {
                                                                      $UrlReferer = $this->Session->read('UrlReferer');
                                                                      $this->Session->delete('UrlReferer');
                                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .$UrlReferer);
                                                                } else {
                                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/index');
                                                                }
								$this->Auth->autoRedirect = false;
						}
				}
			}
		}
	}

	/*
		Function Name : snlogin
		Desc : For patron snlogin(SIP2 Authentication) login method without the pin no
	*/


	function snlogin($library = null){
            
                
           if($library){            
                $library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
                if(count($library_data) > 0)
                {
                    if($library_data['Library']['library_status'] == 'inactive'){
                        $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/libinactive'); 
                        exit;                           
                    }
                }
           }
                       
            $this->Session->write("layout_option", 'login');
            if($this->Session->read('login_action'))
            {
                    if($this->action != $this->Session->read('login_action'))
                    {
                            $this->Session->destroy('referral');
                            $this->Session->destroy('subdomain');
                            $this->Session->destroy('login_action');
                    }
            }
		if(!$this->Session->read('referral') && !$this->Session->read("subdomain")){ 
			if(isset($_SERVER['HTTP_REFERER']) && $library == null){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
						$this->Session->write("login_action",'snlogin');
					}
				}
				else {
					$wrongReferral = 1;
					$data['wrongReferral'] =$wrongReferral;
				}
			}
			else if($library != null)
			{
				$library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
				$this->get_login_layout_name($library_data);
				if(count($library_data) > 0)
				{
					if($library_data['Library']['library_status'] == 'inactive'){
                                            $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/libinactive'); 
                                            exit;                           
                                        }
                                    
                                        if($this->Session->read('lId') == '')
					{
						$this->Session->write("subdomain",$library);
						$this->Session->write("lId",$library_data['Library']['id']);
					}
				}
				else
				{
					$wrongReferral = 1;
				}
			}
		}
		if($this->Session->read('layout_option') == 'login'){
			$this->layout = 'login';
		}
		else{
			$this->layout = 'login';
		}

		if(isset($this->params['form']['lang'])){
			$language = $this->params['form']['lang'];
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
			$card = str_replace(" ","",$card);
			$card = strtolower($card);
			$data['card'] = $card;
			$patronId = $card;
			$data['patronId'] = $patronId;
			if($card == ''){
				$this -> Session -> setFlash("Please provide card number.");
			}     
                        elseif(strlen($card) < $library_data['Library']['minimum_card_length']){
				$this->Session->setFlash("Please provide a correct card number.");			
			}
			else{
				$cardNo = substr($card,0,5);
				$data['cardNo'] = $cardNo;
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				$data['subdomain']=$this->Session->read("subdomain");
				if($this->Session->read('referral') || $this->Session->read("subdomain")){
					$library_cond = $this->Session->read('lId');
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
													'conditions' => array('library_status' => 'active','library_authentication_method' => 'sip2_wo_pin','id' => $library_cond),
													'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
													)
												 );
				} else {
					$library_cond = '';
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
													'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'sip2_wo_pin'),
													'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
													)
												 );
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "https://".$_SERVER['HTTP_HOST']."/users/snlogin"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
					$this->redirect(array('controller' => 'users', 'action' => 'snlogin'));
				}
				else{
						if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
							$authUrl = Configure::read('App.AuthUrl_AU')."snlogin_validation";
						}
						else{
							$authUrl = Configure::read('App.AuthUrl')."snlogin_validation";
						}
						$data['database'] = 'freegal';
						$result = $this->AuthRequest->getAuthResponse($data,$authUrl);
						if($result){
						$resultAnalysis[0] = $result['Posts']['status'];
						$resultAnalysis[1] = $result['Posts']['message'];
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
								$date = time();
								$values = array(0 => $date, 1 => session_id());
								Cache::write("login_".$existingLibraries['0']['Library']['library_territory']."_".$existingLibraries['0']['Library']['id']."_".$patronId, $values);

								  $this->Session->write("library", $existingLibraries['0']['Library']['id']);
                                                                  $this->Session->write("loginchk", 'Yes');
                                                                  
                                                                  //check this library exist is in the library timezone table
                                                                $countLibPicksSql ='select count(*) as total from libraries_timezone  where library_id = "'.$this->Session->read("library").'"';
                                                                $libPicksRecord = $this->LibrariesTimezone->query($countLibPicksSql);
                                                                if(isset($libPicksRecord[0][0]['total']) && ($libPicksRecord[0][0]['total'] > 0 )){
                                                                $this->Session->write("isLibaryExistInTimzone", 1);                               
                                                                }else{
                                                                $this->Session->write("isLibaryExistInTimzone", 0);                               
                                                                }        

                                                                //check if the notification entry is already there in the notification_subscription table
                                                                $notificationSql ='select count(*) as total from notification_subscriptions  where patron_id ="'.$patronId.'" and library_id = "'.$this->Session->read("library").'"';
                                                                $emailNotificationRecord = $this->NotificationSubscriptions->query($notificationSql);
                                                                if(isset($emailNotificationRecord[0][0]['total']) && ($emailNotificationRecord[0][0]['total'] > 0 )){
                                                                $this->Session->write("showNotificationPopup", 'yes');                               
                                                                }else{
                                                                $this->Session->write("showNotificationPopup", 'no');                               
                                                                }
                                                                  
								  $this->Session->write("patron", $patronId);
								  $this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
								  $this->Session->write("sip","sip");
								  if($existingLibraries['0']['Library']['library_logout_url'] != '' && ($this->Session->read('referral') != '' || $this->Session->read("subdomain") != '')){
									  $this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
								  }
								  if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
									  $this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
								  }
								  $isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));
								  $this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
								if($existingLibraries['0']['Library']['library_type'] == 2){
									$this->Session->write("streamPopupShow", $isApproved['Currentpatron']['stream_popup']);
									$this->Session->write("userlogin", 'no');
								}
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
                                                                    if ($this->Session->read('UrlReferer') != '') {
                                                                          $UrlReferer = $this->Session->read('UrlReferer');
                                                                          $this->Session->delete('UrlReferer');
                                                                          $this->redirect('http://'.$_SERVER['HTTP_HOST'] .$UrlReferer);
                                                                    } else {
                                                                          $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/index');
                                                                    }

						}
					}else{
						$this -> Session -> setFlash("Authentication server down.");
					}
				}
			}
		}
	}
	/*
		Function Name : sdlogin
		Desc : For patron sdlogin(SIP2 Var) login method
	*/

	function sdlogin($library = null){
            
        //code to check the library is inactive or not. if library is inactive then redirect user to library inactive page
        if($library){            
            $library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
            if(count($library_data) > 0)
            {
                if($library_data['Library']['library_status'] == 'inactive'){
                    $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/libinactive'); 
                    exit;                           
                }
            }
        } 
            
    $this->Session->write("layout_option", 'login');
		if($this->Session->read('login_action'))
		{
			if($this->action != $this->Session->read('login_action'))
			{
				$this->Session->destroy('referral');
				$this->Session->destroy('subdomain');
				$this->Session->destroy('login_action');
			}
		}
		if(!$this->Session->read('referral') && !$this->Session->read("subdomain")){
			if(isset($_SERVER['HTTP_REFERER']) && $library == null){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
						$this->Session->write('login_action','sdlogin');
					}
				}
				else {
					$wrongReferral = 1;
					$data['wrongReferral'] = 1;
				}
			}
			else if($library != null)
			{
				$library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
				$this->get_login_layout_name($library_data);
				if(count($library_data) > 0)
				{
					if($this->Session->read('lId') == '')
					{
						$this->Session->write("subdomain",$library);
						$this->Session->write("lId",$library_data['Library']['id']);
					}
				}
				else
				{
					$wrongReferral = 1;
				}
			}
		}

		if($this->Session->read('layout_option') == 'login'){
			$this->layout = 'login';
		}
		else{
			$this->layout = 'login';
		}

		if(isset($this->params['form']['lang'])){
			$language = $this->params['form']['lang'];
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
			$data['card_orig'] = $card;
			$card = str_replace(" ","",$card);

			$data['card'] = $card;
			$pin = $this->data['User']['pin'];
			$data['pin'] = $pin;
			$patronId = $card;
			$data['patronId'] = $patronId;
			if($card == ''){
				$this -> Session -> setFlash("Please provide card number.");

				if($pin != ''){
				   $this->set('pin',$pin);
				}
				else{
				   $this->set('pin',"");
				}
			}
                        elseif(strlen($card) < $library_data['Library']['minimum_card_length']){
				$this->Session->setFlash("Please provide a correct card number.");			
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
				$data['cardNo'] = $cardNo;
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				$data['referral'] = $this->Session->read('referral');
				$data['subdomain']=$this->Session->read("subdomain");
				if($this->Session->read('referral') || $this->Session->read("subdomain")){
					$library_cond = $this->Session->read('lId');
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_status' => 'active','library_authentication_method' => 'sip2_var','id' => $library_cond),
														'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_sip_version','Library.library_sip_error','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
														)
													 );
				} else {
					$library_cond = '';
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'sip2_var'),
														'fields' => array('Library.id','Library.library_territory','Library.library_logout_url','Library.library_authentication_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_sip_version','Library.library_sip_error','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
														)
													 );
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "https://".$_SERVER['HTTP_HOST']."/users/sdlogin"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
						}
					$this->redirect(array('controller' => 'users', 'action' => 'sdlogin'));
				}
				else{
						if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
							$authUrl = Configure::read('App.AuthUrl_AU')."sdlogin_validation";
						}
						else{
							$authUrl = Configure::read('App.AuthUrl')."sdlogin_validation";
						}
						$data['database'] = 'freegal';
						$result = $this->AuthRequest->getAuthResponse($data,$authUrl);
						$resultAnalysis[0] = $result['Posts']['status'];
						$resultAnalysis[1] = $result['Posts']['message'];
						if($resultAnalysis[0] == "fail"){
							$this->Session->setFlash($resultAnalysis[1]);
							$this->redirect(array('controller' => 'users', 'action' => 'sdlogin'));
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
							$date = time();
							$values = array(0 => $date, 1 => session_id());
							Cache::write("login_".$existingLibraries['0']['Library']['library_territory']."_".$existingLibraries['0']['Library']['id']."_".$patronId, $values);

							$this->Session->write("library", $existingLibraries['0']['Library']['id']);
                                                        $this->Session->write("loginchk", 'Yes');
                                                        
                                                        //check this library exist is in the library timezone table
                                                        $countLibPicksSql ='select count(*) as total from libraries_timezone  where library_id = "'.$this->Session->read("library").'"';
                                                        $libPicksRecord = $this->LibrariesTimezone->query($countLibPicksSql);
                                                        if(isset($libPicksRecord[0][0]['total']) && ($libPicksRecord[0][0]['total'] > 0 )){
                                                        $this->Session->write("isLibaryExistInTimzone", 1);                               
                                                        }else{
                                                        $this->Session->write("isLibaryExistInTimzone", 0);                               
                                                        }        

                                                        //check if the notification entry is already there in the notification_subscription table
                                                        $notificationSql ='select count(*) as total from notification_subscriptions  where patron_id ="'.$patronId.'" and library_id = "'.$this->Session->read("library").'"';
                                                        $emailNotificationRecord = $this->NotificationSubscriptions->query($notificationSql);
                                                        if(isset($emailNotificationRecord[0][0]['total']) && ($emailNotificationRecord[0][0]['total'] > 0 )){
                                                        $this->Session->write("showNotificationPopup", 'yes');                               
                                                        }else{
                                                        $this->Session->write("showNotificationPopup", 'no');                               
                                                        }
                                                        
							$this->Session->write("patron", $patronId);
							$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
							$this->Session->write("sip2_var","sip2_var");
							if($existingLibraries['0']['Library']['library_logout_url'] != '' && ($this->Session->read('referral') != '' || $this->Session->read("subdomain") != '')){
								$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
							}
							if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
								$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
							}
							$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));
							$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
							if($existingLibraries['0']['Library']['library_type'] == 2){
								$this->Session->write("streamPopupShow", $isApproved['Currentpatron']['stream_popup']);
								$this->Session->write("userlogin", 'no');
							}
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
                                                        if ($this->Session->read('UrlReferer') != '') {
                                                              $UrlReferer = $this->Session->read('UrlReferer');
                                                              $this->Session->delete('UrlReferer');
                                                              $this->redirect('http://'.$_SERVER['HTTP_HOST'] .$UrlReferer);
                                                        } else {
                                                              $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/index');
                                                        }
						}
				}
			}
		}
	}

	/*
		Function Name : sndlogin
		Desc : For patron sndlogin(SIP2 Var w/o Pin) login method
	*/

	function sndlogin($library = null){
             //code to check the library is inactive or not. if library is inactive then redirect user to library inactive page
        if($library){            
            $library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
            if(count($library_data) > 0)
            {
                if($library_data['Library']['library_status'] == 'inactive'){
                    $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/libinactive'); 
                    exit;                           
                }
            }
        }
            
            
            
    $this->Session->write("layout_option", 'login');
		if($this->Session->read('login_action'))
		{
			if($this->action != $this->Session->read('login_action'))
			{
				$this->Session->destroy('referral');
				$this->Session->destroy('subdomain');
				$this->Session->destroy('login_action');
			}
		}
		if(!$this->Session->read('referral') && !$this->Session->read("subdomain")){
			if(isset($_SERVER['HTTP_REFERER']) && $library == null){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
						$this->Session->write('login_action','sndlogin');
					}
				}
				else {
					$wrongReferral = 1;

				}
			}
			else if($library != null)
			{
				$library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
				$this->get_login_layout_name($library_data);
				if(count($library_data) > 0)
				{
					if($this->Session->read('lId') == '')
					{
						$this->Session->write("subdomain",$library);
						$this->Session->write("lId",$library_data['Library']['id']);
					}
				}
				else
				{
					$wrongReferral = 1;
				}
			}
		}
		if($this->Session->read('layout_option') == 'login'){
			$this->layout = 'login';
		}
		else{
			$this->layout = 'login';
		}

		if(isset($this->params['form']['lang'])){
			$language = $this->params['form']['lang'];
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
			$data['card_orig'] = $card;
			$card = str_replace(" ","",$card);
			$data['card'] = $card;
			$patronId = $card;
			$data['patronId'] = $patronId;
			if($card == ''){
				$this -> Session -> setFlash("Please provide card number.");
			}
                        
                        elseif(strlen($card) < $library_data['Library']['minimum_card_length']){
				$this->Session->setFlash("Please provide a correct card number.");			
			}
			else{
				$cardNo = substr($card,0,5);
				$data['cardNo'] = $cardNo;
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				$data['referral'] = $this->Session->read('referral');
				$data['subdomain']=$this->Session->read("subdomain");
				if($this->Session->read('referral') || $this->Session->read("subdomain")){
					$library_cond = $this->Session->read('lId');
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
													'conditions' => array('library_status' => 'active','library_authentication_method' => 'sip2_var_wo_pin','id' => $library_cond),
													'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_sip_version','Library.library_sip_error','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
													)
												 );
				} else {
					$library_cond = '';
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
													'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'sip2_var_wo_pin'),
													'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_sip_version','Library.library_sip_error','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
													)
												 );
				}

				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "https://".$_SERVER['HTTP_HOST']."/users/sndlogin"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
					$this->redirect(array('controller' => 'users', 'action' => 'sndlogin'));
				}
				else{
					if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
						$authUrl = Configure::read('App.AuthUrl_AU')."sndlogin_validation";
					}
					else{
						$authUrl = Configure::read('App.AuthUrl')."sndlogin_validation";
					}
					$data['database'] = 'freegal';
					$result = $this->AuthRequest->getAuthResponse($data,$authUrl);
					$resultAnalysis[0] = $result['Posts']['status'];
					$resultAnalysis[1] = $result['Posts']['message'];
					if($resultAnalysis[0] == "fail"){
						$this->Session->setFlash($resultAnalysis[1]);
						$this->redirect(array('controller' => 'users', 'action' => 'sndlogin'));
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
						$date = time();
						$values = array(0 => $date, 1 => session_id());
						Cache::write("login_".$existingLibraries['0']['Library']['library_territory']."_".$existingLibraries['0']['Library']['id']."_".$patronId, $values);

						$this->Session->write("library", $existingLibraries['0']['Library']['id']);
                                                $this->Session->write("loginchk", 'Yes');
                                                
                                                //check this library exist is in the library timezone table
                                                $countLibPicksSql ='select count(*) as total from libraries_timezone  where library_id = "'.$this->Session->read("library").'"';
                                                $libPicksRecord = $this->LibrariesTimezone->query($countLibPicksSql);
                                                if(isset($libPicksRecord[0][0]['total']) && ($libPicksRecord[0][0]['total'] > 0 )){
                                                    $this->Session->write("isLibaryExistInTimzone", 1);                               
                                                }else{
                                                    $this->Session->write("isLibaryExistInTimzone", 0);                               
                                                }        

                                                //check if the notification entry is already there in the notification_subscription table
                                                $notificationSql ='select count(*) as total from notification_subscriptions  where patron_id ="'.$patronId.'" and library_id = "'.$this->Session->read("library").'"';
                                                $emailNotificationRecord = $this->NotificationSubscriptions->query($notificationSql);
                                                if(isset($emailNotificationRecord[0][0]['total']) && ($emailNotificationRecord[0][0]['total'] > 0 )){
                                                    $this->Session->write("showNotificationPopup", 'yes');                               
                                                }else{
                                                    $this->Session->write("showNotificationPopup", 'no');                               
                                                }
                                                
                                                
						$this->Session->write("patron", $patronId);
						$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
						$this->Session->write("sip2_var_wo_pin","sip2_var_wo_pin");
						if($existingLibraries['0']['Library']['library_logout_url'] != '' && ($this->Session->read('referral') != '' || $this->Session->read("subdomain") != '')){
							$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
						}
						if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
							$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
						}
						$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));
						$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
						if($existingLibraries['0']['Library']['library_type'] == 2){
							$this->Session->write("streamPopupShow", $isApproved['Currentpatron']['stream_popup']);
							$this->Session->write("userlogin", 'no');
						}
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

                                                if ($this->Session->read('UrlReferer') != '') {
                                                      $UrlReferer = $this->Session->read('UrlReferer');
                                                      $this->Session->delete('UrlReferer');
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .$UrlReferer);
                                                } else {
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/index');
                                                }                                                

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
		
		if ( $this->RequestHandler->isPost() ) {
			$index = 'form';
		} else if ( $this->RequestHandler->isGet() ) {
			$index = 'url';
		}

		if(isset($this->params[$index]['libname'])){
			$libName = $this->params[$index]['libname'];
			$this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');
			$existingLibraries = $this->Library->find('all',array(
												'conditions' => array('library_ezproxy_name' => $libName,'library_status' => 'active','library_authentication_method' => 'ezproxy'),
												'fields' => array('Library.id','Library.library_territory','Library.library_ezproxy_secret','library_ezproxy_logout','Library.library_ezproxy_referral','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
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
			if($this->Session->read('layout_option') == 'login'){
				$this->layout = 'login';
			}
			else{
				$this->layout = 'login';
			}

			$referral = $this->Session->read('referral');
			$ref = explode("url=",$referral);
			$this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');
			$existingLibraries = $this->Library->find('all',array(
												'conditions' => array('library_ezproxy_referral' => $referral,'library_status' => 'active','library_authentication_method' => 'ezproxy'),
												'fields' => array('Library.id','Library.library_territory','Library.library_ezproxy_secret','Library.library_ezproxy_referral','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
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
			$date = time();
			$values = array(0 => $date, 1 => session_id());
			Cache::write("login_".$existingLibraries['0']['Library']['library_territory']."_".$existingLibraries['0']['Library']['id']."_".$card, $values);
		
			$this->Session->write("library", $existingLibraries['0']['Library']['id']);
                        $this->Session->write("loginchk", 'Yes');
			$this->Session->write("patron", $user);
			$this->Session->write("ezproxy","ezproxy");
			$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
			$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $user)));
			if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
				$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
			}
			$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
			if($existingLibraries['0']['Library']['library_type'] == 2){
				$this->Session->write("streamPopupShow", $isApproved['Currentpatron']['stream_popup']);
				$this->Session->write("userlogin", 'no');
			}
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
                        if ($this->Session->read('UrlReferer') != '') {
                              $UrlReferer = $this->Session->read('UrlReferer');
                              $this->Session->delete('UrlReferer');
                              $this->redirect('http://'.$_SERVER['HTTP_HOST'] .$UrlReferer);
                        } else {
                              $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/index');
                        }
		}
	}
   /*
    Function Name : inhlogin
    Desc : For patron Innovative Https login method
   */

   function inhlogin($library = null){
       
       
    //code to check the library is inactive or not. if library is inactive then redirect user to library inactive page
    if($library){            
        $library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
        if(count($library_data) > 0)
        {
            if($library_data['Library']['library_status'] == 'inactive'){
                $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/libinactive'); 
                exit;                           
            }
        }
    }
       
       
       
       
    $this->Session->write("layout_option", 'login');
		if($this->Session->read('login_action'))
		{
			if($this->action != $this->Session->read('login_action'))
			{
				$this->Session->destroy('referral');
				$this->Session->destroy('subdomain');
				$this->Session->destroy('login_action');
			}
		}
		if(!$this->Session->read('referral') && !$this->Session->read("subdomain")){
			if(isset($_SERVER['HTTP_REFERER']) && $library == null){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
						$this->Session->write("login_action",'inhlogin');
					}
				}
				else {
					$wrongReferral = 1;
					$data['wrongReferral'] = $wrongReferral;
				}
			}
			else if($library != null)
			{
				$library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
				$this->get_login_layout_name($library_data);
				if(count($library_data) > 0)
				{
					if($this->Session->read('lId') == '')
					{
						$this->Session->write("subdomain",$library);
						$this->Session->write("lId",$library_data['Library']['id']);
					}
				}
				else
				{
					$wrongReferral = 1;
				}
			}
		}
		if($this->Session->read('layout_option') == 'login'){
			$this->layout = 'login';
		}
		else{
			$this->layout = 'login';
		}

		if(isset($this->params['form']['lang'])){
			$language = $this->params['form']['lang'];
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
		 $card = str_replace(" ","",$card);
		 $card = strtolower($card);
		 $data['card'] = $card;
         $pin = $this->data['User']['pin'];
		 $data['pin'] = $pin;
         $patronId = $card;
		$data['patronId'] = $patronId;
         if($card == ''){
            $this -> Session -> setFlash("Please provide card number.");
            if($pin != ''){
               $this->set('pin',$pin);
            }
            else{
               $this->set('pin',"");
            }
         }
   
                 elseif(strlen($card) < $library_data['Library']['minimum_card_length']){
			$this->Session->setFlash("Please provide a correct card number.");			
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
				$data['cardNo'] = $cardNo;
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				$data['referral'] = $this->Session->read('referral');
				$data['subdomain']=$this->Session->read("subdomain");
				if($this->Session->read('referral') || $this->Session->read("subdomain")){
					$library_cond = $this->Session->read('lId');
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_status' => 'active','library_authentication_method' => 'innovative_https','id' => $library_cond),
														'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
														)
													 );

				} else {
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'innovative_https'),
														'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
														)
													 );
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "https://".$_SERVER['HTTP_HOST']."/users/inhlogin"){
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
					$data['database'] = 'freegal';
					if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
						$authUrl = Configure::read('App.AuthUrl_AU')."inhlogin_validation";
					}
					else{
						$authUrl = Configure::read('App.AuthUrl')."inhlogin_validation";
					}
					$result = $this->AuthRequest->getAuthResponse($data,$authUrl);
					$resultAnalysis[0] = $result['Posts']['status'];
					$resultAnalysis[1] = $result['Posts']['message'];
					if($resultAnalysis[0] == "fail"){
						$this->Session->setFlash($resultAnalysis[1]);
						$this->redirect(array('controller' => 'users', 'action' => 'inhlogin'));
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
						$date = time();
						$values = array(0 => $date, 1 => session_id());
						Cache::write("login_".$existingLibraries['0']['Library']['library_territory']."_".$existingLibraries['0']['Library']['id']."_".$patronId, $values);
					
						$this->Session->write("library", $existingLibraries['0']['Library']['id']);
                                                $this->Session->write("loginchk", 'Yes');
                                                
                                                //check this library exist is in the library timezone table
                                                $countLibPicksSql ='select count(*) as total from libraries_timezone  where library_id = "'.$this->Session->read("library").'"';
                                                $libPicksRecord = $this->LibrariesTimezone->query($countLibPicksSql);
                                                if(isset($libPicksRecord[0][0]['total']) && ($libPicksRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("isLibaryExistInTimzone", 1);                               
                                                }else{
                                                $this->Session->write("isLibaryExistInTimzone", 0);                               
                                                }        

                                                //check if the notification entry is already there in the notification_subscription table
                                                $notificationSql ='select count(*) as total from notification_subscriptions  where patron_id ="'.$patronId.'" and library_id = "'.$this->Session->read("library").'"';
                                                $emailNotificationRecord = $this->NotificationSubscriptions->query($notificationSql);
                                                if(isset($emailNotificationRecord[0][0]['total']) && ($emailNotificationRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("showNotificationPopup", 'yes');                               
                                                }else{
                                                $this->Session->write("showNotificationPopup", 'no');                               
                                                }
                                                
						$this->Session->write("patron", $patronId);
						$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
						$this->Session->write("innovative_https","innovative_https");
						if($existingLibraries['0']['Library']['library_logout_url'] != '' && ($this->Session->read('referral') != '' || $this->Session->read("subdomain") != '')){
							$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
						}
						if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
							$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
						}
						$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));
						$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
						if($existingLibraries['0']['Library']['library_type'] == 2){
							$this->Session->write("streamPopupShow", $isApproved['Currentpatron']['stream_popup']);
							$this->Session->write("userlogin", 'no');
						}
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
                                                if ($this->Session->read('UrlReferer') != '') {
                                                      $UrlReferer = $this->Session->read('UrlReferer');
                                                      $this->Session->delete('UrlReferer');
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .$UrlReferer);
                                                } else {
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/index');
                                                }
					}
				}
			}
		}
   }
   
   /*
    *   New Authenctication System: Capita
    * 
    */
   
   
   function capita($library = null){ 
       
       
    //code to check the library is inactive or not. if library is inactive then redirect user to library inactive page
    if($library){            
        $library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
        if(count($library_data) > 0)
        {
            if($library_data['Library']['library_status'] == 'inactive'){
                $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/libinactive'); 
                exit;                           
            }
        }
    }
       
                $this->Session->write("layout_option", 'login');
		if($this->Session->read('login_action'))
		{
			if($this->action != $this->Session->read('login_action'))
			{
				$this->Session->destroy('referral');
				$this->Session->destroy('subdomain');
				$this->Session->destroy('login_action');
			}
		}
		if(!$this->Session->read('referral') && !$this->Session->read("subdomain")){
			if(isset($_SERVER['HTTP_REFERER']) && $library == null){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
						$this->Session->write("login_action",'capita');
					}
				}
				else {
					$wrongReferral = 1;
					$data['wrongReferral'] = $wrongReferral;
				}
			}
			else if($library != null)
			{
				$library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
				$this->get_login_layout_name($library_data);
				if(count($library_data) > 0)
				{
					if($this->Session->read('lId') == '')
					{
						$this->Session->write("subdomain",$library);
						$this->Session->write("lId",$library_data['Library']['id']);
					}
				}
				else
				{
					$wrongReferral = 1;
				}
			}
		}
		if($this->Session->read('layout_option') == 'login'){
			$this->layout = 'login';
		}
		else{
			$this->layout = 'login';
		}

		if(isset($this->params['form']['lang'])){
			$language = $this->params['form']['lang'];
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
		 $card = str_replace(" ","",$card);
		 $card = strtolower($card);
		 $data['card'] = $card;
         $pin = $this->data['User']['pin'];
		 $data['pin'] = $pin;
         $patronId = $card;
		$data['patronId'] = $patronId;
         if($card == ''){
            $this -> Session -> setFlash("Please provide card number.");
            if($pin != ''){
               $this->set('pin',$pin);
            }
            else{
               $this->set('pin',"");
            }
         }
                 
                 elseif(strlen($card) < $library_data['Library']['minimum_card_length']){
			$this->Session->setFlash("Please provide a correct card number.");			
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
				$data['cardNo'] = $cardNo;
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				$data['referral'] = $this->Session->read('referral');
				$data['subdomain']=$this->Session->read("subdomain");
				if($this->Session->read('referral') || $this->Session->read("subdomain")){
					$library_cond = $this->Session->read('lId');
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_status' => 'active','library_authentication_method' => 'capita','id' => $library_cond),
														'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type','Library.library_host_name' ,'Library.library_port_no')
														)
													 );

				} else {
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'capita'),
														'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type','Library.library_host_name' ,'Library.library_port_no')
														)
													 );
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "https://".$_SERVER['HTTP_HOST']."/users/capita"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
					$this->redirect(array('controller' => 'users', 'action' => 'capita'));
				}
				else{
					$matches = array();
					$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];
                                        
					$data['database'] = 'freegal';
                                        $data['library_authentication_url'] = $existingLibraries['0']['Library']['library_authentication_url'];                                        
					if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
						$authUrl = Configure::read('App.AuthUrl_AU')."capita_validation";
					}
					else{
						$authUrl = Configure::read('App.AuthUrl')."capita_validation";
					}
					$result = $this->AuthRequest->getAuthResponse($data,$authUrl);
					$resultAnalysis[0] = $result['Posts']['status'];
					$resultAnalysis[1] = $result['Posts']['message'];
					if($resultAnalysis[0] == "fail"){
						$this->Session->setFlash($resultAnalysis[1]);
						$this->redirect(array('controller' => 'users', 'action' => 'capita'));
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
						$date = time();
						$values = array(0 => $date, 1 => session_id());
						Cache::write("login_".$existingLibraries['0']['Library']['library_territory']."_".$existingLibraries['0']['Library']['id']."_".$patronId, $values);
					
						$this->Session->write("library", $existingLibraries['0']['Library']['id']);
                                                $this->Session->write("loginchk", 'Yes');
                                                
                                                //check this library exist is in the library timezone table
                                                $countLibPicksSql ='select count(*) as total from libraries_timezone  where library_id = "'.$this->Session->read("library").'"';
                                                $libPicksRecord = $this->LibrariesTimezone->query($countLibPicksSql);
                                                if(isset($libPicksRecord[0][0]['total']) && ($libPicksRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("isLibaryExistInTimzone", 1);                               
                                                }else{
                                                $this->Session->write("isLibaryExistInTimzone", 0);                               
                                                }        

                                                //check if the notification entry is already there in the notification_subscription table
                                                $notificationSql ='select count(*) as total from notification_subscriptions  where patron_id ="'.$patronId.'" and library_id = "'.$this->Session->read("library").'"';
                                                $emailNotificationRecord = $this->NotificationSubscriptions->query($notificationSql);
                                                if(isset($emailNotificationRecord[0][0]['total']) && ($emailNotificationRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("showNotificationPopup", 'yes');                               
                                                }else{
                                                $this->Session->write("showNotificationPopup", 'no');                               
                                                }
                                                
						$this->Session->write("patron", $patronId);
						$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
						$this->Session->write("capita","capita");
						if($existingLibraries['0']['Library']['library_logout_url'] != '' && ($this->Session->read('referral') != '' || $this->Session->read("subdomain") != '')){
							$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
						}
						if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
							$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
						}
						$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));
						$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
						if($existingLibraries['0']['Library']['library_type'] == 2){
							$this->Session->write("streamPopupShow", $isApproved['Currentpatron']['stream_popup']);
							$this->Session->write("userlogin", 'no');
						}
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
                                                if ($this->Session->read('UrlReferer') != '') {
                                                      $UrlReferer = $this->Session->read('UrlReferer');
                                                      $this->Session->delete('UrlReferer');
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .$UrlReferer);
                                                } else {
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/index');
                                                }
					}
				}
			}
		}
   }
   
   
   /*
    *    Symphony WS Authentication Method
    * 
    */
   
   
   function symws($library = null){
       
       
    //code to check the library is inactive or not. if library is inactive then redirect user to library inactive page
    if($library){            
        $library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
        if(count($library_data) > 0)
        {
            if($library_data['Library']['library_status'] == 'inactive'){
                $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/libinactive'); 
                exit;                           
            }
        }
    }
       
                $this->Session->write("layout_option", 'login');
		if($this->Session->read('login_action'))
		{
			if($this->action != $this->Session->read('login_action'))
			{
				$this->Session->destroy('referral');
				$this->Session->destroy('subdomain');
				$this->Session->destroy('login_action');
			}
		}
		if(!$this->Session->read('referral') && !$this->Session->read("subdomain")){
			if(isset($_SERVER['HTTP_REFERER']) && $library == null){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
						$this->Session->write("login_action",'symws');
					}
				}
				else {
					$wrongReferral = 1;
					$data['wrongReferral'] = $wrongReferral;
				}
			}
			else if($library != null)
			{
				$library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
				$this->get_login_layout_name($library_data);
				if(count($library_data) > 0)
				{
					if($this->Session->read('lId') == '')
					{
						$this->Session->write("subdomain",$library);
						$this->Session->write("lId",$library_data['Library']['id']);
					}
				}
				else
				{
					$wrongReferral = 1;
				}
			}
		}
		if($this->Session->read('layout_option') == 'login'){
			$this->layout = 'login';
		}
		else{
			$this->layout = 'login';
		}

		if(isset($this->params['form']['lang'])){
			$language = $this->params['form']['lang'];
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
		 $card = str_replace(" ","",$card);
		 $card = strtolower($card);
		 $data['card'] = $card;
         $pin = $this->data['User']['pin'];
		 $data['pin'] = $pin;
         $patronId = $card;
		$data['patronId'] = $patronId;
         if($card == ''){
            $this -> Session -> setFlash("Please provide card number.");
            if($pin != ''){
               $this->set('pin',$pin);
            }
            else{
               $this->set('pin',"");
            }
         }
                 
                 elseif(strlen($card) < $library_data['Library']['minimum_card_length']){
			$this->Session->setFlash("Please provide a correct card number.");			
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
				$data['cardNo'] = $cardNo;
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				$data['referral'] = $this->Session->read('referral');
				$data['subdomain']=$this->Session->read("subdomain");
				if($this->Session->read('referral') || $this->Session->read("subdomain")){
					$library_cond = $this->Session->read('lId');
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_status' => 'active','library_authentication_method' => 'symws','id' => $library_cond),
														'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type','Library.library_host_name' ,'Library.library_port_no')
														)
													 );

				} else {
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'symws'),
														'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type','Library.library_host_name' ,'Library.library_port_no')
														)
													 );
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "https://".$_SERVER['HTTP_HOST']."/users/symws"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
					$this->redirect(array('controller' => 'users', 'action' => 'symws'));
				}
				else{
					$matches = array();
					$authUrl = $existingLibraries['0']['Library']['library_authentication_url'];
                                        
					$data['database'] = 'freegal';
                                        $data['library_host_name'] = $existingLibraries['0']['Library']['library_host_name'];
                                        $data['library_authentication_url'] = $existingLibraries['0']['Library']['library_authentication_url'];                                        
					if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
						$authUrl = Configure::read('App.AuthUrl_AU')."symws_validation";
					}
					else{
						$authUrl = Configure::read('App.AuthUrl')."symws_validation";
					}
					$result = $this->AuthRequest->getAuthResponse($data,$authUrl);
					$resultAnalysis[0] = $result['Posts']['status'];
					$resultAnalysis[1] = $result['Posts']['message'];
					if($resultAnalysis[0] == "fail"){
						$this->Session->setFlash($resultAnalysis[1]);
						$this->redirect(array('controller' => 'users', 'action' => 'symws'));
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
						$date = time();
						$values = array(0 => $date, 1 => session_id());
						Cache::write("login_".$existingLibraries['0']['Library']['library_territory']."_".$existingLibraries['0']['Library']['id']."_".$patronId, $values);
					
						$this->Session->write("library", $existingLibraries['0']['Library']['id']);
                                                $this->Session->write("loginchk", 'Yes');
                                                
                                                //check this library exist is in the library timezone table
                                                $countLibPicksSql ='select count(*) as total from libraries_timezone  where library_id = "'.$this->Session->read("library").'"';
                                                $libPicksRecord = $this->LibrariesTimezone->query($countLibPicksSql);
                                                if(isset($libPicksRecord[0][0]['total']) && ($libPicksRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("isLibaryExistInTimzone", 1);                               
                                                }else{
                                                $this->Session->write("isLibaryExistInTimzone", 0);                               
                                                }        

                                                //check if the notification entry is already there in the notification_subscription table
                                                $notificationSql ='select count(*) as total from notification_subscriptions  where patron_id ="'.$patronId.'" and library_id = "'.$this->Session->read("library").'"';
                                                $emailNotificationRecord = $this->NotificationSubscriptions->query($notificationSql);
                                                if(isset($emailNotificationRecord[0][0]['total']) && ($emailNotificationRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("showNotificationPopup", 'yes');                               
                                                }else{
                                                $this->Session->write("showNotificationPopup", 'no');                               
                                                }
                                                
						$this->Session->write("patron", $patronId);
						$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
						$this->Session->write("symws","symws");
						if($existingLibraries['0']['Library']['library_logout_url'] != '' && ($this->Session->read('referral') != '' || $this->Session->read("subdomain") != '')){
							$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
						}
						if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
							$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
						}
						$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));
						$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
						if($existingLibraries['0']['Library']['library_type'] == 2){
							$this->Session->write("streamPopupShow", $isApproved['Currentpatron']['stream_popup']);
							$this->Session->write("userlogin", 'no');
						}
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
                                                if ($this->Session->read('UrlReferer') != '') {
                                                      $UrlReferer = $this->Session->read('UrlReferer');
                                                      $this->Session->delete('UrlReferer');
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .$UrlReferer);
                                                } else {
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/index');
                                                }
					}
				}
			}
		}
   }
   
   
   
   
   /*
    Function Name : ihdlogin
    Desc : For patron ihdlogin(Innovative Var with HTTPS) login method
   */

   function ihdlogin($library = null){
       
        //code to check the library is inactive or not. if library is inactive then redirect user to library inactive page
        if($library){            
            $library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
            if(count($library_data) > 0)
            {
                if($library_data['Library']['library_status'] == 'inactive'){
                    $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/libinactive'); 
                    exit;                           
                }
            }
        }
       
       
       
    $this->Session->write("layout_option", 'login');
		if($this->Session->read('login_action'))
		{
			if($this->action != $this->Session->read('login_action'))
			{
				$this->Session->destroy('referral');
				$this->Session->destroy('subdomain');
				$this->Session->destroy('login_action');
			}
		}
		if(!$this->Session->read('referral') && !$this->Session->read("subdomain")){
			if(isset($_SERVER['HTTP_REFERER']) && $library == null){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
						$this->Session->write("login_action",'ihdlogin');
					}
				}
				else {
					$wrongReferral = 1;
					$data['wrongReferral'] =$wrongReferral;
				}
			}
			else if($library != null)
			{
				$library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
				$this->get_login_layout_name($library_data);
				if(count($library_data) > 0)
				{
					if($this->Session->read('lId') == '')
					{
						$this->Session->write("subdomain",$library);
						$this->Session->write("lId",$library_data['Library']['id']);
					}
				}
				else
				{
					$wrongReferral = 1;
				}
			}
		}
		if($this->Session->read('layout_option') == 'login'){
			$this->layout = 'login';
		}
		else{
			$this->layout = 'login';
		}

		if(isset($this->params['form']['lang'])){
			$language = $this->params['form']['lang'];
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
			$card = str_replace(" ","",$card);
			$card = strtolower($card);
			$data['card'] = $card;
			$pin = $this->data['User']['pin'];
			$data['pin'] = $pin;
			$patronId = $card;
			$data['patronId'] = $patronId;
			if($card == ''){
				$this -> Session -> setFlash("Please provide card number.");
				if($pin != ''){
				   $this->set('pin',$pin);
				}
				else{
				   $this->set('pin',"");
				}
			}
                        
                        elseif(strlen($card) < $library_data['Library']['minimum_card_length']){
                                $this->Session->setFlash("Please provide a correct card number.");			
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
				$data['cardNo'] = $cardNo;
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				$data['referral'] = $this->Session->read('referral');
				$data['subdomain']=$this->Session->read("subdomain");
				if($this->Session->read('referral') || $this->Session->read("subdomain")){
					$library_cond = $this->Session->read('lId');
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_status' => 'active','library_authentication_method' => 'innovative_var_https','id' => $library_cond),
														'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
														)
													 );
				}
				else {
					$library_cond = '';
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'innovative_var_https'),
														'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
														)
													 );
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "https://".$_SERVER['HTTP_HOST']."/users/ihdlogin"){
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
					$data['database'] = 'freegal';
					if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
						$authUrl = Configure::read('App.AuthUrl_AU')."ihdlogin_validation";
					}
					else{
						$authUrl = Configure::read('App.AuthUrl')."ihdlogin_validation";
					}
					$result = $this->AuthRequest->getAuthResponse($data,$authUrl);
					$resultAnalysis[0] = $result['Posts']['status'];
					$resultAnalysis[1] = $result['Posts']['message'];
					if($resultAnalysis[0] == "fail"){
						$this->Session->setFlash($resultAnalysis[1]);
						$this->redirect(array('controller' => 'users', 'action' => 'ihdlogin'));
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
						$date = time();
						$values = array(0 => $date, 1 => session_id());
						Cache::write("login_".$existingLibraries['0']['Library']['library_territory']."_".$existingLibraries['0']['Library']['id']."_".$patronId, $values);

					   $this->Session->write("library", $existingLibraries['0']['Library']['id']);
                                           $this->Session->write("loginchk", 'Yes');
                                           
                                           //check this library exist is in the library timezone table
                                            $countLibPicksSql ='select count(*) as total from libraries_timezone  where library_id = "'.$this->Session->read("library").'"';
                                            $libPicksRecord = $this->LibrariesTimezone->query($countLibPicksSql);
                                            if(isset($libPicksRecord[0][0]['total']) && ($libPicksRecord[0][0]['total'] > 0 )){
                                            $this->Session->write("isLibaryExistInTimzone", 1);                               
                                            }else{
                                            $this->Session->write("isLibaryExistInTimzone", 0);                               
                                            }        

                                            //check if the notification entry is already there in the notification_subscription table
                                            $notificationSql ='select count(*) as total from notification_subscriptions  where patron_id ="'.$patronId.'" and library_id = "'.$this->Session->read("library").'"';
                                            $emailNotificationRecord = $this->NotificationSubscriptions->query($notificationSql);
                                            if(isset($emailNotificationRecord[0][0]['total']) && ($emailNotificationRecord[0][0]['total'] > 0 )){
                                            $this->Session->write("showNotificationPopup", 'yes');                               
                                            }else{
                                            $this->Session->write("showNotificationPopup", 'no');                               
                                            }
                                           
					   $this->Session->write("patron", $patronId);
					   $this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
					   $this->Session->write("innovative_var_https","innovative_var_https");
					   if($existingLibraries['0']['Library']['library_logout_url'] != '' && ($this->Session->read('referral') != '' || $this->Session->read("subdomain") != '')){
							$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
					   }
						if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
							$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
						}
					   $isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));
					   $this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
						if($existingLibraries['0']['Library']['library_type'] == 2){
							$this->Session->write("streamPopupShow", $isApproved['Currentpatron']['stream_popup']);
							$this->Session->write("userlogin", 'no');
						}
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
                                            if ($this->Session->read('UrlReferer') != '') {
                                                  $UrlReferer = $this->Session->read('UrlReferer');
                                                  $this->Session->delete('UrlReferer');
                                                  $this->redirect('http://'.$_SERVER['HTTP_HOST'] .$UrlReferer);
                                            } else {
                                                  $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/index');
                                            }
					}
				}
			}
		}
	}
   /*
    Function Name : inhdlogin
    Desc : For patron inhdlogin(Innovative Var with HTTPS and without PIN) login method
   */

   function inhdlogin($library = null){
       
       
        //code to check the library is inactive or not. if library is inactive then redirect user to library inactive page
        if($library){            
            $library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
            if(count($library_data) > 0)
            {
                if($library_data['Library']['library_status'] == 'inactive'){
                    $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/libinactive'); 
                    exit;                           
                }
            }
        }
    $this->Session->write("layout_option", 'login');
		if($this->Session->read('login_action'))
		{
			if($this->action != $this->Session->read('login_action'))
			{
				$this->Session->destroy('referral');
				$this->Session->destroy('subdomain');
				$this->Session->destroy('login_action');
			}
		}
		if(!$this->Session->read('referral') && !$this->Session->read("subdomain")){
			if(isset($_SERVER['HTTP_REFERER']) && $library == null){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
						$this->Session->write("login_action",'inhdlogin');
					}
				}
				else {
					$wrongReferral = 1;
					$data['wrongReferral'] = $wrongReferral;
				}
			}
			else if($library != null)
			{
				$library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
				$this->get_login_layout_name($library_data);
				if(count($library_data) > 0)
				{
					if($this->Session->read('lId') == '')
					{

						$this->Session->write("subdomain",$library);
						$this->Session->write("lId",$library_data['Library']['id']);
					}
				}
				else
				{
					$wrongReferral = 1;
				}
			}
		}
		if($this->Session->read('layout_option') == 'login'){
			$this->layout = 'login';
		}
		else{
			$this->layout = 'login';
		}

		if(isset($this->params['form']['lang'])){
			$language = $this->params['form']['lang'];
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
			$card = str_replace(" ","",$card);
			$card = strtolower($card);
			$data['card'] = $card;
			$patronId = $card;
			$data['patronId'] = $patronId;
			if($card == ''){
				$this -> Session -> setFlash("Please provide card number.");
			}
                        
                        elseif(strlen($card) < $library_data['Library']['minimum_card_length']){
                                $this->Session->setFlash("Please provide a correct card number.");			
                        }
			else{
				$cardNo = substr($card,0,5);
				$data['cardNo'] = $cardNo;
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				$data['referral'] = $this->Session->read('referral');
				$data['subdomain']=$this->Session->read("subdomain");
				if($this->Session->read('referral') || $this->Session->read("subdomain")){
					$library_cond = $this->Session->read('lId');
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_status' => 'active','library_authentication_method' => 'innovative_var_https_wo_pin','id' => $library_cond),
														'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
														)
													 );
				}
				else {
					$library_cond = '';
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'innovative_var_https_wo_pin'),
														'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
														)
													 );
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "https://".$_SERVER['HTTP_HOST']."/users/inhdlogin"){
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
					$data['database'] = 'freegal';
					if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
						$authUrl = Configure::read('App.AuthUrl_AU')."inhdlogin_validation";
					}
					else{
						$authUrl = Configure::read('App.AuthUrl')."inhdlogin_validation";
					}
					$result = $this->AuthRequest->getAuthResponse($data,$authUrl);
					$resultAnalysis[0] = $result['Posts']['status'];
					$resultAnalysis[1] = $result['Posts']['message'];
					if($resultAnalysis[0] == "fail"){
						$this->Session->setFlash($resultAnalysis[1]);
						$this->redirect(array('controller' => 'users', 'action' => 'inhdlogin'));
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
						$date = time();
						$values = array(0 => $date, 1 => session_id());
						Cache::write("login_".$existingLibraries['0']['Library']['library_territory']."_".$existingLibraries['0']['Library']['id']."_".$patronId, $values);

					   $this->Session->write("library", $existingLibraries['0']['Library']['id']);
                                           $this->Session->write("loginchk", 'Yes');
                                           
                                           //check this library exist is in the library timezone table
                                            $countLibPicksSql ='select count(*) as total from libraries_timezone  where library_id = "'.$this->Session->read("library").'"';
                                            $libPicksRecord = $this->LibrariesTimezone->query($countLibPicksSql);
                                            if(isset($libPicksRecord[0][0]['total']) && ($libPicksRecord[0][0]['total'] > 0 )){
                                            $this->Session->write("isLibaryExistInTimzone", 1);                               
                                            }else{
                                            $this->Session->write("isLibaryExistInTimzone", 0);                               
                                            }        

                                            //check if the notification entry is already there in the notification_subscription table
                                            $notificationSql ='select count(*) as total from notification_subscriptions  where patron_id ="'.$patronId.'" and library_id = "'.$this->Session->read("library").'"';
                                            $emailNotificationRecord = $this->NotificationSubscriptions->query($notificationSql);
                                            if(isset($emailNotificationRecord[0][0]['total']) && ($emailNotificationRecord[0][0]['total'] > 0 )){
                                            $this->Session->write("showNotificationPopup", 'yes');                               
                                            }else{
                                            $this->Session->write("showNotificationPopup", 'no');                               
                                            }
                                           
					   $this->Session->write("patron", $patronId);
					   $this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
					   $this->Session->write("innovative_var_https_wo_pin","innovative_var_https_wo_pin");
					   if($existingLibraries['0']['Library']['library_logout_url'] != '' && ($this->Session->read('referral') != '' || $this->Session->read("subdomain") != '')){
							$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
					   }
						if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
							$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
						}
					   $isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));
					   $this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
					   if($existingLibraries['0']['Library']['library_type'] == 2){
							$this->Session->write("streamPopupShow", $isApproved['Currentpatron']['stream_popup']);
							$this->Session->write("userlogin", 'no');
						}
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
                                            if ($this->Session->read('UrlReferer') != '') {
                                                  $UrlReferer = $this->Session->read('UrlReferer');
                                                  $this->Session->delete('UrlReferer');
                                                  $this->redirect('http://'.$_SERVER['HTTP_HOST'] .$UrlReferer);
                                            } else {
                                                  $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/index');
                                            }
					}
				}
			}
		}
	}

   /*
    Function Name : plogin
    Desc : For patron login(Using SOAP web services) login method
   */
   
    function plogin($library = null){
            
            //code to check the library is inactive or not. if library is inactive then redirect user to library inactive page
        if($library){            
            $library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
            if(count($library_data) > 0)
            {
                if($library_data['Library']['library_status'] == 'inactive'){
                    $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/libinactive'); 
                    exit;                           
                }
            }
        }
        
    $this->Session->write("layout_option", 'login');
		if($this->Session->read('login_action'))
		{
			if($this->action != $this->Session->read('login_action'))
			{
				$this->Session->destroy('referral');
				$this->Session->destroy('subdomain');
				$this->Session->destroy('login_action');
			}
		}
		if(!$this->Session->read('referral') && !$this->Session->read("subdomain")){
			if(isset($_SERVER['HTTP_REFERER']) && $library == null){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
						$this->Session->write("login_action",'plogin');
					}
				}
				else {
					$wrongReferral = 1;
					$data['wrongReferral'] = $wrongReferral;
				}
			}
			else if($library != null)
			{
				$library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
				$this->get_login_layout_name($library_data);
				if(count($library_data) > 0)
				{
					if($this->Session->read('lId') == '')
					{
						$this->Session->write("subdomain",$library);
						$this->Session->write("lId",$library_data['Library']['id']);
					}
				}
				else
				{
					$wrongReferral = 1;
				}
			}
		}
		if($this->Session->read('layout_option') == 'login'){
			$this->layout = 'login';
		}
		else{
			$this->layout = 'login';
		}

		if(isset($this->params['form']['lang'])){
			$language = $this->params['form']['lang'];
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
			$card = str_replace(" ","",$card);
			$card = strtolower($card);
			$data['card'] = $card;
			$pin = $this->data['User']['pin'];
			$data['pin'] = $pin;
			$patronId = $card;
			$data['patronId'] = $patronId;
			if($card == ''){
				$this -> Session -> setFlash("Please provide card number.");
				if($pin != ''){
					$this->set('pin',$pin);
				}
				else{
					$this->set('pin',"");
				}
			}      
                        
                    elseif(strlen($card) < $library_data['Library']['minimum_card_length']){
                            $this->Session->setFlash("Please provide a correct card number.");			
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
				$data['cardNo'] = $cardNo;
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				$data['referral'] = $this->Session->read('referral');
				$data['subdomain']=$this->Session->read("subdomain");
				if($this->Session->read('referral') || $this->Session->read("subdomain")){
					$library_cond = $this->Session->read('lId');
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
									'conditions' => array('library_status' => 'active','library_authentication_method' => 'soap','id' => $library_cond),
									'fields' => array('Library.id','Library.library_territory','Library.library_soap_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
									)
								 );
				} else {
					$library_cond = '';
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
									'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'soap'),
									'fields' => array('Library.id','Library.library_territory','Library.library_soap_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
									)
								 );
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "https://".$_SERVER['HTTP_HOST']."/users/plogin"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
					$this->redirect(array('controller' => 'users', 'action' => 'plogin'));
				}
				else{
					$data['soapUrl'] = $existingLibraries['0']['Library']['library_soap_url'];
					$data['database'] = 'freegal';
					if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
						$authUrl = Configure::read('App.AuthUrl_AU')."plogin_validation";
					}
					else{
						$authUrl = Configure::read('App.AuthUrl')."plogin_validation";
					}

					$result = $this->AuthRequest->getAuthResponse($data,$authUrl);
					$resultAnalysis[0] = $result['Posts']['status'];
					$resultAnalysis[1] = $result['Posts']['message'];
					if($resultAnalysis[0] == "fail"){
						$this->Session->setFlash($resultAnalysis[1]);
						$this->redirect(array('controller' => 'users', 'action' => 'plogin'));
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
						$date = time();
						$values = array(0 => $date, 1 => session_id());
						Cache::write("login_".$existingLibraries['0']['Library']['library_territory']."_".$existingLibraries['0']['Library']['id']."_".$patronId, $values);

						$this->Session->write("library", $existingLibraries['0']['Library']['id']);
                                                $this->Session->write("loginchk", 'Yes');
                                                
                                                //check this library exist is in the library timezone table
                                                $countLibPicksSql ='select count(*) as total from libraries_timezone  where library_id = "'.$this->Session->read("library").'"';
                                                $libPicksRecord = $this->LibrariesTimezone->query($countLibPicksSql);
                                                if(isset($libPicksRecord[0][0]['total']) && ($libPicksRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("isLibaryExistInTimzone", 1);                               
                                                }else{
                                                $this->Session->write("isLibaryExistInTimzone", 0);                               
                                                }        

                                                //check if the notification entry is already there in the notification_subscription table
                                                $notificationSql ='select count(*) as total from notification_subscriptions  where patron_id ="'.$patronId.'" and library_id = "'.$this->Session->read("library").'"';
                                                $emailNotificationRecord = $this->NotificationSubscriptions->query($notificationSql);
                                                if(isset($emailNotificationRecord[0][0]['total']) && ($emailNotificationRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("showNotificationPopup", 'yes');                               
                                                }else{
                                                $this->Session->write("showNotificationPopup", 'no');                               
                                                }
                                                
						$this->Session->write("patron", $patronId);
						$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
						$this->Session->write("soap","soap");
						if($existingLibraries['0']['Library']['library_logout_url'] != '' && ($this->Session->read('referral') != '' || $this->Session->read("subdomain") != '')){
							$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
						}
						if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
							$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
						}
						$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));
						$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
						if($existingLibraries['0']['Library']['library_type'] == 2){
							$this->Session->write("streamPopupShow", $isApproved['Currentpatron']['stream_popup']);
							$this->Session->write("userlogin", 'no');
						}
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
                                                if ($this->Session->read('UrlReferer') != '') {
                                                      $UrlReferer = $this->Session->read('UrlReferer');
                                                      $this->Session->delete('UrlReferer');
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .$UrlReferer);
                                                } else {
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/index');
                                                }
					}
				}
			}
		}
	}
   /*
    Function Name : ilhdlogin
    Desc : For patron ilhdlogin(Innovative Var HTTPS with Name) login method
   */
   function ilhdlogin($library = null){
       
        //code to check the library is inactive or not. if library is inactive then redirect user to library inactive page
        if($library){            
            $library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
            if(count($library_data) > 0)
            {
                if($library_data['Library']['library_status'] == 'inactive'){
                    $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/libinactive'); 
                    exit;                           
                }
            }
        }
       
       
       
       
    $this->Session->write("layout_option", 'login');
		if($this->Session->read('login_action'))
		{
			if($this->action != $this->Session->read('login_action'))
			{
				$this->Session->destroy('referral');
				$this->Session->destroy('subdomain');
				$this->Session->destroy('login_action');
			}
		}
		if(!$this->Session->read('referral') && !$this->Session->read("subdomain")){
			if(isset($_SERVER['HTTP_REFERER']) && $library == null){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
						$this->Session->write("login_action",'ilhdlogin');
					}
				}
				else {
					$wrongReferral = 1;
					$data['wrongReferral'] = $wrongReferral;
				}
			}
			else if($library != null)
			{
				$library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
				$this->get_login_layout_name($library_data);
				if(count($library_data) > 0)
				{
					if($this->Session->read('lId') == '')
					{
						$this->Session->write("subdomain",$library);
						$this->Session->write("lId",$library_data['Library']['id']);
					}
				}
				else
				{
					$wrongReferral = 1;
				}
			}
		}

		if($this->Session->read('layout_option') == 'login'){
			$this->layout = 'login';
		}
		else{
			$this->layout = 'login';
		}

		if(isset($this->params['form']['lang'])){
			$language = $this->params['form']['lang'];
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
			$card = str_replace(" ","",$card);
			$card = strtolower($card);
			$data['card'] = $card;
			$name = $this->data['User']['name'];
			$data['name'] = $name;
			$patronId = $card;
			$data['patronId'] = $patronId;
			if($card == ''){
				$this -> Session -> setFlash("Please provide card number.");
				if($name != ''){
				   $this->set('name',$name);
				}
				else{
				   $this->set('name',"");
				}            
			}       
                        elseif(strlen($card) < $library_data['Library']['minimum_card_length']){
                            $this->Session->setFlash("Please provide a correct card number.");			
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
				$data['cardNo'] = $cardNo;
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				$data['referral'] = $this->Session->read('referral');
				$data['subdomain']=$this->Session->read("subdomain");
				if($this->Session->read('referral') || $this->Session->read("subdomain")){
					$library_cond = $this->Session->read('lId');
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
															'conditions' => array('library_status' => 'active','library_authentication_method' => 'innovative_var_https_name','id' => $library_cond),
															'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
															)
													 );
				}
				else {
					$library_cond = '';
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'innovative_var_https_name'),
														'fields' => array('Library.id','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
														)
													 );
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "https://".$_SERVER['HTTP_HOST']."/users/ilhdlogin"){
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
					$data['database'] = 'freegal';
					if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
						$authUrl = Configure::read('App.AuthUrl_AU')."ilhdlogin_validation";
					}
					else{
						$authUrl = Configure::read('App.AuthUrl')."ilhdlogin_validation";
					}
					$result = $this->AuthRequest->getAuthResponse($data,$authUrl);
					$resultAnalysis[0] = $result['Posts']['status'];
					$resultAnalysis[1] = $result['Posts']['message'];
					if($resultAnalysis[0] == "fail"){
						$this->Session->setFlash($resultAnalysis[1]);
						$this->redirect(array('controller' => 'users', 'action' => 'ilhdlogin'));
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
						$date = time();
						$values = array(0 => $date, 1 => session_id());
						Cache::write("login_".$existingLibraries['0']['Library']['library_territory']."_".$existingLibraries['0']['Library']['id']."_".$patronId, $values);

						$this->Session->write("library", $existingLibraries['0']['Library']['id']);
                                                $this->Session->write("loginchk", 'Yes');
                                                
                                                //check this library exist is in the library timezone table
                                                $countLibPicksSql ='select count(*) as total from libraries_timezone  where library_id = "'.$this->Session->read("library").'"';
                                                $libPicksRecord = $this->LibrariesTimezone->query($countLibPicksSql);
                                                if(isset($libPicksRecord[0][0]['total']) && ($libPicksRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("isLibaryExistInTimzone", 1);                               
                                                }else{
                                                $this->Session->write("isLibaryExistInTimzone", 0);                               
                                                }        

                                                //check if the notification entry is already there in the notification_subscription table
                                                $notificationSql ='select count(*) as total from notification_subscriptions  where patron_id ="'.$patronId.'" and library_id = "'.$this->Session->read("library").'"';
                                                $emailNotificationRecord = $this->NotificationSubscriptions->query($notificationSql);
                                                if(isset($emailNotificationRecord[0][0]['total']) && ($emailNotificationRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("showNotificationPopup", 'yes');                               
                                                }else{
                                                $this->Session->write("showNotificationPopup", 'no');                               
                                                }
                                                
						$this->Session->write("patron", $patronId);
						$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
						$this->Session->write("innovative_var_https_name","innovative_var_name");
						if($existingLibraries['0']['Library']['library_logout_url'] != '' && ($this->Session->read('referral') != '' || $this->Session->read("subdomain") != '')){
							$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
						}
						if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
							$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
						}
						$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));
						$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
						if($existingLibraries['0']['Library']['library_type'] == 2){
							$this->Session->write("streamPopupShow", $isApproved['Currentpatron']['stream_popup']);
							$this->Session->write("userlogin", 'no');
						}
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
                                                if ($this->Session->read('UrlReferer') != '') {
                                                      $UrlReferer = $this->Session->read('UrlReferer');
                                                      $this->Session->delete('UrlReferer');
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .$UrlReferer);
                                                } else {
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/index');
                                                }
					}
				}
			}
		}
	}

   /*
    Function Name : clogin
    Desc : For patron clogin(Curl) login method
   */

	function clogin($library = null){
            
            
            //code to check the library is inactive or not. if library is inactive then redirect user to library inactive page
            if($library){            
                $library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
                if(count($library_data) > 0)
                {
                    if($library_data['Library']['library_status'] == 'inactive'){
                        $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/users/libinactive'); 
                        exit;                           
                    }
                }
           }
            
            
            
    $this->Session->write("layout_option", 'login');
		if($this->Session->read('login_action'))
		{
			if($this->action != $this->Session->read('login_action'))
			{
				$this->Session->destroy('referral');
				$this->Session->destroy('subdomain');
				$this->Session->destroy('login_action');
			}
		}
		if(!$this->Session->read('referral') && !$this->Session->read("subdomain")){
			if(isset($_SERVER['HTTP_REFERER']) && $library == null){
				$url = $this->Url->find('all', array('conditions' => array('domain_name' => $_SERVER['HTTP_REFERER'])));
				if(count($url) > 0){
					if($this->Session->read('referral') == ''){
						$this->Session->write("referral",$_SERVER['HTTP_REFERER']);
						$this->Session->write("lId",$url[0]['Url']['library_id']);
						$this->Session->write("login_action",'clogin');
					}
				}
				else {
					$wrongReferral = 1;
					$data['wrongReferral'] = $wrongReferral;
				}
			}
			else if($library != null)
			{
				$library_data = $this->Library->find('first', array('conditions' => array('library_subdomain' => $library)));
				$this->get_login_layout_name($library_data);
				if(count($library_data) > 0)
				{
					if($this->Session->read('lId') == '')
					{
						$this->Session->write("subdomain",$library);
						$this->Session->write("lId",$library_data['Library']['id']);
					}
				}
				else
				{
					$wrongReferral = 1;
				}
			}
		}
		if($this->Session->read('layout_option') == 'login'){
			$this->layout = 'login';
		}
		else{
			$this->layout = 'login';
		}

		if(isset($this->params['form']['lang'])){
			$language = $this->params['form']['lang'];
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
			$card = str_replace(" ","",$card);
			$card = strtolower($card);
			$data['card'] = $card;
			$pin = $this->data['User']['pin'];
			$data['pin'] = $pin;
			$patronId = $card;
			$data['patronId'] = $patronId;
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
				$data['cardNo'] = $cardNo;
				$this->Library->recursive = -1;
				$this->Library->Behaviors->attach('Containable');
				$data['referral'] = $this->Session->read('referral');
				$data['subdomain']=$this->Session->read("subdomain");
				$existingLibraries = array();
				if($this->Session->read('referral') || $this->Session->read("subdomain")){
					$library_cond = $this->Session->read('lId');
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
									'conditions' => array('library_status' => 'active','library_authentication_method' => 'curl_method','id' => $library_cond),
									'fields' => array('Library.id','Library.library_territory','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
									)
								 );
				}
				else {
					$library_cond = '';
					$data['library_cond'] = $library_cond;
					$existingLibraries = $this->Library->find('all',array(
									'conditions' => array('library_status' => 'active','library_authentication_method' => 'curl_method','id' => $library_cond),
									'fields' => array('Library.id','Library.library_territory','Library.library_logout_url','Library.library_territory','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_type')
									)
								 );
				}
				if(count($existingLibraries) == 0){
					if(isset($wrongReferral) && $_SERVER['HTTP_REFERER'] != "https://".$_SERVER['HTTP_HOST']."/users/clogin"){
						$this->Session->setFlash("You are not authorized to view this location.");
					}
					else{
						$this->Session->setFlash("This is not a valid credential.");
					}
					$this->redirect(array('controller' => 'users', 'action' => 'clogin'));
				}
				else{
					$data['database'] = 'freegal';
					if($existingLibraries['0']['Library']['library_territory'] == 'AU'){
						$authUrl1 = Configure::read('App.AuthUrl_AU')."clogin_validation";
					}
					else{
						$authUrl1 = Configure::read('App.AuthUrl')."clogin_validation";
					}
					$result = $this->AuthRequest->getAuthResponse($data,$authUrl1);
					$resultAnalysis[0] = $result['Posts']['status'];
					$resultAnalysis[1] = $result['Posts']['message'];
					if($resultAnalysis[0] == "fail"){
						$this->Session->setFlash($resultAnalysis[1]);
						$this->redirect(array('controller' => 'users', 'action' => 'clogin'));
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
						$date = time();
						$values = array(0 => $date, 1 => session_id());
						Cache::write("login_".$existingLibraries['0']['Library']['library_territory']."_".$existingLibraries['0']['Library']['id']."_".$patronId, $values);
						$this->Session->write("library", $existingLibraries['0']['Library']['id']);
                                                $this->Session->write("loginchk", 'Yes');
                                                
                                                //check this library exist is in the library timezone table
                                                $countLibPicksSql ='select count(*) as total from libraries_timezone  where library_id = "'.$this->Session->read("library").'"';
                                                $libPicksRecord = $this->LibrariesTimezone->query($countLibPicksSql);
                                                if(isset($libPicksRecord[0][0]['total']) && ($libPicksRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("isLibaryExistInTimzone", 1);                               
                                                }else{
                                                $this->Session->write("isLibaryExistInTimzone", 0);                               
                                                }        

                                                //check if the notification entry is already there in the notification_subscription table
                                                $notificationSql ='select count(*) as total from notification_subscriptions  where patron_id ="'.$patronId.'" and library_id = "'.$this->Session->read("library").'"';
                                                $emailNotificationRecord = $this->NotificationSubscriptions->query($notificationSql);
                                                if(isset($emailNotificationRecord[0][0]['total']) && ($emailNotificationRecord[0][0]['total'] > 0 )){
                                                $this->Session->write("showNotificationPopup", 'yes');                               
                                                }else{
                                                $this->Session->write("showNotificationPopup", 'no');                               
                                                }
                                                
						$this->Session->write("patron", $patronId);
						$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
						$this->Session->write("curl_method","curl_method");
						if($existingLibraries['0']['Library']['library_logout_url'] != '' && ($this->Session->read('referral') != '' || $this->Session->read("subdomain") != '')){
							$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
						}
						if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
							$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
						}
						$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));
						$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
						if($existingLibraries['0']['Library']['library_type'] == 2){
							$this->Session->write("streamPopupShow", $isApproved['Currentpatron']['stream_popup']);
							$this->Session->write("userlogin", 'no');
						}
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
                                                if ($this->Session->read('UrlReferer') != '') {
                                                      $UrlReferer = $this->Session->read('UrlReferer');
                                                      $this->Session->delete('UrlReferer');
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .$UrlReferer);
                                                } else {
                                                      $this->redirect('http://'.$_SERVER['HTTP_HOST'] .'/index');
                                                }

					}
				}
			}
		}
	}

	function get_login_layout_name($library_data){
		$mobile_auth = $library_data['Library']['mobile_auth'];
		$library_territory = $library_data['Library']['library_territory'];
		$library_authentication_method = $library_data['Library']['library_authentication_method'];

		if(  ($library_territory == 'IT' || $library_authentication_method == 'ezproxy' || $library_authentication_method == 'referral_url')   && ($mobile_auth == '' || $mobile_auth == null) ){
			$this->Session->write("layout_option", 'login');
		}
		else{
			$this->Session->write("layout_option", 'login');
		}
	}

	function admin_addmultipleusers($noOfUsers){
            $this->autoRender = false;
            $userType = $this->Session->read('Auth.User.type_id');
            if($userType != 1){
                $this->redirect('/home/aboutus');
		exit('You are not allowed to use this section.');
            }
            $this->Library->recursive = -1;
            $libId = 2;
            $libraryData = $this->Library->find('first',array('conditions'=>array('id'=>$libId)));
            $fromCount = $libraryData['Library']['generic_count']+1;
            $toCount = $libraryData['Library']['generic_count']+$noOfUsers;
            $counter = 0;
            $file = '../../userslist/users_libraryideas'.$libId.'-'.date('Y-m-d-h-i-s',time()).'.txt';
            $fp = fopen($file,'w');
            for($counter=$fromCount;$counter<=$toCount;$counter++){
                $email = 'library'.$counter.'@libraryideas.com';
                $temp_password = $this->PasswordHelper->generatePasswordWithout10(6);
                $encyptedPassword = Security::hash(Configure::read('Security.salt').$temp_password);
                $data = array(
                        'id'=>'',
                        'password'=>$encyptedPassword,
                        'type_id'=>'5',
                        'first_name'=>'FirstName'.$counter,
                        'last_name'=>'LastName'.$counter,
                        'email'=>$email,
                        'library_id'=>$libId,
                        'consortium'=>'',
                        'user_status'=>'active',
                        'sales'=>'no',
                        'created'=>date('Y-m-d h:i:s',time()),
                        'modified'=>date('Y-m-d h:i:s',time())
                );
                
                if($this->User->save($data)){
                    fwrite($fp,$email.",".$temp_password."\n");
                }
            }
            fclose($fp);
            $libraryData['Library']['generic_count']=$toCount;
            $this->Library->save($libraryData['Library']);
            echo "Users Created";
        }

        
        function isPatronLogin()
        {
            $this -> layout = 'ajax';
            if($this->Session->read("patron"))
            {
                echo true;
            }
            else 
            {
                echo false;
            }
            
            exit;
        }

}

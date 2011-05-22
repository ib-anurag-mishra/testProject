<?php
 /*
 File Name : authentications_controller.php
 File Description : Controller for handeling auth transactions with library.
 Author : Mindfire Solutions
 */
 
Class AuthenticationsController extends AppController
{
	var $name = 'Authentications';
	var $components = array('sip2');
	var $uses = array('User','Group', 'Library', 'Currentpatron', 'Download','Variable','Url','Language');
	
	function data_handler() {
		$url = $_POST['url'];
		// create new cur connection
		$ch = curl_init();
		// tell curl target url
		curl_setopt($ch, CURLOPT_URL, $url);
		// tell curl we will be sending via POST
		curl_setopt($ch, CURLOPT_POST, false);
		// tell it not to validate ssl cert
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// tell it where to get POST variables from
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		// make the connection
		$result = curl_exec($ch);
		// close connection
		curl_close($ch);
		echo $result;
		exit;
	}
	
	function curl_data_handler() {
		$url = $_POST['url'];
		$session = curl_init($url);
		curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt ($session, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($session, CURLOPT_HEADER, true);
		$result = curl_exec($session);
		// close connection
		curl_close($session);
		echo $result;
		exit;
	}
	
	function sip_data_handler() {
		
		//end of class
		App::import('Component', 'sip2');
		$mysip = new sip2();
		$mysip->hostname = $_POST['hostname'];
		$mysip->port = $_POST['port'];
		$mysip->sip_login = $_POST['sip_login'];
		$mysip->sip_password = $_POST['sip_password'];
		$mysip->sip_location = $_POST['sip_location'];
		$mysip->AO = @$_POST['AO'];
		$mysip->AN = @$_POST['AN'];
		$mysip->patron = @$_POST['patron'];
		$mysip->patronpwd = @$_POST['patronpwd'];
		if($mysip->connect()) {
			if($_POST['parameters_number'] == 0){
				//print_r($_POST);
				$result = call_user_func(array($mysip, $_POST['method_name']));
				echo $result;
			}else if($_POST['parameters_number'] == 1){
				$x = $_POST['pr1'];
				
				$result = call_user_func(array($mysip, $_POST['method_name']),$x);
				//call_user_func(function($arg) { print "[$arg]\n"; }, 'test');
				echo $result;
			}else if($_POST['parameters_number'] == 2){
				$result = $mysip->$_POST['method_name']($_POST['pr1'],$_POST['pr2']);
				echo $result;
			}else if($_POST['parameters_number'] == 3){
				$result = $mysip->$_POST['method_name']($_POST['pr1'],$_POST['pr2'],$_POST['pr3']);
				echo $result;
			}else if($_POST['parameters_number'] == 4){
				$result = $mysip->$_POST['method_name']($_POST['pr1'],$_POST['pr2'],$_POST['pr3'],$_POST['pr4']);
				echo $result;
			}	
		} else {
			echo 'failed';
		}
		exit;
	}
	
	function plogin_data_handler() {
		$soapUrl = $_POST['soapUrl'];
		$card = $_POST['card'];
		$pin = $_POST['pin'];
		$client = new SoapClient($soapUrl); 
		$result = $client->validate($card, $pin);
		echo $result;
		exit;
	}
	
	/*function sdlogin_validation(){
		if($_POST){
			print_r($_POST);exit;
			$card = $_POST['card'];
			$pin = $_Post['pin'];
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
						$mysip = new $this->sip2;
						$mysip->hostname = $existingLibraries['0']['Library']['library_host_name'];
						$mysip->port = $existingLibraries['0']['Library']['library_port_no'];
						$mysip->sip_login = $existingLibraries['0']['Library']['library_sip_login'];
						$mysip->sip_password = $existingLibraries['0']['Library']['library_sip_password'];
						$mysip->sip_location = $existingLibraries['0']['Library']['library_sip_location'];
						if($mysip->connect()) {
						
							if(!empty($mysip->sip_login)){
								$sc_login=$mysip->msgLogin($mysip->sip_login,$mysip->sip_password,$mysip->sip_location);
								$mysip->parseLoginResponse($mysip->get_message($sc_login));
							}
							
							//send selfcheck status message
							$in = $mysip->msgSCStatus('','',$existingLibraries['0']['Library']['library_sip_version']);
							$msg_result = $mysip->get_message($in);

							// Make sure the response is 98 as expected
							if (preg_match("/98/", $msg_result)) {

									
								  $parseACSStatusResponse = $mysip->parseACSStatusResponse($msg_result);

								  //  Use result to populate SIP2 setings
								  $mysip->AO = $parseACSStatusResponse['variable']['AO'][0]; /* set AO to value returned */
		//						  $mysip->AN = $parseACSStatusResponse['variable']['AN'][0]; /* set AN to value returned */

	/*							  $mysip->patron = $card;
								  $mysip->patronpwd = $pin;
								  $in = $mysip->msgPatronStatusRequest();
								  $msg_result = $mysip->get_message($in);
								  // Make sure the response is 24 as expected
								  if (preg_match("/24/", $msg_result)) {
									  $parsePatronStatusResponse = $mysip->parsePatronStatusResponse( $msg_result );
									  $in = $mysip->msgPatronInformation('none');
									  $parsePatronInfoResponse = $mysip->parsePatronInfoResponse( $mysip->get_message($in) );						
									  if ($parsePatronStatusResponse['variable']['BL'][0] == 'Y' || $parsePatronInfoResponse['variable']['BL'][0] == 'Y') {
										  // Successful Card!!!
										
										 if ($parsePatronStatusResponse['variable']['CQ'][0] == 'Y' || $parsePatronInfoResponse['variable']['CQ'][0] == 'Y') {
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
					else{
						$this -> Session -> setFlash("Authentication server down.");                              
						$this->redirect(array('controller' => 'users', 'action' => 'sdlogin'));

					}
				}
			}
		
		}
	}
	*/
	function slogin_validation(){
	//print_r($_POST);exit;
		if($_POST){
			$card = $_POST['card'];
			$pin = $_POST['pin'];
			$cardNo = $_POST['cardNo'];
			$library_cond = @$_POST['library_cond'];
			$this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');
			$referral = @$_POST['referral'];
			if($referral){
				$existingLibraries = $this->Library->find('all',array(
														'conditions' => array('library_status' => 'active','library_authentication_method' => 'sip2',$library_cond),
														'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
														)
													 );	
			}else{
				$existingLibraries = $this->Library->find('all',array(
													'conditions' => array('library_authentication_num LIKE "%'.$cardNo.'%"','library_status' => 'active','library_authentication_method' => 'sip2',$library_cond),
													'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language')
													)
												 );			
			}
			//print_r($existingLibraries);exit;
			$mysip = new $this->sip2;
			$mysip->hostname = $existingLibraries['0']['Library']['library_host_name'];
			$mysip->port = $existingLibraries['0']['Library']['library_port_no'];
			$mysip->sip_login = $existingLibraries['0']['Library']['library_sip_login'];
			$mysip->sip_password = $existingLibraries['0']['Library']['library_sip_password'];
			$mysip->sip_location = $existingLibraries['0']['Library']['library_sip_location'];
			if($mysip->connect()) {
	
				if(!empty($mysip->sip_login)){
					$sc_login=$mysip->msgLogin($mysip->sip_login,$mysip->sip_password,$mysip->sip_location);
					$mysip->parseLoginResponse($mysip->get_message($sc_login));
				}
		
				//send selfcheck status message
				$in = $mysip->msgSCStatus();
				$msg_result = $mysip->get_message($in);
				// Make sure the response is 98 as expected
				if (preg_match("/98/", $msg_result)) {									
					$result = $mysip->parseACSStatusResponse($msg_result);
						
					//  Use result to populate SIP2 setings
					$mysip->AO = $result['variable']['AO'][0]; /* set AO to value returned */
					$mysip->AN = $result['variable']['AN'][0]; /* set AN to value returned */

					$mysip->patron = $card;
					$mysip->patronpwd = $pin;
					$in = $mysip->msgPatronStatusRequest();
					$msg_result = $mysip->get_message($in);
					//print_r($msg_result);exit;
					// Make sure the response is 24 as expected
					if (preg_match("/^24/", $msg_result)) {
						$result = $mysip->parsePatronStatusResponse( $msg_result );

						if ($result['variable']['BL'][0] == 'Y') {
						// Successful Card!!!

							if ($result['variable']['CQ'][0] == 'Y') {
								echo 'success|login successful';
								exit;
							} 
							else {
								//$this->Session->setFlash("The PIN is Invalid.");
								//$this->redirect(array('controller' => 'users', 'action' => 'slogin'));
								echo "fail|The PIN is Invalid.";
								
								exit;
							}
						}
						else{
							//$this -> Session -> setFlash("The Card Number is Invalid.");                              
							//$this->redirect(array('controller' => 'users', 'action' => 'slogin'));
							 echo "fail|The Card Number is Invalid.";
						 
							exit;
						}								
					}
					else{
						  
						  echo "fail|Authentication server down.";
						  exit;
					}
				}
				else{
					  
					  echo "fail|Authentication server down.";
					  
					  exit;
				}
			}
			else{
				echo "fail|Authentication server down.";
				exit;
			}
		}
	}
	
	
	function snlogin_validation(){
		if($_POST){
			//print_r($_POST);exit;
			$wrongReferral = @$_POST['wrongReferral'];
			$card = @$_POST['card'];
			$patronId = $_POST['patronId'];
			$cardNo = $_POST['cardNo'];
			$referral = @$_POST['referral'];
			$library_cond = @$_POST['library_cond'];
			$this->Library->recursive = -1;
			$this->Library->Behaviors->attach('Containable');
			
			if($referral){
				$existingLibraries = $this->Library->find('all',array(
													'conditions' => array('library_status' => 'active','library_authentication_method' => 'sip2_wo_pin',$library_cond),
													'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_sip_version')
													)
												 );	
			}else{
				$existingLibraries = $this->Library->find('all',array(
													'conditions' => array('library_status' => 'active','library_authentication_method' => 'sip2_wo_pin',$library_cond),
													'fields' => array('Library.id','Library.library_territory','Library.library_authentication_url','Library.library_logout_url','Library.library_territory','Library.library_host_name','Library.library_port_no','Library.library_sip_login','Library.library_sip_password','Library.library_sip_location','Library.library_user_download_limit','Library.library_block_explicit_content','Library.library_language','Library.library_sip_version')
													)
												 );	
			}
			//print_r($existingLibraries);exit;
			//Start
			$mysip = new $this->sip2;
			$mysip->hostname = $existingLibraries['0']['Library']['library_host_name'];
			$mysip->port = $existingLibraries['0']['Library']['library_port_no'];
			$mysip->sip_login = $existingLibraries['0']['Library']['library_sip_login'];
			$mysip->sip_password = $existingLibraries['0']['Library']['library_sip_password'];
			$mysip->sip_location = $existingLibraries['0']['Library']['library_sip_location'];
			$version = $existingLibraries['0']['Library']['library_sip_version'];
			//echo "before connect";
			if($mysip->connect()) {
				echo "connected";exit;
				if(!empty($mysip->sip_login)){
					$sc_login=$mysip->msgLogin($mysip->sip_login,$mysip->sip_password,$mysip->sip_location);
					$mysip->parseLoginResponse($mysip->get_message($sc_login));
				}
				
				//send selfcheck status message
				$in = $mysip->msgSCStatus('','',$version);
				$msg_result = $mysip->get_message($in);
				print_r($msg_result);exit;
				// Make sure the response is 98 as expected
				if (preg_match("/^98/", $msg_result)) {
					  $result = $mysip->parseACSStatusResponse($msg_result);
					  //  Use result to populate SIP2 setings
					  $mysip->AO = $result['variable']['AO'][0]; /* set AO to value returned */
					  $mysip->AN = $result['variable']['AN'][0]; /* set AN to value returned */

					  $mysip->patron = $card;
					  $mysip->patronpwd = '';

					  $in = $mysip->msgPatronStatusRequest();

					  $msg_result = $mysip->get_message($in);
					  // Make sure the response is 24 as expected
					  if(preg_match("/^24/", $msg_result)) {
						  $result = $mysip->parsePatronStatusResponse( $msg_result );
						  if(($result['variable']['BL'][0] == 'Y')){
								// Success!!!
								echo "success|login successful";
								exit;
							}
							else{
								  echo "fail|The Card Number is Invalid.";
								  exit;
							}							
					}
					else{
						echo "fail|Authentication server down.";
						exit;
					}
				}
				else{
					 echo "fail|Authentication server down.";
					 exit;
				}
			}
			else{
				echo "fail|Authentication server down.";
				exit;
			}
				
		}
	}
}
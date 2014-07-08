<?php

class CheckloginusersComponent extends Object {
	
	var $name = 'Checkloginusers';
	var $components = array('Session');
	
	public function checkLoginUser() {
		
		$userArr = array();

		if ($this->Session->read('referral_url') && ($this->Session->read('referral_url') != '')) {
		
			$userArr['email'] 		    = '';
			$userArr['user_login_type'] = 'referral_url';
		
		} elseif ($this->Session->read('innovative') && ($this->Session->read('innovative') != '')) {
		
			$userArr['email'] 		    = '';
			$userArr['user_login_type'] = 'innovative';
		
		} elseif ($this->Session->read('mdlogin_reference') && ($this->Session->read('mdlogin_reference') != '')) {
		
			$userArr['email'] 		    = '';
			$userArr['user_login_type'] = 'mdlogin_reference';
		
		} elseif ($this->Session->read('mndlogin_reference') && ($this->Session->read('mndlogin_reference') != '')) {
		
			$userArr['email'] 		    = '';
			$userArr['user_login_type'] = 'mndlogin_reference';
		
		} elseif ($this->Session->read('innovative_var') && ($this->Session->read('innovative_var') != '')) {
		
			$userArr['email'] 		    = '';
			$userArr['user_login_type'] = 'innovative_var';
		
		} elseif ($this->Session->read('innovative_var_name') && ($this->Session->read('innovative_var_name') != '')) {
		
			$userArr['email'] 		    = '';
			$userArr['user_login_type'] = 'innovative_var_name';
		
		} elseif ($this->Session->read('innovative_var_https_name') && ($this->Session->read('innovative_var_https_name') != '')) {
		
			$userArr['email'] 		    = '';
			$userArr['user_login_type'] = 'innovative_var_https_name';
		
		} elseif ($this->Session->read('innovative_var_https') && ($this->Session->read('innovative_var_https') != '')) {
		
			$userArr['email'] 		    = '';
			$userArr['user_login_type'] = 'innovative_var_https';
		
		} elseif ($this->Session->read('innovative_var_https_wo_pin') && ($this->Session->read('innovative_var_https_wo_pin') != '')) {
		
			$userArr['email'] 		    = '';
			$userArr['user_login_type'] = 'innovative_var_https_wo_pin';
		
		} elseif ($this->Session->read('innovative_https') && ($this->Session->read('innovative_https') != '')) {
		
			$userArr['email']			= '';
			$userArr['user_login_type'] = 'innovative_https';
		
		} elseif ($this->Session->read('innovative_wo_pin') && ($this->Session->read('innovative_wo_pin') != '')) {
		
			$userArr['email'] 		    = '';
			$userArr['user_login_type'] = 'innovative_wo_pin';
		
		} elseif ($this->Session->read('sip2') && ($this->Session->read('sip2') != '')) {
		
			$userArr['email'] 		    = '';
			$userArr['user_login_type'] = 'sip2';
		
		} elseif ($this->Session->read('sip') && ($this->Session->read('sip') != '')) {
		
			$userArr['email'] 		    = '';
			$userArr['user_login_type'] = 'sip';
		
		} elseif ($this->Session->read('innovative_var_wo_pin') && ($this->Session->read('innovative_var_wo_pin') != '')) {
		
			$userArr['email'] 		    = '';
			$userArr['user_login_type'] = 'innovative_var_wo_pin';
		
		} elseif ($this->Session->read('sip2_var') && ($this->Session->read('sip2_var') != '')) {
		
			$userArr['email'] 		    = '';
			$userArr['user_login_type'] = 'sip2_var';
		
		} elseif ($this->Session->read('sip2_var') && ($this->Session->read('sip2_var') != '')) {
		
			$userArr['email']			= '';
			$userArr['user_login_type'] = 'sip2_var_wo_pin';
		
		} elseif ($this->Session->read('sip2_var_wo_pin') && ($this->Session->read('sip2_var_wo_pin') != '')) {
		
			$userArr['email']			= '';
			$userArr['user_login_type'] = 'sip2_var_wo_pin';
		
		} elseif ($this->Session->read('ezproxy') && ($this->Session->read('ezproxy') != '')) {
		
			$userArr['email'] 		    = '';
			$userArr['user_login_type'] = 'ezproxy';
		
		} elseif ($this->Session->read('soap') && ($this->Session->read('soap') != '')) {

			$userArr['email'] 		   = '';
			$userArr['user_login_type'] = 'soap';
		
		} elseif ($this->Session->read('curl_method') && ($this->Session->read('curl_method') != '')) {
		
			$userArr['email'] 		    = '';
			$userArr['user_login_type'] = 'curl_method';
		
		} else {
		
			$userArr['email'] 		    = $this->Session->read('patronEmail');
			$userArr['user_login_type'] = 'user_account';
		}
		
		return $userArr;
	}
}
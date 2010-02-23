<?php
	class AppController extends Controller {
		
		var $components = array('Session', 'DebugKit.Toolbar','Auth');
		
		 var $helpers = array('Session','Html','Ajax','Javascript','Form');
		
		
		/* var $components = array('Session','Auth');
		 var $helpers = array('Session','Html','Ajax','Javascript','Form');*/
    
		 function beforeFilter()
			{
				$this->Auth->userModel = 'Admin';
				$this->Auth->loginAction = array('controller' => 'admins', 'action' => 'login');
				$this->Auth->loginRedirect = array('controller' => 'admin_homes', 'action' => 'index');
				$this->Auth->logoutRedirect = '/admins/login';

			}
		
	}
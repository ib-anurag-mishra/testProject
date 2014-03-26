<?php

/*
 File Name : queues_controller.php
File Description : Queues controller page
Author : m68interactive
*/

class RegisterConcertsController extends AppController{

	var $name = 'RegisterConcerts';
	var $layout = 'home';
	var $helpers = array( 'Html', 'Form', 'Session', 'Page');
	var $components = array('Session', 'Auth', 'Acl');
	var $uses = array( 'RegisterConcert', 'User', 'Page');

	function beforeFilter(){
		 
		parent::beforeFilter();
		$this->Auth->allow('ajax_submit_register_concert', 'great_fall_concert');
	}


	/*
	 Function Name : ajax_submit_register_concert
	Desc : Ajax function used for submitting registeration concert details
	*
	*
	* @return Message
	*/

	function ajax_submit_register_concert()
	{
		$this -> layout = 'ajax';
		 
		if(empty($this->data['first_name']) || empty($this->data['last_name']) || empty($this->data['phone_no']))
		{
			$this->set('Message', "Failure");

		}
		else
		{
			$this->data['RegisterConcert']['first_name']    = $this->data['first_name'];
			$this->data['RegisterConcert']['last_name']     = $this->data['last_name'];
			$this->data['RegisterConcert']['library_card']  = $this->Session->read('patron');
			$this->data['RegisterConcert']['phone_no']      = $this->data['phone_no'];
			$this->data['RegisterConcert']['library_id']    = $_POST['library_id'];
			$this->data['RegisterConcert']['created']       = date('Y-m-d H:i:s');

			$this->RegisterConcert->setDataSource('master');
			if($this->RegisterConcert->save($this->data['RegisterConcert']))
			{
				$this->set('Message', '<font style="color:green;">Thanks for entering the Concert Ticket Giveaway.</font>');
			}
			else
			{
				$this->set('Message', "There was error while storing the details.");
			}

			$this->RegisterConcert->setDataSource('default');

		}
		 
	}

	/*
	 Function Name : aboutus
	Desc : CMS Page for Great Fall Concert
	*/
	function great_fall_concert()
	{
		$this->layout = 'home';
	}
}
?>
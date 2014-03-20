<?php
/* File Name: maintenance_controller.php
 File Description: This is used to take the site into maintenace mode
Author: m68interactive
*/
class MaintenanceController extends AppController {
	var $name = 'Maintenance';
	var $autoLayout = false;
	var $uses = array();

	function index() {
		// force 404 if not in maintenance mode
		if (Configure::load('maintenance.settings') && !Configure::read('Server.maintenance'))
		{
			$this->cakeError('error404');
		}

		Configure::write('debug', 0);
		$this->header('HTTP/1.1 503 Service Temporarily Unavailable');
		$this->header('Retry-After: ' . 3600);
	}
}

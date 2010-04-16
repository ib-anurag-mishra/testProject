<?php
class AppError extends ErrorHandler {
	var $layout = 'home';
	
	function missingAction($messages = array()) {
		// return parent::missingAction($messages);
		
		$this->controller->redirect(array_merge(
			array('controller' => 'homes', 'action' => 'index'),
			$this->controller->params['pass']
		));
		
	}
	
	function missingController($messages = array()) {
		// return parent::missingController($messages);
		
		$this->controller->redirect(array_merge(
			array('controller' => 'homes', 'action' => 'index'),
			$this->controller->params['pass']
		));
		
	}
}
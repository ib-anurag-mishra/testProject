<?php

class PageHelper extends AppHelper {
    var $uses = array('Page');
    var $helpers = array('Session');
    
    function getPageContent($type) {
		if($this->Session->read('Config.language') == ''){
			$page = 'en';
		} 
		else {
			$page = $this->Session->read('Config.language');
		}
        $pageInstance = ClassRegistry::init('Page');
        $pageDetails = $pageInstance->find('all', array('conditions' => array('page_name' => $type, 'language' => $page)));
        if(count($pageDetails) != 0) {
            return $pageDetails[0]['Page']['page_content'];
        }
        else {
            return "Coming Soon....";
        }
    }
}

?>
<?php

class PageHelper extends AppHelper {
    var $uses = array('Page');
    var $helpers = array('Session');
    
    function getPageContent($type) {
        $pageInstance = ClassRegistry::init('Page');
        $pageDetails = $pageInstance->find('all', array('conditions' => array('page_name' => $type, 'language' => $this->Session->read('Config.language'))));
        if(count($pageDetails) != 0) {
            return $pageDetails[0]['Page']['page_content'];
        }
        else {
            return "Coming Soon....";
        }
    }
}

?>
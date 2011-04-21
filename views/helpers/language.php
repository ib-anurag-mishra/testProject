<?php

class LanguageHelper extends AppHelper {
    var $uses = array('Language');
    var $helpers = array('Session');
    
    function getLanguage() {
        $languageInstance = ClassRegistry::init('Language');
        $languageDetails =  $languageInstance->find('list', array('fields' => array('id_language', 'full_name')));
        return $languageDetails;
    }
}

?>
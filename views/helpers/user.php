<?php

class UserHelper extends AppHelper {
    var $uses = array('Group');
    
    function getAdminType($id) {
        $groupInstance = ClassRegistry::init('Group');
        $getAdminTypes = $groupInstance->find('first', array('conditions' => array('id' => $id)));
        return $getAdminTypes['Group']['type'];
    }
}

?>
<?php

class WishlistHelper extends AppHelper {
    var $uses = array('Wishlist');
    
    function getWishlistData($id) {
        $wishlistInstance = ClassRegistry::init('Wishlist');
        $libraryId = $_SESSION['library'];
        $patronId = $_SESSION['patron'];
        $startDate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-(date('w', mktime(0, 0, 0, date('m'), date('d'), date('Y')))-1), date('Y')))." 00:00:00";
        $endDate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')+(7-date('w', mktime(0, 0, 0, date('m'), date('d'), date('Y')))), date('Y')))." 23:59:59";
        $wishlistDetails = $wishlistInstance->find('all', array('conditions' => array('library_id' => $libraryId,'patron_id' => $patronId,'week_start_date' => $startDate,'week_end_date' => $endDate,'ProdID' => $id)));
        if(count($wishlistDetails) != 0) {
            return "Added to Wishlist";
        }
        else {
            return "Add to wishlist";
        }
    }
}

?>
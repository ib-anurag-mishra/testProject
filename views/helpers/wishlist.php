<?php

class WishlistHelper extends AppHelper {
    var $uses = array('Wishlist');
    
    function getWishlistData($id) {
        $wishlistInstance = ClassRegistry::init('Wishlist');
        $libraryId = $_SESSION['library'];
        $patronId = $_SESSION['patron'];        
        $wishlistDetails = $wishlistInstance->find('all', array('conditions' => array('library_id' => $libraryId,'patron_id' => $patronId, 'ProdID' => $id)));
        if(count($wishlistDetails) != 0) {
            return "Added to Wishlist";
        }
        else {
            return "Add to wishlist";
        }
    }
    
    function getWishlistCount() {
        $wishlistInstance = ClassRegistry::init('Wishlist');
        $libraryId = $_SESSION['library'];
        $patronId = $_SESSION['patron'];        
        $wishlistCount = $wishlistInstance->find('count', array('conditions' => array('library_id' => $libraryId,'patron_id' => $patronId)));
        return $wishlistCount;
    }
}

?>
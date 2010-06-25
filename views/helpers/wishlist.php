<?php

class WishlistHelper extends AppHelper {
    var $helpers = array('Session');	
    var $uses = array('Wishlist');
    
    function getWishlistData($id) {
        $wishlistInstance = ClassRegistry::init('Wishlist');
        $libraryId = $this->Session->read('library');
        $patronId = $this->Session->read('patron');  
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
        $libraryId = $this->Session->read('library');
		$patronId = $this->Session->read('patron');
        $wishlistCount = $wishlistInstance->find('count', array('conditions' => array('library_id' => $libraryId,'patron_id' => $patronId)));
        return $wishlistCount;
    }
}

?>
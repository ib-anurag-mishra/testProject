<?php
/*
	 File Name : wishlist.php
	 File Description : helper file for getting wishlist information
	 Author : m68interactive
 */
class WishlistVideoHelper extends AppHelper {
    var $helpers = array('Session');	
    var $uses = array('WishlistVideo');
    
    function getWishlistVideoData($id) {
        echo 'prodid=>'.$id;
        $wishlistInstance = ClassRegistry::init('WishlistVideo');
        $libraryId = $this->Session->read('library');
        $patronId = $this->Session->read('patron');  
        $wishlistDetails = $wishlistInstance->find('all', array('conditions' => array('library_id' => $libraryId,'patron_id' => $patronId, 'ProdID' => $id)));
        if(count($wishlistDetails) != 0) {
            echo 147;
            return "Added to Wishlist";
        }
        else {
            echo 478;
            return "Add to wishlist";
        }
    }
    
    function getWishlistVideoCount() {
        $wishlistInstance = ClassRegistry::init('WishlistVideo');
        $libraryId = $this->Session->read('library');
		$patronId = $this->Session->read('patron');
        $wishlistCount = $wishlistInstance->find('count', array('conditions' => array('library_id' => $libraryId,'patron_id' => $patronId)));
        return $wishlistCount;
    }
}

?>
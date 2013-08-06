<?php
/*
	 File Name : wishlist.php
	 File Description : helper file for getting wishlist information
	 Author : m68interactive
 */
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
    
    function getWishListMarkup($wishlistInfo,$song_ProdId,$song_Provider_Type){
        if($wishlistInfo == 'Added to Wishlist') {
                $str =  '<a class="add-to-wishlist" href="javascript:void(0);">'.__("Added to Wishlist").'</a>';
         } else { 
                $str = '<span class="beforeClick" id="wishlist'.$song_ProdId.'"><a class="add-to-wishlist" href=\'JavaScript:void(0);\' onclick=\'Javascript: addToWishlist("'.$song_ProdId.'","'.$song_Provider_Type.'");\'>'.__("Added to Wishlist").'</a></span>
                 <span class="afterClick" id="downloading_'.$song_ProdId.'" style="display:none;"><a class="add-to-wishlist" href=\'JavaScript:void(0);\'>'."Please Wait...".'</a></span>';
         }
         return $str;

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
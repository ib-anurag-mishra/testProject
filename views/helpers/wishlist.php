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
        //Configure::write('debug', 2);
        $wishlistInstance = ClassRegistry::init('Wishlist');
        $libraryId = $this->Session->read('library');
        $patronId = $this->Session->read('patron');  
        $wishlistDetails = $wishlistInstance->find('all', array(
            'conditions' => array('library_id' => $libraryId,'patron_id' => $patronId, 'ProdID' => $id),
            'fields' => array('ProdID')
            ));
             
        if(count($wishlistDetails) != 0) {
            return "Added To Wishlist";
        }
        else {
            return "Add To Wishlist";
        }
    }
    
    function getWishListMarkup($wishlistInfo,$song_ProdId,$song_Provider_Type){
        if($wishlistInfo == 'Added To Wishlist') {
                $str =  '<a class="add-to-wishlist" href="javascript:void(0);">'."Added To Wishlist".'</a>';
         } else { 
                $str = '<span class="beforeClick" id="wishlist'.$song_ProdId.'"><a class="add-to-wishlist" href=\'JavaScript:void(0);\' onclick=\'Javascript: addToWishlist("'.$song_ProdId.'","'.$song_Provider_Type.'");\'>'."Add To Wishlist".'</a></span>
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
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
        //echo 'prodid=>'.$id;
        $wishlistInstance = ClassRegistry::init('WishlistVideo');
        $libraryId = $this->Session->read('library');
        $patronId = $this->Session->read('patron');  
        $wishlistDetails = $wishlistInstance->find('all', array('conditions' => array('library_id' => $libraryId,'patron_id' => $patronId, 'ProdID' => $id)));
        if(count($wishlistDetails) != 0) {
           //echo 147;
            return "Added to Wishlist";
        }
        else {
            //echo 478;
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
    
    function getWishListVideoMarkup($wishlistInfo,$video_ProdId,$video_Provider_Type){
        if($wishlistInfo == 'Added to Wishlist') {
                $str =  '<a class="add-to-wishlist" href="javascript:void(0);">'."Added to Wishlist".'</a>';
         } else { 
                $str = '<span class="beforeClick" id="video_wishlist'.$video_ProdId.'"><a class="add-to-wishlist" href=\'JavaScript:void(0);\' onclick=\'Javascript: addToWishlistVideo("'.$video_ProdId.'","'.$video_Provider_Type.'");\'>'."Add to Wishlist".'</a></span>
                 <span class="afterClick" id="downloading_'.$video_ProdId.'" style="display:none;"><a class="add-to-wishlist" href=\'JavaScript:void(0);\'>'."Please Wait...".'</a></span>';
         }
         return $str;

    }    
}

?>
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
		$wishlistVariArray = array();


		//create common structure for add to wishlist functionality
		//first check if session variable not set
		if(!$this->Session->read('wishlistVariArray') ){
			 
			$wishlistDetails = $wishlistInstance->find('all', array(
					'conditions' => array('library_id' => $libraryId,'patron_id' => $patronId, 'ProdID' => $id),
					'fields' => array('ProdID')
			));


			if(count($wishlistDetails) != 0) {
				return "Added to Wishlist";
			}
			else {
				return "Add to wishlist";
			}
		}else{
			 
			$wishlistVariArray = $this->Session->read('wishlistVariArray');
			$wishlistVariArray= array_unique($wishlistVariArray);

			if(!empty($wishlistVariArray)){
				if (in_array($id, $wishlistVariArray)) {
					return "Added To Wishlist";
				}else{
					return "Add To Wishlist";
				}
			}else{
				return "Add To Wishlist";
			}
		}

	}

	function getWishListMarkup($wishlistInfo,$song_ProdId,$song_Provider_Type){
		if($wishlistInfo == 'Added To Wishlist') {
			$str =  '<a class="add-to-wishlist added-to-wishlist" href="javascript:void(0);">' . __('Added To Wishlist', true) . '</a>';
		} else {
			$str = '<span class="beforeClick" id="wishlist'.$song_ProdId.'"><a class="add-to-wishlist" href=\'JavaScript:void(0);\' onclick=\'Javascript: addToWishlist("'.$song_ProdId.'","'.$song_Provider_Type.'");\'>'."Add To Wishlist".'</a></span>
			<span class="afterClick" id="downloading_'.$song_ProdId.'" style="display:none;"><a class="add-to-wishlist" href=\'JavaScript:void(0);\'>' . __('Please Wait', true) . '...</a></span>';
		}
		return $str;

	}

	function getAlbumWishListMarkup($albumId,$providerType,$artistText){

		$str = <<<EOD
            <a onclick="javascript:addAlbumToWishlist($albumId,'$providerType','$artistText');"   href="javascript:void(0);" ><button class="wishlist-icon toggleable"></button></a>
EOD;
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
<?php

/*
  File Name : wishlist.php
  File Description : helper file for getting wishlist information
  Author : m68interactive
 */

class WishlistVideoHelper extends AppHelper
{

    var $helpers = array('Session');
    var $uses = array('WishlistVideo');

    function getWishlistVideoData($id)
    {
        //echo 'prodid=>'.$id;
        $wishlistInstance = ClassRegistry::init('WishlistVideo');
        $libraryId = $this->Session->read('library');
        $patronId = $this->Session->read('patron');

        //create common structure for add to wishlist functionality
        //first check if session variable not set
        if (!$this->Session->check('wishlistVideoArray'))
        {
            $wishlistDetails = $wishlistInstance->find(
                    'all', array('conditions' =>
                array(
                    'library_id' => $libraryId,
                    'patron_id' => $patronId,
                    'ProdID' => $id
                )
            ));
            if (count($wishlistDetails) != 0)
            {
                return "Added To Wishlist";
            }
            else
            {
                return "Add To Wishlist";
            }
        }
        else
        {
            $wishlistVideoArray = $this->Session->read('wishlistVideoArray');
            $wishlistVideoArray = @array_unique($wishlistVideoArray);

            if (!empty($wishlistVideoArray))
            {
                if (in_array($id, $wishlistVideoArray))
                {
                    return "Added To Wishlist";
                }
                else
                {
                    return "Add To Wishlist";
                }
            }
            else
            {
                return "Add To Wishlist";
            }
        }
    }

    function getWishlistVideoCount()
    {
        $wishlistInstance = ClassRegistry::init('WishlistVideo');
        $libraryId = $this->Session->read('library');
        $patronId = $this->Session->read('patron');
        $wishlistCount = $wishlistInstance->find('count', array('conditions' => array('library_id' => $libraryId, 'patron_id' => $patronId)));
        return $wishlistCount;
    }

    function getWishListVideoMarkup($wishlistInfo, $video_ProdId, $video_Provider_Type)
    {
        if ($wishlistInfo == 'Added To Wishlist')
        {
            $str = '<a class="add-to-wishlist" href="javascript:void(0);">' . "Added To Wishlist" . '</a>';
        }
        else
        {
            $str = '<span class="beforeClick" id="video_wishlist' . $video_ProdId . '"><a class="add-to-wishlist" href=\'JavaScript:void(0);\' onclick=\'Javascript: addToWishlistVideo("' . $video_ProdId . '","' . $video_Provider_Type . '");\'>' . "Add To Wishlist" . '</a></span>
                 <span class="afterClick" id="downloading_' . $video_ProdId . '" style="display:none;"><a class="add-to-wishlist" href=\'JavaScript:void(0);\'>' . "Please Wait..." . '</a></span>';
        }
        return $str;
    }

}

?>
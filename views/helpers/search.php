<?php
 /*
	 File Name : Search.php
	 File Description : helper file for getting search detail
	 Author : m68interactive
 */

class SearchHelper extends AppHelper {
	
	var $uses 	 = array('Search');
	var $helpers = array('Html', 'Album', 'Token');
    
    public function checkSongStatusForSearch( $territoryStatus, $territorySalesDate, $territory ) {

    	$songStatus 	 = $this->songStatus( $territoryStatus, $territory );
    	$salesDateStatus = $this->songStatus( $territorySalesDate, $territory );

    	if( ( $songStatus == 1 ) && ( $salesDateStatus <= date( 'Y-m-d' ) ) ) {
    		return 1;
    	} else {
    		return 0;
    	}
    }
    
    public function songStatus( $args, $territory ) {

    	$status = '';

    	if( is_array( $args ) ) {
    		foreach ( $args as $arg ) {
    			$arrStatus = explode( "_", $arg );
    	
    			if( isset( $arrStatus[0] ) && ( $arrStatus[0] === $territory ) ) {
    				$status = trim( $arrStatus[1] );
    			}
    		}
    	} else {
    		$arrStatus = explode( "_", $args );
    	
    		if( isset( $arrStatus[0] ) && ( $arrStatus[0] === $territory ) ) {
    			$status = trim( $arrStatus[1] );
    		}
    	}
    	
    	return $status;
    }

    public function createPagination( $currentPage, $facetPage, $type = 'listing', $totalPages, $pageLimitToShow, $queryString = null ) {
    
    	$queryString 	= html_entity_decode( $queryString );
    	$pagination_str = '';
    
    	if ( $totalPages > 1 ) {
    
    		$part = floor( $pageLimitToShow / 2 );
    
    		if ( $type == 'listing' ) {
    
    			if ( $currentPage != 1 ) {
    
    				$pagination_str .= $this->Html->link( $this->Html->tag( 'button', '', array( 'class' => 'beginning' ) ), array( 'controller' => 'search', 'action' => 'index', $queryString ), array( 'escape' => FALSE ) );
    				$pagination_str .= $this->Html->link( $this->Html->tag( 'button', '', array( 'class' => 'prev' ) ), array( 'controller' => 'search', 'action' => 'index', ( $currentPage - 1 ), $facetPage, $queryString ), array( 'escape' => FALSE ) );
    
    			} else {
    
    				$pagination_str .= $this->Html->tag('button', '', array('class' => 'beginning', 'style' => 'cursor:text;', 'escape' => FALSE));
    				$pagination_str .= $this->Html->tag('button', '', array('class' => 'prev', 'style' => 'cursor:text;', 'escape' => FALSE));
    			}
    
    		} else if ( $type == 'block' ) {
    
    			if ( $facetPage != 1 ) {
    
    				$pagination_str .= $this->Html->link( $this->Html->tag( 'button', '', array( 'class' => 'beginning' ) ), array( 'controller' => 'search', 'action' => 'index', $queryString ), array( 'escape' => FALSE ) );
    				$pagination_str .= $this->Html->link( $this->Html->tag( 'button', '', array( 'class' => 'prev' ) ), array( 'controller' => 'search', 'action' => 'index', $currentPage, ( $facetPage - 1 ), $queryString ), array( 'escape' => FALSE ) );
    
    			} else {
    				$pagination_str .= $this->Html->tag('button', '', array('class' => 'beginning', 'style' => 'cursor:text;', 'escape' => FALSE));
    				$pagination_str .= $this->Html->tag('button', '', array('class' => 'prev', 'style' => 'cursor:text;', 'escape' => FALSE));
    			}
    		}
    
    		$pagination_str .= " ";
    
    		if ( $type == 'listing' ) {
    
    			if ( $currentPage <= $part ) {
    
    				$fromPage = 1;
    				$topage   = $pageLimitToShow <= $totalPages ? $pageLimitToShow : $totalPages;
    
    			} elseif ( $currentPage >= ( $totalPages - $part ) ) {

    				$fromPage = ($currentPage >= $totalPages) ? $totalPages - ($pageLimitToShow - 1) : (($currentPage - ($pageLimitToShow - ($totalPages - $currentPage))) + 1);
    				$topage   = $totalPages;
    				$fromPage = $fromPage > 1 ? $fromPage : 1;
    
    			} else {
    				$fromPage = $currentPage - $part;
    				$topage   = $currentPage + $part;
    			}
    
    		} else if ( $type == 'block' ) {
    
    			if ( $facetPage <= $part ) {
    
    				$fromPage = 1;
    				$topage   = $facetPage + ($pageLimitToShow - $facetPage);
    				$topage   = $topage <= $totalPages ? $topage : $totalPages;
    
    			} elseif ( $facetPage >= ( $totalPages - $part ) ) {

    				$fromPage = ($facetPage >= $totalPages) ? $totalPages - ($pageLimitToShow - 1) : (($facetPage - ($pageLimitToShow - ($totalPages - $facetPage))) + 1);
    				$topage   = $totalPages;
    				$fromPage = $fromPage > 1 ? $fromPage : 1;
    
    			} else {
    				$fromPage = $facetPage - $part;
    				$topage   = $facetPage + $part;
    			}
    		}
    
    		$classCounter = 1;
    
    		if ( isset( $fromPage ) && isset( $topage ) ) {
    
    			for ( $pageCount = $fromPage; $pageCount <= $topage; $pageCount++ ) {
    
    				if ( $type == 'listing' ) {
    					if ( $currentPage == $pageCount ) {
    						$pagination_str .= $this->Html->tag( 'button', $pageCount, array( 'class' => 'page-' . $classCounter, 'style' => 'cursor:text; background: none repeat scroll 0 0 #808080;color: #FFFFFF;', 'escape' => false ) );
    
    					} else {
    						$pagination_str .= $this->Html->link( $this->Html->tag( 'button', $pageCount, array( 'class' => 'page-' . $classCounter ) ), array( 'controller' => 'search', 'action' => 'index', $pageCount, $facetPage, $queryString ), array('escape' => FALSE));
    					}
    
    				} else if ( $type == 'block' ) {
    
    					if ( $facetPage == $pageCount ) {
    
    						$pagination_str .= $this->Html->tag( 'button', $pageCount, array( 'class' => 'page-' . $classCounter, 'style' => 'cursor:text; background: none repeat scroll 0 0 #808080;color: #FFFFFF;', 'escape' => false ) );
    
    					} else {
    						$pagination_str .= $this->Html->link( $this->Html->tag( 'button', $pageCount, array( 'class' => 'page-' . $classCounter ) ), array( 'controller' => 'search', 'action' => 'index', $currentPage, $pageCount, $queryString ), array('escape' => FALSE));
    					}
    				}
    
    				$pagination_str .= " ";
    				$classCounter++;
    			}
    		}
    
    		$pagination_str .= " ";
    
    		if ( $type == 'listing' ) {
    
    			if ( $currentPage != $totalPages ) {
    				$pagination_str .= $this->Html->link( $this->Html->tag( 'button', '', array( 'class' => 'next' ) ), array( 'controller' => 'search', 'action' => 'index', ( $currentPage + 1 ), $facetPage, $queryString ), array('escape' => FALSE));
    				$pagination_str .= $this->Html->link( $this->Html->tag( 'button', '', array( 'class' => 'last' ) ), array( 'controller' => 'search', 'action' => 'index', $totalPages, $facetPage, $queryString ), array('escape' => FALSE));
    			} else {
    				$pagination_str .= '<button class="next" style="cursor:text;"></button>';
    				$pagination_str .= '<button class="last" style="cursor:text;"></button>';
    			}
    		} else if ( $type == 'block' ) {
    
    			if ($facetPage != $totalPages) {
    				$pagination_str .= $this->Html->link( $this->Html->tag( 'button', '', array( 'class' => 'next' ) ), array( 'controller' => 'search', 'action' => 'index', $currentPage, ( $facetPage + 1 ), $queryString ), array('escape' => FALSE));
    				$pagination_str .= $this->Html->link( $this->Html->tag( 'button', '', array( 'class' => 'last' ) ), array( 'controller' => 'search', 'action' => 'index', $currentPage, $totalPages, $queryString ), array('escape' => FALSE));
    			} else {
    				$pagination_str .= $this->Html->tag('button', '', array('class' => 'next', 'style' => 'cursor:text;', 'escape' => FALSE));
    				$pagination_str .= $this->Html->tag('button', '', array('class' => 'last', 'style' => 'cursor:text;', 'escape' => FALSE));
    			}
    		}
    	}
    
    	return $pagination_str;
    }
    
    public function truncateText( $text, $char_count, $obj = null, $truncateByWord = true ) {
    
    	if ( strlen( $text ) > $char_count ) {
    
    		$modified_text = substr( $text, 0, $char_count );
    
    		if ( $truncateByWord == true ) {
    			$modified_text = substr( $modified_text, 0, strrpos( $modified_text, " ", 0 ) );
    		}
    
    		$modified_text = substr( $modified_text, 0, $char_count ) . "...";
    
    	} else {
    		$modified_text = $text;
    	}
    
    	return $obj->getTextEncode( $modified_text );
    }
    
    //Code for check Sales date
    public function getSalesDate( $sales_date_array, $country ) {
    
    	$sales_date = '';
    
    	if ( is_array( $sales_date_array ) ) {
    
    		foreach ( $sales_date_array as $territorySalesDate ) {
    
    			$territory_date_array = explode( "_", $territorySalesDate );
    
    			if ( is_array( $territory_date_array ) ) {
    				$territory = $territory_date_array[0];
    			}
    
    			if ( $country == $territory ) {
    				$sales_date = $territory_date_array[1];
    				break;
    			}
    		}
    	}
    
    	return $sales_date;
    }
    
    public function getAlbumInfo( $palbum, $obj = null ) {
    
    	$albumDetails = $this->Album->getImage( $palbum->ReferenceID, $palbum->provider_type );
    
    	if ( isset( $albumDetails[0]['Files']['CdnPath'] ) && isset( $albumDetails[0]['Files']['SourceURL'] ) && !empty( $albumDetails[0]['Files']['CdnPath'] ) && !empty( $albumDetails[0]['Files']['SourceURL'] ) ) {
    
    		$albumArtwork = $this->Token->artworkToken( $albumDetails[0]['Files']['CdnPath'] . "/" . $albumDetails[0]['Files']['SourceURL'] );
    		$image 		  = Configure::read( 'App.Music_Path' ) . $albumArtwork;
    	} else {
    		$image = 'no-image.jpg';
    	}

    	$arrAlbumInfo	  				  = array();
    	$arrAlbumInfo['album_title'] 	  = $this->truncateText( $obj->getTextEncode( $palbum->AlbumTitle ), 24, $obj, false );
    	$arrAlbumInfo['album_genre'] 	  = str_replace( '"', '', $palbum->Genre );
    	$arrAlbumInfo['linkArtistText']   = str_replace( '/', '@', base64_encode( $palbum->ArtistText ) );
    	$arrAlbumInfo['linkProviderType'] = base64_encode( $palbum->provider_type );
    	$arrAlbumInfo['image'] 			  = $image;
    
    	if ( isset( $palbum->Label ) && !empty( $palbum->Label ) ) {
    		$album_label_str = "Label: " . $this->truncateText( $palbum->Label, 32, $obj );
    	} else {
    		$album_label_str = "";
    	}

    	if ( isset( $palbum->AAdvisory ) && $palbum->AAdvisory == 'T') {
    		$explicit = '<font class="explicit"> (Explicit)</font><br />';
    	} else {
    		$explicit = '';
    	}

    	$arrAlbumInfo['album_label_str'] = $album_label_str;
    	$arrAlbumInfo['explicit']		 = $explicit;
    	
    	return $arrAlbumInfo;
    }
}
?>
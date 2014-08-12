<?php

/* File Name: homes_controller.php
  File Description: Displays the home page for each patron
  Author: Maycreate
 */

class SearchController extends AppController {

    var $name 		= 'Search';
    var $helpers 	= array( 'Html', 'Ajax', 'Javascript', 'Form', 'Library', 'Page', 'Wishlist', 'Song', 'Language', 'Album', 'Session', 'WishlistVideo', 'Mvideo', 'Search', 'Queue', 'Token' );
    var $components = array( 'Auth', 'Downloads', 'Solr', 'Session' );
    var $uses 		= array( 'Searchrecord','LatestDownload','LatestVideodownload' );

    /*
      Function Name : beforeFilter
      Desc : actions that needed before other functions are getting called
     */

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow( 'index', 'autocomplete','ajaxcheckdownload','index_new' );
    }

    public function index( $page = 1, $facetPage = 1 ) {

        // reset page parameters when serach keyword changes
        // to check if the search is made from search bar or click on search page
        $layout 		= isset( $this->params['url']['layout'] ) ? $this->params['url']['layout'] : '';
        $searchedString = isset( $this->params['url']['q'] ) ? trim( $this->params['url']['q'] ) : '';
        $searchedType	= isset( $this->params['url']['type'] ) ? strtolower( trim( $this->params['url']['type'] ) ) : '';
        
        if ( ! empty( $searchedString ) && ! empty( $searchedType ) ) {
        	//sets values in session
        	$this->Session->write('SearchReq.word', $searchedString);
        	$this->Session->write('SearchReq.type', $searchedType);

        } else if ( empty( $searchedString ) || empty( $searchedType ) ) {
            $this->Session->delete('SearchReq'); // unset session when no params
        }

        //reset session & redirect to 1st page
        if ( $this->Session->check('SearchReq') && $this->Session->read('SearchReq.word') != $searchedString && $this->Session->read('SearchReq.type') == $searchedType ) {
        	$this->Session->delete('SearchReq');
        	$this->redirect( array( 'controller' => 'search', 'action' => 'index?q=' . $searchedString . '&type=' . $searchedType ) );
        }

        $queryVar  = null;
        $typeVar   = 'all';

        if ( ! empty( $searchedString ) ) {
            $queryVar = $searchedString;
        }

        if ( ! empty( $searchedType ) ) {
            $arrSearchType = array( 'all', 'song', 'album', 'genre', 'label', 'artist', 'composer', 'video' );

            if( in_array( $searchedType, $arrSearchType ) ) {
            	$typeVar = $searchedType;
            }
        }

        $this->set( 'type', $typeVar );

        if ( !empty( $queryVar ) ) {

            //Added code for log search data
            $insertArr[] = $this->searchrecords( $typeVar, $queryVar );
            $this->Searchrecord->saveAll( $insertArr );
            //End Added code for log search data

            $patronId  = $this->Session->read( 'patron' );
            $libraryId = $this->Session->read( 'library' );

            if ( !empty( $patronId ) ) {
            	$libraryDownload = $this->Downloads->checkLibraryDownload( $libraryId );
            	$patronDownload  = $this->Downloads->checkPatronDownload( $patronId, $libraryId );
            	$this->set( 'libraryDownload', $libraryDownload );
            	$this->set( 'patronDownload', $patronDownload );
            }

            $total = 0;
            $limit = 10;

            if ( !isset( $page ) || $page < 1 ) {
                $page = 1;
            }

            if ( !isset( $facetPage ) || $facetPage < 1 ) {
                $facetPage = 1;
            }

            /* To do: 
             * 		We need to remove below two variables and also remove these arguments from search function of Solr component
            */
            $sortVar   = 'ArtistText';
            $sortOrder = 'asc';
            /**********************************************************************************************************/

            $country 	= $this->Session->read( 'territory' );
            
            if ( $typeVar == 'all' || $typeVar == 'song' || $typeVar == 'video' ) {

	            $songs   	= $this->Solr->search( $queryVar, $typeVar, $sortVar, $sortOrder, $page, $limit, $country );
	            //$total 	 	= $this->Solr->total;
	            //$totalPages = ceil( $total / $limit );
	            $lastPage 	= isset( $songs['lastPage'] ) ? $songs['lastPage'] : '';
	            $lastPage 	= ceil($lastPage / $limit);
	            $songArray 	= array();
	
	            if ( is_array( $songs ) && count( $songs ) > 0  && !empty( $patronId ) ) {
	            	foreach ( $songs as $key => $song ) {
	            		if( isset( $song->ProdID ) && !empty( $song->ProdID ) ) {
	            			$songArray[$key] = (int) $song->ProdID;
	            		}
	
	            		$songs[$key]->status = 'not';
	            	}
	
	            	if ( is_array( $songArray ) && count( $songArray ) > 0 ) {
	
	            		if ( $typeVar == 'video' ) {
	            			$arrayIndex	   = 'LatestVideodownload';
	            			$downloadsUsed = $this->LatestVideodownload->find( 'all', array( 'conditions' => array( 'LatestVideodownload.ProdID IN (' . implode( ',', $songArray ) . ')', 'library_id' => $libraryId, 'patron_id' => $patronId, 'history < 2', 'created BETWEEN ? AND ?' => array( Configure::read( 'App.twoWeekStartDate' ), Configure::read( 'App.twoWeekEndDate' ) ) ) ) );
	            		} else {
	            			$arrayIndex	   = 'LatestDownload';
	            			$downloadsUsed = $this->LatestDownload->find( 'all', array( 'conditions' => array( 'LatestDownload.ProdID in (' . implode( ',', $songArray ) . ')', 'library_id' => $libraryId, 'patron_id' => $patronId, 'history < 2', 'created BETWEEN ? AND ?' => array( Configure::read( 'App.twoWeekStartDate' ), Configure::read( 'App.twoWeekEndDate' ) ) ) ) );
	            		}
	            		
	            		if ( isset( $downloadsUsed ) && is_array( $downloadsUsed ) && count( $downloadsUsed ) > 0 ) {
	
	            			foreach ( $downloadsUsed as $downloadKey => $downloadData ) {
	
	            				if ( in_array( $downloadData[$arrayIndex]['ProdID'],  $songArray ) ) {
	
	            					$key = array_search( $downloadData[$arrayIndex]['ProdID'], $songArray );
	            					$songs[$key]->status = 'avail';
	            				}
	            			}
	            		}
	            	}
	            }

	            $this->set( 'lastPage', $lastPage );
	            $this->set( 'songs', $songs );
            }

            if ( !empty( $typeVar ) && $typeVar != 'all' ) {

                switch ( $typeVar ) {

                    case 'album':
                        $limit = 12;
                        //$totalFacetCount = $this->Solr->getFacetSearchTotal( $queryVar, 'album' );
                        $arr_albumStream = array();
                        $albums 		 = $this->Solr->groupSearch( $queryVar, 'album', $facetPage, $limit );
                        $totalFacetCount = $albums['ngroups'];

                        foreach ( $albums as $objKey => $objAlbum ) {
                        	
                        	if ( !is_object( $objAlbum ) ) {
                        		continue;
                        	}

                            $arr_albumStream[$objKey]['albumSongs'] = $this->requestAction(
                                    array( 'controller' => 'artists', 'action' => 'getAlbumSongs' ), array('pass' => array(base64_encode( $objAlbum->ArtistText ), $objAlbum->ReferenceID, base64_encode( $objAlbum->provider_type ), 1 ) )
                            );
                        }

                        $this->set( 'albumData', $albums );
                        $this->set( 'arr_albumStream', $arr_albumStream );
                        break;

                    case 'genre':
                        $limit = 30;
                        //$totalFacetCount = $this->Solr->getFacetSearchTotal( $queryVar, 'genre' );
                        $genres = $this->Solr->groupSearch( $queryVar, 'genre', $facetPage, $limit );
                        $this->set( 'genres', $genres );
                        break;

                    case 'label':
                        $limit = 18;
                        //$totalFacetCount = $this->Solr->getFacetSearchTotal( $queryVar, 'label' );
                        $labels = $this->Solr->groupSearch( $queryVar, 'label', $facetPage, $limit );
                        $this->set( 'labels', $labels );
                        break;

                    case 'artist':
                        $limit = 18;
                        //$totalFacetCount = $this->Solr->getFacetSearchTotal( $queryVar, 'artist' );
                        $artists = $this->Solr->groupSearch( $queryVar, 'artist', $facetPage, $limit );
                        $this->set( 'artists', $artists );
                        break;

                    case 'composer':
                        $limit = 18;
                        //$totalFacetCount = $this->Solr->getFacetSearchTotal( $queryVar, 'composer' );
                        $composers = $this->Solr->groupSearch( $queryVar, 'composer', $facetPage, $limit );
                        $this->set( 'composers', $composers );
                        break;
                }

                if ( isset( $totalFacetCount ) && !empty( $totalFacetCount ) ) {
                    $this->set( 'totalFacetPages', ceil( $totalFacetCount / $limit ) );
                } else {
                    $this->set( 'totalFacetPages', 0 );
                }
            } else {

            	$arr_albumStream = array();
            	$albums 	 	 = $this->Solr->groupSearch( $queryVar, 'album', 1, 15 );

                foreach ( $albums as $objKey => $objAlbum ) {

                	if ( !is_object( $objAlbum ) ) {
                		continue;
                	}

                    $arr_albumStream[$objKey]['albumSongs'] = $this->requestAction(
                            array( 'controller' => 'artists', 'action' => 'getAlbumSongs' ), array( 'pass' => array( base64_encode( $objAlbum->ArtistText ), $objAlbum->ReferenceID, base64_encode( $objAlbum->provider_type ), 1 ) )
                    );
                }

                $artists   = $this->Solr->groupSearch( $queryVar, 'artist', 1, 5 );
                $genres    = $this->Solr->groupSearch( $queryVar, 'genre', 1, 5 );
                $composers = $this->Solr->groupSearch( $queryVar, 'composer', 1, 5 );
                $videos    = $this->Solr->groupSearch( $queryVar, 'video', 1, 5 );

                $this->set( 'albums', $albums );
                $this->set( 'arr_albumStream', $arr_albumStream );
                $this->set( 'albumData', $albums );
                $this->set( 'artists', $artists );
                $this->set( 'genres', $genres );
                $this->set( 'composers', $composers );
                $this->set( 'videos', $videos );
            }

            //$this->set( 'total', $total );
            //$this->set( 'totalPages', $totalPages );
            $this->set( 'currentPage', $page );
            $this->set( 'facetPage', $facetPage );
            $this->set( 'patronId', $patronId );
            $this->set( 'territory', $country );
            $this->set( 'libraryType', $this->Session->read( 'library_type' ) );
        }

        $this->set( 'keyword', htmlspecialchars( $queryVar ) );

        if ( isset( $this->params['isAjax'] ) && $this->params['isAjax'] && $layout == 'ajax' ) {
            $this->layout = 'ajax';
            $this->autoLayout = false;
            $this->autoRender = false;
            echo $this->render();
            exit;
        } else {
            $this->layout = 'home';
        }
    }

    public function searchrecords( $type, $search_text ) {

        $search_text = strtolower( $search_text );
        $search_text = preg_replace( '/\s\s+/', ' ', $search_text );
        $insertArr['search_text'] = $search_text;
        $insertArr['type'] = $type;

        $genre_id_count_array = $this->Searchrecord->find( 'all', array('conditions' => array( 'search_text' => $search_text, 'type' => $type ) ) );

        if ( count( $genre_id_count_array ) > 0) {
            $insertArr['count'] = $genre_id_count_array[0]['Searchrecord']['count'] + 1;
            $insertArr['id'] 	= $genre_id_count_array[0]['Searchrecord']['id'];
        } else {
            $insertArr['count'] = 1;
        }

        return $insertArr;
    }

    public function autocomplete() {

        $this->layout = 'ajax';

        $searchedString = isset( $this->params['url']['q'] ) ? trim( $this->params['url']['q'] ) : '';
        $searchedType	= isset( $this->params['url']['type'] ) ? trim( $this->params['url']['type'] ) : '';

        $records  = array();
        $queryVar = null;
        $typeVar  = 'all';
        
        if ( ! empty( $searchedString ) ) {
        	$queryVar = $searchedString;
        }
        
        if ( ! empty( $searchedType ) ) {
        	$arrSearchType = array( 'all', 'song', 'album', 'genre', 'label', 'artist', 'composer', 'video' );
        
        	if( in_array( $searchedType, $arrSearchType ) ) {
        		$typeVar = $searchedType;
        	}
        }

        if ( $typeVar != 'all' ) {
            $data = $this->Solr->getAutoCompleteData( $queryVar, $typeVar, 10 );
        }

        switch ( $typeVar ) {

            case 'all':
                $arr_data	 = array(); 

                // each indiviual filter call
                $arr_data[] = $this->Solr->getAutoCompleteData( $queryVar, 'album', 18, '1' );
                $arr_data[] = $this->Solr->getAutoCompleteData( $queryVar, 'artist', 18, '1' );
                $arr_data[] = $this->Solr->getAutoCompleteData( $queryVar, 'composer', 18, '1' );
                $arr_data[] = $this->Solr->getAutoCompleteData( $queryVar, 'genre', 18, '1' );
                $arr_data[] = $this->Solr->getAutoCompleteData( $queryVar, 'song', 18, '1' );

                if ( is_array( $arr_data ) && count( $arr_data ) > 0 ) {
                	// formates array
                	foreach ( $arr_data as $key1 => $val1 ) {
                		foreach ( $val1 as $key2 => $val2 ) {
                			$arr_result[$key2] = $val2;
                		}
                	}

                	//sort an array in decending order of match result count
                	krsort($arr_result);

                	//get 3 elements of each filter
                	$arr_show  = $arr_result;
                	$in_basket = 0;

                	foreach ( $arr_result as $key1 => $val1 ) {

                		foreach ( $val1 as $key2 => $val2 ) {

                			$val2 = array_slice( $val2, 0, 3, true );
                			$in_basket = $in_basket + count( $val2 );
                			$arr_show[$key1][$key2] = $val2;
                		}
                	}

                	//get to be filled records count
                	$to_be_in_basket = 18 - $in_basket;

                	//get remaining elements from most revelant filter
                	if ( 0 != $to_be_in_basket ) {

                		foreach ( $arr_result as $key1 => $val1 ) {
                			
                			if ( $to_be_in_basket == 0 ) {
                				break;
                			}

                			foreach ( $val1 as $key2 => $val2 ) {

                				$val2 = array_slice( $val2, 3, $to_be_in_basket, true );
                				$to_be_in_basket = $to_be_in_basket - count( $val2 );
                				$arr_show[$key1][$key2] = array_merge( $arr_show[$key1][$key2], $val2 );
                				break;
                			}
                		}
                	}

                	$rank = 1;
                	foreach ( $arr_show as $key => $val ) {
                		foreach ( $val as $name => $value ) {
                			foreach ( $value as $record => $count ) {

                				if ( $name == 'album' ) {
                					$keyword   = str_replace( array( ' ', '(', ')', '"', ':', '!', '{', '}', '[', ']', '^', '~', '*', '?' ), array( '\ ', '\(', '\)', '\"', '\:', '\!', '\{', '\}', '\[', '\]', '\^', '\~', '\*', '\?' ), $record );
                				}

                				$regex = "/^$queryVar/i";
                				$str   = "<div class='ac_first' style='font-weight:bold;font-family:Helvetica,Arial,sans-serif;'>" . ucfirst( $name ) . "</div><div class='ac_second' style='font-family:Helvetica,Arial,sans-serif;'>" . $record . "</div>|" . $record . "|" . $rank;

                				if ( preg_match( $regex, $record ) ) {
                					array_unshift( $records, $str );
                				} else {
                			 		$records[] =  $str;
                				}
                				$rank++;
                			}
                		}
                	}
                }
                break;

            case 'album':
                foreach ( $data as $record => $count ) {
                    if ( stripos( $record, $queryVar ) !== false ) {

                        $record    = trim( $record, '"' );
                        $record    = preg_replace( "/\n/", '', $record );
                        $keyword   = str_replace( array( ' ', '(', ')', '"', ':', '!', '{', '}', '[', ']', '^', '~', '*', '?' ), array( '\ ', '\(', '\)', '\"', '\:', '\!', '\{', '\}', '\[', '\]', '\^', '\~', '\*', '\?' ), $record );
                        $records[] = "<div class='ac_first' style='font-weight:bold;font-family:Helvetica,Arial,sans-serif;'>" . ucfirst( $name ) . "</div><div class='ac_second' style='font-family:Helvetica,Arial,sans-serif;'>" . $record . "</div>|" . $record . "|" . $rank;
                    }
                }
                break;

                case 'artist':
                case 'composer':
                case 'song':
              	case 'label':
              	case 'video':
              	case 'genre':
                	foreach ( $data as $record => $count ) {
                		if ( stripos( $record, $queryVar ) !== false ) {
                			$record = trim( $record, '"' );
                			$record = preg_replace( "/\n/", '', $record );
                			$records[] = $record;
                		}
                	}
                break;
        }

        $this->set('type', $typeVar);
        $this->set('records', $records);
    }
}
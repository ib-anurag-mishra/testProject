<?php

class SolrComponent extends Object {

	var $components = array( 'Session' );

	/**
	 * Used for runtime configuration of model
	 */
	static $_defaults = array( 'server' => '192.168.100.24', 'port' => 8080, 'solrpath' => '/solr/freegalmusicstage/' ); //108.166.39.24//192.168.100.24//192.168.100.24

	/**
	 * Used for runtime configuration of model
	 */
	static $_defaults2 = array( 'server' => '192.168.100.24', 'port' => 8080, 'solrpath' => '/solr/freegalmusicvideos/' ); //108.166.39.24//192.168.100.24//192.168.100.24

	/**
	 * Solr client object
	 *
	 * @var SolrClient
	 */
	static $solr = null;

	/**
	 * Solr client object
	 *
	 * @var SolrClient
	 */
	static $solr2 = null;

	/**
	 * Solr client object
	 *
	 * @var SolrClient
	 */
	var $total = null;

	var $timeoutSeconds = 10;

	public function initialize( $config = array(), $config2 = array() ) {
		
		$settings  = array_merge( ( array ) $config, self::$_defaults );
		$settings2 = array_merge( ( array ) $config2, self::$_defaults2 );
		
		App::import( "Vendor", "solr", array( 'file' => "Apache" . DS . "Solr" . DS . "Service.php" ) );
		
		self::$solr = new Apache_Solr_Service( $settings['server'], $settings['port'], $settings['solrpath'] );

		if ( !self::$solr->ping( $this->timeoutSeconds ) ) {
			try {
				
				throw new SolrException();
				
			} catch( Exception $e ) {
				
				$this->log( 'Unable to Connect to Solr from initialize function','search' );
			}
		}

		self::$solr2 = new Apache_Solr_Service( $settings2['server'], $settings2['port'], $settings2['solrpath'] );

		if ( !self::$solr2->ping( $this->timeoutSeconds ) ) {
			try {
				
				throw new SolrException();
			} catch( Exception $e ) {
				
				$this->log( 'Unable to Connect to Solr from initialize function','search' );
			}
		}
	}

	public function createSearchConditions( $type, $country, $mobileExplicitStatus, $filter = '' ) {
		
		$conditions = '';
		
		if ( !empty( $filter ) ){
			$filter = str_replace( ' ', '*', $filter );
			$filter = ' AND Genre:' . $filter;
		}

		if ( $type == 'video' ) {			
			$conditions = ' AND DownloadStatus:1';
		} else {
			$conditions = ' AND (TerritoryDownloadStatus:' . $country . '_1 OR TerritoryDownloadStatus:' . $country . '_1 OR TerritoryStreamingStatus:' . $country . '_1)' . $filter;
		}
		
		if( $mobileExplicitStatus == 1 ) {
			$conditions .= ' AND Advisory:F';
		} else {
			if ( $this->Session->read( 'block' ) == 'yes' ) {
				$conditions .= ' AND Advisory:F';

				if( $type != 'video' ) {
					$conditions .= ' AND AAdvisory:F';
				}
			}
		}
		return $conditions;
	}

	public function connectToSolr() {

		$connectedToSolr = false;
		$retryCount 	 = 1;
			
		while ( !$connectedToSolr &&  $retryCount < 3 ) {
		
			try {
				self::initialize( null );
				$connectedToSolr = true;
					
			} catch( Exception $e ) {
		
			}
			++$retryCount;
		}
		
		if( !$connectedToSolr ) {
		
			$this->log('Unable to Connect to Solr','search');
			exit;
		}
	}
	
	public function solrSearchFileds( $type, $check = 0 ) {
	
		$queryFields = array();
	
		switch ( $type ) {
	
			case 'song':
				$queryFields['queryFields'] = 'catchSongs^10';
				$queryFields['field'] 		= 'SongTitle';
				break;
	
			case 'genre':
				$queryFields['queryFields'] = 'Genre';
				$queryFields['field'] 		= 'Genre';
				break;
	
			case 'album':
				if( !empty( $check ) ) {
					$queryFields['queryFields'] = 'Composer';
				} else {
					$queryFields['queryFields'] = 'catchAlbums^10';
				}
	
				$queryFields['field'] = 'rpjoin';
				break;
	
			case 'artist':
				$queryFields['queryFields'] = 'ArtistText';
				$queryFields['field'] 		= 'ArtistText';
				break;
	
			case 'label':
				$queryFields['queryFields'] = 'Label';
				$queryFields['field'] 		= 'Label';
				break;
	
			case 'video':
				$queryFields['queryFields'] = "CVideoTitle^100 CArtistText^80 CTitle^60";
				$queryFields['field'] 		= 'VideoTitle';
				break;
	
			case 'composer':
				$queryFields['queryFields'] = 'Composer';
				$queryFields['field'] 		= 'Composer';
				break;
	
			default:
				$queryFields['queryFields'] = 'catchSongs^10';
				$queryFields['field'] 		= 'SongTitle';
				break;
		}
	
		return  $queryFields;
	}

	public function getSearchResponse( $type, $query, $begin, $end, $additionalParams, $check = 0 ) {

		if ( $type != 'video' ) {
			if ( !empty( $check ) ) {
				$response = self::$solr->search( $query, $begin, $end, $additionalParams, 1 );
			} else {
				$response = self::$solr->search( $query, $begin, $end, $additionalParams );
			}
		} else {
			$response = self::$solr2->search( $query, $begin, $end, $additionalParams );
		}

		return $response;
	}

	public function search( $keyword, $type = 'song', $sort="SongTitle", $sortOrder = "asc", $page = 1, $limit = 10, $country, $perfect = false, $mobileExplicitStatus = 0 ) {

		if ( !empty( $keyword ) ) {
			if ( !empty( $country ) ) {
				if ( !isset( self::$solr ) ) {
					$this->connectToSolr();
				}

				$conditions    = $this->createSearchConditions( $type, $country, $mobileExplicitStatus );
				$searchkeyword = $this->escapeSpace( $keyword );
				$searchkeyword = $this->checkSearchKeyword( $searchkeyword );
				$queryFields   = $this->solrSearchFileds( $type );

				$queryFields   = isset( $queryFields['queryFields'] ) ? $queryFields['queryFields'] : '';

				if ( $page == 1 ) {
					$this->Session->delete('pagebreak');
					$this->Session->delete('combine_page');
					$this->Session->delete('ioda_cons');
					$this->Session->delete('sony_total');
				}

				$additionalParams = array(
										'defType' => 'edismax',
										'qf' => $queryFields
									);
				
				$query 					 = '(' . $searchkeyword . ') AND Territory:' . $country . $conditions;
				$provider_query_lastpage = ' AND (provider_type:sony OR provider_type:sony OR provider_type:ioda)';

				$lastPageResponse = self::$solr->search( $query . $provider_query_lastpage, 0, 1, $additionalParams );

				$lastPage 		= $lastPageResponse->response->numFound;
				$provider_query = ' AND provider_type:sony';
				
				if ( !( $this->Session->check('pagebreak') ) ) {

					$response = $this->getSearchResponse( $type, $query . $provider_query, 0, 1, $additionalParams );
					$num_found = $response->response->numFound;

					if ( $num_found == 0 ) { // ioda call
						$this->Session->write( 'pagebreak', 0 );
						$this->Session->write( 'combine_page', 0 );
						$this->Session->write( 'ioda_cons', 0 );
					} else {
						$tot_pages = $num_found / $limit;

						if ( is_float( $tot_pages ) ) {
							$intValue = intval( $tot_pages ) + 1;

							$this->Session->write( 'pagebreak', $intValue );
							$this->Session->write( 'combine_page', 1 );
						} else {
							$this->Session->write( 'pagebreak', $tot_pages );
							$this->Session->write( 'combine_page', 0 );
						}
					}
				}

				if ( $page < $this->Session->read( 'pagebreak' ) ) { //sony

					$tmp_start = ( $page - 1 ) * $limit;
					$response  = $this->getSearchResponse( $type, $query . $provider_query, $tmp_start, $limit, $additionalParams );

				} else if ( $page == $this->Session->read( 'pagebreak' ) ) { //sony & ioda

					$tmp_start = ( $page - 1 ) * $limit;
					$response  = $this->getSearchResponse( $type, $query . $provider_query, $tmp_start, $limit, $additionalParams );

					$fetched_result_count = $limit - count( $response->response->docs );
					$provider_query 	  = ' AND provider_type:ioda';

					$this->Session->write( 'ioda_cons', $fetched_result_count );
					$this->Session->write( 'sony_total', $response->response->numFound );

					if ( $this->Session->read('combine_page') == 1 ) {

						$sec_response = $this->getSearchResponse( $type, $query . $provider_query, 0, $fetched_result_count, $additionalParams );

						if ( $sec_response->response->numFound > 0 ) {
							$sec_response->response->docs = array_merge( $response->response->docs, $sec_response->response->docs );
							$response = $sec_response;
						}
					} else {
						$sec_response = $this->getSearchResponse( $type, $query . $provider_query, 0, 1, $additionalParams );

						if ( $sec_response->response->numFound > 0 ) {
							   $response->response->numFound = $sec_response->response->numFound;
						}
					}
				} else if ( $page > $this->Session->read('pagebreak') ) { //ioda

					$provider_query = ' AND provider_type:ioda';
					$tmp_start 		= ( ( ( $page - $this->Session->read('pagebreak') ) - 1 ) * $limit ) + $this->Session->read('ioda_cons');

					$response = $this->getSearchResponse( $type, $query . $provider_query, $tmp_start, $limit, $additionalParams );

					$response->response->numFound = $response->response->numFound + $this->Session->read('sony_total');
				}

				$docs = array();

				if ( $response->getHttpStatus() == 200 ) {
					if ( $response->response->numFound > 0 ) {
						$this->total = $response->response->numFound;

						foreach ( $response->response->docs as $doc ) {
							$docs[] = $doc;
						}
						$docs['lastPage'] = $lastPage;
					}
				}
				return $docs;
			} else {
				$this->log( 'Country was not set in the search function for keyword : ' . $keyword, 'search' );
				return array();
			}
		} else {
			$this->log( 'Keyword was empty in the search function', 'search' );
			return array();
		}
	}

	public function getFacetSearchTotal( $keyword, $type = 'song', $check = 0, $filter = null ) {

		$country = $this->Session->read( 'territory' );

		if ( !empty( $keyword ) ) {

			if ( !empty( $country ) ) {
				if ( !isset( self::$solr ) ) {
					$this->connectToSolr();
				}

				$conditions    = $this->createSearchConditions( $type, $country, 0, $filter );
				$searchkeyword = $this->escapeSpace( $keyword );
				$searchkeyword = $this->checkSearchKeyword( $searchkeyword );
				$arrGroup	   = $this->solrSearchFileds( $type, $check );
				
				$queryFields = isset( $arrGroup['queryFields'] ) ? $arrGroup['queryFields'] : '';
				$field 		 = isset( $arrGroup['field'] ) ? $arrGroup['field'] : '';

				$query = '(' . $searchkeyword . ') AND Territory:' . $country . $conditions;

				$additionalParams = array(
										'defType' => 'edismax',
										'qf' => $queryFields,
										'facet' => 'true',
										'facet.field' => array( $field ),
										'facet.query' => $query,
										'facet.mincount' => 1,
										'facet.limit' => 5000
									);

				$response  = $this->getSearchResponse( $type, $query, 0, '', $additionalParams, $check );
				$docsCount = 0;

				if ( $response->getHttpStatus() == 200 ) {
					if ( !empty( $response->facet_counts->facet_fields->$field ) ) {
						$docsCount = count( $response->facet_counts->facet_fields->$field );
					}
				}
				return $docsCount;
			} else {
				$this->log( 'Country was not set in the facet search total function for keyword : ' . $keyword,'search' );
				return array();
			}
		} else {
			$this->log( 'Keyword was empty in the facet search total function', 'search' );
			return array();
		}
	}

	public function groupSearch( $keyword, $type = 'song', $page = 1, $limit = 5, $mobileExplicitStatus = 0, $country = null, $check = 0, $filter = null ) {

		set_time_limit(0);

		if( empty( $country ) ) {
			$country = $this->Session->read( 'territory' );
		}

		if ( !empty( $keyword ) ) {

			if ( !empty( $country ) ) {
				if ( !isset( self::$solr ) ) {
					$this->connectToSolr();
				}

				$conditions    = $this->createSearchConditions( $type, $country, $mobileExplicitStatus, $filter );
				$searchkeyword = $this->escapeSpace( $keyword );
				$searchkeyword = $this->checkSearchKeyword( $searchkeyword );
				$arrGroup	   = $this->solrSearchFileds( $type, $check );
				
				$queryFields = isset( $arrGroup['queryFields'] ) ? $arrGroup['queryFields'] : '';
				$field 		 = isset( $arrGroup['field'] ) ? $arrGroup['field'] : '';

				$query = '(' . $searchkeyword . ') AND Territory:' . $country . $conditions;

				if ( $page == 1 ) {
					$start = 0;
				} else {
					$start = ( $page - 1 ) * $limit;
				}

				$additionalParams = array(
										'defType' => 'edismax',
										'qf' => $queryFields,
										'group' => 'true',
										'group.field' => $field,
										'group.query' => $query,
										'group.sort' => 'provider_type desc',
										'group.ngroups'	=> 'true'
									);
				$response = $this->getSearchResponse( $type, $query, $start, $limit, $additionalParams, $check );
				$docs 	  = array();

				if ( $response->getHttpStatus() == 200 ) {
					if ( !empty( $response->grouped->$field->groups ) ) {

						foreach ( $response->grouped->$field->groups as $group ) {
							$group->doclist->docs[0]->numFound = $group->doclist->numFound;
							$docs[] = $group->doclist->docs[0];
						}

						$docs['ngroups'] = $response->grouped->$field->ngroups;
					}
				}
				return $docs;
			} else {
				$this->log( 'Country was not set in the group search function for keyword : ' . $keyword, 'search' );
				return array();
			}
		} else {
			$this->log( 'Keyword was empty in the group search function', 'search' );
			return array();
		}
	}

	public function getAutoCompleteData( $keyword, $type, $limit = 10, $allmusic = 0 ) {

		$country = $this->Session->read( 'territory' );

		if ( !empty( $keyword ) ) {
			if ( !empty( $country ) ) {
				if ( !isset( self::$solr ) ) {
					$this->connectToSolr();
				}

				$conditions	   = $this->createSearchConditions( $type, $country, 0 );
				$searchkeyword = $this->escapeSpace( $keyword );
				$searchkeyword = $this->checkSearchKeyword( $searchkeyword );

				if ( $type != 'all' ) {

					$arrAuto 	 = $this->solrSearchFileds( $type );					
					$queryFields = isset( $arrAuto['queryFields'] ) ? $arrAuto['queryFields'] : '';
					$field 		 = isset( $arrAuto['field'] ) ? $arrAuto['field'] : '';
					$query	 	 = $searchkeyword . ' AND Territory:' . $country . $conditions;

					$additionalParams = array(
											'defType' => 'edismax',
											'qf' => $queryFields,
											'facet' => 'true',
											'facet.field' => array( $field ),
											'facet.query' => $query,
											'facet.mincount' => 1,
											'facet.limit' => $limit
										);

					$response 	= $this->getSearchResponse( $type, $query, 0, 0, $additionalParams );
					$arr_result = array();

					if ( $response->getHttpStatus() == 200 ) {
						if ( !empty( $response->facet_counts->facet_fields->$field ) ) {
							if ( $allmusic == 1 ) {
								$arr_result[$response->response->numFound][$type] = $response->facet_counts->facet_fields->$field;
							} else {
								return $response->facet_counts->facet_fields->$field;
							}
						}
					}
					return $arr_result;
				}
			} else {
				$this->log( 'Country was not set in the get autocomplete data function for keyword : ' . $keyword, 'search' );
				return array();
			}
		} else {
			$this->log( 'Keyword was empty in the get autocomplete data function', 'search' );
			return array();
		}
	}

	public function escapeSpace( $keyword ) {
		$keyword = str_replace( array( '(', ')', '"', ':', '!', '{', '}', '[', ']', '^', '~', '*', '?' ), array( '\(', '\)', '\"', '\:', '\!', '\{', '\}', '\[', '\]', '\^', '\~', '\*', '\?' ), $keyword ); // for edismax
		return strtolower( $keyword );
	}

	public function checkSearchKeyword( $searchkeyword ) {
		$synonymsInstance = ClassRegistry::init( 'Synonym' );

		$data = $synonymsInstance->find( 'first', array( 'conditions'=>array( 'searched_text'=>$searchkeyword ) ) );

		if( !empty( $data ) ) {
			$searchkeyword = "(" . $searchkeyword . " " . $data['Synonym']['replacement_text'] . ")";
		}
		return $searchkeyword;
	}
}

class SolrException extends Exception {
    
}
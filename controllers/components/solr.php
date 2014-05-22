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

	function initialize( $config = array(), $config2 = array() ) {
		
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

	function search( $keyword, $type = 'song', $sort="SongTitle", $sortOrder="asc", $page = 1, $limit = 10, $country, $perfect=false, $mobileExplicitStatus=0 ) {
		
		$query 			= '';
		$provider_query = '';
		$docs 			= array();

		if ( !empty( $keyword ) ) {

			if ( !empty( $country ) ) {

				if ( $type == 'video' ) {
					
					$cond = " AND DownloadStatus:1";
					
				} else {
					
					$cond = " AND (TerritoryDownloadStatus:".$country."_1 OR TerritoryStreamingStatus:".$country."_1)";
				}

				if( 1 == $mobileExplicitStatus ) {
					
					$cond .= " AND Advisory:F";
					
				} else {
					
					if ( $this->Session->read( 'block' ) == 'yes' ) {
						
						$cond .= " AND Advisory:F";
						
						if( $type != 'video' ) {
						
							$cond .= " AND AAdvisory:F";
						}
					}
				}

				$searchkeyword = strtolower( $this->escapeSpace( $keyword ) );
				$searchkeyword = $this->checkSearchKeyword( $searchkeyword );

				if ( !isset( self::$solr ) ) {
					
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

				if ( $perfect == false ) {
					
					switch ( $type ) {
						
						case 'song':
							$query 		 = $searchkeyword;
							$queryFields = "CSongTitle^100 CTitle^80 CArtistText^60 CComposer^20 CGenre";
							break;

						case 'genre':
							$query 		 = $searchkeyword;
							$queryFields = "CGenre^100 CTitle^80 CSongTitle^60 CArtistText^20 CComposer";
							break;

						case 'album':
							$query 		 = $searchkeyword;
							$queryFields = "CArtistText^10000 CTitle^100 CGenre^60 CSongTitle^20 CComposer";
							break;

						case 'artist':
							$query 		 = $searchkeyword;
							$queryFields = "CArtistText^100 CTitle^80 CSongTitle^60 CGenre^20 CComposer";
							break;

						case 'label':
							$query 		 = $searchkeyword;
							$queryFields = "CLabel^100 CTitle^80 CArtistText^60 CComposer^20 CGenre";
							break;

						case 'video':
							$query 		 = $searchkeyword;
							$queryFields = "CVideoTitle^100 CArtistText^80 CTitle^60";
							break;

						case 'composer':
							$query 		 = $searchkeyword;
							$queryFields = "CComposer^100";
							break;

						case 'all':
							$query 		 = $searchkeyword;
							$queryFields = "CArtistText^100 CTitle^80 CSongTitle^60 CGenre^20 CComposer";
							break;

						case 'genreAlbum':
							$query 		 = $searchkeyword;
							$queryFields = "CGenre^60";
							break;

						default:
							$query 		 = $searchkeyword;
							$queryFields = "CArtistText^100 CTitle^80 CSongTitle^60 CGenre^20 CComposer";
							break;
					}
				}

				$query = $query . ' AND Territory:' . $country . $cond;

				if ( $page == 1 ) {
					$start = 0;
				} else {
					$start = ( ( $page - 1 ) * $limit );
				}

				$additionalParams = array(
						'defType' => 'edismax',
						'qf' => $queryFields
				);

				if ( 1 == $page ) {
					
					unset( $_SESSION['pagebreak'] );
					unset( $_SESSION['combine_page'] );
					unset( $_SESSION['ioda_cons'] );
					unset( $_SESSION['sony_total'] );
				}
		
				$provider_query_lastpage = ' AND (provider_type:sony OR provider_type:ioda)';
				$lastPageResponse = self::$solr->search( $query . $provider_query_lastpage, 0, 1, $additionalParams );
				$lastPage = $lastPageResponse->response->numFound;

				if ( !( isset( $_SESSION['pagebreak'] ) ) ) {

					$provider_query .= ' AND provider_type:sony';

					if ( $type != 'video' ) {
						
						$response = self::$solr->search( $query . $provider_query, 0, 1, $additionalParams );
						
					} else {
						
						$response = self::$solr2->search( $query . $provider_query, 0, 1, $additionalParams );
						
					}
					$num_found = $response->response->numFound;

					if ( 0 == $num_found ) {
						
						// ioda call
						$_SESSION['pagebreak'] 	  = 0;
						$_SESSION['combine_page'] = 0;
						$_SESSION['ioda_cons'] 	  = 0;
						
					} else {
						
						$tot_pages = $num_found / $limit;
						
						if ( is_float( $tot_pages ) ) {

							$_SESSION['pagebreak'] 	  = intval( $tot_pages ) + 1;
							$_SESSION['combine_page'] = 1;

						} else {
							$_SESSION['pagebreak'] 	  = $tot_pages;
							$_SESSION['combine_page'] = 0;
						}
					}
				}

				if ( $page < $_SESSION['pagebreak'] ) {

					$provider_query = ' AND provider_type:sony';
					$tmp_start 		= ( $page - 1 ) * $limit;
					$start 			= $tmp_start;
					
					if ( $type != 'video' ) {
						
						$response = self::$solr->search( $query . $provider_query, $tmp_start, $limit, $additionalParams );
						
					} else {
						$response = self::$solr2->search( $query . $provider_query, $tmp_start, $limit, $additionalParams );
					}
				}//sony
				
				if ( $page == $_SESSION['pagebreak'] ) {
					
					$provider_query = ' AND provider_type:sony';
					$tmp_start 		= ($page - 1) * $limit;
					$start 			= $tmp_start;

					if ( $type != 'video' ) {
						$response = self::$solr->search( $query . $provider_query, $tmp_start, $limit, $additionalParams );
						
					} else {
						$response = self::$solr2->search( $query . $provider_query, $tmp_start, $limit, $additionalParams );
					}

					$_SESSION['sony_total'] = $response->response->numFound;
					$fetched_result_count 	= count( $response->response->docs );
					$_SESSION['ioda_cons'] 	= ( $limit - $fetched_result_count );

					if ( 1 == $_SESSION['combine_page'] ) {
						
						$provider_query = ' AND provider_type:ioda';
						$start 			= 0;
						
						if ( $type != 'video' ) {
							$sec_response = self::$solr->search( $query . $provider_query, 0, ($limit - $fetched_result_count), $additionalParams );
							
						} else {
							$sec_response = self::$solr2->search( $query . $provider_query, 0, ($limit - $fetched_result_count), $additionalParams );
						}

						if ( $sec_response->response->numFound > 0 ) {
							
							$sec_response->response->docs = array_merge( $response->response->docs, $sec_response->response->docs );
							$response = $sec_response;
						}
					} else {
						
						$provider_query = ' AND provider_type:ioda';
						$start 			= 0;

						if ( $type != 'video' ) {
							$sec_response = self::$solr->search( $query . $provider_query, 0, 1, $additionalParams );
							
						} else {
							$sec_response = self::$solr2->search( $query . $provider_query, 0, 1, $additionalParams );
						}

						if ( $sec_response->response->numFound > 0 ) {
							   $response->response->numFound = $sec_response->response->numFound;
						}
					}
				}//sony & ioda
				
				if ( $page > $_SESSION['pagebreak'] ) {

					$provider_query = ' AND provider_type:ioda';
					$tmp_start 		= ( ( ( $page - $_SESSION['pagebreak'] ) - 1 ) * $limit ) + $_SESSION['ioda_cons'];
					$start 			= $tmp_start;

					if ( $type != 'video' ) {
						$response = self::$solr->search( $query . $provider_query, $tmp_start, $limit, $additionalParams );
						
					} else {
						$response = self::$solr2->search( $query . $provider_query, $tmp_start, $limit, $additionalParams );
					}
					 $response->response->numFound = $response->response->numFound + $_SESSION['sony_total'];
				}//ioda

				if ( $response->getHttpStatus() == 200 ) {

					if ( $response->response->numFound > 0 ) {

						$this->total = $response->response->numFound;
						foreach ( $response->response->docs as $doc ) {
							$docs[] = $doc;
						}
						$docs['lastPage'] = $lastPage;
					} else {
						return array();
					}
				} else {
					return array();
				}
				return $docs;

			} else {
				
				$this->log( 'Country was not set in the search function for keyword : '.$keyword,'search' );
				return array();
			}
		} else {
			$this->log( 'Keyword was empty in the search function','search' );
			return array();
		}
	}

	function facetSearch( $keyword, $type='song', $page=1, $limit = 5 ) {

		$query 	 = '';
		$country = $this->Session->read( 'territory' );

		if ( !empty( $keyword ) ) {

			if ( !empty( $country ) ) {

				if( $type == 'video' ) {
					
					$cond = " AND DownloadStatus:1";
					
				} else {
					
					$cond = " AND (TerritoryDownloadStatus:".$country."_1 OR TerritoryStreamingStatus:".$country."_1)";
				}

				if ( $this->Session->read( 'block' ) == 'yes' ) {

					$cond .= " AND Advisory:F";
					
					if( $type != 'video' ) {
						$cond .= " AND AAdvisory:F";
					}
				}

				$searchkeyword = strtolower( $this->escapeSpace( $keyword ) );
				$searchkeyword = $this->checkSearchKeyword( $searchkeyword );

				if ( !isset( self::$solr ) ) {

					$connectedToSolr = false;
					$retryCount 	= 1;

					while ( !$connectedToSolr &&  $retryCount < 3 ) {

						try {
							self::initialize( null );
							$connectedToSolr = true;
						}
						catch( Exception $e ) {

						}
						++$retryCount;
					}

					if( !$connectedToSolr ) {

						$this->log( 'Unable to Connect to Solr','search' );
						exit;
					}
				}

				switch ( $type ) {

					case 'song':
						$query = '(CSongTitle:(' . $searchkeyword . '))';
						$field = 'SongTitle';
						break;

					case 'genre':
						$query = '(CGenre:(' . $searchkeyword . '))';
						$field = 'Genre';
						break;

					case 'album':
						$query = '(CTitle:('.$searchkeyword.') OR CArtistText:('.$searchkeyword.') OR CComposer:(' . $searchkeyword . '))';
						$field = 'Title';
						break;

					case 'artist':
						$query = '(CArtistText:(' . $searchkeyword . '))';
						$field = 'ArtistText';
						break;

					case 'label':
						$query = '(CLabel:(' . $searchkeyword . '))';
						$field = 'Label';
						break;

					case 'video':
						$query = '(CVideoTitle:(' . $searchkeyword . ') OR CArtistText:(' . $searchkeyword . '))';
						$field = 'VideoTitle';
						break;

					case 'composer':
						$query = '(CComposer:(' . $searchkeyword . '))';
						$field = 'Composer';
						break;

				   case 'album':
					   	$query = '(CTitle:('.$searchkeyword.') OR CArtistText:('.$searchkeyword.') OR CComposer:(' . $searchkeyword . '))';
		   				$field = 'Title';
		   				break;

				   default:
					   	$query = '(CSongTitle:(' . $searchkeyword . '))';
					   	$field = 'SongTitle';
		   				break;
				}

				$query = $query . ' AND Territory:' . $country;

				if ( $page == 1 ) {
					$start = 0;
				} else {
					$start = ( ( $page - 1 ) * $limit );
				}

				$additionalParams = array(
						'facet' => 'true',
						'facet.field' => array(
								$field,
						),
						'facet.query' => $query,
						'facet.mincount' => 1,
						'facet.limit' => $limit,
				);

				if ( $type != 'video' ) {

					$response = self::$solr->search( $query, $start, $limit, $additionalParams );
					
					if ( $response->getHttpStatus() == 200 ) {
						
						if ( !empty( $response->facet_counts->facet_fields->$field ) ) {
							
							return $response->facet_counts->facet_fields->$field;
							
						} else {
							return array();
						}
					} else {
						return array();
					}
					return array();
				} else {
					$response = self::$solr2->search( $query, $start, $limit, $additionalParams );
					
					if ( $response->getHttpStatus() == 200 ) {

						if ( !empty( $response->facet_counts->facet_fields->$field ) ) {

							return $response->facet_counts->facet_fields->$field;

						} else {
							return array();
						}
					} else {
						return array();
					}
					return array();
				}
			} else {
				$this->log( 'Country was not set in the facet search function for keyword : '.$keyword,'search' );
				return array();
			}
		} else {
			$this->log( 'Keyword was empty in the facet search function','search' );
			return array();
		}
	}

	function getFacetSearchTotal( $keyword, $type='song',$check=0, $filter = null ) {

		$query 	 = '';
		$country = $this->Session->read( 'territory' );

		if ( !empty( $keyword ) ) {

			if ( !empty( $country ) ) {

				if ( $type == 'video' ) {
					$cond = " AND DownloadStatus:1";
				} else {

					if ( !empty( $filter ) ) {

						$filter = str_replace( ' ', '*', $filter );
						$cond = " AND (TerritoryDownloadStatus:".$country."_1 OR TerritoryStreamingStatus:".$country."_1) AND Genre:".$filter;

					} else {
						$cond = " AND (TerritoryDownloadStatus:".$country."_1 OR TerritoryStreamingStatus:".$country."_1)";
					}
				}

				if ( $this->Session->read( 'block' ) == 'yes' ) {

					$cond .= " AND Advisory:F";

					if( $type != 'video' ) {
						$cond .= " AND AAdvisory:F";
					}
				}

				$searchkeyword = strtolower( $this->escapeSpace( $keyword ) );
				$searchkeyword = $this->checkSearchKeyword( $searchkeyword );

				if ( !isset( self::$solr ) ) {

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
						$this->log( 'Unable to Connect to Solr','search' );
						exit;
					}
				}

				switch ( $type ) {						

					case 'song':
						$query 		 = $searchkeyword;
						$queryFields = "CSongTitle^100 CTitle^80 CArtistText^60 CComposer^20 CGenre";
						$field 		 = 'SongTitle';
						break;

					case 'genre':
						$queryFields = "CGenre^100 CTitle^80 CSongTitle^60 CArtistText^20 CComposer";
						$query 		 = $searchkeyword;
						$field 		 = 'Genre';
						break;

					case 'album':

						if( !empty( $check ) ) {
							$queryFields = "CComposer";
						} else {
							$queryFields = "CArtistText^10000 CTitle^100 CGenre^60 CSongTitle^20 CComposer";
						}

						$query = $searchkeyword;
						$field = 'rpjoin';
						break;

					case 'artist':
						$queryFields = "CArtistText^1000000 CTitle^80 CSongTitle^60 CGenre^20 CComposer"; // increased priority for artist // CTitle^80 CSongTitle^60 CGenre^20 CComposer
						$query 		 = $searchkeyword;
						$field 		 = 'ArtistText';
						break;

					case 'label':
						$queryFields = "CLabel^100 CTitle^80 CArtistText^60 CComposer^20 CGenre";
						$query 		 = $searchkeyword;
						$field 		 = 'Label';
						break;

					case 'video':
						$queryFields = "CVideoTitle^100 CArtistText^80 CTitle^60";
						$query 		 = $searchkeyword;
						$field 		 = 'VideoTitle';
						break;

					case 'composer':
						$queryFields = "CComposer^100 CArtistText^80 CTitle^60 CSongTitle^20 CGenre";
						$query 		 = $searchkeyword;
						$field 		 = 'Composer';
						break;

					case 'genreAlbum':
						$queryFields = "CGenre^60";
						$query 		 = $searchkeyword;
						$field 		 = 'rpjoin';
						break;

					default:
						$queryFields = "CSongTitle^100 CTitle^80 CArtistText^60 CComposer^20 CGenre";
						$query 		 = $searchkeyword;
						$field 		 = 'SongTitle';
						break;
				}

				$query = $query . ' AND Territory:' . $country . $cond;

				if ( $page == 1 ) {
					$start = 0;
				} else {
					$start = ( ( $page - 1 ) * $limit );
				}

				$additionalParams = array(
						'defType' => 'edismax',
						'qf' => $queryFields,
						'facet' => 'true',
						'facet.field' => array(
								$field
						),
						'facet.query' => $query,
						'facet.mincount' => 1,
						'facet.limit' => 5000
				);

				if ( $type != 'video' ) {

					if( !empty( $check ) ) {
						$response = self::$solr->search( $query, $start, $limit, $additionalParams, 1 );
					} else {
						$response = self::$solr->search( $query, $start, $limit, $additionalParams );
					}

					if ( $response->getHttpStatus() == 200 ) {

						if ( !empty( $response->facet_counts->facet_fields->$field ) ) {

							return count( $response->facet_counts->facet_fields->$field );

						} else {
							return array();
						}
					} else {
						return array();
					}
					return array();
				} else {
					$response = self::$solr2->search( $query, $start, $limit, $additionalParams );
					
					if ( $response->getHttpStatus() == 200 ) {
						
						if ( !empty( $response->facet_counts->facet_fields->$field ) ) {
							return count( $response->facet_counts->facet_fields->$field );
						} else {
							return array();
						}
					} else {
						return array();
					}
					return array();
				}
			} else {
				$this->log( 'Country was not set in the facet search total function for keyword : '.$keyword,'search' );
				return array();
			}
		} else {
			$this->log( 'Keyword was empty in the facet search total function','search' );
			return array();
		}
	}

	function groupSearch( $keyword, $type='song', $page=1, $limit = 5, $mobileExplicitStatus = 0, $country = null, $check = 0, $filter = null ) {

		set_time_limit(0);
		$query = '';

		if( empty( $country ) ) {
			$country = $this->Session->read( 'territory' );
		}

		if ( !empty( $keyword ) ) {

			if ( !empty( $country ) ) {

				if( $type == 'video' ) {
					$cond = " AND DownloadStatus:1";

				} else {

					if( !empty( $filter ) ) {

		    			$filter = str_replace(' ', '*', $filter);
		    			$cond   = " AND (TerritoryDownloadStatus:".$country."_1 OR TerritoryStreamingStatus:".$country."_1) AND Genre:".$filter;

					} else {
						$cond = " AND (TerritoryDownloadStatus:".$country."_1 OR TerritoryStreamingStatus:".$country."_1)";
					}
				}

				if( 1 == $mobileExplicitStatus ) {
					$cond .= " AND Advisory:F";

				} else {

					if ( $this->Session->read( 'block' ) == 'yes' ) {

						$cond .= " AND Advisory:F";

						if( $type != 'video' ) {
							$cond .= " AND AAdvisory:F";
						}
					}
				}

				$searchkeyword = strtolower( $this->escapeSpace( $keyword ) );
				$searchkeyword = $this->checkSearchKeyword( $searchkeyword );

				if ( !isset( self::$solr ) ) {
					
					$connectedToSolr = false;
					$retryCount 	 = 1;

					while ( !$connectedToSolr &&  $retryCount < 3 ) {
						try {
							self::initialize( null );
							$connectedToSolr = true;

						} catch(Exception $e) {

						}
						++$retryCount;
					}

					if( !$connectedToSolr ) {
						$this->log( 'Unable to Connect to Solr','search' );
						exit;
					}
				}

				switch ( $type ) {
					
					case 'song':
						$queryFields = "CSongTitle^100 CTitle^80 CArtistText^60 CComposer^20 CGenre";
						$query 		 = $searchkeyword;
						$field 		 = 'SongTitle';
						break;

					case 'genre':
						$queryFields = "CGenre^100 CTitle^80 CSongTitle^60 CArtistText^20 CComposer";
						$query 		 = $searchkeyword;
						$field 		 = 'Genre';
						break;

					case 'album':

						if( !empty( $check ) ) {
							$queryFields = "CComposer";
						} else {
							$queryFields = "CArtistText^10000 CTitle^100 CGenre^60 CSongTitle^20 CComposer";
						}
						
						$query = $searchkeyword;
						$field = 'rpjoin';
						break;

					case 'artist':
						$queryFields = "CArtistText^1000000 CTitle^80 CSongTitle^60 CGenre^20 CComposer"; // increased priority for artist // CTitle^80 CSongTitle^60 CGenre^20 CComposer
						$query 		 = $searchkeyword;
						$field 		 = 'ArtistText';
						break;

					case 'label':
						$queryFields = "CLabel^100 CTitle^80 CArtistText^60 CComposer^20 CGenre";
						$query 		 = $searchkeyword;
						$field 		 = 'Label';
						break;

					case 'video':
						$queryFields = "CVideoTitle^100 CArtistText^80 CTitle^60";
						$query 		 = $searchkeyword;
						$field 		 = 'VideoTitle';
						break;

					case 'composer':
						$queryFields = "CComposer^100 CArtistText^80 CTitle^60 CSongTitle^20 CGenre";
						$query 		 = $searchkeyword;
						$field 		 = 'Composer';
						break;

					case 'genreAlbum':
						$queryFields = "CGenre^60";
						$query 		 = $searchkeyword;
						$field 		 = 'rpjoin';
						break;

					default:
						$queryFields = "CSongTitle^100 CTitle^80 CArtistText^60 CComposer^20 CGenre";
						$query 		 = $searchkeyword;
						$field 		 = 'SongTitle';
						break;
				}

				$query = $query . ' AND Territory:' . $country . $cond;

				if ( $page == 1 ) {
					$start = 0;
				} else {
					$start = ( ( $page - 1 ) * $limit );
				}

				$additionalParams = array(
						'defType' => 'edismax',
						'qf' => $queryFields,
						'group' => 'true',
						'group.field' => $field,
						'group.query' => $query,
						'group.sort' => 'provider_type desc',
				);

				if ( $type != 'video' ) {

					if( !empty( $check ) ) {
						$response = self::$solr->search( $query, $start, $limit, $additionalParams, 1 );
					} else {
						$response = self::$solr->search( $query, $start, $limit, $additionalParams );
					}

					if ( $response->getHttpStatus() == 200 ) {
						
						if ( !empty( $response->grouped->$field->groups ) ) {
							
							$docs = array();
							
							foreach ( $response->grouped->$field->groups as $group ) {
								
								$group->doclist->docs[0]->numFound = $group->doclist->numFound;
								$docs[] = $group->doclist->docs[0];
							}
							return $docs;
						} else {
							return array();
						}
					} else {
						return array();
					}
					return array();
				} else {

					$response = self::$solr2->search( $query, $start, $limit, $additionalParams );

					if ( $response->getHttpStatus() == 200 ) {

						if ( !empty( $response->grouped->$field->groups ) ) {

							$docs = array();
							foreach ( $response->grouped->$field->groups as $group ) {
								$group->doclist->docs[0]->numFound = $group->doclist->numFound;
								$docs[] = $group->doclist->docs[0];
							}
							return $docs;
						} else {
							return array();
						}
					} else {
						return array();
					}
					return array();
				}
			} else {
				$this->log( 'Country was not set in the group search function for keyword : '.$keyword,'search' );
				return array();
			}
		} else {
			$this->log( 'Keyword was empty in the group search function','search' );
			return array();
		}
	}

	function getGroupSearchTotal( $keyword, $type='song' ) {

		$query 	 = '';
		$country = $this->Session->read( 'territory' );

		if ( !empty( $keyword ) ) {
			
			if ( !empty( $country ) ) {

				if( $type == 'video' ) {
					$cond = " AND DownloadStatus:1";
				} else {
					$cond = " AND (TerritoryDownloadStatus:".$country."_1 OR TerritoryStreamingStatus:".$country."_1)";
				}

				if ( $this->Session->read( 'block' ) == 'yes' ) {
					
					$cond .= " AND Advisory:F";
					
					if( $type != 'video' ) {
						$cond .= " AND AAdvisory:F";
					}
				}

				$searchkeyword = strtolower( $this->escapeSpace( $keyword ) );
				$searchkeyword = $this->checkSearchKeyword( $searchkeyword );

				if ( !isset( self::$solr ) ) {

					$connectedToSolr = false;
					$retryCount 	 = 1;

					while ( !$connectedToSolr &&  $retryCount < 3 ) {
						try {
							self::initialize( null );
							$connectedToSolr = true;

						} catch( Exception $e )
						{

						}
						++$retryCount;
					}

					if( !$connectedToSolr ) {
						$this->log('Unable to Connect to Solr','search');
						exit;
					}
				}

				switch ( $type ) {
					case 'song':
						$queryFields = "CSongTitle^100 CTitle^80 CArtistText^60 CComposer^20 CGenre";
						$query 		 = $searchkeyword;
						$field 		 = 'SongTitle';
						break;

					case 'genre':
						$queryFields = "CGenre^100 CTitle^80 CSongTitle^60 CArtistText^20 CComposer";
						$query 		 = $searchkeyword;
						$field 		 = 'Genre';
						break;

					case 'album':
						$queryFields = "CArtistText^10000 CTitle^100 CGenre^60 CSongTitle^20 CComposer";
						$query 		 = $searchkeyword;
						$field 		 = 'rpjoin';
						break;

					case 'artist':
						$queryFields = "CArtistText^1000000 CTitle^80 CSongTitle^60 CGenre^20 CComposer"; // increased priority for artist // CTitle^80 CSongTitle^60 CGenre^20 CComposer
						$query 		 = $searchkeyword;
						$field 		 = 'ArtistText';
						break;

					case 'label':
						$queryFields = "CLabel^100 CTitle^80 CArtistText^60 CComposer^20 CGenre";
						$query 		 = $searchkeyword;
						$field 		 = 'Label';
						break;

					case 'video':
						$queryFields = "CVideoTitle^100 CArtistText^80 CTitle^60";
						$query 		 = $searchkeyword;
						$field 		 = 'VideoTitle';
						break;

					case 'composer':
						$queryFields = "CComposer^100 CArtistText^80 CTitle^60 CSongTitle^20 CGenre";
						$query 		 = $searchkeyword;
						$field 		 = 'Composer';
						break;

					case 'genreAlbum':
						$queryFields = "CGenre^60";
						$query 		 = $searchkeyword;
						$field 		 = 'rpjoin';
						break;

					default:
						$queryFields = "CSongTitle^100 CTitle^80 CArtistText^60 CComposer^20 CGenre";
						$query 		 = $searchkeyword;
						$field 		 = 'SongTitle';
						break;
				}

				$query = $query . ' AND Territory:' . $country . $cond;

				if ( $page == 1 ) {
					$start = 0;
				} else {
					$start = ( ( $page - 1 ) * $limit );
				}

				$additionalParams = array(
						'defType' => 'edismax',
						'qf' => $queryFields,
						'group' => 'true',
						'fl' => array(
								$field
						),
						'group.query' => $query,
				);

				if ( $type != 'video' ) {

					$response = self::$solr->search( $query, $start, $limit, $additionalParams );

					if ( $response->getHttpStatus() == 200 ) {

						if ( !empty( $response->grouped->$query->doclist->numFound ) ) {
							return count( $response->grouped->$query->doclist->docs );

						} else {
							return array();
						}
					} else {
						return array();
					}
					return array();
				} else {
					$response = self::$solr2->search( $query, $start, $limit, $additionalParams );
					
					if ( $response->getHttpStatus() == 200 ) {

						if ( !empty( $response->grouped->$query->doclist->numFound ) ) {
							return count( $response->grouped->$query->doclist->docs );

						} else {
							return array();
						}
					} else {
						return array();
					}
					return array();
				}
			} else {
				$this->log( 'Country was not set in the group search total function for keyword : '.$keyword,'search' );
				return array();
			}
		} else {
			$this->log( 'Keyword was empty in the group search total function','search' );
			return array();
		}
	}

	function getAutoCompleteData( $keyword, $type, $limit=10, $allmusic=0 ) {

		$query 	 = '';
		$country = $this->Session->read( 'territory' );

		if ( !empty( $keyword ) ) {

			if ( !empty( $country ) ) {

				if( $type == 'video' ) {
					$cond = " AND DownloadStatus:1";
				} else {
					$cond = " AND (TerritoryDownloadStatus:".$country."_1 OR TerritoryStreamingStatus:".$country."_1)";
				}

				if ( $this->Session->read( 'block' ) == 'yes' ) {

					$cond .= " AND Advisory:F";

					if( $type != 'video' ) {
						$cond .= " AND AAdvisory:F";
					}
				}

				$searchkeyword = strtolower( $this->escapeSpace( $keyword ) );
				$searchkeyword = $this->checkSearchKeyword( $searchkeyword );
				$char 		   = substr( $keyword, 0, 1 );

				if ( !isset( self::$solr ) ) {

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
						$this->log( 'Unable to Connect to Solr','search' );
						exit;
					}
				}

				if ( $type != 'all' ) {

					switch ( $type ) {

						case 'song':
							$queryFields = "CSongTitle";
							$query 		 = $searchkeyword;
							$field 		 = 'SongTitle';
							break;

						case 'genre':
							$queryFields = "CGenre";
							$query 		 = $searchkeyword;
							$field 		 = 'Genre';
							break;

						case 'album':
							$queryFields = "CTitle";
							$query 		 = $searchkeyword;
							$field 		 = 'Title';
							break;

						case 'artist':
							$queryFields = "CArtistText";
							$query 		 = $searchkeyword;
							$field 		 = 'ArtistText';
							break;

						case 'video':
							$queryFields = "CVideoTitle^100 CArtistText^80 CTitle^60";
							$query 		 = $searchkeyword;
							$field 		 = 'VideoTitle';
							break;

						case 'composer':
							$queryFields = "CComposer";
							$query 		 = $searchkeyword;
							$field 		 = 'Composer';
							break;

					    case 'genreAlbum':
					    	$queryFields = "CGenre";
					    	$query 		 = $searchkeyword;
		    				$field 		 = 'Genre';
		    				break;

					    default:
					    	$queryFields = "CSongTitle";
					    	$query 		 = $searchkeyword;
					    	$field 		 = 'SongTitle';
					    	break;
					}

					$query = $query . ' AND Territory:' . $country . $cond;

					$additionalParams = array(
							'defType' => 'edismax',
							'qf' => $queryFields,
							'facet' => 'true',
							'facet.field' => array(
									$field
							),
							'facet.query' => $query,
							'facet.mincount' => 1,
							'facet.limit' => $limit
					);

					if ( $type != 'video' ) {

						$response = self::$solr->search( $query, 0, 0, $additionalParams );

						if ( $response->getHttpStatus() == 200 ) {

							if ( !empty( $response->facet_counts->facet_fields->$field ) ) {

								if ( 1 == $allmusic ) {

									$arr_result = array();
									$arr_result[$response->response->numFound][$type] = $response->facet_counts->facet_fields->$field;
									return $arr_result;

								} else {
									return $response->facet_counts->facet_fields->$field;
								}
							} else {
								return array();
							}
						} else {
							return array();
						}
						return array();
					} else {

						$response = self::$solr2->search( $query, 0, 0, $additionalParams );

						if ( $response->getHttpStatus() == 200 ) {

							if ( !empty($response->facet_counts->facet_fields->$field ) ) {

								if ( 1 == $allmusic ) {

									$arr_result = array();
									$arr_result[$response->response->numFound][$type] = $response->facet_counts->facet_fields->$field;
									return $arr_result;
								} else {
									return $response->facet_counts->facet_fields->$field;
								}
							} else {
								return array();
							}
						} else {
							return array();
						}
						return array();
					}
				} else {}
			}
			else
			{
				$this->log( 'Country was not set in the get autocomplete data function for keyword : '.$keyword,'search' );
				return array();
			}
		} else {
			$this->log( 'Keyword was empty in the get autocomplete data function','search' );
			return array();
		}
	}

	function query( $query, $limit ) {

		$country = $this->Session->read( 'territory' );

		if ( !empty( $keyword ) ) {

			if ( !empty( $country ) ) {

				if( $type == 'video' ) {
					$cond = " AND DownloadStatus:1";
				} else {
					$cond = " AND (TerritoryDownloadStatus:".$country."_1 OR TerritoryStreamingStatus:".$country."_1)";
				}

				if ( $this->Session->read( 'block' ) == 'yes' ) {
					$cond .= " AND Advisory:F";
				}

				$query 	  = $query . ' AND Territory:' . $country . $cond;
				$response = self::$solr->search($query, 0, $limit);

				if ( $response->getHttpStatus() == 200 ) {

					if ( $response->response->numFound > 0 ) {

						foreach ( $response->response->docs as $doc ) {
							$docs[] = $doc;
						}
					} else {
						return array();
					}
				} else {
					return array();
				}
				return $docs;
			} else {
				$this->log( 'Country was not set in the query function for query : '.$query,'search' );
				return array();
			}
		} else {
			$this->log( 'Keyword was empty in the query function','search' );
			return array();
		}
	}

	function escapeSpace( $keyword ) {
		$keyword = str_replace( array( '(', ')', '"', ':', '!', '{', '}', '[', ']', '^', '~', '*', '?' ), array( '\(', '\)', '\"', '\:', '\!', '\{', '\}', '\[', '\]', '\^', '\~', '\*', '\?' ), $keyword ); // for edismax
		return $keyword;
	}

	function checkSearchKeyword( $searchkeyword ) {
		$synonymsInstance = ClassRegistry::init( 'Synonym' );

		$data = $synonymsInstance->find( 'first',array( 'conditions'=>array( 'searched_text'=>$searchkeyword ) ) );

		if( !empty( $data ) ) {
			$searchkeyword = "(".$searchkeyword." ".$data['Synonym']['replacement_text'].")";
		}
		return $searchkeyword;
	}
}

class SolrException extends Exception {
    
}
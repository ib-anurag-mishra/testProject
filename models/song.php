<?php
/**
 * File Name : song.php
 * File Description : Models page for the  Songs table.
 * Author : m68interactive
*/

class Song extends AppModel {
	var $name = 'Song';
	var $useTable = 'Songs';
	var $primaryKey = 'ProdID';
	var $actsAs = array('Containable', 'Sphinx');
	var $uses = array('Featuredartist', 'Country');

	var $hasOne = array(
		'Participant' => array(
			'className' => 'Participant',
			'conditions' => array(
				'Participant.Role' => 'Composer'
			),
			'foreignKey' => 'ProdID'
		),
		'Genre' => array(
			'className' => 'Genre',
			'foreignKey' => 'ProdID'
		),
		'Country' => array(
			'className' => 'Country',
			'foreignKey' => 'ProdID'
		),
	);

	var $belongsTo = array(
		'Sample_Files' => array(
			'className' => 'Files',
			'foreignKey' => 'Sample_FileID'
		),
		'Full_Files' => array(
			'className' => 'Files',
			'foreignKey' => 'FullLength_FileID'
		)
	);

	/*
	 Function Name : getallartist
	 Desc : gets the list of all the artists
	*/

	function getallartist() {
		$this->recursive = -1;
		$allArtists = $this->find('all', array(
			'fields' => array(
				'ArtistText'
			),
			'group' => array(
				'ArtistText',
			),
			'order' => array(
				'ArtistText ASC'
			),
			'cache' => 'ArtistText'
		));
		return $allArtists;
	}

	/*
	 Function Name : getallartistname
	 Desc : This would returna a set of featured artist which does not have images associated with them.
	*/
	function getallartistname($condition, $artistName, $country) {
		$this->recursive = 2;
		$allArtists = $this->find('all', array(
			'conditions' => array(
				'and' => array(
					array(
						'Country.Territory' => $country
					)
				)
			),
			'fields' => array(
				'DISTINCT Song.ArtistText',
			),
			'contain' => array(
				'Country' => array(
					'fields' => array(
						'Country.Territory'
					)
				),
			),
			'order' => 'Song.ArtistText'
		));
		$featuredArtistArr = array();
		$featuredArtistObj = new Featuredartist();
		$featuredArtistList = $featuredArtistObj->find('all');
		foreach ($featuredArtistList as $featuredArtist) {
			array_push($featuredArtistArr, $featuredArtist['Featuredartist']['artist_name']);
		}
		$resultArr = array();
		foreach ($allArtists as $allArtistsNames) {
			if ($condition == 'add') {
				if (!in_array($allArtistsNames['Song']['ArtistText'], $featuredArtistArr)) {
					$resultArr[$allArtistsNames['Song']['ArtistText']] = $allArtistsNames['Song']['ArtistText'];
				}
			} else {
				if ($allArtistsNames['Song']['ArtistText'] == $artistName && $allArtistsNames['Song']['ArtistText'] != '') {
					$resultArr[$allArtistsNames['Song']['ArtistText']] = $allArtistsNames['Song']['ArtistText'];
				} elseif (!in_array($allArtistsNames['Song']['ArtistText'], $featuredArtistArr)) {
					$resultArr[$allArtistsNames['Song']['ArtistText']] = $allArtistsNames['Song']['ArtistText'];
				}
			}
		}
		return $resultArr;
	}

	/*
	 Function Name : allartistname
	 Desc : This would returna a set of artist which does not have images associated with them.
	*/
	function allartistname($condition, $artistName) {
		$this->recursive = -1;
		$allArtists = $this->find('all', array(
			'fields' => 'DISTINCT ArtistText',
			'order' => 'ArtistText'
		));
		$artistArr = array();
		$artistObj = new Artist();
		$artistList = $artistObj->getallartists();
		foreach ($artistList as $artist) {
			array_push($artistArr, $artist['Artist']['artist_name']);
		}
		$resultArr = array();
		foreach ($allArtists as $allArtistsNames) {
			if ($condition == 'add') {
				if (!in_array($allArtistsNames['Song']['ArtistText'], $artistArr)) {
					$resultArr[$allArtistsNames['Song']['ArtistText']] = $allArtistsNames['Song']['ArtistText'];
				}
			} else {
				if ($allArtistsNames['Song']['ArtistText'] == $artistName && $allArtistsNames['Song']['ArtistText'] != '') {
					$resultArr[$allArtistsNames['Song']['ArtistText']] = $allArtistsNames['Song']['ArtistText'];
				} elseif (!in_array($allArtistsNames['Song']['ArtistText'], $artistArr)) {
					$resultArr[$allArtistsNames['Song']['ArtistText']] = $allArtistsNames['Song']['ArtistText'];
				}
			}
		}
		return $resultArr;
	}

	/*
	 Function Name : searchArtist
	 Desc : This would returna a artist which is searched
	*/
	function searchArtist($search) {
		if ($search == 'special') {
			$allArtists = $this->find('all', array(
				'fields' => array(
				'ArtistText'
				),
				'group' => array(
					'ArtistText',
				),
				'order' => array(
					'ArtistText ASC',
				),
				'conditions' => array(
					"ArtistText REGEXP '^[^A-Za-z]'"
				)
			));
		} else {
			$allArtists = $this->find('all', array(
				'fields' => array(
				'ArtistText'
				),
				'group' => array(
					'ArtistText',
				),
				'order' => array(
					'ArtistText ASC',
				),
				'conditions' => array(
					'ArtistText LIKE' => $search . '%'
				)
			));
		}
		return $allArtists;
	}

	/*
	 Function Name : allartistname
	 Desc : This would returna the download data for the patron
	*/
	function getdownloaddata($id, $provider) {
		$this->recursive = 2;
		$this->Behaviors->attach('Containable');
		$downloadData = $this->find('all', array(
			'conditions' => array(
				'Song.ProdID' => $id,
				'Song.provider_type' => $provider
			),
			'fields' => array(
				'Song.ProdID',
				'Song.ProductID',
				'Song.Title',
				'Song.SongTitle',
				'Song.Artist',
				'Song.ISRC'
			),
			'contain' => array(
				'Full_Files' => array(
					'fields' => array(
						'Full_Files.CdnPath',
						'Full_Files.SaveAsName'
					)
				),
				'Country' => array(
					'fields' => array(
						'Country.Territory',
						'Country.provider_type'
					)
				),
			),
		));
		return $downloadData;
	}

	/*
	 Function Name : allartistname
	 Desc : This would returna a set of artist.
	*/
	function selectArtist() {
		$this->recursive = -1;
		$allArtists = $this->find('all', array(
				'fields' => array(
						'ArtistText'
				),
				'group' => array(
						'ArtistText',
				),
				'order' => array(
						'ArtistText ASC',
				),
				'conditions' => array('ArtistText LIKE' => 'A%'),
				'cache' => 'ArtistText'
		));
		return $allArtists;
	}

	/**
	 * This function is used to return the Album id with there provider type
	 * and the sales date of the album
	 *
	 * @param string $artist_text
	 * @param string $country
	 * @param string $cond
	 * @return array
	 */
	function getArtistAlbums($artist_text , $country, $cond) {
		
		$this->Behaviors->attach('Containable');
		
		$options = array(
					'fields' => array(
							'DISTINCT Song.ReferenceID',
							'Song.provider_type',
							'Country.SalesDate' 
							),
					'conditions' => array(
							'Song.ArtistText' => $artist_text,
							'Country.DownloadStatus' => 1,
							"Song.Sample_FileID != ''",
							"Song.FullLength_FIleID != ''",
							'Country.Territory' => $country, $cond,
							'Song.provider_type = Country.provider_type'
							),
					'contain' => array( 'Country' => array( 'fields' => array( 'Country.Territory' ) ) ),
					'recursive' => 0,
					'order' => array( 'Country.SalesDate DESC' )
				);

		return $this->find( 'all', $options );
	}

	function getArtistView($id , $country, $cond, $query_id) {
		$this->Behaviors->attach('Containable');
		if ($query_id==1) {
			return $this->find('all', array(
				'fields' => array(
					'DISTINCT Song.ReferenceID',
					'Song.provider_type'),
				'conditions' => array(
					'Song.ArtistText' => base64_decode($id),
					'Country.DownloadStatus' => 1,
					"Song.Sample_FileID != ''",
					"Song.FullLength_FIleID != ''",
					'Country.Territory' => $country,
					$cond
				),
				'contain' => array(
					'Country' => array(
						'fields' => array(
							'Country.Territory'
						)
					)
				),
				'recursive' => 0,
				'limit' => 1
			));
		} else if($query_id==2) {
			return $this->find('all', array(
				'fields' => array(
					'DISTINCT Song.ReferenceID',
					'Song.provider_type'),
				'conditions' => array(
					'Song.ArtistText' => base64_decode($id),
					"Song.Sample_FileID != ''",
					"Song.FullLength_FIleID != ''",
					'Country.Territory' => $country,
					'Country.DownloadStatus' => 1,
					array(
						'or' => array(
							array(
								'Country.StreamingStatus' => 1
							)
						)
					),
					$cond
				),
				'contain' => array(
					'Country' => array(
						'fields' => array(
							'Country.Territory'
						)
					)
				),
				'recursive' => 0,
				'limit' => 1
			));
		}
	}

	function getArtistSongs($prodID , $provider, $country, $cond, $queryType) {

		if ( $queryType == 1 ) {
		$conditions = array('and' =>
									array(
											array('Song.ReferenceID' => $album['Album']['ProdID']),
											array('Song.provider_type = Country.provider_type'),
											array('Country.DownloadStatus' => 1),
											array("Song.Sample_FileID != ''"),
											array("Song.FullLength_FIleID != ''"),
											array("Song.provider_type" => $provider),
											array('Country.Territory' => $country),
											$cond
									)
							);
		} else {
			$conditions = array('and' =>
									array(
											array('Song.ReferenceID' => $album['Album']['ProdID']),
											array('Song.provider_type = Country.provider_type'),
											array("Song.Sample_FileID != ''"),
											array("Song.FullLength_FIleID != ''"),
											array("Song.provider_type" => $provider),
											array('Country.Territory' => $country),
											$cond
									),
									'or' => array(array('and' => array(
											'Country.StreamingStatus' => 1,
											'Country.StreamingSalesDate <=' => date('Y-m-d')
									))
											,
											array('and' => array(
													'Country.DownloadStatus' => 1
											))
									)
							);
		}

		$options = array(
							'conditions' => $conditions,
							'fields' => array(
									'Song.ProdID',
									'Song.Title',
									'Song.ArtistText',
									'Song.DownloadStatus',
									'Song.SongTitle',
									'Song.Artist',
									'Song.Advisory',
									'Song.Sample_Duration',
									'Song.FullLength_Duration',
									'Song.Sample_FileID',
									'Song.FullLength_FIleID',
									'Song.provider_type',
									'Song.sequence_number'
							),
							'contain' => array( 
									'Genre' => array( 'fields' => array( 'Genre.Genre' ) ),
									'Country' => array( 'fields' => array( 'Country.Territory', 'Country.SalesDate', 'Country.StreamingSalesDate', 'Country.StreamingStatus', 'Country.DownloadStatus' ) ),
									'Sample_Files' => array( 'fields' => array( 'Sample_Files.CdnPath', 'Sample_Files.SaveAsName' ) ),
									'Full_Files' => array( 'fields' => array( 'Full_Files.CdnPath', 'Full_Files.SaveAsName' ) ),
									),
							'group' => 'Song.ProdID, Song.provider_type',
							'order' => array('Song.sequence_number', 'Song.ProdID')
					);
		return $this->find( 'all', $options );
	}
	
	function getSongDetails($prodId, $provider, $territory) {

		$songDetails = $this->find('all', array(
			'conditions' => array(
				'and' => array(
					array('Song.ReferenceID' => $prodId),
					array('Song.provider_type = Country.provider_type'),
					array("Song.Sample_FileID != ''"),
					array("Song.FullLength_FIleID != ''"),
					array("Song.provider_type" => $provider),
					array('Country.Territory' => $territory),
					array('Country.StreamingStatus' => 1),
					array('Country.StreamingSalesDate <=' => date('Y-m-d'))
				)
			),
			'fields' => array(
				'Song.ProdID',
				'Song.Title',
				'Song.ArtistText',
				'Song.DownloadStatus',
				'Song.SongTitle',
				'Song.Artist',
				'Song.Advisory',
				'Song.Sample_Duration',
				'Song.FullLength_Duration',
				'Song.Sample_FileID',
				'Song.FullLength_FIleID',
				'Song.provider_type',
				'Song.sequence_number'
			),
			'contain' => array(
				'Genre' => array(
					'fields' => array(
						'Genre.Genre'
					)
				),
				'Country' => array(
					'fields' => array(
						'Country.Territory',
						'Country.SalesDate',
						'Country.StreamingSalesDate',
						'Country.StreamingStatus',
						'Country.DownloadStatus',
					)
				),
				'Sample_Files' => array(
					'fields' => array(
						'Sample_Files.CdnPath',
						'Sample_Files.SaveAsName'
					)
				),
				'Full_Files' => array(
					'fields' => array(
						'Full_Files.CdnPath',
						'Full_Files.SaveAsName'
					)
				),
			),
			'group' => 'Song.ProdID, Song.provider_type',
			'order' => array(
				'Song.sequence_number', 
				'Song.ProdID'
			)
		));
		return $songDetails;
	}
}
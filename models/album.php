<?php
/**
 * File Name : album.php
 * File Description : Models page for the Albums table.
 * Author : m68interactive
 */

class Album extends AppModel  {
	var $name 		= 'Albums';
	var $useTable 	= 'Albums';
	var $primaryKey = 'ProdID';
	var $actsAs 	= array('Containable');
	var $uses 		= array('Featuredartist');
	var $hasOne 	= array(
		'Genre' => array(
			'className' => 'Genre',
			'foreignKey' => 'ProdID'
		),
		'Country' => array(
					'className' => 'Country',
					'foreignKey' => 'ProdID'
		),							
	);
	
	var $hasMany = array(
			'Song' => array(
				'className' => 'Song',
				'foreignKey' => 'ReferenceID'
			),
	);
	
	var $belongsTo = array(
		'Files' => array(
			'className' => 'Files',
			'foreignKey' => 'FileID'
		)
	);

	/*
	 Function Name: getTopAlbumData
	 Desc: gets the data for the top albums.
	*/
	function getTopAlbumData($territory, $ids_provider_type) {

		$this->recursive = 2;
		$topAlbumData = $this->find('all', array(
			'joins' => array(
				array(
					'type' => 'INNER',
					'table' => 'top_albums',
					'alias' => 'ta',
					'conditions' => array(
						'Album.ProdID = ta.album',
						'ta.territory' => $territory
					)
				),
				array(
					'type' => 'INNER',
					'table' => 'Songs',
					'alias' => 'Song',
					'conditions' => array(
						'Album.ProdID = Song.ReferenceID',
						'Album.provider_type = Song.provider_type'
					)
				),
				array(
					'type' => 'INNER',
					'table' => strtolower($territory).'_countries',
					'alias' => 'Country',
					'conditions' => array(
						'Country.ProdID = Song.ProdID',
						'Country.provider_type = Song.provider_type',
						'Country.DownloadStatus = 1',
						'Country.SalesDate != ""',
						'Country.SalesDate < NOW()'
					)
				)
			),
			'conditions' => array(
				'and' => array(
					array(
						"(Album.ProdID, Album.provider_type) IN (" . rtrim($ids_provider_type, ",'") . ")"
					),
				), 
				"1 = 1 GROUP BY Album.ProdID"
			),
			'fields' => array(
				'Album.ProdID',
				'Album.Title',
				'Album.ArtistText',
				'Album.AlbumTitle',
				'Album.Artist',
				'Album.ArtistURL',
				'Album.Label',
				'Album.Copyright',
				'Album.provider_type',
				'ta.sortId as sortID'
			),
			'contain' => array(
				'Genre' => array(
					'fields' => array(
						'Genre.Genre'
					)
				),
				'Files' => array(
					'fields' => array(
						'Files.CdnPath',
						'Files.SaveAsName',
						'Files.SourceURL'
					)
				)
			),
			'order' => 'sortID Asc',
			'limit' => 25
		));

		return $topAlbumData;

	}

	/*
	 Function Name: findSongsForAlbum
	 Desc: gets the songs for an album
	*/
	function findSongsForAlbum($prodId, $provider) {

		$this->recursive = 2;

		$albumData = $this->findSongs('all', array(
			'conditions' => array(
				'and' => array(
					array(
						'Album.ProdID' => $prodId,
						'Album.provider_type' => $provider,
						'Album.provider_type = Genre.provider_type'
					)
				),
				"1 = 1 GROUP BY Album.ProdID, Album.provider_type"
			),
			'fields' => array(
				'Album.ProdID'
			),
			'order' => array(
				'Country.SalesDate' => 'desc'
			),
			'cache' => 'yes'
		));

		return $albumData;
	}
        
	function getAlbum($id) {
            $this->recursive = -1;
            $details = $this->find('all', array(
                                   'conditions'=>array('Album.ProdID' => $id),
                                   'fields' => array(
                                                   'Album.ProdID',
                                                   'Album.AlbumTitle'
                                   )
            )
                   );
            return  $details;
	}        
}


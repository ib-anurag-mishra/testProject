<?php
/*
 File Name : video.php
File Description : Models page for the  videos table.
Author : m68interactive
*/

class Video extends AppModel
{
	var $name = 'Video';
	var $useTable = 'video';
	var $primaryKey = 'ProdID';

	var $actsAs = array('Containable','Sphinx');
	var $uses = array('Featuredartist','Country');

	var $hasOne = array(
			'Participant' => array(
					'className' => 'Participant',
					'conditions' => array('Participant.Role' => 'Composer'),
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
	 Function Name : getVideoData
	Desc : This would returna the video song data
	*/
	function getVideoData($id , $provider) {

		$this->recursive = 2;
		$this->Behaviors->attach('Containable');
		$data = $this->find('all', array(
				'conditions'=>array('Video.ProdID' => $id , 'Video.provider_type' => $provider),
				'fields' => array(
						'Video.ProdID',
						'Video.ProductID',
						'Video.Title',
						'Video.VideoTitle',
						'Video.Artist',
						'Video.ISRC',
						'Video.provider_type'
				),
				'contain' => array(
						'Full_Files' => array(
								'fields' => array(
										'Full_Files.CdnPath',
										'Full_Files.SaveAsName'
								),
						)
				)
		));
		return $data;
	}

	public function fetchVideoTopDownloaedVideos($countryPrefix, $country, $ids, $arrIntSonyIds = array(), $arrIntIodaIds = array()) {
			
		$this->unBindModel(array('belongsTo' => array('Sample_Files', 'Full_Files'), 'hasOne' => array('Participant', 'Genre', 'Country')));
			
		$topTenConditionVideos = '';
			
		if (count($arrIntSonyIds) > 0 && count($arrIntIodaIds) > 0) {
	
			$topTenConditionVideos = array( 'OR' => array(
					array('Video.ProdID' => $arrIntSonyIds, 'Video.provider_type' => 'sony'),
					array('Video.ProdID' => $arrIntIodaIds, 'Video.provider_type' => 'ioda')
			));
		} else if (count($arrIntSonyIds) > 0 ) {
	
			$topTenConditionVideos = array('Video.ProdID' => $arrIntSonyIds, 'Video.provider_type' => 'sony');
				
		} else if (count($arrIntIodaIds) > 0 ) {
	
			$topTenConditionVideos = array('Video.ProdID' => $arrIntIodaIds, 'Video.provider_type' => 'ioda');
		}
			
		$options = array(
				'fields' => array(
						'Video.ProdID',
						'Video.ReferenceID',
						'Video.Title',
						'Video.ArtistText',
						'Video.DownloadStatus',
						'Video.VideoTitle',
						'Video.Artist',
						'Video.Advisory',
						'Video.Sample_Duration',
						'Video.FullLength_Duration',
						'Video.provider_type',
						'Genre.Genre',
						'Country.Territory',
						'Country.SalesDate',
						'Sample_Files.CdnPath',
						'Sample_Files.SaveAsName',
						'Full_Files.CdnPath',
						'Full_Files.SaveAsName',
						'File.CdnPath',
						'File.SourceURL',
						'File.SaveAsName',
						'Sample_Files.FileID'
				),
				'group' => array('Video.ProdID'),
				'order' => array('FIELD(Video.ProdID,' . $ids . ') ASC'),
				'limit' => 10,
				'conditions' => array(
						array('Video.DownloadStatus' => 1),
						$topTenConditionVideos,
						array('Country.Territory' => $country),
						array('Country.SalesDate !=' => ''),
						array('Country.SalesDate <' => 'NOW()'),
						array('1 = 1')
				),
				'joins' => array(
						array(
								'table' => 'File',
								'alias' => 'Sample_Files',
								'type' 	=> 'LEFT',
								'conditions' => array('Video.Sample_FileID = Sample_Files.FileID')
						),
						array(
								'table' =>'File',
								'alias' =>'Full_Files',
								'type' =>'LEFT',
								'conditions' => array('Video.FullLength_FileID = Full_Files.FileID')
						),
						array(
								'table' => 'Genre',
								'alias' => 'Genre',
								'type' => 'LEFT',
								'conditions' => array('Genre.ProdID = Video.ProdID')
						),
						array(
								'table' => $countryPrefix . 'countries',
								'alias' => 'Country',
								'type' => 'LEFT',
								'conditions' => array('Country.ProdID = Video.ProdID', 'Video.provider_type = Country.provider_type')
						),
						array(
								'table' =>'File',
								'alias' =>'File',
								'type' =>'INNER',
								'conditions' => array('Video.Image_FileID = File.FileID')
						)
				)
		);
			
		return $this->find('all', $options);
	}
	
	public function fetchVideoDataByDownloadStatusAndProdId($prefix, $productId) {
	
		$this->unBindModel(array('belongsTo' => array('Sample_Files', 'Full_Files'), 'hasOne' => array('Participant', 'Genre', 'Country')));
	
		$options = array(
				'fields' => array(
						'Video.ProdID',
						'Video.Advisory',
						'Video.ReferenceID',
						'Video.VideoTitle',
						'Video.ArtistText',
						'Video.FullLength_Duration',
						'Video.CreatedOn',
						'Video.Image_FileID',
						'Video.provider_type',
						'Video.Genre',
						'Sample_Files.CdnPath',
						'Sample_Files.SaveAsName',
						'Full_Files.CdnPath',
						'Full_Files.SaveAsName',
						'File.CdnPath',
						'File.SourceURL',
						'File.SaveAsName',
						'Sample_Files.FileID',
						'Country.Territory',
						'Country.SalesDate'
				),
				'conditions' => array('Video.DownloadStatus' => '1', 'Video.ProdID' => $productId, 'Country.SalesDate !=' => '', 'Country.SalesDate <=' => date('Y-m-d')),
				'joins' => array(
						array(
								'table' => $prefix . 'countries',
								'alias' => 'Country',
								'type' 	=> 'LEFT',
								'conditions' => array('Video.ProdID = Country.ProdID', 'Video.provider_type = Country.provider_type')
						),
						array(
								'table' => 'File',
								'alias' => 'Sample_Files',
								'type'  => 'LEFT',
								'conditions' => array('Video.Sample_FileID = Sample_Files.FileID')
						),
						array(
								'table' => 'File',
								'alias' => 'Full_Files',
								'type'  => 'LEFT',
								'conditions' => array('Video.FullLength_FileID = Full_Files.FileID')
						),
						array(
								'table' => 'PRODUCT',
								'alias' => 'PRODUCT',
								'type'  => 'LEFT',
								'conditions' => array('PRODUCT.ProdID = Video.ProdID', 'PRODUCT.provider_type = Video.provider_type')
						),
						array(
								'table' => 'File',
								'alias' => 'File',
								'type'  => 'INNER',
								'conditions' => array('Video.Image_FileID = File.FileID')
						)
				)
		);
			
		return $this->find('all', $options);
	}
}
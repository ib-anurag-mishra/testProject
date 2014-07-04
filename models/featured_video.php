<?php

class FeaturedVideo extends AppModel {

	public function fetchFeaturedVideo( $prefix, $territory, $explicitContent = true ) {

		$options = array(
				'fields'	 => array(
						'FeaturedVideo.id',
						'FeaturedVideo.ProdID',
						'Video.ProdID',
						'Video.Image_FileID',
						'Video.VideoTitle',
						'Video.ArtistText',
						'Video.provider_type',
						'Video.Advisory',
						'File.CdnPath',
						'File.SourceURL',
						'Video_file.SaveAsName',
						'Country.SalesDate'
				),
				'joins'		 =>	array(
						array(
								'table' => 'video',
								'alias' => 'Video',
								'type'	=> 'left',
								'conditions' => array(
										'FeaturedVideo.ProdID = Video.ProdID',
										'FeaturedVideo.provider_type = Video.provider_type'
								)
						),
						array(
								'table' => 'File',
								'alias'	=> 'File',
								'type'	=> 'left',
								'conditions' => array( 'File.FileID = Video.Image_FileID' )
						),
						array(
								'table' => 'File',
								'alias'	=> 'Video_file',
								'type' 	=> 'left',
								'conditions' => array( 'Video_file.FileID = Video.FullLength_FileID' )
						),
						array(
								'table' => $prefix.'countries',
								'alias'	=> 'Country',
								'type'	=> 'left',
								'conditions' => array(
										'Video.ProdID = Country.ProdID',
										'Video.provider_type = Country.provider_type'
								)
						)
				)
		);

		if ( $explicitContent === false ) {
			$options['conditions'] = array( 'FeaturedVideo.territory' => $territory, 'Country.SalesDate <=' => 'NOW()', 'Video.Advisory !=' => 'T' );
		} else {
			$options['conditions'] = array( 'FeaturedVideo.territory' => $territory, 'Country.SalesDate <=' => 'NOW()' );
		}

		return $this->find('all', $options);
	}
}
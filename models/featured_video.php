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
                                                'Video.FullLength_SaveAsName',
						'Video.Image_SaveAsName',
						'Video.CdnPath',						
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
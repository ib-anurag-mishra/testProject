<?php
 /*
 File Name : metadata.php
 File Description : Metadata Model
 Author : maycreate
 */
class Metadata extends AppModel {
	var $name = 'Metadata';
	var $useTable = 'METADATA';
	var $primaryKey = 'ProdId';
	var $actsAs = array('Containable');
	 var $belonsTo = array(
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'ProdID'
		)
	);
	 
	var $hasOne = array(
		'Genre' => array(
			'className' => 'Genre',
			'foreignKey' => 'ProdID'
		),
		'Physicalproduct' => array(
			'className' => 'Physicalproduct',
			'foreignKey' => 'ProdID'
		)

	);
	function gettrackdata($id) {
		$getTrackData = $this->find('first', array('conditions' => array('ProdID' => $id),'fields' => array('Title','Artist')));
		return $getTrackData;
	}	
}

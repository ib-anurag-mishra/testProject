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
	public function gettrackdata($id)
	{
		$getTrackData = $this->find('first', array('conditions' => array('ProdID' => $id),'fields' => array('Title','Artist')));
		return $getTrackData;
	}
	function paginateCount($conditions = null, $recursive = 0, $extra = array())
	{	    
	    $genre = $conditions['Genre.Genre'];
	    $sql = "SELECT distinct Artist AS `count` FROM `METADATA` AS `Metadata` LEFT JOIN `Genre` AS `Genre` ON (`Genre`.`ProdID` = `Metadata`.`ProdId`) LEFT JOIN `PhysicalProduct` AS `Physicalproduct` ON (`Physicalproduct`.`ProdID` = `Metadata`.`ProdId`) WHERE `Genre`.`Genre` = '$genre'";	   
	    $results = $this->query($sql);
	    return count($results);
	}
	
}

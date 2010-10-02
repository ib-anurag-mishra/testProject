<?
/*
 File Name : song.php
 File Description : Models page for the  Songs table.
 Author : maycreate
 */

class Album extends AppModel
{
	var $name = 'Albums';
	var $useDbConfig = 'freegal';
	var $useTable = 'Albums';
	var $primaryKey = 'ProdID';
	var $actsAs = array('Containable');
	var $uses = array('Featuredartist');
	var $hasOne = array(
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
	function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
		$recursive = 2;
		$group = array('Album.ProdID');
		return $this->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group'));
	}	
	function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
		$group = array('Album.ProdID');
	    $results = $this->find('count', compact('conditions','recursive', 'group'));
	    return $results;
	}	
}
?>
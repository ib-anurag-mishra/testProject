<?
/*
 File Name : album.php
 File Description : Models page for the  Songs table.
 Author : m68interactive
 */

class Album extends AppModel
{
	var $name = 'Albums';
//	var $useDbConfig = 'freegal';
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
	
	/*function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
		$group = array('Album.ProdID');
	    $results = $this->find('count', compact('conditions','recursive', 'group'));
	    return $results;
	}*/
}
?>
<?php
/* Artist Fixture generated on: 2010-02-23 17:02:21 : 1266945141 */
class ArtistFixture extends CakeTestFixture {
	var $name = 'Artist';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'artist_name' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'artist_image' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 3,
			'artist_name' => 'Amerie',
			'artist_image' => 'img/artistimg/foofighters.png'
		),
		array(
			'id' => 4,
			'artist_name' => 'Bravehearts',
			'artist_image' => 'img/artistimg/michaeljackson.png'
		),
	);
}
?>
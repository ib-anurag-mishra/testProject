<?php
/* Featuredartist Fixture generated on: 2010-02-23 17:02:28 : 1266945148 */
class FeaturedartistFixture extends CakeTestFixture {
	var $name = 'Featuredartist';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'artist_name' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'artist_image' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 16,
			'artist_name' => 'Amerie',
			'artist_image' => 'img/featuredimg/aliciakeys.png'
		),
		array(
			'id' => 17,
			'artist_name' => 'Bob Dylan',
			'artist_image' => 'img/featuredimg/katdeluna.png'
		),
	);
}
?>
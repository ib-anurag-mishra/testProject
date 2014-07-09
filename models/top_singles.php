<?php

/*
 File Name : top_album.php
File Description : Models page for the  top_albums table.
Author : m68interactive
*/

class TopSingles extends AppModel
{
	var $name = 'TopSingles';

	function getAllTopSingles($territory) {
		return $this->find('all', array(
			'conditions' => array(
				'territory' => $territory, 
			),
			'recursive' => -1,
			'order' => array(
				'id' => 'asc'
			)
		));
	}
}

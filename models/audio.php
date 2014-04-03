<?php
/*
 File Name : audio.php
 File Description : Models page for the audio table.
 Author : m68interactive
*/


class Audio extends AppModel
{
  var $name = 'Audio'; 
  var $useTable = 'Audio';  
  var $primaryKey = 'TrkID';
  var $belongsTo = array(
		'Files' => array(
			'className' => 'Files',
			'foreignKey' => 'FileID'
		)
	);
}
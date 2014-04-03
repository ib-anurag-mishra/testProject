<?php
/*
 File Name : graphic.php
 File Description : Models page for the  graphic table.
 Author : m68interactive
*/

class Graphic extends AppModel
{
  var $name = 'Graphic';
  var $useTable = 'Graphic';  
  var $primaryKey = 'FileID';
  
  var $belongsTo = array(
		'Files' => array(
			'className' => 'Files',
			'foreignKey' => 'FileID'
		)
	);
}
<?php
/*
 File Name : section.php
File Description : Section page.
Author : m68interactive
*/

class Section extends AppModel {
	var $name = 'Section';
	var $displayField = 'title';
	var $validate = array(
			'title' => array(
					'notempty' => array(
							'rule' => array('notempty'),
					),
			),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasMany = array(
			'Question' => array(
					'className' => 'Question',
					'foreignKey' => 'section_id',
					'dependent' => false,
					'conditions' => '',
					'fields' => '',
					'order' => 'Question.id ASC',
					'limit' => '',
					'offset' => '',
					'exclusive' => '',
					'finderQuery' => '',
					'counterQuery' => ''
			)
	);
}
?>
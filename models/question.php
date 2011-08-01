<?php
/*
 File Name : question.php
 File Description : Question page.
 Author : m68interactive
*/

class Question extends AppModel {
	var $name = 'Question';
	var $displayField = 'question';
	var $validate = array(
		'section_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'question' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'answer' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
	);
	
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
		'Section' => array(
			'className' => 'Section',
			'foreignKey' => 'section_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
?>
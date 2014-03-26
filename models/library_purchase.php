<?php
/*
 File Name : library_purchase.php
File Description : Models page for the  library purchase functionality.
Author : m68interactive
*/

class LibraryPurchase extends AppModel
{
	var $name = 'LibraryPurchase';
	var $actsAs = array('Multivalidatable');

	var $belongsTo = array(
			'Library' => array(
					'className' => 'Library',
					'foreignKey' => 'id'
			)
	);

	var $validationSets = array(
			'library_step5' => array(
					'purchased_order_num' => array(
							'purchased_order_num-1' => array('rule' => 'notEmpty', 'allowEmpty' =>  false, 'message' => 'Please provide a purchase order number.', 'last' => true),
							'purchased_order_num-2' => array('rule' => array('noDuplicates', array('library_id', 'purchased_order_num')), 'allowEmpty' =>  false, 'message' => 'This purchase order number already exists in our database for this Library.')
					),
					'purchased_amount' => array(
							'purchased_amount-1' => array('rule' => 'notEmpty', 'allowEmpty' =>  false, 'message' => 'Please provide the total amount for purchased tracks.', 'last' => true),
							'purchased_amount-2' => array('rule' => 'numeric', 'allowEmpty' =>  false, 'message' => 'Please provide the total amount for purchased tracks as a numeric value.')
					)
			),
			'library_step5_edit' => array(
					'purchased_order_num' => array('rule' => array('noDuplicates', array('library_id', 'purchased_order_num')), 'allowEmpty' =>  true, 'message' => 'This purchase order number already exists in our database for this Library.'),
					'purchased_amount' => array('rule' => 'numeric', 'allowEmpty' =>  true, 'message' => 'Please provide the total amount for purchased tracks as a numeric value.')
			)
	);

	/*
	 Function Name : noDuplicates
	Desc : to validate that there are no duplicate records
	*/
	function noDuplicates($value, $params) {
		/* If we happen to editing an existing record then don't count this record in the check for duplicates */
		if (!empty($this->id))
			$conditions[] = array($this->primaryKey . ' <>' => $this->id);

		/* Add a condition for each field we want to check against */
		foreach ($params as $field) {
			/* Check if value is empty. If it is then we want to check for a NULL value against this field */
			if($this->data[$this->name][$field])
				$fieldVal = $this->data[$this->name][$field];
			else
				$fieldVal = null;
			$conditions[] = array($field => $fieldVal);
		}
		$existingFieldsCount = $this->find( 'count', array('conditions' => $conditions, 'recursive' => -1) );
		return $existingFieldsCount < 1;
	}
}
?>
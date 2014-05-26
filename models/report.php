<?php
/*
 File Name : report.php
File Description : Models page for the report functionality.
Author : m68interactive
*/

class Report extends AppModel
{
	var $name = 'Report';
	var $useTable = false;
	var $actsAs = array('Multivalidatable');

	var $validationSets = array(
			'reports_date' => array(
					'Territory' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Territory.'),
					'library_id' => array('rule' => array('minLength', 1), 'allowEmpty' =>  false, 'message' => 'Please select a Library.'),
					'reports_daterange' => array('rule' => array('minLength', 1), 'allowEmpty' =>  false, 'message' => 'Please select the date range.'),
					'date' => array(
							'dateRule-1' => array('rule' => array('minLength', 1), 'allowEmpty' =>  false, 'message' => 'Please select a Date for.', 'last' => true),
							'dateRule-2' => array('rule' => array('date', 'mdy'), 'allowEmpty' =>  false, 'message' => 'Enter a valid date format.')
					)
			),
			'reports_manual' => array(
					'Territory' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Territory.'),
					'library_id' => array('rule' => array('minLength', 1), 'allowEmpty' =>  false, 'message' => 'Please select a Library.'),
					'reports_daterange' => array('rule' => array('minLength', 1), 'allowEmpty' =>  false, 'message' => 'Please select the date range.'),
					'date_from' => array(
							'date_fromRule-1' => array('rule' => array('minLength', 1), 'allowEmpty' =>  false, 'message' => 'Please select a From Date.', 'last' => true),
							'date_fromRule-2' => array('rule' => array('date', 'mdy'), 'allowEmpty' =>  false, 'message' => 'Enter a valid date format.')
					),
					'date_to' => array(
							'date_toRule-1' => array('rule' => array('minLength', 1), 'allowEmpty' =>  false, 'message' => 'Please select a To Date.', 'last' => true),
							'date_toRule-2' => array('rule' => array('date', 'mdy'), 'allowEmpty' =>  false, 'message' => 'Enter a valid date format.')
					)
			)
	);
}
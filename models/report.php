<?php
/*
File Name : report.php
File Description : Models page for the report functionality.
Author : maycreate
*/
class Report extends AppModel
{
    var $name = 'Report';
    var $useTable = false;

    var $validate = array(
      'library_id' => array('rule' => 'notEmpty', 'allowEmpty' =>  false, 'message' => 'Please select a Library.'),
      'reports_daterange' => array('rule' => 'notEmpty', 'allowEmpty' =>  false, 'message' => 'Please select the date range.'),
      'date' => array('rule' => 'notEmpty', 'message' => 'Please select a Date for your report first.')
    );
}
?>
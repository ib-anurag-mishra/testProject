<?php
/*
 File Name : libraries_timezone.php
 File Description : Models page for the libraries timezone functionality.
 Author : m68interactive
*/

class LibrariesTimezone extends AppModel {
  
  var $name = 'LibrariesTimezone';
  var $useTable = 'libraries_timezone';
  var $primaryKey = 'library_id';
  
    var $belongsTo = array(
        'Library' => array(
                'className' => 'Library',
                'foreignKey' => 'library_id'
        )        							
    );
   
    var $validate = array(
      'library_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Name.'),
      'library_timezone' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Timezone for Library.')
    );
  
 
}
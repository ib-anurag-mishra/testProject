<?php
 /*
 File Name : library_template.php
 File Description : Models page for the  library template functionality.
 Author : maycreate
 */
class LibraryTemplate extends AppModel
{
    var $name = 'LibraryTemplate';
    
    var $belongsTo = array(
      'Library' => array(
      'className' => 'Library',
      'foreignKey' => 'library_template_id'
      )
    );
    
}
?>
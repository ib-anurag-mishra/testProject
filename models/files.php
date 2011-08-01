<?php
/*
 File Name : files.php
 File Description : Models page for the  file table.
 Author : m68interactive
*/

class Files extends AppModel
{
  var $name = 'Files';
//  var $useDbConfig = 'freegal';  
  var $useTable = 'File';
  var $primaryKey = 'FileID';
}
?>
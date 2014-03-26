<?php
/*
 File Name : news.php
 File Description : News page.
 Author : m68interactive
*/

class News extends AppModel {
	var $name = 'News';
	var $displayField = 'news';
	var $validate = array(

		'subject' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		)
	);
	
	function getNextAutoIncrement()
	{
        $next_increment = 0;
        $table = Inflector::tableize($this->name);
        $query = "SHOW TABLE STATUS LIKE '$table'";
        $db =& ConnectionManager::getDataSource($this->useDbConfig);
        $result = $db->rawQuery($query);
        while ($row = mysql_fetch_assoc($result)) {
            $next_increment = $row['Auto_increment'];
        }
        return $next_increment;
    } 
}
?>
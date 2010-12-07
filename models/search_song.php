<?php
class SearchSong extends AppModel
{
    var $name = 'SearchSong';
	var $useTable = false;
	var $actsAs = array('Sphinx');
}
?>
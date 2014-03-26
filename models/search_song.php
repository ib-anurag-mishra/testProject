<?php
/*
 File Name : search_song.php
File Description : Models page for the searching song functionality.
Author : m68interactive
*/
class SearchSong extends AppModel
{
	var $name 	  = 'SearchSong';
	var $useTable = false;
	var $actsAs   = array('Sphinx');
}
?>
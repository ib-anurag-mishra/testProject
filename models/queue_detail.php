<?php
/*
 File Name : video.php
File Description : Models page for the  videos table.
Author : m68interactive
*/

class QueueDetail extends AppModel
{
	var $name = 'QueueDetail';
	var $useTable = 'queue_details';
        
    var $validate = array(
            'queue_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Playlist Name.'),
            'artist_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please enter artist name.'),
            'album_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select the Album.'),
            'song_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select the song.'),
       ); 
}
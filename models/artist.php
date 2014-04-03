<?php
/*
 File Name : artist.php
 File Description : Models page for the artists table.
 Author : m68interactive
*/

class Artist extends AppModel
{
    var $name = 'Artist';

    /*
     Function Name : insert
     Desc : save an artist imahe in the db
    */
    function insert($data) {
        if($this->save($data)){
            return true;
        }else{
            return false;
        }
    }
    
    /*
     Function Name : getallartists
     Desc : lists all the artists for slideshow in the homepage
    */
    function getallartists() {
		if (($artists = Cache::read("artists")) === false) {
			$getArtists = $this->find('all');
			Cache::write("artists", $getArtists);
		}
		$getArtists = Cache::read("artists");
        return $getArtists;
    }
    
    /*
     Function Name : getartistdata
     Desc : 
    */
    function getartistdata($id) {
        $getArtistData = $this->find('first', array('conditions' => array('id' => $id)));       
        return $getArtistData;
    }

    /*
     Function Name : del
     Desc : 
    */
    function del($id) {
        if($this->delete($id)){
            return true;
        }else{
            return false;
        }
    }
}
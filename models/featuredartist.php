<?php
/*
 File Name : download.php
 File Description : Models page for the  downloads table.
 Author : m68interactive
*/

class Featuredartist extends AppModel
{
    var $name = 'Featuredartist';
    
    /*
    Function Name : insert
    Desc : updatesa admin user data
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
    Desc : gets all the artists
    */
    function getallartists() {
//		if (($artists = Cache::read("featured")) === false) {
			$getArtists = $this->find('all');
			Cache::write("featured", $getArtists);
//		}
		$getArtists = Cache::read("featured");
        return $getArtists;
    }
    
     /*
    Function Name : getartistdata
    Desc : gets data for the specified artist
    */
    function getartistdata($id) {
       $getArtistData = $this->find('first', array('conditions' => array('Featuredartist.id' => $id)));
       return $getArtistData;
    }
   
    /*
    Function Name : del
    Desc : deletes a featured artist
    */
    function del($id) {
     if($this->delete($id)){
        return true;
      }else{
        return false;
      }
   }
}
?>
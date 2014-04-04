<?php
/*
 File Name : top_album.php
File Description : Models page for the  top_albums table.
Author : m68interactive
*/

class TopAlbum extends AppModel
{
	var $name = 'TopAlbum';

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
            $getArtists = Cache::read("artists");
            if ($getArtists === false) {
            $getArtists = $this->find('all');
            Cache::write("artists", $getArtists);
            }                
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
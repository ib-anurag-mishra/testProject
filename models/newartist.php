<?php
/*
 File Name : metadata.php
File Description : Newartist Model
Author : m68interactive
*/

class Newartist extends AppModel
{
	var $name = 'Newartist';

	/*
	 Function Name : insert
	Desc : updatesa admin user data
	*/
	function insert($data) {
		if($this->save($data))
		{
			return true;
		}else{
			return false;
		}
	}

	/*
	 Function Name : getallartists
	Desc : gets all the artists
	*/
	function getallnewartists() {
		$getArtists = $this->find('all');
		Cache::write("newartists", $getArtists);
		$getArtists = Cache::read("newartists");
		return $getArtists;
	}

	/*
	 Function Name : getartistdata
	Desc : gets data for the specified artist
	*/
	function getartistdata($id) {
		$getArtistData = $this->find('first', array('conditions' => array('Newartist.id' => $id)));
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
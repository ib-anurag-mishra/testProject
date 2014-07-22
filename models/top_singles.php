<?php

/*
 File Name : top_album.php
File Description : Models page for the  top_albums table.
Author : m68interactive
*/

class TopSingles extends AppModel
{
	var $name = 'TopSingles';

	function getAllTopSingles($territory) {
		return $this->find('all', array(
			'conditions' => array(
				'territory' => $territory, 
			),
			'recursive' => -1,
			'order' => array(
				'id' => 'asc'
			)
		));
	}
        
        /*
         Function Name : insert
         Desc : save an video data in the db
        */
        function insert($data) {
            if($this->save($data)){
                return true;
            }else{
                return false;
            }
        }        


	/*
	Function Name : gettopsingledata
	Desc: gets data for the specified top single
	*/
	function gettopsingledata($id) {
		$getTopSingleData = $this->find('first', array('conditions' => array('TopSingles.id' => $id)));
		return $getTopSingleData;
	}

	/*
	Function Name : getartistdata
	Desc: gets data for the specified artist
	*/
	function getartistdata($id) {
		$getArtistData = $this->find('first', array('conditions' => array('TopSingles.id' => $id)));
		return $getArtistData;
	}

	/*
	Function Name : getallartists
	Desc: gets all the artists
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
	 Function Name : del
	 Desc: deletes a top single
	*/
	function del($id) {
		if ($this->delete($id)) {
			return true;
		} else {
			return false;
		}
	}

	
}

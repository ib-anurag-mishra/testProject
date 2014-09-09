<?php
/**
 * File Name : top_album.php
 * File Description : Models page for the top_albums table.
 * Author: m68interactive
 */

class TopAlbum extends AppModel {
	var $name = 'TopAlbum';

	/*
	 Function Name : insert
	 Desc: updates admin user data
	*/
	function insert($data) {
		if ($this->save($data)) {
			return true;
		} else {
			return false;
		}
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
	Function Name : getartistdata
	Desc: gets data for the specified artist
	*/
	function getartistdata($id) {
		$getArtistData = $this->find('first', array('conditions' => array('TopAlbum.id' => $id)));
		return $getArtistData;
	}

	/*
	 Function Name : del
	 Desc: deletes a featured artist
	*/
	function del($id) {
		if ($this->delete($id)) {
			return true;
		} else {
			return false;
		}
	}

	/*
	 Function Name : getTopAlbumsList
	 Desc: gets the list of top albums
	*/
	function getTopAlbumsList($territory) {
		$topAlbumsList = $this->find('all', array(
			'fields' => array(
				'TopAlbum.album',
				'TopAlbum.provider_type'
			),
			'conditions' => array(
				'TopAlbum.territory' => $territory,
				'TopAlbum.language' => Configure::read('App.LANGUAGE')
			),
			'recursive' => -1
		));
		return $topAlbumsList;
	}
        
	/*
	 Function Name : getTopAlbumsList
	 Desc: gets the list of top albums
	*/
	function getAdminTopAlbumsList($territory) {
		$topAlbumsList = $this->find('all', array(
			'conditions' => array(
				'TopAlbum.territory' => $territory,
				'TopAlbum.language' => Configure::read('App.LANGUAGE')
			),
			'recursive' => -1
		));
		return $topAlbumsList;
	}        

}

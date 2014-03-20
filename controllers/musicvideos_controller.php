<?php
/***
 *
* @page: musicvideos_controllers.php
* This controller handlea music videos requestes
*
*/

class MusicvideosController extends AppController {

	var $name = 'Musicvideos';
	var $components = array('Downloadsvideos');
	var $uses = array('Videos', 'Library', 'Album', 'LatestDownloadVideo');


	/**
	 *
	 * @page: beforeFilter
	 * @param null
	 * This default action is called before any action
	 *
	 */
	function beforeFilter(){
		parent::beforeFilter();
	}

}

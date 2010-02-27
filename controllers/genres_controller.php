<?php
/*
 File Name : genres_controller.php
 File Description : Genre controller page
 Author : maycreate
 */
Class GenresController extends AppController
{
	
	function view( $Genre = null )
	{
		$this -> layout = 'home';
		
		if( !$Genre )
		{
			$this -> Session -> setFlash( __( 'Invalid Genre.', true ) );
			$this -> redirect( array( 'controller' => '/', 'action' => 'index' ) );
		}//$this->Genre->recursive = 0;
		if( $Genre != 'all' )
		{
			$this -> paginate = array( 'conditions' => array( 'Genre.genre' => $Genre ) );
		}

		$this->Genre->recursive = 1;
		$this->paginate = array('conditions' => array('Genre.genre' => $Genre));
		$data = $this->paginate('Genre');
		$this->set('genres', $data);
		// $this->set('genre', $this->Genre->read(null, $Genre));
		
	}
}


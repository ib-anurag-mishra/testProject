<?php
/*
 File Name : libraries_controller.php
 File Description : Library controller page
 Author : maycreate
 */
Class LibrariesController extends AppController
{
	var $name = 'Libraries';
	var $layout = 'admin';
	var $helpers = array( 'Html', 'Ajax', 'Javascript', 'Form' );
	var $components = array( 'Session', 'Auth', 'Acl' );
	var $uses = array( 'Library', 'User' );/*
    Function Name : managelibraries
    Desc : action for listing all the libraries
   */
	public function managelibrary()
	{
		$this -> set( 'libraries', $this -> Library -> getalllibraries() );
	}
	
	public function libraryform()
	{
		if( !empty( $this -> params[ 'named' ][ 'id' ] ) )//gets the values from the url in form  of array
		{
			$libraryId = $this -> params[ 'named' ][ 'id' ];
			
			if( trim( $libraryId ) != '' && is_numeric( $libraryId ) )
			{
				$this -> set( 'formAction', 'libraryform/id:' . $libraryId );
				$this -> set( 'formHeader', 'Edit Library' );
				$getLibraryDataObj = new Library();
				$getData = $getLibraryDataObj -> getlibrarydata( $libraryId );
				$this -> set( 'getData', $getData );//editting a value
				if( isset( $this -> data ) )
				{
					$updateObj = new Library();
					$getData[ 'Library' ] = $this -> data[ 'Library' ];
					$this -> set( 'getData', $getData );
					$this -> Library -> id = $this -> data[ 'Library' ][ 'id' ];
					
					if( trim( $this -> data[ 'Library' ][ 'password' ] ) != '' )
					{
						$this -> data[ 'Library' ][ 'password' ] = md5( $this -> data[ 'Library' ][ 'password' ] );
					}
					else
					{// do not update the password
						$this -> data[ 'Library' ] = $updateObj -> arrayremovekey( $this -> data[ 'Library' ], 'password' );
					}
					
					$this -> Library -> set( $this -> data[ 'Library' ] );
					
					if( $this -> Library -> save() )
					{
						$this -> Session -> setFlash( 'Data has been saved Sucessfully!', 'modal', array( 'class' => 'modal success' ) );
						$this -> redirect( '/libraries/managelibrary' );
					}//}
					else {
						$this -> Session -> setFlash( 'Data could not be updated.', 'modal', array( 'class' => 'modal problem' ) );
					}
				}//editting a value
			}
		}
		else
		{
			$arr = array();
			$this -> set( 'getData', $arr );
			$this -> set( 'formAction', 'libraryform' );
			$this -> set( 'formHeader', 'Create Library' );//insertion Operation
			if( isset( $this -> data ) )
			{
				$this -> data[ 'User' ][ 'type_id' ] = 4;
				$admin = $this -> User -> save( $this -> data );
				
				if( !empty( $admin ) )
				{
					$this -> data[ 'Library' ][ 'admin_id' ] = $this -> User -> id;
					
					if( $this -> Library -> save( $this -> data ) )
					{
						$this -> Session -> setFlash( 'Data has been saved Sucessfully!', 'modal', array( 'class' => 'modal success' ) );
						$this -> redirect( '/libraries/managelibrary' );
					}
					else
					{
						$this -> Session -> setFlash( 'Data could not be saved.', 'modal', array( 'class' => 'modal problem' ) );
					}
				}
			}//insertion operation
		}
	}/*
    Function Name : delete
    Desc : For deleting a library
   */
	public function delete()
	{
		$deleteLibraryId = $this -> params[ 'named' ][ 'id' ];
		$deleteObj = new Library();
		
		if( $deleteObj -> del( $deleteLibraryId ) )
		{
			$this -> Session -> setFlash( 'Data deleted Sucessfully!', 'modal', array( 'class' => 'modal success' ) );
			$this -> redirect( '/libraries/managelibrary' );
		}
		else
		{
			$this -> Session -> setFlash( 'Error occured while deleteting the record', 'modal', array( 'class' => 'modal problem' ) );
			$this -> redirect( '/libraries/managelibrary' );
		}
	}
}


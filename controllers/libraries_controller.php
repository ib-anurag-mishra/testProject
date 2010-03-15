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
    var $components = array( 'Session', 'Auth', 'Acl', 'RequestHandler' );
    var $uses = array( 'Library', 'User' );
        
    /*
    Function Name : admin_managelibrary
    Desc : action for listing all the libraries
   */
    public function admin_managelibrary()
    {
        $this -> set( 'libraries', $this -> Library -> getalllibraries() );
    }
	
    /*
    Function Name : admin_libraryform
    Desc : action for adding the libraries
   */
    public function admin_libraryform()
    {
        if( !empty( $this -> params[ 'named' ][ 'id' ] ) )//gets the values from the url in form  of array
        {
            $libraryId = $this -> params[ 'named' ][ 'id' ];
            $condition = 'edit';
            if( trim( $libraryId ) != '' && is_numeric( $libraryId ) )
            {
                $this -> set( 'formAction', 'admin_libraryform/id:' . $libraryId );
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
                    
                    /*if( trim( $this -> data[ 'Library' ][ 'password' ] ) != '' )
                    {
                            $this -> data[ 'Library' ][ 'password' ] = md5( $this -> data[ 'Library' ][ 'password' ] );
                    }
                    else
                    {// do not update the password
                            $this -> data[ 'Library' ] = $updateObj -> arrayremovekey( $this -> data[ 'Library' ], 'password' );
                    }*/
                    
                    $this -> Library -> set( $this -> data[ 'Library' ] );
                    
                    if( $this -> Library -> save() )
                    {
                        $this -> Session -> setFlash( 'Data has been saved Sucessfully!', 'modal', array( 'class' => 'modal success' ) );
                        $this -> redirect( 'managelibrary' );
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
            $condition = 'add';
            $libraryId = '';
            $this -> set( 'getData', $arr );
            $this -> set( 'formAction', 'admin_libraryform' );
            $this -> set( 'formHeader', 'Library Setup' );//insertion Operation
            if( isset( $this -> data ) )
            {
                if( $this -> Library -> save( $this -> data ) )
                {
                    $this -> Session -> setFlash( 'Data has been saved Sucessfully!', 'modal', array( 'class' => 'modal success' ) );
                    $this -> redirect( 'managelibrary' );
                }
                else
                {
                    $this -> Session -> setFlash( 'Data could not be saved.', 'modal', array( 'class' => 'modal problem' ) );
                }
            }//insertion operation
        }
        //$allAdmins = $this->User->getalllibraryadmins($condition,$libraryId);
        //$this -> set( 'allAdmins', $allAdmins );
    }
    
    public function admin_ajax_validate()
    {
        Configure::write('debug', 0);
	$this->layout = 'ajax';
	if ($this->RequestHandler->isAjax()) {
            if (!empty($this->data)) {
                $this->Library->create();
                $this->Library->set($this->data['Library']);
                $this->Library->setValidation('library_'.$this->data['Library']['libraryStepNum']);
                if($this->Library->validates()){
                    $message = __('You will be redirected to the next step shortly...', true);
                    $data = $this->data;
                    $this->set('success', compact('message', 'data'));
                }
                else {
                    $message = __('To proceed further please enter the data correctly.', true);
                    $Library = $this->Library->invalidFields();
                    $data = compact('Library');
                    $this->set('errors', compact('message', 'data'));
                }
            }
                
        }
    }
    
    /*
    Function Name : admin_delete
    Desc : For deleting a library
   */
    public function admin_delete()
    {
        $deleteLibraryId = $this -> params[ 'named' ][ 'id' ];		
        if($this->Library->delete( $deleteLibraryId ) )
        {
            $this -> Session -> setFlash( 'Data deleted Sucessfully!', 'modal', array( 'class' => 'modal success' ) );
            $this -> redirect( 'managelibrary' );
        }
        else
        {
            $this -> Session -> setFlash( 'Error occured while deleteting the record', 'modal', array( 'class' => 'modal problem' ) );
            $this -> redirect( 'managelibrary' );
        }
    }
}
<?php
/*
 File Name : artists_controller.php
 File Description : Artist controller page
 Author : maycreate
 */
Class ArtistsController extends AppController
{
	var $name = 'Artists';
	var $uses = array( 'Featuredartist', 'Physicalproduct', 'Artist', 'Newartist','Files' );
	var $layout = 'admin';
	var $helpers = array( 'Html', 'Ajax', 'Javascript', 'Form' );
	var $components = array( 'Session', 'Auth', 'Acl','RequestHandler');
	
	function beforeFilter() {
	    parent::beforeFilter(); 
	    $this->Auth->allowedActions = array('view','search','download');
	}
	
	/*
    Function Name : managefeaturedartist
    Desc : action for listing all the featured artists
   */
	public function admin_managefeaturedartist()
	{
		$artistObj = new Featuredartist();
		$artists = $artistObj -> getallartists();
		$this -> set( 'artists', $artists );
	}/*
    Function Name : artistform
    Desc : action for displaying the add/edit featured artist form
   */
	public function admin_artistform()
	{
		if( !empty( $this -> params[ 'named' ] ) )//gets the values from the url in form  of array
		{
			$artistId = $this -> params[ 'named' ][ 'id' ];
			
			if( trim( $artistId ) != '' && is_numeric( $artistId ) )
			{
				$this -> set( 'formAction', 'admin_updatefeaturedartist/id:' . $artistId );
				$this -> set( 'formHeader', 'Edit Featured Artist' );
				$getArtistrDataObj = new Featuredartist();
				$getData = $getArtistrDataObj -> getartistdata( $artistId );
				$this -> set( 'getData', $getData );
				$condition = 'edit';
				$artistName = $getData[ 'Featuredartist' ][ 'artist_name' ];
			}
		}
		else
		{
			$this -> set( 'formAction', 'admin_insertfeaturedartist' );
			$this -> set( 'formHeader', 'Add Featured Artist' );
			$getFeaturedDataObj = new Featuredartist();
			$featuredtData = $getFeaturedDataObj -> getallartists();
			$condition = 'add';
			$artistName = '';
		}
		
		$getArtistDataObj = new Physicalproduct();
		$getArtistData = $getArtistDataObj -> getallartistname( $condition, $artistName );
		$this -> set( 'getArtistData', $getArtistData );
	}/*
    Function Name : insertfeaturedartist
    Desc : inserts a featured artist
   */
	public function admin_insertfeaturedartist()
	{
		$errorMsg = '';
		$newPath = '../webroot/img/featuredimg/';
		$fileName = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
		$newPath = $newPath . $fileName;
		move_uploaded_file( $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ], $newPath );
		$filePath = $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ];
		
		if( $this -> data[ 'Artist' ][ 'artist_name' ] == '' )
		{
			$errorMsg .= 'Please select an Artist.<br/>';
		}
		
		
		if( $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ] == '' )
		{
			$errorMsg .= 'Please upload an image.<br/>';
		}
		
		$insertArr = array();
		$insertArr[ 'artist_name' ] = $this -> data[ 'Artist' ][ 'artist_name' ];
		$insertArr[ 'artist_image' ] = 'img/featuredimg/' . $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
		$insertObj = new Featuredartist();
		
		if( empty( $errorMsg ) )
		{
			
			if( $insertObj -> insert( $insertArr ) )
			{
				$this -> Session -> setFlash( 'Data has been saved successfully!', 'modal', array( 'class' => 'modal success' ) );
				$this -> redirect( 'managefeaturedartist' );
			}
		}
		else
		{
			$this -> Session -> setFlash( $errorMsg, 'modal', array( 'class' => 'modal problem' ) );
			$this -> redirect( 'artistform' );
		}
	}/*
    Function Name : updatefeaturedartist
    Desc : Updates a featured artist
   */
	public function admin_updatefeaturedartist()
	{
		$errorMsg = '';
		$this -> Featuredartist -> id = $this -> data[ 'Artist' ][ 'id' ];
		$newPath = '../webroot/img/featuredimg/';
		$fileName = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
		$newPath = $newPath . $fileName;
		move_uploaded_file( $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ], $newPath );
		$filePath = $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ];
		
		if( $this -> data[ 'Artist' ][ 'artist_name' ] == '' )
		{
			$errorMsg .= 'Please select an Artist.<br/>';
		}
		
		$updateArr = array();
		$updateArr[ 'id' ] = $this -> data[ 'Artist' ][ 'id' ];
		$updateArr[ 'artist_name' ] = $this -> data[ 'Artist' ][ 'artist_name' ];
		
		if( $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ] != '' )
		{
			$updateArr[ 'artist_image' ] = 'img/featuredimg/' . $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
		}
		
		$updateObj = new Featuredartist();
		
		if( empty( $errorMsg ) )
		{
			
			if( $updateObj -> insert( $updateArr ) )
			{
				$this -> Session -> setFlash( 'Data has been updated Sucessfully!', 'modal', array( 'class' => 'modal success' ) );
				$this -> redirect( 'managefeaturedartist' );
			}
		}
		else
		{
			$this -> Session -> setFlash( $errorMsg, 'modal', array( 'class' => 'modal problem' ) );
			$this -> redirect( 'managefeaturedartist' );
		}
	}/*
    Function Name : delete
    Desc : For deleting a featured artist
   */
	public function admin_delete()
	{
		$deleteArtistUserId = $this -> params[ 'named' ][ 'id' ];
		$deleteObj = new Featuredartist();
		
		if( $deleteObj -> del( $deleteArtistUserId ) )
		{
			$this -> Session -> setFlash( 'Data deleted Sucessfully!', 'modal', array( 'class' => 'modal success' ) );
			$this -> redirect( 'managefeaturedartist' );
		}
		else
		{
			$this -> Session -> setFlash( 'Error occured while deleteting the record', 'modal', array( 'class' => 'modal problem' ) );
			$this -> redirect( 'managefeaturedartist' );
		}
	}/*
    Function Name : createartist
    Desc : assigns artists with images
   */
	public function admin_createartist()
	{
		$errorMsg = '';
		
		if( !empty( $this -> params[ 'named' ][ 'id' ] ) )//gets the values from the url in form  of array
		{
			$artistId = $this -> params[ 'named' ][ 'id' ];
			
			if( trim( $artistId ) != '' && is_numeric( $artistId ) )
			{
				$this -> set( 'formAction', 'admin_createartist/id:' . $artistId );
				$this -> set( 'formHeader', 'Edit Artist' );
				$getArtistrDataObj = new Artist();
				$getData = $getArtistrDataObj -> getartistdata( $artistId );
				$this -> set( 'getData', $getData );
				$condition = 'edit';
				$artistName = $getData[ 'Artist' ][ 'artist_name' ];
				
				if( isset( $this -> data ) )
				{
					$updateObj = new Artist();
					$updateArr = array();
					
					if( $this -> data[ 'Artist' ][ 'artist_name' ] == '' )
					{
						$errorMsg .= 'Please select Artist Name';
					}
					
					$updateArr[ 'id' ] = $this -> data[ 'Artist' ][ 'id' ];
					$updateArr[ 'artist_name' ] = $this -> data[ 'Artist' ][ 'artist_name' ];
					
					if( $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ] != '' )
					{
						$newPath = '../webroot/img/artistimg/';
						$fileName = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
						$newPath = $newPath . $fileName;
						move_uploaded_file( $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ], $newPath );
						$updateArr[ 'artist_image' ] = 'img/artistimg/' . $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
					}
					
					
					if( empty( $errorMsg ) )
					{
						
						if( $updateObj -> insert( $updateArr ) )
						{
							$this -> Session -> setFlash( 'Data has been saved Sucessfully!', 'modal', array( 'class' => 'modal success' ) );
							$this -> redirect( 'manageartist' );
						}
					}
					else
					{
						$this -> Session -> setFlash( $errorMsg, 'modal', array( 'class' => 'modal problem' ) );
					}
				}
			}
		}
		else
		{
			$this -> set( 'formAction', 'admin_createartist' );
			$this -> set( 'formHeader', 'Add  Artist' );
			$condition = 'add';
			$artistName = '';
			
			if( isset( $this -> data ) )
			{
				
				if( $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ] == '' )
				{
					$errorMsg .= 'Please upload an image<br/>';
				}
				
				
				if( trim( $this -> data[ 'Artist' ][ 'artist_name' ] ) == '' )
				{
					$errorMsg .= 'Please select an artist name<br/>';
				}
				
				$newPath = '../webroot/img/artistimg/';
				$fileName = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
				$newPath = $newPath . $fileName;
				move_uploaded_file( $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ], $newPath );
				$filePath = $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ];
				$insertArr = array();
				$insertArr[ 'artist_name' ] = $this -> data[ 'Artist' ][ 'artist_name' ];
				$insertArr[ 'artist_image' ] = 'img/artistimg/' . $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
				$insertObj = new Artist();
				
				if( empty( $errorMsg ) )
				{
					
					if( $insertObj -> insert( $insertArr ) )
					{
						$this -> Session -> setFlash( 'Data has been saved Sucessfully!', 'modal', array( 'class' => 'modal success' ) );
						$this -> redirect( 'manageartist' );
					}
				}
				else
				{
					$this -> Session -> setFlash( $errorMsg, 'modal', array( 'class' => 'modal problem' ) );
				}
			}
		}
		
		$getArtistDataObj = new Physicalproduct();
		$getArtistData = $getArtistDataObj -> allartistname( $condition, $artistName );
		$this -> set( 'getArtistData', $getArtistData );
	}/*
    Function Name : managenewartist
    Desc : manages new artists with images
   */
	public function admin_manageartist()
	{
		$artistObj = new Artist();
		$artists = $artistObj -> getallartists();
		$this -> set( 'artists', $artists );
	}/*
    Function Name : deletenewartists
    Desc : For deleting a new artist
   */
	public function admin_deleteartists()
	{
		$deleteArtistUserId = $this -> params[ 'named' ][ 'id' ];
		$deleteObj = new Artist();
		
		if( $deleteObj -> del( $deleteArtistUserId ) )
		{
			$this -> Session -> setFlash( 'Data deleted Sucessfully!', 'modal', array( 'class' => 'modal success' ) );
			$this -> redirect( 'manageartist' );
		}
		else
		{
			$this -> Session -> setFlash( 'Error occured while deleteting the record', 'modal', array( 'class' => 'modal problem' ) );
			$this -> redirect( 'manageartist' );
		}
	}/*
    Function Name : createartist
    Desc : assigns artists with images
   */
	public function admin_addnewartist()
	{
		$errorMsg = '';
		
		if( !empty( $this -> params[ 'named' ][ 'id' ] ) )//gets the values from the url in form  of array
		{
			$artistId = $this -> params[ 'named' ][ 'id' ];
			
			if( trim( $artistId ) != '' && is_numeric( $artistId ) )
			{
				$this -> set( 'formAction', 'admin_addnewartist/id:' . $artistId );
				$this -> set( 'formHeader', 'Edit New Artsit' );
				$getArtistrDataObj = new Newartist();
				$getData = $getArtistrDataObj -> getartistdata( $artistId );
				$this -> set( 'getData', $getData );
				$condition = 'edit';
				$artistName = $getData[ 'Newartist' ][ 'artist_name' ];
				
				if( isset( $this -> data ) )
				{
					$updateObj = new Newartist();
					$updateArr = array();
					
					if( $this -> data[ 'Artist' ][ 'artist_name' ] == '' )
					{
						$errorMsg .= 'Please select Artist Name';
					}
					
					$updateArr[ 'id' ] = $this -> data[ 'Artist' ][ 'id' ];
					$updateArr[ 'artist_name' ] = $this -> data[ 'Artist' ][ 'artist_name' ];
					
					if( $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ] != '' )
					{
						$newPath = '../webroot/img/newartistimg/';
						$fileName = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
						$newPath = $newPath . $fileName;
						move_uploaded_file( $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ], $newPath );
						$updateArr[ 'artist_image' ] = 'img/newartistimg/' . $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
					}
					
					
					if( empty( $errorMsg ) )
					{
						
						if( $updateObj -> insert( $updateArr ) )
						{
							$this -> Session -> setFlash( 'Data has been saved Sucessfully!', 'modal', array( 'class' => 'modal success' ) );
							$this -> redirect( 'managenewartist' );
						}
					}
					else
					{
						$this -> Session -> setFlash( $errorMsg, 'modal', array( 'class' => 'modal problem' ) );
					}
				}
			}
		}
		else
		{
			$this -> set( 'formAction', 'admin_addnewartist' );
			$this -> set( 'formHeader', 'Add New Artist' );
			$condition = 'add';
			$artistName = '';
			
			if( isset( $this -> data ) )
			{
				
				if( $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ] == '' )
				{
					$errorMsg .= 'Please upload an image<br/>';
				}
				
				
				if( trim( $this -> data[ 'Artist' ][ 'artist_name' ] ) == '' )
				{
					$errorMsg .= 'Please select an artist name<br/>';
				}
				
				$newPath = '../webroot/img/newartistimg/';
				$fileName = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
				$newPath = $newPath . $fileName;
				move_uploaded_file( $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ], $newPath );
				$filePath = $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ];
				$insertArr = array();
				$insertArr[ 'artist_name' ] = $this -> data[ 'Artist' ][ 'artist_name' ];
				$insertArr[ 'artist_image' ] = 'img/newartistimg/' . $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
				$insertObj = new Newartist();
				
				if( empty( $errorMsg ) )
				{
					
					if( $insertObj -> insert( $insertArr ) )
					{
						$this -> Session -> setFlash( 'Data has been saved Sucessfully!', 'modal', array( 'class' => 'modal success' ) );
						$this -> redirect( 'managenewartist' );
					}
				}
				else
				{
					$this -> Session -> setFlash( $errorMsg, 'modal', array( 'class' => 'modal problem' ) );
				}
			}
		}
		
		$getArtistDataObj = new Physicalproduct();
		$getArtistData = $getArtistDataObj -> allartistname( $condition, $artistName );
		$this -> set( 'getArtistData', $getArtistData );
	}/*
    Function Name : managenewartist
    Desc : manages artists with images
   */
	public function admin_managenewartist()
	{
		$artistObj = new Newartist();
		$artists = $artistObj -> getallnewartists();
		$this -> set( 'artists', $artists );
	}/*
    Function Name : deletenewartists
    Desc : For deleting a featured artist
   */
	public function admin_deletenewartists()
	{
		$deleteArtistUserId = $this -> params[ 'named' ][ 'id' ];
		$deleteObj = new Newartist();
		
		if( $deleteObj -> del( $deleteArtistUserId ) )
		{
			$this -> Session -> setFlash( 'Data deleted Sucessfully!', 'modal', array( 'class' => 'modal success' ) );
			$this -> redirect( 'managenewartist' );
		}
		else
		{
			$this -> Session -> setFlash( 'Error occured while deleteting the record', 'modal', array( 'class' => 'modal problem' ) );
			$this -> redirect( 'managenewartist' );
		}
	}
	
	public function view($id = null) {
		$this->layout = 'home';
                $this->set('artistName',base64_decode($id));
                $this -> paginate =  array('conditions' =>
					  array('and' =>
						array(
							array( 'Physicalproduct.ArtistText' => base64_decode($id)),
                                                        array( "Physicalproduct.ProdID = Physicalproduct.ReferenceID")
						      )
						)/*,
                                                 'group' => 'Physicalproduct.ReferenceID'*/
					  );
                $this->Physicalproduct->recursive = 2;
                $albumData = $this->paginate('Physicalproduct'); //getting the Albums for the artist
                $albumSongs = array();
                foreach($albumData as $album)
                {
                    $albumSongs[$album['Physicalproduct']['ReferenceID']] =  $this->Physicalproduct->find('all',array(
                                                  'conditions' =>
                                                            array('and' =>
                                                                  array(
                                                                          array( 'Physicalproduct.ReferenceID' => $album['Physicalproduct']['ReferenceID']),
                                                                          array( "Physicalproduct.ProdID <> Physicalproduct.ReferenceID"),
                                                                          array('Availability.AvailabilityType' => "PERMANENT"),
                                                                          array('Availability.AvailabilityStatus' => "I"),
                                                                          array('ProductOffer.PRODUCT_OFFER_ID >' => 0),
                                                                          array('ProductOffer.PURCHASE' => 'T')
                                                                        )
                                                                  ),'order' => 'Physicalproduct.ReferenceID'
                                                            ));
                }
		
                $this->set('albumData', $albumData);		
               if($albumData[0]['Metadata']['ArtistURL'] != "" )
                {
                   $this->set('artistUrl',$albumData[0]['Metadata']['ArtistURL']);
                }else{
                   $this->set('artistUrl', "N/A");
                }		
                $this->set('albumSongs',$albumSongs);		
	}
	
	function download($id)
	{
	  ini_set('memory_limit', '1024M');
	  $fileID = $id;
	  $path = pathinfo(dirname(__FILE__));
 	
	  $fileResults = $this->Files->find('all',array('conditions' => array('FileID' => $fileID)));	  	  
	  $fileUrl = shell_exec('perl '.$path['dirname'].'/webroot/files/tokengen ' . $fileResults['0']['Files']['CdnPath']."/".$fileResults['0']['Files']['SaveAsName']);	  		
	  $fileUrl = $fileResults['0']['Files']['HostURL'].$fileUrl;  
	  if(function_exists("curl_init"))
	  {
	    $ch=curl_init($fileUrl);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $rsp=curl_exec($ch);
	    curl_close($ch);
	  } 
	  else
	  {
	    $rsp = file_get_contents($fileUrl);
	  }	
	  $path = pathinfo(dirname(__FILE__));	  
	  $fp = fopen($path['dirname'].'/webroot/tmp/'.$fileResults['0']['Files']['SaveAsName'], 'w');
	  fwrite($fp, $rsp);
	  fclose($fp); 
	  
	  // Allow direct file download (hotlinking)?
	  // Empty - allow hotlinking
	  // If set to nonempty value (Example: example.com) will only allow downloads when referrer contains this text
	  //define('ALLOWED_REFERRER', $config["baseurl"].'/videos.php');
	  
	  // Download folder, i.e. folder where you keep all files for download.
	  // MUST end with slash (i.e. "/" )
	  
	  define('BASE_DIR',"tmp/");
	  // log downloads?  true/false
	  define('LOG_DOWNLOADS',true);
	  // log file name
	  define('LOG_FILE','downloads.log');
	  
	  // Allowed extensions list in format 'extension' => 'mime type'
	  // If myme type is set to empty string then script will try to detect mime type 
	  // itself, which would only work if you have Mimetype or Fileinfo extensions
	  // installed on server.
	  $allowed_ext = array (
	  
	    // archives
	    //'zip' => 'application/zip',
	  
	    // documents
	    //'pdf' => 'application/pdf',
	    //'doc' => 'application/msword',
	    //'xls' => 'application/vnd.ms-excel',
	    //'ppt' => 'application/vnd.ms-powerpoint',
	    
	    // executables
	    //'exe' => 'application/octet-stream',
	  
	    // images
	    //'gif' => 'image/gif',
	    //'png' => 'image/png',
	    //'jpg' => 'image/jpeg',
	    //'jpeg' => 'image/jpeg',
	  
	    // audio
	    'mp3' => 'audio/mpeg',
	    //'wav' => 'audio/x-wav',
	  
	    // video
	    //'mpeg' => 'video/mpeg',
	    'mpg' => 'video/mpeg',
	    //'mpe' => 'video/mpeg',
	    //'mov' => 'video/quicktime',
	    'avi' => 'video/x-msvideo',
	    'doc' => '',
	    'flv' => '',
	    'mp4' => ''
	  );
	  
	  // Make sure program execution doesn't time out
	  // Set maximum script execution time in seconds (0 means no limit)
	  set_time_limit(0);
	  
	  // Get real file name.
	  // Remove any path info to avoid hacking by adding relative path, etc.
	  $fname = $fileResults['0']['Files']['SaveAsName'];
	  
	  // Check if the file exists
	  // Check in subfolders too
	  function find_file ($dirname, $fname, &$file_path) {
	  
	    $dir = opendir($dirname);
	  
	    while ($file = readdir($dir)) {
	      if (empty($file_path) && $file != '.' && $file != '..') {
		if (is_dir($dirname.'/'.$file)) {
		  find_file($dirname.'/'.$file, $fname, $file_path);
		}
		else {
		  if (file_exists($dirname.'/'.$fname)) {
		    $file_path = $dirname.'/'.$fname;
		    return;
		  }
		}
	      }
	    }
	  
	  } // find_file
	  
	  // get full file path (including subfolders)
	  $file_path = '';
	  find_file(BASE_DIR, $fname, $file_path);
	  
	  if (!is_file($file_path)) {
	    die("File does not exist. Make sure you specified correct file name."); 
	  }
	  
	  // file size in bytes
	  $fsize = filesize($file_path); 
	  
	  // file extension
	  $fext = strtolower(substr(strrchr($fname,"."),1));
	  
	  // check if allowed extension
	  if (!array_key_exists($fext, $allowed_ext)) {
	    die("Not allowed file type."); 
	  }
	  
	  // get mime type
	  if ($allowed_ext[$fext] == '') {
	    $mtype = '';
	    // mime type is not set, get from server settings
	    if (function_exists('mime_content_type')) {
	      $mtype = mime_content_type($file_path);
	    }
	    else if (function_exists('finfo_file')) {
	      $finfo = finfo_open(FILEINFO_MIME); // return mime type
	      $mtype = finfo_file($finfo, $file_path);
	      finfo_close($finfo);  
	    }
	    if ($mtype == '') {
	      $mtype = "application/force-download";
	    }
	  }
	  else {
	    // get mime type defined by admin
	    $mtype = $allowed_ext[$fext];
	  }
	  
	  // Browser will try to save file with this filename, regardless original filename.
	  // You can override it if needed.
	  
	  
	  $asfname = $fname;
	  	  
	  // set headers
	  header("Pragma: public");
	  header("Expires: 0");
	  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	  header("Cache-Control: public");
	  header("Content-Description: File Transfer");
	  header("Content-Type: $mtype");
	  header("Content-Disposition: attachment; filename=\"$asfname\"");
	  header("Content-Transfer-Encoding: binary");
	  header("Content-Length: " . $fsize);
	  
	  // download
	  // @readfile($file_path);
	  $file = @fopen($file_path,"rb");
	  if ($file) {
	    while(!feof($file)) {
	      print(fread($file, 1024*8));
	      flush();
	      if (connection_status()!=0) {
		@fclose($file);
		die();
	      }
	    }
	    @fclose($file);
	  }
	
	}	
	
  }	
?>
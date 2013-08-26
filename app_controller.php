<?php
class AppController extends Controller
{
	var $components = array( 'Session', 'RequestHandler','Cookie');
	var $helpers = array( 'Session', 'Html', 'Ajax', 'Javascript', 'Form', 'Library', 'Download','Queue' );
	var $uses = array('Genre','Featuredartist','Newartist','Category','Album','Country');
        var $view = 'Dataencode';
	
	function beforeFilter()
	{
                ini_set('session.cookie_domain', env('HTTP_BASE')); 
                Configure::write('Session.checkAgent', false);
                Configure::write('Session.ini',array('session.cookie_secure' => false, 'session.referer_check' => false));            
		$first_param = explode('/', $_SERVER['REQUEST_URI']);
                if($first_param[1] != 'admin' &&  $first_param[1] != 'users' && !$this->RequestHandler->isAjax()){
                   if($_SERVER['REQUEST_URI'] != '/homes/chooser' && $_SERVER['REQUEST_URI'] != '/' && $_SERVER['REQUEST_URI']!= '/homes/forgot_password' && $_SERVER['REQUEST_URI']!= '/admin/*'){
                       $this->Session->write("UrlReferer",$_SERVER['REQUEST_URI']);
                      // $this->Cookie->write('UrlReferer',$_SERVER['REQUEST_URI']);	
                   }
                }
                $this->switchCpuntriesTable();
		if (Configure::read('SiteSettings.site_status') == 'Offline' && $this->here != Configure::read('SiteSettings.site_offline_url')) {
			$this->redirect(Configure::read('SiteSettings.site_offline_url'));
		}             
                
                //changed the code to display all innner page without login
                $libraryInstance = ClassRegistry::init('Library');
		$url = $_SERVER['SERVER_NAME'];
		$host = explode('.', $url);
		$subdomains = array_slice($host, 0, count($host) - 2 );									
		$subdomains = $subdomains[0] ;               
                
		if($subdomains !== '' && $subdomains != 'www' && $subdomains != 'freegalmusic'){	
                    
                    $libraryIDArray = $libraryInstance->find("first", array("conditions" => array('library_subdomain' => $subdomains), 'fields' => array('id', 'library_name', 'library_home_url','library_image_name', 'library_country', 'library_territory','library_authentication_method','library_type','library_block_explicit_content'), 'recursive' => -1));

                    $this->Session->write("subdomain",$subdomains);
                    $this->Session->write("lId",$libraryIDArray['Library']['id']);                    
                    $this->Session->write("territory", $libraryIDArray['Library']['library_territory']);  
                    $this->Session->write("library_auth_method_name",$libraryIDArray['Library']['library_authentication_method']);
                    $this->Session->write("library", $libraryIDArray['Library']['id']);
                    $this->Session->write("library", $libraryIDArray['Library']['id']);
                    $this->Session->write("library_type", $libraryIDArray['Library']['library_type']);
                    $this->Session->write("block", (($libraryIDArray['Library']['library_block_explicit_content'] == '1')?'yes':'no'));
                                        
		}else{
                    $patronid = $this->Session->read("patron");
                    if(empty($patronid)){
                        $libraryData = $libraryInstance->find("first", array("conditions" => array('id' => 1), 'fields' => array('library_territory','library_type','library_block_explicit_content'), 'recursive' => -1));            
                        $country = $libraryData['Library']['library_territory'];
                        $this->Session->write("libCountry",$country);
                        $this->Session->write("territory",$country);
                        $this->Session->write("lId",1);  
                        $this->Session->write("library", 1);
                        $this->Session->write("library_type", $libraryData['Library']['library_type']);
                        $this->Session->write("block", (($libraryIDArray['Library']['library_block_explicit_content'] == '1')?'yes':'no'));
                    }
                }               
		$this->Auth->authorize = 'actions';
		$this->Auth->fields = array(  'username' => 'email',  'password' => 'password' );
		$this->Auth->loginRedirect = array( 'controller' => 'users', 'action' => 'index' );
		$this -> set( 'username', $this -> Session -> read( 'Auth.User.username' ) );
		$this->set ( 'genresMenu' ,  $this->Category->find('all',array('cache' => 'no')));	
		$this -> set ( 'featuredArtistMenu' ,  $this->Featuredartist->getallartists());
		$this -> set ( 'newArtistMenu' ,  $this->Newartist->getallnewartists());
		$this->set('cdnPath', Configure::read('App.CDN'));
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:S') . ' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
                //$this->checkOnlinePatron();
                
                
                $announcment_query = "SELECT * from pages WHERE announcement = '1' and language='en' ORDER BY modified DESC LIMIT 1";
                $announcment_rs = $this->Album->query($announcment_query);
                //echo "<pre>";
                //print_r($announcment_rs);
                $this -> set ( 'announcment_value' ,  $announcment_rs[0]['pages']['page_content']);
                //$announcment_rs[0]['pages']['page_content'];
                
	}
	
	function checkOnlinePatron()
	{
	 $libraryId = '';
	 $patronId = '';
         $libraryId = $this->Session->read('library');
         $patronId = $this->Session->read('patron');	
		$userCache = Cache::read("login_".$libraryId.$patronId);
		$date = time();
		$modifiedTime = $userCache[0];
		if(!($this->Session->read('patron'))){
			if((($date-$modifiedTime) > 60) && $modifiedTime){
				$this->Session->destroy();
				Cache::delete("login_".$libraryId.$patronId);
			}
		} else {
			if((($date-$modifiedTime) > 60) && $modifiedTime){
				$this->Session->destroy();
				Cache::delete("login_".$libraryId.$patronId);
			}		
		}
	}

	function _setLanguage() {
	    if ($this->Cookie->read('lang') && !$this->Session->check('Config.language')) {
	        $this->Session->write('Config.language', $this->Cookie->read('lang'));
	    }
	    else if (isset($this->params['language']) && ($this->params['language']
	             !=  $this->Session->read('Config.language'))) {
	        $this->Session->write('Config.language', $this->params['language']);
	        $this->Cookie->write('lang', $this->params['language'], false, '20 days');
	    }
	}
	
	function isAuthorized(){
		return true;
	}	
	
	function build_acl(){
		
		if( !Configure :: read( 'debug' ) )
		{
			return $this -> _stop();
		}
		
		$log = array();
		$aco = & $this -> Acl -> Aco;
		$root = $aco -> node( 'controllers' );
		
		if( !$root )
		{
			$aco -> create( array( 'parent_id' => null, 'model' => null, 'alias' => 'controllers' ) );
			$root = $aco -> save();
			$root[ 'Aco' ][ 'id' ] = $aco -> id;
			$log[ '' ] = 'Created Aco node for controllers';
		}
		else
		{
			$root = $root[ 0 ];
		}
		
		App :: import( 'Core', 'File' );
		$Controllers = Configure :: listObjects( 'controller' );
		$appIndex = array_search( 'App', $Controllers );
		
		if( $appIndex !== false )
		{
			unset( $Controllers[ $appIndex ] );
		}
		
		$baseMethods = get_class_methods( 'Controller' );
		$baseMethods[ '' ] = 'buildAcl';
		$Plugins = $this -> _getPluginControllerNames();
		$Controllers = array_merge( $Controllers, $Plugins );
		// look at each controller in app/controllers
		
		foreach( $Controllers as $ctrlName )
		{
			$methods = $this -> _getClassMethods( $this -> _getPluginControllerPath( $ctrlName ) );
			// Do all Plugins First
			
			if( $this -> _isPlugin( $ctrlName ) )
			{
				$pluginNode = $aco -> node( 'controllers/' . $this -> _getPluginName( $ctrlName ) );
				
				if( !$pluginNode )
				{
					$aco -> create( array( 'parent_id' => $root[ 'Aco' ][ 'id' ], 'model' => null, 'alias' => $this -> _getPluginName( $ctrlName ) ) );
					$pluginNode = $aco -> save();
					$pluginNode[ 'Aco' ][ 'id' ] = $aco -> id;
					$log[ '' ] = 'Created Aco node for ' . $this -> _getPluginName( $ctrlName ) . ' Plugin';
				}
				
			}
			
			// find / make controller node
			$controllerNode = $aco -> node( 'controllers/' . $ctrlName );
			
			if( !$controllerNode )
			{
				
				if( $this -> _isPlugin( $ctrlName ) )
				{
					$pluginNode = $aco -> node( 'controllers/' . $this -> _getPluginName( $ctrlName ) );
					$aco -> create( array( 'parent_id' => $pluginNode[ '0' ][ 'Aco' ][ 'id' ], 'model' => null, 'alias' => $this -> _getPluginControllerName( $ctrlName ) ) );
					$controllerNode = $aco -> save();
					$controllerNode[ 'Aco' ][ 'id' ] = $aco -> id;
					$log[ '' ] = 'Created Aco node for ' . $this -> _getPluginControllerName( $ctrlName ) . ' ' . $this -> _getPluginName( $ctrlName ) . ' Plugin Controller';
				}
				else
				{
					$aco -> create( array( 'parent_id' => $root[ 'Aco' ][ 'id' ], 'model' => null, 'alias' => $ctrlName ) );
					$controllerNode = $aco -> save();
					$controllerNode[ 'Aco' ][ 'id' ] = $aco -> id;
					$log[ '' ] = 'Created Aco node for ' . $ctrlName;
				}
				
			}
			else
			{
				$controllerNode = $controllerNode[ 0 ];
			}
			
			//clean the methods. to remove those in Controller and private actions.
			
			foreach( $methods as $k => $method )
			{
				
				if( strpos( $method, '_', 0 ) === 0 )
				{
					unset( $methods[ $k ] );
					continue;
				}
				
				
				if( in_array( $method, $baseMethods ) )
				{
					unset( $methods[ $k ] );
					continue;
				}
				
				$methodNode = $aco -> node( 'controllers/' . $ctrlName . '/' . $method );
				
				if( !$methodNode )
				{
					$aco -> create( array( 'parent_id' => $controllerNode[ 'Aco' ][ 'id' ], 'model' => null, 'alias' => $method ) );
					$methodNode = $aco -> save();
					$log[ '' ] = 'Created Aco node for ' . $method;
				}
				
			}
			
		}
		
		
		if( count( $log )>0 )
		{
			debug( $log );
		}		
	}
	
	
	
	function _getClassMethods( $ctrlName = null )
	{
		App :: import( 'Controller', $ctrlName );
		
		if( strlen( strstr( $ctrlName, '.' ) ) > 0 )
		{
			// plugin's controller
			$num = strpos( $ctrlName, '.' );
			$ctrlName = substr( $ctrlName, $num+1 );
		}
		
		$ctrlclass = $ctrlName . 'Controller';
		$methods = get_class_methods( $ctrlclass );
		// Add scaffold defaults if scaffolds are being used
		$properties = get_class_vars( $ctrlclass );
		
		if( array_key_exists( 'scaffold', $properties ) )
		{
			
			if( $properties[ 'scaffold' ] == 'admin' )
			{
				$methods = array_merge( $methods, array( 'admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete' ) );
			}
			else
			{
				$methods = array_merge( $methods, array( 'add', 'edit', 'index', 'view', 'delete' ) );
			}
			
		}
		
		return $methods;
	}
	
	
	
	function _isPlugin( $ctrlName = null )
	{
		$arr = String :: tokenize( $ctrlName, '/' );
		
		if( count( $arr ) > 1 )
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	
	
	
	function _getPluginControllerPath( $ctrlName = null )
	{
		$arr = String :: tokenize( $ctrlName, '/' );
		
		if( count( $arr ) == 2 )
		{
			return $arr[ 0 ] . '.' . $arr[ 1 ];
		}
		else
		{
			return $arr[ 0 ];
		}
		
	}
	
	
	
	function _getPluginName( $ctrlName = null )
	{
		$arr = String :: tokenize( $ctrlName, '/' );
		
		if( count( $arr ) == 2 )
		{
			return $arr[ 0 ];
		}
		else
		{
			return false;
		}
		
	}
	
	
	
	function _getPluginControllerName( $ctrlName = null )
	{
		$arr = String :: tokenize( $ctrlName, '/' );
		
		if( count( $arr ) == 2 )
		{
			return $arr[ 1 ];
		}
		else
		{
			return false;
		}
		
	}
	
	/** * Get the names of the plugin controllers . . .  *  * This
	
	function will get an array of the plugin controller names, and * also makes sure the controllers are available for us to get the  * method names by doing an App :: import for each plugin controller .  * * @return array of plugin names .  * */
	
	function _getPluginControllerNames()
	{
		App :: import( 'Core', 'File', 'Folder' );
		$paths = Configure :: getInstance();
		$folder = & new Folder();
		$folder -> cd( APP . 'plugins' );
		// Get the list of plugins
		$Plugins = $folder -> read();
		$Plugins = $Plugins[ 0 ];
		$arr = array();
		// Loop through the plugins
		
		foreach( $Plugins as $pluginName )
		{
			// Change directory to the plugin
			$didCD = $folder -> cd( APP . 'plugins' . DS . $pluginName . DS . 'controllers' );
			// Get a list of the files that have a file name that ends
			// with controller.php
			$files = $folder -> findRecursive( '.*_controller\.php' );
			// Loop through the controllers we found in the plugins directory
			
			foreach( $files as $fileName )
			{
				// Get the base file name
				$file = basename( $fileName );
				// Get the controller name
				$file = Inflector :: camelize( substr( $file, 0, strlen( $file )-strlen( '_controller.php' ) ) );
				
				if( !preg_match( '/^' . Inflector :: humanize( $pluginName ) . 'App/', $file ) )
				{
					
					if( !App :: import( 'Controller', $pluginName . '.' . $file ) )
					{
						debug( 'Error importing ' . $file . ' for plugin ' . $pluginName );
					}
					else
					{
						/// Now prepend the Plugin name ...
						// This is required to allow us to fetch the method names.
						$arr[ '' ] = Inflector :: humanize( $pluginName ) . '/' . $file;
					}
					
				}
				
			}
			
		}
		
		return $arr;
	}
	
	function initDB()
	{
		$adminType = & $this -> User -> Group;
		//Allow superadmins to everything
		$adminType -> id = 1;
		$this -> Acl -> allow( $adminType, 'controllers' );
		//allow finance admin nothing for now
		$adminType -> id = 2;
		$this -> Acl -> deny( $adminType, 'controllers' );
		$this -> Acl -> allow( $adminType, 'controllers/users/admin_index' );
		$this -> Acl -> allow( $adminType, 'controllers/users/admin_logout' );
		//$this->Acl->deny($adminType, 'controllers/Artists');
		//allow content editor nothing for now
		$adminType -> id = 3;
		$this -> Acl -> deny( $adminType, 'controllers' );
		$this -> Acl -> allow( $adminType, 'controllers/users/admin_index' );
		$this -> Acl -> allow( $adminType, 'controllers/users/admin_logout' );
		$this -> Acl -> allow( $adminType, 'controllers/Artists' );
		$adminType -> id = 4;
		$this -> Acl -> allow( $adminType, 'controllers' );
		$this -> Acl -> allow( $adminType, 'controllers/users/admin_index' );
		$this -> Acl -> allow( $adminType, 'controllers/users/admin_logout' );
		$this -> Acl -> allow( $adminType, 'controllers/users/admin_patronform' );
		$this -> Acl -> allow( $adminType, 'controllers/users/admin_managepatron' );
		$this -> Acl -> allow( $adminType, 'controllers/reports/admin_index' );
		$this -> Acl -> allow( $adminType, 'controllers/reports/admin_getLibraryIds' );		
		$this -> Acl -> allow( $adminType, 'controllers/reports/admin_downloadAsCsv' );
		$this -> Acl -> allow( $adminType, 'controllers/reports/admin_downloadAsPdf' );
		$this -> Acl -> allow( $adminType, 'controllers/reports/admin_librarywishlistreport' );
		$this -> Acl -> allow( $adminType, 'controllers/reports/admin_unlimited');
		$this -> Acl -> allow( $adminType, 'controllers/reports/admin_consortium');
		$adminType -> id = 5;
		$this -> Acl -> allow( $adminType, 'controllers' );
		$this -> Acl -> allow( $adminType, 'controllers/users/admin_index' );
		$this -> Acl -> allow( $adminType, 'controllers/reports/admin_index' );
		$this -> Acl -> allow( $adminType, 'controllers/reports/admin_getLibraryIds' );		
		$this -> Acl -> allow( $adminType, 'controllers/reports/admin_downloadAsCsv' );
		$this -> Acl -> allow( $adminType, 'controllers/reports/admin_downloadAsPdf' );
		$this -> Acl -> allow( $adminType, 'controllers/reports/admin_librarywishlistreport' );
		$this -> Acl -> allow( $adminType, 'controllers/reports/admin_unlimited');
		$this->Acl->deny( $adminType, 'controllers/libraries/admin_managelibrary');
		$this->Acl->deny( $adminType, 'controllers/users/admin_manageuser');
		$this->Acl->deny( $adminType, 'controllers/users/admin_managepatron');
		$this->Acl->deny( $adminType, 'controllers/users/admin_userform');
		$this->Acl->deny( $adminType, 'controllers/users/admin_patronform');
		$this->Acl->deny( $adminType, 'controllers/libraries/libraryform');
	}
        
      /**
        @ getCurrentCountryTable
        set tablePrefix attribute in countries model  
      */
      function getCurrentCountryTable(){

        $siteConfigSQL = "SELECT * from siteconfigs WHERE soption = 'multiple_countries'";
        $siteConfigData = $this->Album->query($siteConfigSQL);

        $multiple_countries = (($siteConfigData[0]['siteconfigs']['svalue']==1)?true:false);
        if(0 == $multiple_countries) {
            $this->Session->write('multiple_countries', '');
        }  
        
        return $multiple_countries;

      }

      /**
        @ switchCpuntriesTable
        set session raviable for table switching  
      */
      function switchCpuntriesTable() {

        // set session variable multiple_countries
        $tmp_territory = $this->Session->read('territory');    
        if ( !(empty($tmp_territory) )) {

          $this->Session->write('multiple_countries', strtolower($this->Session->read('territory')).'_' );
        } else {
          $this->Session->write('multiple_countries', '');
        }
        //$this->Session->write('multiple_countries','nz_' );
        //var_dump( $this->Session->read('multiple_countries') );

        // call function getCurrentCountrytable from app_controller 
        $multiple_countries = $this->getCurrentCountryTable();
        //var_dump( $this->Session->read('multiple_countries') );

        // switch to table  
        $this->Country->setTablePrefix($this->Session->read('multiple_countries'));

      }
}
?>

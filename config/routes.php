<?php
/**
 * Short description for file.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */
 
        $library = substr($_SERVER['HTTP_HOST'],0,strpos($_SERVER['HTTP_HOST'],'.'));
        
	if($library != 'www' && $library != 'freegalmusic' && $library != '50')
	{
		
                Router::connect('/', array('controller' => 'users', 'action' => 'redirection_manager', $library));
		Router::connect('/users/redirection_manager', array('controller' => 'users', 'action' => 'redirection_manager', $library));
                Router::connect('/users/login', array('controller' => 'users', 'action' => 'login', $library));
		Router::connect('/users/plogin', array('controller' => 'users', 'action' => 'plogin', $library));
		Router::connect('/users/clogin', array('controller' => 'users', 'action' => 'clogin', $library));
		Router::connect('/users/inhdlogin', array('controller' => 'users', 'action' => 'inhdlogin', $library));
		Router::connect('/users/ilogin', array('controller' => 'users', 'action' => 'ilogin', $library));
		Router::connect('/users/inlogin', array('controller' => 'users', 'action' => 'inlogin', $library));
		Router::connect('/users/idlogin', array('controller' => 'users', 'action' => 'idlogin', $library));
		Router::connect('/users/indlogin', array('controller' => 'users', 'action' => 'indlogin', $library));
		Router::connect('/users/sdlogin', array('controller' => 'users', 'action' => 'sdlogin', $library));
		Router::connect('/users/slogin', array('controller' => 'users', 'action' => 'slogin', $library));
		Router::connect('/users/snlogin', array('controller' => 'users', 'action' => 'snlogin', $library));
		Router::connect('/users/sndlogin', array('controller' => 'users', 'action' => 'sndlogin', $library));
		Router::connect('/users/inhlogin', array('controller' => 'users', 'action' => 'inhlogin', $library));
		Router::connect('/users/ihdlogin', array('controller' => 'users', 'action' => 'ihdlogin', $library));
		Router::connect('/users/ildlogin', array('controller' => 'users', 'action' => 'ildlogin', $library));
                Router::connect('/users/ilhdlogin', array('controller' => 'users', 'action' => 'ilhdlogin', $library));
                Router::connect('/users/mdlogin', array('controller' => 'users', 'action' => 'mdlogin', $library));
		Router::connect('/users/mndlogin', array('controller' => 'users', 'action' => 'mndlogin', $library));
		Router::connect('/libraries/patron/:id',array('controller' => 'libraries', 'action' => 'patron',$library),array('id' => '[0-9]+'));
    

	}
	else
	{
		
            Router::connect('/', array('controller' => 'homes', 'action' => 'index'));
	}
	Router::connect('/index', array('controller' => 'homes', 'action' => 'index')); 
        /**	
        * ...and connect the rest of 'Pages' controller's urls.
        */
	
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
        
        Router::connect('/genres/index', array('controller' => 'genres', 'action' => 'view'));
        Router::connect('/genres', array('controller' => 'genres', 'action' => 'view'));
  
        Router::connect('/wsdl', array('controller' => 'soaps', 'action' => 'wsdl'));
        //Router::connect('/soaps/*', array('controller' => 'soaps', 'action' => 'index'));
	
	Router::connect('/admin', array('controller' => 'users', 'action' => 'index', 'admin' => true));
	
	Router::connect('/:language/:controller/:action/*', array(), array('language' => '[a-z]{2}'));
	Router::connect('/homes/chooser', array('controller' => 'homes', 'action' => 'chooser'));
        
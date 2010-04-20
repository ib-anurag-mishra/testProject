<?php
/* File Name: homes_controller.php
   File Description: Displays the home page for each patron 
   Author: Maycreate
*/   

class HomesController extends AppController
{
    var $name = 'Homes';
    var $helpers = array( 'Html','Ajax','Javascript','Form', 'Library', 'Page', 'Physicalproduct', 'Wishlist' );
    var $components = array('RequestHandler','ValidatePatron','Downloads','PasswordHelper','Email');
    var $uses = array('Home','User','Physicalproduct','Featuredartist','Artist','Library','Metadata','Download','Genre','Currentpatron','Page','Wishlist');
 
   function beforeFilter()
   {
	parent::beforeFilter();
        if(($this->action != 'aboutus') && ($this->action != 'admin_aboutusform') && ($this->action != 'admin_termsform') && ($this->action != 'admin_limitsform') && ($this->action != 'forgot_password'))
        {
            $validPatron = $this->ValidatePatron->validatepatron();
            if(!$validPatron)
            {
                $this -> Session -> setFlash("Please follow proper guidelines before accessing our site.");
                $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
            }
        }
    }
    
    function index()
    {
        $this->Physicalproduct->Behaviors->attach('Containable');	
		$songDetails = $this->Physicalproduct->find('all', array('conditions' => 
                                array('Physicalproduct.ReferenceID <> Physicalproduct.ProdID','Physicalproduct.DownloadStatus' => 1,'Physicalproduct.TrackBundleCount' => 0, 'Metadata.Advisory' => 'F'),
                                'fields' => array(
                                                    'Physicalproduct.ProdID',
                                                    'Physicalproduct.Title',
                                                    'Physicalproduct.ArtistText',
                                                    'Physicalproduct.DownloadStatus',
                                                    'Physicalproduct.SalesDate'
                                                    ),
                                'contain' => 
                                array('Audio' => array('fields' => 
                                                                        array('Audio.FileID'),
                                                                        'Files' => array('fields' => array('Files.CdnPath', 'Files.SaveAsName'))
                                                                ),
                                        'Metadata' => array('fields' => array('Metadata.Title', 'Metadata.Artist','Metadata.Advisory'))
                                ),'order'=> 'rand()','limit' => '8'
                )
        );
        $this->set('songs',$songDetails);
		$this->Physicalproduct->recursive = -1;
		$upcoming = $this->Physicalproduct->find('all', array(
			'conditions' => array(
				'Physicalproduct.ReferenceID = Physicalproduct.ProdID', 
				'SalesDate >' => date('Y-m-d')
				),
				'fields' => array(
					'Physicalproduct.Title',
                    'Physicalproduct.ArtistText',
					'Physicalproduct.SalesDate'
				)
			)
		);
		$this->set('upcoming', $upcoming);
        //$this->set('songs',$this->Home->getSongs());
        $this->set('distinctArtists', $this->Physicalproduct->selectArtist());
        $this->set('featuredArtists', $this->Featuredartist->getallartists());
        $this->set('newArtists', $this->Newartist->getallnewartists());
        $this->set('artists', $this->Artist->getallartists());
        $this->layout = 'home';
    }
    
    function autoComplete()
    {
	Configure::write('debug', 0);
        $this->Physicalproduct->recursive = -1;
        $albumResults = $this->Physicalproduct->find('all', array(
	   'conditions'=>array('Physicalproduct.Title LIKE'=>$_GET['q'].'%','Physicalproduct.DownloadStatus' => 1						
	   ),
           'fields' => array(
			  'Title'
		  ), 
		  'group' => array(
			  'Title',
		  ),
                  'limit' => '6'));            
	$this->set('albumResults', $albumResults);        
        $artistResults = $this->Physicalproduct->find('all', array(
	   'conditions'=>array('Physicalproduct.ArtistText LIKE'=>$_GET['q'].'%','Physicalproduct.DownloadStatus' => 1
	   ),
           'fields' => array(
			  'ArtistText'
		  ), 
		  'group' => array(
			  'ArtistText',
		  ),
                  'limit' => '6'));       
	$this->set('artistResults', $artistResults);
        $this->Metadata->recursive=2;
        $songResults = $this->Metadata->find('all', array(
	   'conditions'=>array('Metadata.Title LIKE'=>$_GET['q'].'%','Physicalproduct.DownloadStatus' => 1
	   ),
           'fields' => array(
			  'Title'
		  ), 
		  'group' => array(
			  'Title',
		  ),
                  'limit' => '6'));        
	$this->set('songResults', $songResults);        
        $this->layout = 'ajax';
    }
    
    function artistSearch()
    {
	$search = $_POST['search'];
	$this->Physicalproduct->recursive = -1;
	$this->set('distinctArtists', $this->Physicalproduct->searchArtist($search));  	
    }
    
    function search()
    {
        $patId = $_SESSION['patron'];
        $libId = $_SESSION['library'];        
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);		
        $patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
        $this->set('libraryDownload',$libraryDownload);
        $this->set('patronDownload',$patronDownload);
        if($_SESSION['block'] == 'yes')
        {
            $cond = array('Metadata.Advisory' => 'F');
        }
        else
        {
            $cond = "";
        }
        if((isset($_REQUEST['match']) && $_REQUEST['match'] != '') || (isset($this->data['Home']['Match']) && $this->data['Home']['Match'] != ''))
        {
            if(isset($_REQUEST['match']) && $_REQUEST['match'] != '')
            {
                if($_REQUEST['match'] == 'All')
                {
                    $condition = "and";
                    $preCondition1 =  array('Physicalproduct.ProdID <> Physicalproduct.ReferenceID');
                    $preCondition2 = array('Physicalproduct.DownloadStatus' => 1);
                }
                else
                {
                    $condition = "or";
                    $preCondition1 =  "";
                    $preCondition2 = "";
                }
                $artist =  $_REQUEST['artist'];
                $song =  $_REQUEST['song'];
                $album =  $_REQUEST['album'];
                $genre =  $_REQUEST['genre'];
            }            
            if(isset($this->data['Home']['Match']) && $this->data['Home']['Match'] != '')
            {
                if($this->data['Home']['Match'] == 'All')
                {
                    $condition = "and";
                    $preCondition1 =  array('Physicalproduct.ProdID <> Physicalproduct.ReferenceID');
                    $preCondition2 = array('Physicalproduct.DownloadStatus' => 1);
                }
                else
                {
                    $condition = "or";
                    $preCondition1 =  "";
                    $preCondition2 = "";
                }
                $artist =  $this->data['Home']['artist'];
                $song =  $this->data['Home']['song'];
                $album =  $this->data['Home']['album'];
                $genre =  $this->data['Home']['genre'];
            }            
            if($artist != '')
            {
                $artistSearch = array('Physicalproduct.ArtistText LIKE' => '%'.$artist.'%');    
            }
            else
            {
                $artistSearch = '';
            }
            if($song != '')
            {
                $songSearch = array('Metadata.Title LIKE' => '%'.$song.'%');    
            }
            else
            {
                $songSearch = '';
            }
            if($album != '')
            {
                $albumSearch = array('Physicalproduct.Title LIKE' => '%'.$album.'%');    
            }
            else
            {
                $albumSearch = '';
            }
            if($genre != '')
            {
                $genreSearch = array('Genre.Genre LIKE' => '%'.$genre.'%');    
            }
            else
            {
                $genreSearch = '';
            }
            $this->set('searchKey','match=all&artist='.urlencode($artist).'&song='.urlencode($song).'&album='.$album.'&genre='.$genre);
            $this->Physicalproduct->Behaviors->attach('Containable');
            $this -> paginate = array('conditions' =>
                                array('and' =>
                                        array(                                                      
                                                array('Physicalproduct.ProdID <> Physicalproduct.ReferenceID'),                                                                                                
                                                array('Physicalproduct.DownloadStatus' => 1),
                                                array('Physicalproduct.TrackBundleCount' => 0),$cond
                                            )
                                        ,
                                    $condition =>
                                            array(
                                                    $artistSearch,$songSearch,$albumSearch,$genreSearch,$preCondition1,$preCondition2,$cond
                                                )
                                    ),
                                    'fields' => array(
                                                    'Physicalproduct.ProdID',
                                                    'Physicalproduct.Title',
                                                    'Physicalproduct.ArtistText',
                                                    'Physicalproduct.DownloadStatus',
                                                    'Physicalproduct.SalesDate'
                                                    ),
                                    'contain' => array(                                                                       
                                    'Metadata' => array(
                                            'fields' => array(
                                                    'Metadata.Title',
                                                    'Metadata.Artist',
						    'Metadata.Advisory'
                                                    )
                                            ),
                                    'Genre' => array(
                                            'fields' => array(
                                                    'Genre.Genre'                                                   
                                                    )
                                            ),
                                    'Audio' => array(
                                            'fields' => array(
                                                    'Audio.FileID',                                                    
                                                    ),
                                            'Files' => array(
                                            'fields' => array(
                                                    'Files.CdnPath' ,
                                                    'Files.SaveAsName'
                                                    )
                                            )
                                        )                                    
                                    ), 'cache' => 'yes'
                                );
            $this->Physicalproduct->recursive = 2;
            $searchResults = $this->paginate('Physicalproduct');
            $this->set('searchResults', $searchResults);
        }
        else
        {   
            $searchKey = '';      
            if(isset($_REQUEST['search']) && $_REQUEST['search'] != '')
            {
                $searchKey = $_REQUEST['search'];
            }        
            if($searchKey == '')
            {
                $searchKey = $this->data['Home']['search'];    
            }     
            $this->set('searchKey','search='.urlencode($searchKey));            
            $this->Physicalproduct->Behaviors->attach('Containable');
            $this -> paginate = array('conditions' =>
                                array('and' =>
                                        array(                                                      
                                                array('Physicalproduct.ProdID <> Physicalproduct.ReferenceID'),                                                                                                
                                                array('Physicalproduct.DownloadStatus' => 1),
                                                array('Physicalproduct.TrackBundleCount' => 0),$cond
                                            )
                                        ,
                                    'or' =>
                                                array(
                                                        array('Physicalproduct.ArtistText LIKE' => $searchKey.'%'),
                                                        array('Physicalproduct.Title LIKE' => $searchKey.'%'),
                                                        array('Metadata.Title LIKE' => $searchKey.'%')
                                                    )
                                        ),
                                    'fields' => array(
                                                    'Physicalproduct.ProdID',
                                                    'Physicalproduct.Title',
                                                    'Physicalproduct.ArtistText',
                                                    'Physicalproduct.DownloadStatus',
                                                    'Physicalproduct.SalesDate'
                                                    ),
                                    'contain' => array(                                                                       
                                    'Metadata' => array(
                                            'fields' => array(
                                                    'Metadata.Title',
                                                    'Metadata.Artist',
						    'Metadata.Advisory'
                                                    )
                                            ),
                                    'Genre' => array(
                                            'fields' => array(
                                                    'Genre.Genre'                                                   
                                                    )
                                            ),
                                    'Audio' => array(
                                            'fields' => array(
                                                    'Audio.FileID',                                                    
                                                    ),
                                            'Files' => array(
                                            'fields' => array(
                                                    'Files.CdnPath' ,
                                                    'Files.SaveAsName'
                                                    )
                                            )
                                        )                                    
                                    ), 'cache' => 'yes'
                                );
            $this->Physicalproduct->recursive = 2;
            $searchResults = $this->paginate('Physicalproduct');
            $this->set('searchResults', $searchResults);
        }
        $this->layout = 'home';
    }
    
    function userDownload()
    {          
        $libId = $_SESSION['library'];
        $patId = $_SESSION['patron'];
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);		
	$patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
        if($libraryDownload != '1' || $patronDownload != '1')
        {
            echo "error";
            exit;
        }
        $prodId = $_REQUEST['prodId'];                
        $trackDetails = $this->Physicalproduct->getdownloaddata($prodId);        
        $insertArr = Array();
        $insertArr['library_id'] = $libId;
        $insertArr['patron_id'] = $patId;
        $insertArr['ProdID'] = $prodId;     
        $insertArr['artist'] = $trackDetails['0']['Metadata']['Artist'];
        $insertArr['track_title'] = $trackDetails['0']['Metadata']['Title'];
        $insertArr['ProductID'] = $trackDetails['0']['Physicalproduct']['ProductID'];
        $insertArr['ISRC'] = $trackDetails['0']['Metadata']['ISRC'];	
        $this->Download->save($insertArr);
        $sql = "UPDATE `libraries` SET library_current_downloads=library_current_downloads+1,library_total_downloads=library_total_downloads+1,library_available_downloads=library_available_downloads-1 Where id=".$libId;	
        $this->Library->query($sql);
        $startDate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-(date('w', mktime(0, 0, 0, date('m'), date('d'), date('Y')))-1), date('Y')))." 00:00:00";
        $endDate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')+(7-date('w', mktime(0, 0, 0, date('m'), date('d'), date('Y')))), date('Y')))." 23:59:59";           
        $downloadsUsed =  $this->Download->find('count',array('conditions' => array('library_id' => $libId,'patron_id' => $patId,'created BETWEEN ? AND ?' => array($startDate, $endDate))));       
        $this ->Session->write("downloadsUsed", $downloadsUsed);
        echo $downloadsUsed;
	exit;
    }

    function advance_search()
    {
	$this->layout = 'home';           
	$this->Genre->recursive = -1;
	$genres = $this->Genre->find('all', array('fields' => 'DISTINCT Genre','order' => 'Genre','cache' => 'Genre'));
	$resultArr = array();
	foreach($genres as $genre)
	{                  
	    $resultArr[$genre['Genre']['Genre']] = $genre['Genre']['Genre'];
	}
	$this->set('genres',$resultArr);
    }
    
    function checkPatron()
    {
	$libid = $_REQUEST['libid'];       
        $patronid = $_REQUEST['patronid'];        
        $this->layout = false;           	
	$currentPatron = $this->Currentpatron->find('all',array('conditions' => array('libid' => $libid,'patronid' => $patronid)));        
	if(count($currentPatron) > 0)
        {
          $updateArr = array();
          $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];
          $updateArr['session_id'] = session_id();
          $this->Currentpatron->save($updateArr);
        }
        echo "Success";
        exit;
    }
    
    function approvePatron()
    {
	$libid = $_REQUEST['libid'];       
        $patronid = $_REQUEST['patronid'];        
        $this->layout = false;           	
	$currentPatron = $this->Currentpatron->find('all',array('conditions' => array('libid' => $libid,'patronid' => $patronid)));        
	if(count($currentPatron) > 0)
        {
          $updateArr = array();
          $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];
          $updateArr['is_approved'] = 'yes';          
          $this->Currentpatron->save($updateArr);
          $this->Session->write('approved', 'yes');
        }
        echo "Success";
        exit;
    }
    
    public function admin_aboutusform()
    {
	if(isset($this->data)) {
	    if($this->data['Home']['id'] != "") {
		$this->Page->id = $this->data['Home']['id'];
		$pageData['Page']['page_name'] = $this->data['Home']['page_name'];
		$pageData['Page']['page_content'] = $this->data['Home']['page_content'];;
		$this->Page->set($pageData['Page']);
		if($this->Page->save())
		{
		  $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
		}
	    }
	    else {
		$pageData['Page']['page_name'] = $this->data['Home']['page_name'];
		$pageData['Page']['page_content'] = $this->data['Home']['page_content'];;
		$this->Page->set($pageData['Page']);
		if($this->Page->save()) {
		    $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
		}
		else {
		    $this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
		}
	    }
	}
        $this -> set( 'formAction', 'admin_aboutusform');
        $this -> set( 'formHeader', 'Manage About Us Page Content' );
        $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'aboutus')));
	if(count($getPageData) != 0) {
	    $getData['Home']['id'] = $getPageData[0]['Page']['id'];
	    $getData['Home']['page_name'] = $getPageData[0]['Page']['page_name'];
	    $getData['Home']['page_content'] = $getPageData[0]['Page']['page_content'];
	    $this -> set( 'getData', $getData );
	}
	else {
	    $arr = array();
	    $this->set('getData',$arr);
	}
	$this->layout = 'admin';
    }
    
    public function admin_termsform()
    {
	if(isset($this->data)) {
	    if($this->data['Home']['id'] != "") {
		$this->Page->id = $this->data['Home']['id'];
		$pageData['Page']['page_name'] = $this->data['Home']['page_name'];
		$pageData['Page']['page_content'] = $this->data['Home']['page_content'];;
		$this->Page->set($pageData['Page']);
		if($this->Page->save())
		{
		  $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
		}
	    }
	    else {
		$pageData['Page']['page_name'] = $this->data['Home']['page_name'];
		$pageData['Page']['page_content'] = $this->data['Home']['page_content'];;
		$this->Page->set($pageData['Page']);
		if($this->Page->save()) {
		    $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
		}
		else {
		    $this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
		}
	    }
	}
        $this -> set( 'formAction', 'admin_termsform');
        $this -> set( 'formHeader', 'Manage Terms & Condition Page Content' );
        $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'terms')));
	if(count($getPageData) != 0) {
	    $getData['Home']['id'] = $getPageData[0]['Page']['id'];
	    $getData['Home']['page_name'] = $getPageData[0]['Page']['page_name'];
	    $getData['Home']['page_content'] = $getPageData[0]['Page']['page_content'];
	    $this -> set( 'getData', $getData );
	}
	else {
	    $arr = array();
	    $this->set('getData',$arr);
	}
	$this->layout = 'admin';
    }    

	public function admin_limitsform()
    {
	if(isset($this->data)) {
	    if($this->data['Home']['id'] != "") {
		$this->Page->id = $this->data['Home']['id'];
		$pageData['Page']['page_name'] = $this->data['Home']['page_name'];
		$pageData['Page']['page_content'] = $this->data['Home']['page_content'];;
		$this->Page->set($pageData['Page']);
		if($this->Page->save())
		{
		  $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
		}
	    }
	    else {
		$pageData['Page']['page_name'] = $this->data['Home']['page_name'];
		$pageData['Page']['page_content'] = $this->data['Home']['page_content'];;
		$this->Page->set($pageData['Page']);
		if($this->Page->save()) {
		    $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
		}
		else {
		    $this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
		}
	    }
	}
        $this -> set( 'formAction', 'admin_limitsform');
        $this -> set( 'formHeader', 'Manage Download Limits Page Content' );
        $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'limits')));
	if(count($getPageData) != 0) {
	    $getData['Home']['id'] = $getPageData[0]['Page']['id'];
	    $getData['Home']['page_name'] = $getPageData[0]['Page']['page_name'];
	    $getData['Home']['page_content'] = $getPageData[0]['Page']['page_content'];
	    $this -> set( 'getData', $getData );
	}
	else {
	    $arr = array();
	    $this->set('getData',$arr);
	}
	$this->layout = 'admin';
    }

    function aboutus(){
	$this->layout = 'home';
    }
    
    function terms(){
	$this->layout = 'home';
    }

    function limits() {
            $this->layout = 'home';
    }
     /*
    Function Name : check_email
    Desc : check for a valid email
   */
    function check_email($email){
        $email_regexp = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$";
        return eregi($email_regexp, $email);
    }
     /*
    Function Name : _sendForgotPasswordMail
    Desc : email function for forgot password
   */
    function _sendForgotPasswordMail($id, $password) {
        Configure::write('debug', 0);
        $this->Email->template = 'email/forgotPasswordEmail';
        $this->User->recursive = -1;
        $Patron = $this->User->read(null,$id);
        $this->set('Patron', $Patron);
        $this->set('password', $password);        
        $this->Email->from = Configure::read('App.adminEmail');
        $this->Email->fromName = Configure::read('App.fromName');
        $this->Email->to = $Patron['User']['email'];
        $this->Email->subject = 'FreegalMusic - New Password information';
        $this->Email->smtpHostNames = Configure::read('App.SMTP');
	$this->Email->smtpAuth = Configure::read('App.SMTP_AUTH');
	$this->Email->smtpUserName = Configure::read('App.SMTP_USERNAME');
	$this->Email->smtpPassword = Configure::read('App.SMTP_PASSWORD');
        $result = $this->Email->send(); 
    }
     /*
    Function Name : forgot_password
    Desc : To send mail to patrons with new password
   */
    function forgot_password(){
        $this->layout = 'login';
        $errorMsg ='';
        if($this->data){
            $email = $this->data['Home']['email'];
            if($email == ''){
                $errorMsg = "Please provide your email address.";
            }
            elseif(!($this->check_email($email))){
                $errorMsg = "This is not a valid email.";
            }
            else{
                $email_exists = $this->User->find('all',array('conditions' => array('email' => $email, 'type_id' => '5')));               
                if(count($email_exists) == 0){
                    $errorMsg = "This is not a valid patron email.";    
                }                
            }            
            if($errorMsg != ''){                
                $this->Session->setFlash($errorMsg);
                $this->redirect($this->webroot.'homes/forgot_password');
            }            
            else{
                $temp_password = $this->PasswordHelper->generatePassword(8);
                $this->User->id = $email_exists[0]['User']['id'];                
                $this->User->saveField('password', Security::hash(Configure::read('Security.salt').$temp_password), false);
                $this->_sendForgotPasswordMail($this->User->id, $temp_password);
                $this->Session->setFlash("An email with your new password has been sent to your email account.");
                $this->redirect($this->webroot.'homes/forgot_password');
            }            
        }        
    }
     /*
    Function Name : addToWishlist
    Desc : To let the patron add songs to wishlist
   */
    function addToWishlist(){
        $libraryId = $this->Session->read('library');
        $patronId = $this->Session->read('patron');
        $this->Library->recursive = -1;
        $libraryDetails = $this->Library->find('all',array('conditions' => array('Library.id' => $libraryId),'fields' => 'library_user_download_limit'));
        //get patron limit per week
        $patronLimit = $libraryDetails[0]['Library']['library_user_download_limit'];        
        $startDate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-(date('w', mktime(0, 0, 0, date('m'), date('d'), date('Y')))-1), date('Y')))." 00:00:00";
        $endDate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')+(7-date('w', mktime(0, 0, 0, date('m'), date('d'), date('Y')))), date('Y')))." 23:59:59";
        //get no of downloads for this week
        $wishlistCount =  $this->Wishlist->find('count',array('conditions' => array('library_id' => $libraryId,'patron_id' => $patronId,'week_start_date' => $startDate,'week_end_date' => $endDate)));
        if($wishlistCount >= $patronLimit){            
            echo "error";
            exit;        
        }
        else{
            $prodId = $_REQUEST['prodId'];
            //get song details
            $trackDetails = $this->Physicalproduct->getdownloaddata($prodId);
            $insertArr = Array();
            $insertArr['library_id'] = $libraryId;
            $insertArr['patron_id'] = $patronId;
            $insertArr['ProdID'] = $prodId;
            $insertArr['artist'] = $trackDetails['0']['Metadata']['Artist'];
            $insertArr['album'] = $trackDetails['0']['Physicalproduct']['Title'];
            $insertArr['track_title'] = $trackDetails['0']['Metadata']['Title'];
            $insertArr['ProductID'] = $trackDetails['0']['Physicalproduct']['ProductID'];
            $insertArr['ISRC'] = $trackDetails['0']['Metadata']['ISRC'];
            $insertArr['week_start_date'] = $startDate;
            $insertArr['week_end_date'] = $endDate;
            //insert into wishlist table
            $this->Wishlist->save($insertArr);
            //update the libraries table
            $sql = "UPDATE `libraries` SET library_available_downloads=library_available_downloads-1 Where id=".$libraryId;
            $this->Library->query($sql);
            echo "Success";
            exit;
        }
    }    
    /*
    Function Name : my_wishlist
    Desc : To show songs present in wishlist
   */
    function my_wishlist(){        
        $this->layout = 'home';
        $libraryId = $this->Session->read('library');
        $patronId = $this->Session->read('patron');        
        $libraryDownload = $this->Downloads->checkLibraryDownload($libraryId);		
	$patronDownload = $this->Downloads->checkPatronDownload($patronId,$libraryId);
        $this->set('libraryDownload',$libraryDownload);
        $this->set('patronDownload',$patronDownload);
        $startDate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-(date('w', mktime(0, 0, 0, date('m'), date('d'), date('Y')))-1), date('Y')))." 00:00:00";
        $endDate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')+(7-date('w', mktime(0, 0, 0, date('m'), date('d'), date('Y')))), date('Y')))." 23:59:59";
        $wishlistResults = Array();
        //$wishlistInfo = $this->Wishlist->find('all',array('conditions' => array('library_id' => $libraryId,'patron_id' => $patronId)));
        $wishlistResults =  $this->Wishlist->find('all',array('conditions' => array('library_id' => $libraryId,'patron_id' => $patronId,'week_start_date' => $startDate,'week_end_date' => $endDate)));
        //if(count($wishlistInfo) > 0){            
        //}
        $this->set('wishlistResults',$wishlistResults);
    }
    /*
    Function Name : removeWishlistSong
    Desc : For removing a song from wishlist page
   */
    function removeWishlistSong() {
        $deleteSongId = $this->params['named']['id'];
        $libraryId = $this->Session->read('library');
        if($this->Wishlist->delete($deleteSongId)){
            $sql = "UPDATE `libraries` SET library_available_downloads=library_available_downloads+1 Where id=".$libraryId;
            $this->Library->query($sql);  
            $this->Session->setFlash('Data deleted successfully!');
            $this->redirect('my_wishlist');
        }else{
            $this->Session->setFlash('Error occured while deleteting the record');
            $this->redirect('my_wishlist');
        }
    }
    
    function setStatus(){
        $startDate = date('Y-m-d', mktime(0, 0, 0, date('m'), (date('d')-(date('w', mktime(0, 0, 0, date('m'), date('d'), date('Y')))-1))-7, date('Y')))." 00:00:00";        
        $endDate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')+(7-date('w', mktime(0, 0, 0, date('m'), date('d'), date('Y'))))-7, date('Y')))." 23:59:59";
        $sql = "SELECT * FROM `wishlists` WHERE `week_start_date` = '".$startDate."' AND `week_end_date` = '".$startDate."'";
        //$sql = "SELECT * FROM `wishlists` WHERE `week_start_date` = '2010-04-19 00:00:00' AND `week_end_date` = '2010-04-25 23:59:59'";
        $result = mysql_query($sql);
        while ($row = mysql_fetch_assoc($result)) {
            $libraryId = $row['library_id'];
            $id = $row['id'];
            $qry="DELETE FROM `wishlists` WHERE `wishlists`.`id` = ".$id;
            mysql_query($qry);
            $sql="UPDATE `libraries` SET library_available_downloads=library_available_downloads+1 Where id=".$libraryId;
            mysql_query($sql);
        }
    }
    
    /*
    Function Name : wishlistDownload
    Desc : For downloading a song in wishlist page
   */
    function wishlistDownload(){
        $libId = $_SESSION['library'];
        $patId = $_SESSION['patron'];
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);		
	$patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
        //check for download availability
        if($libraryDownload != '1' || $patronDownload != '1')
        {
            echo "error";
            exit;
        }
        $id = $_REQUEST['id'];       
        $prodId = $_REQUEST['prodId'];
        //get details for this song
        $trackDetails = $this->Physicalproduct->getdownloaddata($prodId);        
        $insertArr = Array();
        $insertArr['library_id'] = $libId;
        $insertArr['patron_id'] = $patId;
        $insertArr['ProdID'] = $prodId;     
        $insertArr['artist'] = $trackDetails['0']['Metadata']['Artist'];
        $insertArr['track_title'] = $trackDetails['0']['Metadata']['Title'];
        $insertArr['ProductID'] = $trackDetails['0']['Physicalproduct']['ProductID'];
        $insertArr['ISRC'] = $trackDetails['0']['Metadata']['ISRC'];
        //save to downloads table
        $this->Download->save($insertArr);
        //update library table
        $sql = "UPDATE `libraries` SET library_current_downloads=library_current_downloads+1,library_total_downloads=library_total_downloads+1 Where id=".$libId;	
        $this->Library->query($sql);
        //delete from wishlist table
        $deleteSongId = $id;     
        $this->Wishlist->delete($deleteSongId);
        //get start and end day of the week
        $startDate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-(date('w', mktime(0, 0, 0, date('m'), date('d'), date('Y')))-1), date('Y')))." 00:00:00";
        $endDate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')+(7-date('w', mktime(0, 0, 0, date('m'), date('d'), date('Y')))), date('Y')))." 23:59:59";
        //get no of downloads for this week
        $downloadsUsed =  $this->Download->find('count',array('conditions' => array('library_id' => $libId,'patron_id' => $patId,'created BETWEEN ? AND ?' => array($startDate, $endDate))));
        //set in session to show on top of every page
        $this ->Session->write("downloadsUsed", $downloadsUsed);
        echo $downloadsUsed;
	exit;
    }
}
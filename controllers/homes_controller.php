<?php
/* File Name: homes_controller.php
   File Description: Displays the home page for each patron 
   Author: Maycreate
*/   

class HomesController extends AppController
{
    var $name = 'Homes';
    var $helpers = array( 'Html','Ajax','Javascript','Form', 'Library', 'Page' );
    var $components = array('RequestHandler','ValidatePatron','Downloads');
    //var $components = array('RequestHandler');
    var $uses = array('Home','Physicalproduct','Featuredartist','Artist','Library','Metadata','Download','Genre','Currentpatron','Page');
    //var $beforeFilter = array('validatePatron');
 
   function beforeFilter()
   {
	parent::beforeFilter();
        if(($this->action != 'aboutus') && ($this->action != 'admin_aboutusform') && ($this->action != 'admin_termsform'))
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
	   'conditions'=>array('Physicalproduct.Title LIKE'=>$_GET['q'].'%'						
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
	   'conditions'=>array('Physicalproduct.ArtistText LIKE'=>$_GET['q'].'%'
	   ),
           'fields' => array(
			  'ArtistText'
		  ), 
		  'group' => array(
			  'ArtistText',
		  ),
                  'limit' => '6'));        
	$this->set('artistResults', $artistResults);
        $songResults = $this->Home->find('all', array(
	   'conditions'=>array('Title LIKE'=>$_GET['q'].'%'
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
            $cond = array('Metadata.Advisory' => 'T');
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
                                                array('Physicalproduct.DownloadStatus' => 1),$cond
                                            )
                                        ,
                                    $condition =>
                                            array(
                                                    /*array('Physicalproduct.ArtistText LIKE' => '%'.$artist.'%'),
                                                    array('Physicalproduct.Title LIKE' => '%'.$album.'%'),
                                                    array('Metadata.Title LIKE' => '%'.$song.'%'),
                                                    array('Genre.Genre LIKE' => '%'.$genre.'%')*/
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
                                    )
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
                                                    array('Physicalproduct.DownloadStatus' => 1),$cond
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
                                        )
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
        //hardcoded for testing purpose        
        //$patId = 223401;
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
        $downloadsUsed =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array($weekFirstDay, $currentDay))));
        $this ->Session->write("downloadsUsed", $downloadsUsed);
        echo $downloadsUsed;
	exit;
    }
    
    function setDownload()
    {      
        $currentDate = date('Y-m-d');
        $date = date('y-m-d');
        list($year, $month, $day) = explode('-', $date);
        $weekFirstDay = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-date('w'), date('Y')));
        $monthFirstDate = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
        $yearFirstDate = date('Y-m-d', mktime(0, 0, 0, 1, 1, $year));      
        $qry = "Select * from libraries";
        $results = mysql_query($qry);
        while($resultsArr = mysql_fetch_assoc($results))
        {
            $downloadType = $resultsArr['library_download_type'];
            if($downloadType == "daily")
            {            
                $sql = "UPDATE `libraries` SET `library_current_downloads` = '0' WHERE `libraries`.`id` =".$resultsArr['id'];
                mysql_query($sql);            
            }
            else if($downloadType == "weekly")
            {
                if($currentDate == $weekFirstDay)
                {
                    $sql = "UPDATE `libraries` SET `library_current_downloads` = '0' WHERE `libraries`.`id` =".$resultsArr['id'];
                    mysql_query($sql);
                }
            }
            else if($downloadType == "monthly")
            {
                if($currentDate == $monthFirstDate)
                {
                    $sql = "UPDATE `libraries` SET `library_current_downloads` = '0' WHERE `libraries`.`id` =".$resultsArr['id'];
                    mysql_query($sql);
                }
            }
            else if($downloadType == "anually")
            {
                if($currentDate == $yearFirstDate)
                {
                    $sql = "UPDATE `libraries` SET `library_current_downloads` = '0' WHERE `libraries`.`id` =".$resultsArr['id'];
                    mysql_query($sql);
                }
            }
        }     
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
    
    public function aboutus()
    {
	$this->layout = 'home';
    }
    
    public function terms()
    {
	$this->layout = 'home';
    }
}
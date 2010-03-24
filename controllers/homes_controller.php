<?php
/* File Name: homes_controller.php
   File Description: Displays the home page for each patron 
   Author: Maycreate
*/   

class HomesController extends AppController
{
    var $name = 'Homes';
    var $helpers = array('Html','Ajax','Javascript','Form' );
    var $components = array('RequestHandler','ValidatePatron','Downloads');
    //var $components = array('RequestHandler');
    var $uses = array('Home','Physicalproduct','Featuredartist','Artist','Library','Metadata','Download');
    var $beforeFilter = array('validatePatron');
 
   /*function beforeFilter()
    {        
        $validPatron = $this->ValidatePatron->validatepatron();
        if($validPatron)
        {
            $this->redirect(array('controller' => 'homes', 'action' => 'index'));
        }
        else
        {
            $this->redirect(array('controller' => 'homes', 'action' => 'error'));
        }
    }*/
    
    function index()
    {        
        //For testing purpose we are assigning some test values
        $this ->Session->write("library", '1');
        $this ->Session->write("patron", '2242');
        $this ->Session->write("block", 'no');       
        $this->Physicalproduct->Behaviors->attach('Containable');	
		$songDetails = $this->Physicalproduct->find('all', array('conditions' => 
                                array('Physicalproduct.ReferenceID <> Physicalproduct.ProdID'),
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
                                        'Metadata' => array('fields' => array('Metadata.Title', 'Metadata.Artist'))
                                ),'order'=> 'rand()','limit' => '8'
                )
        );
        $this->set('songs',$songDetails);
        //$this->set('songs',$this->Home->getSongs());
        $this->set('distinctArtists', $this->Physicalproduct->getallartist());
        $this->set('featuredArtists', $this->Featuredartist->getallartists());
        $this->set('newArtists', $this->Newartist->getallnewartists());
        $this->set('artists', $this->Artist->getallartists());
        $this->layout = 'home';
    }
    
    function autoComplete()
    {      
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
        $searchKey = '';      
        if(isset($_REQUEST['search']) && $_REQUEST['search'] != '')
        {
            $searchKey = $_REQUEST['search'];
        }        
        if($searchKey == '')
        {
            $searchKey = $this->data['Home']['search'];    
        }        
        $patId = $_SESSION['patron'];
        $libId = $_SESSION['library'];        
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);		
        $patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
        $this->set('libraryDownload',$libraryDownload);
        $this->set('patronDownload',$patronDownload);
        $this->set('searchKey','search='.$searchKey);
        if($_SESSION['block'] == 'yes')
        {
              $cond = array('Metadata.Advisory' => 'T');
        }
        else
        {
              $cond = "";
        }
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
        $this->layout = 'home';
    }
    
    function userDownload()
    {          
        $libId = $_SESSION['library'];
        $patId = $_SESSION['patron'];
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
}
?>
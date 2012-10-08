<?php

/* 
  File Name: homes_controller.php
  File Description: Displays the home page for each patron
  Author: Maycreate
*/

class ResetcacheController extends AppController
{
  var $name = 'Resetcache';
  var $helpers = array( 'Html','Ajax','Javascript','Form', 'Library', 'Page', 'Wishlist','Song', 'Language');
  var $components = array('RequestHandler','ValidatePatron','Downloads','PasswordHelper','Email', 'SuggestionSong','Cookie', 'CdnUpload');
  var $uses = array('User','Featuredartist','Artist','Library','Download','Genre','Currentpatron','Page','Wishlist','Album','Song','Language', 'Searchrecord');
  private $filename = '../webroot/uploads/allCache.txt';
  private $email = 'nayan225@gmail.com'; 


  /*
    Function Name : beforeFilter
    Desc : actions that needed before other functions are getting called
  */
  function beforeFilter() {

  }

	function genrateXML() {

    $this->Email->delivery = 'debug';
    $this->Email->from    = 'Somebody <somebody@example.com>';
    $this->Email->to      = 'nayan225@gmail.com';
    $this->Email->subject = 'Test';
    $this->Email->send('Hello message body!');
    exit;
    
    
		$territoryNames = array('US','CA','AU','IT','NZ');
		$xml_data = array();
		
		//loop for all country
		for($i=0;$i<count($territoryNames);$i++){
			
      $territory = $territoryNames[$i];	
					
			// Added code for top 10 genre
			$genresArray = array("Pop", "Rock", "Country", "Alternative", "Classical", "Gospel/Christian", "R&B", "Jazz", "Soundtracks", "Rap", "Blues", "Folk",
                    "Latin", "Children's", "Dance", "Metal/Hard Rock", "Classic Rock", "Soundtrack", "Easy Listening", "New Age");
			foreach($genresArray as $topgenrekey=>$topgenrevalue){
				$genredata = Cache::read($topgenrevalue.$territory);
				$xml_data[$territory]['top_ten_genre'][$topgenrevalue] = $genredata;	
			}
			
			//Restore all Genre
			$genrealldata = Cache::read('genre'.$territory);
			$xml_data[$territory]['genre_all'] = $genrealldata;	
			
			//Restore National top 100
			$NationalTop100data = Cache::read('national'.$territory);
			$xml_data[$territory]['nationaltop100'] = $NationalTop100data;	
			
			//Restore Library top 10
			$libraryDetails = array();
			$libraryDetails = $this->Library->find('all',array(
			  'fields' => array('id', 'library_territory'),
			  'conditions' => array('library_status' => 'active','library_territory' => $territory),
			  'recursive' => -1
			  )
			); 
			
			foreach($libraryDetails AS $key => $val ) {
				 $libId = $val['Library']['id'];
				 $librarytop10Data = Cache::read("lib".$libId);
				 $xml_data['librarytop10'][$libId] = $librarytop10Data;					 
			}
			
			//Restore featured artist slide show
			$languageArray = array('EN', 'ES', 'IT', 'FR');
			foreach($languageArray as $languagekey=>$languagevalue){
				$featured_artist_slide_show_data = Cache::read('ssartists_'.$territory.'_'.$languagevalue);
				$xml_data[$territory]['top_artist_slide_show'][$languagevalue] = $featured_artist_slide_show_data;	
			}

			//Restore featured albums
			$featured_albumsdata = Cache::read('featured'.$territory);
			$xml_data[$territory]['featured_albums'] = $featured_albumsdata;			
			
			//Need to comment this line
			//break;
		}
		
		//About us page
		$languageArray = array('en', 'es', 'it', 'fr');
		foreach($languageArray as $languagekey=>$languagevalue){
			$AboutUsPageData = Cache::read("page".$languagevalue.'aboutus');
			$xml_data['AboutUsPage'][$languagevalue] = $AboutUsPageData;	
		}
		
    /**
     * writes array into file
    **/
    $handle = fopen($this->filename, 'w+');
    fwrite($handle, json_encode($xml_data));
    fclose($handle);

    $src = WWW_ROOT. 'uploads/allCache.txt';
    $dst = Configure::read('App.CDN_PATH').'restcacheXML/'. 'allCache.txt';
    $error = $this->CdnUpload->sendFile($src, $dst); 

    ('error' == $error) ? $status = 'Failed' : $status = 'Success'; 
    $message = 'SRC : ' . $_SERVER['HTTP_HOST'] . ':' . $src . "\n" . 'DST : ' . $dst . "\n" . 'Status : ' . $status . "\n";
    

    if( $this->sendCardImoprtErrorEmail($message) ) {
      echo 'Email Sent Successfully';
    } else {
      echo 'Email Sent Failed'; 
    }  
    exit;	 
	} //genrateXML end
  
  
  
	/**********Functions for restore data from XML**************************/
	
	function top_ten_genre($genre, $territory){
  
    $xml_data = $this->readFile();
    
		$data = $xml_data[$territory]['top_ten_genre'][$genre]; 
    
		if(!empty($data)){
			Cache::write($genre.$territory, $data);          
		} 
		else {
			echo "Unable to update top_ten_genre for genre => ".$genre." with territory => ".$territory;
		}		
    
    exit("<br />============================================= DONE  ===============================================<br />");
	}
	
	function all_Genre($territory){
  
    $xml_data = $this->readFile();
    
		$data = $xml_data[$territory]['genre_all'];
		if(!empty($data)){
			Cache::write('genre'.$territory, $data);          
		} 
		else {
			echo "Unable to update genre with territory => ".$territory;
		}	

    exit("<br />============================================= DONE  ===============================================<br />");
	}
	
	function national_top_100($territory){
		
    $xml_data = $this->readFile();
    
    $data = $xml_data[$territory]['nationaltop100'];				  
		if(!empty($data)){
			Cache::write('national'.$territory, $data);          
		} 
		else {
			echo "Unable to update national_top_100 for territory => ".$territory;
		}	

    exit("<br />============================================= DONE  ===============================================<br />");
	}
	
	function library_top_10($libId){

    $xml_data = $this->readFile();
  
		$data = $xml_data['librarytop10'][$libId];
		if(!empty($data)){
			Cache::write('lib'.$libId, $data);          
		} 
		else {
			echo "Unable to update librarytop10 for library with id => ".$libid;
		}	

    exit("<br />============================================= DONE  ===============================================<br />");
	}
	
	function featured_artist_slide_show($territory, $languagevalue){
    
    $xml_data = $this->readFile();
    
		$data = $xml_data[$territory]['top_artist_slide_show'][$languagevalue];		  
		if(!empty($data)){
			Cache::write('ssartists_'.$territory.'_'.$languagevalue, $data );        
		} 
		else {
			echo "Unable to update ssartists for language => ".$languagevalue." territory => ".$territory;
		}	

    exit("<br />============================================= DONE  ===============================================<br />");
	}
	
	function featured_albums($territory){	
  
    $xml_data = $this->readFile();
    
		$data = $xml_data[$territory]['featured_albums'];  
		if(!empty($data)){
			Cache::write('featured'.$territory, $data );          
		} 
		else {
			echo "Unable to update featured for territory => ".$territory;
		}	

    exit("<br />============================================= DONE  ===============================================<br />");
	}
  
  function aboutuspage($languagevalue){
    
    $xml_data = $this->readFile();
    
		$data = $xml_data['AboutUsPage'][$languagevalue];	  
		if(!empty($data)){
			Cache::write("page".$languagevalue.'aboutus', $data );         
		} 
		else {
			echo "Unable to aboutuspage for language => ".$languagevalue;
		}	

    exit("<br />============================================= DONE  ===============================================<br />");
  }	
  
  /**
   * reads content from file & converts it into array
  **/
  private function readFile() {
    
    $handle = fopen($this->filename, "r");
    $contents = fread($handle, filesize($this->filename));
    fclose($handle);
    
    return json_decode($contents, true);
  }
    
    
  /*
    Function Name : _sendCardImportErrorEmail
    Desc : For sending Card Import Error Email
   */

	function sendCardImoprtErrorEmail($body) {
	  
    Configure::write('debug', 0);
    App::import('vendor', 'PHPMailer', array('file' => 'phpmailer/class.phpmailer.php'));
    $mail = new PHPMailer();


    $mail->IsSMTP();            // set mailer to use SMTP
    $mail->SMTPAuth = 'true';     // turn on SMTP authentication
    $mail->Host     =  Configure::read('App.SMTP');
    $mail->Username = Configure::read('App.SMTP_USERNAME');
    $mail->Password = Configure::read('App.SMTP_PASSWORD');

    $mail->From     = Configure::read('App.adminEmail');
    $mail->FromName = Configure::read('App.fromName');
    $mail->AddAddress($this->email);
	  
	  $mail->ConfirmReadingTo = '';
    $mail->CharSet  = 'UTF-8';
    $mail->WordWrap = 50;  // set word wrap to 50 characters
    $mail->IsHTML(true);  // set email format to HTML

    $mail->Subject = 'Cache Update (' .date('Y-m-d h:i:s') . ')';
    $mail->Body    = $body;
    $result = $mail->Send();

    return $result;

	}  
  
}
?>

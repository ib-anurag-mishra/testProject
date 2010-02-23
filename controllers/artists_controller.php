<?php
  /*
 File Name : artists_controller.php
 File Description : Artist controller page
 Author : maycreate
 */
Class ArtistsController extends AppController
{
  var $name = 'Artists';
  var $uses = array('Featuredartist','Physicalproduct','Artist');
  var $layout = 'admin';
  var $helpers = array('Html','Ajax','Javascript','Form' );
  
  
  
    function beforeFilter()
    {
       $this->Auth->userModel = 'Admins';
       if($this->Session->read('Auth.Admin.type_id') == 1)
       {
          $this->Auth->allow('*');
          $this->set('username',  $this->Session->read('Auth.Admin.username'));
       }else{
         $this->redirect('/admins/login');
       }
    }   
  
   /*
    Function Name : managefeaturedartist
    Desc : action for listing all the featured artists
   */
   
    
    public function managefeaturedartist()
    {         
         $artistObj = new Featuredartist();
         $artists = $artistObj->getallartists();
         $this->set('artists',$artists);
    }
  
  /*
    Function Name : artistform
    Desc : action for displaying the add/edit featured artist form
   */
    public function artistform()
    {       
	if(!empty($this->params['named']))//gets the values from the url in form  of array
        {
            $artistId = $this->params['named']['id'];
	            
            if(trim($artistId) != "" && is_numeric($artistId))
            {
		$this->set('formAction','updatefeaturedartist/id:'.$artistId);
                $this->set('formHeader','Edit Featured Artist');
                $getArtistrDataObj = new Featuredartist();
                $getData = $getArtistrDataObj->getartistdata($artistId);		               
                $this->set('getData', $getData);
                $condition = 'edit';
		$artistName = $getData['Featuredartist']['artist_name'];
            }
        }
        else
        {                
            $this->set('formAction','insertfeaturedartist');
            $this->set('formHeader','Add Featured Artist');
	    $getFeaturedDataObj = new Featuredartist();
            $featuredtData = $getFeaturedDataObj->getallartists();
            $condition = 'add';
	    $artistName = '';
        }            
        $getArtistDataObj = new Physicalproduct();
        $getArtistData = $getArtistDataObj->getallartistname($condition,$artistName);
        $this->set('getArtistData', $getArtistData);            
    }   

   
       
    /*
    Function Name : insertfeaturedartist
    Desc : inserts a featured artist
   */
    public function insertfeaturedartist()
    {
       $errorMsg = '';
       $newPath = "../webroot/img/featuredimg/";
       $fileName = $this->data['Artist']['artist_image']['name'];
       $newPath = $newPath.$fileName;
       move_uploaded_file($this->data['Artist']['artist_image']['tmp_name'], $newPath);      
       $filePath = $this->data['Artist']['artist_image']['tmp_name'];
       if($this->data['Artist']['artist_name'] == "")
       {
        $errorMsg  .="Please select an Artist.<br/>";
       }
      if($this->data['Artist']['artist_image']['name'] == "")
       {
        $errorMsg  .="Please upload an image.<br/>";
       }
       $insertArr = array();
       $insertArr['artist_name'] = $this->data['Artist']['artist_name'];
       $insertArr['artist_image'] = "img/featuredimg/".$this->data['Artist']['artist_image']['name'];
       $insertObj = new Featuredartist();
       if(empty($errorMsg))
       {
          if($insertObj->insert($insertArr))
          {
             $this->Session->setFlash('Data has been saved Sucessfully');
             $this->redirect('/artists/managefeaturedartist');
          }
       }else{
          $this->Session->setFlash($errorMsg);
           $this->redirect('/artists/artistform');
       }
    }
    
    /*
    Function Name : updatefeaturedartist
    Desc : Updates a featured artist
   */
    
    public function updatefeaturedartist()
    {
        $errorMsg = '';
	$this->Featuredartist->id = $this->data['Artist']['id'];              
        $newPath = "../webroot/img/featuredimg/";
        $fileName = $this->data['Artist']['artist_image']['name'];
        $newPath = $newPath.$fileName;
        move_uploaded_file($this->data['Artist']['artist_image']['tmp_name'], $newPath);      
        $filePath = $this->data['Artist']['artist_image']['tmp_name'];
        if($this->data['Artist']['artist_name'] == "")
        {
          $errorMsg  .="Please select an Artist.<br/>";
        }
        $updateArr = array();
        $updateArr['id'] = $this->data['Artist']['id'];
        $updateArr['artist_name'] = $this->data['Artist']['artist_name'];
        if($this->data['Artist']['artist_image']['name'] != "")
        {
          $updateArr['artist_image'] = "img/featuredimg/".$this->data['Artist']['artist_image']['name'];
        }
        $updateObj = new Featuredartist();
        if(empty($errorMsg))
        {
          if($updateObj->insert($updateArr))
          {
             $this->Session->setFlash('Data has been updated Sucessfully');
             $this->redirect('/artists/managefeaturedartist');
          }
        }else{
          $this->Session->setFlash($errorMsg);
          $this->redirect('/artists/managefeaturedartist');
        }
    }
    
     /*
    Function Name : updatefeaturedartist
    Desc : For deleting a featured artist
   */
    public function delete()
    {    
      $deleteArtistUserId =$this->params['named']['id'];	
      $deleteObj  = new Featuredartist();
      if($deleteObj->del($deleteArtistUserId))
      {
        $this->Session->setFlash('Data deleted Sucessfully');
        $this->redirect('/artists/managefeaturedartist');
      }else{
        $this->Session->setFlash('Error occured while deleteting the record');
        $this->redirect('/artists/managefeaturedartist');
      }

    }
     /*
    Function Name : createartist
    Desc : assigns artists with images
   */
    public function createartist()
    {
      $errorMsg = '';
      if(!empty($this->params['named']['id']))//gets the values from the url in form  of array
        {
            $artistId = $this->params['named']['id'];
	            
            if(trim($artistId) != "" && is_numeric($artistId))
            {
		$this->set('formAction','createartist/id:'.$artistId);
                $this->set('formHeader','Edit Artist');
                $getArtistrDataObj = new Artist();
                $getData = $getArtistrDataObj->getartistdata($artistId);		               
                $this->set('getData', $getData);
                $condition = 'edit';
                $artistName = $getData['Artist']['artist_name'];
                if(isset($this->data))
                {   
                  $updateObj = new Artist();
                  $updateArr = array();
                  if($this->data['Artist']['artist_name'] == "")
                  {
                    $errorMsg .= 'Please select Artist Name';
                  }
                  $updateArr['id'] = $this->data['Artist']['id'];
                  $updateArr['artist_name'] = $this->data['Artist']['artist_name'];
                  if($this->data['Artist']['artist_image']['name'] != "")
                  {
                    $newPath = "../webroot/img/artistimg/";
                    $fileName = $this->data['Artist']['artist_image']['name'];
                    $newPath = $newPath.$fileName;
                    move_uploaded_file($this->data['Artist']['artist_image']['tmp_name'], $newPath);   
                    $updateArr['artist_image'] = "img/artistimg/".$this->data['Artist']['artist_image']['name'];
                  }
                  if(empty($errorMsg))
                  {
                    if($updateObj->insert($updateArr))
                    {
                        $this->Session->setFlash('Data has been save Sucessfully');
                        $this->redirect('/artists/manageartist');
                    }
                  }else{
                     $this->Session->setFlash($errorMsg);
                  }
                }
            }
        }
        else
        {                
            $this->set('formAction','createartist');
            $this->set('formHeader','Add  Artist');
            $condition = 'add';
            $artistName = '';
            if(isset($this->data))
            {
             if($this->data['Artist']['artist_image']['name'] == "")
             {
                $errorMsg .= 'Please upload an image<br/>';
             }
             if(trim($this->data['Artist']['artist_name']) == "")
             {
                $errorMsg .= 'Please select an artist name<br/>';
             }
             $newPath = "../webroot/img/artistimg/";
             $fileName = $this->data['Artist']['artist_image']['name'];
             $newPath = $newPath.$fileName;
             move_uploaded_file($this->data['Artist']['artist_image']['tmp_name'], $newPath);      
             $filePath = $this->data['Artist']['artist_image']['tmp_name'];
             $insertArr = array();
             $insertArr['artist_name'] = $this->data['Artist']['artist_name'];
             $insertArr['artist_image'] = "img/artistimg/".$this->data['Artist']['artist_image']['name'];
             $insertObj = new Artist();
             if(empty($errorMsg))
             {
               if($insertObj->insert($insertArr))
               {
                    $this->Session->setFlash('Data has been save Sucessfully');
                    $this->redirect('/artists/manageartist');
               }
             }else{
                $this->Session->setFlash($errorMsg);
             }
            }
        }
      $getArtistDataObj = new Physicalproduct();
      $getArtistData = $getArtistDataObj->allartistname($condition,$artistName);
      $this->set('getArtistData', $getArtistData);           
    }


    /*
    Function Name : manageartists
    Desc : manages artists with images
   */
   public function manageartist()
    {
      $artistObj = new Artist();
      $artists = $artistObj->getallartists();
      $this->set('artists',$artists);
    }

   /*
    Function Name : updatefeaturedartist
    Desc : For deleting a featured artist
   */
    public function deleteartists()
    {    
      $deleteArtistUserId =$this->params['named']['id'];	
      $deleteObj  = new Artist();
      if($deleteObj->del($deleteArtistUserId))
      {
        $this->Session->setFlash('Data deleted Sucessfully');
        $this->redirect('/artists/manageartist');
      }else{
        $this->Session->setFlash('Error occured while deleteting the record');
        $this->redirect('/artists/manageartist');
      }

    }
    
}
?>
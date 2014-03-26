<?php
/*
 File Name : qnewscontroller.php
 File Description : Question controller page
 Author : m68interactive
 */

class NewsController extends AppController
{
	var $name = 'News';
	//var $helpers = array('Library', 'Page', 'Language');
	//var $components = array('RequestHandler','ValidatePatron');
	var $layout = 'admin';
	var $helpers = array('Html', 'Ajax', 'Javascript', 'Form', 'Library', 'Page', 'Wishlist', 'Language');
	var $components = array('CdnUpload');
	var $uses = array('Language','News');
	/*
	 Function Name : beforeFilter
	 Desc : actions that needed before other functions are getting called
        */
	function beforeFilter() {
		parent::beforeFilter();
		// if(($this->action != 'admin_reorder') && ($this->action != 'admin_index') && ($this->action != 'admin_view') && ($this->action != 'admin_add') && ($this->action != 'admin_edit') && ($this->action != 'admin_delete')) {
			// $validPatron = $this->ValidatePatron->validatepatron();
			// if($validPatron == '0') {

				// $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
			// }
			// else if($validPatron == '2') {

				// $this -> Session -> setFlash("Sorry! Your Library or Patron information is missing. Please log back in again if you would like to continue using the site.");
				// $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));			
			// }
		// }
	}
	
	/*
	 Function Name : index
	 Desc : actions index for showing faqs at user end
        */
	function index() {
	
		if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
			$this->Session->write('Config.language', 'en');
		}
		$this->layout = 'home';
		
		$news_count = $this->News->find('count', array('conditions' => array('AND' => array('language' => $this->Session->read('Config.language')))));
		
		if($news_count != 0){
			$this->paginate = array(   
				'conditions' => array('AND' => array('language' => $this->Session->read('Config.language'), 'place LIKE' => "%".$this->Session->read('territory')."%")),
				'order' => 'News.created DESC','limit' => '3','cache' => 'yes'
			);
		}
		else{
			$this->paginate = array(   
				'conditions' => array('AND' => array('language' => 'en', 'place LIKE' => "%".$this->Session->read('territory')."%")),
				'order' => 'News.created DESC','limit' => '3','cache' => 'yes'
			);		
		}
		$this->set('news', $this->paginate('News'));
		
		
		  // print_r($news);
	}
	
	/*
	 Function Name : view
	 Desc : actions index for showing individual faqs at user end
        */
	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'question'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('question', $this->Question->read(null, $id));
	}

	/*
	 Function Name : admin_index
	 Desc : actions for showing admin end faqs form
        */
	function admin_index() {
		Configure::write('debug', 0);
		if((!$this->Session->read('Auth.User.type_id')) && ($this->Session->read('Auth.User.type_id') != 1))
		{
			$this->redirect(array('controller' => 'users', 'action' => 'login'));
		}
		$this->layout = 'admin';
		//$this->Question->recursive = -2;
		$this->paginate = array('conditions' => array('language' => 'en'),		     
		      'order' => 'News.created'		     
		);
		$this->set('news', $this->paginate('News'));
		
	}
	
	/*
	 Function Name : admin_view
	 Desc : actions for showing admin end faqs view
        */
	function admin_view($id = null) {
		$this->layout = 'admin';
		Configure::write('debug', 0);
		if((!$this->Session->read('Auth.User.type_id')) && ($this->Session->read('Auth.User.type_id') != 1))
		{
			$this->redirect(array('controller' => 'users', 'action' => 'login'));
		}
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'question'));
			$this->redirect(array('action' => 'index'));
		}
		
		$this->set('news', $this->News->read(null, $id));
		
	}
	
	/*
	 Function Name : admin_add
	 Desc : actions for showing admin end faqs add
        */
	function admin_add() {
		Configure::write('debug', 0);
		if((!$this->Session->read('Auth.User.type_id')) && ($this->Session->read('Auth.User.type_id') != 1))
		{
			$this->redirect(array('controller' => 'users', 'action' => 'login'));
		}
		$this->layout = 'admin';
		$errorMsg = '';
		if (!empty($this->data)) {
			$updateObj = new News();
			$insert_id_array = $this->News->find('first', array('fields' => array('max(id) +1 as id')), array('conditions' => array()));

			$updateArr = array();
			if( $this -> data[ 'News' ][ 'subject' ] == '' ) {
				$errorMsg .= 'Please insert subject';
			}	
			$updateArr[ 'id' ] = $insert_id_array[0]['id'] ;
			$updateArr[ 'subject' ] = $this -> data[ 'News' ][ 'subject' ];
			$updateArr[ 'body' ] = $this -> data[ 'News' ][ 'body' ];
			$updateArr[ 'place' ] = $this -> data[ 'News' ][ 'place' ];
			$updateArr['created'] = date('Y-m-d h:i:s');
			
			if( $this -> data[ 'News' ][ 'image_name' ][ 'name' ] != '' ) {
			
				$newPath = '../webroot/img_news/';
				$fileName = $this -> data[ 'News' ][ 'image_name' ][ 'name' ];
				
				$path_parts = pathinfo($fileName);
				
				$img_name = $insert_id_array[0]['id']. "." . $path_parts['extension'];
				$newPath = $newPath . $img_name;
				$src = WWW_ROOT."img_news/" . $img_name;
				$dst = Configure::read('App.CDN_PATH').'news_image/'.$img_name;
				
				if(move_uploaded_file($this->data['News']['image_name']['tmp_name'], $newPath ))
				{
					
					$success = $this->CdnUpload->sendFile($src, $dst);
					$updateArr['image_name'] = $img_name;

				}
				else
				{
					$errorMsg .= "Not able to uopload image..";
				}
			}
			
			;

			if(empty( $errorMsg )) {
				if( $this->News->save($updateArr) ) {
					$this -> Session -> setFlash( 'Data has been saved successfully!', 'modal', array( 'class' => 'modal success' ) );
					
				}
				
				$this->redirect(array('action'=>'../../news/clearnewcache/1/en/' . $insert_id_array[0]['id']));
			}
			else {
				echo $errorMsg;
				$this -> Session -> setFlash( $errorMsg, 'modal', array( 'class' => 'modal problem' ) );
			}
		}
		//$sections = $this->Question->Section->find('list');
		//$this->set(compact('sections'));
	}
	
	/*
	 Function Name : admin_edit
	 Desc : actions for showing admin end faqs edit
        */
	function admin_edit($id = null) {
		$this->layout = 'admin';
		Configure::write('debug', 0);
		if((!$this->Session->read('Auth.User.type_id')) && ($this->Session->read('Auth.User.type_id') != 1))
		{
			$this->redirect(array('controller' => 'users', 'action' => 'login'));
		}
		$errorMsg = '';
		$image_name = '';
		$this->set('languages', $this->Language->find('list', array('fields' => array('short_name', 'full_name'))));
		$image_name_array = $this->News->find('first', array('conditions' => array('id' => $id, 'language' => 'en')));
		if(isset($this->data) && ($this->data['News']['language_change']) == 1){
			$language = $this->data['News']['language'];
			$this -> set( 'formAction', 'NewsAdminEditForm');
			$this -> set( 'formHeader', 'Manage About Us Page Content' );
			$this->data  = $this->News->find('first', array('conditions' => array('id' => $id, 'language' => $language)));
			
			if(empty($this->data)){
				
				$getData = array();
				$getData['News']['id'] = $id;
				$getData['News']['language'] = $language;
				$getData['News']['image_name'] = $image_name_array['News']['image_name'];
				$getData['News']['subject'] = '';
				$this->data = $getData;
			}

			$this->set('news' , $this->data);
		}
		else{
			if (!$id && empty($this->data)) {
				$this->Session->setFlash(sprintf(__('Invalid %s', true), 'news'));
				$this->redirect(array('action' => 'index'));
			}
			if (!empty($this->data)) {
				$updateObj = new News();
				$updateObj->id = $id; 
				$updateArr = array();
				if( $this -> data[ 'News' ][ 'subject' ] == '' ) {
					$errorMsg .= 'Please insert subject';
				}	
				
				$subject = mysql_real_escape_string($this -> data[ 'News' ][ 'subject' ]);
				$body = mysql_real_escape_string($this -> data[ 'News' ][ 'body' ]);
				$place = mysql_real_escape_string($this -> data[ 'News' ][ 'place' ]);
				$modified = date('Y-m-d h:i:s');
				$language = $this -> data[ 'News' ][ 'language' ];
				$image_name_path = $image_name_array['News']['image_name'];
				
				if( $this -> data[ 'News' ][ 'image_name' ][ 'name' ] != '' ) {
				
					$newPath = '../webroot/img_news/';
					$fileName = $this -> data[ 'News' ][ 'image_name' ][ 'name' ];
					$path_parts = pathinfo($fileName);
					$newPath .= $id. "." . $path_parts['extension'];
					
					if (file_exists($newPath))
					{
						unlink($newPath);
					}
					
					if(move_uploaded_file($this->data['News']['image_name']['tmp_name'], $newPath ))
					{
						$src = WWW_ROOT."img_news/" . $id. "." . $path_parts['extension'] ;
						$dst = Configure::read('App.CDN_PATH').'news_image/'. $id. "." . $path_parts['extension'];
						$success = $this->CdnUpload->sendFile($src, $dst);
						$image_name = $id . "." . $path_parts['extension'];
					}
					else
					{
						$errorMsg = "Not able to upload file due to some internal error..";
					}
					$this->data = $this->News->read(null, $id);
					$this->set('news' , $this->data);
				};

				if(empty( $errorMsg )) {
					$language = $this->data['News']['language'];
					$check_record =  $this->News->find('first', array('conditions' => array('id' => $id, 'language' => $language)));
					if(!empty($check_record)){
						//update record
						//update the News table
						$this->News->setDataSource('master');
						if($image_name ){
							$sql = "UPDATE `news` SET body = '" . $body . "', subject = '" . $subject . "', place = '" . $place  . "' ,modified = '" . $modified  ."', image_name = '" . $image_name ."' Where id=". $id . " and language= '". $language ."'";
						}
						else{
							$sql = "UPDATE `news` SET body = '" . $body . "', subject = '" . $subject . "', place = '" . $place  . "' ,modified = '" . $modified  ."' Where id=". $id . " and language= '". $language ."'";						
						}
						
						$result = $this->News->query($sql);
						$this->News->setDataSource('default');						
						
						if($result) {
							
							$this -> Session -> setFlash( 'Data has been saved successfully!', 'modal', array( 'class' => 'modal success' ) );
						}	
						
					}
					else{
						//Insert the News table
						$created = date('Y-m-d h:i:s');
						if($image_name_path){
							
							$sql = "Insert into `news` SET body = '" . $body . "', subject = '" . $subject . "', place = '" . $place  . "' ,modified = '" . $modified  . "' ,created = '" . $created  . "', id=". $id . ", language= '". $language ."'" . ", image_name = '" . $image_name_path ."'";
						}
						else{
							$sql = "Insert into `news` SET body = '" . $body . "', subject = '" . $subject . "', place = '" . $place  . "' ,modified = '" . $modified  . "' ,created = '" . $created  .  "', id=". $id . ", language= '". $language ."'";					
						}
						$this->News->setDataSource('master');
						$result = $this->News->query($sql);
						$this->News->setDataSource('default');						
						
						if($result) {
							$this -> Session -> setFlash( 'Data has been saved successfully!', 'modal', array( 'class' => 'modal success' ) );
						}	
					}
					
					//$this->clearnewcache(1, $language, $id);
					$this->redirect(array('action'=>'../../news/clearnewcache/1/' . $language . '/' . $id));
				}
				else {
							echo $errorMsg;
					$this -> Session -> setFlash( $errorMsg, 'modal', array( 'class' => 'modal problem' ) );
				}
			}
				
			if (empty($this->data)) {
				$this->data = $this->News->find('first', array('conditions' => array('id' => $id, 'language' => 'en')));
				$this->set('news' , $this->data);
			}
		}

	}
	
	/*
	 Function Name : admin_delete
	 Desc : actions for showing admin end faqs delete
        */
	function admin_delete($id = null) {
		$this->layout = 'admin';
		Configure::write('debug', 0);
		if((!$this->Session->read('Auth.User.type_id')) && ($this->Session->read('Auth.User.type_id') != 1))
		{
			$this->redirect(array('controller' => 'users', 'action' => 'login'));
		}
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true), 'question'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->News->delete($id)) {
			$this->Session->setFlash(sprintf(__('%s deleted', true), 'News'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true), 'News'));
		$this->redirect(array('action' => 'index'));
	}
	
	function admin_reorder(){
		$list = $this->params['url']['table_1'];
		$all_data = $this->Question->find("all");
		$this->Question->setDataSource('master');
		for($i=0;$i<count($all_data);$i++){
			$sql = "UPDATE questions SET sort_id=".$i." WHERE id=".$list[$i];
			$this->Question->query($sql);
		}
		$this->Question->setDataSource('default'); 
	}

	
	function clearnewcache($page=1, $language='en', $id){
    		$this->autoRender = true;
    		$alias = 'News';
    		$args = array('0' => array('AND'=>array('language' => $language)),'1' => null ,'2' => 'News.created DESC','3' => 3,'4' => intval($page),'5' => 1,'6' => array('cache' => 'yes'));
    		$uniqueCacheId = '';
    		foreach ($args as $arg) {
      			$uniqueCacheId .= serialize($arg);
    		}
    		$uniqueCacheId = md5($uniqueCacheId);
    		$check = Cache::delete('pagination-'.$alias.'-'.$uniqueCacheId);
			$this->redirect(array('action'=>'../admin/news/view/' . $id));
  	}
	
	function clearcache($page=1, $language='en'){
    		$this->autoRender = false;
    		$alias = 'News';
    		$args = array('0' => array('AND'=>array('language' => $language)),'1' => null ,'2' => 'News.created DESC','3' => 3,'4' => intval($page),'5' => 1,'6' => array('cache' => 'yes'));
    		$uniqueCacheId = '';
    		foreach ($args as $arg) {
      			$uniqueCacheId .= serialize($arg);
    		}
    		$uniqueCacheId = md5($uniqueCacheId);
    		$check = Cache::delete('pagination-'.$alias.'-'.$uniqueCacheId);
    		if($check == true){
      			echo "Cache cleared";
    		} else {
      		echo "Cache not cleared";
    		}
  	}
}
?>

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
	//var $components = array('Session', 'Auth', 'Acl','RequestHandler','Downloads','ValidatePatron','CdnUpload');
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
		
		$this->paginate = array(   
			'order' => 'News.created DESC','limit' => '3'
		);
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
		$this->layout = 'admin';
		//$this->Question->recursive = -2;
		$this->paginate = array('conditions' => array(),		     
		      'order' => 'News.created'		     
		);
		$this->set('news', $this->paginate());
		
	}
	
	/*
	 Function Name : admin_view
	 Desc : actions for showing admin end faqs view
        */
	function admin_view($id = null) {
		$this->layout = 'admin';
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
		$this->layout = 'admin';
		if (!empty($this->data)) {
			$updateObj = new News();
			$updateArr = array();
			if( $this -> data[ 'News' ][ 'subject' ] == '' ) {
				$errorMsg .= 'Please insert subject';
			}					
			$updateArr[ 'subject' ] = $this -> data[ 'News' ][ 'subject' ];
			$updateArr[ 'body' ] = $this -> data[ 'News' ][ 'body' ];
			$updateArr[ 'place' ] = $this -> data[ 'News' ][ 'place' ];
			$updateArr['created'] = date('Y-m-d h:i:s');
			
			if( $this -> data[ 'News' ][ 'image_name' ][ 'name' ] != '' ) {
			
				$newPath = '../webroot/img_news/';
				$fileName = $this -> data[ 'News' ][ 'image_name' ][ 'name' ];
				
				$path_parts = pathinfo($fileName);
				
				$newPath = $newPath .$updateObj->getNextAutoIncrement(). "." . $path_parts['extension'];
			
				
				if(move_uploaded_file($this->data['News']['image_name']['tmp_name'], $newPath ))
				{
					$updateArr['image_name'] = $updateObj->getNextAutoIncrement(). "." . $path_parts['extension'];
				}
				else
				{
					$errorMsg = "Not able to uopload image..";
				}
			}
			
			;

			if(empty( $errorMsg )) {
				if( $this->News->save($updateArr) ) {
					$this -> Session -> setFlash( 'Data has been saved successfully!', 'modal', array( 'class' => 'modal success' ) );
				}
			}
			else {
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
			
			$updateArr[ 'subject' ] = $this -> data[ 'News' ][ 'subject' ];
			$updateArr[ 'body' ] = $this -> data[ 'News' ][ 'body' ];
			$updateArr[ 'place' ] = $this -> data[ 'News' ][ 'place' ];
			$updateArr['modified'] = date('Y-m-d h:i:s');
			
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
					$updateArr['image_name'] = $id . "." . $path_parts['extension'];
				}
				else
				{
					$errorMsg = "Not able to upload file due to some internal error..";
				}
				$this->data = $this->News->read(null, $id);
				$this->set('news' , $this->data);
			};

			if(empty( $errorMsg )) {
				if( $updateObj->save($updateArr) ) {
					$this -> Session -> setFlash( 'Data has been saved successfully!', 'modal', array( 'class' => 'modal success' ) );
				}
				$this->redirect(array('action'=>'view/' . $id));
			}
			else {
						echo $errorMsg;
				$this -> Session -> setFlash( $errorMsg, 'modal', array( 'class' => 'modal problem' ) );
			}
		}
			
		if (empty($this->data)) {
			$this->data = $this->News->read(null, $id);
			$this->set('news' , $this->data);
		}
	}
	
	/*
	 Function Name : admin_delete
	 Desc : actions for showing admin end faqs delete
        */
	function admin_delete($id = null) {
		$this->layout = 'admin';
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
}
?>
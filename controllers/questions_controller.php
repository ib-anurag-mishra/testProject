<?php
/*
 File Name : questions_controller.php
 File Description : Question controller page
 Author : maycreate
 */

class QuestionsController extends AppController
{
	var $name = 'Questions';
	var $helpers = array('Library', 'Page', 'Language');
	var $components = array('RequestHandler','ValidatePatron');
	
	/*
	 Function Name : beforeFilter
	 Desc : actions that needed before other functions are getting called
        */
	function beforeFilter() {
		parent::beforeFilter();
		if(($this->action != 'admin_reorder') && ($this->action != 'admin_index') && ($this->action != 'admin_view') && ($this->action != 'admin_add') && ($this->action != 'admin_edit') && ($this->action != 'admin_delete')) {
			$validPatron = $this->ValidatePatron->validatepatron();
			if($validPatron == '0') {
				//$this->Session->destroy();
				//$this -> Session -> setFlash("Sorry! Your session has expired.  Please log back in again if you would like to continue using the site.");
				$this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
			}
			else if($validPatron == '2') {
				//$this->Session->destroy();
				$this -> Session -> setFlash("Sorry! Your Library or Patron information is missing. Please log back in again if you would like to continue using the site.");
				$this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));			
			}
		}
	}
	
	/*
	 Function Name : index
	 Desc : actions index for showing faqs at user end
        */
	function index() {
		  $this->layout = 'home';
		  $this->Question->recursive = 0;
		  $questions = $this->Question->find("all",array( 
				'conditions' => array('Section.language' => $this->Session->read('Config.language')),       
				'order' => 'Question.section_id ASC,Question.sort_id ASC'
		  ));
		  $this->set('questions', $questions);
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
		$this->Question->recursive = -2;
		$this->paginate = array('conditions' => array(),		     
		      'order' => 'Question.section_id ASC,Question.sort_id ASC'		     
		);
		$this->set('questions', $this->paginate());
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
		$this->set('question', $this->Question->read(null, $id));
	}
	
	/*
	 Function Name : admin_add
	 Desc : actions for showing admin end faqs add
        */
	function admin_add() {
		$this->layout = 'admin';
		if (!empty($this->data)) {
			$this->Question->create();
			if ($this->Question->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'question'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'question'));
			}
		}
		$sections = $this->Question->Section->find('list');
		$this->set(compact('sections'));
	}
	
	/*
	 Function Name : admin_edit
	 Desc : actions for showing admin end faqs edit
        */
	function admin_edit($id = null) {
		$this->layout = 'admin';
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'question'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Question->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'question'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'question'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Question->read(null, $id);
		}
		$sections = $this->Question->Section->find('list');
		$this->set(compact('sections'));
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
		if ($this->Question->delete($id)) {
			$this->Session->setFlash(sprintf(__('%s deleted', true), 'Question'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true), 'Question'));
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
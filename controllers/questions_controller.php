<?php
class QuestionsController extends AppController {

	var $name = 'Questions';
	var $helpers = array('Library');
	var $components = array('RequestHandler','ValidatePatron');
	
	function beforeFilter() {
		parent::beforeFilter();
		if(($this->action != 'aboutus') && ($this->action != 'admin_aboutusform') && ($this->action != 'admin_termsform')) {
			$validPatron = $this->ValidatePatron->validatepatron();
			if(!$validPatron) {
				$this->Session->setFlash("Please follow proper guidelines before accessing our site.");
				$this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
			}
		}
	}

	function index() {
		$this->layout = 'home';
		$this->Question->recursive = 0;
		$this->set('questions', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'question'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('question', $this->Question->read(null, $id));
	}

	function admin_index() {
		$this->layout = 'admin';
		$this->Question->recursive = 0;
		$this->set('questions', $this->paginate());
	}

	function admin_view($id = null) {
		$this->layout = 'admin';
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'question'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('question', $this->Question->read(null, $id));
	}

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
}
?>
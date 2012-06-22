<?php
/*
 File Name : sections_controller.php
 File Description : Sections controller page
 Author : m68interactive
 */

class SectionsController extends AppController
{
	var $name = 'Sections';
	var $uses = array('Language','Section');
	var $components = array('Auth', 'Acl');
	
	/*
	 Function Name : beforeFilter
	 Desc : actions that needed before other functions are getting called
        */
	function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow('index', 'view');
	}
	
	/*
	 Function Name : admin_index
	 Desc : actions for faq sections page
        */
	function admin_index() {
		$this->layout = 'admin';
		$this->Section->recursive = 0;
		$this->set('sections', $this->paginate('Section'));
	}
	
	/*
	 Function Name : admin_view
	 Desc : actions for faq sections view
        */
	function admin_view($id = null) {
		$this->layout = 'admin';
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'section'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('section', $this->Section->read(null, $id));
	}
	
	/*
	 Function Name : admin_add
	 Desc : actions for faq sections add
        */
	function admin_add() {
		$this->layout = 'admin';
		$data = $this->Language->find('list', array('fields' => array('short_name', 'full_name')));
		$this->set('languages', $data);
		if (!empty($this->data)) {
			$this->Section->create();
			if ($this->Section->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'section'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'section'));
			}
		}
	}
	
	/*
	 Function Name : admin_edit
	 Desc : actions for faq sections edit
        */
	function admin_edit($id = null) {
		$this->layout = 'admin';
		$data = $this->Language->find('list', array('fields' => array('short_name', 'full_name')));
		$this->set('languages', $data);		
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'section'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Section->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'section'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'section'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Section->read(null, $id);
		}
	}
	
	/*
	 Function Name : admin_delete
	 Desc : actions for faq sections delete
        */
	function admin_delete($id = null) {
		$this->layout = 'admin';
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true), 'section'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Section->delete($id)) {
			$this->Session->setFlash(sprintf(__('%s deleted', true), 'Section'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true), 'Section'));
		$this->redirect(array('action' => 'index'));
	}
}
?>
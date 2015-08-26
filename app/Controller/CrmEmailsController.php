<?php
App::uses('AppController', 'Controller');
/**
 * CrmEmails Controller
 *
 * @property CrmEmail $CrmEmail
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class CrmEmailsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->CrmEmail->recursive = 0;
		$this->set('crmEmails', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->CrmEmail->exists($id)) {
			throw new NotFoundException(__('Invalid crm email'));
		}
		$options = array('conditions' => array('CrmEmail.' . $this->CrmEmail->primaryKey => $id));
		$this->set('crmEmail', $this->CrmEmail->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->CrmEmail->create();
			if ($this->CrmEmail->save($this->request->data)) {
				$this->Session->setFlash(__('The crm email has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The crm email could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->CrmEmail->exists($id)) {
			throw new NotFoundException(__('Invalid crm email'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->CrmEmail->save($this->request->data)) {
				$this->Session->setFlash(__('The crm email has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The crm email could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('CrmEmail.' . $this->CrmEmail->primaryKey => $id));
			$this->request->data = $this->CrmEmail->find('first', $options);
		}
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->CrmEmail->id = $id;
		if (!$this->CrmEmail->exists()) {
			throw new NotFoundException(__('Invalid crm email'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->CrmEmail->delete()) {
			$this->Session->setFlash(__('The crm email has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The crm email could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

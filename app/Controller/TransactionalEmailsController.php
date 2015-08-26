<?php
App::uses('AppController', 'Controller');
/**
 * TransactionalEmails Controller
 *
 * @property TransactionalEmail $TransactionalEmail
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class TransactionalEmailsController extends AppController {

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
		$this->TransactionalEmail->recursive = 0;
		$this->set('transactionalEmails', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->TransactionalEmail->exists($id)) {
			throw new NotFoundException(__('Invalid transactional email'));
		}
		$options = array('conditions' => array('TransactionalEmail.' . $this->TransactionalEmail->primaryKey => $id));
		$this->set('transactionalEmail', $this->TransactionalEmail->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->TransactionalEmail->create();
			if ($this->TransactionalEmail->save($this->request->data)) {
				$this->Session->setFlash(__('The transactional email has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transactional email could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
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
		if (!$this->TransactionalEmail->exists($id)) {
			throw new NotFoundException(__('Invalid transactional email'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->TransactionalEmail->save($this->request->data)) {
				$this->Session->setFlash(__('The transactional email has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transactional email could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('TransactionalEmail.' . $this->TransactionalEmail->primaryKey => $id));
			$this->request->data = $this->TransactionalEmail->find('first', $options);
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
		$this->TransactionalEmail->id = $id;
		if (!$this->TransactionalEmail->exists()) {
			throw new NotFoundException(__('Invalid transactional email'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->TransactionalEmail->delete()) {
			$this->Session->setFlash(__('The transactional email has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The transactional email could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

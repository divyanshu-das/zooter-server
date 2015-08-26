<?php
App::uses('AppController', 'Controller');
/**
 * Singers Controller
 *
 * @property Singer $Singer
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class SingersController extends AppController {

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
		$this->Singer->recursive = 0;
		$this->set('singers', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Singer->exists($id)) {
			throw new NotFoundException(__('Invalid singer'));
		}
		$options = array('conditions' => array('Singer.' . $this->Singer->primaryKey => $id));
		$this->set('singer', $this->Singer->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Singer->create();
			if ($this->Singer->save($this->request->data)) {
				$this->Session->setFlash(__('The singer has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The singer could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
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
		if (!$this->Singer->exists($id)) {
			throw new NotFoundException(__('Invalid singer'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Singer->save($this->request->data)) {
				$this->Session->setFlash(__('The singer has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The singer could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Singer.' . $this->Singer->primaryKey => $id));
			$this->request->data = $this->Singer->find('first', $options);
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
		$this->Singer->id = $id;
		if (!$this->Singer->exists()) {
			throw new NotFoundException(__('Invalid singer'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Singer->delete()) {
			$this->Session->setFlash(__('The singer has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The singer could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

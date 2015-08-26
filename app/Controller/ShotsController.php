<?php
App::uses('AppController', 'Controller');
/**
 * Shots Controller
 *
 * @property Shot $Shot
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class ShotsController extends AppController {

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
		$this->Shot->recursive = 0;
		$this->set('shots', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Shot->exists($id)) {
			throw new NotFoundException(__('Invalid shot'));
		}
		$options = array('conditions' => array('Shot.' . $this->Shot->primaryKey => $id));
		$this->set('shot', $this->Shot->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Shot->create();
			if ($this->Shot->save($this->request->data)) {
				$this->Session->setFlash(__('The shot has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The shot could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
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
		if (!$this->Shot->exists($id)) {
			throw new NotFoundException(__('Invalid shot'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Shot->save($this->request->data)) {
				$this->Session->setFlash(__('The shot has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The shot could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Shot.' . $this->Shot->primaryKey => $id));
			$this->request->data = $this->Shot->find('first', $options);
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
		$this->Shot->id = $id;
		if (!$this->Shot->exists()) {
			throw new NotFoundException(__('Invalid shot'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Shot->delete()) {
			$this->Session->setFlash(__('The shot has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The shot could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

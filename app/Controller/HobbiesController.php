<?php
App::uses('AppController', 'Controller');
/**
 * Hobbies Controller
 *
 * @property Hobby $Hobby
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class HobbiesController extends AppController {

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
		$this->Hobby->recursive = 0;
		$this->set('hobbies', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Hobby->exists($id)) {
			throw new NotFoundException(__('Invalid hobby'));
		}
		$options = array('conditions' => array('Hobby.' . $this->Hobby->primaryKey => $id));
		$this->set('hobby', $this->Hobby->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Hobby->create();
			if ($this->Hobby->save($this->request->data)) {
				$this->Session->setFlash(__('The hobby has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The hobby could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
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
		if (!$this->Hobby->exists($id)) {
			throw new NotFoundException(__('Invalid hobby'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Hobby->save($this->request->data)) {
				$this->Session->setFlash(__('The hobby has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The hobby could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Hobby.' . $this->Hobby->primaryKey => $id));
			$this->request->data = $this->Hobby->find('first', $options);
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
		$this->Hobby->id = $id;
		if (!$this->Hobby->exists()) {
			throw new NotFoundException(__('Invalid hobby'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Hobby->delete()) {
			$this->Session->setFlash(__('The hobby has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The hobby could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

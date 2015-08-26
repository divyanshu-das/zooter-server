<?php
App::uses('AppController', 'Controller');
/**
 * PlacesCosts Controller
 *
 * @property PlacesCost $PlacesCost
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class PlacesCostsController extends AppController {

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
		$this->PlacesCost->recursive = 0;
		$this->set('placesCosts', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->PlacesCost->exists($id)) {
			throw new NotFoundException(__('Invalid places cost'));
		}
		$options = array('conditions' => array('PlacesCost.' . $this->PlacesCost->primaryKey => $id));
		$this->set('placesCost', $this->PlacesCost->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->PlacesCost->create();
			if ($this->PlacesCost->save($this->request->data)) {
				$this->Session->setFlash(__('The places cost has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The places cost could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$places = $this->PlacesCost->Place->find('list');
		$this->set(compact('places'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->PlacesCost->exists($id)) {
			throw new NotFoundException(__('Invalid places cost'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->PlacesCost->save($this->request->data)) {
				$this->Session->setFlash(__('The places cost has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The places cost could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('PlacesCost.' . $this->PlacesCost->primaryKey => $id));
			$this->request->data = $this->PlacesCost->find('first', $options);
		}
		$places = $this->PlacesCost->Place->find('list');
		$this->set(compact('places'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->PlacesCost->id = $id;
		if (!$this->PlacesCost->exists()) {
			throw new NotFoundException(__('Invalid places cost'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->PlacesCost->delete()) {
			$this->Session->setFlash(__('The places cost has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The places cost could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

<?php
App::uses('AppController', 'Controller');
/**
 * PlaceFacilities Controller
 *
 * @property PlaceFacility $PlaceFacility
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class PlaceFacilitiesController extends AppController {

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
		$this->PlaceFacility->recursive = 0;
		$this->set('placeFacilities', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->PlaceFacility->exists($id)) {
			throw new NotFoundException(__('Invalid place facility'));
		}
		$options = array('conditions' => array('PlaceFacility.' . $this->PlaceFacility->primaryKey => $id));
		$this->set('placeFacility', $this->PlaceFacility->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->PlaceFacility->create();
			if ($this->PlaceFacility->save($this->request->data)) {
				$this->Session->setFlash(__('The place facility has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The place facility could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$places = $this->PlaceFacility->Place->find('list');
		$grounds = $this->PlaceFacility->Ground->find('list');
		$this->set(compact('places', 'grounds'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->PlaceFacility->exists($id)) {
			throw new NotFoundException(__('Invalid place facility'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->PlaceFacility->save($this->request->data)) {
				$this->Session->setFlash(__('The place facility has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The place facility could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('PlaceFacility.' . $this->PlaceFacility->primaryKey => $id));
			$this->request->data = $this->PlaceFacility->find('first', $options);
		}
		$places = $this->PlaceFacility->Place->find('list');
		$grounds = $this->PlaceFacility->Ground->find('list');
		$this->set(compact('places', 'grounds'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->PlaceFacility->id = $id;
		if (!$this->PlaceFacility->exists()) {
			throw new NotFoundException(__('Invalid place facility'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->PlaceFacility->delete()) {
			$this->Session->setFlash(__('The place facility has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The place facility could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

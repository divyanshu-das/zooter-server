<?php
App::uses('AppController', 'Controller');
/**
 * PlaceTimings Controller
 *
 * @property PlaceTiming $PlaceTiming
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class PlaceTimingsController extends AppController {

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
		$this->PlaceTiming->recursive = 0;
		$this->set('placeTimings', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->PlaceTiming->exists($id)) {
			throw new NotFoundException(__('Invalid place timing'));
		}
		$options = array('conditions' => array('PlaceTiming.' . $this->PlaceTiming->primaryKey => $id));
		$this->set('placeTiming', $this->PlaceTiming->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->PlaceTiming->create();
			if ($this->PlaceTiming->save($this->request->data)) {
				$this->Session->setFlash(__('The place timing has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The place timing could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$places = $this->PlaceTiming->Place->find('list');
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
		if (!$this->PlaceTiming->exists($id)) {
			throw new NotFoundException(__('Invalid place timing'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->PlaceTiming->save($this->request->data)) {
				$this->Session->setFlash(__('The place timing has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The place timing could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('PlaceTiming.' . $this->PlaceTiming->primaryKey => $id));
			$this->request->data = $this->PlaceTiming->find('first', $options);
		}
		$places = $this->PlaceTiming->Place->find('list');
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
		$this->PlaceTiming->id = $id;
		if (!$this->PlaceTiming->exists()) {
			throw new NotFoundException(__('Invalid place timing'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->PlaceTiming->delete()) {
			$this->Session->setFlash(__('The place timing has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The place timing could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

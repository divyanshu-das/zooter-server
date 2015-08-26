<?php
App::uses('AppController', 'Controller');
/**
 * PlaceRatings Controller
 *
 * @property PlaceRating $PlaceRating
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class PlaceRatingsController extends AppController {

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
		$this->PlaceRating->recursive = 0;
		$this->set('placeRatings', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->PlaceRating->exists($id)) {
			throw new NotFoundException(__('Invalid place rating'));
		}
		$options = array('conditions' => array('PlaceRating.' . $this->PlaceRating->primaryKey => $id));
		$this->set('placeRating', $this->PlaceRating->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->PlaceRating->create();
			if ($this->PlaceRating->save($this->request->data)) {
				$this->Session->setFlash(__('The place rating has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The place rating could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$places = $this->PlaceRating->Place->find('list');
		$users = $this->PlaceRating->User->find('list');
		$this->set(compact('places', 'users'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->PlaceRating->exists($id)) {
			throw new NotFoundException(__('Invalid place rating'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->PlaceRating->save($this->request->data)) {
				$this->Session->setFlash(__('The place rating has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The place rating could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('PlaceRating.' . $this->PlaceRating->primaryKey => $id));
			$this->request->data = $this->PlaceRating->find('first', $options);
		}
		$places = $this->PlaceRating->Place->find('list');
		$users = $this->PlaceRating->User->find('list');
		$this->set(compact('places', 'users'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->PlaceRating->id = $id;
		if (!$this->PlaceRating->exists()) {
			throw new NotFoundException(__('Invalid place rating'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->PlaceRating->delete()) {
			$this->Session->setFlash(__('The place rating has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The place rating could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

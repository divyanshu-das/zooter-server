<?php
App::uses('AppController', 'Controller');
/**
 * PlaceCoaches Controller
 *
 * @property PlaceCoach $PlaceCoach
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class PlaceCoachesController extends AppController {

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
		$this->PlaceCoach->recursive = 0;
		$this->set('placeCoaches', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->PlaceCoach->exists($id)) {
			throw new NotFoundException(__('Invalid place coach'));
		}
		$options = array('conditions' => array('PlaceCoach.' . $this->PlaceCoach->primaryKey => $id));
		$this->set('placeCoach', $this->PlaceCoach->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->PlaceCoach->create();
			if ($this->PlaceCoach->save($this->request->data)) {
				$this->Session->setFlash(__('The place coach has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The place coach could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$places = $this->PlaceCoach->Place->find('list');
		$users = $this->PlaceCoach->User->find('list');
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
		if (!$this->PlaceCoach->exists($id)) {
			throw new NotFoundException(__('Invalid place coach'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->PlaceCoach->save($this->request->data)) {
				$this->Session->setFlash(__('The place coach has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The place coach could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('PlaceCoach.' . $this->PlaceCoach->primaryKey => $id));
			$this->request->data = $this->PlaceCoach->find('first', $options);
		}
		$places = $this->PlaceCoach->Place->find('list');
		$users = $this->PlaceCoach->User->find('list');
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
		$this->PlaceCoach->id = $id;
		if (!$this->PlaceCoach->exists()) {
			throw new NotFoundException(__('Invalid place coach'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->PlaceCoach->delete()) {
			$this->Session->setFlash(__('The place coach has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The place coach could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

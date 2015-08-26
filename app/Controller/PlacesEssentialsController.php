<?php
App::uses('AppController', 'Controller');
/**
 * PlacesEssentials Controller
 *
 * @property PlacesEssential $PlacesEssential
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class PlacesEssentialsController extends AppController {

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
		$this->PlacesEssential->recursive = 0;
		$this->set('placesEssentials', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->PlacesEssential->exists($id)) {
			throw new NotFoundException(__('Invalid places essential'));
		}
		$options = array('conditions' => array('PlacesEssential.' . $this->PlacesEssential->primaryKey => $id));
		$this->set('placesEssential', $this->PlacesEssential->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->PlacesEssential->create();
			if ($this->PlacesEssential->save($this->request->data)) {
				$this->Session->setFlash(__('The places essential has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The places essential could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$places = $this->PlacesEssential->Place->find('list');
		$locations = $this->PlacesEssential->Location->find('list');
		$this->set(compact('places', 'locations'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->PlacesEssential->exists($id)) {
			throw new NotFoundException(__('Invalid places essential'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->PlacesEssential->save($this->request->data)) {
				$this->Session->setFlash(__('The places essential has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The places essential could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('PlacesEssential.' . $this->PlacesEssential->primaryKey => $id));
			$this->request->data = $this->PlacesEssential->find('first', $options);
		}
		$places = $this->PlacesEssential->Place->find('list');
		$locations = $this->PlacesEssential->Location->find('list');
		$this->set(compact('places', 'locations'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->PlacesEssential->id = $id;
		if (!$this->PlacesEssential->exists()) {
			throw new NotFoundException(__('Invalid places essential'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->PlacesEssential->delete()) {
			$this->Session->setFlash(__('The places essential has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The places essential could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

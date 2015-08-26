<?php
App::uses('AppController', 'Controller');
/**
 * PlacesImages Controller
 *
 * @property PlacesImage $PlacesImage
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class PlacesImagesController extends AppController {

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
		$this->PlacesImage->recursive = 0;
		$this->set('placesImages', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->PlacesImage->exists($id)) {
			throw new NotFoundException(__('Invalid places image'));
		}
		$options = array('conditions' => array('PlacesImage.' . $this->PlacesImage->primaryKey => $id));
		$this->set('placesImage', $this->PlacesImage->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->PlacesImage->create();
			if ($this->PlacesImage->save($this->request->data)) {
				$this->Session->setFlash(__('The places image has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The places image could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$places = $this->PlacesImage->Place->find('list');
		$images = $this->PlacesImage->Image->find('list');
		$this->set(compact('places', 'images'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->PlacesImage->exists($id)) {
			throw new NotFoundException(__('Invalid places image'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->PlacesImage->save($this->request->data)) {
				$this->Session->setFlash(__('The places image has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The places image could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('PlacesImage.' . $this->PlacesImage->primaryKey => $id));
			$this->request->data = $this->PlacesImage->find('first', $options);
		}
		$places = $this->PlacesImage->Place->find('list');
		$images = $this->PlacesImage->Image->find('list');
		$this->set(compact('places', 'images'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->PlacesImage->id = $id;
		if (!$this->PlacesImage->exists()) {
			throw new NotFoundException(__('Invalid places image'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->PlacesImage->delete()) {
			$this->Session->setFlash(__('The places image has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The places image could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

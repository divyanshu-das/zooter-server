<?php
App::uses('AppController', 'Controller');
/**
 * FavoritePlaces Controller
 *
 * @property FavoritePlace $FavoritePlace
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class FavoritePlacesController extends AppController {

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
		$this->FavoritePlace->recursive = 0;
		$this->set('favoritePlaces', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->FavoritePlace->exists($id)) {
			throw new NotFoundException(__('Invalid favorite place'));
		}
		$options = array('conditions' => array('FavoritePlace.' . $this->FavoritePlace->primaryKey => $id));
		$this->set('favoritePlace', $this->FavoritePlace->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->FavoritePlace->create();
			if ($this->FavoritePlace->save($this->request->data)) {
				$this->Session->setFlash(__('The favorite place has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The favorite place could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$users = $this->FavoritePlace->User->find('list');
		$places = $this->FavoritePlace->Place->find('list');
		$this->set(compact('users', 'places'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->FavoritePlace->exists($id)) {
			throw new NotFoundException(__('Invalid favorite place'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->FavoritePlace->save($this->request->data)) {
				$this->Session->setFlash(__('The favorite place has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The favorite place could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('FavoritePlace.' . $this->FavoritePlace->primaryKey => $id));
			$this->request->data = $this->FavoritePlace->find('first', $options);
		}
		$users = $this->FavoritePlace->User->find('list');
		$places = $this->FavoritePlace->Place->find('list');
		$this->set(compact('users', 'places'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->FavoritePlace->id = $id;
		if (!$this->FavoritePlace->exists()) {
			throw new NotFoundException(__('Invalid favorite place'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->FavoritePlace->delete()) {
			$this->Session->setFlash(__('The favorite place has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The favorite place could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

<?php
App::uses('AppController', 'Controller');
/**
 * AlbumContributors Controller
 *
 * @property AlbumContributor $AlbumContributor
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class AlbumContributorsController extends AppController {

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
		$this->AlbumContributor->recursive = 0;
		$this->set('albumContributors', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->AlbumContributor->exists($id)) {
			throw new NotFoundException(__('Invalid album contributor'));
		}
		$options = array('conditions' => array('AlbumContributor.' . $this->AlbumContributor->primaryKey => $id));
		$this->set('albumContributor', $this->AlbumContributor->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->AlbumContributor->create();
			if ($this->AlbumContributor->save($this->request->data)) {
				$this->Session->setFlash(__('The album contributor has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The album contributor could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$albums = $this->AlbumContributor->Album->find('list');
		$users = $this->AlbumContributor->User->find('list');
		$this->set(compact('albums', 'users'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->AlbumContributor->exists($id)) {
			throw new NotFoundException(__('Invalid album contributor'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->AlbumContributor->save($this->request->data)) {
				$this->Session->setFlash(__('The album contributor has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The album contributor could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('AlbumContributor.' . $this->AlbumContributor->primaryKey => $id));
			$this->request->data = $this->AlbumContributor->find('first', $options);
		}
		$albums = $this->AlbumContributor->Album->find('list');
		$users = $this->AlbumContributor->User->find('list');
		$this->set(compact('albums', 'users'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->AlbumContributor->id = $id;
		if (!$this->AlbumContributor->exists()) {
			throw new NotFoundException(__('Invalid album contributor'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->AlbumContributor->delete()) {
			$this->Session->setFlash(__('The album contributor has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The album contributor could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

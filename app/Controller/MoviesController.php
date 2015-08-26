<?php
App::uses('AppController', 'Controller');
/**
 * Movies Controller
 *
 * @property Movie $Movie
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class MoviesController extends AppController {

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
		$this->Movie->recursive = 0;
		$this->set('movies', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Movie->exists($id)) {
			throw new NotFoundException(__('Invalid movie'));
		}
		$options = array('conditions' => array('Movie.' . $this->Movie->primaryKey => $id));
		$this->set('movie', $this->Movie->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Movie->create();
			if ($this->Movie->save($this->request->data)) {
				$this->Session->setFlash(__('The movie has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The movie could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
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
		if (!$this->Movie->exists($id)) {
			throw new NotFoundException(__('Invalid movie'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Movie->save($this->request->data)) {
				$this->Session->setFlash(__('The movie has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The movie could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Movie.' . $this->Movie->primaryKey => $id));
			$this->request->data = $this->Movie->find('first', $options);
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
		$this->Movie->id = $id;
		if (!$this->Movie->exists()) {
			throw new NotFoundException(__('Invalid movie'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Movie->delete()) {
			$this->Session->setFlash(__('The movie has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The movie could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

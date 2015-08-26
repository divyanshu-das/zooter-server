<?php
App::uses('AppController', 'Controller');
/**
 * ImageComments Controller
 *
 * @property ImageComment $ImageComment
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class ImageCommentsController extends AppController {

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
		$this->ImageComment->recursive = 0;
		$this->set('imageComments', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->ImageComment->exists($id)) {
			throw new NotFoundException(__('Invalid image comment'));
		}
		$options = array('conditions' => array('ImageComment.' . $this->ImageComment->primaryKey => $id));
		$this->set('imageComment', $this->ImageComment->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->ImageComment->create();
			if ($this->ImageComment->save($this->request->data)) {
				$this->Session->setFlash(__('The image comment has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The image comment could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$images = $this->ImageComment->Image->find('list');
		$users = $this->ImageComment->User->find('list');
		$this->set(compact('images', 'users'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->ImageComment->exists($id)) {
			throw new NotFoundException(__('Invalid image comment'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->ImageComment->save($this->request->data)) {
				$this->Session->setFlash(__('The image comment has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The image comment could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('ImageComment.' . $this->ImageComment->primaryKey => $id));
			$this->request->data = $this->ImageComment->find('first', $options);
		}
		$images = $this->ImageComment->Image->find('list');
		$users = $this->ImageComment->User->find('list');
		$this->set(compact('images', 'users'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->ImageComment->id = $id;
		if (!$this->ImageComment->exists()) {
			throw new NotFoundException(__('Invalid image comment'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ImageComment->delete()) {
			$this->Session->setFlash(__('The image comment has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The image comment could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

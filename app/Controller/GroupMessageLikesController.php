<?php
App::uses('AppController', 'Controller');
/**
 * GroupMessageLikes Controller
 *
 * @property GroupMessageLike $GroupMessageLike
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class GroupMessageLikesController extends AppController {

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
		$this->GroupMessageLike->recursive = 0;
		$this->set('groupMessageLikes', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->GroupMessageLike->exists($id)) {
			throw new NotFoundException(__('Invalid group message like'));
		}
		$options = array('conditions' => array('GroupMessageLike.' . $this->GroupMessageLike->primaryKey => $id));
		$this->set('groupMessageLike', $this->GroupMessageLike->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->GroupMessageLike->create();
			if ($this->GroupMessageLike->save($this->request->data)) {
				$this->Session->setFlash(__('The group message like has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group message like could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$users = $this->GroupMessageLike->User->find('list');
		$groupMessages = $this->GroupMessageLike->GroupMessage->find('list');
		$this->set(compact('users', 'groupMessages'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->GroupMessageLike->exists($id)) {
			throw new NotFoundException(__('Invalid group message like'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->GroupMessageLike->save($this->request->data)) {
				$this->Session->setFlash(__('The group message like has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group message like could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('GroupMessageLike.' . $this->GroupMessageLike->primaryKey => $id));
			$this->request->data = $this->GroupMessageLike->find('first', $options);
		}
		$users = $this->GroupMessageLike->User->find('list');
		$groupMessages = $this->GroupMessageLike->GroupMessage->find('list');
		$this->set(compact('users', 'groupMessages'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->GroupMessageLike->id = $id;
		if (!$this->GroupMessageLike->exists()) {
			throw new NotFoundException(__('Invalid group message like'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->GroupMessageLike->delete()) {
			$this->Session->setFlash(__('The group message like has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The group message like could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

<?php
App::uses('AppController', 'Controller');
/**
 * GroupMessageComments Controller
 *
 * @property GroupMessageComment $GroupMessageComment
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class GroupMessageCommentsController extends AppController {

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
		$this->GroupMessageComment->recursive = 0;
		$this->set('groupMessageComments', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->GroupMessageComment->exists($id)) {
			throw new NotFoundException(__('Invalid group message comment'));
		}
		$options = array('conditions' => array('GroupMessageComment.' . $this->GroupMessageComment->primaryKey => $id));
		$this->set('groupMessageComment', $this->GroupMessageComment->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->GroupMessageComment->create();
			if ($this->GroupMessageComment->save($this->request->data)) {
				$this->Session->setFlash(__('The group message comment has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group message comment could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$users = $this->GroupMessageComment->User->find('list');
		$groupMessages = $this->GroupMessageComment->GroupMessage->find('list');
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
		if (!$this->GroupMessageComment->exists($id)) {
			throw new NotFoundException(__('Invalid group message comment'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->GroupMessageComment->save($this->request->data)) {
				$this->Session->setFlash(__('The group message comment has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group message comment could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('GroupMessageComment.' . $this->GroupMessageComment->primaryKey => $id));
			$this->request->data = $this->GroupMessageComment->find('first', $options);
		}
		$users = $this->GroupMessageComment->User->find('list');
		$groupMessages = $this->GroupMessageComment->GroupMessage->find('list');
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
		$this->GroupMessageComment->id = $id;
		if (!$this->GroupMessageComment->exists()) {
			throw new NotFoundException(__('Invalid group message comment'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->GroupMessageComment->delete()) {
			$this->Session->setFlash(__('The group message comment has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The group message comment could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

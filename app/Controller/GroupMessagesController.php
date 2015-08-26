<?php
App::uses('AppController', 'Controller');
/**
 * GroupMessages Controller
 *
 * @property GroupMessage $GroupMessage
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class GroupMessagesController extends AppController {

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
		$this->GroupMessage->recursive = 0;
		$this->set('groupMessages', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->GroupMessage->exists($id)) {
			throw new NotFoundException(__('Invalid group message'));
		}
		$options = array('conditions' => array('GroupMessage.' . $this->GroupMessage->primaryKey => $id));
		$this->set('groupMessage', $this->GroupMessage->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->GroupMessage->create();
			if ($this->GroupMessage->save($this->request->data)) {
				$this->Session->setFlash(__('The group message has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group message could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$users = $this->GroupMessage->User->find('list');
		$groups = $this->GroupMessage->Group->find('list');
		$this->set(compact('users', 'groups'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->GroupMessage->exists($id)) {
			throw new NotFoundException(__('Invalid group message'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->GroupMessage->save($this->request->data)) {
				$this->Session->setFlash(__('The group message has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group message could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('GroupMessage.' . $this->GroupMessage->primaryKey => $id));
			$this->request->data = $this->GroupMessage->find('first', $options);
		}
		$users = $this->GroupMessage->User->find('list');
		$groups = $this->GroupMessage->Group->find('list');
		$this->set(compact('users', 'groups'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->GroupMessage->id = $id;
		if (!$this->GroupMessage->exists()) {
			throw new NotFoundException(__('Invalid group message'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->GroupMessage->delete()) {
			$this->Session->setFlash(__('The group message has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The group message could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

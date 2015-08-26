<?php
App::uses('AppController', 'Controller');
/**
 * UserFollowers Controller
 *
 * @property UserFollower $UserFollower
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class UserFollowersController extends AppController {

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
		$this->UserFollower->recursive = 0;
		$this->set('userFollowers', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->UserFollower->exists($id)) {
			throw new NotFoundException(__('Invalid user follower'));
		}
		$options = array('conditions' => array('UserFollower.' . $this->UserFollower->primaryKey => $id));
		$this->set('userFollower', $this->UserFollower->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->UserFollower->create();
			if ($this->UserFollower->save($this->request->data)) {
				$this->Session->setFlash(__('The user follower has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user follower could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$userFroms = $this->UserFollower->UserFrom->find('list');
		$userTos = $this->UserFollower->UserTo->find('list');
		$this->set(compact('userFroms', 'userTos'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->UserFollower->exists($id)) {
			throw new NotFoundException(__('Invalid user follower'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->UserFollower->save($this->request->data)) {
				$this->Session->setFlash(__('The user follower has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user follower could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('UserFollower.' . $this->UserFollower->primaryKey => $id));
			$this->request->data = $this->UserFollower->find('first', $options);
		}
		$userFroms = $this->UserFollower->UserFrom->find('list');
		$userTos = $this->UserFollower->UserTo->find('list');
		$this->set(compact('userFroms', 'userTos'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->UserFollower->id = $id;
		if (!$this->UserFollower->exists()) {
			throw new NotFoundException(__('Invalid user follower'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->UserFollower->delete()) {
			$this->Session->setFlash(__('The user follower has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The user follower could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

<?php
App::uses('AppController', 'Controller');
/**
 * VerifiedUsers Controller
 *
 * @property VerifiedUser $VerifiedUser
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class VerifiedUsersController extends AppController {

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
		$this->VerifiedUser->recursive = 0;
		$this->set('verifiedUsers', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->VerifiedUser->exists($id)) {
			throw new NotFoundException(__('Invalid verified user'));
		}
		$options = array('conditions' => array('VerifiedUser.' . $this->VerifiedUser->primaryKey => $id));
		$this->set('verifiedUser', $this->VerifiedUser->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->VerifiedUser->create();
			if ($this->VerifiedUser->save($this->request->data)) {
				$this->Session->setFlash(__('The verified user has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The verified user could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
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
		if (!$this->VerifiedUser->exists($id)) {
			throw new NotFoundException(__('Invalid verified user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->VerifiedUser->save($this->request->data)) {
				$this->Session->setFlash(__('The verified user has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The verified user could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('VerifiedUser.' . $this->VerifiedUser->primaryKey => $id));
			$this->request->data = $this->VerifiedUser->find('first', $options);
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
		$this->VerifiedUser->id = $id;
		if (!$this->VerifiedUser->exists()) {
			throw new NotFoundException(__('Invalid verified user'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->VerifiedUser->delete()) {
			$this->Session->setFlash(__('The verified user has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The verified user could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

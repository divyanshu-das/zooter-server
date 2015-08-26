<?php
App::uses('AppController', 'Controller');
/**
 * FanClubMembers Controller
 *
 * @property FanClubMember $FanClubMember
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class FanClubMembersController extends AppController {

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
		$this->FanClubMember->recursive = 0;
		$this->set('fanClubMembers', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->FanClubMember->exists($id)) {
			throw new NotFoundException(__('Invalid fan club member'));
		}
		$options = array('conditions' => array('FanClubMember.' . $this->FanClubMember->primaryKey => $id));
		$this->set('fanClubMember', $this->FanClubMember->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->FanClubMember->create();
			if ($this->FanClubMember->save($this->request->data)) {
				$this->Session->setFlash(__('The fan club member has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The fan club member could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$fanClubs = $this->FanClubMember->FanClub->find('list');
		$users = $this->FanClubMember->User->find('list');
		$this->set(compact('fanClubs', 'users'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->FanClubMember->exists($id)) {
			throw new NotFoundException(__('Invalid fan club member'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->FanClubMember->save($this->request->data)) {
				$this->Session->setFlash(__('The fan club member has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The fan club member could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('FanClubMember.' . $this->FanClubMember->primaryKey => $id));
			$this->request->data = $this->FanClubMember->find('first', $options);
		}
		$fanClubs = $this->FanClubMember->FanClub->find('list');
		$users = $this->FanClubMember->User->find('list');
		$this->set(compact('fanClubs', 'users'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->FanClubMember->id = $id;
		if (!$this->FanClubMember->exists()) {
			throw new NotFoundException(__('Invalid fan club member'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->FanClubMember->delete()) {
			$this->Session->setFlash(__('The fan club member has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The fan club member could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

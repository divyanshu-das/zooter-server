<?php
App::uses('AppController', 'Controller');
/**
 * FanClubSpecials Controller
 *
 * @property FanClubSpecial $FanClubSpecial
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class FanClubSpecialsController extends AppController {

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
		$this->FanClubSpecial->recursive = 0;
		$this->set('fanClubSpecials', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->FanClubSpecial->exists($id)) {
			throw new NotFoundException(__('Invalid fan club special'));
		}
		$options = array('conditions' => array('FanClubSpecial.' . $this->FanClubSpecial->primaryKey => $id));
		$this->set('fanClubSpecial', $this->FanClubSpecial->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->FanClubSpecial->create();
			if ($this->FanClubSpecial->save($this->request->data)) {
				$this->Session->setFlash(__('The fan club special has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The fan club special could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$fanClubs = $this->FanClubSpecial->FanClub->find('list');
		$users = $this->FanClubSpecial->User->find('list');
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
		if (!$this->FanClubSpecial->exists($id)) {
			throw new NotFoundException(__('Invalid fan club special'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->FanClubSpecial->save($this->request->data)) {
				$this->Session->setFlash(__('The fan club special has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The fan club special could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('FanClubSpecial.' . $this->FanClubSpecial->primaryKey => $id));
			$this->request->data = $this->FanClubSpecial->find('first', $options);
		}
		$fanClubs = $this->FanClubSpecial->FanClub->find('list');
		$users = $this->FanClubSpecial->User->find('list');
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
		$this->FanClubSpecial->id = $id;
		if (!$this->FanClubSpecial->exists()) {
			throw new NotFoundException(__('Invalid fan club special'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->FanClubSpecial->delete()) {
			$this->Session->setFlash(__('The fan club special has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The fan club special could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

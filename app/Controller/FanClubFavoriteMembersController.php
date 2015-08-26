<?php
App::uses('AppController', 'Controller');
/**
 * FanClubFavoriteMembers Controller
 *
 * @property FanClubFavoriteMember $FanClubFavoriteMember
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class FanClubFavoriteMembersController extends AppController {

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
		$this->FanClubFavoriteMember->recursive = 0;
		$this->set('fanClubFavoriteMembers', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->FanClubFavoriteMember->exists($id)) {
			throw new NotFoundException(__('Invalid fan club favorite member'));
		}
		$options = array('conditions' => array('FanClubFavoriteMember.' . $this->FanClubFavoriteMember->primaryKey => $id));
		$this->set('fanClubFavoriteMember', $this->FanClubFavoriteMember->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->FanClubFavoriteMember->create();
			if ($this->FanClubFavoriteMember->save($this->request->data)) {
				$this->Session->setFlash(__('The fan club favorite member has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The fan club favorite member could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$fanClubs = $this->FanClubFavoriteMember->FanClub->find('list');
		$users = $this->FanClubFavoriteMember->User->find('list');
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
		if (!$this->FanClubFavoriteMember->exists($id)) {
			throw new NotFoundException(__('Invalid fan club favorite member'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->FanClubFavoriteMember->save($this->request->data)) {
				$this->Session->setFlash(__('The fan club favorite member has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The fan club favorite member could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('FanClubFavoriteMember.' . $this->FanClubFavoriteMember->primaryKey => $id));
			$this->request->data = $this->FanClubFavoriteMember->find('first', $options);
		}
		$fanClubs = $this->FanClubFavoriteMember->FanClub->find('list');
		$users = $this->FanClubFavoriteMember->User->find('list');
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
		$this->FanClubFavoriteMember->id = $id;
		if (!$this->FanClubFavoriteMember->exists()) {
			throw new NotFoundException(__('Invalid fan club favorite member'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->FanClubFavoriteMember->delete()) {
			$this->Session->setFlash(__('The fan club favorite member has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The fan club favorite member could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

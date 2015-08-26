<?php
App::uses('AppController', 'Controller');
/**
 * FanClubFavorites Controller
 *
 * @property FanClubFavorite $FanClubFavorite
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class FanClubFavoritesController extends AppController {

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
		$this->FanClubFavorite->recursive = 0;
		$this->set('fanClubFavorites', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->FanClubFavorite->exists($id)) {
			throw new NotFoundException(__('Invalid fan club favorite'));
		}
		$options = array('conditions' => array('FanClubFavorite.' . $this->FanClubFavorite->primaryKey => $id));
		$this->set('fanClubFavorite', $this->FanClubFavorite->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->FanClubFavorite->create();
			if ($this->FanClubFavorite->save($this->request->data)) {
				$this->Session->setFlash(__('The fan club favorite has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The fan club favorite could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$fanClubs = $this->FanClubFavorite->FanClub->find('list');
		$this->set(compact('fanClubs'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->FanClubFavorite->exists($id)) {
			throw new NotFoundException(__('Invalid fan club favorite'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->FanClubFavorite->save($this->request->data)) {
				$this->Session->setFlash(__('The fan club favorite has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The fan club favorite could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('FanClubFavorite.' . $this->FanClubFavorite->primaryKey => $id));
			$this->request->data = $this->FanClubFavorite->find('first', $options);
		}
		$fanClubs = $this->FanClubFavorite->FanClub->find('list');
		$this->set(compact('fanClubs'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->FanClubFavorite->id = $id;
		if (!$this->FanClubFavorite->exists()) {
			throw new NotFoundException(__('Invalid fan club favorite'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->FanClubFavorite->delete()) {
			$this->Session->setFlash(__('The fan club favorite has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The fan club favorite could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

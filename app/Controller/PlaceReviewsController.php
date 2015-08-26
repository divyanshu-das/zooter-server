<?php
App::uses('AppController', 'Controller');
/**
 * PlaceReviews Controller
 *
 * @property PlaceReview $PlaceReview
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class PlaceReviewsController extends AppController {

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
		$this->PlaceReview->recursive = 0;
		$this->set('placeReviews', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->PlaceReview->exists($id)) {
			throw new NotFoundException(__('Invalid place review'));
		}
		$options = array('conditions' => array('PlaceReview.' . $this->PlaceReview->primaryKey => $id));
		$this->set('placeReview', $this->PlaceReview->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->PlaceReview->create();
			if ($this->PlaceReview->save($this->request->data)) {
				$this->Session->setFlash(__('The place review has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The place review could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$places = $this->PlaceReview->Place->find('list');
		$users = $this->PlaceReview->User->find('list');
		$this->set(compact('places', 'users'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->PlaceReview->exists($id)) {
			throw new NotFoundException(__('Invalid place review'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->PlaceReview->save($this->request->data)) {
				$this->Session->setFlash(__('The place review has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The place review could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('PlaceReview.' . $this->PlaceReview->primaryKey => $id));
			$this->request->data = $this->PlaceReview->find('first', $options);
		}
		$places = $this->PlaceReview->Place->find('list');
		$users = $this->PlaceReview->User->find('list');
		$this->set(compact('places', 'users'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->PlaceReview->id = $id;
		if (!$this->PlaceReview->exists()) {
			throw new NotFoundException(__('Invalid place review'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->PlaceReview->delete()) {
			$this->Session->setFlash(__('The place review has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The place review could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

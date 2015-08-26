<?php
App::uses('AppController', 'Controller');
/**
 * Musics Controller
 *
 * @property Music $Music
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class MusicsController extends AppController {

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
		$this->Music->recursive = 0;
		$this->set('musics', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Music->exists($id)) {
			throw new NotFoundException(__('Invalid music'));
		}
		$options = array('conditions' => array('Music.' . $this->Music->primaryKey => $id));
		$this->set('music', $this->Music->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Music->create();
			if ($this->Music->save($this->request->data)) {
				$this->Session->setFlash(__('The music has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The music could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
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
		if (!$this->Music->exists($id)) {
			throw new NotFoundException(__('Invalid music'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Music->save($this->request->data)) {
				$this->Session->setFlash(__('The music has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The music could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Music.' . $this->Music->primaryKey => $id));
			$this->request->data = $this->Music->find('first', $options);
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
		$this->Music->id = $id;
		if (!$this->Music->exists()) {
			throw new NotFoundException(__('Invalid music'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Music->delete()) {
			$this->Session->setFlash(__('The music has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The music could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

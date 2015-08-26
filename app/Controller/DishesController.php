<?php
App::uses('AppController', 'Controller');
/**
 * Dishes Controller
 *
 * @property Dish $Dish
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class DishesController extends AppController {

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
		$this->Dish->recursive = 0;
		$this->set('dishes', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Dish->exists($id)) {
			throw new NotFoundException(__('Invalid dish'));
		}
		$options = array('conditions' => array('Dish.' . $this->Dish->primaryKey => $id));
		$this->set('dish', $this->Dish->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Dish->create();
			if ($this->Dish->save($this->request->data)) {
				$this->Session->setFlash(__('The dish has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The dish could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
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
		if (!$this->Dish->exists($id)) {
			throw new NotFoundException(__('Invalid dish'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Dish->save($this->request->data)) {
				$this->Session->setFlash(__('The dish has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The dish could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Dish.' . $this->Dish->primaryKey => $id));
			$this->request->data = $this->Dish->find('first', $options);
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
		$this->Dish->id = $id;
		if (!$this->Dish->exists()) {
			throw new NotFoundException(__('Invalid dish'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Dish->delete()) {
			$this->Session->setFlash(__('The dish has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The dish could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

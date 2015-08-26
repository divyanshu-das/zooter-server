<?php
App::uses('AppController', 'Controller');
/**
 * HolidayDestinations Controller
 *
 * @property HolidayDestination $HolidayDestination
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class HolidayDestinationsController extends AppController {

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
		$this->HolidayDestination->recursive = 0;
		$this->set('holidayDestinations', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->HolidayDestination->exists($id)) {
			throw new NotFoundException(__('Invalid holiday destination'));
		}
		$options = array('conditions' => array('HolidayDestination.' . $this->HolidayDestination->primaryKey => $id));
		$this->set('holidayDestination', $this->HolidayDestination->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->HolidayDestination->create();
			if ($this->HolidayDestination->save($this->request->data)) {
				$this->Session->setFlash(__('The holiday destination has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The holiday destination could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
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
		if (!$this->HolidayDestination->exists($id)) {
			throw new NotFoundException(__('Invalid holiday destination'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->HolidayDestination->save($this->request->data)) {
				$this->Session->setFlash(__('The holiday destination has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The holiday destination could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('HolidayDestination.' . $this->HolidayDestination->primaryKey => $id));
			$this->request->data = $this->HolidayDestination->find('first', $options);
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
		$this->HolidayDestination->id = $id;
		if (!$this->HolidayDestination->exists()) {
			throw new NotFoundException(__('Invalid holiday destination'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->HolidayDestination->delete()) {
			$this->Session->setFlash(__('The holiday destination has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The holiday destination could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

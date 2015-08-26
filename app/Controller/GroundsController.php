<?php
App::uses('AppController', 'Controller');
/**
 * Grounds Controller
 *
 * @property Ground $Ground
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class GroundsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');
	public $api = 'ground';
  public $apiAction;

	public $apiEndPoints = array('get_suggestions');

	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('get_suggestions');

		if(in_array($this->action, $this->apiEndPoints)){
      $this->autoRender = false;
    }
	}

		/**
		 * afterFilter callback
		 *
		 * @return void
		 */
		public function afterFilter() {
			if(!empty($this->apiResponse)){
        $data = $this->ZooterResponse->prepareResponse($this->apiResponse, $this->api, $this->apiAction);
        $this->response->body($data);
      }
		}

	public function get_suggestions() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
		// $this->request->data = array('{"api": "ground","action": "get_suggestions","appGuid": "aonecdad-345hldd-nuhoacfl","data":{"key" : "a"}}');
		$this->apiAction = 'get_suggestions';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
			$key = $this->ZooterRequest->getRequestParam($result['request_data'], 'key');
		  $this->apiResponse = $this->Ground->getSuggestions($key);
		} else {
			$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
		}
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Ground->recursive = 0;
		$this->set('grounds', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Ground->exists($id)) {
			throw new NotFoundException(__('Invalid ground'));
		}
		$options = array('conditions' => array('Ground.' . $this->Ground->primaryKey => $id));
		$this->set('ground', $this->Ground->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Ground->create();
			if ($this->Ground->save($this->request->data)) {
				$this->Session->setFlash(__('The ground has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The ground could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$locations = $this->Ground->Location->find('list');
		$this->set(compact('locations'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Ground->exists($id)) {
			throw new NotFoundException(__('Invalid ground'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Ground->save($this->request->data)) {
				$this->Session->setFlash(__('The ground has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The ground could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Ground.' . $this->Ground->primaryKey => $id));
			$this->request->data = $this->Ground->find('first', $options);
		}
		$locations = $this->Ground->Location->find('list');
		$this->set(compact('locations'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Ground->id = $id;
		if (!$this->Ground->exists()) {
			throw new NotFoundException(__('Invalid ground'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Ground->delete()) {
			$this->Session->setFlash(__('The ground has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The ground could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

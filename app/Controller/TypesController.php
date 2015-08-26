<?php
App::uses('AppController', 'Controller');
/**
 * Types Controller
 *
 * @property Type $Type
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class TypesController extends AppController {

	public $api = 'type';

	public $apiEndPoints = array('add_type');

	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
		public function beforeFilter() {
			parent::beforeFilter();
			$this->Auth->allow('add_type');

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
		
	

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

	public function add_type() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
		// $this->request->data = array('{"api":"type","action":"add_type","data":{"type":"Professional"},"appGuid":"aonecdad-345hldd-nuhoacfl"}');
    $this->apiAction = 'add_type';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
    	$type = $this->ZooterRequest->getRequestParam($result['request_data'], 'type');
    	$this->apiResponse = array(
    		'data' => array(
    			'type_id' => 1
  			)
  		);
    }
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Type->recursive = 0;
		$this->set('types', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Type->exists($id)) {
			throw new NotFoundException(__('Invalid type'));
		}
		$options = array('conditions' => array('Type.' . $this->Type->primaryKey => $id));
		$this->set('type', $this->Type->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Type->create();
			if ($this->Type->save($this->request->data)) {
				$this->Session->setFlash(__('The type has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The type could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
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
		if (!$this->Type->exists($id)) {
			throw new NotFoundException(__('Invalid type'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Type->save($this->request->data)) {
				$this->Session->setFlash(__('The type has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The type could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Type.' . $this->Type->primaryKey => $id));
			$this->request->data = $this->Type->find('first', $options);
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
		$this->Type->id = $id;
		if (!$this->Type->exists()) {
			throw new NotFoundException(__('Invalid type'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Type->delete()) {
			$this->Session->setFlash(__('The type has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The type could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

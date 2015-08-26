<?php
App::uses('AppController', 'Controller');
App::uses('InvitationStatus', 'Lib/Enum');
/**
 * UserRequests Controller
 *
 * @property UserRequest $UserRequest
 * @property PaginatorComponent $Paginator
 */
class UserRequestsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

	public $api = 'user_request';
  public $apiAction;

	public $apiEndPoints = array('handle_request');

	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('handle_request');

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

	public function handle_request() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"user_request","action":"handle_request","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"6","user_request_id":"2","status":'false'
    //                                 }}');
    $this->apiAction = 'handle_request';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $userRequestId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_request_id');
      $status = $this->ZooterRequest->getRequestParam($result['request_data'], 'status');
      $this->apiResponse = $this->UserRequest->handleRequest($userId,$userRequestId,$status);
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
		$this->UserRequest->recursive = 0;
		$this->set('userRequests', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->UserRequest->exists($id)) {
			throw new NotFoundException(__('Invalid user request'));
		}
		$options = array('conditions' => array('UserRequest.' . $this->UserRequest->primaryKey => $id));
		$this->set('userRequest', $this->UserRequest->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->UserRequest->create();
			if ($this->UserRequest->save($this->request->data)) {
				return $this->flash(__('The user request has been saved.'), array('action' => 'index'));
			}
		}
		$requestCreatedBies = $this->UserRequest->RequestCreatedBy->find('list');
		$requestModifiedBies = $this->UserRequest->RequestModifiedBy->find('list');
		$this->set(compact('requestCreatedBies', 'requestModifiedBies'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->UserRequest->exists($id)) {
			throw new NotFoundException(__('Invalid user request'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->UserRequest->save($this->request->data)) {
				return $this->flash(__('The user request has been saved.'), array('action' => 'index'));
			}
		} else {
			$options = array('conditions' => array('UserRequest.' . $this->UserRequest->primaryKey => $id));
			$this->request->data = $this->UserRequest->find('first', $options);
		}
		$requestCreatedBies = $this->UserRequest->RequestCreatedBy->find('list');
		$requestModifiedBies = $this->UserRequest->RequestModifiedBy->find('list');
		$this->set(compact('requestCreatedBies', 'requestModifiedBies'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->UserRequest->id = $id;
		if (!$this->UserRequest->exists()) {
			throw new NotFoundException(__('Invalid user request'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->UserRequest->delete()) {
			return $this->flash(__('The user request has been deleted.'), array('action' => 'index'));
		} else {
			return $this->flash(__('The user request could not be deleted. Please, try again.'), array('action' => 'index'));
		}
	}
}

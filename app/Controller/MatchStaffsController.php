<?php
App::uses('AppController', 'Controller');
/**
 * MatchStaffs Controller
 *
 * @property MatchStaff $MatchStaff
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class MatchStaffsController extends AppController {

/**
 * Components
 *
 * @var array
 */
  public $uses = array('MatchStaff');
	public $components = array('Paginator', 'Session');
	public $api = 'match_staff';
  public $apiAction;
	public $apiEndPoints = array('show','delete','update');


	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('show','delete','update');

		if (in_array($this->action, $this->apiEndPoints)){
      $this->autoRender = false;
    }
	}

		/**
		 * afterFilter callback
		 *
		 * @return void
		 */
	public function afterFilter() {
		if (!empty($this->apiResponse)){
      $data = $this->ZooterResponse->prepareResponse($this->apiResponse, $this->api, $this->apiAction);
      $this->response->body($data);
    }
	}


  public function show(){
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->request->data = array('{"api": "match_staff","action": "show","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'show';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse = $this->MatchStaff->showMatchStaff($id);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function delete(){
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->request->data = array('{"api": "match_staff","action": "delete","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'delete';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse =  $this->MatchStaff->deleteMatchStaff($id);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

	public function update() {

    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

		$this->request->data = array('
    {
      "api": "match_staff",
      "action": "update",
      "appGuid": "aonecdad-345hldd-nuhoacfl",
      "data": { 
        "match_id":1,
        "match_staffs": [
           {
              "user_id": 1,
              "role": 1,
              "status": 1
          },
          {
              "id": 3,
              "match_id": 1,
              "user_id": 1,
              "role": 2,
              "status": 3
          }
        ]
      }
    }
  ');
		$this->apiAction = 'update';
  	$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);  
  	if ($result['validation_result']) {	
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      if (! $this->ZooterRequest->validateUpdateParams($result['request_data']['data'], 'match_id', $matchId)) {
        $result['api_return_code'] = 400;
        $result['message'] = 'Invalid match_id found during update';
        $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
      } else {
        $matchStaffs = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_staffs');
  		  $this->apiResponse = $this->MatchStaff->updateMatchStaffs($matchStaffs, $matchId);
      }
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
		$this->MatchStaff->recursive = 0;
		$this->set('matchStaffs', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->MatchStaff->exists($id)) {
			throw new NotFoundException(__('Invalid match staff'));
		}
		$options = array('conditions' => array('MatchStaff.' . $this->MatchStaff->primaryKey => $id));
		$this->set('matchStaff', $this->MatchStaff->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->MatchStaff->create();
			if ($this->MatchStaff->save($this->request->data)) {
				$this->Session->setFlash(__('The match staff has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The match staff could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$matches = $this->MatchStaff->Match->find('list');
		$users = $this->MatchStaff->User->find('list');
		$this->set(compact('matches', 'users'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->MatchStaff->exists($id)) {
			throw new NotFoundException(__('Invalid match staff'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->MatchStaff->save($this->request->data)) {
				$this->Session->setFlash(__('The match staff has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The match staff could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('MatchStaff.' . $this->MatchStaff->primaryKey => $id));
			$this->request->data = $this->MatchStaff->find('first', $options);
		}
		$matches = $this->MatchStaff->Match->find('list');
		$users = $this->MatchStaff->User->find('list');
		$this->set(compact('matches', 'users'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->MatchStaff->id = $id;
		if (!$this->MatchStaff->exists()) {
			throw new NotFoundException(__('Invalid match staff'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->MatchStaff->delete()) {
			$this->Session->setFlash(__('The match staff has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The match staff could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

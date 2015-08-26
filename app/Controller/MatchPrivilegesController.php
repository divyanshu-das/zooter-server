<?php
App::uses('AppController', 'Controller');
/**
 * MatchPrivileges Controller
 *
 * @property MatchPrivilege $MatchPrivilege
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class MatchPrivilegesController extends AppController {

/**
 * Components
 *
 * @var array
 */

	public $uses = array('MatchPrivilege');
	public $components = array('Paginator', 'Session');
	public $api = 'match_privilege';
  public $apiAction;
	public $apiEndPoints = array('show','delete','update','match_admin_check',
                                'invite_admin','delete_admin','search_match_admin');

	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('show','delete','update','match_admin_check','invite_admin',
                        'delete_admin','search_match_admin');

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

    $this->request->data = array('{"api": "match_privilege","action": "show","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'show';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse = $this->MatchPrivilege->showMatchPrivilege($id);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function delete(){
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->request->data = array('{"api": "match_privilege","action": "delete","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'delete';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse =  $this->MatchPrivilege->deleteMatchPrivilege($id);
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
        "api": "match_privilege",
        "action": "update",
        "appGuid": "aonecdad-345hldd-nuhoacfl",
        "data": {
          "match_id": 1,
          "match_privileges": [
            {
              "id": 1,
              "match_id": 1,
              "user_id": 1,
       				"is_admin": 1
            },
            {
              "match_id": 1,
              "user_id": 1,
              "is_admin": 0
            }
          ]
        }
      }
    ');
    $this->apiAction = 'update';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);  
    if ($result['validation_result']) { 
      $matchId= $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      if (! $this->ZooterRequest->validateUpdateParams($result['request_data']['data'], 'match_id', $matchId)) {
        $result['api_return_code'] = 400;
        $result['message'] = 'Invalid match_id found during update';
        $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
      } else {
        $matchPrivileges= $this->ZooterRequest->getRequestParam($result['request_data'], 'match_privileges');
        $this->apiResponse = $this->MatchPrivilege->updateMatchPrivileges( $matchId, $matchPrivileges);
      }
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
    
  }

  public function invite_admin() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"match_privilege","action":"invite_admin","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"match_id":1,"user_id":8,"invite_user_id":16
    //                               }}');
    $this->apiAction = 'invite_admin';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $inviteUserId = $this->ZooterRequest->getRequestParam($result['request_data'], 'invite_user_id');
      $this->apiResponse = $this->MatchPrivilege->inviteAdmin($userId,$matchId,$inviteUserId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function delete_admin() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"match_privilege","action":"delete_admin","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"match_id":1,"user_id":8,"delete_user_id":6
    //                               }}');
    $this->apiAction = 'delete_admin';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $deleteUserId = $this->ZooterRequest->getRequestParam($result['request_data'], 'delete_user_id');
      $this->apiResponse = $this->MatchPrivilege->deleteAdmin($userId,$matchId,$deleteUserId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function match_admin_check() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"match_privilege","action":"match_admin_check","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"match_id":1,"user_id":6
    //                               }}');
    $this->apiAction = 'match_admin_check';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $this->apiResponse = $this->MatchPrivilege->checkMatchAdminUser($matchId,$userId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function search_match_admin() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"match_privilege","action":"search_match_admin","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"user_id":6,"match_id":1,"input":"da"
    //                               }}');
    $this->apiAction = 'search_match_admin';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $input = $this->ZooterRequest->getRequestParam($result['request_data'], 'input');
      $this->apiResponse = $this->MatchPrivilege->searchMatchAdmins($userId,$matchId,$input);
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
		$this->MatchPrivilege->recursive = 0;
		$this->set('matchPrivileges', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->MatchPrivilege->exists($id)) {
			throw new NotFoundException(__('Invalid match privilege'));
		}
		$options = array('conditions' => array('MatchPrivilege.' . $this->MatchPrivilege->primaryKey => $id));
		$this->set('matchPrivilege', $this->MatchPrivilege->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->MatchPrivilege->create();
			if ($this->MatchPrivilege->save($this->request->data)) {
				$this->Session->setFlash(__('The match privilege has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The match privilege could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$matches = $this->MatchPrivilege->Match->find('list');
		$users = $this->MatchPrivilege->User->find('list');
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
		if (!$this->MatchPrivilege->exists($id)) {
			throw new NotFoundException(__('Invalid match privilege'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->MatchPrivilege->save($this->request->data)) {
				$this->Session->setFlash(__('The match privilege has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The match privilege could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('MatchPrivilege.' . $this->MatchPrivilege->primaryKey => $id));
			$this->request->data = $this->MatchPrivilege->find('first', $options);
		}
		$matches = $this->MatchPrivilege->Match->find('list');
		$users = $this->MatchPrivilege->User->find('list');
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
		$this->MatchPrivilege->id = $id;
		if (!$this->MatchPrivilege->exists()) {
			throw new NotFoundException(__('Invalid match privilege'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->MatchPrivilege->delete()) {
			$this->Session->setFlash(__('The match privilege has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The match privilege could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

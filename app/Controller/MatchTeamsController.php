<?php
App::uses('AppController', 'Controller');
/**
 * MatchTeams Controller
 *
 * @property MatchTeam $MatchTeam
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class MatchTeamsController extends AppController {

/**
 * Components
 *
 * @var array
 */
 	public $uses = array('MatchTeam');
	public $components = array('Paginator', 'Session');
	public $api = 'match_team';
  public $apiAction;
	public $apiEndPoints = array('show','delete','update','invite_team_match','match_admin_teams',
                                'match_team_join');


	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('show','delete','update','invite_team_match','match_admin_teams',
                        'match_team_join');

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

    $this->request->data = array('{"api": "match_team","action": "show","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'show';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse = $this->MatchTeam->showMatchTeam($id);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function delete(){
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->request->data = array('{"api": "match_team","action": "delete","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'delete';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse =  $this->MatchTeam->deleteMatchTeam($id);
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
      "api": "match_team",
      "action": "update",
      "appGuid": "aonecdad-345hldd-nuhoacfl",
      "data": { 
        "match_id":1,
        "match_teams": [
           {
              "match_id": 1,
              "team_id":1,
              "status": 1,
              "is_host":1
          },
          {
              "id": 1,
              "match_id": 6,
              "team_id": 1,
              "status": 1,
              "is_host": 1
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
        $matchTeams = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_teams');
  			$this->apiResponse = $this->MatchTeam->updateMatchTeams($matchTeams, $matchId);
      }
  	} else {
    	$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
  	}
		
	}

	public function invite_team_match() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"team","action":"invite_team_match","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"user_id":6,"match_id":1,"team_id":7
    //                               }}');
    $this->apiAction = 'invite_team_match';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $teamId = $this->ZooterRequest->getRequestParam($result['request_data'], 'team_id');
      $this->apiResponse = $this->MatchTeam->inviteTeamInMatch($userId,$matchId,$teamId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function match_team_join() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"team","action":"match_team_join","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"user_id":15,"match_id":1,"team_id":7
    //                               }}');
    $this->apiAction = 'match_team_join';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $teamId = $this->ZooterRequest->getRequestParam($result['request_data'], 'team_id');
      $this->apiResponse = $this->MatchTeam->requestMatchTeamJoin($userId,$matchId,$teamId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function match_admin_teams() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"team","action":"match_admin_teams","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"user_id":6,"match_id":1
    //                               }}');
    $this->apiAction = 'match_admin_teams';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $this->apiResponse = $this->MatchTeam->getMatchConfirmedTeamsOwnedByUser($userId,$matchId);
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
		$this->MatchTeam->recursive = 0;
		$this->set('matchTeams', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->MatchTeam->exists($id)) {
			throw new NotFoundException(__('Invalid match team'));
		}
		$options = array('conditions' => array('MatchTeam.' . $this->MatchTeam->primaryKey => $id));
		$this->set('matchTeam', $this->MatchTeam->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->MatchTeam->create();
			if ($this->MatchTeam->save($this->request->data)) {
				$this->Session->setFlash(__('The match team has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The match team could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$matches = $this->MatchTeam->Match->find('list');
		$teams = $this->MatchTeam->Team->find('list');
		$this->set(compact('matches', 'teams'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->MatchTeam->exists($id)) {
			throw new NotFoundException(__('Invalid match team'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->MatchTeam->save($this->request->data)) {
				$this->Session->setFlash(__('The match team has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The match team could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('MatchTeam.' . $this->MatchTeam->primaryKey => $id));
			$this->request->data = $this->MatchTeam->find('first', $options);
		}
		$matches = $this->MatchTeam->Match->find('list');
		$teams = $this->MatchTeam->Team->find('list');
		$this->set(compact('matches', 'teams'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->MatchTeam->id = $id;
		if (!$this->MatchTeam->exists()) {
			throw new NotFoundException(__('Invalid match team'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->MatchTeam->delete()) {
			$this->Session->setFlash(__('The match team has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The match team could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

<?php
App::uses('AppController', 'Controller');
/**
 * TeamPlayers Controller
 *
 * @property TeamPlayer $TeamPlayer
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class TeamPlayersController extends AppController {

/**
 * Components
 *
 * @var array
 */
  public $uses = array('TeamPlayer');
	public $components = array('Paginator', 'Session');
	public $api = 'team_player';
  public $apiAction;
	public $apiEndPoints = array('show','delete','update','invite_zooter_basket_player_to_team');


	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('show','delete','update','invite_zooter_basket_player_to_team');

		if (in_array($this->action, $this->apiEndPoints)) {
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

    $this->request->data = array('{"api": "team_player","action": "show","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'show';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse = $this->TeamPlayer->showTeamPlayer($id);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function delete() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->request->data = array('{"api": "team_player","action": "delete","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'delete';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse =  $this->TeamPlayer->deleteTeamPlayer($id);
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
      "api": "team_player",
      "action": "update",
      "appGuid": "aonecdad-345hldd-nuhoacfl",
      "data": { 
        "team_id":2,
        "team_players": [
           {
              "user_id": 2,
              "role": 1,
              "status": 1
          },
          {
              "id": 4,
              "team_id": 2,
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
      $teamId = $this->ZooterRequest->getRequestParam($result['request_data'], 'team_id');
      if ( ! $this->ZooterRequest->validateUpdateParams($result['request_data']['data'], 'team_id', $teamId)) {
        $result['api_return_code'] = 400;
        $result['message'] = 'Invalid team_id found during update';
        $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
      } else {
        $teamPlayers = $this->ZooterRequest->getRequestParam($result['request_data'], 'team_players');
  		  $this->apiResponse = $this->TeamPlayer->updateTeamPlayers($teamPlayers, $teamId);
      }
  	} else {
    	$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
  	}		
	}

	public function invite_zooter_basket_player_to_team() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"team","action":"invite_zooter_basket_player_to_team","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"user_id":15,"match_id":1,"team_id":7,"invite_user_id":14
    //                               }}');
    $this->apiAction = 'invite_zooter_basket_player_to_team';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $teamId = $this->ZooterRequest->getRequestParam($result['request_data'], 'team_id');
      $inviteUserId = $this->ZooterRequest->getRequestParam($result['request_data'], 'invite_user_id');
      $this->apiResponse = $this->TeamPlayer->inviteZooterBasketPlayerToTeam($userId,$matchId,$teamId,$inviteUserId);
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
		$this->TeamPlayer->recursive = 0;
		$this->set('teamPlayers', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->TeamPlayer->exists($id)) {
			throw new NotFoundException(__('Invalid team player'));
		}
		$options = array('conditions' => array('TeamPlayer.' . $this->TeamPlayer->primaryKey => $id));
		$this->set('teamPlayer', $this->TeamPlayer->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->TeamPlayer->create();
			if ($this->TeamPlayer->save($this->request->data)) {
				$this->Session->setFlash(__('The team player has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The team player could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$teams = $this->TeamPlayer->Team->find('list');
		$users = $this->TeamPlayer->User->find('list');
		$this->set(compact('teams', 'users'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->TeamPlayer->exists($id)) {
			throw new NotFoundException(__('Invalid team player'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->TeamPlayer->save($this->request->data)) {
				$this->Session->setFlash(__('The team player has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The team player could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('TeamPlayer.' . $this->TeamPlayer->primaryKey => $id));
			$this->request->data = $this->TeamPlayer->find('first', $options);
		}
		$teams = $this->TeamPlayer->Team->find('list');
		$users = $this->TeamPlayer->User->find('list');
		$this->set(compact('teams', 'users'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->TeamPlayer->id = $id;
		if (!$this->TeamPlayer->exists()) {
			throw new NotFoundException(__('Invalid team player'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->TeamPlayer->delete()) {
			$this->Session->setFlash(__('The team player has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The team player could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

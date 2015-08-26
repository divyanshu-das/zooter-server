<?php
App::uses('AppController', 'Controller');
/**
 * MatchPlayers Controller
 *
 * @property MatchPlayer $MatchPlayer
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class MatchPlayersController extends AppController {

/**
 * Components
 *
 * @var array
 */
  public $uses = array('MatchPlayer');
	public $components = array('Paginator', 'Session');
	public $api = 'match_player';
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



  public function show(){
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->request->data = array('{"api": "match_player","action": "show","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'show';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse = $this->MatchPlayer->showMatchPlayer($id);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function delete(){
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->request->data = array('{"api": "match_player","action": "delete","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'delete';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse =  $this->MatchPlayer->deleteMatchPlayer($id);
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
      "api": "match_player",
      "action": "update",
      "appGuid": "aonecdad-345hldd-nuhoacfl",
      "data": { 
        "match_id":1,
        "match_players": [
          {
            "id": 1,
            "user_id": 1,
            "team_id": 2,
            "role": 6,
            "status": 1
          },
          {
            "id": 2,
            "match_id": 1,
            "team_id": 2,
            "user_id": 1,
            "role": 6,
            "status": 15
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
        $matchPlayers = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_players');
  			$this->apiResponse = $this->MatchPlayer->updateMatchPlayers($matchPlayers, $matchId);
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
		$this->MatchPlayer->recursive = 0;
		$this->set('matchPlayers', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->MatchPlayer->exists($id)) {
			throw new NotFoundException(__('Invalid match player'));
		}
		$options = array('conditions' => array('MatchPlayer.' . $this->MatchPlayer->primaryKey => $id));
		$this->set('matchPlayer', $this->MatchPlayer->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->MatchPlayer->create();
			if ($this->MatchPlayer->save($this->request->data)) {
				$this->Session->setFlash(__('The match player has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The match player could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$matches = $this->MatchPlayer->Match->find('list');
		$users = $this->MatchPlayer->User->find('list');
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
		if (!$this->MatchPlayer->exists($id)) {
			throw new NotFoundException(__('Invalid match player'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->MatchPlayer->save($this->request->data)) {
				$this->Session->setFlash(__('The match player has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The match player could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('MatchPlayer.' . $this->MatchPlayer->primaryKey => $id));
			$this->request->data = $this->MatchPlayer->find('first', $options);
		}
		$matches = $this->MatchPlayer->Match->find('list');
		$users = $this->MatchPlayer->User->find('list');
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
		$this->MatchPlayer->id = $id;
		if (!$this->MatchPlayer->exists()) {
			throw new NotFoundException(__('Invalid match player'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->MatchPlayer->delete()) {
			$this->Session->setFlash(__('The match player has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The match player could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

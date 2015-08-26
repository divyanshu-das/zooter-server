<?php
App::uses('AppController', 'Controller');
/**
 * MatchPlayerScorecards Controller
 *
 * @property MatchPlayerScorecard $MatchPlayerScorecard
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class MatchPlayerScorecardsController extends AppController {

/**
 * Components
 *
 * @var array
 */
  public $uses = array('MatchPlayerScorecard');
	public $components = array('Paginator', 'Session');
	public $api = 'match_player_scorecard';
  public $apiAction;
	public $apiEndPoints = array('show','delete','update','toggle_graph_type');

	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('show','delete','update','toggle_graph_type');

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

    $this->request->data = array('{"api": "match_player_scorecard","action": "show","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'show';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse = $this->MatchPlayerScorecard->showMatchPlayerScorecard($id);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function delete(){
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->request->data = array('{"api": "match_player_scorecard","action": "delete","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'delete';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse =  $this->MatchPlayerScorecard->deleteMatchPlayerScorecard($id);
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
        "api": "match_player_scorecard",
        "action": "update",
        "appGuid": "aonecdad-345hldd-nuhoacfl",
        "data": {
          "match_id": 1,
          "match_player_scorecards": [
            {
              "user_id": 1,
              "inning": 2,
              "runs_scored": 26,
              "balls_faced": 45,
              "fours_hit": 3,
              "sixes_hit": 4,
              "wickets_taken": 3,
              "overs_bowled": 9,
              "maidens_bowled": 2,
              "runs_conceded": 45,
              "wides_bowled": 4,
              "no_balls_bowled": 3
            },
            {
              "user_id": 1,
              "inning": 2,
              "runs_scored": 96,
              "balls_faced": 85,
              "fours_hit": 10,
              "sixes_hit": 3,
              "wickets_taken": 2,
              "overs_blowled": 5,
              "maidens_bowled": 0,
              "runs_conceded": 25,
              "wides_bowled": 1,
              "no_balls_bowled": 1
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
        $matchPlayerScores= $this->ZooterRequest->getRequestParam($result['request_data'], 'match_player_scorecards');
        $this->apiResponse = $this->MatchPlayerScorecard->updateMatchPlayerScorecards( $matchId, $matchPlayerScores);
      }
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
    
  }

  public function toggle_graph_type(){
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api": "match_player_scorecard","action": "toggle_graph_type",
    //                                 "appGuid": "aonecdad-345hldd-nuhoacfl",
    //                                 "data": {"user_id":"3","year":"2015 ","is_batting":true}
    //                               }');
    $this->apiAction = 'toggle_graph_type';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $year = $this->ZooterRequest->getRequestParam($result['request_data'], 'year');
      $isBatting = $this->ZooterRequest->getRequestParam($result['request_data'], 'is_batting');
      $this->apiResponse =  $this->MatchPlayerScorecard->getCareerPerformanceGraph($userId,$year,$isBatting);
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
		$this->MatchPlayerScorecard->recursive = 0;
		$this->set('matchPlayerScorecards', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->MatchPlayerScorecard->exists($id)) {
			throw new NotFoundException(__('Invalid match player scorecard'));
		}
		$options = array('conditions' => array('MatchPlayerScorecard.' . $this->MatchPlayerScorecard->primaryKey => $id));
		$this->set('matchPlayerScorecard', $this->MatchPlayerScorecard->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->MatchPlayerScorecard->create();
			if ($this->MatchPlayerScorecard->save($this->request->data)) {
				$this->Session->setFlash(__('The match player scorecard has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The match player scorecard could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$matches = $this->MatchPlayerScorecard->Match->find('list');
		$users = $this->MatchPlayerScorecard->User->find('list');
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
		if (!$this->MatchPlayerScorecard->exists($id)) {
			throw new NotFoundException(__('Invalid match player scorecard'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->MatchPlayerScorecard->save($this->request->data)) {
				$this->Session->setFlash(__('The match player scorecard has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The match player scorecard could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('MatchPlayerScorecard.' . $this->MatchPlayerScorecard->primaryKey => $id));
			$this->request->data = $this->MatchPlayerScorecard->find('first', $options);
		}
		$matches = $this->MatchPlayerScorecard->Match->find('list');
		$users = $this->MatchPlayerScorecard->User->find('list');
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
		$this->MatchPlayerScorecard->id = $id;
		if (!$this->MatchPlayerScorecard->exists()) {
			throw new NotFoundException(__('Invalid match player scorecard'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->MatchPlayerScorecard->delete()) {
			$this->Session->setFlash(__('The match player scorecard has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The match player scorecard could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

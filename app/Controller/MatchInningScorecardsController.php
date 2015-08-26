<?php
App::uses('AppController', 'Controller');
/**
 * MatchInningScorecards Controller
 *
 * @property MatchInningScorecard $MatchInningScorecard
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class MatchInningScorecardsController extends AppController {
/**
 * Components
 *
 * @var array
 */
  public $uses = array('MatchInningScorecard');
	public $components = array('Paginator', 'Session');
	public $api = 'match_inning_scorecard';
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

    $this->request->data = array('{"api": "match_inning_scorecard","action": "show","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'show';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse = $this->MatchInningScorecard->showMatchInningScorecard($id);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function delete(){
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->request->data = array('{"api": "match_award","action": "delete","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'delete';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse =  $this->MatchInningScorecard->deleteMatchInningScorecard($id);
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
    "api": "match_inning_scorecard",
    "action": "update",
    "appGuid": "aonecdad-345hldd-nuhoacfl",
    "data": {
        "match_id": 1,
        "match_inning_scorecards": [
            {
                "inning": 1,
                "team_id": 1,
                "total_runs": 326,
                "overs": 45.5,
                "wickets": 4,
                "wide_balls": 8,
                "leg_byes": 3,
                "byes": 9,
                "no_balls": 2
            },
            {
                "match_id": 1,
                "inning": 3,
                "team_id": 1,
                "total_runs": 204,
                "overs": 48.3,
                "wickets": 10,
                "wide_balls": 2,
                "leg_byes": 3,
                "byes": 5,
                "no_balls": 2
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
        $matchInningScores= $this->ZooterRequest->getRequestParam($result['request_data'], 'match_inning_scorecards');
  		  $this->apiResponse = $this->MatchInningScorecard->updateMatchInningScorecards( $matchId, $matchInningScores);
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
		$this->MatchInningScorecard->recursive = 0;
		$this->set('matchInningScorecards', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->MatchInningScorecard->exists($id)) {
			throw new NotFoundException(__('Invalid match inning scorecard'));
		}
		$options = array('conditions' => array('MatchInningScorecard.' . $this->MatchInningScorecard->primaryKey => $id));
		$this->set('matchInningScorecard', $this->MatchInningScorecard->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->MatchInningScorecard->create();
			if ($this->MatchInningScorecard->save($this->request->data)) {
				$this->Session->setFlash(__('The match inning scorecard has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The match inning scorecard could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$matches = $this->MatchInningScorecard->Match->find('list');
		$teams = $this->MatchInningScorecard->Team->find('list');
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
		if (!$this->MatchInningScorecard->exists($id)) {
			throw new NotFoundException(__('Invalid match inning scorecard'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->MatchInningScorecard->save($this->request->data)) {
				$this->Session->setFlash(__('The match inning scorecard has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The match inning scorecard could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('MatchInningScorecard.' . $this->MatchInningScorecard->primaryKey => $id));
			$this->request->data = $this->MatchInningScorecard->find('first', $options);
		}
		$matches = $this->MatchInningScorecard->Match->find('list');
		$teams = $this->MatchInningScorecard->Team->find('list');
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
		$this->MatchInningScorecard->id = $id;
		if (!$this->MatchInningScorecard->exists()) {
			throw new NotFoundException(__('Invalid match inning scorecard'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->MatchInningScorecard->delete()) {
			$this->Session->setFlash(__('The match inning scorecard has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The match inning scorecard could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}


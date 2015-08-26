<?php
App::uses('AppController', 'Controller');
/**
 * PlayerStatistics Controller
 *
 * @property PlayerStatistic $PlayerStatistic
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class PlayerStatisticsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');
  public $api = 'player_statistic';
  public $apiAction;
	public $apiEndPoints = array('update_or_add_player_career_statistics','player_search','player_search_public');

	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('update_or_add_player_career_statistics','player_search','player_search_public');

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


	public function update_or_add_player_career_statistics() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

		// $this->request->data = array('
  //       {"api":"player_statistic","action":"update_or_add_player_career_statistics","appGuid":"aonecdad-345hldd-nuhoacfl",
  //         "data":{
  //           "player_career_statistics":[{"id" : 1,"user_id" : 3, "is_cricket_ball_used" : 1, "match_scale" : 1, "match_type" : 2,
  //                                        "total_matches" : 19, "total_runs" : 1226, "total_balls_faced" : 1745, "total_fours_hit" : 43, 
  //                                         "total_sixes_hit" : 34, "total_wickets_taken" : 33, "total_overs_bowled" : 120,
  //                                         "total_maidens_bowled" : 9, "total_runs_conceded" : 745, "total_wides_bowled" : 14,
  //                                         "total_no_balls_bowled" : 13},
  //                                       {"user_id" : 5, "is_cricket_ball_used" : 1, "match_scale" : 2,"match_type" : 4,
  //                                        "total_matches" : 39,"total_runs" : 2126, "total_balls_faced" : 2245,"total_fours_hit" : 53, 
  //                                         "total_sixes_hit" : 30, "total_wickets_taken" : 33, "total_overs_bowled" : 150,
  //                                         "total_maidens_bowled" : 21, "total_runs_conceded" : 945, "total_wides_bowled" : 14,
  //                                         "total_no_balls_bowled" : 3}]
  //           }
  //       }');
		$this->apiAction = 'update_or_add_player_career_statistics';
  	$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);  
  	if ($result['validation_result']) {	
  		$playerCareerStatistics = $this->ZooterRequest->getRequestParam($result['request_data'], 'player_career_statistics');
  			$this->apiResponse = $this->PlayerStatistic->updateOrAddPlayerCareerStatistics($playerCareerStatistics);
  	} else {
    	$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
  	}
		
	}

	public function player_search_public() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"player_statistic","action":"player_search_public","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"filters":{"text":"","minimum_matches":0,
    //                                   "maximum_matches":35,"first_letter":"n","leather":true,"tennis":true,
    //                                   "location":{"latitude":21.6139,"longitude":75.2089}}
    //                               }}');
    $this->apiAction = 'player_search_public';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $filters = $this->ZooterRequest->getRequestParam($result['request_data'], 'filters');
      $this->apiResponse = $this->PlayerStatistic->playerSearchPublic($filters);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

	public function player_search() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"player_statistic","action":"player_search","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"user_id":6,
    //                                 "filters":{"text":"","minimum_matches":0,
    //                                   "maximum_matches":35,"first_letter":"n","leather":true,"tennis":true,
    //                                   "location":{"latitude":21.6139,"longitude":75.2089}}
    //                               }}');
    $this->apiAction = 'player_search';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $filters = $this->ZooterRequest->getRequestParam($result['request_data'], 'filters');
      $this->apiResponse = $this->PlayerStatistic->playerSearchForUser($userId,$filters);
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
		$this->PlayerStatistic->recursive = 0;
		$this->set('playerStatistics', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->PlayerStatistic->exists($id)) {
			throw new NotFoundException(__('Invalid player statistic'));
		}
		$options = array('conditions' => array('PlayerStatistic.' . $this->PlayerStatistic->primaryKey => $id));
		$this->set('playerStatistic', $this->PlayerStatistic->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->PlayerStatistic->create();
			if ($this->PlayerStatistic->save($this->request->data)) {
				$this->Session->setFlash(__('The player statistic has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The player statistic could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$users = $this->PlayerStatistic->User->find('list');
		$this->set(compact('users'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->PlayerStatistic->exists($id)) {
			throw new NotFoundException(__('Invalid player statistic'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->PlayerStatistic->save($this->request->data)) {
				$this->Session->setFlash(__('The player statistic has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The player statistic could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('PlayerStatistic.' . $this->PlayerStatistic->primaryKey => $id));
			$this->request->data = $this->PlayerStatistic->find('first', $options);
		}
		$users = $this->PlayerStatistic->User->find('list');
		$this->set(compact('users'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->PlayerStatistic->id = $id;
		if (!$this->PlayerStatistic->exists()) {
			throw new NotFoundException(__('Invalid player statistic'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->PlayerStatistic->delete()) {
			$this->Session->setFlash(__('The player statistic has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The player statistic could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

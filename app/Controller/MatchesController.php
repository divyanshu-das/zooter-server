<?php
App::uses('AppController', 'Controller');
/**
 * Matches Controller
 *
 * @property Match $Match
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class MatchesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');
	public $api = 'match';
  public $apiAction;
  public $apiEndPoints = array('create','update','show','delete','match_corner','match_search',
                                'match_search_public','match_corner_public','match_live',
                                'match_exists','match_teams','match_miniscorecard','match_checks',
                                'match_scorecard','match_pics');

	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('create','update','show','delete','match_corner','match_search','match_search_public',
                        'match_corner_public','match_live','match_exists','match_teams','match_miniscorecard',
                        'match_checks','match_scorecard','match_pics');

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

  public function create() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api": "match","action": "create","appGuid": "aonecdad-345hldd-nuhoacfl",
    //                               "data": {
    //                                 "user_id": 6,
    //                                 "basic_info" : {"is_cricket_ball_used":true,"is_private": true,
    //                                   "start_date_time": "2015-04-18 20:14:39","players_per_side":11,
    //                                   "overs_per_innings":18,"name":"eliminator semifinal",
    //                                   "location":{"latitude": 21.6139,"longitude":75.2089,                                      
    //                                               "unique_identifier":"gyyrtropWDe4vzsRQAIREDBgguy",
    //                                               "place":"bhakti palace,NH1,tarapore"}},
    //                                 "teams": [{"team_id": 8},{"team_id": 9}],
    //                                 "officials": {"first_umpire": [{"user_id": 12},{"user_id": 14}],
    //                                               "second_umpire": [{"user_id": 12},{"user_id": 14}],
    //                                               "third_umpire": [{"user_id": 12},{"user_id": 14}],
    //                                               "reserve_umpire": [{"user_id": 12},{"user_id": 14}],
    //                                               "referee": [{"user_id": 12},{"user_id": 14}],
    //                                               "scorer": [{"user_id": 12},{"user_id": 14}]},
    //                                 "admins": [{"user_id": 12},{"user_id": 14}]
    //                               }}');
    $this->apiAction = 'create';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $parameters = json_decode($this->request->data[0], true)['data'];
      $this->apiResponse = $this->Match->createNewMatch($userId, $parameters);
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
      "api": "match",
      "action": "update",
      "appGuid": "aonecdad-345hldd-nuhoacfl",
      "data": {
        "id": 6,
        "name": "India vrs Australia",
        "is_public": "0",
        "start_date_time": "2014-10-23 10:10:39",
        "end_date_time": "2014-10-25 20:14:39",
        "location_name": "xyz",
        "location_unique_identifier": "jxfer5fgf45",
        "location_latitude": "19.56",
        "location_longitude": "12.345",
        "match_type": 2,
        "match_scale": 2,
        "is_test": 0,
        "is_cricket_ball_used": 1,
        "overs_per_innings": 47,
        "series_match_level": 2,
        "match_teams": [
          {
            "id": 1,
            "team_id": 1,
            "is_host": 1,
            "status": 2
          },
          {
            "id": 2,
            "team_id": 2,
            "is_host": 0,
            "status": 5
          }
        ],
        "match_awards": [
          {
            "id": 1,
            "award_name": "Player OF The Match",
            "value": 28000,
            "user_id": 1
          },
          {
            "id": 2,
            "award_name": "Player OF The Series",
            "value": 39000,
            "user_id": 1
          }
        ],
        "match_staffs": [
          {
            "id": 1,
            "user_id": 1,
            "role": 4,
            "status": 1
          },
          {
            "id": 2,
            "user_id": 2,
            "role": 8,
            "status": 1
          }
        ],
        "match_admins": [
          {
            "id": 1,
            "user_id": 1,
            "is_admin": 0
          },
          {
            "user_id": 2,
            "is_admin": 1
          }
        ]
      }
    }
  ');
		$this->apiAction = 'update';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
    	$id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
       if (! $this->ZooterRequest->validateUpdateParams($result['request_data']['data'], 'match_id', $id)) {
        $result['api_return_code'] = 400;
        $result['message'] = 'Invalid match_id found during update';
        $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
      } else {
        $name = $this->ZooterRequest->getRequestParam($result['request_data'], 'name');
      	$seriesId = $this->ZooterRequest->getRequestParam($result['request_data'], 'series_id');
      	$startDateTime = $this->ZooterRequest->getRequestParam($result['request_data'], 'start_date_time');
      	$endDateTime = $this->ZooterRequest->getRequestParam($result['request_data'], 'end_date_time');
      	$isPublic = $this->ZooterRequest->getRequestParam($result['request_data'], 'is_public');
      	$matchType = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_type');
      	$matchScale = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_scale');
      	$isTest = $this->ZooterRequest->getRequestParam($result['request_data'], 'is_test');
      	$isCricketBallUsed = $this->ZooterRequest->getRequestParam($result['request_data'], 'is_cricket_ball_used');
      	$oversPerInnings = $this->ZooterRequest->getRequestParam($result['request_data'], 'overs_per_innings');
      	$seriesMatchLevel = $this->ZooterRequest->getRequestParam($result['request_data'], 'series_match_level');
      	$location_name = $this->ZooterRequest->getRequestParam($result['request_data'], 'location_name');
        $location_unique_identifier = $this->ZooterRequest->getRequestParam($result['request_data'], 'location_unique_identifier');
        $location_latitude = $this->ZooterRequest->getRequestParam($result['request_data'], 'location_latitude');
        $location_longitude = $this->ZooterRequest->getRequestParam($result['request_data'], 'location_longitude');
        $locationId = $this->Match->Location->saveLocation($location_name, $location_latitude, $location_longitude, $location_unique_identifier);
        $matchTeams = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_teams');
        $matchAdmins = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_admins');
        $matchAwards = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_awards');
        $matchStaffs = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_staffs');
        $this->apiResponse = $this->Match->updateMatch($id, $name, $seriesId, $startDateTime, $endDateTime, $isPublic, $matchType, $matchScale,	$isTest, $isCricketBallUsed, $oversPerInnings, $seriesMatchLevel, $locationId,	$matchTeams, $matchAdmins, $matchAwards, $matchStaffs);
      }
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
	}

  public function search() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"match","action":"search","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"6","date":"45","dateType":""
    //                                 }}');
    $this->apiAction = 'search';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $searchInput = $this->ZooterRequest->getRequestParam($result['request_data'], 'searchInput');
      $searchType = $this->ZooterRequest->getRequestParam($result['request_data'], 'searchType');
      $this->apiResponse = $this->Match->searchMatches($userId,$searchInput,$searchType);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function match_corner_public() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"match","action":"match_corner_public","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"is_public":true}}');
    $this->apiAction = 'match_corner_public';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $isPublic = $this->ZooterRequest->getRequestParam($result['request_data'], 'is_public');
      $this->apiResponse = $this->Match->matchCornerPublic($isPublic);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function match_corner() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"match","action":"match_corner","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"user_id":6}}');
    $this->apiAction = 'match_corner';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $this->apiResponse = $this->Match->matchCornerForUser($userId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function match_search_public() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"match","action":"match_search_public","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                 "filters":{"text":"","date":"","ongoing":false,
    //                                   "upcoming":false,"finished":false,"leather":true,"tennis":false,
    //                                   "location":{"latitude":21.6139,"longitude":75.2089}}
    //                               }}');
    $this->apiAction = 'match_search_public';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $filters = $this->ZooterRequest->getRequestParam($result['request_data'], 'filters');
      $this->apiResponse = $this->Match->matchSearchPublic($filters);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function match_search() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"match","action":"match_search","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"user_id":6,"top_filter":{},
    //                                 "sub_filter":{"text":"","date":"","ongoing":false,
    //                                   "upcoming":false,"finished":false,"leather":true,"tennis":false,
    //                                   "location":{"latitude":21.6139,"longitude":75.2089}}
    //                               }}');
    $this->apiAction = 'match_search';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $topFilter = $this->ZooterRequest->getRequestParam($result['request_data'], 'top_filter');
      $subFilter = $this->ZooterRequest->getRequestParam($result['request_data'], 'sub_filter');
      $this->apiResponse = $this->Match->matchSearchForUser($userId,$topFilter,$subFilter);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function match_live() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"match","action":"match_live","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"match_id":1
    //                               }}');
    $this->apiAction = 'match_live';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $this->apiResponse = $this->Match->matchLive($matchId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function match_miniscorecard() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"match","action":"match_miniscorecard","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"match_id":1
    //                               }}');
    $this->apiAction = 'match_miniscorecard';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $this->apiResponse = $this->Match->matchMiniScorecard($matchId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function match_scorecard() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"match","action":"match_scorecard","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"match_id":1
    //                               }}');
    $this->apiAction = 'match_scorecard';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $this->apiResponse = $this->Match->matchScorecard($matchId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function match_exists() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"match","action":"match_exists","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"match_id":1
    //                               }}');
    $this->apiAction = 'match_exists';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $this->apiResponse = $this->Match->checkMatchExists($matchId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function match_checks() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"match","action":"match_checks","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"match_id":1,"user_id":6
    //                               }}');
    $this->apiAction = 'match_checks';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $this->apiResponse = $this->Match->initialMatchUserChecks($matchId,$userId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function match_teams() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"match","action":"match_teams","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"match_id":1
    //                               }}');
    $this->apiAction = 'match_teams';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $this->apiResponse = $this->Match->matchViewTeamPublic($matchId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function match_pics() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"match","action":"match_pics","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"match_id":1
    //                               }}');
    $this->apiAction = 'match_pics';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $this->apiResponse = $this->Match->matchImages($matchId);
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
		$this->Match->recursive = 0;
		$this->set('matches', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Match->exists($id)) {
			throw new NotFoundException(__('Invalid match'));
		}
		$options = array('conditions' => array('Match.' . $this->Match->primaryKey => $id));
		$this->set('match', $this->Match->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Match->create();
			if ($this->Match->save($this->request->data)) {
				$this->Session->setFlash(__('The match has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The match could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$series = $this->Match->Series->find('list');
		$locations = $this->Match->Location->find('list');
		$this->set(compact('series', 'locations'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Match->exists($id)) {
			throw new NotFoundException(__('Invalid match'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Match->save($this->request->data)) {
				$this->Session->setFlash(__('The match has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The match could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Match.' . $this->Match->primaryKey => $id));
			$this->request->data = $this->Match->find('first', $options);
		}
		$series = $this->Match->Series->find('list');
		$locations = $this->Match->Location->find('list');
		$this->set(compact('series', 'locations'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Match->id = $id;
		if (!$this->Match->exists()) {
			throw new NotFoundException(__('Invalid match'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Match->delete()) {
			$this->Session->setFlash(__('The match has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The match could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

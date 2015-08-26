<?php
App::uses('AppController', 'Controller');
/**
 * Teams Controller
 *
 * @property Team $Team
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class TeamsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');
	public $api = 'team';
  public $apiAction;
  public $apiEndPoints = array('create','update','show','delete','team_search','team_search_public',
                                'search_match_teams','user_teams');

	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('create','update','show','delete','team_search','team_search_public',
                        'search_match_teams','user_teams');

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

  

  public function show() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->request->data = array('{"api": "team","action": "show","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 2}}');
    $this->apiAction = 'show';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse = $this->Team->showTeam($id);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

/* TODO: Needs Logic Change */

  public function delete() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->request->data = array('{"api": "team","action": "delete","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 3}}');
    $this->apiAction = 'delete';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse = $this->Team->deleteTeam($id);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function create() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api": "team","action": "create","appGuid": "aonecdad-345hldd-nuhoacfl",
    //                               "data": {
    //                                 "user_id": "6",
    //                                 "basic_info" : {"name": "Sun Estate Warriors34","is_public": true,
    //                                   "registration_date": "2010-06-12 20:14:39","team_tagline": "The Unbeatable team",
    //                                   "location":{"latitude": 21.6139,"longitude":75.2089,                                      
    //                                               "unique_identifier":"gyyrtropWDe4vzsRQAIREDBgguy",
    //                                               "place":"bhakti palace,NH1,tarapore"},
    //                                   "profile_image_url": "/img/team101.png","cover_image_url": "/img/team102.png"},
    //                                 "players": [{"user_id": 8,"role":"Batsman"},{"user_id": 10,"role":"Twelfth Man"}],
    //                                 "staffs": [{"user_id": 13,"role":"Physio"},{"user_id": 12,"role":"Manager"}],
    //                                 "admins": [{"user_id": 12},{"user_id": 14}]
    //                               }}');
   	$this->apiAction = 'create';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
    	$userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $parameters = json_decode($this->request->data[0], true)['data'];
      $this->apiResponse = $this->Team->createNewTeam($userId, $parameters);
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
      "api": "team",
      "action": "update",
      "appGuid": "aonecdad-345hldd-nuhoacfl",
      "data": {
        "id": "5",
        "name": "East Asia Warriors",
        "is_public": "1",
        "registration_date": "2014-06-12 20:14:39",
        "user_id": "2",
        "team_tagline": " EC Team",
        "location_name": "xyz",
        "location_unique_identifier": "jbsdb",
        "location_latitude": "2.345",
        "location_longitude": "2.345",
        "players_roles_status": [
          {
            "id": 1 ,
            "user_id": 1,
            "role": 12,
            "status": 0
          },
           {
            "id": 2,
            "user_id": 1,
            "role": 12,
            "status": 0
          }
       ],
      "staffs_roles_status": [
          {
            "user_id": 2,
            "role": 8,
            "status": 0
          }
       ],
      "team_admins": [
          {
            "id":1,
            "user_id": 1,
            "is_admin": 0
          },
          {
            "id": 2,
            "user_id": 1,
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
      if (! $this->ZooterRequest->validateUpdateParams($result['request_data']['data'], 'team_id', $id)) {
        $result['api_return_code'] = 400;
        $result['message'] = 'Invalid team_id found during update';
        $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
      } else {
        $name = $this->ZooterRequest->getRequestParam($result['request_data'], 'name');
        $isPublic = $this->ZooterRequest->getRequestParam($result['request_data'], 'is_public');
        $registrationDate = $this->ZooterRequest->getRequestParam($result['request_data'], 'registration_date');
        $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
        $teamTagline =  $this->ZooterRequest->getRequestParam($result['request_data'], 'team_tagline');
        $location_name = $this->ZooterRequest->getRequestParam($result['request_data'], 'location_name');
        $location_unique_identifier = $this->ZooterRequest->getRequestParam($result['request_data'], 'location_unique_identifier');
        $location_latitude = $this->ZooterRequest->getRequestParam($result['request_data'], 'location_latitude');
        $location_longitude = $this->ZooterRequest->getRequestParam($result['request_data'], 'location_longitude');
        $locationId = $this->Team->Location->saveLocation($location_name, $location_latitude, $location_longitude, $location_unique_identifier);
        $playersRolesStatus = $this->ZooterRequest->getRequestParam($result['request_data'], 'players_roles_status');
        $staffsRolesStatus = $this->ZooterRequest->getRequestParam($result['request_data'], 'staffs_roles_status');
        $teamAdmins = $this->ZooterRequest->getRequestParam($result['request_data'], 'team_admins');
        $this->apiResponse = $this->Team->updateTeam($id, $name,  $userId,  $locationId, $isPublic, $registrationDate, $teamTagline, $playersRolesStatus, $staffsRolesStatus, $teamAdmins);
      }
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function team_search_public() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"team","action":"team_search_public","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"filters":{"text":"",
    //                                   "location":{"latitude":21.6139,"longitude":75.2089}}
    //                               }}');
    $this->apiAction = 'team_search_public';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $filters = $this->ZooterRequest->getRequestParam($result['request_data'], 'filters');
      $this->apiResponse = $this->Team->teamSearchPublic($filters);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function team_search() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"team","action":"team_search","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"user_id":6,"top_filter":{},
    //                                 "sub_filter":{"text":"",
    //                                   "location":{"latitude":21.6139,"longitude":75.2089}}
    //                               }}');
    $this->apiAction = 'team_search';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $topFilter = $this->ZooterRequest->getRequestParam($result['request_data'], 'top_filter');
      $subFilter = $this->ZooterRequest->getRequestParam($result['request_data'], 'sub_filter');
      $this->apiResponse = $this->Team->teamSearchForUser($userId,$topFilter,$subFilter);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function search_match_teams() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"team","action":"search_match_teams","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"user_id":6,"match_id":2,"input":"X"
    //                               }}');
    $this->apiAction = 'search_match_teams';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $input = $this->ZooterRequest->getRequestParam($result['request_data'], 'input');
      $this->apiResponse = $this->Team->searchTeamsForMatchInvitation($userId,$matchId,$input);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function user_teams() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"team","action":"user_teams","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"user_id":6,"num_of_records":"","match_id":1
    //                               }}');
    $this->apiAction = 'user_teams';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $numOfRecords = $this->ZooterRequest->getRequestParam($result['request_data'], 'num_of_records');
      $this->apiResponse = $this->Team->getTeamsOwnedByUser($userId,$matchId,$numOfRecords);
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
		$this->Team->recursive = 0;
		$this->set('teams', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Team->exists($id)) {
			throw new NotFoundException(__('Invalid team'));
		}
		$options = array('conditions' => array('Team.' . $this->Team->primaryKey => $id));
		$this->set('team', $this->Team->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Team->create();
			if ($this->Team->save($this->request->data)) {
				$this->Session->setFlash(__('The team has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The team could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$locations = $this->Team->Location->find('list');
		$owners = $this->Team->Owner->find('list');
		$matches = $this->Team->Match->find('list');
		$series = $this->Team->Series->find('list');
		$this->set(compact('locations', 'owners', 'matches', 'series'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Team->exists($id)) {
			throw new NotFoundException(__('Invalid team'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Team->save($this->request->data)) {
				$this->Session->setFlash(__('The team has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The team could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Team.' . $this->Team->primaryKey => $id));
			$this->request->data = $this->Team->find('first', $options);
		}
		$locations = $this->Team->Location->find('list');
		$owners = $this->Team->Owner->find('list');
		$matches = $this->Team->Match->find('list');
		$series = $this->Team->Series->find('list');
		$this->set(compact('locations', 'owners', 'matches', 'series'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Team->id = $id;
		if (!$this->Team->exists()) {
			throw new NotFoundException(__('Invalid team'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Team->delete()) {
			$this->Session->setFlash(__('The team has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The team could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

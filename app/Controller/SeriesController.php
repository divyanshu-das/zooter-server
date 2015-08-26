<?php
App::uses('AppController', 'Controller');
/**
 * Series Controller
 *
 * @property Series $Series
 * @property PaginatorComponent $Paginator
 */
class SeriesController extends AppController {

/**
 * Components
 *
 * @var array
 */
  public $components = array('Paginator',);

  public $api = 'series';
	public $apiAction;

  public $apiEndPoints = array('create','update','show','delete','tournament_search_public','tournament_search');

/**
 * beforeFilter callback
 *
 * @return void
 */

  public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow('create','update','show','delete','tournament_search_public','tournament_search');

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
	$this->request->data = array('{"api": "series","action": "show","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
		$this->apiAction = 'show';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
		  $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
		  $this->apiResponse = $this->Series->showSeries($id);
		} else {
			$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
		}
  }

	public function delete(){
		$this->request->data = array('{"api": "series","action": "delete","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
		$this->apiAction = 'delete';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
			$id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
			$this->apiResponse = $this->Series->deleteSeries($id);
		} else {
			$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
		}
	}

	public function create(){
		$this->request->data = array('
		{
	    "api": "series",
	    "action": "create",
	    "appGuid": "aonecdad-345hldd-nuhoacfl",
	    "data": {
        "name": "testingseriesverify",
        "user_id": 1,
        "start_datetime": "2014-12-31 20:35:24",
        "end_datetime": "2014-12-31 20:35:25",
        "is_public": true,
        "scale": "1",
        "series_type":"1",
        "location_name": "bits goa",
        "location_unique_identifier": "csdlgfsglngr",
        "location_latitude": "1.5",
        "location_longitude": "3.7",
        "series_teams": [
          {
            "team_id": 1
          },
          {
            "team_id": 2
          }
        ],
        "series_privileges": [
          {
            "user_id": 1
          }
        ],
        "series_awards": [
          {
            "award_name": "golden arm",
            "value": 234
          },
          {
            "award_name": "best bowl2",
            "value": 10056
          },
          {
            "award_name": "man of the match7",
            "value": 876000
          }
        ]
   	 	}
		}
	');
		$this->apiAction = 'create';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
      if (! $this->ZooterRequest->validateCreateParams($result['request_data'], 'id')) {
      $result['api_return_code'] = 400;
      $result['message'] = 'Id found during creation';
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
      } else {
  			$name = $this->ZooterRequest->getRequestParam($result['request_data'], 'name');
  			$startDatetime = $this->ZooterRequest->getRequestParam($result['request_data'], 'start_datetime');
  			$endDatetime = $this->ZooterRequest->getRequestParam($result['request_data'], 'end_datetime');
  			$is_public = $this->ZooterRequest->getRequestParam($result['request_data'], 'is_public');
  			$scale = $this->ZooterRequest->getRequestParam($result['request_data'], 'scale');
  			$series_type = $this->ZooterRequest->getRequestParam($result['request_data'], 'series_type');
  			$owner_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
  			$location_name = $this->ZooterRequest->getRequestParam($result['request_data'], 'location_name');
  			$location_unique_identifier = $this->ZooterRequest->getRequestParam($result['request_data'], 'location_unique_identifier');
  			$location_latitude = $this->ZooterRequest->getRequestParam($result['request_data'], 'location_latitude');
  			$location_longitude = $this->ZooterRequest->getRequestParam($result['request_data'], 'location_longitude');
  			$series_teams = $this->ZooterRequest->getRequestParam($result['request_data'], 'series_teams');
  			$series_awards = $this->ZooterRequest->getRequestParam($result['request_data'], 'series_awards');
  			$seriesPrivileges = $this->ZooterRequest->getRequestParam($result['request_data'], 'series_privileges');
  			$location_id = $this->Series->Location->saveLocation($location_name, $location_latitude, $location_longitude, $location_unique_identifier);
  			$this->apiResponse = $this->Series->createSeries($name, $startDatetime, $endDatetime, $is_public, $scale, $series_type, $owner_id,$location_id,$series_teams,$series_awards, $seriesPrivileges);
	    }
  	} else {
			$this->apiResponse = $th1is->ZooterResponse->getErrorResponse($result);
		}
	}



	public function update(){
		$this->request->data = array('
			{
				  "api": "series",
				  "action": "update",
				  "appGuid": "aonecdad-345hldd-nuhoacfl",
				  "data": {
				  		"id": 1,
				      "name": "testingseriesverify",
				      "user_id": 1,
				      "start_datetime":"2014-12-31 20:35:24",
				      "end_datetime":"2014-12-31 20:35:27",
				      "is_public": true,
				      "is_cancelled": 1,
				      "scale": "2",
				      "series_type": 5,
				      "location_name": "test1",
				      "location_unique_identifier": "fdhgfdhgh",
				      "location_latitude": "4.3",
				      "location_longitude": "5.3",
				      "series_teams": [
				          {
				              "id": "1",
				              "team_id": "4",
				              "invitation_status": true
				          },
				          {
				              "id": "2",
				              "team_id": "1",
				              "invitation_status": true
				          }
				      ],
				      "series_admins": [
				          {
				              "user_id": 1,
                      "is_admin":0
				          },
				          {
				              "user_id": 2,
                      "is_admin":1
				          }
				      ],
				      "series_awards": [
				          {
				              "award_name": "golden arm",
				              "value": 234,
				              "user_id": ""

				          },
				          {
				              "award_name": "man of the match7",
				              "value": 876000,
				              "user_id": ""
				          }
				      ]
				  }
				}
			');
		$this->apiAction = 'update';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
			$id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
        if (! $this->ZooterRequest->validateUpdateParams($result['request_data']['data'], 'series_id', $id)) {
        $result['api_return_code'] = 400;
        $result['message'] = 'Invalid series_id found during update';
        $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
      } else {
			$name = $this->ZooterRequest->getRequestParam($result['request_data'], 'name');
  			$startDatetime = $this->ZooterRequest->getRequestParam($result['request_data'], 'start_datetime');
  			$endDatetime = $this->ZooterRequest->getRequestParam($result['request_data'], 'end_datetime');
  			$is_public = $this->ZooterRequest->getRequestParam($result['request_data'], 'is_public');
  			$is_cancelled = $this->ZooterRequest->getRequestParam($result['request_data'], 'is_cancelled');
  			$scale = $this->ZooterRequest->getRequestParam($result['request_data'], 'scale');
  			$series_type = $this->ZooterRequest->getRequestParam($result['request_data'], 'series_type');
  			$owner_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
  			$location_name = $this->ZooterRequest->getRequestParam($result['request_data'], 'location_name');
  			$location_unique_identifier = $this->ZooterRequest->getRequestParam($result['request_data'], 'location_unique_identifier');
  			$location_latitude = $this->ZooterRequest->getRequestParam($result['request_data'], 'location_latitude');
  			$location_longitude = $this->ZooterRequest->getRequestParam($result['request_data'], 'location_longitude');
  			$series_teams = $this->ZooterRequest->getRequestParam($result['request_data'], 'series_teams');
  			$series_awards = $this->ZooterRequest->getRequestParam($result['request_data'], 'series_awards');
  			$series_admins = $this->ZooterRequest->getRequestParam($result['request_data'], 'series_admins');
  			$location_id = $this->Series->Location->saveLocation($location_name, $location_latitude, $location_longitude, $location_unique_identifier);
  			$this->apiResponse = $this->Series->updateSeries($id, $name, $startDatetime, $endDatetime, $is_public, $is_cancelled, $scale, $series_type, $owner_id,$location_id,$series_teams,$series_awards,$series_admins);
		  }
    } else {
			$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
		}
	}

	public function tournament_search_public() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"series","action":"tournament_search_public","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                 "filters":{"text":"series","start_date":"2014-08-01","end_date":"2014-10-20",
    //                                   "ongoing":false,"upcoming":false,"finished":true,
    //                                   "location":{"latitude":21.6139,"longitude":75.2089}}
    //                               }}');
    $this->apiAction = 'tournament_search_public';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $filters = $this->ZooterRequest->getRequestParam($result['request_data'], 'filters');
      $this->apiResponse = $this->Series->tournamentSearchPublic($filters);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function tournament_search() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"series","action":"tournament_search","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"user_id":6,"top_filter":{},
    //                                 "sub_filter":{"text":"","date":"","ongoing":false,
    //                                   "upcoming":false,"finished":false,"leather":true,"tennis":false,
    //                                   "location":{"latitude":21.6139,"longitude":75.2089}}
    //                               }}');
    $this->apiAction = 'tournament_search';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $topFilter = $this->ZooterRequest->getRequestParam($result['request_data'], 'top_filter');
      $subFilter = $this->ZooterRequest->getRequestParam($result['request_data'], 'sub_filter');
      $this->apiResponse = $this->Series->tournamentSearchForUser($userId,$topFilter,$subFilter);
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
	$this->Series->recursive = 0;
	$this->set('series', $this->Paginator->paginate());
  }

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
  public function admin_view($id = null) {
	if (!$this->Series->exists($id)) {
	  throw new NotFoundException(__('Invalid series'));
	}
	$options = array('conditions' => array('Series.' . $this->Series->primaryKey => $id));
	$this->set('series', $this->Series->find('first', $options));
  }

/**
 * admin_add method
 *
 * @return void
 */
  public function admin_add() {
	if ($this->request->is('post')) {
	  $this->Series->create();
	  if ($this->Series->save($this->request->data)) {
		return $this->flash(__('The series has been saved.'), array('action' => 'index'));
	  }
	}
	$locations = $this->Series->Location->find('list');
	$this->set(compact('locations'));
  }

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
  public function admin_edit($id = null) {
	if (!$this->Series->exists($id)) {
	  throw new NotFoundException(__('Invalid series'));
	}
	if ($this->request->is(array('post', 'put'))) {
	  if ($this->Series->save($this->request->data)) {
		return $this->flash(__('The series has been saved.'), array('action' => 'index'));
	  }
	} else {
	  $options = array('conditions' => array('Series.' . $this->Series->primaryKey => $id));
	  $this->request->data = $this->Series->find('first', $options);
	}
	$locations = $this->Series->Location->find('list');
	$this->set(compact('locations'));
  }

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
  public function admin_delete($id = null) {
	$this->Series->id = $id;
	if (!$this->Series->exists()) {
	  throw new NotFoundException(__('Invalid series'));
	}
	$this->request->onlyAllow('post', 'delete');
	if ($this->Series->delete()) {
	  return $this->flash(__('The series has been deleted.'), array('action' => 'index'));
	} else {
	  return $this->flash(__('The series could not be deleted. Please, try again.'), array('action' => 'index'));
	}
  }
}

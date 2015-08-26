<?php
App::uses('AppController', 'Controller');
/**
 * SeriesTeams Controller
 *
 * @property SeriesTeam $SeriesTeam
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class SeriesTeamsController extends AppController {

/**
 * Components
 *
 * @var array
 */
 	public $uses = array('SeriesTeam');
	public $components = array('Paginator', 'Session');
	public $api = 'series_team';
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
  $this->request->data = array('{"api": "series_team","action": "show","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'show';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse = $this->SeriesTeam->showSeriesTeam($id);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function delete(){
    $this->request->data = array('{"api": "series_team","action": "delete","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'delete';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse =  $this->SeriesTeam->deleteSeriesTeam($id);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

	public function update() {

		$this->request->data = array('
    {
      "api": "series_team",
      "action": "update",
      "appGuid": "aonecdad-345hldd-nuhoacfl",
      "data": { 
        "series_id":1,
        "series_teams": [
           {
              "series_id": 1,
              "team_id":1,
              "status": 1
          },
          {
              "id": 1,
              "series_id": 1,
              "team_id": 1,
              "status": 1
          }
        ]
      }
    }
  ');
		$this->apiAction = 'update';
  	$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);  
  	if ($result['validation_result']) {	
      $seriesId = $this->ZooterRequest->getRequestParam($result['request_data'], 'series_id');
       if (! $this->ZooterRequest->validateUpdateParams($result['request_data']['data'], 'series_id', $seriesId)) {
        $result['api_return_code'] = 400;
        $result['message'] = 'Invalid series_id found during update';
        $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
      } else {
        $seriesTeams = $this->ZooterRequest->getRequestParam($result['request_data'], 'series_teams');
  			$this->apiResponse = $this->SeriesTeam->updateSeriesTeams($seriesTeams, $seriesId);
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
		$this->SeriesTeam->recursive = 0;
		$this->set('seriesTeams', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->SeriesTeam->exists($id)) {
			throw new NotFoundException(__('Invalid series team'));
		}
		$options = array('conditions' => array('SeriesTeam.' . $this->SeriesTeam->primaryKey => $id));
		$this->set('seriesTeam', $this->SeriesTeam->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->SeriesTeam->create();
			if ($this->SeriesTeam->save($this->request->data)) {
				$this->Session->setFlash(__('The series team has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The series team could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->SeriesTeam->exists($id)) {
			throw new NotFoundException(__('Invalid series team'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->SeriesTeam->save($this->request->data)) {
				$this->Session->setFlash(__('The series team has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The series team could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('SeriesTeam.' . $this->SeriesTeam->primaryKey => $id));
			$this->request->data = $this->SeriesTeam->find('first', $options);
		}
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->SeriesTeam->id = $id;
		if (!$this->SeriesTeam->exists()) {
			throw new NotFoundException(__('Invalid series team'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->SeriesTeam->delete()) {
			$this->Session->setFlash(__('The series team has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The series team could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

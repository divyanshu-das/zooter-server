<?php
App::uses('AppController', 'Controller');
/**
 * MatchResults Controller
 *
 * @property MatchResult $MatchResult
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class MatchResultsController extends AppController {

/**
 * Components
 *
 * @var array
 */
  public $uses = array('MatchResult');
	public $components = array('Paginator', 'Session');
	public $api = 'match_result';
  public $apiAction;
	public $apiEndPoints = array('create','show','delete','update');

	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('create','show','delete','update');

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

    $this->request->data = array('{"api": "match_result","action": "show","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'show';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse = $this->MatchResult->showMatchResult($id);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function delete(){
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->request->data = array('{"api": "match_result","action": "delete","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'delete';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse =  $this->MatchResult->deleteMatchResult($id);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function create() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

		$this->request->data = array('
    {
      "api": "match_result",
      "action": "create",
      "appGuid": "aonecdad-345hldd-nuhoacfl",
      "data": { 
        "match_id": 1,
        "winning_team_id": 2,
        "result_type": 1
      }
    }
  ');
		$this->apiAction = 'create';
  	$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);  
  	if ($result['validation_result']) {	
  		$match_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $winning_team_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'winning_team_id');
      $result_type = $this->ZooterRequest->getRequestParam($result['request_data'], 'result_type');
      $this->apiResponse = $this->MatchResult->createMatchResult($match_id, $winning_team_id, $result_type);
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
      "api": "match_result",
      "action": "update",
      "appGuid": "aonecdad-345hldd-nuhoacfl",
      "data": { 
        "id": 1,
        "match_id": 1,
        "winning_team_id": 1,
        "result_type": 2
      }
    }
  ');
		$this->apiAction = 'update';
  	$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);  
  	if ($result['validation_result']) {	
  		$id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $match_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $winning_team_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'winning_team_id');
      $result_type = $this->ZooterRequest->getRequestParam($result['request_data'], 'result_type');
      $this->apiResponse = $this->MatchResult->updateMatchResult($id, $match_id, $winning_team_id, $result_type);
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
		$this->MatchResult->recursive = 0;
		$this->set('matchResults', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->MatchResult->exists($id)) {
			throw new NotFoundException(__('Invalid match result'));
		}
		$options = array('conditions' => array('MatchResult.' . $this->MatchResult->primaryKey => $id));
		$this->set('matchResult', $this->MatchResult->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->MatchResult->create();
			if ($this->MatchResult->save($this->request->data)) {
				$this->Session->setFlash(__('The match result has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The match result could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$matches = $this->MatchResult->Match->find('list');
		$teams = $this->MatchResult->Team->find('list');
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
		if (!$this->MatchResult->exists($id)) {
			throw new NotFoundException(__('Invalid match result'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->MatchResult->save($this->request->data)) {
				$this->Session->setFlash(__('The match result has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The match result could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('MatchResult.' . $this->MatchResult->primaryKey => $id));
			$this->request->data = $this->MatchResult->find('first', $options);
		}
		$matches = $this->MatchResult->Match->find('list');
		$teams = $this->MatchResult->Team->find('list');
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
		$this->MatchResult->id = $id;
		if (!$this->MatchResult->exists()) {
			throw new NotFoundException(__('Invalid match result'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->MatchResult->delete()) {
			$this->Session->setFlash(__('The match result has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The match result could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

<?php
App::uses('AppController', 'Controller');
/**
 * MatchTosses Controller
 *
 * @property MatchToss $MatchToss
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class MatchTossesController extends AppController {


	/**
 * Components
 *
 * @var array
 */
  public $uses = array('MatchToss');
	public $components = array('Paginator', 'Session');
	public $api = 'match_toss';
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

    $this->request->data = array('{"api": "match_toss","action": "show","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'show';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse = $this->MatchToss->showMatchToss($id);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function delete(){
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->request->data = array('{"api": "match_toss","action": "delete","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'delete';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse =  $this->MatchToss->deleteMatchToss($id);
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
      "api": "match_toss",
      "action": "create",
      "appGuid": "aonecdad-345hldd-nuhoacfl",
      "data": { 
        "match_id": 1,
        "winning_team_id": 1,
        "toss_decision": 1
      }
    }
  ');
		$this->apiAction = 'create';
  	$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);  
  	if ($result['validation_result']) {	
  		$match_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $winning_team_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'winning_team_id');
      $toss_decision = $this->ZooterRequest->getRequestParam($result['request_data'], 'toss_decision');
      $this->apiResponse = $this->MatchToss->createMatchToss($match_id, $winning_team_id, $toss_decision);
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
      "api": "match_toss",
      "action": "update",
      "appGuid": "aonecdad-345hldd-nuhoacfl",
      "data": { 
        "id": 4,
        "match_id": 1,
        "winning_team_id": 1,
        "toss_decision": 1
      }
    }
  ');
		$this->apiAction = 'update';
  	$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);  
  	if ($result['validation_result']) {	
  		$id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $match_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $winning_team_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'winning_team_id');
      $toss_decision = $this->ZooterRequest->getRequestParam($result['request_data'], 'toss_decision');
      $this->apiResponse = $this->MatchToss->updateMatchToss($id, $match_id, $winning_team_id, $toss_decision);
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
		$this->MatchToss->recursive = 0;
		$this->set('matchTosses', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->MatchToss->exists($id)) {
			throw new NotFoundException(__('Invalid match toss'));
		}
		$options = array('conditions' => array('MatchToss.' . $this->MatchToss->primaryKey => $id));
		$this->set('matchToss', $this->MatchToss->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->MatchToss->create();
			if ($this->MatchToss->save($this->request->data)) {
				$this->Session->setFlash(__('The match toss has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The match toss could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$matches = $this->MatchToss->Match->find('list');
		$teams = $this->MatchToss->Team->find('list');
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
		if (!$this->MatchToss->exists($id)) {
			throw new NotFoundException(__('Invalid match toss'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->MatchToss->save($this->request->data)) {
				$this->Session->setFlash(__('The match toss has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The match toss could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('MatchToss.' . $this->MatchToss->primaryKey => $id));
			$this->request->data = $this->MatchToss->find('first', $options);
		}
		$matches = $this->MatchToss->Match->find('list');
		$teams = $this->MatchToss->Team->find('list');
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
		$this->MatchToss->id = $id;
		if (!$this->MatchToss->exists()) {
			throw new NotFoundException(__('Invalid match toss'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->MatchToss->delete()) {
			$this->Session->setFlash(__('The match toss has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The match toss could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

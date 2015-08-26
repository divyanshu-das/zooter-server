<?php
App::uses('AppController', 'Controller');
/**
 * MatchAwards Controller
 *
 * @property MatchAward $MatchAward
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class MatchAwardsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');
	public $api = 'match_award';
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
  $this->request->data = array('{"api": "match_award","action": "show","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'show';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse = $this->MatchAward->showMatchAward($id);
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
      $this->apiResponse = $this->MatchAward->deleteMatchAward($id);
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
          "api": "match_award",
          "action": "update",
          "appGuid": "aonecdad-345hldd-nuhoacfl",
          "data": {
              "match_id": "1",
              "match_awards": [
                  {
                      "id": "",
                      "award_name": "Player OF The Match",
                      "value": 22990,
                      "user_id": ""
                  },
                  {
                      "id": 2,
                      "award_name": "Player OF The Series 6",
                      "value": 99000,
                      "user_id": 1
                  },
                  {
                      "id": "",
                      "award_name": "Player OF The Match8.1",
                      "value": 2290,
                      "user_id": ""
                  }
              ]
          }
      }');

    $this->apiAction = 'update';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);  
    if ($result['validation_result']) { 
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      if (! $this->ZooterRequest->validateUpdateParams($result['request_data']['data'], 'match_id', $matchId)) {
        $result['api_return_code'] = 400;
        $result['message'] = 'Invalid match_id found during update';
        $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
      } else {
        $matchAwards = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_awards');
        $this->apiResponse = $this->MatchAward->updateMatchAwards($matchId, $matchAwards);
      }
    } 
    else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->MatchAward->recursive = 0;
		$this->set('matchAwards', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->MatchAward->exists($id)) {
			throw new NotFoundException(__('Invalid match award'));
		}
		$options = array('conditions' => array('MatchAward.' . $this->MatchAward->primaryKey => $id));
		$this->set('matchAward', $this->MatchAward->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->MatchAward->create();
			if ($this->MatchAward->save($this->request->data)) {
				$this->Session->setFlash(__('The match award has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The match award could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$matches = $this->MatchAward->Match->find('list');
		$users = $this->MatchAward->User->find('list');
		$awardTypes = $this->MatchAward->AwardType->find('list');
		$this->set(compact('matches', 'users', 'awardTypes'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->MatchAward->exists($id)) {
			throw new NotFoundException(__('Invalid match award'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->MatchAward->save($this->request->data)) {
				$this->Session->setFlash(__('The match award has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The match award could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('MatchAward.' . $this->MatchAward->primaryKey => $id));
			$this->request->data = $this->MatchAward->find('first', $options);
		}
		$matches = $this->MatchAward->Match->find('list');
		$users = $this->MatchAward->User->find('list');
		$awardTypes = $this->MatchAward->AwardType->find('list');
		$this->set(compact('matches', 'users', 'awardTypes'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->MatchAward->id = $id;
		if (!$this->MatchAward->exists()) {
			throw new NotFoundException(__('Invalid match award'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->MatchAward->delete()) {
			$this->Session->setFlash(__('The match award has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The match award could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

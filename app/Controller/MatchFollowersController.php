<?php
App::uses('AppController', 'Controller');
/**
 * MatchFollowers Controller
 *
 * @property MatchFollower $MatchFollower
 * @property PaginatorComponent $Paginator
 */
class MatchFollowersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

	public $api = 'match_follower';
  	public $apiAction;

	public $apiEndPoints = array('follow_match','unfollow_match','get_followed_matches');

	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('follow_match','unfollow_match','get_followed_matches');

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

	public function follow_match() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"match_follower","action":"follow_match","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"6","match_id":"1"
    //                                 }}');
    $this->apiAction = 'follow_match';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $this->apiResponse = $this->MatchFollower->followMatch($userId,$matchId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function unfollow_match() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"match_follower","action":"unfollow_match","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"6","match_id":"6"
    //                                 }}');
    $this->apiAction = 'unfollow_match';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $this->apiResponse = $this->MatchFollower->unfollowMatch($userId,$matchId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function get_followed_matches() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"match_follower","action":"get_followed_matches","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"6","num_of_records":"6"
    //                                 }}');
    $this->apiAction = 'get_followed_matches';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $numOfRecords = $this->ZooterRequest->getRequestParam($result['request_data'], 'num_of_records');
      $this->apiResponse = $this->MatchFollower->fetchMatchesFollowedByUser($userId,$numOfRecords);
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
		$this->MatchFollower->recursive = 0;
		$this->set('matchFollowers', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->MatchFollower->exists($id)) {
			throw new NotFoundException(__('Invalid match follower'));
		}
		$options = array('conditions' => array('MatchFollower.' . $this->MatchFollower->primaryKey => $id));
		$this->set('matchFollower', $this->MatchFollower->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->MatchFollower->create();
			if ($this->MatchFollower->save($this->request->data)) {
				return $this->flash(__('The match follower has been saved.'), array('action' => 'index'));
			}
		}
		$matches = $this->MatchFollower->Match->find('list');
		$users = $this->MatchFollower->User->find('list');
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
		if (!$this->MatchFollower->exists($id)) {
			throw new NotFoundException(__('Invalid match follower'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->MatchFollower->save($this->request->data)) {
				return $this->flash(__('The match follower has been saved.'), array('action' => 'index'));
			}
		} else {
			$options = array('conditions' => array('MatchFollower.' . $this->MatchFollower->primaryKey => $id));
			$this->request->data = $this->MatchFollower->find('first', $options);
		}
		$matches = $this->MatchFollower->Match->find('list');
		$users = $this->MatchFollower->User->find('list');
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
		$this->MatchFollower->id = $id;
		if (!$this->MatchFollower->exists()) {
			throw new NotFoundException(__('Invalid match follower'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->MatchFollower->delete()) {
			return $this->flash(__('The match follower has been deleted.'), array('action' => 'index'));
		} else {
			return $this->flash(__('The match follower could not be deleted. Please, try again.'), array('action' => 'index'));
		}
	}
}

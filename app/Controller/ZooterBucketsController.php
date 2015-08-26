<?php
App::uses('AppController', 'Controller');
/**
 * ZooterBuckets Controller
 *
 * @property ZooterBucket $ZooterBucket
 * @property PaginatorComponent $Paginator
 */
class ZooterBucketsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');
	public $api = 'zooter_basket';
  public $apiAction;
  public $apiEndPoints = array('add_or_invite','search_player_zooter_basket');

	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('add_or_invite','search_player_zooter_basket');

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

	public function add_or_invite() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"zooter_basket","action":"add_or_invite","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"match_id":1,"user_id":6,"invite_user_id":10
    //                               }}');
    $this->apiAction = 'add_or_invite';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $inviteUserId = $this->ZooterRequest->getRequestParam($result['request_data'], 'invite_user_id');
      $this->apiResponse = $this->ZooterBucket->invitePlayerInZooterBucket($userId,$matchId,$inviteUserId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function search_player_zooter_basket() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"zooter_basket","action":"search_player_zooter_basket","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"user_id":6,"match_id":1,"input":"da"
    //                               }}');
    $this->apiAction = 'search_player_zooter_basket';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $input = $this->ZooterRequest->getRequestParam($result['request_data'], 'input');
      $this->apiResponse = $this->ZooterBucket->searchPlayersForZooterBasket($userId,$matchId,$input);
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
		$this->ZooterBucket->recursive = 0;
		$this->set('zooterBuckets', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->ZooterBucket->exists($id)) {
			throw new NotFoundException(__('Invalid zooter bucket'));
		}
		$options = array('conditions' => array('ZooterBucket.' . $this->ZooterBucket->primaryKey => $id));
		$this->set('zooterBucket', $this->ZooterBucket->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->ZooterBucket->create();
			if ($this->ZooterBucket->save($this->request->data)) {
				return $this->flash(__('The zooter bucket has been saved.'), array('action' => 'index'));
			}
		}
		$matches = $this->ZooterBucket->Match->find('list');
		$users = $this->ZooterBucket->User->find('list');
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
		if (!$this->ZooterBucket->exists($id)) {
			throw new NotFoundException(__('Invalid zooter bucket'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->ZooterBucket->save($this->request->data)) {
				return $this->flash(__('The zooter bucket has been saved.'), array('action' => 'index'));
			}
		} else {
			$options = array('conditions' => array('ZooterBucket.' . $this->ZooterBucket->primaryKey => $id));
			$this->request->data = $this->ZooterBucket->find('first', $options);
		}
		$matches = $this->ZooterBucket->Match->find('list');
		$users = $this->ZooterBucket->User->find('list');
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
		$this->ZooterBucket->id = $id;
		if (!$this->ZooterBucket->exists()) {
			throw new NotFoundException(__('Invalid zooter bucket'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ZooterBucket->delete()) {
			return $this->flash(__('The zooter bucket has been deleted.'), array('action' => 'index'));
		} else {
			return $this->flash(__('The zooter bucket could not be deleted. Please, try again.'), array('action' => 'index'));
		}
	}
}

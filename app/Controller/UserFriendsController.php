<?php
App::uses('AppController', 'Controller');
/**
 * UserFriends Controller
 *
 * @property UserFriend $UserFriend
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class UserFriendsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

	public $api = 'user_friend';
  public $apiAction;
	public $apiEndPoints = array('send_friend_request', 'get_pending_friend_requests', 'sent_friend_requests',
															 'get_friendlist', 'cancel_pending_request', 'accept_pending_request',
															  'cancel_sent_request','toggle_friendship');

  /**
   * beforeFilter callback
   *
   * @return void
   */
  	public function beforeFilter() {
  		parent::beforeFilter();
  		$this->Auth->allow('send_friend_request', 'get_pending_friend_requests', 'sent_friend_requests',
  												'get_friendlist', 'cancel_pending_request', 'accept_pending_request',
  												 'cancel_sent_request','toggle_friendship');

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
  		parent::afterFilter();
  		if(!empty($this->apiResponse)){
        $data = $this->ZooterResponse->prepareResponse($this->apiResponse, $this->api, $this->apiAction);
        $this->response->body($data);
      }
  	}
  


	public function send_friend_request() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

		// $this->request->data = array('{"api":"user_friend","action":"send_friend_request","data":{"user_id":"5","apiKey":"ce4127191419d557ffa44b5453a30617d1f8e981","friend_id":1},"appGuid":"aonecdad-345hldd-nuhoacfl"}');
		$this->apiAction = 'send_friend_request';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $friendId = $this->ZooterRequest->getRequestParam($result['request_data'], 'friend_id');
      $this->apiResponse = $this->UserFriend->sendFriendRequest($userId, $friendId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
	}
	public function cancel_pending_request() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

		// $this->request->data = array('{"api":"user_friend","action":"cancel_pending_request","data":{"user_id":"5","apiKey":"ce4127191419d557ffa44b5453a30617d1f8e981","friend_id":7},"appGuid":"aonecdad-345hldd-nuhoacfl"}');
		$this->apiAction = 'cancel_pending_request';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $friendId = $this->ZooterRequest->getRequestParam($result['request_data'], 'friend_id');
      $this->apiResponse = $this->UserFriend->cancelPendingRequest($userId, $friendId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
	}
	public function cancel_sent_request() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

		$this->request->data = array('{"api":"user_friend","action":"cancel_sent_request","data":{"user_id":"5","apiKey":"ce4127191419d557ffa44b5453a30617d1f8e981","friend_id":1},"appGuid":"aonecdad-345hldd-nuhoacfl"}');
		$this->apiAction = 'cancel_sent_request';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $friendId = $this->ZooterRequest->getRequestParam($result['request_data'], 'friend_id');
      $this->apiResponse = $this->UserFriend->cancelSentRequest($userId, $friendId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
	}
	public function accept_pending_request() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

		// $this->request->data = array('{"api":"user_friend","action":"accept_pending_request","data":{"user_id":"5","apiKey":"ce4127191419d557ffa44b5453a30617d1f8e981","friend_id":7},"appGuid":"aonecdad-345hldd-nuhoacfl"}');
		$this->apiAction = 'accept_pending_request';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $friendId = $this->ZooterRequest->getRequestParam($result['request_data'], 'friend_id');
      $this->apiResponse = $this->UserFriend->acceptPendingRequest($userId, $friendId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
	}

	public function get_pending_friend_requests() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

		// $this->request->data = array('{"api":"user_friend","action":"get_pending_friend_requests","data":{"user_id":"5","apiKey":"ce4127191419d557ffa44b5453a30617d1f8e981"},"appGuid":"aonecdad-345hldd-nuhoacfl"}');
		$this->apiAction = 'get_pending_friend_requests';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
			$userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $this->apiResponse = $this->UserFriend->getPendingFriendRequests($userId);
		} else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
		}
	}
	public function sent_friend_requests() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

		// $this->request->data = array('{"api":"user_friend","action":"sent_friend_requests","data":{"user_id":"5","apiKey":"ce4127191419d557ffa44b5453a30617d1f8e981"},"appGuid":"aonecdad-345hldd-nuhoacfl"}');
		$this->apiAction = 'sent_friend_requests';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
			$userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $this->apiResponse = $this->UserFriend->sentFriendRequests($userId);
		} else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
		}
	}

	public function get_friendlist() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    
		// $this->request->data = array('{"api":"user_friend","action":"get_friendlist","data":{"user_id":"5","apiKey":"ce4127191419d557ffa44b5453a30617d1f8e981"},"appGuid":"aonecdad-345hldd-nuhoacfl"}');
		$this->apiAction = 'get_friendlist';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
			$userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $this->apiResponse = $this->UserFriend->getFriendlist($userId);
		} else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
		}
	}

	public function toggle_friendship() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));    
		// $this->request->data = array('{"api":"user_friend","action":"toggle_friendship",
		// 																"data":{"user_id":"15","friend_id":"14",
		// 																				"apiKey":"ce4127191419d557ffa44b5453a30617d1f8e981"},
		// 																"appGuid":"aonecdad-345hldd-nuhoacfl"}');
		$this->apiAction = 'toggle_friendship';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
			$userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
			$friendId = $this->ZooterRequest->getRequestParam($result['request_data'], 'friend_id');
      $this->apiResponse = $this->UserFriend->toggleFriendship($userId,$friendId);
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
		$this->UserFriend->recursive = 0;
		$this->set('userFriends', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->UserFriend->exists($id)) {
			throw new NotFoundException(__('Invalid user friend'));
		}
		$options = array('conditions' => array('UserFriend.' . $this->UserFriend->primaryKey => $id));
		$this->set('userFriend', $this->UserFriend->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->UserFriend->create();
			if ($this->UserFriend->save($this->request->data)) {
				$this->Session->setFlash(__('The user friend has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user friend could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$users = $this->UserFriend->User->find('list');
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
		if (!$this->UserFriend->exists($id)) {
			throw new NotFoundException(__('Invalid user friend'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->UserFriend->save($this->request->data)) {
				$this->Session->setFlash(__('The user friend has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user friend could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('UserFriend.' . $this->UserFriend->primaryKey => $id));
			$this->request->data = $this->UserFriend->find('first', $options);
		}
		$users = $this->UserFriend->User->find('list');
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
		$this->UserFriend->id = $id;
		if (!$this->UserFriend->exists()) {
			throw new NotFoundException(__('Invalid user friend'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->UserFriend->delete()) {
			$this->Session->setFlash(__('The user friend has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The user friend could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

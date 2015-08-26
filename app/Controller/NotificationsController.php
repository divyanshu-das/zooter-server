<?php
App::uses('AppController', 'Controller');
/**
 * Notifications Controller
 *
 * @property Notification $Notification
 * @property PaginatorComponent $Paginator
 */
class NotificationsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

	public $api = 'notification';
  public $apiAction;

	public $apiEndPoints = array('create','create_for_room','update_notification_read');

	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('create','create_for_room','update_notification_read');

		if(in_array($this->action, $this->apiEndPoints)){
      $this->autoRender = false;
    }
	}

	public function create_for_room() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"notification","action":"create_for_room","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"room_id":"24","notification_data":""
    //                                 }}');
    $this->apiAction = 'create_for_room';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
    	$roomId = $this->ZooterRequest->getRequestParam($result['request_data'], 'room_id');
      $notificationData = $this->ZooterRequest->getRequestParam($result['request_data'], 'notification_data');
      $this->apiResponse = $this->Notification->createRoomNotifications($roomId,$notificationData);
    } else {
      	$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
	}

	public function create() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"notification","action":"create","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"type":"4","users":[{"id": 6},{"id":13}],"date_time":"2015-01-24 12:30:00",
    //                                   "sender_id":"16","direct_link":"match/4","data":"harish sent you a friend request"
    //                                 }}');
    $this->apiAction = 'create';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
    	$type = $this->ZooterRequest->getRequestParam($result['request_data'], 'type');
      $userIdList = $this->ZooterRequest->getRequestParam($result['request_data'], 'users');
      $senderId = $this->ZooterRequest->getRequestParam($result['request_data'], 'sender_id');
      $directLink = $this->ZooterRequest->getRequestParam($result['request_data'], 'direct_link');
      $data = $this->ZooterRequest->getRequestParam($result['request_data'], 'data');
      $dateTime = $this->ZooterRequest->getRequestParam($result['request_data'], 'date_time');
      $this->apiResponse = $this->Notification->createNotification($type,$userIdList,$senderId,$directLink,$data,$dateTime);
    } else {
      	$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
	}

	public function update_notification_read() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"notification","action":"update_notification_read","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{"user_id":"13","notification_id":"78"
    //                                 }}');
    $this->apiAction = 'update_notification_read';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
    	$userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $notificationId = $this->ZooterRequest->getRequestParam($result['request_data'], 'notification_id');
      $this->apiResponse = $this->Notification->updateNotificationRead($userId,$notificationId);
    } else {
      	$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
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

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Notification->recursive = 0;
		$this->set('notifications', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Notification->exists($id)) {
			throw new NotFoundException(__('Invalid notification'));
		}
		$options = array('conditions' => array('Notification.' . $this->Notification->primaryKey => $id));
		$this->set('notification', $this->Notification->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Notification->create();
			if ($this->Notification->save($this->request->data)) {
				return $this->flash(__('The notification has been saved.'), array('action' => 'index'));
			}
		}
		$users = $this->Notification->User->find('list');
		$senders = $this->Notification->Sender->find('list');
		$groups = $this->Notification->Group->find('list');
		$teams = $this->Notification->Team->find('list');
		$matches = $this->Notification->Match->find('list');
		$fanClubs = $this->Notification->FanClub->find('list');
		$this->set(compact('users', 'senders', 'groups', 'teams', 'matches', 'fanClubs'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Notification->exists($id)) {
			throw new NotFoundException(__('Invalid notification'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Notification->save($this->request->data)) {
				return $this->flash(__('The notification has been saved.'), array('action' => 'index'));
			}
		} else {
			$options = array('conditions' => array('Notification.' . $this->Notification->primaryKey => $id));
			$this->request->data = $this->Notification->find('first', $options);
		}
		$users = $this->Notification->User->find('list');
		$senders = $this->Notification->Sender->find('list');
		$groups = $this->Notification->Group->find('list');
		$teams = $this->Notification->Team->find('list');
		$matches = $this->Notification->Match->find('list');
		$fanClubs = $this->Notification->FanClub->find('list');
		$this->set(compact('users', 'senders', 'groups', 'teams', 'matches', 'fanClubs'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Notification->id = $id;
		if (!$this->Notification->exists()) {
			throw new NotFoundException(__('Invalid notification'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Notification->delete()) {
			return $this->flash(__('The notification has been deleted.'), array('action' => 'index'));
		} else {
			return $this->flash(__('The notification could not be deleted. Please, try again.'), array('action' => 'index'));
		}
	}
}

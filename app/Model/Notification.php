<?php
App::uses('AppModel', 'Model');
App::uses('Limit', 'Lib/Enum');
/**
 * Notification Model
 *
 * @property User $User
 * @property Group $Group
 * @property Team $Team
 * @property Match $Match
 */
class Notification extends AppModel {

	public $validate = array(
    'type' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'type is not numeric',
        'required' => true,
        'allowEmpty' => false,
        'on' => 'create'
      ),
    ),
    'user_id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'user id is not numeric',
        'required' => true,
        'allowEmpty' => false,
        'on' => 'create'
      ),
    ),
    'sender_id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'sender id is not numeric',
        'required' => true,
        'allowEmpty' => false,
        'on' => 'create'
      ),
    ),
    'is_read' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				'required' => true,
				'allowEmpty' => false,
				'message' => 'is_read value not valid',
				'on' => 'create'
			),
		)
  );


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Sender' => array(
			'className' => 'User',
			'foreignKey' => 'sender_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Team' => array(
			'className' => 'Team',
			'foreignKey' => 'team_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Match' => array(
			'className' => 'Match',
			'foreignKey' => 'match_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
    'FanClub' => array(
      'className' => 'FanClub',
      'foreignKey' => 'fan_club_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    )
	);

	public function updateNotificationRead($userId,$notificationId) {
		$userId = trim($userId);
		$notificationId = trim($notificationId);
		if (empty($userId) || empty($notificationId)) {
			return array('status' => 100, 'message' => 'updateNotificationRead : Invalid Input Arguments');
		}
		if (!$this->User->userExists($userId)) {
     	return array('status' => 912, 'message' => 'updateNotificationRead : User Does Not Exist');
    }
    if (!$this->isUserEligibleToUpdate($userId,$notificationId)) {
    	return array('status' => 103, 'message' => 'updateNotificationRead : User Not Eligible to update');
    }
    $data = array(
    	'Notification' => array(
    		'id' => $notificationId,
    		'is_read' => true
    	)
    );
   	if ($this->save($data)) {
   		$response = array('user_id' => $userId, 'notification_id' => $notificationId);
      return  array('status' => 200 , 'message' => 'success','data' => $response);
    } else return array('status' => 590, 'message' => 'updateNotificationRead : Notification could not be updated');

	}

	

	public function getNewNotificationsCount($userId , $lastSeenNotificationTime) {
		$count = $this->find('count' , array(
      'conditions' => array(
      	'user_id' => $userId,
        'created >=' => $lastSeenNotificationTime
        )
    ));
    return $count;
	}

	public function fetchNotifications($userId, $lastSeenNotificationTime) {
		$totalNotificationsLimit = Limit::NUM_OF_USER_NOTIFICATIONS;
		$fields = array('id','type','data','direct_link','created','is_read');
		$contain = array(
   		'Sender' => array(
   			'fields' => array('id'),
   			'Profile' => array(
   				'fields' => array('id','first_name','middle_name','last_name','image_id'),
   				'ProfileImage' => array(
   					'fields' => array('id','url')
   				)
   			)
   		)
   	);
		$newNotifications = $this->find('all', array(
     'conditions' => array(
     	 'Notification.user_id' =>$userId,
     	 'Notification.created >=' => $lastSeenNotificationTime
     	),
     'fields' => $fields,
     'contain' => $contain,
     'order' => 'Notification.created DESC'
		));
		
		$newNotificationsCount = count($newNotifications);
		$oldNotifications = array();
		if ($newNotificationsCount < $totalNotificationsLimit) {
			$oldNotifications = $this->find('all',array(
				'conditions' => array(
	     	 'Notification.user_id' =>$userId,
	     	 'Notification.created <' => $lastSeenNotificationTime
	     	),
	     	'fields' => $fields,
	     	'contain' => $contain,
	      'order' => 'Notification.created DESC',
	      'limit' => ($totalNotificationsLimit - $newNotificationsCount)
			));
		}
		$notifications = $this->__concatArray($newNotifications,$oldNotifications);

		$notificationData = array();
		$allData = array();
		$newData = array();
		foreach ($notifications as $key => $notification) {
			$notificationData['id'] = $notification['Notification']['id'];
			$notificationData['type'] = $notification['Notification']['type'];
			$notificationData['data'] = json_decode($notification['Notification']['data']);
			$notificationData['is_read'] = $notification['Notification']['is_read'];
			$notificationData['direct_link'] = $notification['Notification']['direct_link'];
			$notificationData['created_on'] = $notification['Notification']['created'];
			if (!empty($notification['Sender']['Profile']['ProfileImage'])) {
				$image = $notification['Sender']['Profile']['ProfileImage']['url'];
			} else $image = NULL;
			$name = $this->__prepareUserName($notification['Sender']['Profile']['first_name'],
																			 $notification['Sender']['Profile']['middle_name'],
																			 $notification['Sender']['Profile']['last_name']);
			$notificationData['sender']['id'] = $notification['Sender']['id'];
			$notificationData['sender']['name'] = $name;
			$notificationData['sender']['image'] = $image;
			if ($key < $newNotificationsCount) {
				$newData[$key] = $notificationData;
				$allData[$key] = $notificationData;
			} else $allData[$key] = $notificationData;
		}
		return array('new' => $newData, 'all' => $allData);
	}

	public function createRoomNotifications($roomId, $notificationData) {
		if (empty($notificationData) || empty($roomId)) {
			return array('status' => 100, 'message' => 'createRoomNotifications : Invalid Input Arguments');
		}
		if (!$this->User->RoomMember->Room->roomExists($roomId)) {
			return array('status' => 813 , 'message' => 'createRoomNotifications : Room does not exist');
		}
		$userIdList = $this->User->RoomMember->find('list',array(
			'conditions' => array(
				'room_id' => $roomId
			),
			'fields' => array('user_id')
		));
		if (empty($userIdList)) {
			return array('status' => 105, 'message' => 'createRoomNotifications : No users to create room notifications');
		}
		if (($key = array_search($notificationData['sender_id'], $userIdList)) !== false) {
    	array_splice($userIdList, $key, 1);
		}
		if (!empty($notificationData['type']) && !empty($notificationData['sender_id']) 
					&& !empty($notificationData['data']) && !empty($notificationData['direct_link']) 
						&& !empty($notificationData['created_on'])) {
			return $this->createNotification(
				$notificationData['type'],
				$userIdList,
				$notificationData['sender_id'],
				$notificationData['direct_link'],
				json_encode($notificationData['data']),
				$notificationData['created_on']
			);
		} else return array('status' => 100, 'message' => 'createRoomNotifications : Invalid input data in notification data parmeter');
	}

	public function createNotification($type,$userIdList,$senderId,$directLink,$data,$dateTime) {
		$type = trim($type);
		$senderId = trim($senderId);
		$directLink = trim($directLink);
		$data = trim($data);
		$dateTime = trim($dateTime);
		if (empty($type) || empty($senderId) || empty($directLink) || empty($data) || empty($userIdList)) {
			return array('status' => 100 , 'message' => 'createNotification : Invalid Input Arguments');
		}
		if (!$this->User->userExists($senderId)) {
			return array('status' => 912 , 'message' => 'createNotification : sender ID does not exist');	
		}
		if (empty($dateTime)) {
			$dateTime = date('Y-m-d H:i:s');
		}
		$notificationData = array();
		$index = 0;
		foreach ($userIdList as $key => $userId) {
			if ($this->User->userExists($userId)) {
				if ($userId != $senderId) {
					$notificationData[$index]['Notification']['type'] = NotificationType::intValue($type);
					$notificationData[$index]['Notification']['user_id'] = $userId;
					$notificationData[$index]['Notification']['sender_id'] = $senderId;
					$notificationData[$index]['Notification']['direct_link'] = $directLink;
					$notificationData[$index]['Notification']['data'] = $data;
					$notificationData[$index]['Notification']['is_read'] = 0;	
					$notificationData[$index]['Notification']['created'] = $dateTime;			
					$notificationData[$index]['Notification']['modified'] = $dateTime;
					$index = $index + 1;
				}
			} else return array('status' => 912, 'message' => 'createNotification : User Id in Input Does not exist');
		}
		if (!empty($notificationData)) {
			if ($this->saveMany($notificationData)) {
				$responseData = $this->__prepareNotficationResponseData($notificationData);
				$response = array('status' => 200, 'data' => $responseData, 'message' => 'success');
			} else {
					$response = array('status' => 812, 'message' => 'createNotification : Notification could not be created');
			} 
		} else {
				$response = array('status' => 813, 'message' => 'createNotification : No data to create Notification');
		}
		return $response;
	}

	private function __prepareNotficationResponseData($notificationData) {
		$response = array();
		foreach ($notificationData as $key => $data) {
			$response[$key] = $data['Notification'];
		}
		return $response;
	}

	private function __concatArray($newNotifications,$oldNotifications) {
		$finalArray = $newNotifications;
		foreach ($oldNotifications as $data) {
		 	array_push($finalArray, $data);
		 } 
		 return $finalArray;
	}

	private function isUserEligibleToUpdate($userId,$notificationId) {
		return $this->find('count',array(
			'conditions' => array(
				'id' => $notificationId,
				'user_id' => $userId
			)
		));
	}

	private function __prepareUserName($firstName, $middleName, $lastName){
    $name = NULL;
    if (!empty($firstName)) {
      $name = $firstName." ";
    }
    if (!empty($middleName)) {
      $name = $name.$middleName." ";
    }
    if (!empty($lastName)) {
      $name = $name.$lastName." ";
    }
    return $name;
  }

}

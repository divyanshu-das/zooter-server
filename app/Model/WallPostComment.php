<?php
App::uses('AppModel', 'Model');
App::uses('NotificationType', 'Lib/Enum');
/**
 * WallPostComment Model
 *
 * @property User $User
 * @property WallPost $WallPost
 */
class WallPostComment extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	// public $validate = array(
	// 	'comment' => array(
	// 		'notEmpty' => array(
	// 			'rule' => array('notEmpty'),
	// 			//'message' => 'Your custom message here',
	// 			//'allowEmpty' => false,
	// 			//'required' => false,
	// 			//'last' => false, // Stop validation after this rule
	// 			//'on' => 'create', // Limit validation to 'create' or 'update' operations
	// 		),
	// 	),
	// );

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
		'WallPost' => array(
			'className' => 'WallPost',
			'foreignKey' => 'wall_post_id',
			'counterCache' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => true
		)
	);
	public function addComment($userId, $wallPostId, $comment) {
		$comment = trim($comment);
		$userId = trim($userId);
		$wallPostId = trim($wallPostId);
		if (empty($userId) || empty($wallPostId) || empty($comment)) {
			return array('status' => 100, 'message' => 'addComment : Invalid Input Arguments');
		}
		if (!$this->User->userExists($userId)) {
			return array('status' => 812 , 'message' => 'addComment : User does not exist');
		}
		if (!$this->WallPost->wallPostExists($wallPostId)) {
			return array('status' => 813 , 'message' => 'addComment : Wall Post Id does not exist');
		}
		$data = array(
			'user_id' => $userId,
			'comment' => $comment,
			'wall_post_id' => $wallPostId
		);
		$this->create();
    $roomId = $this->WallPost->getWallPostRoom($wallPostId);
    if ($roomId['status'] != 200) {
    	return array('status' => $roomId['status'], 'message' => $roomId['message']); 
    } else $roomId = $roomId['data'];
    $dataSource = $this->getDataSource();
    $dataSource->begin();
		if ($this->save($data)) {
			$addUserInRoom = $this->User->RoomMember->addUser($roomId,$userId);
			if($addUserInRoom['status'] == 200) {
				$responseData = $this->__prepareCommentResponse($this->getLastInsertID());
				$response = array('status' => 200, 'data' => $responseData);
			} else $response = array('status' => $addUserInRoom['status'], 'message' => $addUserInRoom['message']);
		} else $response = array('status' => 601, 'message' => 'addComment : Comment not saved');

		if ($response['status'] == 200) {
      $dataSource->commit();
      $notifyResponse = $this->User->Notification->createRoomNotifications($roomId,$responseData['notification']);
			if ($notifyResponse['status'] != 200) {
				$response['message'] = $notifyResponse['message'];
			}
    } else $dataSource->rollback();
    		
		return $response;
	}

	private function __prepareCommentResponse($commentId) {
		$data = $this->find('first',array(
			'conditions' => array(
				'WallPostComment.id' => $commentId
			),
			'fields' => array('id','user_id','comment','wall_post_id','created'),
			'contain' => array(
				'WallPost' =>array(
					'fields' => array('id','room_id','image_id','video_id'),
					'Image' => array(
	          'fields' => array('id','url')
	        ),
	        'Video' => array(
	          'fields' => array('id','url')
	        ),
				),
				'User' => array(
					'fields' => array('id'),
					'Profile' => array(
						'fields' => array('first_name','middle_name','last_name','image_id'),
						'ProfileImage' => array(
							'fields' => array('id','url')
						)
					)
				)
			)
		));
		$responseData['id'] = $data['WallPostComment']['id'];
		$responseData['wall_post_id'] = $data['WallPostComment']['wall_post_id'];
		$responseData['comment_text'] = $data['WallPostComment']['comment'];
		$responseData['date'] = $data['WallPostComment']['created'];
		$responseData['user']['id'] = $data['User']['id'];
		$responseData['user']['name'] = $this->User->__prepareUserName($data['User']['Profile']['first_name'],
																																		$data['User']['Profile']['middle_name'],
																																		$data['User']['Profile']['last_name']);
		if (!empty($data['User']['Profile']['ProfileImage'])) {
			$responseData['user']['image'] = $data['User']['Profile']['ProfileImage']['url'];
		} else $responseData['user']['image'] = null;
		$responseData['room_id'] = $data['WallPost']['room_id'];
		$responseData['notification']['type'] = NotificationType::stringValue(NotificationType::COMMENT);
		$responseData['notification']['data']['text'] = "commented on the wall post";
		$responseData['notification']['direct_link'] = "/posts/wall:".$data['WallPostComment']['wall_post_id'];
		$responseData['notification']['is_read'] = false;
		if (!empty($data['WallPost']['image_id'])) {
			$responseData['notification']['data']['has_image'] = true;
			if (!empty($data['WallPost']['Image'])) {
				$responseData['notification']['data']['image_url'] = $data['WallPost']['Image']['url'];
			} else $responseData['notification']['data']['image_url'] = null;			
		} else {
				$responseData['notification']['data']['has_image'] = false;
				$responseData['notification']['data']['image_url'] = null;	
		}
		if (!empty($data['WallPost']['video_id'])) {
			$responseData['notification']['data']['has_video'] = true;
			if (!empty($data['WallPost']['Video'])) {
				$responseData['notification']['data']['video_url'] = $data['WallPost']['Video']['url'];
			} else $responseData['notification']['data']['video_url'] = null;			
		} else {
				$responseData['notification']['data']['has_video'] = false;
				$responseData['notification']['data']['video_url'] = null;	
		}
		$responseData['notification']['created_on'] = $data['WallPostComment']['created'];
		$responseData['notification']['sender_id'] = $responseData['user']['id'];
		return $responseData;
	}
}

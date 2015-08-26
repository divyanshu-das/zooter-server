<?php
App::uses('AppModel', 'Model');
App::uses('NotificationType', 'Lib/Enum');
/**
 * WallPostLike Model
 *
 * @property User $User
 * @property WallPost $WallPost
 */
class WallPostLike extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'wall_post_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
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
		'WallPost' => array(
			'className' => 'WallPost',
			'foreignKey' => 'wall_post_id',
			'counterCache' => true,
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function toggleLike($userId, $wallPostId) {
		$userId = trim($userId);
		$wallPostId = trim($wallPostId);
		if (empty($userId) || empty($wallPostId)) {
			return array('status' => 100, 'message' => 'toggleLike : Invalid Input Arguments');
		}
		if (!$this->User->userExists($userId)) {
			return array('status' => 812 , 'message' => 'toggleLike : User does not exist');
		}
		if (!$this->WallPost->wallPostExists($wallPostId)) {
			return array('status' => 813 , 'message' => 'toggleLike : Wall Post Id does not exist');
		}
		$count = $this->find('count', array(
			'conditions' => array(
				'WallPostLike.wall_post_id' => $wallPostId,
				'WallPostLike.user_id' => $userId,
			)
		));
		if (!$count){
			$data = array(
				'user_id' => $userId,
				'wall_post_id' => $wallPostId,
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
					$responseData = $this->__prepareLikeResponseData($this->getLastInsertID());
					$response = array('status' => 200, 'data' => $responseData, 'message' => 'Post Liked');
				} else $response = array('status' => $addUserInRoom['status'], 'message' => $addUserInRoom['message']);
			} else $response = array('status' => 602, 'message' => 'Like not saved');

			if ($response['status'] == 200) {
	      $dataSource->commit();
	      $notifyResponse = $this->User->Notification->createRoomNotifications($roomId,$responseData['notification']);
				if ($notifyResponse['status'] != 200) {
					$response['message'] = $notifyResponse['message'];
				}
	    } else $dataSource->rollback();

		} else {
				$this->deleteAll(array(
					'WallPostLike.wall_post_id' => $wallPostId,
					'WallPostLike.user_id' => $userId),
					false
				);
				$response = array('status' => 200, 'data' => array('liked' => false), 'message' => 'Disliked');
				$this->updateCounterCache(array('wall_post_id' => $wallPostId));
		}
		return $response;
	}

	private function __prepareLikeResponseData($likeId) {
		$data = $this->find('first',array(
			'conditions' => array(
				'WallPostLike.id' => $likeId
			),
			'fields' => array('id','user_id','wall_post_id','created'),
			'contain' => array(
				'WallPost' =>array(
					'fields' => array('id','room_id','wall_post_like_count','image_id','video_id'),
					'Image' => array(
	          'fields' => array('id','url')
	        ),
	        'Video' => array(
	          'fields' => array('id','url')
	        )
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
		$responseData['id'] = $data['WallPostLike']['id'];
		$responseData['wall_post_id'] = $data['WallPostLike']['wall_post_id'];
		$responseData['count'] = $data['WallPost']['wall_post_like_count'];
		$responseData['liked'] = true;
		$responseData['liked_by']['id'] = $data['User']['id'];
		$responseData['liked_by']['name'] = $this->User->__prepareUserName($data['User']['Profile']['first_name'],
																																		$data['User']['Profile']['middle_name'],
																																		$data['User']['Profile']['last_name']);
		if (!empty($data['User']['Profile']['ProfileImage'])) {
			$responseData['liked_by']['image'] = $data['User']['Profile']['ProfileImage']['url'];
		} else $responseData['liked_by']['image'] = null;
		$responseData['room_id'] = $data['WallPost']['room_id'];
		$responseData['notification']['type'] = NotificationType::stringValue(NotificationType::LIKE);
		$responseData['notification']['data']['text'] = "liked on the wall post";
		$responseData['notification']['direct_link'] = "/posts/wall:".$data['WallPostLike']['wall_post_id'];
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
		$responseData['notification']['created_on'] = $data['WallPostLike']['created'];
		$responseData['notification']['sender_id'] = $responseData['liked_by']['id'];
		return $responseData;
	}
}

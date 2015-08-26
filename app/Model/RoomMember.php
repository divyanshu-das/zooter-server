<?php
App::uses('AppModel', 'Model');
/**
 * RoomMember Model
 *
 * @property Room $Room
 * @property User $User
 */
class RoomMember extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Room' => array(
			'className' => 'Room',
			'foreignKey' => 'room_id',
			'counterCache' => true,
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function addUser($roomId,$userId) {
		$roomId = trim($roomId);
		$userId = trim($userId);
		if (empty($roomId) || empty($userId)) {
			return array('status' => 100, 'message' => 'addUser Room member : Invalid Input Arguments');
		}
		if (!$this->User->userExists($userId)) {
			return array('status' => 812 , 'message' => 'addUser Room member : User does not exist');
		}
		if (!$this->Room->roomExists($roomId)) {
			return array('status' => 813 , 'message' => 'addUser Room member : Room does not exist');
		}
		if ($this->userAlreadyInRoom($roomId,$userId)) {
			return array('status' => 200, 'message' => 'addUser Room member : User already in Room','data' => true);
		}
		$data = array(
			'room_id' => $roomId,
			'user_id' => $userId
		);
		$this->create();
		if ($this->save($data)) {
			$response = array('status' => 200, 'message' => 'success','data' => $this->getLastInsertID());
		} else $response = array('status' => 109, 'message' => 'addUser Room member : User Could not be added in Room');
		return $response;
	}

	public function userAlreadyInRoom($roomId,$userId) {
		return $this->find('count',array(
			'conditions' => array(
				'room_id' => $roomId,
				'user_id' => $userId
			)
		));
	}

}

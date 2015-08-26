<?php
App::uses('AppModel', 'Model');
/**
 * Room Model
 *
 * @property RoomMember $RoomMember
 */
class Room extends AppModel {

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'RoomMember' => array(
			'className' => 'RoomMember',
			'foreignKey' => 'room_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public $hasOne = array(
		'WallPost' => array(
			'className' => 'WallPost',
			'foreignKey' => 'room_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function roomExists($roomId) {
		return $this->find('count',array(
			'conditions' => array(
				'id' => $roomId
			)
		));
	}

}

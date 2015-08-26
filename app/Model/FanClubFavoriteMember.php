<?php
App::uses('AppModel', 'Model');
/**
 * FanClubFavoriteMember Model
 *
 * @property FanClub $FanClub
 * @property User $User
 */
class FanClubFavoriteMember extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'FanClub' => array(
			'className' => 'FanClub',
			'foreignKey' => 'fan_club_id',
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
}

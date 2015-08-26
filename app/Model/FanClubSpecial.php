<?php
App::uses('AppModel', 'Model');
/**
 * FanClubSpecial Model
 *
 * @property FanClub $FanClub
 * @property User $User
 */
class FanClubSpecial extends AppModel {


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

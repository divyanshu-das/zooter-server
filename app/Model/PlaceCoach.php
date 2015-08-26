<?php
App::uses('AppModel', 'Model');
/**
 * PlaceCoach Model
 *
 * @property Place $Place
 * @property User $User
 */
class PlaceCoach extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Place' => array(
			'className' => 'Place',
			'foreignKey' => 'place_id',
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

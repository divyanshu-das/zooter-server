<?php
App::uses('AppModel', 'Model');
/**
 * PlaceFacility Model
 *
 * @property Place $Place
 * @property Ground $Ground
 */
class PlaceFacility extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'place_id' => array(
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
		'Place' => array(
			'className' => 'Place',
			'foreignKey' => 'place_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Ground' => array(
			'className' => 'Ground',
			'foreignKey' => 'ground_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

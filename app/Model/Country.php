<?php
App::uses('AppModel', 'Model');
/**
 * Country Model
 *
 */
class Country extends AppModel {

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Series' => array(
			'className' => 'Series',
			'foreignKey' => 'country_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	);


}

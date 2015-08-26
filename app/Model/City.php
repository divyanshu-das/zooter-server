<?php
App::uses('AppModel', 'Model');
/**
 * City Model
 *
 * @property State $State
 */
class City extends AppModel {

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'State' => array(
			'className' => 'State',
			'foreignKey' => 'state_id',
			'counterCache' => true,
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public $hasMany = array(
		'Location' => array(
			'className' => 'Location',
			'foreignKey' => 'city_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

	public function getOrSaveCity($name) {
		$name = trim($name);
		if (empty($name)) {
			return array('status' => 100, 'message' => 'getCity : Invalid Input Arguments');
		}
		$ifAlreadyExist = $this->find('first',array(
			'conditions' => array(
				'name' => $name
			),
			'fields' => array('id')
		));
		if (!empty($ifAlreadyExist['City']['id'])) {
			$response = array('status' => 200, 'data' => $ifAlreadyExist['City']['id']);
		} else {
				$data = array(
					'name' => $name
				);
				if ($this->save($data)) {
					$response = array('status' => 200, 'data' => $this->getLastInsertID());
				} else $response = array('status' => 108, 'message' => 'getCity : City could not be saved');
		}
		return $response;
	}
	
}

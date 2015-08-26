<?php
App::uses('AppModel', 'Model');
/**
 * Ground Model
 *
 * @property Location $Location
 * @property PlaceFacility $PlaceFacility
 */
class Ground extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Location' => array(
			'className' => 'Location',
			'foreignKey' => 'location_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'PlaceFacility' => array(
			'className' => 'PlaceFacility',
			'foreignKey' => 'ground_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	public function saveGround($groundData) {
		$groundId = null;
		$ground = $this->find('first', array(
			'conditions' => array(
				'Ground.name' => $groundData['name'],
				'Ground.location_id' => $groundData['location_id']
			)
		));
		if (empty($ground)) {
			$this->create();
			if ($this->save($groundData)) {
				$groundId = $this->getLastInsertID();
			}
		} else {
			$groundId = $ground['Ground']['id'];
		}
		return $groundId;
	}

	public function getSuggestions($key) {
		$grounds = $this->find('list', array(
			'fields' => array('id', 'name'),
			'conditions' => array(
				'Ground.name LIKE' => $key . '%'
			)
		));
		$data = array();
		if (!empty($grounds)) {
			$count = 0;
			foreach ($grounds as $id => $ground) {
				$data[$count]['id'] = $id;
				$data[$count]['name'] = $ground;
				$count++;
			}
		}
		$response = array('status' => 200, 'message' => 'success', 'data' => $data);
		return $response;
	}
}

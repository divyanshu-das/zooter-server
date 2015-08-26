<?php
App::uses('AppModel', 'Model');
/**
 * ApiKey Model
 *
 * @property User $User
 */
class ApiKey extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'api_key';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'api_key' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
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
		)
	);

	public function generateApiKey() {
		return sha1(uniqid());
	}

	public function getLatestApiKeyForUser($userId) {
		$apiKey = $this->find('first', array(
			'conditions' => array(
				'ApiKey.user_id' => $userId
			),
			'order' => array('ApiKey.created' => 'DESC')
		));
		return $apiKey['ApiKey']['api_key'];
	}
}

<?php
App::uses('AppModel', 'Model');
/**
 * Profile Model
 *
 * @property User $User
 * @property City $City
 */
class Profile extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'first_name';


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
		),
		'Location' => array(
			'className' => 'Location',
			'foreignKey' => 'location_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ProfileImage' => array(
      'className' => 'Image',
      'foreignKey' => 'image_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    ),
    'CoverImage' => array(
      'className' => 'Image',
      'foreignKey' => 'cover_image_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    )
	);

	/**
	 * beforeSave callback
	 *
	 * @param $options array
	 * @return boolean
	 */
		public function beforeSave($options = array()) {
			return true;
		}
	

	public function editPersonalInfo($userId,$profileData) {
		if (empty($userId) || empty($profileData)) {
			return array('status' => 100, 'message' => 'editPersonalInfo : Invalid Input Arguments');
		}
		if (!$this->_userExists($userId)) {
      return array('status' => 332 , 'message' => 'editPersonalInfo : User Does not exist');
    }
		$profile = $this->findByUserId($userId);
		$data = array();
		$data['Profile']['id'] = $profile['Profile']['id'];
		foreach ($profileData as $key => $value) {
			switch ($key) {
				case 'description':
					$data['Profile']['description'] = trim($value);
					break;
				case 'first_name':
					$data['Profile']['first_name'] = trim($value);
					break;
				case 'middle_name':
					$data['Profile']['middle_name'] = trim($value);
					break;
				case 'last_name':
					$data['Profile']['last_name'] = trim($value);
					break;
				case 'date_of_birth':
					$data['Profile']['date_of_birth'] = trim($value);
					break;
				case 'gender':
					$data['Profile']['gender'] = trim($value);
					break;
				case 'email':
					$data['User']['id'] = $userId;
					$data['User']['email'] = trim($value);
					break;
				case 'mobile':
					$data['User']['id'] = $userId;
					$data['User']['phone'] = trim($value);
					break;
				case 'cover_image':
					$data['CoverImage']['url'] = trim($value);
					break;
				case 'profile_image':
					$data['ProfileImage']['url'] = trim($value);
					break;
			}
		}
		if (empty($data)) {
			return array('status' => 104 , 'message' => 'editPersonalInfo : No Data sent for update');
		}

		if ($this->saveAssociated($data)) {
			return array('status' => 200, 'data' => $profileData, 'message' => 'success');
		} else {
			return array('status' => 103, 'message' => 'editPersonalInfo : profile edit was unsuccessful');
		}
	}

	public function editLocation($userId, $locationName, $latitude, $longitude, $uniqueIdentifier) {
		$locationId = $this->Location->saveLocation($locationName, $latitude, $longitude, $uniqueIdentifier);
		if ($locationId['status'] == 200) {
			$profile = $this->findByUserId($userId);
			$profile = $profile['Profile'];
			$data = array(
				'Profile' => array(
					'id' => $profile['id'],
					'location_id' => $locationId['data']
				)
			);
			if ($this->save($data)) {
				$location = $this->Location->findById($locationId['data']);
				$city = $this->Location->City->findById($location['Location']['city_id']);
				$responseData['id'] = $location['Location']['id'];
				$responseData['name'] = $location['Location']['name'];
				if (!empty($city['City'])) {
					$responseData['city'] = $city['City']['name'];
				} else {
					$responseData['city'] = null;
				}
				$response = array('status' => 200 , 'data' => $responseData, 'message' => 'success');
			} else {
				$response = array('status' => 112 , 'message' => 'editLocation : Location Edit was unsuccesful');
			}
		} else {
			$response = array('status' => $locationId['status'] , 'message' => $locationId['message']);
		}
		return $response;
	}

}

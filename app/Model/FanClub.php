<?php
App::uses('AppModel', 'Model');
App::uses('FavoriteType', 'Lib/Enum');
App::uses('UserType', 'Lib/Enum');
/**
 * FanClub Model
 *
 * @property User $User
 * @property Image $Image
 * @property FanClubFavoriteMember $FanClubFavoriteMember
 * @property FanClubFavorite $FanClubFavorite
 * @property Notification $Notification
 */
class FanClub extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
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
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'FanClubFavoriteMember' => array(
			'className' => 'FanClubFavoriteMember',
			'foreignKey' => 'fan_club_id',
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
		'FanClubFavorite' => array(
			'className' => 'FanClubFavorite',
			'foreignKey' => 'fan_club_id',
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
		'Notification' => array(
			'className' => 'Notification',
			'foreignKey' => 'fan_club_id',
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
	/**
	 * beforeSave callback
	 *
	 * @param $options array
	 * @return boolean
	 */
		public function beforeSave($options = array()) {
			return true;
		}
	

	public function createFanClub($userId, $name, $imageUrl, $coverImageUrl, $favorite, $tagline) {
		$this->create();
		$data = array(
			'FanClub' => array('name' => $name, 'tagline' => $tagline, 'user_id' => $userId),
			'ProfileImage' => array('url' => $imageUrl),
			'CoverImage' => array('url' => $coverImageUrl)
		);
		$fanClub = $this->findByName($name);
		if ( ! empty($fanClub[$this->alias]['id'])) {
			$data['FanClub']['id'] = $fanClub[$this->alias]['id'];
		}
		$image = $this->ProfileImage->findByUrl($imageUrl);
		if ( ! empty($image['ProfileImage']['id'])) {
			$data['ProfileImage']['id'] = $image['ProfileImage']['id'];
		}
		$image = $this->CoverImage->findByUrl($imageUrl);
		if ( ! empty($image['CoverImage']['id'])) {
			$data['CoverImage']['id'] = $image['CoverImage']['id'];
		}
		if ($this->saveAssociated($data)) {
			$fanClubId  = $this->getLastInsertID();
			if (empty($fanClubId)) {
				$fanClubId = $fanClub[$this->alias]['id'];
			}
			$responseData = array(
				'id' => $fanClubId,
				'name' => $name
			);
			if ( ! empty($favorite)) {
				$this->FanClubFavorite->updateFanClub($fanClubId, $favorite);
				$response = array('status' => 200, 'data' => $responseData);
			}
		} else {
			$response = array('status' => 600, 'message' => 'Fan Club could not be created');
		}
		return $response;
	}

	public function updateFanClub($id, $userId, $name, $imageUrl, $coverImageUrl, $favorite, $tagline) {
		if ( ! $this->__fanClubExists($id)) {
			$response = array('status' => 603, 'message' => 'Fan Club does not exist');
		} else if ( ! $this->__isUserAuthorized($id, $userId)) {
			$response = array('status' => 604, 'message' => 'You are not authorised to modify this Fan Club');
		} else {
			$data = array(
				'FanClub' => array('id' => $id, 'name' => $name, 'tagline' => $tagline, 'user_id' => $userId),
				'ProfileImage' => array('url' => $imageUrl),
				'CoverImage' => array('url' => $coverImageUrl)
			);
			$fanClub = $this->findByName($name);
			if ( ! empty($fanClub[$this->alias]['id'])) {
				$data['FanClub']['id'] = $fanClub[$this->alias]['id'];
			}
			$image = $this->ProfileImage->findByUrl($imageUrl);
			if ( ! empty($image['ProfileImage']['id'])) {
				$data['ProfileImage']['id'] = $image['ProfileImage']['id'];
			}
			$image = $this->CoverImage->findByUrl($imageUrl);
			if ( ! empty($image['CoverImage']['id'])) {
				$data['CoverImage']['id'] = $image['CoverImage']['id'];
			}
			$this->create();
			if ($this->saveAssociated($data)) {
				$responseData = array(
					'id' => $id,
					'name' => $name
				);
				$this->FanClubFavorite->updateFanClub($id, $favorite);
				$response = array('status' => 200, 'data' => $responseData);
			} else {
				$response = array('status' => 604, 'message' => 'Fan Club could not be updated');
			}
		}
		return $response;
	}

	private function __fanClubExists($id) {
		return $this->find('count', array(
			'conditions' => array(
				'id' => $id
			)
		));
	}

	private function __isUserAuthorized($id, $userId) {
		return $this->find('count', array(
			'conditions' => array(
				'id' => $id,
				'user_id' => $userId
			)
		));
	}

	public function viewFanClub($fanClubId, $userId) {
		// fanClubFeed
		// fanClubProfile
		// Fan count
		
	}

	public function searchFanClub($input,$numOfRecords) {
		if (empty($input)) {
			return array('status' => 100, 'message' => 'searchFanClub : Invalid Input Arguments');
		}
		$fanclubDataArray = array();
		$fanclubData = $this->find('all',array(
			'conditions' => array(
				'FanClub.name LIKE' => "%$input%"
			),
			'fields' => array('id','name','image_id','fan_club_members_count'),
			'contain' => array(
				'ProfileImage' => array(
          'fields' => array('id','url')
        )
			),
			'limit' => $numOfRecords
		));
		foreach ($fanclubData as $index => $fanclub) {
			$fanclubDataArray[$index]['id'] = $fanclub['FanClub']['id'];
			$fanclubDataArray[$index]['name'] = $fanclub['FanClub']['name'];
			if (!empty($fanclub['ProfileImage'])) {
				$fanclubDataArray[$index]['image'] = $fanclub['ProfileImage']['url'];
			} else {
				$fanclubDataArray[$index]['image'] = NULL;
			}
			$fanclubDataArray[$index]['total_members'] = $fanclub['FanClub']['fan_club_members_count'];
		}
		return array('status' => 200 , 'data' => $fanclubDataArray);
	}

}

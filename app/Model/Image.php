<?php
App::uses('AppModel', 'Model');
/**
 * Image Model
 *
 * @property Album $Album
 * @property Location $Location
 * @property User $User
 * @property FanClub $FanClub
 * @property ImageComment $ImageComment
 */
class Image extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'caption';

	public $validate = array(
		'url' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		)
	);


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Album' => array(
			'className' => 'Album',
			'foreignKey' => 'album_id',
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
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Match' => array(
			'className' => 'Match',
			'foreignKey' => 'match_id',
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
		'FanClubProfileImage' => array(
			'className' => 'FanClub',
			'foreignKey' => 'image_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
		),
		'FanClubCoverImage' => array(
			'className' => 'FanClub',
			'foreignKey' => 'cover_image_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'AlbumCoverImage' => array(
			'className' => 'Album',
			'foreignKey' => 'cover_image_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ImageComment' => array(
			'className' => 'ImageComment',
			'foreignKey' => 'image_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'PlacesImage' => array(
			'className' => 'PlacesImage',
			'foreignKey' => 'image_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'PlaceReview' => array(
			'className' => 'PlaceReview',
			'foreignKey' => 'image_id',
			'dependent' => false,
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

	public function editImage($userId,$fields) {
		if (empty($fields) || empty($userId)) {
			return array('status' => 100 , 'message' => 'editImage : Invlid Input Arguments');
		}
		if (!$this->_userExists($userId)) {
      return array('status' => 332 , 'message' => 'editImage : User Does not exist');
    }
    $dataSource = $this->getDataSource();
    $dataSource->begin();
		$data = array();
		foreach ($fields as $key => $value) {
			if(!empty($value)) {
				switch ($key) {
					case 'id':
						$data['Image']['id'] = trim($value);
						break;
					case 'caption':
						$data['Image']['caption'] = trim($value);
						break;
					case 'snap_date_time':
						$data['Image']['snap_date_time'] = trim($value);
						break;
					case 'location':
						$locationId = $this->Location->saveLocation($value['place'], $value['latitude'], $value['longitude'], $value['unique_identifier']);
						if ($locationId['status'] == 200) {
							$data['Image']['location_id'] = $locationId['data'];
						} else {
							return array('status' => $locationId['status'], 'message' => 'editImage : '.$locationId['message']);
						}
						break;
				}
			}
		}
		if (empty($data['Image']['id'])) {
			return array('status' => 100, 'message' => 'editImage : Id field is mandatory');
		}
		if ( ! ( $this->isValidImageId($data['Image']['id']) && $this->isUserEligibleToUpdate($data['Image']['id'],$userId) ) ) {
			return array('status' => 119, 'message' => 'editImage : Image Id invalid Or User ineligible to update');
		}
		if (empty($data)) {
			return array('status' => 104 , 'message' => 'editImage : No Data sent for update');
		}

		if ($this->save($data)) {
			$response = array('status' => 200, 'data' => array('image' => $this->findById($data['Image']['id'])['Image']), 'message' => 'success');
		} else {
			$response = array('status' => 119 , 'message' => 'editImage : Edit image details unsuccessful');
		}

		if ($response['status'] == 200) {
      $dataSource->commit();
    } else {
    	$dataSource->rollback();
    }
		return $response;
	}

	public function deleteMatchPic($userId,$matchId,$imageId) {
		$userId = trim($userId);
		$matchId = trim($matchId);
		$imageId = trim($imageId);
		if (empty($matchId) || empty($userId) || empty($imageId)) {
			return array('status' => 100 , 'message' => 'deleteMatchPic : Invalid Input Arguments');
		}
		if (!$this->_userExists($userId)) {
      return array('status' => 332 , 'message' => 'deleteMatchPic : User Does not exist');
    }
    if (!$this->Match->checkIfMatchExists($matchId)) {
  		return array('status' => 103 , 'message' => 'deleteMatchPic : Invalid Match ID');
  	}
  	if (!$this->Match->MatchPrivilege->isUserMatchAdmin($matchId,$userId)) {
  		return array('status' => 103 , 'message' => 'deleteMatchPic : User is not Match Admin');
  	}
  	if (!$this->isImageBelongsToMatch($imageId,$matchId)) {
  		return array('status' => 104, 'message' => 'deleteMatchPic : Pic does not belong to Match');
  	}
  	$data = array(
  		'Image' => array(
  			'id' => $imageId,
  			'deleted' => true
  		)
  	);
  	if ($this->save($data)) {
  		return array('status' => 200, 'data' => $this->fetchMatchImages($matchId), 'message' => 'success');
  	} else {
  		return array('status' => 105, 'message' => 'deleteMatchPic : Pic Deletion failed');
  	}
	}

	public function fetchMatchImages($matchId) {
		$matchId = trim($matchId);
		if (empty($matchId)) {
			return array('status' => 100 , 'message' => 'fetchMatchImages : Invalid Input Arguments');
		}
		$images = $this->find('all',array(
			'conditions' => array(
				'Image.match_id' => $matchId
			),
			'fields' => array('id','caption','url')
		));
		$response = array();
		foreach ($images as $key => $image) {
			$response[$key]['id'] = $image['Image']['id'];
			$response[$key]['caption'] = $image['Image']['caption'];
			$response[$key]['image'] = $image['Image']['url'];
		}
		return $response;
	}

	public function addImages($userId,$images,$fields) {
		if (empty($fields) || empty($userId)) {
			return array('status' => 100 , 'message' => 'addImages : Invlid Input Arguments');
		}
		if (!$this->_userExists($userId)) {
      return array('status' => 332 , 'message' => 'addImages : User Does not exist');
    }
    $matchId = null;
    if (!empty($fields['match_id'])) {
    	if (!$this->Match->checkIfMatchExists($fields['match_id'])) {
	  		return array('status' => 103 , 'message' => 'addImages : Invalid Match Id');
	  	}
	  	if (!$this->Match->MatchPrivilege->isUserMatchAdmin($fields['match_id'],$userId)) {
	  		return array('status' => 104, 'message' => 'addImages : Only Admin can add pics');
	  	}
    	$matchId = $fields['match_id'];
    }
		$dataSource = $this->getDataSource();
    $dataSource->begin();
		$data = array();
		foreach ($images as $index => $image) {
			foreach ($image as $key => $value) {
				if(!empty($value)) {
					switch ($key) {
						case 'url':
							$data[$index]['Image']['url'] = trim($value);
							break;
						case 'caption':
							$data[$index]['Image']['caption'] = trim($value);
							break;
						case 'snap_date_time':
							$data[$index]['Image']['snap_date_time'] = trim($value);
							break;
						case 'location':
							$locationId = $this->Location->saveLocation($value['place'], $value['latitude'], $value['longitude'], $value['unique_identifier']);
							if ($locationId['status'] == 200) {
								$data[$index]['Image']['location_id'] = $locationId['data'];
							} else {
								return array('status' => $locationId['status'], 'message' => 'addImages : '.$locationId['message']);
							}
							break;
					}
				}
			}
			$data[$index]['Image']['user_id'] = $userId;
			if (empty($data[$index]['Image']['url'])) {
				return array('status' => 100 , 'addImages : url is mandatory');
			}
			$data[$index]['Image']['match_id'] = $matchId;
		}

		if (empty($data)) {
			return array('status' => 104 , 'message' => 'addImages : No Data sent for Images');
		}

		if ($this->saveMany($data)) {
			$response = array('status' => 200, 'data' => array('images' => $this->prepareAddImageResponse($fields)), 'message' => 'success');
		} else {
			$response = array('status' => 119 , 'message' => 'addImages : Create image unsuccessful');
		}

		if ($response['status'] == 200) {
      $dataSource->commit();
    } else {
    	$dataSource->rollback();
    }
		return $response;
	}

	private function prepareAddImageResponse($parameters) {
		$response = array();
		if (!empty($parameters['match_id'])) {
			$response = $this->fetchMatchImages($parameters['match_id']);
		}
		return $response;
	}

	public function isImageBelongsToMatch($imageId,$matchId) {
		return $this->find('count',array(
			'conditions' => array(
				'Image.id' => $imageId,
				'Image.match_id' => $matchId
			)
		));
	}

	private function isValidImageId($id) {
		return $this->find('count',array(
			'conditions' => array(
				'Image.id' => $id
			)
		));
	}

	private function isUserEligibleToUpdate($id,$userId) {
		return $this->find('count',array(
			'conditions' => array(
				'Image.id' => $id,
				'OR' => array(
					'Image.user_id' => $userId,
					'Album.user_id' => $userId
				)
			),
			'contain' => array('Album')
		));
	}

	public function getPhotosByUser($profileUserId) {
		$images = $this->find('all', array(
			'conditions' => array(
				'Image.user_id' => $profileUserId
			),
			'fields' => array(
				'Image.id', 'Image.url', 'Image.caption'
			)
		));
		$data = array();
		if (!empty($images)) {
			foreach ($images as $id => $image) {
				$data[$id]['id'] = $image['Image']['id'];
				$data[$id]['image_url'] = $image['Image']['url'];
				$data[$id]['caption'] = $image['Image']['caption'];
			}
		}
		return $data;
	}
	
}

<?php
App::uses('AppModel', 'Model');
App::uses('Limit', 'Lib/Enum');
/**
 * Album Model
 *
 * @property User $User
 * @property AlbumContributor $AlbumContributor
 * @property Image $Image
 */
class Album extends AppModel {

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
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'CoverImage' => array(
			'className' => 'Image',
			'foreignKey' => 'cover_image_id',
			'dependent' => false,
			'conditions' => ''
		),
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
		'AlbumContributor' => array(
			'className' => 'AlbumContributor',
			'foreignKey' => 'album_id',
			'dependent' => false,
			'conditions' => ''
		),
		'Image' => array(
			'className' => 'Image',
			'foreignKey' => 'album_id',
			'dependent' => false,
			'conditions' => ''
		)
	);

	public function getUserProfileAlbums($userId,$numOfRecords) {
		$albumsFromCache = Cache::read('albums_user_'.$userId);
		if (!empty($albumsFromCache)) {
			return $albumsFromCache;
		}
		if (empty($numOfRecords)) {
			$numOfRecords = Limit::NUM_OF_USER_ALBUMS_IN_PROFILE;
		}
		$albumData = array();
		$total = 0;
		$dataArray = $this->find('all',array(
			'conditions' => array(
				'Album.user_id' => $userId
			),
			'fields' => array('id','name','cover_image_id','image_count','created'),
			'contain' => array(
				'CoverImage' => array(
					'fields' => array('id','url','caption')
				)
			),
			'order' => 'Album.created DESC',
			'limit' => $numOfRecords
		));
		$index = 0;
		foreach ($dataArray as $data) {
			$albumData[$index]['id'] = $data['Album']['id'];
			$albumData[$index]['name'] = $data['Album']['name'];
			$albumData[$index]['image_count'] = $data['Album']['image_count'];
			$albumData[$index]['date'] = $data['Album']['created'];

			if (!empty($data['CoverImage'])) {
				$albumData[$index]['image_url'] = $data['CoverImage']['url'];
			} else {
				$albumData[$index]['image_url'] = null;
			}

			$index = $index + 1;
		}
		$totalAlbumsCount = $this->getTotalCountOfUserAlbums($userId);
		return array('total' => $totalAlbumsCount, 'albums' => $albumData);
	}

	public function createAlbum($userId,$album,$images) {
		$userId = trim($userId);
		if ( empty($userId) || empty($album) || (empty($coverImage) && empty($images)) ) {
			return array('status' => 100, 'message' => 'Invalid Input Arguments');
		}
		if (!$this->_userExists($userId)) {
      return array('status' => 332 , 'message' => 'createAlbum : User Does not exist');
    }
    $data = array();
    $coverImage = null;

    $dataSource = $this->getDataSource();
    $dataSource->begin();
    foreach ($album as $key => $value) {
    	if(!empty($value)) {
    		switch ($key) {
    			case 'name':
    				$data['Album']['name'] = trim($value);
    				break;
    			case 'location':
    				$locationId = $this->Location->saveLocation($value['place'], $value['latitude'], $value['longitude'], $value['unique_identifier']);
						if ($locationId['status'] == 200) {
							$data['Album']['location_id'] = $locationId['data'];
						} else {
							return array('status' => $locationId['status'], 'message' => 'createAlbum : Album : '.$locationId['message']);
						}
    				break;
    		}
    	}
    }
    $data['Album']['user_id'] = $userId;
    if (empty($data['Album']['name'])) {
    	return array('status' => 101, 'message' => 'createAlbum : name is mandatory For Album');
    }

    foreach ($images as $key => $image) {
    	if (!empty($image['is_cover_image']) && $image['is_cover_image'] == true){
    		$coverImage = array_splice($images, $key, 1);
    		$coverImage = $coverImage[0];
    		break;
    	}
    }

    if (empty($coverImage)) {
    	$coverImage = array_splice($images, 0 , 1);
    	$coverImage = $coverImage[0];
    }
  	if (!empty(trim($coverImage['url']))) {
  		$coverImageData['url'] = trim($coverImage['url']);
  	} else {
  		return array('status' => 103, 'message' => 'createAlbum : CoverImage :  Url is mandatory For Image');
  	}
  	if (!empty(trim($coverImage['caption']))) {
  		$coverImageData['caption'] = trim($coverImage['caption']);
  	}
  	if (!empty(trim($coverImage['snap_date_time']))) {
  		$coverImageData['snap_date_time'] = trim($coverImage['snap_date_time']);
  	}
  	if (!empty($coverImage['location'])) {
	  	$locationId = $this->Location->saveLocation($coverImage['location']['place'], $coverImage['location']['latitude'], $coverImage['location']['longitude'], $coverImage['location']['unique_identifier']);
			if ($locationId['status'] == 200) {
				$coverImageData['location_id'] = $locationId['data'];
			} else {
				return array('status' => $locationId['status'], 'message' => 'createAlbum : CoverImage : '.$locationId['message']);
			}
		}
		$coverImageData['user_id'] = $userId;
		$data['CoverImage'] = $coverImageData;
    

    foreach ($images as $key => $image) {
    	if (!empty(trim($image['url']))) {
    		$data['Image'][$key]['url'] = trim($image['url']);
    	} else {
    		return array('status' => 103, 'message' => 'createAlbum : Url is mandatory For Image');
    	}

    	if (!empty(trim($image['caption']))) {
    		$data['Image'][$key]['caption'] = trim($image['caption']);
    	}
    	if (!empty(trim($image['snap_date_time']))) {
    		$data['Image'][$key]['snap_date_time'] = trim($image['snap_date_time']);
    	}
    	if (!empty($image['location'])) {
	    	$locationId = $this->Location->saveLocation($image['location']['place'], $image['location']['latitude'], $image['location']['longitude'], $image['location']['unique_identifier']);
				if ($locationId['status'] == 200) {
					$data['Image'][$key]['location_id'] = $locationId['data'];
				} else {
					return array('status' => $locationId['status'], 'message' => 'createAlbum : Image : '.$locationId['message']);
				}
			}
			$data['Image'][$key]['user_id'] = $userId;
    }
    
    if ($this->saveAssociated($data ,array('deep' => true))) {
    	$albumId = $this->getLastInsertID();
    	$albumData = $this->findById($albumId);
    	$data2 = array(
    		'Image' => array(
    			'id' => $albumData['Album']['cover_image_id'],
    			'album_id' => $albumId
    		)
    	);
    	if ($this->Image->save($data2)) {
    		$response = array('status' => 200 ,'data' => array('albums' => $this->getUserProfileAlbums($userId,Limit::NUM_OF_USER_PROFILE_ABOUT_RECORDS)), 'message' => 'success');
    	} else {
    		$response = array('status' => 109 , 'message' => 'createAlbum : Album could not be Created, Cover Image updation failure');
    	}   	
    } else {
    	$response = array('status' => 109 , 'message' => 'createAlbum : Album could not be Created');
    }

    if ($response['status'] == 200) {
      $dataSource->commit();
    } else {
    	$dataSource->rollback();
    }
    return $response;

	}

	public function getTotalCountOfUserAlbums($userId) {
		return $this->find('count',array(
			'conditions' => array(
				'user_id' => $userId
			)
		));
	}

}

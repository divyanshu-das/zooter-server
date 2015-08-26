<?php
App::uses('AppModel', 'Model');
App::uses('Limit', 'Lib/Enum');
/**
 * Video Model
 *
 * @property Location $Location
 * @property User $User
 */
class Video extends AppModel {

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
		),
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
		'size' => array(
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
		'Image' => array(
			'className' => 'Image',
			'foreignKey' => 'image_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function getUserProfileVideos($userId,$numOfRecords) {

		$videosFromCache = Cache::read('videos_user_'.$userId);
		if (!empty($videosFromCache)) {
			return $videosFromCache;
		}

		if (empty($numOfRecords)) {
			$numOfRecords = Limit::NUM_OF_USER_VIDEOS_IN_PROFILE;
		}

		$dataArray = $this->find('all',array(
			'conditions' => array(
				'Video.user_id' => $userId
			),
			'fields' => array('id','name','url','created','caption','image_id'),
			'contain' => array(
				'Image' => array(
					'fields' => array('id','url')
				),
			),			
			'order' => 'Video.created DESC'
		));

		$videoData = array();
		$index = 0;
		foreach ($dataArray as $data) {
			$videoData[$index]['id'] = $data['Video']['id'];
			$videoData[$index]['caption'] = $data['Video']['caption'];
			$videoData[$index]['name'] = $data['Video']['name'];
			$videoData[$index]['video_url'] = $data['Video']['url'];
			$videoData[$index]['date'] = $data['Video']['created'];
			if (!empty($data['Image'])) {
				$videoData[$index]['image_url'] = $data['Image']['url'];
			} else $videoData[$index]['image_url'] = null;
			$index = $index + 1;
		}

		$totalVideoCount = $this->getUserTotalVidoeCount($userId);
		$videos = array('total' => $totalVideoCount, 'videos' => $videoData);

		return $videos;
	}

	public function getUserTotalVidoeCount($userId) {
		return $this->find('count',array(
			'conditions' => array(
				'user_id' => $userId
			)
		)); 
	}
}

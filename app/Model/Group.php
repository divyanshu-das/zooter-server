<?php
App::uses('AppModel', 'Model');
App::uses('FriendshipStatus', 'Lib/Enum');
/**
 * Group Model
 *
 * @property User $User
 * @property GroupMessage $GroupMessage
 */
class Group extends AppModel {

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
		'GroupMessage' => array(
			'className' => 'GroupMessage',
			'foreignKey' => 'group_id',
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
    'UserGroup' => array(
      'className' => 'UserGroup',
      'foreignKey' => 'group_id',
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

	public function addGroup($name, $profileImage, $coverImage, $userId, $members) {
    $groupData = array(
      'Group' => array(
        'name' => $name,
        'user_id' => $userId,
        'ProfileImage' => array(
        	'url' => $profileImage
        ),
        'CoverImage' => array(
        	'url' => $coverImage
        )
    	)
    );
    $groupData['UserGroup'] = $members;
    if ($this->saveAssociated($groupData)) {
      $groupId = $this->getLastInsertID();
      $group = $this->find('first', array(
        'conditions' => array(
          'Group.id' => $groupId
        ),
        'contain' => array(
          'UserGroup'
        )
      ));
      if (!empty($group['UserGroup'])) {
        $group['Members'] = $group['UserGroup'];
        unset($group['UserGroup']);
      }
      $response = array('status' => 200, 'data' => $group);
    } else {
      $response = array('status' => 1100, 'message' => 'Group not added');
    }
    return $response;
	}

	public function getEligibleUsersList($userId) {
		$responseData = array();
		$friends = $this->User->FriendFrom->find('all', array(
			'conditions' => array(
				'OR' => array(
					'FriendFrom.user_id' => $userId,
					'FriendFrom.friend_id' => $userId
				) 
			),
      'FriendFrom.status' => FriendshipStatus::ACCEPTED
		));
		$friendIds = array();
    foreach ($friends as $id => $friend) {
      if ($friend['FriendFrom']['user_id'] == $userId) {
        $friendIds[$friend['FriendFrom']['id']] = $friend['FriendFrom']['friend_id'];
      } else {
        $friendIds[$friend['FriendFrom']['id']] = $friend['FriendFrom']['user_id'];
      }
    }
  	if (!empty($friendIds)) {
  		$profiles = $this->User->Profile->find('all', array(
  			'fields' => array(
  				'Profile.first_name as name', 'Profile.user_id'
				),
				'conditions' => array(
					'Profile.user_id' => $friendIds
				)
			));
			foreach ($profiles as $id => $profile) {
				$responseData[$profile['Profile']['user_id']] = $profile['Profile']['name'];
			}
			$response = array('status' => 200, 'data' => $responseData);
  	} else {
  		$response = array('status' => 904, 'data' => 'No friend found');
  	}
  	return $response;
	}

	public function searchGroup($input,$numOfRecords) {
		if (empty($input)) {
			return array('status' => 100, 'message' => 'searchGroup : Invalid Input Arguments');
		}
		$groupDataArray = array();
		$groupData = $this->find('all',array(
			'conditions' => array(
				'Group.name LIKE' => "%$input%"
			),
			'fields' => array('id','name','image_id','group_users_count'),
			'contain' => array(
				'ProfileImage' => array(
          'fields' => array('id','url')
        )
			),
			'limit' => $numOfRecords
		));
		foreach ($groupData as $index => $group) {
			$groupDataArray[$index]['id'] = $group['Group']['id'];
			$groupDataArray[$index]['name'] = $group['Group']['name'];
			if (!empty($group['ProfileImage'])) {
				$groupDataArray[$index]['image'] = $group['ProfileImage']['url'];
			} else {
				$groupDataArray[$index]['image'] = NULL;
			}
			$groupDataArray[$index]['total_members'] = $group['Group']['group_users_count'];
		}
		return array('status' => 200 , 'data' => $groupDataArray);
	}

}

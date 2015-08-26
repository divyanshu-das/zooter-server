<?php
App::uses('AppModel', 'Model');
App::uses('InvitationStatus', 'Lib/Enum');
App::uses('Limit', 'Lib/Enum');
/**
 * UserGroup Model
 *
 * @property User $User
 * @property Group $Group
 */
class UserGroup extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'group_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
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
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function getUserGroups($userId,$numOfRecords,$filterLike) {
    $userId = trim($userId);
    $numOfRecords = trim($numOfRecords);
    if(empty($userId)) {
      return array('status' => 100, 'message' => 'getUserGroups : Invalid Input Arguments');
    }
    if (empty($numOfRecords)) {
      $numOfRecords = Limit::NUM_OF_USER_GROUPS;
    }
    if (!empty($filterLike)) {
      $groupsFromCache = Cache::read('groups_user_'.$userId);
      if (!empty($groupsFromCache)) {
        return $groupsFromCache;
      }      
    }
    $userGroupData = array();
    $options = array(
      'conditions' => array(
      	'UserGroup.user_id' => $userId,
        'status' => InvitationStatus::ACCEPTED
      ),
      'fields' => array('id','group_id'),
      'contain' => array(
        'Group' => array(
          'fields' => array('id','name','image_id','group_users_count'),
          'ProfileImage' => array(
            'fields' => array('id','url')
          )
        )
      ),
      'order' => 'UserGroup.created DESC',
      'limit' => $numOfRecords
    );
    if (!empty($filterLike)) {
      $options['conditions']['Group.name LIKE'] = "%$filterLike%";
    }
    $userGroups = $this->find('all',$options);
  	foreach ($userGroups as $key => $group) {
      $userGroupData[$key]['id'] = $group['UserGroup']['id'];
  		$userGroupData[$key]['group']['id'] = $group['Group']['id'];
      $userGroupData[$key]['group']['name'] = $group['Group']['name'];
      if (!empty($group['Group']['ProfileImage'])) {
        $userGroupData[$key]['group']['image'] = $group['Group']['ProfileImage']['url'];
      } else {
          $userGroupData[$key]['group']['image'] = NULL;
      }
      $userGroupData[$key]['group']['group_users_count'] = $group['Group']['group_users_count'];
  	}
    $countOfGroups = $this->getCountOfGroups($userId);
    $data = array('total' => $countOfGroups, 'groups' => $userGroupData);
    return array('status' => 200 , 'data' => $data);
  }

  public function getCountOfGroups($userId) {
    return $this->find('count',array(
      'conditions' => array(
        'UserGroup.user_id' => $userId,
        'status' => InvitationStatus::ACCEPTED
      )
    ));
  }

  public function getGroupRequest($id){
  	$request_name = array();
  	if (!empty($id)) {
      $data = $this->find('first' ,array(
      	'conditions' => array('UserGroup.id' => $id),
        'fields' => array('group_id'),
        'contain' => array(
          'Group' => array(
            'fields' => array('id','name','image_id'),
            'ProfileImage' => array(
              'fields' => array('id','url')
            )
          )
        )
      ));
      if (!empty($data)) {
        $request_name = array(
          'id' => $data['UserGroup']['group_id'],
          'name' => $data['Group']['name'],
        );
        if (!empty($data['Group']['ProfileImage'])) {
          $request_name['image'] = $group['Group']['ProfileImage']['url'];
        }
        else {
          $request_name['image'] = NULL;
        }
      }
  	}
  	return $request_name;
  }

  public function handleGroupRequest($requestId,$userId,$status) {
    if (!empty($requestId) && !empty($userId) && !empty($status)) {
      if ($this->isUserEligbleForRequest($requestId,$userId)) {
        $data = array(
          'UserGroup' => array(
            'id' => $requestId,
            'status' => $status
          )
        );
        if ($this->save($data)) {
          $response =  array('status' => 200 , 'message' => 'success');
        } 
        else {
          $response =  array('status' => 110, 'message' => 'handleGroupRequest :Group Request Accept Could not be updated');
        } 
      }
      else {
        $response =  array('status' => 109, 'message' => 'handleGroupRequest : User Not Eligible to Accept Group Request');
      }
    }
    else {
      $response =  array('status' => 108, 'message' => 'handleGroupRequest : Invalid Input Arguments');
    }
    return $response;
  }

  public function isUserEligbleForRequest($requestId,$userId) {
    return $this->find('count',array(
      'conditions' => array(
        'UserGroup.id' => $requestId,
        'UserGroup.user_id' => $userId,
        'UserGroup.status' => InvitationStatus::INVITED,
      )
    ));
  }
}

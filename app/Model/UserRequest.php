<?php
App::uses('AppModel', 'Model');
App::uses('UserRequestType' , 'Lib/Enum');
App::uses('FriendshipStatus' , 'Lib/Enum');
App::uses('UserRequestActiveStatus', 'Lib/Enum');
App::uses('SeriesPrivilegeType' , 'Lib/Enum');
App::uses('TeamPrivilegeType' , 'Lib/Enum');
App::uses('Limit', 'Lib/Enum');
/**
 * UserRequest Model
 *
 */
class UserRequest extends AppModel {

  public $validate = array(
    'type' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'type is not numeric',
        // 'required' => true,
        // 'allowEmpty' => false,
        // 'on' => 'create'
      ),
    ),
    'request_id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'request id is not numeric',
        // 'required' => true,
        // 'allowEmpty' => false,
        // 'on' => 'create'
      ),
    ),
    'user_id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'user id is not numeric',
        // 'required' => true,
        // 'allowEmpty' => false,
        // 'on' => 'create'
      ),
    ),
    'created_by' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'created by is not numeric',
        // 'required' => true,
        // 'allowEmpty' => false,
        // 'on' => 'create'
      ),
    ),
    // 'active_status' => array(
    //   'boolean' => array(
    //     'rule' => array('boolean'),
    //     'message' => 'active status is not valid',
    //     'required' => true,
    //     'allowEmpty' => false,
    //   ),
    // )
  );

 /**
 * belongsTo associations
 *
 * @var array
 */
  public $belongsTo = array(
  	'RequestCreatedBy' => array(
			'className' => 'User',
			'foreignKey' => 'created_by',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
    'RequestModifiedBy' => array(
			'className' => 'User',
			'foreignKey' => 'modified_by',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
  );

  public function getNewRequestsCount($userId , $lastSeenRequestTime) {
    $count = $this->find('count' , array(
      'conditions' => array(
        'user_id' => $userId,
        'created >=' => $lastSeenRequestTime
        )
    ));
    return $count;
  }

  public function fetchRequests($userId, $lastSeenRequestTime) {
    $totalRequestsLimit = Limit::NUM_OF_USER_REQUESTS;
    $fields = array('id','type','request_id','created_by','created');
    $contain = array(
      'RequestCreatedBy' => array(
        'fields' => array('id'),
        'Profile' => array(
          'fields' => array('first_name','middle_name','last_name','image_id'),
          'ProfileImage' => array(
            'fields' => array('id','url')
          )
        )
      )
    );
    $newRequests = $this->find('all', array(
     'conditions' => array(
        'UserCreatedRequest.user_id' => $userId,
        'UserCreatedRequest.active_status' => UserRequestActiveStatus::ACTIVE,
        'UserCreatedRequest.created >=' => $lastSeenRequestTime
      ),
     'fields' => $fields,
     'contain' => $contain,
     'order' => 'UserCreatedRequest.created DESC'
    ));
    $newRequestsCount = count($newRequests);
    $oldRequests = array();
    if ($newRequestsCount < $totalRequestsLimit) {
      $oldRequests = $this->find('all', array(
       'conditions' => array(
          'UserCreatedRequest.user_id' => $userId,
          'UserCreatedRequest.active_status' => UserRequestActiveStatus::ACTIVE,
          'UserCreatedRequest.created <' => $lastSeenRequestTime
        ),
       'fields' => $fields,
       'contain' => $contain,
       'order' => 'UserCreatedRequest.created DESC',
       'limit' => ($totalRequestsLimit - $newRequestsCount)
      ));
    }
    $userRequests = $this->__concatArray($newRequests,$oldRequests);

    $allData = array();
    $newData = array();
    $requests = array();
    if ( ! empty($userRequests)) {
      foreach ($userRequests as $key => $userRequest) {
        $requestId = $userRequest['UserCreatedRequest']['request_id'];
        $userRequestId = $userRequest['UserCreatedRequest']['id'];
        $type = $userRequest['UserCreatedRequest']['type'];
              
        if ( ! empty($requestId) && (!empty($type))) {
          $request = array();
          $userProfile = $userRequest['RequestCreatedBy'];
          $name = $this->__prepareUserName($userProfile['Profile']['first_name'],
                                          $userProfile['Profile']['middle_name'],
                                          $userProfile['Profile']['last_name']);
          if (!empty($userProfile['Profile']['ProfileImage'])) {
            $image = $userProfile['Profile']['ProfileImage']['url'];
          } else $image = NULL;
          $request['id'] = $userRequestId ;
          $request['type'] = UserRequestType::stringValue($type);
          $request['request_id'] = $requestId;        
          $request['date'] = $userRequest['UserCreatedRequest']['created'];
          $request['request_sender'] = array(
            'id' => $userProfile['id'],
            'name' => $name,
            'image' => $image
          );

          switch ($type) {
            case UserRequestType::FRIEND_REQUEST:
              // no seperate call for any extra data filling for this case 
             // $request = $this->getFriendRequests($requestId,$userProfile);
              break;
            case (($type == UserRequestType::GROUP_JOIN_REQUEST) || ($type == UserRequestType::GROUP_ADD_INVITE)):
              $group = $this->RequestCreatedBy->UserGroup->getGroupRequest($requestId);
              if ( ! empty($group)) {
                $request['group'] = $group;
              }
              break;
            case (($type == UserRequestType::SERIES_TEAM_JOIN_REQUEST) || ($type == UserRequestType::SERIES_TEAM_ADD_INVITE)):
              $seriesTeam = $this->getSeriesTeamRequest($userRequestId);
              if ( ! empty($seriesTeam)) {
                 $request['series'] = $seriesTeam['series'];
                 $request['team'] = $seriesTeam['team'];
               }
              break;
            case (($type == UserRequestType::TEAM_PLAYER_JOIN_REQUEST) || ($type == UserRequestType::TEAM_PLAYER_ADD_INVITE)):
              $teamAndRole = $this->RequestCreatedBy->TeamPlayer->getTeamPlayerRequest($requestId);
              if ( ! empty($teamAndRole)) {
                $request['team'] = $teamAndRole['team'];
              }
              break;
            case UserRequestType::TEAM_ADMIN_ADD_INVITE:
              $teamAdmin = $this->RequestCreatedBy->TeamPrivilege->getTeamAdminRequest($requestId);
              if ( ! empty($teamAdmin)) {
                $request['team'] = $teamAdmin['team'];
              }
              break;
            case UserRequestType::TEAM_STAFF_ADD_INVITE:
              $teamStaffAndRole = $this->RequestCreatedBy->TeamStaff->getTeamStaffRequest($requestId);
               if ( ! empty($teamStaffAndRole)) {
                 $request['role'] = $teamStaffAndRole['role'];
                 $request['team'] = $teamStaffAndRole['team'];
               }
              break;
            case (($type == UserRequestType::MATCH_TEAM_PLAYER_JOIN_REQUEST) || ($type == UserRequestType::MATCH_TEAM_PLAYER_ADD_INVITE)):
              $matchPlayerRole = $this->RequestCreatedBy->MatchPlayer->getMatchTeamPlayerRequest($requestId);
              if ( ! empty($matchPlayerRole)) {
                 $request['team'] = $matchPlayerRole['team'];
                 $request['match'] = $matchPlayerRole['match'];
              }
              break;
            case (($type == UserRequestType::MATCH_TEAM_JOIN_REQUEST) || ($type == UserRequestType::MATCH_TEAM_ADD_INVITE)):
              $matchTeam = $this->getMatchTeamRequest($requestId);
               if ( ! empty($matchTeam)) {
                 $request['match'] = $matchTeam['match'];
                 $request['team'] = $matchTeam['team'];
               }
              break;
            case UserRequestType::MATCH_STAFF_ADD_INVITE:
              $matchStaffRole = $this->RequestCreatedBy->MatchStaff->getMatchStaffRequest($requestId);
              if ( ! empty($matchStaffRole)) {
                 $request['role'] = $matchStaffRole['role'];
                 $request['match'] = $matchStaffRole['match'];
              }
              break;
            case UserRequestType::MATCH_ADMIN_ADD_INVITE:
              $matchAdmin = $this->RequestCreatedBy->MatchPrivilege->getMatchAdminRequest($requestId);
              if ( ! empty($matchAdmin)) {
                 $request['match'] = $matchAdmin['match'];
              }
              break;
            case (($type == UserRequestType::FAN_CLUB_JOIN_REQUEST) || ($type == UserRequestType::FAN_CLUB_ADD_INVITE)):
              $request = $this->getFanClubJoinRequest($requestId);
              break;
            case UserRequestType::SERIES_ADMIN_ADD_INVITE:
              $seresAdmin = $this->RequestCreatedBy->SeriesPrivilege->getSeriesAdminRequest($requestId);
              if ( ! empty($seresAdmin)) {
                 $request['series'] = $seresAdmin['series'];
              }
              break;
          }
          if ($key < $newRequestsCount) {
            array_push($newData, $request);
            array_push($allData, $request);
          } else array_push($allData, $request);
        }
      }
    }
    return array('new' => $newData, 'all' => $allData);
  }

  private function getSeriesTeamRequest($userRequestId) {
    $dataRequest = $this->find('first',array(
      'joins' => array(
        array(
          'table' => 'series_teams',
          'alias' => 'SeriesTeamJoin',
          'type' => 'left',
          'conditions' => array(
            'SeriesTeamJoin.id = UserCreatedRequest.request_id'
           )
        ),
        array(
          'table' => 'series',
          'alias' => 'SeriesJoin',
          'type' => 'left',
          'conditions' => array(
            'SeriesJoin.id = SeriesTeamJoin.series_id'
          ),
        ),
        array(
          'table' => 'teams',
          'alias' => 'TeamJoin',
          'type' => 'left',
          'conditions' => array(
            'TeamJoin.id = SeriesTeamJoin.team_id'
          ),
        )
      ),
      'conditions' => array(
          'UserCreatedRequest.id' => $userRequestId
      ),
      'fields' => array('SeriesTeamJoin.team_id','SeriesTeamJoin.id',
                          'SeriesJoin.id','SeriesJoin.name','SeriesJoin.start_date_time',
                          'TeamJoin.id','TeamJoin.name','TeamJoin.image_id'
                  ),
    ));
    if(empty($dataRequest)) {
      return null;
    }
    $requestName = array();
    $requestName['series']['id'] = $dataRequest['SeriesJoin']['id'];
    $requestName['series']['name'] = $dataRequest['SeriesJoin']['name'];
    $requestName['series']['start_date_time'] = $dataRequest['SeriesJoin']['start_date_time'];
    $requestName['team'] = array(
      'id' => $dataRequest['TeamJoin']['id'],
      'name' => $dataRequest['TeamJoin']['name']
    );
    $imageData = $this->RequestCreatedBy->Team->ProfileImage->find('first',array(
      'conditions' => array(
        'id' => $dataRequest['TeamJoin']['image_id']
      ),
      'fields' => array('id','url')
    ));
    if (!empty($imageData)) {
      $requestName['team']['image'] = $imageData['ProfileImage']['url'];
    } else $requestName['team']['image'] = null;

    return $requestName; 	
  }


  private function getMatchTeamRequest($userRequestId) {
    $dataRequest = $this->find('first',array(
      'joins' => array(
         array(
          'table' => 'match_teams',
          'alias' => 'MatchTeamJoin',
          'type' => 'left',
          'conditions' => array(
            'MatchTeamJoin.id = UserCreatedRequest.request_id',
           )
         ),
         array(
           'table' => 'teams',
           'alias' => 'TeamJoin',
           'type' => 'left',
           'conditions' => array(
             'TeamJoin.id = MatchTeamJoin.team_id'
           ),
         ),
         array(
           'table' => 'matches',
           'alias' => 'MatchJoin',
           'type' => 'left',
           'conditions' => array(
             'MatchJoin.id = MatchTeamJoin.match_id'
           ),
         ),
       ),
       'conditions' => array(
         'UserCreatedRequest.user_id' => $userRequestId
      ),
      'fields' => array(
        'MatchTeamJoin.id',
        'MatchJoin.id',
        'MatchJoin.name',
        'MatchJoin.start_date_time',
        'TeamJoin.id',
        'TeamJoin.name',
        'TeamJoin.image_id'                       
      ),
    )); 
     if (empty($dataRequest)) {
      return null;
    }
    $requestName = array();
    $requestName['match']['id'] = $dataRequest['MatchJoin']['id'];
    $requestName['match']['name'] = $dataRequest['MatchJoin']['name'];
    $requestName['match']['start_date_time'] = $dataRequest['MatchJoin']['start_date_time'];
    $requestName['team'] = array(
      'id' => $dataRequest['TeamJoin']['id'],
      'name' => $dataRequest['TeamJoin']['name']
    );
    $imageData = $this->RequestCreatedBy->Team->ProfileImage->find('first',array(
      'conditions' => array(
        'id' => $dataRequest['TeamJoin']['image_id']
      ),
      'fields' => array('id','url')
    ));
    if (!empty($imageData)) {
      $requestName['team']['image'] = $imageData['ProfileImage']['url'];
    } else $requestName['team']['image'] = null;

    return $requestName;
  }

  private function getFanClubJoinRequests($userId) {
  	/* user fan club table to be created first */
  }

  public function handleRequest($userId,$userRequestId,$status) {
    $userId = trim($userId);
    $userRequestId = trim($userRequestId);
    $status = trim($status);
    if (empty($userId) && empty($userRequestId) && empty($status)) {
      return array('status' => 100, 'message' => 'handleRequest : Invalid Input arguments');
    }
    if (!$this->userExists($userId)) {
      return array('status' => 912, 'message' => 'handleRequest : User Does Not Exist');
    }
    if ($status == true) {
      $status = InvitationStatus::ACCEPTED;
    } else if ($status == false) {
        $status = InvitationStatus::REJECTED;
    } else return array('status' => 101, 'message' => 'handleRequest : Invalid Input data for Status');

    $userRequest = $this->find('first',array(
      'conditions' => array(
        'UserRequest.id' => $userRequestId,
        'UserRequest.user_id' => $userId,
        'UserRequest.active_status' => true
      ),
      'fields' => array('type','request_id')
    ));
    if (!empty($userRequest)) {
      $requestId = $userRequest['UserRequest']['request_id'];
      $type = $userRequest['UserRequest']['type'];
     
      $dataSource = $this->getDataSource();
      $dataSource->begin();
      switch ($type) {
        case UserRequestType::FRIEND_REQUEST:
          $accept = $this->RequestCreatedBy->FriendTo->handleFriendRequest($requestId,$userId,$status);
          break;
        case (($type == UserRequestType::GROUP_JOIN_REQUEST) || ($type == UserRequestType::GROUP_ADD_INVITE)):
          $accept = $this->RequestCreatedBy->UserGroup->handleGroupRequest($requestId,$userId,$status);
          break;
        case (($type == UserRequestType::SERIES_TEAM_JOIN_REQUEST) || ($type == UserRequestType::SERIES_TEAM_ADD_INVITE)):
          $accept = $this->RequestCreatedBy->SeriesOwner->SeriesTeam->handleSeriesTeamRequest($requestId,$userId,$status);
          break;
        case (($type == UserRequestType::TEAM_PLAYER_JOIN_REQUEST) || ($type == UserRequestType::TEAM_PLAYER_ADD_INVITE)):
          $accept = $this->RequestCreatedBy->TeamPlayer->handleTeamPlayerRequest($requestId,$userId,$status);
          break;
        case UserRequestType::TEAM_ADMIN_ADD_INVITE:
          $accept = $this->RequestCreatedBy->TeamPrivilege->handleTeamAdminRequest($requestId,$userId,$status);
          break;
        case UserRequestType::TEAM_STAFF_ADD_INVITE:
          $accept = $this->RequestCreatedBy->TeamStaff->handleTeamStaffRequest($requestId,$userId,$status);
          break;
        case (($type == UserRequestType::MATCH_TEAM_PLAYER_JOIN_REQUEST) || ($type == UserRequestType::MATCH_TEAM_PLAYER_ADD_INVITE)):
          $accept = $this->RequestCreatedBy->MatchPlayer->handleMatchTeamPlayerRequest($requestId,$userId,$status);
          break;
        case (($type == UserRequestType::MATCH_TEAM_JOIN_REQUEST) || ($type == UserRequestType::MATCH_TEAM_ADD_INVITE)):
          $accept = $this->RequestCreatedBy->Match->MatchTeam->handleMatchTeamRequest($requestId,$userId,$status);
          break;
        case UserRequestType::MATCH_STAFF_ADD_INVITE:
          $accept = $this->RequestCreatedBy->MatchStaff->handleMatchStaffRequest($requestId,$userId,$status);
          break;
        case UserRequestType::MATCH_ADMIN_ADD_INVITE:
          $accept = $this->RequestCreatedBy->MatchPrivilege->handleMatchAdminRequest($requestId,$userId,$status);
          break;
        case (($type == UserRequestType::FAN_CLUB_JOIN_REQUEST) || ($type == UserRequestType::FAN_CLUB_ADD_INVITE)):
          $request = $this->getFanClubJoinRequest($requestId);
          break;
        case UserRequestType::SERIES_ADMIN_ADD_INVITE:
          $accept = $this->RequestCreatedBy->SeriesPrivilege->handleSeriesAdminRequest($requestId,$userId,$status);
          break;
      }
      if ($accept['status'] == 200) {
        $updateUserRequestStatus = $this->updateUserRequest($userRequestId);
        if ($updateUserRequestStatus['status'] == 200) {
          $response = array('status' => 200 , 'message' => 'success','data' => $userRequestId);
        } else {
            $response = array(
              'status' => $updateUserRequestStatus['status'],
              'message' => $updateUserRequestStatus['message']
            );
        }
      } else $response = array('status' => $accept['status'], 'message' => $accept['message']);

      if ($response['status'] == 200) {
        $dataSource->commit();
      } else $dataSource->rollback();

    } else {
        $response =  array('status' => 102, 'message' => 'handleRequest : No User Request ID found');
    }
    return $response;
  }

  public function updateUserRequest($userRequestId) {
    if (!empty($userRequestId)) {
      $userRequestData = array(
        'UserRequest' => array(
          'id' => $userRequestId,
          'active_status' => UserRequestActiveStatus::INACTIVE
        )
      );
      if ($this->save($userRequestData)) {
        $response =  array('status' => 200 , 'message' => 'success');
      } else $response =  array('status' => 107, 'message' => 'updateUserRequest : User Request Accept Could not be updated');
    } else $reponse = array('status' => 106, 'message' => 'updateUserRequest :Invalid arguments');

    return $response;
  }

  public function userExists($userId) {    
    return $this->RequestCreatedBy->find('count', array(
      'conditions' => array(
        'RequestCreatedBy.id' => $userId,
      )
    ));
  }

  public function createOrUpdateUserRequest($requestType,$requestId,$userId,$activeStatus,$createdBy) {
    if (empty($requestType) || empty($userId) || empty($requestId) || empty($createdBy)) {
      return array('status' => 100, 'message' => 'handleUserRequestActiveStatus : Invalid Input Arguments');
    }    
    $existingRecord = $this->find('first',array(
      'conditions' => array(
        'request_id' => $requestId,
        'type' => $requestType
      ),
      'fields' => array('id','request_id','user_id','active_status')
    ));
    if (!empty($existingRecord)) {
      $existingRecord = $existingRecord['UserRequest'];
      $existingRecord['user_id'] = $userId;
      $existingRecord['active_status'] = $activeStatus;
      $data = $existingRecord;
    } else {
      $data = array(
        'type' => $requestType,
        'request_id' => $requestId,
        'user_id' => $userId,
        'active_status' => $activeStatus,
        'created_by' => $createdBy
      );
    }
    if ($this->save($data)) {
      return array('status' => 200, 'data' => true, 'message' => 'success');
    } else {
      return array('status' => 101 , 'message' => 'createOrUpdateUserRequest : request status could not be create or updated');
    }    
  }

  public function createMultipleUserRequests($userRequests,$createdBy) {
    if (empty($userRequests) || empty($createdBy)) {
      return array('status' => 100 , 'message' => 'createMultipleUserRequests : Invalid Input Arguments');
    }
    if (!$this->_userExists($createdBy)) {
      return array('status' => 912, 'message' => 'createMultipleUserRequests : User Does Not Exist');
    }

    $userRequestData = array();
    foreach ($userRequests as $key1 => $value1) {
      foreach ($value1 as $key2 => $value2) {
        if(!empty($value2)) {
          $value2 = trim($value2);
          switch ($key2) {
            case 'type':
              $userRequestData[$key1]['type'] = $value2;
              break;
            case 'request_id':
              $userRequestData[$key1]['request_id'] = $value2;
              break;
            case 'user_id':
              $userRequestData[$key1]['user_id'] = $value2;
              break;
          }
          
        }
      }
      if (empty($userRequestData[$key1]['type']) || empty($userRequestData[$key1]['request_id']) || empty($userRequestData[$key1]['user_id'])) {
        return array('status' => 105, 'message' => 'createMultipleUserRequests : [type ,request_id, user_id] are mandatory fields for creating user requests');
      }
      $userRequestData[$key1]['active_status'] = true;
      $userRequestData[$key1]['created_by'] = $createdBy;
    }

    if (empty($userRequestData)) {
      return array('status' => 105, 'message' => 'createMultipleUserRequests : User Requests data is empty');
    }
    
    if ($this->saveMany($userRequestData)) {
      return array('status' => 200, 'data' => true, 'message' => 'success');
    } else {
      return array('status' => 101 , 'message' => 'createOrUpdateUserRequest : user requests could not be created');
    } 

  }

  private function __concatArray($newNotifications,$oldNotifications) {
    $finalArray = $newNotifications;
    foreach ($oldNotifications as $data) {
      array_push($finalArray, $data);
     } 
     return $finalArray;
  }

  private function __prepareUserName($firstName, $middleName, $lastName){
    $name = NULL;
    if (!empty($firstName)) {
      $name = $firstName." ";
    }
    if (!empty($middleName)) {
      $name = $name.$middleName." ";
    }
    if (!empty($lastName)) {
      $name = $name.$lastName." ";
    }
    return $name;
  }

}







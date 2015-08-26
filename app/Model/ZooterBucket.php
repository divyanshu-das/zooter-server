<?php
App::uses('AppModel', 'Model');
App::uses('PlayerProfileType', 'Lib/Enum');
App::uses('PlayerRole', 'Lib/Enum');
App::uses('InvitationStatus', 'Lib/Enum');
App::uses('Limit', 'Lib/Enum');
App::uses('UserRequestType', 'Lib/Enum');

/**
 * ZooterBucket Model
 *
 * @property Match $Match
 * @property User $User
 */
class ZooterBucket extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Match' => array(
			'className' => 'Match',
			'foreignKey' => 'match_id',
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
		)
	);

  public function invitePlayerInZooterBucket($userId,$matchId,$inviteUserId) {
    $matchId = trim($matchId);
    $userId = trim($userId);
    $inviteUserId = trim($inviteUserId);
    if (empty($userId) || empty($matchId) || empty($inviteUserId)) {
      return array('status' => 100, 'message' => 'invitePlayerInZooterBucket : Invalid Input Arguments');
    }
    if ( (!$this->_userExists($userId)) || (!$this->_userExists($inviteUserId)) ) {
      return array('status' => 103 , 'message' => 'invitePlayerInZooterBucket : Invalid User or invite_user ID');
    }
    if (!$this->Match->checkIfMatchExists($matchId)) {
      return array('status' => 103 , 'message' => 'invitePlayerInZooterBucket : Invalid Match ID');
    }
    $status = null;
    $userRequestType = null;
    if ($userId == $inviteUserId) {
      //$userRequestType = UserRequestType::MATCH_ZOOTER_BUCKET_JOIN_REQUEST;
      $status = InvitationStatus::CONFIRMED;
    } elseif (!$this->Match->MatchPrivilege->isUserMatchAdmin($matchId,$userId)) {
      return array('status' => 104 , 'message' => 'invitePlayerInZooterBucket : Only Admin can add');
    } else {
      $userRequestType = UserRequestType::MATCH_ZOOTER_BUCKET_ADD_INVITE;
    }
    $data = array();
    $existingStatus = $this->findByMatchIdAndUserId($matchId,$inviteUserId);
    if (!empty($existingStatus['ZooterBucket'])) {
      if ($existingStatus['ZooterBucket']['status'] == InvitationStatus::CONFIRMED || $existingStatus['ZooterBucket']['status'] == InvitationStatus::INVITED || $existingStatus['ZooterBucket']['status'] == InvitationStatus::REQUEST_PENDING) {
        $response = $this->__prepareResponseZooterBucketInvite($inviteUserId,$existingStatus['ZooterBucket']['status']);
        return array('status' => 200, 'data' => $response);
      } elseif ($existingStatus['ZooterBucket']['status'] == InvitationStatus::REJECTED) {
        $data['ZooterBucket']['id'] = $existingStatus['ZooterBucket']['id'];
      }
    }
    $data['ZooterBucket']['user_id'] = $inviteUserId;
    $data['ZooterBucket']['match_id'] = $matchId;
    if (!empty($status)) {
      $data['ZooterBucket']['status'] = $status;
    } else {
      $data['ZooterBucket']['status'] = InvitationStatus::INVITED;
    }

    $requestResponseStatus = null;
    $requestResponseMessage = null;
    $dataSource = $this->getDataSource();
    $dataSource->begin();
    if ($this->save($data)) {
      if (!empty($userRequestType)) {
        $inserTedid = $this->getLastInsertID();
        $inserTedid = !empty($inserTedid) ? $inserTedid : $data['ZooterBucket']['id'];
        $UserRequest = new UserRequest();
        $requestResponse = $UserRequest->createOrUpdateUserRequest(
          $userRequestType,
          $inserTedid,
          $inviteUserId,
          true,
          $userId
        );
        $requestResponseStatus = $requestResponse['status'];
        $requestResponseMessage = $requestResponse['message'];
      } else {
        $requestResponseStatus = 200;
        $requestResponseMessage = 'success';
      }
      $responseData = $this->__prepareResponseZooterBucketInvite($inviteUserId,$data['ZooterBucket']['status']);
      $response = array('status' => $requestResponseStatus, 'data' => $responseData, 'message' => 'invitePlayerInZooterBucket : '.$requestResponseMessage);
    } else {
      $response = array('status' => 107, 'message' => 'invitePlayerInZooterBucket : adding player in Zooter Bucket addition unsuccessfull');
    }

    if ($response['status'] == 200) {
      $dataSource->commit();
    } else {
      $dataSource->rollback();
    }

    return $response;
  }

  private function __prepareResponseZooterBucketInvite($userId,$status){
    $data = $this->User->Profile->find('first',array(
      'conditions' => array(
        'Profile.user_id' => $userId
      ),
      'fields' => array('user_id','first_name','middle_name','last_name','image_id'),
      'contain' => array(
        'User' => array(
          'fields' => array('id'),
          'PlayerProfile' => array(
            'fields' => array('playing_roles')
          )
        ),
        'ProfileImage' => array(
          'fields' => array('id','url')
        )
      )
    ));
    $response['id'] = $userId;
    if (!empty($data['Profile'])) {
      $response['name'] = $this->_prepareUserName($data['Profile']['first_name'],$data['Profile']['middle_name'],$data['Profile']['last_name']);
      $response['first_name'] = $data['Profile']['first_name'];
      $response['middle_name'] = $data['Profile']['middle_name'];
      $response['last_name'] = $data['Profile']['first_name'];
    } else {
      $response[$key]['name'] = null;
      $response['first_name'] = null;
      $response['middle_name'] = null;
      $response['last_name'] = null;
    }

    if (!empty($data['User']['PlayerProfile'])) {
      $response['role'] = PlayerRole::stringValue($data['User']['PlayerProfile']['playing_roles']);
    } else {
      $response['role'] = null;
    }

    if (!empty($data['ProfileImage'])) {
      $response['image'] = $data['ProfileImage']['url'];
    } else {
      $response['image'] = null;
    }
    $response['status'] = InvitationStatus::stringvalue($status);
    return $response;
  }

	public function fetchMatchZooterBucket($matchId) {
		$matchId = trim($matchId);
		if (empty($matchId)) {
  		return array('status' => 100, 'message' => 'fetchMatchZooterBucket : Invalid Input Arguments');
  	}
  	if (!$this->Match->checkIfMatchExists($matchId)) {
  		return array('status' => 105 , 'message' => 'fetchMatchZooterBucket : Match Id does not exist');
  	}
  	$players = $this->find('all',array(
  		'conditions' => array(
  			'ZooterBucket.match_id' => $matchId,
        'ZooterBucket.status' => [InvitationStatus::INVITED,InvitationStatus::CONFIRMED,InvitationStatus::REQUEST_PENDING]
  		),
  		'fields' => array('id','user_id','status'),
  		'contain' => array(
  			'User' => array(
  				'fields' => array('id'),
  				'Profile' => array(
  					'fields' => array('user_id','first_name','middle_name','last_name'),
            'ProfileImage' => array(
              'fields' => array('id','url')
            )
  				),
  				'PlayerProfile' => array(
  					'fields' => array('batting_arm','bowling_arm','bowling_style','playing_roles')
  				)
  			)
  		)
	  ));
	  $response = array();
	  foreach ($players as $key => $player) {
	  	if (!empty($player['User']['Profile'])) {
	  		$response[$key]['id'] = $player['User']['Profile']['user_id'];
	  		$response[$key]['name'] = $this->_prepareUserName($player['User']['Profile']['first_name'],$player['User']['Profile']['middle_name'],$player['User']['Profile']['last_name']);
   			$response[$key]['first_name'] = $player['User']['Profile']['first_name'];
   			$response[$key]['middle_name'] = $player['User']['Profile']['middle_name'];
   			$response[$key]['last_name'] = $player['User']['Profile']['last_name'];
   		} else {
   			$response[$key]['id'] = null;
   			$response[$key]['name'] = null;
   			$response[$key]['first_name'] = null;
   			$response[$key]['middle_name'] = null;
   			$response[$key]['last_name'] = null;
   		}
      if (!empty($player['User']['Profile']['ProfileImage'])) {
        $response[$key]['image'] = $player['User']['Profile']['ProfileImage']['url'];
      } else {
        $response[$key]['image'] = null;
      }
   		if (!empty($player['User']['PlayerProfile'])) {
   			$response[$key]['batting_style'] = PlayerProfileType::stringValue($player['User']['PlayerProfile']['batting_arm']).' Hand Bat';
   			$response[$key]['bowling_style'] = PlayerProfileType::stringValue($player['User']['PlayerProfile']['bowling_arm']).' Arm '.PlayerProfileType::stringValue($player['User']['PlayerProfile']['bowling_style']);
   			$response[$key]['role'] = PlayerRole::stringValue($player['User']['PlayerProfile']['playing_roles']);
   		} else {
   			$response[$key]['batting_style'] = null;
   			$response[$key]['bowling_style'] = null;
   			$response[$key]['role'] = null;
   		}
      $response[$key]['status'] = InvitationStatus::stringvalue($player['ZooterBucket']['status']);
	  }
	  return array('status' => 200, 'data' => $response);
  }

  public function searchPlayersForZooterBasket($userId,$matchId,$input) {
    $matchId = trim($matchId);
    $userId = trim($userId);
    $input = trim($input);
    if (empty($userId) || empty($matchId) || empty($input)) {
      return array('status' => 100, 'message' => 'searchPlayersForZooterBasket : Invalid Input Arguments');
    }
    if (!$this->_userExists($userId)) {
      return array('status' => 103 , 'message' => 'searchPlayersForZooterBasket : Invalid User or invite_user ID');
    }
    if (!$this->Match->checkIfMatchExists($matchId)) {
      return array('status' => 103 , 'message' => 'searchPlayersForZooterBasket : Invalid Match ID');
    }
    
    $numOfRecords = Limit::NUM_OF_PLAYERS_SEARCH_IN_ZOOTER_BUCKET;
    $users = $this->User->searchUser($input,$numOfRecords);
    if ($users['status'] != 100) {
      $users = $users['data'];
    } else return array('status' => $users['status'], 'message' => $users['message']);
    if (empty($users)) {
      return array('status' => 200, 'data' => array('players' => $users));
    }

    $zooterBucketPlayers = $this->fetchMatchZooterBucket($matchId)['data'];
    $response = array();
    foreach ($users as $key => $user) {
      $found = false;
      foreach ($zooterBucketPlayers as $zooterBucketPlayer) {
        if ($user['id'] == $zooterBucketPlayer['id']) {
          $user['status'] = $zooterBucketPlayer['status'];
          $found = true;
        }
      }
      if ($found == false) {
        $user['status'] = InvitationStatus::stringValue(InvitationStatus::NOT_INVITED);
      }
      if ($user['status'] != InvitationStatus::stringValue(InvitationStatus::BLOCKED)) {
        array_push($response,$user);        
      }
    }
    return array('status' => 200, 'data' => array('players' => $response));
  }

  public function deletePlayerFromZooterBucket($matchId,$userId) {
    $bucket = $this->findByMatchIdAndUserId($matchId,$userId)['ZooterBucket'];
    $data = array(
      'ZooterBucket' => array(
        'id' => $bucket['id'],
        'match_id' => $matchId,
        'user_id' => $userId,
        'deleted' => true
      )
    );
    if ($this->save($data)) {
      return array('status' => 200 , 'message' => 'success' , 'data' => true);
    } else {
      return array('status' => 108, 'message' => 'deletePlayerFromZooterBucket : Player could not be deleted from Zooter Bucket');
    }
  }

  public function checkIfPlayerInZooterBucket($matchId,$userId) {
    return $this->find('count',array(
      'conditions' => array(
        'ZooterBucket.match_id' => $matchId,
        'ZooterBucket.user_id' => $userId
      )
    ));
  }

  public function isPlayerConfirmedInZooterBucket($matchId,$userId) {
    return $this->find('count',array(
      'conditions' => array(
        'ZooterBucket.match_id' => $matchId,
        'ZooterBucket.user_id' => $userId,
        'ZooterBucket.status' => InvitationStatus::CONFIRMED
      )
    ));
  }

}

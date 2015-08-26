<?php
App::uses('AppModel', 'Model');
App::uses('UserRequest', 'Model');
App::uses('FriendshipStatus', 'Lib/Enum');
App::uses('UserRequestType' , 'Lib/Enum');
/**
 * UserFriend Model
 *
 * @property User $User
 */
class UserFriend extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'UserFrom' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'UserTo' => array(
			'className' => 'User',
			'foreignKey' => 'friend_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function sendFriendRequest($userId, $friendId) {
		if ($this->_userExists($userId)) {
			if ($this->_userExists($friendId)) {
				if ($this->__userEligibleToSendFriendRequest($userId, $friendId)) {
					$data = array(
						'user_id' => $userId,
						'friend_id' => $friendId,
            'status' => FriendshipStatus::PENDING
					);
					$this->create();
          if ($this->save($data)) {
            $responseData = array(
              'friendship_id' => $this->getLastInsertID(),
            );
            $response = array('status' => 200, 'data' => $responseData);
          }
				} else {
					$response = array('status' => 802, 'message' => 'User is not eligible to send friend request');
				}
			} else {
				$response = array('status' => 801, 'message' => 'Friend does not exist');
			}
		} else {
			$response = array('status' => 904, 'message' => 'User does not exist');
		}
    return $response;
	}
  public function cancelPendingRequest($userId, $friendId) {
    if ($this->_userExists($userId)) {
      if ($this->_userExists($friendId)) {
        if ($this->__userEligibleToProcessPendingRequest($friendId, $userId)) {
          $pendingRequest = $this->findByUserIdAndFriendIdAndStatus($friendId, $userId, FriendshipStatus::PENDING); // Notice that params are reversed
          $this->id = $pendingRequest['UserFriend']['id'];
          if ($this->saveField('status', FriendshipStatus::REJECTED)) {
            $responseData = array(
              'id' => $pendingRequest['UserFriend']['id']
            );
            $response = array('status' => 200, 'data' => $responseData);
          }
        } else {
          $response = array('status' => 802, 'message' => 'User is not eligible to cancel friend request');
        }
      } else {
        $response = array('status' => 801, 'message' => 'Friend does not exist');
      }
    } else {
      $response = array('status' => 904, 'message' => 'User does not exist');
    }
    return $response;
  }

  public function acceptPendingRequest($userId, $friendId) {
    if ($this->_userExists($userId)) {
      if ($this->_userExists($friendId)) {
        if ($this->__userEligibleToProcessPendingRequest($friendId, $userId)) {
          $pendingRequest = $this->findByUserIdAndFriendIdAndStatus($friendId, $userId, FriendshipStatus::PENDING); // Notice that params are reversed
          $this->id = $pendingRequest['UserFriend']['id'];
          if ($this->saveField('status', FriendshipStatus::ACCEPTED)) {
            $friend = $this->UserFrom->Profile->findByUserId($friendId);

            $responseData = array(
              'name' => $friend['Profile']['first_name'],
              'friend_id' => $friend['Profile']['user_id']
            );
            $response = array('status' => 200, 'data' => $responseData);
          }
        } else {
          $response = array('status' => 802, 'message' => 'User is not eligible to accept friend request');
        }
      } else {
        $response = array('status' => 801, 'message' => 'Friend does not exist');
      }
    } else {
      $response = array('status' => 904, 'message' => 'User does not exist');
    }
    return $response;
  }

  public function getFriendslist($userId,$numOfRecords,$filterLike) {
    $userId = trim($userId);
    $numOfRecords = trim($numOfRecords);
    if(empty($userId)) {
      return array('status' => 100, 'message' => 'getUserFanClubs : Invalid Input Arguments');
    }
    if (empty($numOfRecords)) {
      $numOfRecords = Limit::NUM_OF_USER_FRIENDS;
    }
    if (!$this->_userExists(($userId))) {
      return array('status' => 904, 'message' => 'User does not exist');
    }
    $friends = $this->find('all', array(
      'conditions' => array(
        'OR' => array(
          'FriendFrom.user_id' => $userId,
          'FriendFrom.friend_id' => $userId
        ),
        'FriendFrom.status' => FriendshipStatus::ACCEPTED
      ),
      'fields' => array('id','user_id','friend_id'),
      'order' => 'FriendFrom.created DESC',
      'limit' => $numOfRecords
    ));
    $friendIds = array();
    $userFriendTableIds = array();
    foreach ($friends as $id => $friend) {
      if ($friend['FriendFrom']['user_id'] == $userId) {
        $friendIds[$friend['FriendFrom']['id']] = $friend['FriendFrom']['friend_id'];
        $userFriendTableIds[$friend['FriendFrom']['friend_id']] = $friend['FriendFrom']['id'];
      } else {
        $friendIds[$friend['FriendFrom']['id']] = $friend['FriendFrom']['user_id'];
         $userFriendTableIds[$friend['FriendFrom']['user_id']] = $friend['FriendFrom']['id'];
      }
    }
    $friendsData = array();
    if (!empty($friendIds)) {
      $options = array(
        'conditions' => array(
          'Profile.user_id' => $friendIds
        ),
        'fields' => array('id','user_id','first_name','middle_name','last_name'),
        'contain' => array(
          'ProfileImage' => array(
            'fields' => array('id','url')
          )
        )
      );
      if (!empty($filterLike)) {
        $options['conditions']['OR'] = array(
          'Profile.first_name LIKE' => "%$filterLike%",
          'Profile.middle_name LIKE' => "%$filterLike%",
          'Profile.last_name LIKE' => "%$filterLike%"
        );
      }
      $friendsProfiles = $this->UserFrom->Profile->find('all',$options);
      foreach ($friendsProfiles as $id => $friend) {
        $name = $this->__prepareUserName($friend['Profile']['first_name'],
                                          $friend['Profile']['middle_name'],
                                          $friend['Profile']['last_name']);
        $friendsData[$id]['id'] = $userFriendTableIds[$friend['Profile']['user_id']];
        if (!empty($friend['Profile']['ProfileImage'])) {
          $image = $friend['Profile']['ProfileImage']['url'];
        } else $image = NULL;
        $friendsData[$id]['user']['id'] = $friend['Profile']['user_id'];
        $friendsData[$id]['user']['name'] = $name;
        $friendsData[$id]['user']['image'] = $image;
      }
    }
    $countOfFriends = $this->getTotalCountOfFriends($userId);
    $data = array('total' => $countOfFriends, 'friends' => $friendsData);
    return array('status' => 200, 'data' => $data);
  }

  public function getTotalCountOfFriends($userId) {
    return $this->find('count', array(
      'conditions' => array(
        'OR' => array(
          'FriendFrom.user_id' => $userId,
          'FriendFrom.friend_id' => $userId
        ),
        'FriendFrom.status' => FriendshipStatus::ACCEPTED
      )
    ));
  }

  public function getPendingFriendRequests($userId) {
    if ($this->_userExists($userId)) {
      $pendingRequests = $this->find('all', array(
        'conditions' => array(
          'UserFriend.friend_id' => $userId,
          'UserFriend.status' => FriendshipStatus::PENDING
        ),
        'contain' => array(
          'UserFrom' => array(
            'Profile'
          )
        )
      ));
      $responseData = array();
      foreach ($pendingRequests as $id => $pendingRequest) {
        $responseData[$id]['name'] = $pendingRequest['UserFrom']['Profile']['first_name'];
        $responseData[$id]['friendshipId'] = $pendingRequest['UserFriend']['id'];
        $responseData[$id]['friend_id'] = $pendingRequest['UserFriend']['user_id'];
      }
      $response = array('status' => 200, 'data' => $responseData);
    } else {
      $response = array('status' => 904, 'message' => 'User does not exist');
    }
    return $response;
  }

  public function sentFriendRequests($userId) {
    if ($this->_userExists($userId)) {
      $responseData = array();
      $sentRequests = $this->find('all', array(
        'conditions' => array(
          'UserFriend.user_id' => $userId,
          'NOT' => array(
            'UserFriend.status' => FriendshipStatus::BLOCKED
          )
        )
      ));
      
      if (!empty($sentRequests)) {
        $friendIds = Hash::extract($sentRequests, '{n}.UserFriend.friend_id');
        $friendProfiles = $this->UserFrom->Profile->find('all', array(
          'fields' => array(
            'Profile.first_name',
            'Profile.user_id'
          ),
          'conditions' => array(
            'Profile.user_id' => $friendIds
          )
        ));
        $friendNames = array();
        foreach ($friendProfiles as $id => $friendProfile) {
          $friendNames[$friendProfile['Profile']['user_id']] = $friendProfile['Profile']['first_name'];
        }
        foreach ($sentRequests as $id => $sentRequest) {
          $responseData[$id]['name'] = $friendNames[$sentRequest['UserFriend']['friend_id']];
          $responseData[$id]['status'] = $sentRequest['UserFriend']['status'];
          $responseData[$id]['friend_id'] = $sentRequest['UserFriend']['friend_id'];
        }
      }
      $response = array('status' => 200, 'data' => $responseData);
    } else {
      $response = array('status' => 904, 'message' => 'User does not exist');
    }
    return $response;
  }

  private function __userEligibleToSendFriendRequest($userId, $friendId) {
    return !$this->find('count', array(
      'conditions' => array(
        'UserFriend.user_id' => $userId,
        'UserFriend.friend_id' => $friendId,
        'OR' => array(
          'UserFriend.status' => FriendshipStatus::ACCEPTED,
          'UserFriend.status' => FriendshipStatus::BLOCKED,
          'UserFriend.status' => FriendshipStatus::PENDING
        )
      )
    ));
  }
  private function __userEligibleToProcessPendingRequest($userId, $friendId) {
    return $this->find('count', array(
      'conditions' => array(
        'UserFriend.user_id' => $userId,
        'UserFriend.friend_id' => $friendId,
        'UserFriend.status' => FriendshipStatus::PENDING
      )
    ));
  }

  public function getUserFriendsIdList($userId) { 
    $friendsIdList = array();
    $count = 0;
    $friendList = $this->find('all' , array(
      'fields' => array('user_id','friend_id'),
      'conditions' => array(
        'OR' => array(
          'user_id' => $userId,
          'friend_id' => $userId
        ),
        'status' => FriendshipStatus::ACCEPTED
      ) 
    ));

    if (empty($friendList)) {
      return $friendsIdList;
    }
    
    foreach ($friendList as $list) {
      if ($list['FriendFrom']['user_id'] == $userId) {
        $friendsIdList[$count] = $list['FriendFrom']['friend_id'];
      }
      else {
        $friendsIdList[$count] = $list['FriendFrom']['user_id'];
      }
      $count = $count + 1;
    }
    return $friendsIdList;
  }

  public function handleFriendRequest($requestId,$userId,$status) {
    if (!empty($requestId) && !empty($userId) && !empty($status)) {
      if ($this->isUserEligbleForRequest($requestId,$userId)) {
        $data = array(
          'FriendTo' => array(
            'id' => $requestId,
            'status' => $status
          )
        );
        if ($this->save($data)) {
          $response =  array('status' => 200 , 'message' => 'success');
        } 
        else {
          $response =  array('status' => 105, 'message' => 'handleFriendRequest : Friend Request Accept Could not be updated');
        } 
      }
      else {
        $response =  array('status' => 104, 'message' => 'handleFriendRequest : User Not Eligible to Accept Friend Request');
      }
    }
    else {
      $response =  array('status' => 103, 'message' => 'handleFriendRequest : Invalid Input Arguments');
    }
    return $response;
  }

  public function isUserEligbleForRequest($requestId,$friendId) {
    return $this->find('count',array(
      'conditions' => array(
        'FriendTo.id' => $requestId,
        'FriendTo.friend_id' => $friendId,
        'FriendTo.status' => FriendshipStatus::INVITED,
      )
    ));
  }

  public function isUserAFriend($userId,$friendId) {
    return $this->find('count',array(
      'conditions' => array(
        'OR' => array(
          array(
            'AND' => array(
              'user_id' => $userId,
              'friend_id' => $friendId
            )
          ),
          array(
            'AND' => array(
              'user_id' => $friendId,
              'friend_id' => $userId
            )
          )
        ),
        'status' => FriendshipStatus::ACCEPTED
      )
    ));
  }

  public function toggleFriendship($userId,$friendId) {
    $userId = trim($userId);
    $friendId = trim($friendId);
    if (empty($userId) || empty($friendId)) {
      return array('status' => 100, 'message' => 'toggleFriendship : Invalid Input Arguments');
    }
    if (!$this->_userExists(($userId))) {
      return array('status' => 100, 'message' => 'toggleFriendship : UserID Does not exist');
    }
    if (!$this->_userExists(($friendId))) {
      return array('status' => 100, 'message' => 'toggleFriendship : User Friend ID Does not exist');
    }
    $isRequestActive = false;
    $requestId = null;
    $record = $this->find('first',array(
      'conditions' => array(
        'OR' => array(
          array(
            'AND' => array(
              'user_id' => $userId,
              'friend_id' => $friendId
            )
          ),
          array(
            'AND' => array(
              'user_id' => $friendId,
              'friend_id' => $userId
            )
          )
        )
      ),
      'fields' => array('id','status')
    ));
    if (empty($record)) {
      $data['user_id'] = $userId;
      $data['friend_id'] = $friendId;
      $data['status'] = FriendshipStatus::INVITED;
      $isRequestActive = true;
    } else {
      $requestId = $record['UserFriend']['id'];
      $data['id'] = $record['UserFriend']['id'];
      if ($record['UserFriend']['status'] == FriendshipStatus::INVITED || $record['UserFriend']['status'] == FriendshipStatus::ACCEPTED) {
        $data['status'] = FriendshipStatus::UNFRIEND;
      } else {
        $data['status'] = FriendshipStatus::INVITED;
        $isRequestActive = true;
      }
    }
    $dataSource = $this->getDataSource();
    $dataSource->begin();
    if ($this->save($data)) {
      if (empty($record)) {
        $requestId = $this->getLastInsertID();
      } 
      $UserRequest = new UserRequest();    
      $userRequest = $UserRequest->createOrUpdateUserRequest(UserRequestType::FRIEND_REQUEST,$requestId,$userId,$isRequestActive,$userId);
      if($userRequest['status'] == 200) {
        $response = array('status' => 200, 'data' => true, 'message' => 'Friendship success');
      } else {
        $response = array('status' => $userRequest['status'] , 'message' => $userRequest['message']);
      }
    } else {
      $response = array('status' => 102, 'message' => 'toggleFriendship : Friendship record could not be created');
    }

    if ($response['status'] == 200) {
      $dataSource->commit();
    } else {
      $dataSource->rollback();
    }

    return $response;
  }

  public function getFriendShipStatus($userId,$friendId) {
    $UserFriend = new UserFriend();
    $data = $UserFriend->find('first',array(
      'conditions' => array(
        'OR' => array(
          array(
            'AND' => array(
              'user_id' => $userId,
              'friend_id' => $friendId
            )
          ),
          array(
            'AND' => array(
              'user_id' => $friendId,
              'friend_id' => $userId
            )
          )
        )
      )
    ));
    if (!empty($data)) {
      if ($data['UserFriend']['status'] == FriendshipStatus::REJECTED || $data['UserFriend']['status'] == FriendshipStatus::UNFRIEND || $data['UserFriend']['status'] == FriendshipStatus::NOT_A_FRIEND) {
        return FriendshipStatus::stringValue(FriendshipStatus::NOT_A_FRIEND);       
      } else {
        return FriendshipStatus::stringValue($data['UserFriend']['status']);       
      }
    } else {
      return FriendshipStatus::stringValue(FriendshipStatus::NOT_A_FRIEND);
    }
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

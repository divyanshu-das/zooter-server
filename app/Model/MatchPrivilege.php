<?php
App::uses('AppModel', 'Model');
App::uses('UserRequest', 'Model');
App::uses('InvitationStatus', 'Lib/Enum');
App::uses('UserRequestType', 'Lib/Enum');
/**
 * MatchPrivilege Model
 *
 * @property Match $Match
 * @property User $User
 */
class MatchPrivilege extends AppModel {

	public $validate = array(
		'match_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Match id is not numeric',
				// 'required' => true,
				// 'allowEmpty' => false,
				// 'on' => 'create'
			),
			// 'matchExist' => array(
			// 	'rule' => array('matchExist'),
			// 	'on' => 'create',
			// )
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'User id is not numeric',
				// 'required' => true,
				// 'allowEmpty' => false,
				// 'on' => 'create'
			),
			// 'userExist' => array(
			// 	'rule' => array('userExist')
			// )
		),
		'is_admin' => array(
			'boolean' => array(
				'rule' => array('boolean'),
        'message' => 'IsAdmin value is not valid',
				// 'required' => true,
				// 'allowEmpty' => false,
				// 'on' => 'create'
			)
		),
		'status' => array(
			'numeric' => array(
				'rule' => array('numeric'),
        'message' => 'status value is not valid',
				// 'required' => true,
				// 'allowEmpty' => false,
			)
		),
	);

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

	public function inviteAdmin($userId,$matchId,$inviteUserId) {
		$matchId = trim($matchId);
    $userId = trim($userId);
  	$inviteUserId = trim($inviteUserId);
    if (empty($userId) || empty($matchId) || empty($inviteUserId)) {
      return array('status' => 100, 'message' => 'inviteAdmin : Invalid Input Arguments');
    }
  	if ( (!$this->_userExists($userId)) || (!$this->_userExists($inviteUserId)) ) {
			return array('status' => 103 , 'message' => 'inviteAdmin : Invalid User or invite_user ID');
		}
		if (!$this->Match->checkIfMatchExists($matchId)) {
  		return array('status' => 103 , 'message' => 'inviteAdmin : Invalid Match ID');
  	}
  	if (!$this->isUserMatchAdmin($matchId,$userId)) {
  		return array('status' => 104 , 'message' => 'inviteAdmin : Only Admin can add');
  	}
  	$data = array();
  	$existingStatus = $this->findByMatchIdAndUserId($matchId,$inviteUserId);
  	if (!empty($existingStatus['MatchPrivilege'])) {
  		if ($existingStatus['MatchPrivilege']['status'] == InvitationStatus::ACCEPTED || $existingStatus['MatchPrivilege']['status'] == InvitationStatus::INVITED) {
	  		$response['user_id'] = $inviteUserId;
	  		$response['status'] = InvitationStatus::stringvalue($existingStatus['MatchPrivilege']['status']);
	  		return array('status' => 200, 'data' => $response);
	  	} elseif ($existingStatus['MatchPrivilege']['status'] == InvitationStatus::REJECTED) {
	  		$data['MatchPrivilege']['id'] = $existingStatus['MatchPrivilege']['id'];
	  	}
  	}
  	$data['MatchPrivilege']['user_id'] = $inviteUserId;
  	$data['MatchPrivilege']['match_id'] = $matchId;
  	$data['MatchPrivilege']['is_admin'] = true;
  	$data['MatchPrivilege']['status'] = InvitationStatus::INVITED;

  	$dataSource = $this->getDataSource();
    $dataSource->begin();
  	if ($this->save($data)) {
  		$UserRequest = new UserRequest();
  		$requestResponse = $UserRequest->createOrUpdateUserRequest(
  			UserRequestType::MATCH_ADMIN_ADD_INVITE,
  			$this->getLastInsertID(),
  			$inviteUserId,
  			true,
  			$userId
  		);
  		$responseData['user_id'] = $inviteUserId;
  		$responseData['status'] = InvitationStatus::stringvalue(InvitationStatus::INVITED);
  		$response = array('status' => $requestResponse['status'], 'data' => $responseData, 'message' => 'inviteAdmin : '.$requestResponse['message']);
  	} else {
  		$response = array('status' => 107, 'message' => 'inviteAdmin : admin addition unsuccessfull');
  	}

  	if ($response['status'] == 200) {
      $dataSource->commit();
    } else {
    	$dataSource->rollback();
    }

  	return $response;
	}

	public function deleteAdmin($userId,$matchId,$deleteUserId) {
		$matchId = trim($matchId);
  	$userId = trim($userId);
  	if (empty($userId) || empty($matchId)) {
  		return array('status' => 100, 'message' => 'deleteAdmin : Invalid Input Arguments');
  	}
  	if ( (!$this->_userExists($userId)) || (!$this->_userExists($deleteUserId)) ) {
			return array('status' => 103 , 'message' => 'deleteAdmin : Invalid User or invite_user ID');
		}
		if (!$this->Match->checkIfMatchExists($matchId)) {
  		return array('status' => 103 , 'message' => 'deleteAdmin : Invalid Match ID');
  	}
  	if (!$this->isUserMatchAdmin($matchId,$userId)) {
  		return array('status' => 104 , 'message' => 'deleteAdmin : Only Admin can delete');
  	}
    if ($this->Match->isUserMatchOwner($matchId,$deleteUserId)) {
      return array('status' => 107, 'message' => 'deleteAdmin : Match Owner Cannot be deleted');
    }
  	$data = array();
  	$existingStatus = $this->findByMatchIdAndUserId($matchId,$deleteUserId);
  	if (empty($existingStatus['MatchPrivilege'])) {
  		return array('status' => 108, 'message' => 'deleteAdmin : delete user is not admin');
  	}
  	$data['MatchPrivilege']['id'] = $existingStatus['MatchPrivilege']['id'];
  	$data['MatchPrivilege']['is_admin'] = false;
  	$data['MatchPrivilege']['status'] = InvitationStatus::REMOVED;
  	$data['MatchPrivilege']['deleted'] = true;

  	$dataSource = $this->getDataSource();
    $dataSource->begin();
  	if ($this->save($data)) {
  		$UserRequest = new UserRequest();
  		$requestResponse = $UserRequest->createOrUpdateUserRequest(
  			UserRequestType::MATCH_ADMIN_ADD_INVITE,
  			$existingStatus['MatchPrivilege']['id'],
  			$deleteUserId,
  			false,
  			$userId
  		);
  		$responseData['user_id'] = $deleteUserId;
  		$responseData['status'] = InvitationStatus::stringvalue(InvitationStatus::REMOVED);
  		$response = array('status' => $requestResponse['status'], 'data' => $responseData, 'message' => 'inviteAdmin : '.$requestResponse['message']);
  	} else {
  		$response = array('status' => 107, 'message' => 'deleteAdmin : admin deletion unsuccessfull');
  	}

  	if ($response['status'] == 200) {
      $dataSource->commit();
    } else {
    	$dataSource->rollback();
    }

  	return $response;
  }

	public function fetchMatchAdmins($matchId) {
  	if (!$this->Match->checkIfMatchExists($matchId)) {
  		return array('status' => 105 , 'message' => 'fetchMatchAdmins : Match Id does not exist');
  	}
  	$admins = $this->find('all',array(
  		'conditions' => array(
  			'MatchPrivilege.match_id' => $matchId,
  			'MatchPrivilege.is_admin' => true,
  			'OR' => array(
          array('MatchPrivilege.status' => InvitationStatus::ACCEPTED),
          array('MatchPrivilege.status' => InvitationStatus::CONFIRMED)
        )
  		),
  		'fields' => array('id','user_id','status'),
  		'contain' => array(
  			'User' => array(
  				'fields' => array('id'),
  				'Profile' => array(
  					'fields' => array('user_id','first_name','middle_name','last_name','image_id'),
            'ProfileImage' => array(
              'fields' => array('id','url')
            )
  				)
  			)
  		)
	  ));
	  $match = $this->Match->findById($matchId);
	  $ownerId = $match['Match']['owner_id'];
	  $response = array();
	  foreach ($admins as $key => $admin) {
	  	if (!empty($admin['User']['Profile'])) {
	  		$response[$key]['id'] = $admin['User']['Profile']['user_id'];
	  		$response[$key]['name'] = $this->_prepareUserName($admin['User']['Profile']['first_name'],$admin['User']['Profile']['middle_name'],$admin['User']['Profile']['last_name']);
   			if ( !empty($ownerId) && ($admin['User']['Profile']['user_id'] == $ownerId) ) {
   				$response[$key]['is_owner'] = true;
   			}
   		} else {
   			$response[$key]['id'] = null;
   			$response[$key]['name'] = null;
   		}
      if (!empty($admin['User']['Profile']['ProfileImage'])) {
        $response[$key]['image'] = $admin['User']['Profile']['ProfileImage']['url'];
      } else {
        $response[$key]['image'] = null;
      }
      $response[$key]['status'] = InvitationStatus::stringvalue(InvitationStatus::CONFIRMED);

	  }
	  return array('status' => 200, 'data' => $response);
  }

  public function fetchAllInvitedMatchAdmins($matchId) {
    return $this->find('all',array(
      'conditions' => array(
        'MatchPrivilege.match_id' => $matchId,
      ),
      'fields' => array('id','user_id','status')
    ));
  }

  public function searchMatchAdmins($userId,$matchId,$input) {
    $matchId = trim($matchId);
    $userId = trim($userId);
    $input = trim($input);
    if (empty($userId) || empty($matchId) || empty($input)) {
      return array('status' => 100, 'message' => 'searchMatchAdmins : Invalid Input Arguments');
    }
    if (!$this->_userExists($userId)) {
      return array('status' => 103 , 'message' => 'searchMatchAdmins : Invalid User or invite_user ID');
    }
    if (!$this->Match->checkIfMatchExists($matchId)) {
      return array('status' => 103 , 'message' => 'searchMatchAdmins : Invalid Match ID');
    }
    if (!$this->isUserMatchAdmin($matchId,$userId)) {
      return array('status' => 104 , 'message' => 'searchMatchAdmins : Only Admin can search');
    }
    
    $numOfRecords = Limit::NUM_OF_ADMINS_SEARCH_IN_MATCH_ADMIN;
    $users = $this->User->searchUserFriends($userId,$input,$numOfRecords);
    if ($users['status'] != 100) {
      $users = $users['data'];
    } else {
      return array('status' => $users['status'], 'message' => $users['message']);
    }
    if (empty($users)) {
      return array('status' => 200, 'data' => array('admins' => $users));
    }

    $matchAdmins = $this->fetchAllInvitedMatchAdmins($matchId);
    $response = array();
    foreach ($users as $key => $user) {
      $found = false;
      foreach ($matchAdmins as $matchAdmin) {
        if ($user['id'] == $matchAdmin['MatchPrivilege']['user_id']) {
          if ( ($matchAdmin['MatchPrivilege']['status'] != InvitationStatus::BLOCKED) || ($matchAdmin['MatchPrivilege']['status'] != InvitationStatus::REJECTED) || ($matchAdmin['MatchPrivilege']['status'] != InvitationStatus::REMOVED)) {
            $user['status'] = InvitationStatus::stringValue($matchAdmin['MatchPrivilege']['status']);
            $found = true;
          }
        }
      }
      if ($found == false) {
        $user['status'] = InvitationStatus::stringValue(InvitationStatus::NOT_INVITED);
      }
      if ($user['status'] != InvitationStatus::stringValue(InvitationStatus::BLOCKED)) {
        array_push($response,$user);        
      }
    }
    return array('status' => 200, 'data' => array('admins' => $response));
  }

  public function checkMatchAdminUser($matchId,$userId) {
  	$matchId = trim($matchId);
  	$userId = trim($userId);
  	if (!$this->_userExists($userId)) {
			return array('status' => 103 , 'message' => 'checkMatchAdminUser : Invalid User ID');
		}
		if (!$this->Match->checkIfMatchExists($matchId)) {
  		return array('status' => 103 , 'message' => 'checkMatchAdminUser : Invalid Match ID');
  	}
  	return array('status' => 200, 'data' => $this->isUserMatchAdmin($matchId,$userId));
  }

  public function isUserMatchAdmin($matchId,$userId) {
  	return $this->find('count',array(
  		'conditions' => array(
  			'MatchPrivilege.match_id' => $matchId,
  			'MatchPrivilege.user_id' => $userId,
  			'MatchPrivilege.is_admin' => true,
        'OR' => array(
          array('MatchPrivilege.status' => InvitationStatus::ACCEPTED),
          array('MatchPrivilege.status' => InvitationStatus::CONFIRMED)
        ) 			
  		)
  	));
  }

	public function showMatchPrivilege($id) {
		$match_privilege = $this->findById($id);
		if ( ! empty($match_privilege)) {
			$response = array('status' => 200, 'data' => $match_privilege);
		} else {
			$response = array('status' => 302, 'message' => 'Match Privilege Data Could Not Be Retrieved');
		}
		return $response;
	}

	public function deleteMatchPrivilege($id) {
		$matchPrivilege = $this->showMatchPrivilege($id);
		if ($matchPrivilege['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'MatchPrivilege does not exist');
			return $response;
		}
		$this->_updateCache($id);
		$delete = $this->softDelete($id);
		$response = array('status' => 200, 'data'=> $delete , 'message' => 'Deleted');
		return $response;
	}

	public function updateMatchPrivileges($matchId , $matchPrivileges) {
		$match = $this->Match->showMatch($matchId);
		if ($match['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Match does not exist');
			return $response;
		}
		$this->validateId();
		$this->_updateCache(null,$matchPrivileges,$matchId);
		$this->validator()->add('match_id', 'required', array( 'rule' => 'notEmpty', 'required' => 'update' ));
		if ( ! empty($matchPrivileges)) {
			if ($this->saveMany($matchPrivileges)) {
				$response = array('status' => 200 , 'data' =>'Match Privilege Saved');
			} else {
				$response = array('status' => 316, 'message' => 'Match Privileges Could not be added');
			}
		} else {			
			$response = array('status' => 317, 'message' => 'No Data To Update Match Privilege');			
		}
		return $response ;
	}

	private function _updateCache($id = null,$matchPrivileges = null, $matchId = null) {
		if( ! empty($id)) {
			$matchPrivilege = $this->showMatchPrivilege($id);
			if( ! empty($matchPrivilege['data']['MatchPrivilege']['match_id'])) {
				Cache::delete('show_match_' . $matchPrivilege['data']['MatchPrivilege']['match_id']);
				//Cache::delete('show_user_'.$matchInningScorecard['data']['MatchInningScorecard']['user_id']);  *** use when show_user_1 cache exists ***
			}
		}
		if ( ! empty($matchId)) {
			//  $match = $this->Match->showMatch($matchId); 							*** use when show_user_1 cache exists ***
			Cache::delete('show_match_' . $matchId);
			//  foreach ($match['data']['MatchPlayer'] as $matchplayer) {				*** use when show_user_1 cache exists ***
			//  Cache::delete('show_user_'.$matchplayer['user_id']);							*** use when show_user_1 cache exists ***
			//  }
		}
		//  if(!empty($matchPrivileges)){
		//	foreach ($matchPrivileges as $matchPrivilege) {
		//		Cache::delete('show_user_'.$matchPrivilege['user_id']);
		//	}
		// }
  }

	public function getMatchAdminRequest($id) {
		$request_name = array();
  	if (!empty($id)) {
      $data = $this->find('first' ,array(
      	'conditions' => array(
      		'MatchPrivilege.id' => $id
      	),
        'fields' => array('match_id'),
        'contain' => array(
        	'Match' => array(
        		'fields' => array('id','start_date_time','name')
        	)
        )
      ));
      if (!empty($data)) {
        $request_name['match']['id'] = $data['Match']['id'];
        $request_name['match']['name'] = $data['Match']['name'];
        $request_name['match']['start_date_time'] = $data['Match']['start_date_time'];
      }
  	}
  	return $request_name;
	}

	public function handleMatchAdminRequest($requestId,$userId,$status) {
    if (!empty($requestId) && !empty($userId) && !empty($status)) {
      if ($this->isUserEligbleForRequest($requestId,$userId)) {
        $data = array(
          'MatchPrivilege' => array(
            'id' => $requestId,
            'status' => $status
          )
        );
        if ($this->save($data)) {
          $response =  array('status' => 200 , 'message' => 'success');
        } 
        else {
          $response =  array('status' => 128, 'message' => 'handleMatchAdminRequest : Match Admin Request Accept Could not be updated');
        } 
      }
      else {
        $response =  array('status' => 127, 'message' => 'handleMatchAdminRequest :User Not Eligible to Accept Match Admin Request');
      }
    }
    else {
      $response =  array('status' => 126, 'message' => 'handleMatchAdminRequest : Invalid Input Arguments');
    }
    return $response;
  }

  public function isUserEligbleForRequest($requestId,$userId) {
    return $this->find('count',array(
      'conditions' => array(
        'MatchPrivilege.id' => $requestId,
        'MatchPrivilege.user_id' => $userId,
        'MatchPrivilege.status' => InvitationStatus::INVITED
      )
    ));
  }

}

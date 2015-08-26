<?php
App::uses('AppModel', 'Model');
App::uses('UserRequest', 'Model');
App::uses('UserRequestType', 'Lib/Enum');
App::uses('InvitationStatus', 'Lib/Enum');
/**
 * TeamPlayer Model
 *
 * @property Team $Team
 * @property User $User
 */
class TeamPlayer extends AppModel {

	public $validate = array(
		'team_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Team id is not valid',
				// 'required' => true,
				// 'allowEmpty' => false,
				// 'on' => 'create'
			)
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'User id is not valid',
				// 'required' => true,
				// 'allowEmpty' => false,
				// 'on' => 'create'
			)
		),
		'role' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Status is not valid',
				// 'required' => true,
				// 'allowEmpty' => false
			),
		),
		'status' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'status is not valid',
				// 'required' => false,
				// 'allowEmpty' => false,
				// 'on' => 'create'
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
		'Team' => array(
			'className' => 'Team',
			'foreignKey' => 'team_id',
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

	public function showTeamPlayer($id) {
		$team_player = $this->findById($id);
		if ( ! empty($team_player)) {
			$response = array('status' => 200, 'data' => $team_player);
		} else {
			$response = array('status' => 302, 'message' => 'Team Player Data Could Not Be Retrieved');
		}
		return $response;
	}

	public function deleteTeamPlayer($id) {
		$teamPlayer = $this->showTeamPlayer($id);
		if ($teamPlayer['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Team Player does not exist');
			return $response;
		}
		$this->_updateCache($id,null);
		$delete = $this->softDelete($id);
		$response = array('status' => 200, 'data'=> $delete , 'message' => 'Deleted');
		return $response;
	}


	public function updateTeamPlayers($teamPlayers , $teamId) {
		$team = $this->Team->showTeam($teamId);
		if ($team['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Team does not exist');
			return $response;
		}
		$this->validateId();
		$this->_updateCache(null, $teamPlayers,$teamId);
		if ( ! empty($teamPlayers)) {
			if ($this->saveMany($teamPlayers)) {
				$response = array('status' => 200 , 'message' => ' Team Players Updated');
			} else {
				$response = array('status' => 312, 'message' => 'Team Players Could not be added or updated');
				pr($this->validationErrors);
			}
		} else {			
			$response = array('status' => 313, 'message' => 'No Data To Update or Add Team Players ');			
		}
		return $response ;
	}

	private function _updateCache($id = null,$teamPlayers = null, $teamId = null) {
		if ( ! empty($id)) {
			$teamPlayer = $this->showTeamPlayer($id);
			if ( ! empty($teamPlayer['data']['TeamPlayer']['team_id'])) {
				Cache::delete('show_team_' . $teamPlayer['data']['TeamPlayer']['team_id']);
				//Cache::delete('show_user_'.$teamPlayer['data']['TeamPlayer']['user_id']);  *** use when show_user_1 cache exists ***
			}
		}
		if ( ! empty($teamId)) {
			//  $team = $this->Team->showTeam($teamId); 							*** use when show_user_1 cache exists ***
			Cache::delete('show_team_' . $teamId);
			//  foreach ($team['data']['TeamPlayer'] as $teamplayer) {				*** use when show_user_1 cache exists ***
			//  Cache::delete('show_user_'.$teamplayer['user_id']);							*** use when show_user_1 cache exists ***
			//  }
		}
		//  if(!empty($teamPlayers)){
		//	foreach ($teamPlayers as $teamPlayer) {
		//		Cache::delete('show_user_'.$teamPlayer['user_id']);
		//	}
		// }
  }
public function updatePlayersStatusAndRoles($playersStatusAndRoles) {
		$this->validator()->add('team_id', 'required', array( 'rule' => 'notEmpty', 'required' => 'update' ));
		if (!empty($playersStatusAndRoles)) {
			if ($this->saveMany($playersStatusAndRoles)) {
				$response = array('status' => 200 , 'data' => '');
			} else {
				$response = array('status' => 407, 'message' => 'Updating Team Players Data Unsuccessfull');
				pr($this->validationErrors);
			}
		} else {			
			$response = array('status' => 410, 'message' => 'No Data To Update or Add Team Players and Roles');			
		}
		return $response ;
	}


	public function getTeamPlayerRequest($id) {
    $request_name = array();
  	if (!empty($id)) {
      $data = $this->find('first' ,array(
      	'conditions' => array('TeamPlayer.id' => $id),
        'fields' => array('id','team_id','role'),
        'contain' => array(
        	'Team' => array(
        		'fields' => array('id','name','image_id'),
        		'ProfileImage' => array(
        			'fields' => array('id','url')
        		)
        	)
        )
      ));
      if (!empty($data)) {
        $team = array(
          'id' => $data['TeamPlayer']['team_id'],
          'name' => $data['Team']['name']
        );
        if (!empty($data['Team']['ProfileImage'])) {
        	$team['image'] = $data['Team']['ProfileImage']['url'];
        } else $team['image'] = null;
        
        $request_name['team'] = $team;
      }
  	}
  	return $request_name;
  }

  public function handleTeamPlayerRequest($requestId,$userId,$status) {
    if (!empty($requestId) && !empty($userId) && !empty($status)) {
      if ($this->isUserEligbleForRequest($requestId,$userId)) {
        $data = array(
          'TeamPlayer' => array(
            'id' => $requestId,
            'status' => $status
          )
        );
        if ($this->save($data)) {
          $response =  array('status' => 200 , 'message' => 'success');
        } 
        else {
          $response =  array('status' => 113, 'message' => 'handleTeamPlayerRequest : Team Player Request Accept Could not be updated');
        } 
      }
      else {
        $response =  array('status' => 112, 'message' => 'handleTeamPlayerRequest : User Not Eligible to Accept Team Player Request');
      }
    }
    else {
      $response =  array('status' => 111, 'message' => 'handleTeamPlayerRequest : Invalid Input Arguments');
    }
    return $response;
  }

  public function inviteZooterBasketPlayerToTeam($userId,$matchId,$teamId,$inviteUserId) {
  	$matchId = trim($matchId);
		$userId = trim($userId);
		$teamId = trim($teamId);
		$inviteUserId = trim($inviteUserId);
		if (empty($userId) || empty($matchId) || empty($teamId) || empty($inviteUserId)) {
  		return array('status' => 100, 'message' => 'inviteZooterBasketPlayerToTeam : Invalid Input Arguments');
  	}
  	if ( (!$this->_userExists($userId)) || (!$this->_userExists($inviteUserId)) ) {
			return array('status' => 103 , 'message' => 'inviteZooterBasketPlayerToTeam : Invalid User or invite_user ID');
		}
		if (!$this->Team->MatchTeam->Match->checkIfMatchExists($matchId)) {
  		return array('status' => 103 , 'message' => 'inviteZooterBasketPlayerToTeam : Invalid Match ID');
  	}
  	if (!$this->_teamExists($teamId)) {
  		return array('status' => 103 , 'message' => 'inviteZooterBasketPlayerToTeam : Invalid Team ID');
  	}
  	if (!$this->Team->MatchTeam->isTeamConfirmedInMatch($matchId,$teamId)) {
  		return array('status' => 107, 'message' => 'inviteZooterBasketPlayerToTeam : Team is not Confirmed in Match');
  	}
  	if (!$this->Team->TeamPrivilege->isUserTeamAdmin($teamId,$userId)) {
  		return array('status' => 103 , 'message' => 'inviteZooterBasketPlayerToTeam : User is not Team Admin');
  	}
  	if (!$this->User->ZooterBucket->isPlayerConfirmedInZooterBucket($matchId,$inviteUserId)) {
  		return array('status' => 103 , 'message' => 'inviteZooterBasketPlayerToTeam : Player is not confirmed in Zooter Bucket');
  	}

  	$data = array();
    $existingStatus = $this->findByTeamIdAndUserId($teamId,$inviteUserId);
    if (!empty($existingStatus['TeamPlayer'])) {
      if ($existingStatus['TeamPlayer']['status'] == InvitationStatus::CONFIRMED || $existingStatus['TeamPlayer']['status'] == InvitationStatus::INVITED || $existingStatus['TeamPlayer']['status'] == InvitationStatus::REQUEST_PENDING) {
        $responseData['user_id'] = $inviteUserId;
        $responseData['team_id'] = $teamId;
      	$responseData['status'] =  InvitationStatus::stringvalue($existingStatus['TeamPlayer']['status']);
        return array('status' => 200, 'data' => $responseData);
      } elseif ($existingStatus['TeamPlayer']['status'] == InvitationStatus::REJECTED) {
        $data['TeamPlayer']['id'] = $existingStatus['TeamPlayer']['id'];
      }
    }
    $data['TeamPlayer']['user_id'] = $inviteUserId;
    $data['TeamPlayer']['team_id'] = $teamId;
    $data['TeamPlayer']['status'] = InvitationStatus::CONFIRMED;

    $dataSource = $this->getDataSource();
    $dataSource->begin();
    if ($this->save($data)) {
    	$deleteFromZooterBucket = $this->User->ZooterBucket->deletePlayerFromZooterBucket($matchId,$inviteUserId);
    	if ($deleteFromZooterBucket['status']== 200) {
	      $responseData['user_id'] = $inviteUserId;
	      $responseData['team_id'] = $teamId;
	      $responseData['status'] =  InvitationStatus::stringvalue(InvitationStatus::CONFIRMED);
	      //$response = array('status' => $requestResponse['status'], 'data' => $responseData, 'message' => 'inviteZooterBasketPlayerToTeam : '.$requestResponse['message']);
	      $response = array('status' => 200, 'data' => $responseData, 'message' => 'inviteZooterBasketPlayerToTeam : success');   		
    	} else {
    		$response = array('status' => $deleteFromZooterBucket['status'], 'message' => 'inviteZooterBasketPlayerToTeam : '.$deleteFromZooterBucket['message']);
    	}
    } else {
      $response = array('status' => 107, 'message' => 'inviteZooterBasketPlayerToTeam : adding player from zooter basket to Team unsuccessfull');
    }

    if ($response['status'] == 200) {
      $dataSource->commit();
    } else {
      $dataSource->rollback();
    }

    return $response;

  }

  public function isUserEligbleForRequest($requestId,$userId) {
    return $this->find('count',array(
      'conditions' => array(
        'TeamPlayer.id' => $requestId,
        'TeamPlayer.user_id' => $userId,
        'TeamPlayer.status' => InvitationStatus::INVITED
      )
    ));
  }
	
}

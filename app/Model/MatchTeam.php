<?php
App::uses('AppModel', 'Model');
App::uses('UserRequest', 'Model');
App::uses('InvitationStatus', 'Lib/Enum');
App::uses('UserRequestType', 'Lib/Enum');
/**
 * MatchTeam Model
 *
 * @property Match $Match
 * @property Team $Team
 */
class MatchTeam extends AppModel {

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
		'team_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Team id is not numeric',
				// 'required' => true,
				// 'allowEmpty' => false,
				// 'on' => 'create',
			),
			// 'teamExist' => array(
			// 	'rule' => array('teamExist'),
			// 	'on' => 'create',
			// )
		),
		'status' => array(
		  'numeric' => array(
		    'rule' => array('numeric'),
		    'message' => 'Status is not valid',
		   //  'required' => true,
			  // 'allowEmpty' => false,
			)
		)
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
		'Team' => array(
			'className' => 'Team',
			'foreignKey' => 'team_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	
public function showMatchTeam($id) {
		$match_team = $this->findById($id);
		if ( ! empty($match_team)) {
			$response = array('status' => 200, 'data' => $match_team);
		} else {
			$response = array('status' => 302, 'message' => 'Match Team Data Could Not Be Retrieved');
		}
		return $response;
	}

	public function deleteMatchTeam($id) {
		$matchTeam = $this->showMatchTeam($id);
		if ($matchTeam['status'] != 200 ){
			$response = array('status' => 905, 'message' => 'Match Team does not exist');
			return $response;
		}
		$this->_updateCache($id);
		$delete = $this->softDelete($id);
		$response = array('status' => 200, 'data'=> $delete , 'message' => 'Deleted');
		return $response;
	}

	public function updateMatchTeams( $matchTeams, $matchId) {
		$match = $this->Match->showMatch($matchId);
		if ($match['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Match does not exist');
			return $response;
		}
		$this->validateId();
		$this->_updateCache(null,$matchTeams,$matchId);
		if ( ! empty($matchTeams)) {
			if ($this->saveMany($matchTeams)) {
				$response = array('status' => 200 , 'data' =>'Match Inning Scores Saved');
			} else {
				$response = array('status' => 314, 'message' => 'Match Team Could not be added or updated');
				pr($this->validationErrors);
			}
		} else {			
			$response = array('status' => 315, 'message' => 'No Data To Update or Add Match Team');			
		}
		return $response ;
	}

	private function _updateCache($id = null,$matchTeams = null, $matchId = null) {
		if ( ! empty($id)) {
			$matchTeam = $this->showMatchTeam($id);
			if ( ! empty($matchTeam['data']['MatchTeam']['match_id'])){
				Cache::delete('show_match_' . $matchTeam['data']['MatchTeam']['match_id']);
				Cache::delete('show_team_' . $matchTeam['data']['MatchTeam']['team_id']);
			}
		}
		if ( ! empty($matchId)) {
			$match = $this->Match->showMatch($matchId);
			Cache::delete('show_match_' . $matchId);
			if ( ! empty($match['data']['MatchTeam'])){
				foreach ($match['data']['MatchTeam'] as $team) {
					Cache::delete('show_team_' . $team['team_id']);
				}
			}
		}
		if ( ! empty($matchTeams)) {
			foreach ($matchTeams as $matchTeam) {
				Cache::delete('show_team_' . $matchTeam['team_id']);
			}
		}
  }

  public function handleMatchTeamRequest($requestId,$userId,$status) {
    if (!empty($requestId) && !empty($userId) && !empty($status)) {
      if ($this->isUserEligbleForRequest($requestId,$userId)) {
        $data = array(
          'MatchTeam' => array(
            'id' => $requestId,
            'status' => $status
          )
        );
        if ($this->save($data)) {
          $response =  array('status' => 200 , 'message' => 'success');
        } 
        else {
          $response =  array('status' => 137, 'message' => 'handleMatchTeamRequest : Match Team Request Accept Could not be updated');
        } 
      }
      else {
        $response =  array('status' => 136, 'message' => 'handleMatchTeamRequest : User Not Eligible to Accept Match Team Request');
      }
    }
    else {
      $response =  array('status' => 135, 'message' => 'handleMatchTeamRequest : Invalid Input Arguments');
    }
    return $response;
  }

  public function getMatchConfirmedTeams($matchId) {
  	$teams = $this->find('all',array(
  		'conditions' => array(
  			'MatchTeam.match_id' => $matchId,
				'MatchTeam.status' => InvitationStatus::CONFIRMED
			),
			'fields' => array('id','team_id'),
			'contain' => array(
				'Team' => array(
					'fields' => array('id','name','nick','image_id'),
					'ProfileImage' => array(
						'fields' => array('id','url')
					)
				)
			)
  	));
  	return $teams;
  }

  public function isTeamConfirmedInMatch($matchId,$teamId) {
    return $this->find('count',array(
      'conditions' => array(
        'MatchTeam.match_id' => $matchId,
        'MatchTeam.team_id' => $teamId,
        'MatchTeam.status' => InvitationStatus::CONFIRMED
      )
    ));
  }

  public function getMatchConfirmedTeamsOwnedByUser($userId,$matchId) {
  	$matchId = trim($matchId);
		$userId = trim($userId);
		if (empty($userId) || empty($matchId)) {
  		return array('status' => 100, 'message' => 'getMatchConfirmedTeamsOwnedByUser : Invalid Input Arguments');
  	}
  	if (!$this->_userExists($userId)) {
			return array('status' => 103 , 'message' => 'getMatchConfirmedTeamsOwnedByUser : Invalid User or invite_user ID');
		}
		if (!$this->Match->checkIfMatchExists($matchId)) {
  		return array('status' => 103 , 'message' => 'getMatchConfirmedTeamsOwnedByUser : Invalid Match ID');
  	}
  	$teams = $this->find('all',array(
  		'conditions' => array(
  			'MatchTeam.match_id' => $matchId,
				'MatchTeam.status' => InvitationStatus::CONFIRMED,
				'Team.owner_id' => $userId
			),
			'fields' => array('id','team_id','status'),
			'contain' => array(
				'Team' => array(
					'fields' => array('id','name','nick','image_id','owner_id'),
					'ProfileImage' => array(
						'fields' => array('id','url')
					)
				)
			)
  	));
  	$response = array();
  	foreach ($teams as $key => $team) {
  		$response[$key]['id'] = $team['MatchTeam']['team_id'];
  		$response[$key]['name'] = !empty($team['Team']) ? $team['Team']['name'] : null;
  		$response[$key]['nick'] = !empty($team['Team']) ? $team['Team']['nick'] : null;
  		$response[$key]['image'] = !empty($team['Team']['ProfileImage']) ? $team['Team']['ProfileImage']['url'] : null;
  		$response[$key]['status'] = InvitationStatus::stringvalue(InvitationStatus::CONFIRMED);
  	}
  	return array('status' => 200, 'data' => array('teams' => $response));
  }

  public function fetchInvitedTeamsForMatch($matchId) {
  	$invitedTeams = $this->find('all',array(
  		'conditions' => array(
  			'MatchTeam.match_id' => $matchId
  		),
  		'fields' => array('id','team_id','status'),
			'contain' => array(
				'Team' => array(
					'fields' => array('id','name','nick','image_id'),
					'ProfileImage' => array(
						'fields' => array('id','url')
					)
				)
			) 
  	));
  	$response = array();
  	$index = 0;
  	foreach ($invitedTeams as $key => $team) {
  		if ( (!empty($team['MatchTeam']['status'])) && (!empty($team['Team'])) ) {
  			$response[$index]['id'] = $team['MatchTeam']['team_id'];
	  		$response[$index]['name'] = $team['Team']['name'];
	  		$response[$index]['status'] = InvitationStatus::stringValue($team['MatchTeam']['status']);

	  		if (!empty($team['Team']['ProfileImage'])) {
	  			$response[$index]['image'] = $team['Team']['ProfileImage']['url'];
	  		} else {
	  			$response[$index]['image'] = null;
	  		}
	  		$index++;
	  	} 		
  	}
  	return $response;
  }

  public function inviteTeamInMatch($userId,$matchId,$teamId) {
  	$matchId = trim($matchId);
		$userId = trim($userId);
		$teamId = trim($teamId);
		if (empty($userId) || empty($matchId) || empty($teamId)) {
  		return array('status' => 100, 'message' => 'inviteTeamInMatch : Invalid Input Arguments');
  	}
  	if (!$this->_userExists($userId)) {
			return array('status' => 103 , 'message' => 'inviteTeamInMatch : Invalid User or invite_user ID');
		}
		if (!$this->Match->checkIfMatchExists($matchId)) {
  		return array('status' => 103 , 'message' => 'inviteTeamInMatch : Invalid Match ID');
  	}
  	if (!$this->_teamExists($teamId)) {
  		return array('status' => 103 , 'message' => 'inviteTeamInMatch : Invalid Team ID');
  	}
  	if (!$this->Match->MatchPrivilege->isUserMatchAdmin($matchId,$userId)) {
  		return array('status' => 103 , 'message' => 'inviteTeamInMatch : User is not Match Admin');
  	}

  	$teamOwnerId = $this->Team->findById($teamId);
  	if (!empty($teamOwnerId['Team']['owner_id'])) {
  		$teamOwnerId = $teamOwnerId['Team']['owner_id'];
  	} else {
  		return array('status' => 104, 'message' => 'inviteTeamInMatch : No Owner exists for Team');
  	}

  	$data = array();
  	$existingStatus = $this->findByMatchIdAndTeamId($matchId,$teamId);
  	if (!empty($existingStatus['MatchTeam'])) {
	  	if ($existingStatus['MatchTeam']['status'] == InvitationStatus::REJECTED || $existingStatus['MatchTeam']['status'] == InvitationStatus::UNBLOCKED) {
	  		$data['MatchTeam']['id'] = $existingStatus['MatchTeam']['id'];
	  	} else {
	  		$response['team_id'] = $teamId;
	  		$response['status'] = InvitationStatus::stringvalue($existingStatus['MatchTeam']['status']);
	  		return array('status' => 200, 'data' => $response);
	  	}
  	}

  	$data['MatchTeam']['match_id'] = $matchId;
  	$data['MatchTeam']['team_id'] = $teamId;
  	$data['MatchTeam']['status'] = InvitationStatus::INVITED;

  	$dataSource = $this->getDataSource();
    $dataSource->begin();
  	if ($this->save($data)) {
  		$inserTedid = $this->getLastInsertID();
  		$inserTedid = !empty($inserTedid) ? $inserTedid : $data['MatchTeam']['id'];
  		$UserRequest = new UserRequest();
  		$requestResponse = $UserRequest->createOrUpdateUserRequest(
  			UserRequestType::MATCH_TEAM_ADD_INVITE,
  			$inserTedid,
  			$teamOwnerId,
  			true,
  			$userId
  		);
  		$responseData['team_id'] = $teamId;
  		$responseData['status'] = InvitationStatus::stringvalue(InvitationStatus::INVITED);
  		$response = array('status' => $requestResponse['status'], 'data' => $responseData, 'message' => 'inviteTeamInMatch : '.$requestResponse['message']);
  	} else {
  		pr($this->validationErrors);
  		$response = array('status' => 107, 'message' => 'inviteTeamInMatch : match team invite unsuccessfull');
  	}

  	if ($response['status'] == 200) {
      $dataSource->commit();
    } else {
    	$dataSource->rollback();
    }
    return $response;
  }

  public function requestMatchTeamJoin($userId,$matchId,$teamId) {
  	$matchId = trim($matchId);
		$userId = trim($userId);
		$teamId = trim($teamId);
		if (empty($userId) || empty($matchId) || empty($teamId)) {
  		return array('status' => 100, 'message' => 'requestMatchTeamJoin : Invalid Input Arguments');
  	}
  	if (!$this->_userExists($userId)) {
			return array('status' => 103 , 'message' => 'requestMatchTeamJoin : Invalid User or invite_user ID');
		}
		if (!$this->Match->checkIfMatchExists($matchId)) {
  		return array('status' => 103 , 'message' => 'requestMatchTeamJoin : Invalid Match ID');
  	}
  	if (!$this->_teamExists($teamId)) {
  		return array('status' => 103 , 'message' => 'requestMatchTeamJoin : Invalid Team ID');
  	}
  	if (!$this->Team->TeamPrivilege->isUserTeamAdmin($teamId,$userId)) {
  		return array('status' => 104, 'message' => 'requestMatchTeamJoin : Only Team Admins are allowed to request');
  	}

  	$matchOwnerId = $this->Match->getMatchOwnerId($matchId);
  	if ($matchOwnerId['status'] == 200) {
  		$matchOwnerId = $matchOwnerId['data'];
  	} else {
  		return array('status' => $matchOwnerId['status'], 'message' => 'inviteTeamInMatch : '.$matchOwnerId['message']);
  	}

  	$data = array();
  	$existingStatus = $this->findByMatchIdAndTeamId($matchId,$teamId);
  	if (!empty($existingStatus['MatchTeam'])) {
	  	if ($existingStatus['MatchTeam']['status'] == InvitationStatus::REJECTED || $existingStatus['MatchTeam']['status'] == InvitationStatus::UNBLOCKED) {
	  		$data['MatchTeam']['id'] = $existingStatus['MatchTeam']['id'];
	  	} else {
	  		$response['team_id'] = $teamId;
	  		$response['status'] = InvitationStatus::stringvalue($existingStatus['MatchTeam']['status']);
	  		return array('status' => 200, 'data' => $response);
	  	}
  	}

  	$data['MatchTeam']['match_id'] = $matchId;
  	$data['MatchTeam']['team_id'] = $teamId;
  	$data['MatchTeam']['status'] = InvitationStatus::REQUEST_PENDING;

  	$dataSource = $this->getDataSource();
    $dataSource->begin();
  	if ($this->save($data)) {
  		$inserTedid = $this->getLastInsertID();
  		$inserTedid = !empty($inserTedid) ? $inserTedid : $data['MatchTeam']['id'];
  		$UserRequest = new UserRequest();
  		$requestResponse = $UserRequest->createOrUpdateUserRequest(
  			UserRequestType::MATCH_TEAM_JOIN_REQUEST,
  			$inserTedid,
  			$matchOwnerId,
  			true,
  			$userId
  		);
  		$responseData['team_id'] = $teamId;
  		$responseData['status'] = InvitationStatus::stringvalue(InvitationStatus::REQUEST_PENDING);
  		$response = array('status' => $requestResponse['status'], 'data' => $responseData, 'message' => 'requestMatchTeamJoin : '.$requestResponse['message']);
  	} else {
  		pr($this->validationErrors);
  		$response = array('status' => 107, 'message' => 'requestMatchTeamJoin : match team join request unsuccessfull');
  	}

  	if ($response['status'] == 200) {
      $dataSource->commit();
    } else {
    	$dataSource->rollback();
    }
    return $response;
  }

  public  function getMatchTeams($matchId) {
  	return $this->find('all',array(
  		'conditions' => array(
  			'MatchTeam.match_id' => $matchId
  		),
  		'fields' => array('id','team_id','status')
  	));
  }

  public function isUserEligbleForRequest($requestId,$userId) {
    return $this->find('count',array(
      'conditions' => array(
      	'MatchTeam.id' => $requestId,
      	'MatchTeam.status' => InvitationStatus::INVITED,
        'Team.id = MatchTeam.team_id',
        'Team.owner_id' => $userId,
      ),
      'contain' => array('Team')
    ));
  }

}

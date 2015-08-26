<?php
App::uses('AppModel', 'Model');
App::uses('InvitationStatus', 'Lib/Enum');
App::uses('PlayerRole', 'Lib/Enum');
/**
 * MatchPlayer Model
 *
 * @property Match $Match
 * @property User $User
 */
class MatchPlayer extends AppModel {

	public $validate = array(
		'match_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Match id is not numeric',
				'required' => true,
				'allowEmpty' => false,
				'on' => 'create',
			),
			'matchExist' => array(
				'rule' => array('matchExist'),
				'on' => 'create',
			)
		),
		'team_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Team id is not numeric',
				'required' => true,
				'allowEmpty' => false,
				'on' => 'create',
			),
			'teamExist' => array(
				'rule' => array('teamExist'),
				'on' => 'create',
			)
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'User id is not numeric',
				'required' => true,
				'allowEmpty' => false,
				'on' => 'create',
			),
			'userExist' => array(
				'rule' => array('userExist'),
				'on' => 'create',
			)
		),
		'status' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Status is not valid',
				'required' => true,
				'allowEmpty' => false
			)
		),
		'role' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Status is not valid',
				'on' => 'create',
				'required' => true,
				'allowEmpty' => false
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
		'Match' => array(
			'className' => 'Match',
			'foreignKey' => 'match_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Team'=> array(
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

	//public $virtualFields = array('Count' => 'COUNT(*)');

  public function checkPlayerRole($matchId,$playerId,$role) {
    return $this->find('count',array(
      'conditions' => array(
        'match_id' => $matchId,
        'user_id' => $playerId,
        'role' => $role
      )
    ));
  }

  public function isPlayerCaptain($matchId,$playerId) {
    return $this->find('count',array(
      'conditions' => array(
        'match_id' => $matchId,
        'user_id' => $playerId,
        'is_captain' => true
      )
    ));
  }

  public function getPlayerRoleInMatch($matchId,$playerId) {
    $matchPlayer = $this->findByMatchIdAndUserId($matchId,$playerId);
    $matchPlayer = $matchPlayer['MatchPlayer'];
    return $matchPlayer['role'];
  }

  public function getDidNotBatPlayers($matchId,$teamId,$excludeList) {
    $didNotBatPlayers = array();
    $options = array(
      'conditions' => array(
        'MatchPlayer.match_id' => $matchId,
        'MatchPlayer.team_id' => $teamId,
        'MatchPlayer.role !=' => PlayerRole::TWELFTH_MAN
      ),
      'contain' => array(
        'User' => array(
          'fields' => array('id'),
          'Profile' => array(
            'fields' => array('id','user_id','first_name','middle_name','last_name','image_id'),
            'ProfileImage' => array(
              'fields' => array('id','url')
            )
          ),
          'PlayerStatistic' => array(
            'fields' => array('total_runs','total_matches','total_wickets_taken','total_fours_hit','total_sixes_hit','total_balls_faced','total_overs_bowled','total_runs_conceded')
          )
        )
      )
    );
    if (!empty($excludeList)) {
      $options['conditions']['MatchPlayer.user_id !='] = $excludeList;
    }
    $matchPlayers = $this->find('all',$options);
    foreach ($matchPlayers as $key => $player) {
      $didNotBatPlayers[$key]['id'] = $player['User']['id'];
      
      if (!empty($player['User']['Profile'])) {
        $didNotBatPlayers[$key]['name'] = $this->_prepareUserName($player['User']['Profile']['first_name'],$player['User']['Profile']['middle_name'],$player['User']['Profile']['last_name']);
      } else {
        $didNotBatPlayers[$key]['name'] = null;
      } 

      if (!empty($player['MatchPlayer']['role'])) {
        $didNotBatPlayers[$key]['role'] = PlayerRole::stringValue($player['MatchPlayer']['role']);
      } else {
        $didNotBatPlayers[$key]['role'] = null;
      }

      $didNotBatPlayers[$key]['first_name'] = $player['User']['Profile']['first_name'];
      $didNotBatPlayers[$key]['middle_name'] = $player['User']['Profile']['middle_name'];
      $didNotBatPlayers[$key]['last_name'] = $player['User']['Profile']['last_name'];
      $didNotBatPlayers[$key]['image'] = !empty($player['User']['Profile']['ProfileImage']['url']) ? $player['User']['Profile']['ProfileImage']['url'] : null;
      $didNotBatPlayers[$key]['matches'] = !empty($player['User']['PlayerStatistic']['total_matches']) ? $player['User']['PlayerStatistic']['total_matches'] : 0;
      $didNotBatPlayers[$key]['wickets'] = !empty($player['User']['PlayerStatistic']['total_wickets_taken']) ? $player['User']['PlayerStatistic']['total_wickets_taken'] : 0;
      $didNotBatPlayers[$key]['fours'] = !empty($player['User']['PlayerStatistic']['total_fours_hit']) ? $player['User']['PlayerStatistic']['total_fours_hit'] : 0;
      $didNotBatPlayers[$key]['sixes'] = !empty($player['User']['PlayerStatistic']['total_sixes_hit']) ? $player['User']['PlayerStatistic']['total_sixes_hit'] : 0;
      $runs = !empty($player['User']['PlayerStatistic']['total_runs']) ? $player['PlayerStatistic']['total_runs'] : 0;
      $ballsFaced = !empty($player['User']['PlayerStatistic']['total_balls_faced']) ? $player['PlayerStatistic']['total_balls_faced'] : 0;
      $didNotBatPlayers[$key]['strike_rate'] = $this->Match->MatchInningScorecard->prepareBatsmanStrikeRate($runs,$ballsFaced);
      $didNotBatPlayers[$key]['runs'] = $runs;
      $runsConceded = !empty($player['User']['PlayerStatistic']['total_runs_conceded']) ? $player['User']['PlayerStatistic']['total_runs_conceded'] : 0;
      $oversBowled = !empty($player['User']['PlayerStatistic']['total_overs_bowled']) ? $player['User']['PlayerStatistic']['total_overs_bowled'] : 0;
      $didNotBatPlayers[$key]['economy'] = $this->Match->MatchInningScorecard->getBowlingEconomy($runsConceded,$oversBowled);
    }
    return $didNotBatPlayers;
  }

	public function fetchMatchPlayers($matchId) {
		if (!$this->Match->checkIfMatchExists($matchId)) {
  		return array('status' => 105 , 'message' => 'fetchMatchPlayers : Match Id does not exist');
  	}
  	$response = array();

  	$matchTeams = $this->Match->fetchTeamsOfMatch($matchId);
  	if ($matchTeams['status'] == 200) {
  		$matchTeams = $matchTeams['data'];
  	} else {
  		return array('status' => $matchTeams['status'], 'message' => 'fetchMatchPlayers : '.$matchTeams['message']);
  	}

  	$response['team_one'] = $matchTeams['teamOne'];
  	$response['team_two'] = $matchTeams['teamTwo'];
  	$matchPlayers = $this->find('all',array(
  		'conditions' => array(
        'MatchPlayer.match_id' => $matchId,
        'OR' => array(
          array('MatchPlayer.team_id' => $response['team_one']['id']),
          array('MatchPlayer.team_id' => $response['team_two']['id'])
        ),
  			'MatchPlayer.status' => InvitationStatus::CONFIRMED
  		),
  		'fields' => array('user_id','role','team_id'),
  		'contain' => array(
  			'User' => array(
  				'fields' => array('id'),
  				'Profile' => array(
  					'fields' => array('user_id','first_name','middle_name','last_name')
  				)
  			)
  		)
  	));
  	$teamOneIndex = 0;
  	$teamTwoIndex = 0;
  	foreach ($matchPlayers as $key => $matchPlayer) {
  		if (!empty($matchPlayer['User']['Profile'])) {
  			$player['id'] = $matchPlayer['User']['Profile']['user_id'];
  			$player['name'] = $this->_prepareUserName($matchPlayer['User']['Profile']['first_name'],$matchPlayer['User']['Profile']['middle_name'],$matchPlayer['User']['Profile']['last_name']);
  		} else {
   			$player['id'] = null;
   			$player['name'] = null;
   		}
   		if (!empty($matchPlayer['MatchPlayer']['role'])) {
   			$player['role'] = PlayerRole::stringValue($matchPlayer['MatchPlayer']['role']);
   		} else {
   			$player['role'] = null;
   		}

   		if ($matchPlayer['MatchPlayer']['team_id'] == $response['team_one']['id']) {
   			$response['team_one']['team_players'][$teamOneIndex] = $player;
   			$teamOneIndex++;
   		} elseif ($matchPlayer['MatchPlayer']['team_id'] == $response['team_two']['id']) {
   			$response['team_two']['team_players'][$teamTwoIndex] = $player;
   			$teamTwoIndex++;
   		}  		
  	}
  	return array('status' => 200, 'data' => $response);
	}

  public function fetchMatchZooterBasket($matchId) {
    if (!$this->Match->checkIfMatchExists($matchId)) {
      return array('status' => 105 , 'message' => 'fetchMatchZooterBasket : Match Id does not exist');
    }
    $response = array();
    return array('status' => 200 , 'data' => $response);
  }

  public function getMatchPlayersAll($matchId,$teamIds) {
    return $this->find('all',array(
      'conditions' => array(
        'MatchPlayer.match_id' => $matchId,
        'MatchPlayer.team_id' => $teamIds
      ),
      'fields' => array('user_id','role','team_id','status'),
      'contain' => array(
        'User' => array(
          'fields' => array('id'),
          'Profile' => array(
            'fields' => array('user_id','first_name','middle_name','last_name','image_id'),
            'ProfileImage' => array(
              'fields' => array('id','url')
            )
          ),
          'PlayerStatistic' => array(
            'fields' => array('total_runs','total_matches','total_wickets_taken','total_fours_hit','total_sixes_hit')
          )
        )
      )
    ));
  }

	public function showMatchPlayer($id) {
		$match_player = $this->findById($id);
		if ( ! empty($match_player)) {
			$response = array('status' => 200, 'data' => $match_player);
		} else {
			$response = array('status' => 302, 'message' => 'Match Player Data Could Not Be Retrieved');
		}
		return $response;
	}

	public function deleteMatchPlayer($id) {
		$matchPlayer = $this->showMatchPlayer($id);
		if ( $matchPlayer['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Match Player does not exist');
			return $response;
		}
		$this->_updateCache($id,null);
		$delete = $this->softDelete($id);
		$response = array('status' => 200, 'data'=> $delete , 'message' => 'Deleted');
		return $response;
	}


	public function updateMatchPlayers($matchPlayers , $matchId) {
		$this->_updateCache(null, $matchPlayers,$matchId);
		if ( ! empty($matchPlayers)) {
			if ($this->saveMany($matchPlayers)) {
				$response = array('status' => 200 , 'message' => ' Match Players Updated');
			} else {
				$response = array('status' => 312, 'message' => 'Match Players Could not be added or updated');
				pr($this->validationErrors);
			}
		} else {			
			$response = array('status' => 313, 'message' => 'No Data To Update or Add Match Players and Roles');			
		}
		return $response ;
	}

	private function _updateCache($id = null,$matchPlayers = null, $matchId = null) {
		$match = $this->Match->showMatch($matchId);
		if ($match['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Match does not exist');
			return $response;
		}
		$this->validateId();
		if ( ! empty($id)) {
			$matchPlayer = $this->showMatchPlayer($id);
			if ( ! empty($matchPlayer['data']['MatchPlayer']['match_id'])) {
				Cache::delete('show_match_' . $matchPlayer['data']['MatchPlayer']['match_id']);
				//Cache::delete('show_user_'.$matchPlayer['data']['MatchPlayer']['user_id']);  *** use when show_user_1 cache exists ***
			}
		}
		if ( ! empty($matchId)){
			//  $match = $this->Match->showMatch($matchId); 							*** use when show_user_1 cache exists ***
			Cache::delete('show_match_' . $matchId);
			//  foreach ($match['data']['MatchPlayer'] as $matchplayer) {				*** use when show_user_1 cache exists ***
			//  Cache::delete('show_user_'.$matchplayer['user_id']);							*** use when show_user_1 cache exists ***
			//  }
		}
		//  if(!empty($matchPlayers)){
		//	foreach ($matchPlayers as $matchPlayer) {
		//		Cache::delete('show_user_'.$matchPlayer['user_id']);
		//	}
		// }
  }

	public function getMatchTeamPlayerRequest($id) {
		$request_name = array();
  	if (!empty($id)) {
      $data = $this->find('first' ,array(
      	'conditions' => array('MatchPlayer.id' => $id),
        'fields' => array('match_id','team_id','role'),
        'contain' => array(
        	'Team' => array(
        		'fields' => array('id','name','image_id'),
        		'ProfileImage' => array('id','url')
        	),
      	  'Match' => array(
      	  	'fields' => array('id','start_date_time','name')
      	  )
        )
      ));
      if (!empty($data)) {
        $team = array(
          'id' => $data['MatchPlayer']['team_id'],
          'name' => $data['Team']['name']
        );
        if (!empty($data['Team']['ProfileImage'])) {
        	$team['image'] = $data['Team']['ProfileImage']['url'];
        } else $team['image'] = null;

        $request_name['team'] = $team;
        $request_name['match']['id'] = $data['Match']['id'];
        $request_name['match']['start_date_time'] = $data['Match']['start_date_time'];
        $request_name['match']['name'] = $data['Match']['name'];
      }
  	}
  	return $request_name;
	}

	public function handleMatchTeamPlayerRequest($requestId,$userId,$status) {
    if (!empty($requestId) && !empty($userId) && !empty($status)) {
      if ($this->isUserEligbleForRequest($requestId,$userId)) {
        $data = array(
          'MatchPlayer' => array(
            'id' => $requestId,
            'status' => $status
          )
        );
        if ($this->save($data)) {
          $response =  array('status' => 200 , 'message' => 'success');
        } 
        else {
          $response =  array('status' => 122, 'message' => 'handleMatchTeamPlayerRequest : Match Team Player Request Accept Could not be updated');
        } 
      }
      else {
        $response =  array('status' => 121, 'message' => 'handleMatchTeamPlayerRequest : User Not Eligible to Accept Match Team Player Request');
      }
    }
    else {
      $response =  array('status' => 120, 'message' => 'handleMatchTeamPlayerRequest : Invalid Input Arguments');
    }
    return $response;
  }

  public function getUserPlayedMatches($userId, $is_best, $numOfRecords) {
  	$options = array(
  		'conditions' => array(
  			'Match.is_match_data_complete' => true,
  			'Match.is_public' => true,
  			'MatchPlayer.user_id' => $userId,
	  		'MatchPlayer.status' => InvitationStatus::CONFIRMED
  		),
  		'fields' => array('id','match_id','team_id','is_selected_best','match_contribution'),
  		'contain' => array(
  			'Match' => array(
  				'fields' => array('id','is_public','is_match_data_complete','start_date_time'),
					'MatchResult' => array(
						'fields' => array('id'),
						'conditions' => array(
							'MatchResult.result_type !=' => MatchResultType::ABANDONED,
		  				'MatchResult.result_type !=' => MatchResultType::CANCELLED
						)
					)
				),
			),
  		'limit' => $numOfRecords
  	);
  	if ($is_best) {
			$options['conditions']['MatchPlayer.is_selected_best'] = true;
			$options['order'] = 'MatchPlayer.match_contribution DESC';
  	} else {
  			$options['order'] = 'Match.start_date_time DESC';
  	}

  	$matches = $this->find('all',$options);

  	if ($is_best) {
  		$countBestSelected = Count($matches);
  		if ($countBestSelected < $numOfRecords) {
  			$options['conditions']['MatchPlayer.is_selected_best'] = false;
  			$options['limit'] = $numOfRecords - $countBestSelected;
  			$otherMatches = $this->find('all',$options);
  			foreach ($otherMatches as $otherMatch) {
  				array_push($matches, $otherMatch);
  			} 			
  		}
  	}

  	$matchIdsList = array();
  	$userTeams = array();
  	$matchContributions = array();
  	foreach ($matches as $match) {
  		if (!empty($match['Match']['MatchResult'])) {
	  		array_push($matchIdsList,$match['Match']['id']);
	  		$userTeams[$match['Match']['id']] = $match['MatchPlayer']['team_id'];
	  		$matchContributions[$match['Match']['id']] = $match['MatchPlayer']['match_contribution'];
	  	}
  	}
  	return array('match_id_list' => $matchIdsList, 'user_teams' => $userTeams,
  	 							'match_contributions' => $matchContributions);
  }

  public function getCountOfUserPlayedMatches($userId) {
  	$data = $this->find('all',array(
  		'conditions' => array(
  			'Match.is_match_data_complete' => true,
  			'Match.is_public' => true,
  			'MatchPlayer.user_id' => $userId,
	  		'MatchPlayer.status' => InvitationStatus::CONFIRMED
  		),
  		'fields' => array('id','match_id'),
  		'contain' => array(
  			'Match' => array(
  				'fields' => array('id','is_public','is_match_data_complete'),
					'MatchResult' => array(
						'fields' => array('id'),
						'conditions' => array(
							'MatchResult.result_type !=' => MatchResultType::ABANDONED,
		  				'MatchResult.result_type !=' => MatchResultType::CANCELLED
						)
					)
				)
  		)
  	));
  	$count = 0;
  	foreach ($data as $value) {
  		if (!empty($value['Match']['MatchResult'])) {
  			$count = $count + 1;
  		}
  	}
  	return $count;
  }

  public function getCountOfMatchesUserPlayedForTeam($userId, $teamId) {
  	$data = $this->find('all',array(
  		'conditions' => array(
  			'Match.is_match_data_complete' => true,
  			'Match.is_public' => true,
  			'MatchPlayer.user_id' => $userId,
  			'MatchPlayer.team_id' => $teamId,
	  		'MatchPlayer.status' => InvitationStatus::CONFIRMED
  		),
  		'fields' => array('id','match_id'),
  		'contain' => array(
  			'Match' => array(
  				'fields' => array('id','is_public','is_match_data_complete'),
					'MatchResult' => array(
						'fields' => array('id'),
						'conditions' => array(
							'MatchResult.result_type !=' => MatchResultType::ABANDONED,
		  				'MatchResult.result_type !=' => MatchResultType::CANCELLED
						)
					)
				)
  		)
  	));
  	$count = 0;
  	foreach ($data as $value) {
  		if (!empty($value['Match']['MatchResult'])) {
  			$count = $count + 1;
  		}
  	}
  	return $count;
  }

  public function getUserTeamInMatch($userId,$matchId) {
  	return $this->find('first',array(
  		'conditions' => array(
  			'user_id' => $userId,
  			'match_id' => $matchId,
  			'status' => InvitationStatus::CONFIRMED
  		),
  		'fields' => array('team_id')
  	));
  }

  public function isUserEligbleForRequest($requestId,$userId) {
    return $this->find('count',array(
      'conditions' => array(
        'MatchPlayer.id' => $requestId,
        'MatchPlayer.user_id' => $userId,
        'MatchPlayer.status' => InvitationStatus::INVITED
      )
    ));
  }

}

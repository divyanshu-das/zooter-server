<?php
App::uses('AppModel', 'Model');
/**
 * MatchFollower Model
 *
 * @property Match $Match
 * @property User $User
 */
class MatchFollower extends AppModel {

	public $validate = array(
		'match_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Match id is not numeric',
				'required' => true,
				'allowEmpty' => false,
				'on' => 'create'
			),
			'matchExist' => array(
				'rule' => array('matchExist'),
				'on' => 'create',
			)
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'User id is not numeric',
				'required' => true,
				'allowEmpty' => false,
				'on' => 'create'
			),
			'userExist' => array(
				'rule' => array('userExist'),
				'on' => 'create'
			)
		),
		'is_playing' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'is_playing is not valid',
				'on' => 'create'
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
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	//public $virtualFields = array('common_followers_count' => 'COUNT(MatchFollower.match_id)');

	public function getRecommendedMatches($userId, $numOfRecords, $excludeMatchIdList) {
		$numOfRecords = trim($numOfRecords);
		$userId = trim($userId);
		if (empty($userId)) {
			return array('status' => 100, 'message' => 'Invalid Input Arguments');
		}
		if (empty($numOfRecords)) {
			$numOfRecords = Limit::NUM_OF_RECOMMENDED_MATCHES;
		}
		$recommendedMatchesData = array();
		$friendsId = $this->User->FriendFrom->getUserFriendsIdList($userId);
		$data = $this->find('all' , array(
			// 'fields' => array('match_id','common_followers_count'),
			// 'group' => array('MatchFollower.match_id HAVING COUNT(*) >= 0'),
			'conditions' => array(
        'user_id' => $friendsId,
        'MatchFollower.match_id !=' => $excludeMatchIdList,
        'Match.start_date_time >' => date('Y-m-d H:i:s'),
        'Match.is_public' => true,  
			),
			'contain' => array(
				'Match' => array(
					'fields' => array('id','start_date_time','name','location_id','is_cricket_ball_used','players_per_side'),
          'Location' => array(
            'fields' => array('id' ,'name','city_id'),
            'City' => array(
              'fields' => array('id','name')
            )
          ),
          'MatchTeam' => array(
          	'fields' => array('team_id'),
            'conditions' => array(
            	'MatchTeam.status' => MatchTeamStatus::CONFIRMED
            ),
           'Team' => array(
           	'fields' => array('id','name','image_id'),
           	'ProfileImage' => array('id','url')
           )
          ),
          'MatchPlayer' => array(
          	'fields' => array('id','team_id','status')
          )
      	)        
			),
			'order' => 'Match.start_date_time DESC',
			'limit' => $numOfRecords
		));
		if (!empty($data)) {
      $recommendedMatchesData = $this->__prepareRecommendedMatchesData($userId,$data);
    }
		return $recommendedMatchesData;
	}

	private function __prepareRecommendedMatchesData($userId,$recommendedMatches) {
		$matchData = array();
		$index = 0;
    foreach($recommendedMatches as $data){
    	if (!$this->isUserFollowingTheMatch($userId,$data['MatchFollower']['match_id'])) {
				$teams = $this->Match->getMatchTeamsArrayForMatches(
	        $data['Match']['MatchTeam'],
	        $data['Match']['players_per_side'],
	        $data['Match']['MatchPlayer'],MatchType::RECOMMENDED
	      );
				$matchData[$index]['id'] = $data['MatchFollower']['match_id'];
				$matchData[$index]['start_date_time'] = $data['Match']['start_date_time'];
				$matchData[$index]['name'] = $data['Match']['name'];
				$matchData[$index]['is_cricket_ball_used'] = $data['Match']['is_cricket_ball_used'];
				//$matchData[$index]['common_followers_count'] = $data['MatchFollower']['common_followers_count'];
				$matchData[$index]['teams'] = $teams;
				if (!empty($data['Match']['Location']['City'])) {
          $matchData[$index]['location']['id'] = $data['Match']['Location']['City']['id'];
          $matchData[$index]['location']['name'] = $data['Match']['Location']['City']['name'];
        } else {
            $matchData[$index]['location']['id'] = null;
            $matchData[$index]['location']['name'] = null;
        }
				$index = $index +1;
    	}
		}
		return $matchData;
	}

	public function followMatch($userId,$matchId) {
		$userId = trim($userId);
		$matchId = trim($matchId);
    if (empty($userId) || empty($matchId)) {
    	return array('status' => 100, 'message' => 'Follow Match : Invalid Input Arguments');
    }
    if ($this->isUserFollowingTheMatch($userId,$matchId)) {
    	return array('status' => 324, 'message' => 'User is Already Following the Match');
    }
    if ($this->User->userExists($userId)) {
    	$isPublic = $this->Match->isMatchPublic($matchId);
      if ($isPublic['status'] == 200) {
      	if ($isPublic['data']['is_public'] == true) {
      		$matchFollowerData = array(
	        	'MatchFollower' => array(
	        		'match_id' => $matchId,
	        		'user_id' => $userId
	        	)
	        );
          $dataSource = $this->getDataSource();
          $dataSource->begin();
	        if ($this->save($matchFollowerData)) {
            $deleteRecommendation = $this->Match->MatchRecommendation->deleteMatchRecommendation($matchId, $userId);
            if ($deleteRecommendation['status'] != 200) {
              $response = array('status' => $deleteRecommendation['status'], 'message' => $deleteRecommendation['message']);
            } else {
                $responseData = $this->__prepareResponseForFollowMatch($this->getLastInsertID());
                $response = array('status' => 200 , 'message' => 'success','data' => $responseData);
            }
					} else {
						  $response = array('status' => 321, 'message' => 'folow Match Could not be Set');
					}	
      	} else {
      		  $response = array('status' => 325, 'message' => 'Match is not Public');
      	}
      } else {
      	  $response = array('status' => $isPublic['status'], 'message' => $isPublic['message']);
      }
    } else {
    	  $response = array('status' => 912, 'message' => 'User Does Not Exist');
    }
    if ($response['status'] == 200) {
      $dataSource->commit();
    } else $dataSource->rollback();
    
    return $response ;
	}

	private function __prepareResponseForFollowMatch($id) {
		$data = $this->findById($id);
		$responseData['id'] = $data['MatchFollower']['id'];
		$responseData['user_id'] = $data['MatchFollower']['user_id'];
		$responseData['match_id'] = $data['MatchFollower']['match_id'];
		return $responseData;
	}

	public function unfollowMatch($userId,$matchId) {
		$userId = trim($userId);
		$matchId = trim($matchId);
    if (empty($userId) || empty($matchId)) {
    	return array('status' => 100, 'message' => 'unFollow Match : Invalid Input Arguments');
    }
    if (!$this->isUserFollowingTheMatch($userId,$matchId)) {
    	return array('status' => 324, 'message' => 'User is Not Following the Match');
    }
    $rowIdOfUpdate = $this->find('first',array(
    	'conditions' => array(
    		'user_id' => $userId,
    		'match_id' => $matchId
    	),
    	'fields' => array('id')
    ));
    if ($this->User->userExists($userId)) {
  		$matchFollowerData = array(
      	'MatchFollower' => array(
      		'id' => $rowIdOfUpdate['MatchFollower']['id'],
      		'deleted' => 1,
      		'deleted_date' => date('Y-m-d H:i:s')
      	)
      );
      if ($this->save($matchFollowerData)) {
      	$response = array('id' => $rowIdOfUpdate['MatchFollower']['id'], 'user_id' => $userId, 'match_id' => $matchId);
				$response = array('status' => 200 , 'message' => 'success', 'data' => $response);
			} else {
				  $response = array('status' => 321, 'message' => 'unFollow Match Could not be Set');
			}	
    } else {
    	  $response = array('status' => 912, 'message' => 'User Does Not Exist');
    }
    return $response ;
	}

	public function getCountOfFriendsFollowingTheMatch($match_id,$userFriendList) {
		if (empty($match_id) || empty($userFriendList)) {
    	return array('status' => 100, 'message' => 'getCountOfFriendsFollowingTheMatch : Invalid Input Arguments');
    }
    $data = $this->find('count' , array(
			'conditions' => array(
        'user_id' => $userFriendList,
        'match_id' => $match_id
      )
		));

		return array('status' => 200 , 'data' => $data);
	}

	public function isUserFollowingTheMatch($userId,$matchId) {
		return $this->find('count',array(
    	'conditions' => array(
    		'MatchFollower.user_id' => $userId,
    		'MatchFollower.match_id' => $matchId
    	)
    ));
	}

	public function findCommonFollowers($userId,$matchId) {
		$userId = trim($userId);
		$matchId = trim($matchId);
		if (empty($userId) || empty($matchId)) {
			return array('status' => 100, 'message' => 'findCommonFollowers : Invalid Input Arguments');
		}
		$friendsId = $this->User->FriendFrom->getUserFriendsIdList($userId);
		$data = $this->find('all',array(
			'fields' => array('common_followers_count'),
			'group' => array('MatchFollower.match_id HAVING COUNT(*) >= 0'),
			'conditions' => array(
        'user_id' => $friendsId,
        'match_id' => $matchId
      )
    ));
    if (!empty($data)) {
      $commonFollowers = $data[0]['MatchFollower']['common_followers_count'];
    } else $commonFollowers = 0;
    return array('status' => 200, 'data' => $commonFollowers);
	}

	public function fetchMatchesFollowedByUser($userId,$numOfRecords) {
  	$userId = trim($userId);
  	$numOfRecords = trim($numOfRecords);
  	if (empty($userId)) {
  		return array('status' => 100, 'message' => 'fetchMatchesFollowedByUser : Invalid Input Arguments');
  	}
  	if (empty($numOfRecords)) {
  		$numOfRecords = Limit::NUM_OF_FOLLOWED_MATCHES;
  	}
  	$followedMatchesData = array();
  	$remainingRecords = $numOfRecords;
  	$upcomingMatches = $this->__fetchFollowedMatches($userId,MatchType::UPCOMING,$remainingRecords);
    if (!empty($upcomingMatches)) {
      $upcomingMatchesData = $this->__prepareFollowedMatchesData($upcomingMatches,MatchType::UPCOMING);
      foreach ($upcomingMatchesData as $matchData) {
        array_push($followedMatchesData,$matchData);
      }
      $remainingRecords = $numOfRecords-count($followedMatchesData);
    }

    if ($remainingRecords > 0) {
	    $currentMatches = $this->__fetchFollowedMatches($userId,MatchType::CURRENT,$remainingRecords);
	    if (!empty($currentMatches)) {
	      $currentMatchesData = $this->__prepareFollowedMatchesData($currentMatches,MatchType::CURRENT);
	      foreach ($currentMatchesData as $matchData) {
	        array_push($followedMatchesData,$matchData);
	      }
	      $remainingRecords = $numOfRecords-count($followedMatchesData);
	    }
	  } else return $followedMatchesData;
    
   //  if ($remainingRecords > 0) {
	  //   $finishedMatches = $this->__fetchFollowedMatches($userId,MatchType::FINISHED,$remainingRecords);
	  //   if (!empty($finishedMatches)) {
	  //     $finishedMatchesData = $this->__prepareFollowedMatchesData($finishedMatches,MatchType::FINISHED);
	  //     foreach ($finishedMatchesData as $matchData) {
	  //       array_push($followedMatchesData,$matchData);
	  //     }
	  //   }
	  // }
    $countOfFollowedmatches = $this->getCountOfFollowedMatches($userId);
    $data = array('total' => $countOfFollowedmatches, 'matches' => $followedMatchesData);
    return array('status' => 200, 'data' => $data);
  }

  private function __fetchFollowedMatches($userId,$matchTypeByTime,$numOfRecords) {
  	$options = array(
  		'conditions' => array(
  			'user_id' => $userId,
        'Match.is_public' => 1
  		),
  		'fields' => array('id','match_id'),
  		'contain' => array(
  			'Match' => array(
  				'fields' => array('id','start_date_time','end_date_time','name','location_id','match_type',
  													'is_cricket_ball_used','players_per_side','is_public'),
  				'Location' => array(
  					'fields' => array('id' ,'name','city_id'),
            'City' => array(
              'fields' => array('id','name')
            )
  				),
  				'MatchTeam' => array(
		       	'fields' => array('id','team_id'),
		       	'conditions' => array(
		       		'MatchTeam.status' => MatchTeamStatus::CONFIRMED
		       	),
		        'Team' => array(
		        	'fields' => array('id','name','image_id'),
		        	'ProfileImage' => array(
		        		'fields' => array('id','url')
		        	)
		        )
	        )
  			)
  		),
  		'order' => 'Match.start_date_time DESC',
      'limit' => $numOfRecords
  	);
  	$currentDateTime = date('Y-m-d H:i:s');
		if ($matchTypeByTime == MatchType::UPCOMING) {
			$options['conditions']['Match.start_date_time >'] = $currentDateTime;
			$options['contain']['Match']['MatchPlayer'] = array('fields' => array('id','match_id','team_id','status'));
		} else if($matchTypeByTime == MatchType::CURRENT) {
			$options['conditions']['Match.start_date_time <='] = $currentDateTime;
			$options['conditions']['Match.end_date_time >='] = $currentDateTime;
			$options['contain']['Match']['MatchToss'] = array('fields' => array('id','winning_team_id','toss_decision'));
		} elseif ($matchTypeByTime == MatchType::FINISHED) {
			$options['conditions']['Match.end_date_time <'] = $currentDateTime;
			$options['contain']['Match']['MatchResult'] = array('fields' => array('id','result_type','winning_team_id'));
		}
		if ($matchTypeByTime == MatchType::CURRENT || $matchTypeByTime == MatchType::FINISHED) {
      $options['contain']['Match']['MatchInningScorecard'] = array('fields' => array('id','inning','team_id',
      	                                                                          'total_runs','overs','wickets'),
                                                          'order' => 'MatchInningScorecard.inning ASC');
		}
  	$data = $this->find('all', $options);
    return $data;
  }
	
	public function __prepareFollowedMatchesData($matchesDataArray,$matchTypeByTime) {
  	$preparedMatchData = array();
    foreach ($matchesDataArray as $data) {
      $matchData = array();
    	$teams = array();
    	if ($matchTypeByTime == MatchType::UPCOMING) {
    		$teams = $this->Match->getMatchTeamsArrayForMatches($data['Match']['MatchTeam'],$data['Match']['players_per_side'],
    			                                             $data['Match']['MatchPlayer'],$matchTypeByTime);
    	} else {
        	$teams = $this->Match->getMatchTeamsArrayForMatches($data['Match']['MatchTeam'],NULL,NULL,$matchTypeByTime);
    	}

    	$isSlotAvailable = false;
    	foreach ($teams as $team) {
    		if (!empty($team['slots_available'])) {
    			if ($team['slots_available'] > 0) {
    				$isSlotAvailable = true;
    				break;
    			}
    		}
    	}

    	$matchData['id'] = $data['Match']['id'];
    	$matchData['name'] = $data['Match']['name'];
    	$matchData['start_date_time'] = $data['Match']['start_date_time'];
    	$matchData['is_cricket_ball_used'] = $data['Match']['is_cricket_ball_used'];
    	if (!empty($data['Match']['Location']['City'])) {
        $matchData['location']['id'] = $data['Match']['Location']['City']['id'];
        $matchData['location']['name'] = $data['Match']['Location']['City']['name'];
      } else {
          $matchData['location']['id'] = null;
          $matchData['location']['name'] = null;
      }
      $matchData['teams'] = $teams;
    	$matchData['is_slot_available'] = $isSlotAvailable;

    	if ($matchTypeByTime == MatchType::CURRENT) {
        $toss = array();
        if (!empty($data['Match']['MatchToss'])) {
          $toss['winning_team_id'] = $data['Match']['MatchToss']['winning_team_id'];
          $toss['decision'] = $data['Match']['MatchToss']['toss_decision'];
        }
        $matchData['toss'] = $toss;
    	}

    	if ($matchTypeByTime == MatchType::CURRENT || $matchTypeByTime == MatchType::FINISHED) {
        $innings = array();
        if (!empty($data['Match']['MatchInningScorecard'])) {
          foreach ($data['Match']['MatchInningScorecard'] as $matchInning) {
            $inning = $matchInning['inning'];
            $innings[$inning-1] = array(
              'team_id' => $matchInning['team_id'],
              'runs' =>  $matchInning['total_runs'],
              'overs' =>  $matchInning['overs'],
              'wickets' =>  $matchInning['wickets'],
            );
          }
        }
        $matchData['innings'] = $innings;
    	}

    	if ($matchTypeByTime == MatchType::FINISHED) {
    		$result = array();
        if (!empty($data['Match']['MatchResult'])) {
          if ( $data['Match']['MatchResult']['result_type'] == MatchResultType::ABANDONED) {
            $result['abandoned'] = true;
          }
          else {
          	$result['abandoned'] = 0;
          	$result['result_type'] = $data['Match']['MatchResult']['result_type'];
            $result['winning_team_id'] = $data['Match']['MatchResult']['winning_team_id'];
          	$result['victory_margin'] = $this->Match->findWinMarginInMatch($innings,
          		                                                      $data['Match']['match_type'],
          		                                                      $data['Match']['players_per_side']);          
          }
        }
        $matchData['result'] = $result;
    	}
    	array_push($preparedMatchData, $matchData);
    }
    return $preparedMatchData;
  }

  public function getCountOfFollowedMatches($userId) {
    return $this->find('count',array(
      'conditions' => array(
        'user_id' => $userId,
        'Match.is_public' => 1,
        'Match.end_date_time >=' => date('Y-m-d H:i:s')
      ),
      'fields' => array('id','match_id'),
      'contain' => array(
        'Match' => array(
          'fields' => array('id','end_date_time','is_public')
        )
      )
    ));
  }
}

<?php
App::uses('AppModel', 'Model');
App::uses('Limit', 'Lib/Enum');
/**
 * MatchRecommendation Model
 *
 * @property Match $Match
 * @property User $User
 */
class MatchRecommendation extends AppModel {

  public $validate = array(
    'match_id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'Match id is not numeric',
        'required' => true,
        'allowEmpty' => false
      ),
      // 'matchExist' => array(
      //   'rule' => array('matchExist')
      // )
    ),
    'user_id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'User id is not numeric',
        'required' => true,
        'allowEmpty' => false
      ),
      // 'userExist' => array(
      //   'rule' => array('userExist')
      // )
    ),
    'recommended_to' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'recommended_to is not numeric',
        'required' => true,
        'allowEmpty' => false
      ),
      // 'userExist' => array(
      //   'rule' => array('userExist')
      // )
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
		),
		'UserRecommended' => array(
			'className' => 'User',
			'foreignKey' => 'recommended_to',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

  public function deleteMatchRecommendation($matchId, $userId) {
    $userId = trim($userId);
    $matchId = trim($matchId);
    if (empty($matchId) || empty($userId)) {
      return array('status' => 100, 'message' => 'deleteMatchRecommendation : Invalid Input Arguments');
    }
    $ifMatchRecommended = $this->find('count',array(
      'conditions' => array(
        'recommended_to' => $userId,
        'match_id' => $matchId
      )
    ));
    if ($ifMatchRecommended) {
      $ifUpdated = $this->updateAll(
        array(
          'deleted' => 1
        ),
        array(
          'recommended_to' => $userId,
          'match_id' => $matchId
        )
      );
      if ($ifUpdated) {
        $response = array('status' => 200, 'data' => $ifUpdated,'message' => 'deleteMatchRecommendation : match Recommendation deleted');
      } else  $response = array('status' => 113, 'message' => 'deleteMatchRecommendation : recommendation delete update failed');
    } else {
        $response = array('status' => 200, 'data' => $ifMatchRecommended, 'message' => 'deleteMatchRecommendation : match not recommended');
    }
    return $response;
  }

	public function getUsersForMatchRecommendation($userId,$matchId,$numOfRecords) {
    $numOfRecords = trim($numOfRecords);
    if (empty($numOfRecords)) {
      $numOfRecords = Limit::NUM_OF_USERS_TO_RECOMMEND_FOR_MATCH;
    }
    $recommendedUsers = array();
    $alreadyRecommendedUserIds = array();
		if (!empty($userId) && !empty($matchId)) {
      if ($this->User->userExists($userId)) {
      	$friendsData = $this->User->getFriendsInfo($userId,$numOfRecords);
      	$alreadyRecommendedUsers = $this->find('all',array(
      		'conditions' => array(
      			'match_id' => $matchId,
      			'user_id' => $userId
      		),
      		'fields' => array('recommended_to')
      	));      	
      	foreach ($alreadyRecommendedUsers as $key => $value) {
      		$alreadyRecommendedUserIds[$key] = $value['MatchRecommendation']['recommended_to'];
      	}
      	foreach ($friendsData as $key => $friend) {
          $recommendedUsers[$key] = $friend;
      		if (in_array($friend['id'],$alreadyRecommendedUserIds)) {
      				$recommendedUsers[$key]['is_already_recommended'] = true;
      		} else $recommendedUsers[$key]['is_already_recommended'] = false;
      	}
      	$response = array('status' => 200, 'data' => $recommendedUsers);
      } else {
      		$response = array('status' => 904, 'message' => 'getUsersForMatchRecommendation : User Does Not Exist');
      }
    } else {
    		$response = array('status' => 100, 'message' => 'getUsersForMatchRecommendation : Invalid Input Arguments');    
    }
    return $response;
	}

  public function addUsersForMatchRecommendation($userId,$matchId,$recommendedToUsers) {
    if (empty($userId) || empty($matchId) || empty($recommendedUsers)) {
      if ($this->User->userExists($userId)) {
        $matchRecommendationData = array();
        foreach ($recommendedToUsers as $key => $recommendedUser) {
          if ($this->User->FriendFrom->isUserAFriend($userId,$recommendedUser['id'])) {
            $matchRecommendationData[$key]['MatchRecommendation']['user_id'] = $userId;
            $matchRecommendationData[$key]['MatchRecommendation']['match_id'] = $matchId;
            $matchRecommendationData[$key]['MatchRecommendation']['recommended_to'] = $recommendedUser['id'];
          } else {
              return array('status' => 913, 'message' => 'addUsersForMatchRecommendation : User Not A Friend');
          }
        }
        if (!empty($matchRecommendationData)) {
          if ($this->saveMany($matchRecommendationData)) {
            $responseData = $this->__prepareResponseForAddUsersForRecommendation($matchId,$recommendedToUsers);
            $response =array('status' => 200, 'message' => 'success','data' => $responseData);
          } else {
              $response =array('status' => 914, 'message' => 'addUsersForMatchRecommendation : Users could not be added for recommendation');
          }
        }
      } else {
          $response = array('status' => 904, 'message' => 'addUsersForMatchRecommendation : User Does Not Exist');
      }
    } else {
        $response = array('status' => 100, 'message' => 'addUsersForMatchRecommendation : Invalid Input Arguments');    
    }
    return $response;
  }

  public function searchUsersForMatchRecommendation($userId,$matchId,$input,$numOfRecords) {
    $numOfRecords = trim($numOfRecords);
    $input = trim($input);
    if (empty($input)) {
      return NULL;
    }
    if (empty($numOfRecords)) {
      $numOfRecords = Limit::NUM_OF_USERS_TO_RECOMMEND_FOR_MATCH;
    }
    $recommendedUsers = array();
    $alreadyRecommendedUserIds = array();
    if (!empty($userId) && !empty($matchId)) {
      if ($this->User->userExists($userId)) {
        $friendsData = $this->User->searchUserFriends($userId,$input,$numOfRecords);
        if ($friendsData['status'] == 200) {
          $friendsData = $friendsData['data'];
        } else return array('status' => 200, 'data' => $recommendedUsers, 'message' => $friendsData['message']);
        $alreadyRecommendedUsers = $this->find('all',array(
          'conditions' => array(
            'match_id' => $matchId,
            'user_id' => $userId
          ),
          'fields' => array('recommended_to')
        ));
        
        foreach ($alreadyRecommendedUsers as $key => $value) {
          $alreadyRecommendedUserIds[$key] = $value['MatchRecommendation']['recommended_to'];
        }
        foreach ($friendsData as $key => $friend) {
          $recommendedUsers[$key]['id'] = $friend['id'];
          $recommendedUsers[$key]['name'] = $friend['name'];
          $recommendedUsers[$key]['image'] = $friend['image'];
          if (in_array($friend['id'],$alreadyRecommendedUserIds)) {
              $recommendedUsers[$key]['is_already_recommended'] = true;
          } else $recommendedUsers[$key]['is_already_recommended'] = false;
        }
        //$json = json_encode($recommendedUsersData);
        $response = array('status' => 200, 'data' => $recommendedUsers);
      } else {
          $response = array('status' => 904, 'message' => 'searchUsersForMatchRecommendation : User Does Not Exist');
      }
    } else {
        $response = array('status' => 100, 'message' => 'searchUsersForMatchRecommendation : Invalid Input Arguments');    
    }
    return $response;
  }

  public function getRecommendedMatches($userId,$numOfRecords) {
    $userId = trim($userId);
    $numOfRecords = trim($numOfRecords);
    if (empty($userId)) {
      return array('status' => 100, 'message' => 'getRecommendedMatches : Invalid Input Arguments');
    }
    if (empty($numOfRecords)) {
      $numOfRecords = Limit::NUM_OF_RECOMMENDED_MATCHES;
    }
    $allRecommendedMatches = array();
    $otherRecommendedMatches = array();
    $options['contain'] = array(
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
    );
    $options['limit'] = $numOfRecords;
    $options['order'] = 'MatchRecommendation.created DESC';
    $dataFromRecommendation = $this->find('all',array(
      'conditions' => array(
        'recommended_to' => $userId,
        'Match.start_date_time >' => date('Y-m-d H:i:s'),
        'Match.is_public' => 1,  
      ),
      'fields' => array('id','match_id'),
      'contain' => $options['contain'],
      'order' => $options['order'],
      'limit' => $options['limit']
    ));
    if (!empty($dataFromRecommendation)) {
      $allRecommendedMatches = $this->__prepareRecommendedMatchData($userId,$dataFromRecommendation);
    }
    $remainingRecords = $numOfRecords - count($allRecommendedMatches);
    if ($remainingRecords > 0) {
      $excludeMatchIdList = array();
      foreach ($allRecommendedMatches as $key => $value) {
        $excludeMatchIdList[$key] = $value['id'];
      }
      $otherRecommendedMatches = $this->Match->MatchFollower->getRecommendedMatches(
        $userId,
        $remainingRecords,
        $excludeMatchIdList
      );
    }
    foreach ($otherRecommendedMatches as $other) {
      array_push($allRecommendedMatches, $other);   
    }
    
    $allCount = count($allRecommendedMatches);
    $countOfRecommendedMatches = $this->getCountOfRecommendedMatches($userId);
    if (($allCount < $numOfRecords) || ($countOfRecommendedMatches < $allCount)) {
      $total = $allCount;
    } else  $total = $countOfRecommendedMatches;

    $data = array('total' => $total, 'matches' => $allRecommendedMatches);
    return array('status' => 200, 'data' => $data);
  }

  private function __prepareRecommendedMatchData($userId,$dataFromRecommendation) {
    $matchData = array();
    $index = 0;
    foreach($dataFromRecommendation as $data){
      $matchId = $data['MatchRecommendation']['match_id'];
      if (!$this->Match->MatchFollower->isUserFollowingTheMatch($userId,$matchId)) {
        $teams = $this->Match->getMatchTeamsArrayForMatches(
          $data['Match']['MatchTeam'],
          $data['Match']['players_per_side'],
          $data['Match']['MatchPlayer'],MatchType::RECOMMENDED
        );
        $matchData[$index]['id'] = $matchId;
        $matchData[$index]['start_date_time'] = $data['Match']['start_date_time'];
        $matchData[$index]['name'] = $data['Match']['name'];
        $matchData[$index]['is_cricket_ball_used'] = $data['Match']['is_cricket_ball_used'];
        $matchData[$index]['teams'] = $teams;
        if (!empty($data['Match']['Location']['City'])) {
          $matchData[$index]['location']['id'] = $data['Match']['Location']['City']['id'];
          $matchData[$index]['location']['name'] = $data['Match']['Location']['City']['name'];
        } else {
            $matchData[$index]['location']['id'] = null;
            $matchData[$index]['location']['name'] = null;
        }
        $commonFollowers = $this->Match->MatchFollower->findCommonFollowers($userId,$matchId);
        if ($commonFollowers['status'] == 200) {
          $matchData[$index]['common_followers_count'] = $commonFollowers['data'];
        } else $matchData[$index]['common_followers_count'] = null;
        $index = $index +1;
      }
    }
    return $matchData;
  }

  private function __prepareResponseForAddUsersForRecommendation($matchId,$recommendedToUsers) {
    foreach ($recommendedToUsers as $key => $value) {
      $users[$key] = $value['id'];
    }
    $response = array();
    $data = $this->find('all',array(
      'conditions' => array(
        'match_id' => $matchId,
        'recommended_to' => $users
      ),
      'fields' => array('id','match_id','recommended_to')
    ));
    foreach ($data as $key => $value) {
      $response[$key] = $value['MatchRecommendation'];
    }
    return $response;
  }

  public function getCountOfRecommendedMatches($userId) {
    return $this->find('count',array(
      'conditions' => array(
        'recommended_to' => $userId,
        'Match.start_date_time >' => date('Y-m-d H:i:s'),
        'Match.is_public' => 1,  
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

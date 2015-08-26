<?php
App::uses('AppModel', 'Model');
App::uses('MatchResultType', 'Lib/Enum');
App::uses('MatchTossType', 'Lib/Enum');
App::uses('InvitationStatus', 'Lib/Enum');
App::uses('Limit', 'Lib/Enum');
/**
 * MatchPlayerScorecard Model
 *
 * @property Match $Match
 * @property User $User
 */
class MatchPlayerScorecard extends AppModel {

	public $validate = array(
		'match_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Match id is not numeric',
				'required' => true,
				'allowEmpty' => false,
				'on' => 'update',
			),
			'matchExist' => array(
				'rule' => array('matchExist'),
				'on' => 'update',
			)
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'User id is not numeric',
				'required' => true,
				'allowEmpty' => false
			),
			'userExist' => array(
				'rule' => array('userExist'),
			)
		),
		'inning' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'inning Data is not valid',
			)
		),
		'runs_scored' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'run_scored Data is not valid',
			)
		),
		'balls_faced' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'balls_faced Data is not valid',
			),
		),
		'fours_hit' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'fours_hit Data is not valid',
			)
		),
		'sixes_hit' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'sixes_hit Data is not valid',
			)
		),
		'wickets_taken' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'wickets_taken Data is not valid',
			),
		),
		'overs_bowled' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'overs_bowled Data is not valid',
			)
		),
		'maidens_bowled' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'maidens_bowled Data is not valid',
			),
		),
		'runs_conceded' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'runs_conceded Data is not valid',
			),
		),
		'wides_bowled' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'wides_bowled Data is not valid',
			)
		),
		'no_balls_bowled' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'no_balls_bowled Data is not valid',
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
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);


	public function showMatchPlayerScorecard($id) {
		$match_player_scorecard = $this->findById($id);
		if ( ! empty($match_player_scorecard)) {
			$response = array('status' => 200, 'data' => $match_player_scorecard);
		} else {
			$response = array('status' => 302, 'message' => 'Match Player Scorecard Data Could Not Be Retrieved');
		}
		return $response;
	}

	public function deleteMatchPlayerScorecard($id) {
		$matchPlayerScorecard = $this->showMatchPlayerScorecard($id);
		if ($matchPlayerScorecard['status'] != 200 ){
			$response = array('status' => 905, 'message' => 'MatchPlayerScorecard does not exist');
			return $response;
		}
		$this->_updateCache($id);
		$delete = $this->softDelete($id);
		$response = array('status' => 200, 'data'=> $delete , 'message' => 'Deleted');
		return $response;
	}

	public function updateMatchPlayerScorecards($matchId , $matchPlayerScores) {
		$match = $this->Match->showMatch($matchId);
		if ($match['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Match does not exist');
			return $response;
		}
		$this->validateId();
		$this->_updateCache(null,$matchPlayerScores,$matchId);
		$this->validator()->add('match_id', 'required', array( 'rule' => 'notEmpty', 'required' => 'update' ));
		if ( ! empty($matchPlayerScores)) {
			if ($this->saveMany($matchPlayerScores)) {
				$response = array('status' => 200 , 'data' =>'Match Player Scores Saved');
			} else {
				$response = array('status' => 316, 'message' => 'Match Player Score Could not be added or updated');
				pr($this->validationErrors);
			}
		} else {			
			$response = array('status' => 317, 'message' => 'No Data To Update or Add Match Player Score');			
		}
		return $response ;
	}

	private function _updateCache($id = null,$matchPlayerScores = null, $matchId = null) {
		if ( ! empty($id)) {
			$matchPlayerScorecard = $this->showMatchPlayerScorecard($id);
			if ( ! empty($matchPlayerScorecard['data']['MatchPlayerScorecard']['match_id'])) {
				Cache::delete('show_match_'.$matchPlayerScorecard['data']['MatchPlayerScorecard']['match_id']);
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
		//  if(!empty($matchPlayerScores)){
		//	foreach ($matchPlayerScores as $matchPlayerScore) {
		//		Cache::delete('show_user_'.$matchPlayerScore['user_id']);
		//	}
		// }
  }

  public function getUserMyMatches($userId, $is_best, $numOfRecords) {
  	if (empty($numOfRecords)) {
  		$numOfRecords = Limit::NUM_OF_USER_MY_PLAYED_MATCHES;
  	}
  	$userMatchFiguresFromCache = Cache::read("my_matches_user_".$userId);
  	if (!empty($userMatchFiguresFromCache)) {
  		return $userMatchFiguresFromCache;
  	}
  	$matchAndTeams = $this->Match->MatchPlayer->getUserPlayedMatches($userId, $is_best, $numOfRecords);
  	$matchIdsList = $matchAndTeams['match_id_list'];
  	$userTeams = $matchAndTeams['user_teams'];
  	$matchContributions = $matchAndTeams['match_contributions'];
  	if (!empty($matchIdsList)) {
  		$matchData = $this->Match->getUserPlayedMatchesData($userId,$matchIdsList);
  		$responseData = $this->__prepareUserMatchesData($matchData,$userTeams,$matchContributions);
  		$userTotalMatchesCount = $this->Match->MatchPlayer->getCountOfUserPlayedMatches($userId);
  		$response = array('total' => $userTotalMatchesCount, 'matches' => $responseData);
  		if ($is_best) {
  			return array('status' => 200 , 'data' => $responseData);
  		} else return array('status' => 200 , 'data' => $response);  		
  	} else {
  			return array('status' => 112, 'data' => array(), 'message' => 'getUserMyMatches : No User Played Matches Found');
  	} 
  }

  public function getCareerPerformanceGraph($userId,$year,$is_batting) {
  	$userId = trim($userId);
  	$year = trim($year);
  	$is_batting = trim($is_batting);
  	if (!$this->_userExists($userId)) {
      return array('status' => 332 , 'message' => 'getCareerPerformanceGraph : User Does not exist');
    }
  	if ( empty($userId) || empty($year) || (!ctype_digit($year)) || (strlen($year) != 4) ) {
  		return array('status' => 100, 'message' => 'getCareerPerformanceGraph : Invalid Input Arguments');
  	}

  	$options = array(
  		'conditions' => array(
  			'MatchPlayerScorecard.user_id' => $userId,
  			'MatchPlayerScorecard.is_batting' => $is_batting,
  			'Match.start_date_time LIKE' => "%$year%"
  		),
  		'fields' => array('id','match_id','is_batting','runs_scored','balls_faced','wickets_taken',
  												'overs_bowled','runs_conceded'),
  		'contain' => array(
  			'Match' => array(
  				'fields' => array('id','name','start_date_time','location_id'),
  				'Location' => array(
  					'fields' => array('id','name','city_id'),
  					'City' => array(
  						'fields' => array('id','name')
  					)
  				),
  				'MatchResult' => array(
						'fields' => array('id'),
						'conditions' => array(
							'MatchResult.result_type !=' => MatchResultType::ABANDONED,
		  				'MatchResult.result_type !=' => MatchResultType::CANCELLED
						)
					)
  			)
  		),
      'order' => 'Match.start_date_time ASC'
  	);
  	$scores = $this->find('all',$options);

  	$graph = array();
  	$index = 0;
  	foreach ($scores as $score) {
  		if (!empty($score['Match']['MatchResult'])) {
	  		$graph[$index]['x'] = $score['Match']['start_date_time'];
	  		if ($is_batting) {
	  			$graph[$index]['y'] = $score['MatchPlayerScorecard']['runs_scored'];
	  		} else {
	  			$graph[$index]['y'] = $score['MatchPlayerScorecard']['wickets_taken'];
	  		}
	  		$graph[$index]['match']['id'] = $score['MatchPlayerScorecard']['match_id'];
	  		$graph[$index]['match']['name'] = $score['Match']['name'];
	  		if (!empty($score["Match"]['Location'])) {
	  			$graph[$index]['match']['location']['id'] = $score['Match']['Location']['id'];
	  			$graph[$index]['match']['location']['name'] = $score['Match']['Location']['name'];
	  		} else {
	  			$graph[$index]['match']['location']['id'] = null;
	  		}
	  		if (!empty($score['Match']['Location']['City'])) {
	  			$graph[$index]['match']['location']['city'] = $score['Match']['Location']['City']['city'];
	  		} else {
	  			$graph[$index]['match']['location']['city'] = null;
	  		}
	  		$index = $index + 1;
	  	}
  	}
  	return array('status' => 200, 'data' => array('is_batting' => $is_batting, 'year' => $year, 'records' => $graph));
  }

  private function __prepareUserMatchesData($matchData, $userTeams, $matchContributions) {  	
  	$myMatchesData = array();
  	$index = 0;
  	foreach ($matchData as $match) {
  		$teams = array();
  		$matchTeams = $this->Match->MatchTeam->getMatchConfirmedTeams($match['Match']['id']);
  		foreach ($matchTeams as $id => $team) {
  			if ($team['MatchTeam']['team_id'] == $match['MatchToss']['winning_team_id']) {
  				if ($match['MatchToss']['toss_decision'] == MatchTossType::BATTING_FIRST)  {
  					$teams[$team['MatchTeam']['team_id']] = 'teamA';
  				} else {
  					$teams[$team['MatchTeam']['team_id']] = 'teamB';
  				}						
  			} else {
  				if ($match['MatchToss']['toss_decision'] == MatchTossType::BATTING_FIRST)  {
  					$teams[$team['MatchTeam']['team_id']] = 'teamB';
  				} else {
  					$teams[$team['MatchTeam']['team_id']] = 'teamA';
  				}	
  			} 
  			$myMatchesData[$index][$teams[$team['MatchTeam']['team_id']]]['id'] = $team['MatchTeam']['team_id'];
  			$myMatchesData[$index][$teams[$team['MatchTeam']['team_id']]]['nick'] = $team['Team']['nick'];
  			$myMatchesData[$index][$teams[$team['MatchTeam']['team_id']]]['name'] = $team['Team']['name'];
  			if (!empty($team['Team']['ProfileImage'])) {
  				$myMatchesData[$index][$teams[$team['MatchTeam']['team_id']]]['image_url'] = $team['Team']['ProfileImage']['url'];
  			} else $myMatchesData[$index][$teams[$team['MatchTeam']['team_id']]]['image_url'] = null;
  			if ($userTeams[$match['Match']['id']] == $team['MatchTeam']['team_id']) {
  				$myMatchesData[$index]['player']['team'] = $myMatchesData[$index][$teams[$team['MatchTeam']['team_id']]];
  			}  			
  		}

  		$myMatchesData[$index]['match']['id'] = $match['Match']['id'];
  		$myMatchesData[$index]['match']['name'] = $match['Match']['name'];
  		$myMatchesData[$index]['match']['start_date_time'] = $match['Match']['start_date_time'];
  		
  		$myMatchesData[$index]['teamA']['innings'] = array();
  		$myMatchesData[$index]['teamB']['innings'] = array();
  		foreach ($match['MatchInningScorecard'] as $score) {
  			$inning = array(); 
  			$inning['inning'] = $score['inning']; 			
  			$inning['total_runs'] = $score['total_runs'];
  			$inning['overs'] = $score['overs'];
  			$inning['wickets'] = $score['wickets'];
  			array_push($myMatchesData[$index][$teams[$score['team_id']]]['innings'],$inning);
  		}

  		$index2 = 0;
  		foreach ($match['MatchPlayerScorecard'] as $value) {
  			$playerInning = array();
  			if ($value['is_batting'] == true) {
  				$playerInning['inning'] = $value['inning'];
  				$playerInning['runs_scored'] = $value['runs_scored'];
  				$playerInning['balls_faced'] = $value['balls_faced'];
  				$playerInning['is_batting'] = $value['is_batting'];
  			} else if ($value['is_batting'] == false) {
  					$playerInning['inning'] = $value['inning'];
						$playerInning['wickets_taken'] = $value['wickets_taken'];
						$playerInning['overs_bowled'] = $value['overs_bowled'];
						$playerInning['runs_conceded'] = $value['runs_conceded'];
						$playerInning['is_batting'] = $value['is_batting'];
  			}
  			if (!empty($playerInning)) {
  				$myMatchesData[$index]['player']['innings'][$index2] = $playerInning;
  				$index2 = $index2 + 1;
  			}
  		} 		
			$myMatchesData[$index]['player']['match_contribution'] = $matchContributions[$match['Match']['id']];
			$index = $index + 1;
  	}
  	return $myMatchesData;
  }

}

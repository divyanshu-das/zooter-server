<?php
App::uses('AppModel', 'Model');
App::uses('MatchType', 'Lib/Enum');
App::uses('TeamBattingStatus', 'Lib/Enum');
App::uses('MatchStatus', 'Lib/Enum');
App::uses('TossDecision', 'Lib/Enum');
App::uses('ResultType', 'Lib/Enum');
App::uses('BatsmanStatus', 'Lib/Enum');
App::uses('OutType', 'Lib/Enum');
/**
 * MatchInningScorecard Model
 *
 * @property Match $Match
 * @property Team $Team
 */
class MatchInningScorecard extends AppModel {

public $validate = array(
		'match_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Match id is not numeric',
				'on' => 'update',
			),
			'matchExist' => array(
				'rule' => array('matchExist'),
				'on' => 'update',
			)
		),
		'team_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Team id is not numeric'
			),
			'teamExist' => array(
				'rule' => array('teamExist')
			)
		),
		'inning' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'inning is not valid'
			)
		),
		'overs' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'overs is not valid'
			)
		),
		'total_runs' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'total runs is not valid'
			),
		),
		'wickets' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'wickets is not valid'
			)
		),
		'wide_balls' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'wide balls is not valid'
			)
		),
		'leg_byes' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'leg byes is not valid'
			),
		),
		'byes' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'byes data is not valid'
			)
		),
		'no_balls' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'no ball data is not valid'
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
		'Team' => array(
			'className' => 'Team',
			'foreignKey' => 'team_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'MatchBallScore' => array(
			'className' => 'MatchBallScore',
			'foreignKey' => 'match_inning_scorecard_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => ''
		),
		'MatchBatsmanScore' => array(
			'className' => 'MatchBatsmanScore',
			'foreignKey' => 'match_inning_scorecard_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => ''
		),
		'MatchBowlerScore' => array(
			'className' => 'MatchBowlerScore',
			'foreignKey' => 'match_inning_scorecard_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => ''
		)
	);

public function showMatchInningScorecard($id) {
		$match_inning_scorecard = $this->findById($id);
		if ( ! empty($match_inning_scorecard)) {
			$response = array('status' => 200, 'data' => $match_inning_scorecard);
		} else {
			$response = array('status' => 302, 'message' => 'Match Inning Scorecard Data Could Not Be Retrieved');
		}
		return $response;
	}

	public function deleteMatchInningScorecard($id) {
		$matchInningScorecard = $this->showMatchInningScorecard($id);
		if ( $matchInningScorecard['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Match Inning Scorecard does not exist');
			return $response;
		}
		$this->_updateCache($id,null,null);
		$delete = $this->softDelete($id);
		$response = array('status' => 200, 'data'=> $delete , 'message' => 'Deleted');
		return $response;
	}

	public function updateMatchInningScorecards($matchId , $matchInningScores) {
		$match = $this->Match->showMatch($matchId);
		if ( $match['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Match does not exist');
			return $response;
		}
		$this->validateId();
		$this->_updateCache(null,$matchInningScores,$matchId);
		if ( ! empty($matchInningScores)) {
			if ($this->saveMany($matchInningScores)) {
				$response = array('status' => 200 , 'data' =>'Match Inning Scores Saved');
			} else {
				$response = array('status' => 314, 'message' => 'Match Inning Score Could not be added or updated');
				pr($this->validationErrors);
			}
		} else {			
			$response = array('status' => 315, 'message' => 'No Data To Update or Add Match Inning Score');			
		}
		return $response ;
	}

	private function _updateCache($id = null,$matchInningScores = null, $matchId = null) {
		if( ! empty($id)) {
			$matchInningScorecard = $this->showMatchInningScorecard($id);
			if ( ! empty($matchInningScorecard['data']['MatchInningScorecard']['match_id'])) {
				Cache::delete('show_match_' . $matchInningScorecard['data']['MatchInningScorecard']['match_id']);
				Cache::delete('show_team_' . $matchInningScorecard['data']['MatchInningScorecard']['team_id']);
			}
		}
		if ( ! empty($matchId)) {
			$match = $this->Match->showMatch($matchId);
			Cache::delete('show_match_' . $matchId);
			if ( ! empty($match['data']['MatchTeam'])) {
				foreach ($match['data']['MatchTeam'] as $team) {
					Cache::delete('show_team_' . $team['team_id']);
				}
			}
		}
		if ( ! empty($matchInningScores)) {
			foreach ($matchInningScores as $matchInningScore) {
				Cache::delete('show_team_' . $matchInningScore['team_id']);
			}
		}
  }

  public function fetchMiniScorecard($matchId) {
		$matchData = array();
		$match = $this->Match->getMatchDetailsForScorecard($matchId);
		$series = $match['Series'];
		$location = $match['Location'];
		$firstTeam = $match['FirstTeam'];
		$secondTeam = $match['SecondTeam'];
		$match = $match['Match'];
		$matchData['id'] = $match['id'];
		$matchData['name'] = $match['name'];
		$matchData['series_name'] = $series['name'];
		$matchData['start_date_time'] = $match['start_date_time'];
		$matchData['end_date_time'] = $match['end_date_time'];
		$matchData['in_progress'] = $match['in_progress'];
		$matchData['status'] = $this->Match->fetchMatchTypeByTime($matchId);
		$matchData['location']['id'] = $location['id'];
		$matchData['location']['name'] = $location['name'];
		$matchData['location']['city'] = !empty($location['City']) ? $location['City']['name'] : null;
		$matchData['type'] = MatchType::stringValue($match['match_type']);
		$matchData['overs_per_innings'] = $match['overs_per_innings'];
		$matchData['required_overs'] = $match['required_overs'];
		$matchData['banner'] = '';
		
		if ( $matchData['status'] == MatchStatus::stringValue(MatchStatus::UPCOMING) ) {
			$matchData['team_one']['id'] = $firstTeam['id'];
			$matchData['team_one']['name'] = $firstTeam['name'];
			$matchData['team_one']['image'] = !empty($firstTeam['ProfileImage']['url']) ? $firstTeam['ProfileImage']['url'] : null;
			$matchData['team_two']['id'] = $secondTeam['id'];
			$matchData['team_two']['name'] = $secondTeam['name'];
			$matchData['team_two']['image'] = !empty($secondTeam['ProfileImage']['url']) ? $secondTeam['ProfileImage']['url'] : null;
			return array('status' => 200, 'data' => array('match' => $matchData));
		}

		$matchInnings = $this->find('all',array(
			'conditions' => array(
				'MatchInningScorecard.match_id' => $match['id']
			),
			'contain' => array(
				'Team' => array(
					'fields' => array('id','name','image_id'),
					'ProfileImage' => array(
						'fields' => array('id','url')
					)
				),
			),				
			'order' => 'MatchInningScorecard.inning ASC'
		));
		$teamOne = array();
		$teamTwo = array();
		$numOfInnings = Count($matchInnings);
		$matchData['no_of_innings'] = $numOfInnings;
		if ($numOfInnings > 0) {
			$teamOne = $this->__prepareTeamMiniScore($matchInnings[0],$match['id']);
			$matchData['team_one'] = $teamOne;
		}
		if ($numOfInnings > 1) {
			$teamTwo = $this->__prepareTeamMiniScore($matchInnings[1],$match['id']);
			$matchData['team_two'] = $teamTwo;
		}
		if ($matchData['team_one']['is_batting'] == true) {
			$matchData['strike_batsman'] = $matchData['team_one']['strike_batsman'];
			$matchData['non_strike_batsman'] = $matchData['team_one']['non_strike_batsman'];
			$matchData['bowlers'] = $matchData['team_one']['bowlers'];
			$matchData['recent_overs'] = array();//$matchData['team_one']['recent_overs'];
			$matchData['patnership'] = 12;

		}	else {
			$matchData['strike_batsman'] = $matchData['team_two']['strike_batsman'];
			$matchData['non_strike_batsman'] = $matchData['team_two']['non_strike_batsman'];
			$matchData['bowlers'] = $matchData['team_two']['bowlers'];
			$matchData['recent_overs'] = array();//$matchData['team_one']['recent_overs'];
			$matchData['patnership'] = 12;
		}
		$response = array('status' => 200, 'data' => array('match' => $matchData));
		return $response;
	}

	private function __prepareTeamMiniScore($inning,$matchId) {
		$team = array();
		$team['id'] = $inning['Team']['id'];
		$team['name'] = $inning['Team']['name'];
		$team['image'] = !empty($inning['Team']['ProfileImage']) ? $inning['Team']['ProfileImage']['url'] : null;
		if ($inning['MatchInningScorecard']['in_progress'] == true && $inning['MatchInningScorecard']['is_complete'] != true) {
			$team['is_batting'] = true;
			$team['batting_status'] = TeamBattingStatus::stringValue(TeamBattingStatus::ONGOING);
		} elseif ($inning['MatchInningScorecard']['is_complete'] == true) {
			$team['is_batting'] = false;
			$team['batting_status'] = TeamBattingStatus::stringValue(TeamBattingStatus::FINISHED);
		} else {
			$team['is_batting'] = false;
			$team['batting_status'] = TeamBattingStatus::stringValue(TeamBattingStatus::UPCOMING);
		}
		$team['runs'] = !empty($inning['MatchInningScorecard']['total_runs']) ? $inning['MatchInningScorecard']['total_runs'] : 0;
		$team['wickets'] = !empty($inning['MatchInningScorecard']['wickets']) ? $inning['MatchInningScorecard']['wickets'] : 0;
		$team['overs'] = !empty($inning['MatchInningScorecard']['overs']) ? $inning['MatchInningScorecard']['overs'] : 0;
		$team['run_rate'] = $this->prepareInningsRunRate($team['runs'],$team['overs']);
		$team['strike_batsman'] = array();
		$team['non_strike_batsman'] = array();
		$batsmen = $this->MatchBatsmanScore->getCurrentBattingPair($inning['MatchInningScorecard']['id']);
		$strikerAdded = false;
		foreach ($batsmen as $key => $batsman) {
			if ((!$strikerAdded) && ($batsman['MatchBatsmanScore']['status'] == BatsmanStatus::NOT_OUT || $batsman['MatchBatsmanScore']['status'] == BatsmanStatus::STRIKER)) {
				$team['strike_batsman']['id'] = $batsman['User']['id'];
				$team['strike_batsman']['name'] = $this->_prepareUserName($batsman['User']['Profile']['first_name'],$batsman['User']['Profile']['middle_name'],$batsman['User']['Profile']['last_name']);
				$team['strike_batsman']['first_name'] = $batsman['User']['Profile']['first_name'];
				$team['strike_batsman']['middle_name'] = $batsman['User']['Profile']['middle_name'];
				$team['strike_batsman']['last_name'] = $batsman['User']['Profile']['last_name'];
				$team['strike_batsman']['image'] = !empty($batsman['User']['Profile']['ProfileImage']['url']) ? $batsman['User']['Profile']['ProfileImage']['url'] : null;
				$team['strike_batsman']['runs'] = !empty($batsman['MatchBatsmanScore']['runs']) ? $batsman['MatchBatsmanScore']['runs'] : 0;
				$team['strike_batsman']['balls'] = !empty($batsman['MatchBatsmanScore']['balls']) ? $batsman['MatchBatsmanScore']['balls'] : 0;
				$team['strike_batsman']['dot_balls'] = !empty($batsman['MatchBatsmanScore']['dot_balls']) ? $batsman['MatchBatsmanScore']['dot_balls'] : 0;
				$team['strike_batsman']['fours'] = !empty($batsman['MatchBatsmanScore']['fours']) ? $batsman['MatchBatsmanScore']['fours'] : 0;
				$team['strike_batsman']['sixes'] = !empty($batsman['MatchBatsmanScore']['sixes']) ? $batsman['MatchBatsmanScore']['sixes'] : 0;
				$team['strike_batsman']['strike_rate'] = $this->prepareBatsmanStrikeRate($batsman['MatchBatsmanScore']['runs'],$batsman['MatchBatsmanScore']['balls']);
				$strikerAdded = true;
			} else if ($batsman['MatchBatsmanScore']['status'] == BatsmanStatus::NON_STRIKER) {
				$team['non_strike_batsman']['id'] = $batsman['User']['id'];
				$team['non_strike_batsman']['name'] = $this->_prepareUserName($batsman['User']['Profile']['first_name'],$batsman['User']['Profile']['middle_name'],$batsman['User']['Profile']['last_name']);
				$team['non_strike_batsman']['first_name'] = $batsman['User']['Profile']['first_name'];
				$team['non_strike_batsman']['middle_name'] = $batsman['User']['Profile']['middle_name'];
				$team['non_strike_batsman']['last_name'] = $batsman['User']['Profile']['last_name'];
				$team['non_strike_batsman']['image'] = !empty($batsman['User']['Profile']['ProfileImage']['url']) ? $batsman['User']['Profile']['ProfileImage']['url'] : null;
				$team['non_strike_batsman']['runs'] = !empty($batsman['MatchBatsmanScore']['runs']) ? $batsman['MatchBatsmanScore']['runs'] : 0;
				$team['non_strike_batsman']['balls'] = !empty($batsman['MatchBatsmanScore']['balls']) ? $batsman['MatchBatsmanScore']['balls'] : 0;
				$team['non_strike_batsman']['dot_balls'] = !empty($batsman['MatchBatsmanScore']['dot_balls']) ? $batsman['MatchBatsmanScore']['dot_balls'] : 0;
				$team['non_strike_batsman']['fours'] = !empty($batsman['MatchBatsmanScore']['fours']) ? $batsman['MatchBatsmanScore']['fours'] : 0;
				$team['non_strike_batsman']['sixes'] = !empty($batsman['MatchBatsmanScore']['sixes']) ? $batsman['MatchBatsmanScore']['sixes'] : 0;
				$team['non_strike_batsman']['strike_rate'] = $this->prepareBatsmanStrikeRate($batsman['MatchBatsmanScore']['runs'],$batsman['MatchBatsmanScore']['balls']);
			}
		}
		$team['bowlers'] = array();
		$bowlers = $this->MatchBowlerScore->getCurrentBowlingPair($inning['MatchInningScorecard']['id']);
		foreach ($bowlers as $key => $bowler) {
			$team['bowlers'][$key]['id'] = $bowler['User']['id'];
			$team['bowlers'][$key]['name'] = $this->_prepareUserName($bowler['User']['Profile']['first_name'],$bowler['User']['Profile']['middle_name'],$bowler['User']['Profile']['last_name']);
			$team['bowlers'][$key]['first_name'] = $bowler['User']['Profile']['first_name'];
			$team['bowlers'][$key]['middle_name'] = $bowler['User']['Profile']['middle_name'];
			$team['bowlers'][$key]['last_name'] = $bowler['User']['Profile']['last_name'];
			$team['bowlers']['image'] = !empty($bowler['User']['Profile']['ProfileImage']['url']) ? $bowler['User']['Profile']['ProfileImage']['url'] : null;
			$team['bowlers'][$key]['overs'] = !empty($bowler['MatchBowlerScore']['overs']) ? $bowler['MatchBowlerScore']['overs'] : 0;
			$team['bowlers'][$key]['dot_balls'] = !empty($bowler['MatchBowlerScore']['dot_balls']) ? $bowler['MatchBowlerScore']['dot_balls'] : 0;
			$team['bowlers'][$key]['runs'] = !empty($bowler['MatchBowlerScore']['runs_conceded']) ? $bowler['MatchBowlerScore']['runs_conceded'] : 0;
			$team['bowlers'][$key]['wickets'] = !empty($bowler['MatchBowlerScore']['wickets']) ? $bowler['MatchBowlerScore']['wickets'] : 0;
			$team['bowlers'][$key]['economy'] = $this->getBowlingEconomy($bowler['MatchBowlerScore']['runs_conceded'],$bowler['MatchBowlerScore']['overs']);
		}
		if ($inning['MatchInningScorecard']['inning'] == 2) {
			$team['target'] = $this->__prepareTargetForSecondInnings($inning);
		}
		$team['recent_overs'] = $this->MatchBallScore->getOversData($inning['MatchInningScorecard']['id']);
		return $team;
	}

	private function __prepareTargetForSecondInnings($secondInning) {
		$team = $secondInning['Team'];
		$inning = $secondInning['MatchInningScorecard'];
		$match = $this->Match->findById($inning['match_id']);
		$match = $match['Match'];
		$otherTeam = $this->Match->getBowlingTeam($match['id'],$team['id']);
		$ifAnyRemainingPlayers = $this->MatchBatsmanScore->checkIfAnyBatsmenPairLeftToPlay($inning['id'],$match['players_per_side']);
		$targetString = '';
		$remainingOvers = !empty($match['required_overs']) ? $match['required_overs'] : $match['overs_per_innings'];
		$requiredOversInBalls = ( ((((int)$remainingOvers) * 10) / 10) * 6) + (($remainingOvers * 10) % 10);
		$currentOversInBalls = (((((int)$inning['overs']) * 10) / 10) * 6)  + (($inning['overs'] * 10) % 10);
		$remainingRuns = $match['target'] - $inning['total_runs'];
		$remainingOvers = (int)(($requiredOversInBalls - $currentOversInBalls) / 6).'.'.(($requiredOversInBalls - $currentOversInBalls) % 6);
		$remainingBalls = $requiredOversInBalls - $currentOversInBalls;
		if ($inning['in_progress'] == true || $inning['is_complete'] == true) {
			if ($inning['runs'] == ($match['target']-1) && ($remainingOvers == 0 || $ifAnyRemainingPlayers == true)) {
				if ($inning['is_complete'] == true) {
					$targetString = 'Match Drawn';
				} else {
					$targetString = 'Match is Tied Currently';					
				}
			} else if ($remainingRuns <= 0) {
				$targetString = $team['name'].' won the Match';
			} else if ($remainingRuns > 0  && ($remainingOvers <= 0 || $ifAnyRemainingPlayers == false)) {
				$targetString = $otherTeam['name'].' won the Match';
			} else if ( $remainingRuns > 0 && $remainingOvers > 0 && $ifAnyRemainingPlayers == true ) {
				if ($remainingOvers < 10) {
					$targetString = $team['name'].' need '.$remainingRuns.' runs in '.$remainingBalls.' balls to win';
				} else {
					$targetString = $team['name'].' need '.$remainingRuns.' runs in '.$remainingOvers.' overs to win';					
				}
			} 
		}
		return $targetString;
	}

	public function fetchMatchScorecard($matchId) {
		$matchData = array();
		$match = $this->Match->getMatchDataForScore($matchId);
		if (empty($match)) {
			return array('status' => 101, 'fetchMatchScorecard : Match Data does not Exist');
		}
		$tossTeam = $match['TossWinningTeam'];
		$resultTeam = $match['MatchWinningTeam'];
		$series = $match['Series'];
		$location = $match['Location'];
		$match = $match['Match'];
		$matchData['id'] = $matchId;
		$matchData['name'] = $match['name'];
		$matchData['series_name'] = $series['name'];
		$matchData['start_date_time'] = $match['start_date_time'];
		$matchData['end_date_time'] = $match['end_date_time'];
		$matchData['in_progress'] = $match['in_progress'];
		$matchData['status'] = $this->Match->fetchMatchTypeByTime($matchId);
		$matchData['location']['id'] = $location['id'];
		$matchData['location']['name'] = $location['name'];
		$matchData['location']['city'] = !empty($location['City']) ? $location['City']['name'] : null;
		$matchData['type'] = MatchType::stringValue($match['match_type']);
		$matchData['actual_overs'] = !empty($match['overs']) ? $match['overs'] : 0;
		$matchData['overs_per_innings'] = !empty($match['overs_per_innings']) ? $match['overs_per_innings'] : 0;
		$matchData['target_runs'] = !empty($match['target']) ? $match['target'] : 0;
		$matchData['required_overs'] = !empty($match['required_overs']) ? $match['required_overs'] : 0;
		$matchData['toss_winning_team']['id'] = $match['toss_winning_team_id'];
		$matchData['toss_winning_team']['name'] = $tossTeam['name'];
		$matchData['toss_decision'] = TossDecision::stringValue($match['toss_decision']);
		$matchData['toss'] = $this->prepareTossString($match['toss_decision'],$tossTeam);
		$matchData['result_type'] = ResultType::stringValue($match['result_type']);
		$matchData['winning_team']['id'] = $match['winning_team_id'];
		$matchData['winning_team']['name'] = $resultTeam['name'];
		$matchData['target'] = '';
		$matchInnings = $this->getMatchInningsData($matchId);
		$inningsOneData = array();
		$inningsTwoData = array();
		$numOfInnings = Count($matchInnings);
		$matchData['no_of_innings'] = $numOfInnings;
		if ($numOfInnings > 0) {
			$inningsOneData = $this->__preapareInningsData($matchInnings[0],$matchId);
			$inningsOneData['playing_squad'] = $this->Match->MatchPlayer->getDidNotBatPlayers($matchId,$matchInnings[0]['MatchInningScorecard']['team_id'],null);
			$inningsOneData['team']['id'] = !empty($matchInnings[0]['Team']) ? $matchInnings[0]['Team']['id'] : null;
			$inningsOneData['team']['name'] = !empty($matchInnings[0]['Team']) ? $matchInnings[0]['Team']['name'] : null;
			$inningsOneData['team']['image'] = !empty($matchInnings[0]['Team']['ProfileImage']) ? $matchInnings[0]['Team']['ProfileImage']['url'] : null;
			$matchData['innings_one'] = $inningsOneData;
		}
		if ($numOfInnings > 1) {
			if ($matchInnings[1]['MatchInningScorecard']['in_progress'] == true || $matchInnings[1]['MatchInningScorecard']['is_complete'] == true) {
				$inningsTwoData = $this->__preapareInningsData($matchInnings[1],$matchId);
			} else {
				$inningsTwoData['status'] = $this->__prepareInningsStatus($matchInnings[1]);
				$inningsTwoData['team']['id'] = !empty($matchInnings[1]['Team']) ? $matchInnings[1]['Team']['id'] : null;
				$inningsTwoData['team']['name'] = !empty($matchInnings[1]['Team']) ? $matchInnings[1]['Team']['name'] : null;
				$inningsTwoData['team']['image'] = !empty($matchInnings[1]['Team']['ProfileImage']) ? $matchInnings[1]['Team']['ProfileImage']['url'] : null;
			}
			$inningsTwoData['playing_squad'] = $this->Match->MatchPlayer->getDidNotBatPlayers($matchId,$matchInnings[1]['MatchInningScorecard']['team_id'],null);
			$matchData['innings_two'] = $inningsTwoData;
			$matchData['target'] = $this->__prepareTargetForSecondInnings($matchInnings[1]);
		}
		$response = array('status' => 200, 'data' => array('match' => $matchData));
		return $response;
	}

	private function getMatchInningsData($matchId) {
		return $this->find('all',array(
			'conditions' => array(
				'MatchInningScorecard.match_id' => $matchId
			),
			'contain' => array(
				'Team' => array(
					'fields' => array('id','name','image_id'),
					'ProfileImage' => array(
						'fields' => array('id','url')
					)
				),
				'MatchBatsmanScore' => array(
					'order' => 'MatchBatsmanScore.created ASC',
					'User' => array(
						'fields' => array('id'),
						'Profile' => array(
							'fields' => array('id','user_id','first_name','middle_name','last_name','image_id'),
							'ProfileImage' => array(
								'fields' => array('id','url')
							)
						)
					),
					'MatchBatsmanBowler' => array(
						'fields' => array('id'),
						'Profile' => array(
							'fields' => array('id','user_id','first_name','middle_name','last_name','image_id'),
							'ProfileImage' => array(
								'fields' => array('id','url')
							)
						)
					),
					'MatchBatsmanFielder' => array(
						'fields' => array('id'),
						'Profile' => array(
							'fields' => array('id','user_id','first_name','middle_name','last_name')
						)
					),
				),
				'MatchBowlerScore' => array(
					'order' => 'MatchBowlerScore.created ASC',
					'User' => array(
						'fields' => array('id'),
						'Profile' => array(
							'fields' => array('id','user_id','first_name','middle_name','last_name')
						)
					)
				)
			),
			'order' => 'MatchInningScorecard.inning ASC'
		));
	}

	private function __preapareInningsData($inning,$matchId) {
		$battingTeam = array();
		$bowlingTeam = array();
		$battingTeam = $this->__prepareBattingTeamInning(
			$matchId,
			$inning['Team'],
			$inning['MatchInningScorecard'],
			$inning['MatchBatsmanScore']
		);
		$bowlingTeam = $this->__prepareBowlingTeamInning(
			$matchId,
			$inning['Team']['id'],
			$inning['MatchBowlerScore']
		);
		$status = $this->__prepareInningsStatus($inning);
		return array('status' => $status, 'batting_team' => $battingTeam, 'bowling_team' => $bowlingTeam);
	}

	private function __prepareInningsStatus($inning) {
		$status = null;
		if ($inning['MatchInningScorecard']['in_progress'] == true && $inning['MatchInningScorecard']['is_complete'] != true) {
			$status = TeamBattingStatus::stringValue(TeamBattingStatus::ONGOING);
		} elseif ($inning['MatchInningScorecard']['is_complete'] == true) {
			$status = TeamBattingStatus::stringValue(TeamBattingStatus::FINISHED);
		} else {
			$status = TeamBattingStatus::stringValue(TeamBattingStatus::UPCOMING);
		}
		return $status;
	}

	private function __prepareBattingTeamInning($matchId,$team,$matchInning,$matchBatsmen) {
		$battingTeamInning = array();
		$battingTeamInning['id'] = $team['id'];
		$battingTeamInning['name'] = $team['name'];
		$battingTeamInning['image'] = !empty($team['ProfileImage']) ? $team['ProfileImage']['url'] : null;
		$battingTeamInning['runs'] = $matchInning['total_runs'];
		$battingTeamInning['wickets'] = $matchInning['wickets'];
		$battingTeamInning['overs'] = $matchInning['overs'];
		$battingTeamInning['run_rate'] = $this->prepareInningsRunRate($battingTeamInning['runs'],$battingTeamInning['overs']);
		$battingTeamInning['wides'] = $matchInning['wide_balls'];
		$battingTeamInning['no_balls'] = $matchInning['no_balls'];
		$battingTeamInning['byes'] = $matchInning['byes'];
		$battingTeamInning['leg_byes'] = $matchInning['leg_byes'];
		$battingTeamInning['total_extras'] = $matchInning['wide_balls']+$matchInning['no_balls']+$matchInning['leg_byes']+$matchInning['byes'];
		$battingTeamInning['batsmen'] = $this->__prepareBatsmenData($matchBatsmen,$matchId);
		$battingTeamInning['did_not_bat'] = $this->__prepareDidNotBatData($matchId,$team['id'],$matchBatsmen);
		$battingTeamInning['fall_of_wickets'] = $this->MatchBallScore->prepareFallOfWickets($matchInning['id']);
		$battingTeamInning['overs_data'] = $this->MatchBallScore->getOversData($matchInning['id']);
		return $battingTeamInning;
	}

	private function __prepareBowlingTeamInning($matchId,$battingTeamId,$matchBowlers) {
		$bowlingTeamInning = array();
		$bowlingTeam = $this->Match->getBowlingTeam($matchId,$battingTeamId);
		$bowlingTeamInning['id'] = $bowlingTeam['id'];
		$bowlingTeamInning['name'] = $bowlingTeam['name'];
		$bowlingTeamInning['image'] = !empty($bowlingTeam['ProfileImage']) ? $bowlingTeam['ProfileImage']['url'] : null;
		$bowlingTeamInning['bowlers'] = $this->__prepareBowlersData($matchBowlers,$matchId);
		return $bowlingTeamInning;
	}

	private function __prepareBatsmenData($batsmen,$matchId) {
		$batsmenData = array();
		foreach ($batsmen as $key => $batsman) {
			$batsmenData[$key]['id'] = $batsman['user_id'];

			if (!empty($batsman['User']['Profile'])) {
				$batsmenData[$key]['name'] = $this->_prepareUserName($batsman['User']['Profile']['first_name'],$batsman['User']['Profile']['middle_name'],$batsman['User']['Profile']['last_name']);
      } else {
        $batsmenData[$key]['name'] = null;
      }

			$batsmenData[$key]['first_name'] = $batsman['User']['Profile']['first_name'];
			$batsmenData[$key]['middle_name'] = $batsman['User']['Profile']['middle_name'];
			$batsmenData[$key]['last_name'] = $batsman['User']['Profile']['last_name'];
			$batsmenData[$key]['image'] = !empty($batsman['User']['Profile']['ProfileImage']['url']) ? $batsman['User']['Profile']['ProfileImage']['url'] : null;
			$batsmenData[$key]['status'] = BatsmanStatus::stringValue(BatsmanStatus::NOT_OUT);
			$batsmenData[$key]['is_striker'] = false;
			$batsmenData[$key]['is_out'] = false;
			if ($batsman['status'] == BatsmanStatus::OUT) {
				$batsmenData[$key]['is_out'] = true;
				$batsmenData[$key]['status'] = $this->prepareBatsmanDismissalString($batsman);
			} else if ($batsman['status'] == BatsmanStatus::STRIKER) {
				$batsmenData[$key]['is_striker'] = true;
			} else if ($batsman['status'] == BatsmanStatus::RETIRED_HURT) {
				$batsmenData[$key]['status'] = BatsmanStatus::stringValue(BatsmanStatus::RETIRED_HURT);
			}
			$batsmenData[$key]['is_keeper'] = $this->Match->MatchPlayer->checkPlayerRole(
				$matchId,
				$batsman['user_id'],
				PlayerRole::WICKETKEEPER
			);
			$batsmenData[$key]['role'] = PlayerRole::stringValue($this->Match->MatchPlayer->getPlayerRoleInMatch($matchId,$batsman['user_id']));
			$batsmenData[$key]['is_captain'] = $this->Match->MatchPlayer->isPlayerCaptain($matchId,$batsman['user_id']);
			$batsmenData[$key]['runs'] = $batsman['runs'];
			$batsmenData[$key]['balls'] = $batsman['balls'];
			$batsmenData[$key]['dot_balls'] = $batsman['dot_balls'];
			$batsmenData[$key]['fours'] = $batsman['fours'];
			$batsmenData[$key]['sixes'] = $batsman['sixes'];
			if (!empty($batsman['balls']) && ($batsman['balls'] != 0)) {
				if (empty($batsman['runs'])) {
					$batsmenData[$key]['strike_rate'] = 0;
				} else {
					$batsmenData[$key]['strike_rate'] = round((100 * ($batsman['runs'] / $batsman['balls'])),1,PHP_ROUND_HALF_UP);					
				}
			} else {
				$batsmenData[$key]['strike_rate'] = 'NA';
			}	
		}
		return $batsmenData;
	}

	private function prepareBatsmanDismissalString($batsman) {
		$outType = $batsman['out_type'];
		$unconventionalOutTypes = [OutType::HANDLED_THE_BALL,OutType::HIT_THE_BALL_TWICE,OutType::TIMED_OUT,OutType::OBSTRUCTING_THE_FIELD,OutType::RETIRED];		
		if (in_array($outType, $unconventionalOutTypes)) {
			return 'out ('.OutType::stringValue($outType).')';
		}
		if (!empty($batsman['MatchBatsmanBowler']['Profile'])) {
			$bowlerName = $this->_prepareUserName($batsman['MatchBatsmanBowler']['Profile']['first_name'],$batsman['MatchBatsmanBowler']['Profile']['middle_name'],$batsman['MatchBatsmanBowler']['Profile']['last_name']);
		} else {
			$bowlerName = null;
		}
		if (!empty($batsman['MatchBatsmanFielder']['Profile'])) {
			$fielderName = $this->_prepareUserName($batsman['MatchBatsmanFielder']['Profile']['first_name'],$batsman['MatchBatsmanFielder']['Profile']['middle_name'],$batsman['MatchBatsmanFielder']['Profile']['last_name']);
		} else {
			$fielderName = null;
		}
		switch ($outType) {
			case OutType::BOWLED:
				return 'b '.$bowlerName;
				break;
			case OutType::CAUGHT:
				$outString = ($bowlerName == $fielderName) ?  'c & b ' . $bowlerName : 'c ' . $fielderName . ' b ' . $bowlerName;
				return $outString;
				break;
			case OutType::LBW:
				return 'lbw b '.$bowlerName;
				break;
			case OutType::RUNOUT:
				return 'run out ('.$fielderName.')';
				break;
			case OutType::STUMPED:
				return 'st '.$fielderName.' b '.$bowlerName;
				break;
			case OutType::HIT_WICKET:
				return 'hit-wicket b '.$bowlerName;
				break;
		}
	}

	private function __prepareDidNotBatData($matchId,$teamId,$matchBatsmen) {
		$excludeList = array();
		foreach ($matchBatsmen as $key => $batsman) {
			$excludeList[$key] = $batsman['user_id'];
		}
		$didNotBatPlayers = $this->Match->MatchPlayer->getDidNotBatPlayers($matchId,$teamId,$excludeList);
		return $didNotBatPlayers;
	}

	private function __prepareBowlersData($matchBowlers,$matchId) {
		$bowlersData = array();
		foreach ($matchBowlers as $key => $bowler) {
			$bowlersData[$key]['id'] = $bowler['user_id'];

			if (!empty($bowler['User']['Profile'])) {
        $bowlersData[$key]['name'] = $this->_prepareUserName($bowler['User']['Profile']['first_name'],$bowler['User']['Profile']['middle_name'],$bowler['User']['Profile']['last_name']);
      } else {
        $bowlersData[$key]['name'] = null;
      }

      $bowlersData[$key]['first_name'] = $bowler['User']['Profile']['first_name'];
			$bowlersData[$key]['middle_name'] = $bowler['User']['Profile']['middle_name'];
			$bowlersData[$key]['last_name'] = $bowler['User']['Profile']['last_name'];
			$bowlersData[$key]['image'] = !empty($bowler['User']['Profile']['ProfileImage']['url']) ? $bowler['User']['Profile']['ProfileImage']['url'] : null;

			$bowlersData[$key]['overs'] = $bowler['overs'];
			$bowlersData[$key]['dot_balls'] = $bowler['dot_balls'];
			$bowlersData[$key]['runs_conceded'] = $bowler['runs_conceded'];
			$bowlersData[$key]['wickets'] = $bowler['wickets'];
			$bowlersData[$key]['wides'] = $bowler['wides'];
			$bowlersData[$key]['no_balls'] = $bowler['no_balls'];
			$bowlersData[$key]['economy'] = $this->getBowlingEconomy($bowler['runs_conceded'],$bowler['overs']);
		}
		return $bowlersData;
	}

	public function getBowlingEconomy($runsConceded,$overs) {
		if (!empty($overs) && ($overs != 0)) {
			$quotient = ( ((int)$overs) * 10 ) / 10;
			$remainder = ($overs * 10) % 10;
			$balls = (6 * $quotient) + $remainder;
			return round(( ($runsConceded / $balls) * 6),1,PHP_ROUND_HALF_UP);
		}	else return 'NA';
	}

	public function prepareTossString($tossDecision,$tossTeam) {
		$tossString = '';
		if (!empty($tossDecision) && !empty($tossTeam)) {
			if ($tossDecision == TossDecision::BATTING) {
				$tossString = $tossTeam['name'].' won the toss and elected to Bat First';
			} else if ($tossDecision == TossDecision::BOWLING) {
				$tossString = $tossTeam['name'].' won the toss and elected to Bowl First';
			}
		}
		return $tossString;
	}

	public function prepareInningsRunRate($runs,$overs) {
		if (empty($overs) || $overs == 0) {
			return 'NA';
		} else if (empty($runs) || $runs == 0) {
			return 0.0;
		} else {
			$oversInBalls = ( ((((int)$overs) * 10) / 10) * 6) + (($overs * 10) % 10);
			return round( ( ($runs / $oversInBalls) * 6),1,PHP_ROUND_HALF_UP );
		}
	}

	public function prepareBatsmanStrikeRate($runs,$balls) {
		$strikeRate = null;
		if (!empty($balls) && ($balls != 0)) {
			if (empty($runs)) {
				$strikeRate = 0;
			} else {
				$strikeRate = round((100 * ($runs / $balls)),1,PHP_ROUND_HALF_UP);					
			}
		} else {
			$strikeRate = 'NA';
		}	
		return $strikeRate;
	}

}
  
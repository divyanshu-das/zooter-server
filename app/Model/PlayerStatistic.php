<?php
App::uses('AppModel', 'Model');
App::uses('Limit', 'Lib/Enum');
App::uses('PlayerSearchFilter', 'Lib/Enum');
App::uses('SpecialUsers', 'Lib/Enum');
/**
 * PlayerStatistic Model
 *
 * @property User $User
 */
class PlayerStatistic extends AppModel {

	public $validate = array(
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'is_cricket_ball_used' => array(
			'numeric' => array(
				'rule' => array('numeric')
			)
		),
		'match_scale' => array(
			'numeric' => array(
				'rule' => array('numeric')
			)
		),
		'match_type' => array(
			'numeric' => array(
				'rule' => array('numeric')
			)
		),
		'total_matches' => array(
			'numeric' => array(
				'rule' => array('numeric')
			)
		),
		'total_runs' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			)
		),
		'total_balls_faced' => array(
			'numeric' => array(
				'rule' => array('numeric')
			),
		),
		'total_fours_hit' => array(
			'numeric' => array(
				'rule' => array('numeric')
			)
		),
		'total_sixes_hit' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			)
		),
		'total_wickets_taken' => array(
			'numeric' => array(
				'rule' => array('numeric')
			),
		),
		'total_overs_bowled' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			)
		),
		'total_maidens_bowled' => array(
			'numeric' => array(
				'rule' => array('numeric')
			),
		),
		'total_runs_conceded' => array(
			'numeric' => array(
				'rule' => array('numeric')
			),
		),
		'total_wides_bowled' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			)
		),
		'total_no_balls_bowled' => array(
			'numeric' => array(
				'rule' => array('numeric')
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
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function updateOrAddPlayerCareerStatistics($playerCareerStatistics) {
		$this->validator()->add('user_id', 'required', array( 'rule' => 'notEmpty', 'required' => 'update' ));
		if (!empty($playerCareerStatistics)) {
			if ($this->saveMany($playerCareerStatistics)) {
				$response = array('status' => 200 , 'data' => '');
			} else {
				$response = array('status' => 318, 'message' => 'Player Career Statistics Could not be added or updated');
				pr($this->validationErrors);
			}
		} else {			
			$response = array('status' => 319, 'message' => 'No Data To Update or Add Player Career Statistics');			
		}
		return $response ;
	}

	public function getUserCareerHighlights($userId, $isHighlights) {
    $careerData = $this->find('all' , array(
    	'conditions' => array(
        'user_id' => $userId
    	),
    	'fields' => array(
    		'id','is_cricket_ball_used','total_matches','total_runs','highest_batting_score','total_balls_faced',
    		  'total_overs_bowled','total_wickets_taken','total_runs_conceded'
    	)
    ));
    $leatherBallStats = array();
    $tennisBallStats = array();
    $leatherBallStats = $this->getStatsData($careerData, true, $isHighlights);
    $tennisBallStats = $this->getStatsData($careerData, false, $isHighlights);
    
    return array('leather' => $leatherBallStats , 'tennis' => $tennisBallStats);
	}

	private function getStatsData($userData, $isCricketBallUsed, $isHighlights) {
		$totalMatches = 0;
		$totalRuns = 0;
		$highestBattingScore = 0;
		$totalBallsFaced = 0;
		$totalOversBowled = 0;
		$totalWicketsTaken = 0;
		$totalRunsConceded = 0;
		$bestBowling = 0;
		foreach($userData as $data) {
      if($data['PlayerStatistic']['is_cricket_ball_used'] == $isCricketBallUsed) {
      	if(!empty($data['PlayerStatistic']['total_matches'])) {
          $totalMatches = $totalMatches + $data['PlayerStatistic']['total_matches'];
        }
        if(!empty($data['PlayerStatistic']['total_runs'])) {
          $totalRuns = $totalRuns + $data['PlayerStatistic']['total_runs'];
        }
        if(!empty($data['PlayerStatistic']['highest_batting_score']) 
        	    && $data['PlayerStatistic']['highest_batting_score'] > $highestBattingScore) {
          $highestBattingScore = $data['PlayerStatistic']['highest_batting_score'];
        }
        if(!empty($data['PlayerStatistic']['total_wickets_taken'])) {
          $totalWicketsTaken = $totalWicketsTaken + $data['PlayerStatistic']['total_wickets_taken'];
        }
        if(!empty($data['PlayerStatistic']['best_bowling']) 
        	    && $data['PlayerStatistic']['best_bowling'] > $bestBowling) {
          $bestBowling = $data['PlayerStatistic']['best_bowling'];
        }
        if ($isHighlights == PlayerStatisticsType::FULL_CAREER) {
        	if(!empty($data['PlayerStatistic']['total_balls_faced'])) {
          	$totalBallsFaced = $totalBallsFaced + $data['PlayerStatistic']['total_balls_faced'];
        	}
        	if(!empty($data['PlayerStatistic']['total_overs_bowled'])) {
          	$totalOversBowled = $totalOversBowled + $data['PlayerStatistic']['total_overs_bowled'];
        	}        
        	if(!empty($data['PlayerStatistic']['total_runs_conceded'])) {
          	$totalRunsConceded = $totalRunsConceded + $data['PlayerStatistic']['total_runs_conceded'];
        	}
        }       
      }
    }

    $statsdata = array();
    $statsData['matches'] = $totalMatches;
    $statsData['runs'] = $totalRuns;
    $statsData['high_score'] = $highestBattingScore;
    $statsData['wickets'] = $totalWicketsTaken;
    $statsData['max_wickets'] = $bestBowling;

    if ($isHighlights == PlayerStatisticsType::FULL_CAREER) {
    	$battingAverage = $this->getBattingAverage($totalRuns , $totalMatches);
	    $battingStrikeRate = $this->getBattingStrikeRate($totalRuns , $totalBallsFaced);
	    $bowlingAverage = $this->getBowlongAverage($totalRunsConceded , $totalWicketsTaken);
	    $bowlingEconomy = $this->getBowlingEconomy($totalOversBowled , $totalRunsConceded);

	    $statsData['batting_average'] = $battingAverage;
	    $statsData['batting_strike_rate'] = $battingStrikeRate;
	    $statsData['bowling_average'] = $bowlingAverage;
	    $statsData['bowling_economy'] = $bowlingEconomy;
    }
 
    return $statsData;
	}

  public function playerSearchPublic($filters) {
    $responseData = array();
    $options = array(
      'joins' => array(
        array(
          'table' => 'profiles',
          'alias' => 'ProfileJoin',
          'type' => 'left',
          'conditions' => array(
            'ProfileJoin.user_id = PlayerStatistic.user_id'
           )
        )
      ),
      'fields' => array('PlayerStatistic.user_id','PlayerStatistic.total_matches','PlayerStatistic.is_cricket_ball_used',
      									'PlayerStatistic.total_runs','PlayerStatistic.total_wickets_taken',
      									'ProfileJoin.first_name','ProfileJoin.middle_name','ProfileJoin.last_name',
      									'ProfileJoin.location_id','ProfileJoin.image_id'),
      'limit' => Limit::NUM_OF_PLAYERS_IN_PLAYER_SEARCH,
      'order' => 'PlayerStatistic.total_runs DESC'
    );
    foreach ($filters as $key => $value) {
      if (!empty($value)) {
        switch ($key) {
          case PlayerSearchFilter::stringValue(PlayerSearchFilter::FIRST_LETTER):
            $value = $value[0];
            $options['conditions']['ProfileJoin.first_name LIKE'] = "$value%";
            break;
          case PlayerSearchFilter::stringValue(PlayerSearchFilter::TEXT):
            $options['conditions']['OR'] = array(
              'ProfileJoin.first_name LIKE' => "%$value%",
              'ProfileJoin.middle_name LIKE' => "%$value%",
              'ProfileJoin.last_name LIKE' => "%$value%"
            );
            break;
          case PlayerSearchFilter::stringValue(PlayerSearchFilter::MINIMUM_MATCHES):
            $options['conditions']['PlayerStatistic.total_matches >='] = $value;
            break;
          case PlayerSearchFilter::stringValue(PlayerSearchFilter::MAXIMUM_MATCHES):
            $options['conditions']['PlayerStatistic.total_matches <='] = $value;
            break;
          case PlayerSearchFilter::stringValue(PlayerSearchFilter::LEATHER):
            if (empty($filters['tennis'])) {
              $options['conditions']['PlayerStatistic.is_cricket_ball_used'] = true;              
            }
            break;
          case PlayerSearchFilter::stringValue(PlayerSearchFilter::TENNIS):
            if (empty($filters['leather'])) {
              $options['conditions']['PlayerStatistic.is_cricket_ball_used'] = false;
            }
            break;
          case PlayerSearchFilter::stringValue(PlayerSearchFilter::LOCATION):
            $nearBylocations = $this->User->Profile->Location->getNearbyLocation($value['latitude'], $value['longitude'], Limit::TEAM_SEARCH_DISTANCE);
            $locationIds = array();
            if ($nearBylocations['status'] == 200) {
              if (!empty($nearBylocations['data'])) {
                $locations = $nearBylocations['data'];
                foreach ($locations as $key => $value) {
                  $locationIds[$key] = $value['id'];
                }
                $options['conditions']['ProfileJoin.location_id'] = $locationIds;
              } else {
                return array('status' => 200, 'data' => array('players' => array()),'message' => 'playerSearchPublic : no players for this location');
              }
            } else {
              return array('status' => $nearBylocations['status'] , 'message' => $nearBylocations['message']);
            }
            break;
        }       
      }
    }
    $players = $this->find('all',$options);
  
    foreach ($players as $key => $player) {
    	$responseData[$key]['id'] = $player['PlayerStatistic']['user_id'];
      $responseData[$key]['first_name'] = $player['ProfileJoin']['first_name'];
      $responseData[$key]['middle_name'] = $player['ProfileJoin']['middle_name'];
      $responseData[$key]['last_name'] = $player['ProfileJoin']['last_name'];
    	$responseData[$key]['name'] = $this->_prepareUserName($player['ProfileJoin']['first_name'],
    																												$player['ProfileJoin']['middle_name'],
    																												$player['ProfileJoin']['last_name']);
    	$image = $this->User->Profile->ProfileImage->findById($player['ProfileJoin']['image_id']);
    	if (!empty($image['ProfileImage'])) {
    		$responseData[$key]['image'] = $image['ProfileImage']['url'];
    	} else {
    		$responseData[$key]['image'] = null;
    	}
    	$location = $this->User->Profile->Location->find('first',array(
    		'conditions' => array(
    			'Location.id' => $player['ProfileJoin']['location_id']
    		),
    		'contain' => array('City')
    	));
    	if (!empty($location['Location'])) {
    		$responseData[$key]['location']['id'] = $location['Location']['id'];
    		$responseData[$key]['location']['name'] = $location['Location']['name'];
    		if (!empty($location['Location']['City'])) {
    			$responseData[$key]['location']['city'] = $location['Location']['City']['name'];
    		} else {
    			$responseData[$key]['location']['city'] = null;
    		}
    	} else {
    		$responseData[$key]['location']['id'] = null;
    		$responseData[$key]['location']['name'] = null;
    		$responseData[$key]['location']['city'] = null;
    	}
    	$responseData[$key]['runs'] = $player['PlayerStatistic']['total_runs'];
    	$responseData[$key]['matches'] = $player['PlayerStatistic']['total_matches'];
    	$responseData[$key]['wickets'] = $player['PlayerStatistic']['total_wickets_taken'];
    }
    return array('status' => 200, 'data' => array('players' => $responseData),'message' => 'success');
  }

  public function playerSearchForUser($userId,$filters) {
    $userId = trim($userId);
    if (!empty($userId)) {
      if (!$this->_userExists($userId)) {
        return array('status' => 103 , 'message' => 'playerSearchForUser : Invalid User ID');
      }
    }
    return $this->playerSearchPublic($filters);
    //$responseData = array();   
    //return array('status' => 200, 'data' => array('players' => $responseData),'message' => 'success');
  }

	private function getBattingAverage($totalRuns , $totalMatches) {
		$average = 'NA';
    if($totalMatches == 0) {
    	return $average;
    }
    else {
    	$average = ($totalRuns / $totalMatches);
    	$average = round($average);
    }
    return $average;
	}

	private function getBattingStrikeRate($totalRuns , $totalBallsFaced) {
		$strikeRate = 'NA';
    if($totalBallsFaced == 0) {
    	return $strikeRate;
    }
    else {
    	$strikeRate = (100 * ($totalRuns / $totalBallsFaced));
    	$strikeRate = round($strikeRate, 1, PHP_ROUND_HALF_UP);
    }
    return $strikeRate;
	}

	private function getBowlongAverage($totalRunsConceded , $totalWicketsTaken) {
		$average = 'NA';
    if($totalWicketsTaken == 0) {
    	return $average;
    }
    else {
    	$average = ($totalRunsConceded / $totalWicketsTaken);
    	$average = round($average, 1, PHP_ROUND_HALF_UP);
    }
    return $average;
	}

	private function getBowlingEconomy($totalOversBowled , $totalRunsConceded) {
		$economy = 'NA';
    if($totalOversBowled == 0) {
    	return $economy;
    }
    else {
    	$economy = ($totalRunsConceded / $totalOversBowled);
    	$economy = round($economy , 1, PHP_ROUND_HALF_UP);
    }
    return $economy;
	}
	

}




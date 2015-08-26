<?php
App::uses('AppModel', 'Model');
App::uses('DeliveryType', 'Lib/Enum');
/**
 * MatchBallScore Model
 *
 * @property MatchInningScorecard $MatchInningScorecard
 */
class MatchBallScore extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'MatchInningScorecard' => array(
			'className' => 'MatchInningScorecard',
			'foreignKey' => 'match_inning_scorecard_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Bowler' => array(
			'className' => 'User',
			'foreignKey' => 'bowler_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Fielder' => array(
			'className' => 'User',
			'foreignKey' => 'out_other_by_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Striker' => array(
			'className' => 'User',
			'foreignKey' => 'striker_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'NonStriker' => array(
			'className' => 'User',
			'foreignKey' => 'non_striker_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'OutBatsman' => array(
			'className' => 'User',
			'foreignKey' => 'out_batsman_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);


	public function getOversData($matchInningScoreId) {
		$oversData = array();
		$overBalls = $this->find('all',array(
			'conditions' => array(
				'match_inning_scorecard_id' => $matchInningScoreId,
				'ball_type !=' => null,
				'bowler_id !=' => null
			),
			'fields' => array('id','overs','over_balls','runs_taken_by_batsman','ball_type','no_balls','wides','leg_byes','byes','is_out','is_retired_hurt','is_four','is_six'),
			'order' => 'MatchBallScore.created DESC'
		));
		$index = -1;
		foreach ($overBalls as $ball) {
			$ball = $ball['MatchBallScore'];
			$data = '';
			switch ($ball['ball_type']) {
				case DeliveryType::DOT_BALL:
					if ($ball['is_out'] ==  true) {
						$data = 'W';
					}	else {
						$data = '.';
					}	
					break;
				case DeliveryType::BALL_WITH_RUN_WITHOUT_EXTRA:
					$data = $ball['runs_taken_by_batsman'].'';
					if ($ball['is_out'] == true) {
						$data = $data.',W';
					}
					break;
				case DeliveryType::NO_BALL:
					$data = 'NB';
					if ( !empty($ball['runs_taken_by_batsman']) ) {
						$data = $data.','.$ball['runs_taken_by_batsman'];
					} else if (!empty($ball['leg_byes'])) {
						$data = $data.','.$ball['leg_byes'].'LB';
					} else if (!empty($ball['byes'])) {
						$data = $data.','.$ball['byes'].'B';
					}
					if ($ball['is_out'] == true) {
						$data = $data.',W';
					}
					break;
				case DeliveryType::WIDE_BALL:
				$data = $ball['wides'].'WD';
					if ($ball['is_out'] == true) {
						$data = $data.',W';
					}
					break;
				case DeliveryType::LEG_BYES:
				$data = $ball['leg_byes'].'LB';
					if ($ball['is_out'] == true) {
						$data = $data.',W';
					}
					break;
				case DeliveryType::BYES:
				$data = $ball['byes'].'B';
					if ($ball['is_out'] == true) {
						$data = $data.',W';
					}
					break;
			}
			if ($ball['overs'] != $index) {
				$overs = $ball['overs']+1;
				array_push($oversData, array('over' => 'OVER '.$overs));
				$index = $ball['overs'];
			}
			$ballData = array();
			$ballData['data'] = $data;
			if ($ball['is_out'] == true) {
				$ballData['is_wicket'] = true;
			}
			if ( $ball['is_four'] == true || $ball['is_six'] == true) {
				$ballData['is_boundary'] = true;
			}
			array_push($oversData, array('ball' => $ballData));
		}
		return $oversData;
	}

	public function prepareFallOfWickets($matchInningScorecardId) {
		$fallOfwickets = array();
		$wicketBalls = $this->find('all',array(
			'conditions' => array(
				'MatchBallScore.match_inning_scorecard_id' => $matchInningScorecardId,
				'MatchBallScore.is_out' => true
			),
			'contain' => array(
				'OutBatsman' => array(
					'fields' => array('id'),
					'Profile' => array(
						'fields' => array('id','user_id','first_name','middle_name','last_name','image_id'),
						'ProfileImage' => array(
              'fields' => array('id','url')
            )
					)
				)
			),
			'order' => 'MatchBallScore.id ASC'
		));
		foreach ($wicketBalls as $key => $ball) {
			$fallOfwickets[$key]['wicket_number'] = $key+1;
			$fallOfwickets[$key]['overs'] = $this->__findTeamOversTillThisBall(
				$ball['MatchBallScore']['id'],
				$ball['MatchBallScore']['match_inning_scorecard_id'],
				$ball['MatchBallScore']['overs']
			);
			$fallOfwickets[$key]['team_total_runs'] = $this->__findTeamTotalRunsTillThisBall(
				$ball['MatchBallScore']['id'],
				$ball['MatchBallScore']['match_inning_scorecard_id']
			);
			$fallOfwickets[$key]['batsman']['id'] = $ball['OutBatsman']['id'];

			if (!empty($ball['OutBatsman']['Profile'])) {
        $fallOfwickets[$key]['batsman']['name'] = $this->_prepareUserName($ball['OutBatsman']['Profile']['first_name'],$ball['OutBatsman']['Profile']['middle_name'],$ball['OutBatsman']['Profile']['last_name']);
      } else {
        $fallOfwickets[$key]['batsman']['name'] =  null;
      }

      $fallOfwickets[$key]['batsman']['first_name'] = !empty($ball['OutBatsman']['Profile']['first_name']) ? $ball['OutBatsman']['Profile']['first_name'] : null;
      $fallOfwickets[$key]['batsman']['middle_name'] = !empty($ball['OutBatsman']['Profile']['middle_name']) ? $ball['OutBatsman']['Profile']['middle_name'] : null;
      $fallOfwickets[$key]['batsman']['last_name'] = !empty($ball['OutBatsman']['Profile']['last_name']) ? $ball['OutBatsman']['Profile']['last_name'] : null;
      $fallOfwickets[$key]['batsman']['image'] = !empty($ball['OutBatsman']['Profile']['ProfileImage']['url']) ? $ball['OutBatsman']['Profile']['ProfileImage']['url'] : null;
		}
		return $fallOfwickets;
	}

	private function __findTeamOversTillThisBall($ballId,$matchInningScorecardId,$overs) {
		$deliveryTypes = [DeliveryType::DOT_BALL,DeliveryType::BALL_WITH_RUN_WITHOUT_EXTRA,DeliveryType::BYES,DeliveryType::LEG_BYES];
		$ballCount = $this->find('count',array(
			'conditions' => array(
				'match_inning_scorecard_id' => $matchInningScorecardId,
				'id <=' => $ballId,
				'overs' => $overs,
				'ball_type' => $deliveryTypes
			)
		));
		if ($ballCount == 6) {
			return ($overs+1).'.0';
		} else {
			return ($overs).'.'.$ballCount;
		}		
	}

	private function __findTeamTotalRunsTillThisBall($ballId,$matchInningScorecardId) {
		$data = $this->find('all',array(
			'conditions' => array(
				'match_inning_scorecard_id' => $matchInningScorecardId,
				'id <=' => $ballId,
			),
			'fields' => array('SUM(runs) as total')
		));
		return $data[0][0]['total'];
	}

}

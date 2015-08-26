<?php
App::uses('AppModel', 'Model');
App::uses('Limit', 'Lib/Enum');
App::uses('BatsmanStatus', 'Lib/Enum');
/**
 * MatchBatsmanScore Model
 *
 * @property User $User
 */
class MatchBatsmanScore extends AppModel {


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
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'MatchBatsmanBowler' => array(
			'className' => 'User',
			'foreignKey' => 'out_by_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'MatchBatsmanFielder' => array(
			'className' => 'User',
			'foreignKey' => 'out_other_by_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function getCurrentBattingPair($matchInningScorecardId) {
		return $this->find('all',array(
			'conditions' => array(
				'match_inning_scorecard_id' => $matchInningScorecardId,
				'status' => [BatsmanStatus::STRIKER,BatsmanStatus::NON_STRIKER,BatsmanStatus::NOT_OUT]
			),
			'contain' => array(
				'User' => array(
					'fields' => array('id'),
					'Profile' => array(
						'fields' => array('id','first_name','middle_name','last_name','image_id'),
						'ProfileImage' => array(
              'fields' => array('url')
            )
					)
				)
			),
			'order' => 'MatchBatsmanScore.modified DESC',
			'limit' => 2
		));
	}

	public function checkIfAnyBatsmenPairLeftToPlay($matchInningScorecardId,$playersPerSide) {
		$count = $this->find('count',array(
			'conditions' => array(
				'match_inning_scorecard_id' => $matchInningScorecardId,
				'status' => BatsmanStatus::OUT
			)
		));
		if ( $count < ($playersPerSide-1) ) {
			return true;
		}
		return false;
	}

	public function getTrendingBatsmenPublic() {
		$responseData = array();
		$batsmen = $this->find('all',array(
			'conditions' => array(
				'MatchBatsmanScore.created >=' => date('Y-m-d', strtotime('-50 days'))
			),
			'fields' => array('user_id','SUM(MatchBatsmanScore.runs) as total_runs','COUNT(*) as matches_played'),
			'group' => 'MatchBatsmanScore.user_id',
			'contain' => array(
				'User' => array(
					'fields' => array('id'),
					'Profile' => array(
						'fields' => array('id','first_name','middle_name','last_name','image_id'),
						'ProfileImage' => array(
              'fields' => array('url')
            )
					)
				)
			),
			'order' => 'total_runs DESC',
			'limit' => Limit::NUM_OF_TRENDING_IN_MATCH_CORNER_LANDING
		));

		foreach ($batsmen as $key => $batsman) {
			$responseData[$key]['id'] = $batsman['User']['id'];

			if (!empty($batsman['User']['Profile'])) {
				$responseData[$key]['name'] = $this->_prepareUserName($batsman['User']['Profile']['first_name'],
																														$batsman['User']['Profile']['middle_name'],
																														$batsman['User']['Profile']['last_name']);
			} else {
				$responseData[$key]['name'] = null;
			}

			if (!empty($batsman['User']['Profile']['ProfileImage'])) {
        $responseData[$key]['image'] = $batsman['User']['Profile']['ProfileImage']['url'];
      }
      else {
        $responseData[$key]['image'] = NULL;
      }
      
			$responseData[$key]['matches'] = $batsman[0]['matches_played'];
			$responseData[$key]['runs'] = $batsman[0]['total_runs'];
		}
		return $responseData;
	}

}

<?php
App::uses('AppModel', 'Model');
/**
 * MatchBowlerScore Model
 *
 * @property User $User
 */
class MatchBowlerScore extends AppModel {


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
	);

	public function getCurrentBowlingPair($matchInningScorecardId) {
		return $this->find('all',array(
			'conditions' => array(
				'match_inning_scorecard_id' => $matchInningScorecardId,
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
			'order' => 'MatchBowlerScore.modified DESC',
			'limit' => 2
		));
	}

	public function getTrendingBowlersPublic() {
		$responseData = array();
		$bowlers = $this->find('all',array(
			'conditions' => array(
				'MatchBowlerScore.created >=' => date('Y-m-d', strtotime('-50 days'))
			),
			'fields' => array('user_id','SUM(MatchBowlerScore.wickets) as total_wickets','COUNT(*) as matches_played'),
			'group' => 'MatchBowlerScore.user_id',
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
			'order' => 'total_wickets DESC',
			'limit' => Limit::NUM_OF_TRENDING_IN_MATCH_CORNER_LANDING
		));

		foreach ($bowlers as $key => $bowler) {
			$responseData[$key]['id'] = $bowler['User']['id'];
			if (!empty($bowler['User']['Profile'])) {
				$responseData[$key]['name'] = $this->_prepareUserName($bowler['User']['Profile']['first_name'],
																														$bowler['User']['Profile']['middle_name'],
																														$bowler['User']['Profile']['last_name']);
			} else {
				$responseData[$key]['name'] = null;
			}

			if (!empty($bowler['User']['Profile']['ProfileImage'])) {
        $responseData[$key]['image'] = $bowler['User']['Profile']['ProfileImage']['url'];
      }
      else {
        $responseData[$key]['image'] = NULL;
      }
      
			$responseData[$key]['matches'] = $bowler[0]['matches_played'];
			$responseData[$key]['wickets'] = $bowler[0]['total_wickets'];
		}
		return $responseData;
	}


}

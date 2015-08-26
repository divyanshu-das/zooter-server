<?php
App::uses('AppModel', 'Model');
/**
 * MatchToss Model
 *
 * @property Match $Match
 */
class MatchToss extends AppModel {


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
		'winning_team_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'winning_team_id is not numeric',
				'required' => true,
				'allowEmpty' => false
			),
			'teamExist' => array(
				'rule' => array('teamExist')
			)
		),
		'toss_decision' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'required' => true,
				'allowEmpty' => false,
				'message' => 'toss_decision value is not valid'
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
			'foreignKey' => 'winning_team_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function showMatchToss($id) {
		$match_toss = $this->findById($id);
		if ( ! empty($match_toss)) {
			$response = array('status' => 200, 'data' => $match_toss);
		} else {
			$response = array('status' => 302, 'message' => 'Match Toss Data Could Not Be Retrieved');
		}
		return $response;
	}

	public function deleteMatchToss($id) {
		$matchToss = $this->showMatchToss($id);
		if ($matchToss['status'] != 200 ){
			$response = array('status' => 905, 'message' => 'Match Toss does not exist');
			return $response;
		}
		$this->_updateCache($id);
		$delete = $this->softDelete($id);
		$response = array('status' => 200, 'data'=> $delete , 'message' => 'Deleted');
		return $response;
	}

	public function createMatchToss($match_id, $winning_team_id, $toss_decision) {
		$match = $this->Match->showMatch($match_id);
		if ($match['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Match does not exist');
			return $response;
		}
		$matchToss = $this->findByMatchId($match_id);
		if ( ! empty($matchToss)) {
			$response = array('status' => 312, 'message' => 'Match Toss already created');
			return $response;
		}
		$matchTossData = array(
			'MatchToss' => array(
				'match_id' => $match_id,
				'winning_team_id' => $winning_team_id,
				'toss_decision' => $toss_decision,
			)
		);
		$this->_updateCache(null,$match_id,$winning_team_id);
		if ($this->save($matchTossData)) {
				$response = array('status' => 200 , 'message' => 'Match Toss Created');
			} else {
				$response = array('status' => 312, 'message' => 'Match Toss Could not be added');
				pr($this->validationErrors);
			}
		return $response ;
	}

	public function updateMatchToss($id, $match_id, $winning_team_id, $toss_decision) {
		$match = $this->Match->showMatch($match_id);
		if ($match['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Match does not exist');
			return $response;
		}
		$this->validateId();
		$this->_updateCache($id);
		$matchTossData = array(
			'MatchToss' => array(
				'id' => $id,
				'match_id' => $match_id,
				'winning_team_id' => $winning_team_id,
				'toss_decision' => $toss_decision,
			)
		);
			if ($this->save($matchTossData)) {
				$response = array('status' => 200 , 'message' => 'Match Toss Updated');
				$this->_updateCache(null, $match_id, $winning_team_id);
			} else {
				$response = array('status' => 312, 'message' => 'Match Toss Could not be added');
				pr($this->validationErrors);
			}
		return $response ;
	}

	private function _updateCache($id = null, $match_id = null, $winning_team_id = null) {
		if ( ! empty($id)) {
			$matchToss = $this->showMatchToss($id);
			if ( ! empty($matchToss['data']['MatchToss']['match_id'])) {
				Cache::delete('show_match_' . $matchToss['data']['MatchToss']['match_id']);
			}
			if ( ! empty($matchToss['data']['MatchToss']['winning_team_id'])) {
				Cache::delete('show_team_' . $matchToss['data']['MatchToss']['winning_team_id']);
			}
		}
  	if ( ! empty($match_id)) {
  		Cache::delete('show_match_' . $match_id);
  	}
  	if ( ! empty($winning_team_id)) {
  		Cache::delete('show_team_'.$winning_team_id);	
  	}
  }
}

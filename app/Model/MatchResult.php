<?php
App::uses('AppModel', 'Model');
/**
 * MatchResult Model
 *
 * @property Match $Match
 */
class MatchResult extends AppModel {


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
		'result_type' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'required' => true,
				'allowEmpty' => false,
				'message' => 'result_type value is not valid'
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

	public function showMatchResult($id) {
		$match_result = $this->findById($id);
		if ( ! empty($match_result)) {
			$response = array('status' => 200, 'data' => $match_result);
		} else {
			$response = array('status' => 302, 'message' => 'Match Result Data Could Not Be Retrieved');
		}
		return $response;
	}

	public function deleteMatchResult($id) {
		$matchResult = $this->showMatchResult($id);
		if ($matchResult['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Match Result does not exist');
			return $response;
		}
		$this->_updateCache($id);
		$delete = $this->softDelete($id);
		$response = array('status' => 200, 'data'=> $delete , 'message' => 'Deleted');
		return $response;
	}

	public function createMatchResult($match_id, $winning_team_id, $result_type) {
		$match = $this->Match->showMatch($match_id);
		if ( $match['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Match does not exist');
			return $response;
		}
		$matchResult = $this->findByMatchId($match_id);
		if ( ! empty($matchResult)){
			$response = array('status' => 312, 'message' => 'Match Result already created');
			return $response;
		}

		$matchResultData = array(
			'MatchResult' => array(
				'match_id' => $match_id,
				'winning_team_id' => $winning_team_id,
				'result_type' => $result_type,
			)
		);
		$this->_updateCache(null,$match_id,$winning_team_id);
		if ($this->save($matchResultData)) {
				$response = array('status' => 200 , 'message' => 'Match Result Created');
			} else {
				$response = array('status' => 312, 'message' => 'Match Result Could not be added');
				pr($this->validationErrors);
			}
		return $response ;
	}

	public function updateMatchResult($id, $match_id, $winning_team_id, $result_type) {
		$match = $this->Match->showMatch($match_id);
		if ($match['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Match does not exist');
			return $response;
		}
		$this->validateId();
		$this->_updateCache($id);
		$matchResultData = array(
			'MatchResult' => array(
				'id' => $id,
				'match_id' => $match_id,
				'winning_team_id' => $winning_team_id,
				'result_type' => $result_type,
			)
		);
			if ($this->save($matchResultData)) {
				$response = array('status' => 200 , 'message' => 'Match Result Updated');
				$this->_updateCache(null, $match_id, $winning_team_id);
			} else {
				$response = array('status' => 312, 'message' => 'Match Result Could not be added');
				pr($this->validationErrors);
			}
		return $response ;
	}

	private function _updateCache($id = null, $match_id = null, $winning_team_id = null) {
		if ( ! empty($id)) {
			$matchResult = $this->showMatchResult($id);
			if ( ! empty($matchResult['data']['MatchResult']['match_id'])){
				Cache::delete('show_match_' . $matchResult['data']['MatchResult']['match_id']);
			}
			if (!empty($matchResult['data']['MatchResult']['winning_team_id'])){
				Cache::delete('show_team_' . $matchResult['data']['MatchResult']['winning_team_id']);
			}
		}
  	if ( ! empty($match_id))
  		Cache::delete('show_match_' . $match_id);
  	if ( ! empty($winning_team_id))
  		Cache::delete('show_team_' . $winning_team_id);	
  }
}

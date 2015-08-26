<?php
App::uses('AppModel', 'Model');
/**
 * MatchAward Model
 *
 * @property Match $Match
 * @property User $User
 * @property AwardType $AwardType
 */
class MatchAward extends AppModel {

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
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'User id is not numeric',
				'allowEmpty' => true
			),
			'userExist' => array(
				'rule' => array('userExist'),
				'allowEmpty'=> true
			)
		),
		'award_type_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Award type Id is not numeric'
			)
		),
		'value' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'value is not numeric'
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
		),
		'AwardType' => array(
			'className' => 'AwardType',
			'foreignKey' => 'award_type_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function showMatchAward($id) {
		$match_award = $this->findById($id);
		if ( ! empty($match_award)) {
			$response = array('status' => 200, 'data' => $match_award);
		} else {
			$response = array('status' => 302, 'message' => 'Match Award Data Could Not Be Retrieved');
		}
		return $response;
	}

	public function deleteMatchAward($id) {
		$matchAward = $this->showMatchAward($id);
		if ( $matchAward['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Match Award does not exist');
			return $response;
		}
		$this->_updateCache($id,null);
		$delete = $this->softDelete($id);
		$response = array('status' => 200, 'data'=> $delete , 'message' => 'Deleted');
		return $response;
	}


	public function updateMatchAwards($matchId, $match_awards) {
		$match = $this->Match->showMatch($matchId);
		if ($match['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Match does not exist');
			return $response;
		}
		$this->validateId();
		$this->_updateCache(null,$matchId);
		$award_names = array();
	 	foreach ($match_awards as $match_award) {
	 		$award_names[] = $match_award['award_name'];
	 	}
		$existing_award = $this->AwardType->find('list',array(
			'conditions' => array('AwardType.award_name' => $award_names),
			'fields' => array('AwardType.award_name','AwardType.id')
		));	
		$awards = [];
			foreach($match_awards as $match_award){
				if ( ! array_key_exists('id',$match_award) ) {
					$match_award['id'] = "";
				}
				if ( array_key_exists($match_award['award_name'], $existing_award )) {
					if ( isset($match_award['deleted']) && $match_award['deleted']== true) {
						$awards[] = array('id' => $match_award['id'], 'match_id' => $matchId, 'user_id'=> $match_award['user_id'], 'value'=> $match_award['value'], 'award_type_id' => $existing_award[$match_award['award_name']], 'deleted'=> true, 'deleted_date'=> date('Y-m-d H:i:s') );
					}	else {
						$awards[] = array('id' => $match_award['id'], 'match_id' => $matchId, 'user_id'=> $match_award['user_id'], 'value'=> $match_award['value'], 'award_type_id' => $existing_award[$match_award['award_name']]);
					}
				} else {
					if(isset($match_award['deleted']) && $match_award['deleted']== true) {
						$awards[] = array('id' => $match_award['id'], 'match_id' => $matchId, 'user_id'=> $match_award['user_id'],'value'=> $match_award['value'], 'AwardType' => array('award_name' => $match_award['award_name']), 'deleted'=> true, 'deleted_date'=> date('Y-m-d H:i:s'));
					}	else {
						$awards[] = array('id' => $match_award['id'], 'match_id' => $matchId, 'user_id'=> $match_award['user_id'],'value'=> $match_award['value'], 'AwardType' => array('award_name' => $match_award['award_name'] ));
					}
				}
			}
		if ( ! empty($awards)) {
			if ($this->saveMany($awards, array('deep' => true))) {
				$response = array('status' => 200 , 'message' => 'Match Updated','data' => '');
			} else {
				$response = array('status' => 308, 'message' => 'Updating Match Awards Unsuccessfull' , 'data' => $this->validationErrors);
			}
		} else {			
			$response = array('status' => 309, 'message' => 'No Data To Modify Match Awards');			
		}
		return $response ;
	}

	private function _updateCache($id = null,$match_id = null) {
		if ( ! empty($id)) {
			$matchAward = $this->showMatchAward($id);
			if ( ! empty($matchAward['data']['MatchAward']['match_id'])) {
				Cache::delete('show_match_' . $matchAward['data']['MatchAward']['match_id']);
			}
		}
		if ( ! empty($match_id))
			Cache::delete('show_match_' . $match_id);
  }
}

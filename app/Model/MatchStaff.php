<?php
App::uses('AppModel', 'Model');
/**
 * MatchStaff Model
 *
 * @property Match $Match
 * @property User $User
 */
class MatchStaff extends AppModel {

	public $validate = array(
		'match_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Match id is not numeric',
				// 'required' => true,
				// 'allowEmpty' => false,
				// 'on' => 'create'
			),
			// 'matchExist' => array(
			// 	'rule' => array('matchExist'),
			// 	'on' => 'create',
			// )
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'User id is not numeric',
				// 'required' => true,
				// 'allowEmpty' => false,
				// 'on' => 'create'
			),
			// 'userExist' => array(
			// 	'rule' => array('userExist'),
			// 	'on' => 'create'
			// )
		),
		'status' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Status is not valid',
				// 'required' => true,
				// 'allowEmpty' => false,
			)
		),
		'role' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Status is not valid',
				// 'on' => 'create'
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


	public function showMatchStaff($id) {
		$match_staff = $this->findById($id);
		if ( ! empty($match_staff)) {
			$response = array('status' => 200, 'data' => $match_staff);
		} else {
			$response = array('status' => 302, 'message' => 'Match Staff Data Could Not Be Retrieved');
		}
		return $response;
	}

	public function deleteMatchStaff($id) {
		$matchStaff = $this->showMatchStaff($id);
		if ( $matchStaff['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Match Staff does not exist');
			return $response;
		}
		$this->_updateCache($id,null);
		$delete = $this->softDelete($id);
		$response = array('status' => 200, 'data'=> $delete , 'message' => 'Deleted');
		return $response;
	}


	public function updateMatchStaffs($matchStaffs , $matchId) {
		$match = $this->Match->showMatch($matchId);
		if ($match['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Match does not exist');
			return $response;
		}
		$this->validateId();
		$this->_updateCache(null, $matchStaffs,$matchId);
		if ( ! empty($matchStaffs)) {
			if ($this->saveMany($matchStaffs)) {
				$response = array('status' => 200 , 'message' => ' Match Staffs Updated');
			} else {
				$response = array('status' => 312, 'message' => 'Match Staffs Could not be added or updated');
				pr($this->validationErrors);
			}
		} else {			
			$response = array('status' => 313, 'message' => 'No Data To Update or Add Match Staffs and Roles');			
		}
		return $response ;
	}

	private function _updateCache($id = null,$matchStaffs = null, $matchId = null) {
		if ( ! empty($id)) {
			$matchStaff = $this->showMatchStaff($id);
			if ( ! empty($matchStaff['data']['MatchStaff']['match_id'])){
				Cache::delete('show_match_' . $matchStaff['data']['MatchStaff']['match_id']);
				//Cache::delete('show_user_'.$matchStaff['data']['MatchStaff']['user_id']);  *** use when show_user_1 cache exists ***
			}
		}
		if ( ! empty($matchId)) {
			//  $match = $this->Match->showMatch($matchId); 							*** use when show_user_1 cache exists ***
			Cache::delete('show_match_' . $matchId);
			//  foreach ($match['data']['MatchStaff'] as $matchstaff) {				*** use when show_user_1 cache exists ***
			//  Cache::delete('show_user_'.$matchstaff['user_id']);							*** use when show_user_1 cache exists ***
			//  }
		}
		//  if(!empty($matchStaffs)){
		//	foreach ($matchStaffs as $matchStaff) {
		//		Cache::delete('show_user_'.$matchStaff['user_id']);
		//	}
		// }
  }


	public function getMatchStaffRequest($id) {
		$request_name = array();
  	if (!empty($id)) {
      $data = $this->find('first' ,array(
      	'conditions' => array(
      		'MatchStaff.id' => $id
      	),
        'fields' => array('match_id','role'),
        'contain' => array(
        	'Match' => array(
        		'fields' => array('id','start_date_time','name')
        	)
        )
      ));
      if (!empty($data)) {
        $request_name['role'] = $data['MatchStaff']['role'];
        $request_name['match']['id'] = $data['Match']['id'];
         $request_name['match']['name'] = $data['Match']['name'];
        $request_name['match']['start_date_time'] = $data['Match']['start_date_time'];
      }
  	}
  	return $request_name;
	}

	public function handleMatchStaffRequest($requestId,$userId,$status) {
    if (!empty($requestId) && !empty($userId) && !empty($status)) {
      if ($this->isUserEligbleForRequest($requestId,$userId)) {
        $data = array(
          'MatchStaff' => array(
            'id' => $requestId,
            'status' => $status
          )
        );
        if ($this->save($data)) {
          $response =  array('status' => 200 , 'message' => 'success');
        } 
        else {
          $response =  array('status' => 125, 'message' => 'handleMatchStaffRequest : Match Staff Request Accept Could not be updated');
        } 
      }
      else {
        $response =  array('status' => 124, 'message' => 'handleMatchStaffRequest : User Not Eligible to Accept Match Staff Request');
      }
    }
    else {
      $response =  array('status' => 123, 'message' => 'handleMatchStaffRequest : Invalid Input Arguments');
    }
    return $response;
  }

  public function isUserEligbleForRequest($requestId,$userId) {
    return $this->find('count',array(
      'conditions' => array(
        'MatchStaff.id' => $requestId,
        'MatchStaff.user_id' => $userId,
        'MatchStaff.status' => InvitationStatus::INVITED
      )
    ));
  }

}

<?php
App::uses('AppModel', 'Model');
/**
 * TeamStaff Model
 *
 * @property Team $Team
 * @property User $User
 */
class TeamStaff extends AppModel {

	public $validate = array(
		'team_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Team id is not valid',
				// 'required' => true,
				// 'allowEmpty' => false,
				// 'on' => 'create'
			),
			// 'teamExist' => array(
			// 	'rule' => array('teamExist'),
			// 	'on' => 'create'
			// )
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'User id is not valid',
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
			'numeric one' => array(
				'rule' => array('numeric'),
				'message' => 'status is not valid',
				// 'required' => true,
				// 'allowEmpty' => false,
				// 'on' => 'create'
			),
			// 'numeric one' => array(
			// 	'rule' => array('numeric'),
			// 	'message' => 'status is not valid',
			// 	'required' => false,
			// 	'allowEmpty' => false,
			// 	'on' => 'update'
			// ),
		),
		'role' => array(
			'numeric one' => array(
				'rule' => array('numeric'),
				'message' => 'role is not valid',
				// 'required' => true,
				// 'allowEmpty' => false,
				// 'on' => 'create'
			),
			// 'numeric two' => array(
			// 	'rule' => array('numeric'),
			// 	'message' => 'role is not valid',
			// 	'required' => false,
			// 	'allowEmpty' => false,
			// 	'on' => 'update'
			// ),
		)
	);


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Team' => array(
			'className' => 'Team',
			'foreignKey' => 'team_id',
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

	public function showTeamStaff($id) {
		$team_staff = $this->findById($id);
		if ( ! empty($team_staff)) {
			$response = array('status' => 200, 'data' => $team_staff);
		} else {
			$response = array('status' => 302, 'message' => 'Team Staff Data Could Not Be Retrieved');
		}
		return $response;
	}

	public function deleteTeamStaff($id) {
		$teamStaff = $this->showTeamStaff($id);
		if ( $teamStaff['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Team Staff does not exist');
			return $response;
		}
		$this->_updateCache($id, null);
		$delete = $this->softDelete($id);
		$response = array('status' => 200, 'data'=> $delete , 'message' => 'Deleted');
		return $response;
	}


	public function updateTeamStaffs($teamStaffs , $teamId) {
		$team = $this->Team->showTeam($teamId);
		if ($team['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Team does not exist');
			return $response;
		}
		$this->validateId();
		$this->_updateCache(null, $teamStaffs, $teamId);
		if ( ! empty($teamStaffs)) {
			if ($this->saveMany($teamStaffs)) {
				$response = array('status' => 200 , 'message' => ' Team Staffs Updated');
			} else {
				$response = array('status' => 312, 'message' => 'Team Staffs Could not be added or updated');
				pr($this->validationErrors);
			}
		} else {			
			$response = array('status' => 313, 'message' => 'No Data To Update or Add Team Staffs ');			
		}
		return $response ;
	}
public function updateStaffsStatusAndRoles($staffsStatusAndRoles) {
		$this->validator()->add('team_id', 'required', array(	'rule' => 'notEmpty', 'required' => 'update' ));
		if (!empty($staffsStatusAndRoles)) {
			if ($this->saveMany($staffsStatusAndRoles)) {
				$response = array('status' => 200 , 'data' => '');
			} else {
				$response = array('status' => 408, 'message' => 'Updating Team Staffs Data Unsuccessfull');
				pr($this->validationErrors);
			}
		} else {			
			$response = array('status' => 411, 'message' => 'No Data To Update or Add Team Staffs and Roles');			
		}
		return $response ;
	}

	private function _updateCache($id = null, $teamStaffs = null, $teamId = null) {
		if ( ! empty($id)) {
			$teamStaff = $this->showTeamStaff($id);
			if ( ! empty($teamStaff['data']['TeamStaff']['team_id'])) {
				Cache::delete('show_team_' . $teamStaff['data']['TeamStaff']['team_id']);
				//Cache::delete('show_user_'.$teamStaff['data']['TeamStaff']['user_id']);  *** use when show_user_1 cache exists ***
			}
		}
		if ( ! empty($teamId)) {
			//  $team = $this->Team->showTeam($teamId); 							*** use when show_user_1 cache exists ***
			Cache::delete('show_team_' . $teamId);
			//  foreach ($team['data']['TeamStaff'] as $teamstaff) {				*** use when show_user_1 cache exists ***
			//  Cache::delete('show_user_'.$teamstaff['user_id']);							*** use when show_user_1 cache exists ***
			//  }
		}
		//  if(!empty($teamStaffs)){
		//	foreach ($teamStaffs as $teamStaff) {
		//		Cache::delete('show_user_'.$teamStaff['user_id']);
		//	}
		// }
  }
	public function getTeamStaffRequest($id) {
		$request_name = array();
  	if (!empty($id)) {
      $data = $this->find('first' ,array(
      	'conditions' => array('TeamStaff.id' => $id),
        'fields' => array('team_id','role'),
        'contain' => array(
        	'Team' => array(
        		'fields' => array('id','name','image_id'),
        		'ProfileImage' => array(
        			'fields' => array('id','url')
        		)
        	)
        )
      ));
      if (!empty($data)) {
        $team = array(
          'id' => $data['TeamStaff']['team_id'],
          'name' => $data['Team']['name']
        );
        if (!empty($data['Team']['ProfileImage'])) {
        	$team['image'] = $data['Team']['ProfileImage']['url'];
        } else $team['image'] = null;

        $request_name['team'] = $team;
        $request_name['role'] = $data['TeamStaff']['role'];
      }
  	}
  	return $request_name;
  }

  public function handleTeamStaffRequest($requestId,$userId,$status) {
    if (!empty($requestId) && !empty($userId) && !empty($status)) {
      if ($this->isUserEligbleForRequest($requestId,$userId)) {
        $data = array(
          'TeamStaff' => array(
            'id' => $requestId,
            'status' => $status
          )
        );
        if ($this->save($data)) {
          $response =  array('status' => 200 , 'message' => 'success');
        } 
        else {
          $response =  array('status' => 119, 'message' => 'handleTeamStaffRequest : Team Staff Request Accept Could not be updated');
        } 
      }
      else {
        $response =  array('status' => 118, 'message' => 'handleTeamStaffRequest : User Not Eligible to Accept Team Staff Request');
      }
    }
    else {
      $response =  array('status' => 117, 'message' => 'handleTeamStaffRequest :Invalid Arguments');
    }
    return $response;
  }

  public function isUserEligbleForRequest($requestId,$userId) {
    return $this->find('count',array(
      'conditions' => array(
        'TeamStaff.id' => $requestId,
        'TeamStaff.user_id' => $userId,
        'TeamStaff.status' => InvitationStatus::INVITED
      )
    ));
  }

}

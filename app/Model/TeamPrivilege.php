<?php
App::uses('AppModel', 'Model');
App::uses('InvitationStatus', 'Lib/Enum');
/**
 * TeamPrivilege Model
 *
 * @property Team $Team
 * @property User $User
 */
class TeamPrivilege extends AppModel {

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
		'is_admin' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				// 'required' => true,
				// 'allowEmpty' => false,
				// 'message' => 'IsAdmin value not valid',
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

	public function showTeamPrivilege($id) {
		$team_privilege = $this->findById($id);
		if ( ! empty($team_privilege)) {
			$response = array('status' => 200, 'data' => $team_privilege);
		} else {
			$response = array('status' => 302, 'message' => 'Team Privilege Data Could Not Be Retrieved');
		}
		return $response;
	}

	public function deleteTeamPrivilege($id) {
		$teamPrivilege = $this->showTeamPrivilege($id);
		if ($teamPrivilege['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'TeamPrivilege does not exist');
			return $response;
		}
		$this->_updateCache($id);
		$delete = $this->softDelete($id);
		$response = array('status' => 200, 'data'=> $delete , 'message' => 'Deleted');
		return $response;
	}

	public function updateTeamPrivileges($teamId , $teamPrivileges) {
		$team = $this->Team->showTeam($teamId);
		if ($team['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Team does not exist');
			return $response;
		}
		$this->_updateCache(null,$teamPrivileges,$teamId);
		$this->validateId();
		if ( ! empty($teamPrivileges)) {
			if ($this->saveMany($teamPrivileges)) {
				$response = array('status' => 200 , 'data' =>'Team Player Scores Saved');
			} else {
				$response = array('status' => 316, 'message' => 'Team Player Score Could not be added or updated');
				pr($this->validationErrors);
			}
		} else {			
			$response = array('status' => 317, 'message' => 'No Data To Update or Add Team Player Score');			
		}
		return $response ;
	}

	private function _updateCache($id = null,$teamPrivileges = null, $teamId = null) {
		if ( ! empty($id)) {
			$teamPrivilege = $this->showTeamPrivilege($id);
			if ( ! empty($teamPrivilege['data']['TeamPrivilege']['team_id'])) {
				Cache::delete('show_team_' . $teamPrivilege['data']['TeamPrivilege']['team_id']);
				//Cache::delete('show_user_'.$teamInningScorecard['data']['TeamInningScorecard']['user_id']);  *** use when show_user_1 cache exists ***
			}
		}
		if ( ! empty($teamId)) {
			//  $team = $this->Team->showTeam($teamId); 							*** use when show_user_1 cache exists ***
			Cache::delete('show_team_' . $teamId);
			//  foreach ($team['data']['TeamPlayer'] as $teamplayer) {				*** use when show_user_1 cache exists ***
			//  Cache::delete('show_user_'.$teamplayer['user_id']);							*** use when show_user_1 cache exists ***
			//  }
		}
		//  if(!empty($teamPrivileges)){
		//	foreach ($teamPrivileges as $teamPrivilege) {
		//		Cache::delete('show_user_'.$teamPrivilege['user_id']);
		//	}
		// }
  }
	public function getTeamAdminRequest($id){
		$team = array();
  	if (!empty($id)) {
      $data = $this->find('first' ,array(
      	'conditions' => array('TeamPrivilege.id' => $id),
        'fields' => array('team_id'),
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
          'id' => $data['TeamPrivilege']['team_id'],
          'name' => $data['Team']['name']
        );
        if (!empty($data['Team']['ProfileImage'])) {
        	$team['image'] = $data['Team']['ProfileImage']['url'];
        } else $team['image'] = null;
      }
  	}
  	return array('team' => $team);
	}

	public function handleTeamAdminRequest($requestId,$userId,$status) {
    if (!empty($requestId) && !empty($userId) && !empty($status)) {
      if ($this->isUserEligbleForRequest($requestId,$userId)) {
        $data = array(
          'TeamPrivilege' => array(
            'id' => $requestId,
            'status' => $status
          )
        );
        if ($this->save($data)) {
          $response =  array('status' => 200 , 'message' => 'success');
        } 
        else {
          $response =  array('status' => 116, 'message' => 'handleTeamAdminRequest : Team Admin Request Accept Could not be updated');
        } 
      }
      else {
        $response =  array('status' => 115, 'message' => 'handleTeamAdminRequest : User Not Eligible to Accept Team Admin Request');
      }
    }
    else {
      $response =  array('status' => 114, 'message' => 'handleTeamAdminRequest : Invalid Input Arguments');
    }
    return $response;
  }

  public function isUserAdminOfTeams($userId,$teams) {
  	return $this->find('count',array(
  		'conditions' => array(
  			'TeamPrivilege.team_id' => $teams,
  			'TeamPrivilege.user_id' => $userId,
  			'TeamPrivilege.is_admin' => true,
  			'OR' => array(
          array('TeamPrivilege.status' => InvitationStatus::ACCEPTED),
          array('TeamPrivilege.status' => InvitationStatus::CONFIRMED)
        )  			
  		)
  	));
  }

  public function isUserTeamAdmin($teamId,$userId){
  	return $this->find('count',array(
  		'conditions' => array(
  			'TeamPrivilege.team_id' => $teamId,
  			'TeamPrivilege.user_id' => $userId,
  			'TeamPrivilege.is_admin' => true,
  			'OR' => array(
          array('TeamPrivilege.status' => InvitationStatus::ACCEPTED),
          array('TeamPrivilege.status' => InvitationStatus::CONFIRMED)
        ) 
  		)
  	));
  }

  public function isUserEligbleForRequest($requestId,$userId) {
    return $this->find('count',array(
      'conditions' => array(
        'TeamPrivilege.id' => $requestId,
        'TeamPrivilege.user_id' => $userId,
        'TeamPrivilege.status' => InvitationStatus::INVITED
      )
    ));
  }

}

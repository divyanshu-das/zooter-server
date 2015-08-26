<?php
App::uses('AppModel', 'Model');
/**
 * SeriesTeam Model
 *
 * @property Series $Series
 * @property Team $Team
 */
class SeriesTeam extends AppModel {

public $validate = array(
		'series_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Series id is not numeric',
				'required' => true,
				'allowEmpty' => false,
				'on' => 'create'
			),
			'seriesExist' => array(
				'rule' => array('seriesExist'),
				'on' => 'create'
			)
		),
		'team_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Team id is not numeric',
				'required' => true,
				'allowEmpty' => false,
				'on' => 'create'
			),
			'teamExist' => array(
				'rule' => array('teamExist')
			)
		),
			'status' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Status is not valid',
				'required' => true,
				'allowEmpty' => false,
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
		'Series' => array(
			'className' => 'Series',
			'foreignKey' => 'series_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Team' => array(
			'className' => 'Team',
			'foreignKey' => 'team_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);


	public function showSeriesTeam($id) {
		$series_team = $this->findById($id);
		if ( ! empty($series_team)) {
			$response = array('status' => 200, 'data' => $series_team);
		} else {
			$response = array('status' => 302, 'message' => 'Series Team Data Could Not Be Retrieved');
		}
		return $response;
	}

	public function deleteSeriesTeam($id) {
		$seriesTeam = $this->showSeriesTeam($id);
		if ($seriesTeam['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Series Team does not exist');
			return $response;
		}
		$this->_updateCache($id);
		$delete = $this->softDelete($id);
		$response = array('status' => 200, 'data'=> $delete , 'message' => 'Deleted');
		return $response;
	}

	public function updateSeriesTeams( $seriesTeams, $seriesId) {
		$series = $this->Series->showSeries($seriesId);
		if ($series['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Series does not exist');
			return $response;
		}
		$this->validateId();
		$this->_updateCache(null,$seriesTeams,$seriesId);
		if ( ! empty($seriesTeams)) {
			if ($this->saveMany($seriesTeams)) {
				$response = array('status' => 200 , 'data' =>'Series Team data updated');
			} else {
				$response = array('status' => 314, 'message' => 'Series Team Could not be added or updated' , 'data' => $this->validationErrors);
			}
		} else {			
			$response = array('status' => 315, 'message' => 'No Data To Update or Add Series Team');			
		}
		return $response ;
	}

	private function _updateCache($id = null,$seriesTeams = null, $seriesId = null) {
		if( ! empty($id)) {
			$seriesTeam = $this->showSeriesTeam($id);
			if( ! empty($seriesTeam['data']['SeriesTeam']['series_id'])) {
				Cache::delete('show_series_' . $seriesTeam['data']['SeriesTeam']['series_id']);
				Cache::delete('show_team_' . $seriesTeam['data']['SeriesTeam']['team_id']);
			}
		}
		if ( ! empty($seriesId)) {
			$series = $this->Series->showSeries($seriesId);
			Cache::delete('show_series_' . $seriesId);
			if ( ! empty($series['data']['SeriesTeam'])) {
				foreach ($series['data']['SeriesTeam'] as $team) {
					Cache::delete('show_team_'.$team['team_id']);
				}
			}
		}
		if ( ! empty($seriesTeams)) {
			foreach ($seriesTeams as $seriesTeam) {
				Cache::delete('show_team_' . $seriesTeam['team_id']);
			}
		}
  }

  public function handleSeriesTeamRequest($requestId,$userId,$status) {
    if (!empty($requestId) && !empty($userId) && !empty($status)) {
      if ($this->isUserEligbleForRequest($requestId,$userId)) {
        $data = array(
          'SeriesTeam' => array(
            'id' => $requestId,
            'status' => $status
          )
        );
        if ($this->save($data)) {
          $response =  array('status' => 200 , 'message' => 'success');
        } 
        else {
          $response =  array('status' => 134, 'message' => 'handleSeriesTeamRequest : Series Team Request Accept Could not be updated');
        } 
      }
      else {
        $response =  array('status' => 133, 'message' => 'handleSeriesTeamRequest : User Not Eligible to Accept Series Team Request');
      }
    }
    else {
      $response =  array('status' => 132, 'message' => 'handleSeriesTeamRequest : Invalid Input Arguments');
    }
    return $response;
  }

  public function isUserEligbleForRequest($requestId,$userId) {
    return $this->find('count',array(
      'conditions' => array(
      	'SeriesTeam.id' => $requestId,
      	'SeriesTeam.status' => InvitationStatus::INVITED,
        'Team.id = SeriesTeam.team_id',
        'Team.owner_id' => $userId,
      ),
      'contain' => array('Team')
    ));
  }
}

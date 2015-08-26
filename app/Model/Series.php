<?php
App::uses('AppModel', 'Model');
App::uses('Limit', 'Lib/Enum');
App::uses('SeriesSearchSubFilter', 'Lib/Enum');
App::uses('SeriesSearchTopFilter', 'Lib/Enum');
/**
 * Series Model
 *
 * @property Location $Location
 * @property HostCountry $HostCountry
 * @property Match $Match
 * @property SeriesAwards $awards
 * @property SeriesTeams $teams
 * @property SeriesPrivileges $privileges
 */
class Series extends AppModel {

public $validate = array(
		'is_public' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				'required' => true,
				'allowEmpty' => false
			)
		),
		'is_cancelled' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				'required' => true,
				'allowEmpty' => true
			)
		),
		'location_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'required' => true,
				'allowEmpty' => false,
				'message' => 'Not Valid location'
			),
			'locationExist' => array(
				'rule' => array('locationExist') 
			)
		),
		'start_datetime' => array(
			'futureDatetime' => array(
				'rule' => array('futureDatetime'),
				'required' => true,
				'allowEmpty' => false,
				'on' => 'create'
			)
		),
		'end_datetime' => array(
			'futureDatetime' => array(
				'rule' => array('futureDatetime'),
				'required' => true,
				'allowEmpty' => false,
				'on' => 'create'
			)
		),
		'checksum' => array(
			'alphaNumeric' => array(
				'rule' => array('alphaNumeric'),
				'required' => true,
				'allowEmpty' => false,
				'message' => 'Not Valid checksum',
			)
		),
		'owner_id' => array(
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
		'scale' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'required' => true,
				'allowEmpty' => false,
				'message' => 'Not Valid scale Value'
			)
		),
		'series_type' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'required' => true,
				'allowEmpty' => false,
				'message' => 'Not Valid series type Value'
			)
		)
	);
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Location' => array(
			'className' => 'Location',
			'foreignKey' => 'location_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'owner_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)

	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Match' => array(
			'className' => 'Match',
			'foreignKey' => 'series_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'SeriesAward' => array(
			'className' => 'SeriesAward',
			'foreignKey' => 'series_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'SeriesTeam' => array(
			'className' => 'SeriesTeam',
			'foreignKey' => 'series_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'SeriesPrivilege' => array(
			'className' => 'SeriesPrivilege',
			'foreignKey' => 'series_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);


	public function showSeries($id) {
		$series = Cache::read('show_series_' . $id);
    if ( ! $series) {
			$series = $this->find('first',array(
				'conditions' => array(
					'Series.id' => $id,
				),
				'contain' => array ('Match','SeriesAward','SeriesTeam','SeriesPrivilege')
				));
			Cache::write('show_series_' . $id, $series);
		}
		if ( ! empty($series)) {
			$response = array('status' => 200, 'data' => $series,  'message' => 'Series data found' );
		} else {
			$response = array('status' => 302, 'message' => 'Series Data Could Not Be Retrieved');
		}
		return $response;
	}

	public function deleteSeries($id) {
		$series = $this->showSeries($id);
		if ( $series['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Series does not exist');
			return $response;
		}
		$this->_updateCache($id);
		$delete = $this->softDelete($id, array(
			'contain'=>array('Match','SeriesAward','SeriesTeam','SeriesPrivilege')
			));
		$response = array('status' => 200, 'data'=> $delete , 'message' => 'Deleted');
		return $response;
	}

	public function createSeries($name, $startDatetime, $endDatetime = null, $is_public = true, $scale, $series_type, $owner_id, $location_id , $series_teams , $series_awards , $seriesPrivileges) {
		if( ! empty($endDatetime)) {
			if ($startDatetime >= $endDatetime) {
				$response = array('status' => 905, 'message' => 'End Date cannot be before start date');
				return $response;
			}			
		}
		$checksum_data = [$name, $location_id , $startDatetime];
		$sha1 = $this->createChecksum($checksum_data);
		$checksumExists = $this->find('count',array(
			'conditions'=> array('Series.checksum' => $sha1)
			)
		);
		if ($checksumExists) {
			$response = array('status' => 904, 'message' => 'Series data conflict. Not allowed');
		} else {
			$response = $this->__saveSeries($name, $startDatetime, $endDatetime, $is_public, $scale, $series_type, $owner_id , $location_id, $sha1,$series_teams,$series_awards,$seriesPrivileges);
		}
		return $response;
	}



	public function updateSeries($id ,$name, $startDatetime, $endDatetime = null, $is_public = true, $is_cancelled = false ,$scale, $series_type, $owner_id, $location_id, $series_teams , $series_awards , $series_admins){
		$series = $this->showSeries($id);
		if ($series['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Team does not exist');
			return $response;
		}
		if ($series['data']['Series']['start_datetime'] != $startDatetime && $startDatetime <  date('Y-m-d H:i:s', strtotime('- 10 minutes'))) {
			$response = array('status' => 905, 'message' => 'Start Date cannot be changed to past date');
			return $response;
		}
		if ( ! empty($endDatetime)) {
			if ($series['data']['Series']['end_datetime'] != $endDatetime && $endDatetime <  date('Y-m-d H:i:s', strtotime('- 10 minutes'))) {
				$response = array('status' => 905, 'message' => 'End Date cannot be changed to past date');
				return $response;
			}
			if ($startDatetime >= $endDatetime) {
				$response = array('status' => 905, 'message' => 'End Date cannot be before start date');
				return $response;
			}			
		}
		$checksum_data = [$name, $location_id , $startDatetime];
		$sha1 = $this->createChecksum($checksum_data);
		$checksumExists = $this->find('first',array(
			'conditions'=> array('Series.checksum' => $sha1)
			)
		);
		if (isset($checksumExists['Series']['id']) && $checksumExists['Series']['id'] != $id ) {
			$response = array('status' => 904, 'message' => 'Series data conflict. Not allowed');
		} else {
			$this->_updateCache($id);
			$response = $this->__saveSeriesUpdate($id, $name, $startDatetime, $endDatetime, $is_public, $scale, $series_type, $owner_id , $location_id, $sha1, $series_teams , $series_awards , $series_admins);
		}
		return $response;

	}

	private function __saveSeries($name, $startDatetime, $endDatetime, $is_public, $scale, $series_type, $owner_id, $location_id, $checksum , $series_teams, $series_awards, $seriesPrivileges) {
 		$teams = array();
 		foreach ($series_teams as $series_team) {
 			$teams[] = array(
 				'team_id'=> $series_team['team_id']
 			);
 		}

 		$admins = array();
 		$owner_is_admin = false;
 		foreach ($seriesPrivileges as $series_admin) {
 			if ($series_admin['user_id'] == $owner_id) {
 				$owner_is_admin = true;
 			}
 			$admins[] = array(
 				'user_id' => $series_admin['user_id'], 
 				'is_admin' => true
 			);
 		}
 		if ( ! $owner_is_admin) {
 			$admins[] = array(
 				'user_id'=> $owner_id, 
 				'is_admin' => true
 			);
 		}
 		$award_names = array();
 		foreach ($series_awards as $series_award) {
 			$award_names[] = $series_award['award_name'];
 		}
		$existing_award = $this->SeriesAward->AwardType->find('list',array(
			'conditions' => array('AwardType.award_name' => $award_names),
			 'fields' => array('AwardType.award_name','AwardType.id')
			));	

		$awards = [];
		foreach ($series_awards as $series_award) {
			if (array_key_exists($series_award['award_name'], $existing_award)) {
				$awards[] = array(
					'value'=> $series_award['value'], 
					'award_type_id' => $existing_award[$series_award['award_name']]
				);
			} else {
				$awards[] = array(
					'value' => $series_award['value'], 
					'AwardType' => array(
						'award_name' => $series_award['award_name']
					)
				);
			}
		}


	 	$data =array(
	 		'Series' => array( 
				'name' => $name,
				'start_datetime' => $startDatetime,
				'end_datetime' => $endDatetime,
				'is_public' => $is_public,
				'is_cancelled' => false,
				'owner_id' => $owner_id,
				'checksum' => $checksum,
				'scale' => $scale,
				'series_type' => $series_type,
				'location_id' => $location_id				
				),
	 		'SeriesTeam' => $teams,
	 		'SeriesPrivilege' => $admins,
	 		'SeriesAward' => $awards
		);

		if ($this->saveAssociated($data, array('deep' => true))) {
				$seriesId = $this->getLastInsertID();
				$responseData = $this->showSeries($seriesId);
				$this->_updateCache($seriesId);
				$response = array('status' => 200, 'data' => $responseData['data'] , 'message' => 'Series Created');
			} else {
				$response = array('status' => 801, 'message' => 'Series not created', 'data' => $this->validationErrors);
			}
		return $response;
	}

	private function __saveSeriesUpdate($id, $name, $startDatetime, $endDatetime, $is_public, $scale, $series_type, $owner_id , $location_id, $checksum, $series_teams , $series_awards , $series_admins) {
	 		$award_names = array();
	 		foreach ($series_awards as $series_award) {
	 			$award_names[] = $series_award['award_name'];
	 		}
		$existing_award = $this->SeriesAward->AwardType->find('list',array(
			'conditions' => array(
				'AwardType.award_name' => $award_names
			),
			 'fields' => array(
			 	'AwardType.award_name',
			 	'AwardType.id'
			 )
			));	

		$awards = [];
		foreach ($series_awards as $series_award) {
			if ( ! array_key_exists('id',$series_award) ){
				$series_award['id'] = "";
			}
			if (array_key_exists($series_award['award_name'], $existing_award )) {
				if ( array_key_exists('deleted',$series_award) && $series_award['deleted'] == true) {
					$awards[] = array(
						'id' => $series_award['id'],
						'series_id' => $id,
						'user_id' => $series_award['user_id'], 
						'value' => $series_award['value'], 
						'award_type_id' => $existing_award[$series_award['award_name']], 
						'deleted'=> true, 
						'deleted_date'=> date('Y-m-d H:i:s') 
					);
				} else {
					$awards[] = array(
						'id' => $series_award['id'],
						'series_id' => $id,
						'user_id'=> $series_award['user_id'], 
						'value'=> $series_award['value'], 
						'award_type_id' => $existing_award[$series_award['award_name']]
					);
				}
			} else {
				if ( array_key_exists('deleted',$series_award) && $series_award['deleted'] == true) {
					$awards[] = array(
						'id' => $series_award['id'],
						'series_id' => $id,
						'user_id' => $series_award['user_id'],
						'value' => $series_award['value'], 
						'AwardType' => array(
							'award_name' => $series_award['award_name']
						), 
						'deleted'=> true, 
						'deleted_date'=> date('Y-m-d H:i:s')
					);
				} else {
					$awards[] = array(
						'id' => $series_award['id'],
						'series_id' => $id,
						'user_id' => $series_award['user_id'],
						'value' => $series_award['value'], 
						'AwardType' => array(
							'award_name' => $series_award['award_name']
						)
					);
				}
			}
		}
		$data = array(
	 		'Series' => array(
	 			'id' => $id, 
				'name' => $name,
				'start_datetime' => $startDatetime,
				'end_datetime' => $endDatetime,
				'is_public' => $is_public,
				'is_cancelled' => false,
				'owner_id' => $owner_id,
				'checksum' => $checksum,
				'scale' => $scale,
				'series_type' => $series_type,
				'location_id' => $location_id				
				),
	 		'SeriesTeam' => $series_teams,
	 		'SeriesPrivilege' => $series_admins,
	 		'SeriesAward' => $awards
		);
		$this->SeriesTeam->validateId();
		$this->SeriesPrivilege->validateId();
		$this->SeriesAward->validateId();
		if ($this->saveAssociated($data, array('deep' => true))) {
			$this->_updateCache($id);
			$responseData = $this->showSeries($id);
			$response = array('status' => 200, 'data' => $responseData['data'] , 'message' => 'Series updated');
		} else {
				$response = array('status' => 801, 'message' => 'Series not updated', 'data' => $this->validationErrors );
			}
		return $response;
	}

	private function _updateCache($id) {
		$series = $this->showSeries($id);
		Cache::delete('show_series_' . $id);
		if (!empty($series['data']['SeriesTeam'])) {
			foreach ($series['data']['SeriesTeam'] as $team) {
				Cache::delete('show_team_' . $team['team_id']);
			}
		}
		if (!empty($series['data']['Match'])) {
			foreach($series['data']['Match'] as $match) {
				Cache::delete('show_match_' . $match['id']);
			}
		}
	}

	public function getTrendingTournaments() {
		$responseData = array();
		$seriesData = $this->find('all',array(
			'conditions' => array(
				'is_public' =>true,
				'is_cancelled !=' => true
			),
			'contain' => array(
				'Location' => array(
          'fields' => array('id' ,'name','city_id'),
          'City' => array(
            'fields' => array('id','name')
          )
        )
			),
			'order' => 'Series.start_date_time DESC',
			'limit' => Limit::NUM_OF_TRENDING_IN_MATCH_CORNER_LANDING
		));

		foreach ($seriesData as $key => $series) {
			$seriesData[$key]['followers'] = $this->Match->getTournamentFollowersCount($series['Series']['id']);
		}

		$data = array();
		foreach ($seriesData as $key => $row)
		{
		  $data[$key] = $row['followers'];
		}
		array_multisort($data, SORT_DESC, $seriesData);

		foreach ($seriesData as $key => $series) {
			$responseData[$key]['id'] = $series['Series']['id'];
			$responseData[$key]['name'] = $series['Series']['name'];
			$responseData[$key]['start_date_time'] = $series['Series']['start_date_time'];
			$responseData[$key]['end_date_time'] = $series['Series']['end_date_time'];
			if (!empty($series['Location'])) {
				$responseData[$key]['location']['id'] = $series['Location']['id'];
				$responseData[$key]['location']['name'] = $series['Location']['name'];
				if (!empty($series['Location']['City'])) {
					$responseData[$key]['location']['city'] = $series['Location']['City']['name'];
				} else {
					$responseData[$key]['location']['city'] = null;
				}
			} else {
				$responseData[$key]['location']['id'] = null;
				$responseData[$key]['location']['name'] = null;
				$responseData[$key]['location']['city'] = null;
			}
		}
		return $responseData;
	}

	public function tournamentSearchPublic($filters) {
		if (empty($filters)) {
  		return array('status' => 100 , 'message' => 'tournamentSearchPublic : Invalid Input Arguments');
  	}
  	$options = array(
  		'conditions' => array(
  			'Series.is_public' => true,
  			'Series.is_cancelled' => [false,null]
  		),
  		'fields' => array('id','name','start_date_time','end_date_time','location_id'),
  		'contain' => array(
  			'Location' => array(
          'fields' => array('id' ,'name','city_id'),
          'City' => array(
            'fields' => array('id','name')
          )
        )
  		),
  		'limit' => Limit::NUM_OF_TOURNAMENTS_IN_TOURNAMENT_SEARCH_PAGE,
  		'order' => 'Series.start_date_time DESC'
  	);
  	foreach ($filters as $key => $value) {
  		if (!empty($value)) {
	  		switch ($key) {
	  			case SeriesSearchSubFilter::stringValue(SeriesSearchSubFilter::TEXT):
	  				$value = trim($value);
	  				$options['conditions']['Series.name LIKE'] = "%$value%";
	  				break;
	  			case SeriesSearchSubFilter::stringValue(SeriesSearchSubFilter::START_DATE):
	  				$value = trim($value);
	  				$options['conditions']['OR'] = array(
	  					'Series.start_date_time >=' => $value,
	  					'Series.start_date_time LIKE' => "%$value%"
	  				);
	  				break;
	  			case SeriesSearchSubFilter::stringValue(SeriesSearchSubFilter::END_DATE):
	  				$value = trim($value);
	  				$options['conditions']['OR'] = array(
	  					'Series.end_date_time <=' => $value,
	  					'Series.end_date_time LIKE' => "%$value%"
	  				);
	  				break;
	  			case SeriesSearchSubFilter::stringValue(SeriesSearchSubFilter::UPCOMING):
	  				if (empty($filters['start_date']) && empty($filters['end_date']) && empty($filters['ongoing']) && empty($filters['finished'])) {
	  					$options['conditions']['Series.start_date_time >'] = date('Y-m-d H:i:s');	  					
	  				}
	  				break;
	  			case SeriesSearchSubFilter::stringValue(SeriesSearchSubFilter::ONGOING):
	  				if (empty($filters['start_date']) && empty($filters['end_date'])&& empty($filters['upcoming']) && empty($filters['finished'])) {
		  				$options['conditions']['Series.start_date_time <='] = date('Y-m-d H:i:s');
		  				$options['conditions']['Series.end_date_time >'] = date('Y-m-d H:i:s');
		  			}
	  				break;
	  			case SeriesSearchSubFilter::stringValue(SeriesSearchSubFilter::FINISHED):
	  				if (empty($filters['start_date']) && empty($filters['end_date'])&& empty($filters['ongoing']) && empty($filters['upcoming'])) {
		  				$options['conditions']['Series.start_date_time <'] = date('Y-m-d H:i:s');
		  				$options['conditions']['Series.end_date_time <'] = date('Y-m-d H:i:s');
		  			}
	  				break;
	  			case SeriesSearchSubFilter::stringValue(SeriesSearchSubFilter::LOCATION):
	  				$nearBylocations = $this->Location->getNearbyLocation($value['latitude'], $value['longitude'], Limit::TOURNAMENT_SEARCH_DISTANCE);
	  				$locationIds = array();
	  				if ($nearBylocations['status'] == 200) {
	  					if (!empty($nearBylocations['data'])) {
		  					$locations = $nearBylocations['data'];
		  					foreach ($locations as $key => $value) {
		  						$locationIds[$key] = $value['id'];
		  					}
		  					$options['conditions']['Series.location_id'] = $locationIds;
	  					} else {
	  						return array('status' => 200, 'data' => array('tournaments' => array()),'message' => 'tournamentSearchPublic : no tournaments for this location');
	  					}
	  				} else {
	  					return array('status' => $nearBylocations['status'] , 'message' => $nearBylocations['message']);
	  				}
	  				break;
	  		}  			
  		}
  	}
  	$tournaments = $this->find('all',$options);
  	$responseData = array();
  	foreach ($tournaments as $key => $tournament) {
  		$responseData[$key]['id'] = $tournament['Series']['id'];
  		$responseData[$key]['name'] = $tournament['Series']['name'];
  		$responseData[$key]['start_date_time'] = $tournament['Series']['start_date_time'];
  		$responseData[$key]['end_date_time'] = $tournament['Series']['end_date_time'];
			if (!empty($tournament['Location'])) {
				$responseData[$key]['location']['id'] = $tournament['Location']['id'];
				$responseData[$key]['location']['name'] = $tournament['Location']['name'];
				if (!empty($tournament['Location']['City'])) {
					$responseData[$key]['location']['city'] = $tournament['Location']['City']['name'];
				} else {
					$responseData[$key]['location']['city'] = null;
				}
			} else {
				$responseData[$key]['location']['id'] = null;
				$responseData[$key]['location']['name'] = null;
				$responseData[$key]['location']['city'] = null;
			}
  	}
  	return array('status' => 200, 'data' => array('tournaments' => $responseData));
	}

	public function tournamentSearchForUser($userId,$topFilter,$subFilter) {
  	$userId = trim($userId);
  	if (!empty($userId)) {
  		if (!$this->_userExists($userId)) {
  			return array('status' => 103 , 'message' => 'tournamentSearchForUser : Invalid User ID');
  		}
  	}
  	$responseData = array();
  	if (empty($topFilter) && empty($subFilter)) {
  		return array('status' => 100 , 'message' => 'tournamentSearchForUser : Invalid Input Arguments');
  	}
  	switch ($topFilter) {
  		case SeriesSearchTopFilter::ALL:
  			
  			break;
  		case SeriesSearchTopFilter::FOLLOWED:
  			# code...
  			break;
  		case SeriesSearchTopFilter::RECOMMENDED:
  			# code...
  			break;
  		case SeriesSearchTopFilter::MY:
  			# code...
  			break;
  	}
  	return array('status' => 200, 'data' => array('tournaments' => $responseData));
  }

}

<?php
App::uses('AppModel', 'Model');
App::uses('UserRequest', 'Model');
App::uses('WallPost', 'Model');
App::uses('MatchType', 'Lib/Enum');
App::uses('InvitationStatus', 'Lib/Enum');
App::uses('MatchTeamStatus', 'Lib/Enum');
App::uses('MatchSearchType', 'Lib/Enum');
App::uses('Limit', 'Lib/Enum');
App::uses('SpecialUsers', 'Lib/Enum');
App::uses('MatchSearchTopFilter', 'Lib/Enum');
App::uses('MatchSearchSubFilter', 'Lib/Enum');
App::uses('MatchType', 'Lib/Enum');
App::uses('MatchBallType', 'Lib/Enum');
App::uses('MatchLevel', 'Lib/Enum');
App::uses('MatchStaffRole', 'Lib/Enum');
App::uses('UserRequestType', 'Lib/Enum');
/**
 * Match Model
 *
 * @property Series $Series
 * @property Location $Location
 */
class Match extends AppModel {

	public $validate = array(
		'series_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'series_id is not valid',
				// 'allowEmpty' => true,
				// 'required' => false
			),
			// 'seriesExist' => array(
			// 	'rule' => array('seriesExist'),
			// 	'allowEmpty' => true,
			// 	'required' => false
			// )
		),
		'checksum' => array(
			'alphaNumeric' => array(
				'rule' => array('alphaNumeric'),
				'message' => 'Not Valid checksum',
				// 'required' => true,
				// 'allowEmpty' => false,
			)
		),
		'is_public' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				// 'required' => true,
				// 'allowEmpty' => false
			)
		),
		'start_date_time' => array(
			'futureDatetime' => array(
				'rule' => array('futureDatetime'),
				// 'required' => true,
				// 'allowEmpty' => false,
				// 'on' => 'create'
			)
		),
		'end_date_time' => array(
			'futureDatetime' => array(
				'rule' => array('futureDatetime'),
				// 'required' => true,
				// 'allowEmpty' => false,
				// 'on' => 'create'
			)
		),
		'location_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Not Valid location'
				// 'required' => true,
				// 'allowEmpty' => false,
			),
			// 'locationExist' => array(
			// 	'rule' => array('locationExist') 
			// )
		),
		'match_scale' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Not Valid match scale Value',
				// 'required' => true,
				// 'allowEmpty' => false,
			)
		),
		'match_type' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Not Valid match type Value',
				// 'required' => true,
				// 'allowEmpty' => false,
			)
		),
		'is_test' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Not Valid test type Value'
				// 'required' => true,
				// 'allowEmpty' => false,
			)
		),
		'is_cricket_ball_used' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				'message' => 'Not Valid cricket ball usage Value',
				// 'required' => true,
				// 'allowEmpty' => false,
			)
		),
		'overs_per_innings' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Not Valid overs per innings Value',
				// 'required' => true,
				// 'allowEmpty' => false,
			)
		),
		// 'series_match_level' => array(
		// 	'numeric' => array(
		// 		'rule' => array('numeric'),
		// 		'message' => 'Not Valid series match level Value'
		// 	)
		// ),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
/**
 * hasOne associations
 *
 * @var array
 */
	public $hasOne = array(
		'MatchResult' => array(
			'className' => 'MatchResult',
			'foreignKey' => 'match_id',
		),
		'MatchToss' => array(
			'className' => 'MatchToss',
			'foreignKey' => 'match_id',
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'MatchAward' => array(
			'className' => 'MatchAward',
			'foreignKey' => 'match_id',
		),
		'MatchComment' => array(
			'className' => 'MatchComment',
			'foreignKey' => 'match_id',
		),
		'MatchInningScorecard' => array(
			'className' => 'MatchInningScorecard',
			'foreignKey' => 'match_id',
		),
		'MatchPlayer' => array(
			'className' => 'MatchPlayer',
			'foreignKey' => 'match_id',
		),
		'MatchPlayerScorecard' => array(
			'className' => 'MatchPlayerScorecard',
			'foreignKey' => 'match_id',
		),
		'MatchPrivilege' => array(
			'className' => 'MatchPrivilege',
			'foreignKey' => 'match_id',
		),
		'MatchStaff' => array(
			'className' => 'MatchStaff',
			'foreignKey' => 'match_id',
		),
		'MatchTeam' => array(
			'className' => 'MatchTeam',
			'foreignKey' => 'match_id',
		),
		'MatchFollower' => array(
			'className' => 'MatchFollower',
			'foreignKey' => 'match_id',
		),
		'MatchRecommendation' => array(
			'className' => 'MatchRecommendation',
			'foreignKey' => 'match_id',
		),
		'Image' => array(
			'className' => 'Image',
			'foreignKey' => 'match_id',
		),
		'ZooterBucket' => array(
			'className' => 'ZooterBucket',
			'foreignKey' => 'match_id',
		)
	);


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
		),
		'FirstUmpire' => array(
			'className' => 'User',
			'foreignKey' => 'first_umpire_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'SecondUmpire' => array(
			'className' => 'User',
			'foreignKey' => 'second_umpire_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ThirdUmpire' => array(
			'className' => 'User',
			'foreignKey' => 'third_umpire_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ReserveUmpire' => array(
			'className' => 'User',
			'foreignKey' => 'reserve_umpire_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Referee' => array(
			'className' => 'User',
			'foreignKey' => 'referee_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'FirstTeam' => array(
			'className' => 'Team',
			'foreignKey' => 'first_team_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'SecondTeam' => array(
			'className' => 'Team',
			'foreignKey' => 'second_team_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'TossWinningTeam' => array(
			'className' => 'Team',
			'foreignKey' => 'toss_winning_team_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'MatchWinningTeam' => array(
			'className' => 'Team',
			'foreignKey' => 'winning_team_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function checkIfMatchIsOngoingOrFinished($matchId) {
		return $this->find('count',array(
			'conditions' => array(
				'Match.id' => $matchId,
				'OR' => array(
					array(
						'AND' => array(
							'Match.start_date_time <=' => date('Y-m-d H:i:s'),
							'Match.end_date_time >' => date('Y-m-d H:i:s'),
							'Match.in_progress' => true
						)
					),
					array(
						'AND' => array(
							'Match.start_date_time <' => date('Y-m-d H:i:s'),
							'Match.end_date_time <' => date('Y-m-d H:i:s'),
							'Match.in_progress' => false,
							'Match.is_complete' => true
						)
					)
				)
			)
		));
	}

	public function getMatchDetailsForScorecard($matchId) {
		return $this->find('first',array(
			'conditions' => array(
				'Match.id' => $matchId
			),
			'contain' => array(
				'FirstTeam' => array(
					'fields' => array('id','name','image_id'),
					'ProfileImage' => array(
						'fields' => array('id','url')
					)
				),
				'SecondTeam' => array(
					'fields' => array('id','name','image_id'),
					'ProfileImage' => array(
						'fields' => array('id','url')
					)
				),
				'Series' => array(
					'fields' => array('id','name')
				),
				'Location' => array(
					'fields' => array('id','name','city_id'),
					'City' => array(
						'fields' => array('id','name')
					)
				)
			)
		));
	}

	public function getBowlingTeam($matchId,$battingTeamId) {
		$bowlingTeam = array();
		$match = $this->find('first',array(
			'conditions' => array(
				'Match.id' => $matchId
			),
			'fields' => array('id','first_team_id','second_team_id'),
			'contain' => array(
				'FirstTeam' => array(
					'fields' => array('id','name','image_id'),
					'ProfileImage' => array(
						'fields' => array('id','url')
					)
				),
				'SecondTeam' => array(
					'fields' => array('id','name','image_id'),
					'ProfileImage' => array(
						'fields' => array('id','url')
					)
				)
			)
		));
		if ($match['Match']['first_team_id'] == $battingTeamId) {
			$bowlingTeam = $match['SecondTeam'];
		} else if ($match['Match']['second_team_id'] == $battingTeamId	) {
			$bowlingTeam = $match['FirstTeam'];
		}
		return $bowlingTeam;
	}

	public function getMatchDataForScore($matchId) {
		$match = $this->find('first', array(
			'conditions' => array(
				'Match.id' => $matchId
			),
			'contain' => array(
				'TossWinningTeam' => array(
					'fields' => array('id','name')
				),
				'MatchWinningTeam' => array(
					'fields' => array('id','name')
				),
				'Series' => array(
					'fields' => array('id','name')
				),
				'Location' => array(
					'fields' => array('id','name','city_id'),
					'City' => array(
						'fields' => array('id','name')
					)
				)
			)
		));
		return $match;
	}

	public function createNewMatch($userId,$fields) {
		if (empty($fields) || empty($userId)) {
			return array('status' => 100 , 'message' => 'createNewMatch : Invalid Input Arguments');
		}
		if (!$this->_userExists($userId)) {
      return array('status' => 332 , 'message' => 'createNewMatch : User Does not exist');
    }

    $dataSource = $this->getDataSource();
    $dataSource->begin();
		$data = array();
		foreach ($fields as $key => $value) {
			if(!empty($value)) {
				switch ($key) {
					case 'basic_info':
						$basicInfo = $this->__prepareBasicInfoMatchCreate($value);
						if ($basicInfo['status'] == 200) {
							$data['Match'] = $basicInfo['data'];
							$data['Match']['owner_id'] = $userId;
						} else {
							return array('status' => $basicInfo['status'], 'message' => 'createNewMatch : '.$basicInfo['message']);
						}
						break;
					case 'teams':
						$teams = $this->__prepareTeamsMatchCreate($value);
						if ($teams['status'] == 200) {
							$data['MatchTeam'] = $teams['data'];
						} else {
							return array('status' => $teams['status'], 'message' => 'createNewMatch : '.$teams['message']);
						}
						break;
					case 'admins':
						$admins = $this->__prepareAdminsMatchCreate($value,$userId);
						if ($admins['status'] == 200) {
							$data['MatchPrivilege'] = $admins['data'];
						} else {
							return array('status' => $admins['status'], 'message' => 'createNewMatch : '.$admins['message']);
						}
						break;
					case 'officials':
						$officials = $this->__prepareOfficialsMatchCreate($value);
						if ($officials['status'] == 200) {
							$data['MatchStaff'] = $officials['data'];
						} else {
							return array('status' => $officials['status'], 'message' => 'createNewMatch : '.$officials['message']);
						}
						break;
				}
			}
		}

		if (empty($data) || empty($data['Match'])) {
			return array('status' => 108, 'message' => 'createNewMatch : Basic Information for Match Creaton is mandatory');
		}
		if ($this->saveAssociated($data,array('deep' => true))) {
			$newMatchId = $this->getLastInsertID();
			$createRequest = $this->createMatchCreationUserRequests($newMatchId,$userId);
			if ($createRequest['status'] == 200) {
				$responseData = $this->__preapareMatchCreationResponse($newMatchId);
				$response = array('status' => 200, 'data' => $responseData);
			} else {
				$response = array('status' => $createRequest['status'], 'message' => 'createNewMatch : '.$createRequest['message']);
			}		
		} else {
			$response = array('status' => 109, 'message' => 'createNewMatch : Match Could not be created');
		}

		if ($response['status'] == 200) {
      $dataSource->commit();
    } else {
    	$dataSource->rollback();
    }
		return $response;
	}

	private function __prepareBasicInfoMatchCreate($basicInfoInputData) {
		$basicInfoData = array();
		$basicInfoData['is_public'] = true;
		foreach ($basicInfoInputData as $key => $value) {
			if ( !empty($basicInfoData) && array_key_exists($key, $basicInfoData)) {
				return array('status' => 108, 'message' => 'repeated'.$key.'key in coming in Match Basic Info Tuple');
			}
			if(!empty($value)) {
				switch ($key) {
					case 'is_cricket_ball_used':
						$isCricketBallUsed = trim($value);
						if ($isCricketBallUsed == true) {
							$basicInfoData['is_cricket_ball_used'] = true;
						} elseif ($isCricketBallUsed == false) {
							$basicInfoData['is_cricket_ball_used'] = false;
						} else {
							return array('status' => 104, 'message' => 'Invalid is_cricket_ball_used field');
						}
						break;
					case 'start_date_time':
						$basicInfoData['start_date_time'] = trim($value);
						break;
					case 'players_per_side':
						$basicInfoData['players_per_side'] = trim($value);
						break;
					case 'overs_per_innings':
						$basicInfoData['overs_per_innings'] = trim($value);
						break;
					case 'is_private':
						$isPrivate = trim($value);
						if ( (!empty($isPrivate)) && ($isPrivate == true) ) {
							$basicInfoData['is_public'] = false;
						}
						break;
					case 'name':
						$basicInfoData['name'] = trim($value);
						break;
					case 'location':
						$locationId = $this->Location->saveLocation($value['place'], $value['latitude'], $value['longitude'], $value['unique_identifier']);
						if ($locationId['status'] == 200) {
							$basicInfoData['location_id'] = $locationId['data'];
						} else {
							return array('status' => $locationId['status'], 'message' => $locationId['message']);
						}
						break;
				}
			}
		}

		if ( (!empty($basicInfoData['start_date_time'])) || (!empty($basicInfoData['location_id'])) || (!empty($basicInfoData['is_public'])) || (!empty($basicInfoData['overs_per_innings'])) || (!empty($basicInfoData['players_per_side'])) ) {
			$checkSum = $this->generateMatchCheckSum($basicInfoData['location_id'], $basicInfoData['start_date_time']);
			if ($checkSum['status'] == 200) {
				$basicInfoData['checksum'] = $checkSum['data'];
				$response = array('status' => 200, 'data' => $basicInfoData);
			} else {
				$response = array('status' => $checkSum['status'], 'message' => $checkSum['message']);
			}
		} else {
			$response = array('status' => 105, 'message' => '[startDateTime, location, isPublic, oversPerInnings, PlayersPerSide] are mandatory fields');
		}
		return $response;
	}

	private function __prepareTeamsMatchCreate($teamsInputData) {
		$listOfTeamsIds = array();
		$teamsData = array();
		foreach ($teamsInputData as $key1 => $value1) {
			foreach ($value1 as $key2 => $value2) {
				if ( !empty($teamsData[$key1]) && array_key_exists($key2, $teamsData[$key1])) {
					return array('status' => 108, 'message' => 'repeated'.$key2.'key in coming in Match Teams Tuple');
				}
				if(!empty($value2)) {
					switch ($key2) {
						case 'team_id':
							$teamId = trim($value2);
							if (! in_array($teamId, $listOfTeamsIds)) {
								array_push($listOfTeamsIds, $teamId);
							} else {
								return array('status' => 109, 'message' => 'repeated data in Match Teams Tuple Array');
							}
							if (!$this->_teamExists($teamId)) {
								return array('status' => 106, 'message' => 'Invalid Team Id sent in Team players Invitation');
							}
							$teamsData[$key1]['team_id'] = $teamId;
							break;
					}
				}
			}
			if (empty($teamsData[$key1]['team_id'])) {
				return array('status' => 105, 'message' => 'Team ID is mandatory field for Match Team invitation');
			}
			$teamsData[$key1]['status'] = InvitationStatus::INVITED;
		}

		if (empty($teamsData)) {
			return array('status' => 105, 'message' => 'Match Team data is empty');
		} else {
			return array('status' => 200, 'data' => $teamsData);
		}
	}

	private function __prepareAdminsMatchCreate($adminsInputData,$userId) {
		$listOfAdminsIds = array();
		$adminsData = array();
		foreach ($adminsInputData as $key1 => $value1) {
			foreach ($value1 as $key2 => $value2) {
				if ( !empty($adminsData[$key1]) && array_key_exists($key2, $adminsData[$key1])) {
					return array('status' => 108, 'message' => 'repeated'.$key2.'key in coming in Match Admins Tuple');
				}
				if(!empty($value2)) {
					switch ($key2) {
						case 'user_id':
							$userId = trim($value2);
							if (! in_array($userId, $listOfAdminsIds)) {
								array_push($listOfAdminsIds, $userId);
							} else {
								return array('status' => 109, 'message' => 'repeated data in Match Admins Tuple Array');
							}
							if (!$this->_userExists($userId)) {
								return array('status' => 106, 'message' => 'Invalid user Id sent in Team admin Invitation');
							}
							$adminsData[$key1]['user_id'] = $userId;
							break;
					}
				}
			}
			if (empty($adminsData[$key1]['user_id'])) {
				return array('status' => 105, 'message' => 'user ID is mandatory field for team admin invitation');
			}
			$adminsData[$key1]['status'] = InvitationStatus::INVITED;
			$adminsData[$key1]['is_admin'] = true;
		}
		$nextIndex = count($adminsData);
		$adminsData[$nextIndex]['user_id'] = $userId;
		$adminsData[$nextIndex]['status'] = InvitationStatus::CONFIRMED;
		$adminsData[$nextIndex]['is_admin'] = true;

		if (empty($adminsData)) {
			return array('status' => 105, 'message' => 'Team Admins data is empty');
		} else {
			return array('status' => 200, 'data' => $adminsData);
		}
	}

	private function __prepareOfficialsMatchCreate($officialsInputData) {
		$keysArray = array();
		$officialsData = array();
		$index = 0;
		foreach ($officialsInputData as $key => $value) {
			if (! in_array($key, $keysArray)) {
				array_push($keysArray, $key);
			} else {
				return array('status' => 108, 'message' => 'repeated'.$key.'key in coming in Match Officials Tuple');
			}
			if(!empty($value)) {
				switch ($key) {
					case 'first_umpire':
						$newOfficialsData = $this->__prepareOfficialsElement($value,$officialsData,$index,MatchStaffRole::FIRST_UMPIRE);
						if ($newOfficialsData['status'] == 200) {
							$officialsData = $newOfficialsData['data']['officials'];
							$index = $newOfficialsData['data']['index'];
						} else {
							return array('status' => $newOfficialsData['status'], 'message' => $newOfficialsData['message']);
						}
						break;
					case 'second_umpire':
						$newOfficialsData = $this->__prepareOfficialsElement($value,$officialsData,$index,MatchStaffRole::SECOND_UMPIRE);
						if ($newOfficialsData['status'] == 200) {
							$officialsData = $newOfficialsData['data']['officials'];
							$index = $newOfficialsData['data']['index'];
						} else {
							return array('status' => $newOfficialsData['status'], 'message' => $newOfficialsData['message']);
						}
						break;
					case 'third_umpire':
						$newOfficialsData = $this->__prepareOfficialsElement($value,$officialsData,$index,MatchStaffRole::THIRD_UMPIRE);
						if ($newOfficialsData['status'] == 200) {
							$officialsData = $newOfficialsData['data']['officials'];
							$index = $newOfficialsData['data']['index'];
						} else {
							return array('status' => $newOfficialsData['status'], 'message' => $newOfficialsData['message']);
						}
						break;
					case 'reserve_umpire':
						$newOfficialsData = $this->__prepareOfficialsElement($value,$officialsData,$index,MatchStaffRole::RESERVE_UMPIRE);
						if ($newOfficialsData['status'] == 200) {
							$officialsData = $newOfficialsData['data']['officials'];
							$index = $newOfficialsData['data']['index'];
						} else {
							return array('status' => $newOfficialsData['status'], 'message' => $newOfficialsData['message']);
						}
						break;
					case 'referee':
						$newOfficialsData = $this->__prepareOfficialsElement($value,$officialsData,$index,MatchStaffRole::REFEREE);
						if ($newOfficialsData['status'] == 200) {
							$officialsData = $newOfficialsData['data']['officials'];
							$index = $newOfficialsData['data']['index'];
						} else {
							return array('status' => $newOfficialsData['status'], 'message' => $newOfficialsData['message']);
						}
						break;
					case 'scorer':
						$newOfficialsData = $this->__prepareOfficialsElement($value,$officialsData,$index,MatchStaffRole::FIRST_SCORER);
						if ($newOfficialsData['status'] == 200) {
							$officialsData = $newOfficialsData['data']['officials'];
							$index = $newOfficialsData['data']['index'];
						} else {
							return array('status' => $newOfficialsData['status'], 'message' => $newOfficialsData['message']);
						}
						break;
				}
			}
		}
		if (empty($officialsData)) {
			return array('status' => 105, 'message' => 'Match Official data is empty');
		} else {
			return array('status' => 200, 'data' => $officialsData);
		}
	}

	private function __prepareOfficialsElement($officialElementArray,$officialsData,$index,$role) {
		$listOfOfficialsIds = array();
		foreach ($officialElementArray as $key1 => $value1) {
			foreach ($value1 as $key2 => $value2) {
				if ( !empty($officialsData[$index]) && array_key_exists($key2, $officialsData[$index])) {
					return array('status' => 108, 'message' => 'repeated'.$key2.'key in coming in Match Officials Tuple');
				}
				if(!empty($value2)) {
					switch ($key2) {
						case 'user_id':
							$userId = trim($value2);
							if (! in_array($userId, $listOfOfficialsIds)) {
								array_push($listOfOfficialsIds, $userId);
							} else {
								return array('status' => 109, 'message' => 'repeated data in Match Officials Tuple Array');
							}
							if (!$this->_userExists($userId)) {
								return array('status' => 106, 'message' => 'Invalid user Id sent in Match Officials Invitation');
							}
							$officialsData[$index]['user_id'] = $userId;
							$officialsData[$index]['role'] = $role;
							$officialsData[$index]['status'] = InvitationStatus::INVITED;
							$index = $index + 1;
							break;
					}
				}
			}
		}
		return array('status' => 200, 'data' => array('index' => $index, 'officials' => $officialsData));
	}

	public function createMatchCreationUserRequests($matchId,$userId) {
		$data = $this->find('first',array(
			'conditions' => array(
				'Match.id' => $matchId
			),
			'fields' => array('id'),
			'contain' => array(
				'MatchTeam' => array(
					'fields' => array('id','team_id'),
					'Team' => array(
						'fields' => array('id','owner_id')
					)
				),
				'MatchStaff' => array(
					'fields' => array('id','user_id')
				),
				'MatchPrivilege' => array(
					'conditions' => array(
						'MatchPrivilege.status' => InvitationStatus::INVITED
					),
					'fields' => array('id','user_id')
				)
			)
		));
		
		$index = 0;
		$requestDataArray = array();

		foreach ($data['MatchTeam'] as $key => $value) {
			$requestDataArray[$index]['request_id'] = $value['id'];
			$requestDataArray[$index]['user_id'] = $value['Team']['owner_id']; 
			$requestDataArray[$index]['type'] = UserRequestType::MATCH_TEAM_ADD_INVITE; 
			$index++;
		}

		foreach ($data['MatchStaff'] as $key => $value) {
			$requestDataArray[$index]['request_id'] = $value['id'];
			$requestDataArray[$index]['user_id'] = $value['user_id']; 
			$requestDataArray[$index]['type'] = UserRequestType::MATCH_STAFF_ADD_INVITE; 
			$index++;
		}

		foreach ($data['MatchPrivilege'] as $key => $value) {
			$requestDataArray[$index]['request_id'] = $value['id'];
			$requestDataArray[$index]['user_id'] = $value['user_id']; 
			$requestDataArray[$index]['type'] = UserRequestType::MATCH_ADMIN_ADD_INVITE; 
			$index++;
		}

		if (empty($requestDataArray)) {
			return array('status' => 200 , 'data' => array(), 'message' => 'createMatchCreationUserRequests : No Teams , Staff and Admins were invited and hence,no requests are required');
		}

		$UserRequest = new UserRequest();
		$createRequest = $UserRequest->createMultipleUserRequests($requestDataArray,$userId);
		if ($createRequest['status'] == 200) {
			return array('status' => 200, 'data' => true , 'message' => 'createMatchCreationUserRequests : success');
		} else {
			return array('status' => $createRequest['status'] , 'message' => 'createMatchCreationUserRequests : '.$createRequest['message']);
		}
	}

	private function generateMatchCheckSum($locationId, $startDateTime) {
		$checksum_data = [$locationId, $startDateTime];
		$sha1 = $this->createChecksum($checksum_data);
		$exists = $this->find('count',array(
			'conditions'=> array('Match.checksum' => $sha1)
			)
		);
		if (!$exists) {
			return array('status' => 200, 'data' => $sha1);
		} else {
			return array('status' => 105, 'message' => 'Match data conflict. Start Date Time and Location Pair Already Exists');
		}
	}

	private function __preapareMatchCreationResponse($matchId) {
		$data = $this->find('first',array(
			'conditions' => array(
				'Match.id' => $matchId
			),
			'fields' => array('id','name','is_cricket_ball_used','is_public','start_date_time','location_id','players_per_side','overs_per_innings'),
			'contain' => array(
				'Location' => array(
					'fields' => array('id','name','city_id'),
					'City' => array(
						'fields' => array('id','name')
					)
				),
				'MatchTeam' => array(
					'fields' => array('id','team_id','status'),
					'Team' => array(
						'fields' => array('id','name','image_id'),
						'ProfileImage' => array(
							'fields' => array('id','url')
						)
					)
				),
				'MatchStaff' => array(
					'fields' => array('id','user_id','role','status'),
					'User' => array(
						'fields' => array('id'),
						'Profile' => array(
							'fields' => array('user_id','first_name','middle_name','last_name','image_id'),
							'ProfileImage' => array(
								'fields' => array('id','url')
							)
						)
					)
				),
				'MatchPrivilege' => array(
					'fields' => array('id','user_id','status','is_admin'),
					'User' => array(
						'fields' => array('id'),
						'Profile' => array(
							'fields' => array('user_id','first_name','middle_name','last_name','image_id'),
							'ProfileImage' => array(
								'fields' => array('id','url')
							)
						)
					)
				)
			)
		));
		$response = array();
		$response['id'] = $data['Match']['id'];
		$response['name'] = $data['Match']['name'];
		$response['start_date_time'] = $data['Match']['start_date_time'];
		$response['players_per_side'] = $data['Match']['players_per_side'];
		$response['overs_per_innings'] = $data['Match']['overs_per_innings'];
		$response['is_cricket_ball_used'] = $data['Match']['is_cricket_ball_used'];
		$response['is_public'] = $data['Match']['is_public'];
		$response['location']['id'] = !empty($data['Location']) ? $data['Location']['id'] : null;
		$response['location']['name'] = !empty($data['Location']) ? $data['Location']['name'] : null;
		$response['location']['city'] = !empty($data['Location']['City']) ? $data['Location']['City']['name'] : null;
		
		foreach ($data['MatchTeam'] as $key => $team) {
			$response['teams'][$key]['id'] =  $team['Team']['id'];
			$response['teams'][$key]['name'] = $team['Team']['name'];
			$response['teams'][$key]['image'] = !empty($team['Team']['ProfileImage']) ? $team['Team']['ProfileImage']['url'] : null;
			$response['teams'][$key]['status'] = InvitationStatus::stringValue($team['status']);
		}
		if (empty($response['teams'])) {
			$response['teams'] = array();
		}

		foreach ($data['MatchStaff'] as $key => $staff) {
			if (!empty($staff['User']['Profile'])) {
				$response['staffs'][$key]['id'] =  $staff['User']['Profile']['user_id'];
				$response['staffs'][$key]['first_name'] = $staff['User']['Profile']['first_name'];
				$response['staffs'][$key]['middle_name'] = $staff['User']['Profile']['middle_name'];
				$response['staffs'][$key]['last_name'] = $staff['User']['Profile']['last_name'];
				$response['staffs'][$key]['name'] = $this->_prepareUserName($staff['User']['Profile']['first_name'],$staff['User']['Profile']['middle_name'],$staff['User']['Profile']['last_name']);
			} else {
				$response['staffs'][$key]['id'] = null;
				$response['staffs'][$key]['name'] = null;
				$response['staffs'][$key]['first_name'] = null;
				$response['staffs'][$key]['middle_name'] = null;
				$response['staffs'][$key]['middle_name'] = null;
			}
			$response['staffs'][$key]['image'] = !empty($staff['User']['Profile']['ProfileImage']) ? $staff['User']['Profile']['ProfileImage']['url'] : null;
			$response['staffs'][$key]['role'] = MatchStaffRole::stringValue($staff['role']);
			$response['staffs'][$key]['status'] = InvitationStatus::stringValue($staff['status']);
		}
		if (empty($response['staffs'])) {
			$response['staffs'] = array();
		}

		foreach ($data['MatchPrivilege'] as $key => $admin) {
			if (!empty($admin['User']['Profile'])) {
				$response['admins'][$key]['id'] =  $admin['User']['Profile']['user_id'];
				$response['admins'][$key]['first_name'] = $admin['User']['Profile']['first_name'];
				$response['admins'][$key]['middle_name'] = $admin['User']['Profile']['middle_name'];
				$response['admins'][$key]['last_name'] = $admin['User']['Profile']['last_name'];
				$response['admins'][$key]['name'] = $this->_prepareUserName($admin['User']['Profile']['first_name'],$admin['User']['Profile']['middle_name'],$admin['User']['Profile']['last_name']);
			} else {
				$response['admins'][$key]['id'] = null;
				$response['admins'][$key]['name'] = null;
				$response['admins'][$key]['first_name'] = null;
				$response['admins'][$key]['middle_name'] = null;
				$response['admins'][$key]['middle_name'] = null;
			}
			$response['admins'][$key]['image'] = !empty($admin['User']['Profile']['ProfileImage']) ? $admin['User']['Profile']['ProfileImage']['url'] : null;
			$response['admins'][$key]['status'] = InvitationStatus::stringValue($admin['status']);
			$response['admins'][$key]['is_admin'] = $admin['is_admin'];
		}
		if (empty($response['admins'])) {
			$response['admins'] = array();
		}

		return $response;
	}

	public function showMatch($id) {
		$match = Cache::read('show_match_'.$id);
		if( ! $match) {
			$match = $this->find('first',array(
				'conditions' => array(
					'Match.id' => $id,
					),
				'contain' => array('MatchTeam', 'MatchPlayer', 'MatchPrivilege','MatchAward','MatchInningScorecard','MatchPlayerScorecard','MatchStaff','MatchComment','MatchResult','MatchToss')
				));
			Cache::write('show_match_'.$id, $match);
		}
		if ( ! empty($match )) {
			$response = array('status' => 200, 'data' => $match);
		} else {
			$response = array('status' => 302, 'message' => 'Match Data Could Not Be Retrieved');
		}
		return $response;
	}

	public function deleteMatch($id) {
		$match = $this->showMatch($id);
		if ($match['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Match does not exist');
			return $response;
		}
		$this->_updateCache($id);
		$delete = $this->softDelete($id, array(
			'contain' => array('MatchTeam', 'MatchPlayer', 'MatchPrivilege','MatchAward','MatchInningScorecard','MatchPlayerScorecard','MatchStaff','MatchComment','MatchResult','MatchToss')
		));
		$response = array('status' => 200, 'message' => 'Deleted');
		return $response;
	}

	

	public function updateMatch($id, $name, $seriesId, $startDateTime, $endDateTime, $isPublic, $matchType, $matchScale, $isTest, $isCricketBallUsed, $oversPerInnings, $seriesMatchLevel, $locationId, $matchTeams, $matchAdmins, $matchAwards, $matchStaffs) {
		$match = $this->showMatch($id);
		if ($match['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Match does not exist');
			return $response;
		}
		$checksum_data = [$name, $locationId , $startDateTime];
		$sha1 = $this->createChecksum($checksum_data);
		$checksumExists = $this->find('first',array(
			'conditions'=> array('Match.checksum' => $sha1)
			)
		);
		if ($match['data']['Match']['start_date_time'] != $startDateTime && $startDateTime <  date('Y-m-d H:i:s', strtotime('- 10 minutes'))) {
			$response = array('status' => 905, 'message' => 'Start Date cannot be changed to past date');
			return $response;
		}
		if( ! empty ($endDateTime)) {
			if ($match['data']['Match']['end_date_time'] != $endDateTime && $endDateTime <  date('Y-m-d H:i:s', strtotime('- 10 minutes'))) {
				$response = array('status' => 905, 'message' => 'End Date cannot be changed to past date');
				return $response;
			}
			if ($startDateTime >= $endDateTime) {
				$response = array('status' => 905, 'message' => 'End Date cannot be before start date');
				return $response;
			}			
		}
		if (isset($checksumExists['Match']['id']) && $checksumExists['Match']['id'] != $id ) {
			$response = array('status' => 904, 'message' => 'Match data conflict. Not allowed');
			return $response;
		}
		$this->_updateCache($id);
		$awards = $this->prepareAwardData($matchAwards,'update');
		$matchData = array(
			'Match' => array(
			'id' => $id,
			'name' => $name,
			'checksum' => $sha1,
			'series_id' => $seriesId,
			'start_date_time' => $startDateTime,
			'end_date_time' => $endDateTime,
			'is_public' => $isPublic,
			'match_scale' => $matchScale,
			'match_type' => $matchType,
			'is_test' => $isTest,
			'is_cricket_ball_used' => $isCricketBallUsed,
			'overs_per_innings' => $oversPerInnings,
			'series_match_level' => $seriesMatchLevel,
			'location_id' => $locationId
			)
		);
		if ( ! empty($matchTeams)) {
			$this->MatchTeam->validateId();
			$matchData['MatchTeam'] = $matchTeams;
		}
		if ( ! empty($matchAdmins)) {
			$this->MatchPrivilege->validateId();
			$matchData['MatchPrivilege'] = $matchAdmins;
		}
		if ( ! empty($awards)) {
			$this->MatchAward->validateId();
			$matchData['MatchAward'] = $awards;
		}
		if ( ! empty($matchStaffs)) {
			$this->MatchStaff->validateId();
			$matchData['MatchStaff'] = $matchStaffs;
		}
		if ($this->saveAssociated($matchData, array('deep' => true))) {
			$this->_updateCache($id , $seriesId);
			$responseData = $this->showMatch($id);
			$response = array('status' => 200, 'data' => $responseData['data'], 'message' => 'Match updated');
		} else {
			$response = array('status' => 303, 'data' => $this->validationErrors ,'message' => 'Match Could Not Be Updated');
		}
		return $response;
	}

	public function prepareAwardData($match_awards,$type) {
		$award_names = array();
	 	foreach ($match_awards as $match_award) {
	 		$award_names[] = $match_award['award_name'];
	 	}
		$existing_awards = $this->MatchAward->AwardType->find('list',array(
			'conditions' => array('AwardType.award_name' => $award_names),
			'fields' => array('AwardType.award_name','AwardType.id')
		));	
		$awards = [];
		if ($type == 'create') {
			foreach ($match_awards as $match_award) {
				if (array_key_exists($match_award['award_name'], $existing_awards)) {
					$awards[] = array('value'=> $match_award['value'], 'award_type_id' => $existing_awards[$match_award['award_name']]);
				} else {
					$awards[] = array('value'=> $match_award['value'], 'AwardType' => array('award_name' => $match_award['award_name']));
				}
			}
		} else {
			foreach ($match_awards as $match_award) {
				if ( !array_key_exists('id',$match_award) ) {
					$match_award['id'] = "";
				}
				if (array_key_exists($match_award['award_name'], $existing_awards)) {
					if (isset($match_award['deleted']) && $match_award['deleted'] == true) {
						$awards[] = array('id' => $match_award['id'],'user_id'=> $match_award['user_id'], 'value'=> $match_award['value'], 'award_type_id' => $existing_awards[$match_award['award_name']], 'deleted'=> true, 'deleted_date'=> date('Y-m-d H:i:s') );
					} else {
						$awards[] = array('id' => $match_award['id'],'user_id'=> $match_award['user_id'], 'value'=> $match_award['value'], 'award_type_id' => $existing_awards[$match_award['award_name']]);
					}
				} else {
					if (isset($match_award['deleted']) && $match_award['deleted'] == true) {
						$awards[] = array('id' => $match_award['id'],'user_id'=> $match_award['user_id'],'value'=> $match_award['value'], 'AwardType' => array('award_name' => $match_award['award_name']), 'deleted'=> true, 'deleted_date'=> date('Y-m-d H:i:s'));
					} else {
						$awards[] = array('id' => $match_award['id'],'user_id'=> $match_award['user_id'],'value'=> $match_award['value'], 'AwardType' => array('award_name' => $match_award['award_name'] ));
					}
				}
			}
		}
		return $awards;
	}
  private function _updateCache($id = null , $seriesId = null){
	  $match = $this->showMatch($id);
		Cache::delete('show_match_' . $id);
		if ( ! empty($match['data']['MatchTeam'])) {
			foreach ($match['data']['MatchTeam'] as $team) {
				Cache::delete('show_team_' . $team['team_id']);
			}
		}
		if ( ! empty($match['data']['Match']['series_id'])) {
			Cache::delete('show_series_' . $match['data']['Match']['series_id']);
		}
		if ( ! empty($seriesId)) {
			Cache::delete('show_series_' . $seriesId);
		}
  }

	private function __prepareMatchResponse($matchId) {
    $resposne = array();
    $match = $this->find('first', array(
      'conditions' => array(
        'Match.id' => $matchId
      ),
      'contain' => array('MatchTeam', 'MatchPrivilege','MatchAward', 'MatchStaff', 'Location')
    ));
    if (!empty($match)) {
      $response = array(
      	'id' => $matchId,
        'series_id' => $match['Match']['series_id'],
				'start_date_time' => $match['Match']['start_date_time'],
				'end_date_time' => $match['Match']['end_date_time'],
				'is_public' => $match['Match']['is_public'],
				'match_scale' => $match['Match']['match_scale'],
				'match_type' => $match['Match']['match_type'],
				'is_test' => $match['Match']['is_test'],
				'is_cricket_ball_used' => $match['Match']['is_cricket_ball_used'],
				'overs_per_innings' => $match['Match']['overs_per_innings'],
				'series_match_level' => $match['Match']['series_match_level'],
        'location_name' => $match['Location']['name']
      );     
    }
    return $response;
  }

  public function fetchFollowedMatches($userId,$matchTypeByTime,$numOfRecords) {
  	$isPublic = 1;
  	$options =  array(
  		'joins' => array(
        array(
         'table' => 'match_followers',
         'alias' => 'MatchFollowerJoin',
         'type' => 'inner',
         'conditions' => array(
           'MatchFollowerJoin.user_id' => $userId,
           'MatchFollowerJoin.match_id = Match.id'
         	)
        )
  		),
     'conditions' => array(
        'Match.is_public' => $isPublic,
     	),    
     	'contain' => array(
     		'Location' => array(
     			'fields' => array('id','name')
     		),
         'MatchTeam' => array(
	       	'fields' => array('id','team_id'),
	       	'conditions' => array(
	       		'MatchTeam.status' => MatchTeamStatus::CONFIRMED
	       	),
	        'Team' => array(
	        	'fields' => array('id','name','image_id'),
	        	'ProfileImage' => array(
	        		'fields' => array('id','url')
	        	)
	        )
         )
      ),
     'fields' => array('id','start_date_time','name','location_id','match_type','players_per_side',
     	                     'match_scale','is_test','is_cricket_ball_used','overs_per_innings'),
     'order' => 'Match.start_date_time DESC',
     'limit' => $numOfRecords
  	);
    $currentDateTime = date('Y-m-d H:i:s');
		if ($matchTypeByTime == MatchType::UPCOMING) {
			$options['conditions']['Match.start_date_time >'] = $currentDateTime;
			$options['contain']['MatchPlayer'] = array('fields' => array('id','match_id','team_id','status'));
		} else if($matchTypeByTime == MatchType::CURRENT) {
			$options['conditions']['Match.start_date_time <='] = $currentDateTime;
			$options['conditions']['Match.end_date_time >='] = $currentDateTime;
			$options['contain']['MatchToss'] = array('fields' => array('id','winning_team_id','toss_decision'));
		} elseif ($matchTypeByTime == MatchType::FINISHED) {
			$options['conditions']['Match.end_date_time <'] = $currentDateTime;
			$options['contain']['MatchResult'] = array('fields' => array('id','result_type','winning_team_id'));
		}
		if ($matchTypeByTime == MatchType::CURRENT || $matchTypeByTime == MatchType::FINISHED) {
      $options['contain']['MatchInningScorecard'] = array('fields' => array('id','inning','team_id',
      	                                                                          'total_runs','overs','wickets'),
                                                          'order' => 'MatchInningScorecard.inning ASC');
		}

  	$data = $this->find('all', $options);
    return $data;
  }

  public function getMatchDataForWallUpdate($matchIds,$rearrageDataBoolean) {
  	$match = array();
  	$MatchData = $this->find('all' , array(
  		'conditions' => array(
  		  'Match.id' => $matchIds
  		),
  		'fields' => array('id','name','start_date_time','match_type','is_cricket_ball_used','players_per_side','overs_per_innings'),
  		'contain' => array(
  			'MatchTeam' => array(
  				'fields' => array('team_id'),
  			  'conditions' => array('MatchTeam.status' => MatchTeamStatus::CONFIRMED),
  				'Team' => array(
  					'fields' => array('id','name','image_id'),
  					'ProfileImage' => array(
  						'fields' => array('id','url')
  					)
  				)
  			),
        'MatchToss' => array(
        	'fields' => array('winning_team_id','toss_decision')
        ),
        'MatchInningScorecard' => array(
        	'fields' => array('team_id','inning','total_runs','overs','wickets'),
        	'order' => 'MatchInningScorecard.inning ASC'
        ),
        'MatchResult' => array('fields' => array('winning_team_id','result_type')),
        'Location' => array('fields' => array('id','name'))
  		)
  	));
  	if ($rearrageDataBoolean == 1) {
  		foreach ($MatchData as $data) {
  			$match[$data['Match']['id']] = $data;
  		}
  		return $match;
  	}
  	else return $MatchData;
  }

  public function getMatchTeamsArrayForMatches($matchTeamsArray,$matchPlayersPerSide,
  	                                                         $matchTeamsPlayersArray,$matchType){
		$teams = array();
		if (empty($matchTeamsArray)) {
			return $teams;
		}
		$index = 0;
		foreach($matchTeamsArray as $teamArray){
			if (!empty($teamArray['Team']['ProfileImage'])) {
				$image = $teamArray['Team']['ProfileImage']['url'];
			} else $image = NULL;
			$teams[$index]= array(
				'id' => $teamArray['Team']['id'],
				'name' => $teamArray['Team']['name'],
				'image' => $image
			);
			if ($matchType == MatchType::UPCOMING || $matchType == MatchType::RECOMMENDED) {
        $slots = $this->calcualteSlotsAvailabilityForUpcomingMatches($matchPlayersPerSide,
				                                                            $matchTeamsPlayersArray,$teamArray['id']);
			  $teams[$index]['slots_available'] = $slots;
			}
			$index = $index + 1;
		}

		return $teams;
	}

  public function calcualteSlotsAvailabilityForUpcomingMatches($matchPlayersPerSide,$matchTeamsPlayersArray,$teamId) {
    if (($matchPlayersPerSide == 0 || empty($matchPlayersPerSide)) && empty($matchTeamsPlayersArray)) {
      return 'NA';
    }
    $slotsOccupied = 0;
    foreach ($matchTeamsPlayersArray as $matchTeamPlayer) {
      if($matchTeamPlayer['team_id'] == $teamId && 
           ($matchTeamPlayer['status'] == InvitationStatus::CONFIRMED )) {
        $slotsOccupied = $slotsOccupied + 1;
      }
    }
    return ($matchPlayersPerSide - $slotsOccupied);
  }

  public function findWinMarginInMatch($innings ,$matchType ,$wicketsPerSide) {
    $victoryMargin = NULL;
    
    //Assuming the match is a 2 innings match only
    if (count($innings) < 2) {
      return $victoryMargin;
    }
    if ($innings[0]['runs'] < $innings[1]['runs']) {
      $victoryMargin = 'WON BY '.($wicketsPerSide-$innings[1]['wickets'])."  WICKETS";
    }
    else if ($innings[0]['runs'] > $innings[1]['runs']) {
      $victoryMargin = 'WON BY '.($innings[0]['runs']-$innings[1]['runs'])."  RUNS";
    }
    else $victoryMargin = "MATCH DRAW";
    
    return $victoryMargin;
  }

  public function isMatchPublic($matchId) {
  	if( empty($matchId)) {
  		return array('Status' => 322 , 'message' => 'Invalid input match id');
  	}
  	$matchData = $this->find('first',array(
  		'conditions' => array(
  			'Match.id' => $matchId
  		),
  		'fields' => array('id','is_public')
  	));
  	if (!empty($matchData)) {
  		return array('status' => 200 , 'data' => array('is_public' => $matchData['Match']['is_public']));
  	} else {
  		  return array('status' => 323 , 'message' => 'No Match Data found for Match Id');
  	}
  }

  public function searchMatches($userId,$input,$inputType,$numOfRecords) {
  	if (empty($userId) || empty($input) || empty($inputType)) {
  		return array('status' => 100 , 'message' => 'searchMatches : Invalid Input Arguments');
  	}
		$matchDataArray = array();
		$options = array(
			'fields' => array('id','start_date_time','name','owner_id','is_cricket_ball_used',
													'location_id','match_followers_count'),
			'contain' => array(
				'MatchTeam' => array(
  				'fields' => array('team_id'),
  			  'conditions' => array('MatchTeam.status' => MatchTeamStatus::CONFIRMED),
  				'Team' => array(
  					'fields' => array('id','name','image_id'),
  					'ProfileImage' => array(
  						'fields' => array('id','url')
  					)
  				)
  			),
				'Location' => array(
          'fields' => array('id','name','city_id'),
          'City' => array(
            'fields' => array('id','name')
          )
        ),
			),
			'order' => 'Match.match_followers_count DESC'
		);
		if ($inputType == MatchSearchType::NAME) {
			$options['conditions'] = array(
				'Match.name LIKE' => "%$input%",
				'OR' => array(
					'Match.is_public' => true,
					'Match.owner_id' => $userId
				),				
				'Match.is_match_data_complete' => true
			);
		} else if ($inputType == MatchSearchType::LOCATION) {
				$options['conditions'] = array(
					'Match.location_id' => $input,
					'OR' => array(
						'Match.is_public' => true,
						'Match.owner_id' => $userId
					),				
					'Match.is_match_data_complete' => true
				);
		}
		$options['limit'] = $numOfRecords;
		$matchData = $this->find('all',$options);

		foreach ($matchData as $index => $match) {
			$matchDataArray[$index]['id'] = $match['Match']['id'];
			$matchDataArray[$index]['name'] = $match['Match']['name'];
			$matchDataArray[$index]['start_date_time'] = $match['Match']['start_date_time'];
			$matchDataArray[$index]['is_cricket_ball_used'] = $match['Match']['is_cricket_ball_used'];
			$matchDataArray[$index]['match_followers_count'] = $match['Match']['match_followers_count'];

			if (!empty($match['Location'])) {
        $matchDataArray[$index]['location']['id'] = $match['Location']['id'];
        $matchDataArray[$index]['location']['name'] = $match['Location']['name'];
      } else {
        $matchDataArray[$index]['location']['id'] = null;
        $matchDataArray[$index]['location']['name'] = null;
      }
      if (!empty($match['Location']['City'])) {
        $matchDataArray[$index]['location']['city'] = $match['Location']['City']['name'];
      } else {
        $matchDataArray[$index]['location']['city'] = null;
      }

			if ($match['Match']['owner_id'] == $userId) {
				$matchDataArray[$index]['is_user_owner'] = true;
			} else {
				$matchDataArray[$index]['is_user_owner'] = false;
			}
			if (!empty($match['MatchTeam'])) {
				foreach ($match['MatchTeam'] as $teamIndex => $team) {
					if (!empty($team['Team']['ProfileImage'])) {
						$image = $team['Team']['ProfileImage']['url'];
					} else $image = null;
					$matchDataArray[$index]['Team'][$teamIndex]['id'] = $team['Team']['id'];
					$matchDataArray[$index]['Team'][$teamIndex]['name'] = $team['Team']['name'];
					$matchDataArray[$index]['Team'][$teamIndex]['image'] = $image;
				}
			} else {
				$matchDataArray[$index]['Team'] = array();
			}
			// $countOfFriendsFollowingTheMatch = $this->MatchFollower->getCountOfFriendsFollowingTheMatch($match['Match']['id'],$userFriendList);
			// if ($countOfFriendsFollowingTheMatch['status'] == 200) {
			// 	$matchDataArray[$index]['friends_followers_count'] = $countOfFriendsFollowingTheMatch['data'];
			// } else {
			// 		return array(
			// 			'status' => $countOfFriendsFollowingTheMatch['status'],
			// 			'message' => $countOfFriendsFollowingTheMatch['message']
			// 		);
			// }
		}
		return array('status' => 200, 'data' => $matchDataArray);
  }

  public function getFullMatchDataForSearch($matchId) {
	  $fullMatchData = $this->find('first',array(
	  	'conditions' => array('Match.id' => $matchId),
  		'fields' => array('id','name','start_date_time','end_date_time','is_public','players_per_side',
  											'match_type','match_scale','series_match_level','is_cricket_ball_used',
  											'overs_per_innings','owner_id','match_followers_count'),
  		'contain' => array(
  			'Series' => array(
  				'fields' => array('id','name','series_type')
  			),
  			'MatchTeam' => array(
  				'fields' => array('id','team_id','status'),
  				'Team' => array(
  					'fields' => array('id','name')
  				)
  			),
  			'MatchPlayer' => array(
  				'fields' => array('user_id','status'),
  				'User' => array(
  					'fields' => array('id'),
  					'Profile' => array(
  						'fields' => array('first_name','middle_name','last_name')
  					)
  				)
  			),
  			'MatchInningScorecard' => array(
  				'fields' => array('id','inning','team_id','total_runs','overs','wickets','wide_balls',
  													'leg_byes','byes','no_balls'),
  				'Team' => array(
  					'fields' => array('id','name')
  				)
  			),
  			'MatchPlayerScorecard' => array(
  				'fields' => array('id','user_id','inning','runs_scored','balls_faced','fours_hit','sixes_hit','wickets_taken',
  													'overs_bowled','maidens_bowled','runs_conceded','wides_bowled','no_balls_bowled',
  													'batting_contribution','bowling_contribution'),
  				'User' => array(
  					'fields' => array('id'),
  					'Profile' => array(
  						'fields' => array('first_name','middle_name','last_name')
  					)
  				)
  			),
  			'MatchStaff' => array(
  				'fields' => array('id','user_id','role'),
  				'User' => array(
  					'fields' => array('id'),
  					'Profile' => array(
  						'fields' => array('first_name','middle_name','last_name')
  					)
  				)
  			),
  			'MatchToss' => array(
  				'fields' => array('id','winning_team_id','toss_decision'),
  				'Team' => array(
  					'fields' => array('id','name')
  				)
  			),
  			'MatchResult' => array(
  				'fields' => array('id','winning_team_id','result_type')
  			),
  			'MatchPrivilege' => array(
  				'fields' => array('id','user_id'),
  				'conditions' => array(
  					'is_admin' => true
  				),
  				'User' => array(
  					'fields' => array('id'),
  					'Profile' => array(
  						'fields' => array('first_name','middle_name','last_name')
  					)
  				)
  			),
  			'MatchFollower' => array(
  				'fields' => array('id','user_id'),
  				'User' => array(
  					'fields' => array('id'),
  					'Profile' => array(
  						'fields' => array('first_name','middle_name','last_name')
  					)
  				)
  			),
  			'MatchAward' => array(
  				'fields' => array('id','award_type_id','user_id'),
  				'User' => array(
  					'fields' => array('id'),
  					'Profile' => array(
  						'fields' => array('first_name','middle_name','last_name')
  					)
  				),
  				'AwardType' => array(
  					'fields' => array('id','award_name')
  				)
  			)
  		)
  	));
		return $fullMatchData;
	}

	public function getUserNearByMatches($userId,$numOfRecords) {
		$userId = trim($userId);
		$numOfRecords = trim($numOfRecords);
		if (empty($userId)) {
			return array('status' => 100 , 'message' => 'getUserNearByMatches : Invalid Input Argumetns');
		}
		if(!$this->User->userExists($userId)) {
      return array('status' => 912, 'message' => 'getUserNearByMatches : User does not exist');
    }
		if (empty($numOfRecords)) {
			$numOfRecords = Limit::NUM_OF_USER_NEARBY_MATCHES;
		}		
		$userLocation = $this->User->getUserLocation($userId);
		if ($userLocation['status'] == 200) {
			$userLocation = $userLocation['data'];
		} else return array('status' => $userLocation['status'],'data' => $userLocation['data']);

		$locations = $this->User->getUserNearByLocations($userId);
		if ($locations['status'] == 200) {
			$locations = $locations['data'];
		} else return array('status' => $locations['status'],'data' => $locations['data']);

		$locationsIdList = array();
    foreach ($locations as $key => $data) {
      $locationsIdList[$key] = $data['id'];
    }
    $recomendedMatches = $this->MatchRecommendation->find('list',array(
    	'conditions' => array(
    		'recommended_to' => $userId 
    	),
    	'fields' => array('match_id')
    ));
    $followedMatches = $this->MatchFollower->find('list',array(
    	'conditions' => array(
    		'user_id' => $userId 
    	),
    	'fields' => array('match_id')
    ));
    $exculdedMatchIds = $this->__mergeArrays($recomendedMatches,$followedMatches);
    $matchData = array();
    $matches = $this->find('all',array(
    	'conditions' => array(
    		'Match.location_id' => $locationsIdList,
    		'Match.is_public' => 1,
    		'Match.id !=' => $exculdedMatchIds,
    		'Match.end_date_time >' => date('Y-m-d H:i:s')
    	),
    	'fields' => array('id','start_date_time'),
    	'contain' => array(
     		'Location' => array(
          'fields' => array('id' ,'name','city_id'),
          'City' => array(
            'fields' => array('id','name')
          )
        ),
        'MatchTeam' => array(
	       	'fields' => array('id','team_id'),
	       	'conditions' => array(
	       		'MatchTeam.status' => MatchTeamStatus::CONFIRMED
	       	),
	        'Team' => array(
	        	'fields' => array('id','name','image_id'),
	        	'ProfileImage' => array(
	        		'fields' => array('id','url')
	        	)
	        )
	       ),
	      'MatchInningScorecard' => array(
	      	'fields' => array('team_id','inning','total_runs','overs','wickets'),
	      	'order' => 'MatchInningScorecard.inning ASC'
	      ),
	      'MatchPlayer' => array(
	      	'fields' => array('id','match_id','team_id','status')
	      )
      ),
     'fields' => array('id','start_date_time','end_date_time','name','location_id','players_per_side',
     	                     'is_cricket_ball_used','overs_per_innings'),
     'order' => 'Match.start_date_time ASC',
    ));
		if (!empty($matches)) {
			$matchData = $this->__prepareNearByMatchesData($matches);
		}
		$nearByMatchData['location']['id'] = $userLocation['id'];
		$nearByMatchData['location']['name'] = $userLocation['city_name'];
		$nearByMatchData['total'] = $this->getCountOfNearByMatches($locationsIdList,$exculdedMatchIds);
		$nearByMatchData['matches'] = $matchData;
		return array('status' => 200, 'data' => $nearByMatchData);
	}

	public function __prepareNearByMatchesData($matchesDataArray) {
  	$preparedMatchData = array();
    foreach ($matchesDataArray as $data) {
    	$matchData = array();
    	$teams = array();
    	$date = date('Y-m-d H:i:s');
    	if ($data['Match']['end_date_time'] > $date && $data['Match']['start_date_time'] < $date) {
    		$type = MatchType::CURRENT;
    	} else $type = MatchType::UPCOMING;
 
    	if ($type == MatchType::UPCOMING) {
    		$teams = $this->getMatchTeamsArrayForMatches($data['MatchTeam'],$data['Match']['players_per_side'],
    			                                             $data['MatchPlayer'],$type);
    	} else {
        	$teams = $this->getMatchTeamsArrayForMatches($data['MatchTeam'],NULL,NULL,$type);
    	}
    	$isSlotAvaible = false;
    	foreach ($teams as $team) {
    		if (!empty($team['slots_available'])) {
    			if ($team['slots_available'] > 0) {
    				$isSlotAvaible = true;
    				break;
    			}
    		}
    	}
    	$matchData['id'] = $data['Match']['id'];
    	$matchData['name'] = $data['Match']['name'];
    	$matchData['start_date_time'] = $data['Match']['start_date_time'];
    	$matchData['is_cricket_ball_used'] = $data['Match']['is_cricket_ball_used'];
    	if (!empty($data['Location']['City'])) {
        $matchData['location']['id'] = $data['Location']['City']['id'];
        $matchData['location']['name'] = $data['Location']['City']['name'];
      } else {
          $matchData['location']['id'] = null;
          $matchData['location']['name'] = null;
      }
      $matchData['teams'] = $teams;
    	$matchData['is_slot_available'] = $isSlotAvaible;

    	if ($type == MatchType::CURRENT) {
        $innings = array();
        if (!empty($data['MatchInningScorecard'])) {
          foreach ($data['MatchInningScorecard'] as $matchInning) {
            $inning = $matchInning['inning'];
            $innings[$inning-1] = array(
              'team_id' => $matchInning['team_id'],
              'runs' =>  $matchInning['total_runs'],
              'overs' =>  $matchInning['overs'],
              'wickets' =>  $matchInning['wickets'],
            );
          }
        }
        $matchData['innings'] = $innings;
    	}
    	array_push($preparedMatchData, $matchData);
    }
    return $preparedMatchData;
  }

  public function getCountOfNearByMatches($locationsIdList,$exculdedMatchIds) {
  	return $this->find('count',array(
    	'conditions' => array(
    		'Match.location_id' => $locationsIdList,
    		'Match.is_public' => 1,
    		'Match.id !=' => $exculdedMatchIds,
    		'Match.end_date_time >' => date('Y-m-d H:i:s')
    	)
    ));
  }

  public function getUserPlayedMatchesData($userId,$matchIdsList) {
  	$matchesData = $this->find('all',array(
  		'conditions' => array(
  			'Match.id' => $matchIdsList,
  		),
  		'fields' => array('id','name','start_date_time'),
  		'contain' => array(
  			'MatchToss' => array(
  				'fields' => array('id','winning_team_id','toss_decision')
  			),
  			'MatchInningScorecard' => array(
  				'fields' => array('team_id','inning','total_runs','overs','wickets')
  			),
  			'MatchPlayerScorecard' => array(
  				'fields' => array('user_id','inning','runs_scored','overs_bowled','wickets_taken','is_batting',
  														'balls_faced','runs_conceded','batting_contribution','bowling_contribution'),
  				'conditions' => array(
  					'MatchPlayerScorecard.user_id' => $userId
  				),
  				'order' => 'MatchPlayerScorecard.inning ASC'
  			)
  		)
  	));
  	return $matchesData;
  }

  public function matchCornerForUser($userId) {
  	$userId = trim($userId);
  	if (!empty($userId)) {
  		if (!$this->_userExists($userId)) {
  			return array('status' => 103 , 'message' => 'matchCornerForUser : Invalid User ID');
  		}
  	}
  	$trendingMatches = array();
  	$trendingTeams = array();
  	$trendingTournaments = array();
  	$trendingPlayers = array();
  	return array(
  		'status' => 200,
  		'data' => array(
  			'trending_matches' => $trendingMatches,
  			'trending_teams' => $trendingTeams,
  			'trending_tournaments' => $trendingTournaments,
  			'trending_players' => $trendingPlayers
  		),
  		'message' => 'success'
  	);
  }

  public function matchCornerPublic($isPublic) {
  	if (empty($isPublic) || $isPublic != true) {
  		return array('status' => 100 , 'message' => 'matchCornerPublic : Invalid Input arguments');
  	}
  	$trendingMatches = $this->getTrendingMatches();
  	$trendingTeams = array();//$this->MatchTeam->Team->getTrendingTeams();
  	$trendingTournaments = $this->Series->getTrendingTournaments();
  	$trendingPlayers = $this->getTrendingPlayersPublic();
  	return array(
  		'status' => 200,
  		'data' => array(
  			'trending_matches' => $trendingMatches,
  			'trending_teams' => $trendingTeams,
  			'trending_tournaments' => $trendingTournaments,
  			'trending_players' => $trendingPlayers
  		),
  		'message' => 'success'
  	);
  }

  public function matchSearchPublic($filters) {
  	if (empty($filters)) {
  		return array('status' => 100 , 'message' => 'matchSearchPublic : Invalid Input Arguments');
  	}
  	$options = array(
  		'conditions' => array(
  			'Match.is_public' => true,
  			'Match.is_match_data_complete' => true
  		),
  		'fields' => array('id','name','start_date_time','end_date_time','location_id','is_cricket_ball_used'),
  		'contain' => array(
  			'MatchTeam' => array(
  				'fields' => array('id','team_id'),
  				'conditions' => array(
	       		'MatchTeam.status' => MatchTeamStatus::CONFIRMED
	       	),
	        'Team' => array(
	        	'fields' => array('id','name','image_id'),
	        	'ProfileImage' => array(
	        		'fields' => array('id','url')
	        	)
	        )
  			),
  			'Location' => array(
          'fields' => array('id' ,'name','city_id'),
          'City' => array(
            'fields' => array('id','name')
          )
        )
  		),
  		'limit' => Limit::NUM_OF_MATCHES_IN_MATCH_SEARCH_PAGE,
  		'order' => 'Match.match_followers_count DESC'
  	);
  	foreach ($filters as $key => $value) {
  		if (!empty($value)) {
	  		switch ($key) {
	  			case MatchSearchSubFilter::stringValue(MatchSearchSubFilter::TEXT):
	  				$value = trim($value);
	  				$options['conditions']['Match.name LIKE'] = "%$value%";
	  				break;
	  			case MatchSearchSubFilter::stringValue(MatchSearchSubFilter::DATE):
	  				$value = trim($value);
	  				$options['conditions']['Match.start_date_time LIKE'] = "%$value%";
	  				//$options['conditions']['Match.end_date_time >'] = $value;
	  				break;
	  			case MatchSearchSubFilter::stringValue(MatchSearchSubFilter::UPCOMING):
	  				if (empty($filters['date']) && empty($filters['ongoing']) && empty($filters['finished'])) {
	  					$options['conditions']['Match.start_date_time >'] = date('Y-m-d H:i:s');	  					
	  				}
	  				break;
	  			case MatchSearchSubFilter::stringValue(MatchSearchSubFilter::ONGOING):
	  				if (empty($filters['date']) && empty($filters['upcoming']) && empty($filters['finished'])) {
		  				$options['conditions']['Match.start_date_time <='] = date('Y-m-d H:i:s');
		  				$options['conditions']['Match.end_date_time >'] = date('Y-m-d H:i:s');
		  			}
	  				break;
	  			case MatchSearchSubFilter::stringValue(MatchSearchSubFilter::FINISHED):
	  				if (empty($filters['date']) && empty($filters['ongoing']) && empty($filters['upcoming'])) {
		  				$options['conditions']['Match.start_date_time <'] = date('Y-m-d H:i:s');
		  				$options['conditions']['Match.end_date_time <'] = date('Y-m-d H:i:s');
		  			}
	  				break;
	  			case MatchSearchSubFilter::stringValue(MatchSearchSubFilter::LEATHER):
	  				if (empty($filters['tennis'])) {
	  					$options['conditions']['Match.is_cricket_ball_used'] = true;	  					
	  				}
	  				break;
	  			case MatchSearchSubFilter::stringValue(MatchSearchSubFilter::TENNIS):
	  				if (empty($filters['leather'])) {
	  					$options['conditions']['Match.is_cricket_ball_used'] = false;
	  				}
	  				break;
	  			case MatchSearchSubFilter::stringValue(MatchSearchSubFilter::LOCATION):
	  				$nearBylocations = $this->Location->getNearbyLocation($value['latitude'], $value['longitude'], Limit::MATCH_SEARCH_DISTANCE);
	  				$locationIds = array();
	  				if ($nearBylocations['status'] == 200) {
	  					if (!empty($nearBylocations['data'])) {
		  					$locations = $nearBylocations['data'];
		  					foreach ($locations as $key => $value) {
		  						$locationIds[$key] = $value['id'];
		  					}
		  					$options['conditions']['Match.location_id'] = $locationIds;
	  					} else {
	  						return array('status' => 200, 'data' => array('matches' => array()),'message' => 'matchSearchPublic : no matches for this location');
	  					}
	  				} else {
	  					return array('status' => $nearBylocations['status'] , 'message' => $nearBylocations['message']);
	  				}
	  				break;
	  		}  			
  		}
  	}
  	$matches = $this->find('all',$options);
  	$responseData = array();
  	foreach ($matches as $key => $match) {
  		$responseData[$key]['id'] = $match['Match']['id'];
  		$responseData[$key]['name'] = $match['Match']['name'];
  		$responseData[$key]['start_date_time'] = $match['Match']['start_date_time'];
  		$responseData[$key]['is_cricket_ball_used'] = $match['Match']['is_cricket_ball_used'];
  		foreach($match['MatchTeam'] as $matchTeam){
  			$team['id'] = $matchTeam['Team']['id'];
				$team['name'] = $matchTeam['Team']['name'];
				if (!empty($matchTeam['Team']['ProfileImage'])) {
					$team['image'] = $matchTeam['Team']['ProfileImage']['url'];
				} else {
					$team['image'] = NULL;
				}
				if (empty($responseData[$key]['teamA'])) {
					$responseData[$key]['teamA'] = $team;
				} else if (empty($responseData[$key]['teamB'])) {
					$responseData[$key]['teamB'] = $team;
				}
			}
			if (!empty($match['Location'])) {
				$responseData[$key]['location']['id'] = $match['Location']['id'];
				$responseData[$key]['location']['name'] = $match['Location']['name'];
				if (!empty($match['Location']['City'])) {
					$responseData[$key]['location']['city'] = $match['Location']['City']['name'];
				} else {
					$responseData[$key]['location']['city'] = null;
				}
			} else {
				$responseData[$key]['location']['id'] = null;
				$responseData[$key]['location']['name'] = null;
				$responseData[$key]['location']['city'] = null;
			}
  	}
  	return array('status' => 200, 'data' => array('matches' => $responseData));
  }

  public function matchSearchForUser($userId,$topFilter,$subFilter) {
  	$userId = trim($userId);
  	if (!empty($userId)) {
  		if (!$this->_userExists($userId)) {
  			return array('status' => 103 , 'message' => 'matchSearchForUser : Invalid User ID');
  		}
  	}
  	$responseData = array();
  	if (empty($topFilter) && empty($subFilter)) {
  		return array('status' => 100 , 'message' => 'matchSearchForUser : Invalid Input Arguments');
  	}
  	switch ($topFilter) {
  		case MatchSearchTopFilter::ALL:
  			
  			break;
  		case MatchSearchTopFilter::FOLLOWED:
  			# code...
  			break;
  		case MatchSearchTopFilter::RECOMMENDED:
  			# code...
  			break;
  		case MatchSearchTopFilter::MY:
  			# code...
  			break;
  	}
  	return array('status' => 200, 'data' => array('matches' => $responseData));
  }

  private function getTrendingMatches() {
  	$responseData = array();
  	$matches = $this->find('all',array(
  		'conditions' => array(
  			'Match.is_public' => true,
  			'Match.is_match_data_complete' => true,
  			'Match.start_date_time >=' => date('Y-m-d', strtotime('-50 days'))
  		),
  		'contain' => array(
  			'MatchTeam' => array(
  				'fields' => array('id','team_id'),
  				'conditions' => array(
	       		'MatchTeam.status' => MatchTeamStatus::CONFIRMED
	       	),
	        'Team' => array(
	        	'fields' => array('id','name','image_id'),
	        	'ProfileImage' => array(
	        		'fields' => array('id','url')
	        	)
	        )
  			),
  			'Location' => array(
          'fields' => array('id' ,'name','city_id'),
          'City' => array(
            'fields' => array('id','name')
          )
        )
  		),
  		'order' => 'Match.match_followers_count DESC',
  		'limit' => Limit::NUM_OF_TRENDING_IN_MATCH_CORNER_LANDING
  	));

  	foreach ($matches as $key => $match) {
  		$responseData[$key]['id'] = $match['Match']['id'];
  		$responseData[$key]['name'] = $match['Match']['name'];
  		$responseData[$key]['start_date_time'] = $match['Match']['start_date_time'];
  		foreach($match['MatchTeam'] as $matchTeam){
  			$team['id'] = $matchTeam['Team']['id'];
				$team['name'] = $matchTeam['Team']['name'];
				if (!empty($matchTeam['Team']['ProfileImage'])) {
					$team['image'] = $matchTeam['Team']['ProfileImage']['url'];
				} else {
					$team['image'] = NULL;
				}
				if (empty($responseData[$key]['teamA'])) {
					$responseData[$key]['teamA'] = $team;
				} else if (empty($responseData[$key]['teamB'])) {
					$responseData[$key]['teamB'] = $team;
				}
			}
			if (!empty($match['Location'])) {
				$responseData[$key]['location']['id'] = $match['Location']['id'];
				$responseData[$key]['location']['name'] = $match['Location']['name'];
				if (!empty($match['Location']['City'])) {
					$responseData[$key]['location']['city'] = $match['Location']['City']['name'];
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

  public function getTrendingPlayersPublic() {
  	$batsmen = $this->User->MatchBatsmanScore->getTrendingBatsmenPublic();
  	$bowlers = $this->User->MatchBowlerScore->getTrendingBowlersPublic();
  	$players = array();
  	$index = 0;
  	foreach ($batsmen as $key => $batsman) {
  		$players[$key] = $batsman;
  		$players[$key]['wickets'] = 0;
  		$players[$key]['performance_score'] = $batsman['runs'];
  		$index = $index + 1;
  	}
  	foreach ($bowlers as $key1 => $bowler) {
  		$found = false;
  		foreach ($players as $key2 => $player) {
  			if ($player['id'] == $bowler['id']) {
  				$players[$key2]['wickets'] = $players[$key2]['wickets'] + $bowler['wickets'];
  				$players[$key2]['performance_score'] = $players[$key2]['runs'] + ($players[$key2]['wickets'] * Limit::RUNS_SCORED_EQUIVALENT_PER_WICKET_TAKEN);
  				$found = true;
  			}
  		}
  		if ( $found == false) {
  			$players[$index] = $bowler;
  			$players[$index]['runs'] = 0;
  			$players[$index]['performance_score'] = ($players[$index]['wickets'] * Limit::RUNS_SCORED_EQUIVALENT_PER_WICKET_TAKEN);
  			$index = $index + 1;
  		}
  	}

  	$sort = array();
  	foreach ($players as $key => $row) {
  		$sort[$key] = $row['performance_score'];
  	}
  	array_multisort($sort, SORT_DESC, $players);
  	if (count($players) > Limit::NUM_OF_TRENDING_IN_MATCH_CORNER_LANDING) {
  		$start = Limit::NUM_OF_TRENDING_IN_MATCH_CORNER_LANDING - 1;
  		$length = count($players) - Limit::NUM_OF_TRENDING_IN_MATCH_CORNER_LANDING;
  		array_splice($players,$start,$length);
  	} 	
  	return $players;
  }

  public function initialMatchUserChecks($matchId,$userId) {
  	$matchId = trim($matchId);
  	$userId = trim($userId);
  	if (empty($matchId) || empty($userId)) {
  		return array('status' => 100 , 'message' => 'initialMatchUserChecks : Invalid Input Arguments');
  	}
  	if (!$this->_userExists($userId)) {
  		return array('status' => 100 , 'message' => 'initialMatchUserChecks : User Id does not exist');
  	}
  	$response['does_match_exist'] = $this->checkIfMatchExists($matchId);
  	$response['is_match_admin'] = $this->MatchPrivilege->isUserMatchAdmin($matchId,$userId);

  	$match = $this->find('first',array(
  		'conditions' => array(
  			'Match.id' => $matchId
  		),
  		'fields' => array('id','first_team_id','second_team_id')
  	));
  	if (empty($match['Match']['first_team_id']) || empty($match['Match']['second_team_id'])) {
  		$response['is_team_admin'] = false;
  	} else {
  		$teams = [$match['Match']['first_team_id'],$match['Match']['second_team_id']];
  		$response['is_team_admin'] = $this->MatchTeam->Team->TeamPrivilege->isUserAdminOfTeams($userId,$teams);
  	}
  	return array('status' => 200, 'data' => $response);
  }

  public function matchMiniScorecard($matchId) {
  	$matchId = trim($matchId);
  	if (!$this->checkIfMatchExists($matchId)) {
  		return array('status' => 103 , 'message' => 'matchMiniScorecard : Invalid Match ID');
  	}

  	$miniScorecard = $this->MatchInningScorecard->fetchMiniScorecard($matchId);
  	if ($miniScorecard['status'] == 200) {
  		$responseData = $miniScorecard['data'];
  	} else {
  		return array('status' => $miniScorecard['status'], 'message' => 'matchMiniScorecard : '.$miniScorecard['message']);
  	}

  	$responseData['match']['status'] = $this->fetchMatchTypeByTime($matchId);

  	return array('status' => 200, 'data' => $responseData);
  }

  public function matchScorecard($matchId) {
  	$matchId = trim($matchId);
  	if (!$this->checkIfMatchExists($matchId)) {
  		return array('status' => 103 , 'message' => 'matchScorecard : Invalid Match ID');
  	}
  	$scorecard = $this->MatchInningScorecard->fetchMatchScorecard($matchId);
  	if ($scorecard['status'] == 200) {
  		$responseData = $scorecard['data'];
  	} else {
  		return array('status' => $scorecard['status'], 'message' => 'matchScorecard : '.$scorecard['message']);
  	}
  	return array('status' => 200, 'data' => $responseData);
  }

  public function matchLive($matchId) {
  	$matchId = trim($matchId);
  	if (!$this->checkIfMatchExists($matchId)) {
  		return array('status' => 103 , 'message' => 'matchLive : Invalid Match ID');
  	}
  	$userId = null;

  	$responseData['id'] = $matchId;

  	$matchDetails = $this->fetchMatchDetails($matchId);
  	if ($matchDetails['status'] == 200) {
  		$responseData['match_details'] = $matchDetails['data'];
  	} else {
  		return array('status' => $matchDetails['status'], 'message' => 'matchLive : '.$matchDetails['message']);
  	}

  	$responseData['name'] = $responseData['match_details']['name'];
  	$responseData['series_name'] = $responseData['match_details']['series_name'];
  	$responseData['match_details']['match_status'] = $this->fetchMatchTypeByTime($matchId);

  	$matchAdmins = $this->MatchPrivilege->fetchMatchAdmins($matchId);
  	if ($matchAdmins['status'] == 200) {
  		$responseData['match_admins'] = $matchAdmins['data'];
  	} else {
  		return array('status' => $matchAdmins['status'], 'message' => 'matchLive : '.$matchAdmins['message']);
  	}

  	$matchPlayers = $this->MatchPlayer->fetchMatchPlayers($matchId);
  	if ($matchPlayers['status'] == 200) {
  		$responseData['match_players'] = $matchPlayers['data'];
  	} else {
  		return array('status' => $matchPlayers['status'], 'message' => 'matchLive : '.$matchPlayers['message']);
  	}

  	$zooterBasket = $this->ZooterBucket->fetchMatchZooterBucket($matchId);
  	if ($zooterBasket['status'] == 200) {
  		$responseData['zooter_basket'] = $zooterBasket['data'];
  	} else {
  		return array('status' => $zooterBasket['status'], 'message' => 'matchLive : '.$zooterBasket['message']);
  	}

  	$headToHead = $this->fetchMatchHeadToHead($matchId);
  	if ($headToHead['status'] == 200) {
  		$responseData['head_to_head'] = $headToHead['data'];
  	} else {
  		return array('status' => $headToHead['status'], 'message' => 'matchLive : '.$headToHead['message']);
  	}

  	$WallPost = new WallPost();
  	$feeds = $WallPost->fetchMatchFeeds($userId,$matchId,null);
  	if ($feeds['status'] == 200) {
  		$responseData['newsfeed'] = $feeds['data'];
  	} else {
  		return array('status' => $feeds['status'], 'message' => 'matchLive : '.$feeds['message']);
  	}

		return array('status' => 200 , 'data' => $responseData);  	 
  }

  public function matchViewTeamPublic($matchId) {
  	$matchId = trim($matchId);
  	if (!$this->checkIfMatchExists($matchId)) {
  		return array('status' => 103 , 'message' => 'matchViewTeamPublic : Invalid Match ID');
  	}
  	$match = $this->find('first',array(
  		'conditions' => array(
  			'Match.id' => $matchId
  		),
  		'fields' => array('id','players_per_side')
  	));
  	$responseData['players_per_side'] = $match['Match']['players_per_side'];
  	$responseData['teams_invited'] = $this->MatchTeam->fetchInvitedTeamsForMatch($matchId);

  	$zooterBasket = $this->ZooterBucket->fetchMatchZooterBucket($matchId);
  	if ($zooterBasket['status'] == 200) {
  		$responseData['zooter_basket'] = $zooterBasket['data'];
  	} else {
  		return array('status' => $zooterBasket['status'], 'message' => 'matchViewTeamPublic : '.$zooterBasket['message']);
  	}
  	
  	$teams = $this->fetchTeamAndPlayersForMatchView($matchId);
  	if ($teams['status'] == 200) {
  		$responseData['teams'] = $teams['data'];
  	} else {
  		return array('status' => $teams['status'], 'message' => 'matchViewTeamPublic'.$teams['message']);
  	}
  	return array('status' => 200, 'data' =>$responseData);
  }

  public function matchImages($matchId) {
  	$matchId = trim($matchId);
  	if (!$this->checkIfMatchExists($matchId)) {
  		return array('status' => 103 , 'message' => 'matchImages : Invalid Match ID');
  	}
  	$matchImages = $this->Image->fetchMatchImages($matchId);
  	return array('status' => 200 , 'data' => array('images' => $matchImages));
  }

  public function fetchMatchDetails($matchId) {
  	$match = $this->find('first',array(
			'conditions' => array(
				'Match.id' => $matchId
			),
			'contain' => array(
				'Series' => array(
					'fields' => array('id','name')
				),
				'Location' => array(
					'fields' => array('id','name','city_id'),
					'City' => array(
						'fields' => array('id','name')
					)
				),
				'FirstUmpire' => array(
					'fields' => array('id'),
					'Profile' => array(
						'fields' => array('id','user_id','first_name','middle_name','last_name')
					)
				),
				'SecondUmpire' => array(
					'fields' => array('id'),
					'Profile' => array(
						'fields' => array('id','user_id','first_name','middle_name','last_name')
					)
				),
				'ThirdUmpire' => array(
					'fields' => array('id'),
					'Profile' => array(
						'fields' => array('id','user_id','first_name','middle_name','last_name')
					)
				),
				'Referee' => array(
					'fields' => array('id'),
					'Profile' => array(
						'fields' => array('id','user_id','first_name','middle_name','last_name')
					)
				)
			)
		));
		$location = $match['Location'];
		$matchData['id'] = $match['Match']['id'];
		$matchData['name'] = $match['Match']['name'];
		$matchData['series_name'] = !empty($match['Series']['name']) ? $match['Series']['name'] : null;
		$matchData['start_date_time'] = $match['Match']['start_date_time'];
		$matchData['end_date_time'] = $match['Match']['end_date_time'];
		$matchData['in_progress'] = $match['Match']['in_progress'];
		$matchData['location']['id'] = $location['id'];
		$matchData['location']['name'] = $location['name'];
		$matchData['location']['city'] = !empty($location['City']) ? $location['City']['name'] : null;
		$matchData['type'] = MatchType::stringValue($match['Match']['match_type']);
		$matchData['overs_per_innings'] = $match['Match']['overs_per_innings'];
		$matchData['required_overs'] = $match['Match']['required_overs'];
		$matchData['players_per_side'] = $match['Match']['players_per_side'];
		$matchData['match_followers_count'] = $match['Match']['match_followers_count'];

		if (!empty($match['Match']['level'])) {
			$matchData['match_format'] = MatchLevel::stringValue($match['Match']['level']);
		} else {
			$matchData['match_format'] = '';
		}

		if ($match['Match']['is_cricket_ball_used'] == true) {
			$matchData['ball_type'] = MatchBallType::stringValue(MatchBallType::CRICKET_BALL);
		} else if ($match['Match']['is_cricket_ball_used'] == false) {
			$matchData['ball_type'] = MatchBallType::stringValue(MatchBallType::TENNIS_BALL);
		} else {
			$matchData['ball_type'] = '';
		}

		if (!empty($match['FirstUmpire'])) {
			$matchData['first_umpire']['id'] = $match['FirstUmpire']['id'];
		} else {
			$matchData['first_umpire']['id'] = null;
		}
		if (!empty($match['FirstUmpire']['Profile'])) {
			$matchData['first_umpire']['name'] = $this->_prepareUserName($match['FirstUmpire']['Profile']['first_name'],$match['FirstUmpire']['Profile']['middle_name'],$match['FirstUmpire']['Profile']['last_name']);
		} else {
			$matchData['first_umpire']['name'] = null;
		}

		if (!empty($match['SecondUmpire'])) {
			$matchData['second_umpire']['id'] = $match['SecondUmpire']['id'];
		} else {
			$matchData['second_umpire']['id'] = null;
		}
		if (!empty($match['SecondUmpire']['Profile'])) {
			$matchData['second_umpire']['name'] = $this->_prepareUserName($match['SecondUmpire']['Profile']['first_name'],$match['SecondUmpire']['Profile']['middle_name'],$match['SecondUmpire']['Profile']['last_name']);
		} else {
			$matchData['second_umpire']['name'] = null;
		}

		if (!empty($match['ThirdUmpire'])) {
			$matchData['third_umpire']['id'] = $match['ThirdUmpire']['id'];
		} else {
			$matchData['third_umpire']['id'] = null;
		}
		if (!empty($match['ThirdUmpire']['Profile'])) {
			$matchData['third_umpire']['name'] = $this->_prepareUserName($match['ThirdUmpire']['Profile']['first_name'],$match['ThirdUmpire']['Profile']['middle_name'],$match['ThirdUmpire']['Profile']['last_name']);
		} else {
			$matchData['third_umpire']['name'] = null;
		}

		if (!empty($match['ReserveUmpire'])) {
			$matchData['reserve_umpire']['id'] = $match['ReserveUmpire']['id'];
		} else {
			$matchData['reserve_umpire']['id'] = null;
		}
		if (!empty($match['ReserveUmpire']['Profile'])) {
			$matchData['reserve_umpire']['name'] = $this->_prepareUserName($match['ReserveUmpire']['Profile']['first_name'],$match['ReserveUmpire']['Profile']['middle_name'],$match['ReserveUmpire']['Profile']['last_name']);
		} else {
			$matchData['reserve_umpire']['name'] = null;
		}

		if (!empty($match['Referee'])) {
			$matchData['referee']['id'] = $match['Referee']['id'];
		} else {
			$matchData['referee']['id'] = null;
		}
		if (!empty($match['Referee']['Profile'])) {
			$matchData['referee']['name'] = $this->_prepareUserName($match['Referee']['Profile']['first_name'],$match['Referee']['Profile']['middle_name'],$match['Referee']['Profile']['last_name']);
		} else {
			$matchData['referee']['name'] = null;
		}
		return array('status' => 200, 'data' => $matchData);
  }

  public function fetchTeamAndPlayersForMatchView($matchId) {
  	$teams = $this->fetchTeamsOfMatch($matchId);
  	if ($teams['status'] == 200) {
  		$teams = $teams['data'];
  	} else {
  		return array('status' => $teams['status'], 'message' => 'fetchTeamAndPlayersForMatchView : '.$teams['message']);
  	}
  	$response = array();
  	$teamIds[0] = $teams['teamOne']['id'];
  	$teamIds[1] = $teams['teamTwo']['id'];
  	$players = $this->MatchPlayer->getMatchPlayersAll($matchId,$teamIds);
  	$index1 = 0;
  	$index2 = 0;
  	$index3 = 0;
  	$index4 = 0;
  	$response['team_one'] = $teams['teamOne'];
  	$response['team_two'] = $teams['teamTwo'];
  	foreach ($players as $key => $player) {
  		$member = array();
			if (!empty($player['User']['Profile'])) {
				$member['id'] = $player['User']['Profile']['user_id'];
				$member['name'] = $this->_prepareUserName($player['User']['Profile']['first_name'],$player['User']['Profile']['middle_name'],$player['User']['Profile']['last_name']);;
				$member['first_name'] = $player['User']['Profile']['first_name'];
				$member['middle_name'] = $player['User']['Profile']['middle_name'];
				$member['last_name'] = $player['User']['Profile']['last_name'];
			} else {
				$member['id'] = null;
				$member['name'] = null;
				$member['first_name'] = null;
				$member['middle_name'] = null;
				$member['last_name'] = null;
			} 
			if (!empty($player['User']['Profile']['ProfileImage']))	{
				$member['image'] = $player['User']['Profile']['ProfileImage']['url'];
			}	else {
				$member['image'] = null;
			}	
			if (!empty($player['MatchPlayer']['role'])) {
   			$member['role'] = PlayerRole::stringValue($player['MatchPlayer']['role']);
   		} else {
   			$member['role'] = null;
   		}
 			$member['runs'] = !empty($player['PlayerStatistic']['total_runs']) ? $player['PlayerStatistic']['total_runs'] :0;
 			$member['matches'] = !empty($player['PlayerStatistic']['total_matches']) ? $player['PlayerStatistic']['total_matches'] :0;
 			$member['wickets'] = !empty($player['PlayerStatistic']['total_wickets_taken']) ? $player['PlayerStatistic']['total_wickets_taken'] :0;
 			$member['fours'] = !empty($player['PlayerStatistic']['total_fours_hit']) ? $player['PlayerStatistic']['total_fours_hit'] :0;
 			$member['sixes'] = !empty($player['PlayerStatistic']['total_sixes_hit']) ? $player['PlayerStatistic']['total_sixes_hit'] :0;
 			if (!empty($player['PlayerStatistic'])) {
 				$member['strike_rate'] = $this->MatchInningScorecard->prepareBatsmanStrikeRate($player['PlayerStatistic']['total_runs'],$player['PlayerStatistic']['total_balls_faced']);
 			} else {
 				$member['strike_rate'] = 'NA';
 			}

  		switch ($player['MatchPlayer']['team_id']) {
  			case $teamIds[0]:
  				if ($player['MatchPlayer']['status'] == InvitationStatus::CONFIRMED) {
  					$response['team_one']['playing_members'][$index1] = $member;
  					$index1++;
  				} else {
  					$response['team_one']['non_playing_members'][$index2] = $member;
  					$index2++;
  				}
  				break;
  			case $teamIds[1]:
  				if ($player['MatchPlayer']['status'] == InvitationStatus::CONFIRMED) {
  					$response['team_two']['playing_members'][$index3] = $member;
  					$index3++;
  				} else {
  					$response['team_two']['non_playing_members'][$index4] = $member;
  					$index4++;
  				}
  				break;
  		}
  	}
  	if (empty($response['team_one']['playing_members'])) {
  		$response['team_one']['playing_members'] = array();
  	}
  	if (empty($response['team_two']['playing_members'])) {
  		$response['team_two']['playing_members'] = array();
  	}
  	if (empty($response['team_one']['non_playing_members'])) {
  		$response['team_one']['non_playing_members'] = array();
  	}
  	if (empty($response['team_two']['non_playing_members'])) {
  		$response['team_two']['non_playing_members'] = array();
  	}
  	return array('status' => 200, 'data' => $response);
  }

  public function fetchTeamsOfMatch($matchId) {
  	$match = $this->find('first',array(
  		'conditions' => array(
  			'Match.id' => $matchId
  		),
  		'fields' => array('id','first_team_id','second_team_id'),
  		'contain' => array(
  			'FirstTeam' => array(
  				'fields' => array('id','name')
  			),
  			'SecondTeam' => array(
  				'fields' => array('id','name')
  			)
  		)
  	));
  	if (empty($match['Match'])) {
  		return array('status' => 105 , 'message' => 'fetchTeamsOfMatch : Match Id does not exist');
  	}
  	if (empty($match['FirstTeam']) || empty($match['SecondTeam'])) {
  		return array('status' => 105 , 'message' => 'fetchTeamsOfMatch : Match Teams does not exist');
  	}
  	$response['teamOne']['id'] = !empty($match['FirstTeam']['id']) ? $match['FirstTeam']['id'] : null;
  	$response['teamOne']['name'] = !empty($match['FirstTeam']['name']) ? $match['FirstTeam']['name'] : null;
  	$response['teamTwo']['id'] = !empty($match['SecondTeam']['id']) ? $match['SecondTeam']['id'] : null;
  	$response['teamTwo']['name'] = !empty($match['SecondTeam']['name']) ? $match['SecondTeam']['name'] : null;

  	return array('status' => 200, 'data' => $response);
  }

  public function fetchMatchHeadToHead($matchId) {
  	$matchId = trim($matchId);
  	if (!$this->checkIfMatchExists($matchId)) {
  		return array('status' => 103 , 'message' => 'fetchMatchHeadToHead : Invalid Match ID');
  	}
  	$teams = $this->fetchTeamsOfMatch($matchId)['data'];
  	if (empty($teams['teamOne']['id']) || empty($teams['teamTwo']['id'])) {
  		return array('status' => 200 , 'data' => array());
  	}
  	$response['team_one'] = $teams['teamOne'];
  	$response['team_two'] = $teams['teamTwo'];
  	$response['total_previous_matches'] = $this->fetchTotalClashesBetweenTeams($response['team_one']['id'],$response['team_two']['id']);
  	$response['team_one_won'] = $this->fetchTeamOneWinsAgainstTeamTwo($response['team_one']['id'],$response['team_two']['id']);
  	$response['team_two_won'] = $this->fetchTeamOneWinsAgainstTeamTwo($response['team_two']['id'],$response['team_one']['id']);
  	if ( ($response['team_one_won'] == 0) && ($response['team_two_won'] == 0) ) {
  		$response['team_one']['win_rate'] = 50;
  		$response['team_two']['win_rate'] = 50;
  	} else {
	  	$response['team_one']['win_rate'] = $this->calculateWinRate($response['team_one_won'],$response['total_previous_matches']);
	  	$response['team_two']['win_rate'] = $this->calculateWinRate($response['team_two_won'],$response['total_previous_matches']);  		
  	}
  	$response['no_result'] = $response['total_previous_matches'] - ($response['team_one_won']+$response['team_two_won']);
  	return array('status' => 200 , 'data' => $response);
  }

  public function fetchTotalClashesBetweenTeams($teamOneId,$teamTwoId) {
  	return $this->find('count',array(
  		'conditions' => array(
  			'OR' => array(
  				array(
  					'AND' => array(
  						'Match.first_team_id' => $teamOneId,
  						'Match.second_team_id' => $teamTwoId,
  						'Match.first_team_id !=' => null,
  						'Match.second_team_id !=' => null
  					)
  				),
  				array(
  					'AND' => array(
  						'Match.first_team_id' => $teamTwoId,
  						'Match.second_team_id' => $teamOneId,
  						'Match.first_team_id !=' => null,
  						'Match.second_team_id !=' => null
  					)
  				)
  			)
  		)
  	));
  }

  public function fetchTeamOneWinsAgainstTeamTwo($forTeamId,$againstTeamId) {
  	return $this->find('count',array(
  		'conditions' => array(
  			'Match.winning_team_id' => $forTeamId,
  			'OR' => array(
  				array(
  					'AND' => array(
  						'Match.first_team_id' => $forTeamId,
  						'Match.second_team_id' => $againstTeamId,
  						'Match.first_team_id !=' => null,
  						'Match.second_team_id !=' => null
  					)
  				),
  				array(
  					'AND' => array(
  						'Match.first_team_id' => $againstTeamId,
  						'Match.second_team_id' => $forTeamId,
  						'Match.first_team_id !=' => null,
  						'Match.second_team_id !=' => null
  					)
  				)
  			)
  		)
  	));
  }

  public function getMatchDataForMatchFeed($matchId) {
  	return $this->find('first',array(
  		'conditions' => array(
  			'Match.id' => $matchId
  		),
  		'fields' => array('id','name','start_date_time','match_type','level','is_cricket_ball_used','players_per_side','overs_per_innings','first_team_id','second_team_id','toss_decision','toss_winning_team_id','result_type','winning_team_id'),
	     'contain' => array(
	      'FirstTeam' => array(
	        'fields' => array('id','name','image_id'),
	        'ProfileImage' => array(
	        	'fields' => array('id','url')
	        )
	      ),
	      'SecondTeam' => array(
	        'fields' => array('id','name','image_id'),
	        'ProfileImage' => array(
	        	'fields' => array('id','url')
	        )
	      ),
	      'TossWinningTeam' => array(
	        'fields' => array('id','name','image_id'),
	        'ProfileImage' => array(
	        	'fields' => array('id','url')
	        )
	      ),
	      'MatchWinningTeam' => array(
	        'fields' => array('id','name','image_id'),
	        'ProfileImage' => array(
	        	'fields' => array('id','url')
	        )
	      ),
	      'Location' => array(
	        'fields' => array('id','name'),
	        'City' => array(
	          'fields' => array('id','name')
	        )
	      ),
	      'MatchInningScorecard' => array(
	        'fields' => array('team_id','inning','total_runs','overs','wickets'),
	        'order' => 'MatchInningScorecard.inning ASC'
	      ),
	    ),
  	));         
  }

  public function isUserMatchOwner($matchId,$userId) {
  	return $this->find('count',array(
  		'conditions' => array(
  			'Match.id' => $matchId,
  			'Match.owner_id' => $userId
  		)
  	));
  }

  public function getMatchOwnerId($matchId) {
  	$matchId = trim($matchId);
  	if (empty($matchId)) {
  		return array('status' => 100 , 'message' => 'getMatchOwnerId : Invalid Input Arguments');
  	}
  	$data =  $this->find('first',array(
  		'conditions' => array(
  			'Match.id' => $matchId
  		),
  		'fields' => array('owner_id')
  	));
  	if (empty($data['Match'])) {
  		return array('status' => 106 , 'message' => 'getMatchOwnerId : Invalid Match Id sent');
  	}
  	if (empty($data['Match']['owner_id'])) {
  		return array('status' => 106 , 'message' => 'getMatchOwnerId : No Owner exists for given Match');
  	}
  	return array('status' => 200, 'data' => $data['Match']['owner_id']);
  }

  private function calculateWinRate($wins,$totalClashes) {
		$winRate = null;
		if (!empty($totalClashes) && ($totalClashes != 0)) {
			if (empty($wins)) {
				$winRate = 0;
			} else {
				$winRate = round((100 * ($wins / $totalClashes)),1,PHP_ROUND_HALF_UP);					
			}
		} else {
			$winRate = 'NA';
		}	
		return $winRate;
  }

  public function fetchMatchTypeByTime($matchId) {
  	$match = $this->find('first',array(
  		'conditions' => array(
  			'Match.id' => $matchId
  		),
  		'fields' => array('id','start_date_time','end_date_time')
  	));
  	$match = $match['Match'];
  	$date = date('Y-m-d H:i:s');
  	$startDate = $match['start_date_time'];
  	$endDate = $match['end_date_time'];
  	if ( ($startDate > $date) && ($endDate > $date) ) {
  		$response = 'upcoming';
  	} elseif ( ($startDate <= $date) && ($endDate > $date) ) {
  		$response = 'ongoing';
  	} elseif ( ($startDate < $date) && ($endDate < $date) ) {
  		$response = 'finished';
  	} else {
  		$response = '';
  	}
  	return $response;
  }

  public function checkMatchExists($matchId) {
  	return array('status' => 200 , 'data' => $this->checkIfMatchExists($matchId));
  }

  public function getTournamentFollowersCount($seriesId) {
  	$data = $this->find('all',array(
  		'conditions' => array(
  			'Match.series_id' => $seriesId
  		),
  		'fields' => array('sum(Match.match_followers_count) as total'),
  		'order' => 'total'
  	));
  	return $data[0][0]['total'];
  }

  public function checkIfMatchExists($matchId) {
  	return $this->find('count',array(
  		'conditions' => array(
  			'Match.id' => $matchId
  		)
  	));
  }

	private function __mergeArrays($array1,$array2) {
		$result = array();
		$index = 0;
		foreach ($array1 as $value) {
			$result[$index] = $value;
			$index = $index + 1;
		}
		foreach ($array2 as $value) {
			if (!in_array($value, $result)) {
				$result[$index] = $value;
				$index = $index + 1;
			}			
		}
		return $result;
	}

}

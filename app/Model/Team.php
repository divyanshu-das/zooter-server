<?php
App::uses('AppModel', 'Model');
App::uses('TeamSearchType', 'Lib/Enum');
App::uses('Limit', 'Lib/Enum');
App::uses('InvitationStatus', 'Lib/Enum');
App::uses('PlayerRole', 'Lib/Enum');
App::uses('TeamStaffRole', 'Lib/Enum');
App::uses('SpecialUsers', 'Lib/Enum');
App::uses('TeamSearchSubFilter', 'Lib/Enum');
App::uses('TeamSearchTopFilter', 'Lib/Enum');
App::uses('UserRequestType', 'Lib/Enum');
/**
 * Team Model
 *
 * @property Location $Location
 * @property MatchInningScorecard $MatchInningScorecard
 * @property Match $Match
 * @property Series $Series
 */
class Team extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

	/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'is_public' => array(
			'numeric' => array(
				'rule' => array('boolean'),
				'message' => 'is_public not valid',
				// 'required' => true,
				// 'allowEmpty' => false,
			)
		),
		'registration_date' => array(
			'pastDate' => array(
				'rule' => array('pastDateTime'),
				// 'required' => false,
				// 'allowEmpty' => false,
			)
		),
		'owner_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'User id is not numeric',
				//'allowEmpty' => true
			),
			// 'userExist' => array(
			// 	'rule' => array('userExist'),
			// 	'allowEmpty'=> true
			// )
		),
		'location_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Not Valid location',
				// 'required' => true,
				// 'allowEmpty' => false,
			)
		),
		'checksum' => array(
			'alphaNumeric' => array(
				'rule' => array('alphaNumeric'),
				'message' => 'Not Valid checksum',
				// 'required' => true,
				// 'allowEmpty' => false,
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
		'ProfileImage' => array(
			'className' => 'Image',
			'foreignKey' => 'image_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'CoverImage' => array(
			'className' => 'Image',
			'foreignKey' => 'cover_image_id',
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
		'MatchTeam' => array(
			'className' => 'MatchTeam',
			'foreignKey' => 'team_id',
		),
		'MatchInningScorecard' => array(
			'className' => 'MatchInningScorecard',
			'foreignKey' => 'team_id',
		),
		'MatchResult' => array(
			'className' => 'MatchResult',
			'foreignKey' => 'winning_team_id',
		),
		'MatchToss' => array(
			'className' => 'MatchToss',
			'foreignKey' => 'winning_team_id',
		),
		'TeamPlayer' => array(
			'className' => 'TeamPlayer',
			'foreignKey' => 'team_id',
		),
		'TeamPrivilege' => array(
			'className' => 'TeamPrivilege',
			'foreignKey' => 'team_id',
		),
		'TeamStaff' => array(
			'className' => 'TeamStaff',
			'foreignKey' => 'team_id',
		),
		'TeamPlayerHistory' => array(
			'className' => 'TeamPlayerHistory',
			'foreignKey' => 'team_id',
		),
		'MatchPlayer' => array(
			'className' => 'MatchPlayer',
			'foreignKey' => 'team_id',
		),
		'TeamOne' => array(
      'className' => 'Match',
      'foreignKey' => 'first_team_id',
    ),
    'TeamTwo' => array(
      'className' => 'Match',
      'foreignKey' => 'second_team_id',
    ),
    'TeamWinningToss' => array(
      'className' => 'Match',
      'foreignKey' => 'toss_winning_team_id',
    ),
    'TeamWinningMatch' => array(
      'className' => 'Match',
      'foreignKey' => 'winning_team_id',
    )
	);


/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Match' => array(
			'className' => 'Match',
			'joinTable' => 'match_teams',
			'foreignKey' => 'team_id',
			'associationForeignKey' => 'match_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),
		'Series' => array(
			'className' => 'Series',
			'joinTable' => 'series_teams',
			'foreignKey' => 'team_id',
			'associationForeignKey' => 'series_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);

	public function showTeam($id) {
		$team = Cache::read('show_team_' . $id);
    if ( ! $team) {
			$team = $this->find('first',array(
				'conditions' => array(
					'Team.id' => $id,
					),
				'contain' => array ('Match','Series','MatchInningScorecard','MatchResult','MatchToss',
															'TeamPlayer','TeamStaff','TeamPrivilege','ProfileImage','CoverImage')
				));
			Cache::write('show_team_'.$id, $team);
		}
		if ( ! empty($team)) {
			$response = array('status' => 200, 'data' => $team,  'message' => 'data');
		} else {
			$response = array('status' => 302, 'message' => 'Team Data Could Not Be Retrieved');
		}
		return $response;
	}

	public function deleteTeam($id){
		$team = $this->showTeam($id);
		if ( $team['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Team does not exist');
			return $response;
		}
		$this->_updateCache($id);
		$delete = $this->softDelete($id, array(
			'contain' => array ('Match','Series','TeamPlayer','TeamStaff','TeamPrivilege')
			));
		$response = array('status' => 200, 'data'=> $delete , 'message' => 'Deleted');
		return $response;
	}
		
	public function createNewTeam($userId,$fields) {
		if (empty($fields) || empty($userId)) {
			return array('status' => 100 , 'message' => 'createNewTeam : Invalid Input Arguments');
		}
		if (!$this->_userExists($userId)) {
      return array('status' => 332 , 'message' => 'createNewTeam : User Does not exist');
    }

    $dataSource = $this->getDataSource();
    $dataSource->begin();
		$data = array();
		foreach ($fields as $key => $value) {
			if(!empty($value)) {
				switch ($key) {
					case 'basic_info':
						$basicInfo = $this->__prepareBasicInfoTeamCreate($value);
						if ($basicInfo['status'] == 200) {
							$data['Team'] = $basicInfo['data'];
							$data['Team']['owner_id'] = $userId;
						} else {
							return array('status' => $basicInfo['status'], 'message' => 'createNewTeam : '.$basicInfo['message']);
						}
						break;
					case 'players':
						$players = $this->__preparePlayersTeamCreate($value);
						if ($players['status'] == 200) {
							$data['TeamPlayer'] = $players['data'];
						} else {
							return array('status' => $players['status'], 'message' => 'createNewTeam : '.$players['message']);
						}
						break;
					case 'admins':
						$admins = $this->__prepareAdminsTeamCreate($value,$userId);
						if ($admins['status'] == 200) {
							$data['TeamPrivilege'] = $admins['data'];
						} else {
							return array('status' => $admins['status'], 'message' => 'createNewTeam : '.$admins['message']);
						}
						break;
					case 'staffs':
						$staffs = $this->__prepareStaffsTeamCreate($value);
						if ($staffs['status'] == 200) {
							$data['TeamStaff'] = $staffs['data'];
						} else {
							return array('status' => $staffs['status'], 'message' => 'createNewTeam : '.$staffs['message']);
						}
						break;
				}
			}
		}

		if (empty($data) || empty($data['Team'])) {
			return array('status' => 108, 'message' => 'createNewTeam : Basic Information for Team Creaton is mandatory');
		}
		if ($this->saveAssociated($data,array('deep' => true))) {
			$newTeamId = $this->getLastInsertID();
			$createRequest = $this->createTeamCreationUserRequests($newTeamId,$userId);
			if ($createRequest['status'] == 200) {
				$responseData = $this->__preapareTeamCreationResponse($newTeamId);
				$response = array('status' => 200, 'data' => $responseData);
			} else {
				$response = array('status' => $createRequest['status'], 'message' => 'createNewTeam : '.$createRequest['message']);
			}			
		} else {
			$response = array('status' => 109, 'message' => 'createNewTeam : Team Could not be created');
		}

		if ($response['status'] == 200) {
      $dataSource->commit();
    } else {
    	$dataSource->rollback();
    }
		return $response;
	}

	private function __prepareBasicInfoTeamCreate($basicInfoInputData) {
		$basicInfoData = array();
		foreach ($basicInfoInputData as $key => $value) {
			if ( !empty($basicInfoData) && array_key_exists($key, $basicInfoData)) {
				return array('status' => 108, 'message' => 'repeated'.$key.'key in coming in Team Basic Info Tuple');
			}
			if(!empty($value)) {
				switch ($key) {
					case 'name':
						$basicInfoData['name'] = trim($value);
						break;
					case 'team_tagline':
						$basicInfoData['team_tagline'] = trim($value);
						break;
					case 'is_public':
						$isPublic = trim($value);
						if ($isPublic == true) {
							$basicInfoData['is_public'] = true;
						} elseif ($isPublic == false) {
							$basicInfoData['is_public'] = false;
						} else {
							return array('status' => 104, 'message' => 'Invalid is_public field');
						}
						break;
					case 'registration_date':
						$basicInfoData['registration_date'] = trim($value);
						break;
					case 'profile_image_url':
						$basicInfoData['ProfileImage']['url'] = trim($value);
						break;
					case 'cover_image_url':
						$basicInfoData['CoverImage']['url'] = trim($value);
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

		if ( (!empty($basicInfoData['name'])) || (!empty($basicInfoData['location_id'])) || (!empty($basicInfoData['is_public'])) ) {
			$checkSum = $this->generateTeamCheckSum($basicInfoData['name'],$basicInfoData['location_id']);
			if ($checkSum['status'] == 200) {
				$basicInfoData['checksum'] = $checkSum['data'];
				$response = array('status' => 200, 'data' => $basicInfoData);
			} else {
				$response = array('status' => $checkSum['status'], 'message' => $checkSum['message']);
			}
		} else {
			$response = array('status' => 105, 'message' => '[name, location, is_public] are mandatory fields');
		}
		return $response;
	}

	private function __preparePlayersTeamCreate($playersInputData) {
		$listOfPlayerids = array();
		$playersData = array();
		foreach ($playersInputData as $key1 => $value1) {
			foreach ($value1 as $key2 => $value2) {
				if ( !empty($playersData[$key1]) && array_key_exists($key2, $playersData[$key1])) {
					return array('status' => 108, 'message' => 'repeated'.$key2.'key in coming in Team Players Tuple');
				}
				if(!empty($value2)) {
					switch ($key2) {
						case 'user_id':
							$userId = trim($value2);
							if (! in_array($userId, $listOfPlayerids)) {
								array_push($listOfPlayerids, $userId);
							} else {
								return array('status' => 109, 'message' => 'repeated data in Team Players Tuple Array');
							}
							if (!$this->_userExists($userId)) {
								return array('status' => 106, 'message' => 'Invalid user Id sent in Team players Invitation');
							}
							$playersData[$key1]['user_id'] = $userId;
							break;
						case 'role':
							$role = trim($value2);
							if (in_array($role,PlayerRole::options())) {
								$playersData[$key1]['role'] = PlayerRole::intValue($role);
							} else {
								return array('status' => 105, 'message' => 'Invalid role string sent in Team Player Invitation');
							}					
							break;
					}
				}
			}
			if (empty($playersData[$key1]['user_id'])) {
				return array('status' => 105, 'message' => 'user ID is mandatory field for team player invitation');
			}
			$playersData[$key1]['status'] = InvitationStatus::INVITED;
		}

		if (empty($playersData)) {
			return array('status' => 105, 'message' => 'Team Players data is empty');
		} else {
			return array('status' => 200, 'data' => $playersData);
		}
	}

	private function __prepareAdminsTeamCreate($adminsInputData,$userId) {
		$listOfAdminsId = array();
		$adminsData = array();
		foreach ($adminsInputData as $key1 => $value1) {
			foreach ($value1 as $key2 => $value2) {
				if ( !empty($adminsData[$key1]) && array_key_exists($key2, $adminsData[$key1])) {
					return array('status' => 108, 'message' => 'repeated'.$key2.'key in coming in Team Admins Tuple');
				}
				if(!empty($value2)) {
					switch ($key2) {
						case 'user_id':
							$userId = trim($value2);
							if (! in_array($userId, $listOfAdminsId)) {
								array_push($listOfAdminsId, $userId);
							} else {
								return array('status' => 109, 'message' => 'repeated data in Team Admins');
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

	private function __prepareStaffsTeamCreate($staffsInputData) {
		$listOfStaffsIds = array();
		$staffsData = array();
		foreach ($staffsInputData as $key1 => $value1) {
			foreach ($value1 as $key2 => $value2) {
				if ( !empty($staffsData[$key1]) && array_key_exists($key2, $staffsData[$key1])) {
					return array('status' => 108, 'message' => 'repeated'.$key2.'key in coming in Team Staffs Tuple');
				}
				if(!empty($value2)) {
					switch ($key2) {
						case 'user_id':
							$userId = trim($value2);
							if (! in_array($userId, $listOfStaffsIds)) {
								array_push($listOfStaffsIds, $userId);
							} else {
								return array('status' => 109, 'message' => 'repeated data in Team Staffs');
							}
							if (!$this->_userExists($userId)) {
								return array('status' => 106, 'message' => 'Invalid user Id sent in Team staff Invitation');
							}
							$staffsData[$key1]['user_id'] = $userId;
							break;
						case 'role':
							$role = trim($value2);
							if (in_array($role,TeamStaffRole::options())) {
								$staffsData[$key1]['role'] = TeamStaffRole::intValue($role);
							} else {
								return array('status' => 105, 'message' => 'Invalid role string sent in Team Staff Invitation');
							}					
							break;
					}
				}
			}
			if (empty($staffsData[$key1]['user_id'])) {
				return array('status' => 105, 'message' => 'user ID is mandatory field for team staff invitation');
			}
			$staffsData[$key1]['status'] = InvitationStatus::INVITED;
		}

		if (empty($staffsData)) {
			return array('status' => 105, 'message' => 'Team Staffs data is empty');
		} else {
			return array('status' => 200, 'data' => $staffsData);
		}
	}

	private function generateTeamCheckSum($name, $locationId) {
		$checksum_data = [$name, $locationId];
		$sha1 = $this->createChecksum($checksum_data);
		$exists = $this->find('count',array(
			'conditions'=> array('Team.checksum' => $sha1)
			)
		);
		if (!$exists) {
			return array('status' => 200, 'data' => $sha1);
		} else {
			return array('status' => 105, 'message' => 'Team data conflict. Name and Location Pair Already Exists');
		}
	}

	private function __preapareTeamCreationResponse($teamId) {
		$data = $this->find('first',array(
			'conditions' => array(
				'Team.id' => $teamId
			),
			'fields' => array('id','name','team_tagline','cover_image_id','image_id','location_id','registration_date'),
			'contain' => array(
				'ProfileImage' => array(
					'fields' => array('id','url')
				),
				'CoverImage' => array(
					'fields' => array('id','url')
				),
				'Location' => array(
					'fields' => array('id','name','city_id'),
					'City' => array(
						'fields' => array('id','name')
					)
				),
				'TeamPlayer' => array(
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
				'TeamStaff' => array(
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
				'TeamPrivilege' => array(
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
		$response['id'] = $data['Team']['id'];
		$response['name'] = $data['Team']['name'];
		$response['team_tagline'] = $data['Team']['team_tagline'];
		$response['registration_date'] = $data['Team']['registration_date'];
		$response['profile_image_url'] = !empty($data['ProfileImage']) ? $data['ProfileImage']['url'] : null;
		$response['cover_image_url'] = !empty($data['ProfileImage']) ? $data['ProfileImage']['url'] : null;
		$response['location']['id'] = !empty($data['Location']) ? $data['Location']['id'] : null;
		$response['location']['name'] = !empty($data['Location']) ? $data['Location']['name'] : null;
		$response['location']['city'] = !empty($data['Location']['City']) ? $data['Location']['City']['name'] : null;
		
		foreach ($data['TeamPlayer'] as $key => $player) {
			if (!empty($player['User']['Profile'])) {
				$response['players'][$key]['id'] =  $player['User']['Profile']['user_id'];
				$response['players'][$key]['first_name'] = $player['User']['Profile']['first_name'];
				$response['players'][$key]['middle_name'] = $player['User']['Profile']['middle_name'];
				$response['players'][$key]['last_name'] = $player['User']['Profile']['last_name'];
				$response['players'][$key]['name'] = $this->_prepareUserName($player['User']['Profile']['first_name'],$player['User']['Profile']['middle_name'],$player['User']['Profile']['last_name']);
			} else {
				$response['players'][$key]['id'] = null;
				$response['players'][$key]['name'] = null;
				$response['players'][$key]['first_name'] = null;
				$response['players'][$key]['middle_name'] = null;
				$response['players'][$key]['middle_name'] = null;
			}
			$response['players'][$key]['image'] = !empty($player['User']['Profile']['ProfileImage']) ? $player['User']['Profile']['ProfileImage']['url'] : null;
			$response['players'][$key]['role'] = PlayerRole::stringValue($player['role']);
			$response['players'][$key]['status'] = InvitationStatus::stringValue($player['status']);
		}
		if (empty($response['players'])) {
			$response['players'] = array();
		}

		foreach ($data['TeamStaff'] as $key => $staff) {
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
			$response['staffs'][$key]['role'] = TeamStaffRole::stringValue($staff['role']);
			$response['staffs'][$key]['status'] = InvitationStatus::stringValue($staff['status']);
		}
		if (empty($response['staffs'])) {
			$response['staffs'] = array();
		}

		foreach ($data['TeamPrivilege'] as $key => $admin) {
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

	public function createTeamCreationUserRequests($teamId,$userId) {
		$data = $this->find('first',array(
			'conditions' => array(
				'Team.id' => $teamId
			),
			'fields' => array('id'),
			'contain' => array(
				'TeamPlayer' => array(
					'fields' => array('id','user_id')
				),
				'TeamStaff' => array(
					'fields' => array('id','user_id')
				),
				'TeamPrivilege' => array(
					'conditions' => array(
						'TeamPrivilege.status' => InvitationStatus::INVITED
					),
					'fields' => array('id','user_id')
				)
			)
		));
		
		$index = 0;
		$requestDataArray = array();

		foreach ($data['TeamPlayer'] as $key => $value) {
			$requestDataArray[$index]['request_id'] = $value['id'];
			$requestDataArray[$index]['user_id'] = $value['user_id']; 
			$requestDataArray[$index]['type'] = UserRequestType::TEAM_PLAYER_ADD_INVITE; 
			$index++;
		}

		foreach ($data['TeamStaff'] as $key => $value) {
			$requestDataArray[$index]['request_id'] = $value['id'];
			$requestDataArray[$index]['user_id'] = $value['user_id']; 
			$requestDataArray[$index]['type'] = UserRequestType::TEAM_STAFF_ADD_INVITE; 
			$index++;
		}

		foreach ($data['TeamPrivilege'] as $key => $value) {
			$requestDataArray[$index]['request_id'] = $value['id'];
			$requestDataArray[$index]['user_id'] = $value['user_id']; 
			$requestDataArray[$index]['type'] = UserRequestType::TEAM_ADMIN_ADD_INVITE; 
			$index++;
		}

		if (empty($requestDataArray)) {
			return array('status' => 200 , 'data' => array(), 'message' => 'createTeamCreationUserRequests : No player , staff and admins were invited and hence,no requests are required');
		}

		$UserRequest = new UserRequest();
		$createRequest = $UserRequest->createMultipleUserRequests($requestDataArray,$userId);
		if ($createRequest['status'] == 200) {
			return array('status' => 200, 'data' => true , 'message' => 'createTeamCreationUserRequests : success');
		} else {
			return array('status' => $createRequest['status'] , 'message' => 'createTeamCreationUserRequests : '.$createRequest['message']);
		}
	}

	public function updateTeam($id, $name,  $userId,  $locationId, $isPublic = true, $registrationDate = null, $teamTagline = null, $playersRolesStatus = null, $staffsRolesStatus = null,  $teamAdmins = null) {
		$team = $this->showTeam($id);
		if ($team['status'] != 200 ){
			$response = array('status' => 905, 'message' => 'Team does not exist');
			return $response;
		}
		$checksum_data = [$name, $locationId];
		$sha1 = $this->createChecksum($checksum_data);
		$checksumExists = $this->find('count',array(
			'conditions'=> array('Team.checksum' => $sha1)
			)
		);
		if ( isset($checksumExists['Team']['id']) && $checksumExists['Team']['id'] != $id ) {
			$response = array('status' => 904, 'message' => 'Team data conflict. Not allowed');
			return $response;
		}
		$this->_updateCache($id);
		$teamData = array(
			'Team' => array(
				'id' => $id,
				'name' => $name,
				'owner_id' => $userId,
				'is_public' => $isPublic,
				'registration_date' => $registrationDate,
				'location_id' => $locationId,
				'checksum' => $sha1,
				'team_tagline' => $teamTagline
			)
		);
		if ( ! empty($playersRolesStatus)) {
			$this->TeamPlayer->validateId();
			$teamData['TeamPlayer'] = $playersRolesStatus;
		}
		if ( ! empty($staffsRolesStatus) ) {
			$this->TeamStaff->validateId();
			$teamData['TeamStaff'] = $staffsRolesStatus;
		}
		if ( ! empty($teamAdmins) ) {
			$this->TeamPrivilege->validateId();
			$teamData['TeamPrivilege'] = $teamAdmins;
		}
		if ( $this->saveAssociated($teamData, array('deep' => true))) {
			$this->_updateCache($id);
			$responseData = $this->showTeam($id);
			$response = array('status' => 200, 'data' => $responseData['data'], 'message' => 'Team Updated');
		} else {
			$response = array('status' => 303 ,'message' => 'Team Could Not Be Updated');
			pr($this->validationErrors );
		}
		return $response;
	}

	public function searchTeams($userId,$input,$inputType,$numOfRecords) {
		if (empty($userId) || empty($input) || empty($inputType)) {
  		return array('status' => 100 , 'message' => 'serachTeams : Invalid Input Arguments');
  	}
  	if (!$this->User->userExists($userId)) {
  		return array('status' => 335 , 'message' => 'serachTeams : User Does not exists');
  	} 
  	$teamDataArray = array();
  	$options = array(
  		'fields' => array('id','name','image_id','team_tagline','location_id','owner_id'),
  		'contain' => array(
  			'ProfileImage' => array(
  				'fields' => array('id','url')
  			),
  			'Location' => array(
          'fields' => array('id','name','city_id'),
          'City' => array(
            'fields' => array('id','name')
          )
        ),
  		)
  	);
  	if ($inputType == TeamSearchType::NAME) {
			$options['conditions'] = array(
				'Team.name LIKE' => "%$input%",
				'OR' => array(
					'Team.is_public' => true,
					'Team.owner_id' => $userId
				)			
			);
		} elseif ($inputType == TeamSearchType::LOCATION) {

		}
		$options['limit'] = $numOfRecords;		
		$teamData = $this->find('all',$options);

		foreach ($teamData as $index => $team) {
			$teamDataArray[$index]['id'] = $team['Team']['id'];
			$teamDataArray[$index]['name'] = $team['Team']['name'];
			if (!empty($team['ProfileImage'])) {
				$teamDataArray[$index]['image'] = $team['ProfileImage']['url'];
			}
			else {
				$teamDataArray[$index]['image'] = NULL;
			}
			$teamDataArray[$index]['team_tagline'] = $team['Team']['team_tagline'];

			if (!empty($team['Location'])) {
        $teamDataArray[$index]['location']['id'] = $team['Location']['id'];
        $teamDataArray[$index]['location']['name'] = $team['Location']['name'];
      } else {
        $teamDataArray[$index]['location']['id'] = null;
        $teamDataArray[$index]['location']['name'] = null;
      }
      if (!empty($team['Location']['City'])) {
        $teamDataArray[$index]['location']['city'] = $team['Location']['City']['name'];
      } else {
        $teamDataArray[$index]['location']['city'] = null;
      }

			if ($team['Team']['owner_id'] == $userId) {
				$teamDataArray[$index]['is_user_owner'] = true;
			} else {
				$teamDataArray[$index]['is_user_owner'] = false;
			}
		}
		return array('status' => 200 , 'data' => $teamDataArray);
	}

	public function searchTeamsForMatchInvitation($userId,$matchId,$input) {
		$matchId = trim($matchId);
		$userId = trim($userId);
		$input = trim($input);
		if (empty($userId) || empty($matchId) || empty($input)) {
  		return array('status' => 100, 'message' => 'searchTeamsForMatchInvitation : Invalid Input Arguments');
  	}
  	if (!$this->_userExists($userId)) {
			return array('status' => 103 , 'message' => 'searchTeamsForMatchInvitation : Invalid User or invite_user ID');
		}
		if (!$this->MatchTeam->Match->checkIfMatchExists($matchId)) {
  		return array('status' => 103 , 'message' => 'searchTeamsForMatchInvitation : Invalid Match ID');
  	}
  	
  	$numOfRecords = Limit::NUM_OF_TEAMS_SEARCH_IN_MATCH_INVITATION;
  	$teams = $this->searchTeams($userId,$input,TeamSearchType::NAME,$numOfRecords);
  	if ($teams['status'] != 100) {
      $teams = $teams['data'];
    } else {
    	return array('status' => $teams['status'], 'message' => $teams['message']);
    }
    if (empty($teams)) {
    	return array('status' => 200, 'data' => array('teams' => $teams));
    }
    //pr($teams);die;
    $matchTeams = $this->MatchTeam->getMatchTeams($matchId);	
    $response = array();
    foreach ($teams as $key => $team) {
    	$found = false;
    	foreach ($matchTeams as $matchTeam) {
    		if ($team['id'] == $matchTeam['MatchTeam']['team_id']) {
    			if ( ($matchTeam['MatchTeam']['status'] == InvitationStatus::REJECTED) || ($matchTeam['MatchTeam']['status'] == InvitationStatus::UNBLOCKED) ) {
    				$team['status'] = InvitationStatus::stringValue(InvitationStatus::NOT_INVITED);
    			} elseif ($matchTeam['MatchTeam']['status'] == InvitationStatus::ACCEPTED) {
    				$team['status'] = InvitationStatus::stringValue(InvitationStatus::CONFIRMED);    				
    			} else {
    				$team['status'] = InvitationStatus::stringValue($matchTeam['MatchTeam']['status']);    				
    			}
    			$found = true;
    		}
    	}
    	if ($found == false) {
    		$team['status'] = InvitationStatus::stringValue(InvitationStatus::NOT_INVITED);
    	}
    	if ($team['status'] != InvitationStatus::stringValue(InvitationStatus::BLOCKED)) {
    		array_push($response,$team);    		
    	}
    }
    return array('status' => 200, 'data' => array('teams' => $response));
	}

	private function __prepareTeamResponse($teamId) {
    $resposne = array();
    $team = $this->find('first', array(
      'conditions' => array(
        'Team.id' => $teamId
      ),
      'contain' => array('Location', 'TeamPlayer', 'TeamStaff','TeamPrivilege')
    ));
    if (!empty($team)) {
	    $response = array(
	      'id' => $teamId,
	      'name' => $team['Team']['name'],
	      'registration_date' => $team['Team']['registration_date'],
	      'team_tagline' => $team['Team']['team_tagline'],
	      'location_name' => $team['Location']['name']
	    );     
    }
    return $response;
  }

  private function _updateCache($teamId) {
  	$team = $this->showTeam($teamId);
		Cache::delete('show_team_' . $teamId);
		if ( ! empty($team['data']['Series'])) {
			foreach ($team['data']['Series'] as $series) {
				Cache::delete('show_series_' . $series['id']);
			}
		}
		if ( ! empty($team['data']['Match'])) {
			foreach ($team['data']['Match'] as $match) {
				Cache::delete('show_match_' . $match['id']);
			}
		}
  }

  public function getTeamsOwnedByUser($userId,$matchId,$numOfRecords) {
  	$userId = trim($userId);
  	$matchId = trim($matchId);
    $numOfRecords = trim($numOfRecords);
    if(empty($userId)) {
      return array('status' => 100, 'message' => 'getTeamsOwnedByUser : Invalid Input Arguments');
    }
    if (empty($numOfRecords)) {
      $numOfRecords = Limit::NUM_OF_USER_OWNED_TEAMS;
    }
    
    $options = array(
    	'conditions' => array(
    		'Team.owner_id' => $userId
    	),
    	'fields' => array('id','name','image_id','location_id','team_tagline'),
    	'contain' => array(
    		'Location' => array(
          'fields' => array('id' ,'name','city_id'),
          'City' => array(
            'fields' => array('id','name')
          )
        ),
    		'ProfileImage' => array(
    			'fields' => array('id','url')
    		)
    	)
    );
    if (!empty($matchId)) {
    	$options['contain']['MatchTeam'] = array(
    		'conditions' => array(
    			'MatchTeam.match_id' => $matchId
    		),
    		'fields' => array('status')
    	);
    }
    $teams = $this->find('all',$options);
    
    $teamsData = array();
    foreach ($teams as $key => $team) {
    	$teamsData[$key]['id'] = $team['Team']['id'];
    	$teamsData[$key]['name'] = $team['Team']['name'];
    	$teamsData[$key]['team_tagline'] = $team['Team']['team_tagline'];
    	if (!empty($team['ProfileImage'])) {
    		$teamsData[$key]['image'] = $team['ProfileImage']['url'];
    	} else $teamsData[$key]['image'] = null;
    	if (!empty($data['Location']['City'])) {
        $teamsData[$key]['location']['id'] = $team['Location']['City']['id'];
        $teamsData[$key]['location']['name'] = $team['Location']['City']['name'];
      } else {
          $teamsData[$key]['location']['id'] = null;
          $teamsData[$key]['location']['name'] = null;
      }
      if (!empty($team['MatchTeam'])) {
	      $matchTeam = $team['MatchTeam'][0];
	      if ( ($matchTeam['status'] == InvitationStatus::REJECTED) || ($matchTeam['status'] == InvitationStatus::UNBLOCKED) ) {
					$teamsData[$key]['status'] = InvitationStatus::stringValue(InvitationStatus::NOT_INVITED);
				} elseif ($matchTeam['status'] == InvitationStatus::ACCEPTED) {
					$teamsData[$key]['status'] = InvitationStatus::stringValue(InvitationStatus::CONFIRMED);    				
				} else {
					$teamsData[$key]['status'] = InvitationStatus::stringValue($matchTeam['status']);    				
				}     	
      } else {
      	$teamsData[$key]['status'] = InvitationStatus::stringValue(InvitationStatus::NOT_INVITED);
      }
    }
    $countOfUserTeams = $this->getCountOfUserTeams($userId);
    $data = array('total' => $countOfUserTeams, 'teams' => $teamsData);
    return array('status' => 200, 'data' => $data);
  }

  public function getCountOfUserTeams($userId) {
  	return $this->find('count',array(
  		'conditions' => array(
  			'Team.owner_id' => $userId
  		)
  	));
  }

  public function teamSearchPublic($filters) {
  	$responseData = array();
  	$options = array(
  		'conditions' => array(
  			'Team.is_public' => true
  		),
  		'fields' => array('id','name','nick','image_id','location_id','team_tagline'),
    	'contain' => array(
    		'Location' => array(
          'fields' => array('id' ,'name','city_id'),
          'City' => array(
            'fields' => array('id','name')
          )
        ),
    		'ProfileImage' => array(
    			'fields' => array('id','url')
    		)
    	),
    	'limit' => Limit::NUM_OF_TEAMS_IN_TEAM_SEARCH_PAGE,
    	'order' => 'Team.created ASC'
  	);
  	foreach ($filters as $key => $value) {
  		if (!empty($value)) {
	  		switch ($key) {
	  			case TeamSearchSubFilter::stringValue(TeamSearchSubFilter::TEXT):
	  				$options['conditions']['OR'] = array(
	  					'Team.name LIKE' => "%$value%",
	  					'Team.nick LIKE' => "%$value%"
	  				);
	  				break;
	  			case TeamSearchSubFilter::stringValue(TeamSearchSubFilter::LOCATION):
	  				$nearBylocations = $this->Location->getNearbyLocation($value['latitude'], $value['longitude'], Limit::TEAM_SEARCH_DISTANCE);
	  				$locationIds = array();
	  				if ($nearBylocations['status'] == 200) {
	  					if (!empty($nearBylocations['data'])) {
		  					$locations = $nearBylocations['data'];
		  					foreach ($locations as $key => $value) {
		  						$locationIds[$key] = $value['id'];
		  					}
		  					$options['conditions']['Team.location_id'] = $locationIds;
	  					} else {
	  						return array('status' => 200, 'data' => array('teams' => array()),'message' => 'teamSearchPublic : no teams for this location');
	  					}
	  				} else {
	  					return array('status' => $nearBylocations['status'] , 'message' => $nearBylocations['message']);
	  				}
	  				break;
	  		}  			
  		}
  	}
  	$teams = $this->find('all',$options);
  	foreach ($teams as $key => $team) {
    	$responseData[$key]['id'] = $team['Team']['id'];
    	$responseData[$key]['name'] = $team['Team']['name'];
    	$responseData[$key]['nick'] = $team['Team']['nick'];
    	$responseData[$key]['team_tagline'] = $team['Team']['team_tagline'];
    	if (!empty($team['ProfileImage'])) {
    		$responseData[$key]['image'] = $team['ProfileImage']['url'];
    	} else $responseData[$key]['image'] = null;
    	if (!empty($team['Location'])) {
				$responseData[$key]['location']['id'] = $team['Location']['id'];
				$responseData[$key]['location']['name'] = $team['Location']['name'];
				if (!empty($team['Location']['City'])) {
					$responseData[$key]['location']['city'] = $team['Location']['City']['name'];
				} else {
					$responseData[$key]['location']['city'] = null;
				}
			} else {
				$responseData[$key]['location']['id'] = null;
				$responseData[$key]['location']['name'] = null;
				$responseData[$key]['location']['city'] = null;
			}
    }
  	return array('status' => 200, 'data' => array('teams' => $responseData));
  }

  public function teamSearchForUser($userId,$topFilter,$subFilter) {
  	$userId = trim($userId);
  	if (!empty($userId)) {
  		if (!$this->_userExists($userId)) {
  			return array('status' => 103 , 'message' => 'teamSearchForUser : Invalid User ID');
  		}
  	}
  	$responseData = array();
  	if (empty($topFilter) && empty($subFilter)) {
  		return array('status' => 100 , 'message' => 'teamSearchForUser : Invalid Input Arguments');
  	}
  	switch ($topFilter) {
  		case TeamSearchTopFilter::ALL:
  			
  			break;
  		case TeamSearchTopFilter::FOLLOWED:
  			# code...
  			break;
  		case TeamSearchTopFilter::RECOMMENDED:
  			# code...
  			break;
  		case TeamSearchTopFilter::MY:
  			# code...
  			break;
  	}
  	return array('status' => 200, 'data' => array('teams' => $responseData));
  }

}

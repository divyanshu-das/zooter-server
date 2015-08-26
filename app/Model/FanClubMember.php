<?php
App::uses('AppModel', 'Model');
App::uses('InvitationStatus', 'Lib/Enum');
App::uses('Limit', 'Lib/Enum');
/**
 * FanClubMember Model
 *
 * @property FanClub $FanClub
 * @property User $User
 */
class FanClubMember extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'FanClub' => array(
			'className' => 'FanClub',
			'foreignKey' => 'fan_club_id',
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

	public function getUserFanClubs($userId,$numOfRecords,$filterLike) {
		$userId = trim($userId);
		$numOfRecords = trim($numOfRecords);
		if(empty($userId)) {
			return array('status' => 100, 'message' => 'getUserFanClubs : Invalid Input Arguments');
		}
		if (empty($numOfRecords)) {
			$numOfRecords = Limit::NUM_OF_USER_FANCLUBS;
		}
		if (!empty($filterLike)) {
			$fanClubsFromCache = Cache::read('fan_clubs_user_'.$userId);
			if (!empty($fanClubsFromCache)) {
				return $fanClubsFromCache;
			}
		}
		$fanClubsData = array();
		$options = array(
			'conditions' => array(
				'FanClubMember.user_id' => $userId,
				'FanClubMember.status' => InvitationStatus::CONFIRMED
			),
			'contain' => array(
				'FanClub' => array(
					'fields' => array('id','name','image_id','fan_club_members_count'),
					'ProfileImage' => array(
						'fields' => array('id','url')
					)
				)
			),
			'order' => 'FanClubMember.created DESC',
			'limit' => $numOfRecords
		);
		if (!empty($filterLike)) {
      $options['conditions']['FanClub.name LIKE'] = "%$filterLike%";
    }
		$fanClubs = $this->find('all',$options);
		foreach ($fanClubs as $key => $club) {
			$fanClubsData[$key]['id'] = $club['FanClubMember']['id'];
			$fanClubsData[$key]['fanclub']['id'] = $club['FanClub']['id'];
			$fanClubsData[$key]['fanclub']['name'] = $club['FanClub']['name'];
			if (!empty($club['FanClub']['ProfileImage'])) {
				$fanClubsData[$key]['fanclub']['image'] = $club['FanClub']['ProfileImage']['url'];
			} else {
					$fanClubsData[$key]['fanclub']['image'] = NULL;
			}			
			$fanClubsData[$key]['fanclub']['fan_club_followers_count'] = $club['FanClub']['fan_club_members_count'];
		}
		$countOfFanclubs = $this->getCountOfUserFanClubs($userId);
		$data = array('total' => $countOfFanclubs, 'fanclubs' => $fanClubsData);
		return array('status' => 200 , 'data' => $data);
	}

	public function getCountOfUserFanClubs($userId) {
		return $this->find('count',array(
			'conditions' => array(
				'FanClubMember.user_id' => $userId,
				'FanClubMember.status' => InvitationStatus::CONFIRMED
			)
		));
	}

}

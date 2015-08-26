<?php
App::uses('AppModel', 'Model');
App::uses('TeamPlayerHistoryStatus', 'Lib/Enum');
App::uses('InvitationStatus', 'Lib/Enum');
App::uses('Limit', 'Lib/Enum');
/**
 * TeamPlayerHistory Model
 *
 * @property Team $Team
 * @property User $User
 */
class TeamPlayerHistory extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'team_player_history';


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

	public function getUserTeamsHistory($userId,$numOfRecords) {
		if (empty($numOfRecords)) {
      $numOfRecords = Limit::NUM_OF_USER_TEAMS_HISTORY_RECORDS;
    }
		$options = array(
			'conditions' => array(
				'active_status' => TeamPlayerHistoryStatus::ACTIVE,
				'user_id' => $userId
			),
			'fields' => array('id','team_id','created','modified'),
			'contain' => array(
				'Team' => array(
					'fields' => array('id','name','image_id','location_id'),
					'ProfileImage' => array(
						'fields' => array('id','url')
					),
					'Location' => array(
						'fields' => array('id','name','city_id'),
						'City' => array(
							'fields' => array('id','name')
						)
					)
				)
			),
			'limit'=> $numOfRecords
		);
		$activeTeams = $this->find('all',$options);

		$teamsData = array();
		$index = 0;
		foreach ($activeTeams as $value) {
			if (!empty($value['Team']['ProfileImage'])) {
				$image = $value['Team']['ProfileImage']['url'];
			} else $image = NULL;
			$noOfMatches = $this->Team->MatchPlayer->getCountOfMatchesUserPlayedForTeam($userId, $value['Team']['id']);
			$teamsData[$index]['id'] = $value['Team']['id'];
			$teamsData[$index]['name'] = $value['Team']['name'];
			$teamsData[$index]['image_url'] = $image;
			if (!empty($value['Team']['Location'])) {
				$teamsData[$index]['location']['id'] = $value['Team']['Location']['id'];
				$teamsData[$index]['location']['name'] = $value['Team']['Location']['name'];
			} else {
				$teamsData[$index]['location']['id'] = null;
				$teamsData[$index]['location']['name'] = null;
			}
			if (!empty($value['Team']['Location']['City'])) {
				$teamsData[$index]['location']['city'] = $value['Team']['Location']['City']['name'];
			} else {
				$teamsData[$index]['location']['city'] = null;
			}
			$teamsData[$index]['no_of_matches'] = $noOfMatches;
			$teamsData[$index]['started_on'] = $value['TeamPlayerHistory']['created'];
			$teamsData[$index]['status'] = 'active';
			$index = $index + 1;
		}

		$countOfActiveTeams = count($activeTeams);
		if ($countOfActiveTeams < $numOfRecords) {
			$options['conditions']['active_status'] = TeamPlayerHistoryStatus::INACTIVE;
			$options['limit'] = $numOfRecords - $countOfActiveTeams;
			$inactiveTeams = $this->find('all',$options);

			foreach ($inactiveTeams as $value) {
				if (!empty($value['Team']['ProfileImage'])) {
					$image = $value['Team']['ProfileImage']['url'];
				} else $image = NULL;
				$noOfMatches = $this->Team->MatchPlayer->getCountOfMatchesUserPlayedForTeam($userId, $value['Team']['id']);
				$teamsData[$index]['id'] = $value['Team']['id'];
				$teamsData[$index]['name'] = $value['Team']['name'];
				$teamsData[$index]['image'] = $image;
				if (!empty($value['Team']['Location'])) {
					$teamsData[$index]['location']['id'] = $value['Team']['Location']['id'];
					$teamsData[$index]['location']['name'] = $value['Team']['Location']['name'];
				} else {
					$teamsData[$index]['location']['id'] = null;
					$teamsData[$index]['location']['name'] = null;
				}
				if (!empty($value['Team']['Location']['City'])) {
					$teamsData[$index]['location']['city'] = $value['Team']['Location']['City']['name'];
				} else {
					$teamsData[$index]['location']['city'] = null;
				}
				$teamsData[$index]['no_of_matches'] = $noOfMatches;
				$teamsData[$index]['started_on'] = $value['TeamPlayerHistory']['created'];
				$teamsData[$index]['ended_on'] = $value['TeamPlayerHistory']['modified'];
				$teamsData[$index]['status'] = 'inactive';
				$index = $index + 1;
			}
		}

		$totalTeams = $this->getUserTotalCountOfTeams($userId);
		return array('total' => $totalTeams, 'teams' => $teamsData);
	}

	public function getUserTotalCountOfTeams($userId) {
		return $this->find('count',array(
			'conditions' => array(
				'user_id' => $userId
			)
		));
	}

}

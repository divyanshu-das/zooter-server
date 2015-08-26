<?php
App::uses('AppModel', 'Model');
App::uses('Movie', 'Model');
App::uses('Music', 'Model');
App::uses('SportsPersonality', 'Model');
App::uses('Ground', 'Model');
App::uses('PopularTeam', 'Model');
App::uses('Team', 'Model');
App::uses('FavoriteType', 'Lib/Enum');
/**
 * UserFavorite Model
 *
 * @property User $User
 */
class UserFavorite extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function getUserProfileFavorites($userId) {
		
		$favoritesFromCache = Cache::read('favorites_user_'.$userId);
		if (!empty($favoritesFromCache)) {
			return $favoritesFromCache;
		}

		$dataArray = $this->find('all',array(
			'conditions' => array(
				'user_id' => $userId
			),
			'fields' => array('id','type','favorite_id')
		));

		$favoriteData = array();
		$sportsIndex = 0;
		$sportsGroundIndex = 0;
		$popularTeamIndex = 0;
		$otherTeamIndex = 0;
		$moviesIndex = 0;
		$musicIndex = 0;

		foreach ($dataArray as $data) {
			if (!empty($data['UserFavorite']['type'])) {
				switch ($data['UserFavorite']['type']) {
					case FavoriteType::SPORTS_PERSONALITY:
						$favoriteData['sports_personalities'][$sportsIndex] = $data['UserFavorite']['favorite_id'];
						$sportsIndex = $sportsIndex+1;
						break;
					case FavoriteType::SPORTS_GROUND:
						$favoriteData['sports_grounds'][$sportsGroundIndex] = $data['UserFavorite']['favorite_id'];
						$sportsGroundIndex = $sportsGroundIndex+1;
						break;
					case FavoriteType::POPULAR_TEAM:
						$favoriteData['popular_teams'][$popularTeamIndex] = $data['UserFavorite']['favorite_id'];
						$popularTeamIndex = $popularTeamIndex+1;
						break;
					case FavoriteType::OTHER_TEAM:
						$favoriteData['other_teams'][$otherTeamIndex] = $data['UserFavorite']['favorite_id'];
						$otherTeamIndex = $otherTeamIndex+1;
						break;
					case FavoriteType::MUSIC:
						$favoriteData['music'][$musicIndex] = $data['UserFavorite']['favorite_id'];
						$musicIndex = $musicIndex+1;
						break;
					case FavoriteType::MOVIES:
						$favoriteData['movies'][$moviesIndex] = $data['UserFavorite']['favorite_id'];
						$moviesIndex = $moviesIndex+1;
						break;
				}
			}
		}

		$favorites = array();

		$index = 0;
		if (count($favoriteData['sports_personalities']) > 0) {
			$SportsPersonality = new SportsPersonality();
			$sportsPersonalityData = $SportsPersonality->find('all',array(
				'conditions' => array(
					'id' => $favoriteData['sports_personalities']
				),
				'fields' => array('id','type','name','image')
			));
			foreach ($sportsPersonalityData as $personalityData) {
				$favorites['sports']['personalities'][$index]['id'] = $personalityData['SportsPersonality']['id'];
				$favorites['sports']['personalities'][$index]['type'] = $personalityData['SportsPersonality']['type'];
				$favorites['sports']['personalities'][$index]['name'] = $personalityData['SportsPersonality']['name'];
				$favorites['sports']['personalities'][$index]['image'] = $personalityData['SportsPersonality']['image'];
				$index = $index+1;
			}
		}

		$index = 0;
		if (count($favoriteData['sports_grounds']) > 0) {
			$Ground = new Ground();
			$sportsGroundData = $Ground->find('all',array(
				'conditions' => array(
					'id' => $favoriteData['sports_grounds']
				),
				'fields' => array('id','name','image')
			));
			foreach ($sportsGroundData as $groundData) {
				$favorites['sports']['grounds'][$index]['id'] = $groundData['Ground']['id'];
				$favorites['sports']['grounds'][$index]['name'] = $groundData['Ground']['name'];
				$favorites['sports']['grounds'][$index]['image'] = $groundData['Ground']['image'];
				$index = $index+1;
			}
		}

		$index = 0;
		if (count($favoriteData['popular_teams']) > 0) {
			$PopularTeam = new PopularTeam();
			$popularTeamData = $PopularTeam->find('all',array(
				'conditions' => array(
					'id' => $favoriteData['popular_teams']
				),
				'fields' => array('id','name','image')
			));
			foreach ($popularTeamData as $teamData) {
				$favorites['sports']['popular_teams'][$index]['id'] = $teamData['PopularTeam']['id'];
				$favorites['sports']['popular_teams'][$index]['name'] = $teamData['PopularTeam']['name'];
				$favorites['sports']['popular_teams'][$index]['image'] = $teamData['PopularTeam']['image'];
				$index = $index+1;
			}
		}

		$index = 0;
		if (count($favoriteData['other_teams']) > 0) {
			$Team = new Team();
			$otherTeamData = $Team->find('all',array(
				'conditions' => array(
					'id' => $favoriteData['other_teams']
				),
				'fields' => array('id','name','image')
			));
			foreach ($otherTeamData as $teamData) {
				$favorites['sports']['other_teams'][$index]['id'] = $teamData['Team']['id'];
				$favorites['sports']['other_teams'][$index]['name'] = $teamData['Team']['name'];
				$favorites['sports']['other_teams'][$index]['image'] = $teamData['Team']['image'];
				$index = $index+1;
			}
		}

		$index = 0;
		if (count($favoriteData['music']) > 0) {
			$Music = new Music();
			$musicData = $Music->find('all',array(
				'conditions' => array(
					'id' => $favoriteData['music']
				),
				'fields' => array('id','type','name','image')
			));
			foreach ($musicData as $data) {
				$favorites['sports']['music'][$index]['id'] = $data['Music']['id'];
				$favorites['sports']['music'][$index]['type'] = $data['Music']['type'];
				$favorites['sports']['music'][$index]['name'] = $data['Music']['name'];
				$favorites['sports']['music'][$index]['image'] = $data['Music']['image'];
				$index = $index+1;
			}
		}

		$index = 0;
		if (count($favoriteData['Movies']) > 0) {
			$Movies = new Movies();
			$moviesData = $Movies->find('all',array(
				'conditions' => array(
					'id' => $favoriteData['Movies']
				),
				'fields' => array('id','type','name','image')
			));
			foreach ($moviesData as $data) {
				$favorites['sports']['Movies'][$index]['id'] = $data['Movies']['id'];
				$favorites['sports']['Movies'][$index]['type'] = $data['Movies']['type'];
				$favorites['sports']['Movies'][$index]['name'] = $data['Movies']['name'];
				$favorites['sports']['Movies'][$index]['image'] = $data['Movies']['image'];
				$index = $index+1;
			}
		}

		return $favorites;
	}
	
}

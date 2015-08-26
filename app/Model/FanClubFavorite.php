<?php
App::uses('AppModel', 'Model');
App::uses('Movie', 'Model');
App::uses('Music', 'Model');
App::uses('Hobby', 'Model');
App::uses('Dish', 'Model');
App::uses('Singer', 'Model');
App::uses('HolidayDestination', 'Model');
App::uses('Shot', 'Model');
App::uses('FavoriteType','Lib/Enum');
/**
 * FanClubFavorite Model
 *
 * @property FanClub $FanClub
 */
class FanClubFavorite extends AppModel {

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
		)
	);

	public function updateFanClub($fanClubId, $favorite) {
		$ds = $this->getDataSource();
		$ds->begin();

		$favoriteMovieData = $this->__updateFavoriteMovies($favorite, $fanClubId);
		$favoriteMusicData = $this->__updateFavoriteMusic($favorite, $fanClubId);
		$favoriteHobbyData = $this->__updateFavoriteHobbies($favorite, $fanClubId);
		$favoriteDishData = $this->__updateFavoriteDishes($favorite, $fanClubId);
		$favoriteSingerData = $this->__updateFavoriteSingers($favorite, $fanClubId);
		$favoriteHolidayDestinationData = $this->__updateFavoriteHolidateDestinations($favorite, $fanClubId);
		$favoriteShotData = $this->__updateFavoriteShots($favorite, $fanClubId);

		$favorites = array_merge($favoriteMovieData, $favoriteMusicData, $favoriteHobbyData, $favoriteDishData, $favoriteSingerData, $favoriteHolidayDestinationData, $favoriteShotData);
		$this->create();
		if ($this->__skipOrSave($favorites)) {
			$ds->commit();
		} else {
			$ds->rollback();
		}
	}
	private function __skipOrSave($favorites) {
		foreach ($favorites as $favorite) {
			$existing = array();
			$existing = $this->find('first', array(
				'conditions' => array(
					'fan_club_id' => $favorite['fan_club_id'],
					'type' => $favorite['type'],
					'favorite_id' => $favorite['favorite_id']
				)
			));
			if (count($existing) == 0) {
				$this->create();
				$this->save($favorite);
			}
		}
		return true;
	}

	private function __updateFavoriteMovies($favorite, $fanClubId) {
		$favoriteMovieData = array();
		$Movie = new Movie();
		if ( ! empty($favorite['movies'])) {
			foreach ($favorite['movies'] as $id => $movie) {
				$movieData = array();
				if ( ! empty($movie['id'])) { 
					$movieData['Movie']['id'] = $movie['id'];
				}
				$movieData['Movie']['name'] = $movie['name'];
				$movieDetails = $Movie->findByName($movie['name']);
				if ( ! empty($movieDetails)) {
					$movieData['Movie']['id'] = $movieDetails['Movie']['id'];
				}
				$Movie->create();
				if ($Movie->save($movieData)) {
					$movieId = $Movie->getInsertID();
					if ( ! empty($movieId)) {
						$favoriteMovieData[$id]['favorite_id'] = $movieId;
					} else {
						$favoriteMovieData[$id]['favorite_id'] = $movieDetails['Movie']['id'];
					}
					$favoriteMovieData[$id]['type'] = FavoriteType::MOVIE;
					$favoriteMovieData[$id]['fan_club_id'] = $fanClubId;
				}
			}
		}
		return $favoriteMovieData;
	}

	private function __updateFavoriteMusic($favorite, $fanClubId) {
		$favoriteMusicData = array();
		$Music = new Music();
		if ( ! empty($favorite['music'])) {
			foreach ($favorite['music'] as $id => $music) {
				$musicData = array();
				if ( ! empty($music['id'])) { 
					$musicData['Music']['id'] = $music['id'];
				}
				$musicData['Music']['name'] = $music['name'];
				$musicDetails = $Music->findByName($music['name']);
				if ( ! empty($musicDetails)) {
					$musicData['Music']['id'] = $musicDetails['Music']['id'];
				}
				$Music->create();
				if ($Music->save($musicData)) {
					$musicId = $Music->getInsertID();
					if ( ! empty($musicId)) {
						$favoriteMusicData[$id]['favorite_id'] = $musicId;
					} else {
						$favoriteMusicData[$id]['favorite_id'] = $musicDetails['Music']['id'];
					}
					$favoriteMusicData[$id]['type'] = FavoriteType::MUSIC;
					$favoriteMusicData[$id]['fan_club_id'] = $fanClubId;
				}
			}
		}
		return $favoriteMusicData;
	}

	private function __updateFavoriteHobbies($favorite, $fanClubId) {
		$favoriteHobbyData = array();
		$Hobby = new Hobby();
		if ( ! empty($favorite['hobbies'])) {
			foreach ($favorite['hobbies'] as $id => $hobby) {
				$hobbyData = array();
				if ( ! empty($hobby['id'])) { 
					$hobbyData['Hobby']['id'] = $hobby['id'];
				}
				$hobbyData['Hobby']['name'] = $hobby['name'];
				$hobbyDetails = $Hobby->findByName($hobby['name']);
				if ( ! empty($hobbyDetails)) {
					$hobbyData['Hobby']['id'] = $hobbyDetails['Hobby']['id'];
				}
				$Hobby->create();
				if ($Hobby->save($hobbyData)) {
					$hobbyId = $Hobby->getInsertID();
					if ( ! empty($hobbyId)) {
						$favoriteHobbyData[$id]['favorite_id'] = $hobbyId;
					} else {
						$favoriteHobbyData[$id]['favorite_id'] = $hobbyDetails['Hobby']['id'];
					}
					$favoriteHobbyData[$id]['type'] = FavoriteType::HOBBY;
					$favoriteHobbyData[$id]['fan_club_id'] = $fanClubId;
				}
			}
		}
		return $favoriteHobbyData;
	}

	private function __updateFavoriteDishes($favorite, $fanClubId) {
		$favoriteDishData = array();
		$Dish = new Dish();
		if ( ! empty($favorite['dishes'])) {
			foreach ($favorite['dishes'] as $id => $dish) {
				$dishData = array();
				if ( ! empty($dish['id'])) { 
					$dishData['Dish']['id'] = $dish['id'];
				}
				$dishData['Dish']['name'] = $dish['name'];
				$dishDetails = $Dish->findByName($dish['name']);
				if ( ! empty($dishDetails)) {
					$dishData['Dish']['id'] = $dishDetails['Dish']['id'];
				}
				$Dish->create();
				if ($Dish->save($dishData)) {
					$dishId = $Dish->getInsertID();
					if ( ! empty($dishId)) {
						$favoriteDishData[$id]['favorite_id'] = $dishId;
					} else {
						$favoriteDishData[$id]['favorite_id'] = $dishDetails['Dish']['id'];
					}
					$favoriteDishData[$id]['type'] = FavoriteType::DISH;
					$favoriteDishData[$id]['fan_club_id'] = $fanClubId;
				}
			}
		}
		return $favoriteDishData;
	}

	private function __updateFavoriteSingers($favorite, $fanClubId) {
		$favoriteSingerData = array();
		$Singer = new Singer();
		if ( ! empty($favorite['singers'])) {
			foreach ($favorite['singers'] as $id => $singer) {
				$singerData = array();
				if ( ! empty($singer['id'])) { 
					$singerData['Singer']['id'] = $singer['id'];
				}
				$singerData['Singer']['name'] = $singer['name'];
				$singerDetails = $Singer->findByName($singer['name']);
				if ( ! empty($singerDetails)) {
					$singerData['Singer']['id'] = $singerDetails['Singer']['id'];
				}
				$Singer->create();
				if ($Singer->save($singerData)) {
					$singerId = $Singer->getInsertID();
					if ( ! empty($singerId)) {
						$favoriteSingerData[$id]['favorite_id'] = $singerId;
					} else {
						$favoriteSingerData[$id]['favorite_id'] = $singerDetails['Singer']['id'];
					}
					$favoriteSingerData[$id]['type'] = FavoriteType::SINGER;
					$favoriteSingerData[$id]['fan_club_id'] = $fanClubId;
				}
			}
		}
		return $favoriteSingerData;
	}

	private function __updateFavoriteHolidateDestinations($favorite, $fanClubId) {
		$favoriteHolidayDestinationData = array();
		$HolidayDestination = new HolidayDestination();
		if ( ! empty($favorite['holiday_destinations'])) {
			foreach ($favorite['holiday_destinations'] as $id => $holidayDestination) {
				$holidayDestinationData = array();
				if ( ! empty($holidayDestination['id'])) { 
					$holidayDestinationData['HolidayDestination']['id'] = $holidayDestination['id'];
				}
				$holidayDestinationData['HolidayDestination']['name'] = $holidayDestination['name'];
				$holidayDestinationDetails = $HolidayDestination->findByName($holidayDestination['name']);
				if ( ! empty($holidayDestinationDetails)) {
					$holidayDestinationData['HolidayDestination']['id'] = $holidayDestinationDetails['HolidayDestination']['id'];
				}
				$HolidayDestination->create();
				if ($HolidayDestination->save($holidayDestinationData)) {
					$holidayDestinationId = $HolidayDestination->getInsertID();
					if ( ! empty($holidayDestinationId)) {
						$favoriteHolidayDestinationData[$id]['favorite_id'] = $holidayDestinationId;
					} else {
						$favoriteHolidayDestinationData[$id]['favorite_id'] = $holidayDestinationDetails['HolidayDestination']['id'];
					}
					$favoriteHolidayDestinationData[$id]['type'] = FavoriteType::HOLIDAY_DESTINATION;
					$favoriteHolidayDestinationData[$id]['fan_club_id'] = $fanClubId;
				}
			}
		}
		return $favoriteHolidayDestinationData;
	}

	private function __updateFavoriteShots($favorite, $fanClubId) {
		$favoriteShotData = array();
		$Shot = new Shot();
		if ( ! empty($favorite['shots'])) {
			foreach ($favorite['shots'] as $id => $shot) {
				$shotData = array();
				if ( ! empty($shot['id'])) { 
					$shotData['Shot']['id'] = $shot['id'];
				}
				$shotData['Shot']['name'] = $shot['name'];
				$shotDetails = $Shot->findByName($shot['name']);
				if ( ! empty($shotDetails)) {
					$shotData['Shot']['id'] = $shotDetails['Shot']['id'];
				}
				$Shot->create();
				if ($Shot->save($shotData)) {
					$shotId = $Shot->getInsertID();
					if ( ! empty($shotId)) {
						$favoriteShotData[$id]['favorite_id'] = $shotId;
					} else {
						$favoriteShotData[$id]['favorite_id'] = $shotDetails['Shot']['id'];
					}
					$favoriteShotData[$id]['type'] = FavoriteType::SHOT;
					$favoriteShotData[$id]['fan_club_id'] = $fanClubId;
				}
			}
		}
		return $favoriteShotData;
	}
}

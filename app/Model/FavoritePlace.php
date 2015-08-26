<?php
App::uses('AppModel', 'Model');
/**
 * FavoritePlace Model
 *
 * @property User $User
 * @property Place $Place
 */
class FavoritePlace extends AppModel {


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
		),
		'Place' => array(
			'className' => 'Place',
			'foreignKey' => 'place_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	public function getFavoritePlaces($profileUserId) {
		$favorites = $this->find('all', array(
			'conditions' => array(
				'FavoritePlace.user_id' => $profileUserId,
			),
			'fields' => array(
				'id'
			),
			'contain' => array(
				'Place' => array(
					'fields' => array('Place.id', 'Place.name'),
					'PlacesImage' => array(
						'fields' => array('PlacesImage.id'),
						'Image' => array(
							'fields' => array('Image.url', 'Image.caption')
						)
					),
					'PlaceRating' => array(
						'fields' => array('id', 'PlaceRating.rating')
					)
				)
			)
		));
		$data = array();
		if (!empty($favorites)) {
			$count = 0;
			foreach ($favorites as $id => $favorite) {
				$data[$count]['id'] = $favorite['Place']['id'];
				$data[$count]['name'] = $favorite['Place']['name'];
				$data[$count]['image_url'] = !empty($favorite['Place']['PlacesImage'][0]['Image']['url']) ? $favorite['Place']['PlacesImage'][0]['Image']['url'] : null;
				$data[$count]['rating'] = $this->calculatePlaceRating($favorite['Place']['PlaceRating']);
				$count++;
			}
		}
		return $data;
	}
	public function calculatePlaceRating($ratings) {
		$ratingString = 'Not Enough Ratings To Show';
		if (!empty($ratings)) {
			$totalRating = 0;
			foreach ($ratings as $id => $rating) {
				$totalRating += $rating['rating'];
			}
			$raterCount = count($ratings);
			$ratingString = $totalRating / $raterCount . ' / 10';
		}
		return $ratingString;
	}

	public function saveFavorite($data) {
		$this->create();
		return $this->save($data);
	}

	public function findFavoritePlaces($userId) {
		$favoritePlaces = $this->find('all', array(
			'conditions' => array(
				'FavoritePlace.user_id' => $userId
			),
			'contain' => array(
				'Place' => array(
					'Location',
					'PlacesImage' => array(
						'limit' => 1,
						'Image'
					)
				)
			)
		));
		return $favoritePlaces;
	}

	public function deleteFavorite($favoritePlace) {
		return $this->delete($favoritePlace['FavoritePlace']['id']);
	}
}

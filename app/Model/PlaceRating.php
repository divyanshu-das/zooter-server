<?php
App::uses('AppModel', 'Model');
/**
 * PlaceRating Model
 *
 * @property Place $Place
 * @property User $User
 */
class PlaceRating extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Place' => array(
			'className' => 'Place',
			'foreignKey' => 'place_id',
			'counterCache' => true,
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

	public function saveRating($rating) {
		$alreadyRated = $this->find('first', array(
			'conditions' => array(
				'PlaceRating.place_id' => $rating['place_id'],
				'PlaceRating.user_id' => $rating['user_id']
			)
		));
		if ($alreadyRated) {
			$this->id = $alreadyRated['PlaceRating']['id'];
			return $this->saveField('rating', $rating['rating']);
		} else {
			$this->create();
			return $this->save($rating);
		}
	}
	public function addRating($placeId, $userId, $rating) {
		$user = $this->User->findById($userId);
		if (!empty($user)) {
			$place = $this->Place->findById($placeId);
			if (!empty($place)) {
				$data = array(
					'place_id' => $placeId,
					'user_id' => $userId,
					'rating' => $rating
				);
				if ($this->saveRating($data)) {
					$ratings = $this->find('all', array(
						'conditions' => array(
							'PlaceRating.place_id' => $placeId
						),
						'fields' => array('ROUND(AVG(rating), 1) as average_rating', 'count(rating) as rating_count')
					));
					$averageRating = !empty($ratings[0][0]['average_rating']) ? $ratings[0][0]['average_rating'] : null;
					$responseData['rating'] = !empty($averageRating) ? $averageRating . '/10' : 'Not enough rating';
					$responseData['rating_count'] = !empty($ratings[0][0]['rating_count']) ? $ratings[0][0]['rating_count'] : 0;
					$response = array('status' => 200, 'message' => 'success', 'data' => $responseData);
				} else {
					$response = array('status' => 400, 'message' => 'Could not save rating');	
				}
			} else {
				$response = array('status' => 400, 'message' => 'Place does not exist');	
			}
		} else {
			$response = array('status' => 400, 'message' => 'User does not exist');
		}
		return $response;
	}
}
